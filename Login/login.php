<?php
session_start(); // Start session to store user information

// Include database connection
include_once '../database/db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize input to prevent SQL Injection
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to get the user data
    $sql = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // User exists
        $user = mysqli_fetch_assoc($result);

        // Check password (without hashing or encryption)
        if ($password == $user['password']) {
            // Password is correct, set session variables
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Assuming a 'role' column exists in your table

            // Redirect user to their dashboard based on their role
            if ($user['role'] == 'Partner') {
                header("Location: ../Partners_Dashboard/dashboard.html");
            } elseif ($user['role'] == 'admin') {
                header("Location: ../Admin_Dashboard/dashboard.html");
            } elseif ($user['role'] == 'salesrep') {
                header("Location: ../Salesrep_Dashboard/dashboard.html");
            } elseif ($user['role'] == 'accountant') {
                header("Location: ../Accountant_Dashboard/dashboard.html");
            } else {
                $_SESSION['error'] = "Unknown role!";
                header("Location: ../login.html");
            }
            exit();
        } else {
            // Password is incorrect
            $_SESSION['error'] = "Incorrect username or password!";
            header("Location: ./login-form.php");
            exit();
        }
    } else {
        // No user found
        $_SESSION['error'] = "No user found with that username!";
        header("Location: ./login-form.php");
        exit();
    }
}
?>
