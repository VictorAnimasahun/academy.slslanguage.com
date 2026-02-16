<?php
/**
 * Registration Bridge File
 * Location: /academy.slslanguage.com/process_registration.php
 */

// Log errors, don't display
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Error: This page only processes form submissions.');
}

// Bootstrap is in the same directory!
require_once __DIR__ . '/bootstrap.php';

// Include the registration handler
$handler_path = CONFIG_PATH . '/edu_hub_registration_handler.php';

if (!file_exists($handler_path)) {
    die('Error: Registration handler not found at: ' . $handler_path);
}

require_once($handler_path);
?>