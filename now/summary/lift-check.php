<?php
require("/var/www/html/includes/connect.php");
require_once("/var/www/html/includes/functions.php");

$liftID = NULL;
$liftRepsActual = 0;
$comments = NULL;
$fieldName = NULL;
$updateValue = NULL;


if(isset($_REQUEST['liftID']))
	$liftID = $_REQUEST['liftID'];
if(isset($_REQUEST['liftRepsActual']))
	$liftRepsActual = $_REQUEST['liftRepsActual'];
if(isset($_REQUEST['liftComments']))
	$comments = $_REQUEST['liftComments'];
if(isset($_REQUEST['fieldName']))
	$fieldName = $_REQUEST['fieldName'];
if(isset($_REQUEST['updateValue']))
	$updateValue = $_REQUEST['updateValue'];

// Update lift info
// $query = $myPDO->prepare("UPDATE athlete_lift SET final_num_reps=:final_num_reps, user_comment=:user_comment WHERE lift_id=:lift_id");
$query = $myPDO->prepare("UPDATE athlete_lift SET $fieldName=:update_value WHERE lift_id=:lift_id");
$query->bindParam(':update_value', $updateValue, PDO::PARAM_STR);
$query->bindParam(':lift_id', $liftID, PDO::PARAM_STR);
$result = $query->execute();

$count = $query->rowCount();

if ($count > 0) :
	echo "Your post-lift corrections have been made.";
else :
	echo "\nPDO::errorInfo():\n";
    print_r($query->errorInfo());
endif;

?>