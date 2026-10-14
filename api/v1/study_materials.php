<?php
// GET /api/v1/study_materials.php -- the mobile Study materials list (prototype's Resources >
// Study materials row). Same real catalogue as resources/study_materials/index.php/view.php (4
// real .docx vocabulary packs under assets/downloads/vocabulary/, no PDFs on disk yet so there's
// nothing to embed inline) -- native list, the actual "Download" action still bridges through the
// existing token-to-web-session mechanism (api.startWebSession) since the real download endpoint
// is session-gated and this is exactly the kind of binary-file handoff that bridge exists for.
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

requireMobileAuth($db);

$books = [
    ['id' => 'vb01', 'title' => 'Astronomy & Stargazing', 'series' => 'VB01', 'description' => 'Expand your vocabulary around space, celestial objects, and astronomical phenomena.', 'downloadPath' => 'resources/study_materials/index.php?file=vb01'],
    ['id' => 'vb02', 'title' => 'Restaurants & Food', 'series' => 'VB02', 'description' => 'Key vocabulary for describing food, dining experiences, and culinary contexts.', 'downloadPath' => 'resources/study_materials/index.php?file=vb02'],
    ['id' => 'vb03', 'title' => 'Sustainable Food', 'series' => 'VB03', 'description' => 'Vocabulary for discussing sustainability, food systems, and environmental impact.', 'downloadPath' => 'resources/study_materials/index.php?file=vb03'],
    ['id' => 'vb04', 'title' => 'Technology & the Future', 'series' => 'VB04', 'description' => 'Modern vocabulary around digital innovation, AI, and future technology trends.', 'downloadPath' => 'resources/study_materials/index.php?file=vb04'],
];

echo json_encode(['items' => $books], JSON_UNESCAPED_UNICODE);
