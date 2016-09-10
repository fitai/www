<?php
require("/var/www/html/includes/functions.php");
require("/var/www/html/includes/connect.php");

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?php page_title(); ?></title>
<link rel="stylesheet" href="/css/style.css" />
<link rel="stylesheet" href="/css/webfont.css" />
<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div id="body-content" class="body-<?php echo strtolower($title); ?>" >
	<div id="main">
