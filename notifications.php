<?php
// Notifications fold into the Inbox (founder, 2026-10-27): announcements, conversations, assignments due and events
// starting soon are all inbox items there (includes/inbox.php). The mobile app's feed is api/v1/notifications.php,
// which still uses includes/notifications_feed.php.
header('Location: inbox.php');
exit();
