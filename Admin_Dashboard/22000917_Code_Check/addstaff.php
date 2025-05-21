<?php

include '../../../database/db.php';

$date = $username = $password = $role = $name = $email = $phonenumber = $nic ="";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $role = htmlspecialchars(trim($_POST['role']));
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $contactNo = htmlspecialchars(trim($_POST['contactNo']));
    $nic = htmlspecialchars(trim($_POST['nic']));



    $sql = "INSERT INTO user (username, password, role, name, email, contactNo,nic) 
    VALUES ('$username', '$password', '$role', '$name', '$email', '$contactNo','$nic')";     

if ($conn->query($sql) === TRUE) {
    $user_id = $conn->insert_id;

    header("Location: ../../../Admin_Dashboard/Pages/Staff/Staff.php");
    exit();
} else {
    $errorMessage = "Error adding purchase record: " . $conn->error;
}
}


$conn->close();
?>


