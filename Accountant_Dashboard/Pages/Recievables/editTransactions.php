<?php
include '../../../database/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['transaction_id'])) {
    $transaction_id = $_GET['transaction_id'];

    // Fetch transaction details
    $sql = "SELECT * FROM transactions WHERE transaction_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $transaction = $result->fetch_assoc();

    if (!$transaction) {
        echo "Transaction not found.";
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transaction_id = $_POST['transaction_id'];
    $customer_id = $_POST['customer_id'];
    $stone_id = $_POST['stone_id'];
    $new_amount = $_POST['amount'];

    try {
        // Start transaction
        $conn->begin_transaction();

        // Fetch original transaction amount
        $getOriginalSQL = "SELECT amount FROM transactions WHERE transaction_id = ?";
        $stmt = $conn->prepare($getOriginalSQL);
        $stmt->bind_param("i", $transaction_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $original_transaction = $result->fetch_assoc();

        if (!$original_transaction) {
            throw new Exception("Original transaction not found.");
        }

        $original_amount = $original_transaction['amount'];

        // Update the transactions table
        $updateTransactionSQL = "UPDATE transactions SET amount = ?, customer_id = ?, stone_id = ? WHERE transaction_id = ?";
        $stmt = $conn->prepare($updateTransactionSQL);
        $stmt->bind_param("diii", $new_amount, $customer_id, $stone_id, $transaction_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update transaction.");
        }

        // Update the sales table
        $adjustment = $new_amount - $original_amount; // Difference between new and original amount
        $updateSalesSQL = "UPDATE sales SET amountSettled = amountSettled + ? WHERE customer_id = ? AND stone_id = ?";
        $stmt = $conn->prepare($updateSalesSQL);
        $stmt->bind_param("dii", $adjustment, $customer_id, $stone_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update sales.");
        }

        // Commit transaction
        $conn->commit();
        header("Location: ./invoices.php?UpdateSuccess=1");
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaction</title>
    <link rel="stylesheet" href="../../../Components/Accountant_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="../transactions/edittransactionstyles.css">
</head>
<body>
    <dashboard-component></dashboard-component>

    <section id="content">
        <main>
            <div class="head-title">
                <h1>Edit Transaction</h1>
            </div>

            <div class="edit-sales-container">
                <form class="edit-sales-form" method="POST">
                    <input type="hidden" name="transaction_id" value="<?= htmlspecialchars($transaction['transaction_id']) ?>">

                    <div class="form-group">
                        <label for="customer">Customer ID</label>
                        <input type="number" id="customer" name="customer_id" value="<?= htmlspecialchars($transaction['customer_id']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="stone">Stone ID</label>
                        <input type="number" id="stone" name="stone_id" value="<?= htmlspecialchars($transaction['stone_id']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="amount">Amount (Rs.)</label>
                        <input type="number" id="amount" name="amount" value="<?= htmlspecialchars($transaction['amount']) ?>" required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save">Save Changes</button>
                    </div>
                </form>
            </div>
        </main>
    </section>

    <script src="../../../Components/Accountant_Dashboard_Template/script.js"></script>
</body>
</html>

