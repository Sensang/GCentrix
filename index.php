<?php
if (include("")) {
    require_once "./std.php";
    require_once "./config.php";
    Database::Initialize(); 
} else {
    echo "<!DOCTYPE html><html><head><meta http-equiv=\"refresh\" content=\"0; url=install/\" /></head></html>";
}