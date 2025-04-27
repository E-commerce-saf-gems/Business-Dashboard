<?php
include '../../../database/db.php';

$shape = $color = $type = $origin = $description = $visibility = $availability = "";
$weight = $amount = $name = 0;
$image = $certificate = "";
$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    $amountSettled = floatval($_POST['amountSettled']);

    if (!empty($_FILES['image']['name']) && !empty($_FILES['certificate']['name'])) {
        $image = basename($_FILES["image"]["name"]);
        $certificate = basename($_FILES["certificate"]["name"]);

        $targetDir = "../../../uploads/";
        $imagePath = $targetDir . $image;
        $certificatePath = $targetDir . $certificate;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath) &&
            move_uploaded_file($_FILES["certificate"]["tmp_name"], $certificatePath)) {

            $sql = "INSERT INTO inventory (shape, colour, type, size, origin, amount, image, certificate, description, visibility, buyer_id)
                    VALUES ('$shape', '$color', '$type', '$weight', '$origin', '$amount', '$image', '$certificate', '$description', '$visibility', '$buyer_id')";

            if ($conn->query($sql) === TRUE) {
                $stone_id = $conn->insert_id;

                $purchaseSql = "INSERT INTO purchases (stone_id, buyer_id, amount, amountSettled)
                                VALUES ('$stone_id', '$buyer_id', '$amount', '$amountSettled')";

                if ($conn->query($purchaseSql) === TRUE) {
                    header("Location: ../../../Sales_Rep_Dashboard/Pages/Inventory/inventory.php");
                    exit(); 
                } else {
                    $errorMessage = "Error adding purchase record: " . $conn->error;
                }
            } else {
                $errorMessage = "Error adding inventory record: " . $conn->error;
            }
        } else {
            $errorMessage = "File upload failed. Please check file permissions.";
        }
    } else {
        $errorMessage = "Please upload both image and certificate files.";
    }
}

$conn->close();
?>
