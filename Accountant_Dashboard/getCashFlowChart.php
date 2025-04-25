<?php
// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "safgems";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed."]));
}

header('Content-Type: application/json');

// Unified months from sales, payments, and orders (last 6 months)
$sql = "
SELECT 
    DATE_FORMAT(activity.month, '%b') AS monthLabel,
    IFNULL(s.cash_in, 0) + IFNULL(o.cash_in, 0) AS cash_in,
    IFNULL(p.cash_out, 0) AS cash_out
FROM (
    SELECT DISTINCT DATE_FORMAT(date, '%Y-%m-01') AS month
    FROM transactions
    WHERE date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)

    UNION

    SELECT DATE_FORMAT(order_date, '%Y-%m-01') AS month
    FROM orders
    WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    
    UNION
    
    SELECT DISTINCT DATE_FORMAT(date, '%Y-%m-01')
    FROM payments
    WHERE date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
) activity

LEFT JOIN (
    SELECT DATE_FORMAT(date, '%Y-%m-01') AS month, SUM(amount) AS cash_in
    FROM transactions
    WHERE date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY month
) s ON s.month = activity.month

LEFT JOIN (
    SELECT DATE_FORMAT(order_date, '%Y-%m-01') AS month, SUM(total_amount) AS cash_in
    FROM orders
    WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY month
) o ON o.month = activity.month

LEFT JOIN (
    SELECT DATE_FORMAT(date, '%Y-%m-01') AS month, SUM(amount) AS cash_out
    FROM payments
    WHERE date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY month
) p ON p.month = activity.month

ORDER BY activity.month ASC
";

$result = $conn->query($sql);
if (!$result) {
    http_response_code(500);
    echo json_encode(["error" => "Query failed: " . $conn->error]);
    exit;
}

$labels = [];
$cashIn = [];
$cashOut = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['monthLabel'];
    $cashIn[] = (float)$row['cash_in'];
    $cashOut[] = (float)$row['cash_out'];
}

echo json_encode([
    "labels" => $labels,
    "cashIn" => $cashIn,
    "cashOut" => $cashOut
]);

$conn->close();
?>
