<?php
include_once('../../config/config.php');
include_once('../../config/functions.php');

header('Content-Type: application/json');

// Check if user is logged in
if (!isUserLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'get_user_profile':
        getUserProfile();
        break;
    case 'get_team_statistics':
        getTeamStats();
        break;
    case 'get_available_projects':
        getAvailableProjectsForUser();
        break;
    case 'assign_user_to_project':
        assignUserToProjectAjax();
        break;
    case 'remove_user_from_project':
        removeUserFromProjectAjax();
        break;
    case 'get_user_projects':
        getUserProjects();
        break;
    case 'edit_user':
        editUserAjax();
        break;
    case 'delete_user':
        deleteUserAjax();
        break;
    case 'get_notifications':
        getNotifications();
        break;
    case 'mark_notification_read':
        markNotificationRead();
        break;
    case 'mark_all_notifications_read':
        markAllNotificationsRead();
        break;
    case 'delete_notification':
        deleteNotificationAjax();
        break;
    case 'get_unread_count':
        getUnreadCount();
        break;
    case 'task_status_change':
        handleTaskStatusChange();
        break;
    case 'task_created':
        handleTaskCreated();
        break;
    case 'task_assigned':
        handleTaskAssigned();
        break;
    case 'task_creation_comprehensive':
        handleTaskCreationComprehensive();
        break;
    case 'get_analytics':
        getAnalyticsData();
        break;
    case 'get_performance_data':
        getPerformanceData();
        break;
    case 'get_project_distribution':
        getProjectDistribution();
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
}

function getUserProfile() {
    try {
        $user_id = $_POST['user_id'] ?? 0;
        
        if (!$user_id) {
            echo json_encode(['status' => 'error', 'message' => 'User ID is required']);
            return;
        }
        
        // Get user details
        $user = getUsersDetailsByUser_id($user_id);
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
            return;
        }
        
        // Get user's enrolled projects using the correct function
        $userProjects = getUserProjectAssignments($user_id);
        
        // Generate HTML for user profile
        $html = generateUserProfileHTML($user, $userProjects);
        
        echo json_encode([
            'status' => 'success',
            'html' => $html
        ]);
        
    } catch (Exception $e) {
        error_log("Error getting user profile: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while getting user profile']);
    }
}

