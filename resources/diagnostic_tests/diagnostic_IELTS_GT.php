<?php
// Redirect stub — sibling of diagnostic_IELTS.php. The diagnostic test runs on
// the real DB-driven mock-session architecture (see
// resources/mock_tests/ielts_gt_diagnostic.php). This file just forwards
// links from the selection page there.
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
header("Location: " . ACADEMY_URL . "resources/mock_tests/ielts_gt_diagnostic.php");
exit();
