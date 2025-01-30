<?php
include '../../../database/db.php';

// Fetch all orders along with customer email and status
$order_sql = "SELECT 
                o.order_id, 
                o.order_date, 
                o.total_amount, 
                o.payment_method, 
                o.shipping_method, 
                o.order_status, 
                c.email AS customer_email
              FROM orders o
              JOIN customer c ON o.customer_id = c.customer_id
              ORDER BY o.order_date DESC";

$result = $conn->query($order_sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>
    <link rel="stylesheet" href="../../../Components/SalesRep_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="../Sales/salesStyles.css" />
    <link rel="stylesheet" href="./styles.css">
    <link rel="stylesheet" href="../Sales/editSalesStyles.css" />  
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <dashboard-component></dashboard-component>

    <section id="content">
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Order Summary</h1>
                    <ul class="breadcrumb">
                        <li><a class="active" href="#">All Orders</a></li>
                    </ul>
                </div>
            </div>

            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="success-message">
                    Order status updated successfully!
                </div>
            <?php elseif (isset($_GET['success']) && $_GET['success'] == 0): ?>
                <div class="error-message">
                    Failed to update the order status. Please try again.
                </div>
            <?php endif; ?>

            <!-- Filter Options -->
            <div class="sales-table-container">
                <div class="table-filters">
                    <label for="date-filter">Date:</label>
                    <input type="date" id="date-filter">
                    
                    <label for="customer-filter">Customer Email:</label>
                    <input type="text" id="customer-filter" placeholder="Search by Email">
                    
                    <button class="btn-filter">Filter</button>
                </div>

                <!-- Order Summary Table -->
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Order No</th>
                            <th>Date</th>
                            <th>Customer Email</th>
                            <th>Total (LKR)</th>
                            <th>Payment Method</th>
                            <th>Shipping Method</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td># " . $row['order_id'] . "</td>";
                                echo "<td>" . $row['order_date'] . "</td>";
                                echo "<td>" . $row['customer_email'] . "</td>";
                                echo "<td>Rs. " . number_format($row['total_amount'], 2) . "</td>";
                                echo "<td>" . $row['payment_method'] . "</td>";
                                echo "<td>" . $row['shipping_method'] . "</td>";
                                
                                // Form to update order status
                                echo "<td>";
                                echo "<form method='POST' action='./updateOrderStatus.php'>";
                                echo "<input type='hidden' name='order_id' value='" . $row['order_id'] . "'>";
                                echo "<select name='status' onchange='this.form.submit()'>";
                                echo "<option value='pending'" . ($row['order_status'] === 'pending' ? " selected" : "") . ">Pending</option>";
                                echo "<option value='confirmed'" . ($row['order_status'] === 'confirmed' ? " selected" : "") . ">Confirmed</option>";
                                echo "<option value='completed'" . ($row['order_status'] === 'completed' ? " selected" : "") . ">Completed</option>";
                                echo "<option value='cancelled'" . ($row['order_status'] === 'cancelled' ? " selected" : "") . ">Cancelled</option>";
                                echo "</select>";
                                echo "</form>";
                                echo "</td>";

                                echo "<td><a href='./viewOrder.php?id=" . $row['order_id'] . "' class='btn btn-view'><i class='bx bx-show'></i></a></td>";

                                if ($row['order_status'] === 'cancelled') {
                                    echo "<td><a href='./deleteOrder.php?id=" . $row['order_id'] . "' class='btn btn-delete'><i class='bx bx-trash'></i></a></td>";
                                } else {
                                    echo "<td></td>"; 
                                }
                                
                                // Show Print button only for completed Store Pick-up orders
                                if ($row['shipping_method'] === 'store-pickup' && $row['order_status'] === 'completed') {
                                    echo "<td><a href='./printInvoice.php?id=" . $row['order_id'] . "' class='btn btn-print'><i class='bx bx-printer'></i></a></td>";
                                } else {
                                    echo "<td></td>"; 
                                }
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No orders found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>    
        </main>
    </section>

    <script>
    setTimeout(function() {
        const successMessage = document.querySelector(".success-message");
        const errorMessage = document.querySelector(".error-message");

        if (successMessage) {
            successMessage.style.display = "none";
        }

        if (errorMessage) {
            errorMessage.style.display = "none";
        }
    }, 5000);
</script>


    <script src="../../../Components/SalesRep_Dashboard_Template/script.js"></script>
    <script src="../../../Admin_Dashboard/script.js"></script>

</body>
</html>

<?php
$conn->close();
?>
