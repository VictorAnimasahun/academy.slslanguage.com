<?php
// Redirect stub — the diagnostic test now runs on the real DB-driven mock-session
// architecture (see resources/mock_tests/ielts_aca_diagnostic.php), same as the
// Full Mock tests. This file just forwards old links/bookmarks there.
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
header("Location: " . ACADEMY_URL . "resources/mock_tests/ielts_aca_diagnostic.php");
exit();
