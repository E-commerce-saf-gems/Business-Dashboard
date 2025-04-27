<?php
include('../../../database/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'];
    $status = $_POST['status'];
    $customer_id = $_POST['customer_id']; 
    $reason = isset($_POST['reason']) ? $_POST['reason'] : ''; 

    $query = "UPDATE request SET status = ? WHERE request_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $request_id);

    if ($stmt->execute()) {
        if ($status == 'approved') {
            $notification_message = "Your request has been approved.";
            $insertNotification = "INSERT INTO notifications (customer_id, message) VALUES (?, ?)";
            $notificationStmt = $conn->prepare($insertNotification);
            $notificationStmt->bind_param("is", $customer_id, $notification_message);

            if ($notificationStmt->execute()) {
                header("Location: requests.php?success=1");
            } else {
                echo "Error sending notification: " . $conn->error;
            }
            $notificationStmt->close();
        } elseif ($status == 'declined') {
            if (empty($reason)) {
                echo "Reason is required for declined requests.";
                exit;
            }

            $notification_message = "Your request has been declined. Reason: " . $reason;
            $insertNotification = "INSERT INTO notifications (customer_id, message) VALUES (?, ?)";
            $notificationStmt = $conn->prepare($insertNotification);
            $notificationStmt->bind_param("is", $customer_id, $notification_message);

            if ($notificationStmt->execute()) {
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
