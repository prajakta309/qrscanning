<?php
// Database configuration
$host = getenv('MYSQL_HOST') ?: 'mysql';
$user = getenv('MYSQL_USER') ?: 'root';
$pass = getenv('MYSQL_PASSWORD') ?: 'root';
$dbname = getenv('MYSQL_DATABASE') ?: 'library';

// Create connection with error handling and retry logic
$maxRetries = 5;
$retryDelay = 5; // seconds

for ($i = 0; $i < $maxRetries; $i++) {
    try {
        $conn = new mysqli($host, $user, $pass, $dbname);
        
        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Set charset to utf8mb4
        $conn->set_charset("utf8mb4");
        
        // If we get here, connection is successful
        break;
    } catch (Exception $e) {
        if ($i === $maxRetries - 1) {
            // Last retry failed
            die("Database connection failed after {$maxRetries} attempts: " . $e->getMessage());
        }
        // Wait before retrying
        sleep($retryDelay);
    }
}
?> 