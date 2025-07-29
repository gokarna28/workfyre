<?php
include_once(__DIR__ . '/../config/config.php');
include_once(__DIR__ . '/../config/connection.php');

try {
    global $conn;
    $table_name = PREFIX . "users";
    
    // Check if the table exists
    $stmt = $conn->prepare("SHOW TABLES LIKE '$table_name'");
    $stmt->execute();
    $tableExists = $stmt->fetch();
    
    echo "<h2>Database Check Results:</h2>";
    echo "<p><strong>Table '$table_name' exists:</strong> " . ($tableExists ? 'Yes' : 'No') . "</p>";
    
    if ($tableExists) {
        // Check if reset_token column exists
        $stmt = $conn->prepare("SHOW COLUMNS FROM $table_name LIKE 'reset_token'");
        $stmt->execute();
        $resetTokenExists = $stmt->fetch();
        
        // Check if reset_token_expires column exists
        $stmt = $conn->prepare("SHOW COLUMNS FROM $table_name LIKE 'reset_token_expires'");
        $stmt->execute();
        $resetTokenExpiresExists = $stmt->fetch();
        
        echo "<p><strong>reset_token column exists:</strong> " . ($resetTokenExists ? 'Yes' : 'No') . "</p>";
        echo "<p><strong>reset_token_expires column exists:</strong> " . ($resetTokenExpiresExists ? 'Yes' : 'No') . "</p>";
        
        if (!$resetTokenExists || !$resetTokenExpiresExists) {
            echo "<h3>Missing columns detected!</h3>";
            echo "<p>You need to run the following SQL to add the missing columns:</p>";
            echo "<pre>";
            echo "ALTER TABLE `$table_name` \n";
            echo "ADD COLUMN `reset_token` VARCHAR(255) NULL,\n";
            echo "ADD COLUMN `reset_token_expires` DATETIME NULL;\n\n";
            echo "CREATE INDEX `idx_reset_token` ON `$table_name` (`reset_token`);\n";
            echo "CREATE INDEX `idx_reset_token_expires` ON `$table_name` (`reset_token_expires`);";
            echo "</pre>";
        } else {
            echo "<p><strong>All required columns exist!</strong></p>";
            
            // Check if there are any existing reset tokens
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM $table_name WHERE reset_token IS NOT NULL");
            $stmt->execute();
            $result = $stmt->fetch();
            echo "<p><strong>Existing reset tokens:</strong> " . $result['count'] . "</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
}
?> 