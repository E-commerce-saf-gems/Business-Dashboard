<?php
header('Content-Type: application/json');

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../database/db.php';

if (!isset($conn) || !$conn) {
    echo json_encode(["error" => "Database connection failed."]);
    exit;
}

// Accept both JSON and GET for testing/flexibility
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $startDate = $data->startDate ?? null;
    $endDate = $data->endDate ?? null;
} else {
    $startDate = $_GET['from'] ?? null;
    $endDate = $_GET['to'] ?? null;
}

// Validate date input
if (!$startDate || !$endDate) {
    echo json_encode(["error" => "Invalid date range."]);
    exit;
}

// SQL combining both sales and orders
$sql = "
    SELECT total AS total, amountSettled FROM sales 
    WHERE date >= ? AND date < DATE_ADD(?, INTERVAL 1 DAY)
    UNION ALL
    SELECT total_amount AS total, total_amount AS amountSettled FROM orders 
    WHERE order_date >= ? AND order_date < DATE_ADD(?, INTERVAL 1 DAY)
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["error" => "Failed to prepare SQL statement."]);
    exit;
}

// Bind all 4 placeholders
$stmt->bind_param("ssss", $startDate, $endDate, $startDate, $endDate);
$stmt->execute();
$result = $stmt->get_result();

// Process result
$totalSales = 0;
$totalSettled = 0;
$itemsSold = 0;

while ($row = $result->fetch_assoc()) {
    $total = isset($row['total']) ? (float) $row['total'] : 0;
    $settled = isset($row['amountSettled']) ? (float) $row['amountSettled'] : 0;

    $totalSales += $total;
    $totalSettled += $settled;
    $itemsSold++;
}

$totalRemaining = $totalSales - $totalSettled;

// Return JSON
echo json_encode([
    "totalSalesAmount" => round($totalSales, 2),
    "settledAmount" => round($totalSettled, 2),
    "remainingAmount" => round($totalRemaining, 2),
    "itemsSold" => $itemsSold
]);

$stmt->close();
$conn->close();
