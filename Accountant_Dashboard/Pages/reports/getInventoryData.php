<?php
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../database/db.php';

if (!isset($conn) || !$conn) {
    echo json_encode(["error" => "Database connection failed."]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $startDate = $data->startDate ?? null;
    $endDate = $data->endDate ?? null;
} else {
    $startDate = $_GET['from'] ?? null;
    $endDate = $_GET['to'] ?? null;
}

if (!$startDate || !$endDate) {
    echo json_encode(["error" => "Invalid date range."]);
    exit;
}

// 1. Total items and total value
$sql = "SELECT COUNT(*) AS totalItems, SUM(amount) AS totalInventoryValue FROM inventory WHERE date >= ? AND date < DATE_ADD(?, INTERVAL 1 DAY)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["error" => "Failed to prepare SQL statement."]);
    exit;
}
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$inventoryData = $stmt->get_result()->fetch_assoc();

// 2. Total Bid items
$sqlBid = "SELECT COUNT(*) AS totalBidItems FROM inventory WHERE availability = 'Bid' AND date >= ? AND date < DATE_ADD(?, INTERVAL 1 DAY)";
$stmtBid = $conn->prepare($sqlBid);
if (!$stmtBid) {
    echo json_encode(["error" => "Failed to prepare bid SQL statement."]);
    exit;
}
$stmtBid->bind_param("ss", $startDate, $endDate);
$stmtBid->execute();
$bidData = $stmtBid->get_result()->fetch_assoc();

// 3. Breakdown by colour, type, origin, shape
$breakdown = [];

$attributes = [
    'colour' => 'gems',
    'type' => 'type gems',
    'origin' => 'origin gems',
    'shape' => 'shaped gems'
];

foreach ($attributes as $field => $suffix) {
    $sqlAttr = "SELECT $field AS value, COUNT(*) AS count FROM inventory WHERE date >= ? AND date < DATE_ADD(?, INTERVAL 1 DAY) GROUP BY $field";
    $stmtAttr = $conn->prepare($sqlAttr);
    if (!$stmtAttr) {
        echo json_encode(["error" => "Failed to prepare breakdown for $field."]);
        exit;
    }

    $stmtAttr->bind_param("ss", $startDate, $endDate);
    $stmtAttr->execute();
    $resultAttr = $stmtAttr->get_result();

    while ($row = $resultAttr->fetch_assoc()) {
        $value = ucfirst($row['value']);
        $label = "$value $suffix";
        $breakdown[] = [
            'attribute' => $label,
            'count' => (int)$row['count']
        ];
    }

    $stmtAttr->close();
}

// Output result
echo json_encode([
    "totalItems" => (int)$inventoryData['totalItems'],
    "totalBidItems" => (int)$bidData['totalBidItems'],
    "totalInventoryValue" => round((float)$inventoryData['totalInventoryValue'], 2),
    "breakdown" => $breakdown,
    "dateRange" => "From $startDate to $endDate"
]);

$stmt->close();
$stmtBid->close();
$conn->close();




