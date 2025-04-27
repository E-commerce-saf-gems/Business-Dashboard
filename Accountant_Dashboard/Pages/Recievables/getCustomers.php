<?php
// Include database connection
include('../../../database/db.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get the email from POST data, trim spaces
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    // Validate if email is provided
    if (empty($email)) {
        echo json_encode([
            'success' => false,
            'message' => 'Email is required'
        ]);
        exit;
    }

    // Prepare SQL to fetch customer details (customer_id, NIC, full name)
    $stmt = $conn->prepare("
        SELECT customer_id, NIC, CONCAT(firstName, ' ', lastName) AS fullName
        FROM customer
        WHERE email = ?
    ");
    $stmt->bind_param("s", $email); // Bind the email parameter
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a customer was found
    if ($row = $result->fetch_assoc()) {
        // Customer found - return customer details
        echo json_encode([
            'success' => true,
            'customer' => [
                'customer_id' => $row['customer_id'],
                'name' => $row['fullName'],
                'nic' => $row['NIC']
            ]
            // Stones related to this customer will be fetched separately (as per your system design)
        ]);
    } else {
        // Customer not found - return error
        echo json_encode([
            'success' => false,
            'message' => 'Customer not found'
        ]);
    }

} else {
    // Invalid request method - only POST allowed
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
}

// Close the database connection
$conn->close();
?>



