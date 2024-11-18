<?php
// Include database connection
include '../../../database/db.php';

// Initialize variables for form inputs
$size = $shape = $color = $type = $origin = $description = $visibility = $availability="";
$weight = $amount = $buyer_id = 0;
$image = $certificate = "";
$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $size = floatval($_POST['size']);
    $shape = htmlspecialchars(trim($_POST['shape']));
    $color = htmlspecialchars(trim($_POST['colour']));
    $type = htmlspecialchars(trim($_POST['type']));
    $weight = floatval($_POST['weight']);
    $origin = htmlspecialchars(trim($_POST['origin']));
    $amount = floatval($_POST['amount']);
    $description = htmlspecialchars(trim($_POST['description']));
    $visibility = htmlspecialchars(trim($_POST['visibility']));
    $availability = htmlspecialchars(trim($_POST['availability']));
    $buyer_id = intval($_POST['buyer_id']);

    // Handle file uploads
    if (!empty($_FILES['image']['name']) && !empty($_FILES['certificate']['name'])) {
        $image = basename($_FILES["image"]["name"]);
        $certificate = basename($_FILES["certificate"]["name"]);

        // Define upload directory and file paths
        $targetDir = "../../../uploads/";
        $imagePath = $targetDir . $image;
        $certificatePath = $targetDir . $certificate;

        // Check if the directory exists and create it if necessary
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Move the uploaded files to the specified target directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath) &&
            move_uploaded_file($_FILES["certificate"]["tmp_name"], $certificatePath)) {

            // Prepare SQL query
            $sql = $sql = "INSERT INTO inventory (size, shape, colour, type, weight, origin, amount, image, certificate, description, visibility, availability, buyer_id)
            VALUES ('$size', '$shape', '$color', '$type', '$weight', '$origin', '$amount', '$image', '$certificate', '$description', '$visibility', '$availability', '$buyer_id')";
    

            // Execute query
            if ($conn->query($sql) === TRUE) {
                // Redirect to inventory.php upon success
                header("Location: ../../../Sales_Rep_Dashboard/Pages/Inventory/inventory.php");
                exit();  // Ensure no further code is executed after redirection
            } else {
                // Print error if the SQL query fails
                $errorMessage = "Database error: " . $conn->error;
            }
        } else {
            $errorMessage = "File upload failed. Please check file permissions.";
        }
    } else {
        $errorMessage = "Please upload both image and certificate files.";
    }
}

// Close the database connection
$conn->close();
?>
