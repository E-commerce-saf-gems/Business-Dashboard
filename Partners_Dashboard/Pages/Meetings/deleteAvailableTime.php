<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../Login/login.html");
    exit;
}

include('../../../database/db.php');

$salesRep_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get the date and time to delete
    $date = $_GET['date'];
    $time = $_GET['time'];

    // Delete the time slot
    $deleteSql = "DELETE FROM availabletimes WHERE salesRep_id = ? AND date = ? AND time = ?";
    if ($stmt = $conn->prepare($deleteSql)) {
        $stmt->bind_param("iss", $salesRep_id, $date, $time);
        if ($stmt->execute()) {
            // Success, redirect to meeting page with success message
            header("Location: ./meeting.php?success=1");
        } else {
            // Error during execution
            header("Location: ./meeting.php?error=1");
        }
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
