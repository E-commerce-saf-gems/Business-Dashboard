<?php

include '../../../database/db.php';

var_dump($_POST);


$type = isset($_POST['type']) ? $_POST['type'] : null;
$customer_id = isset($_POST['customer_id']) ? $_POST['customer_id'] : null;
$amount = isset($_POST['amount']) ? $_POST['amount'] : null;
$stone_id = isset($_POST['stone_id']) ? $_POST['stone_id'] : null;


if (!$type || !$customer_id || !$amount || !$stone_id) {
    echo "Error: Missing required fields";
    exit();
}

$stmt = $conn->prepare("INSERT INTO transactions (type, customer_id, amount, stone_id) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sidi", $type, $customer_id, $amount, $stone_id);


if ($stmt->execute()) {
    header("Location: ./transactions.php?success=1"); 
    exit();
} else {
    header("Location: ./transactions.php?success=2"); 

}

$stmt->close();
$conn->close();
?>

