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
    //php data object (PDO) for database connection
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    //error handling mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to get gender-wise totals
    $stmt = $pdo->prepare("
        SELECT gender, COUNT(*) AS count
        FROM customer
        GROUP BY gender
    ");
    $stmt->execute();

    //takes all the rows returned and puts them in an associative array
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>