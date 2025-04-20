<?php
session_start();
include('../../../database/db.php'); // Include database connection

if (!isset($_GET['id'])) {
    echo "Order ID is required.";
    exit;
}

$order_id = intval($_GET['id']);
$customer_id = $_SESSION['customer_id'] ?? null;

// Fetch order details
$order_sql = "SELECT 
                o.*, 
                c.firstName, c.lastName, c.address1, c.address2, c.city, c.country, c.postalCode, c.contactNo, c.email
              FROM orders o
              JOIN customer c ON o.customer_id = c.customer_id
              WHERE o.order_id = ?";
$order_stmt = $conn->prepare($order_sql);
$order_stmt->bind_param("i", $order_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();

if ($order_result->num_rows === 0) {
    echo "Order not found or access denied.";
    exit;
}

$order = $order_result->fetch_assoc();

// Fetch stone details for the order
$stones_sql = "SELECT i.*
               FROM inventory i
               INNER JOIN order_items oi ON oi.stone_id = i.stone_id
               WHERE oi.order_id = ?";
$stones_stmt = $conn->prepare($stones_sql);
$stones_stmt->bind_param("i", $order_id);
$stones_stmt->execute();
$stones_result = $stones_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="../../../Components/SalesRep_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="../Sales/salesStyles.css" />
    <link rel="stylesheet" href="../Sales/editSalesStyles.css" />  
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./invoice.css">
</head>
<body>
    <dashboard-component></dashboard-component>
    <section id="content">
        <main>
            <div class="invoice-container">
                <div class="invoice-header">
                    <div class="company-info">
                        <h1>SAF Gems Pvt.Ltd.</h1>
                        <p>Timeless Elegance, Accessible to All</p>
                        <p>75/4, Mihiripenna Road, Dharga Town, Sri Lanka</p>
                        <p>+94 76 256 8459 | safgems@live.com</p>
                    </div>
                    <div class="invoice-info">
                        <p><strong>Invoice #00<?php echo $order['order_id']; ?></strong></p>
                        <p>Date: <?php echo date("m/d/Y"); ?></p>
                    </div>
                </div>

                <div class="billing-details">
                    <h3>Bill To:</h3>
                    <p><?php echo $order['firstName'] . ' ' . $order['lastName']; ?></p>
                    <p><?php echo $order['address1']; ?></p>
                    <p><?php echo $order['city'] . ', ' . $order['country'] . ' ' . $order['postalCode']; ?></p>
                    <p><?php echo $order['contactNo']; ?></p>
                </div>
                
                <table class="item-table">
                    <thead>
                        <tr>
                            <th>Quantity</th>
                            <th>Description</th>
                            <th>Unit Price (LKR)</th>
                            <th>Total (LKR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($stone = $stones_result->fetch_assoc()): ?>
                        <tr>
                            <td>1</td> <!-- Default quantity 1 -->
                            <td><?php echo $stone['colour'] . ' ' . $stone['type'] . ' ' . $stone['size'] . ' carats'; ?></td>
                            <td><?php echo number_format($stone['amount'], 2); ?></td>
                            <td><?php echo number_format($stone['amount'], 2); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <div class="summary">
                    <p>Subtotal: Rs.<?php echo number_format($order['total_amount'], 2); ?></p>
                    <p>Sales Tax: Rs.<?php echo number_format($order['total_amount'] * 0.05, 2); ?></p>
                    <p><strong>Total Due: Rs.<?php echo number_format($order['total_amount'] * 1.05, 2); ?></strong></p>
                </div>

                <div class="invoice-footer">
                    <p>Make all checks payable to SAF Gems Pvt.Ltd.</p>
                    <p>If you have any questions about this invoice, contact: Accountant at +94 (0)76 256 8459.</p>
                    <p><strong>Thank you for your business!</strong></p>
                </div>
            </div>

            <div class="export-options">
                <button id="export-pdf" class="export-button">Export as PDF</button>
                <button id="print-invoice" class="export-button">Print</button>
            </div>
        </main>
    </section>

    <script src="../../../Components/SalesRep_Dashboard_Template/script.js"></script>
    <script scr="../../../Sales_Rep_Dashboard/Pages/Inventory/inventory.js"></script>

    <script>
        document.getElementById('print-invoice').addEventListener('click', function() {
            window.print();
        });

        document.getElementById('export-pdf').addEventListener('click', function() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            doc.text("Invoice #00<?php echo $order['order_id']; ?>", 20, 20);
            doc.text("Bill To:", 20, 30);
            doc.text("<?php echo $order['firstName'] . ' ' . $order['lastName']; ?>", 20, 40);
            doc.text("<?php echo $order['address_name']; ?>", 20, 50);
            doc.text("<?php echo $order['city'] . ', ' . $order['country'] . ' ' . $order['postalCode']; ?>", 20, 60);
            doc.text("<?php echo $order['contactNo']; ?>", 20, 70);
            doc.text("Subtotal: Rs.<?php echo number_format($order['total_amount'], 2); ?>", 20, 90);
            doc.text("Sales Tax: Rs.<?php echo number_format($order['total_amount'] * 0.05, 2); ?>", 20, 100);
            doc.text("Total Due: Rs.<?php echo number_format($order['total_amount'] * 1.05, 2); ?>", 20, 110);
            doc.save("Invoice_00<?php echo $order['order_id']; ?>.pdf");
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>