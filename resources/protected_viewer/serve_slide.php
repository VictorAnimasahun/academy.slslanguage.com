<?php
// /academy/resources/protected_viewer/serve_slide.php
// Streams one slide (image or text-slide JSON) from protected_resources/.
// Independently auth-gated (same check as celpip_reading.php) so this
// endpoint can't be reached directly by URL to bypass the tier check --
// the viewer page hiding a link is not itself access control.

require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once INCLUDES_PATH . '/protected_reading_resources.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit;
}

$part = $_GET['part'] ?? '';
$n    = (int)($_GET['n'] ?? 0);

if (!isset(PROTECTED_READING_RESOURCES[$part])) {
    http_response_code(404);
    exit;
}

$resource = PROTECTED_READING_RESOURCES[$part];
if (!can_access($resource['min_tier'])) {
    http_response_code(403);
    exit;
}

$dir = ACADEMY_ROOT . '/protected_resources/' . $part;
$manifestPath = $dir . '/manifest.json';
if (!file_exists($manifestPath)) {
    http_response_code(404);
    exit;
}

$manifest = json_decode(file_get_contents($manifestPath), true);
$slide = null;
foreach ($manifest['slides'] as $s) {
    if ((int)$s['n'] === $n) { $slide = $s; break; }
}
if (!$slide) {
    http_response_code(404);
    exit;
}

$filePath = $dir . '/' . $slide['file'];
if (!file_exists($filePath)) {
    http_response_code(404);
    exit;
}

header('Cache-Control: no-store, private');
header('X-Content-Type-Options: nosniff');

if ($slide['type'] === 'image') {
    header('Content-Type: image/jpeg');
    header('Content-Length: ' . filesize($filePath));
    readfile($filePath);
} else {
    header('Content-Type: application/json');
    header('Content-Length: ' . filesize($filePath));
    readfile($filePath);
}
