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
?>
<div style="display:none;"><pre><?php echo(json_encode($redisArray)); ?></pre></div>
<?php
//echo("/home/jbrubaker/anaconda2/envs/fitai/bin/python /var/opt/python/fitai_controller/comms/update_redis.py -v -j '".json_encode($redisArray)."'");
$redisReturn = exec("/home/jbrubaker/anaconda2/envs/fitai/bin/python /var/opt/python/fitai_controller/comms/update_redis.py -j '".json_encode($redisArray)."'", $returnExec);

if ($_REQUEST['dbPull']) :
	$redisReturn = exec("/home/jbrubaker/anaconda2/envs/fitai/bin/python /var/opt/python/fitai_controller/databasing/database_pull.py -l 1", $returnExec);
endif;

$json = '{"columns":["time", "a_rms","p_rms","v_rms"],"index":[0,1,2,3,4],"data":[[0, 0.12,0.01152,0.0024],[0.2, 0.21,0.05544,0.0066],[0.4, 0.12,0.0432,0.009],[0.6, 0.02,0.00752,0.0094],[0.8, 0.11,0.05104,0.0116]]}';

$array = json_decode($redisReturn, true);
?>
<div style="display:none;"><pre>RedisReturn:<br><?php var_dump($redisReturn); ?><br>returnExec:<br><?php var_dump($returnExec); ?></pre></div>
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
$calcReps = $fetch['calc_reps'];
$finalReps = $fetch['final_num_reps'];
$comments = $fetch['user_comment'];

//Set reps to be displayed
$reps = $calcReps;
if ($finalReps > 0) :
	$reps = $finalReps;
endif;
?>
<hr>
<div id="lift-data" class="lift-data">
	<div class="data-item">
		<h3 class="title">Exercise:</h3>
		<div id="lift-type" class="summary-item"><span id="summary-lift-type"><?php echo $exercise; ?></span> <span id="lift-type-edit" class="summary-edit" onClick="editSummary('lift-type');"><i class="dripicons-document-edit"></i></span></div>
	</div>
	<div class="data-item">
		<h3 class="title">Weight:</h3>
		<div id="weight" class="summary-item"><span id="summary-weight"><?php echo $weight; ?></span> <span id="weight-edit" class="summary-edit" onClick="editSummary('weight');"><i class="dripicons-document-edit"></i></span></div>
	</div>
	<div class="data-item">
		<h3 class="title">Reps:</h3>
		<div id="reps" class="summary-item"><span id="summary-reps"><?php echo $reps; ?></span> <span id="reps-edit" class="summary-edit" onClick="editSummary('reps');"><i class="dripicons-document-edit"></i></span></div>
	</div>
	<div class="data-item">
		<h3 class="title">Comments:</h3>
		<div id="comments" class="summary-item">
			<p id="summary-comments"><?php echo nl2br($comments); ?></p>
			<span id="comments-edit" class="summary-edit" onClick="editSummary('comments');"><i class="dripicons-document-edit"></i></span>
		</div>
	</div>
