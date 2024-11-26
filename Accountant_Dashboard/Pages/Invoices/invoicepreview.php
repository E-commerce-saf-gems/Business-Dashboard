<?php
// Database connection
include '../../../database/db.php';

// Retrieve invoice and customer details (replace `transaction_id` with actual filtering condition, e.g., $_GET['invoice_id'])
$transaction_id = isset($_GET['transaction_id']) ? intval($_GET['transaction_id']) : 0; // Example transaction ID
$sql = "SELECT 
            t.transaction_id, t.amount,
            CONCAT(c.firstName,' ',c.lastName) AS customer_name, CONCAT(c.address1,',',c.address2) AS address_name, c.city,c.country, c.postalCode, c.contactNo, c.email,
            CONCAT(st.colour,' ',st.type,' ',st.size,' carats') AS description
        FROM transactions t
        JOIN customer c ON t.customer_id = c.customer_id
        JOIN inventory st ON t.stone_id = st.stone_id
        WHERE t.transaction_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $transaction_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $invoice = $result->fetch_assoc();
} else {
    die("No invoice data found.");
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Preview</title>
    <link rel="stylesheet" href="../../../Components/Accountant_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="./invoice.css">   
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    <!-- Include jsPDF library for PDF generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

</head>
<body>
    <dashboard-component></dashboard-component>
    <section id="content">
        <main>
            <div class="head-title">
				<div class="left">
					<h1>Invoices</h1>
					<ul class="breadcrumb">
						<li><a class="active" href="../transactions/transactions.php">Home</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="#">Invoice Preview</a></li>
					</ul>
				</div>
			</div>
            <div class="invoice-container">
                <!-- Header Section with Logo -->
                <div class="invoice-header">
                    <div class="logo">
                        <img src="../../../Images/logo.png" alt="Company Logo" /> <!-- Replace logo.png with your logo file -->
                    </div>
                <div class="company-info">
                    <h1>SAF Gems Pvt.Ltd.</h1>
                    <p><em>Timeless Elegance, Accessible to All</em></p>
                    <p>75/4, Mihiripenna Road Dharga Town, Sri Lanka</p>
                    <p>+94 76 256 8459 | safgems@live.com</p>
                </div>
                <div class="invoice-info">
                    <p><strong>Invoice #00<?php echo $invoice['transaction_id']; ?></strong></p>
                    <p>Date: <?php echo date("m/d/Y"); ?></p>
                </div>
            </div>

                <!-- Billing Details -->
                <div class="billing-shipping">
                    <div class="bill-to">
                        <h3>Bill To:</h3>
                        <p><?php echo $invoice['customer_name']; ?></p>
                        <p><?php echo $invoice['address_name']; ?></p>
                        <p><?php echo $invoice['city'] . ', ' . $invoice['country'] . ' ' . $invoice['postalCode']; ?></p>
                        <p><?php echo $invoice['contactNo']; ?></p>
                    </div>
                </div>
                
                <!-- Itemized List Table -->
                <table class="item-table">
                    <thead>
                        <tr>
                            <th>Quantity</th>
                            <th>Description</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><?php echo $invoice['description']; ?></td>
                            <td><?php echo number_format($invoice['amount'], 2); ?></td>
                            <td><?php echo number_format($invoice['amount']*1 , 2); ?></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Summary Section -->
                <div class="summary">
                    <p>Subtotal: Rs.<?php echo number_format($invoice['amount']*1, 2); ?></p>
                    <p>Sales Tax: Rs.<?php echo number_format($invoice['amount'] * 0.05, 2); ?></p>
                    
                    <p><strong>Total Due: Rs.<?php echo number_format($invoice['amount'] + ($invoice['amount'] * 0.05), 2); ?></strong></p>
                </div>

                <!-- Footer Section -->
                <div class="invoice-footer">
                    <p>Make all checks payable to SAF Gems Pvt.Ltd.</p>
                    <p>If you have any questions concerning this invoice, contact: Accountant at +94 (0)76 256 8459.</p>
                    <p style="color: ash"><strong>Thank you for your business!</strong></p>
                </div>
            </div>

            <div class="export-options">
                <button id="export-pdf" class="export-button">Export as PDF</button>
                <button id="email-popup-button" class="export-button">Send via Email</button>
            </div>

        </main>
    </section>

    <script src="../../../Components/Accountant_Dashboard_Template/script.js"></script>
    <script src="invoice.js"></script>
</body>
</html>