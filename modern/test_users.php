<?php
include('init.php');

// Check if user is logged in
check_login();

echo "<h2>Testing Users Database</h2>";

// Check if users table exists
$table_check = mysqli_query($con, "SHOW TABLES LIKE '$tbl_users'");
if (mysqli_num_rows($table_check) == 0) {
    echo "<p style='color: red;'>❌ Users table '$tbl_users' does not exist!</p>";
} else {
    echo "<p style='color: green;'>✅ Users table '$tbl_users' exists</p>";
}

// Check table structure
echo "<h3>Table Structure:</h3>";
$structure = mysqli_query($con, "DESCRIBE $tbl_users");
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

// Check for users
echo "<h3>Users in Database:</h3>";
$users = get_users();
if ($users && mysqli_num_rows($users) > 0) {
    echo "<p style='color: green;'>✅ Found " . mysqli_num_rows($users) . " users</p>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Profile ID</th></tr>";
    while ($user = mysqli_fetch_assoc($users)) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . htmlspecialchars($user['username']) . "</td>";
        echo "<td>" . htmlspecialchars($user['email']) . "</td>";
        echo "<td>" . htmlspecialchars($user['role']) . "</td>";
        echo "<td>" . htmlspecialchars($user['profile_id']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: orange;'>⚠️ No users found in database</p>";
    
    // Try to add a test user
    echo "<h3>Adding Test User:</h3>";
    $test_user = array(
        'username' => 'admin',
        'email' => 'admin@kovilapp.com',
        'password' => 'admin123',
        'role' => 'admin',
        'profile_id' => '1',
        'created_by' => 'system'
    );
    
    if (add_user($test_user)) {
        echo "<p style='color: green;'>✅ Test user added successfully</p>";
    } else {
        echo "<p style='color: red;'>❌ Failed to add test user: " . mysqli_error($con) . "</p>";
    }
}

// Test the get_users function
echo "<h3>Testing get_users() function:</h3>";
$test_users = get_users();
if ($test_users) {
    echo "<p style='color: green;'>✅ get_users() function works</p>";
    echo "<p>Result type: " . gettype($test_users) . "</p>";
    if (is_object($test_users)) {
        echo "<p>Object class: " . get_class($test_users) . "</p>";
    }
} else {
    echo "<p style='color: red;'>❌ get_users() function failed: " . mysqli_error($con) . "</p>";
}

echo "<br><a href='user/userlist.php'>← Back to User List</a>";
?> 