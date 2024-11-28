<?php
include '../../../database/db.php';

// Fetch data from the 'sales', 'inventory', and 'customer' tables using a JOIN
$sql = "SELECT 
            s.sale_id, 
            s.date, 
            c.email AS customer_email, 
            i.type AS type, 
            s.total, 
            s.amountSettled,
            CASE 
                WHEN s.amountSettled = s.total THEN 'Paid'
                ELSE 'Pending'
            END AS status
        FROM sales s
        JOIN inventory i ON s.stone_id = i.stone_id
        JOIN customer c ON s.customer_id = c.customer_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales</title>
    <link rel="stylesheet" href="../../../Components/SalesRep_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="./salesStyles.css">   
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <dashboard-component></dashboard-component>

    <section id="content">
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Sales</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a class="active" href="#">Sales Summary</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sales-summary-box">
                <div class="sales-summary-title">
                    <h2>Monthly Sales Summary</h2>
                </div>
                <div class="sales-item">
                    <h3>This Month</h3>
                    <p>Rs. 254300</p>
                </div>
                <div class="sales-item">
                    <h3>Last Month</h3>
                    <p>Rs. 213200</p>
                </div>
                <div class="sales-item">
                    <h3>Last Two Months</h3>
                    <p>Rs. 408900</p>
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

                    <label for="customer-filter">Customer:</label>
                    <input type="text" id="customer-filter" placeholder="Search Customer">
                    
                    <button class="btn-filter">Filter</button>
                </div>

                <!-- Table -->
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Email</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Amount Settled</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Check if there are results and display each row in the table
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['date'] . "</td>";
                                echo "<td>" . $row['customer_email'] . "</td>";
                                echo "<td>" . $row['type'] . "</td>";
                                echo "<td>Rs. " . number_format($row['total'], 0, '.', ',') . "</td>";
                                echo "<td>" . $row['status'] . "</td>";
                                echo "<td>Rs. " . number_format($row['amountSettled'], 0, '.', ',') . "</td>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No sales found.</td></tr>";
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

    <script src="../../../Components/SalesRep_Dashboard_Template/script.js"></script>
    <script src="./sales.js"></script>
</body>
</html>

<?php
$conn->close();
?>
