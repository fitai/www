google.charts.load('current', {'packages':['corechart', 'gauge', 'line']});
google.charts.setOnLoadCallback(drawGauge);
google.charts.setOnLoadCallback(drawBar);
google.charts.setOnLoadCallback(drawLine);

//Declare Global Variables
var data, options, chart, dataColumn, optionsColumn, chartColumn, chartContainerWidth, velocityChart, velocityOptions, velocityData;

//Gauge
var majorValues = [];
for (var i = 0; i <= 10; i++) {
	majorValues.push(i);
}

function drawGauge() {
	var dynamicWidth;
	chartContainerWidth = $('.charts-container').width();
	
	//Check viewport width and set width of gauge
	if (chartContainerWidth <= 600)
		dynamicWidth = chartContainerWidth * 0.8;
	else 
		dynamicWidth = chartContainerWidth / 2;
	
	
	if (dynamicWidth > 400)
		dynamicWidth = 400;
	
	data = google.visualization.arrayToDataTable([
	  ['Label', 'Value'],
	  ['Velocity', 0],
	]);
	options = {
	  width: dynamicWidth, height: dynamicWidth,
	  redColor: '#FF2C00', redFrom: 9, redTo: 10,
	  yellowColor: '#FFB600', yellowFrom:7, yellowTo: 9,
	  majorTicks: majorValues, minorTicks: 2,
	  min: 0, max: 10
	};

	chart = new google.visualization.Gauge(document.getElementById('chart_div'));

	chart.draw(data, options);
}

function updateGauge(a) {
  data.setValue(0, 1, a);
  chart.draw(data, options);
}

//Bar Chart
function drawBar() {
	// Some raw data (not necessarily accurate)
	dataColumn = google.visualization.arrayToDataTable([
         ['Athlete', 'Kyle'],
         ['Now',  0]
      ]);

    optionsColumn = {
      title : 'Power',
	  titlePosition: 'none',
      vAxis: {title: 'Power (mW)', maxValue: 250, ticks: [0, 50, 100, 150, 200, 250]},
      hAxis: {title: 'Time'},
      seriesType: 'bars',
	  series: {0: { color: '#00aeef'} },
	  animation: {easing: 'in', duration: '300'},
	  legend: {position: 'none'}
    };

    chartColumn = new google.visualization.ComboChart(document.getElementById('chart_column'));
    chartColumn.draw(dataColumn, optionsColumn);
}

function updateColumn(a) {
  dataColumn.setValue(0, 1, a);
  chartColumn.draw(dataColumn, optionsColumn);
}

// Line Chart

