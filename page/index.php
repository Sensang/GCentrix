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
        $Field = new Table(Database::$Tables["Field"], TRUE);
        $Field->SetValueFilter("Field Type", 1);
        $Field->PrintTable();
        ?>
    </body>
</html>