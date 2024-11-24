<?php
include '../../../database/db.php';

$sql = "SELECT t.transaction_id, t.date, 'Sales' as type, c.email AS email, t.amount , CONCAT(st.colour,' ',st.type,' ',st.weight,' ',' carats') AS stone
        FROM transactions as t
        JOIN customer as c ON t.customer_id = c.customer_id
        JOIN inventory as st ON t.stone_id = st.stone_id
        UNION ALL
        SELECT p.payment_id, p.date, 'Purchase' as type, b.email AS email, p.amount, CONCAT(st.colour,' ',st.type,' ',st.weight,' ',' carats') AS stone
        FROM payment as p
        JOIN buyer as b ON p.buyer_id = b.buyer_id
        JOIN inventory as st ON p.stone_id = st.stone_id
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
    <link rel="stylesheet" href="./expenses.css">   
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <dashboard-component></dashboard-component>

    <section id="content">
        <main>
            <div class="head-title">
				<div class="left">
					<h1>Expense Management</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="#">Expenses Summary</a>
						</li>
					</ul>
				</div>
                

			</div>

            
            <div class="sales-summary-box">
                
                <div class="sales-summary-title">
                    <h2>Expenses Preview</h2>
                </div>
                <div class="sales-item">
                    <h3>Weekly Total Expenses</h3>
                    <p>$543</p>
                </div>
                <div class="sales-item">
                    <h3>Monthly Total Expenses</h3>
                    <p>$2132</p>
                </div>
                <div class="sales-item">
                    <h3>Top Expense Catagory</h3>
                    <p>Marketing</p>
                </div>
            </div>

            

            <div class="addnew">
                <a href="./addExpenses.html" class="btn-add"><i class='bx bx-plus'></i>Add New</a>
            </div>
            
            <div class="sales-table-container">
                <div class="table-filters">
                    <label for="date-filter">Date:</label>
                    <input type="date" id="date-filter">
                    
                    <label for="status-filter">Category:</label>
                    <select id="status-filter">
                        <option value="">--All--</option>
                        <option value="pending">Purchasing Row Materials</option>
                        <option value="pending">Salleries</option>
                        <option value="paid">Marketing</option>
                        <option value="pending">Logistics</option>
                        <option value="paid">Rent & Utitlities</option>
                    </select>

                    
                    
                    <button class="btn-filter">Filter</button>
                </div>

                <!-- Table -->
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Expense No</th>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                
                               
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['transaction_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['stone']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>Rs. " . htmlspecialchars($row['amount']) . "</td>";
                                
                                
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
    <script scr="expenses.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>