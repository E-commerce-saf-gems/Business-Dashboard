<?php
include '../../../database/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch data from the 'request' and 'customer' tables using a JOIN
$sql = "SELECT meeting.meeting_id, meeting.type, meeting.date, meeting.time, customer.firstName AS customer_name, meeting.email, meeting.status
        FROM meeting
        JOIN customer ON meeting.customer_id = customer.customer_id";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Meeting</title>
    <link
      rel="stylesheet"
      href="../../../Components/SalesRep_Dashboard_Template/styles.css"
    />
    <link rel="stylesheet" href="../Sales/salesStyles.css" />
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

          <!-- Table -->
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
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>


              <?php
              // Check if there are results and display each row in the table
              if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                      // Determine the status label and color
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

                        echo "<form method='POST' action='./deleteMeeting.php'>";
                        echo "<input type='hidden' name='meeting_id' value='" . $row['meeting_id'] . "'>";
                        echo "<select name='status' onchange='this.form.submit()'>";
                        echo "<option value='P'" . ($row['status'] === 'P' ? " selected" : "") . ">Pending</option>";
                        echo "<option value='A'" . ($row['status'] === 'A' ? " selected" : "") . ">Approved</option>";
                        echo "<option value='C'" . ($row['status'] === 'C' ? " selected" : "") . ">Complete</option>";
                        echo "</select>";
                        echo "</form>";
                        echo "</td>";

                        echo "<td class='actions'>";
                        echo "<a href='./viewMeeting.php?id=" . htmlspecialchars($row['meeting_id']) . "' class='btn' title='View'><i class='bx bx-show'></i></a>";
                        echo "<a href='./editMeeting.php?id=" . htmlspecialchars($row['meeting_id']) . "' class='btn' title='Edit'><i class='bx bx-pencil'></i></a>";
                        echo "<a href='./deleteMeeting.php?id=" . htmlspecialchars($row['meeting_id']) . "' class='btn' title='Delete' onclick='return confirm(\"Are you sure you want to delete this meeting?\");'><i class='bx bx-trash'></i></a>";
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




             <!-- <tr>
                <td><input type="checkbox" /></td>
                <td>Online</td>
                <td>2024-11-04</td>
                <td>16:00</td>
                <td>Ashcharya</td>
                <td>Ash@gmail.com</td>
                <td>Request Delete</td>
                <td class="actions">
                  <a href="./editmeeting.html" class="btn"
                    ><i class="bx bx-pencil"></i
                  ></a>
                  <a class="btn"><i class="bx bx-eye"></i></a>
                  <a class="btn"><i class="bx bx-trash"></i></a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </main>
    </section> -->

   
    <script src="/Components/SalesRep_Dashboard_Template/script.js"></script>
    <script scr="./meeting.js"></script>
  </body>
</html>

<?php
// Close the database connection
$conn->close();
?>
