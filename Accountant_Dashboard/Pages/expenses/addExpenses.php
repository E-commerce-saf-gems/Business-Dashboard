<?php

include '../../../database/db.php';

// Ensure data is received
if (empty($_POST)) {
    die("Error: No data received.");
}

// Debugging: Print received data
var_dump($_POST);

// Retrieve POST data with correct field mapping
$type = !empty($_POST['type']) ? $_POST['type'] : null;  // 'type' is the expense category
$description = !empty($_POST['description']) ? $_POST['description'] : null;  // 'description' describes the expense
$amount = !empty($_POST['amount']) ? $_POST['amount'] : null;
$status = !empty($_POST['status']) ? $_POST['status'] : null;

// Debugging: Print values before inserting
echo "Type: $type, Description: $description, Amount: $amount, Status: $status<br>";

// Check if any required field is missing
if (!$type || !$description || !$amount || !$status) {
    die("Error: Missing required fields.");
}

// Start transaction
$conn->begin_transaction();

try {
    // Insert the expense into the expenses table
    $stmt = $conn->prepare("INSERT INTO expenses (type, description, amount, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $type, $description, $amount, $status);

    if (!$stmt->execute()) {
        throw new Exception("Error inserting expense: " . $stmt->error);
    }

    $stmt->close();

    // Commit transaction
    $conn->commit();

    // Redirect on success
    header("Location: ../expenses/expenseType.php?ExpenseAdded=1");
    exit();

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();

    // Log the error (optional) and redirect with an error
    error_log("Transaction failed: " . $e->getMessage());
    header("Location: ../expenses/expenseType.php?ExpenseAdded=2");
    exit();
}

?>

