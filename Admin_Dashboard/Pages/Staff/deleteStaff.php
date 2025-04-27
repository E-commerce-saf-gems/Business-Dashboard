<?php
if (isset($_GET['id'])) {
    $userId = intval($_GET['id']); 

    include '../../../database/db.php';

    $sql = "DELETE FROM user WHERE user_id = $userId";

    if ($conn->query($sql) === TRUE) {
        header("Location: ./Staff.php?deleteSuccess=1");
    } else {
        echo "Error deleting record: " . $conn->error;
        header("Location: ./Staff.php?deleteSuccess=2");
    }

    $conn->close();
} else {
    header("Location: ./Staff.php");
}
?>
