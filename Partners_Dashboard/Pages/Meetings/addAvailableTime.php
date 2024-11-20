<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../Login/login.html");
    exit;
}

include('../../../database/db.php');

$salesRep_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $time = $_POST['time'];

    $sql = "INSERT INTO availabletimes (salesRep_id, date, time) VALUES (?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iss", $salesRep_id, $date, $time);

        if ($stmt->execute()) {
            header("Location: ./meeting.php?success=1");
        } else {
            header("Location: ./meeting.php?error=1");
        }

        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close(); 
}
?>
