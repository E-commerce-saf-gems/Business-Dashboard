<?php
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "safgems";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to get the last 4 months of registered users
    $stmt = $pdo->prepare("
        SELECT DATE_FORMAT(date, '%b') AS month, COUNT(*) AS count
        FROM customer
        WHERE date >= DATE_SUB(CURDATE(), INTERVAL 4 MONTH)
        GROUP BY month
        ORDER BY date ASC
    ");
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>