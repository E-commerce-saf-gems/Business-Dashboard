<?php
include('../../../database/db.php'); // Adjust based on your project structure

// Initialize response array
$response = [
    'totalSalesRevenue' => 0,
    'inventoryOpening' => 0,
    'purchases' => 0,
    'inventoryClosing' => 0,
    'salaries' => 0,
    'rentUtilities' => 0,
    'marketing' => 0,
    'adminExpenses' => 0,
    'otherIncome' => 0
];

// Define time period (modify as needed)
$startDate = $_GET['startDate'] ?? date('Y-m-01');  // Start of current month
$endDate = $_GET['endDate'] ?? date('Y-m-t'); // End of current month

// Fetch Total Sales Revenue from payments table
$salesQuery = "SELECT SUM(amount) AS totalSalesRevenue FROM payments WHERE date BETWEEN '$startDate' AND '$endDate'";
$result = $conn->query($salesQuery);
if ($row = $result->fetch_assoc()) {
    $response['totalSalesRevenue'] = floatval($row['totalSalesRevenue']);
}

// Fetch Inventory Opening Value (assume last month's closing inventory)
$openingQuery = "SELECT closing_value FROM inventory WHERE date < '$startDate' ORDER BY date DESC LIMIT 1";
$result = $conn->query($openingQuery);
if ($row = $result->fetch_assoc()) {
    $response['inventoryOpening'] = floatval($row['closing_value']);
}

// Fetch Purchases from purchases table
$purchasesQuery = "SELECT SUM(amount) AS totalPurchases FROM purchases WHERE date BETWEEN '$startDate' AND '$endDate'";
$result = $conn->query($purchasesQuery);
if ($row = $result->fetch_assoc()) {
    $response['purchases'] = floatval($row['totalPurchases']);
}

// Fetch Inventory Closing Value
$closingQuery = "SELECT closing_value FROM inventory WHERE date <= '$endDate' ORDER BY date DESC LIMIT 1";
$result = $conn->query($closingQuery);
if ($row = $result->fetch_assoc()) {
    $response['inventoryClosing'] = floatval($row['closing_value']);
}

// Fetch Expenses from expenses table
$expenseQuery = "SELECT type, SUM(amount) AS total FROM expenses WHERE date BETWEEN '$startDate' AND '$endDate' GROUP BY type";
$result = $conn->query($expenseQuery);
while ($row = $result->fetch_assoc()) {
    switch (strtolower($row['type'])) {
        case 'salaries':
            $response['salaries'] = floatval($row['total']);
            break;
        case 'rent':
        case 'utilities':
            $response['rentUtilities'] += floatval($row['total']);
            break;
        case 'marketing':
            $response['marketing'] = floatval($row['total']);
            break;
        case 'admin':
            $response['adminExpenses'] = floatval($row['total']);
            break;
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
