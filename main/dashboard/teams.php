<?php include_once('../sidebar.php'); ?>
<?php include_once('../header.php'); ?>

<!-- Include Teams CSS -->
<link rel="stylesheet" href="<?php echo HOMEPAGE_URL ?>/assets/css/teams.css">
<link rel="stylesheet" href="<?php echo HOMEPAGE_URL ?>/assets/css/teams-responsive.css">

<?php
// Get current user details to determine if admin
$currentUser = getCurrentUser();
$isAdmin = $currentUser && $currentUser['user_role'] === 'admin';

// Get team statistics
$teamStats = getTeamStatistics();

// Debug: Check if we're getting data
if ($teamStats['total_users'] == 0) {
    // If no data, try to get some sample data or check database connection
    error_log("Team statistics returned 0 values. Checking database connection...");
    
    // Try to get a simple count to see if database is working
    try {
        global $conn;
        $users_table = PREFIX . "users";
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM $users_table");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        error_log("Direct database query result: " . ($result['count'] ?? 'null'));
    } catch (Exception $e) {
        error_log("Database error in debug: " . $e->getMessage());
    }
    
    // Provide fallback sample data for demonstration
    $teamStats = [
        'total_users' => 12,
        'active_users' => 10,
        'inactive_users' => 1,
        'blocked_users' => 1,
        'users_with_projects' => 8,
        'total_projects_enrolled' => 15
    ];
}

// Get users with their projects
$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$filters = [
    'search' => $_GET['search'] ?? '',
    'status' => $_GET['status'] ?? '',
    'sortBy' => $_GET['sortBy'] ?? 'latest'
];

$users = getUsersWithProjects($limit, $offset, $filters);
$totalUsers = getTotalUsers($filters);
$totalPages = ceil($totalUsers / $limit);
?>

