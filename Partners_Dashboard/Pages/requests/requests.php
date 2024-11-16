<?php
include '../../../database/db.php';

// Fetch data from the 'request' and 'customer' tables using a JOIN
$sql = "SELECT request.request_id, request.date, customer.firstName AS customer_name, customer.email AS email, request.shape, request.type, 
               request.weight, request.color, request.requirement, request.status
        FROM request
        JOIN customer ON request.customer_id = customer.customer_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Gems</title>
    <link rel="stylesheet" href="../../../Components/Partner_Dashboard_Template/styles.css">
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

            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="success-message">
                    Request status updated successfully!
                </div>
            <?php endif; ?>


            <div class="sales-table-container">
                <div class="table-filters">
                    <label for="date-filter">Date:</label>
                    <input type="date" id="date-filter">
                    
                    <label for="status-filter">Status:</label>
                    <select id="status-filter">
                        <option value="">All</option>
                        <option value="A">Approved</option>
                        <option value="P">Pending</option>
                        <option value="C">Complete</option>
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
                            <th>Name</th>
                            <th>Email</th>
                            <th>Shape</th>
                            <th>Type</th>
                            <th>Weight</th>
                            <th>Color</th>
                            <th>Other Requirements</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Check if there are results and display each row in the table
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Determine the status label and color
                                $statusLabel = '';
                                $statusColor = '';

                                switch ($row['status']) {
                                    case 'P':
                                        $statusLabel = 'Pending';
                                        $statusColor = 'color: red;';
                                        break;
                                    case 'A':
                                        $statusLabel = 'Approved';
                                        $statusColor = 'color: blue;';
                                        break;
                                    case 'C':
                                        $statusLabel = 'Complete';
                                        $statusColor = 'color: green;';
                                        break;
                                    default:
                                        $statusLabel = 'Unknown';
                                        $statusColor = 'color: black;';
                                }

                                echo "<tr>";
                                echo "<td><input type='checkbox'></td>";
                                echo "<td>" . $row['date'] . "</td>";
                                echo "<td>" . $row['customer_name'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['shape'] . "</td>";
                                echo "<td>" . $row['type'] . "</td>";
                                echo "<td>" . $row['weight'] . "</td>";
                                echo "<td>" . $row['color'] . "</td>";
                                echo "<td>" . $row['requirement'] . "</td>";
                                echo "<td>";

                                echo "<form method='POST' action='./updateRequest.php'>";
                                echo "<input type='hidden' name='request_id' value='" . $row['request_id'] . "'>";
                                echo "<select name='status' onchange='this.form.submit()'>";
                                echo "<option value='P'" . ($row['status'] === 'P' ? " selected" : "") . ">Pending</option>";
                                echo "<option value='A'" . ($row['status'] === 'A' ? " selected" : "") . ">Approved</option>";
                                echo "<option value='C'" . ($row['status'] === 'C' ? " selected" : "") . ">Complete</option>";
                                echo "</select>";
                                echo "</form>";
                                echo "</td>";
                                echo '</tr>';
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

    <script>
        setTimeout(function() {
        const message = document.querySelector(".success-message");
        if (message) {
            message.style.display = "none";
        }
        }, 5000);
    </script>



    <script src="../../../Components/Partner_Dashboard_Template/script.js"></script>
    <script src="./admin.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
