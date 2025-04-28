<?php
include_once('connection.php');
include_once('config.php');

//crete user
function registerUser($params)
{
    try {
       
        global $conn;
        $table_name = PREFIX . "users";
        $stmt = $conn->prepare("INSERT INTO $table_name (firstname, lastname, email, password, created_at, updated_at) 
        VALUES (:firstname, :lastname, :email, :password, :created_at, :updated_at)");

        $stmt->bindParam(':firstname', $params['firstname'], PDO::PARAM_STR);
        $stmt->bindParam(':lastname', $params['lastname'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $params['email'], PDO::PARAM_STR);
        $stmt->bindParam(':password', $params['password'], PDO::PARAM_STR);
        $stmt->bindParam(':created_at', $params['created_at'], PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $params['updated_at'], PDO::PARAM_STR);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("An error occurred: " . $e->getMessage());
        return "An error occurred: " . $e->getMessage();
    }
}

