<?php
include "connect.php";

if ($_REQUEST['liftid'])
	$liftid = $_REQUEST['liftid'];
else
	$liftid = 0;
		
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
}
$stmt2 = $myPDO->query($sql2);
if (!$stmt2) {
	echo "\nPDO::errorInfo():\n";
	print_r($myPDO->errorInfo());
}
else {
	$results = $stmt2->fetchAll(PDO::FETCH_ASSOC);	
	$rate = $results[0][lift_sampling_rate];
	$weight = $results[0][lift_weight];
	$units = $results[0][lift_weight_units];
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
$master = json_encode($master);
echo $master;
?>