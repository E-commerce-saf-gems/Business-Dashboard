<td>
    <form method="POST" action="./updateRequest.php" onsubmit="return handleStatusChange(this)">
        <input type="hidden" name="request_id" value="<?php echo $row['request_id']; ?>">
        <input type="hidden" name="decline_reason" id="decline_reason_<?php echo $row['request_id']; ?>" value="">
        
        <select name="status" data-original-value="<?php echo $row['status']; ?>" onchange="handleStatusChange(this, <?php echo $row['request_id']; ?>)">
            <?php
            // Dynamic status dropdown options based on current status
            $statusOptions = [
                'P' => ['P' => 'Pending', 'A' => 'Approve', 'D' => 'Decline'],  // Pending status
                'A' => ['A' => 'Approved', 'C' => 'Complete', 'D' => 'Decline'], // Approved status
                'C' => ['C' => 'Completed', 'P' => 'Pending', 'D' => 'Decline'], // Completed status
                'D' => ['D' => 'Declined', 'A' => 'Approve', 'P' => 'Pending'],  // Declined status
            ];

            // Iterate through the options based on the request's current status
            foreach ($statusOptions[$row['status']] as $statusKey => $statusLabel) {
                $selected = $row['status'] === $statusKey ? "selected" : ""; // Mark the current status as selected
                echo "<option value=\"$statusKey\" $selected>$statusLabel</option>";
            }
            ?>
        </select>
        
        <button id="update_btn_<?php echo $row['request_id']; ?>" type="submit" style="display: none;">Update</button>
    </form>
</td>
