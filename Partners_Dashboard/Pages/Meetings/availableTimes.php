<?php
// Fetch available times for the current salesRep (using session for user_id)
$salesRep_id = $_SESSION['user_id']; // Assuming the salesRep_id is stored in the session

$sql_available_times = "SELECT * FROM availabletimes WHERE salesRep_id = ?"; 
// $sql_booked_times = "SELECT date, time FROM meeting WHERE salesRep_id = ?";

// Prepare and execute queries
if ($stmt = $conn->prepare($sql_available_times)) {
    $stmt->bind_param("i", $salesRep_id);
    $stmt->execute();
    $available_times_result = $stmt->get_result();
}

// if ($stmt = $conn->prepare($sql_booked_times)) {
//     $stmt->bind_param("i", $salesRep_id);
//     $stmt->execute();
//     $booked_times_result = $stmt->get_result();
// }

// Create arrays to store available and booked times
$available_times = [];
// $booked_times = [];

while ($row = $available_times_result->fetch_assoc()) {
    $available_times[] = $row;
}

while ($row = $booked_times_result->fetch_assoc()) {
    $booked_times[] = $row;
}
?>
