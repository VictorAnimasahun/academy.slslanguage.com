<?php
// Old "class hub" page. Classes now open straight on their items from the course page (founder, 2026-10-21: a test
// "should open immediately, not bring me to this screen that again takes me to another screen"); this page had its
// class contents typed in and went stale when the schedule changed. Kept only so old links land on the course page.
require_once dirname(__DIR__, 2) . '/bootstrap.php';
header('Location: course_overview.php');
exit();
