<?php
include '../../../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $stone_id = $_POST['stone_id'];
    $customer_id = $_POST['customer_id'];
    $total = $_POST['total'];
    $status = $_POST['status'];
    $amountSettled = $_POST['amountSettled'];

    // Optional: Ensure amountSettled doesn't exceed total
    if ($amountSettled > $total) {
        die("Error: Settled amount cannot exceed total.");
    }

    $stmt = $conn->prepare("INSERT INTO sales (date, stone_id, customer_id, total, amountSettled) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("siiid", $date, $stone_id, $customer_id, $total, $amountSettled);

    if ($stmt->execute()) {
        header("Location: sales.php?message=Sale added successfully");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

