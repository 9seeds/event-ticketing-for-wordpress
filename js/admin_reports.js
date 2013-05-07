google.load('visualization', '1', {packages:['gauge']});
google.setOnLoadCallback(drawChart);
function drawChart() {
	var data = google.visualization.arrayToDataTable(reportsL10n.data);

	var options = {
		width: 200, height: 200,
		redFrom: 90, redTo: 100,
		yellowFrom: 75, yellowTo: 90,
		minorTicks: 5
	};

	var chart = new google.visualization.Gauge(document.getElementById('pie_chart_div'));
	chart.draw(data, options);
}
