<?php
include('../../../database/db.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../../../../Group-Project-ECommerce/vendor/autoload.php';

function sendApprovalEmail($first_name, $email, $date, $shape, $type, $weight, $color, $requirement) {
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
    
        $mail->setFrom('sadheeysalim10@gmail.com', 'Saf Gems');
        $mail->addAddress($email, $first_name);                                     

        $email_template = "
            <h2 style='color: #449f9f;'>Your Request Has Been Approved</h2>
            <p>Dear $first_name,</p>
            <p>We are pleased to inform you that your request has been approved. Below are the details of your request:</p>
            <ul>
                <li><strong>Date:</strong> $date</li>
                <li><strong>Shape:</strong> $shape</li>
                <li><strong>Type:</strong> $type</li>
                <li><strong>Weight:</strong> $weight</li>
                <li><strong>Color:</strong> $color</li>
                <li><strong>Other Requirements:</strong> $requirement</li>
            </ul>
             <p>In order to proceed with fulfilling your request, we kindly ask that you book a meeting at your convenience. Please click the link below to choose a time that works best for you:</p>
            <br/>
            <a href='http://localhost/Group-Project-ECommerce/pages/BookAppointmentPage/submit-appointment.php' style='background-color: #449f9f; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>Book a Meeting</a>
            <br/><br/>
            <p>Thank you for choosing SAF GEMS.</p>
        ";

        $mail->isHTML(true);
        $mail->Subject = 'Request Approved';
        $mail->Body    = $email_template;

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function sendDeclineEmail($first_name, $email, $reason, $date, $shape, $type, $weight, $color, $requirement) {
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
    
        $mail->setFrom('sadheeysalim10@gmail.com', 'Saf Gems');
        $mail->addAddress($email, $first_name);  

        $email_template = "
            <h2 style='color: #ff4c4c;'>Your Request Has Been Declined</h2>
            <p>Dear $first_name,</p>
            <p>We regret to inform you that your request has been declined. Below are the details of your request:</p>
            <ul>
                <li><strong>Date:</strong> $date</li>
                <li><strong>Shape:</strong> $shape</li>
                <li><strong>Type:</strong> $type</li>
                <li><strong>Weight:</strong> $weight</li>
                <li><strong>Color:</strong> $color</li>
                <li><strong>Other Requirements:</strong> $requirement</li>
            </ul>
            <p>The reason provided is as follows:</p>
            <blockquote style='border-left: 4px solid #ff4c4c; padding-left: 10px;'>
                $reason
            </blockquote>
            <p>If you have any questions or need further assistance, please don't hesitate to contact us.</p>
        ";

        $mail->isHTML(true);
        $mail->Subject = 'Request Declined';
        $mail->Body    = $email_template;

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'];
    $status = $_POST['status'];
    $decline_reason = $_POST['decline_reason'] ?? null;

    if ($status === 'D' && empty($decline_reason)) {
        echo "A reason is required to decline the request.";
        exit();
    }

    if ($status === 'D') {
        $query = "UPDATE request SET status = ?, declineReason = ? WHERE request_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $status, $decline_reason, $request_id);
    } else {
        $query = "UPDATE request SET status = ?, declineReason = NULL WHERE request_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $status, $request_id);
    }

    if ($stmt->execute()) {
        $details_query = "SELECT customer.firstName, customer.email, request.date, request.shape, 
                                  request.type, request.weight, request.color, request.requirement 
                          FROM customer 
                          INNER JOIN request ON customer.customer_id = request.customer_id 
                          WHERE request.request_id = ?";
        $details_stmt = $conn->prepare($details_query);
        $details_stmt->bind_param("i", $request_id);
        $details_stmt->execute();
        $details_stmt->bind_result($first_name, $email, $date, $shape, $type, $weight, $color, $requirement);

        if ($details_stmt->fetch()) {
            if ($status === 'A') {
                sendApprovalEmail($first_name, $email, $date, $shape, $type, $weight, $color, $requirement);
            } elseif ($status === 'D') {
                sendDeclineEmail($first_name, $email, $decline_reason, $date, $shape, $type, $weight, $color, $requirement);
            }
        }

        $details_stmt->close();

        header("Location: requests.php?success=1");
        exit();
    } else {
        echo "Error updating status: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