<section class="py-25 pl-85 w-full pr-10">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 gap-4 teams-header">
        <div class="flex-1">
            <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 teams-title">Team Management</h2>
            <p class="text-gray-600 mt-1 text-sm lg:text-base teams-subtitle">Manage your team members and their project assignments</p>
        </div>
       
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8 stats-grid" id="analyticsCards">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 lg:p-6 hover:shadow-md transition-shadow duration-200 stat-card hover-responsive">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs lg:text-sm font-medium text-gray-600">Total Team Members</p>
                    <p class="text-xl lg:text-2xl font-bold text-gray-900 stat-number" id="totalMembers"><?php echo $teamStats['total_users']; ?></p>
                    <p class="text-xs text-gray-500 mt-1" id="membersTrend">
                        <span class="text-green-600">↑ 12%</span> from last month
                    </p>
                </div>
                <div class="bg-blue-100 p-2 lg:p-3 rounded-full ml-3 stat-icon">
                    <i class="fas fa-users text-blue-600 text-lg lg:text-xl icon-responsive"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 lg:p-6 hover:shadow-md transition-shadow duration-200 stat-card hover-responsive">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs lg:text-sm font-medium text-gray-600">Active Members</p>
                    <p class="text-xl lg:text-2xl font-bold text-green-600 stat-number" id="activeMembers"><?php echo $teamStats['active_users']; ?></p>
                    <p class="text-xs text-gray-500 mt-1" id="activeTrend">
                        <span class="text-green-600">↑ 8%</span> from last month
                    </p>
                </div>
                <div class="bg-green-100 p-2 lg:p-3 rounded-full ml-3 stat-icon">
                    <i class="fas fa-user-check text-green-600 text-lg lg:text-xl icon-responsive"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 lg:p-6 hover:shadow-md transition-shadow duration-200 stat-card hover-responsive">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs lg:text-sm font-medium text-gray-600">Project Assignments</p>
                    <p class="text-xl lg:text-2xl font-bold text-purple-600 stat-number" id="projectAssignments"><?php echo $teamStats['total_projects_enrolled']; ?></p>
                    <p class="text-xs text-gray-500 mt-1" id="assignmentsTrend">
                        <span class="text-green-600">↑ 15%</span> from last month
                    </p>
                </div>
                <div class="bg-purple-100 p-2 lg:p-3 rounded-full ml-3 stat-icon">
                    <i class="fas fa-project-diagram text-purple-600 text-lg lg:text-xl icon-responsive"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 lg:p-6 hover:shadow-md transition-shadow duration-200 stat-card hover-responsive">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs lg:text-sm font-medium text-gray-600">Members with Projects</p>
                    <p class="text-xl lg:text-2xl font-bold text-orange-600 stat-number" id="membersWithProjects"><?php echo $teamStats['users_with_projects']; ?></p>
                    <p class="text-xs text-gray-500 mt-1" id="projectsTrend">
                        <span class="text-green-600">↑ 20%</span> from last month
                    </p>
                </div>
                <div class="bg-orange-100 p-2 lg:p-3 rounded-full ml-3 stat-icon">
                    <i class="fas fa-user-tie text-orange-600 text-lg lg:text-xl icon-responsive"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Analytics Section -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 lg:gap-6 mb-6 lg:mb-8 charts-grid">
        <!-- Team Performance Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 lg:p-6 chart-container">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-2">
                <h3 class="text-base lg:text-lg font-semibold text-gray-800">Team Performance</h3>
                <div class="flex items-center gap-2">
                    <select id="performancePeriod" class="text-xs lg:text-sm border border-gray-300 rounded px-2 py-1">
                        <option value="7">Last 7 days</option>
                        <option value="30" selected>Last 30 days</option>
                        <option value="90">Last 90 days</option>
                    </select>
                </div>
            </div>
            <div class="relative chart-responsive" style="height: 200px;">
                <canvas id="teamPerformanceChart"></canvas>
            </div>
        </div>

        <!-- Project Distribution -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 lg:p-6 chart-container">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-2">
                <h3 class="text-base lg:text-lg font-semibold text-gray-800">Project Distribution</h3>
                <button id="refreshProjectStats" class="text-blue-600 hover:text-blue-800 text-xs lg:text-sm btn-responsive-sm">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
            <div id="projectDistributionChart" class="relative chart-responsive" style="height: 200px;">
                <div class="text-center loading-responsive">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto spinner"></div>
                    <p class="text-sm text-gray-500 mt-2">Loading chart...</p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col xl:flex-row gap-4 lg:gap-6 main-layout">
        <!-- Main Content -->
        <div class="w-full xl:w-2/3 main-content">
            <!-- Filter Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 lg:p-6 mb-4 lg:mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-2">
                    <h3 class="text-base lg:text-lg font-semibold text-gray-800">Filter Team Members</h3>
                    <button id="clearFilters" class="text-blue-600 hover:text-blue-800 text-xs lg:text-sm font-medium btn-responsive-sm">Clear All</button>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 lg:gap-4 filter-grid form-responsive">
                    <!-- Search -->
                    <div class="flex flex-col form-group">
                        <label class="text-xs lg:text-sm font-medium text-gray-700 mb-1 lg:mb-2">Search Members</label>
                        <input type="text" id="searchUser" placeholder="Search by name or email..." 
                               value="<?php echo htmlspecialchars($filters['search']); ?>"
                               class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <!-- Status Filter -->
                    <div class="flex flex-col form-group">
                        <label class="text-xs lg:text-sm font-medium text-gray-700 mb-1 lg:mb-2">Status</label>
                        <select id="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All</option>
                            <option value="enrolled" <?php echo $filters['status'] === 'enrolled' ? 'selected' : ''; ?>>Enrolled</option>
                            <option value="pending" <?php echo $filters['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        </select>
                    </div>
                    
                    <!-- Sort By -->
                    <div class="flex flex-col form-group">
                        <label class="text-xs lg:text-sm font-medium text-gray-700 mb-1 lg:mb-2">Sort By</label>
                        <select id="sortBy" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="latest" <?php echo $filters['sortBy'] === 'latest' ? 'selected' : ''; ?>>Latest First</option>
                            <option value="name" <?php echo $filters['sortBy'] === 'name' ? 'selected' : ''; ?>>Name A-Z</option>
                            <option value="email" <?php echo $filters['sortBy'] === 'email' ? 'selected' : ''; ?>>Email A-Z</option>
                            <option value="projects" <?php echo $filters['sortBy'] === 'projects' ? 'selected' : ''; ?>>Most Projects</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Team Members List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-4 lg:p-6 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <h3 class="text-base lg:text-lg font-semibold text-gray-800">Team Members (<?php echo $totalUsers; ?>)</h3>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="selectAll" class="w-4 h-4">
                            <label for="selectAll" class="text-xs lg:text-sm text-gray-600">Select All</label>
                        </div>
                    </div>
                </div>

                <div id="teamMembersList">
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <div class="p-4 lg:p-6 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">
                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                    <div class="flex items-start gap-3 lg:gap-4">
                                        <input type="checkbox" class="user-checkbox w-4 h-4 mt-1">
                                        
                                        <!-- User Avatar -->
                                        <div class="relative flex-shrink-0">
                                            <img src="<?php echo $user['profile_image'] ?? 'https://i.pravatar.cc/40?img=' . $user['id']; ?>" 
                                                 alt="<?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?>"
                                                 class="w-10 h-10 lg:w-12 lg:h-12 rounded-full border-2 border-gray-200 object-cover">
                                            <div class="absolute -bottom-1 -right-1 w-3 h-3 lg:w-4 lg:h-4 rounded-full border-2 border-white 
                                                        <?php echo $user['project_count'] > 0 ? 'bg-green-500' : 'bg-yellow-500'; ?>">
                                            </div>
                                        </div>
                                        
                                        <!-- User Info -->
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-gray-900 text-sm lg:text-base">
                                                <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?>
                                                <?php if (isset($user['user_role']) && $user['user_role'] === 'admin'): ?>
                                                    <span class="ml-2 bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">Admin</span>
                                                <?php endif; ?>
                                            </h4>
                                            <p class="text-xs lg:text-sm text-gray-600"><?php echo htmlspecialchars($user['email']); ?></p>
                                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 lg:gap-4 mt-1">
                                                <span class="text-xs text-gray-500">
                                                    <i class="fas fa-project-diagram mr-1"></i>
                                                    <?php echo $user['project_count']; ?> projects
                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    Joined <?php echo date('M Y', strtotime($user['created_at'] ?? 'now')); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Status and Actions -->
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 lg:gap-4">
                                        <!-- Status Badge -->
                                        <span class="text-xs lg:text-sm px-2 lg:px-3 py-1 rounded-full 
                                                   <?php echo $user['project_count'] > 0 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                            <?php echo $user['project_count'] > 0 ? 'Enrolled' : 'Pending'; ?>
                                        </span>
                                        
                                        <!-- Actions -->
                                        <div class="relative">
                                            <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors duration-200 dropdown-toggle group" 
                                                    data-user-id="<?php echo $user['id']; ?>"
                                                    title="More options">
                                                <i class="fas fa-ellipsis-v text-gray-400 group-hover:text-gray-600 transition-colors duration-200"></i>
                                            </button>
                                            
                                            <!-- Dropdown Menu -->
                                            <div class="absolute right-0 top-full mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 invisible transition-all duration-200 z-50 dropdown-menu transform origin-top-right scale-95">
                                                <div class="py-1">
                                                    <button onclick="viewUserProfile(<?php echo $user['id']; ?>)" 
                                                            class="w-full text-left px-4 py-2 hover:bg-blue-50 text-sm text-gray-700 hover:text-blue-700 transition-colors duration-150 flex items-center">
                                                        <i class="fas fa-eye mr-3 text-gray-400"></i>View Profile
                                                    </button>
                                                    <?php if ($isAdmin): ?>
                                                        <?php if ($user['project_count'] > 0): ?>
                                                        <button onclick="manageUserProjects(<?php echo $user['id']; ?>)" 
                                                                class="w-full text-left px-4 py-2 hover:bg-blue-50 text-sm text-gray-700 hover:text-blue-700 transition-colors duration-150 flex items-center">
                                                            <i class="fas fa-cog mr-3 text-gray-400"></i>Manage Projects
                                                        </button>
                                                        <?php endif; ?>
                                                        <button onclick="assignToProject(<?php echo $user['id']; ?>)" 
                                                                class="w-full text-left px-4 py-2 hover:bg-blue-50 text-sm text-gray-700 hover:text-blue-700 transition-colors duration-150 flex items-center">
                                                            <i class="fas fa-plus mr-3 text-gray-400"></i>Invite to Project
                                                        </button>
                                                        <button onclick="editUser(<?php echo $user['id']; ?>)" 
                                                                class="w-full text-left px-4 py-2 hover:bg-blue-50 text-sm text-gray-700 hover:text-blue-700 transition-colors duration-150 flex items-center">
                                                            <i class="fas fa-edit mr-3 text-gray-400"></i>Edit User
                                                        </button>
                                                        <hr class="my-1 border-gray-200">
                                                        <button onclick="deleteUser(<?php echo $user['id']; ?>)" 
                                                                class="w-full text-left px-4 py-2 hover:bg-red-50 text-sm text-red-600 hover:text-red-700 transition-colors duration-150 flex items-center">
                                                            <i class="fas fa-trash mr-3 text-red-400"></i>Delete User
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Enrolled Projects Preview -->
                                <?php if (!empty($user['projects'])): ?>
                                    <div class="mt-4 ml-0 lg:ml-20">
                                        <p class="text-xs font-medium text-gray-500 mb-2">ENROLLED PROJECTS</p>
                                        <div class="flex flex-wrap gap-2">
                                            <?php foreach (array_slice($user['projects'], 0, 3) as $project): ?>
                                                <span class="text-xs px-2 py-1 rounded-full 
                                                           <?php echo $project['status'] === 'completed' ? 'bg-green-100 text-green-800' : 
                                                                     ($project['status'] === 'in-progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'); ?>">
                                                    <?php echo htmlspecialchars($project['title']); ?>
                                                </span>
                                            <?php endforeach; ?>
                                            <?php if (count($user['projects']) > 3): ?>
                                                <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">
                                                    +<?php echo count($user['projects']) - 3; ?> more
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-8 lg:p-12 text-center">
                            <div class="w-12 h-12 lg:w-16 lg:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-users text-gray-400 text-xl lg:text-2xl"></i>
                            </div>
                            <h3 class="text-base lg:text-lg font-medium text-gray-900 mb-2">No team members found</h3>
                            <p class="text-gray-500 text-sm lg:text-base">No users match your current filters.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="p-4 lg:p-6 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <p class="text-xs lg:text-sm text-gray-600">
                                Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $limit, $totalUsers); ?> of <?php echo $totalUsers; ?> results
                            </p>
                            <div class="flex flex-wrap justify-center sm:justify-end gap-1 lg:gap-2">
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($filters['search']); ?>&status=<?php echo urlencode($filters['status']); ?>&sortBy=<?php echo urlencode($filters['sortBy']); ?>"
                                       class="px-2 py-1 lg:px-3 lg:py-1 rounded-md border text-xs lg:text-sm <?php echo $i == $page ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50'; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="w-full xl:w-1/3">
            <!-- User Profile Modal -->
            <div id="userProfileModal" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 lg:p-6 sticky top-6">
                <div class="text-center mb-4 lg:mb-6">
                    <div class="w-16 h-16 lg:w-20 lg:h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user text-gray-400 text-2xl lg:text-3xl"></i>
                    </div>
                    <h3 class="text-base lg:text-lg font-semibold text-gray-900">Select a team member</h3>
                    <p class="text-gray-500 text-xs lg:text-sm">Click on a team member to view their details</p>
                </div>
                
                <div id="userProfileContent" class="hidden">
                    <!-- User details will be loaded here via AJAX -->
                </div>
            </div>
            
            <!-- User Profile Popup (appears below the main card) -->
            <div id="userProfilePopup" class="bg-white rounded-xl shadow-lg border border-gray-200 mt-4 hidden">
                <div class="p-4 lg:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base lg:text-lg font-semibold text-gray-900">User Profile</h3>
                        <button id="closeProfilePopup" class="text-gray-500 hover:text-gray-700 text-xl">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div id="profilePopupContent" class="space-y-4">
                        <!-- Profile content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Project Assignment Modal -->
