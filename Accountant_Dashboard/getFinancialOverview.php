<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "safgems";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed"]);
    exit();
}

$filter = $_GET['filter'] ?? 'monthly';
$dateCondition = "";

switch ($filter) {
    case 'yearly':
        $dateCondition = "date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
        break;
    case 'quarterly':
        $dateCondition = "date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
        break;
    case 'monthly':
    default:
        $dateCondition = "MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE())";
        break;
}

// Total Sales from transactions
$salesQuery = "SELECT IFNULL(SUM(amount), 0) AS totalSales, IFNULL(MAX(amount), 0) AS outstandingPayment FROM transactions WHERE $dateCondition";
$salesResult = $conn->query($salesQuery);
$salesRow = $salesResult->fetch_assoc();

// Total Purchases from payments
$purchasesQuery = "SELECT IFNULL(SUM(amount), 0) AS totalPurchases FROM payments WHERE $dateCondition";
$purchasesResult = $conn->query($purchasesQuery);
$purchasesRow = $purchasesResult->fetch_assoc();

// Total Expenses (only Paid)
$expensesQuery = "SELECT IFNULL(SUM(amount), 0) AS totalExpenses FROM expenses WHERE $dateCondition AND status = 'Paid'";
$expensesResult = $conn->query($expensesQuery);
$expensesRow = $expensesResult->fetch_assoc();

echo json_encode([
    "totalSales" => (float)$salesRow['totalSales'],
    "totalPurchases" => (float)$purchasesRow['totalPurchases'],
    "totalExpenses" => (float)$expensesRow['totalExpenses'],
    "outstandingPayment" => (float)$salesRow['outstandingPayment']
]);

$conn->close();
?>


