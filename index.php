<!DOCTYPE html>

<?php
require_once "default/default.php";
require_once StandardPath . "/standard.php";
use GCentrix\Standard as Standard;
Standard::GetDir(StandardPath, TRUE, TRUE, ".php");
Standard::GetDir(DefaultPath, TRUE, TRUE, ".php");
Standard::$Database = new \GCentrix\Database(Database, Host, Port, User, Password, TRUE);
Standard::GetDir("custom/", TRUE, TRUE, ".php");

//require "page/index.php";