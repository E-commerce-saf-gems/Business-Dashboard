<?php

include '../../../database/db.php';

// Ensure data is received
if (empty($_POST)) {
    die("Error: No data received.");
}

// Retrieve POST data safely
$stone_id   = isset($_POST['stone_id']) ? $_POST['stone_id'] : null;
$type       = isset($_POST['type']) ? $_POST['type'] : null;
$description = isset($_POST['description']) ? $_POST['description'] : null;
$amount     = isset($_POST['amount']) ? $_POST['amount'] : null;
$status     = isset($_POST['status']) ? $_POST['status'] : null;

// Debugging (Optional): Print values before insert
// echo "Stone ID: $stone_id, Type: $type, Description: $description, Amount: $amount, Status: $status<br>";

// Validate required fields
if (!$stone_id || !$type || !$description || !$amount || !$status) {
    die("Error: Missing required fields.");
}

// Start transaction
$conn->begin_transaction();

try {
    // Insert into expenses table including stone_id
    $stmt = $conn->prepare("INSERT INTO expenses (stone_id, type, description, amount, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issds", $stone_id, $type, $description, $amount, $status);

    if (!$stmt->execute()) {
        throw new Exception("Error inserting expense: " . $stmt->error);
    }

    $stmt->close();
    $conn->commit();

    // Redirect on success
    header("Location: ../expenses/expenseType.php?ExpenseAdded=1");
    exit();

} catch (Exception $e) {
    $conn->rollback();
    error_log("Transaction failed: " . $e->getMessage());
    header("Location: ../expenses/expenseType.php?ExpenseAdded=2");
    exit();
}
?>


