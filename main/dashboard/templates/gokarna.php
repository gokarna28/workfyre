// Initialize FormData object to send form data and files
        // const formData = new FormData();
        // formData.append('project_title', $('#project_title').val());  // Append the project title
        // formData.append('project_priority', $('#project_priority').val());  // Append project priority
        // formData.append('project_description', $('#project_description').val());  // Append project description
        // formData.append('action', 'create_project');  // Append action type



        $to = $users['email'];
                    $subject = "WORKFYRE - Project Invitation";
                    
                    // Replace with actual project details and user ID/token
                    $projectName = ucfirst($project['title']);
                    $projectId = 123; // Example
                    $userId = $users['id']; // Assuming you have user ID
                    // $acceptLink = "https://workfyre.com.np/accept-invite.php?project_id=$projectId&user_id=$userId";
                    
                    $message = "
                    <html>
                    <head>
                      <title>Project Invitation</title>
                    </head>
                    <body>
                      <p>Hello!</p>
                      <p>You have been invited to join the project <strong>$projectName</strong> on Workfyre.</p>
                      <p>Click the button below to accept the invitation:</p>
                      <a href='' style='display:inline-block;padding:10px 20px;background-color:#28a745;color:white;text-decoration:none;border-radius:5px;'>Accept</a>
                      <p>If the button doesn't work, copy and paste this link into your browser:<br></p>
                      <br>
                      <p>Thank you,<br>Workfyre Team</p>
                    </body>
                    </html>
                    ";
                    
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= "From: noreply@workfyre.com.np";
                    