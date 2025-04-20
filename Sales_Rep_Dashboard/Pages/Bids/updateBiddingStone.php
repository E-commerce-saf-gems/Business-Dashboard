<?php
include '../../../database/db.php'; // Adjust the path as needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $biddingstone_id = $_POST['biddingstone_id'];
    $stone = $_POST['stone'];
    $startingBid = $_POST['startingBid'];
    $currentBid = $_POST['currentBid'];
    $no_of_Cycles = $_POST['no_of_Cycles'];
    $startDate = $_POST['startDate'];

    // Calculate finishDate as one day after the startDate
    $finishDate = date('Y-m-d', strtotime($startDate . ' +1 day'));

    // Update query
    $query = "UPDATE biddingstone
              SET stone = ?, startingBid = ?, currentBid = ?, no_of_Cycles = ?, startDate = ?, finishDate = ?
              WHERE biddingstone_id = ?";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sddiiss", $stone, $startingBid, $currentBid, $no_of_Cycles, $startDate, $finishDate, $biddingstone_id);

    if ($stmt->execute()) {
        header("Location: stonessummary.php?message=Bidding Stone updated successfully");
        exit();
    } else {
        echo "Error updating bidding stone: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
