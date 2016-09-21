//google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawVisualization);

var dataColumn, optionsColumn;

function drawVisualization() {
	// Some raw data (not necessarily accurate)
	dataColumn = google.visualization.arrayToDataTable([
         ['Athlete', 'Kyle'],
         ['Now',  165]
      ]);

    optionsColumn = {
      title : 'Power',
      vAxis: {title: 'Power (mW)'},
      hAxis: {title: 'Athlete'},
      seriesType: 'bars'
    };

    var chart = new google.visualization.ComboChart(document.getElementById('chart_column'));
    chart.draw(dataColumn, optionsColumn);
}

function updateColumn(a) {
  dataColumn.setValue(0, 1, a);
  chart.draw(dataColumn, optionsColumn);
}