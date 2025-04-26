<?php
include("../../../database/db.php");

try {
    // Query to fetch stones with availability = 'Available'
    $stmt = $conn->prepare("
        SELECT stone_id, colour, shape, type, weight, amount
        FROM inventory 
        WHERE availability = 'Available'
    ");
    $stmt->execute();
    $result = $stmt->get_result();

    $stones = [];
    while ($row = $result->fetch_assoc()) {
        $stones[] = $row;
    }
    // Send data as JSON
    header('Content-Type: application/json');
    echo json_encode($stones); // Send stones data as JSON response
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]); // Handle any errors
}

$stmt->close();
$conn->close();
?>

