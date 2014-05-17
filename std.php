<?php
// REMINDER
// "SELECT setval('"public"."Table_ID_seq"',(SELECT max("ID") + 1 FROM "Table"));"

function Get($VarName) {
    if(!isset(self::$Variables[$VarName])) {
        self::$Variables[$VarName] = new $VarName();
    }
    return self::$Variables[$VarName];
}
function ReadDir($Filepath, $RemoveDefault, $Directories, $OnlyExt) {
    if($Filepath[strlen($Filepath) - 1] != "/")
    {
        $Filepath .= "/";
    }
    $OnlyExt = strtolower($OnlyExt);
    $Files = scandir($Filepath);
    if($RemoveDefault) {
        unset($Files[0]);
        unset($Files[1]);
    }
    if((!$Directories) ||($OnlyExt != "")) {
        foreach($Files as $Key=>$File) {
            if(!((($Directories) &&(is_dir($Filepath . $File)))
               ||(($OnlyExt == "") ||(strtolower(substr($File, strlen($File) - strlen($OnlyExt))) == $OnlyExt)))) {
                unset($Files[$Key]);
            }
        }
    }
    return $Files;
}
function GetDir($Filepath, $Require, $Directories, $OnlyExt) {
    if($Filepath[strlen($Filepath) - 1] != "/")
    {
        $Filepath .= "/";
    }
    $Files = ReadDir($Filepath, true, $Directories, $OnlyExt);
    foreach($Files as $File) {
        if(($Directories) &&(is_dir($Filepath . $File))) {
            GetDir($Filepath . $File, $Require, $Directories, $OnlyExt);
        } else if($Require) {
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
        self::InitSystemTableByCache("Table");
        foreach(self::$SystemTables["Table"] as $Table) {
            if(($Table["ID"] != 1) &&($Table["System Table"] == 1)) {
                self::InitSystemTableByCache($Table["Name"]);
            }
        }
        foreach (self::$SystemTables["Table"] as $Table) {
            self::$Tables[$Table["Name"]] = $Table["ID"];
        }
        self::$Field = new Table(self::$Tables["Field"], FALSE);
        self::$Field->Fields = self::PGFetchAllWithFormat("SELECT * FROM \"Field\" WHERE \"Table\"=" . self::$Tables["Field"]);
    }
    public static function FormatValueByType(&$Value, $Type) {
        switch($Type) {
            case "int8":
                $Value =(float) $Value;
                break;
            case "bpchar":
                $Value = trim($Value);
                break;
        }
    }
    public static function PGFetchAllWithFormat($Query) {
        $Result = pg_query(self::$Connection, $Query);
        $Tables = pg_fetch_all($Result);
        if($Tables == FALSE) {
            return FALSE;
        } else {
            $Fields = array();
            $Counter = 0;
            foreach($Tables as $Key => $Data) {
                foreach($Data as $SubKey => $SubData) {
                    if(!isset($Fields[$SubKey])) {
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
        $Result = pg_query(self::$Connection, $Query);
        $Tables = pg_fetch_all($Result);
        if($Tables == FALSE) {
            return FALSE;
        } else {
            foreach($Tables as $Key => $Data) {
                foreach($Data as $SubKey => $SubData) {
                    self::FormatValueByType($Tables[$Key][$SubKey], $Fields["Field Type"]);
                }
            }
        }
        return $Tables;
    }
    private function InitSystemTableByCache($Name) {
        // TODO - SECURITY
        // EVALUATE CACHE FILE FIRST?
        include "cache/table/$Name.php";
        if(!isset($this->SystemTables[$Name])) {
            $File = fopen(self::$CachePath . "table/$Name.php", "w");
            $this->SystemTables[$Name] = self::PGFetchAllWithFormat("SELECT * FROM \"$Name\" ORDER BY ID");
            if($this->SystemTables[$Name] != FALSE) {
                $CacheText = "<?php\r\n";
                foreach($this->SystemTables[$Name] as $Key => $Data) {
                    $CacheText .= 'Database::$SystemTables["' . $Name .'"][' .($Key) . '] = [';
                    foreach($Data as $SubKey => $SubData) {
                        if($SubKey != "ID") {
                            $CacheText .= ", ";
                        }
                        $CacheText .= "'$SubKey' => ";
                        if(is_string($SubData)) {
                            $CacheText .= "'" . trim($SubData) . "'";
                        } else {
                            $CacheText .= $SubData;
                        }
                    }
                    $CacheText .= "];\r\n";
                }
            fwrite($File, $CacheText);
            fclose($File);
            require_once self::$CacheTable . "table/$Name.php";
            }
        }
    }
}
class Debug {
    public static function OutputVar($Name, $Var) {
        echo "$Name: $Var</br>";
    }
    public static function TestValue($Name, $Value, $TestValue) {
        if($Value != $TestValue) {
            // ERROR
            echo "Fehler: $Name ist $Value, $TestValue erwartet</br>";
            return FALSE;
        }
        return true;
    }
}
class Table {
    public $ID = 0;
    public $Name = "";
    public $Filters = array();
    public $Fields = array();
    public $Data = array();
    public $Pointer = 0;
    public function __construct($ID, $Initialization) {
        $this->ID = $ID;
        $this->Name = Database::$Table[$ID];
        if($Initialization) {
            $this->Initialization();
        }
    }
    public function Copy($Table) {
        foreach($this->Fields as $Field) {
            $this->{$Field["Name"]} = $Table->{$Field["Name"]};
        }
    }
    public function Initialization() {
        Database::$Field->SetValueFilter("Table", $this->ID);
        Database::$Field->Query();
        do {
            $this->Fields = new Field(["ID"=>Database::$Field->ID, "Name"=>Database::$Field->Name, "Field Type"=>Database::$Field->{"Field Type"}]);
        } while (Database::$Field->Next());
    }
    public function SetValueFilter($Field, $Value) {
        $this->Filters[$Field][0] = 1;
        $this->Filters[$Field][1] = $Value;
    }
    public function SetRangeFilter($Field, $Value, $Value2) {
        $this->Filters[$Field][0] = 2;
        $this->Filters[$Field][1] = $Value;
        $this->Filters[$Field][2] = $Value2;
    }
    public function SetStringFilter($Field, $Value) {
        $this->Filters[$Field][0] = 3;
        $this->Filters[$Field][1] = $Value;        
    }
    public function ResetFilter($Field) {
        unset($this->Filters[$Field]);
    }
    public function Reset() {
        unset($this->Filters);
        $this->Filters = array();
    }
    public function Step($Number) {
        if(isset($this->Data[$this->Pointer + $Number])) {
            $this->Pointer += $Number;
            return TRUE;
        }
        return FALSE;
    }
    public function Query() {
        $FilterString = "";
        foreach ($this->Filters as $Key=>$Filter) {
            if (isset($Filter[0])) {
                if ($FilterString == "") {
                    $FilterString .= " WHERE ";
                } else {
                    $FilterString .= " AND ";
                }
                $FilterString .= $Key;
                switch ($Filter[0]) {
                    case 1:
                        $FilterString .= " = '$Filter[1]'";
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
        $this->Data = Database::PGFetchAllWithFieldType("SELECT * FROM \"$this->Name\"" . $FilterString, $this->Fields);
        $this->Pointer = 0;
        $this->Step(0);
    }
}
class Field {
    public $ID = "";
    public $Name = "";
    public $Type = "";
    public function __construct($Field) {
        foreach ($Field as $Key=>$SubField) {
            $this->$Key = $SubField;
        }
    }
}