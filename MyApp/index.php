<?php
$title = "Now";
include("/var/www/html/header-app.php");
?>
<div id="reset-reps" class="reset-reps" onclick="resetReps();">Reset Reps</div>
<div id="connect_string"></div>
<div id="data-container" class="data-container flexbox">
	<div class="tab">
		<span id="rep-count" class="count-number">0</span> <span class="count-text">reps</span>
	</div>
</div>
<div class="flexbox charts-container">
	<div id="chart_div" class="chart"></div>
	<div id="chart_column" class="chart"></div>
</div>
<script>
var connectDiv = document.getElementById("connect_string");
var gauge = document.getElementById("chart_div");
connectDiv.innerHTML="attempting to establish connection...<br>";

connectDiv.innerHTML=location.port;

var conn = new WebSocket('ws://52.204.229.101:8080');
connectDiv.innerHTML="did something...<br>";
conn.onopen = function(e) {
    console.log("Connection established!");
    connectDiv.innerHTML="Connection successful<br>";
};

conn.onmessage = function(e) {
    console.log(e.data);
    connectDiv.innerHTML=e.data;
	var values = JSON.parse(e.data);
	updateGauge(values.velocity);
	updateColumn(values.power);
	updateReps(values.repCount);
};
connectDiv.innerHTML="\nDone!";

//Reset Reps
function resetReps() {
	$.ajax({
		url: "reset-reps.php"
	}).done(function(e) {
		console.log(e);
		updateReps('0');
	});
}
</script>
<?php
include("/var/www/html/footer.php");
?>
