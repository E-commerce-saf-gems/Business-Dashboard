<?php
// addSales.php

// 1. Connect to your database
include '../../../database/db.php'; // or your correct connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 2. Get data from form
    $email = trim($_POST['email']);
    $stone_id = intval($_POST['stone_id']);
    $amount = floatval($_POST['amount']);
    $amountSettled = floatval($_POST['amountSettled']);

    // 3. Basic validation
    if (empty($email) || empty($stone_id) || $amount <= 0 || $amountSettled < 10000) {
        echo "<script>alert('Invalid input. Please fill the form correctly.'); window.history.back();</script>";
        exit;
    }

    if ($amountSettled > $amount) {
        echo "<script>alert('Amount Settled cannot exceed Sale Amount.'); window.history.back();</script>";
        exit;
    }

    // 4. Get customer_id using email
    $getCustomerQuery = "SELECT customer_id FROM customer WHERE email = ?";
    $stmt = $conn->prepare($getCustomerQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($customer_id);
    $stmt->fetch();
    $stmt->close();

    if (!$customer_id) {
        echo "<script>alert('Customer not found.'); window.history.back();</script>";
        exit;
    }

    // 5. Insert into sales table
    $insertSaleQuery = "INSERT INTO sales (customer_id, stone_id, total, amountSettled, date) 
                        VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($insertSaleQuery);
    $stmt->bind_param("iidd", $customer_id, $stone_id, $amount, $amountSettled);

    if ($stmt->execute()) {
        // 6. Update stone availability to 'sold'
        $updateStoneQuery = "UPDATE inventory SET availability = 'available' WHERE stone_id = ?";
        $updateStmt = $conn->prepare($updateStoneQuery);
        $updateStmt->bind_param("i", $stone_id);
        $updateStmt->execute();
        $updateStmt->close();

        // 7. Success - Redirect back to sales summary
        echo "<script>alert('Sale added successfully!'); window.location.href = './sales.php';</script>";
    } else {
        // 8. Error
        echo "<script>alert('Failed to add sale. Please try again.'); window.history.back();</script>";
    }

    $stmt->close();
} else {
    // If not POST method
    echo "<script>alert('Invalid Request.'); window.history.back();</script>";
}

$conn->close();
?>


