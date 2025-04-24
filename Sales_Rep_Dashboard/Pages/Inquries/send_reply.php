<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../../../vendor/autoload.php'; // 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $message = $_POST['message'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("❌ Invalid email format.");
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
$mail->Host       = 'smtp.gmail.com';   
$mail->SMTPAuth   = true;  
$mail->Username   = 'ashcharyawaduge@gmail.com';  
$mail->Password   = 'zmikpzbtyihohwwi';  // Your App Password (without spaces)
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  // FIX: Use correct encryption method
$mail->Port       = 465;  // Port for SSL (or use 587 for TLS)
                                 
    
        $mail->setFrom('ashcharyawaduge@gmail.com', 'Saf Gems');
        $mail->addAddress($email, $first_name);  

        $mail->isHTML(true);
        $mail->Subject = "Reply to Your Inquiry";
        $mail->Body = "<p>Dear Customer,</p><p>$message</p><p>Best Regards,<br>SAF GEMS</p>";

        $mail->send();
        echo "✅ Reply sent successfully!";
    } catch (Exception $e) {
        echo "❌ Error sending email: {$mail->ErrorInfo}";
    }
}
?>
