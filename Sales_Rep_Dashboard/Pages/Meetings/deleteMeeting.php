<?php
session_start();
include '../../../database/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../Login/login-form.php");
    exit;
}

if (!isset($_POST['meeting_id'])) {
    header("Location: ./meeting.php?error=InvalidRequest");
    exit;
}

$salesRep_id = $_SESSION['user_id'];
$meeting_id = $_POST['meeting_id'];

$sql_check = "SELECT m.status, m.availableTimes_id 
              FROM meeting AS m
              JOIN availabletimes AS a ON m.availableTimes_id = a.availableTimes_id
              WHERE m.meeting_id = ? AND a.salesRep_id = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("ii", $meeting_id, $salesRep_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
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
    $conn->close();
    header("Location: ./meeting.php?error=NotDeletable");
    exit;
}


$conn->begin_transaction();

try {
    
    $sql_delete = "DELETE FROM meeting WHERE meeting_id = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $meeting_id);
    $stmt->execute();
    $stmt->close();

    
    $sql_update = "UPDATE availabletimes SET availability = 'available' WHERE availableTimes_id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("i", $availableTimes_id);
    $stmt->execute();
    $stmt->close();

    $conn->commit();
    $conn->close();
    header("Location: ./meeting.php?success=1");
    exit;
} catch (Exception $e) {
    $conn->rollback();
    $conn->close();
    header("Location: ./meeting.php?error=TransactionFailed");
    exit;
}
?>
