<?php
include('../../../database/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
    $username = isset($_POST['username']) ? $_POST['username'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $name = isset($_POST['name']) ? $_POST['name'] : null;
    $role = isset($_POST['role']) ? $_POST['role'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $contactNo = isset($_POST['contactNo']) ? $_POST['contactNo'] : null;

 
    $sql = "UPDATE user SET role = ?, name = ?, email = ?, contactNo = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $role, $name, $email, $contactNo, $user_id);
    

    if ($stmt->execute()) {
       
        echo "Record updated successfully.";
        header("Location: ./Staff.php?editSuccess=1");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
        header("Location: ./Staff.php?editSuccess=2");
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
