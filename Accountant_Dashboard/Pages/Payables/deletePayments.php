
<?php
include '../../../database/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payment_id'])) {
    $payment_id = $_POST['payment_id'];

    try {
        // Start payment
        $conn->begin_transaction();

        // Fetch transaction details
        $getPaymentSQL = "SELECT amount, buyer_id, stone_id FROM payment WHERE payment_id = ?";
        $stmt = $conn->prepare($getPaymentSQL);
        $stmt->bind_param("i", $payment_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $payment = $result->fetch_assoc();

        if (!$payment) {
            throw new Exception("Payment not found.");
        }

        $amount = $payment['amount'];
        $buyer_id = $payment['buyer_id'];
        $stone_id = $payment['stone_id'];

        // Delete the payment from payment table
        $deletePaymentSQL = "DELETE FROM payment WHERE payment_id = ?";
        $stmt = $conn->prepare($deletePaymentSQL);
        $stmt->bind_param("i", $payment_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to delete payment.");
        }

        // Update the purchases table by reducing the amountSettled
        $updatePurchasesSQL = "UPDATE purchases SET amountSettled = amountSettled - ? WHERE buyer_id = ? AND stone_id = ?";
        $stmt = $conn->prepare($updatePurchasesSQL);
        $stmt->bind_param("dii", $amount, $buyer_id, $stone_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update purchases.");
        }

        // Commit transaction
        $conn->commit();
        header("Location: ./payments.php?DeleteSuccess=1");
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