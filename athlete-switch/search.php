<?php
session_start();
require("/var/www/html/includes/connect.php");

$athlete_name = '';
if (isset($_REQUEST['athlete_name'])) :
	$athlete_name = '%' . ucwords($_REQUEST['athlete_name']) . '%';
endif;

$query = $myPDO->prepare("
		SELECT * 
		FROM athlete_info
		WHERE 
			team_id IN (
				SELECT team_id
				FROM athlete_info
				WHERE user_id=:user_id
				)
			AND athlete_first_name || ' ' || athlete_last_name 
				LIKE :athlete_name
		ORDER BY athlete_first_name
		");

$query->bindParam(':user_id', $_SESSION['userID'], PDO::PARAM_STR);
$query->bindParam(':athlete_name', $athlete_name, PDO::PARAM_STR);
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