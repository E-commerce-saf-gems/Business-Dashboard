<?php

include '../../../database/db.php';  

$sql = "INSERT INTO cron (age) VALUES (20)";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully\n";
} else {
    echo "Error: " . $sql . "\n" . $conn->error;
}

$conn->close();
?>
