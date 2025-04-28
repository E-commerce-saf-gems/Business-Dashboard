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

// Get last 12 months of sales
$sql = "
    SELECT DATE_FORMAT(date, '%b') AS month, SUM(amountSettled) AS total
    FROM sales
    WHERE date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY YEAR(date), MONTH(date)
    ORDER BY YEAR(date), MONTH(date)
";

$result = $conn->query($sql);
$labels = [];
$sales = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['month'];
    $sales[] = (float)$row['total'];
}

echo json_encode(["labels" => $labels, "sales" => $sales]);
$conn->close();
?>

