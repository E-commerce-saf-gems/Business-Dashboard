<?php

include("../../../database/db.php");

$buyer_id = $_POST['buyer_id'];
$amount = $_POST['amount'];
$stone_id = $_POST['stone_id'];

try {

    
    // Begin transaction
    $conn->begin_transaction();

    // Insert into transactions
    $stmt = $conn->prepare("INSERT INTO payment (buyer_id, amount, stone_id) VALUES (?, ?, ?)");
    $stmt->bind_param("idi", $buyer_id, $amount, $stone_id);

    if (!$stmt->execute()) {
        header("Location: ../transactions/transactions.php?PaymentSuccess=2") ;
    }

    // Update the sales table
    $stmt = $conn->prepare("
        UPDATE purchases
        SET amountSettled = amountSettled + ?
        WHERE buyer_id = ? AND stone_id = ? AND amountSettled + ? <= total
    ");
    $stmt->bind_param("didi", $amount, $buyer_id, $stone_id, $amount);

    if (!$stmt->execute()) {
        header("Location: ../transactions/transactions.php?PaymentSuccess=2") ;
    }

    // Commit transaction
    $conn->commit();
    echo "Payment and purchases update completed successfully!";
    header("Location: ../transactions/transactions.php?PaymentSuccess=1") ;

    
} catch (Exception $e) {
    // Rollback transaction on failure
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

// Close the prepared statement
$stmt->close();
$conn->close();
?>