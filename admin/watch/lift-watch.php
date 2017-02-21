<?php
require("/var/www/html/includes/connect.php");
require_once("/var/www/html/includes/functions.php");

$returnArray = array();
$athleteID = "";
$collarID = "";
$liftWeight = "";
$liftType = "";
$maxReps = "";
// Parse form submission data
foreach($_REQUEST as $key => $value) {
	switch ($key) {
		case 'collarID':
			$returnArray[] = "updateCollarID('".$value."')";
			$collarID = $value;
			break;
		case 'athleteID':
			$athleteID = get_athlete_id($value); // Set athleteID
			break;
	}
}

//Return JSON string to web listener
$jsonArray = array(
				"functions" => $returnArray,
				"redis" => $redisArray,
				"return" => $redisReturn
				);
			
//print json_encode($jsonArray);

?>
