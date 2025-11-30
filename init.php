<?php
// Disable deprecated warnings (PHP 8.1+)
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

require(dirname(__FILE__)."/config.php");
require(dirname(__FILE__)."/vars.php");
include_once(dirname(__FILE__)."/function.php");

connectdb();
?>