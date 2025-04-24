<?php
session_start();

include '../../../database/db.php';

date_default_timezone_set('Asia/Kolkata');
$currentDateTime = date('Y-m-d H:i:s');

$liveBidsQuery = "
    SELECT bs.*, 
           i.image,
           (SELECT MAX(amount) FROM bid WHERE biddingStone_id = bs.biddingStone_id AND validity='valid') AS highestBid
    FROM biddingstone bs
    JOIN inventory i ON bs.stone_id = i.stone_id
    WHERE bs.startDate <= '$currentDateTime' 
      AND bs.finishDate > '$currentDateTime'
";

$liveBidsResult = $conn->query($liveBidsQuery);

$upcomingQuery = "
    SELECT bs.*, i.image
    FROM biddingstone bs
    JOIN inventory i ON bs.stone_id = i.stone_id
    WHERE bs.startDate > '$currentDateTime'
";

$upcomingResult = $conn->query($upcomingQuery);

$completedQuery = "
    SELECT 
        bs.*, 
        i.image,
        COUNT(b.bid_id) AS totalBids,
        MAX(b.amount) AS highestBid,
        CASE 
            WHEN COUNT(b.bid_id) > 0 THEN 'Completed with Bids'
            ELSE 'No Bids Placed'
        END AS status
    FROM biddingstone bs
    LEFT JOIN bid b ON bs.biddingStone_id = b.biddingStone_id
    JOIN inventory i ON bs.stone_id = i.stone_id
    WHERE bs.finishDate <= '$currentDateTime'
    GROUP BY bs.biddingStone_id
";


$completedResult = $conn->query($completedQuery);
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
        const startDateStr = elem.dataset.start;
        let targetTimeStr = endDateStr || startDateStr;
        const targetTime = new Date(targetTimeStr).getTime();
        const now = new Date().getTime();
        const distance = targetTime - now;

        if (distance > 0) {
            const hours = Math.floor((distance / (1000 * 60 * 60)));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            elem.textContent = `${hours}h ${minutes}m ${seconds}s`;
        } else {
            elem.textContent = "Started";
        }
    });
}

updateCountdowns();
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

        <div class="bids-wrapper">
                <!-- My Active Bids -->
                <div class="bids-box">
                    <h3 class="active-text"><span class="dot red"></span> Current Live Bids</h3>
                    <div class="bids-table-wrapper">
                        <table class="bids-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Bid ID</th>
                                    <th>Starting Bid (Rs.)</th>
                                    <th>Highest Bid (Rs.)</th>
                                    <th>Actions</th>
                                    <th>Time Left</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php while($row = $liveBidsResult->fetch_assoc()): ?>
                                <?php
                                    $startingBid = $row['startingBid'];
                                    $highestBid = $row['highestBid'];
                                    $increaseFromStart = $highestBid - $startingBid;
                                    $increaseFormatted = number_format($increaseFromStart);
                                ?>
                                    <tr>
                                        <td><img src="http://localhost/Group-Project-ECommerce/assets/images/<?= $row['image'] ?>" alt="stone"></td>
                                        <td>#<?= $row['biddingStone_id'] ?></td>
                                        <td>
                                            <span class="bid-result pending"><?= number_format($startingBid) ?></span>
                                        </td>
                                        <td>
                                            <span class="bid-result none"><?= number_format($highestBid) ?></span>
                                            <span class="bid-result-difference win"><i class='bx bx-chevrons-up'></i><?= $increaseFormatted ?></span>
                                        </td>
                                        <td>
                                            <a href="./activeBids.php?id=<?= $row['biddingStone_id'] ?>" class="bid-now-button">Details</a>
                                        </td>

                                        <td>
                                            <span class="bid-result loss countdown" data-end="<?= $row['finishDate'] ?>"></span>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Completed Bids -->
                <div class="bids-box">
                    <h3 class="completed-text"><span class="dot green"></span>Completed Bids</h3>
                    <div class="bids-table-wrapper">
                        <table class="bids-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Bid ID</th>
                                    <th>End Date</th>
                                    <th>Starting Bid (Rs.)</th>
                                    <th>Final Value</th>
                                    <th>No Of Bids</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $completedResult->fetch_assoc()): ?>
                                    <?php
                                        $startingBid = $row['startingBid'];
                                        $highestBid = $row['highestBid'];
                                        $increaseFromStart = $highestBid - $startingBid;
                                        $increaseFormatted = number_format($increaseFromStart);
                                    ?>
                                    <tr>
                                        <td><img src="http://localhost/Group-Project-ECommerce/assets/images/<?= $row['image'] ?>" alt="stone"></td>
                                        <td>#<?= $row['biddingStone_id'] ?></td>
                                        <td><?= date('M d, Y H:i', strtotime($row['finishDate'])) ?></td>
                                        <td>
                                            <span class="bid-result pending"><?= number_format($startingBid) ?></span>
                                        </td>
                                        <td>
                                            <span class="bid-result none"><?= number_format($highestBid) ?></span>
                                            <span class="bid-result-difference win">
                                                <i class='bx bx-chevrons-up'></i><?= $increaseFormatted ?>
                                            </span>
                                        </td>
                                        <td><?= $row['totalBids'] ?></td>
                                        <td><?= $row['status'] ?></td>
                                        <td>
                                            <a href="./completedBid.php?id=<?= $row['biddingStone_id'] ?>" class="bid-now-button">Details</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                </tbody>

                        </table>
                    </div>
                </div>
                <!-- Upcoming Bids -->
