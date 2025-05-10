<?php
/**
 * Environment Configuration Example
 * 
 * This file shows how to switch between local and production environments.
 * To use:
 * 1. Copy this file and rename it to env.php
 * 2. Set your preferred environment
 * 3. Include env.php before config.php
 */

// Set your environment here: 'local' or 'production'
$environment = 'local';

// Optional: Debug mode (set to false in production)
$debug = ($environment === 'local') ? true : false;

// If debug mode is enabled, show all errors
if ($debug) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
?> 