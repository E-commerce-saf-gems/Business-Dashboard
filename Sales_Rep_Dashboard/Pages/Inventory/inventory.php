<?php
include '../../../database/db.php';

// Corrected SQL query syntax
$ssql = "SELECT 
            inventory.stone_id, 
            inventory.date, 
            inventory.size, 
            inventory.shape, 
            inventory.colour, 
            inventory.type, 
            inventory.amount, 
            inventory.certificate, 
            buyer.name,
            inventory.visibility,
            inventory.availability
        FROM inventory
        JOIN buyer ON inventory.buyer_id = buyer.buyer_id 
        WHERE 1=1"; // Ensure WHERE clause starts correctly

// Apply filters
if (isset($_GET['date']) && !empty($_GET['date'])) {
    $date = $conn->real_escape_string($_GET['date']);
    $ssql .= " AND DATE(inventory.date) = '$date'"; // Use DATE() to extract the date part from the timestamp
}

if (isset($_GET['type']) && !empty($_GET['type'])) {
    $type = $conn->real_escape_string($_GET['type']);
    $ssql .= " AND inventory.type = '$type'";
}

if (isset($_GET['shape']) && !empty($_GET['shape'])) {
    $shape = $conn->real_escape_string($_GET['shape']);
    $ssql .= " AND inventory.shape = '$shape'";
}

if (isset($_GET['colour']) && !empty($_GET['colour'])) {
    $colour = $conn->real_escape_string($_GET['colour']);
    $ssql .= " AND inventory.colour = '$colour'";
}

$ssql .= " ORDER BY inventory.date DESC"; // Ensure ORDER BY is added at the end

$result = $conn->query($ssql);

