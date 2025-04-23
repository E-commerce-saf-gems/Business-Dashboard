<?php
session_start();
$customer_id = $_SESSION['customer_id'];

include '../../../database/db.php';  

date_default_timezone_set('Asia/Kolkata');
$currentDateTime = date('Y-m-d H:i:s');

$biddingStoneId = $_GET['id']; 
$biddingStoneQuery = "
    SELECT bs.*, 
           i.image  
    FROM biddingstone bs
    JOIN inventory i ON bs.stone_id = i.stone_id  
    WHERE bs.biddingStone_id = $biddingStoneId
      AND bs.startDate > '$currentDateTime'
";

$biddingStoneResult = $conn->query($biddingStoneQuery);
$biddingStone = $biddingStoneResult->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Upcoming Bid Details</title>
  <link rel="stylesheet" href="../../../Components/SalesRep_Dashboard_Template/styles.css">
  <link rel="stylesheet" href="../../styles.css">
  <link rel="stylesheet" href="./sadheeyaBids.css">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <style>
    .bid-image-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }

    .main-image {
        width: 120px;
        height: 120px;
    }
  </style>
</head>
<body>
  <dashboard-component></dashboard-component>

  <section id="content">
    <main>
      <div class="bid-details-container">
        <div class="bid-details-box">
          <div class="bid-header">
            <div class="bid-title">
              <h2>Bid No #<?= $biddingStone['biddingStone_id'] ?></h2>
            </div>
            <div class="bid-status">
              <span class="dot dot-upcoming"></span>
              <span class="live-text-upcoming">Upcoming</span>
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
            </div>

            <div class="bid-info">
            <div><strong>End Date:</strong> <span class="info-value"><?= $biddingStone['finishDate'] ?></span></div>
              <div><strong>Starts In:</strong> 
                <span id="countdown" class="bid-result win"></span>
              </div>
              <div class="bid-actions" style="margin-top: 20px; display: flex; gap: 12px; justify-content: center;">
                <a href="editBid.php?id=<?= $biddingStone['biddingStone_id'] ?>" class="action-button" style="padding: 8px 16px; background-color: #007bff; color: white; border-radius: 5px; text-decoration: none;">Edit</a>
                <a href="deleteBid.php?id=<?= $biddingStone['biddingStone_id'] ?>" class="action-button" style="padding: 8px 16px; background-color: #dc3545; color: white; border-radius: 5px; text-decoration: none;" onclick="return confirm('Are you sure you want to delete this bid?');">Delete</a>
              </div>

            </div>
          </div>
        </div>

        <div class="bid-cycle-container">
          <p class="no-bids-text">No bids available yet. The bidding starts on <strong><?= $biddingStone['startDate'] ?></strong>.</p>
        </div>
      </div>
    </main>
  </section>

  <script>
    const countdownEl = document.getElementById("countdown");
    const startTime = new Date("<?= $biddingStone['startDate'] ?>").getTime();

    function updateCountdown() {
      const now = new Date().getTime();
      const diff = startTime - now;

      if (diff <= 0) {
        countdownEl.textContent = "Starting soon...";
        clearInterval(interval);
        return;
      }

      const days = Math.floor(diff / (1000 * 60 * 60 * 24));
      const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((diff % (1000 * 60)) / 1000);

      countdownEl.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
    }

    updateCountdown();
    const interval = setInterval(updateCountdown, 1000);
  </script>


  <script src="../../../Components/SalesRep_Dashboard_Template/script.js"></script>
</body>
</html>
