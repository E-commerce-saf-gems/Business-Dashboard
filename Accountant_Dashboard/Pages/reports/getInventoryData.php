<?php
header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include your DB connection
require_once '../../../database/db.php';

// Check DB connection
if (!isset($conn) || !$conn) {
    echo json_encode(["error" => "Database connection failed."]);
    exit;
}

// Initialize dates
$startDate = null;
$endDate = null;

// Handle POST request with JSON body
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $startDate = $data->startDate ?? null;
    $endDate = $data->endDate ?? null;
} else {
    // Fallback for GET requests using URL parameters
    $startDate = $_GET['from'] ?? null;
    $endDate = $_GET['to'] ?? null;
}

// If dates are still not provided, use default range (current month)
if (!$startDate || !$endDate) {
    $startDate = date('Y-m-01'); // First day of current month
    $endDate = date('Y-m-t');    // Last day of current month
}

// ----------------- INVENTORY STATS -----------------

// 1. Total items and inventory value in date range
$sql = "SELECT COUNT(*) AS totalItems, SUM(amount) AS totalInventoryValue 
        FROM inventory 
        WHERE date >= ? AND date < DATE_ADD(?, INTERVAL 1 DAY)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["error" => "Failed to prepare SQL statement."]);
    exit;
}
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$inventoryData = $stmt->get_result()->fetch_assoc();

// 2. Total Bid items in date range
$sqlBid = "SELECT COUNT(*) AS totalBidItems 
           FROM inventory 
           WHERE availability = 'Bid' AND date >= ? AND date < DATE_ADD(?, INTERVAL 1 DAY)";
$stmtBid = $conn->prepare($sqlBid);
if (!$stmtBid) {
    echo json_encode(["error" => "Failed to prepare bid SQL statement."]);
    exit;
}
$stmtBid->bind_param("ss", $startDate, $endDate);
$stmtBid->execute();
$bidData = $stmtBid->get_result()->fetch_assoc();

// 3. Breakdown by attribute (colour, type, origin, shape)
$breakdown = [];

$attributes = [
    'colour' => 'gems',
    'type' => 'type gems',
    'origin' => 'origin gems',
    'shape' => 'shaped gems'
];

foreach ($attributes as $field => $labelSuffix) {
    $sqlAttr = "SELECT $field AS value, COUNT(*) AS count 
                FROM inventory 
                WHERE date >= ? AND date < DATE_ADD(?, INTERVAL 1 DAY)
                GROUP BY $field";
    
    $stmtAttr = $conn->prepare($sqlAttr);
    if (!$stmtAttr) {
        echo json_encode(["error" => "Failed to prepare breakdown for $field."]);
        exit;
    }

    $stmtAttr->bind_param("ss", $startDate, $endDate);
    $stmtAttr->execute();
    $resultAttr = $stmtAttr->get_result();

    // Add breakdown for each value (e.g., Pink gems, Round shaped gems)
    while ($row = $resultAttr->fetch_assoc()) {
        $value = ucfirst($row['value']);
        $label = "$value $labelSuffix";
        $breakdown[] = [
            'attribute' => $label,
            'count' => (int)$row['count']
        ];
    }

    $stmtAttr->close();
}

// Return result as JSON
echo json_encode([
    "totalItems" => (int)$inventoryData['totalItems'],
    "totalBidItems" => (int)$bidData['totalBidItems'],
    "totalInventoryValue" => round((float)$inventoryData['totalInventoryValue'], 2),
    "breakdown" => $breakdown,
    "dateRange" => "From $startDate to $endDate"
]);

// Close remaining statements and connection
$stmt->close();
$stmtBid->close();
$conn->close();
?>





