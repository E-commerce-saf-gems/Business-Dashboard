<?php
include '../../../database/db.php';

$sql = "SELECT expense_id, date, type, description, amount, status 
        FROM expenses;";
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
            <div class="sales-summary-box">
                
                <div class="sales-summary-title">
                    <h2>Expenses Preview</h2>
                </div>
                <div class="sales-item">
                    <h3>Weekly Total Expenses</h3>
                    <p>Rs.543</p>
                </div>
                <div class="sales-item">
                    <h3>Monthly Total Expenses</h3>
                    <p>Rs.2132</p>
                </div>
                <div class="sales-item">
                    <h3>Top Expense Catagory</h3>
                    <p>Marketing</p>
                </div>
            </div>

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
                
                <div class="report-box">
                    <h2>Cutting & Pollishing </h2>
                    <a href="./addExpenses.html" class="report-link">Add</a>
                </div>

                <div class="report-box">
                    <h2>Certifications </h2>
                    <a href="./addExpenses.html" class="report-link">Add</a>
                </div>

                <div class="report-box">
                    <h2>Marketing</h2>
                    <a href="./addExpenses.html" class="report-link">Add </a>
                </div>

                <div class="report-box">
                    <h2>Logistics</h2>
                    <a href="./addExpenses.html" class="report-link">Add</a>
                </div>

                <div class="report-box">
                    <h2>Other</h2>
                    <a href="./addExpenses.html" class="report-link">Add</a>
                </div>
            </div>

            <div class="sales-table-container">
                <div class="table-filters">
                    <label for="customer-filter">Category:</label>
                    <input type="text" id="customer-filter" placeholder="Search Category">
                    
                    <button class="btn-filter">Search</button>
                </div>

                <!-- Table -->
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Expense ID</th>
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
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>