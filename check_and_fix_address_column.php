<?php
include('init.php');

// Check if address column exists in member_subscriptions table
$check_sql = "SHOW COLUMNS FROM member_subscriptions LIKE 'address'";
$check_result = mysqli_query($con, $check_sql);

if (mysqli_num_rows($check_result) == 0) {
    // Address column doesn't exist, add it
    $add_sql = "ALTER TABLE member_subscriptions ADD COLUMN address text AFTER receipt_no";
    if (mysqli_query($con, $add_sql)) {
        echo "✅ Address column added successfully to member_subscriptions table!";
    } else {
        echo "❌ Error adding address column: " . mysqli_error($con);
    }
} else {
    echo "✅ Address column already exists in member_subscriptions table!";
}

mysqli_close($con);
?> 