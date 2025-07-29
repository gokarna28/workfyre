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
            case 'delete_task_attachment':
                ajaxDeleteTaskAttachment($data);
                break;
            case 'invite_team':
                ajaxInviteTeam($data);
                break;
            case 'create_task':
                ajaxCreateTask($data, $files);
                break;
            case 'update_task_status':
                ajaxUpdateTaskStatus($data);
                break;
            case 'check_task_dependencies':
                ajaxCheckTaskDependencies($data);
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

function ajaxDeleteTaskAttachment($params)
{
    try {
        if (isset($params['attachmentId'])) {

            $result = deleteTaskAttachment($params['attachmentId']);
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
        if (empty($params['task_title']) || empty($params['task_description'])) {
            echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
            return;
        }

        $createdAt = $updatedAt = strtolower(date('F-d-Y'));
        $params['created_at'] = $createdAt;
        $params['updated_at'] = $updatedAt;

        //insert to database 
        $result = createTask($params);

        if ($result['status'] == 'success') {

            $assignUser = getUsersDetailsByUser_id($params['task_assignto']);
            
            // Get the created task to check if it's in critical path
            $createdTask = getTasksDetailsByTask_id($result['task_id']);
            
            // Automatically assign priority based on critical path
            $autoPriority = 'low'; // Default priority
            if ($createdTask && isset($createdTask['critical']) && $createdTask['critical'] == 1) {
                $autoPriority = 'high'; // High priority for critical path tasks
            }
            
            $classes = getClasses($autoPriority);
            
            //data to send in the response 
            $response = [
                'task_id' => $result['task_id'],
                'project_id' => $params['project_id'],
                'title' => $params['task_title'],
                'priority' => $autoPriority,
                'description' => strlen($params['task_description']) > 20 ? substr($params['task_description'], 0, 40) . '...' : $params['task_description'],
                'deadline' => $params['task_deadline'],
                'created_at' => $createdAt,
                'assignto_user' => $assignUser['firstname'] . ' ' . $assignUser['lastname'],
                'priority_class' => $classes,
            ];


            $task_dependencies = json_decode($params['task_dependencies'], true);

            if (isset($task_dependencies) && is_array($task_dependencies)) {
                foreach ($task_dependencies as $dependency) {
                    $dependencyParams = [
                        'task_id' => $result['task_id'],
                        'dependency_task_id' => $dependency,
                        'created_at' => $createdAt,
                        'updated_at' => $updatedAt
                    ];

                    updateTaskDependencies($dependencyParams);
                }
            }


            //upload the attachments
            if (isset($files['task_attachments']) && is_array($files['task_attachments']['name'])) {
                $base_dir = '/assets/uploads/';
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . $base_dir;

                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                // $uploadedFiles = [];

                foreach ($files['task_attachments']['name'] as $index => $name) {
                    $tmpName = $files['task_attachments']['tmp_name'][$index];
                    $error = $files['task_attachments']['error'][$index];

                    if ($error === UPLOAD_ERR_OK) {
                        // Use current date-time instead of time()
                        $datePrefix = date('Ymd_His');
                        $newFileName = $datePrefix . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($name));
                        $targetPath = $uploadDir . $newFileName;

                        if (move_uploaded_file($tmpName, $targetPath)) {
                            // Relative path to store in the database
                            $uploadedFile = $base_dir . $newFileName;
                            $data = [
                                'task_id' => $result['task_id'],
                                'created_at' => $createdAt,
                                'updated_at' => $updatedAt,
                                'attachment' => $uploadedFile
                            ];

                            //save to the attachments table
                            saveTaskAttachments($data);

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

            /**update the critical path params to the task table */
            $tasks = getTasksDetailsByProject_id($params['project_id']);

            $taskCriticalDetails = [];

            foreach ($tasks as $task) {
                $taskDependencies = getTaskDependencies($task['id']);
                $predecessorIds = array_column($taskDependencies, 'dependency_task_id');

                //convert deadline in days
                $deadline = $task['deadline'];
                $today = new DateTime(); // today
                $endDate = new DateTime($deadline);

                $interval = $today->diff($endDate);
                $durationInDays = (int) $interval->format('%r%a');

                $taskCriticalDetails[] = [
                    'id' => $task['id'],
                    'duration' => $durationInDays,
                    'predecessors' => $predecessorIds
                ];

                
            }

            //get the params for critial path
            $allCriticalParams = calculateCriticalPath($taskCriticalDetails);
            //update the task critical params
            foreach ($allCriticalParams as $criticalParams) {
                updateTaskCriticalPathParams($criticalParams);
            }

            // Update priority based on critical path analysis
            $updatedTask = getTasksDetailsByTask_id($result['task_id']);
            if ($updatedTask && isset($updatedTask['critical']) && $updatedTask['critical'] == 1) {
                // Update task priority to high if it's in critical path
                updateTaskPriority($result['task_id'], 'high');
                $response['priority'] = 'high';
                $response['priority_class'] = getClasses('high');
                $response['critical'] = 1; // Add critical status
            } else {
                // Update task priority to low if it's not in critical path
                updateTaskPriority($result['task_id'], 'low');
                $response['priority'] = 'low';
                $response['priority_class'] = getClasses('low');
                $response['critical'] = 0; // Add critical status
            }

            /**ends */

            echo json_encode(['status' => 'success', 'message' => 'Task Created Successfully.', 'task_card_details' => $response]);

        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to create the task.']);
        }

    } catch (Exception $e) {
        error_log('Error processing request: ' . $e->getMessage());
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function ajaxUpdateTaskStatus($params)
{
    try {
        if (isset($params)) {
            // Check if user has permission to modify this task
            if (!canUserModifyTask($params['task_id'])) {
                echo json_encode(['status' => 'error', 'message' => 'You do not have permission to modify this task. Only the assigned user or admin can change task status.']);
                return;
            }
            
            $result = updateTaskStatus($params);
            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Task Status Updated to' . ' ' . $params['task_status'] . ' ' . 'Successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update task status.']);
            }
        }
    } catch (Exception $e) {
        error_log('Error processing request: ' . $e->getMessage());
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function ajaxCheckTaskDependencies($params)
{
    try {
        if (empty($params['task_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Task ID is required']);
            return;
        }

        $taskId = $params['task_id'];
        
        // Get task dependencies
        $dependencies = getTaskDependencies($taskId);
        
        if (empty($dependencies)) {
            // No dependencies, so it's safe to move
            echo json_encode(['status' => 'success', 'dependencies_completed' => true]);
            return;
        }
        
        // Check if all dependencies are completed
        $allCompleted = true;
        foreach ($dependencies as $dependency) {
            $dependencyTask = getTasksDetailsByTask_id($dependency['dependency_task_id']);
            if ($dependencyTask && $dependencyTask['status'] !== 'completed') {
                $allCompleted = false;
                break;
            }
        }
        
        echo json_encode([
            'status' => 'success', 
            'dependencies_completed' => $allCompleted,
            'dependency_count' => count($dependencies),
            'completed_count' => $allCompleted ? count($dependencies) : 0
        ]);

    } catch (Exception $e) {
        error_log('Error checking task dependencies: ' . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Failed to check dependencies']);
    }
}