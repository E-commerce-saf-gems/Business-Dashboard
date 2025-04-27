<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../database/db.php'; 

if (!isset($_SESSION['user_id'])) {
  header("Location: ./../Login/login-form.php"); 
  exit;
}

$salesRep_id = $_SESSION['user_id'];

$gemCounts = [];
$gemTypes = ['ruby', 'emerald', 'sapphire', 'amethyst', 'diamond'];

foreach ($gemTypes as $type) {
    $query = "SELECT COUNT(*) as count FROM inventory WHERE type = '$type'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $gemCounts[$type] = $row['count'];
}

$monthlySalesQuery = "
    SELECT DATE_FORMAT(date, '%Y-%m') AS month, SUM(total) AS total_sales 
    FROM sales 
    GROUP BY month 
    ORDER BY month DESC 
    LIMIT 6
";
$monthlySalesResult = $conn->query($monthlySalesQuery);

$months = [];
$totals = [];

while ($row = $monthlySalesResult->fetch_assoc()) {
    $months[] = $row['month'];
    $totals[] = $row['total_sales'];
}

$months = array_reverse($months);
$totals = array_reverse($totals);


$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./styles.css" />

    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <title>Sales Rep Dashboard</title>
  </head> 
  <body>
    <section id="sidebar">
      <a href="#" class="logo">
        <img src="../images/logo.png" width="90" height="90" alt="SAF GEMS" />
      </a>
      <ul class="side-menu">
        <li class="active">
          <a href="#">
            <i class="bx bxs-dashboard"></i>
            <span class="text">Dashboard</span>
          </a>
        </li>
        <li>
          <a href="../Sales_Rep_Dashboard/Pages/Sales/sales.php">
            <i class="bx bx-chart"></i>
            <span class="text">Sales</span>
          </a>
        </li>
        <li>
          <a href="../Sales_Rep_Dashboard/Pages/Bids/bidssummary.php">
            <i class="bx bx-dollar-circle"></i>
            <span class="text">Bids</span>
          </a>
        </li>
        
        <li>
          <a href="../Sales_Rep_Dashboard/Pages/Inventory/inventory.php">
            <i class="bx bxs-inbox"></i>
            <span class="text">Inventory</span>
          </a>
        </li>
        <li>
          <a href="../Sales_Rep_Dashboard/Pages/Orders/orders.php">
            <i class="bx bxs-inbox"></i>
            <span class="text">Orders</span>
          </a>
        </li>
        <li>
          <a href="../Sales_Rep_Dashboard/Pages/Customer/customers.php">
            <i class="bx bxs-user-detail"></i>
            <span class="text">Customers</span>
          </a>
        </li>
        <li>
          <a href="../Sales_Rep_Dashboard/Pages/Meetings/meeting.php">
            <i class="bx bx-calendar"></i>
            <span class="text">Meetings</span>
          </a>
        </li>
        <li>
          <a href="../Sales_Rep_Dashboard/Pages/requests/requests.php">
            <i class="bx bxs-dashboard"></i>
            <span class="text">Requests</span>
          </a>
        </li>
        <li>
          <a href="../Sales_Rep_Dashboard/Pages/Inquries/inquries.php">
            <i class="bx bxs-phone-call"></i>
            <span class="text">Inquries</span>
          </a>
        </li>
      </ul>
    </section>

    <section id="content">
      <nav>
        <i class="bx bx-menu"></i>
        
        <form action="#">
          <div class="form-input">
          </div>
        </form>
        
 <!-- profile Dropdown -->
 

            <div class="profile">
                <i class='bx bx-user' id="profile-icon"></i>
                <ul class="dropdown-menu">
                    <li><a href="./Pages/Profile/profile.php" class="dropdown-item">Profile</a></li>
                    <li><a href="../Login/logout.php" class="dropdown-item" id="logout">Logout</a></li>
                </ul>
            </div>

      </nav>
