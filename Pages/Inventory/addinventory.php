<?php
// Include the database connection file
require 'db.php'; // Ensure this path is correct based on your folder structure

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $date = $_POST['date'];
    $size = $_POST['size'];
    $shape = $_POST['shape'];
    $color = $_POST['color'];
    $type = $_POST['type'];
    $origin = $_POST['origin'];
    $amount = $_POST['amount'];
    $certificate = $_POST['certificate'];

    // SQL query to insert data into the inventory table
    $sql = "INSERT INTO inventory (date, size, shape, color, type, origin, amount, certificate) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Check if statement preparation was successful
    if ($stmt === false) {
        echo json_encode(["status" => "error", "message" => "Failed to prepare statement: " . $conn->error]);
        exit;
    }

    // Bind the form values to the SQL query
    $stmt->bind_param("ssssssss", $date, $size, $shape, $color, $type, $origin, $amount, $certificate);

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
