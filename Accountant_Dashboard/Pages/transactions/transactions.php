<?php
// Database connection setup
$servername = "localhost";  // Replace with your database server
$username = "root";         // Replace with your database username
$password = "";             // Replace with your database password
$dbname = "safgems";  // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get filter values from GET request
$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';
$customerFilter = isset($_GET['customer']) ? $_GET['customer'] : '';

// Start building the SQL query
$sql = "
    (SELECT t.transaction_id, t.date, 'Sales' as type, c.email AS email, t.amount, CONCAT(st.colour, ' ', st.type, ' ', st.weight, ' carats') AS stone
     FROM transactions AS t
     JOIN customer AS c ON t.customer_id = c.customer_id
     JOIN inventory AS st ON t.stone_id = st.stone_id
     WHERE 1";  // 1 is a placeholder to allow dynamic WHERE conditions below

// Apply the date filter for transactions
if ($dateFilter) {
    $sql .= " AND DATE(t.date) = '" . $conn->real_escape_string($dateFilter) . "'";
}

// Apply the customer filter for transactions
if ($customerFilter) {
    $sql .= " AND c.email LIKE '%" . $conn->real_escape_string($customerFilter) . "%'";
}

$sql .= ") UNION ALL ";

$sql .= "
    (SELECT p.payment_id AS transaction_id, p.date, 'Purchase' AS type, b.email AS email, p.amount, CONCAT(st.colour, ' ', st.type, ' ', st.weight, ' carats') AS stone
     FROM payment AS p
     JOIN buyer AS b ON p.buyer_id = b.buyer_id
     JOIN inventory AS st ON p.stone_id = st.stone_id
     WHERE 1";  // Same placeholder for WHERE condition

// Apply the date filter for purchases
if ($dateFilter) {
    $sql .= " AND DATE(p.date) = '" . $conn->real_escape_string($dateFilter) . "'";
}

// Apply the customer filter for purchases
if ($customerFilter) {
    $sql .= " AND b.email LIKE '%" . $conn->real_escape_string($customerFilter) . "%'";
}

$sql .= ") ORDER BY date DESC";  // Order by the date column



// Execute the query
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
                            <a class="active" href="#">Gem Stone Sales & Purchases Summary</a>
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

            <div class="table-header">
                <div class="option-tab">
                    <a href="#" class="tab-btn"><i></i>All</a>
                    <a href="../Recievables/invoices.php" class="tab-btn"><i></i>Invoice</a>
                    <a href="../Payables/payments.php" class="tab-btn"><i></i>Payment</a>
                </div>
                <div class="addnew">
                    <a href="./customerType.php" class="btn-add"><i class='bx bx-plus'></i>Add New Trader</a>
                </div>
            </div>

            <?php if (isset($_GET['ReceivalSuccess']) && $_GET['ReceivalSuccess'] == 1): ?>
                <div class="success-message">
                    Payment receival was recorded successfully in Transactions and Sales!
                </div>
            <?php elseif(isset($_GET['ReceivalSuccess']) && $_GET['ReceivalSuccess'] == 2): ?>
                <div class="error-message">
                    An error occurred when recording the payment! Try Again!
                </div>
            <?php elseif(isset($_GET['ReceivalSuccess']) && $_GET['ReceivalSuccess'] == 3): ?>
                <div class="error-message">
                    An error occurred when updating the sales table! Try Again!
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['PaymentSuccess']) && $_GET['PaymentSuccess'] == 1): ?>
                <div class="success-message">
                    Payment was recorded successfully in Payments and Purchases!
                </div>
            <?php elseif(isset($_GET['PaymentSuccess']) && $_GET['PaymentSuccess'] == 2): ?>
                <div class="error-message">
                    An error occurred when recording the payment! Try Again!
                </div>
            <?php endif; ?>

            <div class="sales-table-container">
                <div class="table-filters" >
                    <form method="GET" action="transactions.php">
                    
                        <label for="status-filter">Type:</label>
                        <select id="status-filter">
                            <option value="">All</option>
                            <option value="sale" >Sales</option>
                            <option value="purchase" >Purchase</option>
                        </select>
                
                        <label for="date-filter">Date:</label>
                        <input type="date" id="date-filter" name="date" value="<?php echo htmlspecialchars($dateFilter); ?>">
                    
                        <label for="customer-filter">Customer/Buyer:</label>
                        <input type="text" id="customer-filter"  name="customer" placeholder="Search Customer/Buyer" value="<?php echo htmlspecialchars($customerFilter); ?>">
                    
                        <button class="btn-filter" type="submit" onclick="filterTransactions()">Filter</button>
                        <button><a href="transactions.php" class="btn-clear">Clear</a></button>
                    </form>
                </div>

                <!-- Table -->
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Stone</th>
                            <th>Email</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody id="transaction-body">
                        <?php
                        // Check if results were found and loop through the data
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['transaction_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['stone']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>Rs. " . htmlspecialchars($row['amount']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No transactions found.Query executed but returned no rows.</td></tr>";
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