</div>
<hr>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
function editSummary(field) {
	var fieldValue = $('#summary-'+field).text();
	var fieldName; // declare variables for function
	var fieldHTML = '<input class="prev-input" name="' + fieldName + '" value="' + fieldValue + '">';
	var fieldButtons = '<button name="update" class="small summary-update" value="Update">Update</button><button name="cancel" class="small summary-cancel" value="' + fieldValue + '">Cancel</button>';
	if (field == 'reps') {
		fieldName = 'final_num_reps';
		fieldHTML = '<input class="prev-input" name="' + fieldName + '" value="' + fieldValue + '">'
	} else if (field == 'weight') {
		fieldName = 'lift_weight';
		fieldHTML = '<input class="prev-input" name="' + fieldName + '" value="' + fieldValue + '">'
	} else if (field == 'comments') {
		fieldName = 'user_comment';
		fieldHTML = '<textarea class="prev-input" name="' + fieldName + '">' + fieldValue + '</textarea>';
	} else if (field == 'lift-type') {
		fieldName = 'lift_type';
		fieldHTML = '<select class="prev-input" name="' + fieldName + '">';
		<?php 
		$type = get_lift_types();
		foreach ($type as $row) :
		?>
			fieldHTML += '<option value="<?php echo $row['name_display']; ?>"><?php echo $row['name_display']; ?></option>';
		<?php endforeach; ?> 
		fieldHTML += '</select>';
	}
	console.log('Editing: #' + field + ' Value: ' + fieldValue);	
	$('#'+field).html(fieldHTML + fieldButtons);
}
$(document).ready(function() {
	var liftID = <?php echo $liftID; ?>;
	$('.summary-item').on('click', '.summary-cancel', function() {
		var parentID = $(this).parent().attr('id');
		var defaultValue = $(this).attr('value');
		console.log('Cancel: ' + parentID);
		$(this).parent().html('<span id="summary-' + parentID + '">' + defaultValue + '</span> <span id="' + parentID + '-edit" class="summary-edit" onClick="editSummary(\'' + parentID + '\');"><i class="dripicons-document-edit"></i></span></div>');
	});
	$('.summary-item').on('click', '.summary-update', function() {
		var input = $(this).prev('.prev-input');
		var parentID = $(this).parent().attr('id');
		console.log('ParentID: ' + parentID);
		var updateValue = input.val();
		console.log('updatevalue: ' + updateValue);
		var fieldName = input.attr('name');
		console.log('fieldName: ' + fieldName);
		$.post('lift-check.php', { liftID: liftID, fieldName: fieldName, updateValue: updateValue }, function(data) {
			console.log('Response: ' + data);
			if (parentID == 'comments') {
				$('#' + parentID).html('<p id="summary-' + parentID + '">' + nl2br(updateValue) + '</p> <span id="' + parentID + '-edit" class="summary-edit" onClick="editSummary(\'' + parentID + '\');"><i class="dripicons-document-edit"></i></span></div>');
			} else {
				$('#' + parentID).html('<span id="summary-' + parentID + '">' + updateValue + '</span> <span id="' + parentID + '-edit" class="summary-edit" onClick="editSummary(\'' + parentID + '\');"><i class="dripicons-document-edit"></i></span></div>');
			}
		});
	});
});

  google.charts.load('current', {'packages':['line', 'corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function getColumns(columns) {
  	return columns['timepoint'];
  }

  function drawChart() {
	var jsonString = JSON.parse('<?php echo $redisReturn; ?>');
	console.log(jsonString);
	var columns = jsonString.columns;
	var coords = jsonString.data;
	var velocityData = new google.visualization.DataTable(); // Velocity Chart
	var powerData = new google.visualization.DataTable(); // Power Chart
	
	// Loop through columns
	/*for (var key in columns) {
	  if (columns.hasOwnProperty(key)) {
		var val = columns[key];
		data.addColumn('number', val);
	  }
	}*/
	
	// Velocity Columns
	velocityData.addColumn('number', columns['timepoint']);
	velocityData.addColumn('number', columns['v_x']);
	velocityData.addColumn('number', columns['v_y']);
	velocityData.addColumn('number', columns['v_z']);

	
	// Power Columns
	powerData.addColumn('number', columns['timepoint']);
	powerData.addColumn('number', columns['pwr_x']);
	powerData.addColumn('number', columns['pwr_y']);
	powerData.addColumn('number', columns['pwr_z']);
	
	// Get index of each value
	var timeIndex = columns.indexOf('timepoint');
	var velXindex = columns.indexOf('v_x');
	var velYindex = columns.indexOf('v_y');
	var velZindex = columns.indexOf('v_z');
	var pwrXindex = columns.indexOf('pwr_x');
	var pwrYindex = columns.indexOf('pwr_y');
	var pwrZindex = columns.indexOf('pwr_z');

	// Add values to rows
	for (var key in coords) {
		var time = coords[key][timeIndex];
		var vel_x = coords[key][velXindex];
		var vel_y = coords[key][velYindex];
		var vel_z = coords[key][velZindex];
		var pwr_x = coords[key][pwrXindex];
		var pwr_y = coords[key][pwrYindex];
		var pwr_z = coords[key][pwrZindex];
		velocityData.addRow([time, vel_x, vel_y, vel_z]);
		powerData.addRow([time, pwr_x, pwr_y, pwr_z]);
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
			  labelInLegend: 'Velocity X'
		  },
		  1: {
			  labelInLegend: 'Velocity Y'
		  },
		  2: {
			  labelInLegend: 'Velocity Z'
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
			  labelInLegend: 'Power X',
		  },
		  1: {
			  labelInLegend: 'Power Y',
		  },
		  2: {
			  labelInLegend: 'Power Z',
		  }
	  }
	};

	// Comobo chart options
	var comboOptions = {
	  chart: {
		  title: 'Combo',
		  subtitle: 'mixed'
	  },
	  vAxis: {title: 'Title'},
	  hAxis: {title: 'Title'},
	  legend: { position: 'bottom' },
	  explorer: { zoomDelta: 1.1 },
	  seriesType: 'bars',
	  series: {
		  5: {
			  labelInLegend: 'Power',
			  type: 'area',
		  }
	  }
	};

	var comboData = google.visualization.arrayToDataTable([
         ['Month', 'Bolivia', 'Ecuador', 'Madagascar', 'Papua New Guinea', 'Rwanda', 'Average'],
         ['2004/05',  165,      938,         522,             998,           450,      614.6],
         ['2005/06',  135,      1120,        599,             1268,          288,      682],
         ['2006/07',  157,      1167,        587,             807,           397,      623],
         ['2007/08',  139,      1110,        615,             968,           215,      609.4],
         ['2008/09',  136,      691,         629,             1026,          366,      569.6]
      ]);

	// Create Velocity chart
	var velocityChart = new google.charts.Line(document.getElementById('velocity_chart'));
	velocityChart.draw(velocityData, google.charts.Line.convertOptions(velocityOptions));
	
	// Create Power chart
	var powerChart = new google.charts.Line(document.getElementById('power_chart'));
	powerChart.draw(powerData, google.charts.Line.convertOptions(powerOptions));


    // Create Combo chart
	var comboChart = new google.visualization.ComboChart(document.getElementById('combo_chart'));
    comboChart.draw(comboData, comboOptions);
  }
</script>
<div id="velocity_chart" style="width: 100%; height: 500px"></div>
<div id="power_chart" style="width: 100%; height: 500px"></div>
<div id="combo_chart" style="width: 100%; height: 500px"></div>
<div class="lift-summary-links">
	<a href="/now/">Start A New Lift</a>
</div>
<?php
include('/var/www/html/footer.php');
?>
