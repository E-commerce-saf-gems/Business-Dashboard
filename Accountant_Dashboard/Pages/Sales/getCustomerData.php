<?php
// getCustomersData.php

// 1. Include database connection file
require_once '../../../database/db.php'; // Correct path if needed

// 2. Query to fetch all customer emails
$query = "SELECT customer_id, email FROM customer ORDER BY email ASC"; // Table name should be `customer` (not `customers`)

$result = $conn->query($query);

$customers = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $customers[] = [
            'customer_id' => $row['customer_id'],
            'email' => $row['email']
        ];
    }
}

// 3. Return data as JSON
header('Content-Type: application/json');
echo json_encode($customers);

// 4. Close connection
$conn->close();
?>
