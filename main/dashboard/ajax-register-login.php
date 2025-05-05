<?php
include_once(__DIR__ . '/../../config/config.php');
include_once(__DIR__ . '/../../config/functions.php');

header('Content-Type: application/json');

// Use $_POST to retrieve the data
$data = $_POST;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($data['action'])) {
    try {
        switch ($data['action']) {
            case 'user_register':
                userRegistration($data);
                break;
            case 'user_login':
                userLogin($data);
                break;
            case 'accept_invite':
                ajaxUpdateProjectMeta($data);
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
                echo json_encode(['status' => 'success', 'message' => 'Successfully Inrolled to the Project.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to Inrolled to the Project.']);
            }

        }

    } catch (Exception $e) {
        error_log('Error processing request: ' . $e->getMessage());
        echo json_encode(['error' => $e->getMessage()]);
    }
}