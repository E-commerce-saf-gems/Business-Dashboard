<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "safgems";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the 'request' table
$sql = "SELECT date, customer_name, shape, type, weight, color, requirement, status FROM request";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Gems</title>
    <link rel="stylesheet" href="/Components/Partner_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="./requests.css">   
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <dashboard-component></dashboard-component>

    <section id="content">
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Customer Requests</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a class="active" href="#">Request Summary</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sales-table-container">
                <div class="table-filters">
                    <label for="date-filter">Date:</label>
                    <input type="date" id="date-filter">
                    
                    <label for="status-filter">Status:</label>
                    <select id="status-filter">
                        <option value="">All</option>
                        <option value="A">Approved</option>
                        <option value="P"> Pending</option>
                    </select>

                    <label for="customer-filter">Gem Type:</label>
                    <input type="text" id="customer-filter" placeholder="Search Gem Type">
                    
                    <button class="btn-filter">Filter</button>
                </div>

                <!-- Table -->
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="select-all"></th>
                            <th>Date</th>
                            <th>Customer Name</th>
                            <th>Shape</th>
                            <th>Type</th>
                            <th>Weight</th>
                            <th>Color</th>
                            <th>Special Requirements</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Check if there are results and display each row in the table
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td><input type='checkbox'></td>";
                                echo "<td>" . $row['date'] . "</td>";
                                echo "<td>" . $row['customer_name'] . "</td>";
                                echo "<td>" . $row['shape'] . "</td>";
                                echo "<td>" . $row['type'] . "</td>";
                                echo "<td>" . $row['weight'] . "</td>";
                                echo "<td>" . $row['color'] . "</td>";
                                echo "<td>" . $row['requirement'] . "</td>";
                                echo "<td>" . $row['status'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9'>No requests found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>    
        </main>
    </section>

    <script src="/Components/Dashboard_Template/script.js"></script>
    <script src="/Components/Partner_Dashboard_Template/script.js"></script>
    <script src="./admin.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
