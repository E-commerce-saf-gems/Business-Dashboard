<?php
include '../../../database/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];

    // Fetch payment details
    $sql = "SELECT * FROM payments WHERE payment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $payment = $result->fetch_assoc();

    if (!$payment) {
        echo "Payment not found.";
        exit;
    }

    // Fetch buyer email
    $buyer_id = $payment['buyer_id'];
    $buyerSQL = "SELECT email FROM buyer WHERE buyer_id = ?";
    $stmt = $conn->prepare($buyerSQL);
    $stmt->bind_param("i", $buyer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $buyer = $result->fetch_assoc();
    $current_buyer_email = $buyer ? $buyer['email'] : 'Unknown Buyer';

} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle update
    $payment_id = $_POST['payment_id'];
    $buyer_id = $_POST['buyer_id'];
    $stone_id = $_POST['stone_id'];
    $new_amount = $_POST['amount'];

    try {
        $conn->begin_transaction();

        // Fetch original payment amount
        $originalSQL = "SELECT amount FROM payments WHERE payment_id = ?";
        $stmt = $conn->prepare($originalSQL);
        $stmt->bind_param("i", $payment_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $original = $result->fetch_assoc();

        if (!$original) {
            throw new Exception("Original payment not found.");
        }

        $original_amount = $original['amount'];

        // Update payment
        $updateSQL = "UPDATE payments SET buyer_id = ?, stone_id = ?, amount = ? WHERE payment_id = ?";
        $stmt = $conn->prepare($updateSQL);
        $stmt->bind_param("iidi", $buyer_id, $stone_id, $new_amount, $payment_id);
        if (!$stmt->execute()) {
            throw new Exception("Failed to update payment.");
        }

        // Update purchases (adjust amountSettled)
        $adjustment = $new_amount - $original_amount;
        $updatePurchasesSQL = "UPDATE purchases SET amountSettled = amountSettled + ? WHERE buyer_id = ? AND stone_id = ?";
        $stmt = $conn->prepare($updatePurchasesSQL);
        $stmt->bind_param("dii", $adjustment, $buyer_id, $stone_id);
        if (!$stmt->execute()) {
            throw new Exception("Failed to update purchases record.");
        }

        $conn->commit();
        header("Location: ../transactions/transactions.php?PaymentUpdateSuccess=1");
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
    <title>Edit Payment</title>
    <link rel="stylesheet" href="../../../Components/Accountant_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="../transactions/edittransactionstyles.css">
</head>
<body>
<dashboard-component></dashboard-component>

<section id="content">
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Edit Payment</h1>
                <ul class="breadcrumb">
                    <li><a class="active" href="./payments.php">Home</a></li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li><a class="active" href="#">Edit Payment</a></li>
                </ul>
            </div>
        </div>

        <div class="edit-sales-container">
            <form method="POST" class="edit-sales-form" id="editPaymentForm">
                <input type="hidden" name="payment_id" value="<?= htmlspecialchars($payment['payment_id']) ?>">

                <div class="form-group">
                    <label for="buyer">Buyer Email</label>
                    <select id="buyer" name="buyer_id" required>
                        <option value="<?= htmlspecialchars($payment['buyer_id']) ?>" selected>
                            <?= htmlspecialchars($current_buyer_email) ?>
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="stone">Purchased Stone</label>
                    <select id="stone" name="stone_id" required>
                        <option value="<?= htmlspecialchars($payment['stone_id']) ?>" selected>
                            <?= htmlspecialchars($payment['stone_id']) ?>
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="amount">Amount (Rs.)</label>
                    <input type="number" id="amount" name="amount" value="<?= htmlspecialchars($payment['amount']) ?>" step="0.01" required>
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
    const buyerDropdown = document.getElementById('buyer');
    const stoneDropdown = document.getElementById('stone');
    const amountInput = document.getElementById('amount'); // Assuming there's an amount input
    const amountError = document.getElementById('amount-error') || createErrorElement('amount'); // Create error element if it doesn't exist
    const editPaymentForm = document.getElementById('editPaymentForm') || document.querySelector('form'); // Get the form
    
    const currentBuyerId = "<?= htmlspecialchars($payment['buyer_id']) ?>";
    const currentStoneId = "<?= htmlspecialchars($payment['stone_id']) ?>";
    
    let stonesData = {}; // stone_id => amountToBeSettled mapping

    // Function to create error element if it doesn't exist
    function createErrorElement(inputId) {
        const input = document.getElementById(inputId);
        if (!input) return null;
        
        const errorDiv = document.createElement('div');
        errorDiv.id = `${inputId}-error`;
        errorDiv.style.color = 'red';
        errorDiv.style.marginTop = '5px';
        errorDiv.style.fontSize = '14px';
        
        input.parentNode.insertBefore(errorDiv, input.nextSibling);
        return errorDiv;
    }

    // Fetch all buyers
    fetch('./getBuyers.php')
        .then(response => response.json())
        .then(buyers => {
            buyers.forEach(buyer => {
                const option = document.createElement('option');
                option.value = buyer.buyer_id;
                option.textContent = buyer.email;
                if (buyer.buyer_id == currentBuyerId) {
                    option.selected = true;
                }
                buyerDropdown.appendChild(option);
            });
            
            // AFTER loading buyers, now load related stones
            loadStones(currentBuyerId);
        })
        .catch(error => {
            console.error('Error loading buyers:', error);
            showError('buyer-error', 'Failed to load buyers. Please try again.');
        });

    // Load stones based on selected buyer
    function loadStones(buyerId) {
        stoneDropdown.innerHTML = '<option value="">Select a Stone</option>';
        if (buyerId) {
            fetch(`./getStones.php?buyer_id=${buyerId}`)
                .then(response => response.json())
                .then(stones => {
                    stonesData = {}; // Reset stones data
                    
                    stones.forEach(stone => {
                        const option = document.createElement('option');
                        option.value = stone.stone_id;
                        option.textContent = `${stone.type} (Carats: ${stone.weight}) (Amount To Be Settled: Rs.${stone.amountToBeSettled})`;
                        if (stone.stone_id == currentStoneId) {
                            option.selected = true;
                        }
                        stoneDropdown.appendChild(option);
                        
                        // Save stone data for validation
                        stonesData[stone.stone_id] = parseFloat(stone.amountToBeSettled);
                    });
                    
                    // Validate amount after loading stones (if there's an amount input)
                    if (amountInput) {
                        validateAmount();
                    }
                })
                .catch(error => {
                    console.error('Error loading stones:', error);
                    showError('stone-error', 'Failed to load stones. Please try again.');
                });
        }
    }

    // Show error message
    function showError(errorId, message) {
        const errorElement = document.getElementById(errorId) || createErrorElement(errorId.replace('-error', ''));
        if (errorElement) {
            errorElement.textContent = message;
        }
    }

    // Clear error message
    function clearError(errorId) {
        const errorElement = document.getElementById(errorId);
        if (errorElement) {
            errorElement.textContent = '';
        }
    }

    // Function to validate amount
    function validateAmount() {
        if (!amountInput) return true; // Skip if no amount input
        
        const amountValue = parseFloat(amountInput.value);
        const selectedStoneId = stoneDropdown.value;
        const availableAmount = stonesData[selectedStoneId];
        
        // Clear previous error
        clearError('amount-error');
        
        // Check for empty value
        if (amountInput.value.trim() === '') {
            showError('amount-error', 'Please enter an amount.');
            return false;
        }
        
        // Check for invalid number
        if (isNaN(amountValue)) {
            showError('amount-error', 'Please enter a valid amount.');
            return false;
        }
        
        // Check for negative amount
        if (amountValue < 0) {
            showError('amount-error', '❌ Error: Amount cannot be negative.');
            return false;
        }

        if (amountValue === 0) {
            showError('amount-error', '❌ Error: Amount cannot be 0(Zero).');
            return false;
        }
        
        // Check if amount exceeds available amount to be settled
        if (availableAmount !== undefined && amountValue > availableAmount) {
            showError('amount-error', `❌ Error: Amount cannot be greater than the available amount to be settled (Rs.${availableAmount}).`);
            return false;
        }
        
        return true;
    }

    // Event listeners
    buyerDropdown.addEventListener('change', function () {
        loadStones(this.value);
        clearError('buyer-error');
    });
    
    stoneDropdown.addEventListener('change', function () {
        clearError('stone-error');
        if (amountInput) {
            validateAmount();
        }
    });
    
    // Add validation for amount input if it exists
    if (amountInput) {
        amountInput.addEventListener('input', validateAmount);
    }
    
    // Form submission validation
    if (editPaymentForm) {
        editPaymentForm.addEventListener('submit', function (e) {
            // Validate buyer selection
            if (!buyerDropdown.value) {
                showError('buyer-error', 'Please select a buyer.');
                e.preventDefault();
                return;
            }
            
            // Validate stone selection
            if (!stoneDropdown.value) {
                showError('stone-error', 'Please select a stone.');
                e.preventDefault();
                return;
            }
            
            // Validate amount if it exists
            if (amountInput && !validateAmount()) {
                e.preventDefault();
                amountInput.focus();
                return;
            }
        });
    }
});

</script>

<script src="../../../Components/Accountant_Dashboard_Template/script.js"></script>
</body>
</html>
