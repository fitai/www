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
		case 'liftWeight':
			$returnArray[] = "updateLiftWeight('".$value."')";
			$liftWeight = $value;
			break;
		case 'liftReps':
			$maxReps = $value;
			break;
		case 'userID':
			$athleteID = get_athlete_id($value); // Set athleteID
			break;
	}
}

// Pass data to Redis
$redisArray = array(
				"collar_id" => $collarID,
				"athlete_id" => $athleteID,
				"lift_id" => 'None',
				"lift_start" => "None",
				"lift_type" => $liftType,
				"lift_weight" => $liftWeight,
				"weight_units" => "lbs",
				"init_num_reps" => $maxReps,
				"calc_reps" => 0,
				"threshold" => "None",
				"curr_state" => 'rest',
				"active" => True
				);

				
$redisReturn = exec("/home/jbrubaker/anaconda2/envs/fitai/bin/python /var/opt/python/fitai_controller/comms/update_redis.py -v -j '".json_encode($redisArray)."'");

$liftID = str_replace("lift_id: ", "", $redisReturn);
			
// Update collar_info table
$query = $myPDO->prepare("UPDATE collar_info SET athlete_id=:athlete_id, last_update=:last_update WHERE collar_id=:collar_id");
$query->bindParam(':athlete_id', $athleteID, PDO::PARAM_STR);
$query->bindParam(':last_update', date("Y-m-d H:i:s"), PDO::PARAM_STR);
$query->bindParam(':collar_id', $collarID, PDO::PARAM_STR);
$result = $query->execute();

//Return JSON string to web listener
$jsonArray = array(
				"functions" => $returnArray,
				"redis" => $redisArray,
				"return" => $redisReturn,
				"liftID" => $liftID
				);
			
print json_encode($jsonArray);

?>
