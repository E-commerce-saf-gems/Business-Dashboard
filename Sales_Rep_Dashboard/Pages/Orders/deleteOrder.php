<?php
include '../../../database/db.php';

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Delete the order from the database
    $delete_sql = "DELETE FROM orders WHERE order_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        // Redirect back to the orders page after successful deletion
        header("Location: order_summary.php?success=1");
    } else {
        // Redirect with an error message if deletion fails
        header("Location: order_summary.php?success=0");
    }

    $stmt->close();
}

$conn->close();
?>
