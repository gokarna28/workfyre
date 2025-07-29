<?php include_once('../sidebar.php'); ?>
<?php include_once('../header.php'); 

// Get current user data
$currentUser = getCurrentUser();

// Get dashboard statistics
$totalUsers = getTotalUsers();
$totalProjects = getTotalProjects();
$enrolledProjects = getTotalEnrolledProjects($currentUser['id']);
$projectStats = getProjectStatistics($currentUser['id']);

// Get recent team members (limit to 6 for display)
$recentUsers = getUsersWithProjects(6, 0);

// Get recent projects for the current user
$recentProjects = getEnrolledProjects(4, 0, $currentUser['id']);

// Get current user's tasks
$userTasks = [];
if (isset($currentUser['id'])) {
    try {
        global $conn;
        $stmt = $conn->prepare("
            SELECT t.*, p.title as project_title, p.id as project_id 
            FROM workfyre_tasks t 
            JOIN workfyre_projects p ON t.project_id = p.id 
            WHERE t.assign_to = ? 
            ORDER BY t.created_at DESC 
            LIMIT 5
        ");
        $stmt->execute([$currentUser['id']]);
        $userTasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Error fetching user tasks: " . $e->getMessage());
    }
}

// Get upcoming deadlines (tasks due in next 7 days)
$upcomingDeadlines = [];
if (isset($currentUser['id'])) {
    try {
        global $conn;
        $stmt = $conn->prepare("
            SELECT t.*, p.title as project_title 
            FROM workfyre_tasks t 
            JOIN workfyre_projects p ON t.project_id = p.id 
            WHERE t.assign_to = ? 
            AND t.deadline BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
            AND t.status != 'completed'
            ORDER BY t.deadline ASC 
            LIMIT 3
        ");
        $stmt->execute([$currentUser['id']]);
        $upcomingDeadlines = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Error fetching upcoming deadlines: " . $e->getMessage());
    }
}

// Get recent activities (last 5 activities)
$recentActivities = [];
if (isset($currentUser['id'])) {
    try {
        global $conn;
        $stmt = $conn->prepare("
            SELECT 'task_created' as type, t.title, p.title as project_title, t.created_at, 'Task created' as action
            FROM workfyre_tasks t 
            JOIN workfyre_projects p ON t.project_id = p.id 
            WHERE t.created_by = ? 
            UNION ALL
            SELECT 'task_completed' as type, t.title, p.title as project_title, t.updated_at, 'Task completed' as action
            FROM workfyre_tasks t 
            JOIN workfyre_projects p ON t.project_id = p.id 
            WHERE t.assign_to = ? AND t.status = 'completed'
            ORDER BY created_at DESC 
            LIMIT 5
        ");
        $stmt->execute([$currentUser['id'], $currentUser['id']]);
        $recentActivities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Error fetching recent activities: " . $e->getMessage());
    }
}

// Get user's project progress (for progress bars)
$userProjectProgress = [];
if (isset($currentUser['id'])) {
    try {
        global $conn;
        $stmt = $conn->prepare("
            SELECT p.id, p.title, 
                   COUNT(t.id) as total_tasks,
                   SUM(CASE WHEN t.status = 'completed' THEN 1 ELSE 0 END) as completed_tasks
            FROM workfyre_projects p 
            LEFT JOIN workfyre_tasks t ON p.id = t.project_id
            JOIN workfyre_project_meta pm ON p.id = pm.project_id
            WHERE pm.user_id = ? AND p.status = 'active'
            GROUP BY p.id, p.title
            HAVING total_tasks > 0
            ORDER BY (completed_tasks/total_tasks) ASC
            LIMIT 3
        ");
        $stmt->execute([$currentUser['id']]);
        $userProjectProgress = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Error fetching project progress: " . $e->getMessage());
    }
}

// Calculate completion percentage
$completedProjects = $projectStats['completed'] ?? 0;
$totalUserProjects = $projectStats['total'] ?? 1;
$completionPercentage = round(($completedProjects / $totalUserProjects) * 100);

// Get current date info
$currentDate = date('l, F j, Y');
$currentTime = date('g:i A');
$greeting = '';
$hour = (int)date('H');
if ($hour < 12) {
    $greeting = 'Good Morning';
} elseif ($hour < 17) {
    $greeting = 'Good Afternoon';
} else {
    $greeting = 'Good Evening';
}

// Get user's role for personalized content
$userRole = $currentUser['user_role'] ?? 'member';
$isAdmin = $userRole === 'admin';
?>

<section class="py-25 pl-85 w-full pr-10">
    <!-- Welcome Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">
                    <?php echo $greeting; ?>, <?php echo htmlspecialchars($currentUser['firstname'] ?? 'User'); ?>! 👋
                </h1>
                <p class="text-lg text-gray-600">
                    <?php if ($isAdmin): ?>
                        Welcome back! You have <?php echo $totalUsers; ?> team members and <?php echo $totalProjects; ?> total projects to manage.
                    <?php else: ?>
                        Here's what's happening with your projects today. You have <?php echo $enrolledProjects; ?> active projects.
                    <?php endif; ?>
                </p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500"><?php echo $currentDate; ?></p>
                <p class="text-lg font-semibold text-gray-700"><?php echo $currentTime; ?></p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Projects Card -->
        <div class="bg-gradient-to-br from-slate-600 to-slate-700 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-200 text-sm font-medium"><?php echo $isAdmin ? 'Total Projects' : 'My Projects'; ?></p>
                    <p class="text-3xl font-bold mt-1"><?php echo $isAdmin ? $totalProjects : $enrolledProjects; ?></p>
                    <p class="text-slate-300 text-xs mt-2"><?php echo $isAdmin ? 'Across all teams' : 'You\'re enrolled in'; ?></p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-folder text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Projects Card -->
        <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-emerald-200 text-sm font-medium">Active Projects</p>
                    <p class="text-3xl font-bold mt-1"><?php echo $projectStats['active'] ?? 0; ?></p>
                    <p class="text-emerald-300 text-xs mt-2">Currently in progress</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-play text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Completed Projects Card -->
        <div class="bg-gradient-to-br from-teal-600 to-teal-700 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-teal-200 text-sm font-medium">Completed</p>
                    <p class="text-3xl font-bold mt-1"><?php echo $completedProjects; ?></p>
                    <p class="text-teal-300 text-xs mt-2">Successfully finished</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-check text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Completion Rate Card -->
        <div class="bg-gradient-to-br from-amber-600 to-amber-700 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-200 text-sm font-medium">Completion Rate</p>
                    <p class="text-3xl font-bold mt-1"><?php echo $completionPercentage; ?>%</p>
                    <p class="text-amber-300 text-xs mt-2">Project success rate</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-chart-line text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Projects -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Recent Projects</h2>
                    <a href="projects.php" class="text-slate-600 hover:text-slate-800 text-sm font-medium flex items-center gap-2">
                        View All <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
                
                <?php if (!empty($recentProjects)): ?>
                    <div class="space-y-4">
                        <?php foreach ($recentProjects as $project): ?>
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-slate-500 to-slate-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                                        <?php echo strtoupper(substr($project['title'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900"><?php echo htmlspecialchars($project['title']); ?></h3>
                                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars(substr($project['description'], 0, 50)) . '...'; ?></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        <?php echo $project['status'] === 'active' ? 'bg-emerald-100 text-emerald-800' : 
                                            ($project['status'] === 'completed' ? 'bg-teal-100 text-teal-800' : 'bg-amber-100 text-amber-800'); ?>">
                                        <?php echo ucfirst($project['status']); ?>
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1"><?php echo date('M j', strtotime($project['created_at'])); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-folder text-slate-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500 mb-2">No projects yet</p>
                        <a href="projects.php" class="text-slate-600 hover:text-slate-800 text-sm font-medium">Create your first project</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Your Tasks -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Your Tasks</h2>
                    <a href="projects.php" class="text-slate-600 hover:text-slate-800 text-sm font-medium">View All</a>
                </div>
                
                <?php if (!empty($userTasks)): ?>
                    <div class="space-y-3">
                        <?php foreach ($userTasks as $task): ?>
                            <div class="p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900 text-sm"><?php echo htmlspecialchars($task['title']); ?></h4>
                                        <p class="text-xs text-gray-600 mt-1"><?php echo htmlspecialchars($task['project_title']); ?></p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ml-2
                                        <?php echo $task['status'] === 'completed' ? 'bg-teal-100 text-teal-800' : 
                                            ($task['status'] === 'in-progress' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-800'); ?>">
                                        <?php echo ucfirst(str_replace('-', ' ', $task['status'])); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-tasks text-slate-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500 mb-2">No tasks assigned</p>
                        <p class="text-xs text-gray-400">Tasks will appear here when assigned to you</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Additional Dynamic Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <!-- Upcoming Deadlines -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Upcoming Deadlines</h2>
                <span class="text-xs text-gray-500">Next 7 days</span>
            </div>
            
            <?php if (!empty($upcomingDeadlines)): ?>
                <div class="space-y-3">
                    <?php foreach ($upcomingDeadlines as $task): ?>
                        <?php 
                        $deadline = new DateTime($task['deadline']);
                        $today = new DateTime();
                        $diff = $today->diff($deadline);
                        $daysLeft = $diff->days;
                        $isUrgent = $daysLeft <= 2;
                        ?>
                        <div class="p-3 bg-slate-50 rounded-lg border-l-4 <?php echo $isUrgent ? 'border-red-500' : 'border-amber-500'; ?>">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900 text-sm"><?php echo htmlspecialchars($task['title']); ?></h4>
                                    <p class="text-xs text-gray-600 mt-1"><?php echo htmlspecialchars($task['project_title']); ?></p>
                                    <p class="text-xs <?php echo $isUrgent ? 'text-red-600' : 'text-amber-600'; ?> font-medium mt-1">
                                        Due in <?php echo $daysLeft; ?> day<?php echo $daysLeft != 1 ? 's' : ''; ?>
                                    </p>
                                </div>
                                <span class="text-xs text-gray-500"><?php echo date('M j', strtotime($task['deadline'])); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-calendar-check text-slate-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-500 mb-2">No upcoming deadlines</p>
                    <p class="text-xs text-gray-400">You're all caught up!</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Project Progress -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Project Progress</h2>
                <span class="text-xs text-gray-500">Active projects</span>
            </div>
            
            <?php if (!empty($userProjectProgress)): ?>
                <div class="space-y-4">
                    <?php foreach ($userProjectProgress as $project): ?>
                        <?php 
                        $progress = $project['total_tasks'] > 0 ? round(($project['completed_tasks'] / $project['total_tasks']) * 100) : 0;
                        ?>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <h4 class="font-medium text-gray-900 text-sm"><?php echo htmlspecialchars($project['title']); ?></h4>
                                <span class="text-xs text-gray-500"><?php echo $progress; ?>%</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-2 rounded-full transition-all duration-300" 
                                     style="width: <?php echo $progress; ?>%"></div>
                            </div>
                            <p class="text-xs text-gray-500">
                                <?php echo $project['completed_tasks']; ?> of <?php echo $project['total_tasks']; ?> tasks completed
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-chart-bar text-slate-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-500 mb-2">No active projects</p>
                    <p class="text-xs text-gray-400">Start a project to see progress</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Team Members Section -->
    <div class="mt-8">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Team Members</h2>
                <a href="teams.php" class="text-slate-600 hover:text-slate-800 text-sm font-medium flex items-center gap-2">
                    View All <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
            
            <?php if (!empty($recentUsers)): ?>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <?php foreach ($recentUsers as $user): ?>
                        <div class="text-center group">
                            <div class="relative mb-3">
                                <img src="<?php echo $user['profile_picture'] ?? 'https://i.pravatar.cc/150?img=' . $user['id']; ?>" 
                                     alt="<?php echo htmlspecialchars($user['firstname']); ?>"
                                     class="w-16 h-16 rounded-full mx-auto border-2 border-slate-200 group-hover:border-slate-400 transition-colors object-cover">
                                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-white"></div>
                            </div>
                            <h3 class="font-medium text-gray-900 text-sm"><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></h3>
                            <p class="text-xs text-gray-500"><?php echo ucfirst($user['user_role']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-users text-slate-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-500 mb-2">No team members found</p>
                    <a href="teams.php" class="text-slate-600 hover:text-slate-800 text-sm font-medium">Invite team members</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Project Progress Chart -->
    <div class="mt-8">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Project Progress Overview</h2>
            <div class="h-80">
                <canvas id="projectChart"></canvas>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Project Progress Chart
    const ctx = document.getElementById('projectChart');
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Completed', 'In Progress', 'On Hold'],
            datasets: [{
                data: [
                    <?php echo $projectStats['active'] ?? 0; ?>,
                    <?php echo $projectStats['completed'] ?? 0; ?>,
                    <?php echo $projectStats['in-progress'] ?? 0; ?>,
                    <?php echo $projectStats['on-hold'] ?? 0; ?>
                ],
                backgroundColor: [
                    '#059669', // Emerald-600 for active
                    '#0D9488', // Teal-600 for completed
                    '#D97706', // Amber-600 for in progress
                    '#DC2626'  // Red-600 for on hold
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true
                }
            },
            cutout: '60%'
        }
    });
});
</script>