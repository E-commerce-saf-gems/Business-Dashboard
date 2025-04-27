<?php
// getStonesData.php

// Include database connection file
require_once '../../../database/db.php';

// Query to fetch all available stones
$query = "SELECT stone_id, type, colour, shape, size FROM inventory WHERE availability = 'available' ORDER BY stone_id ASC";
$result = $conn->query($query);

$stones = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $stones[] = $row;
    }
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($stones);

// Close connection
$conn->close();
?>
