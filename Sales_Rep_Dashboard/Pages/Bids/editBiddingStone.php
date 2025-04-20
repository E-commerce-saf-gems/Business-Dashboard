<?php
include '../../../database/db.php'; // Adjust the path as needed

// Check if biddingstone_id is provided
if (isset($_GET['biddingstone_id'])) {
    $biddingstone_id = $_GET['biddingstone_id'];

    // Fetch the bidding stone details
    $query = "SELECT * FROM biddingstone WHERE biddingstone_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $biddingstone_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stone = $result->fetch_assoc();
    } else {
        echo "Bidding Stone not found.";
        exit;
    }
} else {
    echo "Bidding Stone ID not provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Bidding Stone</title>
    <link rel="stylesheet" href="../../../Components/Accountant_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="../../../Accountant_Dashboard/Pages/transactions/edittransactionstyles.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
<dashboard-component></dashboard-component>

<section id="content">
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Bidding Stones</h1>
                <ul class="breadcrumb">
                    <li><a href="./stonessummary.php">Home</a></li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li><a class="active" href="#">Edit Bidding Stone</a></li>
                </ul>
            </div>
        </div>

        <div class="edit-sales-container">
            <form class="edit-sales-form" id="editBiddingForm" method="POST" action="updateBiddingStone.php">
                <h2>Edit Bidding Stone Details</h2>

                
                <input type="hidden" name="biddingstone_id" value="<?php echo $biddingStone['biddingstone_id']; ?>">
                
                <div class="form-group">
                    <label for="stone">Stone</label>
                    <input type="text" id="stone" name="stone" value="<?php echo htmlspecialchars($biddingStone['stone']); ?>" required />
                </div>

                <div class="form-group">
                    <label for="startingBid">Starting Bid (Rs)</label>
                    <input type="number" id="startingBid" name="startingBid" value="<?php echo $stone['startingBid']; ?>" required />
                </div>

                <div class="form-group">
                    <label for="currentBid">Current Bid (Rs)</label>
                    <input type="number" id="currentBid" name="currentBid" value="<?php echo $stone['currentBid']; ?>" required />
                </div>

                <div class="form-group">
                    <label for="no_of_Cycles">Number of Cycles</label>
                    <input type="number" id="no_of_Cycles" name="no_of_Cycles" value="<?php echo $stone['no_of_Cycles']; ?>" required />
                </div>

                <div class="form-group">
                    <label for="startDate">Start Date</label>
                    <input type="date" id="startDate" name="startDate" value="<?php echo $stone['startDate']; ?>" required />
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-save">
                        <i class='bx bx-save'></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </main>
</section>

<script src="../../../Components/Accountant_Dashboard_Template/script.js"></script>
</body>
</html>

