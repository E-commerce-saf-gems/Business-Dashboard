<?php
include("../../../database/db.php");

$stone_id = $_POST['stone_id'];
$startingBid = $_POST['startingBid'];
$no_of_Cycles = $_POST['no_of_Cycles'];
$cycleDuration = $_POST['duration']; 
$startDate = str_replace('T', ' ', $_POST['startDate']); 

$start = new DateTime($startDate);
$totalCycleMinutes = $no_of_Cycles * $cycleDuration * 60;
$totalBreakMinutes = ($no_of_Cycles- 1) * 5;
$totalMinutes = $totalCycleMinutes + $totalBreakMinutes;
$interval = new DateInterval('PT' . $totalMinutes . 'M'); 
$start->add($interval);

$finishDate = $start->format('Y-m-d H:i:s');


try {
    $conn->begin_transaction();

    $stmt = $conn->prepare("
        INSERT INTO biddingstone (stone_id, startingBid, no_of_Cycles, startDate, finishDate) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("idiss", $stone_id, $startingBid, $no_of_Cycles, $startDate, $finishDate);

    if ($stmt->execute()) {
        $updateStmt = $conn->prepare("UPDATE inventory SET availability = 'Bid' WHERE stone_id = ?");
        $updateStmt->bind_param("i", $stone_id);
        $updateStmt->execute();
        $updateStmt->close();

        echo "Stone successfully added to bidding and availability updated.";
    } else {
        throw new Exception("Error: " . $stmt->error);
    }

    $conn->commit();
    header("Location: ./bidssummary.html?ReceivalSuccess=1");
} catch (Exception $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

$stmt->close();
$conn->close();
?>