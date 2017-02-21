<?php
$title = "Lift Summary";
include("/var/www/html/header.php");

$returnExec = "";
$collarID = $_REQUEST['collarID'];
$liftID = $_REQUEST['liftID'];
$redisArray = array(
				"collar_id" => $collarID,
				"active" => false
				);
//echo("/home/jbrubaker/anaconda2/envs/fitai/bin/python /var/opt/python/fitai_controller/comms/update_redis.py -v -j '".json_encode($redisArray)."'");
$redisReturn = exec("/home/jbrubaker/anaconda2/envs/fitai/bin/python /var/opt/python/fitai_controller/comms/update_redis.py -v -j '".json_encode($redisArray)."'", $returnExec);

if ($_REQUEST['dbPull']) :
	$redisReturn = exec("/home/jbrubaker/anaconda2/envs/fitai/bin/python /var/opt/python/fitai_controller/databasing/database_pull.py -l 0", $returnExec);
endif;
?>
<div id="liftCheckContainer">
	<p>
		<a id="liftError" style="cursor: pointer;">Click here to correct lift data or leave a comment</a>
	</p>
	<form id="liftCheck" style="display: none;">
		<h3>Please confirm your lift info for accuracy:</h3>
		<p>
			<label>Actual Reps Performed:</label><br>
			<input name="liftRepsActual" type="number" min="0" required>
		</p>
		<p>
			<label>Comments:</label><br>
			<textarea name="liftComments" style="width: 100%; min-height:200px;"></textarea>
		</p>
		<input type="hidden" name="liftID" value="<?php echo $liftID; ?>">
		<button id="submitCheck">Submit</button>
		<hr>
	</form>
	<div id="liftCheckResponse"></div>
</div>
<script>
$("#liftError").click(function(e) {
	e.preventDefault();
	$('#liftCheck').show();
	$(this).hide();
});
$("#submitCheck").click(function(e) {
	e.preventDefault();
	//var validate = $('form#liftCheck').valid();
	//if (validate == true) {
		var formData = $('form#liftCheck').serialize();
		$.post('lift-check.php', formData, function(data) {
			$('#liftCheck').hide();
			$('#liftCheckResponse').text(data);
		});
	//}
});
</script>
<?php
$json = '{"columns":["time", "a_rms","p_rms","v_rms"],"index":[0,1,2,3,4],"data":[[0, 0.12,0.01152,0.0024],[0.2, 0.21,0.05544,0.0066],[0.4, 0.12,0.0432,0.009],[0.6, 0.02,0.00752,0.0094],[0.8, 0.11,0.05104,0.0116]]}';

$array = json_decode($redisReturn, true);
?>
<?php /* <pre>Array:<br><?php print_r($returnExec[7]); ?></pre> */ ?>
<h1><?php echo $title; ?></h1>
<?php
$query = $myPDO->prepare("
		SELECT * 
		FROM athlete_lift
		WHERE lift_id=:lift_id
	");
$query->bindParam(':lift_id', $liftID, PDO::PARAM_STR);
$result = $query->execute();
$fetch = $query->fetch(PDO::FETCH_ASSOC);
$exercise = $fetch['lift_type'];
$weight = $fetch['lift_weight'];
$reps = $fetch['init_num_reps'];
?>
<hr>
<div id="lift-data">
	<p>Exercise: <?php echo $exercise; ?></p>
	<p>Weight: <?php echo $weight; ?></p>
	<p>Reps: <?php echo $reps; ?></p>
</div>
<hr>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['line']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
	var jsonString = JSON.parse('<?php echo $returnExec[7]; ?>');
	var columns = jsonString.columns;
	var coords = jsonString.data;
	var velocityData = new google.visualization.DataTable();
	var powerData = new google.visualization.DataTable();
	
	// Loop through columns
	/*for (var key in columns) {
	  if (columns.hasOwnProperty(key)) {
		var val = columns[key];
		data.addColumn('number', val);
	  }
	}*/
	
	// Velocity Columns
	velocityData.addColumn('number', columns['timepoint']);
	velocityData.addColumn('number', columns['v_rms']);
	
	// Power Columns
	powerData.addColumn('number', columns['timepoint']);
	powerData.addColumn('number', columns['p_rms']);
	
	// Add values to rows
	for (var key in coords) {
		var time = coords[key][2];
		var velocity = coords[key][3];
		var power = coords[key][1];
		velocityData.addRow([time, velocity]);
		powerData.addRow([time, power]);
	}
	
	// Velocity chart options
	var velocityOptions = {
	  chart: {
		  title: 'Velocity',
		  subtitle: 'in m/s^2'
	  },
	  legend: { position: 'bottom' },
	  explorer: { zoomDelta: 1.1 },
	  series: {
		  0: {
			  labelInLegend: 'Velocity'
		  }
	  }
	};
	
	// Power chart options
	var powerOptions = {
	  chart: {
		  title: 'Power',
		  subtitle: 'in m/s^2'
	  },
	  legend: { position: 'bottom' },
	  explorer: { zoomDelta: 1.1 },
	  series: {
		  0: {
			  labelInLegend: 'Power'
		  }
	  }
	};

	// Create Velocity chart
	var velocityChart = new google.charts.Line(document.getElementById('velocity_chart'));
	velocityChart.draw(velocityData, google.charts.Line.convertOptions(velocityOptions));
	
	// Create Power chart
	var powerChart = new google.charts.Line(document.getElementById('power_chart'));
	powerChart.draw(powerData, google.charts.Line.convertOptions(powerOptions));
  }
</script>
<div id="velocity_chart" style="width: 100%; height: 500px"></div>
<div id="power_chart" style="width: 100%; height: 500px"></div>
<div class="lift-summary-links">
	<a href="/now/">Start A New Lift</a>
</div>
<?php
include('/var/www/html/footer.php');
?>
