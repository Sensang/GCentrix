<?php
// use GCentrix\Standard as Standard;
namespace GCentrix {
    Standard::$DefaultClient = "GCentrix";
    Standard::$Database = "GCentrix";
    Standard::$Host = "localhost";
    Standard::$Port = 5432;
    Standard::$User = "GAdmin";
    Standard::$Password = "G123";
    Standard::$Connection = pg_connect("host=" . Standard::$Host . " port=" . Standard::$Port . " dbname=" . Standard::$Database . " user='" . Standard::$User . "' password='" . Standard::$Password . "'");
}