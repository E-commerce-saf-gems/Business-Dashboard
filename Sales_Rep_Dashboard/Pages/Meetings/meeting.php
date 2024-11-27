<?php
session_start();
include '../../../database/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../Login/login-form.php"); 
    exit;
}

$salesRep_id = $_SESSION['user_id'];

// Fetch available times for the logged-in sales representative
$sql_available_times = "SELECT date, time, availability 
                        FROM availabletimes 
                        WHERE salesRep_id = ? 
                        ORDER BY date, time";
$stmt = $conn->prepare($sql_available_times);
$stmt->bind_param("i", $salesRep_id);
$stmt->execute();
$availableTimesResult = $stmt->get_result();

$availableTimes = [];
while ($row = $availableTimesResult->fetch_assoc()) {
    $date = $row['date'];
    $time = $row['time'];
    $availability = $row['availability'];

    if (!isset($availableTimes[$date])) {
        $availableTimes[$date] = [];
    }
    $availableTimes[$date][] = ['time' => $time, 'availability' => $availability];
}

$stmt->close();

// Fetch meetings for the logged-in sales representative
$sql_meetings = "SELECT m.meeting_id, m.type, a.date, a.time, 
                        c.firstName AS customer_name, c.email AS email, 
                        m.status 
                 FROM meeting AS m
                 JOIN customer AS c ON m.customer_id = c.customer_id
                 JOIN availabletimes AS a ON m.availableTimes_id = a.availableTimes_id
                 WHERE a.salesRep_id = ?
                 ORDER BY a.date, a.time";

$stmt = $conn->prepare($sql_meetings);
$stmt->bind_param("i", $salesRep_id);
$stmt->execute();
$meetingsResult = $stmt->get_result();

$stmt->close();
$conn->close();

$jsonData = json_encode($availableTimes);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Meetings</title>
    <link rel="stylesheet" href="../../../Components/SalesRep_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="../Sales/salesStyles.css" />
    <link rel="stylesheet" href="./styles.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>
<body>
<dashboard-component></dashboard-component>

