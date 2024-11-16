<?php
include '../../../database/db.php';

// Corrected SQL query syntax
$ssql = "SELECT 
            date,
            username,
            password,
            role,
            contactNo
        FROM user";

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
    <title>Staff</title>
    <link rel="stylesheet" href="../../../Components/Admin_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="../userStyles.css">   
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <dashboard-component></dashboard-component>

    <section id="content">
        <main>
            <div class="head-title">
				<div class="left">
					<h1>Staff</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="#">Staff Summary</a>
						</li>
					</ul>
				</div>
                <a href="./addnewstaff.html" class="btn-add"><i class='bx bx-plus'></i>Add New</a>

			</div>

            <div class="sales-table-container">
                <div class="table-filters">
                    <label for="date-filter">Date:</label>
                    <input type="date" id="date-filter">
                    
                    <label for="status-filter">Status:</label>
                    <select id="status-filter">
                        <option value="">All</option>
                        <option value="paid">Accountant</option>
                        <option value="pending">Partners</option>
                        <option value="pending">Sales Res.</option>
                    </select>

                    <label for="customer-filter">Staff Member:</label>
                    <input type="text" id="customer-filter" placeholder="Search Member">
                    
                    <button class="btn-filter">Filter</button>
                </div>

                <!-- Table -->
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="select-all"></th>
                            <th>Date</th>
                            <th>Username</th>
                            <th>Passward</th>
                            <th>Role</th>
                            <th>Contact Number</th>
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
                                echo "<td>" . $row['username'] . "</td>";
                                echo "<td>" . $row['password'] . "</td>";
                                echo "<td>" . $row['role'] . "</td>";
                                echo "<td>" . $row['contactNo'] . "</td>";
                                echo "<td class='actions'>
                                <a href='./editstaff.html' class='btn'></a>
                                <i class='bx bx-pencil'></i>
                                <a class='btn'><i class='bx bx-trash'></i></a>
                                </td>";
                                echo '</tr>';
                            }
                        } else {
                            echo "<tr><td colspan='9'>No staff members in the system.</td></tr>";
                        }
                        ?>
                        
                    </tbody>
                </table>
            </div>    
        </main>
    </section>

    <script src="../../../Components/Admin_Dashboard_Template/script.js"></script>
    <script src="../../../Admin_Dashboard/script.js"></script>
    
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>