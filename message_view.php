<?php
// An announcement, course update or reminder: the Inbox (inbox.php) shows it and marks it read (2026-10-27). Links to
// this page (the mobile feed's urls, older emails) land there with it open.
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
header('Location: inbox.php' . ($id > 0 ? '?open=a' . $id : ''));
exit();
