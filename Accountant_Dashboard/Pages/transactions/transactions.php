<?php
include '../../../database/db.php';

$sql = "SELECT t.transaction_id, t.date, t.type, c.email AS email, t.amount
        FROM transactions as t
        JOIN customer as c ON t.customer_id = c.customer_id
        UNION ALL
        SELECT p.payment_id, p.date, p.type, b.email AS email, p.amount
        FROM payment as p
        JOIN buyer as b ON p.buyer_id = b.buyer_id
        ORDER BY date DESC";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accountant Dashboard</title>
    <link rel="stylesheet" href="../../../Components/Accountant_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="./styles.css">   
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <dashboard-component></dashboard-component>

    <section id="content">
        <main>
            <div class="head-title">
				<div class="left">
					<h1>Transactions</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="#">Transactions Summary</a>
						</li>
					</ul>
				</div>
			</div>

            <div class="sales-summary-box">
                <div class="sales-summary-title">
                    <h2>Monthly Transactions Summary</h2>
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
                <a href="./customerType.html" class="btn-add"><i class='bx bx-plus'></i>Add New</a>
            </div>

            <?php if (isset($_GET['ReceivalSuccess']) && $_GET['ReceivalSuccess'] == 1): ?>
                <div class="success-message">
                    Payment receival was recorded successfully in Transactions and Sales!
                </div>
            <?php elseif(isset($_GET['ReceivalSuccess']) && $_GET['ReceivalSuccess'] == 2) : ?>
                <div class="error-message">
                    An error occured when recording the payment! Try Again!
                </div>
            <?php elseif(isset($_GET['ReceivalSuccess']) && $_GET['ReceivalSuccess'] == 3) : ?>
            <div class="error-message">
                An error occured when updating the sales table! Try Again!
            </div>
            <?php endif; ?>
            

            <?php if (isset($_GET['PaymentSuccess']) && $_GET['PaymentSuccess'] == 1): ?>
                <div class="success-message">
                    Payment was recorded successfully in Payments and Purchases!
                </div>
            <?php elseif(isset($_GET['PaymentSuccess']) && $_GET['PaymentSuccess'] == 2) : ?>
                <div class="error-message">
                    An error occured when recording the payment! Try Again!
                </div>
            <?php endif; ?>


            <div class="sales-table-container">
                <div class="table-filters">
                    <label for="date-filter">Date:</label>
                    <input type="date" id="date-filter">
                    
                    <label for="status-filter">Status:</label>
                    <select id="status-filter">
                        <option value="">All</option>
                        <option value="completed">Completed</option>
                        <option value="pending">Pending</option>
                    </select>

                    <label for="customer-filter">Customer:</label>
                    <input type="text" id="customer-filter" placeholder="Search Customer">
                    
                    <button class="btn-filter">Filter</button>
                </div>

                <!-- Table -->
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Customer Email</th>
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
                                echo "<td>" . htmlspecialchars($row['transaction_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>Rs. " . htmlspecialchars($row['amount']) . "</td>";
                                echo "<td class='actions'>
                                        <a href='./editTransaction.html' class='btn'><i class='bx bx-pencil'></i></a>
                                        <a class='btn'><i class='bx bx-trash'></i></a>
                                        <a class='btn printBtn'><i class='bx bx-printer'></i></a>
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








