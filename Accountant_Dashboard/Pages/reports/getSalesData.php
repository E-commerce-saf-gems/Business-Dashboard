<?php
header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../database/db.php';

if (!isset($conn) || !$conn) {
    echo json_encode(["error" => "Database connection failed."]);
    exit;
}

// Accept both JSON and GET for debugging
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $startDate = $data->startDate ?? null;
    $endDate = $data->endDate ?? null;
} else {
    $startDate = $_GET['from'] ?? null;
    $endDate = $_GET['to'] ?? null;
}

// Validate dates
if (!$startDate || !$endDate) {
    echo json_encode(["error" => "Invalid date range."]);
    exit;
}

// Query DB
$sql = "SELECT total, amountSettled FROM sales WHERE date >= ? AND date < DATE_ADD(?, INTERVAL 1 DAY)";  
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["error" => "Failed to prepare SQL statement."]);
    exit;
}

$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$result = $stmt->get_result();

// Calculate totals
$totalSales = 0;
$totalSettled = 0;
$itemsSold = 0;

while ($row = $result->fetch_assoc()) {
    $total = isset($row['total']) ? (float)$row['total'] : 0;
    $settled = isset($row['amountSettled']) ? (float)$row['amountSettled'] : 0;

    $totalSales += $total;
    $totalSettled += $settled;
    $itemsSold++;
}

$totalRemaining = $totalSales - $totalSettled;

// Return all data
echo json_encode([
    "totalSalesAmount" => round($totalSales, 2),
    "totalSettledAmount" => round($totalSettled, 2),
    "totalRemainingAmount" => round($totalRemaining, 2),
    "itemsSold" => $itemsSold
]);

$stmt->close();
$conn->close();



