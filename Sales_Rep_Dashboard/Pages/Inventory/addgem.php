<?php
// Include database connection
include '../../../database/db.php';

// Initialize variables for form inputs
$shape = $color = $type = $origin = $description = $visibility = $availability = "";
$weight = $amount = $name = 0;
$image = $certificate = "";
$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $shape = htmlspecialchars(trim($_POST['shape']));
    $color = htmlspecialchars(trim($_POST['colour']));
    $type = htmlspecialchars(trim($_POST['type']));
    $weight = floatval($_POST['weight']);
    $origin = htmlspecialchars(trim($_POST['origin']));
    $amount = floatval($_POST['amount']);
    $description = htmlspecialchars(trim($_POST['description']));
    $visibility = htmlspecialchars(trim($_POST['visibility']));
    $buyer_id = intval($_POST['buyer_id']);
    $amountSettled = floatval($_POST['amountSettled']);

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

            // Prepare SQL query for inventory table
            $sql = "INSERT INTO inventory (shape, colour, type, size, origin, amount, image, certificate, description, visibility, buyer_id)
                    VALUES ('$shape', '$color', '$type', '$weight', '$origin', '$amount', '$image', '$certificate', '$description', '$visibility', '$buyer_id')";

            // Execute inventory query
            if ($conn->query($sql) === TRUE) {
                // Get the last inserted inventory ID
                $stone_id = $conn->insert_id;

                // Prepare SQL query for purchases table
                $purchaseSql = "INSERT INTO purchases (stone_id, buyer_id, amount, amountSettled)
                                VALUES ('$stone_id', '$buyer_id', '$amount', '$amountSettled')";

                // Execute purchases query
                if ($conn->query($purchaseSql) === TRUE) {
                    // Redirect to inventory.php upon success
                    header("Location: ../../../Sales_Rep_Dashboard/Pages/Inventory/inventory.php");
                    exit(); // Ensure no further code is executed after redirection
                } else {
                    // Print error if the purchases SQL query fails
                    $errorMessage = "Error adding purchase record: " . $conn->error;
                }
            } else {
                // Print error if the inventory SQL query fails
                $errorMessage = "Error adding inventory record: " . $conn->error;
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
