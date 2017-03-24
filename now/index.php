<?php
$title = "Now";
include("/var/www/html/header.php");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.1/jquery.validate.js"></script>
<div id="overlay" class="overlay">
	<div class="content center">
		<form id="lift-new" class="lift new">
			<p>
				<label>Collar: </label>
				<select name="collarID" required>
					<?php 
					$collars = get_team_collars();
					foreach ($collars as $row) :
					?>
						<option value="<?php echo $row['collar_id']; ?>"><?php echo $row['collar_id']; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>
				<label>Type: </label>
				<select name="lift-type" required>
					<?php 
					$type = get_lift_types();
					foreach ($type as $row) :
					?>
						<option value="<?php echo $row['name_display']; ?>"><?php echo $row['name_display']; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>
				<label>Weight: </label>
				<input name="liftWeight" type="number" min="1" required>
			</p>
			<p>
				<label>Reps: </label>
				<input name="liftReps" type="number" min="1" required>
			</p>
			<input name="userID" type="hidden" value=<?php echo $_SESSION['userID']; ?>>
			<p>
				<button id="lift-new-submit">Submit</button><br>
				<a href="<?php echo site_url(); ?>">Cancel</a>
			</p>
		</form>
	</div>
</div>
<div id="end-lift" class="reset-reps end-lift">End Lift</div>
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
<div class="flexbox charts-container">
	<div id="chart_div" class="chart"></div>
	<div id="chart_column" class="chart"></div>
</div>
<div id="liftID" class="hidden">
</div>
<script>
var connectDiv = document.getElementById("connect_string");!
var gauge = document.getElementById("chart_div");
var athleteID = "<?php echo get_athlete_id($_SESSION['userID']); ?>";
connectDiv.innerHTML="attempting to establish connection...<br>";

connectDiv.innerHTML=location.port;

var conn = new WebSocket('ws://52.204.229.101:8080');
connectDiv.innerHTML="did something...<br>";
conn.onopen = function(e) {
    console.log("Connection established!");
    connectDiv.innerHTML="Connection successful<br>";
};

conn.onmessage = function(e) {
	var values = JSON.parse(e.data);
	if (values.athleteID == athleteID) {
		console.log(e.data);
		connectDiv.innerHTML=e.data;
		updateGauge(values.velocity);
		updateLine(values.velocity);
		updateColumn(values.power);
		updateReps(values.repCount);
		updateActive(values.active);
	}
};
connectDiv.innerHTML="\nDone!";

$('form#lift-new').validate();
</script>
<div id="velocity_chart" style="width: 100%; height: 500px"></div>
<?php
include('/var/www/html/footer.php');
?>
