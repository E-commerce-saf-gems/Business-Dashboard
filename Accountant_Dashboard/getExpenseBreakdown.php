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

if (!isset($_GET['month'])) {
    echo json_encode(["labels" => [], "data" => []]);
    exit();
}

$monthYear = $_GET['month'];
list($year, $month) = explode("-", $monthYear);

$sql = "
    SELECT t.type, IFNULL(SUM(e.amount), 0) AS total
    FROM (
        SELECT DISTINCT type FROM expenses
    ) t
    LEFT JOIN expenses e ON t.type = e.type 
        AND MONTH(e.date) = ? 
        AND YEAR(e.date) = ?
    GROUP BY t.type
    ORDER BY t.type;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $month, $year);
$stmt->execute();
$result = $stmt->get_result();

$labels = [];
$data = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['type'];
    $data[] = (float)$row['total'];
}

echo json_encode([
    "labels" => $labels,
    "data" => $data
]);

$conn->close();
?>


