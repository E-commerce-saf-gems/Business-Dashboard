<?php
include '../../../database/db.php';

// Get filter values from GET request
$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';
$customerFilter = isset($_GET['customer']) ? $_GET['customer'] : '';

$sql = "SELECT t.transaction_id, t.date, c.email AS email, t.amount
        FROM transactions as t
        JOIN customer as c ON t.customer_id = c.customer_id
        WHERE 1";

// Apply the date filter for transactions
if ($dateFilter) {
    $sql .= " AND DATE(t.date) = '" . $conn->real_escape_string($dateFilter) . "'";
}

// Apply the customer filter for transactions
if ($customerFilter) {
    $sql .= " AND c.email LIKE '%" . $conn->real_escape_string($customerFilter) . "%'";
}
$sql .= " ORDER BY t.date DESC";  // Order by the date column


$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accountant Transactions</title>
    <link rel="stylesheet" href="../../../Components/Accountant_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="../transactions/styles.css"> 
    <link rel="stylesheet" href="../transactions/edittransactionstyles.css">   
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; 
            z-index: 1000; 
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; 
            background-color: rgba(0,0,0,0.5); /* Semi-transparent black background */
        }
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border-radius: 10px;
            width: 30%;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        .modal-actions {
            gap: 5px;
            display: flex;
            justify-content: center;
            
        }
        .modal-actions button {
            margin: 0 10px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-confirm {
            background-color: var(--red);
            color: #fff;
        }
        .btn-cancel {
            background-color: var(--teal);
            color: #fff;
        }
    </style>

    
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
                        <li><a class="active" href="#">Invoices Summary</a></li>
					</ul>
				</div>
			</div>

            <div class="sales-summary-box">
                <div class="sales-summary-title">
                    <h2>Monthly Invoices Summary</h2>
                </div>
                <div class="sales-item">
                    <h3>This Month</h3>
                    <p>$2543</p>
                </div>
                <div class="sales-item">
                    <h3>Last Month</h3>
                    <p>$2132</p>
                </div>
                <div class="sales-item">
                    <h3>Last Two Months</h3>
                    <p>$4089</p>
                </div>
            </div>

            <div class="table-header">
                    <div class="option-tab">
                        <a href="../transactions/transactions.php" class="tab-btn"><i ></i>All</a>
                        <a href="#" class="tab-btn"><i ></i>Invoice</a>
                        <a href="../Payables/payments.php" class="tab-btn"><i ></i>Payment</a>
                    </div>
                    <div class="addnew">
                        <a href="./addTransactions.html" class="btn-add"><i class='bx bx-plus'></i>Add New</a>
                    </div>
                    
                </div>
            </div>


            
            <div class="sales-table-container">
                <div class="table-filters">
                    <form method="GET" action="invoices.php">
                        <label for="date-filter">Date:</label>
                        <input type="date" id="date-filter" name="date" value="<?php echo htmlspecialchars($dateFilter); ?>">
                        
                        <label for="customer-filter">Email:</label>
                        <input type="text" id="customer-filter" name="customer" placeholder="Search Customer" value="<?php echo htmlspecialchars($customerFilter); ?>">
                        
                        <button class="btn-filter" type="submit">Filter</button>
                        <button><a href="invoices.php" class="btn-clear">Clear</a></button>
                    </form>
                </div>

                <!-- Table -->
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Date</th>
                            <th>Customer Email</th>
                            <th>Amount</th>
                            <th>Option</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ( $result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Determine the status label and color
                               
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['transaction_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>Rs. " . htmlspecialchars($row['amount']) . "</td>";
                                echo "<td class='actions'>
                                        <a href='../Invoices/invoicepreview.php?transaction_id=" . $row['transaction_id'] ."' class='btn'><i class='bx bx-show'></i></a>        
                                        <a href='./editTransactions.php?transaction_id=" . $row['transaction_id'] . "' class='btn'><i class='bx bx-pencil'></i></a>
                                        <button class='btn deleteBtn' data-id='" . $row['transaction_id'] . "'><i class='bx bx-trash'></i></button>
                                    </td>";
                                echo "<td class='actions'>
                                    <a href='#' class='btn'><i class='bx bx-printer'></i></a>        
                                    <a href='#' class='btn'><i class='bx bx-send'></i></a>
                                </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9'>No transactions found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Modal for Delete Confirmation -->
                <div class="modal" id="confirmationModal">
                    <div class="modal-content">
                        <p>Are you sure you want to delete this invoice?</p>
                        <div class="modal-actions">
                            <button class="btn-cancel" id="cancelDelete">Cancel</button>
                            <form id="deleteForm" action="./deleteTransactions.php" method="POST">
                                <input type="hidden" name="transaction_id" id="transaction_id">
                                <button type="submit" class="btn-confirm">Yes, Delete</button>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>    
        </main>
    </section>

    <script>
        const deleteButtons = document.querySelectorAll('.deleteBtn');
        const modal = document.getElementById('confirmationModal');
        const cancelDelete = document.getElementById('cancelDelete');
        const invoiceIdInput = document.getElementById('transaction_id');
        const deleteForm = document.getElementById('deleteForm');

        deleteButtons.forEach(button => {
            button.addEventListener('click', () => {
                const invoiceId = button.getAttribute('data-id');
                invoiceIdInput.value = invoiceId;
                modal.style.display = 'block';
            });
        });

        cancelDelete.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        window.addEventListener('click', event => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    </script>

    <script src="../../../Components/Accountant_Dashboard_Template/script.js"></script>
    <script src="./script.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>