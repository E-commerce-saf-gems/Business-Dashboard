<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "safgems";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed."]));
}

header('Content-Type: application/json');

// Combine all unique months from transactions, orders, payments, and expenses within the past 12 months
$sql = "
    SELECT 
        DATE_FORMAT(date_group.date, '%b') AS month,
        -- Revenue: sum of transactions.amount + orders.total_amount
        IFNULL((
            SELECT SUM(s.amount)
            FROM transactions s
            WHERE MONTH(s.date) = MONTH(date_group.date) AND YEAR(s.date) = YEAR(date_group.date)
        ), 0) + IFNULL((
            SELECT SUM(o.total_amount)
            FROM orders o
            WHERE MONTH(o.order_date) = MONTH(date_group.date) AND YEAR(o.order_date) = YEAR(date_group.date)
        ), 0) AS revenue,
        
        -- Purchases
        IFNULL((
            SELECT SUM(p.amount)
            FROM payments p
            WHERE MONTH(p.date) = MONTH(date_group.date) AND YEAR(p.date) = YEAR(date_group.date)
        ), 0) AS purchases,
        
        -- Expenses
        IFNULL((
            SELECT SUM(e.amount)
            FROM expenses e
            WHERE MONTH(e.date) = MONTH(date_group.date) AND YEAR(e.date) = YEAR(date_group.date)
        ), 0) AS expenses

    FROM (
        SELECT DISTINCT DATE_FORMAT(date, '%Y-%m-01') AS date
        FROM (
            SELECT date FROM transactions WHERE date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            UNION
            SELECT order_date AS date FROM orders WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            UNION
            SELECT date FROM payments WHERE date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            UNION
            SELECT date FROM expenses WHERE date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
        ) AS all_dates
    ) AS date_group
    ORDER BY date_group.date
";

$result = $conn->query($sql);
$labels = [];
$revenue = [];
$profit = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['month'];
        $rev = (float)$row['revenue'];
        $purch = (float)$row['purchases'];
        $exp = (float)$row['expenses'];
        $revenue[] = $rev;
        $profit[] = $rev - ($purch + $exp);
    }

    echo json_encode([
        "labels" => $labels,
        "revenue" => $revenue,
        "profit" => $profit
    ]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Query failed: " . $conn->error]);
}

$conn->close();
?>
