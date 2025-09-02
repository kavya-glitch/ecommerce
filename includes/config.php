<?php
// includes/config.php
session_start();
date_default_timezone_set('Asia/Kolkata');

// By default (local XAMPP)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ecommerce');

// Later, when you upload to InfinityFree, you will change these values ↑

// Website base URL (local for now, change later on server)
define('BASE_URL', 'http://localhost/ecommerce');

// Absolute server path (helps with includes)
define('ROOT_PATH', __DIR__ . '/../');

// Error logging
ini_set('display_errors', 1);   // Show errors while testing locally
ini_set('log_errors', 1);
ini_set('error_log', ROOT_PATH . 'error.log');
