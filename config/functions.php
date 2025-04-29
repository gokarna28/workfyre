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