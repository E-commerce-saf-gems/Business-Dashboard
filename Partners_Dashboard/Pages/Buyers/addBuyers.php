<?php
include '../../../database/db.php'; // adjust if needed

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $address = $conn->real_escape_string($_POST['address']);
    $email = $conn->real_escape_string($_POST['email']);
    $contact_no = $conn->real_escape_string($_POST['contact_no']);

    $sql = "INSERT INTO buyer (name, address, email, contact_no) 
            VALUES ('$name', '$address', '$email', '$contact_no')";

    if ($conn->query($sql) === TRUE) {
        header("Location: buyers.php?message=added"); // redirect back after success
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
