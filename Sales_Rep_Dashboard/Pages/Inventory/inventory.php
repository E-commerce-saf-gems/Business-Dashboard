<?php
include '../../../database/db.php';

// Fetch data from the 'request' and 'customer' tables using a JOIN
$sql = "SELECT inventory.date,inventory.size,inventory.shape,inventory.colour,inventory.type,
        inventory.origin,inventory.amount,buyer.name,inventory.visibility,
        FROM inventory,buyer
        JOIN inventory ON inventory.stone_id = buyer.buyer_id";
$result = $conn->query($sql);
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
          <a href="../../Pages/Inventory/addinventory.html" class="btn-add"
            ><i class="bx bx-plus"></i>Add New</a
          >
        </div>
        <div class="sales-summary-box">
          <div class="sales-summary-title">
            <h2>Monthly Inventory Summary</h2>
          </div>
          <div class="sales-item">
            <h3>Ruby</h3>
            <p>8</p>
          </div>
          <div class="sales-item">
            <h3>Emerald</h3>
            <p>4</p>
          </div>
          <div class="sales-item">
            <h3>Sapphire</h3>
            <p>5</p>
          </div>
          <div class="sales-item">
            <h3>Amethyst</h3>
            <p>2</p>
          </div>
          <div class="sales-item">
            <h3>Diamond</h3>
            <p>15</p>
          </div>
        </div>

        <div class="sales-table-container">
          <div class="table-filters">
            <label for="date-filter">Date:</label>
            <input type="date" id="date-filter" />

            <label for="type-filter">Type:</label>
            <select id="type-filter">
              <option value="">All</option>
              <option value="paid">Ruby</option>
              <option value="pending">Emerald</option>
              <option value="pending">Sapphire</option>
              <option value="pending">Amethyst</option>
              <option value="pending">Diamond</option>
            </select>

            <label for="shape-filter">shape:</label>
            <select id="shape-filter">
              <option value="">All</option>
              <option value="paid">Round</option>
              <option value="pending">Oval</option>
              <option value="pending">Princess</option>
              <option value="pending">Cushion</option>
              <option value="pending">Emerald</option>
              <option value="pending">Marquise</option>
              <option value="pending">Pear</option>
              <option value="pending">Heart</option>
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
               <!-- <th><input type="checkbox" class="select-all" /></th>  -->
                <th>Date</th>
                <!-- <th>Stone ID</th> -->
                <th>Size</th>
                <th>Shape</th>
                <th>Color</th>
                <th>Type</th>
                <th>Origin</th>
                <th>Amount</th>
                <!-- <th>Image</th>
                <th>Certificate</th> -->
                <th>Buyer ID</th>
                <th>Visibility</th>
                <th>Action</th>
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

                                switch ($row['visibility']) {
                                  case 'P':
                                      $statusLabel = 'hide';
                                      $statusColor = 'color: red;';
                                      break;
                                  case 'A':
                                      $statusLabel = 'show';
                                      $statusColor = 'color: green;';
                                      break;

                                }
                                echo "<tr>";
                                echo "<td>" . $row['date'] . "</td>";
                                echo "<td>" . $row['size'] . "</td>";
                                echo "<td>" . $row['shape'] . "</td>";
                                echo "<td>" . $row['colour'] . "</td>";
                                echo "<td>" . $row['type'] . "</td>";
                                echo "<td>" . $row['origin'] . "</td>";
                                echo "<td>" . $row['amount'] . "</td>";
                                echo "<td>" . $row['name'] . "</td>";
                                echo "<td style='$statusColor'>$statusLabel</td>";
                                echo "<td class='actions'>
                                <a href='../../Pages/Inventory/editinventory.html' class='btn'>
                                <i class='bx bx-pencil'></i></a>
                                <a class='btn'><i class='bx bx-trash'></i></a>
                                </td>";
                                echo '</tr>';
                            }
                        } else {
                            echo "<tr><td colspan='9'>No Gems in the inventory.</td></tr>";
                        }
                        ?>
              <!-- <tr> -->
               <!-- <td><input type="checkbox" /></td>  -->
                <!-- <td>2024-10-20</td> -->
                <!-- <td>004</td> -->
                <!-- <td>10 Oct</td>
                <td>Round</td>
                <td>Red</td>
                <td>Ruby</td>
                <td>Sri Lanka</td>
                <td>$350</td> -->
                <!-- <td>
                  <img
                    src="./path/to/gem-image.jpg"
                    alt="Gem Image"
                    width="50"
                    height="50"
                  />
                </td> -->
                <!-- Image of the certificate -->
                <!-- <td>
                  <img
                    src="./path/to/certificate-image.jpg"
                    alt="Certificate Image"
                    width="50"
                    height="50"
                  />
                </td> -->
                <!-- <td>b006</td>
                <td>Hide</td>
                <td class="actions">
                  <a href="../../Pages/Inventory/editinventory.html" class="btn">
                    <i class="bx bx-pencil"></i>
                  </a>
                  <a class="btn"><i class="bx bx-eye"></i></a>
                  <a class="btn"><i class="bx bx-trash"></i></a>
                </td>
              </tr> -->
            </tbody>

            
          </table>
        </div>
      </main>
    </section>

    <script src="../../Pages/Inventory/script.js"></script>
    <script scr="./inventory.js"></script>
  </body>
</html>

<?php
// Close the database connection
$conn->close();
?>