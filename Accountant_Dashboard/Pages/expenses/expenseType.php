<?php
include '../../../database/db.php';

// Get filter values from GET request
$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';
$categoryFilter = isset($_GET['type']) ? $_GET['type'] : '';

$sql = "SELECT * 
        FROM expenses  WHERE 1";

// Apply the date filter for transactions
if ($dateFilter) {
    $sql .= " AND DATE(date) = '" . $conn->real_escape_string($dateFilter) . "'";
}

// Apply the category filter for transactions
if ($categoryFilter) {
    $sql .= " AND type LIKE '%" . $conn->real_escape_string($categoryFilter) . "%'";
}

$sql .= " ORDER BY DATE(date) DESC";  // Order by the date column

$result = $conn->query($sql);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenses Management</title>
    <link rel="stylesheet" href="../../../Components/Accountant_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="../transactions/styles.css"> 
    <link rel="stylesheet" href="./expenses.css">   
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
					<h1>Expense Management</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="#">Expenses Summary</a>
						</li>
					</ul>
				</div>
			</div>
            <section class="overview">
                <h2>Financial Overview</h2>
                <div class="overview-filter">
                    <select id="viewFilter">
                        <option>Monthly</option>
                        <option>Quarterly</option>
                        <option>Yearly</option>
                    </select>
                </div>
                <div class="overview-boxes">
                    <div class="overview-box">
                        <h4>Total Expenses</h4>
                        <p id="totalExpenses">Rs. 0</p>
                    </div>
                    <div class="overview-box">
                        <h4>Total Paid Expenses</h4>
                        <p id="totalPaidExpenses">Rs. 0</p>
                    </div>
                    <div class="overview-box">
                        <h4>Total Pending Expenses</h4>
                        <p id="totalPendingExpenses">Rs. 0</p>
                    </div>
                    <div class="overview-box">
                        <h4>Outstanding Expenses Type</h4>
                        <p id="maxExpenseCategory">None</p>
                    </div>
                </div>
            </section> 

            

            <div class="head-title">
                <div class="left">
					<h1>Choose Expenses Category</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="#">Add New Expense</a>
						</li>
					</ul>
				</div>
			</div>

            <div class="report-boxes-container">
                <button class="report-box" onclick="location.href='./addExpenses.html'">
                    Add New Expense
                </button>

                
            </div>


            <div class="sales-table-container">
                <div class="table-filters">
                    <form method="GET" action="expenseType.php">
                        <!-- Date Filter -->
                        <label for="date-filter">Date:</label>
                        <input type="date" id="date-filter" name="date" value="<?php echo htmlspecialchars($dateFilter); ?>">

                        <!-- Category Filter (Dropdown) -->
                        <label for="category-filter">Type:</label>
                        <select id="category-filter" name="type">
                            <option value="">Select Type</option>
                            <option value="Cutting and Polishing" <?php echo ($categoryFilter == 'Cutting and Polishing') ? 'selected' : ''; ?>>Cutting and Polishing</option>
                            <option value="Certifications" <?php echo ($categoryFilter == 'Certifications') ? 'selected' : ''; ?>>Certifications</option>
                            <option value="Marketing" <?php echo ($categoryFilter == 'Marketing') ? 'selected' : ''; ?>>Marketing</option>
                            <option value="Logistics" <?php echo ($categoryFilter == 'Logistics') ? 'selected' : ''; ?>>Logistics</option>
                            <option value="Other" <?php echo ($categoryFilter == 'Other') ? 'selected' : ''; ?>>Other</option>
                            <!-- Add more categories as needed -->
                        </select>

                        <!-- Filter Button -->
                        <button class="btn-filter" type="submit">Filter</button>

                        <!-- Clear Button (Redirect to clear filters) -->
                        <button><a href="expenseType.php" class="btn-clear">Clear</a></button>
                    </form>
                </div>
   

                <!-- Table -->
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Expense ID</th>
                            <th>Stone ID</th>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Option</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Determine the status label and color
                               
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['expense_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['stone_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                echo "<td class='actions'> 
                                        <a href='./editExpenses.php?expense_id=" . $row['expense_id'] . "' class='btn'><i class='bx bx-pencil'></i></a>
                                        <button class='btn deleteBtn' data-id='" . $row['expense_id'] . "'><i class='bx bx-trash'></i></button>
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No Expense found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Modal for Delete Confirmation -->
                <div class="modal" id="confirmationModal">
                    <div class="modal-content">
                        <p>Are you sure you want to delete this expense?</p>
                        <div class="modal-actions">
                            <button class="btn-cancel" id="cancelDelete">Cancel</button>
                            <form id="deleteForm" action="./deleteExpenses.php" method="POST">
                                <input type="hidden" name="expense_id" id="expense_id">
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
    const expenseIdInput = document.getElementById('expense_id');
    const deleteForm = document.getElementById('deleteForm');

    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Get the expense_id from the button's data-id attribute
            const expenseId = button.getAttribute('data-id');
            expenseIdInput.value = expenseId;  // Set the expense_id input value to the selected expense_id
            modal.style.display = 'block';  // Show the modal for confirmation
        });
    });

    // Cancel button in the modal hides the modal
    cancelDelete.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Clicking anywhere outside the modal closes it
    window.addEventListener('click', event => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
</script>



    <script src="../../../Components/Accountant_Dashboard_Template/script.js"></script>
        <!-- At the bottom of body -->
    <script src="expenses.js"></script>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>