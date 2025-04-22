<?php
include '../../../database/db.php';

// Function to get stone image path (edit path as per your structure)
function getStoneImage($filename) {
    return "../../../../Group-Project-ECommerce/assets/images/" . $filename;
}


// Classify bids
$activeBids = $completedBids = $upcomingBids = [];

$sql = "SELECT bs.*, i.image AS stone_image 
        FROM biddingstone bs 
        JOIN inventory i ON bs.stone_id = i.stone_id";
$result = $conn->query($sql);
$dateNow = date("Y-m-d");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $endDate = $row['finishDate'];
        $startDate = $row['startDate'];

        if ($dateNow < $startDate) {
            $upcomingBids[] = $row;
        } elseif ($dateNow >= $startDate && $dateNow <= $endDate) {
            $activeBids[] = $row;
        } else {
            $completedBids[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Accountant Bids</title>
    <link rel="stylesheet" href="../../../Components/SalesRep_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="./bids.css">
    <link rel="stylesheet" href="../../styles.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<script>
function updateCountdowns() {
    const countdownElements = document.querySelectorAll('.countdown');

    countdownElements.forEach(elem => {
        const endDateStr = elem.dataset.end;
        const endTime = new Date(endDateStr).getTime();
        const now = new Date().getTime();
        const distance = endTime - now;

        if (distance > 0) {
            const hours = Math.floor((distance / (1000 * 60 * 60)));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            elem.textContent = `${hours}h ${minutes}m ${seconds}s`;
        } else {
            elem.textContent = "Ended";
        }
    });
}

// Initial call
updateCountdowns();
// Update every second
setInterval(updateCountdowns, 1000);
</script>

<body>
<dashboard-component></dashboard-component>

<section id="content">
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Bids Summary</h1>
                <ul class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li><a class="active" href="#">Bids Summary</a></li>
                </ul>
            </div>
        </div>

        <div class="sales-summary-title">
            <h2>Bidding Summary</h2>
        </div>

        <div class="summary-cards">
            <div class="card"><h3>Total Bids Placed</h3></div>
            <div class="card"><h3>Total Bids Revenue</h3></div>
            <div class="card"><h3>Successful Bids</h3></div>
            <div class="card"><h3>Average Bid Value</h3></div>
        </div>

        <!-- ACTIVE BIDS -->
        <div class="sales-table-container">
            <div class="sales-summary-title active-bids-title">
                <ul><li><h2><span class="red-dot"></span>Active Bids</h2></li></ul>
            </div>
            <table class="sales-table">
                <thead>
                    <tr><th>Stone</th><th>Bid No</th><th>Starting Bid</th><th>Current Highest Bid</th><th>Cycle No Completed</th><th>Time Left</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($activeBids as $bid): ?>
                        <tr>
                            <td><div class="stone-img-wrapper"><img src="<?= getStoneImage($bid['stone_image']) ?>" alt="Stone"></div></td>
                            <td><?= $bid['biddingStone_id'] ?></td>
                            <td>$<?= number_format($bid['startingBid']) ?></td>
                            <td>$<?= number_format($bid['currentBid']) ?></td>
                            <td><?= $bid['cycle_no_completed'] ?>/<?= $bid['no_of_Cycles'] ?></td>
                            <td class="countdown" data-end="<?= $bid['end_date'] ?>" id="countdown-<?= $bid['id'] ?>">Loading...</td>
                            
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="bids-dual-container">
            <!-- COMPLETED BIDS -->
            <div class="bid-box completed-bids">
                <div class="header"><h2><span class="dot green-dot"></span>Completed Bids</h2></div>
                <div class="bids-table-wrapper">
                    <table class="bids-table">
                        <thead><tr><th>Stone</th><th>Starting Bid</th><th>Highest Bid</th><th>End Date</th><th>Purchase</th></tr></thead>
                        <tbody>
                            <?php foreach ($completedBids as $bid): ?>
                                <tr>
                                    <td>
                                        <div class="stone-img-wrapper-other">
                                            <a href="../Inventory/viewInventory.php?id=<?= $bid['stone_id'] ?>">
                                                <img src="<?= getStoneImage($bid['stone_image']) ?>" alt="Stone">
                                            </a>
                                        </div>
                                    </td>
                                    <td>$<?= number_format($bid['startingBid']) ?></td>
                                    <td>$<?= number_format($bid['currentBid']) ?></td>
                                    <td><?= $bid['finishDate'] ?></td>
                                    <td><?= $bid['currentBid'] > 0 ? 'Purchased' : 'Not Purchased' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- UPCOMING BIDS -->
            <div class="bid-box upcoming-bids">
                <div class="header">
                    <h2><span class="dot yellow-dot"></span>Upcoming Bids</h2>
                    <a href="./addBiddingStone.html" class="add-new-btn">+ Add New</a>
                </div>
                <div class="bids-table-wrapper">
                    <table class="bids-table">
                        <thead><tr><th>Stone</th><th>Starting Bid</th><th>Start Date</th><th>Cycles</th><th>End Date</th></tr></thead>
                        <tbody>
                            <?php foreach ($upcomingBids as $bid): ?>
                                <tr>
                                    <td>
                                        <div class="stone-img-wrapper-other">
                                            <a href="../Inventory/viewInventory.php?id=<?= $bid['stone_id'] ?>">
                                                <img src="<?= getStoneImage($bid['stone_image']) ?>" alt="Stone">
                                            </a>
                                        </div>
                                    </td>
                                    <td>$<?= number_format($bid['startingBid']) ?></td>
                                    <td><?= $bid['startDate'] ?></td>
                                    <td><?= $bid['no_of_Cycles'] ?></td>
                                    <td><?= $bid['finishDate'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</section>

<script src="../../../Components/SalesRep_Dashboard_Template/script.js"></script>
<script src="bids.js"></script>
</body>
</html>
