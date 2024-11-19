<?php
include '../../../database/db.php';

$sql = "SELECT customer_id AS person_id, firstName AS name , CONCAT(address1, ' , ', address2) AS address  , email , contactNo AS contact_no, 'Customer' AS type
        FROM customer
        UNION ALL
        SELECT buyer_id AS person_id, name , address  , email , contact_no, 'Buyer' AS type
        FROM buyer";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accountant Transactions</title>
    <link rel="stylesheet" href="../../../Components/Accountant_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="./styles.css">   
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <dashboard-component></dashboard-component>

    <section id="content">
        <main>  
            <div class="report-boxes-container">
                <div class="report-box">
                    <h2>New <br> Customer</h2>
                    <a href="../Customer/addCustomer.html" class="report-link">Add Customer</a>
                </div>

                <div class="report-box">
                    <h2>New <br> Buyer</h2>
                    <a href="../Buyer/addBuyer.html" class="report-link">Add Buyer</a>
                </div>

                <div class="report-box">
                    <h2>Existing <br> Customer/Buyer</h2>
                    <a href="./transactionType.html" class="report-link">Create Transaction</a>
                </div>
            </div>

            <div class="sales-table-container">
                <div class="table-filters">
                    <label for="customer-filter">Customer:</label>
                    <input type="text" id="customer-filter" placeholder="Search Customer">
                    
                    <label for="customer-filter">Buyer:</label>
                    <input type="text" id="customer-filter" placeholder="Search Buyer">
                    
                    <button class="btn-filter">Filter</button>
                </div>

                <!-- Table -->
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Person ID</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Contact No:</th>
                            <th>Type</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Determine the status label and color
                               
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['person_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['contact_no']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No transactions found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div> 
        </main>
    </section>


    <script src="../../../Components/Accountant_Dashboard_Template/script.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>