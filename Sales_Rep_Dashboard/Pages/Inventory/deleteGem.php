<?php
if (isset($_GET['id'])) {
    $stoneId = intval($_GET['id']); 

    include '../../../database/db.php';

    $sql = "DELETE FROM inventory WHERE stone_id = $stoneId";

    if ($conn->query($sql) === TRUE) {
        header("Location: ./inventory.php?deleteSuccess=1");
    } else {
        echo "Error deleting record: " . $conn->error;
        header("Location: ./inventory.php?deleteSuccess=2");
    }

    $conn->close();
} else {
    header("Location: ./inventory.php");
}
?>
