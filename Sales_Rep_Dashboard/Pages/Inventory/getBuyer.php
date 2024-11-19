<?php
include('../../../database/db.php'); // Include your database connection

$sql = "SELECT buyer_id, email FROM buyer";
$result = $conn->query($sql);

$buyers = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $buyers[] = $row; // Add each buyer to the array
    }
}

header('Content-Type: application/json');
echo json_encode($buyers);
// Return the array as JSON

$conn->close();
?>
