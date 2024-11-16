<?php
include '../../database/db.php';

// Check if request_id is provided via POST
if (isset($_POST['request_id'])) {
    // Get the request ID from POST data
    $requestId = $_POST['request_id'];

    // Prepare the SQL statement to delete the request
    $sql = "DELETE FROM request WHERE request_id = ?";

    // Prepare and bind the statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $requestId); // 'i' stands for integer type
        if ($stmt->execute()) {
            // Successful deletion
            echo "success";
        } else {
            // Error occurred
            echo "error";
        }
        $stmt->close();
    } else {
        echo "error";
    }
} else {
    echo "error";
}

// Close the database connection
$conn->close();
?>
