<?php
include_once('../../config/config.php');
include_once('../../config/functions.php');

header('Content-Type: application/json');

// Check if user is logged in
if (!isUserLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'filter_projects':
        filterProjects();
        break;
    case 'get_statistics':
        getStatistics();
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
}

function filterProjects() {
    try {
        $filters = $_POST['filters'] ?? [];
        $page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
        $limit = 5;
        $offset = ($page - 1) * $limit;
        
        // Get filtered projects
        $projects = getFilteredProjects($filters, $limit, $offset);
        $totalProjects = getTotalFilteredProjects($filters);
        $totalPages = ceil($totalProjects / $limit);
        
        // Generate HTML for projects
        $html = '';
        
        if (!empty($projects)) {
            foreach ($projects as $project) {
                $html .= generateProjectCard($project);
            }
        } else {
            $currentUser = getCurrentUser();
            $isAdmin = $currentUser && $currentUser['user_role'] === 'admin';
            
            if ($isAdmin) {
                $html = "<div class='text-center py-8'>
                            <p class='text-gray-500 text-lg mb-4'>No projects found.</p>
                            <p class='text-gray-400'>No projects match your current filters.</p>
                          </div>";
            } else {
                $html = "<div class='text-center py-8'>
                            <p class='text-gray-500 text-lg mb-4'>No projects found.</p>
                            <p class='text-gray-400'>No enrolled projects match your current filters.</p>
                          </div>";
            }
        }
        
        // Generate pagination HTML
        $paginationHtml = '';
        if ($totalPages > 1) {
            $paginationHtml = '<div class="flex justify-center mt-6 space-x-2">';
            for ($i = 1; $i <= $totalPages; $i++) {
                $activeClass = $i == $page ? 'bg-blue-600 text-white' : 'bg-white text-blue-600 border-blue-600';
                $paginationHtml .= "<button onclick='loadPage($i)' class='px-3 py-1 rounded-md border $activeClass'>$i</button>";
            }
            $paginationHtml .= '</div>';
        }
        
        echo json_encode([
            'status' => 'success',
            'html' => $html,
            'pagination' => $paginationHtml,
            'count' => $totalProjects,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
        
    } catch (Exception $e) {
        error_log("Error filtering projects: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while filtering projects']);
    }
}

function getStatistics() {
    try {
        $stats = getProjectStatistics();
        
        echo json_encode([
            'status' => 'success',
            'statistics' => $stats
        ]);
        
    } catch (Exception $e) {
        error_log("Error getting statistics: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while getting statistics']);
    }
}

function generateProjectCard($project) {
    // Calculate progress
    $totalTask = $project['total_tasks'] ?? 0;
    $completedTask = $project['completed_tasks'] ?? 0;
    $progress_percentage = $totalTask > 0 ? ($completedTask / $totalTask) * 100 : 0;
    $progress_percentage = round($progress_percentage, 2);
    
    // Determine progress bar color
    $progress_class = 'blue';
    if ($progress_percentage > 25 && $progress_percentage != 100) {
        $progress_class = 'orange';
    } elseif ($progress_percentage >= 100) {
        $progress_class = 'green';
    }
    
    // Get project meta for team members
    $projectMeta = getProjectMeta($project['id']);
    $teamMembers = is_array($projectMeta) ? count($projectMeta) : 0;
    
    $shortTitle = mb_strlen($project['title']) > 50 ? mb_substr($project['title'], 0, 50) . '...' : $project['title'];
    
    return "
    <div onclick=\"window.location='/main/dashboard/templates/single.php?pid={$project['id']}';\" 
         class=\"w-full bg-white rounded-xl shadow-md p-4 mb-4 hover:shadow-lg transition-shadow duration-200 cursor-pointer\">
        <!-- Category -->
        <p class=\"text-sm text-blue-600 font-medium mb-1 rounded-full px-2 w-fit " . getClasses($project['priority']) . "\">
            " . ucwords(str_replace('_', ' ', $project['priority'])) . "
        </p>
        <!-- Title -->
        <h3 class=\"text-lg font-semibold text-gray-900 mb-1\">
            " . htmlspecialchars($shortTitle) . "
        </h3>
        <!-- Progress Section -->
        <div class=\"flex items-center justify-between mb-3\">
            <div class=\"w-3/4\">
                <p class=\"text-sm text-gray-500 mb-1\">Progress</p>
                <div class=\"w-full bg-gray-200 rounded-full h-2.5\">
                    <div class=\"bg-{$progress_class}-400 h-2.5 rounded-full\" 
                         style=\"width: {$progress_percentage}%\"></div>
                </div>
            </div>
            <p class=\"text-lg font-semibold text-gray-900\">{$progress_percentage}%</p>
        </div>
        <!-- Avatars and Counters -->
        <div class=\"flex items-center justify-between\">
            <div class=\"flex -space-x-2\">
                " . generateTeamAvatars($projectMeta) . "
            </div>
            <div class=\"flex items-center space-x-3\">
                <div class=\"flex items-center space-x-1 text-gray-500\">
                    <svg class=\"w-4 h-4\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">
                        <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" 
                              d=\"M8 10h.01M12 10h.01M16 10h.01M9 16H5v-2a2 2 0 012-2h10a2 2 0 012 2v2h-4m-6 0h.01M12 16h.01\">
                        </path>
                    </svg>
                    <span class=\"text-sm\">{$totalTask}</span>
                </div>
                <div class=\"flex items-center space-x-1 text-gray-500\">
                    <svg class=\"w-4 h-4\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">
                        <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" 
                              d=\"M5 13l4 4L19 7\"></path>
                    </svg>
                    <span class=\"text-sm\">{$completedTask}</span>
                </div>
            </div>
        </div>
    </div>";
}

function generateTeamAvatars($projectMeta) {
    $html = '';
    if (is_array($projectMeta) && !empty($projectMeta)) {
        foreach ($projectMeta as $projectM) {
            $html .= "<img src=\"" . ($projectM['profile_picture'] ?? 'https://i.pravatar.cc/40?img=4') . "\" alt=\"Avatar\" 
                           class=\"w-8 h-8 rounded-full border-2 border-white object-cover\">";
        }
    }
    return $html;
}
?> 