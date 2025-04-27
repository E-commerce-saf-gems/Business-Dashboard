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

// Combined sales from transactions and orders for last 12 months
$sql = "
    SELECT monthLabel, SUM(total) AS total
    FROM (
        SELECT DATE_FORMAT(date, '%b') AS monthLabel, SUM(amount) AS total
        FROM transactions
        WHERE date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
        GROUP BY YEAR(date), MONTH(date)
        
        UNION ALL
        
        SELECT DATE_FORMAT(order_date, '%b') AS monthLabel, SUM(total_amount) AS total
        FROM orders
        WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
        GROUP BY YEAR(order_date), MONTH(order_date)
    ) AS combined
    GROUP BY monthLabel
    ORDER BY STR_TO_DATE(monthLabel, '%b') ASC
";

$result = $conn->query($sql);
$labels = [];
$sales = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['monthLabel'];
    $sales[] = (float)$row['total'];
}

echo json_encode(["labels" => $labels, "sales" => $sales]);
$conn->close();
?>
