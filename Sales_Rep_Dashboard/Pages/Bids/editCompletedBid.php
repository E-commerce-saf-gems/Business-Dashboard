<?php
// Ensure the customer is logged in and the bidding stone ID is available

include '../../../database/db.php';

$biddingStoneId = $_GET['id']; 

// Query to get the bidding stone details
$query = "
    SELECT bs.*, 
           i.image
    FROM biddingstone bs
    JOIN inventory i ON bs.stone_id = i.stone_id
    WHERE bs.biddingStone_id = $biddingStoneId
";
$result = $conn->query($query);
$bid = $result->fetch_assoc();

date_default_timezone_set('Asia/Kolkata');
$currentDateTime = date('Y-m-d H:i:s');
$minFinishDate = date('Y-m-d\TH:i', strtotime($currentDateTime)+3600);

// Ensure that the bid exists
if (!$bid) {
    echo "No bidding stone found for ID: $biddingStoneId";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Reopen Bid</title>
    <link rel="stylesheet" href="../../../Components/SalesRep_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="../../styles.css">
    <link rel="stylesheet" href="./sadheeyaBids.css">
    <link rel="stylesheet" href="../Sales/editSalesStyles.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <dashboard-component></dashboard-component>

    <section id="content">
        <main>
            <div class="edit-sales-container">
                <?php if ($bid): ?>
                    <form class="edit-sales-form" id="reopenBidForm" action="updateCompletedBid.php" method="post">
                        <h2>Reopen Bid for Stone #<?= $biddingStoneId ?></h2><br>

                        <!-- Hidden field for bidding stone ID -->
                        <input type="hidden" name="biddingStone_id" value="<?= $bid['biddingStone_id'] ?>">

                        <!-- Starting Bid (Readonly) -->
                        <div class="form-group">
                            <label for="startingBid">Starting Bid Value (Rs.)</label>
                            <input type="number" id="startingBid" name="startingBid" value="<?= $bid['startingBid'] ?>" readonly />
                        </div>

                        <!-- Start Date (Readonly) -->
                        <div class="form-group">
                            <label for="startDate">Start Date and Time</label>
                            <input type="datetime-local" id="startDate" name="startDate" 
                                   value="<?= date('Y-m-d\TH:i', strtotime($bid['startDate'])) ?>" readonly />
                        </div>

                        <!-- Finish Date (Editable) -->
                        <div class="form-group">
                            <label for="finishDate">Finish Date and Time</label>
                            <input type="datetime-local" id="finishDate" name="finishDate" 
                                   value="<?= date('Y-m-d\TH:i', strtotime($bid['finishDate'])) ?>" 
                                   min="<?= $minFinishDate ?>" required />
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-save"><i class='bx bx-save'></i>Confirm</button>
                    </form>
                <?php else: ?>
                    <p style="color: red;">Bid not found for Stone ID <?= htmlspecialchars($biddingStoneId) ?>.</p>
                <?php endif; ?>
            </div>
        </main>
    </section>

    <script src="../../../Components/SalesRep_Dashboard_Template/script.js"></script>
    <script src="./bids.js"></script>
</body>
</html>
