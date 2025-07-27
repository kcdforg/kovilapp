<?php
include('init.php');

// Check if user is logged in
check_login();

echo "<h2>Testing Delete Functionality</h2>";

// Test 1: Check if we can find a member to delete
$test_member_sql = "SELECT id, name, deleted FROM $tbl_family WHERE deleted=0 LIMIT 1";
$test_result = mysqli_query($con, $test_member_sql);
$test_member = mysqli_fetch_assoc($test_result);

if ($test_member) {
    echo "<p style='color: green;'>✅ Found test member: " . htmlspecialchars($test_member['name']) . " (ID: {$test_member['id']})</p>";
    
    // Test 2: Try to delete the member
    if (isset($_GET['test_delete']) && $_GET['test_delete'] == 'yes') {
        echo "<h3>Testing Delete Process:</h3>";
        
        $delete_sql = "UPDATE $tbl_family SET deleted=1 WHERE id=? AND deleted=0";
        $stmt = mysqli_prepare($con, $delete_sql);
        mysqli_stmt_bind_param($stmt, "i", $test_member['id']);
        
        if (mysqli_stmt_execute($stmt)) {
            $affected = mysqli_stmt_affected_rows($stmt);
            echo "<p style='color: green;'>✅ Delete query executed. Affected rows: $affected</p>";
            
            // Check if member is now deleted
            $check_sql = "SELECT id, name, deleted FROM $tbl_family WHERE id=?";
            $check_stmt = mysqli_prepare($con, $check_sql);
            mysqli_stmt_bind_param($check_stmt, "i", $test_member['id']);
            mysqli_stmt_execute($check_stmt);
            $check_result = mysqli_stmt_get_result($check_stmt);
            $check_member = mysqli_fetch_assoc($check_result);
            
            if ($check_member['deleted'] == 1) {
                echo "<p style='color: green;'>✅ Member successfully marked as deleted!</p>";
            } else {
                echo "<p style='color: red;'>❌ Member not marked as deleted. Current status: {$check_member['deleted']}</p>";
            }
            
            // Restore the member for testing
            $restore_sql = "UPDATE $tbl_family SET deleted=0 WHERE id=?";
            $restore_stmt = mysqli_prepare($con, $restore_sql);
            mysqli_stmt_bind_param($restore_stmt, "i", $test_member['id']);
            mysqli_stmt_execute($restore_stmt);
            echo "<p style='color: blue;'>🔄 Member restored for further testing</p>";
            
        } else {
            echo "<p style='color: red;'>❌ Delete query failed: " . mysqli_stmt_error($stmt) . "</p>";
        }
        mysqli_stmt_close($stmt);
    }
    
    echo "<br><a href='?test_delete=yes'>🧪 Test Delete Member: " . htmlspecialchars($test_member['name']) . "</a>";
    
} else {
    echo "<p style='color: red;'>❌ No members found to test with</p>";
}

echo "<br><br><a href='member/memberlist.php'>← Back to Member List</a>";
?> 