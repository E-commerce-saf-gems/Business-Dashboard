<?php
include '../../../database/db.php'; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $biddingStoneId = isset($_POST['biddingStone_id']) ? intval($_POST['biddingStone_id']) : 0;
    $startingBid = isset($_POST['startingBid']) ? floatval($_POST['startingBid']) : 0;
    $startDate = $_POST['startDate'] ?? '';
    $finishDate = $_POST['finishDate'] ?? '';

    if ($biddingStoneId && $startingBid && $startDate && $finishDate) {
        $stmt = $conn->prepare("
            UPDATE biddingstone 
            SET startingBid = ?, startDate = ?, finishDate = ?
            WHERE biddingStone_id = ?
        ");
        $stmt->bind_param("dssi", $startingBid, $startDate, $finishDate, $biddingStoneId);
        
        if ($stmt->execute()) {

            header("Location: bidssummary.php");
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
