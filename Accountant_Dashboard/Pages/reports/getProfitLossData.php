<?php
header('Content-Type: application/json');
require_once '../../../database/db.php'; // update as per your path

$data = json_decode(file_get_contents("php://input"));
$startDate = $data->startDate;
$endDate = $data->endDate;

$response = [
    'totalSalesRevenue' => 0,
    'purchases' => 0,
    'salaries' => 0,
    'rentUtilities' => 0,
    'marketing' => 0,
    'adminExpenses' => 0
];

// Sales Revenue
$stmt = $conn->prepare("SELECT SUM(amount) FROM transactions WHERE date >= ? AND date < DATE_ADD(?, INTERVAL 1 DAY)");
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$stmt->bind_result($sales);
$stmt->fetch();
$response['totalSalesRevenue'] = $sales ?: 0;
$stmt->close();

// Purchases
$stmt = $conn->prepare("SELECT SUM(amount) FROM payments WHERE date >= ? AND date < DATE_ADD(?, INTERVAL 1 DAY)");
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$stmt->bind_result($purchases);
$stmt->fetch();
$response['purchases'] = $purchases ?: 0;
$stmt->close();

// Expenses by type
$types = ['Cutting and Polishing', 'Certifications', 'Marketing', 'Logistics', 'Other'];
foreach ($types as $type) {
    $stmt = $conn->prepare("SELECT SUM(amount) FROM expenses WHERE type = ? AND status='Paid' AND date >= ? AND date < DATE_ADD(?, INTERVAL 1 DAY)");
    $stmt->bind_param("sss", $type, $startDate, $endDate);
    $stmt->execute();
    $stmt->bind_result($amount);
    $stmt->fetch();
    $key = strtolower(str_replace([' ', '&'], ['', ''], $type)); // e.g., salaries
    $response[$key] = $amount ?: 0;
    $stmt->close();
}

echo json_encode($response);
$conn->close();
?>
