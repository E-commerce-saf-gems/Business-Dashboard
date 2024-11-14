<?php
// addTransaction.php
// Database connection
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from POST request
$transaction_id = $_POST['transaction_id'];
$date = $_POST['date'];
$type = $_POST['type'];
$gem_id = $_POST['gem_id'];
$customer_id = $_POST['customer_id'];
$supplier_id = $_POST['supplier_id'];
$amount = $_POST['amount'];
$status = $_POST['status'];

// Insert transaction into database
$sql = "INSERT INTO transactions (transaction_id, date, type, gem_id, customer_id, supplier_id, amount, status)
        VALUES ('$transaction_id', '$date', '$type', '$gem_id', '$customer_id', '$supplier_id', '$amount', '$status')";

if ($conn->query($sql) === TRUE) {
    echo "New transaction added successfully";
    // Redirect to transactions page or display a success message
    header("Location: transactions.html"); // Adjust path as needed
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
