<?php
/**
 * Configuration Fix Script for Kovil App
 * 
 * This script fixes path configuration issues in existing installations
 * Run this if you're experiencing URL or redirection problems
 */

// Enable error display for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<h2>Kovil App Configuration Fix</h2>";

// Determine paths
$installer_path = dirname(__FILE__);
$web_root = dirname($installer_path);
$config_file = $web_root . '/config.php';

echo "<h3>Path Analysis:</h3>";
echo "<ul>";
echo "<li><strong>Installer Path:</strong> {$installer_path}</li>";
echo "<li><strong>Web Root:</strong> {$web_root}</li>";
echo "<li><strong>Config File:</strong> {$config_file}</li>";
echo "<li><strong>Document Root:</strong> " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "</li>";
echo "<li><strong>Script Name:</strong> " . ($_SERVER['SCRIPT_NAME'] ?? 'Unknown') . "</li>";
echo "</ul>";

// Check if config file exists
if (!file_exists($config_file)) {
    echo "<div style='color: red;'><strong>ERROR:</strong> Config file not found at {$config_file}</div>";
    echo "<p>Please run the installation first.</p>";
    exit;
}

// Read current config
$current_config = file_get_contents($config_file);
echo "<h3>Current Configuration Issues:</h3>";

// Check for problematic paths
$issues = [];
if (strpos($current_config, '/kovilapp/modern') !== false) {
    $issues[] = "Found hardcoded '/kovilapp/modern' path";
}
if (strpos($current_config, '/kovilapp') !== false && strpos($current_config, '/kovilapp-installer') === false) {
    $issues[] = "Found hardcoded '/kovilapp' path";
}

if (empty($issues)) {
    echo "<div style='color: green;'>No obvious path issues found in config file.</div>";
} else {
    echo "<ul>";
    foreach ($issues as $issue) {
        echo "<li style='color: orange;'>{$issue}</li>";
    }
    echo "</ul>";
}

