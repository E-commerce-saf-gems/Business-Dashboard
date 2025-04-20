<?php
include '../../../database/db.php'; // adjust if needed

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $biddingStoneId = isset($_GET['biddingStone_id']) ? intval($_GET['biddingStone_id']) : 0;

    if ($biddingStoneId > 0) {
        $stmt = $conn->prepare("DELETE FROM biddingstone WHERE biddingStone_id = ?");
        $stmt->bind_param("i", $biddingStoneId);

        if ($stmt->execute()) {
            header("Location: bids.html?deleted=success");
            exit();
        } else {
            echo "Error deleting record: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid biddingStone_id.";
    }
} else {
    echo "Unauthorized access.";
}
?>

