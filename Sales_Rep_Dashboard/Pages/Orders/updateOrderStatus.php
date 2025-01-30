<?php
include '../../../database/db.php';

if (isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    // Prepare and execute the SQL query to update the status
    $update_sql = "UPDATE orders SET order_status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $new_status, $order_id);

    if ($stmt->execute()) {
        // Redirect back to the main page with success indicator
        header("Location: ./orders.php?success=1");
    } else {
        // Redirect back with error indicator if the update fails
        header("Location: ./orders.php?success=0");
    }
    $stmt->close();
}
$conn->close();
?>
