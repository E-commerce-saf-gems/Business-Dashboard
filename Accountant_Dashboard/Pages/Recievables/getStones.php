<?php
include('../../../database/db.php'); // Include your database connection

if (isset($_GET['customer_id'])) {
    $customer_id = intval($_GET['customer_id']);
    $query = "SELECT s.stone_id, st.type ,st.size,  (s.total-s.amountSettled) AS amountToBeSettled FROM sales s 
              JOIN inventory st ON s.stone_id = st.stone_id 
              WHERE s.customer_id = ? AND s.amountSettled<s.total" ;
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $stones = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $stones[] = $row;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($stones);
}

$conn->close();
?>