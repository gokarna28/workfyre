<?php

// $tasks = [
//   'A' => ['duration' => 3, 'dependencies' => []],
//   'B' => ['duration' => 2, 'dependencies' => ['A']],
//   'C' => ['duration' => 1, 'dependencies' => ['A']],
//   'D' => ['duration' => 4, 'dependencies' => ['B', 'C']],
// ];

// function criticalPath($tasks)
// {
//   // Step 1: Calculate Earliest Start and Finish times
//   $es = []; // Earliest Start
//   $ef = []; // Earliest Finish

//   function calcEarly($taskId, &$tasks, &$es, &$ef)
//   {
//     if (isset($es[$taskId]))
//       return;

//     $task = $tasks[$taskId];
//     $dependencies = $task['dependencies'];

//     if (empty($dependencies)) {
//       $es[$taskId] = 0;
//     } else {
//       foreach ($dependencies as $dep) {
//         calcEarly($dep, $tasks, $es, $ef);
//       }
//       $es[$taskId] = max(array_map(function ($d) use ($ef) {
//         return $ef[$d];
//       }, $dependencies));
//     }

//     $ef[$taskId] = $es[$taskId] + $task['duration'];
//   }

//   foreach (array_keys($tasks) as $taskId) {
//     calcEarly($taskId, $tasks, $es, $ef);
//   }

//   // Step 2: Calculate Latest Finish and Start times
//   $lf = [];
//   $ls = [];

//   // Find the project duration
//   $projectDuration = max($ef);

//   function calcLate($taskId, &$tasks, &$es, &$ef, &$ls, &$lf)
//   {
//     if (isset($lf[$taskId]))
//       return;

//     // Get successors of the task
//     $successors = [];
//     foreach ($tasks as $id => $task) {
//       if (in_array($taskId, $task['dependencies'])) {
//         $successors[] = $id;
//       }
//     }

//     if (empty($successors)) {
//       $lf[$taskId] = $ef[$taskId]; // Or projectDuration
//     } else {
//       foreach ($successors as $succ) {
//         calcLate($succ, $tasks, $es, $ef, $ls, $lf);
//       }
//       $lf[$taskId] = min(array_map(function ($s) use ($ls) {
//         return $ls[$s];
//       }, $successors));
//     }

//     $ls[$taskId] = $lf[$taskId] - $tasks[$taskId]['duration'];
//   }

//   foreach (array_keys($tasks) as $taskId) {
//     calcLate($taskId, $tasks, $es, $ef, $ls, $lf);
//   }

//   // Step 3: Determine slack and critical path
//   $criticalPath = [];

//   foreach ($tasks as $id => $task) {
//     $slack = $ls[$id] - $es[$id];
//     if ($slack === 0) {
//       $criticalPath[] = $id;
//     }
//     echo "<br>";
//     echo "Task $id: ES={$es[$id]}, EF={$ef[$id]}, LS={$ls[$id]}, LF={$lf[$id]}, Slack=$slack\n";
//   }
//   echo "<br>";

//   echo "\nCritical Path: " . implode(" → ", $criticalPath) . "\n";
// }

// // Sample input
// $tasks = [
//   'A' => ['duration' => 3, 'dependencies' => []],
//   'B' => ['duration' => 2, 'dependencies' => ['A']],
//   'C' => ['duration' => 1, 'dependencies' => ['A']],
//   'D' => ['duration' => 4, 'dependencies' => ['B', 'C']],
// ];

// criticalPath($tasks);




function calculateCriticalPath($tasks)
{
  $taskMap = [];
  foreach ($tasks as $task) {
    $taskMap[$task['id']] = $task;
  }

  // Step 1: Forward Pass (ES and EF)
  $esEf = [];
  function forwardPass($taskId, $taskMap, &$esEf)
  {
    $task = $taskMap[$taskId];
    if (empty($task['predecessors'])) {
      $es = 0;
    } else {
      $maxEf = 0;
      foreach ($task['predecessors'] as $preId) {
        if (!isset($esEf[$preId])) {
          forwardPass($preId, $taskMap, $esEf);
        }
        $maxEf = max($maxEf, $esEf[$preId]['ef']);
      }
      $es = $maxEf;
    }
    $ef = $es + $task['duration'];
    $esEf[$taskId] = ['es' => $es, 'ef' => $ef];
  }

  foreach ($tasks as $task) {
    forwardPass($task['id'], $taskMap, $esEf);
  }

  // Step 2: Backward Pass (LS and LF)
  $lfLs = [];
  $maxEf = max(array_column($esEf, 'ef'));

  function backwardPass($taskId, $taskMap, &$lfLs, $esEf)
  {
    $task = $taskMap[$taskId];
    $successors = [];
    foreach ($taskMap as $otherTask) {
      if (in_array($taskId, $otherTask['predecessors'])) {
        $successors[] = $otherTask['id'];
      }
    }

    if (empty($successors)) {
      $lf = $maxEf = max(array_column($esEf, 'ef'));
    } else {
      $minLs = INF;
      foreach ($successors as $succId) {
        if (!isset($lfLs[$succId])) {
          backwardPass($succId, $taskMap, $lfLs, $esEf);
        }
        $minLs = min($minLs, $lfLs[$succId]['ls']);
      }
      $lf = $minLs;
    }

    $ls = $lf - $task['duration'];
    $lfLs[$taskId] = ['lf' => $lf, 'ls' => $ls];
  }

  $reversedTasks = array_reverse($tasks);
  foreach ($reversedTasks as $task) {
    backwardPass($task['id'], $taskMap, $lfLs, $esEf);
  }

  // Step 3: Combine all and calculate slack
  $results = [];
  foreach ($tasks as $task) {
    $id = $task['id'];
    $es = $esEf[$id]['es'];
    $ef = $esEf[$id]['ef'];
    $ls = $lfLs[$id]['ls'];
    $lf = $lfLs[$id]['lf'];
    $slack = $ls - $es;
    $results[$id] = [
      'es' => $es,
      'ef' => $ef,
      'ls' => $ls,
      'lf' => $lf,
      'slack' => $slack,
      'critical' => $slack === 0
    ];
  }

  return $results;
}



