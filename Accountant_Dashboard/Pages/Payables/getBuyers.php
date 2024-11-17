<?php
include('../../../database/db.php'); // Include your database connection

$query = "SELECT buyer_id, email FROM buyer";
$result = $conn->query($query);

$buyers = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $buyers[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($buyers);

$conn->close();
?>
