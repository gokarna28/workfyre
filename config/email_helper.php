<?php
/**
 * Email Helper Functions
 * Provides multiple methods for sending emails
 */

// Method 1: Using PHP's built-in mail() function
function sendEmailBasic($to, $subject, $message, $headers = '')
{
    return mail($to, $subject, $message, $headers);
}

// Method 2: Using cURL to send via external SMTP service (like Mailgun, SendGrid, etc.)
function sendEmailCurl($to, $subject, $message, $from = 'noreply@workfyre.com.np')
{
    // This is a placeholder for external SMTP service
    // You would need to configure with your SMTP provider
    $url = 'https://api.mailgun.net/v3/your-domain.com/messages';
    $apiKey = 'your-api-key';
    
    $data = [
        'from' => $from,
        'to' => $to,
        'subject' => $subject,
        'html' => $message
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "api:$apiKey");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $httpCode === 200;
}

// Method 3: Using file-based email logging (for development/testing)
function sendEmailLog($to, $subject, $message, $headers = '')
{
    $logDir = __DIR__ . '/../../logs/emails/';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $logFile = $logDir . date('Y-m-d') . '_emails.log';
    $timestamp = date('Y-m-d H:i:s');
    
    $logEntry = "[$timestamp] To: $to | Subject: $subject\n";
    $logEntry .= "Headers: $headers\n";
    $logEntry .= "Message: $message\n";
    $logEntry .= str_repeat('-', 80) . "\n";
    
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    
    return true;
}

// Method 4: Using local SMTP (if available)
function sendEmailLocalSMTP($to, $subject, $message, $from = 'noreply@workfyre.com.np')
{
    // Configure local SMTP settings
    ini_set('SMTP', 'localhost');
    ini_set('smtp_port', '25');
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: $from" . "\r\n";
    $headers .= "Reply-To: $from" . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    return mail($to, $subject, $message, $headers);
}

// Main email sending function that tries multiple methods
function sendEmail($to, $subject, $message, $method = 'auto')
{
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: noreply@workfyre.com.np" . "\r\n";
    $headers .= "Reply-To: noreply@workfyre.com.np" . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    switch ($method) {
        case 'basic':
            return sendEmailBasic($to, $subject, $message, $headers);
            
        case 'curl':
            return sendEmailCurl($to, $subject, $message);
            
        case 'log':
            return sendEmailLog($to, $subject, $message, $headers);
            
        case 'smtp':
            return sendEmailLocalSMTP($to, $subject, $message);
            
        case 'auto':
        default:
            // Try multiple methods in order of preference
            $methods = ['basic', 'smtp', 'log'];
            
            foreach ($methods as $method) {
                $result = false;
                
                switch ($method) {
                    case 'basic':
                        $result = sendEmailBasic($to, $subject, $message, $headers);
                        break;
                    case 'smtp':
                        $result = sendEmailLocalSMTP($to, $subject, $message);
                        break;
                    case 'log':
                        $result = sendEmailLog($to, $subject, $message, $headers);
                        break;
                }
                
                if ($result) {
                    error_log("Email sent successfully using method: $method");
                    return true;
                }
            }
            
            error_log("All email methods failed for: $to");
            return false;
    }
}

// Enhanced OTP email function
function sendOtpEmailEnhanced($email, $otp, $user, $method = 'auto')
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
    
    return sendEmail($email, $subject, $message, $method);
}
?> 