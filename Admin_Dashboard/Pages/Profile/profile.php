<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

include '../../../database/db.php';

$userId = $_SESSION['user_id'];
$username = $role = $name = $email = $contactNo = "";

$sql = "SELECT username, role, name, email, contactNo FROM user WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($username, $role, $name, $email, $contactNo);
$stmt->fetch();
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>User Profile</title>
  <link rel="stylesheet" href="profile.css" />
  <link rel="stylesheet" href="../../../Components/Partner_Dashboard_Template/styles.css">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

</head>
<dashboard-component></dashboard-component>
<body>
  <h1>User Profile</h1>
  <div class="profile-container">
    <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
    <p><strong>Role:</strong> <?php echo htmlspecialchars($role); ?></p>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
    <p><strong>Contact No:</strong> <?php echo htmlspecialchars($contactNo); ?></p>
  </div>
  <script src="../../../Components/Partner_Dashboard_Template/script.js"></script>
</body>
</html>
