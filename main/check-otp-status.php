<?php
include_once(__DIR__ . '/../config/config.php');
include_once(__DIR__ . '/../config/connection.php');

echo "<h1>Current OTP Status</h1>";

$email = "damudar@gmail.com";

try {
    global $conn;
    $table_name = PREFIX . "users";
    
    // Get current OTP data
    $stmt = $conn->prepare("SELECT reset_token, reset_token_expires FROM $table_name WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $otpData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($otpData) {
        echo "<h2>Current OTP Data:</h2>";
        echo "Email: " . $email . "<br>";
        echo "Stored OTP: " . ($otpData['reset_token'] ?? 'NULL') . "<br>";
        echo "Expires at: " . ($otpData['reset_token_expires'] ?? 'NULL') . "<br>";
        
        if ($otpData['reset_token_expires']) {
            $expires = new DateTime($otpData['reset_token_expires']);
            $now = new DateTime();
            $diff = $now->diff($expires);
            $isExpired = $expires < $now;
            
            echo "Current time: " . $now->format('Y-m-d H:i:s') . "<br>";
            echo "Time until expiry: " . $diff->format('%H:%I:%S') . "<br>";
            echo "Is expired: " . ($isExpired ? 'YES' : 'NO') . "<br>";
        }
    } else {
        echo "No OTP data found for this email.<br>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?> 