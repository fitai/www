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
		case 'lift-type':
			$returnArray[] = "updateLiftType('".$value."')";
			$liftType = $value;
			break;
		case 'lift-weight':
			$returnArray[] = "updateLiftWeight('".$value."')";
			$liftWeight = $value;
			break;
		case 'lift-reps':
			$maxReps = $value;
			break;
		case 'userID':
			$athleteID = get_athlete_id($value); // Set athleteID
			break;
	}
}
$redisArray = array(
				"athlete_id" => $athleteID,
				"lift_id" => 'None',
				"lift_start" => "None",
				"lift_type" => $liftType,
				"lift_weight" => $liftWeight,
				"lift_weight_units" => "lbs",
				"lift_num_reps" => $maxReps,
				"calc_reps" => 0,
				"threshold" => "None",
				"curr_state" => 'rest'
				);

// Update collar_info table
$query = $myPDO->prepare("UPDATE collar_info SET athlete_id=:athlete_id, last_update=:last_update WHERE collar_id=:collar_id");
$query->bindParam(':athlete_id', $athleteID, PDO::PARAM_STR);
$query->bindParam(':last_update', date("Y-m-d H:i:s"), PDO::PARAM_STR);
$query->bindParam(':collar_id', $collarID, PDO::PARAM_STR);
$result = $query->execute();
$jsonArray = array(
				"functions" => $returnArray,
				"redis" => $redisArray
				);
print json_encode($jsonArray);
?>