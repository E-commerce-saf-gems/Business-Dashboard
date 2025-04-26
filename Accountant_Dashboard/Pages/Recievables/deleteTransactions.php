
<?php
include '../../../database/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['transaction_id'])) {
    $transaction_id = $_POST['transaction_id'];

    try {
        // Start transaction
        $conn->begin_transaction();

        // Fetch transaction details
        $getTransactionSQL = "SELECT amount, customer_id, stone_id FROM transactions WHERE transaction_id = ?";
        $stmt = $conn->prepare($getTransactionSQL);
        $stmt->bind_param("i", $transaction_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $transaction = $result->fetch_assoc();

        if (!$transaction) {
            throw new Exception("Transaction not found.");
        }

        $amount = $transaction['amount'];
        $customer_id = $transaction['customer_id'];
        $stone_id = $transaction['stone_id'];

        // Delete the transaction from transactions table
        $deleteTransactionSQL = "DELETE FROM transactions WHERE transaction_id = ?";
        $stmt = $conn->prepare($deleteTransactionSQL);
        $stmt->bind_param("i", $transaction_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to delete transaction.");
        }

        // Update the sales table by reducing the amountSettled
        $updateSalesSQL = "UPDATE sales SET amountSettled = amountSettled - ? WHERE customer_id = ? AND stone_id = ?";
        $stmt = $conn->prepare($updateSalesSQL);
        $stmt->bind_param("dii", $amount, $customer_id, $stone_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update sales.");
        }

        // Commit transaction
        $conn->commit();
        header("Location: ./invoices.php?DeleteSuccess=1");
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    exit;
} else {
    echo "Invalid request.";
    exit;
}
?>

