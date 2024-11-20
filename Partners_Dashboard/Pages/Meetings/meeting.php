<?php
session_start() ;
include '../../../database/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$sql = "SELECT meeting.meeting_id, meeting.type, meeting.date, meeting.time, customer.firstName AS customer_name, customer.email AS email, meeting.status
        FROM meeting
        JOIN customer ON meeting.customer_id = customer.customer_id";
$result = $conn->query($sql);

$salesRep_id = $_SESSION['user_id'];

$sql_available_times = "SELECT * FROM availabletimes WHERE salesRep_id = ?"; 
$sql_booked_times = "SELECT date, time FROM meeting WHERE salesRep_id = ?";

if ($stmt = $conn->prepare($sql_available_times)) {
    $stmt->bind_param("i", $salesRep_id);
    $stmt->execute();
    $available_times_result = $stmt->get_result();
}

if ($stmt = $conn->prepare($sql_booked_times)) {
    $stmt->bind_param("i", $salesRep_id);
    $stmt->execute();
    $booked_times_result = $stmt->get_result();
}

$available_times = [];
$booked_times = [];

while ($row = $available_times_result->fetch_assoc()) {
    $available_times[] = $row;
}

while ($row = $booked_times_result->fetch_assoc()) {
    $booked_times[] = $row;
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Meeting</title>

    <link rel="stylesheet" href="../../../Components/SalesRep_Dashboard_Template/styles.css">
    <!-- <link
      rel="stylesheet"
      href="../../../Components/SalesRep_Dashboard_Template/styles.css"
    /> -->
    <link rel="stylesheet" href="../Sales/salesStyles.css" />
    <link rel="stylesheet" href="./styles.css">
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
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
        </div>

        <div class="available-times-container">
          <h3>Available Times</h3>
          <div class="time-slots">

          <?php
            foreach ($available_times as $available_time) {
                $time_slot = $available_time['time'];
                $is_booked = false;
                
                foreach ($booked_times as $booked_time) {
                    if ($booked_time['time'] === $time_slot && $booked_time['date'] === $available_time['date']) {
                        $is_booked = true;
                        break;
                    }
                }

                echo '<div class="time-slot" style="' . ($is_booked ? 'background-color: red;' : 'background-color: green;') . '">';
                echo '<p>' . $time_slot . '</p>';
                echo '<p>' . ($is_booked ? 'Booked' : 'Available') . '</p>';
                echo '</div>';
            }
            ?>
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
            <input type="time" id="date-filter" />

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
                
              </tr>
            </thead>
            <tbody>


              <?php
              if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
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
                        echo "</select>";
                        echo "</form>";
                        echo "</td>";
                        echo '</tr>';
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
    setTimeout(function() {
    const message = document.querySelector(".success-message");
    if (message) {
        message.style.display = "none";
    }
    }, 5000);
    </script>

   
    <script src="../../../Components/SalesRep_Dashboard_Template/script.js"></script>
    <script scr="./meeting.js"></script>
  </body>
</html>

<?php
  $conn->close();
?>
