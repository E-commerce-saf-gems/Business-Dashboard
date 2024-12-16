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
    $buyer_id = isset($_POST['buyer_id']) ? $_POST['buyer_id'] : null;
    $new_amount_settled = isset($_POST['amountSettled']) ? $_POST['amountSettled'] : null;

    // File handling
    $image_name = isset($_POST['current_image']) ? $_POST['current_image'] : null;
    $certificate_name = isset($_POST['current_certificate']) ? $_POST['current_certificate'] : null;

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

    // Prepare and execute the inventory update
    $sql = "UPDATE inventory 
            SET size=?, shape=?, colour=?, type=?, origin=?, certificate=?, amount=?, image=?, description=?, visibility=?, buyer_id=? 
            WHERE stone_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssssiii", 
        $size, $shape, $colour, $type, $origin, $certificate_name, $amount, $image_name, $description, $visibility, $buyer_id, $stone_id
    );

    if ($stmt->execute()) {
        // Update the amountSettled in the purchases table
        $current_settled_sql = "SELECT amountSettled FROM purchases WHERE stone_id = ? AND buyer_id = ?";
        $current_stmt = $conn->prepare($current_settled_sql);
        $current_stmt->bind_param("ii", $stone_id, $buyer_id);
        $current_stmt->execute();
        $current_result = $current_stmt->get_result();

        $current_amount_settled = 0;
        if ($current_result->num_rows > 0) {
            $current_row = $current_result->fetch_assoc();
            $current_amount_settled = $current_row['amountSettled'];
        }

        // Update purchases table if amountSettled has changed
        if ($new_amount_settled != $current_amount_settled) {
            $update_purchases_sql = "UPDATE purchases SET amountSettled = ? WHERE stone_id = ? AND buyer_id = ?";
            $update_purchases_stmt = $conn->prepare($update_purchases_sql);
            $update_purchases_stmt->bind_param("dii", $new_amount_settled, $stone_id, $buyer_id);
            $update_purchases_stmt->execute();
            $update_purchases_stmt->close();
        }

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
