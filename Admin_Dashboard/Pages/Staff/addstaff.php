<?php

include '../../../database/db.php';

$date = $username = $password = $role = $name = $email = $phonenumber ="";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $role = htmlspecialchars(trim($_POST['role']));
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $contactNo = htmlspecialchars(trim($_POST['contactNo']));


    $sql = "INSERT INTO user (username, password, role, name, email, contactNo) 
    VALUES ('$username', '$password', '$role', '$name', '$email', '$contactNo')";     

// Execute inventory query
if ($conn->query($sql) === TRUE) {
// Get the last inserted inventory ID
    $user_id = $conn->insert_id;

    // Redirect to inventory.php upon success
    header("Location: ../../../Admin_Dashboard/Pages/Staff/Staff.php");
    exit(); // Ensure no further code is executed after redirection
} else {
    // Print error if the purchases SQL query fails
    $errorMessage = "Error adding purchase record: " . $conn->error;
}
}


// Close the database connection
$conn->close();
?>


