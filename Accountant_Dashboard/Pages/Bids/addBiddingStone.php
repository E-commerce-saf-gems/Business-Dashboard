<?php
include("../../../database/db.php");


$stone_id = $_POST['stone_id'];
$startingBid = $_POST['startingBid'];
$no_of_Cycles = $_POST['no_of_Cycles'];
$startDate = str_replace('T', ' ', $_POST['startDate']);
$finishDate = str_replace('T', ' ', $_POST['finishDate']);

try {
    // Begin transaction
    $conn->begin_transaction();

    // Insert into biddingstone table
    $stmt = $conn->prepare("
        INSERT INTO biddingstone (stone_id, startingBid, no_of_Cycles, startDate, finishDate) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("idiss", $stone_id, $startingBid, $no_of_Cycles, $startDate, $finishDate);

    if ($stmt->execute()) {
        // Update availability to "Bid" in inventory table
        $updateStmt = $conn->prepare("UPDATE inventory SET availability = 'Bid' WHERE stone_id = ?");
        $updateStmt->bind_param("i", $stone_id);
        $updateStmt->execute();
        $updateStmt->close();

        echo "Stone successfully added to bidding and availability updated.";
    } else {
        throw new Exception("Error: " . $stmt->error);
    }

    // Commit transaction
    $conn->commit();
    header("Location: ./stonessummary.php?ReceivalSuccess=1"); // Redirect with success
} catch (Exception $e) {
    // Rollback transaction on failure
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

$stmt->close();
$conn->close();
?>

