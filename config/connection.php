<?php
$hostname = 'localhost';
$username = 'root';
$password = 'root';
$dbname = 'local';
$port = 10047; 

try {
    // Use the variables, including the port, in the DSN string
    $conn = new PDO("mysql:host=$hostname;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Set the PDO error mode to Exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // echo "Successfully connected.";
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage(); // <-- show real error
}
?>