$tasks = [
  ['id' => 'A', 'duration' => 3, 'predecessors' => []],
  ['id' => 'B', 'duration' => 2, 'predecessors' => ['A']],
  ['id' => 'C', 'duration' => 1, 'predecessors' => ['A']],
  ['id' => 'D', 'duration' => 4, 'predecessors' => ['B', 'C']],
];

$results = calculateCriticalPath($tasks);
echo "<pre>";
 var_dump($results);
echo "<pre>";

foreach ($results as $id => $info) {
  echo "Task $id: ES={$info['es']}, EF={$info['ef']}, LS={$info['ls']}, LF={$info['lf']}, Slack={$info['slack']}";
  echo $info['critical'] ? " (Critical)" : "";
  echo "<br>";
}

?>










<!DOCTYPE html>
<html>

<head>
  <script type="module">
    import mermaid from 'https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.esm.min.mjs';
    mermaid.initialize({ startOnLoad: true });
  </script>
</head>

<body>

  <div class="mermaid">
    graph TD
    A[Task A<br />Duration: 3] --> B[Task B<br />Duration: 2]
    A --> C[Task C<br />Duration: 1]
    B --> D[Task D<br />Duration: 4]
    C --> D
  </div>

</body>

</html>
<!-- 
<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['gantt']});
      google.charts.setOnLoadCallback(drawChart);

      function daysToMilliseconds(days) {
        return days * 24 * 60 * 60 * 1000;
      }

      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Task ID');
        data.addColumn('string', 'Task Name');
        data.addColumn('string', 'Resource');
        data.addColumn('date',   'Start Date');
        data.addColumn('date',   'End Date');
        data.addColumn('number', 'Duration');
        data.addColumn('number', 'Percent Complete');
        data.addColumn('string', 'Dependencies');

        data.addRows([
          ['Create',  'Create outline',  'Critical', new Date(2025, 9, 8), new Date(2025, 9, 9), null, 100, null],
          ['Write',   'Write draft',     'Critical', new Date(2025, 9, 9), new Date(2025, 9, 15), null, 100, 'Create'],
          ['Edit',    'Edit and create final draft', 'Critical', new Date(2025, 9, 15), new Date(2025, 9, 18), null, 100, 'Write'],
          ['Finalize','Finalize and approve final draft', 'Critical', new Date(2025, 9, 18), new Date(2025, 9, 22), null, 0, 'Edit'],
          ['Upload',  'Upload blog',     'Critical', new Date(2025, 9, 22), new Date(2025, 9, 25), null, 0, 'Finalize'],
          
          ['Visuals', 'Design blog visuals', 'Non-critical', new Date(2025, 9, 15), new Date(2025, 9, 21), null, 0, null],
          ['Anim',    'Add animations to visuals', 'Non-critical', new Date(2025, 9, 21), new Date(2025, 9, 25), null, 0, 'Visuals'],
        ]);

        var options = {
          height: 400,
          gantt: {
            criticalPathEnabled: true,
            criticalPathStyle: {
              stroke: '#e64a19',
              strokeWidth: 3
            },
            labelStyle: {
              fontName: "Arial",
              fontSize: 14,
              color: '#555'
            }
          }
        };

        var chart = new google.visualization.Gantt(document.getElementById('gantt_chart'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <h2>Blog Workflow Gantt Chart (Critical Path Highlighted)</h2>
    <div id="gantt_chart" style="width: 100%; height: 500px;"></div>
  </body>
</html> -->

<!DOCTYPE html>
<html>

<head>
  <title>Critical Path Gantt Chart</title>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
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
  </script>
</head>

<body>
  <h2>Gantt Chart with Critical Path</h2>
  <div id="chart_div"></div>
</body>

</html>