<?php
include '../../../database/db.php';

// Get filter values from GET request
$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';
$categoryFilter = isset($_GET['type']) ? $_GET['type'] : '';

$sql = "SELECT report_id, report_type, generated_date FROM reports WHERE 1";

// Apply the date filter
if ($dateFilter) {
    $sql .= " AND DATE(generated_date) = '" . $conn->real_escape_string($dateFilter) . "'";
}

// Apply the category filter
if ($categoryFilter) {
    $sql .= " AND report_type LIKE '%" . $conn->real_escape_string($categoryFilter) . "%'";
}

$sql .= " ORDER BY generated_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accountant Reports</title>
    <link rel="stylesheet" href="../../../Components/Accountant_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="../transactions/styles.css">    
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background: #fff;
            margin: 10% auto;
            padding: 30px;
            border-radius: 15px;  /* Increased border-radius for a smoother corner */
            width: 100%;
            height: 50%;  /* Increased width of the modal */
            max-width: 800px;  /* Ensure it's not too large on wide screens */
            text-align: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .modal-actions {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 50px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 8px;  /* Rounded corners for buttons */
            border: none;
            transition: all 0.3s ease;  /* Smooth hover transition */
        }

        button:hover {
            opacity: 0.9;
        }

        /* Button for 'Generate' report - Green color */
        button:nth-child(1) {
            background-color: teal; 
            color: white;
        }

        button:nth-child(1):hover {
            background-color: teal;  
        }

        /* Button for 'Cancel' - Light Red color */
        button:nth-child(2) {
            background-color:rgb(247, 36, 21);  /* Red */
            color: white;
        }

        button:nth-child(2):hover {
            background-color:rgb(163, 36, 34);  /* Darker red on hover */
        }

        /* Style for the 'Select Period' and 'Custom Date Range' Inputs */
        #time-period, #start-date, #end-date {
            padding: 8px;
            font-size: 20px;
            border-radius: 5px;
            border: 2px solid #ccc;
            margin-top: 30px;
        }

        #custom-date-range {
            margin-top: 20px;
        }

    </style>
</head>
<body>
    <dashboard-component></dashboard-component>

    <section id="content">
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Generate Report</h1>
                </div>
            </div>

            <div class="report-boxes-container">
                <div class="report-box" onclick="openModal('profitLoss')">
                    <h2>Profit and Loss Report</h2>
                    <p>The Profit and Loss Report shows our business revenue, costs, expenses, and net profit over a specific period.</p>
                    <a href="#" class="report-link">Generate Report</a>
                </div>

                <div class="report-box" onclick="openModal('sales')">
                    <h2>Sales Report</h2>
                    <p>The Sales Report provides insights into total sales, sales by gem type, auction vs. regular sales, and customer segments.</p>
                    <a href="#" class="report-link">Generate Report</a>
                </div>

                <div class="report-box" onclick="openModal('inventory')">
                    <h2>Inventory Report</h2>
                    <p>The Inventory Report gives an overview of our stock, including total value, sales, acquisitions, and aging.</p>
                    <a href="#" class="report-link">Generate Report</a>
                </div>
            </div>

            
        </main>
    </section>

    <!-- Report Time Period Modal -->
    <div id="reportModal" class="modal">
        <div class="modal-content">
            <h1>Select Report Time Period</h1>
            <label for="time-period">Choose Period:</label>
            <select id="time-period">
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
                <option value="yearly">Yearly</option>
                <option value="custom">Custom Date Range</option>
            </select>
            
            <div id="custom-date-range" style="display: none; margin-top: 10px;">
                <label>From: <input type="date" id="start-date"></label>
                <label>To: <input type="date" id="end-date"></label>
            </div>
            
            <div class="modal-actions">
                <button onclick="generateReport()">Generate</button>
                <button onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        let selectedReportType = '';

        function openModal(reportType) {
            selectedReportType = reportType;
            document.getElementById('reportModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('reportModal').style.display = 'none';
        }

        document.getElementById('time-period').addEventListener('change', function() {
            if (this.value === 'custom') {
                document.getElementById('custom-date-range').style.display = 'block';
            } else {
                document.getElementById('custom-date-range').style.display = 'none';
            }
        });

        function generateReport() {
            let period = document.getElementById('time-period').value;
            let url = '';
            
            // Check for custom date range
            if (period === 'custom') {
                let startDate = document.getElementById('start-date').value;
                let endDate = document.getElementById('end-date').value;
                // Pass the dates in the URL for custom report
                url = `./${selectedReportType}preview.html?period=custom&start=${startDate}&end=${endDate}`;
            } else {
                // For non-custom reports, just pass the period
                url = `./${selectedReportType}preview.html?period=${period}`;
            }

            // Redirect to the corresponding report form
            window.location.href = url;
        }
    </script>

    <script src="../../../Components/Accountant_Dashboard_Template/script.js"></script>
    <script src="./reports.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>



