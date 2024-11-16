<?php
// addTransaction.php
// Database connection
include '../../../database/db.php';



/* Check if all required POST variables are set
$transaction_id = isset($_POST['transaction_id']) ? $_POST['transaction_id'] : null;
$date = isset($_POST['date']) ? $_POST['date'] : null;
$type = isset($_POST['type']) ? $_POST['type'] : null;
$stone_id = isset($_POST['stone_id']) ? $_POST['stone_id'] : null;
$customer_id = isset($_POST['customer_id']) ? $_POST['customer_id'] : null;
$buyer_id = isset($_POST['buyer_id']) ? $_POST['buyer_id'] : null;
$amount = isset($_POST['amount']) ? $_POST['amount'] : null;
$status = isset($_POST['status']) ? $_POST['status'] : null;*/


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $type = $_POST['type'];
    $stone_id = $_POST['stone_id'];
    $customer_id = $_POST['customer_id'];
    $buyer_id = $_POST['buyer_id'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];

    // Check if any required fields are missing
    if (($type === 'Sale' && empty($customer_id)) || ($type === 'Purchase' && empty($buyer_id))) {
        echo "Error: Missing required fields for the selected type.";
        exit();
    }

    // Prepare an SQL statement to insert the transaction into the database
    $stmt = $conn->prepare("INSERT INTO transactions (transaction_id, date, type, stone_id, customer_id, buyer_id, amount, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $transaction_id, $date, $type, $stone_id, $customer_id, $buyer_id, $amount, $status);

    if ($stmt->execute()) {
    // Redirect to transactions page or display a success message
        header("Location: transactions.php?message=Transaction added successfully"); // Adjust path as needed
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>






