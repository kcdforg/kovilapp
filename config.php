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
$tbl_ftree = 'ftree';

// Subscription Module Tables
$tbl_subscription_events = 'subscription_events';
$tbl_receipt_books = 'receipt_books';
$tbl_member_subscriptions = 'member_subscriptions';
$tbl_receipt_details = 'receipt_details';

// Dynamically determine the protocol
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

// Determine the application root path
// This works by finding the path from document root to the config.php location
$doc_root = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
$config_dir = dirname(__FILE__);
$app_path = str_replace($doc_root, '', $config_dir);
$app_path = str_replace('\\', '/', $app_path); // Normalize for Windows

// Base URL path for the application (always points to root)
$path = $protocol . '://' . $_SERVER['SERVER_NAME'] . $app_path;

// Base directory for file operations
$base_dir = dirname(__FILE__);

// Organization details
$org_name = 'அருள்மிகு புது வெங்கரை அம்மன் கோயில்';
$org_name_english = 'Arulmigu Puthukarai Amman Kovil';

?>