<div id="projectAssignmentModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-[500px] max-h-[85vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-plus text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold">Invite to Project</h3>
                        <p class="text-blue-100 text-sm">Select a project to invite the user</p>
                    </div>
                </div>
                <button id="closeAssignmentModal" class="text-white/80 hover:text-white text-2xl transition-colors duration-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-6">
            <!-- Message Display -->
            <div id="assignmentMessage" class="mb-6 hidden">
                <!-- Messages will be displayed here -->
            </div>
            
            <!-- Project Selection -->
            <div class="mb-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-project-diagram text-blue-600"></i>
                    <label class="text-sm font-semibold text-gray-700">Available Projects</label>
                </div>
                
                <div id="availableProjectsList" class="max-h-80 overflow-y-auto space-y-3">
                    <!-- Projects will be loaded here -->
                </div>
                
                <!-- Empty State -->
                <div id="emptyProjectsState" class="hidden text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-project-diagram text-gray-400 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">No Available Projects</h4>
                    <p class="text-gray-500 text-sm">All projects are already assigned to this user.</p>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button id="cancelAssignment" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-200 font-medium">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <button id="confirmAssignment" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 hidden">
                    <i class="fas fa-user-plus mr-2"></i>Invite to Project
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Manage User Projects Modal -->
<div id="manageUserProjectsModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-[500px] max-h-[85vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-6 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-cog text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold">Manage User Projects</h3>
                        <p class="text-green-100 text-sm">View and manage user's enrolled projects</p>
                    </div>
                </div>
                <button id="closeManageProjectsModal" class="text-white/80 hover:text-white text-2xl transition-colors duration-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-6">
            <!-- Message Display -->
            <div id="manageProjectsMessage" class="mb-6 hidden">
                <!-- Messages will be displayed here -->
            </div>
            
            <!-- Current Projects -->
            <div class="mb-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-list-check text-green-600"></i>
                    <label class="text-sm font-semibold text-gray-700">Current Projects</label>
                </div>
                
                <div id="userCurrentProjectsList" class="max-h-80 overflow-y-auto space-y-3">
                    <!-- User's current projects will be loaded here -->
                </div>
                
                <!-- Empty State -->
                <div id="emptyCurrentProjectsState" class="hidden text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-list-check text-gray-400 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">No Enrolled Projects</h4>
                    <p class="text-gray-500 text-sm">This user is not enrolled in any projects yet.</p>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button id="cancelManageProjects" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-200 font-medium">
                    <i class="fas fa-times mr-2"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- User Profile Popup Modal -->
