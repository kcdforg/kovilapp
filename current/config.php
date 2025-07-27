<?php

global $db_host, $db_name, $db_user, $db_pass, $con,$path;

$db_host = 'localhost';
$db_name = 'kovil';
$db_user = 'root';
$db_pass = '';


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

// Dynamically determine the base path for assets
$script_name = dirname($_SERVER['SCRIPT_NAME']);
$script_name = rtrim($script_name, '/\\');
// Dynamically determine the protocol
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$path = $protocol . '://' . $_SERVER['SERVER_NAME'] . '/kovilapp/current';
$base_dir = $_SERVER['DOCUMENT_ROOT'] ;


?>