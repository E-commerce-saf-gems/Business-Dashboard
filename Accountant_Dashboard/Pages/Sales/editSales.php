<?php
include '../../../database/db.php';

$sale_id = $_GET['id'];
$sql = "SELECT * FROM sales WHERE sale_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $sale_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>

<form action="updateSales.php" method="POST" class="edit-sales-form">
    <input type="hidden" name="sale_id" value="<?php echo $row['sale_id']; ?>">

    <div class="form-group">
        <label for="stone_id">Stone ID</label>
        <input type="number" name="stone_id" value="<?php echo $row['stone_id']; ?>" required>
    </div>

    <div class="form-group">
        <label for="customer_id">Customer ID</label>
        <input type="number" name="customer_id" value="<?php echo $row['customer_id']; ?>" required>
    </div>

    <div class="form-group">
        <label for="total">Amount (Rs.)</label>
        <input type="number" name="total" value="<?php echo $row['total']; ?>" required>
    </div>

    <div class="form-group">
        <label for="date">Date</label>
        <input type="datetime-local" name="date" value="<?php echo date('Y-m-d\TH:i', strtotime($row['date'])); ?>" required>
    </div>

    <div class="form-group">
        <label for="amountSettled">Amount Settled (Rs.)</label>
        <input type="number" name="amountSettled" value="<?php echo $row['amountSettled']; ?>" required>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn-save"><i class='bx bx-edit'></i> Update</button>
    </div>
</form>
