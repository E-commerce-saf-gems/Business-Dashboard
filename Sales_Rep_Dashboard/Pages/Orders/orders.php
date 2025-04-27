<?php
include '../../../database/db.php';

// Get today's date
$today = date("Y-m-d");

// Fetch today's orders
$today_orders_sql = "SELECT order_id,shipping_method,order_status FROM orders WHERE DATE(order_date) = ? && (order_status='confirmed' || order_status='ready for collection') ";
$today_orders_stmt = $conn->prepare($today_orders_sql);
$today_orders_stmt->bind_param("s", $today);
$today_orders_stmt->execute();
$today_orders_result = $today_orders_stmt->get_result();

// Fetch missed pickups
$missed_pickups_sql = "SELECT order_id, pickup_date FROM orders 
                       WHERE shipping_method = 'store-pickup' 
                       AND pickup_date < ? 
                       AND order_status != 'completed'";
$missed_pickups_stmt = $conn->prepare($missed_pickups_sql);
$missed_pickups_stmt->bind_param("s", $today);
$missed_pickups_stmt->execute();
$missed_pickups_result = $missed_pickups_stmt->get_result();

// Fetch pending orders
$pending_orders_sql = "SELECT order_id FROM orders WHERE order_status = 'pending'";
$pending_orders_result = $conn->query($pending_orders_sql);
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
                </div>
            </div>

            <!-- Dashboard Overview -->
            <div class="dashboard-container">

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

                    <label for="customer-filter">Type:</label>
                    <input type="text" id="customer-filter" placeholder="Search Gem Type">
                    
                    <button class="btn-filter">Filter</button>
                </div>

                <!-- Today's Orders -->
                <div class="dashboard-card">
                    <h2><i class='bx bx-calendar-check dashboard-icon'></i> Today's Collections / Deliveries</h2>
                    <div class="scrollable-list">
                        <ul>
                            <?php while ($row = $today_orders_result->fetch_assoc()): ?>
                                <li>
                                    <a href="./viewOrder.php?id=<?php echo $row['order_id']; ?>" class="order-link">
                                        Order #<?php echo $row['order_id']; ?>
                                    </a>
                                    <span class="shipping-method"><?php echo ucfirst($row['shipping_method']); ?></span>

                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>

                <!-- Missed Pickups -->
                <div class="dashboard-card">
                    <h2><i class='bx bx-time-five dashboard-icon'></i> Missed Pickups</h2>
                    <div class="scrollable-list">
                        <ul>
                            <?php while ($row = $missed_pickups_result->fetch_assoc()): ?>
                                <li>
                                    Order #<?php echo $row['order_id']; ?>
                                    <span class="missed-pickup">Missed: <?php echo $row['pickup_date']; ?></span>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>

                <!-- Pending Orders -->
                <div class="dashboard-card">
                    <h2><i class='bx bx-hourglass dashboard-icon'></i> Pending Orders</h2>
                    <div class="scrollable-list">
                        <ul>
                            <?php while ($row = $pending_orders_result->fetch_assoc()): ?>
                                <li>
                                    Order #<?php echo $row['order_id']; ?>
                                    <a href="./viewOrder.php?id=<?php echo $row['order_id']; ?>" class="btn-view">View</a>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>

            </div>

            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="success-message">
                    Order Status Has Been Updated
                </div>
            <?php elseif (isset($_GET['success']) && $_GET['success'] == 2): ?>
                <div class="error-message">
                    Failed To Update Order Status
                </div>
            <?php endif; ?>

            <!-- Sales Table -->
            <div class="sales-table-container">
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Order No</th>
                            <th>Date</th>
                            <th>Customer Email</th>
                            <th>Total (LKR)</th>
                            <th>Shipping Method</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch all orders
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
                        $orders_result = $conn->query($order_sql);

                        if ($orders_result->num_rows > 0):
                            while ($row = $orders_result->fetch_assoc()):
                                ?>
                                <tr>
                                    <td>#<?php echo $row['order_id']; ?></td>
                                    <td><?php echo date("Y-m-d", strtotime($row['order_date'])); ?></td>
                                    <td><?php echo $row['customer_email']; ?></td>
                                    <td>LKR <?php echo number_format($row['total_amount'], 2); ?></td>
                                    <td><?php echo ucfirst($row['shipping_method']); ?></td>
                                    <td>
                                    <form method="POST" action="updateOrderStatus.php" class="status-form">
    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
    <select name="order_status" class="status-dropdown" onchange="this.form.submit()">
        <?php
        $statuses = ['pending', 'confirmed', 'ready for collection', 'ready for delivery', 'completed'];
        foreach ($statuses as $status) {
            $selected = ($row['order_status'] === $status) ? 'selected' : '';
            echo "<option value=\"$status\" $selected>" . ucfirst($status) . "</option>";
        }
        ?>
    </select>
</form>
</td>
                                    <td>
                                        <a href="./viewOrder.php?id=<?php echo $row['order_id']; ?>" class="btn-view">View</a>
                                    </td>
                                </tr>
                            <?php endwhile; else: ?>
                            <tr>
                                <td colspan="8">No orders found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>

        </main>
    </section>
    <script src="./orders.js"></script>
    <script src="../../../Components/SalesRep_Dashboard_Template/script.js"></script>
    
</body>

</html>

<?php $conn->close(); ?>



