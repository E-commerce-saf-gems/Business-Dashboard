<?php
include('../../../database/db.php'); 

if (isset($_GET['id'])) {
    $stone_id = $_GET['id'];

    $sql = "SELECT * FROM inventory WHERE stone_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $stone_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $buyers_sql = "SELECT buyer_id, email FROM buyer";
    $buyers_result = $conn->query($buyers_sql);

    $amountSettled = 0; // Initialize amountSettled

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $amount_settled_sql = "SELECT amountSettled FROM purchases WHERE stone_id = ? AND buyer_id = ?";
        $settled_stmt = $conn->prepare($amount_settled_sql);
        $settled_stmt->bind_param("ii", $stone_id, $row['buyer_id']);
        $settled_stmt->execute();
        $settled_result = $settled_stmt->get_result();

        if ($settled_result->num_rows > 0) {
            $settled_row = $settled_result->fetch_assoc();
            $amountSettled = $settled_row['amountSettled']; // Assign the fetched value
        }

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

        <input type="hidden" name="stone_id" value="<?php echo $stone_id; ?>" />

            <div class="form-group">
              <label for="size">Size</label>
              <input type="text" id="size" name="size" placeholder="size" value="<?php echo $row['size'];?>" required />
            </div>

            <div class="form-group">
              <label for="shape">Shape</label>
              <input type="text" id="shape" name="shape" placeholder="shape" value="<?php echo $row['shape'];?>" required />
            </div>

            <div class="form-group">
              <label for="colour">Color</label>
              <input type="text" id="colour" name="colour" placeholder="Colour" value="<?php echo $row['colour'];?>" required />
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
            
            <div class="form-group">
              <label for="origin">Origin</label>
              <input type="text" id="origin" name="origin" placeholder="Origin" value="<?php echo $row['origin'];?>" required />
            </div>

            <div class="form-group">
              <label for="amount">Amount ($)</label>
              <input type="number" id="amount" name="amount" placeholder="Enter Amount" value="<?php echo $row['amount'];?>" required />
            </div>

            <div class="form-group">
              <label for="image">Image:</label>
              <?php if (!empty($row['image'])): ?>
                <div>
                  <p>Current Image:</p>
                  <img src="../../../uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Uploaded Image" style="max-width: 200px;" />
                </div>
              <?php endif; ?>
              <input type="file" id="image" name="image" accept=".pdf,.jpg,.jpeg,.png" />
              <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($row['image']); ?>" />
            </div>

            <div class="form-group">
              <label for="certificate">Certificate:</label>
              <?php if (!empty($row['certificate'])): ?>
                <div>
                  <p>Current Certificate:</p>
                  <img src="../../../uploads/<?php echo htmlspecialchars($row['certificate']); ?>"/>
                </div>
              <?php endif; ?>
              <input type="file" id="certificate" name="certificate" accept=".pdf,.jpg,.jpeg,.png" />
              <input type="hidden" name="current_certificate" value="<?php echo htmlspecialchars($row['certificate']); ?>" />
            </div>



            <div class="form-group">
              <label for="description">Description</label>
              <input type="text" id="description" name="description" placeholder="Description" value="<?php echo $row['description'];?>" required />
            </div>

            <div class="form-group">
            <label for="visibility">Visibility:</label>
            <select id="visibility" name="visibility">
              <option value="show" <?php if ($row['visibility'] === 'show') echo 'selected'; ?>>show</option>
              <option value="hide" <?php if ($row['visibility'] === 'hide') echo 'selected'; ?>>hide</option>
            </select>
            </div>

            <!-- <div class="form-group">
              <label for="availability">Availability:</label>
              <select id="availability" name="availability">
                <option value="available" <?php if ($row['availability'] === 'available') echo 'selected'; ?>>available</option>
                <option value="not available" <?php if ($row['availability'] === 'notavailable') echo 'selected'; ?>>not available</option>
              </select>
            </div> -->

            <div class="form-group">
                <label for="buyer">Select Buyer:</label>
                <select id="buyer" name="buyer_id">
                    <option value="">Select a buyer</option>
                    <?php
                    while ($buyer = $buyers_result->fetch_assoc()) {
                        $selected = ($buyer['buyer_id'] == $row['buyer_id']) ? 'selected' : '';
                        echo "<option value='{$buyer['buyer_id']}' {$selected}>{$buyer['email']}</option>";
                    }
                    ?>
                </select>
                <span class="error-message" id="buyer-error"></span>
            </div>

            <div class="form-group">
              <label for="amountSettled">Amount Settled:</label>
              <input type="number" id="amountSettled" name="amountSettled" value="<?php echo $amountSettled; ?>"/>
            </div>
          
            <div class="form-actions">
              <button type="submit" class="btn-save">
                  <i class='bx bx-save'></i> Save Changes
              </button>
            </div>
          </form>
        </div>
      </main>
    </section>

    <script src="../../Pages/Inventory/script.js"></script>
    <script src="../../../Sales_Rep_Dashboard/Pages/Inventory/inventory.js"></script>
    <script src="../../../Components/SalesRep_Dashboard_Template/script.js"></script>

  </body>
</html>