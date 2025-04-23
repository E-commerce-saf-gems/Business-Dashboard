<?php
include '../../../database/db.php';

// Fetch data from the 'purchases', 'stone', and 'buyer' tables using a JOIN
$sql = "SELECT 
            p.purchase_id, 
            p.date, 
            b.email AS buyer_email, 
            i.type AS stone_type, 
            p.amount, 
            p.amountSettled,
            CASE 
                WHEN p.amountSettled = p.amount THEN 'Paid'
                ELSE 'Pending'
            END AS status
        FROM purchases p
        JOIN inventory i ON p.stone_id = i.stone_id
        JOIN buyer b ON p.buyer_id = b.buyer_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchases</title>
    <link rel="stylesheet" href="../../../Components/Partner_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="./salesStyles.css">   
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <dashboard-component></dashboard-component>

    <section id="content">
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Purchases</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a class="active" href="#">Purchases Summary</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sales-summary-box">
                <div class="sales-summary-title">
                    <h2>Monthly Purchases Summary</h2>
                </div>
                <div class="sales-item">
                    <h3>This Month</h3>
                    <p>Rs. 354200</p>
                </div>
                <div class="sales-item">
                    <h3>Last Month</h3>
                    <p>Rs. 298500</p>
                </div>
                <div class="sales-item">
                    <h3>Last Two Months</h3>
                    <p>Rs. 652700</p>
                </div>
            </div>

            <div class="sales-table-container">
                <div class="table-filters">
                    <label for="date-filter">Date:</label>
                    <input type="date" id="date-filter">
                    
                    <label for="status-filter">Status:</label>
                    <select id="status-filter">
                        <option value="">All</option>
                        <option value="Paid">Paid</option>
                        <option value="Pending">Pending</option>
                    </select>

                    <label for="buyer-filter">Buyer:</label>
                    <input type="text" id="buyer-filter" placeholder="Search Buyer">
                    
                    <button class="btn-filter">Filter</button>
                </div>

                <!-- Table -->
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Buyer Email</th>
                            <th>Stone Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Amount Settled</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Check if there are results and display each row in the table
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['date'] . "</td>";
                                echo "<td>" . $row['buyer_email'] . "</td>";
                                echo "<td>" . $row['stone_type'] . "</td>";
                                echo "<td>Rs. " . number_format($row['amount'], 0, '.', ',') . "</td>";
                                echo "<td>" . $row['status'] . "</td>";
                                echo "<td>Rs. " . number_format($row['amountSettled'], 0, '.', ',') . "</td>";
                                echo "<td class='actions'>";
                                echo "<a class='btn printBtn'><i class='bx bx-printer'></i></a>";
                                echo "<a class='btn'><i class='bx bx-trash'></i></a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No purchases found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>    
        </main>
    </section>

    <script>
        setTimeout(function() {
            const message = document.querySelector(".success-message");
            if (message) {
                message.style.display = "none";
            }
        }, 5000);
    </script>

    <script src="../../../Components/Partner_Dashboard_Template/script.js"></script>
    <script src="./sales.js"></script>
</body>
</html>

<?php
$conn->close();
?>
