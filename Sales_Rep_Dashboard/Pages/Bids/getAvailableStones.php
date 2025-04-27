<?php
include("../../../database/db.php");

try {
    $stmt = $conn->prepare("
        SELECT stone_id, colour, shape, type, size, amount
        FROM inventory 
        WHERE availability = 'available'
    ");
    $stmt->execute();
    $result = $stmt->get_result();

    $stones = [];
    while ($row = $result->fetch_assoc()) {
        $stones[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($stones); 
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]); 
}

$stmt->close();
$conn->close();
?>

