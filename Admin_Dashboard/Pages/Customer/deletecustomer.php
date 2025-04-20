<?php
if (isset($_GET['id'])) {
    $customerId = intval($_GET['id']); // Get the ID and convert it to an integer

    // Database connection
    include '../../../database/db.php';

    // SQL to delete the record
    $sql = "DELETE FROM customer WHERE customer_id = $customerId";

    if ($conn->query($sql) === TRUE) {
        // Redirect back with a success message
        header("Location: ./customers.php?deleteSuccess=1");
    } else {
        // Redirect back with an error message
        echo "Error deleting record: " . $conn->error;
        header("Location: ./customers.php?deleteSuccess=2");
    }

    $conn->close();
} else {
    // Redirect if ID is not set
    header("Location: ./customers.php");
}
?>
