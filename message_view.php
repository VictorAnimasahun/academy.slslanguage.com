<?php
// An announcement, course update or reminder. The inbox (messages.php) shows it now and marks it read
// (2026-10-27); links to this page (notifications, the mobile feed's urls, older emails) land there with it open.
require_once __DIR__ . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: edu_hub_registration.php?message=Please+login+to+access+messages");
    exit();
}

$message_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
header('Location: messages.php' . ($message_id > 0 ? '?open=a' . $message_id : ''));
exit();
