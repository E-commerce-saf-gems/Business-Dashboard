<?php
include '../../../database/db.php';

// Initialize variables for filters
$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
$buyerFilter = isset($_GET['buyer']) ? $_GET['buyer'] : '';

// Build the SQL query with filters
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
        JOIN buyer b ON p.buyer_id = b.buyer_id
        WHERE 1=1";

// Apply filters to the query
if (!empty($dateFilter)) {
    $sql .= " AND p.date = '$dateFilter'";
}
if (!empty($statusFilter)) {
    $sql .= " AND (CASE WHEN p.amountSettled = p.amount THEN 'Paid' ELSE 'Pending' END) = '$statusFilter'";
}
if (!empty($buyerFilter)) {
    $sql .= " AND b.email LIKE '%$buyerFilter%'";
}

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

            <div class="sales-table-container">
                <form method="GET" class="table-filters">
                    <label for="date-filter">Date:</label>
                    <input type="date" id="date-filter" name="date" value="<?php echo htmlspecialchars($dateFilter); ?>">
                    
                    <label for="status-filter">Status:</label>
                    <select id="status-filter" name="status">
                        <option value="">All</option>
                        <option value="paid" <?php echo $statusFilter === 'paid' ? 'selected' : ''; ?>>Paid</option>
                        <option value="pending" <?php echo $statusFilter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    </select>

                    <label for="buyer-filter">Buyer:</label>
                    <input type="text" id="buyer-filter" name="buyer" placeholder="Search Buyer" value="<?php echo htmlspecialchars($buyerFilter); ?>">
                    
                    <button type="submit" class="btn-filter">Filter</button>
                </form>

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