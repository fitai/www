<?php
require("/var/www/html/includes/connect.php");
require_once("/var/www/html/includes/functions.php");

$function = "query_athlete_name";
$variables = 4;

if(isset($_REQUEST['func']))
	$function = $_REQUEST['func'];
if(isset($_REQUEST['vars']))
	$variables = $_REQUEST['vars'];

$response = $function($variables);
return $response;
?>