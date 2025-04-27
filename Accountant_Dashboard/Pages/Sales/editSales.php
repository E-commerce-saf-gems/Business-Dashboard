<?php
include '../../../database/db.php';

$sale_id = $_GET['id'];
$sql = "SELECT 
    s.*, 
    c.email AS customer_email, 
    st.stone_id AS stone_id, 
    st.type AS stone_type, 
    st.colour AS stone_color, 
    st.size AS stone_weight
FROM 
    sales s
JOIN 
    inventory st ON s.stone_id = st.stone_id
JOIN 
    customer c ON s.customer_id = c.customer_id
WHERE 
    s.sale_id = ?;
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $sale_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sales</title>
    <link rel="stylesheet" href="../../../Components/Accountant_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="../transactions/edittransactionstyles.css">
</head>
<body>
<dashboard-component></dashboard-component>

<section id="content">
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Edit Sales</h1>
                <ul class="breadcrumb">
                    <li><a class="active" href="./invoices.php">Home</a></li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li><a class="active" href="#">Edit Sales</a></li>
                </ul>
            </div>
        </div>

        <div class="edit-sales-container">
        <form action="updateSales.php" method="POST" class="edit-sales-form">
            <input type="hidden" name="sale_id" value="<?php echo $row['sale_id']; ?>">

            <div class="form-group">
                <label for="customer_email">Customer Email</label>
                <input type="text" id="customer_email" name="customer_email" value="<?php echo $row['customer_email']; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="stone_details">Stone Details</label>
                <input type="text" id="stone_details" name="stone_details" value="<?php echo $row['stone_id'] . ' - ' . $row['stone_color'] . ' - ' . $row['stone_type'] . ' - ' . $row['stone_weight'] . ' Carats'; ?>" readonly>

            </div>

            <div class="form-group">
                <label for="amount">Amount (Rs.)</label>
                <input type="number" id="amount" name="amount" value="<?php echo $row['total']; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="amountSettled">Amount Settled (Rs.)</label>
                <input type="number" name="amountSettled" value="<?php echo $row['amountSettled']; ?>" required>
            </div>

            

            <div class="form-actions">
                <button type="submit" class="btn-save"><i class='bx bx-edit'></i> Update</button>
            </div>
        </form>
        </div>
    </main>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editSalesForm = document.querySelector('.edit-sales-form'); // Ensure you are selecting the correct form

    // If form exists, add submit event listener
    if (editSalesForm) {
        editSalesForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent default form submission

            // Get the sale ID and other fields from the form
            const saleId = document.querySelector('[name="sale_id"]').value;
            const customerEmail = document.querySelector('[name="customer_email"]').value; // Customer Email
            const stoneDetails = document.querySelector('[name="stone_details"]').value; // Stone Details
            const amount = document.querySelector('[name="amount"]').value; // Total Amount
            const amountSettled = document.querySelector('[name="amountSettled"]').value; // Amount Settled

            // Prepare FormData to be sent to the server
            const formData = new FormData();
            formData.append('sale_id', saleId);
            formData.append('customer_email', customerEmail);
            formData.append('stone_details', stoneDetails);
            formData.append('amount', amount);
            formData.append('amountSettled', amountSettled);

            // Send the form data using Fetch API
            fetch('updateSales.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // If the response contains 'Success', show a success message
                if (data.includes('Success')) {
                    alert('Sales updated successfully!');
                    window.location.href = 'sales.php'; // Redirect back to the sales page
                } else {
                    alert('Error updating sales.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    }
});
</script>

<script src="../../../Components/Accountant_Dashboard_Template/script.js"></script>
</body>
</html>

