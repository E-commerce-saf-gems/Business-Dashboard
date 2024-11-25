<?php
include '../../../database/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];

    // Fetch payment details
    $sql = "SELECT * FROM payment WHERE payment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $payment = $result->fetch_assoc();

    if (!$payment) {
        echo "Payment not found.";
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_id = $_POST['payment_id'];
    $buyer_id = $_POST['buyer_id'];
    $stone_id = $_POST['stone_id'];
    $new_amount = $_POST['amount'];

    try {
        // Start payment
        $conn->begin_transaction();

        // Fetch original transaction amount
        $getOriginalSQL = "SELECT amount FROM payment WHERE payment_id = ?";
        $stmt = $conn->prepare($getOriginalSQL);
        $stmt->bind_param("i", $payment_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $original_payment = $result->fetch_assoc();

        if (!$original_payment) {
            throw new Exception("Original payment not found.");
        }

        $original_amount = $original_payment['amount'];

        // Update the payment table
        $updatePaymentSQL = "UPDATE payment SET amount = ?, buyer_id = ?, stone_id = ? WHERE payment_id = ?";
        $stmt = $conn->prepare($updatePaymentSQL);
        $stmt->bind_param("diii", $new_amount, $buyer_id, $stone_id, $payment_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update payment.");
        }

        // Update the purchases table
        $adjustment = $new_amount - $original_amount; // Difference between new and original amount
        $updatePurchasesSQL = "UPDATE purchases SET amountSettled = amountSettled + ? WHERE buyer_id = ? AND stone_id = ?";
        $stmt = $conn->prepare($updatePurchasesSQL);
        $stmt->bind_param("dii", $adjustment, $buyer_id, $stone_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update purchases.");
        }

        // Commit transaction
        $conn->commit();
        header("Location: ./payments.php?UpdateSuccess=1");
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
    <title>Edit Payment</title>
    <link rel="stylesheet" href="../../../Components/Accountant_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="../transactions/edittransactionstyles.css">
</head>
<body>
    <dashboard-component></dashboard-component>

    <section id="content">
        <main>
            <div class="head-title">
                <h1>Edit Payment</h1>
            </div>

            <div class="edit-sales-container">
                <form class="edit-sales-form" method="POST">
                    <input type="hidden" name="payment_id" value="<?= htmlspecialchars($payment['payment_id']) ?>">

                    <div class="form-group">
                        <label for="buyer">Buyer ID</label>
                        <input type="number" id="buyer" name="buyer_id" value="<?= htmlspecialchars($payment['buyer_id']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="stone">Stone ID</label>
                        <input type="number" id="stone" name="stone_id" value="<?= htmlspecialchars($payment['stone_id']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="amount">Amount (Rs.)</label>
                        <input type="number" id="amount" name="amount" value="<?= htmlspecialchars($payment['amount']) ?>" required>
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