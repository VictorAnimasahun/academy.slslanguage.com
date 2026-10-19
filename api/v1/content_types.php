<?php
// GET /api/v1/content_types.php -- every content type and every file rule (migration 152), so the app
// decides what a link IS (and whether it may open on a phone) from the same data the website uses,
// instead of keeping its own list of folders. Public: it describes the site's structure, not a student.
//   { "types": { "<kind>": {"label","category","webOnly"} }, "files": [ {"path","kind","prefix"} ] }   longest path first
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once INCLUDES_PATH . '/content_types.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=3600');

$types = [];
foreach (array_keys(content_types_table()) as $kind) {
    $t = content_type_info($kind);
    $types[$kind] = ['label' => $t['label'], 'category' => $t['group'], 'webOnly' => $t['web_only']];
}
$files = [];
foreach (content_type_rules($db) as $path => $kind) {
    // A rule ending in / (a folder) or _ (a file-name prefix) covers everything that starts with it.
    $files[] = ['path' => $path, 'kind' => $kind, 'prefix' => str_ends_with($path, '/') || str_ends_with($path, '_')];
}

echo json_encode(['types' => $types, 'files' => $files], JSON_UNESCAPED_SLASHES);
