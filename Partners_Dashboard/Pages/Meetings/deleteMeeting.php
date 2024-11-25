<?php
session_start();
include '../../../database/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../Login/login-form.php");
    exit;
}

// Check if meeting_id is provided
if (!isset($_POST['meeting_id'])) {
    header("Location: ./meeting.php?error=InvalidRequest");
    exit;
}

$salesRep_id = $_SESSION['user_id'];
$meeting_id = $_POST['meeting_id'];

// Verify that the meeting exists and has the status "Request To Delete"
$sql_check = "SELECT m.status, m.availableTimes_id 
              FROM meeting AS m
              JOIN availabletimes AS a ON m.availableTimes_id = a.availableTimes_id
              WHERE m.meeting_id = ? AND a.salesRep_id = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("ii", $meeting_id, $salesRep_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // No matching meeting found or not authorized
    $stmt->close();
    $conn->close();
    header("Location: ./meeting.php?error=NotFoundOrUnauthorized");
    exit;
}

$row = $result->fetch_assoc();
$status = $row['status'];
$availableTimes_id = $row['availableTimes_id'];
$stmt->close();

if ($status !== 'R') {
    // Only allow deletion if the meeting status is "Request To Delete"
    $conn->close();
    header("Location: ./meeting.php?error=NotDeletable");
    exit;
}

// Start a transaction to ensure both operations are performed atomically
$conn->begin_transaction();

try {
    // Delete the meeting
    $sql_delete = "DELETE FROM meeting WHERE meeting_id = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $meeting_id);
    $stmt->execute();
    $stmt->close();

    // Set the availability of the time slot to "available"
    $sql_update = "UPDATE availabletimes SET availability = 'available' WHERE availableTimes_id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("i", $availableTimes_id);
    $stmt->execute();
    $stmt->close();

    // Commit the transaction
    $conn->commit();
    $conn->close();
    header("Location: ./meeting.php?success=1");
    exit;
} catch (Exception $e) {
    // Rollback the transaction in case of an error
    $conn->rollback();
    $conn->close();
    header("Location: ./meeting.php?error=TransactionFailed");
    exit;
}
?>
