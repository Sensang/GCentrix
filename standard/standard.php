<?php
namespace GCentrix {
    $Tables = array();
    class Standard {
        public static $TableData;
        public static $DefaultClient;
        public static $Database;
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
            $Files = Standard::ReadDir($Filepath, TRUE, $Directories, $OnlyExt);
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
            return TRUE;
        }
    }
    class Table {
        public $ID = 0;
        public $Name = '';
        public function __construct($ID) {
            if ($ID !== 0) {
                $this->Name = Standard::$TableData[$ID];
            }
        }
    }
    class Field {
        
    }
}