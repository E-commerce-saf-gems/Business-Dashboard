<?php
include '../../../database/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['expense_id'])) {
    $expense_id = $_POST['expense_id'];

    try {
        // Start transaction
        $conn->begin_transaction();

        // Fetch expense details
        $getExpenseSQL = "SELECT amount, type FROM expenses WHERE expense_id = ?";
        $stmt = $conn->prepare($getExpenseSQL);
        $stmt->bind_param("i", $expense_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $expense = $result->fetch_assoc();

        if (!$expense) {
            throw new Exception("Expense not found.");
        }

        $amount = $expense['amount'];
        $type = $expense['type'];

        // Delete the expense from expenses table
        $deleteExpenseSQL = "DELETE FROM expenses WHERE expense_id = ?";
        $stmt = $conn->prepare($deleteExpenseSQL);
        $stmt->bind_param("i", $expense_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to delete expense.");
        }

        // Optionally, if you need to update any other table related to the expense, do so here
        // For example, updating a "budget" or "expenses summary" table if applicable

        // Commit transaction
        $conn->commit();
        header("Location: ../expenses/expenseType.php?DeleteSuccess=1");
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
