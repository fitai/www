<?php
$host = "test-db-instance.cls1x0o0bifh.us-east-1.rds.amazonaws.com";
$port = "5432";
$dbname = "fitai";
$dbuser = "db_user";
$dbpass = "dbuserpassword";

/*pg_connect("host=".$host." dbname=fitai user=db_user password=dbuserpassword")
    or die("Can't connect to database".pg_last_error());*/
	
try {
	// Connect to the DB
    $myPDO = new PDO("pgsql:dbname=$dbname;host=$host", $dbuser, $dbpass);
	// SQL query and results
	$sql = "SELECT * FROM lift_data";
	foreach($myPDO->query($sql) as $row) {
		echo $row[0], ': ', $row[1], '<br>';
	}
	$myPDO = null;
} catch (PDOException $e) {
	// Display error messages
	echo 'Connection failed: ' . $e->getMessage();
	die();
}
?>