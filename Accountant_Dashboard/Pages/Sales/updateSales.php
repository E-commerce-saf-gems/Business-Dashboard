<?php
include '../../../database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sale_id = $_POST['sale_id'];
    $stone_id = $_POST['stone_id'];
    $customer_id = $_POST['customer_id'];
    $total = $_POST['total'];
    $date = $_POST['date'];
    $amountSettled = $_POST['amountSettled'];

    $sql = "UPDATE sales SET stone_id=?, customer_id=?, total=?, date=?, amountSettled=? WHERE sale_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiisii", $stone_id, $customer_id, $total, $date, $amountSettled, $sale_id);

    if ($stmt->execute()) {
        header("Location: sales.php?updated=1");
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
