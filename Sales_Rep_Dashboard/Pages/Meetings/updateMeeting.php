<?php
include('../../../database/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $meeting_id = $_POST['meeting_id'];
    $status = $_POST['status'];

    
    $query = "UPDATE meeting SET status = ? WHERE meeting_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $meeting_id);

    if ($stmt->execute()) {
        
        if ($status === 'A') {
            $getAvailableTimeQuery = "SELECT availableTimes_id FROM meeting WHERE meeting_id = ?";
            $getStmt = $conn->prepare($getAvailableTimeQuery);
            $getStmt->bind_param("i", $meeting_id);
            $getStmt->execute();
            $getResult = $getStmt->get_result();

            if ($getResult->num_rows > 0) {
                $row = $getResult->fetch_assoc();
                $availableTimes_id = $row['availableTimes_id'];

                $updateAvailabilityQuery = "UPDATE availabletimes SET availability = 'booked' WHERE availableTimes_id = ?";
                $updateStmt = $conn->prepare($updateAvailabilityQuery);
                $updateStmt->bind_param("i", $availableTimes_id);

                if ($updateStmt->execute()) {
                    header("Location: meeting.php?success=1");
                    exit();
                } else {
                    echo "Error updating availability: " . $conn->error;
                }

                $updateStmt->close();
            } else {
                echo "Error: Could not find availableTimes_id for the meeting.";
            }

            $getStmt->close();
        } else {
            header("Location: meeting.php?success=1");
            exit();
        }

    } else { 
        echo "Error updating status: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
