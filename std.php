<?php
// REMINDER
// "SELECT setval('"public"."Table_ID_seq"',(SELECT max("ID") + 1 FROM "Table"));"

function Get($VarName) {
    if (!isset(self::$Variables[$VarName])) {
        self::$Variables[$VarName] = new $VarName();
    }
    return self::$Variables[$VarName];
}
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
class Database {
    public static $Name = "";
    public static $Database = "";
    public static $Host = "";
    public static $Port = 0;
    public static $User = "";
    public static $Password = "";
    public static $Connection;
    public static $Field = array();
    public static $SystemTables = array();
    public static $Tables = array();
    public static $CachePath = "";
    public static $StandardPath = "";
    public static $DefaultPath = "";
    public static $CustomPath = "";
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
        self::$Field = new Table(self::$Tables["Field"], FALSE);
        $Fields = self::PGFetchAllWithFormat("SELECT * FROM \"Field\" WHERE \"Table\"='" . self::$SystemTables["Table"][self::$Tables["Field"]]["ID"] . "'");
        foreach ($Fields as $Field) {
            self::$Field->Meta->Fields[$Field["Name"]] = new Field($Field);
        }
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
            case 13:
            case 14:
            case 15:
            case "bpchar":
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
        // TODO - SECURITY
        // EVALUATE CACHE FILE FIRST?
        include self::$CachePath . "/table/$Name.php";
        if (!isset(self::$SystemTables[$Name])) {
            $File = fopen(self::$CachePath . "table/$Name.php", "w");
            $Tables = self::PGFetchAllWithFormat("SELECT * FROM \"$Name\" ORDER BY \"ID\"");
            if ($Tables != FALSE) {
                $CacheText = "<?php\r\n";
                foreach ($Tables as $Data) {
                    $CacheText .= 'Database::$SystemTables["' . $Name .'"][' .($Data["ID"] - $ID * 1000000000000) . '] = [';
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
            require_once self::$CachePath . "table/$Name.php";
            }
        }
    }
}
class Debug {
    public static function OutputVar($Name, $Var) {
        echo "$Name: $Var</br>";
    }
    public static function TestValue($Name, $Value, $TestValue) {
        if ($Value != $TestValue) {
            // ERROR
            echo "Fehler: $Name ist $Value, $TestValue erwartet</br>";
            return FALSE;
        }
        return true;
    }
}
class Table {
    public $Meta;
    public function __construct($ID, $Initialization) {
        $this->Meta = new Meta();
        $this->Meta->ID = $ID;
        $this->Meta->Name = Database::$SystemTables["Table"][$ID]["Name"];
        $this->Meta->Filters = array();
        $this->Meta->Fields = array();
        $this->Meta->Data = array();
        $this->Meta->Pointer = 0;
        $this->Meta->Queried = FALSE;
        if ($Initialization) {
            $this->Initialization();
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
    public function Initialization() {
        Database::$Field->SetValueFilter("Table", $this->Meta->ID + 1000000000000);
        Database::$Field->Query();
        do {
            $this->Meta->Fields[Database::$Field->Name] = new Field(["ID"=>Database::$Field->ID, "Name"=>Database::$Field->Name, "Field Type"=>Database::$Field->{"Field Type"}]);
        } while (Database::$Field->Next());
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
        return $this->Step(-1);
    }
    public function Next() {
        return $this->Step(1);
    }
    public function First() {
        return $this->Step(-$this->Meta->Pointer);
    }
    public function Step($Number) {
        if (!$this->Meta->Queried) {
            return $this->Query();
        } else if (isset($this->Meta->Data[$this->Meta->Pointer + $Number])) {
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
        $FilterString = "";
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
        $this->Meta->Data = Database::PGFetchAllWithFieldType("SELECT * FROM \"{$this->Meta->Name}\"$FilterString ORDER BY \"ID\"", $this->Meta->Fields);
        if (!$this->Meta->Data) {
            return FALSE;
        }
        $this->Meta->Queried = TRUE;
        $this->Step(0);
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
class Meta {
    
}