<?php
include("../../../database/db.php");

$stone_id = $_POST['stone_id'];
$startingBid = $_POST['startingBid'];
$no_of_Cycles = $_POST['no_of_Cycles'];
$cycleDuration = $_POST['duration']; 
$startDate = str_replace('T', ' ', $_POST['startDate']); 

$start = new DateTime($startDate);
$totalCycleMinutes = $no_of_Cycles * $cycleDuration * 60;
$totalBreakMinutes = ($no_of_Cycles - 1) * 5;
$totalMinutes = $totalCycleMinutes + $totalBreakMinutes;
$interval = new DateInterval('PT' . $totalMinutes . 'M'); 
$start->add($interval);

$finishDate = $start->format('Y-m-d H:i:s');

try {
    $conn->begin_transaction();

    $stmt = $conn->prepare("
        INSERT INTO biddingstone (stone_id, startingBid, no_of_Cycles, startDate, finishDate,duration) 
        VALUES (?, ?, ?, ?, ?,?)
    ");
    $stmt->bind_param("idissi", $stone_id, $startingBid, $no_of_Cycles, $startDate, $finishDate,$cycleDuration);

    if ($stmt->execute()) {
        $bidding_id = $stmt->insert_id;

        $updateStmt = $conn->prepare("UPDATE inventory SET availability = 'Bid' WHERE stone_id = ?");
        $updateStmt->bind_param("i", $stone_id);
        $updateStmt->execute();
        $updateStmt->close();

        $cycleStart = new DateTime($startDate);

        for ($i = 1; $i <= $no_of_Cycles; $i++) {
            $cycleEnd = clone $cycleStart;
            $cycleEnd->modify("+{$cycleDuration} hours");

            $insertCycle = $conn->prepare("
                INSERT INTO cycle (biddingStone_id, cycleNumber, startTime, endTime) 
                VALUES (?, ?, ?, ?)
            ");

            $startStr = $cycleStart->format('Y-m-d H:i:s');
            $endStr = $cycleEnd->format('Y-m-d H:i:s');

            $insertCycle->bind_param("iiss", $bidding_id, $i, $startStr, $endStr);
            $insertCycle->execute();
            $insertCycle->close();

            if ($i < $no_of_Cycles) {
                $cycleStart = clone $cycleEnd;
                $cycleStart->modify("+5 minutes");
            }
        }

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
