<?php
include '../../../database/db.php';

// Check if the form data is valid
if (isset($_POST['biddingStone_id'], $_POST['finishDate'])) {
    $biddingStoneId = $_POST['biddingStone_id'];
    $finishDate = $_POST['finishDate'];

    // Update the finish date for the bidding stone
    $updateQuery = "UPDATE biddingstone SET finishDate = ? , reBidCount= reBidCount+1 WHERE biddingStone_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $finishDate, $biddingStoneId);

    if ($stmt->execute()) {
        header("Location: ./activeBids.php?id=$biddingStoneId") ;
        exit;
    } else {
        echo "Error updating finish date: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid data.";
}
?>
