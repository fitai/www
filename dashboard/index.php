<?php
$title = "Dashboard";
include("/var/www/html/header.php");
include("/var/www/html/auth.php");
?>

<div class="form">
	<p>This is another secured page.</p>
	<p><a href="/">Home</a></p>
	<a href="/logout/">Logout</a>
</div>

<?php
include('/var/www/html/footer.php');
?>