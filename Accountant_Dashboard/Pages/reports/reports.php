<?php
include '../../../database/db.php';

// Get filter values from GET request
$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';
$categoryFilter = isset($_GET['type']) ? $_GET['type'] : '';

$sql = "SELECT report_id, report_type, generated_date FROM reports WHERE 1";

// Apply the date filter
if ($dateFilter) {
    $sql .= " AND DATE(generated_date) = '" . $conn->real_escape_string($dateFilter) . "'";
}

// Apply the category filter
if ($categoryFilter) {
    $sql .= " AND report_type LIKE '%" . $conn->real_escape_string($categoryFilter) . "%'";
}

$sql .= " ORDER BY generated_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accountant Reports</title>
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
                    <h1>Generate Report</h1>
                </div>
            </div>

            <div class="report-boxes-container">
                <div class="report-box">
                    <h2>Profit and Loss Report</h2>
                    <p>The Profit and Loss Report shows our business revenue, costs, expenses, and net profit over a specific period.</p>
                    <a href="./profitreport.php" class="report-link">Generate Report</a>
                </div>

                <div class="report-box">
                    <h2>Sales Report</h2>
                    <p>The Sales Report provides insights into total sales, sales by gem type, auction vs. regular sales, and customer segments.</p>
                    <a href="./salesreport.php" class="report-link">Generate Report</a>
                </div>

                <div class="report-box">
                    <h2>Inventory Report</h2>
                    <p>The Inventory Report gives an overview of our stock, including total value, sales, acquisitions, and aging.</p>
                    <a href="./inventoryreport.php" class="report-link">Generate Report</a>
                </div>
            </div>

            <div class="sales-table-container">
                <h2>Recent Reports</h2>
                <div class="table-filters">
                    <form method="GET" action="reports.php">
                        <label for="date-filter">Date:</label>
                        <input type="date" id="date-filter" name="date" value="<?php echo htmlspecialchars($dateFilter); ?>">
                        
                        <label for="status-filter">Type:</label>
                        <select id="status-filter" name="type">
                            <option value="">All</option>
                            <option value="Profit and Loss" <?php echo ($categoryFilter == 'Profit and Loss') ? 'selected' : ''; ?>>Profit & Loss</option>
                            <option value="Sales" <?php echo ($categoryFilter == 'Sales') ? 'selected' : ''; ?>>Sales</option>
                            <option value="Inventory" <?php echo ($categoryFilter == 'Inventory') ? 'selected' : ''; ?>>Inventory</option>
                        </select>
                        
                        <button class="btn-filter" type="submit">Filter</button>
                        <button><a href="reports.php" class="btn-clear">Clear</a></button>
                    </form>
                </div>

                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Report Number</th>
                            <th>Report Type</th>
                            <th>Generated Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['report_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['report_type']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['generated_date']) . "</td>";
                                echo "<td class='actions'>
                                        <a href='#' class='btn'><i class='bx bx-pencil'></i></a>
                                        <a class='btn'><i class='bx bx-trash'></i></a>
                                        <a class='btn printBtn'><i class='bx bx-printer'></i></a>
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No reports found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </section>

    <script src="../../../Components/Accountant_Dashboard_Template/script.js"></script>
    <script src="./reports.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>


