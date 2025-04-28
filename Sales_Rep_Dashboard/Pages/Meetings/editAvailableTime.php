<?php
session_start();
include '../../../database/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../Login/login-form.php");
    exit;
}


if (!isset($_GET['date']) || !isset($_GET['time'])) {
    header("Location: ./meeting.php?error=NoDateTimeProvided");
    exit;
}

$date = $_GET['date'];
$time = $_GET['time'];
$error = '';


$sql_check = "SELECT availability FROM availabletimes WHERE date = ? AND time = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("ss", $date, $time);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $availabilityData = $result->fetch_assoc();
    $availability = $availabilityData['availability'];
} else {
    
    $conn->close();
    header("Location: ./meeting.php?error=InvalidDateTime");
    exit;
}


if ($availability !== 'available') {
    $conn->close();
    header("Location: ./meeting.php?error=NotEditable");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newDate = $_POST['date'];
    $newTime = $_POST['time'];

    
    $currentDate = date('Y-m-d');
    $currentTime = date('H:i');

    if ($newDate < $currentDate || ($newDate === $currentDate && $newTime <= $currentTime)) {
        $error = "The selected date and time must be in the future.";
    } else {
        
        $sql_update = "UPDATE availabletimes SET date = ?, time = ? WHERE date = ? AND time = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("ssss", $newDate, $newTime, $date, $time);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $stmt->close();
            $conn->close();

            
            header("Location: ./meeting.php?success=1");
            exit;
        } else {
            $error = "Failed to update the available time. Please try again.";
        }

        $stmt->close();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Available Time</title>
    <link rel="stylesheet" href="../../../Components/SalesRep_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="../Sales/salesStyles.css">
    <link rel="stylesheet" href="../Sales/editSalesStyles.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
<dashboard-component></dashboard-component>

<section id="content">
    <main>
        <div class="edit-sales-container">
            <h2>Edit Available Meeting Time</h2>

            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="editAvailableTime.php?date=<?php echo $date; ?>&time=<?php echo $time; ?>" method="POST" class="edit-sales-form">
                <div class="form-group">
                    <label for="date">Edit Date:</label>
                    <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($date); ?>" required>
                </div>

                <div class="form-group">
                    <label for="time">Edit Time:</label>
                    <input type="time" id="time" name="time" value="<?php echo htmlspecialchars($time); ?>" required>
                </div>

                
                <div class="form-actions">
                    <button type="submit" class="btn-save">
                        <i class="bx bx-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </main>
</section>

<script src="../../../Components/SalesRep_Dashboard_Template/script.js"></script>
</body>
</html>
