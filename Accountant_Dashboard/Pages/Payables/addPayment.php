<?php

include '../../../database/db.php';

$buyer_id = $_POST['buyer_id'] ?? null;
$amount = $_POST['amount'] ?? null;
$stone_id = $_POST['stone_id'] ?? null;

if (!$buyer_id || !$amount || !$stone_id) {
    echo "Error: Missing required fields";
    exit();
}

// Start transaction
$conn->begin_transaction();

try {
    // Insert into payments
    $stmt = $conn->prepare("INSERT INTO payments (buyer_id, amount, stone_id) VALUES (?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Prepare failed for INSERT: " . $conn->error);
    }

    $stmt->bind_param("idi", $buyer_id, $amount, $stone_id);

    if (!$stmt->execute()) {
        throw new Exception("Execute failed for INSERT: " . $stmt->error);
    }

    echo "Payment inserted successfully.<br>";
    $stmt->close();

    // Update purchases
    $stmt = $conn->prepare("
        UPDATE purchases 
        SET amountSettled = amountSettled + ?
        WHERE buyer_id = ? AND stone_id = ? AND amountSettled + ? <= total
    ");
    if (!$stmt) {
        throw new Exception("Prepare failed for UPDATE: " . $conn->error);
    }

    $stmt->bind_param("diii", $amount, $buyer_id, $stone_id, $amount);

    if (!$stmt->execute()) {
        throw new Exception("Execute failed for UPDATE: " . $stmt->error);
    }

    if ($stmt->affected_rows === 0) {
        throw new Exception("No rows updated in purchases. Check buyer_id, stone_id, or amount constraints.");
    }

    echo "Purchases updated successfully.<br>";
    $stmt->close();

    // Commit transaction
    $conn->commit();
    echo "Transaction committed.<br>";

    header("Location: ../transactions/transactions.php?PaymentSuccess=1");
    exit();

} catch (Exception $e) {
    $conn->rollback();
    echo "Transaction failed: " . $e->getMessage();
    exit();
}
?>

