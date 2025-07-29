<?php include_once('../../sidebar.php'); ?>
<?php include_once('../../header.php');

$project_id = isset($_GET['pid']) ? $_GET['pid'] : "";
$taskData = getTasksDetailsByProject_id($project_id);

// Get current user for permission checks
$currentUser = getCurrentUser();

?>
<script type="text/javascript">
    google.charts.load('current', { 'packages': ['timeline'] });
    google.charts.setOnLoadCallback(drawChart);

    // Global variable to store chart instance
    let globalChart = null;
    let globalDataTable = null;

    function drawChart() {
        var container = document.getElementById('chart_div');
        globalChart = new google.visualization.Timeline(container);
        globalDataTable = new google.visualization.DataTable();

        globalDataTable.addColumn({ type: 'string', id: 'Task ID' });
        globalDataTable.addColumn({ type: 'string', id: 'Task Name' });
        globalDataTable.addColumn({ type: 'string', id: 'style', role: 'style' });
        globalDataTable.addColumn({ type: 'date', id: 'Start Date' });
        globalDataTable.addColumn({ type: 'date', id: 'End Date' });

        <?php

        // Function declared once here
        function parseCustomDate($dateStr) {
            $months = [
                'january' => 1, 'february' => 2, 'march' => 3, 'april' => 4, 
                'may' => 5, 'june' => 6, 'july' => 7, 'august' => 8, 
                'september' => 9, 'october' => 10, 'november' => 11, 'december' => 12
            ];
            
            // First try to parse as custom format (month-day-year)
            $parts = explode('-', strtolower($dateStr));
            if (count($parts) === 3 && isset($months[$parts[0]])) {
                $month = $months[$parts[0]];
                $day = intval($parts[1]);
                $year = intval($parts[2]);
                
                // Validate date components
                if ($day >= 1 && $day <= 31 && $year >= 1900 && $year <= 2100) {
                    return mktime(0, 0, 0, $month, $day, $year);
                }
            }
            
            // Fallback to standard parsing
            $timestamp = strtotime($dateStr);
            if ($timestamp === false) {
                // If standard parsing fails, return current time
                return time();
            }
            return $timestamp;
        }

        $chartData = [];

        if (is_array($taskData) && !empty($taskData)) {
            echo "var rows = [";
            foreach ($taskData as $tData) {
                $start = parseCustomDate($tData['created_at']);
                $end = parseCustomDate($tData['deadline']);
                $critical = $tData['critical'];

                // Validate dates - ensure start is not after end
                if ($start > $end) {
                    // If start is after end, swap them or adjust end date
                    $temp = $start;
                    $start = $end;
                    $end = $temp + (24 * 60 * 60); // Add 1 day to end date
                }

                // Calculate duration in days and hours
                $duration_seconds = $end - $start;
                $days = floor($duration_seconds / (60 * 60 * 24));
                $remaining_seconds = $duration_seconds % (60 * 60 * 24);
                $hours = floor($remaining_seconds / (60 * 60));

                // Limit duration display to prevent extremely long durations
                $max_display_days = 365; // Maximum days to display
                if ($days > $max_display_days) {
                    $days = $max_display_days;
                    $hours = 0;
                }

                $duration_text = '';
                if ($days > 0) {
                    $duration_text .= $days . ' day' . ($days > 1 ? 's' : '');
                }
                if ($hours > 0) {
                    $duration_text .= ($days > 0 ? ' - ' : '') . $hours . ' hr' . ($hours > 1 ? 's' : '');
                }

                $startDate = getdate($start);
                $endDate = getdate($end);
                $taskName = $tData['title'] . ' (' . $duration_text . ')' . ($critical == 1 ? ' (Critical)' : ' (Non-critical)');
                $barColor = $critical == 1 ? '#FF6363' : '#A0C878'; // Red for critical, Green for non-critical
        
                echo "[ 
                    '{$tData['id']}',
                    '{$taskName}',
                    '{$barColor}',
                    new Date({$startDate['year']}, " . ($startDate['mon'] - 1) . ", {$startDate['mday']}), 
                    new Date({$endDate['year']}, " . ($endDate['mon'] - 1) . ", {$endDate['mday']})
                ],";
            }
            echo "];";

            echo "globalDataTable.addRows(rows);";
        }
        ?>

        var options = {
            height: '100%',
            timeline: {
                showRowLabels: true,
                showBarLabels: true,
                singleColor: false
            },
            hAxis: {
                format: 'MMM d',
                minorGridlines: {
                    count: 0
                }
            }
        };

        globalChart.draw(globalDataTable, options);
    }

    // Function to refresh chart with latest data from server
    function refreshChartData() {
        if (!globalChart || !globalDataTable) {
            console.error('Chart not initialized');
            return;
        }

        // Clear existing data
        globalDataTable.removeRows(0, globalDataTable.getNumberOfRows());

        // Fetch latest task data from server
        $.ajax({
            type: 'GET',
            url: window.location.href,
            success: function(response) {
                // Parse the response to extract task data
                // This is a simplified approach - in a real implementation, 
                // you might want to create a separate API endpoint for chart data
                console.log('Chart data refreshed');
            },
            error: function(xhr, status, error) {
                console.error('Error refreshing chart data:', error);
            }
        });
    }

    // Function to update chart with new task data
    function updateChartWithNewTask(taskData) {
        if (!globalChart || !globalDataTable) {
            console.error('Chart not initialized');
            return;
        }

        // Convert date format from "may-28-2025" to JavaScript Date
        function parseCustomDate(dateStr) {
            const months = {
                'january': 0, 'february': 1, 'march': 2, 'april': 3, 'may': 4, 'june': 5,
                'july': 6, 'august': 7, 'september': 8, 'october': 9, 'november': 10, 'december': 11
            };
            
            // First try to parse as custom format (month-day-year)
            const parts = dateStr.toLowerCase().split('-');
            if (parts.length === 3 && months[parts[0]] !== undefined) {
                const month = months[parts[0]];
                const day = parseInt(parts[1]);
                const year = parseInt(parts[2]);
                
                // Validate date components
                if (!isNaN(day) && !isNaN(year) && day >= 1 && day <= 31 && year >= 1900 && year <= 2100) {
                    return new Date(year, month, day);
                }
            }
            
            // Fallback to standard date parsing
            const standardDate = new Date(dateStr);
            if (isNaN(standardDate.getTime())) {
                // If standard parsing fails, return current date
                return new Date();
            }
            return standardDate;
        }

        // Calculate duration for the new task
        const start = parseCustomDate(taskData.created_at);
        const end = parseCustomDate(taskData.deadline);
        
        // Validate dates - ensure start is not after end
        let validStart = start;
        let validEnd = end;
        
        if (start > end) {
            // If start is after end, swap them or adjust end date
            validStart = end;
            validEnd = new Date(end.getTime() + (24 * 60 * 60 * 1000)); // Add 1 day to end date
        }
        
        const duration_seconds = validEnd - validStart;
        let days = Math.floor(duration_seconds / (1000 * 60 * 60 * 24));
        const remaining_seconds = duration_seconds % (1000 * 60 * 60 * 24);
        let hours = Math.floor(remaining_seconds / (1000 * 60 * 60));

        // Limit duration display to prevent extremely long durations
        const maxDisplayDays = 365; // Maximum days to display
        if (days > maxDisplayDays) {
            days = maxDisplayDays;
            hours = 0;
        }

        let duration_text = '';
        if (days > 0) {
            duration_text += days + ' day' + (days > 1 ? 's' : '');
        }
        if (hours > 0) {
            duration_text += (days > 0 ? ' - ' : '') + hours + ' hr' + (hours > 1 ? 's' : '');
        }

        const taskName = taskData.title + ' (' + duration_text + ')' + (taskData.critical == 1 ? ' (Critical)' : ' (Non-critical)');
        const barColor = taskData.critical == 1 ? '#FF6363' : '#A0C878';

        // Add new row to the data table
        globalDataTable.addRow([
            taskData.id.toString(),
            taskName,
            barColor,
            validStart,
            validEnd
        ]);

        // Redraw the chart
        const options = {
            height: '100%',
            timeline: {
                showRowLabels: true,
                showBarLabels: true,
                singleColor: false
            },
            hAxis: {
                format: 'MMM d',
                minorGridlines: {
                    count: 0
                }
            }
        };

        globalChart.draw(globalDataTable, options);
    }
