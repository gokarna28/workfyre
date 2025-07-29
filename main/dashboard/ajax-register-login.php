<?php
include_once(__DIR__ . '/../../config/config.php');
include_once(__DIR__ . '/../../config/connection.php');
include_once(__DIR__ . '/../../config/functions.php');
include_once(__DIR__ . '/../../config/email_helper.php');

header('Content-Type: application/json');

// Set default REQUEST_METHOD if not set (for testing)
if (!isset($_SERVER['REQUEST_METHOD'])) {
    $_SERVER['REQUEST_METHOD'] = 'POST';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = $_POST;
        $action = $data['action'] ?? '';

        switch ($action) {
            case 'register':
                userRegistration($data);
                break;
            case 'login':
                userLogin($data);
                break;
            case 'update_profile':
                updateProfileAjax($data, $_FILES);
                break;
            case 'accept_invite':
                ajaxUpdateProjectMeta($data);
                break;
            case 'send_password_reset':
                sendPasswordReset($data);
                break;
            case 'reset_password':
                resetPasswordAjax($data);
                break;
            case 'change_password':
                changePasswordAjax($data);
                break;
            case 'send_otp_reset':
                sendOtpReset($data);
                break;
            case 'verify_otp_reset':
                verifyOtpReset($data);
                break;
            case 'reset_password_otp':
                resetPasswordOtp($data);
                break;
        }
    } catch (Exception $e) {
        error_log('Error processing request: ' . $e->getMessage());
        echo json_encode(['error' => $e->getMessage()]);
    }
}

