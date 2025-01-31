<?php
include '../../../database/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['expense_id'])) {
    $expense_id = $_GET['expense_id'];

    // Fetch expense details
    $sql = "SELECT * FROM expenses WHERE expense_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $expense_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $expense = $result->fetch_assoc();

    if (!$expense) {
        echo "Expense not found.";
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $expense_id = $_POST['expense_id'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];

    try {
        // Start transaction
        $conn->begin_transaction();

        // Update the expense in the expenses table
        $updateExpenseSQL = "UPDATE expenses SET type = ?, description = ?, amount = ?, status = ? WHERE expense_id = ?";
        $stmt = $conn->prepare($updateExpenseSQL);
        $stmt->bind_param("ssdsd", $type, $description, $amount, $status, $expense_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update expense.");
        }

        // Commit transaction
        $conn->commit();
        header("Location: ../expenses/expenseType.php?ExpenseUpdated=1");
        exit();
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
    <title>Edit Expenses</title>
    <link rel="stylesheet" href="../../../Components/Accountant_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="../transactions/edittransactionstyles.css">
</head>
<body>
    <dashboard-component></dashboard-component>

    <section id="content">
        <main>
            <div class="head-title">
				<div class="left">
					<h1>Edit Expenses</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="./expenseType.php">Home</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Edit Expenses</a>
						</li>
					</ul>
				</div>
			</div>

            <div class="edit-sales-container">
                <form class="edit-sales-form" method="POST">
                    <input type="hidden" name="expense_id" value="<?= htmlspecialchars($expense['expense_id']) ?>">

                    <div class="form-group">
                        <label for="type">Expense Type</label>
                        <input type="text" id="type" name="type" value="<?= htmlspecialchars($expense['type']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" id="description" name="description" value="<?= htmlspecialchars($expense['description']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="amount">Amount (Rs.)</label>
                        <input type="number" id="amount" name="amount" value="<?= htmlspecialchars($expense['amount']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            <option value="Paid" <?= ($expense['status'] == 'Paid') ? 'selected' : '' ?>>Paid</option>
                            <option value="Unpaid" <?= ($expense['status'] == 'Unpaid') ? 'selected' : '' ?>>Unpaid</option>
                        </select>
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
