<?php
include "connect.php";

$liftid = '0';
if ($_REQUEST['liftid'])
	$liftid = $_REQUEST['liftid'];
		
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
	$timepointData = $content['timepoint'];
	$timepointData = str_replace("}", "", str_replace("{", "", $timepointData));
	$axData = $content['a_x'];
	$axData = str_replace("}", "", str_replace("{", "", $axData));
	$contentArray = array (
						'timepoint' => array($timepointData),
						'a_x' => array($axData)
					);
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

//Prepare and execute python script
$json = "'".json_encode($master)."'";
$return = "";
$output = "";
$results = exec("/home/jbrubaker/anaconda2/envs/fitai/bin/python /var/opt/python/fitai/php_process_data.py -d ".$json, $output, $return);

//Turn JSON to Array
$outputArray = json_decode($results, true);

//Create array for headers
$headerArray = array(json_decode($output[6], true));


//Output to CSV
function outputCSV($data) {
	$outputBuffer = fopen("php://output", 'w');
	foreach($data as $val) {
		fputcsv($outputBuffer, $val);
	}
	fclose($outputBuffer);
}

//Download results as CSV
$filename = date('Y-m-d-His');
$csvResults = array_merge($headerArray, $outputArray);

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename={$filename}.csv");
header("Pragma: no-cache");
header("Expires: 0");

outputCSV($csvResults);
?>


