<?php
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