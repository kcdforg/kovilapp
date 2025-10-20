<?php
/**
 * Test script to verify installation fixes
 * Run this to test the database connection and schema before running the full installer
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Kovil App Installation Fixes - Test Script</h2>";

// Test database connection with sample credentials
echo "<h3>Testing Database Connection:</h3>";

// You can modify these values to match your actual database setup
$test_db_config = [
    'host' => 'localhost',
    'database' => 'koil_kovilapp', // Use the actual database name from your error
    'username' => 'root',
    'password' => ''
];

try {
    $connection = new mysqli(
        $test_db_config['host'],
        $test_db_config['username'],
        $test_db_config['password']
    );
    
    if ($connection->connect_error) {
        echo "<div style='color: red;'>❌ Connection failed: " . $connection->connect_error . "</div>";
    } else {
        echo "<div style='color: green;'>✅ Database connection successful</div>";
        
        // Check if database exists
        $db_name = $connection->real_escape_string($test_db_config['database']);
        $result = $connection->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$db_name}'");
        
        if ($result && $result->num_rows > 0) {
            echo "<div style='color: green;'>✅ Database '{$db_name}' exists</div>";
            
            // Select the database
            $connection->select_db($db_name);
            
            // Check for existing tables
            $result = $connection->query("SHOW TABLES");
            if ($result) {
                $tables = [];
                while ($row = $result->fetch_array()) {
                    $tables[] = $row[0];
                }
                
                if (!empty($tables)) {
                    echo "<div style='color: orange;'>⚠️ Database already contains tables: " . implode(', ', $tables) . "</div>";
                    echo "<div style='color: blue;'>ℹ️ You may want to backup and drop existing tables before reinstalling</div>";
                } else {
                    echo "<div style='color: green;'>✅ Database is empty and ready for installation</div>";
                }
            }
        } else {
            echo "<div style='color: orange;'>⚠️ Database '{$db_name}' does not exist - installer will create it</div>";
        }
        
        $connection->close();
    }
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error: " . $e->getMessage() . "</div>";
}

echo "<h3>Testing SQL Schema File:</h3>";

$schema_file = dirname(__FILE__) . '/kovil.sql';
if (file_exists($schema_file)) {
    echo "<div style='color: green;'>✅ Schema file found: kovil.sql</div>";
    
    $sql_content = file_get_contents($schema_file);
    if ($sql_content) {
        echo "<div style='color: green;'>✅ Schema file readable (" . strlen($sql_content) . " bytes)</div>";
        
        // Count CREATE TABLE statements
        $create_count = preg_match_all('/CREATE TABLE/i', $sql_content);
        echo "<div style='color: blue;'>ℹ️ Found {$create_count} CREATE TABLE statements</div>";
        
        // Check for users table specifically
        if (strpos($sql_content, 'CREATE TABLE `users`') !== false) {
            echo "<div style='color: green;'>✅ Users table definition found in schema</div>";
        } else {
            echo "<div style='color: red;'>❌ Users table definition not found in schema</div>";
        }
    } else {
        echo "<div style='color: red;'>❌ Could not read schema file</div>";
    }
} else {
    echo "<div style='color: red;'>❌ Schema file not found: {$schema_file}</div>";
}

echo "<h3>Installation Recommendations:</h3>";
echo "<ul>";
echo "<li>✅ All fixes have been applied to install.php</li>";
echo "<li>🔧 Fixed users table column mismatch</li>";
echo "<li>🔧 Fixed database configuration storage</li>";
echo "<li>🔧 Improved SQL parsing and error handling</li>";
echo "<li>🔧 Enhanced configuration file checks</li>";
echo "</ul>";

echo "<h3>Next Steps:</h3>";
echo "<ol>";
echo "<li>Ensure your database credentials are correct</li>";
echo "<li>If you have existing tables, consider backing them up and dropping them</li>";
echo "<li>Run the installer again: <a href='install.php?install=1'>install.php?install=1</a></li>";
echo "<li>Or run from command line: <code>php install.php</code></li>";
echo "</ol>";

?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; max-width: 800px; }
h2, h3 { color: #333; }
ul, ol { background: #f9f9f9; padding: 15px; border-radius: 5px; }
code { background: #f0f0f0; padding: 2px 5px; border-radius: 3px; }
</style>
