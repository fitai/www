<?php
$host = "test-db-instance.cls1x0o0bifh.us-east-1.rds.amazonaws.com";
$port = "5432";
$dbname = "fitai";
$dbuser = "db_user";
$dbpass = "dbuserpassword";

$myPDO = new PDO("pgsql:dbname=$dbname;host=$host", $dbuser, $dbpass);
?>