// Generate correct configuration
function generateFixedConfig() {
    global $web_root;
    
    // Get database credentials from existing config
    $db_host = 'localhost';
    $db_name = 'kovil';
    $db_user = 'root';
    $db_pass = '';
    
    // Try to extract from existing config
    $config_file = $web_root . '/config.php';
    if (file_exists($config_file)) {
        $config_content = file_get_contents($config_file);
        if (preg_match('/\$db_host = [\'"]([^\'"]*)[\'"]/', $config_content, $matches)) {
            $db_host = $matches[1];
        }
        if (preg_match('/\$db_name = [\'"]([^\'"]*)[\'"]/', $config_content, $matches)) {
            $db_name = $matches[1];
        }
        if (preg_match('/\$db_user = [\'"]([^\'"]*)[\'"]/', $config_content, $matches)) {
            $db_user = $matches[1];
        }
        if (preg_match('/\$db_pass = [\'"]([^\'"]*)[\'"]/', $config_content, $matches)) {
            $db_pass = $matches[1];
        }
    }
    
    // Calculate correct paths
    $script_name = dirname($_SERVER['SCRIPT_NAME'] ?? '');
    $script_name = rtrim($script_name, '/\\');
    
    // Remove installer directory from path
    $installer_dir = basename(dirname(__FILE__));
    if (strpos($script_name, $installer_dir) !== false) {
        $app_path = str_replace('/' . $installer_dir, '', $script_name);
    } else {
        $app_path = dirname($script_name);
    }
    $app_path = rtrim($app_path, '/');
    
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $base_url = $protocol . '://' . $_SERVER['SERVER_NAME'] . $app_path;
    
    return '<?php

global $db_host, $db_name, $db_user, $db_pass, $con, $path, $base_path;

// Database credentials (connection will be established in init.php)
$db_host = \'' . addslashes($db_host) . '\';
$db_name = \'' . addslashes($db_name) . '\';
$db_user = \'' . addslashes($db_user) . '\';
$db_pass = \'' . addslashes($db_pass) . '\';

// Table definitions
$tbl_users = \'users\';
$tbl_family = \'family\';
$tbl_child = \'child\';
$tbl_event = \'event\';
$tbl_matrimony = \'matrimony\';
$tbl_kattam = \'kattam\';
$tbl_attachments = \'attachments\';
$tbl_book = \'book\';
$tbl_receipt = \'receipt\';
$tbl_labels = \'labels\';
$tbl_donation = \'donation\';
$tbl_horoscope = \'horoscope\';
$tbl_subscription = \'subscription\';

// Subscription Module Tables
$tbl_subscription_events = \'subscription_events\';
$tbl_receipt_books = \'receipt_books\';
$tbl_member_subscriptions = \'member_subscriptions\';
$tbl_receipt_details = \'receipt_details\';

// Dynamically determine paths
$document_root = $_SERVER[\'DOCUMENT_ROOT\'];
$script_name = $_SERVER[\'SCRIPT_NAME\'];
$request_uri = $_SERVER[\'REQUEST_URI\'];

// Get the directory where this script is located relative to document root
$script_dir = dirname($script_name);

// Determine protocol
$protocol = (!empty($_SERVER[\'HTTPS\']) && $_SERVER[\'HTTPS\'] !== \'off\') ? \'https\' : \'http\';

// Application base URL (should be root after installation)
$path = $protocol . \'://\' . $_SERVER[\'SERVER_NAME\'];

// Base directory for file operations (should be document root after installation)
$base_dir = $document_root;

// Paths for application (all point to document root after installation)
$current_path = $document_root;
$modern_path = $document_root;

// Shared assets path
$shared_assets = $document_root;

?>';
}

// Show what the fixed config would look like
echo "<h3>Proposed Fixed Configuration:</h3>";
$fixed_config = generateFixedConfig();
echo "<pre style='background: #f5f5f5; padding: 15px; border-radius: 5px; overflow-x: auto;'>";
echo htmlspecialchars($fixed_config);
echo "</pre>";

// Apply fix if requested
if (isset($_POST['apply_fix'])) {
    // Backup current config
    $backup_file = $web_root . '/config.php.backup.' . date('Y-m-d-H-i-s');
    if (copy($config_file, $backup_file)) {
        echo "<div style='color: green;'>✓ Backup created: " . basename($backup_file) . "</div>";
    }
    
    // Write fixed config
    if (file_put_contents($config_file, $fixed_config)) {
        echo "<div style='color: green;'><strong>✓ Configuration fixed successfully!</strong></div>";
        echo "<p>Your application should now work correctly at: <a href='" . dirname($_SERVER['REQUEST_URI']) . "/../'>" . dirname($_SERVER['REQUEST_URI']) . "/../</a></p>";
    } else {
        echo "<div style='color: red;'><strong>✗ Failed to write configuration file</strong></div>";
    }
}

?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; max-width: 1000px; }
h2, h3 { color: #333; }
ul { background: #f9f9f9; padding: 15px; border-radius: 5px; }
pre { font-size: 12px; }
.button { 
    background: #007cba; 
    color: white; 
    padding: 10px 20px; 
    border: none; 
    border-radius: 5px; 
    cursor: pointer; 
    font-size: 16px;
}
.button:hover { background: #005a87; }
.warning { 
    background: #fff3cd; 
    border: 1px solid #ffeaa7; 
    padding: 15px; 
    border-radius: 5px; 
    margin: 15px 0; 
}
</style>

<div class="warning">
<strong>⚠️ Warning:</strong> This will modify your config.php file. A backup will be created automatically.
</div>

<form method="post">
    <button type="submit" name="apply_fix" class="button">Apply Configuration Fix</button>
</form>

<h3>Manual Fix Instructions:</h3>
<p>If you prefer to fix manually, update your <code>config.php</code> file:</p>
<ol>
    <li>Replace hardcoded paths with dynamic path detection</li>
    <li>Ensure <code>$path</code> variable points to your web root, not subfolder</li>
    <li>Update <code>$base_dir</code> to point to actual web root directory</li>
</ol>
