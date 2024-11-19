<?php
include('../../../database/db.php'); // Include your database connection here

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get POST data
    $stone_id = $_POST['stone_id'];
    $size = $_POST['size'];
    $shape = $_POST['shape'];
    $colour = $_POST['colour'];
    $type = $_POST['type'];
    $weight = $_POST['weight'];
    $origin = $_POST['origin'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $visibility = $_POST['visibility'];
    $availability = $_POST['availability'];
    $buyer_id = $_POST['buyer_id'];

    // Handle image upload
    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = "../../../uploads/" . basename($image_name);

    // Handle certificate upload
    $certificate_name = $_FILES['certificate']['name'];
    $certificate_tmp_name = $_FILES['certificate']['tmp_name'];
    $certificate_folder = "../../../uploads/" . basename($certificate_name);

    // Move uploaded files to their respective directories
    $image_uploaded = move_uploaded_file($image_tmp_name, $image_folder);
    $certificate_uploaded = move_uploaded_file($certificate_tmp_name, $certificate_folder);

    if ($image_uploaded && $certificate_uploaded) {
        // Update the inventory table
        $sql_inventory = "UPDATE inventory 
                          SET size = ?, shape = ?, colour = ?, type = ?, weight = ?, origin = ?, 
                              amount = ?, description = ?, visibility = ?, availability = ?, 
                              image = ?, certificate = ? 
                          WHERE stone_id = ?";
        $stmt_inventory = $conn->prepare($sql_inventory);
        $stmt_inventory->bind_param("ssssssssssssi", 
            $size, $shape, $colour, $type, $weight, $origin, $amount, $description, 
            $visibility, $availability, $image_name, $certificate_name, $stone_id
        );

        if ($stmt_inventory->execute()) {
            // Update the buyer table with the new buyer_id
            $sql_buyer = "UPDATE buyer SET stone_id = ? WHERE buyer_id = ?";
            $stmt_buyer = $conn->prepare($sql_buyer);
            $stmt_buyer->bind_param("ii", $stone_id, $buyer_id);

            if ($stmt_buyer->execute()) {
                // Both updates were successful
                echo "Record updated successfully.";
                header("Location: ./inventory.php?editSuccess=1");
                exit();
            } else {
                echo "Error updating buyer record: " . $conn->error;
                header("Location: ./inventory.php?editSuccess=2");
            }

            $stmt_buyer->close();
        } else {
            echo "Error updating inventory record: " . $conn->error;
            header("Location: ./inventory.php?editSuccess=2");
        }

        $stmt_inventory->close();
    } else {
        echo "Error uploading files.";
        header("Location: ./inventory.php?editSuccess=2");
    }

    $conn->close();
} else {
    echo "Invalid request method.";
}
?>