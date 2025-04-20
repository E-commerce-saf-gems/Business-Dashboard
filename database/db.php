<?php
<<<<<<< HEAD
// Database connection details
=======
>>>>>>> Dev
$servername = "localhost";
$username = "root";
$password = "";
$database = "safgems";

<<<<<<< HEAD
// Create the connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
=======
// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
>>>>>>> Dev