function getTeamStats() {
    try {
        $stats = getTeamStatistics();
        
        echo json_encode([
            'status' => 'success',
            'statistics' => $stats
        ]);
        
    } catch (Exception $e) {
        error_log("Error getting team statistics: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while getting team statistics']);
    }
}

function getAvailableProjectsForUser() {
    try {
        $user_id = $_POST['user_id'] ?? null;
        
        // Get available projects for the user
        $projects = getAvailableProjects($user_id);
        
        // Generate HTML for project selection
        $html = '';
        if (!empty($projects)) {
            $html .= '<div class="space-y-2">';
            foreach ($projects as $project) {
                $html .= "
                <div class='flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer project-option' 
                     data-project-id='{$project['id']}' data-project-title='" . htmlspecialchars($project['title']) . "'>
                    <div class='flex items-center gap-3'>
                        <div class='w-3 h-3 rounded-full bg-blue-500'></div>
                        <div>
                            <span class='font-medium text-gray-900'>" . htmlspecialchars($project['title']) . "</span>
                        </div>
                    </div>
                    <span class='text-xs px-2 py-1 rounded-full " . getClasses($project['priority']) . "'>" . ucfirst($project['priority']) . "</span>
                </div>";
            }
            $html .= '</div>';
        } else {
            $html = "
            <div class='text-center py-6'>
                <div class='w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3'>
                    <i class='fas fa-project-diagram text-gray-400'></i>
                </div>
                <p class='text-gray-500 text-sm'>No available projects to assign</p>
            </div>";
        }
        
        echo json_encode([
            'status' => 'success',
            'html' => $html,
            'projects' => $projects
        ]);
        
    } catch (Exception $e) {
        error_log("Error getting available projects: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while getting available projects']);
    }
}

function assignUserToProjectAjax() {
    try {
        $user_id = $_POST['user_id'] ?? 0;
        $project_id = $_POST['project_id'] ?? 0;
        
        if (!$user_id || !$project_id) {
            echo json_encode(['status' => 'error', 'message' => 'User ID and Project ID are required']);
            return;
        }
        
        // Check permissions
        if (!canAssignUserToProject($user_id, $project_id)) {
            echo json_encode(['status' => 'error', 'message' => 'You do not have permission to assign users to projects']);
            return;
        }
        
        // Assign user to project
        $result = assignUserToProject($user_id, $project_id);
        
        if ($result['status'] === 'success') {
            // Get project details for notification
            $project = getProjectById($project_id);
            $project_title = $project['title'] ?? 'Unknown Project';
            
            // Create notification for the assigned user
            createAssignmentNotification($user_id, $project_id, $project_title, $_SESSION['user_id'] ?? null);
            
            // Get updated user data
            $user = getUsersDetailsByUser_id($user_id);
            $userProjects = getUserProjectAssignments($user_id);
            
            echo json_encode([
                'status' => 'success',
                'message' => $result['message'],
                'user' => $user,
                'projects' => $userProjects,
                'project_count' => count($userProjects)
            ]);
        } else {
            echo json_encode($result);
        }
        
    } catch (Exception $e) {
        error_log("Error assigning user to project: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while assigning user to project']);
    }
}

function removeUserFromProjectAjax() {
    try {
        $user_id = $_POST['user_id'] ?? 0;
        $project_id = $_POST['project_id'] ?? 0;
        
        if (!$user_id || !$project_id) {
            echo json_encode(['status' => 'error', 'message' => 'User ID and Project ID are required']);
            return;
        }
        
        // Check permissions (same as assignment)
        if (!canAssignUserToProject($user_id, $project_id)) {
            echo json_encode(['status' => 'error', 'message' => 'You do not have permission to remove users from projects']);
            return;
        }
        
        // Get project details before removal for notification
        $project = getProjectById($project_id);
        $project_title = $project['title'] ?? 'Unknown Project';
        
        // Remove user from project
        $result = removeUserFromProject($user_id, $project_id);
        
        if ($result['status'] === 'success') {
            // Create notification for the removed user
            createRemovalNotification($user_id, $project_id, $project_title, $_SESSION['user_id'] ?? null);
            
            // Get updated user data
            $user = getUsersDetailsByUser_id($user_id);
            $userProjects = getUserProjectAssignments($user_id);
            
            echo json_encode([
                'status' => 'success',
                'message' => $result['message'],
                'user' => $user,
                'projects' => $userProjects,
                'project_count' => count($userProjects)
            ]);
        } else {
            echo json_encode($result);
        }
        
    } catch (Exception $e) {
        error_log("Error removing user from project: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while removing user from project']);
    }
}

function getUserProjects() {
    try {
        $user_id = $_POST['user_id'] ?? 0;
        
        if (!$user_id) {
            echo json_encode(['status' => 'error', 'message' => 'User ID is required']);
            return;
        }
        
        // Get user's current projects
        $projects = getUserProjectAssignments($user_id);
        
        // Generate HTML for project list
        $html = '';
        if (!empty($projects)) {
            $html .= '<div class="space-y-2">';
            foreach ($projects as $project) {
                $html .= "
                <div class='flex items-center justify-between p-3 bg-gray-50 rounded-lg'>
                    <div class='flex items-center gap-3'>
                        <div class='w-3 h-3 rounded-full bg-blue-500'></div>
                        <div>
                            <span class='font-medium text-gray-900'>" . htmlspecialchars($project['title']) . "</span>
                            <p class='text-xs text-gray-500'>" . ucfirst($project['priority']) . " Priority</p>
                        </div>
                    </div>
                    <div class='flex items-center gap-2'>
                        <span class='text-xs px-2 py-1 rounded-full " . getClasses($project['project_status']) . "'>" . ucfirst(str_replace('-', ' ', $project['project_status'])) . "</span>
                        <button onclick='removeUserFromProject({$user_id}, {$project['project_id']})' class='text-red-600 hover:text-red-800 text-sm'>
                            <i class='fas fa-times'></i>
                        </button>
                    </div>
                </div>";
            }
            $html .= '</div>';
        } else {
            $html = "
            <div class='text-center py-6'>
                <div class='w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3'>
                    <i class='fas fa-project-diagram text-gray-400'></i>
                </div>
                <p class='text-gray-500 text-sm'>No projects enrolled yet</p>
            </div>";
        }
        
        echo json_encode([
            'status' => 'success',
            'html' => $html,
            'projects' => $projects
        ]);
        
    } catch (Exception $e) {
        error_log("Error getting user projects: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while getting user projects']);
    }
}

function generateUserProfileHTML($user, $projects) {
    // Get current user's role to determine admin permissions
    $currentUser = getCurrentUser();
    $isAdmin = $currentUser && $currentUser['user_role'] === 'admin';
    
    $fullName = htmlspecialchars($user['firstname'] . ' ' . $user['lastname']);
    $email = htmlspecialchars($user['email']);
    $status = $user['user_status'] ?? 'active';
    $role = $user['user_role'] ?? 'user';
    $joinDate = date('F j, Y', strtotime($user['created_at'] ?? 'now'));
    
    // Status color classes
    $statusClass = $status === 'active' ? 'bg-green-100 text-green-800' : 
                   ($status === 'blocked' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800');
    
    $html = "
    <div class='space-y-6'>
        <!-- User Header with Profile Picture -->
        <div class='text-center'>
            <img src='" . ($user['profile_image'] ?? 'https://i.pravatar.cc/120?img=' . $user['id']) . "' 
                 alt='{$fullName}' 
                 class='w-24 h-24 rounded-full border-4 border-gray-200 mx-auto mb-4 shadow-lg object-cover'>
            <h3 class='text-xl font-bold text-gray-900 mb-1'>{$fullName}</h3>
            <p class='text-gray-600 mb-3'>{$email}</p>
            <div class='flex items-center justify-center gap-2 mb-4'>
                <span class='text-sm px-3 py-1 rounded-full {$statusClass}'>
                    " . ucfirst($status) . "
                </span>
                " . ($role === 'admin' ? "<span class='text-sm px-3 py-1 rounded-full bg-purple-100 text-purple-800'>Admin</span>" : "") . "
            </div>
        </div>
        
        <!-- User Stats -->
        <div class='grid grid-cols-2 gap-4'>
            <div class='bg-gray-50 p-4 rounded-lg text-center'>
                <p class='text-2xl font-bold text-gray-900'>" . count($projects) . "</p>
                <p class='text-sm text-gray-600'>Enrolled Projects</p>
            </div>
            <div class='bg-gray-50 p-4 rounded-lg text-center'>
                <p class='text-sm font-medium text-gray-900'>" . date('M Y', strtotime($user['created_at'] ?? 'now')) . "</p>
                <p class='text-sm text-gray-600'>Joined</p>
            </div>
        </div>
        
        <!-- Enrolled Projects -->
        <div>
            <h4 class='font-semibold text-gray-900 mb-3 flex items-center'>
                <i class='fas fa-project-diagram mr-2 text-blue-600'></i>
                Enrolled Projects
            </h4>
            " . generateProjectsList($projects) . "
        </div>
        
        <!-- Action Buttons -->
        <div class='space-y-3 pt-4 border-t border-gray-200'>
            " . ($isAdmin ? "
            <button onclick='assignToProject({$user['id']})' class='w-full bg-white text-indigo-600 border border-indigo-500 py-2.5 px-4 rounded-lg hover:bg-indigo-50 hover:border-indigo-600 transition-all duration-200 text-sm font-medium shadow-sm hover:shadow-md'>
                <i class='fas fa-plus mr-2'></i>Invite to Project
            </button>
            <button onclick='deleteUserFromPopup({$user['id']})' class='w-full bg-white text-rose-600 border border-rose-500 py-2.5 px-4 rounded-lg hover:bg-rose-50 hover:border-rose-600 transition-all duration-200 text-sm font-medium shadow-sm hover:shadow-md'>
                <i class='fas fa-trash mr-2'></i>Delete User
            </button>
            " : "") . "
        </div>
    </div>";
    
    return $html;
}

function generateProjectsList($projects) {
    if (empty($projects)) {
        return "
        <div class='text-center py-6'>
            <div class='w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3'>
                <i class='fas fa-project-diagram text-gray-400'></i>
            </div>
            <p class='text-gray-500 text-sm'>No projects enrolled yet</p>
        </div>";
    }
    
    $html = "<div class='space-y-2'>";
    
    foreach ($projects as $project) {
        $title = htmlspecialchars($project['title']);
        $status = $project['project_status'] ?? 'unknown';
        $priority = $project['priority'] ?? 'medium';
        
        // Status color classes
        $statusClass = $status === 'completed' ? 'bg-green-100 text-green-800' : 
                       ($status === 'in-progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800');
        
        $html .= "
        <div class='flex items-center justify-between p-3 bg-gray-50 rounded-lg'>
            <div class='flex items-center gap-3'>
                <div class='w-2 h-2 rounded-full " . ($status === 'completed' ? 'bg-green-500' : ($status === 'in-progress' ? 'bg-blue-500' : 'bg-gray-400')) . "'></div>
                <div>
                    <span class='font-medium text-gray-900'>{$title}</span>
                    <p class='text-xs text-gray-500'>" . ucfirst($priority) . " Priority</p>
                </div>
            </div>
            <span class='text-xs px-2 py-1 rounded-full {$statusClass}'>
                " . ucfirst(str_replace('-', ' ', $status)) . "
            </span>
        </div>";
    }
    
    $html .= "</div>";
    
    return $html;
}

function deleteUserAjax() {
    try {
        $user_id = $_POST['user_id'] ?? 0;
        if (!$user_id) {
            echo json_encode(['status' => 'error', 'message' => 'User ID is required']);
            return;
        }
        $result = deleteUserById($user_id);
        echo json_encode($result);
    } catch (Exception $e) {
        error_log("Error deleting user: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while deleting user']);
    }
}

function editUserAjax() {
    try {
        $user_id = $_POST['user_id'] ?? 0;
        $data = $_POST['data'] ?? [];
        if (!$user_id || empty($data) || !is_array($data)) {
            echo json_encode(['status' => 'error', 'message' => 'User ID and data are required']);
            return;
        }
        
        $result = updateUserDetails($user_id, $data);
        
        if ($result['status'] === 'success') {
            // Determine update type for notification
            $update_type = 'general';
            if (isset($data['firstname']) || isset($data['lastname'])) {
                $update_type = 'name';
            } elseif (isset($data['email'])) {
                $update_type = 'email';
            } elseif (isset($data['user_role'])) {
                $update_type = 'role';
            } elseif (isset($data['user_status'])) {
                $update_type = 'status';
            }
            
            // Create notification for the updated user
            createProfileUpdateNotification($user_id, $update_type, $_SESSION['user_id'] ?? null);
        }
        
        echo json_encode($result);
    } catch (Exception $e) {
        error_log("Error editing user: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while editing user']);
    }
}

// Notification Functions
function getNotifications() {
    try {
        $user_id = $_SESSION['user_id'] ?? 0;
        $limit = $_POST['limit'] ?? 10;
        $offset = $_POST['offset'] ?? 0;
        $unread_only = isset($_POST['unread_only']) ? (bool)$_POST['unread_only'] : false;
        
        if (!$user_id) {
            echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
            return;
        }
        
        $notifications = getUserNotifications($user_id, $limit, $offset, $unread_only);
        $html = generateNotificationsHTML($notifications);
        
        echo json_encode([
            'status' => 'success',
            'notifications' => $notifications,
            'html' => $html
        ]);
        
    } catch (Exception $e) {
        error_log("Error getting notifications: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while getting notifications']);
    }
}

function markNotificationRead() {
    try {
        $user_id = $_SESSION['user_id'] ?? 0;
        $notification_id = $_POST['notification_id'] ?? 0;
        
        if (!$user_id || !$notification_id) {
            echo json_encode(['status' => 'error', 'message' => 'User ID and notification ID are required']);
            return;
        }
        
        $result = markNotificationAsRead($notification_id, $user_id);
        echo json_encode($result);
        
    } catch (Exception $e) {
        error_log("Error marking notification as read: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while marking notification as read']);
    }
}

function markAllNotificationsRead() {
    try {
        $user_id = $_SESSION['user_id'] ?? 0;
        
        if (!$user_id) {
            echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
            return;
        }
        
        $result = markAllNotificationsAsRead($user_id);
        echo json_encode($result);
        
    } catch (Exception $e) {
        error_log("Error marking all notifications as read: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while marking notifications as read']);
    }
}

function deleteNotificationAjax() {
    try {
        $user_id = $_SESSION['user_id'] ?? 0;
        $notification_id = $_POST['notification_id'] ?? 0;
        
        if (!$user_id || !$notification_id) {
            echo json_encode(['status' => 'error', 'message' => 'User ID and notification ID are required']);
            return;
        }
        
        $result = deleteNotification($notification_id, $user_id);
        echo json_encode($result);
        
    } catch (Exception $e) {
        error_log("Error deleting notification: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while deleting notification']);
    }
}

function getUnreadCount() {
    try {
        $user_id = $_SESSION['user_id'] ?? 0;
        
        if (!$user_id) {
            echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
            return;
        }
        
        $count = getUnreadNotificationCount($user_id);
        
        echo json_encode([
            'status' => 'success',
            'count' => $count
        ]);
        
    } catch (Exception $e) {
        error_log("Error getting unread count: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while getting unread count']);
    }
}

function generateNotificationsHTML($notifications) {
    if (empty($notifications)) {
        return "
        <div class='text-center py-8'>
            <div class='w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4'>
                <i class='fas fa-bell text-gray-400 text-2xl'></i>
            </div>
            <h3 class='text-lg font-medium text-gray-900 mb-2'>No notifications</h3>
            <p class='text-gray-500 text-sm'>You're all caught up!</p>
        </div>";
    }
    
    $html = '<div class="space-y-2">';
    
    foreach ($notifications as $notification) {
        $icon = getNotificationIcon($notification['type']);
        $colorClass = getNotificationColor($notification['type']);
        $isRead = $notification['is_read'] ? 'read' : 'unread';
        $timeAgo = timeAgo($notification['created_at']);
        $unreadIndicator = $notification['is_read'] ? '' : '<div class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 animate-pulse"></div>';
        
        $html .= "
        <div class='notification-item p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-all duration-200 group {$isRead}' 
              data-notification-id='{$notification['id']}' data-is-read='{$notification['is_read']}'>
            <div class='flex items-start gap-3'>
                {$unreadIndicator}
                <div class='flex-shrink-0'>
                    <div class='w-10 h-10 rounded-full {$colorClass} flex items-center justify-center'>
                        <i class='{$icon} text-sm'></i>
                    </div>
                </div>
                <div class='flex-1 min-w-0'>
                    <div class='flex items-center justify-between mb-1'>
                        <h4 class='notification-title text-sm font-medium " . ($notification['is_read'] ? 'text-gray-600' : 'text-gray-900') . "'>{$notification['title']}</h4>
                        <div class='flex items-center gap-2'>
                            <span class='text-xs text-gray-500'>{$timeAgo}</span>
                            <button onclick='deleteNotification({$notification['id']})' class='text-gray-400 hover:text-red-500 text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-200'>
                                <i class='fas fa-times'></i>
                            </button>
                        </div>
                    </div>
                    <p class='notification-message text-sm " . ($notification['is_read'] ? 'text-gray-500' : 'text-gray-700') . " mb-2'>{$notification['message']}</p>
                    " . ($notification['created_by'] ? "<p class='text-xs text-gray-400'>By {$notification['created_by_firstname']} {$notification['created_by_lastname']}</p>" : "") . "
                </div>
            </div>
        </div>";
    }
    
    $html .= '</div>';
    
    return $html;
}

function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    
    if ($time < 60) {
        return 'Just now';
    } elseif ($time < 3600) {
        $minutes = floor($time / 60);
        return $minutes . ' min' . ($minutes > 1 ? 's' : '') . ' ago';
    } elseif ($time < 86400) {
        $hours = floor($time / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($time < 2592000) {
        $days = floor($time / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } else {
        return date('M j, Y', strtotime($datetime));
    }
}

function handleTaskStatusChange() {
    try {
        $task_id = $_POST['task_id'] ?? 0;
        $old_status = $_POST['old_status'] ?? '';
        $new_status = $_POST['new_status'] ?? '';
        $changed_by = $_SESSION['user_id'] ?? null;
        
        if (!$task_id || !$old_status || !$new_status || !$changed_by) {
            echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
            return;
        }
        
        // Check if the user making the change is an admin
        $is_admin = isUserAdmin($changed_by);
        
        if ($is_admin) {
            // Admin changed task status - notify assigned users
            $result = createAdminTaskStatusChangeNotification($task_id, $old_status, $new_status, $changed_by);
        } else {
            // Regular user changed task status - notify admins
            $result = createTaskStatusChangeNotification($task_id, $old_status, $new_status, $changed_by);
        }
        
        echo json_encode($result);
        
    } catch (Exception $e) {
        error_log("Error handling task status change: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while processing task status change']);
    }
}

function handleTaskCreated() {
    try {
        $task_id = $_POST['task_id'] ?? 0;
        $created_by = $_SESSION['user_id'] ?? null;
        
        if (!$task_id || !$created_by) {
            echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
            return;
        }
        
        // Create notification for task creation
        $result = createTaskCreationNotification($task_id, $created_by);
        
        echo json_encode($result);
        
    } catch (Exception $e) {
        error_log("Error handling task created: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while processing task creation notification']);
    }
}

function handleTaskAssigned() {
    try {
        $task_id = $_POST['task_id'] ?? 0;
        $assigned_user_id = $_POST['assigned_user_id'] ?? 0;
        $assigned_by = $_SESSION['user_id'] ?? null;
        
        if (!$task_id || !$assigned_user_id || !$assigned_by) {
            echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
            return;
        }
        
        // Create notification for task assignment
        $result = createTaskAssignmentNotification($task_id, $assigned_user_id, $assigned_by);
        
        echo json_encode($result);
        
    } catch (Exception $e) {
        error_log("Error handling task assigned: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while processing task assignment notification']);
    }
}

function handleTaskCreationComprehensive() {
    try {
        $task_id = $_POST['task_id'] ?? 0;
        $created_by = $_SESSION['user_id'] ?? null;
        $assigned_to = $_POST['assigned_to'] ?? null;
        
        if (!$task_id || !$created_by) {
            echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
            return;
        }
        
        // Create comprehensive notification for task creation
        $result = createTaskCreationNotificationComprehensive($task_id, $created_by, $assigned_to);
        
        echo json_encode($result);
        
    } catch (Exception $e) {
        error_log("Error handling comprehensive task creation: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while processing comprehensive task creation notification']);
    }
}

// New Analytics Functions
function getAnalyticsData() {
    try {
        // Get current analytics data
        $teamStats = getTeamStatistics();
        
        // Calculate trends (simplified - in production, you'd fetch historical data)
        $trends = calculateAnalyticsTrends();
        
        echo json_encode([
            'success' => true,
            'data' => $teamStats,
            'trends' => $trends
        ]);
        
    } catch (Exception $e) {
        error_log("Error getting analytics data: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Error loading analytics data'
        ]);
    }
}

function getPerformanceData() {
    try {
        $period = $_GET['period'] ?? 30;
        $days = (int) $period;
        
        // Get performance data for the specified period
        $performanceData = getTeamPerformanceData($days);
        
        echo json_encode([
            'success' => true,
            'data' => $performanceData
        ]);
        
    } catch (Exception $e) {
        error_log("Error getting performance data: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Error loading performance data'
        ]);
    }
}

function getProjectDistribution() {
    try {
        // Get project distribution data
        $distributionData = getProjectDistributionData();
        
        echo json_encode([
            'success' => true,
            'data' => $distributionData
        ]);
        
    } catch (Exception $e) {
        error_log("Error getting project distribution: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Error loading project distribution data'
        ]);
    }
}

// Helper function to calculate analytics trends
function calculateAnalyticsTrends() {
    // In a real application, you would fetch historical data from the database
    // For now, we'll return simulated trends
    return [
        'members' => rand(5, 25),
        'active' => rand(3, 20),
        'assignments' => rand(10, 30),
        'projects' => rand(15, 35)
    ];
}

// Helper function to get team performance data
function getTeamPerformanceData($days) {
    try {
        global $conn;
        
        $users_table = PREFIX . "users";
        $project_meta_table = PREFIX . "project_meta";
        
        $labels = [];
        $activeMembers = [];
        $projectAssignments = [];
        
        // Generate data for the specified number of days
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $labels[] = date('M j', strtotime($date));
            
            // Get active members count for this date
            $activeQuery = "
                SELECT COUNT(*) as count 
                FROM $users_table 
                WHERE user_status = 'active' 
                AND DATE(created_at) <= '$date'
            ";
            $stmt = $conn->prepare($activeQuery);
            $stmt->execute();
            $activeResult = $stmt->fetch(PDO::FETCH_ASSOC);
            $activeMembers[] = $activeResult['count'] ?? rand(10, 20);
            
            // Get project assignments count for this date
            $assignmentsQuery = "
                SELECT COUNT(*) as count 
                FROM $project_meta_table 
                WHERE DATE(created_at) <= '$date'
            ";
            $stmt = $conn->prepare($assignmentsQuery);
            $stmt->execute();
            $assignmentsResult = $stmt->fetch(PDO::FETCH_ASSOC);
            $projectAssignments[] = $assignmentsResult['count'] ?? rand(5, 15);
        }
        
        return [
            'labels' => $labels,
            'activeMembers' => $activeMembers,
            'projectAssignments' => $projectAssignments
        ];
        
    } catch (Exception $e) {
        error_log("Error getting team performance data: " . $e->getMessage());
        // Return sample data if database query fails
        return getSamplePerformanceData($days);
    }
}

// Helper function to get project distribution data
function getProjectDistributionData() {
    try {
        global $conn;
        
        $projects_table = PREFIX . "projects";
        $project_meta_table = PREFIX . "project_meta";
        
        // Get project distribution by status
        $query = "
            SELECT 
                p.status,
                COUNT(pm.id) as member_count
            FROM $projects_table p
            LEFT JOIN $project_meta_table pm ON p.id = pm.project_id
            GROUP BY p.status
            ORDER BY member_count DESC
        ";
        
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $labels = [];
        $values = [];
        
        foreach ($results as $row) {
            $status = ucfirst($row['status'] ?? 'unknown');
            $labels[] = $status;
            $values[] = (int) $row['member_count'];
        }
        
        // If no data, return sample data
        if (empty($labels)) {
            return [
                'labels' => ['In Progress', 'Completed', 'Planning', 'On Hold'],
                'values' => [12, 8, 5, 3]
            ];
        }
        
        return [
            'labels' => $labels,
            'values' => $values
        ];
        
    } catch (Exception $e) {
        error_log("Error getting project distribution data: " . $e->getMessage());
        // Return sample data if database query fails
        return [
            'labels' => ['In Progress', 'Completed', 'Planning', 'On Hold'],
            'values' => [12, 8, 5, 3]
        ];
    }
}

// Helper function to get sample performance data
function getSamplePerformanceData($days = 30) {
    $labels = [];
    $activeMembers = [];
    $projectAssignments = [];
    
    for ($i = $days - 1; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $labels[] = date('M j', strtotime($date));
        $activeMembers[] = rand(10, 20);
        $projectAssignments[] = rand(5, 15);
    }
    
    return [
        'labels' => $labels,
        'activeMembers' => $activeMembers,
        'projectAssignments' => $projectAssignments
    ];
} 