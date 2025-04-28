<?php
include '../../../database/db.php';

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    $delete_sql = "DELETE FROM orders WHERE order_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        header("Location: order_summary.php?success=1");
    } else {
        header("Location: order_summary.php?success=0");
    }

    $stmt->close();
}

$conn->close();
?>
