<?php
// Disable deprecated warnings (PHP 8.1+)
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

require(dirname(__FILE__)."/config.php");
require(dirname(__FILE__)."/vars.php");
include_once(dirname(__FILE__)."/function.php");

connectdb();

// Load organization settings from database (override config defaults)
$org_name = get_setting('org_name', $org_name ?? '');
$org_name_english = get_setting('org_name_english', $org_name_english ?? '');
?>