<?php

include('../../database/db.php'); 

$errors = [];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

function sendVerificationEmail($first_name, $email, $token) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
       
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'sadheeyasalim10@gmail.com';                     //SMTP username
        $mail->Password   = 'ijkwzrnamjyfeimb';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom('sadheeysalim10@gmail.com', 'Saf Gems');
        $mail->addAddress($email, $first_name);     //Add a recipient

        $email_template = "
            <h2 style='color='#449f9f''>Welcome To SAF GEMS</h2>
            <h2>Thank you for registering with us!</h2>
            <h3>To complete your registration, verify your email address with the link given below</h5>
            <br/><br/>
            <button>
                <a href='http://localhost/Group-Project-ECommerce/pages/RegisterPage/verifyEmail.php?token=$token'>Click Here!</a>
            </button>
        " ;

        //Content
        $mail->isHTML(true);                      //Set email format to HTML
        $mail->Subject = 'Email Verification';
        $mail->Body    = $email_template ;
    
        $mail->send();
       // echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $first_name = mysqli_real_escape_string($conn, $_POST['firstName']); 
    $last_name = mysqli_real_escape_string($conn, $_POST['lastName']);
    
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    }

    // Step 3: Continue collecting data for Step 2 and Step 3
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address1 = mysqli_real_escape_string($conn, $_POST['address1']);
    $address2 = mysqli_real_escape_string($conn, $_POST['address2']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $postal_code = mysqli_real_escape_string($conn, $_POST['postal_code']);
    $nic = mysqli_real_escape_string($conn, $_POST['nic']);
    $token = md5(rand()) ;

    // Photo handling
    if (!empty($_POST['photo'])) {
        $base64_string = $_POST['photo'];
        $data = explode(',', $base64_string);
        $image_data = base64_decode($data[1]);
        $target_dir = "../../uploads/";
        $photo_filename = "photo_" . time() . ".png";
        $photo_file = $target_dir . $photo_filename;
        file_put_contents($photo_file, $image_data);
    } else {
        $errors[] = "Photo capture was not successful.";
    }

    // Handle ID copy upload
    if (!empty($_FILES["id_copy"]["name"])) {
        $id_copy_file = $target_dir . basename($_FILES["id_copy"]["name"]);
        if (!move_uploaded_file($_FILES["id_copy"]["tmp_name"], $id_copy_file)) {
            $errors[] = "There was an error uploading your ID copy.";
        }
    } else {
        $errors[] = "ID copy is required.";
    }

    // Insert into database if there are no errors
    if (empty($errors)) {
        // Check if email already exists
        $email_check_query = "SELECT * FROM customer WHERE email = '$email' LIMIT 1";
        $email_result = mysqli_query($conn, $email_check_query);
        
        // Check if NIC already exists
        $nic_check_query = "SELECT * FROM customer WHERE NIC = '$nic' LIMIT 1";
        $nic_result = mysqli_query($conn, $nic_check_query);
        
        if (mysqli_num_rows($email_result) > 0) {
            $errors[] = "The email address is already registered.";
            header("Location: ./register.html") ;
        }
        
        if (mysqli_num_rows($nic_result) > 0) {
            $errors[] = "The NIC number is already registered.";
            header("Location: ./register.html") ;
        }
    
        if (empty($errors)) {
            // If no errors, proceed with registration
            $sql = "INSERT INTO customer (title, firstName, lastName, email, password, gender, DOB, contactNo, address1, address2, city, country, postalCode, NIC, image, pdf, token)
                    VALUES ('$title', '$first_name', '$last_name', '$email', '$hashed_password', '$gender', '$dob', '$phone', '$address1', '$address2', '$city', '$country', '$postal_code', '$nic', '$photo_file', '$id_copy_file', '$token')";
            
            if (mysqli_query($conn, $sql)) {
                sendVerificationEmail("$first_name", "$email", "$token") ;
                echo "Registration successful! Verify Your Email Address";
                header("Location: ./registrationSuccess.html") ;
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                header("Location: ./register.html") ;
            }
        } else {
            // Output errors
            foreach ($errors as $error) {
                echo "<p style='color: red;'>$error</p>";
            }
        }
    }
    


    // Close the database connection
    mysqli_close($conn);
}
?>
