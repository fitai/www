<?php
function home_path($data) {
	echo "/var/www/html/".$data;
}
function page_title() {
	global $title;
	echo $title." | Fit A.I";
}

/* Client Calls */
function get_athlete_school() {
	global $myPDO;
	$query = $myPDO->prepare("
		SELECT cli.client_name 
		FROM client_info AS cli 
		INNER JOIN athlete_info AS ai 
		ON cli.client_id = ai.client_id 
		WHERE ai.user_id=:user_id");
	$query->bindParam(':user_id', $_SESSION['userID'], PDO::PARAM_STR);
	$result = $query->execute();
	$fetch = $query->fetch(PDO::FETCH_ASSOC);
	$name = $fetch['client_name'];
	
	return $name;
}

/* Team Calls */
function get_athlete_team_name() {
	global $myPDO;
	$query = $myPDO->prepare("
		SELECT ti.team_name 
		FROM team_info AS ti 
		INNER JOIN athlete_info AS ai 
		ON ti.coach_id = ai.coach_id 
		WHERE ai.user_id=:user_id");
	$query->bindParam(':user_id', $_SESSION['userID'], PDO::PARAM_STR);
	$result = $query->execute();
	$fetch = $query->fetch(PDO::FETCH_ASSOC);
	$name = $fetch['team_name'];
	
	return $name;
}

/* Coach Calls */
function get_coach_id() {
	global $myPDO;
	$query = $myPDO->prepare("SELECT coach_id FROM athlete_info WHERE user_id=:user_id");
	$query->bindParam(':user_id', $_SESSION['userID'], PDO::PARAM_STR);
	$result = $query->execute();
	$fetch = $query->fetch(PDO::FETCH_OBJ);
	$userID = $fetch->coach_id;
	
	return $userID;
}
function get_coach_name() {
	global $myPDO;
	$query = $myPDO->prepare("
		SELECT ci.coach_first_name, ci.coach_last_name
		FROM coach_info AS ci 
		INNER JOIN athlete_info AS ai 
		ON ci.coach_id = ai.coach_id 
		WHERE ai.user_id=:user_id");
	$query->bindParam(':user_id', $_SESSION['userID'], PDO::PARAM_STR);
	$result = $query->execute();
	$fetch = $query->fetch(PDO::FETCH_ASSOC);
	$firstName = $fetch['coach_first_name'];
	$lastName = $fetch['coach_last_name'];
	$fullName = $firstName." ".$lastName;
	
	return $fullName;
}
function get_coach_first_name() {
	global $myPDO;
	$query = $myPDO->prepare("
		SELECT ci.coach_first_name 
		FROM coach_info AS ci 
		INNER JOIN athlete_info AS ai 
		ON ci.coach_id = ai.coach_id 
		WHERE ai.user_id=:user_id");
	$query->bindParam(':user_id', $_SESSION['userID'], PDO::PARAM_STR);
	$result = $query->execute();
	$fetch = $query->fetch(PDO::FETCH_ASSOC);
	$firstName = $fetch['coach_first_name'];
	
	return $firstName;
}

/* Athlete Calls */
function get_athlete_name() {
	global $myPDO;
	$query = $myPDO->prepare("SELECT athlete_first_name, athlete_last_name FROM athlete_info WHERE user_id=:user_id");
	$query->bindParam(':user_id', $_SESSION['userID'], PDO::PARAM_STR);
	$result = $query->execute();
	$fetch = $query->fetch(PDO::FETCH_ASSOC);
	$firstName = $fetch['athlete_first_name'];
	$lastName = $fetch['athlete_last_name'];
	$fullName = $firstName." ".$lastName;
	
	return $fullName;
}
function get_next_lift() {
	global $myPDO;
	$query = $myPDO->prepare("SELECT * FROM lift_schedule WHERE user_id=:user_id AND complete <> 'y' ORDER BY id ASC LIMIT 1");
	$query->bindParam(':user_id', $_SESSION['userID'], PDO::PARAM_STR);
	$result = $query->execute();
	$fetch = $query->fetch(PDO::FETCH_ASSOC);
		
	return $fetch;
}

?>