<?php
/**
 * Kovil App - Configuration Template
 * 
 * Copy this file to modern/config.php and update the values below
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

// Dynamically determine the base path for assets
$script_name = dirname($_SERVER['SCRIPT_NAME']);
$script_name = rtrim($script_name, '/\\');

// Dynamically determine the protocol (HTTP/HTTPS)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

// Base URL for the modern version
// Update this if your application is in a different directory
$path = $protocol . '://' . $_SERVER['SERVER_NAME'] . '/kovilapp/modern';

// Base directory for file operations
$base_dir = $_SERVER['DOCUMENT_ROOT'] . '/kovilapp';

// Paths for different versions
$current_path = $base_dir . '/current';    // Legacy version path
$modern_path = $base_dir . '/modern';      // Modern version path

// Shared assets path (if needed for legacy compatibility)
$shared_assets = $base_dir . '/current';

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
