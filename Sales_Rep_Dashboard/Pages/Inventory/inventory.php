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
        ORDER BY inventory.date DESC";


$result = $conn->query($ssql);

// Check if query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}

$inventoryCounts = [];

// for Monthly Inventory Summary
$types = ['Ruby', 'Emerald', 'Sapphire', 'Amethyst', 'Diamond'];
foreach ($types as $type) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM inventory WHERE LOWER(type) = LOWER(?) AND availability = 'available'");
    $stmt->bind_param("s", $type);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $inventoryCounts[$type] = $count;
    $stmt->close();
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

    <?php foreach ($inventoryCounts as $type => $count): ?>
        <div class="sales-item">
            <h3><?= htmlspecialchars($type) ?></h3>
            <p><?= $count ?></p>
        </div>
    <?php endforeach; ?>
</div>


        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="success-message">
                    Gem availability updated successfully!
                </div>
        <?php endif; ?>

        <div class="sales-table-container">
          <div class="table-filters">
            <label for="date-filter">Date:</label>
            <input type="date" id="date-filter" />

            <label for="type-filter">Type:</label>
            <select id="type-filter">
  <option value="">All</option>
  <option value="ruby">Ruby</option>
  <option value="emerald">Emerald</option>
  <option value="sapphire">Sapphire</option>
  <option value="amethyst">Amethyst</option>
  <option value="diamond">Diamond</option>
</select>


            <label for="shape-filter">shape:</label>
            <select id="shape-filter">
  <option value="">All</option>
  <option value="round">Round</option>
  <option value="oval">Oval</option>
  <option value="princess">Princess</option>
  <option value="cushion">Cushion</option>
  <option value="emerald">Emerald</option>
  <option value="marquise">Marquise</option>
  <option value="pear">Pear</option>
  <option value="heart">Heart</option>
</select>


            <label for="customer-filter">color:</label>
            <input
              type="text"
              id="customer-filter"
              placeholder="Search Color"
            />

            <button class="btn-filter">Filter</button>
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
                <th>Visibility</th>
                <th>Availability</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
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
                      // echo "<td>" . $row['visibility'] . "</td>";
                      //form for visibility check
                      echo "<td>";
                      echo "<form method='POST' action='./updatevisibility.php'>";
                      echo "<input type='hidden' name='stone_id' value='" . $row['stone_id'] . "'>";
                      echo "<select name='visibility' onchange='this.form.submit()'>";
                      echo "<option value='show'" . ($row['visibility'] === 'show' ? " selected" : "") . ">show</option>";
                      echo "<option value='hide'" . ($row['visibility'] === 'hide' ? " selected" : "") . ">hide</option>";
                      echo "</select>";
                      echo "</form>";
                      echo "</td>";

                      // Form for updating availability
                      echo "<td>";
                      echo "<form method='POST' action='./updateavailable.php'>";
                      echo "<input type='hidden' name='stone_id' value='" . $row['stone_id'] . "'>";
                      echo "<select name='availability' onchange='this.form.submit()'>";
                      echo "<option value='available'" . ($row['availability'] === 'available' ? " selected" : "") . ">available</option>";
                      echo "<option value='not available'" . ($row['availability'] === 'not available' ? " selected" : "") . ">not available</option>";
                      echo "<option value='Bids'" . ($row['availability'] === 'Bids' ? " selected" : "") . ">Bids</option>";
                      echo "</select>";
                      echo "</form>";
                      echo "</td>";

                      // Action buttons
                      echo "<td class='actions'>";

                      
                      if ($row['availability'] == 'available' || $row['availability'] == 'Available') {
                          echo "<a href='./editInventory.php?id=" . $row['stone_id'] . "' class='btn'><i class='bx bx-pencil'></i></a>";
                          echo "<a href='#' onclick='confirmDelete(" . $row['stone_id'] . ")' class='btn'><i class='bx bx-trash'></i></a>";
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

<script>
document.querySelector(".btn-filter").addEventListener("click", () => {
    const dateFilter = document.getElementById("date-filter").value;
    const typeFilter = document.getElementById("type-filter").value.toLowerCase();
    const shapeFilter = document.getElementById("shape-filter").value.toLowerCase();
    const colorFilter = document.getElementById("customer-filter").value.toLowerCase();

    const rows = document.querySelectorAll(".sales-table tbody tr");

    rows.forEach(row => {
        const date = row.children[0].textContent.trim() .substring(0, 10);// Extract just the date part
        const type = row.children[5].textContent.toLowerCase().trim();
        const shape = row.children[3].textContent.toLowerCase().trim();
        const color = row.children[4].textContent.toLowerCase().trim();

        let isVisible = true;

        if (dateFilter && date !== dateFilter) {
            isVisible = false;
        }

        if (typeFilter && !type.includes(typeFilter)) {
            isVisible = false;
        }

        if (shapeFilter && !shape.includes(shapeFilter)) {
            isVisible = false;
        }

        if (colorFilter && !color.includes(colorFilter)) {
            isVisible = false;
        }

        row.style.display = isVisible ? "" : "none";
    });
});
</script>
    
    <script src="../../../Components/SalesRep_Dashboard_Template/script.js"></script>
    <script src="../../Pages/Inventory/script.js"></script>
    <script scr="../../../Sales_Rep_Dashboard/Pages/Inventory/inventory.js"></script>

  </body>
</html>

<?php
// Close the database connection
$conn->close();
?>