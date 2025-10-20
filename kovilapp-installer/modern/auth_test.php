<?php
include('init.php');

// This page tests the authentication system
echo "<h2>Authentication Test Page</h2>";

if (is_logged_in()) {
    echo "<div style='color: green;'>✅ User is logged in</div>";
    echo "<p><strong>Username:</strong> " . htmlspecialchars($_SESSION['username']) . "</p>";
    echo "<p><strong>Name:</strong> " . htmlspecialchars($_SESSION['name'] ?? 'N/A') . "</p>";
    echo "<p><strong>User ID:</strong> " . htmlspecialchars($_SESSION['ID'] ?? 'N/A') . "</p>";
    echo "<p><a href='dashboard.php'>Go to Dashboard</a> | <a href='logout.php'>Logout</a></p>";
} else {
    echo "<div style='color: red;'>❌ User is NOT logged in</div>";
    echo "<p><a href='index.php'>Go to Login Page</a></p>";
}

echo "<hr>";
echo "<h3>Test Links:</h3>";
echo "<ul>";
echo "<li><a href='dashboard.php'>Dashboard (should redirect to login if not authenticated)</a></li>";
echo "<li><a href='member/memberlist.php'>Member List (should redirect to login if not authenticated)</a></li>";
echo "<li><a href='user/userlist.php'>User List (should redirect to login if not authenticated)</a></li>";
echo "<li><a href='matrimony/listhoroscope.php'>Matrimony (should redirect to login if not authenticated)</a></li>";
echo "<li><a href='subscription/list.php'>Subscription (should redirect to login if not authenticated)</a></li>";
echo "<li><a href='index.php'>Login Page (should redirect to dashboard if already logged in)</a></li>";
echo "</ul>";
?>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background-color: #f8f9fa;
}
h2, h3 {
    color: #333;
}
div {
    margin: 10px 0;
    padding: 10px;
    border-radius: 5px;
}
ul {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
li {
    margin: 10px 0;
}
a {
    color: #007bff;
    text-decoration: none;
}
a:hover {
    text-decoration: underline;
}
</style> 