<?php
include '../../../database/db.php'; // Adjust the path as needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transaction_id = $_POST['transaction_id'];
    $date = $_POST['date'];
    $type = $_POST['type'];
    $stone_id = $_POST['stone_id'];
    $customer_id = $_POST['customer_id'];
    $buyer_id = $_POST['buyer_id'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];

    // Validation for Sale and Purchase types
    if (($type === 'Sale' && empty($customer_id)) || ($type === 'Purchase' && empty($buyer_id))) {
        echo "Error: Missing required fields for the selected type.";
        exit();
    }

    // Update query
    $query = "UPDATE transactions SET date = ?, type = ?, stone_id = ?, customer_id = ?, buyer_id = ?, amount = ?, status = ? WHERE transaction_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssisssi", $date, $type, $stone_id, $customer_id, $buyer_id, $amount, $status, $transaction_id);

    if ($stmt->execute()) {
        header("Location: transactions.php?message=Transaction updated successfully");
        exit();
    } else {
        echo "Error updating transaction: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