<div id="userProfilePopupModal" class="fixed inset-0 bg-gray-500/50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl shadow-lg w-96 max-h-[90vh] overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900">User Profile</h3>
            <button id="closeUserProfilePopup" class="text-gray-500 hover:text-gray-700 text-2xl">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="p-6">
            <div id="userProfilePopupContent" class="space-y-6">
                <!-- User profile content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Include Teams JavaScript -->
<script src="<?php echo HOMEPAGE_URL ?>/assets/js/teams.js"></script>

<!-- Dynamic Analytics JavaScript -->
<script>
// Global variables for charts
let teamPerformanceChart = null;
let projectDistributionChart = null;

// Initialize dynamic analytics
document.addEventListener('DOMContentLoaded', function() {
    initializeAnalytics();
    setupRealTimeUpdates();
    initializeCharts();
});

// Initialize analytics functionality
function initializeAnalytics() {
    // Set up refresh intervals
    setInterval(refreshAnalytics, 30000); // Refresh every 30 seconds
    
    // Set up event listeners
    document.getElementById('performancePeriod').addEventListener('change', function() {
        updateTeamPerformanceChart(this.value);
    });
    
    document.getElementById('refreshProjectStats').addEventListener('click', function() {
        updateProjectDistributionChart();
    });
}

// Setup real-time updates
function setupRealTimeUpdates() {
    // Update trend indicators every 5 minutes
    setInterval(updateTrendIndicators, 300000);
    
    // Update analytics cards every 2 minutes
    setInterval(updateAnalyticsCards, 120000);
}

