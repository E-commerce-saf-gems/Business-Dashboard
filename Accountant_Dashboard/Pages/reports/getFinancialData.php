<?php
include('../../../database/db.php'); // Adjust based on your project structure

/*// Ensure startDate and endDate are correctly received from the request
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : null;
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : null;// Initialize response array

// Fallback to defaults if not provided
if (!$startDate || !$endDate) {
    die(json_encode(["error" => "Missing startDate or endDate"]));
}

// Debugging: Log startDate and endDate to check if they are received correctly
error_log("Received startDate: " . $startDate);
error_log("Received endDate: " . $endDate);

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
echo json_encode($response);*/
?>

<?php
header("Content-Type: application/json");

if (isset($_GET['startDate']) && isset($_GET['endDate'])) {
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];

    // Validate date input
    if (!validateDate($startDate) || !validateDate($endDate)) {
        echo json_encode(["error" => "Invalid date format"]);
        exit;
    }

    // Sample data
    $data = [
        "totalSalesRevenue" => 25000.00,
        "otherIncome" => 1500.00,
        "inventoryOpening" => 5000.00,
        "purchases" => 8000.00,
        "inventoryClosing" => 6000.00,
        "grossProfit" => 19000.00,
        "salaries" => 3000.00,
        "rentUtilities" => 2000.00,
        "marketing" => 500.00,
        "adminExpenses" => 1000.00,
        "totalExpenses" => 6500.00,
        "netOperatingProfit" => 12500.00,
        "netProfit" => 14000.00
    ];

    echo json_encode($data);
} else {
    echo json_encode(["error" => "Missing parameters"]);
}

function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}
?>



