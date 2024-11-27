<?php
include('../../../database/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'];
    $status = $_POST['status'];
    $customer_id = $_POST['customer_id']; // Assuming you have customer_id in the form
    $reason = isset($_POST['reason']) ? $_POST['reason'] : ''; // Reason message for declined requests

    // Update the request status in the database
    $query = "UPDATE request SET status = ? WHERE request_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $request_id);

    if ($stmt->execute()) {
        // Check if the request is approved or declined
        if ($status == 'approved') {
            // Insert notification for approved request
            $notification_message = "Your request has been approved.";
            $insertNotification = "INSERT INTO notifications (customer_id, message) VALUES (?, ?)";
            $notificationStmt = $conn->prepare($insertNotification);
            $notificationStmt->bind_param("is", $customer_id, $notification_message);

            if ($notificationStmt->execute()) {
                // Redirect to the requests page with success message
                header("Location: requests.php?success=1");
            } else {
                echo "Error sending notification: " . $conn->error;
            }
            $notificationStmt->close();
        } elseif ($status == 'declined') {
            // Insert notification for declined request along with the reason
            if (empty($reason)) {
                echo "Reason is required for declined requests.";
                exit;
            }

            $notification_message = "Your request has been declined. Reason: " . $reason;
            $insertNotification = "INSERT INTO notifications (customer_id, message) VALUES (?, ?)";
            $notificationStmt = $conn->prepare($insertNotification);
            $notificationStmt->bind_param("is", $customer_id, $notification_message);

            if ($notificationStmt->execute()) {
                // Redirect to the requests page with success message
                header("Location: requests.php?success=2");
            } else {
                echo "Error sending notification: " . $conn->error;
            }
            $notificationStmt->close();
        }

        $stmt->close();
    } else {
        echo "Error updating status: " . $conn->error;
    }

    $conn->close();
}
?>