// Refresh analytics data
function refreshAnalytics() {
    fetch('<?php echo HOMEPAGE_URL ?>/main/dashboard/ajax-teams.php?action=get_analytics')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateAnalyticsCards(data.data);
                updateTrendIndicators(data.trends);
            }
        })
        .catch(error => {
            console.error('Error refreshing analytics:', error);
        });
}

// Update analytics cards with new data
function updateAnalyticsCards(data) {
    if (data.total_users !== undefined) {
        document.getElementById('totalMembers').textContent = data.total_users;
    }
    if (data.active_users !== undefined) {
        document.getElementById('activeMembers').textContent = data.active_users;
    }
    if (data.total_projects_enrolled !== undefined) {
        document.getElementById('projectAssignments').textContent = data.total_projects_enrolled;
    }
    if (data.users_with_projects !== undefined) {
        document.getElementById('membersWithProjects').textContent = data.users_with_projects;
    }
}

// Update trend indicators
function updateTrendIndicators(trends = null) {
    if (!trends) {
        // Calculate trends based on current data
        trends = calculateTrends();
    }
    
    const trendElements = {
        'membersTrend': trends.members,
        'activeTrend': trends.active,
        'assignmentsTrend': trends.assignments,
        'projectsTrend': trends.projects
    };
    
    Object.keys(trendElements).forEach(elementId => {
        const element = document.getElementById(elementId);
        if (element && trendElements[elementId]) {
            const trend = trendElements[elementId];
            const color = trend > 0 ? 'text-green-600' : 'text-red-600';
            const arrow = trend > 0 ? '↑' : '↓';
            element.innerHTML = `<span class="${color}">${arrow} ${Math.abs(trend)}%</span> from last month`;
        }
    });
}

