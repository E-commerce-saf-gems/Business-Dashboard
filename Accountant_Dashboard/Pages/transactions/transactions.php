<?php
include '../../../database/db.php';

$sql = "SELECT t.transaction_id, t.date, t.type, CONCAT(i.shape, ' ', i.colour, ' ', i.type) AS gem_name, c.firstname AS customer_name, 
               b.name AS buyer_name, t.amount, t.status
        FROM transactions t
        JOIN customer c ON t.customer_id = c.customer_id
        JOIN inventory i ON t.stone_id = i.stone_id
        JOIN buyer b ON t.buyer_id = b.buyer_id";
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
                <a href="./addTransaction.html" class="btn-add"><i class='bx bx-plus'></i>Add New</a>
            </div>
            
            <div class="sales-table-container">
                <div class="table-filters">
                    <label for="date-filter">Date:</label>
                    <input type="date" id="date-filter">
                    
                    <label for="status-filter">Status:</label>
                    <select id="status-filter">
                        <option value="">All</option>
                        <option value="paid">Completed</option>
                        <option value="pending">Pending</option>
                    </select>

                    <label for="customer-filter">Customer:</label>
                    <input type="text" id="customer-filter" placeholder="Search Customer">
                    
                    <button class="btn-filter">Filter</button>
                </div>

                
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Gem Name</th>
                            <th>Customer Name</th>
                            <th>Supplier Name</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Trim and normalize the status value
                            $status = (trim($row['status']));

                            // Determine the status label and color
                            $statusLabel = $status === 'Completed' ? 'Completed' : 'Pending';
                            $statusColor = $status === 'Completed' ? 'color: green;' : 'color: red;';

                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['transaction_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['gem_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['buyer_name']) . "</td>";
                            echo "<td>$" . htmlspecialchars($row['amount']) . "</td>";
                            echo "<td style='$statusColor'>$statusLabel</td>";
                            echo "<td class='actions'>
                                <a href='./editTransaction.php?transaction_id=" . $transaction['transaction_id'] . "' class='btn'><i class='bx bx-pencil'></i> Edit</a>
                                <a href='#' class='btn' onclick='deleteTransaction(" . $transaction['transaction_id'] . ")'><i class='bx bx-trash'></i> Delete</a>
                                <a href='#' class='btn printBtn' onclick='printTransaction()'><i class='bx bx-printer'></i> Print</a>
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
    <script scr="./script.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>








