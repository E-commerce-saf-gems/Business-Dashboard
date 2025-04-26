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

// Determine filter
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

$query = "
    SELECT 
        (SELECT IFNULL(SUM(total), 0) FROM sales WHERE ($dateCondition)) AS totalSales,
        (SELECT IFNULL(SUM(amountSettled), 0) FROM sales WHERE ($dateCondition)) AS totalSettledSales,
        (SELECT IFNULL(SUM(total - amountSettled), 0) FROM sales WHERE ($dateCondition)) AS totalRemainingSales
";

$result = $conn->query($query);

// Error check
if (!$result) {
    echo json_encode(["error" => "Query failed", "details" => $conn->error]);
    exit();
}

$row = $result->fetch_assoc();

echo json_encode([
    "totalSales" => (float)($row['totalSales'] ?? 0),
    "totalSettledSales" => (float)($row['totalSettledSales'] ?? 0),
    "totalRemainingSales" => (float)($row['totalRemainingSales'] ?? 0)
]);

$conn->close();

