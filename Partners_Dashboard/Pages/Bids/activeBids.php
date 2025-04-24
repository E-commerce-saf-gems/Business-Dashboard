<?php
include '../../../database/db.php';  

date_default_timezone_set('Asia/Kolkata');
$currentDateTime = date('Y-m-d H:i:s');

$biddingStoneId = $_GET['id']; 
$biddingStoneQuery = "
    SELECT bs.*, 
           i.image,  
           (SELECT MAX(amount) FROM bid WHERE biddingStone_id = bs.biddingStone_id AND validity='valid') AS highestBid,
           (SELECT COUNT(DISTINCT customer_id) FROM bid WHERE biddingStone_id = bs.biddingStone_id) AS uniqueBidders
    FROM biddingstone bs
    JOIN inventory i ON bs.stone_id = i.stone_id  
    WHERE bs.biddingStone_id = $biddingStoneId
";

$biddingStoneResult = $conn->query($biddingStoneQuery);
$biddingStone = $biddingStoneResult->fetch_assoc();

$bidsQuery = "
    SELECT b.bid_id,b.validity, b.amount, b.time, c.firstName AS bidderName, (SELECT COUNT(*) FROM bid WHERE biddingStone_id = $biddingStoneId) AS totalNoOfBids
    FROM bid b
    INNER JOIN customer c ON b.customer_id = c.customer_id
    WHERE b.biddingStone_id = $biddingStoneId
    ORDER BY b.time DESC
";
$bidsResult = $conn->query($bidsQuery);
$bids = [];
while ($row = $bidsResult->fetch_assoc()) {
    $bids[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Accountant Bids</title>
  <link rel="stylesheet" href="../../../Components/SalesRep_Dashboard_Template/styles.css">
  <link rel="stylesheet" href="../../styles.css">
  <link rel="stylesheet" href="./sadheeyaBids.css">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <dashboard-component></dashboard-component>

  <?php if($biddingStoneResult->num_rows ==0):?>
  <section id="content">
    <main>
      <h2>This Stone Is Currently Not Active. Check Upcoming Or Completed Bids</h2>
    </main>
  </section>
  <?php endif?>

  <?php if($biddingStoneResult->num_rows >0):?>
  <section id="content">
    <main>
      <div class="bid-details-container">
        <div class="bid-details-box">
          <div class="bid-header">
            <div class="bid-title">
              <h2>Bid No #<?= $biddingStone['biddingStone_id'] ?></h2>
            </div>
            <div class="bid-status">
              <span class="dot dot-active"></span>
              <span class="live-text-active">Live</span>
            </div>
          </div>

          <div class="bid-info-box">
            <div class="bid-image-section main-image">
              <img src="http://localhost/Group-Project-ECommerce/assets/images/<?= $biddingStone['image'] ?>" alt="stone">
            </div>

            <div class="bid-info">
              <div><strong>Stone ID:</strong> <span class="info-value"><?= $biddingStone['stone_id'] ?></span></div>
              <div><strong>Starting Bid:</strong> <span class="info-value"><?= number_format($biddingStone['startingBid']) ?></span></div>
              <div><strong>Start Date:</strong> <span class="info-value"><?= $biddingStone['startDate'] ?></span></div>
              <div><strong>End Date:</strong> <span class="info-value"><?= $biddingStone['finishDate'] ?></span></div>
            </div>

            <div class="bid-info">
              <?php
                // Calculate the time left between current time and finish date
                $finishDate = $biddingStone['finishDate'];
                $timeLeft = date_diff(date_create($currentDateTime), date_create($finishDate));
                
                // Format the time left (only Days, Hours, and Minutes)
                $timeLeftFormatted = $timeLeft->invert ? 'Expired' : $timeLeft->format('%dD %hH %iM');
              ?>
              <div><strong>Time Left:</strong> <span class="info-value"><?= $timeLeftFormatted ?></span></div>

              <div><strong>Total No Of Bids:</strong> 
                <span class="info-value"><?= isset($bids[0]) ? $bids[0]['totalNoOfBids'] : 0 ?></span>
              </div>
              <div><strong>No. of Bidders:</strong> <span class="info-value"><?= $biddingStone['uniqueBidders'] ?></span></div>
              <div><strong>Current Highest:</strong> <span class="info-value"><?= number_format($biddingStone['highestBid']) ?></span></div>
            </div>
          </div>
        </div>

        <div class="bid-cycle-container">
          <table class="bids-table">
            <thead>
              <tr>
                <th>Bid ID</th>
                <th>Date</th>
                <th>Time</th>
                <th>Value</th>
                <th>Bidder Name</th>
                <th>Validity</th>
              </tr>
            </thead>
            <tbody>
              <?php
              for ($i = 0; $i < count($bids); $i++):
                  $current = $bids[$i];
                  $next = $bids[$i + 1] ?? null;
                  $increase = ($next !== null) ? $current['amount'] - $next['amount'] : 0;
              ?>
              <tr>
                <td>#<?= $current['bid_id'] ?></td>
                <td><?= date('d M Y', strtotime($current['time'])) ?></td>
                <td><?= date('h:i A', strtotime($current['time'])) ?></td>
                <td>
                  <span class="bid-result win"><?= number_format($current['amount']) ?></span>
                  <?php if ($next !== null): ?>
                    <span class="bid-result-difference win">
                      <i class='bx bx-chevrons-up'></i><?= number_format($increase) ?>
                    </span>
                  <?php endif; ?>
                </td>
                <td><?= $current['bidderName'] ?></td>
                <td>
                  <?php if($current['validity'] == 'invalid'): ?> 
                    <span class="bid-result loss">
                      <?= $current['validity'] ?>
                    </span>
                  <?php else: ?>
                    <span class="bid-result win">
                      <?= $current['validity'] ?>
                    </span>
                  <?php endif; ?>
              </td>
              </tr>
              <?php endfor; ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </section>
  <?php endif?>

  <script src="../../../Components/SalesRep_Dashboard_Template/script.js"></script>
  <script src="./bids.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
</body>
</html>
