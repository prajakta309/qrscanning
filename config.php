<?php
// Database configuration
$host = 'localhost';  // Use the service name from docker-compose.yml
$user = 'root';
$pass = 'root';   // Match the MYSQL_ROOT_PASSWORD in docker-compose.yml
$dbname = 'library';

// Create connection with error handling
try {
    $conn = new mysqli($host, $user, $pass, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Set charset to utf8mb4
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}
?> 