<?php
include '../../../database/db.php';

date_default_timezone_set('Asia/Kolkata');
$currentDateTime = date('Y-m-d H:i:s');

$query = "
    SELECT bs.biddingStone_id, bs.finishDate, bs.customer_id
    FROM biddingstone bs
    WHERE bs.finishDate < DATE_SUB('$currentDateTime', INTERVAL 7 DAY)
      AND bs.customer_id IS NOT NULL
";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $biddingStone_id = $row['biddingStone_id'];
        $customer_id = $row['customer_id'];

        echo "Found expired bid → Stone ID: $biddingStone_id | Customer ID: $customer_id<br>";

        if ($biddingStone_id && $customer_id) {
            $invalidateQuery = "
                UPDATE bid
                SET validity = 'invalid'
                WHERE bid_id = (
                    SELECT bid_id FROM (
                        SELECT bid_id
                        FROM bid
                        WHERE biddingStone_id = $biddingStone_id AND customer_id = $customer_id
                        ORDER BY amount DESC
                        LIMIT 1
                    ) AS temp
                )
            ";

            if ($conn->query($invalidateQuery)) {
                echo "Invalidated top bid for Stone ID: $biddingStone_id<br>";
            } else {
                echo "Error invalidating bid: " . $conn->error . "<br>";
            }
            $revokeWinQuery = "
                UPDATE biddingstone
                SET customer_id = 0
                WHERE biddingStone_id = $biddingStone_id
            ";
            $conn->query($revokeWinQuery);
        }
    }
} else {
    echo "No expired bids found.<br>";
}
?>
