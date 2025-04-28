<?php
include '../../../database/db.php';

if (isset($_POST['order_id']) && isset($_POST['order_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['order_status'];

    $update_sql = "UPDATE orders SET order_status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($update_sql);

    if ($stmt) {
        $stmt->bind_param("si", $new_status, $order_id);
        if ($stmt->execute()) {
            $stmt->close();  
            header("Location: ./orders.php?success=1");
            exit();
        } else {
            $stmt->close();  
            header("Location: ./orders.php?success=2");
            exit();
        }
    } else {
        header("Location: ./orders.php?success=2");
        exit();
    }
}

$conn->close();
?>
