<?php
require("/var/www/html/includes/connect.php");
require_once("/var/www/html/includes/functions.php");

$returnArray = array();
$athleteID = "";
$collarID = "";
$liftWeight = "";
$liftType = "";
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
		case 'userID':
			$athleteID = get_athlete_id($value); // Set athleteID
			break;
	}
}

// Update collar_info table
$query = $myPDO->prepare("UPDATE collar_info SET athlete_id=:athlete_id, last_update=:last_update WHERE collar_id=:collar_id");
$query->bindParam(':athlete_id', $athleteID, PDO::PARAM_STR);
$query->bindParam(':last_update', date("Y-m-d H:i:s"), PDO::PARAM_STR);
$query->bindParam(':collar_id', $collarID, PDO::PARAM_STR);
$result = $query->execute();
print json_encode($returnArray);
?>