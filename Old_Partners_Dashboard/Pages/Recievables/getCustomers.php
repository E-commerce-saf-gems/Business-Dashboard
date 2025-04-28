<?php
include('../../../database/db.php'); // Include your database connection

$query = "SELECT customer_id, email FROM customer";
$result = $conn->query($query);

$customers = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($customers);

$conn->close();
?>
