<?php
/**
 * Kovil App - Modern Version Installation Script
 * Packaged Installation Version
 * 
 * This script will help you install and configure the Kovil App Modern Version
 * 
 * Requirements:
 * - PHP 7.4 or higher
 * - MySQL 5.7 or higher / MariaDB 10.2 or higher
 * - Apache/Nginx web server
 * - PHP Extensions: mysqli, gd, mbstring, json
 * 
 * @version 1.0
 * @author Kovil App Team
 */

// Prevent direct access if not in CLI mode
if (php_sapi_name() !== 'cli' && !isset($_GET['install'])) {
    die('This installation script should be run from command line or accessed with ?install parameter');
}

// Set error reporting for installation
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);

// Installation configuration
$config = [
    'app_name' => 'Kovil App - Modern Version',
    'version' => '1.0.0',
    'min_php_version' => '7.4.0',
    'required_extensions' => ['mysqli', 'gd', 'mbstring', 'json'],
    'required_directories' => [
        'modern/images/member',
        'modern/attachments',
        'modern/assets/uploads'
    ],
    'database_schema' => 'kovil.sql'
];

class KovilAppInstaller {
    private $config;
    private $errors = [];
    private $warnings = [];
    private $success_messages = [];
    private $install_path;
    private $web_root;
    
    public function __construct($config) {
        $this->config = $config;
        $this->install_path = dirname(__FILE__);
        
        // Determine web root more accurately
        $document_root = $_SERVER['DOCUMENT_ROOT'] ?? '';
        $script_dir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
        
        // Calculate web root based on script path
        if (!empty($document_root) && !empty($script_dir)) {
            // Remove installer directory from the path to get web root
            $installer_dir = basename($this->install_path);
            if (strpos($script_dir, $installer_dir) !== false) {
                $web_root_path = str_replace('/' . $installer_dir, '', $script_dir);
                $this->web_root = $document_root . $web_root_path;
            } else {
                $this->web_root = $document_root . $script_dir;
            }
        } else {
            // Fallback: assume parent directory
            $this->web_root = dirname($this->install_path);
        }
        
        // Ensure web root exists and is writable
        if (!is_dir($this->web_root)) {
            $this->web_root = dirname($this->install_path);
        }
    }
    
    public function run() {
        $this->printHeader();
        
        // Step 1: Check system requirements
        $this->checkSystemRequirements();
        
        // Step 2: Check file permissions
        $this->checkFilePermissions();
        
        // Step 3: Create directories
        $this->createDirectories();
        
        // Step 4: Database setup
        $this->setupDatabase();
        
        // Step 5: Set up default data (move files first)
        $this->setupDefaultData();
        
        // Step 6: Create configuration files (after files are moved)
        $this->createConfigFiles();
        
        // Step 7: Final checks
        $this->finalChecks();
        
        // Display results
        $this->displayResults();
    }
    
    private function printHeader() {
        echo "\n";
        echo "========================================\n";
        echo "  {$this->config['app_name']}\n";
        echo "  Installation Script v{$this->config['version']}\n";
        echo "  Packaged Installation\n";
        echo "========================================\n\n";
    }
    
    private function checkSystemRequirements() {
        echo "Checking system requirements...\n";
        
        // Check PHP version
        if (version_compare(PHP_VERSION, $this->config['min_php_version'], '<')) {
            $this->errors[] = "PHP version {$this->config['min_php_version']} or higher is required. Current version: " . PHP_VERSION;
        } else {
            $this->success_messages[] = "PHP version check passed: " . PHP_VERSION;
        }
        
        // Check required extensions
        foreach ($this->config['required_extensions'] as $extension) {
            if (!extension_loaded($extension)) {
                $this->errors[] = "Required PHP extension '{$extension}' is not loaded";
            } else {
                $this->success_messages[] = "PHP extension '{$extension}' is available";
            }
        }
        
        // Check web server
        $server_software = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';
        if (strpos($server_software, 'Apache') !== false || strpos($server_software, 'nginx') !== false) {
            $this->success_messages[] = "Web server detected: {$server_software}";
        } else {
            $this->warnings[] = "Web server not recognized: {$server_software}";
        }
        
        // Check if kovil.sql exists
        if (file_exists($this->install_path . '/kovil.sql')) {
            $this->success_messages[] = "Database schema file found: kovil.sql";
        } else {
            $this->errors[] = "Database schema file not found: kovil.sql";
        }
    }
    
