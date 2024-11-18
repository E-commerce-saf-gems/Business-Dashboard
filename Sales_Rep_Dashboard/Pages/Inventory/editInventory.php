<?php
include('../../../database/db.php'); // Include your database connection here

// Check if request_id is provided in the URL
if (isset($_GET['id'])) {
  $stone_id = $_GET['id'];

  // Fetch the record from the database
  $sql = "SELECT * FROM inventory WHERE stone_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $stone_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
  } else {
      echo "No record found";
      exit;
  }
} else {
  echo "No ID specified";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Inventory Details</title>
    <link
      rel="stylesheet"
      href="../../Pages/Inventory/styles.css"
    />
    <link rel="stylesheet" href="../../Pages/Inventory/editSalesStyles.css" />
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
                <a href="../../../Sales_Rep_Dashboard/Pages/Inventory/inventory.php">Inventory Summary</a>
              </li>
              <li><i class="bx bx-chevron-right"></i></li>
              <li>
                <a class="active" href="#">Add Inventory</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="edit-sales-container">
        <form class="edit-sales-form" id="editgemForm" action="./updategem.php" method="POST" enctype="multipart/form-data">
        <h2>New Inventory Details</h2>

           


            <!--size Field-->
            <div class="form-group">
              <label for="size">Size</label>
              <input
                type="text"
                id="size"
                name="size"
                placeholder="size"
                value="<?php echo $row['size'];?>"
                required
              />
            </div>

            <!--shape Feild-->
            <div class="form-group">
              <label for="shape">Shape</label>
              <input
                type="text"
                id="shape"
                name="shape"
                placeholder="shape"
                value="<?php echo $row['shape'];?>"
                required
              />
            </div>

            <!-- Color Field -->
            <div class="form-group">
              <label for="colour">Color</label>
              <input
                type="text"
                id="colour"
                name="colour"
                placeholder="Colour"
                value="<?php echo $row['colour'];?>"
                required
              />
            </div>

            <div class="form-group">
              <label for="type">Type</label>
              <select id="type" name="type">
                <option value="Ruby"  <?php if ($row['type'] === 'Ruby') echo 'selected'; ?>>Ruby</option>
                <option value="Emerald" <?php if ($row['type'] === 'Emerald') echo 'selected'; ?>>Emerald</option>
                <option value="Sapphire" <?php if ($row['type'] === 'Sapphire') echo 'selected'; ?>>Sapphire</option>
                <option value="Amethyst"  <?php if ($row['type'] === 'Amethyst') echo 'selected'; ?>>Amethyst</option>
                <option value="Diamond"  <?php if ($row['type'] === 'Diamond') echo 'selected'; ?>>Diamond</option>
              </select>
            </div>


              <!--Weight-->
            <div class="form-group">
              <label for="weight">Weight</label>
              <input
                type="float"
                id="weight"
                name="weight"
                placeholder="Weight"
                value="<?php echo $row['weight'];?>"
                required
              />
            </div>

            
            <!-- origin Field -->
            <div class="form-group">
              <label for="origin">Origin</label>
              <input
                type="text"
                id="origin"
                name="origin"
                placeholder="Origin"
                value="<?php echo $row['origin'];?>"
                required
              />
            </div>

            <!-- Amount Field -->
            <div class="form-group">
              <label for="amount">Amount ($)</label>
              <input
                type="number"
                id="amount"
                name="amount"
                placeholder="Enter Amount"
                value="<?php echo $row['amount'];?>"
                required
              />
            </div>

            <!-- Image Field-->
            <label for="image">Image:</label>
            <input
              type="file"
              id="image"
              name="image"
              accept=".pdf,.jpg,.jpeg,.png"
              value="<?php echo $row['image'];?>"
            
              required
            />

            <!-- Certificate Field-->
            <div class="form-group">
            <label for="certificate">Certificate:</label>
            <input
              type="file"
              id="certificate"
              name="certificate"
              accept=".pdf,.jpg,.jpeg,.png"
              value="<?php echo $row['certificate'];?>"
              required
            />
            </div>

               <!--description-->
            <div class="form-group">
              <label for="description">Description</label>
              <input
                type="text"
                id="description"
                name="description"
                placeholder="Description"
                value="<?php echo $row['description'];?>"

                required
              />
            </div>

            <!-- Visibility Field -->
            <label for="visibility">Visibility:</label>
            <select id="visibility">
              <option value="show" <?php if ($row['visibility'] === 'show') echo 'selected'; ?>>Show</option>
              <option value="hide" <?php if ($row['visibility'] === 'hide') echo 'selected'; ?>>Hide</option>
            </select>

            <div class="form-group">
              <label for="availability">Availability:</label>
              <select id="availability" name="availability">
                <option value="available" <?php if ($row['availability'] === 'available') echo 'selected'; ?>>available</option>
                <option value="not available" <?php if ($row['availability'] === 'not available') echo 'selected'; ?>>not Available</option>
              </select>
            </div>

            <div class="form-group">
              <label for="buyer_id">Buyer ID</label>
              <input
                type="number"
                id="buyer_id"
                name="buyer_id"
                placeholder="Buyer Id"
                value="<?php echo $row['buyer_id'];?>"
                required
              />
            </div>

            <!-- Save Button -->
            <!-- <div class="form-actions">
              <div class="form-actions">
                  <button type="submit" class="btn-save">
                      <i class='bx bx-save'></i> Save Changes
                  </button>
              </div>                        
          </div> -->
          
            <?php if ($row['availability'] === 'available') { ?>
                        <div class="form-actions">
                            <button type="submit" class="btn-save">
                                <i class='bx bx-save'></i> Save Changes
                            </button>
                        </div>
                    <?php } else { ?>
                        <script>
                            document.querySelectorAll('input, select, textarea').forEach(input => input.disabled = true);
                        </script>
                    <?php } ?>
          </form>
        </div>
      </main>
    </section>

    <script src="../../Pages/Inventory/script.js"></script>
    <script src="../../../Sales_Rep_Dashboard/Pages/Inventory/inventory.js"></script>
    <script src="../../../Components/SalesRep_Dashboard_Template/script.js"></script>

  </body>
</html>