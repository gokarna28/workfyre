<?php
include_once(__DIR__ . '/../../config/config.php');
include_once(__DIR__ . '/../../config/functions.php');

header('Content-Type: application/json');

// Use $_POST to retrieve the data
$data = $_POST;
$files = $_FILES;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($data['action'])) {
    try {
        switch ($data['action']) {
            case 'create_project':
                ajaxCreateProject($data, $files);
                break;
            case 'delete_project_attachment':
                ajaxDeleteProjectAttachment($data);
                break;
            case 'invite_team':
                ajaxInviteTeam($data);
                break;
            case 'create_task':
                ajaxCreateTask($data, $files);
                break;

        }
    } catch (Exception $e) {
        error_log('Error processing request: ' . $e->getMessage());
        echo json_encode(['error' => $e->getMessage()]);
    }
}


//create project
function ajaxCreateProject($params, $files)
{
    try {
        if (empty($params['project_title']) || empty($params['project_priority']) || empty($params['project_description'])) {
            echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
            return;
        }

        $createdAt = $updatedAt = strtolower(date('F-d-Y'));
        $params['created_at'] = $createdAt;
        $params['updated_at'] = $updatedAt;

        //insert to database 
        $result = createProject($params);

        if ($result['status'] == 'success') {
            echo json_encode(['status' => 'success', 'message' => 'Project Created Successfully.']);

            //upload the attachments
            if (isset($files['project_attachments']) && is_array($files['project_attachments']['name'])) {
                $base_dir = '/assets/uploads/';
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . $base_dir;

                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $uploadedFiles = [];

                foreach ($files['project_attachments']['name'] as $index => $name) {
                    $tmpName = $files['project_attachments']['tmp_name'][$index];
                    $error = $files['project_attachments']['error'][$index];

                    if ($error === UPLOAD_ERR_OK) {
                        // Use current date-time instead of time()
                        $datePrefix = date('Ymd_His');
                        $newFileName = $datePrefix . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($name));
                        $targetPath = $uploadDir . $newFileName;

                        if (move_uploaded_file($tmpName, $targetPath)) {
                            // Relative path to store in the database
                            $uploadedFile = $base_dir . $newFileName;
                            $data = [
                                'project_id' => $result['project_id'],
                                'created_at' => $createdAt,
                                'updated_at' => $updatedAt,
                                'attachment' => $uploadedFile
                            ];

                            //save to the attachments table
                            saveProjectAttachments($data);

                        } else {
                            echo json_encode(['status' => 'error', 'message' => 'Failed to move file: ' . $name]);
                            return;
                        }
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Upload error on file: ' . $name]);
                        return;
                    }
                }


            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to create the project.']);
        }

    } catch (Exception $e) {
        error_log('Error processing request: ' . $e->getMessage());
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function ajaxDeleteProjectAttachment($params)
{
    try {
        if (isset($params['attachmentId'])) {

            $result = deleteProjectAttachment($params['attachmentId']);
            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'File Deleted Successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to Delete.']);
            }
        }
    } catch (Exception $e) {
        error_log('Error processing request: ' . $e->getMessage());
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function ajaxInviteTeam($params)
{
    try {
        if ($params) {
            $created_at = $updated_at = (date('F-d-Y'));
            foreach ($params['user_ids'] as $user_id) {

                $result = insertDataProjectMeta($params['project_id'], $user_id, $created_at, $updated_at);

                if ($result['status'] == 'success') {
                    $users = getUsersDetailsByUser_id($user_id);
                    $project = getProjectDetailsByProjectID($params['project_id']);
                    // $projectMeta = getProjectMeta($params['project_id']);
                    $projectTeamAdded = getProjectTeamByPm_id($result['inserted_id']);

                    /**send mail to the invited user */
                    $to = $users['email'];
                    $subject = "WORKFYRE - Project Invitation";

                    // Replace with actual project details and user ID/token
                    $projectName = ucfirst($project['title']);
                    $projectId = $project['id'];
                    $invite_id = $result['inserted_id'];
                    // $userId = $users['id'];
                    $acceptLink = HOMEPAGE_URL . "/main/accept-invite.php?invite_id=$invite_id&project_id=$projectId";

                    $message = "
                    <html>
                    <head>
                      <title>Project Invitation</title>
                    </head>
                    <body>
                      <p>Hello!</p>
                      <p>You have been invited to join the project <strong>$projectName</strong> on Workfyre.</p>
                      <p>Click the button below to accept the invitation:</p>
                      <a href='$acceptLink' style='display:inline-block;padding:10px 20px;background-color:#28a745;color:white;text-decoration:none;border-radius:5px;'>Accept</a>
                      <p>If the button doesn't work, copy and paste this link into your browser:<br>$acceptLink</p>
                      <br>
                      <p>Thank you,<br>Workfyre Team</p>
                    </body>
                    </html>
                    ";

                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= "From: noreply@workfyre.com.np";

                    if (mail($to, $subject, $message, $headers)) {
                        echo json_encode(['status' => 'success', 'message' => 'A Invitation mail is sent to the users.', 'project_meta' => $projectTeamAdded]);

                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Failed to send invitation mail to the users.']);

                    }

                } else {
                    echo json_encode(['status' => 'error', 'message' => $result['message']]);
                }
            }


        }
    } catch (Exception $e) {
        error_log('Error processing request: ' . $e->getMessage());
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function ajaxCreateTask($params, $files)
{
    try {
        if (empty($params['task_title']) || empty($params['task_priority']) || empty($params['task_description'])) {
            echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
            return;
        }

        $createdAt = $updatedAt = strtolower(date('F-d-Y'));
        $params['created_at'] = $createdAt;
        $params['updated_at'] = $updatedAt;

        //insert to database 
        $result = createTask($params);
        var_dump($result);
        exit;

        if ($result['status'] == 'success') {
            echo json_encode(['status' => 'success', 'message' => 'Project Created Successfully.']);

            //upload the attachments
            if (isset($files['project_attachments']) && is_array($files['project_attachments']['name'])) {
                $base_dir = '/assets/uploads/';
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . $base_dir;

                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $uploadedFiles = [];

                foreach ($files['project_attachments']['name'] as $index => $name) {
                    $tmpName = $files['project_attachments']['tmp_name'][$index];
                    $error = $files['project_attachments']['error'][$index];

                    if ($error === UPLOAD_ERR_OK) {
                        // Use current date-time instead of time()
                        $datePrefix = date('Ymd_His');
                        $newFileName = $datePrefix . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($name));
                        $targetPath = $uploadDir . $newFileName;

                        if (move_uploaded_file($tmpName, $targetPath)) {
                            // Relative path to store in the database
                            $uploadedFile = $base_dir . $newFileName;
                            $data = [
                                'project_id' => $result['project_id'],
                                'created_at' => $createdAt,
                                'updated_at' => $updatedAt,
                                'attachment' => $uploadedFile
                            ];

                            //save to the attachments table
                            saveProjectAttachments($data);

                        } else {
                            echo json_encode(['status' => 'error', 'message' => 'Failed to move file: ' . $name]);
                            return;
                        }
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Upload error on file: ' . $name]);
                        return;
                    }
                }


            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to create the project.']);
        }

    } catch (Exception $e) {
        error_log('Error processing request: ' . $e->getMessage());
        echo json_encode(['error' => $e->getMessage()]);
    }
}