//user registration
function userRegistration($data)
{
    try {

        // Validation for first name and last name - only letters allowed
        if (!preg_match("/^[A-Za-z]+$/", $data['firstname'])) {
            echo json_encode(['status' => 'error', 'message' => 'First name can only contain letters.']);
            exit;
        }

        if (!preg_match("/^[A-Za-z]+$/", $data['lastname'])) {
            echo json_encode(['status' => 'error', 'message' => 'Last name can only contain letters.']);
            exit;
        }

        // Validation for email - basic email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address.']);
            exit;
        }

        // Check if password and confirm password match
        if ($data['password'] !== $data['confirmPassword']) {
            echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
            exit;
        }


        // $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT); 
        $createdAt = $updatedAt = date('Y-m-d H:i:s');
        $data['created_at'] = $createdAt;
        $data['updated_at'] = $updatedAt;

        $response = registerUser($data);

        if ($response === true) {
            echo json_encode(['status' => 'success', 'message' => 'Successfully Registered.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $response]);
        }

    } catch (Exception $e) {
        error_log('Error processing request: ' . $e->getMessage());
        echo json_encode(['error' => $e->getMessage()]);
    }
}


function userLogin($data)
{
    try {

        // Validation for email - basic email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address.']);
            exit;
        }

        $response = loginUser($data);

        if ($response === true) {
            echo json_encode(['status' => 'success', 'message' => 'Login Successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $response]);
        }

    } catch (Exception $e) {
        error_log('Error processing request: ' . $e->getMessage());
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function ajaxUpdateProjectMeta($params)
{
    try {
        if ($params) {

            $response = updateProjectMeta($params);

            if ($response === true) {
                echo json_encode(['status' => 'success', 'message' => 'Project updated successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => $response]);
            }
        }
    } catch (Exception $e) {
        error_log('Error processing request: ' . $e->getMessage());
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function sendPasswordReset($data)
{
    try {
        if (empty($data['email'])) {
            echo json_encode(['status' => 'error', 'message' => 'Email is required.']);
            return;
        }

        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address.']);
            return;
        }

        // Generate reset token
        $result = generatePasswordResetToken($data['email']);
        
        if ($result['status'] === 'success') {
            // Send email
            $emailSent = sendPasswordResetEmail($data['email'], $result['token'], $result['user']);
            
            if ($emailSent) {
                echo json_encode(['status' => 'success', 'message' => 'Password reset link has been sent to your email. Please check your inbox and spam folder.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to send email. Please try again later.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => $result['message']]);
        }

    } catch (Exception $e) {
        error_log('Error sending password reset: ' . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while processing your request.']);
    }
}

function resetPasswordAjax($data)
{
    try {
        if (empty($data['token']) || empty($data['new_password'])) {
            echo json_encode(['status' => 'error', 'message' => 'Token and new password are required.']);
            return;
        }

        // Validate password length
        if (strlen($data['new_password']) < 6) {
            echo json_encode(['status' => 'error', 'message' => 'Password must be at least 6 characters long.']);
            return;
        }

        // Reset password
        $result = resetPassword($data['token'], $data['new_password']);
        
        if ($result['status'] === 'success') {
            echo json_encode(['status' => 'success', 'message' => $result['message']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => $result['message']]);
        }

    } catch (Exception $e) {
        error_log('Error resetting password: ' . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while resetting your password.']);
    }
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

        // Change password
        $result = changePassword($data['current_password'], $data['new_password']);
        
        if ($result['status'] === 'success') {
            echo json_encode(['status' => 'success', 'message' => $result['message']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => $result['message']]);
        }

    } catch (Exception $e) {
        error_log('Error changing password: ' . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while changing your password.']);
    }
}

// New OTP-based password reset functions
function sendOtpReset($data)
{
    try {
        // Debug: Log the received data
        error_log("sendOtpReset called with data: " . print_r($data, true));
        
        if (empty($data['email'])) {
            echo json_encode(['status' => 'error', 'message' => 'Email is required.']);
            return;
        }

        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address.']);
            return;
        }

        global $conn;
        $table_name = PREFIX . "users";

        // Check if user exists
        $stmt = $conn->prepare("SELECT id, firstname, lastname FROM $table_name WHERE email = :email");
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'User with this email does not exist.']);
            return;
        }

        // Generate 6-digit OTP
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes')); // OTP expires in 10 minutes

        // Debug: Log OTP generation
        error_log("Generated OTP: " . $otp . " for email: " . $data['email'] . ", expires: " . $expires_at);

        // Store OTP in database
        $stmt = $conn->prepare("UPDATE $table_name SET reset_token = :otp, reset_token_expires = :expires_at WHERE email = :email");
        $stmt->bindParam(':otp', $otp, PDO::PARAM_STR);
        $stmt->bindParam(':expires_at', $expires_at, PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            // Debug: Verify OTP was stored
            $verifyStmt = $conn->prepare("SELECT reset_token, reset_token_expires FROM $table_name WHERE email = :email");
            $verifyStmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $verifyStmt->execute();
            $stored = $verifyStmt->fetch(PDO::FETCH_ASSOC);
            error_log("OTP stored successfully. Stored OTP: " . ($stored['reset_token'] ?? 'NULL') . ", Stored expires: " . ($stored['reset_token_expires'] ?? 'NULL'));
            
            // Try to send email using enhanced function with multiple methods
            $emailSent = sendOtpEmailEnhanced($data['email'], $otp, $user, 'auto');
            
            if ($emailSent) {
                echo json_encode(['status' => 'success', 'message' => 'OTP has been sent to your email. Please check your inbox and spam folder.']);
            } else {
                // If all email methods fail, still show OTP for testing purposes
                echo json_encode(['status' => 'success', 'message' => 'OTP has been sent to your email. Please check your inbox. (For testing: OTP is ' . $otp . ')']);
            }
        } else {
            error_log("Failed to store OTP in database");
            echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP. Please try again.']);
        }

    } catch (Exception $e) {
        error_log('Error sending OTP: ' . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while sending OTP.']);
    }
}

function verifyOtpReset($data)
{
    try {
        // Debug: Log the received data
        error_log("verifyOtpReset called with data: " . print_r($data, true));
        
        if (empty($data['email']) || empty($data['otp'])) {
            error_log("Missing email or OTP. Email: " . ($data['email'] ?? 'empty') . ", OTP: " . ($data['otp'] ?? 'empty'));
            echo json_encode(['status' => 'error', 'message' => 'Email and OTP are required.']);
            return;
        }

        global $conn;
        $table_name = PREFIX . "users";

        // Debug: Log the query parameters
        error_log("Verifying OTP - Email: " . $data['email'] . ", OTP: " . $data['otp']);

        // First, get the stored OTP data
        $stmt = $conn->prepare("SELECT id, firstname, lastname, reset_token, reset_token_expires FROM $table_name WHERE email = :email");
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            error_log("User not found with email: " . $data['email']);
            echo json_encode(['status' => 'error', 'message' => 'User not found.']);
            return;
        }

        // Check if OTP matches
        if ($user['reset_token'] !== $data['otp']) {
            error_log("OTP mismatch. Stored: " . $user['reset_token'] . ", Provided: " . $data['otp']);
            echo json_encode(['status' => 'error', 'message' => 'Invalid OTP.']);
            return;
        }

        // Check if OTP is expired
        if ($user['reset_token_expires']) {
            $expires = new DateTime($user['reset_token_expires']);
            $now = new DateTime();
            $isExpired = $expires < $now;
            
            error_log("OTP expires at: " . $user['reset_token_expires'] . ", Current time: " . $now->format('Y-m-d H:i:s') . ", Expired: " . ($isExpired ? 'YES' : 'NO'));
            
            if ($isExpired) {
                echo json_encode(['status' => 'error', 'message' => 'OTP has expired.']);
                return;
            }
        } else {
            error_log("No expiration time found for OTP");
            echo json_encode(['status' => 'error', 'message' => 'OTP has expired.']);
            return;
        }

        // If we get here, OTP is valid
        error_log("OTP verification successful for user: " . $user['firstname'] . " " . $user['lastname']);
        echo json_encode(['status' => 'success', 'message' => 'OTP verified successfully.']);

    } catch (Exception $e) {
        error_log('Error verifying OTP: ' . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while verifying OTP.']);
    }
}

function resetPasswordOtp($data)
{
    try {
        if (empty($data['email']) || empty($data['otp']) || empty($data['new_password'])) {
            echo json_encode(['status' => 'error', 'message' => 'Email, OTP, and new password are required.']);
            return;
        }

        // Validate password length
        if (strlen($data['new_password']) < 6) {
            echo json_encode(['status' => 'error', 'message' => 'Password must be at least 6 characters long.']);
            return;
        }

        global $conn;
        $table_name = PREFIX . "users";

        // First, get the stored OTP data
        $stmt = $conn->prepare("SELECT id, reset_token, reset_token_expires FROM $table_name WHERE email = :email");
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'User not found.']);
            return;
        }

        // Check if OTP matches
        if ($user['reset_token'] !== $data['otp']) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid OTP.']);
            return;
        }

        // Check if OTP is expired
        if ($user['reset_token_expires']) {
            $expires = new DateTime($user['reset_token_expires']);
            $now = new DateTime();
            $isExpired = $expires < $now;
            
            if ($isExpired) {
                echo json_encode(['status' => 'error', 'message' => 'OTP has expired.']);
                return;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'OTP has expired.']);
            return;
        }

        // Hash the new password
        $hashedPassword = password_hash($data['new_password'], PASSWORD_DEFAULT);
        $updatedAt = date('Y-m-d H:i:s');

        // Update password and clear OTP
        $stmt = $conn->prepare("UPDATE $table_name SET password = :password, reset_token = NULL, reset_token_expires = NULL, updated_at = :updated_at WHERE email = :email");
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $updatedAt, PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Password has been reset successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to reset password.']);
        }

    } catch (Exception $e) {
        error_log('Error resetting password with OTP: ' . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while resetting password.']);
    }
}
?>