<?php
include '../../../database/db.php';

// Get filter values from GET request
$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';
$customerFilter = isset($_GET['customer']) ? $_GET['customer'] : '';

$sql = "SELECT bs.biddingstone_id, bs.startingBid , bs.currentBid , bs.startDate, bs.finishDate, bs.no_of_Cycles , CONCAT(st.colour, ' ' ,st.shape, ' ' ,st.type, ' ' ,st.weight, ' carats' ) AS stone
        FROM biddingstone as bs
        JOIN inventory as st ON bs.stone_id = st.stone_id
        WHERE 1";

// Apply the date filter for transactions
if ($dateFilter) {
    $sql .= " AND DATE(t.date) = '" . $conn->real_escape_string($dateFilter) . "'";
}

// Apply the customer filter for transactions
if ($customerFilter) {
    $sql .= " AND c.email LIKE '%" . $conn->real_escape_string($customerFilter) . "%'";
}
$sql .= " ORDER BY bs.startDate DESC";  // Order by the date column


$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accountant Bids</title>
    <link rel="stylesheet" href="../../../Components/SalesRep_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="./bids.css"> 
    <link rel="stylesheet" href="../../transactions/styles.css"> 
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <dashboard-component></dashboard-component>

    <section id="content">
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Bidding Stones Summary</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="./bids.html">Home</a>
                        </li>
                        <li><i class='bx bx-chevron-right' ></i></li>
                        <li>
                            <a class="active" href="#">Bidding Stones Summary</a>
                        </li>
                    </ul>
                </div>
            </div>

            
            <div class="sales-summary-title">
                <h2>Monthly Bidding Stones Summary</h2>
            </div>
            
            <div class="summary-cards">
                
                
                <div class="card">
                    <h3>Total Gems Auctioned</h3>
                    <p style="margin-top: 15px;">20</p>
                </div>
                <div class="card">
                    <h3>Total Sold Gems</h3>
                    <p style="margin-top: 15px;">18</p>
                </div>
                <div class="card">
                    <h3>Most Popular Gem Type</h3>
                    <p style="margin-top: 15px;">Ruby</p>
                </div>
                <div class="card">
                    <h3>Highest Bid on a Single Gem</h3>
                    <p>Rs.40000.00</p>
                </div>
            </div>


            <div class="addnew">
                <a href="./addBiddingStone.html" class="btn-add"><i class='bx bx-plus'></i>Add New</a>
            </div>

            <div class="sales-table-container">
                <div class="table-filters">
                    <label for="date-filter">Date:</label>
                    <input type="date" id="date-filter">
                    
                    <label for="customer-filter">Stone:</label>
                    <input type="text" id="customer-filter" placeholder="Search Stone">
                    
                    <button class="btn-filter">Filter</button>
                </div>

                <!-- Table -->
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Stone</th>
                            <th>Starting Bid</th>
                            <th>Current Bid</th>
                            <th>NO.of Cycles</th>
                            <th>Start Date</th>
                            <th>Finish Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ( $result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Determine the status label and color
                               
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['stone']) . "</td>";
                                echo "<td>Rs." . htmlspecialchars($row['startingBid']) . "</td>";
                                echo "<td>Rs." . htmlspecialchars($row['currentBid']) . "</td>";
                                echo "<td> " . htmlspecialchars($row['no_of_Cycles']) . "</td>";
                                echo "<td> " . htmlspecialchars($row['startDate']) . "</td>";
                                echo "<td> " . htmlspecialchars($row['finishDate']) . "</td>";
                                
                                echo "<td class='actions'>
                                        <a href='./editTransactions.php?biddingstone_id=" . $row['biddingstone_id'] . "' class='btn'><i class='bx bx-pencil'></i></a>
                                        <button class='btn deleteBtn' data-id='" . $row['biddingstone_id'] . "'><i class='bx bx-trash'></i></button>
                                    </td>";
                                
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No transactions found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>    
        </main>
    </section>


    <script src="../../../Components/SalesRep_Dashboard_Template/script.js"></script>
    <script scr="./bids.js"></script>
</body>
</html>