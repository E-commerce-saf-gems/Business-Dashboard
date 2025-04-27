<?php
include '../../../database/db.php';

$currentMonth = date('m');
$currentYear = date('Y');

$lastMonth = $currentMonth - 1;
$lastMonthYear = $currentYear;

if ($lastMonth == 0) {
    $lastMonth = 12;
    $lastMonthYear = $currentYear - 1;
}

$thisMonthQuery = "SELECT COUNT(*) AS thisMonthCount FROM customer WHERE MONTH(date) = $currentMonth AND YEAR(date) = $currentYear";
$thisMonthResult = $conn->query($thisMonthQuery);
$thisMonthCount = $thisMonthResult->fetch_assoc()['thisMonthCount'] ?? 0;

$lastMonthQuery = "SELECT COUNT(*) AS lastMonthCount FROM customer WHERE MONTH(date) = $lastMonth AND YEAR(date) = $lastMonthYear";
$lastMonthResult = $conn->query($lastMonthQuery);
$lastMonthCount = $lastMonthResult->fetch_assoc()['lastMonthCount'] ?? 0;

$ssql = "SELECT 
            customer.customer_id,
            customer.date, 
            customer.firstName, 
            customer.contactNo, 
            customer.NIC, 
            customer.email, 
            customer.city,
            customer.gender
        FROM customer 
        WHERE 1=1"; 

if (isset($_GET['date']) && !empty($_GET['date'])) {
    $date = $conn->real_escape_string($_GET['date']);
    $ssql .= " AND DATE(date) = '$date'"; 
}

if (isset($_GET['status']) && !empty($_GET['status'])) {
    $status = $conn->real_escape_string($_GET['status']);
    $ssql .= " AND gender = '$status'";
}


if (isset($_GET['customer-name']) && !empty($_GET['customer-name'])) {
    $customerName = $conn->real_escape_string($_GET['customer-name']);
    $ssql .= " AND firstName LIKE '%$customerName%'";
}

$result = $conn->query($ssql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Details</title>
    <link rel="stylesheet" href="../../../Components/SalesRep_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="../../Pages/userStyles.css">   
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <dashboard-component></dashboard-component>

    <section id="content">
        <main>
            <div class="head-title">
				<div class="left">
					<h1>Customers</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="#">Customer Details Summary</a>
                        </li>
					</ul>
				</div>
                <a href="../../../../Group-Project-ECommerce/pages/RegisterPage/register.html" class="btn-add"><i class='bx bx-plus'></i>Add New</a>

			</div>

            <div class="sales-summary-box">
                <div class="sales-summary-title">
                    <h2>Summary of Customers</h2>
                </div>
                <div class="sales-item">
                    <h3>This Month</h3>
                    <p><?php echo $thisMonthCount; ?></p>
                    </div>
                <div class="sales-item">
                    <h3>Last Month</h3>
                    <p><?php echo $lastMonthCount; ?></p>
                    </div>
                <!-- <div class="sales-item">
                    <h3>Last Two Months</h3>
                    <p>1200</p>
                </div> -->
            </div>
            <div class="sales-table-container">
            <div class="table-filters">
                <form method="GET" id="filter-form">
                    <label for="date-filter">Date:</label>
                    <input type="date" id="date-filter" name="date" value="<?= isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>" onchange="document.getElementById('filter-form').submit();">

                    <label for="status-filter">Gender:</label>
                    <select id="status-filter" name="status" onchange="document.getElementById('filter-form').submit();">
                        <option value="">All</option>
                        <option value="M" <?= (isset($_GET['status']) && $_GET['status'] == 'M') ? 'selected' : ''; ?>>Male</option>
                        <option value="F" <?= (isset($_GET['status']) && $_GET['status'] == 'F') ? 'selected' : ''; ?>>Female</option>
                    </select>

                    <label for="customer-filter">Customer Name:</label>
                    <input type="text" id="customer-filter" name="customer-name" placeholder="Search Customer Name" value="<?= isset($_GET['customer-name']) ? htmlspecialchars($_GET['customer-name']) : ''; ?>" oninput="document.getElementById('filter-form').submit();">

                    <!-- <button type="submit" class="btn-filter">Filter</button> -->
                    <button type="button" onclick="window.location.href='<?= strtok($_SERVER['REQUEST_URI'], '?'); ?>'">Reset Filters</button>
                </form>
        </div>
                <!-- Table -->
                <table class="sales-table">
                    <thead>
                        <tr>
                            <!-- <th><input type="checkbox" class="select-all"></th> -->
                            <th>Date</th>
                            <th>Customer Id</th>
                            <th>Customer Name</th>
                            <th>Telephone No</th>
                            <th>NIC</th>
                            <th>Email</th>
                            <th>City</th>
                            <!-- <th>Total Purchases</th> -->
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()){
                                echo "<tr>";
                                echo "<td>" . $row['date'] . "</td>";
                                echo "<td>" . $row['customer_id'] . "</td>";
                                echo "<td>" . $row['firstName'] . "</td>";
                                echo "<td>" . $row['contactNo'] . "</td>";
                                echo "<td>" . $row['NIC'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['city'] . "</td>";
                                echo "<td class='actions'>";
                                echo "<a href='./viewcustomer.php?id=" . $row['customer_id'] . "' class='btn'><i class='bx bx-detail'></i></a>";
                                echo "<a href='./deletecustomer.php' onclick='confirmDelete(" . $row['customer_id'] . ")' class='btn'><i class='bx bx-trash'></i></a>";
                                // echo "<a class='btn'><i class='bx bx-trash'></i></a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9'>No customers in the database.</td></tr>";
                        }
                        ?>
        
                    </tbody>
                </table>
            </div>    
        </main>
    </section>

    <script>
        function confirmDelete(customerId) {
        const userConfirmed = confirm("Are you sure you want to delete this customer?");
        if (userConfirmed) {
            window.location.href = `./deletecustomer.php?id=${customerId}`;
        }
        }
    </script>  
    
    <script src="../../../Components/SalesRep_Dashboard_Template/script.js"></script>
    <script src="../../../Sales_Rep_Dashboard/script.js"></script>
    <script src="../customer.js"></script>


</body>
</html>

<?php
// Close the database connection
$conn->close();
?>