<?php
session_start();
require("/var/www/html/includes/connect.php");
$newUserID = $_REQUEST['id'];

// TODO: Check to make sure current user is part of same team

$query = $myPDO->prepare("SELECT username FROM users WHERE id=:id");
$query->bindParam(':id', $newUserID, PDO::PARAM_STR);
$result = $query->execute();
$fetch = $query->fetch(PDO::FETCH_ASSOC);
$username = $fetch['username'];		

$rows = $query->rowCount();
if($rows==1) :
	$_SESSION['userID'] = $newUserID;
	$_SESSION['username'] = $username;
	header("Location: /now/"); // Redirect user to new lift
endif;	
?>