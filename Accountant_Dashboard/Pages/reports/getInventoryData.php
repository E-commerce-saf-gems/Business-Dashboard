<?php
require_once('../../../database/db.php');

$startDate = $_GET['startDate'] ?? '2024-01-01';
$endDate = $_GET['endDate'] ?? '2024-01-31';

$response = [];

// Fetch Total Sales Revenue
$salesQuery = "SELECT SUM(amount) AS totalSalesRevenue FROM payments WHERE date BETWEEN ? AND ?";
$stmt = $conn->prepare($salesQuery);
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$salesResult = $stmt->get_result()->fetch_assoc();
$response['totalSalesRevenue'] = $salesResult['totalSalesRevenue'] ?? 0;

// Fetch Total Purchases
$purchasesQuery = "SELECT SUM(amount) AS purchases FROM purchases WHERE date BETWEEN ? AND ?";
$stmt = $conn->prepare($purchasesQuery);
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$purchasesResult = $stmt->get_result()->fetch_assoc();
$response['purchases'] = $purchasesResult['purchases'] ?? 0;

// Fetch Total Expenses
$expensesQuery = "SELECT SUM(amount) AS totalExpenses FROM expenses WHERE type NOT IN ('Purchase') AND date BETWEEN ? AND ?";
$stmt = $conn->prepare($expensesQuery);
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$expensesResult = $stmt->get_result()->fetch_assoc();
$response['totalExpenses'] = $expensesResult['totalExpenses'] ?? 0;

// Fetch Inventory Values
$inventoryQuery = "SELECT 
    SUM(quantity) AS currentQuantity, 
    SUM(value) AS totalInventoryValue, 
    AVG(value) AS valuePerGemType 
    FROM inventory";
$inventoryResult = $conn->query($inventoryQuery)->fetch_assoc();
$response['currentQuantity'] = $inventoryResult['currentQuantity'] ?? 0;
$response['totalInventoryValue'] = $inventoryResult['totalInventoryValue'] ?? 0;
$response['valuePerGemType'] = $inventoryResult['valuePerGemType'] ?? 0;

// Fetch Items Sold
$itemsSoldQuery = "SELECT COUNT(*) AS itemsSold FROM sales WHERE date BETWEEN ? AND ?";
$stmt = $conn->prepare($itemsSoldQuery);
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$itemsSoldResult = $stmt->get_result()->fetch_assoc();
$response['itemsSold'] = $itemsSoldResult['itemsSold'] ?? 0;

// Fetch Inventory Aging
$agingQuery = "SELECT 
    SUM(CASE WHEN DATEDIFF(NOW(), added_date) > 30 THEN 1 ELSE 0 END) * 100 / COUNT(*) AS percentOlder30,
    SUM(CASE WHEN DATEDIFF(NOW(), added_date) > 60 THEN 1 ELSE 0 END) * 100 / COUNT(*) AS percentOlder60,
    SUM(CASE WHEN DATEDIFF(NOW(), added_date) > 90 THEN 1 ELSE 0 END) * 100 / COUNT(*) AS percentOlder90,
    AVG(DATEDIFF(NOW(), added_date)) AS avgDaysInventory
    FROM inventory";
$agingResult = $conn->query($agingQuery)->fetch_assoc();
$response['percentOlder30'] = $agingResult['percentOlder30'] ?? 0;
$response['percentOlder60'] = $agingResult['percentOlder60'] ?? 0;
$response['percentOlder90'] = $agingResult['percentOlder90'] ?? 0;
$response['avgDaysInventory'] = $agingResult['avgDaysInventory'] ?? 0;

// Fetch New Acquisitions
$newAcquisitionsQuery = "SELECT COUNT(*) AS newAcquisitions FROM inventory WHERE added_date BETWEEN ? AND ?";
$stmt = $conn->prepare($newAcquisitionsQuery);
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$newAcquisitionsResult = $stmt->get_result()->fetch_assoc();
$response['newAcquisitions'] = $newAcquisitionsResult['newAcquisitions'] ?? 0;

// Fetch Gem Type Breakdown
$gemTypeQuery = "SELECT type, COUNT(*) AS count FROM inventory GROUP BY type";
$gemTypeResult = $conn->query($gemTypeQuery);
$gemTypeBreakdown = [];
while ($row = $gemTypeResult->fetch_assoc()) {
    $gemTypeBreakdown[$row['type']] = $row['count'];
}
$response['gemTypeBreakdown'] = json_encode($gemTypeBreakdown);

// Return JSON Response
header('Content-Type: application/json');
echo json_encode($response);
?>
