<?php
$title = "Switch";
include("/var/www/html/header.php");
?>
<div id="athlete-switch">
	<?php
	$query = $myPDO->prepare("
		SELECT * 
		FROM athlete_info
		WHERE team_id IN (
			SELECT team_id
			FROM athlete_info
			WHERE user_id=:user_id
			)
		ORDER BY athlete_first_name
		");
	$query->bindParam(':user_id', $_SESSION['userID'], PDO::PARAM_STR);
	$result = $query->execute();
	$fetch = $query->fetchAll(PDO::FETCH_ASSOC);
	foreach ($fetch as $row) :
	?>
		<div class="athlete">
			<a href="switch.php?id=<?php echo $row['user_id']; ?>">
				<?php echo $row['athlete_first_name']." ".$row['athlete_last_name']; ?>
			</a>
		</div>
	<?php
	endforeach;
	?>
</div>
<?php
include('/var/www/html/footer.php');
?>