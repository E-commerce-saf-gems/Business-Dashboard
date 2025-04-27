<?php
include '../../../database/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sale_id = $_POST['sale_id'];
    $amountSettled = $_POST['amountSettled'];

    // Update the sales record with the new amountSettled
    $sql = "UPDATE sales SET amountSettled = ? WHERE sale_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $amountSettled, $sale_id);

    if ($stmt->execute()) {
        // Redirect back to the sales list page with a success message
        header("Location: sales.php?SalesUpdateSuccess=1");
    } else {
        // Handle the error case
        echo "Error: " . $stmt->error;
    }
}
?>


