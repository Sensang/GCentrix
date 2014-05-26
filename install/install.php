<?php
$Connection = pg_connect("host={$_GET["Server"]} port={$_GET["Port"]} dbname='{$_GET["Database"]}' user='{$_GET["User"]}' password='{$_GET["Password"]}'");
require "../std.php";
require "./data.php";
require "./cache.php";