// Check if query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inventory</title>
    <link
      rel="stylesheet"
      href="../../Pages/Inventory/styles.css"
    />
    <link rel="stylesheet" href="../../Pages/userStyles.css">   
    <link rel="stylesheet" href="../../Pages/Inventory/salesStyles.css" />
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
            <h1>Inventory</h1>
            <ul class="breadcrumb">
              <li>
                <a class="active" href="#">Inventory Summary</a>
              </li>
            </ul>
          </div>
          <a href="./addinventory.html" class="btn-add"
            ><i class="bx bx-plus"></i>Add New</a>
        </div>
        <div class="sales-summary-box">
          <div class="sales-summary-title">
            <h2>Monthly Inventory Summary</h2>
          </div>
          <?php
        // Query to get the count of each type of gem
        $typeQuery = "SELECT type, COUNT(*) AS count FROM inventory GROUP BY type";
        $typeResult = $conn->query($typeQuery);

        if ($typeResult->num_rows > 0) {
            while ($typeRow = $typeResult->fetch_assoc()) {
                echo "<div class='sales-item'>";
                echo "<h3>" . htmlspecialchars($typeRow['type']) . "</h3>";
                echo "<p>" . htmlspecialchars($typeRow['count']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No inventory data available.</p>";
        }
        ?>
        </div>

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="success-message">
                    Gem details updated successfully!
                </div>
        <?php endif; ?>

        <div class="sales-table-container">
        <div class="table-filters">
        <form method="GET" id="filter-form">
          <label for="date-filter">Date:</label>
          <input type="date" id="date-filter" name="date" value="<?= isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>" onchange="document.getElementById('filter-form').submit();">
          <label for="type-filter">Type:</label>
          <select id="type-filter" name="type" onchange="document.getElementById('filter-form').submit();">
              <option value="">All</option>
              <option value="Ruby" <?= (isset($_GET['type']) && $_GET['type'] == 'Ruby') ? 'selected' : ''; ?>>Ruby</option>
              <option value="Emerald" <?= (isset($_GET['type']) && $_GET['type'] == 'Emerald') ? 'selected' : ''; ?>>Emerald</option>
              <option value="Sapphire" <?= (isset($_GET['type']) && $_GET['type'] == 'Sapphire') ? 'selected' : ''; ?>>Sapphire</option>
              <option value="Amethyst" <?= (isset($_GET['type']) && $_GET['type'] == 'Amethyst') ? 'selected' : ''; ?>>Amethyst</option>
              <option value="Diamond" <?= (isset($_GET['type']) && $_GET['type'] == 'Diamond') ? 'selected' : ''; ?>>Diamond</option>
          </select>
          

        <label for="shape-filter">Shape:</label>
        <select id="shape-filter" name="shape" onchange="document.getElementById('filter-form').submit();">
            <option value="">All</option>
            <option value="Round" <?= (isset($_GET['shape']) && $_GET['shape'] == 'Round') ? 'selected' : ''; ?>>Round</option>
            <option value="Oval"  <?= (isset($_GET['shape']) && $_GET['shape'] == 'Oval') ? 'selected' : ''; ?>>Oval</option>
            <option value="Square"  <?= (isset($_GET['shape']) && $_GET['shape'] == 'Square') ? 'selected' : ''; ?>>Square</option>
            <option value="Rectangle"  <?= (isset($_GET['shape']) && $_GET['shape'] == 'Rectangle') ? 'selected' : ''; ?>>Rectangle</option>
        </select>

        <!-- <label for="customer-filter">Color:</label>
        <input type="text" id="customer-filter" name="colour" placeholder="Search Color" value="<?= isset($_GET['colour']) ? htmlspecialchars($_GET['colour']) : ''; ?>" onchange="document.getElementById('filter-form').submit();"> -->

        <button type="button" onclick="window.location.href='<?= strtok($_SERVER['REQUEST_URI'], '?'); ?>'">Reset Filters</button>
        </form>
</div>

          <!-- Table -->
          <table class="sales-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>ID</th>
                <th>Size</th>
                <th>Shape</th>
                <th>Color</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Buyer Name</th>
                <th>Availability</th>
                <th>Visibility</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>

            <?php
              if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                      echo "<tr>";
                      echo "<td>" . $row['date'] . "</td>";
                      echo "<td>" . $row['stone_id'] . "</td>";
                      echo "<td>" . $row['size'] . "</td>";
                      echo "<td>" . $row['shape'] . "</td>";
                      echo "<td>" . $row['colour'] . "</td>";
                      echo "<td>" . $row['type'] . "</td>";
                      echo "<td>" . $row['amount'] . "</td>";
                      echo "<td>" . $row['name'] . "</td>";
                      echo "<td>" . $row['availability'] . "</td>";

                      // form for visibility
                      echo "<td>";
                      echo "<form method='POST' action='./updatevisibility.php'>";
                      echo "<input type='hidden' name='stone_id' value='" . htmlspecialchars($row['stone_id']) . "'>";
                      echo "<select name='visibility' onchange='this.form.submit()'>";
                      echo "<option value='show'" . ($row['visibility'] === 'show' ? " selected" : "") . ">show</option>";
                      echo "<option value='hide'" . ($row['visibility'] === 'hide' ? " selected" : "") . ">hide</option>";
                      echo "</select>";
                      echo "</form>";
                      echo "</td>";

                    

                      // Action buttons
                      echo "<td class='actions'>";

                      
                      if ($row['availability'] == 'available' && $row['visibility'] == 'show') {
                          echo "<a href='./editInventory.php?id=" . $row['stone_id'] . "' class='btn'><i class='bx bx-pencil'></i></a>";
                          echo "<a href='./deleteGem.php' onclick='confirmDelete(" . $row['stone_id'] . ")' class='btn'><i class='bx bx-trash'></i></a>";
                      }

                      echo "<a href='./viewInventory.php?id=" . $row['stone_id'] . "' class='btn'><i class='bx bx-detail'></i></a>";

                      
                      echo "</td>";
                      
                    echo "</tr>";
                  }
              } else {
                  echo "<tr><td colspan='9'>No Gems in the inventory.</td></tr>";
              }
            ?>

            </tbody>

          </table>
        </div>
      </main>
    </section>

    <script>
    function confirmDelete(stoneId) {
        const userConfirmed = confirm("Are you sure you want to delete this Gem?");
        if (userConfirmed) {
            window.location.href = `./deleteGem.php?id=${stoneId}`;
        }
    }
    </script>
    
    <script src="../../../Components/SalesRep_Dashboard_Template/script.js"></script>
    <script src="../../Pages/Inventory/script.js"></script>
    <script src="../../../Sales_Rep_Dashboard/Pages/Inventory/inventory.js"></script>

  </body>
</html>

<?php
// Close the database connection
$conn->close();
?>