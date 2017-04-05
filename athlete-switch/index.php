<?php
$title = "Switch";
include("/var/www/html/header.php");
?>
<h1><?php echo $title; ?></h1>
<div id="athlete-search">
	<label for="athlete_name">Search Athlete: </label>
	<input name="athlete_name"> <button id="search-athlete" class="small">Search</button>
</div>
<hr>
<div id="athlete-switch" class="flexbox athlete-switch">
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
				<img src="/images/blank-person.png" width="200" height="200"><br>
				<?php echo $row['athlete_first_name']." ".$row['athlete_last_name']; ?>
			</a>
		</div>
	<?php
	endforeach;
	?>
</div>
<script>
$('input[name=athlete_name').keypress(function(e) {
	// If press enter on field
	if (e.which == 13) {
		$('#search-athlete').click();
	}
});
$('#search-athlete').on('click', function() {
	var athlete_name = $('input[name=athlete_name]').val();

	if (athlete_name !== '' ) {
		$.post('search.php', { athlete_name: athlete_name }, function(data) {
			$('#athlete-switch').html(data);
		});
	}
});
</script>
<?php
include('/var/www/html/footer.php');
?>