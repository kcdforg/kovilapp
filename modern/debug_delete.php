<?php
include('init.php');

// Check if user is logged in
check_login();

echo "<h2>Debug Delete Functionality</h2>";

$id = $_GET['id'] ?? 0;
echo "<p><strong>Member ID:</strong> $id</p>";

// Check database connection
if (!$con) {
    echo "<p style='color: red;'>❌ Database connection failed!</p>";
    exit;
} else {
    echo "<p style='color: green;'>✅ Database connection successful</p>";
}

// Check if table exists
$table_check = mysqli_query($con, "SHOW TABLES LIKE '$tbl_family'");
if (mysqli_num_rows($table_check) == 0) {
    echo "<p style='color: red;'>❌ Table '$tbl_family' does not exist!</p>";
    exit;
} else {
    echo "<p style='color: green;'>✅ Table '$tbl_family' exists</p>";
}

// Get member details
$member = get_member($id);
if (!$member) {
    echo "<p style='color: red;'>❌ Member not found with ID: $id</p>";
    
    // Check what members exist
    echo "<h3>Available Members:</h3>";
    $all_members = mysqli_query($con, "SELECT id, name FROM $tbl_family WHERE deleted=0 LIMIT 10");
    if ($all_members) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Name</th></tr>";
        while ($row = mysqli_fetch_assoc($all_members)) {
            echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td></tr>";
        }
        echo "</table>";
    }
} else {
    echo "<p style='color: green;'>✅ Member found: " . htmlspecialchars($member['name']) . "</p>";
    echo "<p><strong>Current deleted status:</strong> " . ($member['deleted'] ?? 'NULL') . "</p>";
}

// Test the delete query
if (isset($_GET['test_delete']) && $_GET['test_delete'] == 'yes') {
    echo "<h3>Testing Delete Query:</h3>";
    
    $sql = "UPDATE $tbl_family SET `deleted`=1 WHERE id=?";
    echo "<p><strong>SQL:</strong> $sql</p>";
    
    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        echo "<p style='color: red;'>❌ Prepare statement failed: " . mysqli_error($con) . "</p>";
    } else {
        echo "<p style='color: green;'>✅ Prepare statement successful</p>";
        
        mysqli_stmt_bind_param($stmt, "i", $id);
        echo "<p style='color: green;'>✅ Parameter binding successful</p>";
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<p style='color: green;'>✅ Delete query executed successfully</p>";
            echo "<p><strong>Affected rows:</strong> " . mysqli_stmt_affected_rows($stmt) . "</p>";
        } else {
            echo "<p style='color: red;'>❌ Delete query failed: " . mysqli_stmt_error($stmt) . "</p>";
        }
        mysqli_stmt_close($stmt);
    }
}

// Check table structure
echo "<h3>Table Structure:</h3>";
$structure = mysqli_query($con, "DESCRIBE $tbl_family");
if ($structure) {
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = mysqli_fetch_assoc($structure)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<br><a href='member/memberlist.php'>← Back to Member List</a>";
echo "<br><a href='?id=$id&test_delete=yes'>🧪 Test Delete Query</a>";
?> 