<?php
/**
 * Kovil App - Configuration Template
 * 
 * Copy this file to config.php and update the values below
 * 
 * SECURITY NOTE: Never commit config.php with real credentials to version control
 */

global $db_host, $db_name, $db_user, $db_pass, $con, $path, $base_path;

// =============================================================================
// DATABASE CONFIGURATION
// =============================================================================

// Database credentials (connection will be established in init.php)
$db_host = 'localhost';                    // Database host (usually localhost)
$db_name = 'kovil';                        // Database name
$db_user = 'root';                         // Database username
$db_pass = '';                             // Database password

// =============================================================================
// TABLE DEFINITIONS
// =============================================================================

// Core tables
$tbl_users = 'users';                      // User accounts table
$tbl_family = 'family';                    // Family/member records table
$tbl_child = 'child';                      // Children records table
$tbl_event = 'event';                      // Events table
$tbl_matrimony = 'matrimony';              // Matrimony profiles table
$tbl_kattam = 'kattam';                    // Kattam/categories table
$tbl_attachments = 'attachments';          // File attachments table
$tbl_book = 'book';                        // Books/records table
$tbl_receipt = 'receipt';                  // Receipt records table
$tbl_labels = 'labels';                    // Labels/categories table
$tbl_donation = 'donation';                // Donation records table
$tbl_horoscope = 'horoscope';              // Horoscope data table
$tbl_subscription = 'subscription';         // Subscription records table

// Subscription Module Tables (New in Modern Version)
$tbl_subscription_events = 'subscription_events';      // Subscription events
$tbl_receipt_books = 'receipt_books';                  // Receipt books
$tbl_member_subscriptions = 'member_subscriptions';    // Member subscriptions
$tbl_receipt_details = 'receipt_details';              // Receipt details

// =============================================================================
// PATH CONFIGURATION
// =============================================================================

// Dynamically determine the protocol (HTTP/HTTPS)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

// Determine the application root path
// This works by finding the path from document root to the config.php location
$doc_root = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
$config_dir = dirname(__FILE__);
$app_path = str_replace($doc_root, '', $config_dir);
$app_path = str_replace('\\', '/', $app_path); // Normalize for Windows

// Base URL path for the application (always points to root)
// This ensures menu links work correctly from any subdirectory
$path = $protocol . '://' . $_SERVER['SERVER_NAME'] . $app_path;

// Base directory for file operations
$base_dir = dirname(__FILE__);

// =============================================================================
// APPLICATION SETTINGS
// =============================================================================

// Application settings (you can add more as needed)
define('APP_NAME', 'Kovil App');
define('APP_VERSION', '1.0.0');
define('APP_TIMEZONE', 'Asia/Kolkata');

// File upload settings
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024);  // 10MB in bytes
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);
define('ALLOWED_DOCUMENT_TYPES', ['pdf', 'doc', 'docx']);

// Session settings
define('SESSION_TIMEOUT', 3600);  // 1 hour in seconds

// =============================================================================
// ENVIRONMENT SPECIFIC SETTINGS
// =============================================================================

// Set timezone
if (defined('APP_TIMEZONE')) {
    date_default_timezone_set(APP_TIMEZONE);
}

// Error reporting (set to 0 in production)
if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') {
    // Development environment
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    define('DEBUG_MODE', true);
} else {
    // Production environment
    error_reporting(0);
    ini_set('display_errors', 0);
    define('DEBUG_MODE', false);
}

// =============================================================================
// SECURITY SETTINGS
// =============================================================================

// Security headers (optional - can also be set in web server config)
if (!headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    
    // Only set HTTPS headers if using HTTPS
    if ($protocol === 'https') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
}

// =============================================================================
// CUSTOM CONFIGURATION
// =============================================================================

// Add any custom configuration variables here
// Example:
// $temple_name = 'Your Temple Name';
// $temple_address = 'Your Temple Address';
// $contact_email = 'admin@yourtemple.com';
// $contact_phone = '+91-XXXXXXXXXX';

?>
