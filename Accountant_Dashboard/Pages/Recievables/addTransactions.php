<?php

include("../../../database/db.php");

$customer_id = $_POST['customer_id'];
$amount = $_POST['amount'];
$stone_id = $_POST['stone_id'];

try {
    // Begin transaction
    $conn->begin_transaction();

    // Insert into transactions
    $stmt = $conn->prepare("INSERT INTO transactions (customer_id, amount, stone_id) VALUES (?, ?, ?)");
    $stmt->bind_param("idi", $customer_id, $amount, $stone_id);

    if (!$stmt->execute()) {
        header("Location: ../transactions/transactions.php?ReceivalSuccess=2") ;
    }

    // Update the sales table
    $stmt = $conn->prepare("
        UPDATE sales
        SET amountSettled = amountSettled + ?
        WHERE customer_id = ? AND stone_id = ? AND amountSettled + ? <= total
    ");
    $stmt->bind_param("didi", $amount, $customer_id, $stone_id, $amount);

    if (!$stmt->execute()) {
        header("Location: ../transactions/transactions.php?ReceivalSuccess=3") ;
    }

    // Commit transaction
    $conn->commit();
    echo "Transaction and sales update completed successfully!";
    header("Location: ../transactions/transactions.php?ReceivalSuccess=1") ;

    
} catch (Exception $e) {
    // Rollback transaction on failure
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

// Close the prepared statement
$stmt->close();
$conn->close();
?>