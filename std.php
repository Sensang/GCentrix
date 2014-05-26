<?php
// REMINDER
// "SELECT setval('"public"."Table_ID_seq"',(SELECT max("ID") + 1 FROM "Table"));"
function AddDir($Filepath, $RemoveDefault, $Directories, $OnlyExt) {
    if ($Filepath[strlen($Filepath) - 1] != "/")
    {
        $Filepath .= "/";
    }
    $OnlyExt = strtolower($OnlyExt);
    $Files = scandir($Filepath);
    if ($RemoveDefault) {
        unset($Files[0]);
        unset($Files[1]);
    }
    if ((!$Directories) ||($OnlyExt != "")) {
        foreach ($Files as $Key=>$File) {
            if (!((($Directories) &&(is_dir($Filepath . $File)))
               ||(($OnlyExt == "") ||(strtolower(substr($File, strlen($File) - strlen($OnlyExt))) == $OnlyExt)))) {
                unset($Files[$Key]);
            }
        }
    }
    return $Files;
}
function GetDir($Filepath, $Require, $Directories, $OnlyExt) {
    if ($Filepath[strlen($Filepath) - 1] != "/")
    {
        $Filepath .= "/";
    }
    $Files = ReadDir($Filepath, true, $Directories, $OnlyExt);
    foreach ($Files as $File) {
        if (($Directories) &&(is_dir($Filepath . $File))) {
            GetDir($Filepath . $File, $Require, $Directories, $OnlyExt);
        } else if ($Require) {
            require_once $Filepath . $File;
        } else {
            include $Filepath . $File;
        }
    }
}
function CreateCacheForTable() {
    
}
class Database {
    public static $Name = "";
    public static $Database = "";
    public static $Host = "";
    public static $Port = 0;
    public static $User = "";
    public static $Password = "";
    public static $Connection;
    public static $Field;
    public static $TableRelation;
    public static $SystemTables = array();
    public static $Tables = array();
    public static $CachePath = "";
    public static $StandardPath = "";
    public static $DefaultPath = "";
    public static $CustomPath = "";
    public static $TableIDOffset = 0;
    public static $Initialized = FALSE;
    public static function Initialize() {
        self::$Connection = pg_connect("host=" . self::$Host . " port=" . self::$Port . " dbname=" . self::$Name . " user='" . self::$User . "' password='" . self::$Password . "'", self::$Connection);
        self::InitSystemTableByCache(1, "Table");
        foreach (self::$SystemTables["Table"] as $Key=>$Table) {
            if (($Table["ID"] != 1) &&($Table["System Table"] == TRUE)) {
                self::InitSystemTableByCache($Key, $Table["Name"]);
            }
        }
        
        foreach (self::$SystemTables["Table"] as $Key=>$Table) {
            self::$Tables[$Table["Name"]] = $Key;
        }
        self::$Field = new Table("Field");
        $Fields = self::PGFetchAllWithFormat("SELECT * FROM \"Field\" WHERE \"Table\"='" . (self::$TableIDOffset + self::$Tables["Field"]) . "' ORDER BY \"ID\"");
        foreach ($Fields as $Field) {
            self::$Field->Meta->Fields[$Field["Name"]] = new Field($Field);
        }
        $TableRelations = self::PGFetchAllWithFormat(
            "SELECT \"Table\".\"Name\"  AS \"From Table\", " .
                   "\"Field\".\"Name\"  AS \"From Field\", " .
                   "\"Table2\".\"Name\"  AS \"To Table\", " .
                   "\"Field2\".\"Name\" AS \"To Field\" " .
            "FROM   \"Table Relation\" " .
                   "LEFT JOIN \"Table\" " .
                          "ON \"Table\".\"ID\" = \"Table Relation\".\"From Table\" " .
                   "LEFT JOIN \"Field\" " .
                          "ON \"Field\".\"ID\" = \"Table Relation\".\"From Field\" " .
                   "LEFT JOIN \"Table\" AS \"Table2\"" .
                          "ON \"Table2\".\"ID\" = \"Table Relation\".\"To Table\" " .
                   "LEFT JOIN \"Field\" AS \"Field2\" " .
                          "ON \"Field2\".\"ID\" = \"Table Relation\".\"To Field\" " .
            "WHERE  \"Table Relation\".\"From Table\" = '" . (self::$TableIDOffset + self::$Tables["Field"]) . "' " .
            "ORDER BY \"Table Relation\".\"ID\"");
        foreach ($TableRelations as $TableRelation) {
            self::$Field->Meta->TableRelations[$TableRelation["From Field"]] = new TableRelation($TableRelation);
        }
        
        self::$TableRelation = new Table("Table Relation");
        $Fields = self::PGFetchAllWithFormat("SELECT * FROM \"Field\" WHERE \"Table\"='" . (self::$TableIDOffset + self::$Tables["Table Relation"]) . "'");
        foreach ($Fields as $Field) {
            self::$TableRelation->Meta->Fields[$Field["Name"]] = new Field($Field);
        }
        $TableRelations = self::PGFetchAllWithFormat(
            "SELECT \"Table\".\"Name\"  AS \"From Table\", " .
                   "\"Field\".\"Name\"  AS \"From Field\", " .
                   "\"Table2\".\"Name\"  AS \"To Table\", " .
                   "\"Field2\".\"Name\" AS \"To Field\" " .
            "FROM   \"Table Relation\" " .
                   "LEFT JOIN \"Table\" " .
                          "ON \"Table\".\"ID\" = \"Table Relation\".\"From Table\" " .
                   "LEFT JOIN \"Field\" " .
                          "ON \"Field\".\"ID\" = \"Table Relation\".\"From Field\" " .
                   "LEFT JOIN \"Table\" AS \"Table2\"" .
                          "ON \"Table2\".\"ID\" = \"Table Relation\".\"To Table\" " .
                   "LEFT JOIN \"Field\" AS \"Field2\" " .
                          "ON \"Field2\".\"ID\" = \"Table Relation\".\"To Field\" " .
            "WHERE  \"Table Relation\".\"From Table\" = '" . (self::$TableIDOffset + self::$Tables["Table Relation"]) . "' " .
            "ORDER BY \"Table Relation\".\"ID\"");
        foreach ($TableRelations as $TableRelation) {
            self::$TableRelation->Meta->TableRelations[$TableRelation["From Field"]] = new TableRelation($TableRelation);
        }
        self::$Initialized = TRUE;
    }
    public static function ExtractID($ID) {
        return $ID % self::$TableIDOffset;
    }
    public static function ExtractTableID($ID) {
        return ($ID - self::ExtractID($ID)) / self::$TableIDOffset + self::$TableIDOffset;
    }
    public static function FormatValueByType(&$Value, $Type) {
        switch ($Type) {
            case "int8":
                $Value =(float) $Value;
                break;
            case "bpchar":
                $Value = trim($Value);
                break;
            case "bool":
                if ($Value == "t") {
                    $Value = TRUE;
                } else {
                    $Value = FALSE;
                }
                break;
            default:
                $Value = trim($Value);
                break;
        }
    }
    public static function PGFetchAllWithFormat($Query) {
        $Result = pg_query(self::$Connection, $Query);
        $Tables = pg_fetch_all($Result);
        if ($Tables == FALSE) {
            return FALSE;
        } else {
            $Fields = array();
            $Counter = 0;
            foreach ($Tables as $Key => $Data) {
                foreach ($Data as $SubKey => $SubData) {
                    if (!isset($Fields[$SubKey])) {
                        $Fields[$SubKey] = pg_field_type($Result, $Counter);
                        $Counter++;
                    }
                    self::FormatValueByType($Tables[$Key][$SubKey], $Fields[$SubKey]);
                }
            }
        }
        return $Tables;
    }
    public static function PGFetchAllWithFieldType($Query, $Fields) {
        $Tables = pg_fetch_all(pg_query(self::$Connection, $Query));
        if ($Tables == FALSE) {
            return FALSE;
        } else {
            foreach ($Tables as $Key => $Data) {
                foreach ($Data as $SubKey => $SubData) {
                    self::FormatValueByType($Tables[$Key][$SubKey], $Fields[$SubKey]->Type);
                }
            }
        }
        return $Tables;
    }
    private function InitSystemTableByCache($ID, $Name) {
        include self::$CachePath . "/tabledata/$Name.php";
        if (!isset(self::$SystemTables[$Name])) {
            $File = fopen(self::$CachePath . "tabledata/$Name.php", "w");
            $Tables = self::PGFetchAllWithFormat("SELECT * FROM \"$Name\" ORDER BY \"ID\"");
            if ($Tables != FALSE) {
                $CacheText = "<?php\r\n";
                foreach ($Tables as $Data) {
                    $CacheText .= 'Database::$SystemTables["' . $Name .'"][' .($Data["ID"] - $ID * Database::$TableIDOffset) . '] = [';
                    foreach ($Data as $SubKey => $SubData) {
                        if ($SubKey != "ID") {
                            $CacheText .= ", ";
                        }
                        $CacheText .= "'$SubKey' => ";
                        if (is_string($SubData)) {
                            $CacheText .= "'" . trim($SubData) . "'";
                        } else if (is_bool ($SubData)) {
                            if ($SubData) {
                                $CacheText .= "TRUE";
                            } else {
                                $CacheText .= "FALSE";                                
                            }
                        } else {
                            $CacheText .= $SubData;
                        }
                    }
                    $CacheText .= "];\r\n";
                }
                fwrite($File, $CacheText);
                fclose($File);
                require_once self::$CachePath . "tabledata/$Name.php";
            }
        }
    }
    public static function PGQuery($Query) {
        return pg_query(self::$Connection, $Query);
    }
}
class Debug {
    public static function OutputVar($Name, $Var) {
        echo "$Name: $Var</br>";
    }
    public static function TestValue($Name, $Value, $TestValue) {
        if ($Value != $TestValue) {
            echo "Fehler: $Name ist $Value, $TestValue erwartet</br>";
            return FALSE;
        }
        return true;
    }
}
class Table {
    public $Meta;
    public function __construct($Name) {
        $this->Meta = new Meta();
        $this->Meta->ID = Database::$Tables[$Name];
        $this->Meta->Name = $Name;
        $this->Meta->Filters = array();
        $this->Meta->Fields = array();
        $this->Meta->TableRelations = array();
        $this->Meta->Data = array();
        $this->Meta->Pointer = 0;
        $this->Meta->Queried = FALSE;
        if (Database::$Initialized) {
            $this->Initialization();
        }
    }
    public function Initialization() {
        Database::$Field->SetValueFilter("Table", $this->Meta->ID + Database::$TableIDOffset);
        Database::$Field->First();
        do {
            $this->Meta->Fields[Database::$Field->Name] = new Field(["ID"=>Database::$Field->ID, "Name"=>Database::$Field->Name, "Field Type"=>Database::$Field->{"Field Type"}]);
        } while (Database::$Field->Next());
                
        Database::$TableRelation->setValueFilter("From Table", $this->Meta->ID + Database::$TableIDOffset);
        if (Database::$TableRelation->First()) {
            do {
                if (Database::$TableRelation->{"To Field"} != "") {
                    $this->Meta->TableRelations[Database::$TableRelation->{"From Field"}] = 
                        new TableRelation([ "From Table" => Database::$TableRelation->{"From Table"}, 
                                            "From Field" => Database::$TableRelation->{"From Field"}, 
                                            "To Table" => Database::$TableRelation->{"To Table"}, 
                                            "To Field" => Database::$TableRelation->{"To Field"}]);
                } else {
                    $this->Meta->TableRelations[Database::$TableRelation->{"From Field"}] = 
                        new TableRelation([ "From Table" => Database::$TableRelation->{"From Table"}
                                          , "From Field" => Database::$TableRelation->{"From Field"}
                                          , "To Table"   => Database::$TableRelation->{"To Table"}]);
                }
            } while (Database::$Field->Next());
        }
    }
    public function Copy($Table) {
        foreach ($this->Meta->Fields as $Field) {
            $this->Meta->{$Field->Name} = $Table->{$Field->Name};
        }
    }
    public function ToArray() {
        $Array = array();
        foreach ($this->Meta->Fields as $Field) {
            if (isset($this->{$Field->Name})) {
                $Array[$Field->Name] = $this->{$Field->Name};
            } else {
                $Array[$Field->Name] = "";
            }
        }
        return $Array;
    }
    public function PrintTable() {
        $this->First();
        echo "<table><tr>";
        foreach ($this->Meta->Fields as $Field) {
            echo "<th>$Field->Name</th>";
        }
        echo "</tr>";
        do {
            echo "<tr>";
            foreach ($this->Meta->Fields as $Field) {
                echo "<td>" . $this->{$Field->Name} . "</td>";
            }
            echo "</tr>";
        } while ($this->Next());
        echo "</table>";
    }
    public function SetFilter($Field, $Filter) {
        $this->Meta->Filters[$Field] = $Filter;
    }
    public function SetValueFilter($Field, $Value) {
        $this->SetFilter($Field, [1, $Value]);
    }
    public function SetRangeFilter($Field, $Value, $Value2) {
        $this->SetFilter($Field, [2, $Value, $Value2]);
    }
    public function SetStringFilter($Field, $Value) {
        $this->SetFilter($Field, [3, $Value]);
    }
    public function ResetFilter($Field) {
        if (isset($this->Meta->Filters[$Field])) {
            unset($this->Meta->Filters[$Field]);
            $this->Meta->Queried = FALSE;
        }
    }
    public function Reset() {
        unset($this->Meta->Filters);
        $this->Meta->Filters = array();
        $this->Meta->Queried = FALSE;
    }
    public function Last() {
        if (!$this->Meta->Queried) {
            if (!$this->Query()) {
                return FALSE;
            }
        }
        return $this->Step(count($this->Meta->Data));
    }
    public function Next() {
        return $this->Step(1);
    }
    public function First() {
        if (!$this->Meta->Queried) {
            if (!$this->Query()) {
                return FALSE;
            }
        }
        return $this->Step(-$this->Meta->Pointer);
    }
    public function Step($Number) {
        if (isset($this->Meta->Data[$this->Meta->Pointer + $Number])) {
            $this->SetPointer($this->Meta->Pointer + $Number);
            return TRUE;
        }
        return FALSE;
    }
    public function SetPointer ($Pointer) {
        $this->Meta->Pointer = $Pointer;
        foreach ($this->Meta->Fields as $Field) {
            $this->{$Field->Name} = $this->Meta->Data[$Pointer][$Field->Name];
        }
    }
    public function Query() {
        $FieldString = "";
        $FilterString = "";
        $JoinString = "";
        $Counter = 0;
        foreach ($this->Meta->Fields as $Field) {
            if ($FieldString != "") {
                $FieldString .= ", ";
            }
            if (isset($this->Meta->TableRelations[$Field->Name])) {
                $Counter++;
                if ($Field->{"Type"} == 5) {
                    $FieldString .= "\"" . $this->Meta->TableRelations[$Field->Name]->{"To Table"} . "$Counter\".\"" . $this->Meta->TableRelations[$Field->Name]->{"To Field"}  . "\" AS \"$Field->Name\"";
                    $FieldString .= ", (SELECT "
                                    . "\"Field TypeAA" . $Counter . "\".\"Name\" AS \"To Field Type\" FROM \"Field\" AS \"FieldAA" . $Counter . "\""
                                    . "LEFT JOIN \"Field Type\" AS \"Field TypeAA" . $Counter . "\""
                                    . "ON \"Field TypeAA" . $Counter . "\".\"ID\"=\"FieldAA" . $Counter . "\".\"Field Type\" LIMIT 1)";
                    $JoinString .= " LEFT JOIN "
                                . "\"" . $this->Meta->TableRelations[$Field->Name]->{"To Table"} . "\" AS \"" . $this->Meta->TableRelations[$Field->Name]->{"To Table"} . "$Counter\" "
                                . "ON \"" . $this->Meta->TableRelations[$Field->Name]->{"To Table"} . "$Counter\".\"ID\" = \"{$this->Meta->Name}\".\"$Field->Name\"";
                } else if ($Field->{"Type"} == 16) {
                    $FieldString .= "\"" . $this->Meta->TableRelations[$Field->Name]->{"To Table"} . "$Counter\".\"Caption\" AS \"$Field->Name\"";
                    $JoinString .= " LEFT JOIN "
                                . "\"" . $this->Meta->TableRelations[$Field->Name]->{"To Table"} . "\" AS \"" . $this->Meta->TableRelations[$Field->Name]->{"To Table"} . "$Counter\" "
                                . "ON \"" . $this->Meta->TableRelations[$Field->Name]->{"To Table"} . "$Counter\".\"ID\" = \"{$this->Meta->Name}\".\"Caption\"";
                }
            } else {
                $FieldString .= "\"{$this->Meta->Name}\".\"$Field->Name\"";
            }
        }
        foreach ($this->Meta->Filters as $Key=>$Filter) {
            if (isset($Filter[0])) {
                if ($FilterString == "") {
                    $FilterString .= " WHERE ";
                } else {
                    $FilterString .= " AND ";
                }
                $FilterString .= "\"$Key\"";
                switch ($Filter[0]) {
                    case 1:
                        $FilterString .= " = $Filter[1]";
                        break;
                    case 2:
                        $FilterString .= " BETWEEN '$Filter[1]' AND '$Filter[2]'";
                        break;
                    case 3:
                        $FilterString .= $Filter[1];
                        break;
                }
            }
        }
        $this->Meta->Data = Database::PGFetchAllWithFieldType("SELECT $FieldString FROM \"{$this->Meta->Name}\"$JoinString$FilterString ORDER BY \"{$this->Meta->Name}\".\"ID\"", $this->Meta->Fields);
        if (!$this->Meta->Data) {
            return FALSE;
        }
        return TRUE;
    }
}
class Field {
    public $ID = 0;
    public $Name = "";
    public $Type = "";    
    public function __construct($Field) {
        $this->ID = $Field["ID"];
        $this->Name = $Field["Name"];
        $this->Type = $Field["Field Type"];
    }
}
class TableRelation {
    public function __construct($TableRelation) {
        foreach ($TableRelation as $Key=>$Property) {
            $this->$Key = $Property;
        }
    }
}
class Meta {
    
}