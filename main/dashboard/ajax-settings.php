<?php
session_start();
include_once(__DIR__ . '/../../config/config.php');
include_once(__DIR__ . '/../../config/connection.php');
// include_once(__DIR__ . '/../../config/functions.php'); // Commented out to avoid redeclaration

// Essential functions we need
function getCurrentUser() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        global $conn;
        $table_name = PREFIX . "users";
        
        $stmt = $conn->prepare("SELECT * FROM $table_name WHERE id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function isUserLoggedIn() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        return true;
    } else {
        return false;
    }
}

function getUsersDetailsByUser_id($user_id) {
    try {
        global $conn;
        $table_name = PREFIX . "users";
        
        $stmt = $conn->prepare("SELECT * FROM $table_name WHERE id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error in getUsersDetailsByUser_id: " . $e->getMessage());
        return null;
    }
}

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

function changePassword($current_password, $new_password, $user_id = null) {
    try {
        global $conn;
        $table_name = PREFIX . "users";
        
        // If user_id is not provided, get it from session
        if (!$user_id) {
            $currentUser = getCurrentUser();
            if (!$currentUser) {
                return ['status' => 'error', 'message' => 'User not authenticated'];
            }
            $user_id = $currentUser['id'];
        }
        
        // Get current user data
        $stmt = $conn->prepare("SELECT password FROM $table_name WHERE id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            return ['status' => 'error', 'message' => 'User not found'];
        }
        
        // Verify current password
        if (!password_verify($current_password, $user['password'])) {
            return ['status' => 'error', 'message' => 'Current password is incorrect'];
        }
        
        // Hash new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update password
        $stmt = $conn->prepare("UPDATE $table_name SET password = :password, updated_at = :updated_at WHERE id = :user_id");
        $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return ['status' => 'success', 'message' => 'Password changed successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to change password'];
        }
        
    } catch (PDOException $e) {
        error_log("Database error in changePassword: " . $e->getMessage());
        return ['status' => 'error', 'message' => 'Database error occurred'];
    } catch (Exception $e) {
        error_log("An error occurred in changePassword: " . $e->getMessage());
        return ['status' => 'error', 'message' => 'An error occurred while changing password'];
    }
}

// Check if user is logged in
if (!isUserLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
    exit();
}

// Get the action from POST data
$action = $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'update_profile':
            updateProfileAjax($_POST, $_FILES);
            break;
        case 'change_password':
            changePasswordAjax($_POST);
            break;
        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
            break;
    }
} catch (Exception $e) {
    error_log('Error in ajax-settings.php: ' . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'An error occurred']);
}

function changePasswordAjax($data)
{
    try {
        if (empty($data['current_password']) || empty($data['new_password'])) {
            echo json_encode(['status' => 'error', 'message' => 'Current password and new password are required.']);
            return;
        }

        // Validate password length
        if (strlen($data['new_password']) < 6) {
            echo json_encode(['status' => 'error', 'message' => 'New password must be at least 6 characters long.']);
            return;
        }

        $currentUser = getCurrentUser();
        $result = changePassword($data['current_password'], $data['new_password'], $currentUser['id']);

        if ($result['status'] === 'success') {
            echo json_encode(['status' => 'success', 'message' => $result['message']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => $result['message']]);
        }

    } catch (Exception $e) {
        error_log('Error in changePasswordAjax: ' . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while changing password']);
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
            echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
            return;
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
                    echo json_encode(['status' => 'error', 'message' => 'File size must be less than 5MB']);
                    return;
                }
                
                // Generate unique filename with date prefix
                $datePrefix = date('Ymd_His');
                $filename = $datePrefix . '_profile_' . $user_id . '.' . $extension;
                $targetPath = $uploadDir . $filename;
                
                if (move_uploaded_file($files['profile_picture']['tmp_name'], $targetPath)) {
                    // Store relative path in database
                    $updateData['profile_image'] = $base_dir . $filename;
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to upload profile picture']);
                    return;
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Please upload JPG, PNG, GIF, or WebP images only']);
                return;
            }
        }
        
        // Update the user data
        if (!empty($updateData)) {
            $result = updateUserDetails($user_id, $updateData);
            if ($result['status'] === 'success') {
                echo json_encode([
                    'status' => 'success', 
                    'message' => 'Profile updated successfully!',
                    'user_data' => getUsersDetailsByUser_id($user_id)
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => $result['message'] ?? 'Failed to update profile']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No data to update']);
        }
        
    } catch (PDOException $e) {
        error_log("Database error in updateProfileAjax: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Database error occurred']);
    } catch (Exception $e) {
        error_log("An error occurred in updateProfileAjax: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while updating profile']);
    }
}
?> 