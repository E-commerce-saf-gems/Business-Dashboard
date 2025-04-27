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

//Get gem count 
$gemCounts = [];
$gemTypes = ['ruby', 'emerald', 'sapphire', 'amethyst', 'diamond'];

foreach ($gemTypes as $type) {
    $query = "SELECT COUNT(*) as count FROM inventory WHERE type = '$type'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $gemCounts[$type] = $row['count'];
}

// Get monthly sales data (last 6 months)
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

// Reverse arrays to show oldest to newest
$months = array_reverse($months);
$totals = array_reverse($totals);



// Get today's date
$today = date("Y-m-d");

// Query to fetch today's approved meetings
$sql = "
    SELECT a.time, c.firstName AS customer_name, c.email 
    FROM meeting AS m
    JOIN availabletimes AS a ON m.availableTimes_id = a.availableTimes_id
    JOIN customer AS c ON m.customer_id = c.customer_id
    WHERE a.salesRep_id = ? 
    AND DATE(a.date) = ? 
    AND m.status = 'A' 
    ORDER BY a.time
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $salesRep_id, $today);
$stmt->execute();
$result = $stmt->get_result();

// Get today's approved meetings - corrected version
$today_meetings = [];
if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $today_meetings[] = [
                'time' => $row['time'],
                'customer_name' => $row['customer_name'],
                'email' => $row['email']
            ];
        }
    }
}








// Fetch today's orders
$today_orders_sql = "SELECT order_id,shipping_method,order_status FROM orders WHERE DATE(order_date) = ? && (order_status='confirmed' || order_status='ready for collection') ";
$today_orders_stmt = $conn->prepare($today_orders_sql);
$today_orders_stmt->bind_param("s", $today);
$today_orders_stmt->execute();
$today_orders_result = $today_orders_stmt->get_result();




$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./styles.css" />
    <link rel="stylesheet" href="./Sales/editSalesStyles.css" />  
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
              <a href="Pages/Customer/customers.php">
                <i class="bx bxs-user-plus"></i>
                <span class="text">
                  <h3>view Customer</h3>
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

<!-- Today's Approved Meetings -->
<div class="sales-summary">
    <h2><i class='bx bx-calendar-check dashboard-icon'></i> Today's Approved Meetings</h2>
    <div class="approved-meetings-list">
        <?php if (!empty($today_meetings)): ?>
            <ul>
                <?php foreach ($today_meetings as $meeting): ?>
                    <li>
                        <strong><?php echo date('h:i A', strtotime($meeting['time'])); ?></strong>
                        <span><?php echo htmlspecialchars($meeting['customer_name']); ?></span>
                        <a href="mailto:<?php echo htmlspecialchars($meeting['email']); ?>">
                            <i class='bx bx-envelope'></i>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No approved meetings scheduled for today.</p>
        <?php endif; ?>
    </div>
    <a href="./Pages/Meetings/meeting.php" class="view-more">View All Meetings</a>
</div>




           <!-- Today's Orders -->
<div class="sales-summary">
    <h2><i class='bx bx-calendar-check dashboard-icon'></i> Today's Collections / Deliveries</h2>
    <div class="scrollable-list">
        <ul>
            <?php 
            $today_orders_result->data_seek(0); // Reset pointer if needed
            while ($row = $today_orders_result->fetch_assoc()): 
                $order_id = htmlspecialchars($row['order_id']);
                $shipping_method = htmlspecialchars($row['shipping_method']);
                $order_status = htmlspecialchars($row['order_status']);
            ?>
                <li class="order-item">
                    <a href="./viewOrder.php?id=<?= $order_id ?>" class="order-link">
                        Order #<?= $order_id ?>
                    </a>  
                    <span class="shipping-method">
                        <?= ucfirst(str_replace('-', ' ', $shipping_method)) ?>
                    </span>
                    
                    <?php if ($shipping_method === 'store-pickup'): ?>
                        <button class="status-btn pickup-btn" 
                            data-order-id="<?= $order_id ?>" 
                            data-current-status="<?= $order_status ?>"
                            data-shipping-method="<?= $shipping_method ?>">
                            <?= ($order_status == 'ready for collection') ? 'Mark as Collected' : 'Prepare for Collection' ?>
                        </button>
                    <?php elseif ($shipping_method === 'home-delivery'): ?>
                        <button class="status-btn delivery-btn" 
                            data-order-id="<?= $order_id ?>" 
                            data-current-status="<?= $order_status ?>"
                            data-shipping-method="<?= $shipping_method ?>">
                            <?= ($order_status == 'ready for delivery') ? 'Mark as Delivered' : 'Prepare for Delivery' ?>
                        </button>
                    <?php endif; ?>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</div>
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