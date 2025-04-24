<?php
include '../../../database/db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $biddingStoneId = $_GET['id'];

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("SELECT stone_id FROM biddingstone WHERE biddingStone_id = ?");
        $stmt->bind_param("i", $biddingStoneId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) {
            throw new Exception("No bidding stone found with ID: $biddingStoneId");
        }

        $stoneId = $row['stone_id'];

        $stmt = $conn->prepare("DELETE FROM biddingstone WHERE biddingStone_id = ?");
        $stmt->bind_param("i", $biddingStoneId);
        $stmt->execute();

        $stmt = $conn->prepare("UPDATE inventory SET availability = 'available' WHERE stone_id = ?");
        $stmt->bind_param("i", $stoneId);
        $stmt->execute();

        $conn->commit();

        header("Location: bidssummary.php");
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
