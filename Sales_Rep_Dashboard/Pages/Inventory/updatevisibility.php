<?php
include('../../../database/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stone_id = $_POST['stone_id'];
    $visibility = $_POST['visibility'];

    $query = "UPDATE inventory SET visibility = ? WHERE stone_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $visibility, $stone_id);

    if ($stmt->execute()) {
        header("Location: inventory.php?success=1");
        exit();
    } else {
        echo "Error updating status: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
