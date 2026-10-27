<?php
// An older copy of message_view.php (show an announcement and mark it read). The Inbox (inbox.php) does both now
// (2026-10-27); old links land there with the message open.
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
header('Location: inbox.php' . ($id > 0 ? '?open=a' . $id : ''));
exit();
