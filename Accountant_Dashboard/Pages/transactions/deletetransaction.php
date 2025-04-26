<?php
include '../../../database/db.php';

if (isset($_GET['transaction_id'])) {
    $transaction_id = $_GET['transaction_id'];

    $stmt = $conn->prepare("DELETE FROM transactions WHERE transaction_id = ?");
    $stmt->bind_param("i", $transaction_id);

    if ($stmt->execute()) {
        header("Location: transactions.php?message=Transaction deleted successfully");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
