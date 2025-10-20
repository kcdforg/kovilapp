<?php
/**
 * Debug Configuration for Kovil App
 * 
 * This file enables comprehensive error reporting and debugging
 * Use this during development and troubleshooting
 */

// Enable all error reporting
error_reporting(E_ALL | E_STRICT);

// Display errors on screen
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Log errors to file
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/debug_errors.log');

// HTML errors for better web display
ini_set('html_errors', 1);

// Show more detailed error information
ini_set('track_errors', 1);

// Memory and execution time for debugging
ini_set('memory_limit', '512M');
ini_set('max_execution_time', 300);

// Display PHP info and current error settings
echo "<h2>PHP Debug Information</h2>";
echo "<h3>Current Error Settings:</h3>";
echo "<ul>";
echo "<li><strong>Error Reporting:</strong> " . error_reporting() . "</li>";
echo "<li><strong>Display Errors:</strong> " . (ini_get('display_errors') ? 'ON' : 'OFF') . "</li>";
echo "<li><strong>Display Startup Errors:</strong> " . (ini_get('display_startup_errors') ? 'ON' : 'OFF') . "</li>";
echo "<li><strong>Log Errors:</strong> " . (ini_get('log_errors') ? 'ON' : 'OFF') . "</li>";
echo "<li><strong>Error Log:</strong> " . ini_get('error_log') . "</li>";
echo "<li><strong>Memory Limit:</strong> " . ini_get('memory_limit') . "</li>";
echo "<li><strong>Max Execution Time:</strong> " . ini_get('max_execution_time') . " seconds</li>";
echo "</ul>";

echo "<h3>Test Error Display:</h3>";
echo "<p>If error display is working, you should see an error below:</p>";

// Trigger a notice to test error display
echo $undefined_variable; // This will generate a notice

echo "<h3>PHP Configuration:</h3>";
echo "<details><summary>Click to view full PHP info</summary>";
phpinfo();
echo "</details>";

echo "<h3>Server Information:</h3>";
echo "<ul>";
echo "<li><strong>PHP Version:</strong> " . PHP_VERSION . "</li>";
echo "<li><strong>Server Software:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</li>";
echo "<li><strong>Document Root:</strong> " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "</li>";
echo "<li><strong>Script Name:</strong> " . ($_SERVER['SCRIPT_NAME'] ?? 'Unknown') . "</li>";
echo "</ul>";

?>
<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2, h3 { color: #333; }
ul { background: #f5f5f5; padding: 15px; border-radius: 5px; }
details { margin: 10px 0; }
summary { cursor: pointer; background: #e0e0e0; padding: 10px; border-radius: 3px; }
</style>
