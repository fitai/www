<?php
echo "test";
$host = "test-db-instance.cls1x0o0bifh.us-east-1.rds.amazonaws.com";
$db = "fitai";
$user = "db_user";
$pass = "dbuserpassword";

try {
	// Connect to the DB
    $myPDO = new PDO('pgsql:host=localhost;dbname='.$db, $user, $pass);
	
	// SQL query and results
	$sql = "SELECT * FROM lift_data";
	foreach($dbh->query($sql) as $row) {
		echo $row[0], ': ', $row[1], '<br>';
	}
	$dbh = null;
} catch (PDOException $e) {
	// Display error messages
	echo "Error!: ", $e->getMessage*(), '<br>';
	die();
}
?>