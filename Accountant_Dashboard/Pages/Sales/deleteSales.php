<?php
include '../../../database/db.php';

if (isset($_GET['id'])) {
    $sale_id = $_GET['id'];

    // Prepare and execute delete statement
    $sql = "DELETE FROM sales WHERE sale_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $sale_id);

    if ($stmt->execute()) {
        header("Location: sales.php?deleted=1");
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
