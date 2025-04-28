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

// SQL: Combine sales and purchases by month
$sql = "
SELECT 
    DATE_FORMAT(s.date, '%b') AS month,
    IFNULL(SUM(s.amountSettled), 0) AS cash_in,
    IFNULL((
        SELECT SUM(p.amountSettled)
        FROM purchases p
        WHERE MONTH(p.date) = MONTH(s.date) AND YEAR(p.date) = YEAR(s.date)
    ), 0) AS cash_out
FROM sales s
WHERE s.date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
GROUP BY YEAR(s.date), MONTH(s.date)
ORDER BY YEAR(s.date), MONTH(s.date)
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
    $labels[] = $row['month'];
    $cashIn[] = (float)$row['cash_in'];
    $cashOut[] = (float)$row['cash_out'];
}

echo json_encode(["labels" => $labels, "cashIn" => $cashIn, "cashOut" => $cashOut]);
$conn->close();
?>

