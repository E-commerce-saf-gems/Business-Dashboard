<?php
include '../../../database/db.php';

// Get stone_id from URL
$stoneId = isset($_GET['biddingStone_id']) ? intval($_GET['biddingStone_id']) : 0;

$bid = null;
if ($stoneId > 0) {
    $stmt = $conn->prepare("SELECT * FROM biddingstone WHERE biddingStone_id = ?");
    $stmt->bind_param("i", $stoneId);
    $stmt->execute();
    $result = $stmt->get_result();
    $bid = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bidding Stone</title>
    <link rel="stylesheet" href="../../../Components/SalesRep_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="./bids.css">
    <link rel="stylesheet" href="../../styles.css">
    <link rel="stylesheet" href="../Sales/salesStyles.css" />
    <link rel="stylesheet" href="../Sales/editSalesStyles.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body>
<dashboard-component></dashboard-component>

<section id="content">
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Edit Bidding Stone</h1>
                <ul class="breadcrumb">
                    <li><a class="active" href="./stonessummary.php">Home</a></li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li><a href="#">Edit Stone</a></li>
                </ul>
            </div>
        </div>

        <div class="edit-sales-container">
            <?php if ($bid): ?>
                <form class="edit-sales-form" id="editBiddingStoneForm" action="updateBid.php" method="post">
                    <h2>Edit Bidding Stone Details</h2><br>
                    
                    <input type="hidden" name="stone_id" value="<?= $bid['stone_id'] ?>">

                    <div class="form-group">
                        <label for="startingBid">Starting Bid Value (Rs.)</label>
                        <input type="number" id="startingBid" name="startingBid" value="<?= $bid['startingBid'] ?>" required />
                    </div>

                    <div class="form-group">
                        <label for="no_of_Cycles">Number of Cycles</label>
                        <input type="number" id="no_of_Cycles" name="no_of_Cycles" value="<?= $bid['no_of_Cycles'] ?>" required />
                    </div>

                    <div class="form-group">
                        <label for="cycle_duration">Cycle Duration (e.g. 2h)</label>
                        <input type="text" id="cycle_duration" name="cycle_duration" value="<?= $bid['cycle_duration'] ?>" required />
                    </div>

                    <div class="form-group">
                        <label for="startDate">Start Date and Time</label>
                        <input type="datetime-local" id="startDate" name="startDate" value="<?= date('Y-m-d\TH:i', strtotime($bid['startDate'])) ?>" required />
                    </div>

                    <div class="form-group">
                        <label for="finishDate">Finish Date and Time</label>
                        <input type="datetime-local" id="finishDate" name="finishDate" value="<?= date('Y-m-d\TH:i', strtotime($bid['finishDate'])) ?>" required />
                    </div>

                    <button type="submit" class="btn-save"><i class='bx bx-save'></i>Confirm</button>
                </form>
            <?php else: ?>
                <p style="color: red;">Bid not found for Stone ID <?= htmlspecialchars($stoneId) ?>.</p>
            <?php endif; ?>
        </div>
    </main>
</section>

<script src="../../../Components/SalesRep_Dashboard_Template/script.js"></script>
<script src="./bids.js"></script>
</body>
</html>
