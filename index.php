<!DOCTYPE html>
<?php
require_once "standard/standard.php";
use GCentrix\Standard as Standard;
Standard::GetDir("standard/", true, true, ".php");
Standard::GetDir("default/", true, true, ".php");
Standard::$TableData = array();
Standard::GetDir("client/" . strtolower(Standard::$DefaultClient) . "/", true, true, ".php");
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>
            
        </title>
    </head>
    <body>
        <?php
        $Result = pg_fetch_all(pg_query("SELECT * FROM \"Table\""));
        foreach ($Result as $Data) {
            echo implode(", ", $Data) . "<br />";
        }
        ?>
    </body>
</html>