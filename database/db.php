<?php
// Database configuration
$servername = "localhost"; // Your server, usually localhost
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "safgems"; // Replace with the actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connection successful!";
}
