<?php
include("../../../database/db.php");

$customer_id = $_POST['customer_id'] ?? null;
$amount = $_POST['amount'] ?? null;
$stone_id = $_POST['stone_id'] ?? null;

if (!$customer_id || !$amount || !$stone_id) {
    die("Missing required data.");
}

try {
    // Begin transaction
    $conn->begin_transaction();

    // Insert into transactions table
    $stmt = $conn->prepare("INSERT INTO transactions (customer_id, amount, stone_id) VALUES (?, ?, ?)");
    $stmt->bind_param("idi", $customer_id, $amount, $stone_id);
    $stmt->execute();
    $stmt->close();

    // Update sales table
    $stmt = $conn->prepare("
        UPDATE sales
        SET amountSettled = amountSettled + ?
        WHERE customer_id = ? AND stone_id = ? AND (amountSettled + ?) <= total
    ");
    $stmt->bind_param("didi", $amount, $customer_id, $stone_id, $amount);
    $stmt->execute();
    $stmt->close();

    // Commit transaction
    $conn->commit();

    header("Location: ../transactions/transactions.php?ReceivalSuccess=1");
    exit();
} catch (Exception $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage();
    exit();
}
?>
