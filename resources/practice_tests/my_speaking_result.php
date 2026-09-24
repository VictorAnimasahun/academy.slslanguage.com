<?php
// Official, instructor-released result for a Speaking practice test attempt.
// Distinct from the instant, unsaved AI reaction shown right after submitting
// (see celpip_speaking_00N.php's analyze_speaking_batch call) -- this is the
// same kind of proper, confirmed result Listening/Reading/Writing/Full Mock
// already deliver, just for Speaking, which never had one before.
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}

if (!function_exists('e')) {
    function e(mixed $v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
}

$studentId = (int)$_SESSION['user_id'];
$testCode  = (string)($_GET['test_code'] ?? '');

$stmt = $db->prepare("SELECT title FROM tests WHERE code = ? LIMIT 1");
$stmt->execute([$testCode]);
$testTitle = $stmt->fetchColumn() ?: $testCode;
$scoreLabel = str_starts_with($testCode, 'IELTS') ? 'Band' : 'CLB';

$stmt = $db->prepare("SELECT * FROM speaking_session_scores WHERE student_id = ? AND test_code = ? AND status = 'results_released'");
$stmt->execute([$studentId, $testCode]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$tasks = [];
if ($result) {
    $stmt = $db->prepare("
        SELECT sr.*
        FROM speaking_recordings sr
        INNER JOIN (
            SELECT task_number, MAX(id) AS max_id
            FROM speaking_recordings
            WHERE mock_session_id IS NULL AND student_id = ? AND test_code = ?
            GROUP BY task_number
        ) latest ON latest.max_id = sr.id
        ORDER BY sr.task_number
    ");
    $stmt->execute([$studentId, $testCode]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Speaking Practice Results — <?= e($testTitle) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../../assets/css/courses.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .result-card { background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:1.75rem; margin-bottom:1.25rem; }
        .score-band { background:linear-gradient(135deg,#e0f2fe,#fce7f3); border-radius:12px; padding:1.5rem; text-align:center; margin-bottom:1.5rem; }
        .score-band .num { font-size:3rem; font-weight:800; color:#0369a1; line-height:1; }
        .score-band .lbl { font-size:.85rem; color:#64748b; text-transform:uppercase; letter-spacing:.05em; margin-top:.3rem; }
        .task-row { border-top:1px solid #e2e8f0; padding:1rem 0; }
        .task-row:first-child { border-top:none; padding-top:0; }
    </style>
</head>
<body>
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="mobile-overlay" id="mobileOverlay"></div>
<?php include INCLUDES_PATH . '/navbar.php'; ?>
<main class="main-wrapper">
    <div class="course-card">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../../learning_dashboard.php" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Speaking Practice Results</li>
            </ol>
        </nav>

        <h1 class="mb-4">🎙️ <?= e($testTitle) ?> — Speaking Results</h1>

        <?php if (!$result): ?>
            <div class="result-card text-center text-muted">
                <i class="bi bi-hourglass-split" style="font-size:2rem;"></i>
                <p class="mt-3 mb-0">Your speaking practice results aren't ready yet. Your instructor is still reviewing your responses — check back soon.</p>
            </div>
        <?php else: ?>
            <div class="score-band">
                <div class="num"><?= e($result['total_score']) ?></div>
                <div class="lbl">Overall <?= e($scoreLabel) ?></div>
            </div>

            <?php if (trim((string)$result['summary']) !== ''): ?>
                <div class="result-card">
                    <h5 class="mb-2"><i class="bi bi-chat-square-text me-2"></i>Instructor Summary</h5>
                    <p class="mb-0" style="white-space:pre-wrap;"><?= nl2br(e($result['summary'])) ?></p>
                </div>
            <?php endif; ?>

            <?php if ($tasks): ?>
                <div class="result-card">
                    <h5 class="mb-3"><i class="bi bi-list-check me-2"></i>Per-Task Breakdown</h5>
                    <?php foreach ($tasks as $t): ?>
                        <div class="task-row">
                            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                                <strong>Task <?= (int)$t['task_number'] ?><?= $t['task_title'] ? ' — ' . e($t['task_title']) : '' ?></strong>
                                <?php if ($t['manual_score'] !== null && trim((string)$t['manual_score']) !== ''): ?>
                                    <span class="badge bg-primary"><?= e($scoreLabel) ?> <?= e($t['manual_score']) ?></span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Not scored</span>
                                <?php endif; ?>
                            </div>
                            <?php if (trim((string)$t['manual_analysis']) !== ''): ?>
                                <p class="text-muted mb-0 mt-2" style="font-size:.9rem;"><?= nl2br(e($t['manual_analysis'])) ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <a href="../../learning_dashboard.php" class="btn btn-outline-primary"><i class="bi bi-speedometer2 me-2"></i>Back to Dashboard</a>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
