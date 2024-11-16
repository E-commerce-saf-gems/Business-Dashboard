<?php
include '../../../database/db.php';// Adjust path as needed

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transaction_id = $_POST['transaction_id'];
    $date = $_POST['date'];
    $type = $_POST['type'];
    $stone_id = $_POST['stone_id'];
    $customer_id = $_POST['customer_id'];
    $buyer_id = $_POST['buyer_id'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];

    // Update query
    $query = "UPDATE transactions SET date = ?, type = ?, stone_id = ?, customer_id = ?, buyer_id = ?, amount = ?, status = ? WHERE transaction_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssisssi", $date, $type, $stone_id, $customer_id, $buyer_id, $amount, $status, $transaction_id);

    if ($stmt->execute()) {
        echo "Transaction updated successfully!";
        header("Location: ../../Pages/transactions/transactions.php?message=Transaction updated successfully");
        exit;
    } else {
        echo "Error updating transaction: " . $conn->error;
    }
}
// Close the statement and connection
$stmt->close();
$conn->close();
?>
