<?php
/**
 * Database Troubleshooting Script for Kovil App
 * 
 * This script helps diagnose database connection and table issues
 */

// Enable error display for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<h2>Kovil App Database Troubleshooting</h2>";

// Check if config file exists
$installer_path = dirname(__FILE__);
$web_root = dirname($installer_path);
$config_file = $web_root . '/config.php';

echo "<h3>Configuration Check:</h3>";
echo "<ul>";
echo "<li><strong>Installer Path:</strong> {$installer_path}</li>";
echo "<li><strong>Web Root:</strong> {$web_root}</li>";
echo "<li><strong>Config File:</strong> {$config_file}</li>";
echo "</ul>";

if (!file_exists($config_file)) {
    echo "<div style='color: red;'><strong>ERROR:</strong> Config file not found at {$config_file}</div>";
    echo "<p>Please run the installation first.</p>";
    exit;
}

// Load configuration
include $config_file;

echo "<h3>Database Configuration:</h3>";
echo "<ul>";
echo "<li><strong>Host:</strong> " . ($db_host ?? 'Not set') . "</li>";
echo "<li><strong>Database:</strong> " . ($db_name ?? 'Not set') . "</li>";
echo "<li><strong>Username:</strong> " . ($db_user ?? 'Not set') . "</li>";
echo "<li><strong>Password:</strong> " . (isset($db_pass) ? (empty($db_pass) ? 'Empty' : 'Set (hidden)') : 'Not set') . "</li>";
echo "</ul>";

// Test database connection
echo "<h3>Database Connection Test:</h3>";

try {
    $connection = new mysqli($db_host, $db_user, $db_pass);
    
    if ($connection->connect_error) {
        echo "<div style='color: red;'>✗ Connection failed: " . $connection->connect_error . "</div>";
        exit;
    }
    
    echo "<div style='color: green;'>✓ Database server connection successful</div>";
    
    // Check if database exists
    $result = $connection->query("SHOW DATABASES LIKE '$db_name'");
    if ($result->num_rows > 0) {
        echo "<div style='color: green;'>✓ Database '$db_name' exists</div>";
        
        // Select database
        $connection->select_db($db_name);
        
        // Check required tables
        $required_tables = [
            'users', 'family', 'child', 'matrimony', 'kattam', 
            'attachments', 'labels', 'subscription_events', 
            'receipt_books', 'member_subscriptions', 'receipt_details'
        ];
        
        echo "<h3>Table Check:</h3>";
        echo "<ul>";
        
        $missing_tables = [];
        foreach ($required_tables as $table) {
            $result = $connection->query("SHOW TABLES LIKE '$table'");
            if ($result->num_rows > 0) {
                echo "<li style='color: green;'>✓ Table '$table' exists</li>";
            } else {
                echo "<li style='color: red;'>✗ Table '$table' missing</li>";
                $missing_tables[] = $table;
            }
        }
        echo "</ul>";
        
        if (!empty($missing_tables)) {
            echo "<div style='color: orange;'><strong>Missing Tables Found!</strong></div>";
            echo "<p>The following tables are missing: " . implode(', ', $missing_tables) . "</p>";
            echo "<p>This usually means the database schema wasn't imported properly during installation.</p>";
            
            // Offer to reimport schema
            if (file_exists($installer_path . '/kovil.sql')) {
                echo "<h3>Schema Reimport:</h3>";
                echo "<p>The kovil.sql file is available. You can reimport the schema:</p>";
                
                if (isset($_POST['reimport_schema'])) {
                    echo "<div style='background: #f0f0f0; padding: 15px; border-radius: 5px;'>";
                    echo "<h4>Importing Schema...</h4>";
                    
                    $sql_content = file_get_contents($installer_path . '/kovil.sql');
                    $statements = array_filter(array_map('trim', explode(';', $sql_content)));
                    
                    $success_count = 0;
                    $error_count = 0;
                    
                    foreach ($statements as $statement) {
                        if (!empty($statement) && !preg_match('/^--/', $statement)) {
                            if ($connection->query($statement)) {
                                $success_count++;
                            } else {
                                $error_count++;
                                echo "<div style='color: orange;'>Warning: " . $connection->error . "</div>";
                            }
                        }
                    }
                    
                    echo "<div style='color: green;'>✓ Schema import completed</div>";
                    echo "<div>Executed: {$success_count} statements, Errors: {$error_count}</div>";
                    
                    // Create default admin user
                    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
                    $sql = "INSERT IGNORE INTO `users` (`username`, `password`, `email`, `role`) 
                            VALUES ('admin', '{$admin_password}', 'admin@kovilapp.com', 'admin')";
                    
                    if ($connection->query($sql)) {
                        echo "<div style='color: green;'>✓ Default admin user created (username: admin, password: admin123)</div>";
                    }
                    
                    echo "</div>";
                    echo "<p><a href='" . $_SERVER['PHP_SELF'] . "'>Refresh to check tables again</a></p>";
                } else {
                    echo "<form method='post'>";
                    echo "<button type='submit' name='reimport_schema' style='background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>Reimport Database Schema</button>";
                    echo "</form>";
                }
            }
        } else {
            echo "<div style='color: green;'><strong>✓ All required tables exist!</strong></div>";
            
            // Test a simple query
            $result = $connection->query("SELECT COUNT(*) as count FROM users");
            if ($result) {
                $row = $result->fetch_assoc();
                echo "<div style='color: green;'>✓ Database queries working (Users table has {$row['count']} records)</div>";
            } else {
                echo "<div style='color: red;'>✗ Query test failed: " . $connection->error . "</div>";
            }
        }
        
    } else {
        echo "<div style='color: red;'>✗ Database '$db_name' does not exist</div>";
        echo "<p>Available databases:</p>";
        $result = $connection->query("SHOW DATABASES");
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . $row['Database'] . "</li>";
        }
        echo "</ul>";
    }
    
    $connection->close();
    
} catch (Exception $e) {
    echo "<div style='color: red;'>✗ Database error: " . $e->getMessage() . "</div>";
}

?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; max-width: 1000px; }
h2, h3 { color: #333; }
ul { background: #f9f9f9; padding: 15px; border-radius: 5px; }
li { margin: 5px 0; }
</style>

<h3>Next Steps:</h3>
<ul>
    <li>If tables are missing, use the "Reimport Database Schema" button above</li>
    <li>If database doesn't exist, create it manually or run the installer again</li>
    <li>If connection fails, check your database credentials in config.php</li>
    <li>Make sure your database server is running</li>
</ul>

<p><strong>Common Issues:</strong></p>
<ul>
    <li><strong>Wrong database name:</strong> Your production database might have a different name (like 'koil_kovilapp' instead of 'kovil')</li>
    <li><strong>Missing tables:</strong> Schema import failed during installation</li>
    <li><strong>Wrong credentials:</strong> Database username/password incorrect</li>
</ul>
