<?php
// File: academy/api/get_unread_count.php -- the inbox's unread count, for the bell's refresh (includes/footer.php,
// includes/topbar.php). Counted in includes/inbox.php.
header('Content-Type: application/json');

// Go up one level to academy root, then require bootstrap
require_once __DIR__ . '/../bootstrap.php';
require_once INCLUDES_PATH . '/inbox.php';

$unread_count = isset($_SESSION['user_id']) ? inbox_unread_count($db, (int) $_SESSION['user_id']) : 0;

echo json_encode(['unread_count' => $unread_count]);