    private function checkFilePermissions() {
        echo "Checking file permissions...\n";
        
        // Check if we can write to the installation directory
        if (!is_writable($this->install_path)) {
            $this->errors[] = "Installation directory is not writable: {$this->install_path}";
        } else {
            $this->success_messages[] = "Installation directory is writable";
        }
        
        // Check specific directories
        $check_dirs = [
            'modern',
            'modern/images',
            'modern/attachments'
        ];
        
        foreach ($check_dirs as $dir) {
            $full_path = $this->install_path . '/' . $dir;
            if (file_exists($full_path)) {
                if (!is_writable($full_path)) {
                    $this->errors[] = "Directory is not writable: {$full_path}";
                } else {
                    $this->success_messages[] = "Directory is writable: {$dir}";
                }
            }
        }
    }
    
    private function createDirectories() {
        echo "Creating required directories...\n";
        
        foreach ($this->config['required_directories'] as $dir) {
            $full_path = $this->install_path . '/' . $dir;
            
            if (!file_exists($full_path)) {
                if (mkdir($full_path, 0755, true)) {
                    $this->success_messages[] = "Created directory: {$dir}";
                } else {
                    $this->errors[] = "Failed to create directory: {$dir}";
                }
            } else {
                $this->success_messages[] = "Directory already exists: {$dir}";
            }
        }
    }
    
    private function setupDatabase() {
        echo "Setting up database...\n";
        
        // Get database credentials from user input
        $db_config = $this->getDatabaseConfig();
        
        if (!$db_config) {
            $this->errors[] = "Database configuration failed";
            return;
        }
        
        // Test database connection
        try {
            $connection = new mysqli(
                $db_config['host'],
                $db_config['username'],
                $db_config['password']
            );
            
            if ($connection->connect_error) {
                $this->errors[] = "Database connection failed: " . $connection->connect_error;
                return;
            }
            
            $this->success_messages[] = "Database connection successful";
            
            // Create database if it doesn't exist
            $db_name = $connection->real_escape_string($db_config['database']);
            $sql = "CREATE DATABASE IF NOT EXISTS `{$db_name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
            if ($connection->query($sql)) {
                $this->success_messages[] = "Database '{$db_name}' created/verified";
            } else {
                $this->errors[] = "Failed to create database: " . $connection->error;
                return;
            }
            
            // Select the database
            $connection->select_db($db_name);
            
            // Set charset
            $connection->set_charset('utf8mb4');
            
            // Store database config for later use (before importing schema)
            $this->db_config = $db_config;
            
            // Import database schema
            $this->importDatabaseSchema($connection);
            
            $connection->close();
            
        } catch (Exception $e) {
            $this->errors[] = "Database setup error: " . $e->getMessage();
        }
    }
    
    private function getDatabaseConfig() {
        if (php_sapi_name() === 'cli') {
            // CLI mode - get input from user
            echo "\nDatabase Configuration:\n";
            echo "Enter database host [localhost]: ";
            $host = trim(fgets(STDIN)) ?: 'localhost';
            
            echo "Enter database name [kovil]: ";
            $database = trim(fgets(STDIN)) ?: 'kovil';
            
            echo "Enter database username [root]: ";
            $username = trim(fgets(STDIN)) ?: 'root';
            
            echo "Enter database password: ";
            $password = trim(fgets(STDIN));
            
        } else {
            // Web mode - use form input or defaults
            $host = $_POST['db_host'] ?? 'localhost';
            $database = $_POST['db_name'] ?? 'kovil';
            $username = $_POST['db_user'] ?? 'root';
            $password = $_POST['db_pass'] ?? '';
        }
        
        return [
            'host' => $host,
            'database' => $database,
            'username' => $username,
            'password' => $password
        ];
    }
    
