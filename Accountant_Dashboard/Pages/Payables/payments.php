<?php
include '../../../database/db.php';

$sql = "SELECT p.payment_id, p.date, b.email AS email, p.amount
        FROM payment as p
        JOIN buyer as b ON p.buyer_id = b.buyer_id";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accountant Transactions</title>
    <link rel="stylesheet" href="../../../Components/Accountant_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="../transactions/styles.css"> 
       
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <dashboard-component></dashboard-component>

    <section id="content">
        <main>
            <div class="head-title">
				<div class="left">
					<h1>Payments</h1>
					<ul class="breadcrumb">
						<li><a class="active" href="../transactions/transactions.php">Home</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="#">Payments Summary</a></li>
					</ul>
				</div>
			</div>

            <div class="sales-summary-box">
                <div class="sales-summary-title">
                    <h2>Monthly Payments Summary</h2>
                </div>
                <div class="sales-item">
                    <h3>This Month</h3>
                    <p>$2543</p>
                </div>
                <div class="sales-item">
                    <h3>Last Month</h3>
                    <p>$2132</p>
                </div>
                <div class="sales-item">
                    <h3>Last Two Months</h3>
                    <p>$4089</p>
                </div>
            </div>

            <div class="addnew">
                <a href="./addPayments.html" class="btn-add"><i class='bx bx-plus'></i>Add New</a>
            </div>


            
            <div class="sales-table-container">
                <div class="table-filters">
                    <label for="date-filter">Date:</label>
                    <input type="date" id="date-filter">
                    
                    <label for="customer-filter">Email:</label>
                    <input type="text" id="customer-filter" placeholder="Search Buyer">
                    
                    <button class="btn-filter">Filter</button>
                </div>

                <!-- Table -->
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Date</th>
                            <th>Buyer Email</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Determine the status label and color
                               
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['payment_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>Rs. " . htmlspecialchars($row['amount']) . "</td>";
                                echo "<td class='actions'>
                                        <a href='./editTransaction.html' class='btn'><i class='bx bx-pencil'></i></a>
                                        <a class='btn'><i class='bx bx-trash'></i></a>
                                        <a class='btn'><i class='bx bx-printer'></i></a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9'>No transactions found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>    
        </main>
    </section>

    <script src="../../../Components/Accountant_Dashboard_Template/script.js"></script>
    <script src="./script.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>