<?php
// Include database connection
include '../../../database/db.php';  // Adjust the path to match the location of db.php relative to this file

// Initialize variables for form inputs
$date = "";
$size = "";
$shape = "";
$color = "";
$type = "";
$weight = "";
$origin = "";
$amount = "";
$image = "";
$certificate = "";
$description = "";
$visibility = "";
$buyer_id = "";

$errorMessage = "";
$successMessage = "";

// Check if form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize the input
    $date = isset($_POST['date']) ? htmlspecialchars(trim($_POST['date'])) : '';
    $size = isset($_POST['size']) ? floatval($_POST['size']) : '';
    $shape = isset($_POST['shape']) ? htmlspecialchars(trim($_POST['shape'])) : '';
    $color = isset($_POST['colour'])? htmlspecialchars(trim($_POST['colour'])) : '';
    $type = isset($_POST['type'])? htmlspecialchars(trim($_POST['type'])) : '';
    $weight = isset($_POST['weight'])? floatval($_POST['weight']) : '';
    $origin = isset($_POST['origin'])? htmlspecialchars(trim($_POST['origin'])) : '';
    $amount = isset($_POST['amount'])? floatval($_POST['amount']) : '';
    $image = isset($_FILES['image'])? $_FILES['image']['name'] : '';
    $certificate = isset($_FILES['certificate'])? $_FILES['certificate']['name'] : '';
    $description = isset($_POST['description'])? htmlspecialchars(trim($_POST['description'])) : '';
    $visibility = isset($_POST['visibility'])? htmlspecialchars(trim($_POST['visibility'])) : '';
    $buyer_id = isset($_POST['buyer_id'])? intval($_POST['buyer_id']) : '';

    // Basic validation
    if (empty($date) || empty($size) || empty($shape) || empty($color) || empty($type) || empty($weight) || empty($origin) 
     || empty($amount) || empty($image) || empty($certificate)  || empty($description) || empty($visibility)) {
        $errorMessage = "All information are required.";
    } else {
        // Construct the SQL query
        $sql = "INSERT INTO inventory (date, size, shape, colour, type, weight, origin, amount, image,  certificate, description, visibility) 
                VALUES ('$date', '$size', '$shape', '$color', '$type', '$weight', '$origin', '$amount', '$image', '$certificate', '$description', '$visibility')";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            $successMessage = "Add new gem into the inventory successfully!";
        } else {
            $errorMessage = "Error: " . $conn->error;
        }
    }
}

// Close the database connection
$conn->close();
?>
