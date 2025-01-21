<?php
include('../../../database/db.php'); // Include your database connection

if (isset($_GET['buyer_id'])) {
    $buyer_id = intval($_GET['buyer_id']);
    $query = "SELECT p.stone_id, st.type , st.size , (p.amount-p.amountSettled) AS amountToBeSettled FROM purchases p
              JOIN inventory st ON p.stone_id = st.stone_id 
              WHERE p.buyer_id = ? AND p.amountSettled<p.amount" ;
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $buyer_id);
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