</section>
<section id="content">
      <main>
        <div class="head-title">
          <div class="left">
            <h1>Dashboard</h1>
            <ul class="breadcrumb">
              <li>
                <a href="#">Dashboard</a>
              </li>
              <li><i class="bx bx-chevron-right"></i></li>
              <li>
                <a class="active" href="#">Home</a>
              </li>
            </ul>
          </div>
          
        </div>
        <div class="dashboard-container">
          <div class="shortcuts">
            <h2>Shortcuts</h2>
            <ul class="shortcut-options">
              <li>
              <a href="Pages/Customers/addcustomer.php">
                <i class="bx bxs-user-plus"></i>
                <span class="text">
                  <h3>Add Customer</h3>
                </span>
              </li>
              <li>
              <a href="Pages/inventory/addinventory.html">
                <i class="bx bxs-diamond"></i>
                <span class="text">
                  <h3>Add Gem</h3>
                </span>
              </li>
              <li>
              <a href="Pages/Sales/addSales.html">
                <i class="bx bxs-dollar-circle"></i>
                <span class="text">
                  <h3>Add Sales</h3>
                </span>
              </li>
              <li>
              <a href="Pages/Meetings/addAvailableTime.html">
                <i class="bx bxs-time"></i>
                <span class="text">
                  <h3>Add Available Time Slots</h3>
                </span>
              </li>
            </ul>
</div>

                    <!-- pie chart for Gem Type Distribution-->
                    <div class="sales-summary">
              <h2>Gem Type <br>Distribution</h2>
              <canvas id="gemChart" ></canvas>
              <a href="./Pages/inventory/inventory.php" class="view-more">View More</a>
            </div>

        
          <!-- Bar chart for Sales Summary-->
          <div class="sales-summary">
            <h2>Monthly Sales Summary</h2>
            <canvas id="salesChart"></canvas>
            <!-- Canvas for Sales Summary Line Chart -->

            <!-- View More as an underlined text link -->
            <a href="./Pages/Sales/sales.php" class="view-more">View More</a>
          </div>
        </div>
      </main>
    </section>

    <script>
      // pie chart for inventory
      const gemData = <?php echo json_encode(array_values($gemCounts)); ?>;
      const gemLabels = <?php echo json_encode(array_keys($gemCounts)); ?>;
    
      const ctx = document.getElementById("gemChart").getContext("2d");
      new Chart(ctx, {
        type: "pie",
        data: {
          labels: gemLabels,
          datasets: [{
            label: "Gem Count",
            data: gemData,
            backgroundColor: [
              "#FF6384",
              "#36A2EB",
              "#FFCE56",
              "#A569BD",
              "#2ECC71"
            ],
            borderColor: "#fff",
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: "bottom"
            },
            title: {
              display: true,
            }
          }
        }
      });
    </script>


<!-- monthly sales for bar graph-->
<script>
  const salesMonths = <?php echo json_encode($months); ?>;
  const salesTotals = <?php echo json_encode($totals); ?>;

  const salesChartCtx = document.getElementById("salesChart").getContext("2d");
  new Chart(salesChartCtx, {
    type: "bar",
    data: {
      labels: salesMonths,
      datasets: [{
        label: "Monthly Sales (Rs.)",
        data: salesTotals,
        backgroundColor: "#4CAF50",
        borderRadius: 5,
        barThickness: 40
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: false
        },
        title: {
          display: true,
          text: "Monthly Sales Summary"
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: value => `Rs. ${value.toLocaleString()}`
          }
        }
      }
    }
  });
</script>


<!-- profile and notification on nav bar-->
<script>
            const profileIcon = document.getElementById("profile-icon");
            const profileMenu = document.querySelector(".profile");

            // Toggle dropdown visibility
            profileIcon.addEventListener("click", function (e) {
                e.stopPropagation(); // Prevent click from bubbling up
                profileMenu.classList.toggle("active");
            });

            // Close dropdown if clicking outside
            document.addEventListener("click", function (e) {
                if (!profileMenu.contains(e.target)) {
                    profileMenu.classList.remove("active");
                }
            });
            document.querySelector('.notification').addEventListener('click', function (e) {
                e.preventDefault(); // Prevent default link behavior
                const dropdown = document.querySelector('.notification-dropdown');
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            });

            // Close the dropdown if clicking outside
            document.addEventListener('click', function (e) {
                const notification = document.querySelector('.notification');
                const dropdown = document.querySelector('.notification-dropdown');
                if (!notification.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });

    </script>

<script src="./script.js"></script>
    <script src="../Partners_Dashboard/script.js"></script>
    <script src="../../../Components/SalesRep_Dashboard_Template/script.js"></script>
  </body>
</html>
