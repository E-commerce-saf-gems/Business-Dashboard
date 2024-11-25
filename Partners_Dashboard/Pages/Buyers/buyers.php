<?php
include '../../../database/db.php';

// Fetch data from the 'buyer' table
$sql = "SELECT 
            buyer_id, 
            name, 
            address, 
            contact_no, 
            email 
        FROM buyer";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyers</title>
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
                    <h1>Buyers</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a class="active" href="#">Buyer Summary</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sales-summary-box">
                <div class="sales-summary-title">
                    <h2>Total Buyers</h2>
                </div>
                <div class="sales-item">
                    <h3>Total Registered Buyers</h3>
                    <p>
                        <?php
                        // Count the total number of buyers
                        $totalBuyers = $conn->query("SELECT COUNT(*) AS count FROM buyer");
                        $count = $totalBuyers->fetch_assoc();
                        echo $count['count'];
                        ?>
                    </p>
                </div>
            </div>

            <div class="addnew">
                <a href="./addBuyer.html" class="btn-add"><i class='bx bx-plus'></i>Add New</a>
            </div>

            <div class="sales-table-container">
                <div class="table-filters">
                    <label for="name-filter">Name:</label>
                    <input type="text" id="name-filter" placeholder="Search Name">
                    
                    <label for="email-filter">Email:</label>
                    <input type="text" id="email-filter" placeholder="Search Email">
                    
                    <button class="btn-filter">Filter</button>
                </div>

                <!-- Table -->
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Buyer ID</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Contact No</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Check if there are results and display each row in the table
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['buyer_id'] . "</td>";
                                echo "<td>" . $row['name'] . "</td>";
                                echo "<td>" . $row['address'] . "</td>";
                                echo "<td>" . $row['contact_no'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td class='actions'>";
                                echo "<a class='btn editBtn'><i onclick='./editBuyer.html' class='bx bx-edit'></i></a>";
                                echo "<a class='btn'><i class='bx bx-trash'></i></a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No buyers found.</td></tr>";
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

        // Example functionality for filters (you can implement backend filter handling)
        document.querySelector('.btn-filter').addEventListener('click', function () {
            alert('Filtering functionality not implemented yet.');
        });
    </script>

    <script src="../../../Components/Partner_Dashboard_Template/script.js"></script>
    <script src="./sales.js"></script>
</body>
</html>

<?php
$conn->close();
?>
