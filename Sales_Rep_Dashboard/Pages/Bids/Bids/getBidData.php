<?php
// Database connection
$host = 'localhost';
$db = 'test';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL to fetch counts of different gem attributes (color, shape, type, origin)
    // Count gems by color
    $colorQuery = "SELECT color, COUNT(*) as count FROM bidstones GROUP BY color";
    $shapeQuery = "SELECT shape, COUNT(*) as count FROM bidstones GROUP BY shape";
    $typeQuery = "SELECT type, COUNT(*) as count FROM bidstones GROUP BY type";
    $originQuery = "SELECT origin, COUNT(*) as count FROM bidstones GROUP BY origin";

    // Execute the queries
    $colorStmt = $pdo->query($colorQuery);
    $shapeStmt = $pdo->query($shapeQuery);
    $typeStmt = $pdo->query($typeQuery);
    $originStmt = $pdo->query($originQuery);

    // Fetch the results
    $colors = [];
    while ($row = $colorStmt->fetch(PDO::FETCH_ASSOC)) {
        $colors[$row['color']] = $row['count'];
    }

    $shapes = [];
    while ($row = $shapeStmt->fetch(PDO::FETCH_ASSOC)) {
        $shapes[$row['shape']] = $row['count'];
    }

    $types = [];
    while ($row = $typeStmt->fetch(PDO::FETCH_ASSOC)) {
        $types[$row['type']] = $row['count'];
    }

    $origins = [];
    while ($row = $originStmt->fetch(PDO::FETCH_ASSOC)) {
        $origins[$row['origin']] = $row['count'];
    }

    // Prepare the data to be returned as JSON
    $chartData = [
        'color' => $colors,
        'shape' => $shapes,
        'type' => $types,
        'origin' => $origins
    ];

    // Return the chart data as JSON
    echo json_encode($chartData);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
