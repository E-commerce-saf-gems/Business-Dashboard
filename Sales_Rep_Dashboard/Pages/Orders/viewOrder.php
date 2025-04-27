<?php
session_start();
include('../../../database/db.php'); 

if (!isset($_GET['id'])) {
    echo "Order ID is required.";
    exit;
}

$order_id = $_GET['id'];
$customer_id = $_SESSION['customer_id'] ?? null;

$order_sql = "SELECT * FROM orders WHERE order_id = $order_id";
$order_result = $conn->query($order_sql);

if ($order_result->num_rows === 0) {
    echo "Order not found or access denied.";
    exit;
}

$order = $order_result->fetch_assoc();

$stones_sql = "SELECT i.* 
               FROM inventory i
               INNER JOIN order_items oi ON oi.stone_id = i.stone_id
               WHERE oi.order_id = $order_id";
$stones_result = $conn->query($stones_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="../../../Components/SalesRep_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="../Sales/salesStyles.css" />
    <link rel="stylesheet" href="./styles.css">
    <link rel="stylesheet" href="../Sales/editSalesStyles.css" />  
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <dashboard-component></dashboard-component>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Dashboard</h2>
        </div>
        <div class="sidebar-links">
            <a href="/dashboard.php">Dashboard Home</a>
            <a href="/orders/order_summary.php">Orders</a>
            <a href="/products/products_list.php">Products</a>
            <a href="/customers/customer_list.php">Customers</a>
            <a href="/settings/settings.php">Settings</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <custom-header></custom-header>
        <section id="content">
            <main>
                <div class="head-title">
                    <div class="left">
                        <h1>Order #<?php echo $order['order_id']; ?></h1>
                        <ul class="breadcrumb">
                            <li><a class="active" href="#">Order Details</a></li>
                        </ul>
                    </div>
                </div>

                <div class="order-details-container">
                    <!-- Order Header -->
                    <div class="order-header">
                        <h1>Order #<?php echo $order['order_id']; ?></h1>
                        <p><strong>Order Date:</strong> <?php echo $order['order_date']; ?> <span class="status-badge"><?php echo $order['order_status']; ?></span></p>
                    </div>

                    <!-- Order Summary Table -->
                    <div class="order-summary-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Shipping Status</th>
                                    <th>Payment Status</th>
                                    <th>Payment Method</th>
                                    <th>Shipping Method</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Open</td>
                                    <td><?php echo $order['order_status']; ?></td>
                                    <td><?php echo $order['payment_method']; ?></td>
                                    <td><?php echo $order['shipping_method']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Product Details Table -->
                    <div class="product-details-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($stone = $stones_result->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <div class="product-info">
                                            <img src="http://localhost/Group-Project-ECommerce/assets/images/<?php echo htmlspecialchars($stone['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Stone Image" class="product-image">
                                            <div>
                                                <p><strong><?php echo $stone['type']; ?></strong></p>
                                                <p>Shape: <?php echo $stone['shape']; ?></p>
                                                <p>Size: <?php echo $stone['size']; ?> carats</p>
                                                <p>Colour: <?php echo $stone['colour']; ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>1</td>
                                    <td>--</td> <!-- Replace with unit price if available -->
                                    <td>--</td> <!-- Replace with subtotal if available -->
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Order Summary Footer -->
                    <div class="order-summary-footer">
                        <table>
                            <tbody>
                                <tr>
                                    <td><strong>Order Date:</strong></td>
                                    <td><?php echo $order['order_date']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Order Number:</strong></td>
                                    <td><?php echo $order['order_id']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Method:</strong></td>
                                    <td><?php echo $order['payment_method']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Shipping Method:</strong></td>
                                    <td><?php echo $order['shipping_method']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Total (Gross):</strong></td>
                                    <td><?php echo number_format($order['total_amount'], 2); ?> LKR</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </section>
    </div>

    <script src="../../../Components/SalesRep_Dashboard_Template/script.js"></script>
    <script src="../../../Admin_Dashboard/script.js"></script>
</body>
</html>

<?php
$conn->close();
?>
