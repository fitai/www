<?php

include "connect.php";

$columns = $_POST['columns'];
$columns = rtrim($columns, ", ");
$now = date('YmdHis');

//$sql = "COPY (SELECT $columns FROM lift_data) TO '/var/www/html/query/$now.csv' WITH (FORMAT CSV, HEADER)";
//$sql = "COPY (SELECT * FROM lift_data) TO '/var/www/html/query/test.csv' format csv";
//$sql = "Copy (Select * From lift_data) To 'test.csv' With CSV DELIMITER ','";
$sql = "SELECT ".$columns." FROM lift_data";
//$sql = "SELECT a_x FROM lift_data";

$columnarray = explode(", ", $columns);
$headerarray =  array($columnarray);

//Output to CSV
function outputCSV($data) {
	$outputBuffer = fopen("php://output", 'w');
	foreach($data as $val) {
		fputcsv($outputBuffer, $val);
	}
	fclose($outputBuffer);
}

$stmt = $myPDO->query($sql);
if (!$stmt) {
	echo "\nPDO::errorInfo():\n";
	print_r($myPDO->errorInfo());
}
else {
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	//print_r($results);
	$filename = date('Y-m-d-His');
	$csvresults = array_merge($headerarray, $results);

	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename={$filename}.csv");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	outputCSV($csvresults);
}

//$text = pg_copy_to($myPDO, 'lift_data');


?>