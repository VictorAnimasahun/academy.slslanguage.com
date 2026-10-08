<?php
/**
 * Password Reset Bridge File
 * Location: /academy.slslanguage.com/process_password_reset.php
 *
 * Mirrors process_registration.php's role, just for the new request_reset / reset_password
 * POST actions that config/edu_hub_registration_handler.php now also handles.
 */

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Error: This page only processes form submissions.');
}

require_once __DIR__ . '/bootstrap.php';

$handler_path = CONFIG_PATH . '/edu_hub_registration_handler.php';

if (!file_exists($handler_path)) {
    die('Error: Registration handler not found at: ' . $handler_path);
}

require_once($handler_path);
?>
