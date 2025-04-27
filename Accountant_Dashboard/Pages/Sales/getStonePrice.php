<?php
// getStonesPrice.php

// Include database connection file
require_once '../../../database/db.php';

// Check if stone ID is provided
if (isset($_GET['stone_id']) && !empty($_GET['stone_id'])) {
    $stone_id = filter_var($_GET['stone_id'], FILTER_SANITIZE_NUMBER_INT);

    // Query to fetch stone amount
    $query = "SELECT amount FROM inventory WHERE stone_id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $stone_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Return amount as JSON (fix: use 'amount' not 'price')
            header('Content-Type: application/json');
            echo json_encode(['amount' => $row['amount']]);
        } else {
            // Stone not found or not available
            http_response_code(404);
            echo json_encode(['error' => 'Stone not found or not available']);
        }
        $stmt->close();
    } else {
        // Query preparation failed
        http_response_code(500);
        echo json_encode(['error' => 'Failed to prepare statement']);
    }
} else {
    // Invalid request
    http_response_code(400);
    echo json_encode(['error' => 'Invalid stone ID']);
}

// Close connection
$conn->close();
?>
