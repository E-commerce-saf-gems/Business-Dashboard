<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../../database/db.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'fetchGems') {
    $query = "SELECT stone_id, type, availability, visibility FROM inventory";
    $result = $conn->query($query);
    $gems = [];

    while ($row = $result->fetch_assoc()) {
        $gems[] = $row;
    }

    echo json_encode($gems);
    exit;
}

if ($action === 'fetchExpenses' && isset($_GET['stone_id'])) {
    $stoneId = $_GET['stone_id'];

    // Fetch gem details
    $stmt = $conn->prepare("SELECT stone_id, type, availability, visibility FROM inventory WHERE stone_id = ?");
    $stmt->bind_param("i", $stoneId);
    $stmt->execute();
    $gemResult = $stmt->get_result();
    $gem = $gemResult->fetch_assoc();
    $stmt->close();

    if (!$gem) {
        echo json_encode(['gem' => null, 'expenses' => []]);
        exit;
    }

    // Fetch and group expense values by type
    $stmt = $conn->prepare("SELECT type, SUM(amount) as total FROM expenses WHERE stone_id = ? GROUP BY type");
    $stmt->bind_param("i", $stoneId);
    $stmt->execute();
    $expenseResult = $stmt->get_result();
    $expenses = [];

    while ($row = $expenseResult->fetch_assoc()) {
        $expenses[$row['type']] = (float)$row['total'];
    }
    $stmt->close();

    echo json_encode(['gem' => $gem, 'expenses' => $expenses]);
    exit;
}

// Invalid or missing action
http_response_code(400);
echo json_encode(["error" => "Invalid request"]);

?>


