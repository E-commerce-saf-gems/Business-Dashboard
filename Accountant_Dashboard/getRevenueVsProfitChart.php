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

// Combine all unique months from sales, purchases, and expenses within the past 12 months
$sql = "
    SELECT 
        DATE_FORMAT(date_group.date, '%b') AS month,
        IFNULL((
            SELECT SUM(s.amountSettled)
            FROM sales s
            WHERE MONTH(s.date) = MONTH(date_group.date) AND YEAR(s.date) = YEAR(date_group.date)
        ), 0) AS revenue,
        IFNULL((
            SELECT SUM(p.amountSettled)
            FROM purchases p
            WHERE MONTH(p.date) = MONTH(date_group.date) AND YEAR(p.date) = YEAR(date_group.date)
        ), 0) AS purchases,
        IFNULL((
            SELECT SUM(e.amount)
            FROM expenses e
            WHERE MONTH(e.date) = MONTH(date_group.date) AND YEAR(e.date) = YEAR(date_group.date)
        ), 0) AS expenses
    FROM (
        SELECT DISTINCT DATE_FORMAT(date, '%Y-%m-01') AS date
        FROM (
            SELECT date FROM sales WHERE date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            UNION
            SELECT date FROM purchases WHERE date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
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

    echo json_encode(["labels" => $labels, "revenue" => $revenue, "profit" => $profit]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Query failed: " . $conn->error]);
}

$conn->close();
?>


