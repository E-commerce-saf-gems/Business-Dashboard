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

// Make sure the condition is grouped properly in each subquery
$query = "
    SELECT 
        (SELECT IFNULL(SUM(amount), 0) FROM expenses WHERE ($dateCondition)) AS totalExpenses,
        (SELECT IFNULL(SUM(amount), 0) FROM expenses WHERE ($dateCondition) AND status = 'Paid') AS totalPaidExpenses,
        (SELECT IFNULL(SUM(amount), 0) FROM expenses WHERE ($dateCondition) AND status = 'Pending') AS totalPendingExpenses,
        (
            SELECT type 
            FROM expenses 
            WHERE ($dateCondition) 
            GROUP BY type 
            ORDER BY SUM(amount) DESC 
            LIMIT 1
        ) AS maxExpenseCategory
";

$result = $conn->query($query);

// Error check
if (!$result) {
    echo json_encode(["error" => "Query failed", "details" => $conn->error]);
    exit();
}

$row = $result->fetch_assoc();

echo json_encode([
    "totalExpenses" => (float)($row['totalExpenses'] ?? 0),
    "totalPaidExpenses" => (float)($row['totalPaidExpenses'] ?? 0),
    "totalPendingExpenses" => (float)($row['totalPendingExpenses'] ?? 0),
    "maxExpenseCategory" => $row['maxExpenseCategory'] ?? "None"
]);

$conn->close();
?>