</script>

<section class="py-25 pl-85 w-full pr-10">

    <?php
    $project_id = isset($_GET['pid']) ? $_GET['pid'] : "";
    $project = getProjectDetailsByProjectID($project_id);

    // $start_date = date('Y-m-d', strtotime(str_replace('-', ' ', $taskData[0]['created_at'])));
    // $deadline = date('Y-m-d', strtotime($taskData[0]['deadline']));

    // $diffInSeconds = strtotime($deadline) - strtotime($start_date);
    // $diffInDays = $diffInSeconds / (60 * 60 * 24);

    // echo "Difference in days: " . $diffInDays;


    $projectDeadline = 0;
    if (is_array($taskData)) {
        foreach ($taskData as $task) {
            if ($task['latest_finish'] > $projectDeadline) {
                $projectDeadline = $task['latest_finish'];
            }
        }
    }


    $attachments = getProjectAttachments($project_id);
    ?>

    <div class="mb-5">
        <!-- Enhanced Project Banner -->
        <div class="relative overflow-hidden bg-gradient-to-br from-gray-100 via-gray-200 to-gray-300 p-8 rounded-3xl text-gray-800 shadow-md">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-0 w-32 h-32 bg-gray-400 rounded-full -translate-x-16 -translate-y-16"></div>
                <div class="absolute top-1/2 right-0 w-24 h-24 bg-gray-400 rounded-full translate-x-12 -translate-y-12"></div>
                <div class="absolute bottom-0 left-1/3 w-20 h-20 bg-gray-400 rounded-full translate-y-10"></div>
            </div>
            
            <div class="relative z-10">
                <!-- Header Section -->
                <div class="flex items-start justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <a href="http://workfyre.local/main/dashboard/projects.php"
                            class="flex items-center justify-center w-12 h-12 bg-white/20 hover:bg-white/30 rounded-xl transition-all duration-300 group">
                            <i class="fa-solid fa-arrow-left text-lg group-hover:scale-110 transition-transform"></i>
                        </a>
                        <div>
                            <h1 class="text-5xl font-bold mb-2 drop-shadow-lg"><?php echo $project['title']; ?></h1>
                            <p class="text-xl opacity-90 max-w-2xl leading-relaxed">
                                <?php echo strlen($project['description']) > 120 ? substr($project['description'], 0, 120) . '...' : $project['description']; ?>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Project Status Badge -->
                    <div class="flex flex-col items-end gap-3">
                        <div class="flex items-center gap-2 bg-white/20 px-4 py-2 rounded-full">
                            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                            <span class="text-sm font-medium">Active</span>
                        </div>
                        <button id="header-invite-team"
                            class="flex items-center gap-2 bg-white/20 hover:bg-white/30 px-4 py-2 rounded-full transition-all duration-300 group">
                            <i class="fa-solid fa-user-plus text-sm group-hover:scale-110 transition-transform"></i>
                            <span class="text-sm font-medium">Invite Team</span>
                        </button>
                    </div>
                </div>
                
                <!-- Project Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <?php
                    // Calculate project statistics
                    $totalTasks = is_array($taskData) ? count($taskData) : 0;
                    $completedTasks = 0;
                    $inProgressTasks = 0;
                    $notStartedTasks = 0;
                    
                    if (is_array($taskData)) {
                        foreach ($taskData as $task) {
                            switch ($task['status']) {
                                case 'completed':
                                    $completedTasks++;
                                    break;
                                case 'in-progress':
                                    $inProgressTasks++;
                                    break;
                                case 'not-started':
                                    $notStartedTasks++;
                                    break;
                            }
                        }
                    }
                    
                    $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                    ?>
                    
                    <!-- Progress Card -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm opacity-80">Progress</span>
                            <span class="text-lg font-bold"><?php echo $progressPercentage; ?>%</span>
                        </div>
                        <div class="w-full bg-white/20 rounded-full h-2">
                            <div class="bg-gradient-to-r from-green-400 to-emerald-500 h-2 rounded-full transition-all duration-1000" 
                                 style="width: <?php echo $progressPercentage; ?>%"></div>
                        </div>
                    </div>
                    
                    <!-- Total Tasks -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-500/30 rounded-xl flex items-center justify-center">
                                <i class="fa-solid fa-tasks text-blue-300"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold"><?php echo $totalTasks; ?></p>
                                <p class="text-sm opacity-80">Total Tasks</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Completed Tasks -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-500/30 rounded-xl flex items-center justify-center">
                                <i class="fa-solid fa-check text-green-300"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold"><?php echo $completedTasks; ?></p>
                                <p class="text-sm opacity-80">Completed</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Deadline -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-orange-500/30 rounded-xl flex items-center justify-center">
                                <i class="fa-solid fa-calendar text-orange-300"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold"><?php echo $projectDeadline; ?></p>
                                <p class="text-sm opacity-80">Days Left</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Team Section -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <span class="text-sm opacity-80">Team Members:</span>
                        <div class="flex -space-x-3">
                            <?php
                            $projectMeta = getProjectMeta($project['id']);
                            if (is_array($projectMeta) && isset($projectMeta)) {
                                foreach ($projectMeta as $index => $projectM) {
                                    if ($index < 5) { // Show max 5 team members
                                        ?>
                                        <div class="relative group">
                                            <img src="<?php echo $projectM['profile_image'] ?? 'https://i.pravatar.cc/40?img=' . ($index + 1); ?>" 
                                                 alt="<?php echo $projectM['firstname']; ?>"
                                                 class="w-12 h-12 rounded-full border-2 border-white/50 hover:border-white transition-all duration-300 shadow-lg object-cover">
                                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                                <?php echo $projectM['firstname'] . ' ' . $projectM['lastname']; ?>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                            ?>
                            <?php if (is_array($projectMeta) && count($projectMeta) > 5): ?>
                                <div class="w-12 h-12 rounded-full border-2 border-white/50 bg-white/20 flex items-center justify-center text-sm font-medium">
                                    +<?php echo is_array($projectMeta) ? count($projectMeta) - 5 : 0; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="flex items-center gap-3">
                        <button class="flex items-center gap-2 bg-white/20 hover:bg-white/30 px-4 py-2 rounded-full transition-all duration-300 group">
                            <i class="fa-solid fa-download text-sm group-hover:scale-110 transition-transform"></i>
                            <span class="text-sm font-medium">Export</span>
                        </button>
                        <button class="flex items-center gap-2 bg-white/20 hover:bg-white/30 px-4 py-2 rounded-full transition-all duration-300 group">
                            <i class="fa-solid fa-share text-sm group-hover:scale-110 transition-transform"></i>
                            <span class="text-sm font-medium">Share</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <?php
        $taskData = getTasksDetailsByProject_id($project_id);
        if (is_array($taskData) && !empty($taskData)) { ?>
            <div class="mb-4 mt-4">
                <h2 class="text-xl font-semibold mb-4">Project Timeline - Gantt Chart</h2>
                <div id="chart_div" class="w-full bg-white p-4 rounded-lg shadow-md"
                    style="min-height: 300px; max-height: 600px;"></div>
            </div>
        <?php } ?>
    </div>

    <div class="w-full flex items-center justify-between mb-10">
        <ul class="flex items-center w-1/2 border-b border-slate-300">
            <li id="projectBoard"
                class="mr-10 text-lg text-sky-700 border-b-2 border-sky-700 pb-4 cursor-pointer flex gap-2">
                <span><i class="fa-solid fa-chart-bar"></i></span>Board
            </li>

            <li id="projectFiles" class="mr-10 text-lg pb-4 cursor-pointer flex gap-2"><span><i
                        class="fa-regular fa-file"></i></span>Files</li>
            <li id="projectTeam" class="mr-10 text-lg pb-4 cursor-pointer flex gap-2"><span><i
                        class="fa-solid fa-users"></i></span>Team</li>
        </ul>

    </div>
    <!-- board container -->
    <div id="projectBoardContainer" class="grid grid-cols-3 gap-4 mb-5">

        <!-- To Do Column -->
        <div class="bg-white rounded shadow p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">To Do</h2>
                <?php if ($currentUser && $currentUser['user_role'] === 'admin'): ?>
                <button
                    class="add-task-btn text-sm px-2 py-1 bg-gray-100 hover:bg-gray-200 border border-slate-300 rounded hover:bg-blue-600"
                    data-target="todo">+ Add</button>
                <?php endif; ?>
            </div>
            <div id="todo" class="task-column space-y-3 min-h-[200px] max-h-[700px] snap-y overflow-y-auto">
                <?php
                $taskDetails = getTasksDetailsByStatus($project_id, 'not-started');
                if (isset($taskDetails) && is_array($taskDetails)) {
                    foreach ($taskDetails as $taskCard) {
                        // Check if current user can modify this task
                        $canModify = canUserModifyTask($taskCard['id']);
                        $dragClass = $canModify ? 'cursor-move' : 'cursor-not-allowed';
                        $draggable = $canModify ? 'true' : 'false';
                        $permissionIndicator = $canModify ? 'text-green-600' : 'text-red-600';
                        $permissionText = $canModify ? 'You can modify' : 'Assigned to another user';
                        ?>
                        <div id="tasks<?php echo $taskCard['id']; ?>" class="p-3 bg-gray-100 rounded shadow-md <?php echo $dragClass; ?> hover:shadow-lg transition-all duration-200"
                            draggable="<?php echo $draggable; ?>" data-task_id="<?php echo $taskCard['id']; ?>">
                            
                            <!-- Permission Indicator -->
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2 text-xs <?php echo $permissionIndicator; ?>">
                                    <i class="fa-solid <?php echo $canModify ? 'fa-check-circle' : 'fa-lock'; ?>"></i>
                                    <span><?php echo $permissionText; ?></span>
                                </div>
                            </div>
                            
                            <?php
                            // Check if current user can access the task details
                            $currentUser = getCurrentUser();
                            $canAccess = false;
                            $taskUrl = '';
                            
                            if ($currentUser) {
                                // Admin can access all tasks
                                if ($currentUser['user_role'] === 'admin') {
                                    $canAccess = true;
                                    $taskUrl = "http://workfyre.local/main/dashboard/templates/tasks.php?pid=" . $_GET['pid'] . "&tid=" . $taskCard['id'];
                                }
                                // Assigned user can access their tasks
                                elseif ($taskCard['assign_to'] == $currentUser['id']) {
                                    $canAccess = true;
                                    $taskUrl = "http://workfyre.local/main/dashboard/templates/tasks.php?pid=" . $_GET['pid'] . "&tid=" . $taskCard['id'];
                                }
                            }
                            
                            if ($canAccess) {
                                // User can access - show clickable link
                                ?>
                                <a
                                    href="<?php echo $taskUrl; ?>"
                                    class="block hover:bg-gray-50 p-2 -m-2 rounded transition-colors duration-200 cursor-pointer pointer-events-auto">
                                    <h2 class="text-xl font-medium"><?php echo $taskCard['title']; ?></h2>
                                    <P class="text-sm mb-2 w-full">
                                        <?php echo strlen($taskCard['description']) > 20 ? substr($taskCard['description'], 0, 40) . '...' : $taskCard['description']; ?>
                                    </p>
                                    <div
                                        class="flex items-center mb-2 border-b border-slate-300 p-2 justify-between text-sm font-light">
                                        <P><?php echo $taskCard['deadline']; ?></p>
                                        <span
                                            class="<?php echo getClasses($taskCard['priority']); ?> rounded-full flex px-2 items-center justify-center"><?php echo $taskCard['priority']; ?>
                                            </spam>
                                    </div>

                                    <!-- Dependency Status Indicator -->
                                    <?php
                                    $taskDependencies = getTaskDependencies($taskCard['id']);
                                    if (is_array($taskDependencies) && !empty($taskDependencies)) {
                                        $completedDeps = 0;
                                        $totalDeps = count($taskDependencies);
                                        foreach ($taskDependencies as $dep) {
                                            $depTask = getTasksDetailsByTask_id($dep['dependency_task_id']);
                                            if (is_array($depTask) && isset($depTask['status']) && $depTask['status'] === 'completed') {
                                                $completedDeps++;
                                            }
                                        }
                                        $depStatusClass = $completedDeps === $totalDeps ? 'text-green-600' : 'text-yellow-600';
                                        $depStatusText = $completedDeps === $totalDeps ? 'Ready' : 'Waiting';
                                    ?>
                                    <div class="flex items-center gap-2 mb-2 text-xs <?php echo $depStatusClass; ?>">
                                        <i class="fa-solid fa-link"></i>
                                        <span><?php echo $depStatusText; ?> (<?php echo $completedDeps; ?>/<?php echo $totalDeps; ?> deps)</span>
                                    </div>
                                    <?php } ?>

                                    <div class="flex items-center justify-between text-sm relative">
                                        <div class="flex items-center gap-1">
                                            <i class="fa-regular fa-comments"></i>
                                            <p>12 Comments</p>
                                        </div>
                                        <div class="flex items-center gap-1"><span>Assign to:</span>
                                            <?php $assignUser = getUsersDetailsByUser_id($taskCard['assign_to']); ?>
                                            <span
                                                class="assignUserProfile rounded-full ml-1 font-medium border border-slate-300 flex items-center justify-center w-6 h-6 overflow-hidden">
                                                <img src="http://workfyre.local/assets/images/default-profile.png"
                                                    class="w-full h-full object-cover" alt="default profile" />
                                            </span>
                                            <div
                                                class="userNameTooltip bg-slate-900/80 text-white absolute px-4 py-2 rounded top-5 left-8 flex z-50 hidden group-hover:flex">
                                                <span><?php echo is_array($assignUser) ? $assignUser['firstname'] . ' ' . $assignUser['lastname'] : 'Unknown User'; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <?php
                            } else {
                                // User cannot access - show non-clickable content
                                ?>
                                <div class="block p-2 -m-2 rounded cursor-not-allowed opacity-75">
                                    <h2 class="text-xl font-medium"><?php echo $taskCard['title']; ?></h2>
                                    <P class="text-sm mb-2 w-full">
                                        <?php echo strlen($taskCard['description']) > 20 ? substr($taskCard['description'], 0, 40) . '...' : $taskCard['description']; ?>
                                    </p>
                                    <div
                                        class="flex items-center mb-2 border-b border-slate-300 p-2 justify-between text-sm font-light">
                                        <P><?php echo $taskCard['deadline']; ?></p>
                                        <span
                                            class="<?php echo getClasses($taskCard['priority']); ?> rounded-full flex px-2 items-center justify-center"><?php echo $taskCard['priority']; ?>
                                            </spam>
                                    </div>

                                    <!-- Dependency Status Indicator -->
                                    <?php
                                    $taskDependencies = getTaskDependencies($taskCard['id']);
                                    if (is_array($taskDependencies) && !empty($taskDependencies)) {
                                        $completedDeps = 0;
                                        $totalDeps = count($taskDependencies);
                                        foreach ($taskDependencies as $dep) {
                                            $depTask = getTasksDetailsByTask_id($dep['dependency_task_id']);
                                            if (is_array($depTask) && isset($depTask['status']) && $depTask['status'] === 'completed') {
                                                $completedDeps++;
                                            }
                                        }
                                        $depStatusClass = $completedDeps === $totalDeps ? 'text-green-600' : 'text-yellow-600';
                                        $depStatusText = $completedDeps === $totalDeps ? 'Ready' : 'Waiting';
                                    ?>
                                    <div class="flex items-center gap-2 mb-2 text-xs <?php echo $depStatusClass; ?>">
                                        <i class="fa-solid fa-link"></i>
                                        <span><?php echo $depStatusText; ?> (<?php echo $completedDeps; ?>/<?php echo $totalDeps; ?> deps)</span>
                                    </div>
                                    <?php } ?>

                                    <div class="flex items-center justify-between text-sm relative">
                                        <div class="flex items-center gap-1">
                                            <i class="fa-regular fa-comments"></i>
                                            <p>12 Comments</p>
                                        </div>
                                        <div class="flex items-center gap-1"><span>Assign to:</span>
                                            <?php $assignUser = getUsersDetailsByUser_id($taskCard['assign_to']); ?>
                                            <span
                                                class="assignUserProfile rounded-full ml-1 font-medium border border-slate-300 flex items-center justify-center w-6 h-6 overflow-hidden">
                                                <img src="http://workfyre.local/assets/images/default-profile.png"
                                                    class="w-full h-full object-cover" alt="default profile" />
                                            </span>
                                            <div
                                                class="userNameTooltip bg-slate-900/80 text-white absolute px-4 py-2 rounded top-5 left-8 flex z-50 hidden group-hover:flex">
                                                <span><?php echo is_array($assignUser) ? $assignUser['firstname'] . ' ' . $assignUser['lastname'] : 'Unknown User'; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                }
                ?>

            </div>
        </div>

        <!-- In Progress Column -->
        <div class="bg-white rounded shadow p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">In Progress</h2>
                <!-- <button class="add-task-btn text-sm px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600"
                    data-target="inprogress">+ Add</button> -->
            </div>
            <div id="inprogress" class="task-column space-y-3 min-h-[200px]">
                <?php
                $taskDetails = getTasksDetailsByStatus($project_id, 'in-progress');
                if (isset($taskDetails) && is_array($taskDetails)) {
                    foreach ($taskDetails as $taskCard) {
                        // Check if current user can modify this task
                        $canModify = canUserModifyTask($taskCard['id']);
                        $dragClass = $canModify ? 'cursor-move' : 'cursor-not-allowed';
                        $draggable = $canModify ? 'true' : 'false';
                        $permissionIndicator = $canModify ? 'text-green-600' : 'text-red-600';
                        $permissionText = $canModify ? 'You can modify' : 'Assigned to another user';
                        ?>
                        <div id="tasks<?php echo $taskCard['id']; ?>" class="p-3 bg-yellow-200 rounded shadow-md <?php echo $dragClass; ?> hover:shadow-lg transition-all duration-200"
                            draggable="<?php echo $draggable; ?>" data-task_id="<?php echo $taskCard['id']; ?>">
                            
                            <!-- Permission Indicator -->
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2 text-xs <?php echo $permissionIndicator; ?>">
                                    <i class="fa-solid <?php echo $canModify ? 'fa-check-circle' : 'fa-lock'; ?>"></i>
                                    <span><?php echo $permissionText; ?></span>
                                </div>
                            </div>
                            
                            <?php
                            // Check if current user can access the task details
                            $currentUser = getCurrentUser();
                            $canAccess = false;
                            $taskUrl = '';
                            
                            if ($currentUser) {
                                // Admin can access all tasks
                                if ($currentUser['user_role'] === 'admin') {
                                    $canAccess = true;
                                    $taskUrl = "http://workfyre.local/main/dashboard/templates/tasks.php?pid=" . $_GET['pid'] . "&tid=" . $taskCard['id'];
                                }
                                // Assigned user can access their tasks
                                elseif ($taskCard['assign_to'] == $currentUser['id']) {
                                    $canAccess = true;
                                    $taskUrl = "http://workfyre.local/main/dashboard/templates/tasks.php?pid=" . $_GET['pid'] . "&tid=" . $taskCard['id'];
                                }
                            }
                            
                            if ($canAccess) {
                                // User can access - show clickable link
                                ?>
                                <a
                                    href="<?php echo $taskUrl; ?>"
                                    class="block hover:bg-gray-50 p-2 -m-2 rounded transition-colors duration-200 cursor-pointer pointer-events-auto">
                                    <h2 class="text-xl font-medium"><?php echo $taskCard['title']; ?></h2>
                                    <P class="text-sm mb-2 w-full">
                                        <?php echo strlen($taskCard['description']) > 20 ? substr($taskCard['description'], 0, 40) . '...' : $taskCard['description']; ?>
                                    </p>
                                    <div
                                        class="flex items-center mb-2 border-b border-slate-300 p-2 justify-between text-sm font-light">
                                        <P><?php echo $taskCard['deadline']; ?></p>
                                        <span
                                            class="<?php echo getClasses($taskCard['priority']); ?> rounded-full flex px-2 items-center justify-center"><?php echo $taskCard['priority']; ?>
                                            </spam>
                                    </div>

                                    <!-- Dependency Status Indicator -->
                                    <?php
                                    $taskDependencies = getTaskDependencies($taskCard['id']);
                                    if (is_array($taskDependencies) && !empty($taskDependencies)) {
                                        $completedDeps = 0;
                                        $totalDeps = count($taskDependencies);
                                        foreach ($taskDependencies as $dep) {
                                            $depTask = getTasksDetailsByTask_id($dep['dependency_task_id']);
                                            if (is_array($depTask) && isset($depTask['status']) && $depTask['status'] === 'completed') {
                                                $completedDeps++;
                                            }
                                        }
                                        $depStatusClass = $completedDeps === $totalDeps ? 'text-green-600' : 'text-yellow-600';
                                        $depStatusText = $completedDeps === $totalDeps ? 'Ready' : 'Waiting';
                                    ?>
                                    <div class="flex items-center gap-2 mb-2 text-xs <?php echo $depStatusClass; ?>">
                                        <i class="fa-solid fa-link"></i>
                                        <span><?php echo $depStatusText; ?> (<?php echo $completedDeps; ?>/<?php echo $totalDeps; ?> deps)</span>
                                    </div>
                                    <?php } ?>

                                    <div class="flex items-center justify-between text-sm relative">
                                        <div class="flex items-center gap-1">
                                            <i class="fa-regular fa-comments"></i>
                                            <p>12 Comments</p>
                                        </div>
                                        <div class="flex items-center gap-1"><span>Assign to:</span>
                                            <?php $assignUser = getUsersDetailsByUser_id($taskCard['assign_to']); ?>
                                            <span
                                                class="assignUserProfile rounded-full ml-1 font-medium border border-slate-300 flex items-center justify-center w-6 h-6 overflow-hidden">
                                                <img src="http://workfyre.local/assets/images/default-profile.png"
                                                    class="w-full h-full object-cover" alt="default profile" />
                                            </span>
                                            <div
                                                class="userNameTooltip bg-slate-900/80 text-white absolute px-4 py-2 rounded top-5 left-8 flex z-50 hidden group-hover:flex">
                                                <span><?php echo is_array($assignUser) ? $assignUser['firstname'] . ' ' . $assignUser['lastname'] : 'Unknown User'; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <?php
                            } else {
                                // User cannot access - show non-clickable content
                                ?>
                                <div class="block p-2 -m-2 rounded cursor-not-allowed opacity-75">
                                    <h2 class="text-xl font-medium"><?php echo $taskCard['title']; ?></h2>
                                    <P class="text-sm mb-2 w-full">
                                        <?php echo strlen($taskCard['description']) > 20 ? substr($taskCard['description'], 0, 40) . '...' : $taskCard['description']; ?>
                                    </p>
                                    <div
                                        class="flex items-center mb-2 border-b border-slate-300 p-2 justify-between text-sm font-light">
                                        <P><?php echo $taskCard['deadline']; ?></p>
                                        <span
                                            class="<?php echo getClasses($taskCard['priority']); ?> rounded-full flex px-2 items-center justify-center"><?php echo $taskCard['priority']; ?>
                                            </spam>
                                    </div>

                                    <!-- Dependency Status Indicator -->
                                    <?php
                                    $taskDependencies = getTaskDependencies($taskCard['id']);
                                    if (is_array($taskDependencies) && !empty($taskDependencies)) {
                                        $completedDeps = 0;
                                        $totalDeps = count($taskDependencies);
                                        foreach ($taskDependencies as $dep) {
                                            $depTask = getTasksDetailsByTask_id($dep['dependency_task_id']);
                                            if (is_array($depTask) && isset($depTask['status']) && $depTask['status'] === 'completed') {
                                                $completedDeps++;
                                            }
                                        }
                                        $depStatusClass = $completedDeps === $totalDeps ? 'text-green-600' : 'text-yellow-600';
                                        $depStatusText = $completedDeps === $totalDeps ? 'Ready' : 'Waiting';
                                    ?>
                                    <div class="flex items-center gap-2 mb-2 text-xs <?php echo $depStatusClass; ?>">
                                        <i class="fa-solid fa-link"></i>
                                        <span><?php echo $depStatusText; ?> (<?php echo $completedDeps; ?>/<?php echo $totalDeps; ?> deps)</span>
                                    </div>
                                    <?php } ?>

                                    <div class="flex items-center justify-between text-sm relative">
                                        <div class="flex items-center gap-1">
                                            <i class="fa-regular fa-comments"></i>
                                            <p>12 Comments</p>
                                        </div>
                                        <div class="flex items-center gap-1"><span>Assign to:</span>
                                            <?php $assignUser = getUsersDetailsByUser_id($taskCard['assign_to']); ?>
                                            <span
                                                class="assignUserProfile rounded-full ml-1 font-medium border border-slate-300 flex items-center justify-center w-6 h-6 overflow-hidden">
                                                <img src="http://workfyre.local/assets/images/default-profile.png"
                                                    class="w-full h-full object-cover" alt="default profile" />
                                            </span>
                                            <div
                                                class="userNameTooltip bg-slate-900/80 text-white absolute px-4 py-2 rounded top-5 left-8 flex z-50 hidden group-hover:flex">
                                                <span><?php echo is_array($assignUser) ? $assignUser['firstname'] . ' ' . $assignUser['lastname'] : 'Unknown User'; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>

        <!-- Done Column -->
        <div class="bg-white rounded shadow p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Done</h2>
                <!-- <button class="add-task-btn text-sm px-2 py-1 bg-sky-500 text-white rounded hover:bg-sky-600"
                    data-target="done">+ Add</button> -->
            </div>
            <div id="done" class="task-column space-y-3 min-h-[200px]">
                <?php
                $taskDetails = getTasksDetailsByStatus($project_id, 'completed');
                if (isset($taskDetails) && is_array($taskDetails)) {
                    foreach ($taskDetails as $taskCard) {
                        // Check if current user can modify this task
                        $canModify = canUserModifyTask($taskCard['id']);
                        $dragClass = $canModify ? 'cursor-move' : 'cursor-not-allowed';
                        $draggable = $canModify ? 'true' : 'false';
                        $permissionIndicator = $canModify ? 'text-green-600' : 'text-red-600';
                        $permissionText = $canModify ? 'You can modify' : 'Assigned to another user';
                        ?>
                        <div id="tasks<?php echo $taskCard['id']; ?>" class="p-3 bg-sky-200 rounded shadow-md <?php echo $dragClass; ?> hover:shadow-lg transition-all duration-200"
                            draggable="<?php echo $draggable; ?>" data-task_id="<?php echo $taskCard['id']; ?>">
                            
                            <!-- Permission Indicator -->
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2 text-xs <?php echo $permissionIndicator; ?>">
                                    <i class="fa-solid <?php echo $canModify ? 'fa-check-circle' : 'fa-lock'; ?>"></i>
                                    <span><?php echo $permissionText; ?></span>
                                </div>
                            </div>
                            
                            <?php
                            // Check if current user can access the task details
                            $currentUser = getCurrentUser();
                            $canAccess = false;
                            $taskUrl = '';
                            
                            if ($currentUser) {
                                // Admin can access all tasks
                                if ($currentUser['user_role'] === 'admin') {
                                    $canAccess = true;
                                    $taskUrl = "http://workfyre.local/main/dashboard/templates/tasks.php?pid=" . $_GET['pid'] . "&tid=" . $taskCard['id'];
                                }
                                // Assigned user can access their tasks
                                elseif ($taskCard['assign_to'] == $currentUser['id']) {
                                    $canAccess = true;
                                    $taskUrl = "http://workfyre.local/main/dashboard/templates/tasks.php?pid=" . $_GET['pid'] . "&tid=" . $taskCard['id'];
                                }
                            }
                            
                            if ($canAccess) {
                                // User can access - show clickable link
                                ?>
                                <a
                                    href="<?php echo $taskUrl; ?>"
                                    class="block hover:bg-gray-50 p-2 -m-2 rounded transition-colors duration-200 cursor-pointer pointer-events-auto">
                                    <h2 class="text-xl font-medium"><?php echo $taskCard['title']; ?></h2>
                                    <P class="text-sm mb-2 w-full">
                                        <?php echo strlen($taskCard['description']) > 20 ? substr($taskCard['description'], 0, 40) . '...' : $taskCard['description']; ?>
                                    </p>
                                    <div
                                        class="flex items-center mb-2 border-b border-slate-300 p-2 justify-between text-sm font-light">
                                        <P><?php echo $taskCard['deadline']; ?></p>
                                        <span
                                            class="<?php echo getClasses($taskCard['priority']); ?> rounded-full flex px-2 items-center justify-center"><?php echo $taskCard['priority']; ?>
                                            </spam>
                                    </div>

                                    <!-- Dependency Status Indicator -->
                                    <?php
                                    $taskDependencies = getTaskDependencies($taskCard['id']);
                                    if (is_array($taskDependencies) && !empty($taskDependencies)) {
                                        $completedDeps = 0;
                                        $totalDeps = count($taskDependencies);
                                        foreach ($taskDependencies as $dep) {
                                            $depTask = getTasksDetailsByTask_id($dep['dependency_task_id']);
                                            if (is_array($depTask) && isset($depTask['status']) && $depTask['status'] === 'completed') {
                                                $completedDeps++;
                                            }
                                        }
                                        $depStatusClass = $completedDeps === $totalDeps ? 'text-green-600' : 'text-yellow-600';
                                        $depStatusText = $completedDeps === $totalDeps ? 'Ready' : 'Waiting';
                                    ?>
                                    <div class="flex items-center gap-2 mb-2 text-xs <?php echo $depStatusClass; ?>">
                                        <i class="fa-solid fa-link"></i>
                                        <span><?php echo $depStatusText; ?> (<?php echo $completedDeps; ?>/<?php echo $totalDeps; ?> deps)</span>
                                    </div>
                                    <?php } ?>

                                    <div class="flex items-center justify-between text-sm relative">
                                        <div class="flex items-center gap-1">
                                            <i class="fa-regular fa-comments"></i>
                                            <p>12 Comments</p>
                                        </div>
                                        <div class="flex items-center gap-1"><span>Assign to:</span>
                                            <?php $assignUser = getUsersDetailsByUser_id($taskCard['assign_to']); ?>
                                            <span
                                                class="assignUserProfile rounded-full ml-1 font-medium border border-slate-300 flex items-center justify-center w-6 h-6 overflow-hidden">
                                                <img src="http://workfyre.local/assets/images/default-profile.png"
                                                    class="w-full h-full object-cover" alt="default profile" />
                                            </span>
                                            <div
                                                class="userNameTooltip bg-slate-900/80 text-white absolute px-4 py-2 rounded top-5 left-8 flex z-50 hidden group-hover:flex">
                                                <span><?php echo is_array($assignUser) ? $assignUser['firstname'] . ' ' . $assignUser['lastname'] : 'Unknown User'; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <?php
                            } else {
                                // User cannot access - show non-clickable content
                                ?>
                                <div class="block p-2 -m-2 rounded cursor-not-allowed opacity-75">
                                    <h2 class="text-xl font-medium"><?php echo $taskCard['title']; ?></h2>
                                    <P class="text-sm mb-2 w-full">
                                        <?php echo strlen($taskCard['description']) > 20 ? substr($taskCard['description'], 0, 40) . '...' : $taskCard['description']; ?>
                                    </p>
                                    <div
                                        class="flex items-center mb-2 border-b border-slate-300 p-2 justify-between text-sm font-light">
                                        <P><?php echo $taskCard['deadline']; ?></p>
                                        <span
                                            class="<?php echo getClasses($taskCard['priority']); ?> rounded-full flex px-2 items-center justify-center"><?php echo $taskCard['priority']; ?>
                                            </spam>
                                    </div>

                                    <!-- Dependency Status Indicator -->
                                    <?php
                                    $taskDependencies = getTaskDependencies($taskCard['id']);
                                    if (is_array($taskDependencies) && !empty($taskDependencies)) {
                                        $completedDeps = 0;
                                        $totalDeps = count($taskDependencies);
                                        foreach ($taskDependencies as $dep) {
                                            $depTask = getTasksDetailsByTask_id($dep['dependency_task_id']);
                                            if (is_array($depTask) && isset($depTask['status']) && $depTask['status'] === 'completed') {
                                                $completedDeps++;
                                            }
                                        }
                                        $depStatusClass = $completedDeps === $totalDeps ? 'text-green-600' : 'text-yellow-600';
                                        $depStatusText = $completedDeps === $totalDeps ? 'Ready' : 'Waiting';
                                    ?>
                                    <div class="flex items-center gap-2 mb-2 text-xs <?php echo $depStatusClass; ?>">
                                        <i class="fa-solid fa-link"></i>
                                        <span><?php echo $depStatusText; ?> (<?php echo $completedDeps; ?>/<?php echo $totalDeps; ?> deps)</span>
                                    </div>
                                    <?php } ?>

                                    <div class="flex items-center justify-between text-sm relative">
                                        <div class="flex items-center gap-1">
                                            <i class="fa-regular fa-comments"></i>
                                            <p>12 Comments</p>
                                        </div>
                                        <div class="flex items-center gap-1"><span>Assign to:</span>
                                            <?php $assignUser = getUsersDetailsByUser_id($taskCard['assign_to']); ?>
                                            <span
                                                class="assignUserProfile rounded-full ml-1 font-medium border border-slate-300 flex items-center justify-center w-6 h-6 overflow-hidden">
                                                <img src="http://workfyre.local/assets/images/default-profile.png"
                                                    class="w-full h-full object-cover" alt="default profile" />
                                            </span>
                                            <div
                                                class="userNameTooltip bg-slate-900/80 text-white absolute px-4 py-2 rounded top-5 left-8 flex z-50 hidden group-hover:flex">
                                                <span><?php echo is_array($assignUser) ? $assignUser['firstname'] . ' ' . $assignUser['lastname'] : 'Unknown User'; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <!-- files container -->
    <div id="projectFilesContainer" class="hidden">
        <div class="mb-2" id="deleteProjectAttachmentSuccessMessage"></div>
        <ul>
            <?php
            if (!empty($attachments)) {
                foreach ($attachments as $attachment) {
                    $path = $attachment['attachment'];
                    $filename = basename($path);
                    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

                    $isImage = in_array($extension, $imageExtensions);
                    $isOpenableFile = in_array($extension, ['pdf', 'zip']);

                    $previewSrc = $isImage ? $path : '/assets/images/default-file-icon.png';

                    // Start <a> tag if it's a PDF or ZIP
                    if ($isOpenableFile) {
                        echo '<a href="' . $path . '" target="_blank">';
                    }
                    ?>
                    <li id="projectAttachmentConainer<?php echo $attachment['id'] ?>"
                        class="shadow-sm p-2 rounded-lg flex items-center justify-between mb-5">
                        <div class="flex items-center gap-2">
                            <span
                                class="rounded-lg font-medium border border-slate-300 flex items-center justify-center w-20 h-20 overflow-hidden bg-white">
                                <img src="<?php echo $previewSrc; ?>"
                                    class="preview-image cursor-pointer w-full h-full object-cover"
                                    alt="<?php echo $filename; ?>" />
                            </span>
                            <p class="text-sm"><?php echo $filename; ?></p>
                        </div>
                        <?php
                        // Close <a> tag if started
                        if ($isOpenableFile) {
                            echo '</a>';
                        }
                        ?>
                        <div class="flex items-center gap-2">

                            <button
                                class="deleteProjectAttachment border border-slate-300 px-4 py-2 rounded-lg hover:bg-red-800 hover:text-white"
                                data-attachment_id="<?php echo $attachment['id'] ?>">
                                Delete
                            </button>
                        </div>
                    </li>
                    <?php

                }
            } else {
                echo "No Attachments Found.";
            }

            ?>

        </ul>
    </div>
    <!-- team container -->
    <div id="projectTeamContainer" class="hidden">
        <div class="items-center justify-end flex mb-5">
            <button id="inviteTeamBtn"
                class="border border-slate-300 px-4 py-2 rounded-lg hover:bg-blue-600 hover:text-white">
                <i class="fa-solid fa-user-plus"></i>
                Invite Team
            </button>
        </div>
        <div class="border-t border-slate-300 p-4">
            <ul id="invitation_container<?php echo $project_id; ?>">
                <?php
                $projectMeta = getProjectMeta($project_id);
                if (is_array($projectMeta) && isset($projectMeta)) {
                    foreach ($projectMeta as $projectM) {
                        ?>
                        <li class="mb-5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-5">
                                    <span
                                        class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-8 h-8 overflow-hidden">
                                        <img src="http://workfyre.local/assets/images/default-profile.png"
                                            class="w-full h-full object-cover" alt="default profile" />
                                    </span>
                                    <p class="text-lg font-medium">
                                        <?php echo $projectM['firstname'] . ' ' . $projectM['lastname']; ?>
                                    </p>
                                </div>
                                <div class="text-sm">Email:<span class="ml-2 text-sm font-light">gokarnachy28@gmail.com</span>
                                </div>
                                <div class="text-sm">status:<span
                                        class="<?php echo getClasses($projectM['status']) ?> ml-2 px-2 rounded-full text-sm"><?php echo ucfirst($projectM['status']); ?></span>
                                </div>
                                <span class="text-sm font-light"><?php echo $projectM['created_at'] ?></span>
                            </div>
                        </li>
                        <?php
                    }
                } else {
                    echo "No User Found.";
                }
                ?>

            </ul>
        </div>


        <!-- invite team modal -->
        <div id="inviteTeamForm" class="fixed inset-0 bg-gray-500/50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg p-6 w-1/2 shadow-lg">
                <h3 class="text-xl font-semibold mb-4">Invite Team to the <span
                        class="font-bold"><?php echo $project['title'] ?></span></h3>
                <div class="mb-2" id="inviteTeamSuccessMessage"></div>
                <form id="inviteTeamForm" method="POST">
                    <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">

                    <div
                        class="flex flex-col items-start justify-center w-full gap-5 border-t border-slate-300 p-4 w-full">
                        <ul class="w-full max-h-[500px] snap-y overflow-y-auto">
                            <?php
                            // var_dump(getUsersDetails());
                            $users = getUsersDetails();
                            if ($users) {
                                foreach ($users as $user) {
                                    ?>
                                    <li class="mb-5 w-full">
                                        <label class="flex items-center gap-5 hover:bg-slate-100 p-3 rounded-lg">
                                            <input class="w-6 h-6" type="checkbox" name="user_id[]"
                                                value="<?php echo $user['id']; ?>">
                                            <div class="flex items-center justify-between w-full">
                                                <div class="flex items-center gap-2">
                                                    <span
                                                        class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-8 h-8 overflow-hidden">
                                                        <img src="http://workfyre.local/assets/images/default-profile.png"
                                                            class="w-full h-full object-cover" alt="default profile" />
                                                    </span>
                                                    <p class="text-lg">
                                                        <?php echo ucfirst($user['firstname'] . ' ' . $user['lastname']) ?>
                                                    </p>
                                                </div>
                                                <p>Email:<span
                                                        class="ml-2 text-sm font-light"><?php echo $user['email'] ?></span></p>
                                            </div>
                                        </label>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button id="inviteTeamCancelBtn"
                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Add
                            Team</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- carate task modal -->
    <div id="taskModal" class="fixed inset-0 bg-gray-500/50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg p-6 w-1/2 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold">Add Task</h3>
                <button id="closeTaskModal" class="text-gray-500 hover:text-gray-700 text-2xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form id="createTaskForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
                <div class="mb-2" id="taskCreateSuccessMessage"></div>
                <div>
                    <label>Title:</label>
                    <input type="text" id="task_title" name="task_title"
                        class="w-full border p-2 rounded mb-4 border border-slate-300" placeholder="Task title"
                        required>
                </div>
                <div class="flex items-center w-full gap-5">
                    <div class="w-full">
                        <label>Deadline:</label>
                        <input type="date" id="task_deadline" name="task_deadline"
                            class="w-full border p-2 rounded mb-4 border border-slate-300" placeholder="Task deadline"
                            required>
                    </div>
                </div>
                <div class="flex items-center w-full gap-5">
                    <div class="w-full">
                        <label class="block mb-2 font-medium text-slate-700">Dependencies:</label>
                        <div id="task_dependencies_wrapper"
                            class="mb-4 space-y-1 border border-slate-300 rounded p-4 max-h-30 snap-y overflow-y-auto">
                            <?php
                            $tasks = getTasksDetailsByProject_id($project['id']);
                            if (is_array($tasks)) {
                                foreach ($tasks as $task) {
                                    ?>
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" name="task_dependencies[]" value="<?php echo $task['id']; ?>"
                                            class="task-dependency-checkbox">
                                        <span><?php echo ucfirst($task['title']); ?></span>
                                    </label>
                                    <?php
                                }
                            } else {
                                echo "No Task Yet.";
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="flex items-center w-full gap-5">

                    <div class="w-1/2">
                        <label>Assign to:</label>
                        <select id="task_assign" name="task_assign"
                            class="w-full border p-2 rounded mb-4 border border-slate-300" required>
                            <option value="">assign to</option>
                            <?php $users = getProjectMetaByStatus($project['id'], 'enrolled');
                            if ($users) {
                                foreach ($users as $user) {
                                    ?>
                                    <option value="<?php echo $user['id'] ?>">
                                        <?php echo ucfirst($user['firstname'] . ' ' . $user['lastname']) ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div>
                    <label>Description:</label>
                    <textarea id="task_description" name="task_description"
                        class="w-full border p-2 rounded mb-4 border border-slate-300" placeholder="description"
                        required></textarea>
                </div>

                <div>
                    <label>Attachments:</label>
                    <input type="file" id="task_attachments" name="task_attachments[]"
                        class="w-full border p-2 rounded mb-4 border border-slate-300" placeholder="Task title"
                        multiple>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="submit" id="addTaskBtn" name="addTaskBtn"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Add
                        Task</button>
                </div>
            </form>

        </div>
    </div>
    <!-- image preview popup   -->
    <div id="imageModal" class="fixed inset-0 bg-gray-400 bg-opacity-80 z-50 items-center justify-center p-4 hidden">
        <div class="relative max-w-full max-h-full flex flex-col items-center">
            <span class="flex w-full text-4xl items-center justify-end"><i
                    class="fa-regular fa-circle-xmark cursor-pointer" id="closeImageModal"></i></span>
            <img id="modalImage" src="" alt="Preview"
                class="max-w-full max-h-[90vh] rounded shadow-lg object-contain" />
        </div>
    </div>
</section>