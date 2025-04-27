<?php
include("../../../database/db.php");

$stone_id = $_POST['stone_id'];
$startingBid = $_POST['startingBid'];

$startDate = str_replace('T', ' ', $_POST['startDate']); 
$finishDate = str_replace('T', ' ', $_POST['finishDate']); 


try {
    $conn->begin_transaction();

    $stmt = $conn->prepare("
        INSERT INTO biddingstone (stone_id, startingBid, startDate, finishDate) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("idss", $stone_id, $startingBid, $startDate, $finishDate);

    if ($stmt->execute()) {
        $bidding_id = $stmt->insert_id;

        $updateStmt = $conn->prepare("UPDATE inventory SET availability = 'Bid' WHERE stone_id = ?");
        $updateStmt->bind_param("i", $stone_id);
        $updateStmt->execute();
        $updateStmt->close();

        $conn->commit();
        header("Location: ./bidssummary.php?ReceivalSuccess=1");
    } else {
        throw new Exception("Error: " . $stmt->error);
    }

} catch (Exception $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

$stmt->close();
$conn->close();
?>
