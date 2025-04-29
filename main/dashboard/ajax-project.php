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

        }
    } catch (Exception $e) {
        error_log('Error processing request: ' . $e->getMessage());
        echo json_encode(['error' => $e->getMessage()]);
    }
}


//create project
function ajaxCreateProject($params, $files)
{

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

            // All files uploaded successfully
            // echo json_encode(['status' => 'success', 'files' => $uploadedFiles]);
            // return;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create the project.']);
    }

}