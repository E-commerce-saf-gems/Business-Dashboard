<?php
include('../../../database/db.php'); 

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $sql = "SELECT * FROM user WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
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
    <title>Edit Staff Details</title>
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
					<h1>Edit Staff Details</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="#">Edit Staff Details</a>
						</li>
					</ul>
				</div>
			</div>
            <div class="edit-sales-container">
                <form class="edit-sales-form" id="editStaffForm" action="./updatestaff.php" method="POST" enctype="multipart/form-data">
                    <h2>Edit Staff Details</h2>

                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="<?php echo $row['username'];?>"required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" value="<?php echo $row['password'];?>" required>
                    </div>
    
                    <!-- Status Field -->
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role">
                            <option value="Partner" <?php if ($row['role'] === 'Partner') echo 'selected'; ?>>Partner</option>
                            <option value="SalesRep" <?php if ($row['role'] === 'SalesRep') echo 'selected'; ?>>Sales Res.</option>
                            <option value="Accounatnt" <?php if ($row['role'] === 'Accounatnt') echo 'selected'; ?>>Accountant</option>
                        </select>
                    </div>
                    

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" value="<?php echo $row['name'];?>"required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" value="<?php echo $row['email'];?>"required>
                    </div>
                    
                    <div>
                        <label for="contactNo">Phone Number</label>
                        <input type="number" id="contactNo" name="contactNo" placeholder="Phone Number" 
                        value="<?php echo $row['contactNo'];?>"required>
                    </div>
                    
                    <!-- Save Button -->
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

    <script src="../../../Components/Admin_Dashboard_Template/script.js"></script>
    <script src="../../../Admin_Dashboard/script.js"></script>
    <script src="./staff.js"></script>
</body>
</html>