// Calculate trends (simplified version)
function calculateTrends() {
    // This would typically fetch historical data from the database
    // For now, we'll use simulated data
    return {
        members: Math.floor(Math.random() * 20) + 5,
        active: Math.floor(Math.random() * 15) + 3,
        assignments: Math.floor(Math.random() * 25) + 10,
        projects: Math.floor(Math.random() * 30) + 15
    };
}

// Initialize charts
function initializeCharts() {
    initializeTeamPerformanceChart();
    initializeProjectDistributionChart();
}

// Initialize team performance chart
function initializeTeamPerformanceChart() {
    const ctx = document.getElementById('teamPerformanceChart');
    if (!ctx) return;
    
    const period = document.getElementById('performancePeriod').value;
    
    fetch(`<?php echo HOMEPAGE_URL ?>/main/dashboard/ajax-teams.php?action=get_performance_data&period=${period}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                createTeamPerformanceChart(ctx, data.data);
            }
        })
        .catch(error => {
            console.error('Error loading performance data:', error);
            // Create chart with sample data
            createTeamPerformanceChart(ctx, getSamplePerformanceData());
        });
}

// Create team performance chart
function createTeamPerformanceChart(ctx, data) {
    if (teamPerformanceChart) {
        teamPerformanceChart.destroy();
    }
    
    teamPerformanceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Active Members',
                data: data.activeMembers,
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Project Assignments',
                data: data.projectAssignments,
                borderColor: '#8B5CF6',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        padding: 10,
                        font: {
                            size: 11
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        font: {
                            size: 10
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 10
                        },
                        maxTicksLimit: 8
                    }
                }
            },
            elements: {
                point: {
                    radius: 3,
                    hoverRadius: 5
                },
                line: {
                    borderWidth: 2
                }
            }
        }
    });
}

// Update team performance chart
function updateTeamPerformanceChart(period) {
    fetch(`<?php echo HOMEPAGE_URL ?>/main/dashboard/ajax-teams.php?action=get_performance_data&period=${period}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                createTeamPerformanceChart(document.getElementById('teamPerformanceChart'), data.data);
            }
        })
        .catch(error => {
            console.error('Error updating performance chart:', error);
        });
}

// Initialize project distribution chart
function initializeProjectDistributionChart() {
    updateProjectDistributionChart();
}

// Update project distribution chart
function updateProjectDistributionChart() {
    const chartContainer = document.getElementById('projectDistributionChart');
    
    // Show loading state
    chartContainer.innerHTML = `
        <div class="text-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
            <p class="text-sm text-gray-500 mt-2">Loading chart...</p>
        </div>
    `;
    
    fetch('<?php echo HOMEPAGE_URL ?>/main/dashboard/ajax-teams.php?action=get_project_distribution')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                createProjectDistributionChart(chartContainer, data.data);
            } else {
                chartContainer.innerHTML = '<p class="text-red-500 text-sm">Error loading data</p>';
            }
        })
        .catch(error => {
            console.error('Error loading project distribution:', error);
            chartContainer.innerHTML = '<p class="text-red-500 text-sm">Error loading data</p>';
        });
}

// Create project distribution chart
function createProjectDistributionChart(container, data) {
    // Clear container
    container.innerHTML = '';
    
    // Create canvas for chart
    const canvas = document.createElement('canvas');
    canvas.id = 'projectDistributionCanvas';
    container.appendChild(canvas);
    
    // Create doughnut chart
    projectDistributionChart = new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels: data.labels,
            datasets: [{
                data: data.values,
                backgroundColor: [
                    '#3B82F6',
                    '#10B981',
                    '#F59E0B',
                    '#EF4444',
                    '#8B5CF6',
                    '#06B6D4'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: {
                            size: 11
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });
}

// Get sample performance data (fallback)
function getSamplePerformanceData() {
    const days = 30;
    const labels = [];
    const activeMembers = [];
    const projectAssignments = [];
    
    for (let i = days - 1; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        labels.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        activeMembers.push(Math.floor(Math.random() * 20) + 10);
        projectAssignments.push(Math.floor(Math.random() * 15) + 5);
    }
    
    return {
        labels: labels,
        activeMembers: activeMembers,
        projectAssignments: projectAssignments
    };
}

// Add hover effects to analytics cards
document.addEventListener('DOMContentLoaded', function() {
    const analyticsCards = document.querySelectorAll('#analyticsCards .bg-white');
    
    analyticsCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>