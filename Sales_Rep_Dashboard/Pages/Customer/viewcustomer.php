<?php
include('../../../database/db.php'); 

if (isset($_GET['id'])) {
    $customer_id = $_GET['id'];

    $sql = "SELECT * FROM customer WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer Details</title>
    <link rel="stylesheet" href="../../../Components/Admin_Dashboard_Template/styles.css">
    <link rel="stylesheet" href="../editCustomerStyles.css">   
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <dashboard-component></dashboard-component>
    <section id="content">
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Customers</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a class="active" href="#">Edit Customer Details</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="edit-sales-container">
                <form class="edit-sales-form" id="editCustomerForm" method="POST" action="updatecustomer.php" enctype="multipart/form-data">
                    <h2>Edit Customer Details</h2>

                    <div class="form-group">
                        <label for="NIC">NIC Number</label>
                        <input type="text" id="NIC" name="NIC" value="<?= htmlspecialchars($row['NIC']); ?>" placeholder="NIC Number" required>
                    </div>

                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" value="<?= htmlspecialchars($row['title']); ?>" placeholder="Title" required>
                    </div>

                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="firstName" value="<?= htmlspecialchars($row['firstName']); ?>" placeholder="First Name" required>
                    </div>

                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastName" value="<?= htmlspecialchars($row['lastName']); ?>" placeholder="Last Name" required>
                    </div>

                    <div class="form-group">
                        <label for="contactNo">Contact Number</label>
                        <input type="text" id="contactNo" name="contactNo" value="<?= htmlspecialchars($row['contactNo']); ?>" placeholder="Contact Number" required>
                    </div>

                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp">
                        <p>Current Image: <?= htmlspecialchars($row['image']); ?></p>
                    </div>

                    <div class="form-group">
                        <label for="pdf">PDF</label>
                        <input type="file" id="pdf" name="pdf" accept=".pdf">
                        <p>Current PDF: <?= htmlspecialchars($row['pdf']); ?></p>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <input type="text" id="status" name="status" value="<?= htmlspecialchars($row['status']); ?>" placeholder="Status" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($row['email']); ?>" placeholder="Email Address" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" value="<?= htmlspecialchars($row['password']); ?>" placeholder="Password" required>
                    </div>

                    <div class="form-group">
                        <label for="address1">Address Line 1</label>
                        <input type="text" id="address1" name="address1" value="<?= htmlspecialchars($row['address1']); ?>" placeholder="Address Line 1" required>
                    </div>

                    <div class="form-group">
                        <label for="address2">Address Line 2</label>
                        <input type="text" id="address2" name="address2" value="<?= htmlspecialchars($row['address2']); ?>" placeholder="Address Line 2" required>
                    </div>

                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" value="<?= htmlspecialchars($row['city']); ?>" placeholder="City" required>
                    </div>

                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country" value="<?= htmlspecialchars($row['country']); ?>" placeholder="Country" required>
                    </div>

                    <div class="form-group">
                        <label for="postalCode">Postal Code</label>
                        <input type="text" id="postalCode" name="postalCode" value="<?= htmlspecialchars($row['postalCode']); ?>" placeholder="Postal Code" required>
                    </div>

                    <div class="form-group">
                        <label for="DOB">DOB</label>
                        <input type="date" id="DOB" name="DOB" value="<?= htmlspecialchars($row['DOB']); ?>" placeholder="DOB" required>
                    </div>

                    <div class="form-group">
                        <label for="token">Token</label>
                        <input type="text" id="token" name="token" value="<?= htmlspecialchars($row['token']); ?>" placeholder="Token" required>
                    </div>

                    <div class="form-group">
                        <label for="verificationStatus">Verification Status</label>
                        <input type="text" id="verificationStatus" name="verificationStatus" value="<?= htmlspecialchars($row['verificationStatus']); ?>" placeholder="Verification Status" required>
                    </div>
                    
                    <div class="form-actions">
                        <div class="form-actions">
                            <button type="submit" class="btn-save">
                                <i class='bx bx-save'></i> Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </section>

    <script>
            document.querySelectorAll('input, select, textarea').forEach(input => input.disabled = true);
        </script>
        
    <script src="../../../Components/Admin_Dashboard_Template/script.js"></script>
    <script src="../../../Admin_Dashboard/script.js"></script>
</body>
</html>
