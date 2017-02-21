<?php
require("/var/www/html/includes/connect.php");
require_once("/var/www/html/includes/functions.php");

$liftID = "";
$liftRepsActual = "";
$comments = "";

if(isset($_REQUEST['liftID']))
	$liftID = $_REQUEST['liftID'];
if(isset($_REQUEST['liftRepsActual']))
	$liftRepsActual = $_REQUEST['liftRepsActual'];
if(isset($_REQUEST['liftComments']))
	$comments = $_REQUEST['liftComments'];

// Update lift info
$query = $myPDO->prepare("UPDATE athlete_lift SET final_num_reps=:final_num_reps, user_comment=:user_comment WHERE lift_id=:lift_id");
$query->bindParam(':final_num_reps', $liftRepsActual, PDO::PARAM_STR);
$query->bindParam(':user_comment', $comments, PDO::PARAM_STR);
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