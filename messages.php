<?php
// Moved to the Inbox (inbox.php, 2026-10-27); old links and bookmarks land there, with whatever was open.
$q = array_intersect_key($_GET, array_flip(['open', 'q']));
header('Location: inbox.php' . ($q ? '?' . http_build_query($q) : ''));
exit();
