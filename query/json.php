<?php
include "connect.php";

$liftid = '0';
/*if ($_REQUEST['liftid'])
	$liftid = $_REQUEST['liftid'];*/
		
$sql = "SELECT 
		ARRAY_AGG(timepoint) AS timepoint, 
		ARRAY_AGG(a_x) AS a_x 
		FROM lift_data 
		WHERE lift_id = $liftid
		GROUP BY lift_id";
$sql2 = "SELECT lift_sampling_rate, lift_weight, lift_weight_units FROM athlete_lift WHERE lift_id = $liftid";
$header = "";
$content = "";

$stmt = $myPDO->query($sql);
if (!$stmt) {
	echo "\nPDO::errorInfo():\n";
	print_r($myPDO->errorInfo());
}
else {
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$content = $results[0];
	//echo json_encode($results);
	$timepointData = $content['timepoint'];
	$timepointData = str_replace("}", "", str_replace("{", "", $timepointData));
	$axData = $content['a_x'];
	$axData = str_replace("}", "", str_replace("{", "", $axData));
	$contentArray = array (
						'timepoint' => array($timepointData),
						'a_x' => array($axData)
					);
	//echo $timepointData;
	//print_r($contentArray);
	$content = $contentArray;
}
$stmt2 = $myPDO->query($sql2);
if (!$stmt2) {
	echo "\nPDO::errorInfo():\n";
	print_r($myPDO->errorInfo());
}
else {
	$results = $stmt2->fetchAll(PDO::FETCH_ASSOC);	
	$rate = $results[0]['lift_sampling_rate'];
	$weight = $results[0]['lift_weight'];
	$units = $results[0]['lift_weight_units'];
	$header = array
		(
			'lift_id' => $liftid,
			'lift_sampling_rate' => $rate,
			'lift_weight' => $weight,
			'lift_weight_units' => $units
		);
}
$master = array
			( 
				'header' => $header,
				'content' => $content	
			);


$json = "'".json_encode($master)."'";
//$json = json_encode($master);
//echo exec("/var/www/html/query/php_process_data.py ".$json);
echo exec('/var/opt/python/fitai/php_process_data.py '.$json);