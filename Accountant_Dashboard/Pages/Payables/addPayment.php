<?php

include '../../../database/db.php';

var_dump($_POST);

// Get POST data
$buyer_id = isset($_POST['buyer_id']) ? $_POST['buyer_id'] : null;
$amount = isset($_POST['amount']) ? $_POST['amount'] : null;
$stone_id = isset($_POST['stone_id']) ? $_POST['stone_id'] : null;

if (!$buyer_id || !$amount || !$stone_id) {
    echo "Error: Missing required fields";
    exit();
}

$conn->begin_transaction();

try {
    // Insert the payment
    $stmt = $conn->prepare("INSERT INTO payment (buyer_id, amount, stone_id) VALUES (?, ?, ?)");
    $stmt->bind_param("idi", $buyer_id, $amount, $stone_id);

    if (!$stmt->execute()) {
        throw new Exception("Error inserting payment: " . $stmt->error);
    }

    $stmt->close();

    // Update the purchases table
    $stmt = $conn->prepare("
        UPDATE purchases 
        SET amountSettled = amountSettled + ?
        WHERE buyer_id = ? AND stone_id = ? AND amountSettled < amount
    ");
    $stmt->bind_param("dii", $amount, $buyer_id, $stone_id);

    if (!$stmt->execute()) {
        throw new Exception("Error updating purchase: " . $stmt->error);
    }

    $stmt->close();

    // Commit the transaction
    $conn->commit();

    // Redirect on success
    header("Location: ../transactions/transactions.php?PaymentSuccess=1");
    exit();

} catch (Exception $e) {
    // Rollback the transaction on error
    $conn->rollback();

    // Log the error (optional) and redirect with an error
    error_log("Transaction failed: " . $e->getMessage());
    header("Location: ../transactions/transactions.php?PaymentSuccess=2");
    exit();
}

// Close the connection
?>