    private function importDatabaseSchema($connection) {
        echo "Importing database schema...\n";
        
        $schema_file = $this->install_path . '/kovil.sql';
        
        if (!file_exists($schema_file)) {
            $this->errors[] = "Database schema file not found: {$schema_file}";
            return;
        }
        
        $sql_content = file_get_contents($schema_file);
        if (!$sql_content) {
            $this->errors[] = "Failed to read database schema file";
            return;
        }
        
        // Clean up SQL content - remove comments and split properly
        $lines = explode("\n", $sql_content);
        $clean_sql = '';
        
        foreach ($lines as $line) {
            $line = trim($line);
            // Skip empty lines and comment lines
            if (empty($line) || preg_match('/^--/', $line) || preg_match('/^\/\*/', $line) || preg_match('/^\*/', $line) || preg_match('/^#/', $line)) {
                continue;
            }
            // Skip MySQL-specific directives
            if (preg_match('/^\/\*!.*?\*\//', $line) || preg_match('/^SET /', $line) || preg_match('/^START TRANSACTION/', $line) || preg_match('/^COMMIT/', $line)) {
                continue;
            }
            $clean_sql .= $line . "\n";
        }
        
        // Split by semicolon and execute each statement
        $statements = array_filter(array_map('trim', explode(';', $clean_sql)));
        
        $success_count = 0;
        $error_count = 0;
        
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                // Remove any remaining MySQL-specific comments
                $statement = preg_replace('/\/\*.*?\*\//s', '', $statement);
                $statement = trim($statement);
                
                if (!empty($statement)) {
                    if ($connection->query($statement)) {
                        $success_count++;
                    } else {
                        $error_count++;
                        $this->warnings[] = "SQL Warning: " . $connection->error . " (Statement: " . substr($statement, 0, 100) . "...)";
                    }
                }
            }
        }
        
        if ($success_count > 0) {
            $this->success_messages[] = "Database schema imported successfully ({$success_count} statements executed)";
        }
        
        if ($error_count > 0) {
            $this->warnings[] = "Some SQL statements failed ({$error_count} errors)";
        }
        
        // Verify that critical tables were created
        $critical_tables = ['users', 'family', 'child', 'labels'];
        $missing_tables = [];
        
        foreach ($critical_tables as $table) {
            $result = $connection->query("SHOW TABLES LIKE '{$table}'");
            if (!$result || $result->num_rows == 0) {
                $missing_tables[] = $table;
            }
        }
        
        if (empty($missing_tables)) {
            $this->success_messages[] = "All critical tables verified successfully";
            // Create default admin user
            $this->createDefaultUser($connection);
        } else {
            $this->errors[] = "Missing tables after schema import: " . implode(', ', $missing_tables);
            
            // Show all existing tables for debugging
            $result = $connection->query("SHOW TABLES");
            $existing_tables = [];
            if ($result) {
                while ($row = $result->fetch_array()) {
                    $existing_tables[] = $row[0];
                }
            }
            $this->warnings[] = "Existing tables: " . (empty($existing_tables) ? 'None' : implode(', ', $existing_tables));
        }
    }
    
    private function createDefaultUser($connection) {
        // Create default admin user - match the actual users table schema
        $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
        $creation_date = date('Y-m-d H:i:s');
        $sql = "INSERT IGNORE INTO `users` (`username`, `password`, `email`, `role`, `profile_id`, `creation_date`, `created_by`, `u_image`, `mobile_no`) 
                VALUES ('admin', '{$admin_password}', 'admin@kovilapp.com', 'admin', '1', '{$creation_date}', 'system', '', '')";
        
        if ($connection->query($sql)) {
            $this->success_messages[] = "Created default admin user (username: admin, password: admin123)";
        } else {
            $this->warnings[] = "Could not create default admin user: " . $connection->error;
        }
    }
    
    private function createConfigFiles() {
        echo "Creating configuration files...\n";
        
        if (!isset($this->db_config)) {
            $this->errors[] = "Database configuration not available for config file creation";
            return;
        }
        
        // Update config.php with user's database settings
        $config_content = $this->generateConfigFile();
        $config_path = $this->web_root . '/config.php';
        
        if (file_put_contents($config_path, $config_content)) {
            $this->success_messages[] = "Updated configuration file: config.php";
        } else {
            $this->errors[] = "Failed to update configuration file";
        }
        
        // Create .htaccess for security in web root
        $htaccess_content = $this->generateHtaccessFile();
        $htaccess_path = $this->web_root . '/.htaccess';
        
        if (file_put_contents($htaccess_path, $htaccess_content)) {
            $this->success_messages[] = "Created .htaccess file in web root";
        } else {
            $this->warnings[] = "Could not create .htaccess file";
        }
    }
    
    private function generateConfigFile() {
        $db = $this->db_config;
        
        return '<?php

global $db_host, $db_name, $db_user, $db_pass, $con, $path, $base_path;

// Database credentials (connection will be established in init.php)
$db_host = \'' . addslashes($db['host']) . '\';
$db_name = \'' . addslashes($db['database']) . '\';
$db_user = \'' . addslashes($db['username']) . '\';
$db_pass = \'' . addslashes($db['password']) . '\';

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
    
    private function generateHtaccessFile() {
        return '# Kovil App Security Settings
RewriteEngine On

# Prevent access to sensitive files
<Files "config.php">
    Order Allow,Deny
    Deny from all
</Files>

<Files "*.sql">
    Order Allow,Deny
    Deny from all
</Files>

<Files "install.php">
    Order Allow,Deny
    Deny from all
</Files>

<Files "check_requirements.php">
    Order Allow,Deny
    Deny from all
</Files>

# Enable compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>

# Set cache headers
<IfModule mod_expires.c>
    ExpiresActive on
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
</IfModule>';
    }
    
    private function moveFilesToRoot() {
        echo "Moving application files to web root...\n";
        
        $source_dir = $this->install_path . '/modern';
        
        if (!is_dir($source_dir)) {
            $this->errors[] = "Source directory not found: {$source_dir}";
            return;
        }
        
        // Create target directories if they don't exist
        $target_dirs = [
            'images', 'images/member', 'attachments', 'assets', 'assets/css', 'assets/js',
            'includes', 'member', 'matrimony', 'donation', 'subscription', 'label', 'user', 'settings'
        ];
        
        foreach ($target_dirs as $dir) {
            $target_path = $this->web_root . '/' . $dir;
            if (!is_dir($target_path)) {
                if (!mkdir($target_path, 0755, true)) {
                    $this->errors[] = "Failed to create directory: {$target_path}";
                    return;
                }
            }
        }
        
        // Copy all files from modern/ to web root
        if ($this->recursiveCopy($source_dir, $this->web_root)) {
            $this->success_messages[] = "Application files moved to web root successfully";
        } else {
            $this->errors[] = "Failed to move application files to web root";
        }
    }
    
    private function recursiveCopy($source, $destination) {
        if (!is_dir($source)) {
            return false;
        }
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $item) {
            $target = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
            
            if ($item->isDir()) {
                if (!is_dir($target)) {
                    mkdir($target, 0755, true);
                }
            } else {
                // Skip config.php - it will be generated separately with correct database settings
                if (basename($target) === 'config.php') {
                    continue;
                }
                
                // Skip if file already exists and is newer
                if (file_exists($target) && filemtime($target) >= filemtime($item)) {
                    continue;
                }
                
                if (!copy($item, $target)) {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    private function setupDefaultData() {
        echo "Setting up default data...\n";
        
        // Move application files to web root
        $this->moveFilesToRoot();
        
        // Copy default images if they don't exist
        $default_image_target = $this->web_root . '/images/default.png';
        
        if (!file_exists($default_image_target)) {
            // Create a simple default image placeholder
            $this->createDefaultImage($default_image_target);
        }
        
        $this->success_messages[] = "Default data setup completed";
    }
    
    private function createDefaultImage($path) {
        // Create a simple 200x200 default image
        if (extension_loaded('gd')) {
            $image = imagecreatetruecolor(200, 200);
            $bg_color = imagecolorallocate($image, 240, 240, 240);
            $text_color = imagecolorallocate($image, 100, 100, 100);
            
            imagefill($image, 0, 0, $bg_color);
            
            $text = 'No Image';
            $font_size = 3;
            $text_width = imagefontwidth($font_size) * strlen($text);
            $text_height = imagefontheight($font_size);
            
            $x = (200 - $text_width) / 2;
            $y = (200 - $text_height) / 2;
            
            imagestring($image, $font_size, $x, $y, $text, $text_color);
            
            imagepng($image, $path);
            imagedestroy($image);
            
            $this->success_messages[] = "Created default image placeholder";
        }
    }
    
    private function finalChecks() {
        echo "Performing final checks...\n";
        
        // Check if index.php exists in web root
        if (file_exists($this->web_root . '/index.php')) {
            $this->success_messages[] = "Application entry point found";
        } else {
            $this->errors[] = "Application entry point not found: index.php";
        }
        
        // Check if configuration is readable
        $config_path = $this->web_root . '/config.php';
        if (file_exists($config_path)) {
            if (is_readable($config_path)) {
                $this->success_messages[] = "Configuration file is readable";
            } else {
                $this->errors[] = "Configuration file exists but is not readable (check permissions)";
            }
        } else {
            $this->errors[] = "Configuration file was not created";
        }
    }
    
    private function displayResults() {
        echo "\n========================================\n";
        echo "  INSTALLATION RESULTS\n";
        echo "========================================\n\n";
        
        if (!empty($this->success_messages)) {
            echo "✓ SUCCESS MESSAGES:\n";
            foreach ($this->success_messages as $message) {
                echo "  ✓ {$message}\n";
            }
            echo "\n";
        }
        
        if (!empty($this->warnings)) {
            echo "⚠ WARNINGS:\n";
            foreach ($this->warnings as $warning) {
                echo "  ⚠ {$warning}\n";
            }
            echo "\n";
        }
        
        if (!empty($this->errors)) {
            echo "✗ ERRORS:\n";
            foreach ($this->errors as $error) {
                echo "  ✗ {$error}\n";
            }
            echo "\n";
        }
        
        if (empty($this->errors)) {
            echo "🎉 INSTALLATION COMPLETED SUCCESSFULLY!\n\n";
            echo "Next steps:\n";
            echo "1. Navigate to: " . $this->getApplicationUrl() . "\n";
            echo "2. Login with: admin / admin123\n";
            echo "3. Change the default password immediately\n";
            echo "4. Configure your application settings\n";
            echo "5. Start adding members and data\n\n";
            echo "For security, please delete or rename install.php and kovil.sql files.\n";
        } else {
            echo "❌ INSTALLATION FAILED!\n\n";
            echo "Please fix the errors above and run the installation again.\n";
        }
        
        echo "\n========================================\n";
    }
    
    private function getApplicationUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['SERVER_NAME'] ?? 'yourdomain.com';
        $script_dir = dirname($_SERVER['SCRIPT_NAME'] ?? '/kovilapp-installer');
        
        // Remove installer directory from path to get web root URL
        $installer_dir = basename($this->install_path);
        if (strpos($script_dir, $installer_dir) !== false) {
            $app_dir = str_replace('/' . $installer_dir, '', $script_dir);
        } else {
            $app_dir = dirname($script_dir);
        }
        
        if ($app_dir === '/' || $app_dir === '\\' || empty($app_dir)) {
            $app_dir = '';
        }
        
        return $protocol . '://' . $host . $app_dir . '/';
    }
}

// Web interface for installation
if (php_sapi_name() !== 'cli' && isset($_GET['install'])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kovil App - Installation</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
        <style>
            body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
            .install-card { box-shadow: 0 10px 30px rgba(0,0,0,0.3); border: none; }
            .card-header { background: linear-gradient(135deg, #5a7ae0 0%, #3355c4 100%); }
            .btn-primary { background: linear-gradient(135deg, #5a7ae0 0%, #3355c4 100%); border: none; }
            .btn-primary:hover { background: linear-gradient(135deg, #4a6ad0 0%, #2345b4 100%); }
        </style>
    </head>
    <body>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card install-card">
                        <div class="card-header text-white">
                            <h3 class="mb-0">
                                <i class="bi bi-gear-fill"></i> 
                                Kovil App - Installation
                            </h3>
                            <small>Modern Temple Management System</small>
                        </div>
                        <div class="card-body">
                            <?php if (!isset($_POST['install'])): ?>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i>
                                <strong>Welcome!</strong> This installer will set up your Kovil App installation.
                                Please provide your database details below.
                            </div>
                            
                            <form method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="db_host" class="form-label">
                                                <i class="bi bi-server"></i> Database Host
                                            </label>
                                            <input type="text" class="form-control" id="db_host" name="db_host" value="localhost" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="db_name" class="form-label">
                                                <i class="bi bi-database"></i> Database Name
                                            </label>
                                            <input type="text" class="form-control" id="db_name" name="db_name" value="kovil" required>
                                            <small class="form-text text-muted">Use your actual database name (e.g., koil_kovilapp if that's what you created)</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="db_user" class="form-label">
                                                <i class="bi bi-person"></i> Database Username
                                            </label>
                                            <input type="text" class="form-control" id="db_user" name="db_user" value="root" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="db_pass" class="form-label">
                                                <i class="bi bi-key"></i> Database Password
                                            </label>
                                            <input type="password" class="form-control" id="db_pass" name="db_pass" placeholder="Leave empty if no password">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    <strong>Note:</strong> The installer will create the database if it doesn't exist.
                                    Make sure your database user has CREATE privileges.
                                </div>
                                
                                <div class="text-center">
                                    <button type="submit" name="install" class="btn btn-primary btn-lg">
                                        <i class="bi bi-play-fill"></i> Install Kovil App
                                    </button>
                                </div>
                            </form>
                            <?php else: ?>
                            <div class="alert alert-info">
                                <i class="bi bi-gear"></i> Installation in progress...
                            </div>
                            <pre style="background: #f8f9fa; padding: 15px; border-radius: 5px; max-height: 500px; overflow-y: auto; font-size: 12px;">
                            <?php
                            ob_start();
                            $installer = new KovilAppInstaller($config);
                            $installer->run();
                            $output = ob_get_clean();
                            echo htmlspecialchars($output);
                            ?>
                            </pre>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Run installation
if (php_sapi_name() === 'cli' || isset($_POST['install'])) {
    $installer = new KovilAppInstaller($config);
    $installer->run();
}
?>
