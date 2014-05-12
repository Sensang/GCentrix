<?php
// REMINDER
// "SELECT setval('"public"."Table_ID_seq"', (SELECT max("ID") + 1 FROM "Table"));"

namespace GCentrix {
    $Tables = array();
    class Standard {
        public static $Database;
        public static $TableData;
        public static $DefaultServer;
        public static $Host;
        public static $Port;
        public static $User;
        public static $Password;
        public static $Connection;
        private static $Variables = array();
        public static $Tables = array();
        public static function Get($VarName) {
            if (!isset(self::$Variables[$VarName])) {
                self::$Variables[$VarName] = new $VarName();
            }
            return self::$Variables[$VarName];
        }
        public static function ReadDir($Filepath, $RemoveDefault, $Directories, $OnlyExt) {
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
            if ((!$Directories) || ($OnlyExt != "")) {
                foreach($Files as $Key=>$File) {
                    if (!((($Directories) && (is_dir($Filepath . $File)))
                       || (($OnlyExt == "") || (strtolower(substr($File, strlen($File) - strlen($OnlyExt))) == $OnlyExt)))) {
                        unset($Files[$Key]);
                    }
                }
            }
            return $Files;
        }
        public static function GetDir($Filepath, $Require, $Directories, $OnlyExt) {
            if ($Filepath[strlen($Filepath) - 1] != "/")
            {
                $Filepath .= "/";
            }
            $Files = Standard::ReadDir($Filepath, true, $Directories, $OnlyExt);
            foreach ($Files as $File) {
                if (($Directories) && (is_dir($Filepath . $File))) {
                    Standard::GetDir($Filepath . $File, $Require, $Directories, $OnlyExt);
                } else if ($Require) {
                    require_once $Filepath . $File;
                } else {
                    include $Filepath . $File;
                }
            }
        }
    }
    class Database {
        public $Name = "";
        public $Database = "";
        public $Host = "";
        public $Port = 0;
        public $User = "";
        public $Password = "";
        public $Cache = FALSE;
        public $Connection;
        public static function FormatValueByType(&$Value, $Type) {
            switch ($Type) {
                case "int8":
                    $Value = (float) $Value;
                    break;
                case "bpchar":
                    $Value = trim($Value);
                    break;
            }
        }
        public function PGFetchAllWithFormat($Query) {
            $Result = pg_query($this->Connection, $Query);
            $Table = pg_fetch_all($Result);
            if ($Table == FALSE) {
                return FALSE;
            } else {
                $Fields = array();
                $Counter = 0;
                foreach ($Table as $Key => $Data) {
                    foreach ($Data as $SubKey => $SubData) {
                        if (!isset($Fields[$SubKey])) {
                            $Fields[$SubKey] = pg_field_type($Result, $Counter);
                            $Counter++;
                        }
                        $this->FormatValueByType($Table[$Key][$SubKey], $Fields[$SubKey]);
                    }
                }
            }
            return $Table;
        }
        private function InitSystemTableByCache($Name) {
            // TODO - SECURITY
            // EVALUATE CACHE FILE FIRST?
            include "cache/table/$Name.php";
            if (!isset($this->SystemTables[$Name])) {
                $File = fopen(CacheTablePath . "$Name.php", "w");
                // $this->SystemTables[$Name] = pg_fetch_all(pg_query("SELECT * FROM \"$Name\""));
                $this->SystemTables[$Name] = $this->PGFetchAllWithFormat("SELECT * FROM \"$Name\"");
                if ($this->SystemTables[$Name] != FALSE) {
                    $CacheText = "<?php\r\n";
                    foreach ($this->SystemTables[$Name] as $Key => $Data) {
                        $CacheText .= '$this->SystemTables["' . $Name .'"][' . ($Key) . '] = [';
                        foreach ($Data as $SubKey => $SubData) {
                            if ($SubKey != "ID") {
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
                require_once CacheTablePath . "$Name.php";
                }
            }
        }
        private function InitSystemTableByQuery($Name) {
            $this->SystemTables[$Name] = $this->PGFetchAllWithFormat(pg_query("SELECT * FROM \"$Name\""));
            foreach ($this->SystemTables[$Name] as $Key => $Data) {
                foreach ($Data as $SubKey => $SubData) {
                    if (is_string($SubData)) {
                        $this->SystemTables[$Name][$Key][$SubKey] = trim($SubData);
                    }
                }
            }
        }
        public function __construct($Name, $Host, $Port, $User, $Password, $Cache) {
            $this->Name = $Name;
            $this->Host = $Host;
            $this->Port = $Port;
            $this->User = $User;
            $this->Password = $Password;
            $this->Cache = $Cache;
            $this->Connection = pg_connect("host=" . $this->Host . " port=" . $this->Port . " dbname=" . $this->Name . " user='" . $this->User . "' password='" . $this->Password . "'", $this->Connection);
            if ($Cache) {
                $this->InitSystemTableByCache("Table");
                // TODO - PERFORMANCE
                // LIMIT TO SYSTEM TABLES
                foreach ($this->SystemTables["Table"] as $Table) {
                    if ($Table["ID"] != 1) {
                        $this->InitSystemTableByCache($Table["Name"]);
                    }
                }
            } else {
                // TODO - VALIDITY
                // INT VALUES SHOULDNT BE STRING
                // 
                // TODO - PERFORMANCE
                // LIMIT TO SYSTEM TABLES
                $this->InitSystemTableByQuery("Table");
                foreach ($this->SystemTables["Table"] as $Table) {
                    if ($Table["ID"] != "1") {
                        $this->InitSystemTableByQuery($Table["Name"]);
                    }
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
        public $ID = 0;
        public $Name = "";
        public function __construct($ID) {
            if ($ID !== 0) {
                $this->Name = Standard::$TableData[$ID];
            }
        }
    }
    class Field {
        
    }
}