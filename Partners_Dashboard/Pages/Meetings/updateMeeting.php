<?php
include('../../../database/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $meeting_id = $_POST['meeting_id'];
    $status = $_POST['status'];

    $query = "UPDATE meeting SET status = ? WHERE meeting_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $meeting_id);

    if ($stmt->execute()) {
        header("Location: meeting.php?success=1");
        exit();
    } else { 
        echo "Error updating status: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>