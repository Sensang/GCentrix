<?php
require_once "default.php";
require_once "std.php";
Standard::$Database = new \GCentrix\Database(Database, Host, Port, User, Password, TRUE);
require "page/index.php";