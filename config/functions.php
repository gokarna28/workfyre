<?php
include_once('connection.php');
include_once('config.php');

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

        $stmt = $conn->prepare("INSERT INTO $table_name (project_id, title, priority, deadline, assign_to, description, created_at, updated_at) 
        VALUES (:project_id, :title, :priority, :deadline, :assign_to, :description, :created_at, :updated_at)");

        $stmt->bindParam(':project_id', $params['project_id'], PDO::PARAM_INT);
        $stmt->bindParam(':title', $params['task_title'], PDO::PARAM_STR);
        $stmt->bindParam(':priority', $params['task_priority'], PDO::PARAM_STR);
        $stmt->bindParam(':deadline', $params['task_deadline'], PDO::PARAM_STR);
        $stmt->bindParam(':assign_to', $params['task_assignto'], PDO::PARAM_INT);
        $stmt->bindParam(':description', $params['task_description'], PDO::PARAM_STR);
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

function getProjectDetails()
{
    try {
        global $conn;
        $table_name = PREFIX . "projects";

        $stmt = $conn->prepare("SELECT * FROM $table_name ORDER BY id desc");

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

function getClasses($params)
{
    $classes = ' ';
    $status = strtolower($params);

    switch ($status) {
        case 'completed':
            $classes .= ' bg-sky-200 text-sky-500';
            break;
        case 'in_progress':
            $classes .= ' bg-yellow-200 text-yellow-500';
            break;
        case 'pending':
            $classes .= ' bg-yellow-200 text-yellow-500';
            break;
        case 'not_started':
            $classes .= ' bg-stone-200 text-stone-500';
            break;
        case 'inrolled':
            $classes .= ' bg-stone-200 text-stone-500';
            break;
        case 'medium':
            $classes .= ' bg-lime-200 text-lime-500';
            break;
        case 'low':
            $classes .= ' bg-orange-200 text-orange-500';
            break;
        case 'active':
            $classes .= ' bg-green-200 text-green-500';
            break;

        default:
            $classes .= ' bg-offred color-red';
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