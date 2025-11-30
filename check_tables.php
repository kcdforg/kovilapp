<?php
include('init.php');

echo "<h2>Database Tables Check</h2>";
echo "<p>Database: $db_name</p>";

// Get all tables
$result = mysqli_query($con, "SHOW TABLES");
if ($result) {
    echo "<h3>Existing Tables:</h3>";
    echo "<ul>";
    while ($row = mysqli_fetch_array($result)) {
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Error getting tables: " . mysqli_error($con) . "</p>";
}

// Check specific tables mentioned in config
$tables_to_check = [
    'users',
    'family', 
    'child',
    'event',
    'matrimony',
    'kattam',
    'attachments',
    'book',
    'receipt',
    'labels',
    'donation',
    'horoscope',
    'subscription'
];

echo "<h3>Checking Required Tables:</h3>";
echo "<ul>";
foreach ($tables_to_check as $table) {
    $result = mysqli_query($con, "SHOW TABLES LIKE '$table'");
    if (mysqli_num_rows($result) > 0) {
        echo "<li style='color: green;'>✓ $table - EXISTS</li>";
    } else {
        echo "<li style='color: red;'>✗ $table - MISSING</li>";
    }
}
echo "</ul>";
?> 