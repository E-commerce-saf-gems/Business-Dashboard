<?php
include '../../../database/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['transaction_id'])) {
    $transaction_id = $_GET['transaction_id'];

    // Fetch invoice details
    $sql = "SELECT * FROM transactions WHERE transaction_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $invoice = $result->fetch_assoc();

    if (!$invoice) {
        echo "Invoice not found.";
        exit;
    }

    // Fetch current customer email
    $customer_id = $invoice['customer_id'];
    $customerSQL = "SELECT email FROM customer WHERE customer_id = ?";
    $stmt = $conn->prepare($customerSQL);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();
    $current_customer_email = $customer ? $customer['email'] : 'Unknown Customer';
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transaction_id = $_POST['transaction_id'];
    $customer_id = $_POST['customer_id'];
    $stone_id = $_POST['stone_id'];
    $new_amount = $_POST['amount'];

    try {
        // Start transaction
        $conn->begin_transaction();

        // Fetch original transaction amount
        $getOriginalSQL = "SELECT amount FROM transactions WHERE transaction_id = ?";
        $stmt = $conn->prepare($getOriginalSQL);
        $stmt->bind_param("i", $transaction_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $original_invoice = $result->fetch_assoc();

        if (!$original_invoice) {
            throw new Exception("Original invoice not found.");
        }

        $original_amount = $original_invoice['amount'];

        // Update the transactions table
        $updateInvoiceSQL = "UPDATE transactions SET amount = ?, customer_id = ?, stone_id = ? WHERE transaction_id = ?";
        $stmt = $conn->prepare($updateInvoiceSQL);
        $stmt->bind_param("diii", $new_amount, $customer_id, $stone_id, $transaction_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update invoice.");
        }

        // Update the sales table
        $adjustment = $new_amount - $original_amount; // Difference between new and original amount
        $updateSalesSQL = "UPDATE sales SET amountSettled = amountSettled + ? WHERE customer_id = ? AND stone_id = ?";
        $stmt = $conn->prepare($updateSalesSQL);
        $stmt->bind_param("dii", $adjustment, $customer_id, $stone_id);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update sales.");
        }

        // Commit transaction
        $conn->commit();
        header("Location: ./invoices.php?UpdateSuccess=1");
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Invoice</title>
    <link rel="stylesheet" href="../../../Components/Accountant_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="../transactions/edittransactionstyles.css">
</head>
<body>
    <dashboard-component></dashboard-component>

    <section id="content">
        <main>
        <div class="head-title">
				<div class="left">
					<h1>Edit Invoice</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="./invoices.php">Home</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Edit Invoice</a>
						</li>
					</ul>
				</div>
			</div>

            <div class="edit-sales-container">
                <form class="edit-sales-form" method="POST" id="editInvoiceForm">
                    <input type="hidden" name="transaction_id" value="<?= htmlspecialchars($invoice['transaction_id']) ?>">

                    <div class="form-group">
                        <label for="customer">Customer Email</label>
                        <select id="customer" name="customer_id" required>
                            <option value="<?= htmlspecialchars($invoice['customer_id']) ?>" selected>
                                <?= htmlspecialchars($current_customer_email) ?>
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="stone">Purchased Stones</label>
                        <select id="stone" name="stone_id" required>
                            <option value="<?= htmlspecialchars($invoice['stone_id']) ?>">
                                <?= htmlspecialchars($invoice['stone_id']) ?>
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="amount">Amount (Rs.)</label>
                        <input type="number" id="amount" name="amount" value="<?= htmlspecialchars($invoice['amount']) ?>" required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save">Save Changes</button>
                    </div>
                </form>
            </div>
        </main>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const customerDropdown = document.getElementById('customer');
            const stoneDropdown = document.getElementById('stone');

            // Fetch customers
            fetch('./getCustomers.php')
                .then(response => response.json())
                .then(customers => {
                    customers.forEach(customer => {
                        const option = document.createElement('option');
                        option.value = customer.customer_id; // Set value as customer_id
                        option.textContent = customer.email; // Show customer email
                        customerDropdown.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching buyers:', error));

            // Fetch stones when a customer is selected
            customerDropdown.addEventListener('change', function () {
                const customerId = this.value;

                // Clear existing stones
                stoneDropdown.innerHTML = '<option value="">Select a Stone</option>';

                if (customerId) {
                    fetch(`./getStones.php?customer_id=${customerId}`)
                        .then(response => response.json())
                        .then(stones => {
                            stones.forEach(stone => {
                                const option = document.createElement('option');
                                option.value = stone.stone_id;
                                option.textContent = `${stone.type} (Carats: ${stone.weight}) (Amount To Be Settled: Rs.${stone.amountToBeSettled})`;
                                stoneDropdown.appendChild(option);
                            });

                            // Set the current stone as selected
                            stoneDropdown.value = "<?= htmlspecialchars($invoice['stone_id']) ?>";
                        })
                        .catch(error => console.error('Error fetching stones:', error));
                }
            });

            // Trigger initial stones load for the current buyer
            customerDropdown.dispatchEvent(new Event('change'));
        });

        // Hide success message after 5 seconds
        setTimeout(function () {
            const message = document.querySelector(".success-message");
            if (message) {
                message.style.display = "none";
            }
        }, 5000);
    </script>
    <script src="../../../Components/Accountant_Dashboard_Template/script.js"></script>


</body>
</html>

