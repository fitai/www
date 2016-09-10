<?php
$title = "Now";
include("/var/www/html/header.php");
?>
<h1><?php echo $title; ?></h1>
School: <?php echo get_athlete_school(); ?><br>
Coach: <?php echo get_coach_name(); ?><br>
Team: <?php echo get_athlete_team_name(); ?><br>
Athlete Name: <?php echo get_athlete_name(); ?><br>	
Collar: <select name="collarID">
			<option value="1">1</value>
		</select><br>
Next Lift: <?php print_r(get_next_lift()); ?>

<?php
include('/var/www/html/footer.php');
?>
