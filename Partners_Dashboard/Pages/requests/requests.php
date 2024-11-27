<?php
include '../../../database/db.php';

$sql = "SELECT request.request_id, request.date, customer.firstName AS customer_name, customer.email AS email, request.shape, request.type, 
               request.weight, request.color, request.requirement, request.status, request.declineReason
        FROM request
        JOIN customer ON request.customer_id = customer.customer_id
        ORDER BY request.date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Gems</title>
    <link rel="stylesheet" href="../../../Components/Partner_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="./requests.css">   
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <dashboard-component></dashboard-component>

    <section id="content">
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Customer Requests</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a class="active" href="#">Request Summary</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sales-summary-box">
                <div class="sales-summary-title">
                    <h2>Monthly Requests Summary</h2>
                </div>
                <div class="sales-item">
                    <h3>Pending</h3>
                    <p style="color: orange">2</p>
                </div>
                <div class="sales-item">
                    <h3>Completed</h3>
                    <p style="color: green">4</p>
                </div>
                <div class="sales-item">
                    <h3>Declined</h3>
                    <p style="color: red">2</p>
                </div>
                <div class="sales-item">
                    <h3>Approved</h3>
                    <p style="color:light-blue">3</p>
                </div>
            </div>

            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="success-message">
                    Request status was updated successfully! And Email has been Sent!
                </div>
            <?php endif; ?>


            <div class="sales-table-container">
                <div class="table-filters">
                    <label for="date-filter">Date:</label>
                    <input type="date" id="date-filter">
                    
                    <label for="status-filter">Status:</label>
                    <select id="status-filter">
                        <option value="">All</option>
                        <option value="A">Approved</option>
                        <option value="P">Pending</option>
                        <option value="C">Complete</option>
                    </select>

                    <label for="customer-filter">Gem Type:</label>
                    <input type="text" id="customer-filter" placeholder="Search Gem Type">
                    
                    <button class="btn-filter">Filter</button>
                </div>

                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Email</th>
                            <th>Shape</th>
                            <th>Type</th>
                            <th>Weight</th>
                            <th>Color</th>
                            <th>Other Requirements</th>
                            <th>Status</th>
                            <th>Decline Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['date'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['shape'] . "</td>";
                                echo "<td>" . $row['type'] . "</td>";
                                echo "<td>" . $row['weight'] . "</td>";
                                echo "<td>" . $row['color'] . "</td>";
                                echo "<td>" . $row['requirement'] . "</td>";

                                echo "<td>";
                                echo "<form method='POST' action='./updateRequest.php' onsubmit='return handleStatusChange(this)'>"; 
                                echo "<input type='hidden' name='request_id' value='" . $row['request_id'] . "'>";
                                echo "<input type='hidden' name='decline_reason' id='decline_reason_" . $row['request_id'] . "' value=''>"; 
                                echo "<select name='status' onchange='handleStatusChange(this, " . $row['request_id'] . ")'>"; 
                                echo "<option value='P'" . ($row['status'] === 'P' ? " selected" : "") . ">Pending</option>";
                                echo "<option value='A'" . ($row['status'] === 'A' ? " selected" : "") . ">Approved</option>";
                                echo "<option value='C'" . ($row['status'] === 'C' ? " selected" : "") . ">Complete</option>";
                                echo "<option value='D'" . ($row['status'] === 'D' ? " selected" : "") . ">Declined</option>";
                                echo "</select>";
                                echo "<button id='update_btn_" . $row['request_id'] . "' type='submit' style='display: none;'>Update</button>";
                                echo "</form>";
                                echo "</td>";  
                                
                                echo "<td>" . $row['declineReason'] . "</td>";
                                echo "</tr>"; 
                            }
                        } else {
                            echo "<tr><td colspan='9'>No requests found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div> 
            <div id="custom-prompt" class="modal">
                <div class="modal-content">
                    <h2>Decline Reason</h2>
                    <p>Please provide a reason for declining this request:</p>
                    <textarea id="prompt-input" rows="4" placeholder="Enter reason here..."></textarea>
                    <div class="modal-buttons">
                        <button id="prompt-ok">Confirm</button>
                        <button id="prompt-cancel">Cancel</button>
                    </div>
                </div>
            </div>
   
        </main>
    </section>

    <script>
        setTimeout(function() {
        const message = document.querySelector(".success-message");
        if (message) {
            message.style.display = "none";
        }
        }, 5000);

        function handleStatusChange(selectElement, requestId) {
    const updateButton = document.getElementById(`update_btn_${requestId}`);
    const originalValue = selectElement.getAttribute('data-original-value') || selectElement.value;

    if (selectElement.value !== originalValue) {
        updateButton.style.display = 'inline-block';
    } else {
        updateButton.style.display = 'none';
    }

    if (selectElement.value === 'D') {
        // Show custom prompt
        const modal = document.getElementById('custom-prompt');
        modal.style.display = 'block';

        // Handle OK and Cancel actions
        const inputField = document.getElementById('prompt-input');
        const okButton = document.getElementById('prompt-ok');
        const cancelButton = document.getElementById('prompt-cancel');

        // Clear previous input
        inputField.value = '';

        // OK Button Logic
        okButton.onclick = function () {
            const reason = inputField.value.trim();
            if (!reason) {
                alert('A reason is required to decline a request.');
                return;
            }
            document.getElementById(`decline_reason_${requestId}`).value = reason;
            modal.style.display = 'none'; // Hide modal
            updateButton.style.display = 'inline-block';
        };

        // Cancel Button Logic
        cancelButton.onclick = function () {
            selectElement.value = originalValue; // Revert selection
            modal.style.display = 'none'; // Hide modal
            updateButton.style.display = 'none'; // Hide update button
        };

        // Prevent form submission for now
        return false;
    }

    return true; // Allow form submission
}

    </script>
    <script src="../../../Components/Partner_Dashboard_Template/script.js"></script>
    <script src="./admin.js"></script>
</body>
</html>

<?php
$conn->close();
?>
