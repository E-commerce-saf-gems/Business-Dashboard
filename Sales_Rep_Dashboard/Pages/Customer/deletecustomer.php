<?php
if (isset($_GET['id'])) {
    $customerId = intval($_GET['id']); 

    include '../../../database/db.php';

    $sql = "DELETE FROM customer WHERE customer_id = $customerId";

    if ($conn->query($sql) === TRUE) {
        header("Location: ./customers.php?deleteSuccess=1");
    } else {
        echo "Error deleting record: " . $conn->error;
        header("Location: ./customers.php?deleteSuccess=2");
    }

    $conn->close();
} else {
    header("Location: ./customers.php");
}
?>
