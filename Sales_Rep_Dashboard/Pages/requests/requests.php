<?php
include '../../../database/db.php';

$sql = "SELECT request.request_id, request.date, customer.firstName AS customer_name, customer.email AS email, request.shape, request.type, 
               request.weight, request.color, request.requirement, request.status, request.declineReason
        FROM request
        JOIN customer ON request.customer_id = customer.customer_id
        ORDER BY request.date DESC";
$result = $conn->query($sql);

$countSql = "
    SELECT 
        COUNT(CASE WHEN request.status = 'P' THEN 1 END) AS pending,
        COUNT(CASE WHEN request.status = 'A' THEN 1 END) AS approved,
        COUNT(CASE WHEN request.status = 'C' THEN 1 END) AS completed,
        COUNT(CASE WHEN request.status = 'D' THEN 1 END) AS declined
    FROM request
";

$countResult = $conn->query($countSql);
$statusCounts = $countResult->fetch_assoc();

$pendingCount = $statusCounts['pending'];
$approvedCount = $statusCounts['approved'];
$completedCount = $statusCounts['completed'];
$declinedCount = $statusCounts['declined'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Gems</title>
    <link rel="stylesheet" href="../../../Components/SalesRep_Dashboard_Template/styles.css">
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
                    <p style="color: orange"><?php echo $pendingCount; ?></p>
                </div>
                <div class="sales-item">
                    <h3>Completed</h3>
                    <p style="color: green"><?php echo $completedCount; ?></p>
                </div>
                <div class="sales-item">
                    <h3>Declined</h3>
                    <p style="color: red"><?php echo $declinedCount; ?></p>
                </div>
                <div class="sales-item">
                    <h3>Approved</h3>
                    <p style="color: lightblue"><?php echo $approvedCount; ?></p>
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
                                echo "<select name='status' data-original-value='" . $row['status'] . "' onchange='handleStatusChange(this, " . $row['request_id'] . ")'>";
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
        setTimeout(function () {
            const message = document.querySelector(".success-message");
            if (message) {
                message.style.display = "none";
            }
        }, 5000);

        let currentRequestId = null;
        let currentSelectElement = null;

        function handleStatusChange(selectElement, requestId) {
            const updateButton = document.getElementById(`update_btn_${requestId}`);
            const originalValue = selectElement.getAttribute('data-original-value');

            if (selectElement.value !== originalValue) {
                updateButton.style.display = 'inline-block';
            } else {
                updateButton.style.display = 'none';
            }

            if (selectElement.value === 'D') {
                currentRequestId = requestId;
                currentSelectElement = selectElement;

                const modal = document.getElementById('custom-prompt');
                modal.style.display = 'block';

                document.getElementById('prompt-input').value = '';

                return false;
            }

            return true;
        }

        document.getElementById('prompt-ok').onclick = function () {
            const reason = document.getElementById('prompt-input').value.trim();
            if (!reason) {
                alert('A reason is required to decline a request.');
                return;
            }
            document.getElementById(`decline_reason_${currentRequestId}`).value = reason;
            document.getElementById('custom-prompt').style.display = 'none';
            const updateButton = document.getElementById(`update_btn_${currentRequestId}`);
            updateButton.style.display = 'inline-block';
        };

        document.getElementById('prompt-cancel').onclick = function () {
            if (currentSelectElement) {
                const originalValue = currentSelectElement.getAttribute('data-original-value');
                currentSelectElement.value = originalValue;
            }
            const updateButton = document.getElementById(`update_btn_${currentRequestId}`);
            updateButton.style.display = 'none';
            document.getElementById('custom-prompt').style.display = 'none';
        };

        document.querySelector('.btn-filter').addEventListener('click', function () {
            const dateFilter = document.getElementById('date-filter').value;
            const statusFilter = document.getElementById('status-filter').value;
            const gemTypeFilter = document.getElementById('customer-filter').value.toLowerCase();

            const rows = document.querySelectorAll('.sales-table tbody tr');

            rows.forEach(row => {
                const date = row.cells[0].innerText.trim();
                const email = row.cells[1].innerText.trim();
                const shape = row.cells[2].innerText.trim();
                const type = row.cells[3].innerText.trim();
                const statusSelect = row.querySelector('select');
                const status = statusSelect ? statusSelect.value : '';

                let show = true;

                if (dateFilter && !date.startsWith(dateFilter)) {
                    show = false;
                }
                if (statusFilter && status !== statusFilter) {
                    show = false;
                }
                if (gemTypeFilter && !(shape.toLowerCase().includes(gemTypeFilter) || type.toLowerCase().includes(gemTypeFilter))) {
                    show = false;
                }

                row.style.display = show ? '' : 'none';
            });
        });
</>


            <script src="../../../Components/SalesRep_Dashboard_Template/script.js"></script>
    <script src="./admin.js"></script>
</body>

</html>

<?php
$conn->close();
?>