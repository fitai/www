<?php
require("/var/www/html/includes/connect.php");
require_once("/var/www/html/includes/functions.php");
require("/var/www/html/auth.php");

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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<?php if ($title == "Now" || $title == "Admin - Watch") : ?>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript" src="/js/charts.js"></script>
	<script src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
	<script src="/js/form-validation.js"></script>
<?php endif; ?>

</head>
<body>
<div id="body-content" class="body-<?php echo strtolower($title); ?>" >
	<nav id="nav-menu">
		<div class="container">
			<div class="nav-items">
				<div>
					<a href="/">
						<i class="dripicons-home"></i>Home
					</a>
				</div>
				<div>
					<a href="/now/">
						<i class="dripicons-clock"></i><span>Now</span>
					</a>
				</div>
				<div>
					<a href="/profile/">
						<i class="dripicons-user-id"></i>Profile
					</a>
				</div>
				<div>
					<a href="/export/">
						<i class="dripicons-export"></i>Export
					</a>
				</div>
				<div>
					<a href="/settings/">
						<i class="dripicons-gear"></i>Settings
					</a>
				</div>
				<div>
					<a href="/athlete-switch/">
						<i class="dripicons-user-group"></i>Switch
					</a>
				</div>
			</div>
			<div class="nav-footer">
				<a href="/logout/">Log Out</a><br>
				<?php //echo $_SESSION['username'];
					echo get_athlete_name();
				?>
			</div>
		</div>
	</nav>
	<div id="main">
		<header>
			<img id="logo" src="/images/fitai-logo-black.svg">
		</header>
		<div id="dynamic-content">