//Create variables for Line Chart
var lineColumns = [];
var timestamps = [];
var velocity = [];
function drawLine() {
	// var jsonString = JSON.parse('<?php echo $redisReturn; ?>');
	// var columns = jsonString.columns;
	// var coords = jsonString.data;
	velocityData = new google.visualization.DataTable();
	timestamps.push(getCurrentTime());
	velocity.push(0);
	// var powerData = new google.visualization.DataTable();
	
	// Loop through columns
	/*for (var key in columns) {
	  if (columns.hasOwnProperty(key)) {
		var val = columns[key];
		data.addColumn('number', val);
	  }
	}*/
	
	// Velocity Columns
	velocityData.addColumn('string', 'Timestamp');
	velocityData.addColumn('number', 'Velocity');

	velocityData.addRow([getCurrentTime(), 0]);
	
	// Power Columns
	// powerData.addColumn('number', columns['timepoint']);
	// powerData.addColumn('number', columns['p_rms']);
	
	// // Add values to rows
	// for (var key in coords) {
	// 	var time = coords[key][2];
	// 	var velocity = coords[key][3];
	// 	var power = coords[key][1];
	// 	velocityData.addRow([time, velocity]);
	// 	// powerData.addRow([time, power]);
	// }
	
	// Velocity chart options
	velocityOptions = {
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
	// var powerOptions = {
	//   chart: {
	// 	  title: 'Power',
	// 	  subtitle: 'in m/s^2'
	//   },
	//   legend: { position: 'bottom' },
	//   explorer: { zoomDelta: 1.1 },
	//   series: {
	// 	  0: {
	// 		  labelInLegend: 'Power'
	// 	  }
	//   }
	// };

	// Create Velocity chart
	velocityChart = new google.charts.Line(document.getElementById('velocity_chart'));
	velocityChart.draw(velocityData, google.charts.Line.convertOptions(velocityOptions));
	
	// // Create Power chart
	// var powerChart = new google.charts.Line(document.getElementById('power_chart'));
	// powerChart.draw(powerData, google.charts.Line.convertOptions(powerOptions));
}
function updateLine(a) {
  velocityData.addRow([getCurrentTime(), a]);
  velocityChart.draw(velocityData, google.charts.Line.convertOptions(velocityOptions));
}

//Rep Count
function updateReps(a) {
	$("#rep-count").html(a);
}

// Update Lift Weight
function updateLiftWeight(a) {
	$("#lift-weight").html(a);
}

// Update Lift Type
function updateLiftType(a) {
	$("#lift-type").html(a);
}

// Update Collar ID
function updateCollarID(a) {
	$("#collarID").html(a);
}

// Update Active
function updateActive(a) {
	$("#active").html(a);
}

// Update Lift ID
function updateLiftID(a) {
	$("#lift-id").html(a);
}

// Get time in 24hr format
function addZero(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}
function getCurrentTime() {
    var d = new Date();
    var h = addZero(d.getHours());
    var m = addZero(d.getMinutes());
    var s = addZero(d.getSeconds());
    var time = h + ":" + m + ":" + s;
    return time;
}

// New Lift Form Submission
$( document ).ready(function() {
	$("#lift-new-submit").click(function(e) {
		e.preventDefault();
		console.log('Submitting lift data...');
		var validate = $('form#lift-new').valid();
		if (validate == true) {
			console.log('Form validation successful...');
			var formData = $('form#lift-new').serialize();
			$.post('lift-new.php', formData, function(data) {
				console.log(data);
				var parsed = $.parseJSON(data);
				var functions = parsed.functions;
				var liftID = parsed.liftID;
				console.log('Lift ID: '+liftID);
				$('#liftID').text(liftID);
				console.log(functions);
				$.each(functions, function(key,value) {
					eval(value);
				});
				$('#overlay').hide();
			});
		}
	});
	
	// End lift Operations
	$("#end-lift").click(function(e) {
		e.preventDefault();
		var collarID = $('#collarID').text();
		var liftID = $('#liftID').text();
		/*$.post('lift-stop.php', { "collarID": collarID } , function(data) {
			console.log(data);
		});*/
		window.location.href = "summary/?collarID="+collarID+"&liftID="+liftID;
	});
});

// Admin Watch Form Submission
$( document ).ready(function() {
	$("#lift-watch-submit").click(function(e) {
		e.preventDefault();
		var validate = $('form#lift-watch').valid();
		if (validate == true) {
			$('#watchSelector').attr('data-val', 'athlete');
			var watchID = $('select[name=athleteID]').val();
			var athleteName = $('select[name=athleteID]').find(':selected').data('athlete-name');
			$('#athleteID').text(watchID);
			$('#current-athlete-name').text(athleteName);
			console.log('Now watching athleteID:' + watchID + '(' + athleteName + ')');
			$('#overlay').hide();
		}
	});
	$("#lift-watch-by-ID-submit").click(function(e) {
		e.preventDefault();
		var validate = $('form#lift-watch-by-ID').valid();
		if (validate == true) {
			$('#watchSelector').attr('data-val', 'collar');
			var watchID = $('select[name=collarID]').val();
			$('#athleteID').text(watchID);
			$('#current-athlete-name').text('Collar ' + watchID);
			console.log('Now watching collarID:' + watchID);
			$('#overlay').hide();
		}
	});
});

function liftNew() {
	var values = $('#lift-new').serializeArray();
}

