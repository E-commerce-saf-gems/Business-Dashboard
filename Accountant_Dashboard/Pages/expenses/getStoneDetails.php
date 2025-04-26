<?php
include '../../../database/db.php';

header('Content-Type: application/json');

// Fetch all gems from the inventory
$sql = "SELECT stone_id, type, availability, visibility FROM inventory ORDER BY stone_id ASC";
$result = $conn->query($sql);

$gems = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $gems[] = $row;
    }
}

echo json_encode($gems);
?>
