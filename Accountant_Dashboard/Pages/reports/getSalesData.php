<?php
include '../../../db_connection.php';

// Ensure startDate and endDate are correctly received from the request
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : null;
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : null;// Initialize response array

// Fallback to defaults if not provided
if (!$startDate || !$endDate) {
    die(json_encode(["error" => "Missing startDate or endDate"]));
}

$response = [
    'totalSales' => 0,
    'totalRevenue' => 0,
    'avgSaleAmount' => 0,
    'unitsSold' => 0,
    'auctionRevenue' => 0,
    'regularRevenue' => 0,
    'newCustomerSales' => 0,
    'repeatCustomerSales' => 0,
    'avgSalesNew' => 0,
    'avgSalesRepeat' => 0
];

if ($dateFrom && $dateTo) {
    $sql = "SELECT 
                COUNT(id) AS totalSales, 
                SUM(amount) AS totalRevenue, 
                AVG(amount) AS avgSaleAmount, 
                SUM(quantity) AS unitsSold,
                SUM(CASE WHEN sale_type = 'auction' THEN amount ELSE 0 END) AS auctionRevenue,
                SUM(CASE WHEN sale_type = 'regular' THEN amount ELSE 0 END) AS regularRevenue,
                SUM(CASE WHEN customer_type = 'new' THEN amount ELSE 0 END) AS newCustomerSales,
                SUM(CASE WHEN customer_type = 'repeat' THEN amount ELSE 0 END) AS repeatCustomerSales,
                AVG(CASE WHEN customer_type = 'new' THEN amount ELSE NULL END) AS avgSalesNew,
                AVG(CASE WHEN customer_type = 'repeat' THEN amount ELSE NULL END) AS avgSalesRepeat
            FROM sales
            WHERE sale_date BETWEEN ? AND ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $dateFrom, $dateTo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $response = array_merge($response, $row);
    }

    $stmt->close();
}

$conn->close();
echo json_encode($response);
?>
