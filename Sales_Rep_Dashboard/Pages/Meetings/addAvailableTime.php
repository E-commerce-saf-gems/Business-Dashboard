<?php
session_start(); // Start the session to access session variables

// Check if the user is logged in and if the user_id exists in the session
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to login page
    header("Location: login.php");
    exit;
}

// Include database connection
include('../../../database/db.php');

// Get the Sales Rep ID from the session (user_id is used as salesRep_id)
$salesRep_id = $_SESSION['user_id'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the date and time from the form
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Prepare the SQL query to insert the available time into the database
    $sql = "INSERT INTO availabletimes (salesRep_id, date, time) VALUES (?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iss", $salesRep_id, $date, $time);

        if ($stmt->execute()) {
            // Redirect with success message
            header("Location: ./meeting.php?success=1");
        } else {
            // Handle error
            header("Location: ./meeting.php?error=1");
        }

        $stmt->close();
    } else {
        // If the SQL preparation fails
        echo "Error: " . $conn->error;
    }

    $conn->close(); // Close the database connection
}
?>