<div class="bids-box">
    
        <h3 class="upcoming-text"><span class="dot orange"></span>Upcoming Bids</h3>
        <a href="./addBiddingStone.html" class="bid-now-button bid-upcoming">+ Add New</a>

    <div class="bids-table-wrapper">
        <table class="bids-table">
            <thead>
                <tr>
                    <th></th>
                    <th>Bid ID</th>
                    <th>Start Date</th>
                    <th>Starting Bid (Rs.)</th>
                    <th>Time To Begin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $upcomingResult->fetch_assoc()): ?>
                    <?php
                        $startDate = $row['startDate'];
                        $startDiff = date_diff(date_create($currentDateTime), date_create($startDate))->format('%dD %hH %iM');
                    ?>
                    <tr>
                        <td><img src="http://localhost/Group-Project-ECommerce/assets/images/<?= $row['image'] ?>" alt="stone"></td>
                        <td>#<?= $row['biddingStone_id'] ?></td>
                        <td><?= date('M d, Y H:i', strtotime($startDate)) ?></td>
                        <td>
                            <span class="bid-result pending"><?= number_format($row['startingBid']) ?></span>
                        </td>
                        <td>
                            <span class="bid-result win countdown" data-start="<?= $row['startDate'] ?>"></span>
                        </td>

                        <td>
                            <a href="./upcomingBid.php?id=<?= $row['biddingStone_id'] ?>" class="bid-now-button">View</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

            </div>
    </main>
</section>
<script>
function updateCountdowns() {
    const countdownElements = document.querySelectorAll('.countdown');

    countdownElements.forEach(elem => {
        const endDateStr = elem.dataset.end;
        const startDateStr = elem.dataset.start;

        let targetTimeStr = endDateStr || startDateStr;
        const targetTime = new Date(targetTimeStr).getTime();
        const now = new Date().getTime();
        const distance = targetTime - now;

        if (distance > 0) {
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            elem.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
        } else {
            elem.textContent = endDateStr ? "Ended" : "Starting soon...";
        }
    });
}

updateCountdowns();
setInterval(updateCountdowns, 1000);
</script>



<script src="../../../Components/SalesRep_Dashboard_Template/script.js"></script>
<script src="bids.js"></script>
</body>
</html>
