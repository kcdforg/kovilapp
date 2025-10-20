<?php
/**
 * Kovil App - Modern Version Installation Script
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

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Installation configuration
$config = [
    'app_name' => 'Kovil App - Modern Version',
    'version' => '1.0.0',
    'min_php_version' => '7.4.0',
    'required_extensions' => ['mysqli', 'gd', 'mbstring', 'json'],
    'required_directories' => [
        'modern/images/member',
        'modern/attachments',
        'modern/assets/uploads',
        'current/images/member',
        'current/attachments'
    ],
    'database_tables' => [
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
        'subscription_events',
        'receipt_books',
        'member_subscriptions',
        'receipt_details'
    ]
];

class KovilAppInstaller {
    private $config;
    private $errors = [];
    private $warnings = [];
    private $success_messages = [];
    
    public function __construct($config) {
        $this->config = $config;
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
        
        // Step 5: Create configuration files
        $this->createConfigFiles();
        
        // Step 6: Set up default data
        $this->setupDefaultData();
        
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
    }
    
    private function checkFilePermissions() {
        echo "Checking file permissions...\n";
        
        $base_dir = dirname(__FILE__);
        
        // Check if we can write to the base directory
        if (!is_writable($base_dir)) {
            $this->errors[] = "Base directory is not writable: {$base_dir}";
        } else {
            $this->success_messages[] = "Base directory is writable";
        }
        
        // Check specific directories
        $check_dirs = [
            'modern',
            'modern/images',
            'modern/attachments',
            'current/images'
        ];
        
        foreach ($check_dirs as $dir) {
            $full_path = $base_dir . '/' . $dir;
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
        
        $base_dir = dirname(__FILE__);
        
        foreach ($this->config['required_directories'] as $dir) {
            $full_path = $base_dir . '/' . $dir;
            
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
                $db_config['password'],
                $db_config['database']
            );
            
            if ($connection->connect_error) {
                $this->errors[] = "Database connection failed: " . $connection->connect_error;
                return;
            }
            
            $this->success_messages[] = "Database connection successful";
            
            // Set charset
            $connection->set_charset('utf8mb4');
            
            // Create database tables
            $this->createDatabaseTables($connection);
            
            // Import sample data
            $this->importSampleData($connection);
            
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
            // Web mode - use default values or form input
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
    
    private function createDatabaseTables($connection) {
        echo "Creating database tables...\n";
        
        // Read and execute SQL files
        $sql_files = [
            'modern/subscription_database.sql',
            'modern/add_address_column.sql',
            'modern/add_book_type_column.sql'
        ];
        
        foreach ($sql_files as $sql_file) {
            if (file_exists($sql_file)) {
                $sql_content = file_get_contents($sql_file);
                if ($sql_content) {
                    // Split by semicolon and execute each statement
                    $statements = array_filter(array_map('trim', explode(';', $sql_content)));
                    
                    foreach ($statements as $statement) {
                        if (!empty($statement) && !preg_match('/^--/', $statement)) {
                            if ($connection->query($statement)) {
                                // Success - don't log every statement
                            } else {
                                $this->warnings[] = "SQL Warning in {$sql_file}: " . $connection->error;
                            }
                        }
                    }
                    $this->success_messages[] = "Executed SQL file: {$sql_file}";
                }
            }
        }
        
        // Create basic tables if they don't exist
        $this->createBasicTables($connection);
    }
    
    private function createBasicTables($connection) {
        // Users table
        $sql = "CREATE TABLE IF NOT EXISTS `users` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `username` varchar(50) NOT NULL UNIQUE,
            `password` varchar(255) NOT NULL,
            `email` varchar(100),
            `full_name` varchar(100),
            `role` enum('admin', 'user') DEFAULT 'user',
            `status` enum('active', 'inactive') DEFAULT 'active',
            `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        if ($connection->query($sql)) {
            $this->success_messages[] = "Created users table";
        }
        
        // Family table
        $sql = "CREATE TABLE IF NOT EXISTS `family` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `member_id` varchar(20),
            `name` varchar(100) NOT NULL,
            `w_name` varchar(100),
            `mobile_no` varchar(15),
            `email` varchar(100),
            `village` varchar(100),
            `taluk` varchar(100),
            `district` varchar(100),
            `state` varchar(100),
            `pincode` varchar(10),
            `c_village` varchar(100),
            `c_taluk` varchar(100),
            `c_district` varchar(100),
            `c_state` varchar(100),
            `c_pincode` varchar(10),
            `permanent_address` text,
            `current_address` text,
            `kattalai` int(11),
            `image` varchar(255),
            `w_image` varchar(255),
            `admin_notes` text,
            `family_name` varchar(100),
            `created_by` varchar(50),
            `created_date` timestamp DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `deleted` tinyint(1) DEFAULT 0,
            PRIMARY KEY (`id`),
            KEY `idx_member_id` (`member_id`),
            KEY `idx_name` (`name`),
            KEY `idx_mobile` (`mobile_no`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        if ($connection->query($sql)) {
            $this->success_messages[] = "Created family table";
        }
        
        // Labels table
        $sql = "CREATE TABLE IF NOT EXISTS `labels` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `display_name` varchar(100) NOT NULL,
            `slug` varchar(100),
            `type` int(11) DEFAULT 0,
            `order` int(11) DEFAULT 0,
            `status` enum('active', 'inactive') DEFAULT 'active',
            `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_type` (`type`),
            KEY `idx_slug` (`slug`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        if ($connection->query($sql)) {
            $this->success_messages[] = "Created labels table";
        }
    }
    
    private function importSampleData($connection) {
        echo "Importing sample data...\n";
        
        // Create default admin user
        $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
        $sql = "INSERT IGNORE INTO `users` (`username`, `password`, `email`, `full_name`, `role`, `status`) 
                VALUES ('admin', '{$admin_password}', 'admin@kovilapp.com', 'System Administrator', 'admin', 'active')";
        
        if ($connection->query($sql)) {
            $this->success_messages[] = "Created default admin user (username: admin, password: admin123)";
        }
        
        // Create sample labels
        $labels = [
            ['display_name' => 'Kattalai', 'slug' => 'kattalai', 'type' => 0],
            ['display_name' => 'Education', 'slug' => 'education', 'type' => 0],
            ['display_name' => 'Occupation', 'slug' => 'occupation', 'type' => 0]
        ];
        
        foreach ($labels as $label) {
            $sql = "INSERT IGNORE INTO `labels` (`display_name`, `slug`, `type`) 
                    VALUES ('{$label['display_name']}', '{$label['slug']}', {$label['type']})";
            $connection->query($sql);
        }
        
        $this->success_messages[] = "Created sample label categories";
    }
    
    private function createConfigFiles() {
        echo "Creating configuration files...\n";
        
        // Update config.php with user's database settings
        $config_content = $this->generateConfigFile();
        
        if (file_put_contents('modern/config.php', $config_content)) {
            $this->success_messages[] = "Updated configuration file";
        } else {
            $this->errors[] = "Failed to update configuration file";
        }
        
        // Create .htaccess for security
        $htaccess_content = $this->generateHtaccessFile();
        
        if (file_put_contents('.htaccess', $htaccess_content)) {
            $this->success_messages[] = "Created .htaccess file";
        } else {
            $this->warnings[] = "Could not create .htaccess file";
        }
    }
    
    private function generateConfigFile() {
        return '<?php

global $db_host, $db_name, $db_user, $db_pass, $con, $path, $base_path;

// Database credentials (connection will be established in init.php)
$db_host = \'localhost\';
$db_name = \'kovil\';
$db_user = \'root\';
$db_pass = \'\';

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

// Dynamically determine the base path for assets
$script_name = dirname($_SERVER[\'SCRIPT_NAME\']);
$script_name = rtrim($script_name, \'/\\\\\');

// Dynamically determine the protocol
$protocol = (!empty($_SERVER[\'HTTPS\']) && $_SERVER[\'HTTPS\'] !== \'off\') ? \'https\' : \'http\';

// Base path for the modern version
$path = $protocol . \'://\' . $_SERVER[\'SERVER_NAME\'] . \'/kovilapp/modern\';

// Base directory for file operations
$base_dir = $_SERVER[\'DOCUMENT_ROOT\'] . \'/kovilapp\';

// Paths for different versions
$current_path = $base_dir . \'/current\';
$modern_path = $base_dir . \'/modern\';

// Shared assets path (if needed)
$shared_assets = $base_dir . \'/current\';

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
    
    private function setupDefaultData() {
        echo "Setting up default data...\n";
        
        // Copy default images if they don't exist
        $default_image_source = 'current/images/default.png';
        $default_image_target = 'modern/images/default.png';
        
        if (file_exists($default_image_source) && !file_exists($default_image_target)) {
            if (copy($default_image_source, $default_image_target)) {
                $this->success_messages[] = "Copied default image";
            }
        }
        
        $this->success_messages[] = "Default data setup completed";
    }
    
    private function finalChecks() {
        echo "Performing final checks...\n";
        
        // Check if modern/index.php exists
        if (file_exists('modern/index.php')) {
            $this->success_messages[] = "Application entry point found";
        } else {
            $this->errors[] = "Application entry point not found: modern/index.php";
        }
        
        // Check if configuration is readable
        if (is_readable('modern/config.php')) {
            $this->success_messages[] = "Configuration file is readable";
        } else {
            $this->errors[] = "Configuration file is not readable";
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
            echo "1. Navigate to: http://yourdomain.com/kovilapp/modern/\n";
            echo "2. Login with: admin / admin123\n";
            echo "3. Change the default password\n";
            echo "4. Configure your application settings\n";
            echo "5. Start adding members and data\n\n";
            echo "For security, please delete or rename this install.php file.\n";
        } else {
            echo "❌ INSTALLATION FAILED!\n\n";
            echo "Please fix the errors above and run the installation again.\n";
        }
        
        echo "\n========================================\n";
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
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">Kovil App - Installation</h3>
                        </div>
                        <div class="card-body">
                            <?php if (!isset($_POST['install'])): ?>
                            <form method="post">
                                <div class="mb-3">
                                    <label for="db_host" class="form-label">Database Host</label>
                                    <input type="text" class="form-control" id="db_host" name="db_host" value="localhost" required>
                                </div>
                                <div class="mb-3">
                                    <label for="db_name" class="form-label">Database Name</label>
                                    <input type="text" class="form-control" id="db_name" name="db_name" value="kovil" required>
                                </div>
                                <div class="mb-3">
                                    <label for="db_user" class="form-label">Database Username</label>
                                    <input type="text" class="form-control" id="db_user" name="db_user" value="root" required>
                                </div>
                                <div class="mb-3">
                                    <label for="db_pass" class="form-label">Database Password</label>
                                    <input type="password" class="form-control" id="db_pass" name="db_pass">
                                </div>
                                <button type="submit" name="install" class="btn btn-primary">Install Kovil App</button>
                            </form>
                            <?php else: ?>
                            <pre style="background: #f8f9fa; padding: 15px; border-radius: 5px; max-height: 500px; overflow-y: auto;">
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
