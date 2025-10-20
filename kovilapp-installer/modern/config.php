<?php

global $db_host, $db_name, $db_user, $db_pass, $con, $path, $base_path;

// Database credentials (connection will be established in init.php)
$db_host = 'localhost';
$db_name = 'kovil';
$db_user = 'root';
$db_pass = '';

// Table definitions
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

// Subscription Module Tables
$tbl_subscription_events = 'subscription_events';
$tbl_receipt_books = 'receipt_books';
$tbl_member_subscriptions = 'member_subscriptions';
$tbl_receipt_details = 'receipt_details';

// Dynamically determine paths
$document_root = $_SERVER['DOCUMENT_ROOT'];
$script_name = $_SERVER['SCRIPT_NAME'];
$request_uri = $_SERVER['REQUEST_URI'];

// Get the directory where this script is located relative to document root
$script_dir = dirname($script_name);

// Determine protocol
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

// Application base URL (should be root after installation)
$path = $protocol . '://' . $_SERVER['SERVER_NAME'];

// Base directory for file operations (should be document root after installation)
$base_dir = $document_root;

// Paths for application (all point to document root after installation)
$current_path = $document_root;
$modern_path = $document_root;

// Shared assets path
$shared_assets = $document_root;

?>