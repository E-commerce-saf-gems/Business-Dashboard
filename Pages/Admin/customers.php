<?php
include '../../database/db.php';

// Corrected SQL query syntax
$ssql = "SELECT 
            customer.date, 
            customer.firstName, 
            customer.contactNo, 
            customer.NIC, 
            customer.email, 
            customer.city
        FROM customer";

$result = $conn->query($ssql);

// Check if query was successful
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
    <link rel="stylesheet" href="../../Components/Admin_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="./userStyles.css">   
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
                <a href="./addnewcustomer.html" class="btn-add"><i class='bx bx-plus'></i>Add New</a>

			</div>

            <div class="sales-summary-box">
                <div class="sales-summary-title">
                    <h2>Monthly Users Visit Website</h2>
                </div>
                <div class="sales-item">
                    <h3>This Month</h3>
                    <p>750</p>
                </div>
                <div class="sales-item">
                    <h3>Last Month</h3>
                    <p>600</p>
                </div>
                <div class="sales-item">
                    <h3>Last Two Months</h3>
                    <p>1200</p>
                </div>
            </div>
            <div class="sales-table-container">
                <div class="table-filters">
                    <label for="date-filter">Date:</label>
                    <input type="date" id="date-filter">
                    
                    <label for="status-filter">Status:</label>
                    <select id="status-filter">
                        <option value="">All</option>
                        <option value="paid">Online Registered</option>
                        <option value="pending">Walking Customers</option>
                    </select>

                    <label for="customer-filter">Customer Name</label>
                    <input type="text" id="customer-filter" placeholder="Search Customer Name">
                    
                    <button class="btn-filter">Filter</button>
                </div>

                <!-- Table -->
                <table class="sales-table">
                    <thead>
                        <tr>
                            <!-- <th><input type="checkbox" class="select-all"></th> -->
                            <th>Date</th>
                            <th>Customer Name</th>
                            <th>Telephone No</th>
                            <th>NIC</th>
                            <th>Email</th>
                            <th>City</th>
                            <!-- <th>Total Purchases</th> -->
                            <th>Actions</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        // Check if there are results and display each row in the table
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()){
                                echo "<tr>";
                                echo "<td>" . $row['date'] . "</td>";
                                echo "<td>" . $row['firstName'] . "</td>";
                                echo "<td>" . $row['contactNo'] . "</td>";
                                echo "<td>" . $row['NIC'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['city'] . "</td>";
                                echo "<td class='actions'>
                                <a href='../../Pages/Admin/customers.php' class='btn'>
                                <i class='bx bx-pencil'></i></a>
                                <a class='btn'><i class='bx bx-trash'></i></a>
                                </td>";
                                echo '</tr>';
                            }
                        } else {
                            echo "<tr><td colspan='9'>No customers in the inventory.</td></tr>";
                        }
                        ?>
                        <!-- <tr>
                            <td><input type="checkbox"></td>
                            <td>2024-11-01</td>
                            <td>Chethana</td>
                            <td>071 4562341</td> 
                            <td>200267893412</td>
                            <td>ck@gmail.com</td>
                            <td>1</td>
                            <td class="actions">
                                <a href="./editcustomer.html" class="btn"><i class="bx bx-pencil"></i></a>
                                <a class="btn"><i class="bx bx-eye"></i></a>
                                <a class="btn"><i class="bx bx-trash"></i></a>
                            </td>
                            <td class="options">
                                <a class="btn printBtn"><i class='bx bx-printer'></i></a>
                            </td>
                        </tr> -->
                    </tbody>
                </table>
            </div>    
        </main>
    </section>

    
    <script src="../../Components/Admin_Dashboard_Template/script.js"></script>
    <script src="../../Components/Admin_Dashboard_Template/script.js"></script>
    <script scr="admin.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>