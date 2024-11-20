<?php
session_start();
include '../../../database/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../Login/login-form.php"); 
    exit;
}

$salesRep_id = $_SESSION['user_id'];

$sql = "SELECT date, time, availability FROM availabletimes WHERE salesRep_id = ? ORDER BY date, time";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $salesRep_id);
$stmt->execute();
$result = $stmt->get_result();

$availableTimes = [];
while ($row = $result->fetch_assoc()) {
    $date = $row['date'];
    $time = $row['time'];
    $availability = $row['availability'];

    if (!isset($availableTimes[$date])) {
        $availableTimes[$date] = [];
    }
    $availableTimes[$date][] = ['time' => $time, 'availability' => $availability];
}

$stmt->close();
$conn->close();

$jsonData = json_encode($availableTimes);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Meeting</title>

    <link rel="stylesheet" href="../../../Components/SalesRep_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="../Sales/salesStyles.css" />
    <link rel="stylesheet" href="./styles.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <style>

    </style>
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
