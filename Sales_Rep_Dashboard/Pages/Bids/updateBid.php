<?php
include '../../../database/db.php'; // adjust the path if needed

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and retrieve POST data
    $stoneId = isset($_POST['biddingStone_id']) ? intval($_POST['biddingStone_id']) : 0;
    $startingBid = isset($_POST['startingBid']) ? floatval($_POST['startingBid']) : 0;
    $noOfCycles = isset($_POST['no_of_Cycles']) ? intval($_POST['no_of_Cycles']) : 0;
    $cycleDuration = isset($_POST['cycle_duration']) ? trim($_POST['cycle_duration']) : '';
    $startDate = $_POST['startDate'] ?? '';
    $finishDate = $_POST['finishDate'] ?? '';

    // Basic validation
    if ($stoneId && $startingBid && $noOfCycles && $cycleDuration && $startDate && $finishDate) {
        // Prepare and execute update query
        $stmt = $conn->prepare("
            UPDATE biddingstone 
            SET startingBid = ?, no_of_Cycles = ?, cycle_duration = ?, startDate = ?, finishDate = ?
            WHERE stone_id = ?
        ");
        $stmt->bind_param("disssi", $startingBid, $noOfCycles, $cycleDuration, $startDate, $finishDate, $stoneId);

        if ($stmt->execute()) {
            // Redirect back with success
            header("Location: upcomingBid.php?biddingStone_id=" . $stoneId);
            exit();
        } else {
            echo "Error updating bid: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "All fields are required!";
    }
} else {
    echo "Invalid request.";
}
?>
