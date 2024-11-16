<?php
include '../../../database/db.php';// Adjust path as needed

// Check if the transaction ID is passed in the URL
if (isset($_GET['transaction_id'])) {
    $transaction_id = $_GET['transaction_id'];

    // Fetch transaction data from the database
    $query = "SELECT * FROM transactions WHERE transaction_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    
    // Check if the transaction exists
    if ($result->num_rows > 0) {
        $transaction = $result->fetch_assoc();
    } else {
        echo "Transaction not found.";
        exit;
    }
} else {
    echo "Transaction ID not provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transactions Details</title>
    <link rel="stylesheet" href="../../../Components/Accountant_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="/edittransactionstyles.css">   
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <dashboard-component></dashboard-component>
    <section id="content">
        <main>
            <div class="head-title">
				<div class="left">
					<h1>Transactions</h1>
					<ul class="breadcrumb">
						<li>
							<a href="../transactions/transactions.php">Home</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Edit Transactions</a>
						</li>
					</ul>
				</div>
			</div>
            <div class="edit-sales-container">
            <form class="edit-sales-form" id="editSalesForm" method="POST" action="updatetransaction.php" >
                <h2>Edit Transactions Details</h2>

                <!-- Hidden Field for Transaction ID -->
                <input type="hidden" id="transaction" name="transaction_id" value="<?php echo isset($transaction) ? $transaction['transaction_id'] : ''; ?>" />

                <!-- Date Field -->
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" value="<?php echo isset($transaction) ? $transaction['date'] : ''; ?>" required />
                </div>

                <!-- Type Field -->
                <div class="form-group">
                    <label for="type">Type</label>
                    <select id="type" name="type">
                        <option value="Sale" <?php echo (isset($transaction) && $transaction['type'] == 'Sale') ? 'selected' : ''; ?>>Sale</option>
                        <option value="Purchase" <?php echo (isset($transaction) && $transaction['type'] == 'Purchase') ? 'selected' : ''; ?>>Purchase</option>
                        <option value="Auction" <?php echo (isset($transaction) && $transaction['type'] == 'Auction') ? 'selected' : ''; ?>>Auction</option>
                        <option value="Refund" <?php echo (isset($transaction) && $transaction['type'] == 'Refund') ? 'selected' : ''; ?>>Refund</option>
                    </select>
                </div>

                <!-- Stone ID Field -->
                <div class="form-group">
                    <label for="stone_id">Stone ID</label>
                    <input type="number" id="stone_id" name="stone_id" value="<?php echo isset($transaction) ? $transaction['stone_id'] : ''; ?>" required />
                </div>

                <!-- Customer ID Field -->
                <div class="form-group">
                    <label for="customer_id">Customer ID</label>
                    <input type="number" id="customer_id" name="customer_id" value="<?php echo isset($transaction) ? $transaction['customer_id'] : ''; ?>" required />
                </div>

                <!-- Supplier ID Field -->
                <div class="form-group">
                    <label for="buyer_id">Supplier ID</label>
                    <input type="number" id="buyer_id" name="buyer_id" value="<?php echo isset($transaction) ? $transaction['buyer_id'] : ''; ?>" required />
                </div>

                <!-- Amount Field -->
                <div class="form-group">
                    <label for="amount">Amount ($)</label>
                    <input type="number" id="amount" name="amount" value="<?php echo isset($transaction) ? $transaction['amount'] : ''; ?>" required />
                </div>

                <!-- Status Field -->
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="Completed" <?php echo (isset($transaction) && $transaction['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                        <option value="Pending" <?php echo (isset($transaction) && $transaction['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                    </select>
                </div>

                <!-- Save Button -->
                <div class="form-actions">
                    <button type="submit" class="btn-save">
                        <i class='bx bx-save'></i> Save Changes
                    </button>
                </div>
            </form>

            </div>
        </main>
    </section>

    <script src="../../../Components/Accountant_Dashboard_Template/script.js"></script>
</body>
</html>
