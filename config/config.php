<?php
$hostname = 'localhost';
$username = 'root';
$password = 'root';
$dbname = 'local';

$conn = new mysqli($hostname, $username, $password, $dbname);

if ($conn) {
    echo "successfully connected";
} else {
    echo "failed to connect" . mysqli_error($conn);
}
?>