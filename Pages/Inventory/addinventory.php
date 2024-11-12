<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
require '../../database/db.php'; // Adjust this path based on your folder structure

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and validate form data
    $date = isset($_POST['date']) ? $_POST['date'] : null;
    $size = isset($_POST['size']) ? $_POST['size'] : null;
    $shape = isset($_POST['shape']) ? $_POST['shape'] : null;
    $color = isset($_POST['color']) ? $_POST['color'] : null;
    $type = isset($_POST['type']) ? $_POST['type'] : null;
    $origin = isset($_POST['origin']) ? $_POST['origin'] : null;
    $amount = isset($_POST['amount']) ? intval($_POST['amount']) : null;
    $certificate = isset($_POST['certificate']) ? $_POST['certificate'] : null;

    // Check if any required field is missing
    if (!$date || !$size || !$shape || !$color || !$type || !$origin || !$amount || !$certificate) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    // Prepare SQL query to insert data into the inventory table
    $sql = "INSERT INTO inventory (date, size, shape, color, type, origin, amount, certificate) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Check if statement preparation was successful
    if ($stmt === false) {
        echo json_encode(["status" => "error", "message" => "Failed to prepare statement: " . $conn->error]);
        exit;
    }

    // Bind the form values to the SQL query (adjust data types to match database schema)
    $stmt->bind_param("sssssssi", $date, $size, $shape, $color, $type, $origin, $certificate, $amount);

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Inventory added successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to execute statement: " . $stmt->error]);
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
