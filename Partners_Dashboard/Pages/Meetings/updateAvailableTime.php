<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../Login/login.html");
    exit;
}

include('../../../database/db.php');

$salesRep_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the new date, time, and the current date/time to update
    $old_date = $_POST['old_date'];
    $old_time = $_POST['old_time'];
    $new_date = $_POST['new_date'];
    $new_time = $_POST['new_time'];

    // Check if the new date and time already exist for this sales rep
    $checkSql = "SELECT COUNT(*) as count FROM availabletimes WHERE salesRep_id = ? AND date = ? AND time = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("iss", $salesRep_id, $new_date, $new_time);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        // If the date and time already exist, show error
        header("Location: ./meeting.php?error=duplicate");
        exit;
    }

    // Update the time slot
    $updateSql = "UPDATE availabletimes SET date = ?, time = ? WHERE salesRep_id = ? AND date = ? AND time = ?";
    if ($stmt = $conn->prepare($updateSql)) {
        $stmt->bind_param("ssiss", $new_date, $new_time, $salesRep_id, $old_date, $old_time);
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
