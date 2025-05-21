<?php
include '../../../database/db.php';

$ssql = "SELECT 
            user_id,
            username,
            nic,
            password,
            name,
            role,
            email,
            contactNo
        FROM user WHERE 1=1";


if (isset($_GET['staff-id']) && !empty($_GET['staff-id'])) {
    $staffId = $conn->real_escape_string($_GET['staff-id']);
    $ssql .= " AND user_id LIKE '%$staffId%'"; 
}

if (isset($_GET['status']) && !empty($_GET['status'])) {
    $status = $conn->real_escape_string($_GET['status']);
    $ssql .= " AND role = '$status'";
}

if (isset($_GET['staff-name']) && !empty($_GET['staff-name'])) {
    $staffName = $conn->real_escape_string($_GET['staff-name']);
    $ssql .= " AND name LIKE '%$staffName%'";
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
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="success-message">
                    Staff member updated successfully!
                </div>
        <?php endif; ?>

            <div class="sales-table-container">
            <div class="table-filters">
                <form method="GET" id="filter-form">
                <label for="id-filter">Staff Id</label>
                <input type="text" id="staff-id-filter" name="staff-id" placeholder="Search by Staff ID" value="<?= isset($_GET['staff-id']) ? htmlspecialchars($_GET['staff-id']) : ''; ?>" oninput="document.getElementById('filter-form').submit();">                    <label for="status-filter">Status:</label>
                    <select id="status-filter" name="status" onchange="document.getElementById('filter-form').submit();">
                        <option value="">All</option>
                        <option value="Accountant" <?= (isset($_GET['status']) && $_GET['status'] == 'Accountant') ? 'selected' : ''; ?>>Accountant</option>
                        <option value="Partner" <?= (isset($_GET['status']) && $_GET['status'] == 'Partner') ? 'selected' : ''; ?>>Partner</option>
                        <option value="SalesRep" <?= (isset($_GET['status']) && $_GET['status'] == 'SalesRep') ? 'selected' : ''; ?>>SalesRep</option>
                    </select>

                    <label for="customer-filter">Staff Member:</label>
                    <input type="text" id="customer-filter" name="staff-name" placeholder="Search Member" value="<?= isset($_GET['staff-name']) ? htmlspecialchars($_GET['staff-name']) : ''; ?>" oninput="document.getElementById('filter-form').submit();">

                    <button type="button" onclick="window.location.href='<?= strtok($_SERVER['REQUEST_URI'], '?'); ?>'">Reset Filters</button>
                </form>
            </div>

                <!-- Table -->
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>UserID</th>
                            <th>Username</th>
                            <th>NIC</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                   <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['user_id'] . "</td>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['nic'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['role'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['contactNo'] . "</td>";
                        echo "<td class='actions'>";
                        echo "<a href='./editstaff.php?id=" . $row['user_id'] . "' class='btn'><i class='bx bx-pencil'></i></a>";
                        echo "<a href='#' onclick='confirmDelete(" . $row['user_id'] . ")' class='btn'><i class='bx bx-trash'></i></a>";
                        // echo "<a class='btn'><i class='bx bx-trash'></i></a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No ones in the company.</td></tr>";
                }
                ?>
                    </tbody>
                </table>
            </div>    
        </main>
    </section>
    <script>
        function confirmDelete(userId) {
        const userConfirmed = confirm("Are you sure you want to delete this user?");
        if (userConfirmed) {
            window.location.href = `./deleteStaff.php?id=${userId}`;
        }
        }
    </script>   


    <script src="../../../Components/Admin_Dashboard_Template/Dashboard.js"></script>
    <script src="../../../Components/Admin_Dashboard_Template/script.js"></script>
    <script src="../../../Admin_Dashboard/script.js"></script>
    <script src="./staff.js"></script>
    <script src="./EditStaff.js"></script>
</body>
</html>

<?php
$conn->close();
?>