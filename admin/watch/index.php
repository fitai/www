<?php
$title = "Admin - Watch";
include("/var/www/html/header.php");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.1/jquery.validate.js"></script>
<div id="overlay" class="overlay">
	<div class="content center">
		<form id="lift-watch" class="lift new">
			<p>
				<label>Athlete: </label>
				<select name="athleteID" required>
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
						<option value="<?php echo $row['athlete_id']; ?>" data-athlete-name="<?php echo $row['athlete_first_name']." ".$row['athlete_last_name']; ?>"><?php echo $row['athlete_first_name']." ".$row['athlete_last_name']; ?></option>
					<?php
					endforeach;
					?>
				</select>
			</p>
			<input name="userID" type="hidden" value=<?php echo $_SESSION['userID']; ?>>
			<p>
				<button id="lift-watch-submit">Submit</button><br>
				<a href="<?php echo site_url(); ?>">Cancel</a>
			</p>
		</form>
	</div>
</div>
<div id="connect_string"></div>
<div id="data-container" class="data-container flexbox">
	<div class="tab">
		<span id="collarID" class="count-number"></span> <span class="count-text">collar</span>
	</div>
	<div class="tab">
		<span id="lift-type" class="count-number lift-type"></span> <span class="count-text">lift</span>
	</div>
	<div class="tab">
		<span id="lift-weight" class="count-number">0</span> <span class="count-text">lbs</span>
	</div>
	<div class="tab">
		<span id="rep-count" class="count-number">0</span> <span class="count-text">reps</span>
	</div>
	<div class="tab">
		<span id="active" class="count-number"></span> <span class="count-text">active</span>
	</div>
</div>
<h1><?php echo $title; ?></h1>
<div id="athleteID" style="display: none;"></div>
<div id="currently-watching">
	<p>
	Currently Watching: <span id="current-athlete-name">None</span>
	</p>
	<p>
		<a href="/admin/watch/">Select a different athlete</a>
	</p>
</div>
<div class="flexbox charts-container">
	<div id="chart_div" class="chart"></div>
	<div id="chart_column" class="chart"></div>
</div>
<script>
var connectDiv = document.getElementById("connect_string");
var gauge = document.getElementById("chart_div");
var athleteID = $('#athleteID').text();
connectDiv.innerHTML="attempting to establish connection...<br>";

connectDiv.innerHTML=location.port;

var conn = new WebSocket('ws://52.204.229.101:8080');
connectDiv.innerHTML="did something...<br>";
conn.onopen = function(e) {
    console.log("Connection established!");
    connectDiv.innerHTML="Connection successful<br>";
};

conn.onmessage = function(e) {
	athleteID = $('#athleteID').text();
	var values = JSON.parse(e.data);
	if (values.athleteID == athleteID) {
		console.log(e.data);
		connectDiv.innerHTML=e.data;
		updateGauge(values.velocity);
		updateColumn(values.power);
		updateReps(values.repCount);
		updateActive(values.active);
		updateCollarID(values.collarID);
		updateLiftType(values.liftType);
		updateLiftWeight(values.liftWeight);
	}
};
connectDiv.innerHTML="\nDone!";

$('form#lift-watch').validate();
</script>
<?php
include('/var/www/html/footer.php');
?>
