<?php
$title = "Now";
include("/var/www/html/header.php");
?>
<div id="overlay" class="overlay">
	<div class="content center">
		<form id="lift-new" class="lift new">
			<p>
				<label>Collar: </label>
				<select name="collarID" required>
					<option value="FAI01">FAI01</option>
					<option value="FAI02">FAI02</option>
				</select>
			</p>
			<p>
				<label>Type: </label>
				<select name="lift-type" required>
					<option>Bench</option>
					<option>Squat</option>
				</select>
			</p>
			<p>
				<label>Weight: </label>
				<input name="lift-weight" type="number" required>
			</p>
			<p>
				<label>Reps: </label>
				<input name="lift-reps" type="number">
			</p>
			<input name="userID" type="hidden" value=<?php echo $_SESSION['userID']; ?>>
			<p>
				<button id="lift-new-submit">Submit</button><br>
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
		<span id="lift-type" class="count-number"></span> <span class="count-text">lift</span>
	</div>
	<div class="tab">
		<span id="lift-weight" class="count-number">0</span> <span class="count-text">lbs</span>
	</div>
	<div class="tab">
		<span id="rep-count" class="count-number">0</span> <span class="count-text">reps</span>
	</div>
</div>
<h1><?php echo $title; ?></h1>
<div class="flexbox charts-container">
	<div id="chart_div" class="chart"></div>
	<div id="chart_column" class="chart"></div>
</div>
<script>
var connectDiv = document.getElementById("connect_string");
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
		updateColumn(values.power);
		updateReps(values.repCount);
	}
};
connectDiv.innerHTML="\nDone!";
</script>
<?php
include('/var/www/html/footer.php');
?>
