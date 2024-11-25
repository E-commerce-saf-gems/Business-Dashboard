<?php
session_start();
include '../../../database/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../Login/login-form.php");
    exit;
}

if (!isset($_GET['date']) || !isset($_GET['time'])) {
    header("Location: ./meeting.php?error=InvalidRequest");
    exit;
}

$salesRep_id = $_SESSION['user_id'];
$date = $_GET['date'];
$time = $_GET['time'];

// Fetch the availability status of the time slot
$sql_check = "SELECT availability 
              FROM availabletimes 
              WHERE salesRep_id = ? AND date = ? AND time = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("iss", $salesRep_id, $date, $time);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // No matching time slot found
    $stmt->close();
    $conn->close();
    header("Location: ./meeting.php?error=NotFound");
    exit;
}

$row = $result->fetch_assoc();
$availability = $row['availability'];

$stmt->close();

if ($availability !== 'available') {
    // Only allow deletion if the availability status is Pending
    $conn->close();
    header("Location: ./meeting.php?error=NotDeletable");
    exit;
}

// Proceed to delete the time slot
$sql_delete = "DELETE FROM availabletimes 
               WHERE salesRep_id = ? AND date = ? AND time = ?";
$stmt = $conn->prepare($sql_delete);
$stmt->bind_param("iss", $salesRep_id, $date, $time);
if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: ./meeting.php?success=1");
    exit;
} else {
    $stmt->close();
    $conn->close();
    header("Location: ./meeting.php?error=DeleteFailed");
    exit;
}
?>