<section id="content">
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Meetings</h1>
                <ul class="breadcrumb">
                    <li>
                        <a class="active" href="#">Meetings Summary</a>
                    </li>
                </ul>
            </div>
            <a href="./addAvailableTime.html" class="btn-add">
                <i class="bx bx-plus"></i>Add New
            </a>
        </div>

        <div class="edit-availability-container">
            <h3>Available Times</h3>
            <div class="navigation-buttons">
                <button id="prevBtn" class="btn-nav" disabled>&laquo; Previous</button>
                <button id="nextBtn" class="btn-nav">Next &raquo;</button>
            </div>
            <div class="day-grid" id="dayGrid">
            </div>
        </div>
        
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="success-message">
            Request status updated successfully!
        </div>
        <?php endif; ?>

        <div class="sales-table-container">
            <div class="table-filters">
                <label for="date-filter">Date:</label>
                <input type="date" id="date-filter" />

                <label for="status-filter">Time:</label>
                <input type="time" id="time-filter" />

                <label for="customer-filter">Name:</label>
                <input type="text" id="customer-filter" placeholder="Search name" />

                <button class="btn-filter">Filter</button>
            </div>

            <table class="sales-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" class="select-all" /></th>
                        <th>Appointment Type</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Name</th>
                        <th>Email Address</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($meetingsResult->num_rows > 0) {
                        while ($row = $meetingsResult->fetch_assoc()) {
                            $statusLabel = '';
                            $statusColor = '';

                            switch ($row['status']) {
                                case 'P':
                                    $statusLabel = 'Pending';
                                    $statusColor = 'color: red;';
                                    break;
                                case 'A':
                                    $statusLabel = 'Approved';
                                    $statusColor = 'color: blue;';
                                    break;
                                case 'C':
                                    $statusLabel = 'Complete';
                                    $statusColor = 'color: green;';
                                    break;
                                default:
                                    $statusLabel = 'Unknown';
                                    $statusColor = 'color: black;';
                            }

                            echo "<tr>";
                            echo "<td><input type='checkbox'></td>";
                            echo "<td>" . $row['type'] . "</td>";
                            echo "<td>" . $row['date'] . "</td>";
                            echo "<td>" . $row['time'] . "</td>";
                            echo "<td>" . $row['customer_name'] . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "<td>";
                            echo "<form method='POST' action='./updateMeeting.php'>";
                            echo "<input type='hidden' name='meeting_id' value='" . $row['meeting_id'] . "'>";
                            echo "<select name='status' onchange='this.form.submit()'>";
                            echo "<option value='P'" . ($row['status'] === 'P' ? " selected" : "") . ">Pending</option>";
                            echo "<option value='A'" . ($row['status'] === 'A' ? " selected" : "") . ">Approved</option>";
                            echo "<option value='C'" . ($row['status'] === 'C' ? " selected" : "") . ">Complete</option>";
                            echo "<option value='R'" . ($row['status'] === 'R' ? " selected" : "") . ">Request To Delete</option>";
                            echo "</select>";
                            echo "</form>";
                            echo "</td>";


                            // Add a Delete button only if the status is "Request To Delete"
echo "<td>";
if ($row['status'] === 'R') { // 'R' is the code for "Request To Delete"
    echo "<form method='POST' action='./deleteMeeting.php'>";
    echo "<input type='hidden' name='meeting_id' value='" . $row['meeting_id'] . "'>";
    echo "<button type='submit' class='btn-delete' onclick='return confirm(\"Are you sure you want to delete this meeting?\")'>Delete</button>";
    echo "</form>";
} else {
    echo "-"; // Placeholder or leave blank if no action needed
}
echo "</td>";


            echo "</tr>";
                            
                        }
                    } else {
                        echo "<tr><td colspan='9'>No requests found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</section>

<script>
    const availableTimes = <?php echo $jsonData; ?>;
    const dayGrid = document.getElementById('dayGrid');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    let currentStartIndex = 0;
    const daysPerPage = 5;

    function renderDays() {
        dayGrid.innerHTML = '';

        const dates = Object.keys(availableTimes).sort(); 
        const visibleDates = dates.slice(currentStartIndex, currentStartIndex + daysPerPage);

        visibleDates.forEach(date => {
            const timeSlots = availableTimes[date];
            const dayBox = document.createElement('div');
            dayBox.classList.add('day-box');

            const dayTitle = document.createElement('h4');
            dayTitle.textContent = new Date(date).toLocaleDateString('en-US', {
                weekday: 'long',
                month: 'short',
                day: 'numeric'
            });
            dayBox.appendChild(dayTitle);

            const slotsContainer = document.createElement('div');
            slotsContainer.classList.add('time-slots');
            timeSlots.forEach(slot => {
                const timeSlot = document.createElement('div');
                timeSlot.classList.add('time-slot', slot.availability);

                const timeText = document.createElement('span');
                timeText.textContent = new Date(`1970-01-01T${slot.time}`).toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                timeSlot.appendChild(timeText);

                const actions = document.createElement('div');
                actions.classList.add('time-slot-actions');

                const editIcon = document.createElement('i');
                editIcon.classList.add('bx', 'bx-edit');
                editIcon.addEventListener('click', () => {
                    window.location.href = `./editAvailableTime.php?date=${date}&time=${slot.time}`;
                });

                const deleteIcon = document.createElement('i');
                deleteIcon.classList.add('bx', 'bx-trash');
                deleteIcon.addEventListener('click', () => {
                    window.location.href = `./deleteAvailableTime.php?date=${date}&time=${slot.time}`;
                });

                actions.appendChild(editIcon);
                actions.appendChild(deleteIcon);

                timeSlot.appendChild(actions);
                slotsContainer.appendChild(timeSlot);
            });

            dayBox.appendChild(slotsContainer);
            dayGrid.appendChild(dayBox);
        });

        prevBtn.disabled = currentStartIndex === 0;
        nextBtn.disabled = currentStartIndex + daysPerPage >= dates.length;
    }

    prevBtn.addEventListener('click', () => {
        currentStartIndex = Math.max(0, currentStartIndex - daysPerPage);
        renderDays();
    });

    nextBtn.addEventListener('click', () => {
        currentStartIndex = Math.min(Object.keys(availableTimes).length - daysPerPage, currentStartIndex + daysPerPage);
        renderDays();
    });

    renderDays();
</script>
<script src="../../../Components/SalesRep_Dashboard_Template/script.js"></script>
<script src="./meeting.js"></script>
</body>
</html>
