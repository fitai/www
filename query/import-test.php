<?php
include "../includes/connect.php";

$csv = array_map('str_getcsv', file('lift-schedule.csv'));
$result = pg_convert($myPDO, 'lift_schedule', $csv);
if ($result)
	echo "success";
else
	echo "unsuccess";

/*$sql = "COPY lift_schedule FROM 'lift-schedule.csv' DELIMITER ',' CSV HEADER";
$stmt = $myPDO->query($sql);
if (!$stmt) {
	echo "\nPDO::errorInfo():\n";
	print_r($myPDO->errorInfo());
}
else {
	echo "Success!";
}*/
?>