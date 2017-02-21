google.charts.load('current', {'packages':['corechart', 'gauge']});
google.charts.setOnLoadCallback(drawGauge);
google.charts.setOnLoadCallback(drawBar);

//Declare Global Variables
var data, options, chart, dataColumn, optionsColumn, chartColumn, chartContainerWidth;


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

// New Lift Form Submission
$( document ).ready(function() {
	$("#lift-new-submit").click(function(e) {
		e.preventDefault();
		var validate = $('form#lift-new').valid();
		if (validate == true) {
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
			var watchID = $('select[name=athleteID]').val();
			var athleteName = $('select[name=athleteID]').find(':selected').data('athlete-name');
			$('#athleteID').text(watchID);
			$('#current-athlete-name').text(athleteName);
			console.log('Now watching athleteID:' + watchID + '(' + athleteName + ')');
			$('#overlay').hide();
		}
	});
});

function liftNew() {
	var values = $('#lift-new').serializeArray();
}

