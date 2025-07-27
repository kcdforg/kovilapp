<?php

global $db_host, $db_name, $db_user, $db_pass, $con, $path, $base_path;

// Database credentials (connection will be established in init.php)
$db_host = 'localhost';
$db_name = 'kovil';
$db_user = 'root';
$db_pass = '';

// Table definitions (these are already defined in vars.php, but keeping for reference)
$tbl_users = 'users';
$tbl_family = 'family';
$tbl_child = 'child';
$tbl_event = 'event';
$tbl_matrimony = 'matrimony';
$tbl_kattam = 'kattam';
$tbl_attachments = 'attachments';
$tbl_book = 'book';
$tbl_receipt = 'receipt';
$tbl_labels = 'labels';
$tbl_donation = 'donation';
$tbl_horoscope = 'horoscope';
$tbl_subscription = 'subscription';

// Dynamically determine the base path for assets
$script_name = dirname($_SERVER['SCRIPT_NAME']);
$script_name = rtrim($script_name, '/\\');

// Dynamically determine the protocol
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

// Base path for the modern version
$path = $protocol . '://' . $_SERVER['SERVER_NAME'] . '/kovilapp/modern';

// Base directory for file operations
$base_dir = $_SERVER['DOCUMENT_ROOT'] . '/kovilapp';

// Paths for different versions
$current_path = $base_dir . '/current';
$modern_path = $base_dir . '/modern';

// Shared assets path (if needed)
$shared_assets = $base_dir . '/current';

?>