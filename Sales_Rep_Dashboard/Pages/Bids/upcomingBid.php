<?php
// Connect to DB
include '../../../database/db.php'; // adjust the path if needed

// Get stone_id from query
$biddingStoneId = isset($_GET['biddingStone_id']) ? intval($_GET['biddingStone_id']) : 0;

// Fetch bid info
$bidQuery = $conn->prepare("SELECT * FROM biddingstone WHERE biddingStone_id = ?");
$bidQuery->bind_param("i", $biddingStoneId);
$bidQuery->execute();
$bidResult = $bidQuery->get_result();
$bid = $bidResult->fetch_assoc();

// Calculate Time Left
$now = new DateTime();
$finish = new DateTime($bid['finishDate']);

if ($now > $finish) {
    $timeLeft = "Ended";
} else {
    $interval = $now->diff($finish);
    $timeLeft = $interval->format('%a days, %h hours, %i minutes');
}

// Placeholder for users interested (replace this with real logic if applicable)
$usersInterested = 7; // dummy number
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Upcoming Bid</title>
  <link rel="stylesheet" href="../../../Components/SalesRep_Dashboard_Template/styles.css">
  <link rel="stylesheet" href="../../styles.css">
  <link rel="stylesheet" href="./sadheeyaBids.css">
</head>
<body>
  <dashboard-component></dashboard-component>
  <section id="content">
    <main>
      <div class="head-title">
                <div class="left">
                    <h1>Upcoming Bidding Stone</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a class="active" href="#">Upcoming Bidding Stone Details</a>
                        </li>
                    </ul>
                </div>
            </div>
      <div class="bid-details-container">
        <div class="bid-details-box">
          <div class="bid-header">
            <div class="bid-title">
              <h2>Bid No #<?= $bid['biddingStone_id'] ?? 'N/A' ?></h2>
            </div>
            <div class="bid-status">
              <span class="dot dot-upcoming"></span>
              <span class="live-text-upcoming">Upcoming</span>
            </div>
          </div>

          <div class="bid-info-box">
            <div class="bid-image-section">
              <img src="../../../../Group-Project-ECommerce/assets/images/stone11.jpg" alt="Stone" class="main-image">
              <img src="../../../../Group-Project-ECommerce/assets/images/certificate.jpg" alt="Certificate" class="cert-image">
            </div>

            <div class="bid-info">
              <div><strong>Stone ID:</strong> <span class="info-value"><?= $bid['biddingStone_id'] ?></span></div>
              <div><strong>Starting Bid:</strong> <span class="info-value">$<?= number_format($bid['startingBid']) ?></span></div>
              <div><strong>No Of Cycles:</strong> <span class="info-value"><?= $bid['no_of_Cycles'] ?></span></div>
              <div><strong>Cycle Time:</strong> <span class="info-value"><?= $bid['cycle_duration'] ?></span></div>
            </div>

            <div class="bid-info">
              <div><strong>End Date:</strong> <span class="info-value"><?= date('Y-m-d', strtotime($bid['finishDate'])) ?></span></div>
              <div><strong>Time Left:</strong> <span class="info-value"><?= $timeLeft ?></span></div>
              <div><strong>No Of Users Interested:</strong> <span class="info-value"><?= $usersInterested ?></span></div>
              <div>
                <a href="./editBid.php?biddingStone_id=<?= $bid['biddingStone_id'] ?>" class="add-new-btn">Edit</a>
                <a href="deleteBid.php?biddingStone_id=<?= $bid['biddingStone_id'] ?>" class="add-new-btn" onclick="return confirm('Are you sure you want to delete this bid?');">
                    Delete
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </section>
  
  <script src="../../../Components/SalesRep_Dashboard_Template/script.js"></script>
  <script src="./bids.js"></script>

</body>
</html>

