<?php
include('../../../database/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize POST data
    $stone_id = isset($_POST['stone_id']) ? $_POST['stone_id'] : null;
    $size = isset($_POST['size']) ? $_POST['size'] : null;
    $shape = isset($_POST['shape']) ? $_POST['shape'] : null;
    $colour = isset($_POST['colour']) ? $_POST['colour'] : null;
    $type = isset($_POST['type']) ? $_POST['type'] : null;
    $origin = isset($_POST['origin']) ? $_POST['origin'] : null;
    $amount = isset($_POST['amount']) ? $_POST['amount'] : null;
    $description = isset($_POST['description']) ? $_POST['description'] : null;
    $visibility = isset($_POST['visibility']) ? $_POST['visibility'] : null;
    $availability = isset($_POST['availability']) ? $_POST['availability'] : null;

    // File handling
    $image_name = null;
    $certificate_name = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = "../../../uploads/" . basename($image_name);
        move_uploaded_file($image_tmp_name, $image_folder);
    }

    if (isset($_FILES['certificate']) && $_FILES['certificate']['error'] == UPLOAD_ERR_OK) {
        $certificate_name = $_FILES['certificate']['name'];
        $certificate_tmp_name = $_FILES['certificate']['tmp_name'];
        $certificate_folder = "../../../uploads/" . basename($certificate_name);
        move_uploaded_file($certificate_tmp_name, $certificate_folder);
    }

    // Prepare and execute the SQL statement
    $sql = "UPDATE inventory 
            SET size=?, shape=?, colour=?, type=?, origin=?, certificate=?, amount=?, image=?, description=?, visibility=?, availability=? 
            WHERE stone_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssssssii", 
        $size, $shape, $colour, $type, $origin, $certificate_name, $amount, $image_name, $description, $visibility, $availability, $stone_id
    );

    if ($stmt->execute()) {
        echo "Record updated successfully.";
        header("Location: ./inventory.php?editSuccess=1");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
        header("Location: ./inventory.php?editSuccess=2");
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>