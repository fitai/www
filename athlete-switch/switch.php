<?php
session_start();
require("/var/www/html/includes/connect.php");
$newUserID = $_REQUEST['id'];

// TODO: Check to make sure current user is part of same team

//$query = $myPDO->prepare("SELECT username FROM users WHERE id=:id");
$query = $myPDO->prepare("
					SELECT
						u.username
					FROM users AS u
					INNER JOIN athlete_info AS ai
						ON u.id = ai.user_id
					WHERE ai.team_id = (SELECT team_id FROM athlete_info WHERE user_id=:curr_user_id)
						AND ai.user_id = :new_user_id
					");
$query->bindParam(':curr_user_id', $_SESSION['userID'], PDO::PARAM_STR);
$query->bindParam(':new_user_id', $newUserID, PDO::PARAM_STR);
$result = $query->execute();
$fetch = $query->fetch(PDO::FETCH_ASSOC);
$username = $fetch['username'];		

$rows = $query->rowCount();
if($rows==1) :
	$_SESSION['userID'] = $newUserID;
	$_SESSION['username'] = $username;
	header("Location: /now/"); // Redirect user to new lift
else:
	echo ('Not a valid teammate. <a href="/">Try again</a>.');
endif;	
?>