<?php
include '../../../database/db.php';
require '../../../vendor/autoload.php';  

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendReadyForCollectionEmail($first_name, $email, $order_id) {
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sadheeyasalim10@gmail.com';          
        $mail->Password   = 'ijkwzrnamjyfeimb';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('sadheeyasalim10@gmail.com', 'Saf Gems');
        $mail->addAddress($email, $first_name);

        $email_template = "
            <h2 style='color: #449f9f;'>Your Order #$order_id is Ready for Collection</h2>
            <p>Dear $first_name,</p>
            <p>We are pleased to inform you that your order is now ready for pickup.</p>
            <p>Please visit our store at your earliest convenience to collect your items.</p>
            <p>For any inquiries, contact our support team.</p>
            <br/>
            <a href='http://localhost/Group-Project-ECommerce/pages/Profile/Purchases/MyPurchases.php' 
               style='background-color: #449f9f; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>
               View Your Orders
            </a>
            <br/><br/>
            <p>Thank you for shopping with SAF GEMS!</p>
        ";

        $mail->isHTML(true);
        $mail->Subject = "Order #$order_id is Ready for Collection";
        $mail->Body    = $email_template;

        $mail->send();
    } catch (Exception $e) {
        error_log("Email not sent: {$mail->ErrorInfo}");
    }
}

if (isset($_POST['order_id']) && isset($_POST['new_status'])) {
    
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];


    $update_sql = "UPDATE orders SET order_status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($update_sql);

    if (!$stmt) {
        die("SQL Prepare failed: " . $conn->error); // Debugging
    }

    // Bind parameters BEFORE executing
    $stmt->bind_param("si", $new_status, $order_id);

    // Execute the statement
    if (!$stmt->execute()) {
        die("Failed to update order status: " . $stmt->error); // Debugging
    }

    // Check if any rows were updated
    if ($stmt->affected_rows === 0) {
        die("Order ID not found or status already updated."); // Debugging
    }

    // If the new status is 'ready for collection', fetch customer details & send email
    if ($new_status == "ready for collection") {
        $query = "SELECT customer.firstName, customer.email FROM orders 
                  JOIN customer ON orders.customer_id = customer.customer_id 
                  WHERE orders.order_id = ?";
        $stmt2 = $conn->prepare($query);

        if (!$stmt2) {
            die("Prepare failed: " . $conn->error); // Debugging
        }

        $stmt2->bind_param("i", $order_id);
        $stmt2->execute();
        $result = $stmt2->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            sendReadyForCollectionEmail($user['firstName'], $user['email'], $order_id);
        }

        $stmt2->close();
    }

    header("Location: ./orders.php?success=1");
    echo "success";

    $stmt->close();
    $conn->close();
}

$conn->close();
?>
