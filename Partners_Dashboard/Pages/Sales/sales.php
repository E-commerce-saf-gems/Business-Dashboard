<?php
include '../../../database/db.php';

// Initialize variables for filters
$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
$customerFilter = isset($_GET['customer']) ? $_GET['customer'] : '';

// Build the SQL query with filters
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
        JOIN customer c ON s.customer_id = c.customer_id
        WHERE 1=1";

// Apply filters to the query
if (!empty($dateFilter)) {
    $sql .= " AND s.date = '$dateFilter'";
}
if (!empty($statusFilter)) {
    $sql .= " AND (CASE WHEN s.amountSettled = s.total THEN 'Paid' ELSE 'Pending' END) = '$statusFilter'";
}
if (!empty($customerFilter)) {
    $sql .= " AND c.email LIKE '%$customerFilter%'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales</title>
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
                    <h1>Sales</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a class="active" href="#">Sales Summary</a>
                        </li>
                    </ul>
                </div>
            </div>

            
            <div class="sales-table-container">
                <form method="GET" class="table-filters">
                    <label for="date-filter">Date:</label>
                    <input type="date" id="date-filter" name="date" value="<?php echo htmlspecialchars($dateFilter); ?>">
                    
                    <label for="status-filter">Status:</label>
                    <select id="status-filter" name="status">
                        <option value="">All</option>
                        <option value="Paid" <?php echo $statusFilter === 'Paid' ? 'selected' : ''; ?>>Paid</option>
                        <option value="Pending" <?php echo $statusFilter === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    </select>

                    <label for="customer-filter">Customer:</label>
                    <input type="text" id="customer-filter" name="customer" placeholder="Search Customer" value="<?php echo htmlspecialchars($customerFilter); ?>">
                    
                    <button type="submit" class="btn-filter">Filter</button>
                </form>

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

    <script src="../../../Components/Partner_Dashboard_Template/script.js"></script>
    <script src="./sales.js"></script>
</body>
</html>

<?php
$conn->close();
?>