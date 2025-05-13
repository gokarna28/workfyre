google.charts.load('current', { 'packages': ['gantt'] });
google.charts.setOnLoadCallback(drawChart);

function daysToMilliseconds(days) {
  return days * 24 * 60 * 60 * 1000;
}

function drawChart() {
  var data = new google.visualization.DataTable();

  data.addColumn('string', 'Task ID');
  data.addColumn('string', 'Task Name');
  data.addColumn('string', 'Resource');
  data.addColumn('date', 'Start Date');
  data.addColumn('date', 'End Date');
  data.addColumn('number', 'Duration');
  data.addColumn('number', 'Percent Complete');
  data.addColumn('string', 'Dependencies');

  // Task info from PHP structure and critical path: A -> B -> D
  data.addRows([
    ['A', 'Task A', 'critical', new Date(2025, 4, 1), new Date(2025, 4, 4), null, 100, null],
    ['B', 'Task B', 'critical', new Date(2025, 4, 4), new Date(2025, 4, 6), null, 100, 'A'],
    ['C', 'Task C', 'non-critical', new Date(2025, 4, 4), new Date(2025, 4, 5), null, 100, 'A'],
    ['D', 'Task D', 'critical', new Date(2025, 4, 6), new Date(2025, 4, 10), null, 100, 'B,C']
  ]);

  var options = {
    height: 300,
    gantt: {
      criticalPathEnabled: true,
      criticalPathStyle: {
        stroke: '#e64a19',
        strokeWidth: 5
      },
      defaultStartDate: new Date(2025, 4, 1)
    }
  };

  var chart = new google.visualization.Gantt(document.getElementById('chart_div'));

  chart.draw(data, options);
}