<!DOCTYPE html>
<?php
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