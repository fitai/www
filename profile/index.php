<?php
$title = "Profile";
include("/var/www/html/header.php");
?>
<h1><?php echo $title; ?></h1>
Athlete Name: <?php echo get_athlete_name(); ?><br>	
Age: <?php echo get_athlete_age(); ?><br>	
Gender: <?php echo get_athlete_gender(); ?><br>	
School: <?php echo get_athlete_school(); ?><br>
Coach: <?php echo get_coach_name(); ?><br>
Team: <?php echo get_athlete_team_name(); ?><br>
Team ID: <?php echo get_athlete_team_id(); ?><br>
Next Lift: <?php print_r(get_next_lift()); ?>


<?php
include('/var/www/html/footer.php');
?>
