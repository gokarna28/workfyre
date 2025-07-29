<?php
include_once('connection.php');
include_once('config.php');


//current url
// function getCurrentUrl() {
//     $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
//     $host = $_SERVER['HTTP_HOST'];
//     $requestUri = $_SERVER['REQUEST_URI'];

//     return $protocol . $host . $requestUri;
// }

function getCurrentPageName()
{
    return basename($_SERVER['REQUEST_URI']);
}


//crete user
function registerUser($params)
{
    try {
        global $conn;
        $table_name = PREFIX . "users";

        // First, check if the email already exists
        $checkStmt = $conn->prepare("SELECT id FROM $table_name WHERE email = :email");
        $checkStmt->bindParam(':email', $params['email'], PDO::PARAM_STR);
        $checkStmt->execute();

        if ($checkStmt->fetch(PDO::FETCH_ASSOC)) {
            return "User with this email already exists.";
        }

        // If not exists, insert the new user
        $stmt = $conn->prepare("INSERT INTO $table_name (firstname, lastname, email, password, created_at, updated_at) 
        VALUES (:firstname, :lastname, :email, :password, :created_at, :updated_at)");

        $stmt->bindParam(':firstname', $params['firstname'], PDO::PARAM_STR);
        $stmt->bindParam(':lastname', $params['lastname'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $params['email'], PDO::PARAM_STR);

        // Hash the password before storing
        $hashedPassword = password_hash($params['password'], PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

        $stmt->bindParam(':created_at', $params['created_at'], PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $params['updated_at'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            // After successful registration, fetch the inserted user's ID
            $userId = $conn->lastInsertId();

            // Start session and set session variables
            session_start();
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_email'] = $params['email'];
            $_SESSION['user_firstname'] = $params['firstname'];
            $_SESSION['user_lastname'] = $params['lastname'];

            return true;
        } else {
            return 'Failed to Register.';
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}

function loginUser($params)
{
    try {
        global $conn;
        $table_name = PREFIX . "users";

        $stmt = $conn->prepare("SELECT * FROM $table_name WHERE email = :email");
        $stmt->bindParam(':email', $params['email'], PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify the password
            if (password_verify($params['password'], $user['password'])) {
                // Password is correct, you can start a session
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];

                return true;
            } else {
                return "Invalid password.";
            }
        } else {
            return "User not found.";
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}


function isUserLoggedIn()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        return true;
    } else {
        return false;
    }
}


function createProject($params)
{
    try {
        global $conn;
        $table_name = PREFIX . "projects";

        $stmt = $conn->prepare("INSERT INTO $table_name (title, priority, description, created_at, updated_at) 
        VALUES (:title, :priority, :description, :created_at, :updated_at)");

        $stmt->bindParam(':title', $params['project_title'], PDO::PARAM_STR);
        $stmt->bindParam(':priority', $params['project_priority'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $params['project_description'], PDO::PARAM_STR);
        $stmt->bindParam(':created_at', $params['created_at'], PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $params['updated_at'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            $project_id = $conn->lastInsertId();
            return ['status' => 'success', 'project_id' => $project_id];
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}

function createTask($params)
{
    try {
        global $conn;
        $table_name = PREFIX . "tasks";
        $created_by = $_SESSION['user_id'] ?? null;

        // Set default priority if not provided
        $priority = isset($params['task_priority']) ? $params['task_priority'] : 'low';

        $stmt = $conn->prepare("INSERT INTO $table_name (project_id, title, priority, deadline, assign_to, description, created_at, updated_at) 
        VALUES (:project_id, :title, :priority, :deadline, :assign_to, :description, :created_at, :updated_at)");

        $stmt->bindParam(':project_id', $params['project_id'], PDO::PARAM_INT);
        $stmt->bindParam(':title', $params['task_title'], PDO::PARAM_STR);
        $stmt->bindParam(':priority', $priority, PDO::PARAM_STR);
        $stmt->bindParam(':deadline', $params['task_deadline'], PDO::PARAM_STR);
        $stmt->bindParam(':assign_to', $params['task_assignto'], PDO::PARAM_INT);
        $stmt->bindParam(':description', $params['task_description'], PDO::PARAM_STR);
        $stmt->bindParam(':created_at', $params['created_at'], PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $params['updated_at'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            $task_id = $conn->lastInsertId();
            
            // Create comprehensive notifications for task creation and assignment
            if ($task_id && $created_by) {
                // Debug: Log task creation
                error_log("Task created successfully. Task ID: {$task_id}, Created by: {$created_by}");
                
                // Use comprehensive notification function
                $notification_result = createTaskCreationNotificationComprehensive(
                    $task_id, 
                    $created_by, 
                    $params['task_assignto'] ?? null
                );
                error_log("Task creation notification result: " . json_encode($notification_result));
                
                if ($notification_result['status'] === 'success') {
                    error_log("Successfully created {$notification_result['notifications_created']} notifications");
                } else {
                    error_log("Notification creation failed: " . $notification_result['message']);
                }
            } else {
                error_log("Task created but no notifications sent. Task ID: {$task_id}, Created by: {$created_by}");
            }
            
            return ['status' => 'success', 'task_id' => $task_id];
        }

    } catch (PDOException $e) {
        error_log("Database error in createTask: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred in createTask: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}
function updateTaskCriticalPathParams($params)
{

    try {
        global $conn;
        $table_name = PREFIX . "tasks";

        $stmt = $conn->prepare("UPDATE $table_name SET early_start=:early_start, early_finish=:early_finish, latest_start=:latest_start, latest_finish=:latest_finish,slack=:slack, critical=:critical WHERE id=:task_id");

        $stmt->bindParam(':early_start', $params['es'], PDO::PARAM_INT);
        $stmt->bindParam(':early_finish', $params['ef'], PDO::PARAM_INT);
        $stmt->bindParam(':latest_start', $params['ls'], PDO::PARAM_INT);
        $stmt->bindParam(':latest_finish', $params['lf'], PDO::PARAM_INT);
        $stmt->bindParam(':slack', $params['slack'], PDO::PARAM_INT);
        $stmt->bindParam(':critical', $params['critical'], PDO::PARAM_INT);
        $stmt->bindParam(':task_id', $params['task_id'], PDO::PARAM_INT);


        if ($stmt->execute()) {
            // $task_id = $conn->lastInsertId();
            return true;
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}

function updateTaskDependencies($params)
{
    try {
        global $conn;
        $table_name = PREFIX . "dependencies";

        $stmt = $conn->prepare("INSERT INTO $table_name (task_id, dependency_task_id, created_at, updated_at) 
        VALUES (:task_id, :dependency_task_id, :created_at, :updated_at)");

        $stmt->bindParam(':task_id', $params['task_id'], PDO::PARAM_INT);
        $stmt->bindParam(':dependency_task_id', $params['dependency_task_id'], PDO::PARAM_STR);
        $stmt->bindParam(':created_at', $params['created_at'], PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $params['updated_at'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            $task_id = $conn->lastInsertId();
            return ['status' => 'success', 'task_id' => $task_id];
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}

function getTaskDependencies($task_id)
{

    try {
        global $conn;
        $table_name = PREFIX . "dependencies";

        $stmt = $conn->prepare("SELECT * FROM $table_name WHERE task_id=:task_id");

        $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);


        if ($stmt->execute()) {
            $dependencies = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return !empty($dependencies) ? $dependencies : [];

        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}

function saveProjectAttachments($params)
{

    try {
        global $conn;
        $table_name = PREFIX . "project_attachments";

        $stmt = $conn->prepare("INSERT INTO $table_name (project_id, attachment, created_at, updated_at) 
        VALUES (:project_id, :attachment, :created_at, :updated_at)");

        $stmt->bindParam(':project_id', $params['project_id'], PDO::PARAM_INT);
        $stmt->bindParam(':attachment', $params['attachment'], PDO::PARAM_STR);
        $stmt->bindParam(':created_at', $params['created_at'], PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $params['updated_at'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return true;
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}

function saveTaskAttachments($params)
{
    try {
        global $conn;
        $table_name = PREFIX . "task_attachments";

        $stmt = $conn->prepare("INSERT INTO $table_name (task_id, attachment, created_at, updated_at) 
        VALUES (:task_id, :attachment, :created_at, :updated_at)");

        $stmt->bindParam(':task_id', $params['task_id'], PDO::PARAM_INT);
        $stmt->bindParam(':attachment', $params['attachment'], PDO::PARAM_STR);
        $stmt->bindParam(':created_at', $params['created_at'], PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $params['updated_at'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return true;
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}

function getProjectDetails($limit, $offset)
{
    try {
        global $conn;
        $table_name = PREFIX . "projects";

        $stmt = $conn->prepare("SELECT * FROM $table_name ORDER BY id DESC LIMIT $limit OFFSET $offset");

        if ($stmt->execute()) {
            $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return !empty($projects) ? $projects : "";
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}
function getTotalProjects()
{
    try {
        global $conn;
        $table_name = PREFIX . "projects";

        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM $table_name");

        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return 0;
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return 0;
    }
}

// Function to get enrolled projects for current user
function getEnrolledProjects($limit, $offset, $user_id = null)
{
    try {
        global $conn;
        
        // If no user_id provided, get from session
        if ($user_id === null) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $user_id = $_SESSION['user_id'] ?? null;
        }
        
        if (!$user_id) {
            return [];
        }
        
        // Get user details to check if admin
        $user = getUsersDetailsByUser_id($user_id);
        if (!$user) {
            return [];
        }
        
        $projects_table = PREFIX . "projects";
        
        // If user is admin, show all projects
        if ($user['user_role'] === 'admin') {
            $stmt = $conn->prepare("
                SELECT * FROM $projects_table 
                ORDER BY id DESC 
                LIMIT :limit OFFSET :offset
            ");
            
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        } else {
            // Regular users only see enrolled projects
            $project_meta_table = PREFIX . "project_meta";

            $stmt = $conn->prepare("
                SELECT DISTINCT p.* 
                FROM $projects_table p
                INNER JOIN $project_meta_table pm ON p.id = pm.project_id
                WHERE pm.user_id = :user_id
                ORDER BY p.id DESC 
                LIMIT :limit OFFSET :offset
            ");
            
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }

        if ($stmt->execute()) {
            $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return !empty($projects) ? $projects : [];
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return [];
    }
}

// Function to get total count of enrolled projects for current user
function getTotalEnrolledProjects($user_id = null)
{
    try {
        global $conn;
        
        // If no user_id provided, get from session
        if ($user_id === null) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $user_id = $_SESSION['user_id'] ?? null;
        }
        
        if (!$user_id) {
            return 0;
        }
        
        // Get user details to check if admin
        $user = getUsersDetailsByUser_id($user_id);
        if (!$user) {
            return 0;
        }
        
        $projects_table = PREFIX . "projects";
        
        // If user is admin, count all projects
        if ($user['user_role'] === 'admin') {
            $stmt = $conn->prepare("
                SELECT COUNT(*) as total FROM $projects_table
            ");
        } else {
            // Regular users only count enrolled projects
            $project_meta_table = PREFIX . "project_meta";

            $stmt = $conn->prepare("
                SELECT COUNT(DISTINCT p.id) as total 
                FROM $projects_table p
                INNER JOIN $project_meta_table pm ON p.id = pm.project_id
                WHERE pm.user_id = :user_id
            ");
            
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        }

        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return 0;
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return 0;
    }
}

// Function to get filtered projects
function getFilteredProjects($filters = [], $limit = 5, $offset = 0, $user_id = null)
{
    try {
        global $conn;
        
        // If no user_id provided, get from session
        if ($user_id === null) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $user_id = $_SESSION['user_id'] ?? null;
        }
        
        if (!$user_id) {
            return [];
        }
        
        // Get user details to check if admin
        $user = getUsersDetailsByUser_id($user_id);
        if (!$user) {
            return [];
        }
        
        $projects_table = PREFIX . "projects";
        $project_meta_table = PREFIX . "project_meta";
        
        // Build the base query
        $whereConditions = [];
        $params = [];
        
        // If admin, show all projects, otherwise only enrolled projects
        if ($user['user_role'] !== 'admin') {
            $whereConditions[] = "pm.user_id = :user_id";
            $params[':user_id'] = $user_id;
        }
        
        // Apply search filter
        if (!empty($filters['search'])) {
            $whereConditions[] = "p.title LIKE :search";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        // Apply status filter
        if (!empty($filters['status'])) {
            $whereConditions[] = "p.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        // Apply priority filter
        if (!empty($filters['priority'])) {
            $whereConditions[] = "p.priority = :priority";
            $params[':priority'] = $filters['priority'];
        }
        
        // Build WHERE clause
        $whereClause = '';
        if (!empty($whereConditions)) {
            $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
        }
        
        // Build ORDER BY clause
        $orderBy = 'ORDER BY p.id DESC'; // Default
        if (!empty($filters['sortBy'])) {
            switch ($filters['sortBy']) {
                case 'oldest':
                    $orderBy = 'ORDER BY p.id ASC';
                    break;
                case 'title':
                    $orderBy = 'ORDER BY p.title ASC';
                    break;
                case 'priority':
                    $orderBy = 'ORDER BY CASE p.priority WHEN "high" THEN 1 WHEN "medium" THEN 2 WHEN "low" THEN 3 END';
                    break;
                case 'progress':
                    $orderBy = 'ORDER BY p.progress DESC';
                    break;
                default:
                    $orderBy = 'ORDER BY p.id DESC';
            }
        }
        
        // Build the complete query
        if ($user['user_role'] === 'admin') {
            $query = "
                SELECT p.*, 
                       (SELECT COUNT(*) FROM " . PREFIX . "tasks WHERE project_id = p.id) as total_tasks,
                       (SELECT COUNT(*) FROM " . PREFIX . "tasks WHERE project_id = p.id AND status = 'completed') as completed_tasks
                FROM $projects_table p
                $whereClause
                $orderBy
                LIMIT :limit OFFSET :offset
            ";
        } else {
            $query = "
                SELECT DISTINCT p.*, 
                       (SELECT COUNT(*) FROM " . PREFIX . "tasks WHERE project_id = p.id) as total_tasks,
                       (SELECT COUNT(*) FROM " . PREFIX . "tasks WHERE project_id = p.id AND status = 'completed') as completed_tasks
                FROM $projects_table p
                INNER JOIN $project_meta_table pm ON p.id = pm.project_id
                $whereClause
                $orderBy
                LIMIT :limit OFFSET :offset
            ";
        }
        
        $stmt = $conn->prepare($query);
        
        // Bind all parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return !empty($projects) ? $projects : [];
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return [];
    }
}

// Function to get total count of filtered projects
function getTotalFilteredProjects($filters = [], $user_id = null)
{
    try {
        global $conn;
        
        // If no user_id provided, get from session
        if ($user_id === null) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $user_id = $_SESSION['user_id'] ?? null;
        }
        
        if (!$user_id) {
            return 0;
        }
        
        // Get user details to check if admin
        $user = getUsersDetailsByUser_id($user_id);
        if (!$user) {
            return 0;
        }
        
        $projects_table = PREFIX . "projects";
        $project_meta_table = PREFIX . "project_meta";
        
        // Build the base query
        $whereConditions = [];
        $params = [];
        
        // If admin, show all projects, otherwise only enrolled projects
        if ($user['user_role'] !== 'admin') {
            $whereConditions[] = "pm.user_id = :user_id";
            $params[':user_id'] = $user_id;
        }
        
        // Apply search filter
        if (!empty($filters['search'])) {
            $whereConditions[] = "p.title LIKE :search";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        // Apply status filter
        if (!empty($filters['status'])) {
            $whereConditions[] = "p.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        // Apply priority filter
        if (!empty($filters['priority'])) {
            $whereConditions[] = "p.priority = :priority";
            $params[':priority'] = $filters['priority'];
        }
        
        // Build WHERE clause
        $whereClause = '';
        if (!empty($whereConditions)) {
            $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
        }
        
        // Build the complete query
        if ($user['user_role'] === 'admin') {
            $query = "
                SELECT COUNT(*) as total FROM $projects_table p
                $whereClause
            ";
        } else {
            $query = "
                SELECT COUNT(DISTINCT p.id) as total 
                FROM $projects_table p
                INNER JOIN $project_meta_table pm ON p.id = pm.project_id
                $whereClause
            ";
        }
        
        $stmt = $conn->prepare($query);
        
        // Bind all parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return 0;
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return 0;
    }
}

// Function to get project statistics for sidebar
function getProjectStatistics($user_id = null)
{
    try {
        global $conn;
        
        // If no user_id provided, get from session
        if ($user_id === null) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $user_id = $_SESSION['user_id'] ?? null;
        }
        
        if (!$user_id) {
            return [
                'total' => 0,
                'active' => 0,
                'in_progress' => 0,
                'completed' => 0,
                'on_hold' => 0
            ];
        }
        
        // Get user details to check if admin
        $user = getUsersDetailsByUser_id($user_id);
        if (!$user) {
            return [
                'total' => 0,
                'active' => 0,
                'in_progress' => 0,
                'completed' => 0,
                'on_hold' => 0
            ];
        }
        
        $projects_table = PREFIX . "projects";
        $project_meta_table = PREFIX . "project_meta";
        
        // Build the base query
        if ($user['user_role'] === 'admin') {
            $query = "
                SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN status = 'in-progress' THEN 1 ELSE 0 END) as in_progress,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN status = 'on-hold' THEN 1 ELSE 0 END) as on_hold
                FROM $projects_table
            ";
            $stmt = $conn->prepare($query);
        } else {
            $query = "
                SELECT 
                    COUNT(DISTINCT p.id) as total,
                    SUM(CASE WHEN p.status = 'active' THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN p.status = 'in-progress' THEN 1 ELSE 0 END) as in_progress,
                    SUM(CASE WHEN p.status = 'completed' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN p.status = 'on-hold' THEN 1 ELSE 0 END) as on_hold
                FROM $projects_table p
                INNER JOIN $project_meta_table pm ON p.id = pm.project_id
                WHERE pm.user_id = :user_id
            ";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        }
        
        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return [
                'total' => $result['total'] ?? 0,
                'active' => $result['active'] ?? 0,
                'in_progress' => $result['in_progress'] ?? 0,
                'completed' => $result['completed'] ?? 0,
                'on_hold' => $result['on_hold'] ?? 0
            ];
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return [
            'total' => 0,
            'active' => 0,
            'in_progress' => 0,
            'completed' => 0,
            'on_hold' => 0
        ];
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return [
            'total' => 0,
            'active' => 0,
            'in_progress' => 0,
            'completed' => 0,
            'on_hold' => 0
        ];
    }
}

// Function to get all users with their enrolled projects
function getUsersWithProjects($limit = 10, $offset = 0, $filters = [])
{
    try {
        global $conn;
        
        $users_table = PREFIX . "users";
        $project_meta_table = PREFIX . "project_meta";
        $projects_table = PREFIX . "projects";
        
        // Build WHERE conditions
        $whereConditions = [];
        $params = [];
        
        // Apply search filter
        if (!empty($filters['search'])) {
            $whereConditions[] = "(u.firstname LIKE :search OR u.lastname LIKE :search OR u.email LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        // Apply status filter
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'enrolled') {
                $whereConditions[] = "EXISTS (SELECT 1 FROM " . PREFIX . "project_meta WHERE user_id = u.id)";
            } elseif ($filters['status'] === 'pending') {
                $whereConditions[] = "NOT EXISTS (SELECT 1 FROM " . PREFIX . "project_meta WHERE user_id = u.id)";
            }
        }
        
        // Build WHERE clause
        $whereClause = '';
        if (!empty($whereConditions)) {
            $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
        }
        
        // Build ORDER BY clause
        $orderBy = 'ORDER BY u.id DESC'; // Default
        if (!empty($filters['sortBy'])) {
            switch ($filters['sortBy']) {
                case 'name':
                    $orderBy = 'ORDER BY u.firstname ASC, u.lastname ASC';
                    break;
                case 'email':
                    $orderBy = 'ORDER BY u.email ASC';
                    break;
                case 'projects':
                    $orderBy = 'ORDER BY project_count DESC';
                    break;
                default:
                    $orderBy = 'ORDER BY u.id DESC';
            }
        }
        
        $query = "
            SELECT 
                u.*,
                COUNT(DISTINCT pm.project_id) as project_count,
                GROUP_CONCAT(DISTINCT p.title SEPARATOR '|') as project_titles,
                GROUP_CONCAT(DISTINCT p.status SEPARATOR '|') as project_statuses
            FROM $users_table u
            LEFT JOIN $project_meta_table pm ON u.id = pm.user_id
            LEFT JOIN $projects_table p ON pm.project_id = p.id
            $whereClause
            GROUP BY u.id
            $orderBy
            LIMIT :limit OFFSET :offset
        ";
        
        $stmt = $conn->prepare($query);
        
        // Bind all parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Process project data for each user
            foreach ($users as &$user) {
                $user['projects'] = [];
                if (!empty($user['project_titles'])) {
                    $titles = explode('|', $user['project_titles']);
                    $statuses = explode('|', $user['project_statuses']);
                    
                    for ($i = 0; $i < count($titles); $i++) {
                        if (!empty($titles[$i])) {
                            $user['projects'][] = [
                                'title' => $titles[$i],
                                'status' => $statuses[$i] ?? 'unknown'
                            ];
                        }
                    }
                }
                
                // Remove raw project data
                unset($user['project_titles'], $user['project_statuses']);
            }
            
            return $users;
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return [];
    }
}

// Function to get total count of users
function getTotalUsers($filters = [])
{
    try {
        global $conn;
        
        $users_table = PREFIX . "users";
        
        // Build WHERE conditions
        $whereConditions = [];
        $params = [];
        
        // Apply search filter
        if (!empty($filters['search'])) {
            $whereConditions[] = "(firstname LIKE :search OR lastname LIKE :search OR email LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        // Apply status filter
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'enrolled') {
                $whereConditions[] = "EXISTS (SELECT 1 FROM " . PREFIX . "project_meta WHERE user_id = " . PREFIX . "users.id)";
            } elseif ($filters['status'] === 'pending') {
                $whereConditions[] = "NOT EXISTS (SELECT 1 FROM " . PREFIX . "project_meta WHERE user_id = " . PREFIX . "users.id)";
            }
        }
        
        // Build WHERE clause
        $whereClause = '';
        if (!empty($whereConditions)) {
            $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
        }
        
        $query = "SELECT COUNT(*) as total FROM $users_table $whereClause";
        $stmt = $conn->prepare($query);
        
        // Bind all parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return 0;
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return 0;
    }
}

// Function to get team statistics
function getTeamStatistics()
{
    try {
        global $conn;
        
        $users_table = PREFIX . "users";
        $project_meta_table = PREFIX . "project_meta";
        
        // First, check if tables exist and have data
        $checkUsersQuery = "SELECT COUNT(*) as count FROM $users_table";
        $stmt = $conn->prepare($checkUsersQuery);
        $stmt->execute();
        $userCount = $stmt->fetch(PDO::FETCH_ASSOC);
        
        error_log("Users table count: " . ($userCount['count'] ?? 'null'));
        
        // Get user statistics with better error handling
        $userStatsQuery = "
            SELECT 
                COUNT(*) as total_users,
                SUM(CASE WHEN user_status = 'active' THEN 1 ELSE 0 END) as active_users,
                SUM(CASE WHEN user_status = 'inactive' THEN 1 ELSE 0 END) as inactive_users,
                SUM(CASE WHEN user_status = 'blocked' THEN 1 ELSE 0 END) as blocked_users
            FROM $users_table
        ";
        
        $stmt = $conn->prepare($userStatsQuery);
        $stmt->execute();
        $userStats = $stmt->fetch(PDO::FETCH_ASSOC);
        
        error_log("User stats query result: " . json_encode($userStats));
        
        // Check if project_meta table exists
        $checkProjectMetaQuery = "SELECT COUNT(*) as count FROM $project_meta_table";
        $stmt = $conn->prepare($checkProjectMetaQuery);
        $stmt->execute();
        $projectMetaCount = $stmt->fetch(PDO::FETCH_ASSOC);
        
        error_log("Project meta table count: " . ($projectMetaCount['count'] ?? 'null'));
        
        // Get project enrollment statistics
        $enrollmentQuery = "
            SELECT 
                COUNT(DISTINCT user_id) as users_with_projects,
                COUNT(DISTINCT project_id) as total_projects_enrolled
            FROM $project_meta_table
        ";
        
        $stmt = $conn->prepare($enrollmentQuery);
        $stmt->execute();
        $enrollmentStats = $stmt->fetch(PDO::FETCH_ASSOC);
        
        error_log("Enrollment stats query result: " . json_encode($enrollmentStats));
        
        $result = [
            'total_users' => (int)($userStats['total_users'] ?? 0),
            'active_users' => (int)($userStats['active_users'] ?? 0),
            'inactive_users' => (int)($userStats['inactive_users'] ?? 0),
            'blocked_users' => (int)($userStats['blocked_users'] ?? 0),
            'users_with_projects' => (int)($enrollmentStats['users_with_projects'] ?? 0),
            'total_projects_enrolled' => (int)($enrollmentStats['total_projects_enrolled'] ?? 0)
        ];
        
        error_log("Final team statistics result: " . json_encode($result));
        
        return $result;

    } catch (PDOException $e) {
        error_log("Database error in getTeamStatistics: " . $e->getMessage());
        return [
            'total_users' => 0,
            'active_users' => 0,
            'inactive_users' => 0,
            'blocked_users' => 0,
            'users_with_projects' => 0,
            'total_projects_enrolled' => 0
        ];
    } catch (Exception $e) {
        error_log("An error occurred in getTeamStatistics: " . $e->getMessage());
        return [
            'total_users' => 0,
            'active_users' => 0,
            'inactive_users' => 0,
            'blocked_users' => 0,
            'users_with_projects' => 0,
            'total_projects_enrolled' => 0
        ];
    }
}

// Function to get available projects for assignment
function getAvailableProjects($user_id = null)
{
    try {
        global $conn;
        
        $projects_table = PREFIX . "projects";
        $project_meta_table = PREFIX . "project_meta";
        
        // If user_id is provided, exclude projects the user is already enrolled in
        if ($user_id) {
            $query = "
                SELECT p.* 
                FROM $projects_table p
                WHERE p.id NOT IN (
                    SELECT project_id 
                    FROM $project_meta_table 
                    WHERE user_id = :user_id
                )
                ORDER BY p.title ASC
            ";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        } else {
            // Get all projects
            $query = "SELECT * FROM $projects_table ORDER BY title ASC";
            $stmt = $conn->prepare($query);
        }
        
        if ($stmt->execute()) {
            $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $projects;
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return [];
    }
}

// Function to assign user to project
function assignUserToProject($user_id, $project_id, $assigned_by = null)
{
    try {
        global $conn;
        
        // If no assigned_by provided, get from session
        if ($assigned_by === null) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $assigned_by = $_SESSION['user_id'] ?? null;
        }
        
        if (!$assigned_by) {
            return ['status' => 'error', 'message' => 'Assignment must be made by a logged-in user'];
        }
        
        // Check if user exists
        $user = getUsersDetailsByUser_id($user_id);
        if (!$user) {
            return ['status' => 'error', 'message' => 'User not found'];
        }
        
        // Check if project exists
        $project = getProjectDetailsByProjectID($project_id);
        if (!$project) {
            return ['status' => 'error', 'message' => 'Project not found'];
        }
        
        // Check if user is already enrolled in this project
        $project_meta_table = PREFIX . "project_meta";
        $checkStmt = $conn->prepare("SELECT id FROM $project_meta_table WHERE user_id = :user_id AND project_id = :project_id");
        $checkStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $checkStmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
        $checkStmt->execute();
        
        if ($checkStmt->fetch(PDO::FETCH_ASSOC)) {
            return ['status' => 'error', 'message' => 'User is already enrolled in this project'];
        }
        
        // Assign user to project
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');
        
        $stmt = $conn->prepare("
            INSERT INTO $project_meta_table (user_id, project_id, assigned_by, created_at, updated_at) 
            VALUES (:user_id, :project_id, :assigned_by, :created_at, :updated_at)
        ");
        
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
        $stmt->bindParam(':assigned_by', $assigned_by, PDO::PARAM_INT);
        $stmt->bindParam(':created_at', $created_at, PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $updated_at, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            $assignment_id = $conn->lastInsertId();
            
            // Log the assignment
            error_log("User ID $user_id assigned to Project ID $project_id by User ID $assigned_by");
            
            return [
                'status' => 'success', 
                'message' => 'User successfully assigned to project',
                'assignment_id' => $assignment_id,
                'user' => $user,
                'project' => $project
            ];
        } else {
            return ['status' => 'error', 'message' => 'Failed to assign user to project'];
        }

    } catch (PDOException $e) {
        error_log("Database error in assignUserToProject: " . $e->getMessage());
        return ['status' => 'error', 'message' => 'Database error occurred'];
    } catch (Exception $e) {
        error_log("An error occurred in assignUserToProject: " . $e->getMessage());
        return ['status' => 'error', 'message' => 'An error occurred while assigning user to project'];
    }
}

// Function to remove user from project
function removeUserFromProject($user_id, $project_id, $removed_by = null)
{
    try {
        global $conn;
        
        // If no removed_by provided, get from session
        if ($removed_by === null) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $removed_by = $_SESSION['user_id'] ?? null;
        }
        
        if (!$removed_by) {
            return ['status' => 'error', 'message' => 'Removal must be made by a logged-in user'];
        }
        
        // Check if assignment exists
        $project_meta_table = PREFIX . "project_meta";
        $checkStmt = $conn->prepare("SELECT id FROM $project_meta_table WHERE user_id = :user_id AND project_id = :project_id");
        $checkStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $checkStmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
        $checkStmt->execute();
        
        if (!$checkStmt->fetch(PDO::FETCH_ASSOC)) {
            return ['status' => 'error', 'message' => 'User is not enrolled in this project'];
        }
        
        // Remove user from project
        $stmt = $conn->prepare("DELETE FROM $project_meta_table WHERE user_id = :user_id AND project_id = :project_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            // Log the removal
            error_log("User ID $user_id removed from Project ID $project_id by User ID $removed_by");
            
            return [
                'status' => 'success', 
                'message' => 'User successfully removed from project'
            ];
        } else {
            return ['status' => 'error', 'message' => 'Failed to remove user from project'];
        }

    } catch (PDOException $e) {
        error_log("Database error in removeUserFromProject: " . $e->getMessage());
        return ['status' => 'error', 'message' => 'Database error occurred'];
    } catch (Exception $e) {
        error_log("An error occurred in removeUserFromProject: " . $e->getMessage());
        return ['status' => 'error', 'message' => 'An error occurred while removing user from project'];
    }
}

// Function to get user's project assignments
function getUserProjectAssignments($user_id)
{
    try {
        global $conn;
        
        $project_meta_table = PREFIX . "project_meta";
        $projects_table = PREFIX . "projects";
        
        $query = "
            SELECT pm.*, p.title, p.description, p.status as project_status, p.priority
            FROM $project_meta_table pm
            INNER JOIN $projects_table p ON pm.project_id = p.id
            WHERE pm.user_id = :user_id
            ORDER BY pm.created_at DESC
        ";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $assignments;
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return [];
    }
}

// Function to check if user can be assigned to project (permission check)
function canAssignUserToProject($user_id, $project_id, $assigning_user_id = null)
{
    try {
        // If no assigning_user_id provided, get from session
        if ($assigning_user_id === null) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $assigning_user_id = $_SESSION['user_id'] ?? null;
        }
        
        if (!$assigning_user_id) {
            return false;
        }
        
        // Get assigning user details
        $assigningUser = getUsersDetailsByUser_id($assigning_user_id);
        if (!$assigningUser) {
            return false;
        }
        
        // Admin can assign anyone to any project
        if ($assigningUser['user_role'] === 'admin') {
            return true;
        }
        
        // Regular users cannot assign others to projects
        return false;

    } catch (Exception $e) {
        error_log("Error checking assignment permission: " . $e->getMessage());
        return false;
    }
}
function getTotalTaskByStatus($project_id, $status)
{
    try {
        global $conn;
        $table_name = PREFIX . "tasks";

        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM $table_name WHERE project_id = :project_id AND status = :status");
        $stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0; // safely returns 0 if no row found
        }

        return 0;

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return 0;
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return 0;
    }
}


function getClasses($params)
{
    $classes = ' ';
    $status = strtolower($params);

    switch ($status) {
        case 'completed':
            $classes .= ' bg-sky-200 text-sky-600';
            break;
        case 'in-progress':
            $classes .= ' bg-yellow-200 text-yellow-600';
            break;
        case 'pending':
            $classes .= ' bg-yellow-200 text-yellow-600';
            break;
        case 'not-started':
            $classes .= ' bg-stone-200 text-stone-600';
            break;
        case 'inrolled':
            $classes .= ' bg-stone-200 text-stone-600';
            break;
        case 'medium':
            $classes .= ' bg-lime-100 text-lime-600';
            break;
        case 'low':
            $classes .= ' bg-orange-100 text-orange-600';
            break;
        case 'active':
            $classes .= ' bg-green-200 text-green-600';
            break;
        case 'high':
            $classes .= ' bg-red-100 text-red-600';
            break;

        default:
            $classes .= ' bg-red-200 text-red-600';
            break;
    }
    return $classes;
}

function getProjectDetailsByProjectID($project_id)
{
    try {
        global $conn;
        $table_name = PREFIX . "projects";

        $stmt = $conn->prepare("SELECT * FROM $table_name WHERE id=:id");
        $stmt->bindParam(':id', $project_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $projects = $stmt->fetch(PDO::FETCH_ASSOC);

            return !empty($projects) ? $projects : "";
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}

function getTasksDetailsByProject_id($project_id)
{
    try {
        global $conn;
        $table_name = PREFIX . "tasks";

        $stmt = $conn->prepare("SELECT * FROM $table_name WHERE project_id=:project_id");
        $stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return !empty($projects) ? $projects : "";
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}


function getTasksDetailsByTask_id($task_id)
{
    try {
        global $conn;
        $table_name = PREFIX . "tasks";

        $stmt = $conn->prepare("SELECT * FROM $table_name WHERE id=:task_id");
        $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $tasks = $stmt->fetch(PDO::FETCH_ASSOC);

            return !empty($tasks) ? $tasks : "";
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}

function getTasksDetailsByStatus($project_id, $status)
{
    try {
        global $conn;
        $table_name = PREFIX . "tasks";

        $stmt = $conn->prepare("SELECT * FROM $table_name WHERE status = :status AND project_id = :project_id ORDER BY id DESC");
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return !empty($tasks) ? $tasks : "";
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}

function getProjectAttachments($project_id)
{
    try {
        global $conn;
        $table_name = PREFIX . "project_attachments";

        $stmt = $conn->prepare("SELECT * FROM $table_name WHERE project_id=:project_id");
        $stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $attachments = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return !empty($attachments) ? $attachments : "";
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}

function getTaskAttachments($task_id)
{
    try {
        global $conn;
        $table_name = PREFIX . "task_attachments";

        $stmt = $conn->prepare("SELECT * FROM $table_name WHERE id=:task_id");
        $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $attachments = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return !empty($attachments) ? $attachments : "";
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}

function deleteProjectAttachment($attachment_id)
{

    try {
        global $conn;
        $table_name = PREFIX . "project_attachments";

        $stmt = $conn->prepare("DELETE FROM $table_name WHERE id=:id");
        $stmt->bindParam(':id', $attachment_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}


function deleteTaskAttachment($attachment_id)
{

    try {
        global $conn;
        $table_name = PREFIX . "task_attachments";

        $stmt = $conn->prepare("DELETE FROM $table_name WHERE id=:id");
        $stmt->bindParam(':id', $attachment_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}

function getUsersDetails()
{
    try {
        global $conn;
        $table_name = PREFIX . "users";

        $stmt = $conn->prepare("SELECT * FROM $table_name");
        // $stmt->bindParam(':id', $attachment_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $users;
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}

function insertDataProjectMeta($project_id, $user_id, $created_at, $updated_at)
{
    try {
        global $conn;
        $table_name = PREFIX . "project_meta";

        //check if the user is alread inrolled
        $stmt = $conn->prepare("SELECT * FROM $table_name WHERE project_id=:project_id AND user_id=:user_id");
        $stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count > 0) {
            return ['status' => 'error', 'message' => 'User is already inrolled to the project.'];
        } else {
            $stmt = $conn->prepare("INSERT INTO $table_name (project_id, user_id, created_at, updated_at)VALUES( :project_id, :user_id, :created_at, :updated_at)");
            $stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':created_at', $created_at, PDO::PARAM_STR);
            $stmt->bindParam(':updated_at', $updated_at, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $lastInsertId = $conn->lastInsertId();

                return ['status' => 'success', 'inserted_id' => $lastInsertId];
            }
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}

function getUsersDetailsByUser_id($user_id)
{
    try {
        global $conn;
        $table_name = PREFIX . "users";

        $stmt = $conn->prepare("SELECT * FROM $table_name WHERE id=:user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $users = $stmt->fetch(PDO::FETCH_ASSOC);
            return $users;
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}

function getProjectMeta($project_id)
{
    try {
        global $conn;
        $table_meta = PREFIX . "project_meta";
        $table_users = PREFIX . "users";

        $stmt = $conn->prepare("
            SELECT pm.*, u.firstname, u.lastname, u.email 
            FROM $table_meta AS pm
            INNER JOIN $table_users AS u ON pm.user_id = u.id WHERE pm.project_id=$project_id ORDER BY pm.id DESC
        ");

        if ($stmt->execute()) {
            $projectMeta = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($projectMeta) {
                return $projectMeta;
            }
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }

}
function getProjectMetaByStatus($project_id, $status = "inrolled")
{
    try {
        global $conn;
        $table_meta = PREFIX . "project_meta";
        $table_users = PREFIX . "users";

        $stmt = $conn->prepare("
            SELECT  u.id, u.firstname, u.lastname, u.email 
            FROM $table_meta AS pm
            INNER JOIN $table_users AS u ON pm.user_id = u.id 
            WHERE pm.status = :status AND project_id=:project_id
            ORDER BY pm.id DESC
        ");

        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $projectMeta = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($projectMeta) {
                return $projectMeta;
            }
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }

    return []; // Return empty array if no results or on failure
}


function getProjectTeamByPm_id($pm_id)
{
    try {
        global $conn;
        $table_meta = PREFIX . "project_meta";
        $table_users = PREFIX . "users";

        $stmt = $conn->prepare("
            SELECT pm.*, u.firstname, u.lastname, u.email 
            FROM $table_meta AS pm
            INNER JOIN $table_users AS u ON pm.user_id = u.id WHERE pm.id=$pm_id ORDER BY pm.id DESC
        ");

        if ($stmt->execute()) {
            $projectMeta = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($projectMeta) {
                return $projectMeta;
            }
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }

}

function updateProjectMeta($params)
{
    try {
        global $conn;
        $table_meta = PREFIX . "project_meta";

        $stmt = $conn->prepare("
        UPDATE $table_meta SET status=:status WHERE id=:id
    ");
        $stmt->bindParam(':status', $params['invite_statu'], PDO::PARAM_STR);
        $stmt->bindParam(':id', $params['invite_id'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}


function updateTaskStatus($params)
{
    try {
        global $conn;
        $table_meta = PREFIX . "tasks";
        $updatedAt = strtolower(date('F-d-Y'));
        
        // Get the current task status before updating
        $current_task = getTasksDetailsByTask_id($params['task_id']);
        $old_status = $current_task['status'] ?? '';
        $new_status = $params['task_status'];
        $changed_by = $_SESSION['user_id'] ?? null;

        $stmt = $conn->prepare("
        UPDATE $table_meta SET status=:status, updated_at=:updated_at WHERE id=:id
    ");
        $stmt->bindParam(':status', $params['task_status'], PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $updatedAt, PDO::PARAM_STR);
        $stmt->bindParam(':id', $params['task_id'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Create notifications for task status change
            if ($old_status !== $new_status && $changed_by) {
                // Check if the user making the change is an admin
                $is_admin = isUserAdmin($changed_by);
                
                if ($is_admin) {
                    // Admin changed task status - notify assigned users
                    createAdminTaskStatusChangeNotification($params['task_id'], $old_status, $new_status, $changed_by);
                } else {
                    // Regular user changed task status - notify admins
                    createTaskStatusChangeNotification($params['task_id'], $old_status, $new_status, $changed_by);
                }
            }
            
            return true;
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}

function updateTaskPriority($task_id, $priority)
{
    try {
        global $conn;
        $table_meta = PREFIX . "tasks";
        $updatedAt = strtolower(date('F-d-Y'));

        $stmt = $conn->prepare("
        UPDATE $table_meta SET priority=:priority, updated_at=:updated_at WHERE id=:id
    ");
        $stmt->bindParam(':priority', $priority, PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $updatedAt, PDO::PARAM_STR);
        $stmt->bindParam(':id', $task_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}

// Function to check if current user can modify a task
function canUserModifyTask($task_id, $user_id = null)
{
    try {
        global $conn;
        
        // If no user_id provided, get from session
        if ($user_id === null) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $user_id = $_SESSION['user_id'] ?? null;
        }
        
        if (!$user_id) {
            return false;
        }
        
        // Get user role
        $user = getUsersDetailsByUser_id($user_id);
        if (!$user) {
            return false;
        }
        
        // Admin can modify any task
        if ($user['user_role'] === 'admin') {
            return true;
        }
        
        // Get task details
        $task = getTasksDetailsByTask_id($task_id);
        if (!$task) {
            return false;
        }
        
        // Regular users can only modify tasks assigned to them
        return ($task['assign_to'] == $user_id);
        
    } catch (Exception $e) {
        error_log("Error checking task modification permission: " . $e->getMessage());
        return false;
    }
}

// Function to get current user details
function getCurrentUser()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    $user_id = $_SESSION['user_id'] ?? null;
    if (!$user_id) {
        return null;
    }
    
    return getUsersDetailsByUser_id($user_id);
}

// Function to generate password reset token
function generatePasswordResetToken($email)
{
    try {
        global $conn;
        $table_name = PREFIX . "users";

        // Debug: Log the email being processed
        error_log("Generating reset token for email: " . $email);

        // Check if user exists
        $stmt = $conn->prepare("SELECT id, firstname, lastname FROM $table_name WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            error_log("User not found for email: " . $email);
            return ['status' => 'error', 'message' => 'User with this email does not exist.'];
        }

        // Generate a unique token
        $token = bin2hex(random_bytes(32));
        $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token expires in 1 hour

        // Debug: Log the generated token and expiration
        error_log("Generated token: " . $token);
        error_log("Token expires at: " . $expires_at);

        // Store the token in the database
        $stmt = $conn->prepare("UPDATE $table_name SET reset_token = :token, reset_token_expires = :expires_at WHERE email = :email");
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->bindParam(':expires_at', $expires_at, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        if ($stmt->execute()) {
            error_log("Token stored successfully for user ID: " . $user['id']);
            
            // Debug: Verify the token was stored
            $verifyStmt = $conn->prepare("SELECT reset_token, reset_token_expires FROM $table_name WHERE email = :email");
            $verifyStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $verifyStmt->execute();
            $storedToken = $verifyStmt->fetch(PDO::FETCH_ASSOC);
            error_log("Stored token verification: " . print_r($storedToken, true));
            
            return [
                'status' => 'success',
                'token' => $token,
                'user' => $user,
                'expires_at' => $expires_at
            ];
        } else {
            error_log("Failed to store token in database");
            return ['status' => 'error', 'message' => 'Failed to generate reset token.'];
        }

    } catch (Exception $e) {
        error_log("Error generating password reset token: " . $e->getMessage());
        return ['status' => 'error', 'message' => 'An error occurred while generating reset token.'];
    }
}

// Function to verify password reset token
function verifyPasswordResetToken($token)
{
    try {
        global $conn;
        $table_name = PREFIX . "users";

        // Debug: Log the token being searched
        error_log("Verifying token: " . $token);
        error_log("Table name: " . $table_name);

        $stmt = $conn->prepare("SELECT id, email, firstname, lastname FROM $table_name WHERE reset_token = :token AND reset_token_expires > NOW()");
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Debug: Log the query result
        error_log("Query result: " . ($user ? "User found" : "No user found"));
        if ($user) {
            error_log("User details: " . print_r($user, true));
        }

        // Debug: Let's also check what tokens exist in the database
        $debugStmt = $conn->prepare("SELECT id, email, reset_token, reset_token_expires FROM $table_name WHERE reset_token IS NOT NULL");
        $debugStmt->execute();
        $allTokens = $debugStmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("All tokens in database: " . print_r($allTokens, true));

        if ($user) {
            return ['status' => 'success', 'user' => $user];
        } else {
            return ['status' => 'error', 'message' => 'Invalid or expired reset token.'];
        }

    } catch (Exception $e) {
        error_log("Error verifying password reset token: " . $e->getMessage());
        return ['status' => 'error', 'message' => 'An error occurred while verifying reset token.'];
    }
}

// Function to reset password
function resetPassword($token, $new_password)
{
    try {
        global $conn;
        $table_name = PREFIX . "users";

        // First verify the token
        $verifyResult = verifyPasswordResetToken($token);
        if ($verifyResult['status'] !== 'success') {
            return $verifyResult;
        }

        // Hash the new password
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
        $updatedAt = date('Y-m-d H:i:s');

        // Update the password and clear the reset token
        $stmt = $conn->prepare("UPDATE $table_name SET password = :password, reset_token = NULL, reset_token_expires = NULL, updated_at = :updated_at WHERE reset_token = :token");
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $updatedAt, PDO::PARAM_STR);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return ['status' => 'success', 'message' => 'Password has been reset successfully.'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to reset password.'];
        }

    } catch (Exception $e) {
        error_log("Error resetting password: " . $e->getMessage());
        return ['status' => 'error', 'message' => 'An error occurred while resetting password.'];
    }
}

// Function to send password reset email
function sendPasswordResetEmail($email, $token, $user)
{
    $resetLink = HOMEPAGE_URL . "/main/reset-password.php?token=" . $token;
    
    $subject = "Workfyre - Password Reset Request";
    
    $message = "
    <html>
    <head>
        <title>Password Reset Request</title>
    </head>
    <body>
        <h2>Hello {$user['firstname']} {$user['lastname']},</h2>
        <p>You have requested to reset your password for your Workfyre account.</p>
        <p>Click the button below to reset your password:</p>
        <a href='{$resetLink}' style='display:inline-block;padding:10px 20px;background-color:#6c63ff;color:white;text-decoration:none;border-radius:5px;'>Reset Password</a>
        <p>If the button doesn't work, copy and paste this link into your browser:</p>
        <p>{$resetLink}</p>
        <p><strong>This link will expire in 1 hour for security reasons.</strong></p>
        <p>If you didn't request this password reset, please ignore this email.</p>
        <br>
        <p>Thank you,<br>Workfyre Team</p>
    </body>
    </html>
    ";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: noreply@workfyre.com.np";
    $headers .= "Reply-To: noreply@workfyre.com.np" . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    return mail($email, $subject, $message, $headers);
}

// Function to change password for logged-in users
function changePassword($current_password, $new_password, $user_id = null)
{
    try {
        global $conn;
        
        // If no user_id provided, get from session
        if ($user_id === null) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $user_id = $_SESSION['user_id'] ?? null;
        }
        
        if (!$user_id) {
            return ['status' => 'error', 'message' => 'User not logged in.'];
        }
        
        $table_name = PREFIX . "users";
        
        // Get current user's password
        $stmt = $conn->prepare("SELECT password FROM $table_name WHERE id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            return ['status' => 'error', 'message' => 'User not found.'];
        }
        
        // Verify current password
        if (!password_verify($current_password, $user['password'])) {
            return ['status' => 'error', 'message' => 'Current password is incorrect.'];
        }
        
        // Hash the new password
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
        $updatedAt = date('Y-m-d H:i:s');
        
        // Update the password
        $stmt = $conn->prepare("UPDATE $table_name SET password = :password, updated_at = :updated_at WHERE id = :user_id");
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $updatedAt, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return ['status' => 'success', 'message' => 'Password changed successfully.'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to change password.'];
        }
        
    } catch (Exception $e) {
        error_log("Error changing password: " . $e->getMessage());
        return ['status' => 'error', 'message' => 'An error occurred while changing password.'];
    }
}

function calculateCriticalPath($tasks)
{
    $taskMap = [];
    foreach ($tasks as $task) {
        $taskMap[$task['id']] = $task;
    }

    // Step 1: Forward Pass (ES and EF)
    $esEf = [];

    $forwardPass = function ($taskId) use (&$taskMap, &$esEf, &$forwardPass) {
        $task = $taskMap[$taskId];
        if (empty($task['predecessors'])) {
            $es = 0;
        } else {
            $maxEf = 0;
            foreach ($task['predecessors'] as $preId) {
                if (!isset($esEf[$preId])) {
                    $forwardPass($preId);
                }
                $maxEf = max($maxEf, $esEf[$preId]['ef']);
            }
            $es = $maxEf;
        }

        $duration = is_numeric($task['duration']) ? (float) $task['duration'] : 0;
        $ef = $es + $duration;
        $esEf[$taskId] = ['es' => $es, 'ef' => $ef];
    };

    foreach ($tasks as $task) {
        $forwardPass($task['id']);
    }

    // Step 2: Backward Pass (LS and LF)
    $lfLs = [];
    $maxEf = max(array_column($esEf, 'ef'));

    $backwardPass = function ($taskId) use (&$taskMap, &$lfLs, $esEf, &$backwardPass) {
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
                    $backwardPass($succId);
                }
                $minLs = min($minLs, $lfLs[$succId]['ls']);
            }
            $lf = $minLs;
        }

        $duration = is_numeric($task['duration']) ? (float) $task['duration'] : 0;
        $ls = $lf - $duration;
        $lfLs[$taskId] = ['lf' => $lf, 'ls' => $ls];
    };

    $reversedTasks = array_reverse($tasks);
    foreach ($reversedTasks as $task) {
        $backwardPass($task['id']);
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
            'task_id' => $id,
            'es' => $es,
            'ef' => $ef,
            'ls' => $ls,
            'lf' => $lf,
            'slack' => $slack,
            'critical' => $slack == 0 ? 1 : 0
        ];
    }

    return $results;
}

// Update user details
function updateUserDetails($user_id, $data) {
    try {
        global $conn;
        $table_name = PREFIX . "users";
        
        // Build the SET clause dynamically
        $setClause = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            if ($key !== 'id') { // Don't allow updating the ID
                $setClause[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }
        
        if (empty($setClause)) {
            return ['status' => 'error', 'message' => 'No data to update'];
        }
        
        // Add updated_at timestamp
        $setClause[] = "updated_at = :updated_at";
        $params[':updated_at'] = date('Y-m-d H:i:s');
        
        $sql = "UPDATE $table_name SET " . implode(', ', $setClause) . " WHERE id = :user_id";
        $params[':user_id'] = $user_id;
        
        $stmt = $conn->prepare($sql);
        
        if ($stmt->execute($params)) {
            return ['status' => 'success', 'message' => 'User details updated successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to update user details'];
        }
        
    } catch (PDOException $e) {
        error_log("Database error in updateUserDetails: " . $e->getMessage());
        return ['status' => 'error', 'message' => 'Database error occurred'];
    } catch (Exception $e) {
        error_log("An error occurred in updateUserDetails: " . $e->getMessage());
        return ['status' => 'error', 'message' => 'An error occurred while updating user details'];
    }
}

// Delete user
function deleteUserById($user_id) {
    try {
        global $conn;
        $table_name = PREFIX . "users";
        $stmt = $conn->prepare("DELETE FROM $table_name WHERE id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return ['status' => 'success', 'message' => 'User deleted successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to delete user'];
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return ['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()];
    }
}

/**
 * Notification System Functions
 */

/**
 * Create a new notification
 * @param int $user_id - User to notify
 * @param string $type - Notification type (assignment, project, system, etc.)
 * @param string $title - Notification title
 * @param string $message - Notification message
 * @param array $data - Additional data (optional)
 * @param int $created_by - User who created the notification (optional)
 * @return array
 */
function createNotification($user_id, $type, $title, $message, $data = [], $created_by = null) {
    try {
        global $conn;
        
        if (!$created_by) {
            $created_by = $_SESSION['user_id'] ?? null;
        }
        
        $sql = "INSERT INTO notifications (user_id, type, title, message, data, created_by, created_at) 
                VALUES (:user_id, :type, :title, :message, :data, :created_by, NOW())";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'user_id' => $user_id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => json_encode($data),
            'created_by' => $created_by
        ]);
        
        return [
            'status' => 'success',
            'message' => 'Notification created successfully',
            'notification_id' => $conn->lastInsertId()
        ];
        
    } catch (Exception $e) {
        error_log("Error creating notification: " . $e->getMessage());
        return [
            'status' => 'error',
            'message' => 'Failed to create notification'
        ];
    }
}

/**
 * Get notifications for a user
 * @param int $user_id - User ID
 * @param int $limit - Number of notifications to return
 * @param int $offset - Offset for pagination
 * @param bool $unread_only - Return only unread notifications
 * @return array
 */
function getUserNotifications($user_id, $limit = 10, $offset = 0, $unread_only = false) {
    try {
        global $conn;
        
        $where_clause = "WHERE user_id = :user_id";
        if ($unread_only) {
            $where_clause .= " AND is_read = 0";
        }
        
        $sql = "SELECT n.*, 
                       u.firstname as created_by_firstname, 
                       u.lastname as created_by_lastname,
                       u.email as created_by_email
                FROM notifications n
                LEFT JOIN users u ON n.created_by = u.id
                {$where_clause}
                ORDER BY n.created_at DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Decode JSON data for each notification
        foreach ($notifications as &$notification) {
            if (!empty($notification['data'])) {
                $notification['data'] = json_decode($notification['data'], true);
            }
        }
        
        return $notifications;
        
    } catch (Exception $e) {
        error_log("Error getting user notifications: " . $e->getMessage());
        return [];
    }
}

/**
 * Get unread notification count for a user
 * @param int $user_id - User ID
 * @return int
 */
function getUnreadNotificationCount($user_id) {
    try {
        global $conn;
        
        $sql = "SELECT COUNT(*) as count FROM notifications WHERE user_id = :user_id AND is_read = 0";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['count'];
        
    } catch (Exception $e) {
        error_log("Error getting unread notification count: " . $e->getMessage());
        return 0;
    }
}

/**
 * Mark notification as read
 * @param int $notification_id - Notification ID
 * @param int $user_id - User ID (for security)
 * @return array
 */
function markNotificationAsRead($notification_id, $user_id) {
    try {
        global $conn;
        
        $sql = "UPDATE notifications SET is_read = 1, read_at = NOW() 
                WHERE id = :notification_id AND user_id = :user_id";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'notification_id' => $notification_id,
            'user_id' => $user_id
        ]);
        
        if ($stmt->rowCount() > 0) {
            return [
                'status' => 'success',
                'message' => 'Notification marked as read'
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Notification not found or access denied'
            ];
        }
        
    } catch (Exception $e) {
        error_log("Error marking notification as read: " . $e->getMessage());
        return [
            'status' => 'error',
            'message' => 'Failed to mark notification as read'
        ];
    }
}

/**
 * Mark all notifications as read for a user
 * @param int $user_id - User ID
 * @return array
 */
function markAllNotificationsAsRead($user_id) {
    try {
        global $conn;
        
        $sql = "UPDATE notifications SET is_read = 1, read_at = NOW() 
                WHERE user_id = :user_id AND is_read = 0";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        
        return [
            'status' => 'success',
            'message' => 'All notifications marked as read',
            'updated_count' => $stmt->rowCount()
        ];
        
    } catch (Exception $e) {
        error_log("Error marking all notifications as read: " . $e->getMessage());
        return [
            'status' => 'error',
            'message' => 'Failed to mark notifications as read'
        ];
    }
}

/**
 * Delete a notification
 * @param int $notification_id - Notification ID
 * @param int $user_id - User ID (for security)
 * @return array
 */
function deleteNotification($notification_id, $user_id) {
    try {
        global $conn;
        
        $sql = "DELETE FROM notifications WHERE id = :notification_id AND user_id = :user_id";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'notification_id' => $notification_id,
            'user_id' => $user_id
        ]);
        
        if ($stmt->rowCount() > 0) {
            return [
                'status' => 'success',
                'message' => 'Notification deleted successfully'
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Notification not found or access denied'
            ];
        }
        
    } catch (Exception $e) {
        error_log("Error deleting notification: " . $e->getMessage());
        return [
            'status' => 'error',
            'message' => 'Failed to delete notification'
        ];
    }
}

/**
 * Create notification for user assignment to project
 * @param int $user_id - User being assigned
 * @param int $project_id - Project ID
 * @param string $project_title - Project title
 * @param int $assigned_by - User who made the assignment
 * @return array
 */
function createAssignmentNotification($user_id, $project_id, $project_title, $assigned_by = null) {
    $assigned_by_user = null;
    if ($assigned_by) {
        $assigned_by_user = getUsersDetailsByUser_id($assigned_by);
    }
    
    $assigned_by_name = $assigned_by_user ? 
        $assigned_by_user['firstname'] . ' ' . $assigned_by_user['lastname'] : 
        'System';
    
    return createNotification(
        $user_id,
        'assignment',
        'Project Assignment',
        "You have been assigned to the project '{$project_title}' by {$assigned_by_name}.",
        [
            'project_id' => $project_id,
            'project_title' => $project_title,
            'assigned_by' => $assigned_by,
            'assigned_by_name' => $assigned_by_name
        ],
        $assigned_by
    );
}

/**
 * Create notification for user removal from project
 * @param int $user_id - User being removed
 * @param int $project_id - Project ID
 * @param string $project_title - Project title
 * @param int $removed_by - User who made the removal
 * @return array
 */
function createRemovalNotification($user_id, $project_id, $project_title, $removed_by = null) {
    $removed_by_user = null;
    if ($removed_by) {
        $removed_by_user = getUsersDetailsByUser_id($removed_by);
    }
    
    $removed_by_name = $removed_by_user ? 
        $removed_by_user['firstname'] . ' ' . $removed_by_user['lastname'] : 
        'System';
    
    return createNotification(
        $user_id,
        'removal',
        'Project Removal',
        "You have been removed from the project '{$project_title}' by {$removed_by_name}.",
        [
            'project_id' => $project_id,
            'project_title' => $project_title,
            'removed_by' => $removed_by,
            'removed_by_name' => $removed_by_name
        ],
        $removed_by
    );
}

/**
 * Create notification for user profile updates
 * @param int $user_id - User whose profile was updated
 * @param string $update_type - Type of update (name, email, role, etc.)
 * @param int $updated_by - User who made the update
 * @return array
 */
function createProfileUpdateNotification($user_id, $update_type, $updated_by = null) {
    $updated_by_user = null;
    if ($updated_by) {
        $updated_by_user = getUsersDetailsByUser_id($updated_by);
    }
    
    $updated_by_name = $updated_by_user ? 
        $updated_by_user['firstname'] . ' ' . $updated_by_user['lastname'] : 
        'System';
    
    $messages = [
        'name' => 'Your name has been updated',
        'email' => 'Your email address has been updated',
        'role' => 'Your role has been updated',
        'status' => 'Your account status has been updated',
        'general' => 'Your profile has been updated'
    ];
    
    $message = $messages[$update_type] ?? $messages['general'];
    $message .= " by {$updated_by_name}.";
    
    return createNotification(
        $user_id,
        'profile_update',
        'Profile Updated',
        $message,
        [
            'update_type' => $update_type,
            'updated_by' => $updated_by,
            'updated_by_name' => $updated_by_name
        ],
        $updated_by
    );
}

/**
 * Get notification icon based on type
 * @param string $type - Notification type
 * @return string
 */
function getNotificationIcon($type) {
    $icons = [
        'assignment' => 'fas fa-user-plus',
        'removal' => 'fas fa-user-minus',
        'profile_update' => 'fas fa-user-edit',
        'project' => 'fas fa-project-diagram',
        'task_status_change' => 'fas fa-tasks',
        'admin_task_update' => 'fas fa-user-shield',
        'task_created' => 'fas fa-plus-circle',
        'task_assigned' => 'fas fa-user-check',
        'system' => 'fas fa-cog',
        'warning' => 'fas fa-exclamation-triangle',
        'success' => 'fas fa-check-circle',
        'info' => 'fas fa-info-circle'
    ];
    
    return $icons[$type] ?? 'fas fa-bell';
}

/**
 * Get notification color based on type
 * @param string $type - Notification type
 * @return string
 */
function getNotificationColor($type) {
    $colors = [
        'assignment' => 'text-blue-600 bg-blue-100',
        'removal' => 'text-red-600 bg-red-100',
        'profile_update' => 'text-green-600 bg-green-100',
        'project' => 'text-purple-600 bg-purple-100',
        'task_status_change' => 'text-orange-600 bg-orange-100',
        'admin_task_update' => 'text-indigo-600 bg-indigo-100',
        'task_created' => 'text-emerald-600 bg-emerald-100',
        'task_assigned' => 'text-cyan-600 bg-cyan-100',
        'system' => 'text-gray-600 bg-gray-100',
        'warning' => 'text-yellow-600 bg-yellow-100',
        'success' => 'text-green-600 bg-green-100',
        'info' => 'text-blue-600 bg-blue-100'
    ];
    
    return $colors[$type] ?? 'text-gray-600 bg-gray-100';
}

/**
 * Get project details by ID
 * @param int $project_id - Project ID
 * @return array|null
 */
function getProjectById($project_id) {
    try {
        global $conn;
        $table_name = PREFIX . "projects";
        
        $sql = "SELECT * FROM $table_name WHERE id = :project_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['project_id' => $project_id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (Exception $e) {
        error_log("Error getting project by ID: " . $e->getMessage());
        return null;
    }
}

/**
 * Get task details by ID
 * @param int $task_id - Task ID
 * @return array|null
 */
function getTaskById($task_id) {
    try {
        global $conn;
        $table_name = PREFIX . "tasks";
        
        $sql = "SELECT * FROM $table_name WHERE id = :task_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['task_id' => $task_id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (Exception $e) {
        error_log("Error getting task by ID: " . $e->getMessage());
        return null;
    }
}

/**
 * Get all admin users
 * @return array
 */
function getAdminUsers() {
    try {
        global $conn;
        $table_name = PREFIX . "users";
        
        $sql = "SELECT id, firstname, lastname, email FROM $table_name WHERE user_role = 'admin'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (Exception $e) {
        error_log("Error getting admin users: " . $e->getMessage());
        return [];
    }
}

/**
 * Get users assigned to a specific task
 * @param int $task_id - Task ID
 * @return array
 */
function getTaskAssignedUsers($task_id) {
    try {
        global $conn;
        $users_table = PREFIX . "users";
        $project_meta_table = PREFIX . "project_meta";
        $tasks_table = PREFIX . "tasks";
        
        $sql = "SELECT DISTINCT u.id, u.firstname, u.lastname, u.email 
                FROM $users_table u 
                INNER JOIN $project_meta_table pm ON u.id = pm.user_id 
                INNER JOIN $tasks_table t ON pm.project_id = t.project_id 
                WHERE t.id = :task_id";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute(['task_id' => $task_id]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (Exception $e) {
        error_log("Error getting task assigned users: " . $e->getMessage());
        return [];
    }
}

/**
 * Create notification for task status change by user
 * @param int $task_id - Task ID
 * @param string $old_status - Previous status
 * @param string $new_status - New status
 * @param int $changed_by - User who changed the status
 * @return array
 */
function createTaskStatusChangeNotification($task_id, $old_status, $new_status, $changed_by = null) {
    try {
        // Get task details
        $task = getTaskById($task_id);
        if (!$task) {
            return ['status' => 'error', 'message' => 'Task not found'];
        }
        
        // Get project details
        $project = getProjectById($task['project_id']);
        if (!$project) {
            return ['status' => 'error', 'message' => 'Project not found'];
        }
        
        // Get user who changed the status
        $changed_by_user = null;
        if ($changed_by) {
            $changed_by_user = getUsersDetailsByUser_id($changed_by);
        }
        
        $changed_by_name = $changed_by_user ? 
            $changed_by_user['firstname'] . ' ' . $changed_by_user['lastname'] : 
            'Unknown User';
        
        // Get all admin users to notify
        $admin_users = getAdminUsers();
        
        $notifications_created = 0;
        $errors = [];
        
        foreach ($admin_users as $admin) {
            // Don't notify the user who made the change if they are an admin
            if ($admin['id'] == $changed_by) {
                continue;
            }
            
            $result = createNotification(
                $admin['id'],
                'task_status_change',
                'Task Status Updated',
                "{$changed_by_name} changed task '{$task['title']}' status from " . ucfirst(str_replace('-', ' ', $old_status)) . " to " . ucfirst(str_replace('-', ' ', $new_status)) . " in project '{$project['title']}'.",
                [
                    'task_id' => $task_id,
                    'task_title' => $task['title'],
                    'project_id' => $task['project_id'],
                    'project_title' => $project['title'],
                    'old_status' => $old_status,
                    'new_status' => $new_status,
                    'changed_by' => $changed_by,
                    'changed_by_name' => $changed_by_name
                ],
                $changed_by
            );
            
            if ($result['status'] === 'success') {
                $notifications_created++;
            } else {
                $errors[] = "Failed to notify admin {$admin['firstname']} {$admin['lastname']}: " . $result['message'];
            }
        }
        
        return [
            'status' => 'success',
            'message' => "Created {$notifications_created} notifications for admins",
            'notifications_created' => $notifications_created,
            'errors' => $errors
        ];
        
    } catch (Exception $e) {
        error_log("Error creating task status change notification: " . $e->getMessage());
        return ['status' => 'error', 'message' => 'Failed to create task status change notifications'];
    }
}

/**
 * Create notification for task status change by admin
 * @param int $task_id - Task ID
 * @param string $old_status - Previous status
 * @param string $new_status - New status
 * @param int $changed_by - Admin who changed the status
 * @return array
 */
function createAdminTaskStatusChangeNotification($task_id, $old_status, $new_status, $changed_by = null) {
    try {
        // Get task details
        $task = getTaskById($task_id);
        if (!$task) {
            return ['status' => 'error', 'message' => 'Task not found'];
        }
        
        // Get project details
        $project = getProjectById($task['project_id']);
        if (!$project) {
            return ['status' => 'error', 'message' => 'Project not found'];
        }
        
        // Get admin who changed the status
        $changed_by_user = null;
        if ($changed_by) {
            $changed_by_user = getUsersDetailsByUser_id($changed_by);
        }
        
        $changed_by_name = $changed_by_user ? 
            $changed_by_user['firstname'] . ' ' . $changed_by_user['lastname'] : 
            'Admin';
        
        // Get users assigned to this task/project
        $assigned_users = getTaskAssignedUsers($task_id);
        
        $notifications_created = 0;
        $errors = [];
        
        foreach ($assigned_users as $user) {
            // Don't notify the admin who made the change
            if ($user['id'] == $changed_by) {
                continue;
            }
            
            $result = createNotification(
                $user['id'],
                'admin_task_update',
                'Task Status Updated by Admin',
                "Admin {$changed_by_name} changed task '{$task['title']}' status from " . ucfirst(str_replace('-', ' ', $old_status)) . " to " . ucfirst(str_replace('-', ' ', $new_status)) . " in project '{$project['title']}'.",
                [
                    'task_id' => $task_id,
                    'task_title' => $task['title'],
                    'project_id' => $task['project_id'],
                    'project_title' => $project['title'],
                    'old_status' => $old_status,
                    'new_status' => $new_status,
                    'changed_by' => $changed_by,
                    'changed_by_name' => $changed_by_name
                ],
                $changed_by
            );
            
            if ($result['status'] === 'success') {
                $notifications_created++;
            } else {
                $errors[] = "Failed to notify user {$user['firstname']} {$user['lastname']}: " . $result['message'];
            }
        }
        
        return [
            'status' => 'success',
            'message' => "Created {$notifications_created} notifications for assigned users",
            'notifications_created' => $notifications_created,
            'errors' => $errors
        ];
        
    } catch (Exception $e) {
        error_log("Error creating admin task status change notification: " . $e->getMessage());
        return ['status' => 'error', 'message' => 'Failed to create admin task status change notifications'];
    }
}

/**
 * Check if user is admin
 * @param int $user_id - User ID
 * @return bool
 */
function isUserAdmin($user_id) {
    try {
        global $conn;
        
        $table_name = PREFIX . "users";
        $sql = "SELECT user_role FROM $table_name WHERE id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result && $result['user_role'] === 'admin';
        
    } catch (Exception $e) {
        error_log("Error checking if user is admin: " . $e->getMessage());
        return false;
    }
}

/**
 * Create notification for task creation
 * @param int $task_id - Task ID
 * @param int $created_by - User who created the task
 * @return array
 */
function createTaskCreationNotification($task_id, $created_by = null) {
    try {
        // Get task details
        $task = getTaskById($task_id);
        if (!$task) {
            return ['status' => 'error', 'message' => 'Task not found'];
        }
        
        // Get project details
        $project = getProjectById($task['project_id']);
        if (!$project) {
            return ['status' => 'error', 'message' => 'Project not found'];
        }
        
        // Get user who created the task
        $created_by_user = null;
        if ($created_by) {
            $created_by_user = getUsersDetailsByUser_id($created_by);
        }
        
        $created_by_name = $created_by_user ? 
            $created_by_user['firstname'] . ' ' . $created_by_user['lastname'] : 
            'Unknown User';
        
        // Get all admin users to notify about new task
        $admin_users = getAdminUsers();
        
        $notifications_created = 0;
        $errors = [];
        
        foreach ($admin_users as $admin) {
            // Don't notify the user who created the task if they are an admin
            if ($admin['id'] == $created_by) {
                continue;
            }
            
            $result = createNotification(
                $admin['id'],
                'task_created',
                'New Task Created',
                "{$created_by_name} created a new task '{$task['title']}' in project '{$project['title']}'.",
                [
                    'task_id' => $task_id,
                    'task_title' => $task['title'],
                    'project_id' => $task['project_id'],
                    'project_title' => $project['title'],
                    'task_priority' => $task['priority'] ?? 'medium',
                    'task_status' => $task['status'] ?? 'pending',
                    'created_by' => $created_by,
                    'created_by_name' => $created_by_name
                ],
                $created_by
            );
            
            if ($result['status'] === 'success') {
                $notifications_created++;
            } else {
                $errors[] = "Failed to notify admin {$admin['firstname']} {$admin['lastname']}: " . $result['message'];
            }
        }
        
        return [
            'status' => 'success',
            'message' => "Created {$notifications_created} notifications for admins about new task",
            'notifications_created' => $notifications_created,
            'errors' => $errors
        ];
        
    } catch (Exception $e) {
        error_log("Error creating task creation notification: " . $e->getMessage());
        return ['status' => 'error', 'message' => 'Failed to create task creation notifications'];
    }
}

/**
 * Create notification for task assignment
 * @param int $task_id - Task ID
 * @param int $assigned_user_id - User assigned to the task
 * @param int $assigned_by - User who assigned the task
 * @return array
 */
function createTaskAssignmentNotification($task_id, $assigned_user_id, $assigned_by = null) {
    try {
        // Get task details
        $task = getTaskById($task_id);
        if (!$task) {
            return ['status' => 'error', 'message' => 'Task not found'];
        }
        
        // Get project details
        $project = getProjectById($task['project_id']);
        if (!$project) {
            return ['status' => 'error', 'message' => 'Project not found'];
        }
        
        // Get user who assigned the task
        $assigned_by_user = null;
        if ($assigned_by) {
            $assigned_by_user = getUsersDetailsByUser_id($assigned_by);
        }
        
        $assigned_by_name = $assigned_by_user ? 
            $assigned_by_user['firstname'] . ' ' . $assigned_by_user['lastname'] : 
            'Admin';
        
        // Get assigned user details
        $assigned_user = getUsersDetailsByUser_id($assigned_user_id);
        if (!$assigned_user) {
            return ['status' => 'error', 'message' => 'Assigned user not found'];
        }
        
        // Notify the assigned user
        $result = createNotification(
            $assigned_user_id,
            'task_assigned',
            'Task Assigned to You',
            "You have been assigned to task '{$task['title']}' in project '{$project['title']}' by {$assigned_by_name}.",
            [
                'task_id' => $task_id,
                'task_title' => $task['title'],
                'project_id' => $task['project_id'],
                'project_title' => $project['title'],
                'task_priority' => $task['priority'] ?? 'medium',
                'task_status' => $task['status'] ?? 'pending',
                'assigned_by' => $assigned_by,
                'assigned_by_name' => $assigned_by_name,
                'due_date' => $task['due_date'] ?? null
            ],
            $assigned_by
        );
        
        if ($result['status'] === 'success') {
            return [
                'status' => 'success',
                'message' => "Task assignment notification sent to {$assigned_user['firstname']} {$assigned_user['lastname']}",
                'notifications_created' => 1
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Failed to send task assignment notification: ' . $result['message']
            ];
        }
        
    } catch (Exception $e) {
        error_log("Error creating task assignment notification: " . $e->getMessage());
        return ['status' => 'error', 'message' => 'Failed to create task assignment notification'];
    }
}

/**
 * Create notification for task assignment to multiple users
 * @param int $task_id - Task ID
 * @param array $assigned_user_ids - Array of user IDs assigned to the task
 * @param int $assigned_by - User who assigned the task
 * @return array
 */
function createMultipleTaskAssignmentNotification($task_id, $assigned_user_ids, $assigned_by = null) {
    try {
        // Get task details
        $task = getTaskById($task_id);
        if (!$task) {
            return ['status' => 'error', 'message' => 'Task not found'];
        }
        
        // Get project details
        $project = getProjectById($task['project_id']);
        if (!$project) {
            return ['status' => 'error', 'message' => 'Project not found'];
        }
        
        // Get user who assigned the task
        $assigned_by_user = null;
        if ($assigned_by) {
            $assigned_by_user = getUsersDetailsByUser_id($assigned_by);
        }
        
        $assigned_by_name = $assigned_by_user ? 
            $assigned_by_user['firstname'] . ' ' . $assigned_by_user['lastname'] : 
            'Admin';
        
        $notifications_created = 0;
        $errors = [];
        
        foreach ($assigned_user_ids as $user_id) {
            // Get assigned user details
            $assigned_user = getUsersDetailsByUser_id($user_id);
            if (!$assigned_user) {
                $errors[] = "User ID {$user_id} not found";
                continue;
            }
            
            // Don't notify the user who assigned the task
            if ($user_id == $assigned_by) {
                continue;
            }
            
            $result = createNotification(
                $user_id,
                'task_assigned',
                'Task Assigned to You',
                "You have been assigned to task '{$task['title']}' in project '{$project['title']}' by {$assigned_by_name}.",
                [
                    'task_id' => $task_id,
                    'task_title' => $task['title'],
                    'project_id' => $task['project_id'],
                    'project_title' => $project['title'],
                    'task_priority' => $task['priority'] ?? 'medium',
                    'task_status' => $task['status'] ?? 'pending',
                    'assigned_by' => $assigned_by,
                    'assigned_by_name' => $assigned_by_name,
                    'due_date' => $task['due_date'] ?? null
                ],
                $assigned_by
            );
            
            if ($result['status'] === 'success') {
                $notifications_created++;
            } else {
                $errors[] = "Failed to notify user {$assigned_user['firstname']} {$assigned_user['lastname']}: " . $result['message'];
            }
        }
        
        return [
            'status' => 'success',
            'message' => "Created {$notifications_created} task assignment notifications",
            'notifications_created' => $notifications_created,
            'errors' => $errors
        ];
        
    } catch (Exception $e) {
        error_log("Error creating multiple task assignment notification: " . $e->getMessage());
        return ['status' => 'error', 'message' => 'Failed to create task assignment notifications'];
    }
}

/**
 * Create comprehensive notification for task creation
 * This function handles both admin notifications and user assignment notifications
 * @param int $task_id - Task ID
 * @param int $created_by - User who created the task (sender)
 * @param int $assigned_to - User assigned to the task (receiver)
 * @return array
 */
function createTaskCreationNotificationComprehensive($task_id, $created_by = null, $assigned_to = null) {
    try {
        // Get task details
        $task = getTaskById($task_id);
        if (!$task) {
            return ['status' => 'error', 'message' => 'Task not found'];
        }
        
        // Get project details
        $project = getProjectById($task['project_id']);
        if (!$project) {
            return ['status' => 'error', 'message' => 'Project not found'];
        }
        
        // Get user who created the task (sender)
        $created_by_user = null;
        if ($created_by) {
            $created_by_user = getUsersDetailsByUser_id($created_by);
        }
        
        $created_by_name = $created_by_user ? 
            $created_by_user['firstname'] . ' ' . $created_by_user['lastname'] : 
            'Unknown User';
        
        $notifications_created = 0;
        $errors = [];
        
        // 1. Notify admins about new task creation (if creator is not admin)
        $admin_users = getAdminUsers();
        foreach ($admin_users as $admin) {
            // Don't notify the user who created the task if they are an admin
            if ($admin['id'] == $created_by) {
                continue;
            }
            
            $result = createNotification(
                $admin['id'],
                'task_created',
                'New Task Created',
                "{$created_by_name} created a new task '{$task['title']}' in project '{$project['title']}'.",
                [
                    'task_id' => $task_id,
                    'task_title' => $task['title'],
                    'project_id' => $task['project_id'],
                    'project_title' => $project['title'],
                    'task_priority' => $task['priority'] ?? 'medium',
                    'task_status' => $task['status'] ?? 'pending',
                    'created_by' => $created_by,
                    'created_by_name' => $created_by_name,
                    'sender' => $created_by,
                    'receiver' => $admin['id']
                ],
                $created_by
            );
            
            if ($result['status'] === 'success') {
                $notifications_created++;
            } else {
                $errors[] = "Failed to notify admin {$admin['firstname']} {$admin['lastname']}: " . $result['message'];
            }
        }
        
        // 2. Notify the assigned user (receiver) if task is assigned
        if ($assigned_to && $assigned_to > 0) {
            // Get assigned user details
            $assigned_user = getUsersDetailsByUser_id($assigned_to);
            if ($assigned_user) {
                // Don't notify the user who created the task if they assigned it to themselves
                if ($assigned_to != $created_by) {
                    $result = createNotification(
                        $assigned_to,
                        'task_assigned',
                        'Task Assigned to You',
                        "You have been assigned to task '{$task['title']}' in project '{$project['title']}' by {$created_by_name}.",
                        [
                            'task_id' => $task_id,
                            'task_title' => $task['title'],
                            'project_id' => $task['project_id'],
                            'project_title' => $project['title'],
                            'task_priority' => $task['priority'] ?? 'medium',
                            'task_status' => $task['status'] ?? 'pending',
                            'created_by' => $created_by,
                            'created_by_name' => $created_by_name,
                            'sender' => $created_by,
                            'receiver' => $assigned_to,
                            'due_date' => $task['deadline'] ?? null
                        ],
                        $created_by
                    );
                    
                    if ($result['status'] === 'success') {
                        $notifications_created++;
                    } else {
                        $errors[] = "Failed to notify assigned user {$assigned_user['firstname']} {$assigned_user['lastname']}: " . $result['message'];
                    }
                }
            } else {
                $errors[] = "Assigned user ID {$assigned_to} not found";
            }
        }
        
        return [
            'status' => 'success',
            'message' => "Created {$notifications_created} notifications for task creation",
            'notifications_created' => $notifications_created,
            'errors' => $errors,
            'task_id' => $task_id,
            'sender' => $created_by,
            'receiver' => $assigned_to
        ];
        
    } catch (Exception $e) {
        error_log("Error creating comprehensive task creation notification: " . $e->getMessage());
        return ['status' => 'error', 'message' => 'Failed to create task creation notifications: ' . $e->getMessage()];
    }
}

function updateProfileAjax($data, $files = null)
{
    try {
        global $conn;
        $table_name = PREFIX . "users";
        
        // Get current user
        $currentUser = getCurrentUser();
        if (!$currentUser) {
            return ['status' => 'error', 'message' => 'User not authenticated'];
        }
        
        $user_id = $currentUser['id'];
        $updateData = [];
        
        // Validate and prepare update data
        if (isset($data['firstname']) && !empty(trim($data['firstname']))) {
            $updateData['firstname'] = trim($data['firstname']);
        }
        
        if (isset($data['lastname']) && !empty(trim($data['lastname']))) {
            $updateData['lastname'] = trim($data['lastname']);
        }
        
        if (isset($data['bio'])) {
            $updateData['bio'] = trim($data['bio']);
        }
        
        // Handle profile picture upload
        if (isset($files['profile_picture']) && $files['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $base_dir = '/assets/uploads/';
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . $base_dir;
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileInfo = pathinfo($files['profile_picture']['name']);
            $extension = strtolower($fileInfo['extension']);
            
            // Validate file type
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($extension, $allowedTypes)) {
                // Validate file size (5MB max)
                if ($files['profile_picture']['size'] > 5 * 1024 * 1024) {
                    return ['status' => 'error', 'message' => 'File size must be less than 5MB'];
                }
                
                // Generate unique filename with date prefix
                $datePrefix = date('Ymd_His');
                $filename = $datePrefix . '_profile_' . $user_id . '.' . $extension;
                $targetPath = $uploadDir . $filename;
                
                if (move_uploaded_file($files['profile_picture']['tmp_name'], $targetPath)) {
                    // Store relative path in database
                    $updateData['profile_picture'] = $base_dir . $filename;
                } else {
                    return ['status' => 'error', 'message' => 'Failed to upload profile picture'];
                }
            } else {
                return ['status' => 'error', 'message' => 'Invalid file type. Please upload JPG, PNG, GIF, or WebP images only'];
            }
        }
        
        // Update the user data
        if (!empty($updateData)) {
            $result = updateUserDetails($user_id, $updateData);
            if ($result['status'] === 'success') {
                return [
                    'status' => 'success', 
                    'message' => 'Profile updated successfully!',
                    'user_data' => getUsersDetailsByUser_id($user_id)
                ];
            } else {
                return ['status' => 'error', 'message' => $result['message'] ?? 'Failed to update profile'];
            }
        } else {
            return ['status' => 'error', 'message' => 'No data to update'];
        }
        
    } catch (PDOException $e) {
        error_log("Database error in updateProfileAjax: " . $e->getMessage());
        return ['status' => 'error', 'message' => 'Database error occurred'];
    } catch (Exception $e) {
        error_log("An error occurred in updateProfileAjax: " . $e->getMessage());
        return ['status' => 'error', 'message' => 'An error occurred while updating profile'];
    }
}

// Function to send OTP email for password reset
function sendOtpEmail($email, $otp, $user)
{
    $subject = "Workfyre - Password Reset OTP";
    
    $message = "
    <html>
    <head>
        <title>Password Reset OTP</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
            .otp-box { background: #fff; border: 2px dashed #667eea; padding: 20px; text-align: center; margin: 20px 0; border-radius: 10px; }
            .otp-code { font-size: 32px; font-weight: bold; color: #667eea; letter-spacing: 5px; }
            .warning { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0; }
            .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Workfyre</h1>
                <h2>Password Reset OTP</h2>
            </div>
            <div class='content'>
                <h3>Hello {$user['firstname']} {$user['lastname']},</h3>
                <p>You have requested to reset your password for your Workfyre account.</p>
                <p>Please use the following OTP to verify your identity:</p>
                
                <div class='otp-box'>
                    <div class='otp-code'>{$otp}</div>
                    <p><strong>Your 6-digit OTP</strong></p>
                </div>
                
                <div class='warning'>
                    <p><strong>⚠️ Important:</strong></p>
                    <ul>
                        <li>This OTP will expire in 10 minutes</li>
                        <li>Do not share this OTP with anyone</li>
                        <li>If you didn't request this password reset, please ignore this email</li>
                    </ul>
                </div>
                
                <p>If you have any questions, please contact our support team.</p>
            </div>
            <div class='footer'>
                <p>Thank you,<br><strong>Workfyre Team</strong></p>
                <p>This is an automated message, please do not reply to this email.</p>
            </div>
        </div>
    </body>
    </html>
    ";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: noreply@workfyre.com.np";
    $headers .= "Reply-To: noreply@workfyre.com.np" . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    return mail($email, $subject, $message, $headers);
}


