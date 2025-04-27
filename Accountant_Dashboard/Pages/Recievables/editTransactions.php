<?php
include '../../../database/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['transaction_id'])) {
    $transaction_id = $_GET['transaction_id'];

    // Fetch transaction details
    $sql = "SELECT * FROM transactions WHERE transaction_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $transaction = $result->fetch_assoc();

    if (!$transaction) {
        echo "Transaction not found.";
        exit;
    }

    // Fetch customer email
    $customer_id = $transaction['customer_id'];
    $customerSQL = "SELECT email FROM customer WHERE customer_id = ?";
    $stmt = $conn->prepare($customerSQL);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();
    $current_customer_email = $customer ? $customer['email'] : 'Unknown Customer';

} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update the transaction
    $transaction_id = $_POST['transaction_id'];
    $customer_id = $_POST['customer_id'];
    $stone_id = $_POST['stone_id'];
    $amount = $_POST['amount'];

    try {
        $conn->begin_transaction();

        // Fetch original transaction amount
        $originalSQL = "SELECT amount FROM transactions WHERE transaction_id = ?";
        $stmt = $conn->prepare($originalSQL);
        $stmt->bind_param("i", $transaction_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $original = $result->fetch_assoc();
        if (!$original) {
            throw new Exception("Original transaction not found.");
        }

        $original_amount = $original['amount'];

        // Update transaction
        $updateSQL = "UPDATE transactions SET customer_id = ?, stone_id = ?, amount = ? WHERE transaction_id = ?";
        $stmt = $conn->prepare($updateSQL);
        $stmt->bind_param("iidi", $customer_id, $stone_id, $amount, $transaction_id);
        if (!$stmt->execute()) {
            throw new Exception("Failed to update transaction.");
        }

        // Update sales (adjust amountSettled)
        $adjustment = $amount - $original_amount;
        $updateSalesSQL = "UPDATE sales SET amountSettled = amountSettled + ? WHERE customer_id = ? AND stone_id = ?";
        $stmt = $conn->prepare($updateSalesSQL);
        $stmt->bind_param("dii", $adjustment, $customer_id, $stone_id);
        if (!$stmt->execute()) {
            throw new Exception("Failed to update sales record.");
        }

        $conn->commit();
        header("Location: ../transactions/transactions.php?ReceivalUpdateSuccess=1");
    } catch (Exception $e) {
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
    <title>Edit Transaction</title>
    <link rel="stylesheet" href="../../../Components/Accountant_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="../transactions/edittransactionstyles.css">
</head>
<body>
<dashboard-component></dashboard-component>

<section id="content">
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Edit Transaction</h1>
                <ul class="breadcrumb">
                    <li><a class="active" href="./invoices.php">Home</a></li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li><a class="active" href="#">Edit Transaction</a></li>
                </ul>
            </div>
        </div>

        <div class="edit-sales-container">
            <form method="POST" class="edit-sales-form" id="editTransactionForm">
                <input type="hidden" name="transaction_id" value="<?= htmlspecialchars($transaction['transaction_id']) ?>">

                <div class="form-group">
                    <label for="customer">Customer Email</label>
                    <select id="customer" name="customer_id" required>
                        <option value="<?= htmlspecialchars($transaction['customer_id']) ?>" selected>
                            <?= htmlspecialchars($current_customer_email) ?>
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="stone">Purchased Stone</label>
                    <select id="stone" name="stone_id" required>
                        <option value="<?= htmlspecialchars($transaction['stone_id']) ?>" selected>
                            <?= htmlspecialchars($transaction['stone_id']) ?>
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="amount">Amount (Rs.)</label>
                    <input type="number" id="amount" name="amount" value="<?= htmlspecialchars($transaction['amount']) ?>" step="0.01" required>
                    <div id="amount-error" style="color: red; margin-top: 5px; font-size: 14px;"></div> <!-- Error message here -->
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
    const amountInput = document.getElementById('amount');
    const amountError = document.getElementById('amount-error');
    const editTransactionForm = document.getElementById('editTransactionForm');

    const currentCustomerId = "<?= htmlspecialchars($transaction['customer_id']) ?>";
    const currentStoneId = "<?= htmlspecialchars($transaction['stone_id']) ?>";

    let stonesData = {}; // stone_id => amountToBeSettled mapping

    // Load customers
    fetch('./getCustomers.php')
        .then(response => response.json())
        .then(customers => {
            customers.forEach(customer => {
                const option = document.createElement('option');
                option.value = customer.customer_id;
                option.textContent = customer.email;
                if (customer.customer_id == currentCustomerId) {
                    option.selected = true;
                }
                customerDropdown.appendChild(option);
            });
        })
        .catch(error => console.error('Error loading customers:', error));

    function loadStones(customerId, preselectStoneId = null) {
        stoneDropdown.innerHTML = ''; // Clear previous stones

        if (customerId) {
            fetch(`./getStones.php?customer_id=${customerId}`)
                .then(response => response.json())
                .then(stones => {
                    stonesData = {}; // Reset stonesData

                    stones.forEach(stone => {
                        const option = document.createElement('option');
                        option.value = stone.stone_id;
                        option.textContent = `${stone.type} (Carats: ${stone.weight}) (Amount To Be Settled: Rs.${stone.amountToBeSettled})`;

                        if (stone.stone_id == preselectStoneId) {
                            option.selected = true;
                        }

                        stoneDropdown.appendChild(option);

                        // Save stone_id => amountToBeSettled
                        stonesData[stone.stone_id] = parseFloat(stone.amountToBeSettled);
                    });
                })
                .catch(error => console.error('Error loading stones:', error));
        }
    }

    // On customer change, reload stones
    customerDropdown.addEventListener('change', function () {
        loadStones(this.value);
    });

    // Add validation on amount input to prevent negative values in real-time
    amountInput.addEventListener('input', function() {
        validateAmount();
    });

    // Function to validate amount
    function validateAmount() {
        const amountValue = parseFloat(amountInput.value);
        const selectedStoneId = stoneDropdown.value;
        const availableAmount = stonesData[selectedStoneId];

        // Clear previous error message
        amountError.textContent = "";

        // Check for negative amount
        if (amountInput.value.trim() === '') {
            amountError.textContent = "Please enter an amount.";
            return false;
        }

        

        if (amountValue < 0) {
            amountError.textContent = "❌ Error: Amount cannot be negative.";
            return false;
        }

        if (amountValue === 0) {
            amountError.textContent = "❌ Error: Amount cannot be 0(Zero).";
            return false;
        }

        // Check if amount exceeds available amount to be settled
        if (availableAmount !== undefined && amountValue > availableAmount) {
            amountError.textContent = `❌ Error: Amount cannot be greater than the available amount to be settled (Rs.${availableAmount}).`;
            return false;
        }

        return true;
    }

    // On form submit, validate amount
    editTransactionForm.addEventListener('submit', function (e) {
        const amountValue = parseFloat(amountInput.value);
        
        // Clear previous error message
        amountError.textContent = "";

        if (isNaN(amountValue)) {
            amountError.textContent = "Please enter a valid amount.";
            e.preventDefault();
            return;
        }

        // Run the validation and prevent form submission if validation fails
        if (!validateAmount()) {
            e.preventDefault();
            // Focus on the amount input for better UX
            amountInput.focus();
            return;
        }
    });

    // Also validate when stone selection changes
    stoneDropdown.addEventListener('change', function() {
        validateAmount();
    });

    // Initial load for current customer and stone
    loadStones(currentCustomerId, currentStoneId);
});

</script>


<script src="../../../Components/Accountant_Dashboard_Template/script.js"></script>
</body>
</html>


