<?php
?>
<!DOCTYPE>
<html>
<head>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
	<div id="chart_div" style="width: 600px; height: 600px;"></div>
	<script>
		google.charts.load('current', {'packages':['gauge']});
		google.charts.setOnLoadCallback(drawChart);
		
		var majorValues = [];
		for (var i = 0; i <= 10; i++) {
			majorValues.push(i);
		}
		var data, options, chart;
		
		
		function drawChart() {
	
			data = google.visualization.arrayToDataTable([
			  ['Label', 'Value'],
			  ['Velocity', 0],
			]);
			options = {
			  width: 600, height: 600,
			  redFrom: 9, redTo: 10,
			  yellowFrom:7, yellowTo: 9,
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
		
		// Random float between
		function randomFloatBetween(minValue,maxValue,precision){
			if(typeof(precision) == 'undefined'){
				precision = 2;
			}
			return parseFloat(Math.min(minValue + (Math.random() * (maxValue - minValue)),maxValue).toFixed(precision));
		}
	
	</script>
</body>
</html>