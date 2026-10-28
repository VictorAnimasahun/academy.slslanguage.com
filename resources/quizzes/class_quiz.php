<?php
/**
 * Generic class-quiz engine — DB-driven, keyed by tests.code rather than a
 * single content type (word_quiz.php's equivalent, but for course_pacing_items
 * quizzes rather than vocabulary words). Same rendering/scoring pattern as
 * word_quiz.php: multiple_choice_single scored against question_options,
 * saved to test_attempts/attempt_answers so assignments.php's existing
 * "latest attempt" join picks it up automatically — no separate completion
 * flag needed.
 */
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

$testCode  = isset($_GET['test_code']) ? trim($_GET['test_code']) : '';
$returnUrl = isset($_GET['return']) ? $_GET['return'] : '';
$studentId = (int)$_SESSION['user_id'];

if (!$testCode) { header("Location: quizzes_home.php"); exit(); }

// A return URL must be a same-site relative path — never redirect through an
// attacker-controlled absolute/external URL taken from the query string.
if ($returnUrl !== '' && (str_contains($returnUrl, '://') || str_starts_with($returnUrl, '//') || str_starts_with($returnUrl, '/'))) {
    $returnUrl = '';
}

$tstmt = $db->prepare("SELECT * FROM tests WHERE code = ? AND is_active = 1 LIMIT 1");
$tstmt->execute([$testCode]);
$test = $tstmt->fetch(PDO::FETCH_ASSOC);
if (!$test) { header("Location: quizzes_home.php"); exit(); }
$testId = (int)$test['id'];

$qstmt = $db->prepare("SELECT * FROM questions WHERE test_id = ? ORDER BY display_order, question_number");
$qstmt->execute([$testId]);
$questions = $qstmt->fetchAll(PDO::FETCH_ASSOC);

if (!$questions) { header("Location: quizzes_home.php"); exit(); }

// Shared stimulus (passage/transcript/map description) — shown once, taken
// from whichever question carries it (by convention, the first one).
$stimulus = '';
foreach ($questions as $q) {
    if (!empty($q['stimulus_text'])) { $stimulus = $q['stimulus_text']; break; }
}

$qids = array_column($questions, 'id');
$placeholders = implode(',', array_fill(0, count($qids), '?'));

$options = $db->prepare("SELECT * FROM question_options WHERE question_id IN ($placeholders) ORDER BY display_order");
$options->execute($qids);
$optsByQ = [];
foreach ($options->fetchAll(PDO::FETCH_ASSOC) as $o) {
    $optsByQ[$o['question_id']][] = $o;
}

// ── POST: mark quiz ─────────────────────────────────────────────────────────
$results    = null;
$totalScore = 0;
$maxScore   = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultDetails = [];

    foreach ($questions as $q) {
        $qid = $q['id'];
        $pts = (float)$q['points'];
        $maxScore += $pts;
        $awarded = 0.0;
        $correct = false;
        $userAnswer = '';
        $correctAnswer = '';

        $selectedId = isset($_POST["q_$qid"]) ? (int)$_POST["q_$qid"] : 0;
        foreach ($optsByQ[$qid] ?? [] as $opt) {
            if ((int)$opt['id'] === $selectedId) {
                $userAnswer = $opt['option_label'] . ': ' . $opt['option_text'];
                if ($opt['is_correct']) { $awarded = $pts; $correct = true; }
            }
            if ($opt['is_correct']) {
                $correctAnswer = $opt['option_label'] . ': ' . $opt['option_text'];
            }
        }

        $totalScore += $awarded;
        $resultDetails[] = [
            'q' => $q, 'correct' => $correct, 'awarded' => $awarded,
            'correctAnswer' => $correctAnswer, 'opts' => $optsByQ[$qid] ?? [],
            'selectedId' => $selectedId ?: null,
        ];
    }

    $maxAttempt = $db->prepare("SELECT COALESCE(MAX(attempt_number),0) FROM test_attempts WHERE student_id=? AND test_id=?");
    $maxAttempt->execute([$studentId, $testId]);
    $nextAttempt = (int)$maxAttempt->fetchColumn() + 1;

    $db->prepare("INSERT INTO test_attempts (student_id,test_id,attempt_number,mode,score,max_score,status,completed_at) VALUES (?,?,?,?,?,?,'completed',NOW())")
       ->execute([$studentId, $testId, $nextAttempt, 'practice', $totalScore, $maxScore]);
    $attemptId = (int)$db->lastInsertId();

    foreach ($resultDetails as $rd) {
        $db->prepare("INSERT INTO attempt_answers (attempt_id,question_id,selected_option_id,score_awarded) VALUES (?,?,?,?)")
           ->execute([$attemptId, $rd['q']['id'], $rd['selectedId'], $rd['awarded']]);
    }

    $results = $resultDetails;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($test['title']) ?> | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= ACADEMY_URL ?>assets/css/courses.css" rel="stylesheet">   <!-- before navbar_styles, as in lesson_shell.php: courses.css sizes .sidebar too -->
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        /* Same frame and header as a lesson page (includes/lesson_shell.php, assets/css/courses.css): founder,
           2026-10-28, "Every single content page in a course should take the same space." Flat: bordered blocks,
           no gradients, no coloured left edges. */
        .stimulus-box { background:#f8fafc; border:1px solid #e2e8f0; border-radius:3px; padding:1.25rem 1.5rem; margin-bottom:1.5rem; white-space:pre-line; font-size:.95rem; line-height:1.7; color:#334155; }
        .stimulus-box .stim-label { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#64748b; margin-bottom:.6rem; }

        .q-card { border:1px solid #e2e8f0; border-radius:3px; padding:1.25rem 1.4rem; margin-bottom:1rem; }
        .q-card.answered-correct { border-color:#86efac; }
        .q-card.answered-wrong   { border-color:#fca5a5; }
        .q-num  { font-size:.74rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:#64748b; margin-bottom:.45rem; display:flex; gap:.6rem; align-items:center; }
        .q-mark { font-size:.72rem; letter-spacing:0; text-transform:none; font-weight:700; border-radius:3px; padding:.05rem .55rem; }
        .q-mark.ok { background:#dcfce7; color:#166534; } .q-mark.no { background:#fee2e2; color:#991b1b; }
        .q-text { font-size:1rem; font-weight:600; color:#1e293b; line-height:1.55; margin-bottom:.9rem; }

        .opt-label { display:flex; align-items:flex-start; gap:.75rem; padding:.65rem .9rem; border-radius:3px; border:1px solid #e2e8f0; background:#fff; margin-bottom:.45rem; cursor:pointer; transition:border-color .15s, background .15s; }
        .opt-label:hover { border-color:#0b77ff; background:#f0f7ff; }
        .opt-label input[type=radio] { margin-top:3px; flex-shrink:0; accent-color:#0b77ff; }
        .opt-key  { font-weight:700; color:#475569; min-width:20px; }
        .opt-text { font-size:.95rem; color:#1e293b; }
        .opt-correct { border-color:#22c55e !important; background:#f0fdf4 !important; }
        .opt-wrong   { border-color:#ef4444 !important; background:#fff1f2 !important; }

        .score-box { border:1px solid #e2e8f0; border-radius:3px; padding:1.1rem 1.4rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:1.25rem; flex-wrap:wrap; }
        .score-num { font-size:2.2rem; font-weight:800; line-height:1; color:#0b77ff; }
        .score-box.perfect .score-num, .score-box.good .score-num { color:#16a34a; }
        .score-box.low .score-num { color:#dc2626; }
        .score-sub { color:#475569; font-size:1rem; }

        .submit-btn { padding:.7rem 1.6rem; border-radius:3px; background:#0b77ff; color:#fff; border:1px solid #0b77ff; font-size:1rem; font-weight:600; cursor:pointer; margin-top:.5rem; }
        .submit-btn:hover { background:#0a66db; }

        body.dark .q-card, body.dark .score-box { border-color:rgba(255,255,255,.1); }
        body.dark .q-text, body.dark .opt-text { color:#e5e7eb; }
        body.dark .opt-label { background:#1f1f1f; border-color:rgba(255,255,255,.12); }
        body.dark .opt-label:hover { background:#1b2638; border-color:#0b77ff; }
        body.dark .opt-key { color:#cbd5e1; }
        body.dark .opt-correct { background:#12321f !important; } body.dark .opt-wrong { background:#3a1717 !important; }
        body.dark .stimulus-box { background:#1b2638; border-color:#2c3e57; color:#e2e8f0; }
        body.dark .score-sub { color:#cbd5e1; }
    </style>
</head>
<?php
// Where this quiz sits in the course it was opened from (?from=, includes/item_nav.php), for the same breadcrumb and
// Week / Class / Quiz header a lesson has. Opened outside a course, the header just has the title.
require_once INCLUDES_PATH . '/item_nav.php';
require_once INCLUDES_PATH . '/content_types.php';
$quizPath = 'resources/quizzes/class_quiz.php?test_code=' . $testCode;
$navCourse = item_nav_course($db);
$here = $navCourse ? course_item_neighbours($db, $navCourse, $quizPath) : ['found' => false];
$h = fn ($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
$quizTitle = !empty($here['found']) ? $here['item']['title'] : $test['title'];
$kt = content_type_info('quiz');
?>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="mobile-overlay" id="mobileOverlay"></div>
<?php include INCLUDES_PATH . '/navbar.php'; ?>

<main class="main-wrapper">
    <div class="course-card">
            <?php if (!empty($here['found'])): ?>
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= ACADEMY_URL ?>courses/courses_catalogue.php" class="text-decoration-none">Courses</a></li>
                    <li class="breadcrumb-item"><a href="<?= ACADEMY_URL ?>courses/<?= $h($navCourse['folder']) ?>/course_overview.php" class="text-decoration-none"><?= $h($navCourse['title']) ?></a></li>
                    <li class="breadcrumb-item active"><?= $h($quizTitle) ?></li>
                </ol>
            </nav>
            <?php elseif ($returnUrl): ?>
            <a href="<?= $h($returnUrl) ?>" class="d-inline-flex align-items-center gap-1 mb-3 text-decoration-none"><i class="bi bi-chevron-left"></i> Back to lesson</a>
            <?php endif; ?>

            <div class="class-head">
                <div class="class-head-icon"><i class="bi <?= $h($kt['icon'] ?? 'bi-patch-question') ?>"></i></div>
                <div>
                    <div class="class-chips">
                        <?php if (!empty($here['found'])): ?>
                        <span class="class-chip">Week <?= (int) $here['item']['week'] ?></span>
                        <span class="class-chip">Class <?= (int) $here['item']['class'] ?> of <?= (int) $here['classes'] ?></span>
                        <?php endif; ?>
                        <span class="class-chip kind" style="background:<?= $h($kt['bg']) ?>;color:<?= $h($kt['fg']) ?>;"><?= $h($kt['label']) ?></span>
                        <span class="class-chip"><?= count($questions) ?> questions</span>
                    </div>
                    <h1 class="class-title"><?= $h($quizTitle) ?></h1>
                    <?php if (!empty($test['description'])): ?><p class="class-sub"><?= $h($test['description']) ?></p><?php endif; ?>
                </div>
            </div>

            <?php if ($results !== null): ?>
            <?php
            $pct = $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0;
            $bannerClass = $pct >= 100 ? 'perfect' : ($pct >= 67 ? 'good' : ($pct >= 34 ? 'fair' : 'low'));
            $msg = $pct >= 100 ? 'Perfect score! Excellent work.' : ($pct >= 67 ? 'Great job!' : ($pct >= 34 ? 'Keep practising.' : 'Review this topic and try again.'));
            ?>
            <div class="score-box <?= $bannerClass ?>">
                <div class="score-num"><?= (int)$totalScore ?> / <?= (int)$maxScore ?></div>
                <div class="score-sub"><?= $msg ?></div>
            </div>

            <?php if ($stimulus): ?>
            <div class="stimulus-box"><div class="stim-label">Reference</div><?= htmlspecialchars($stimulus) ?></div>
            <?php endif; ?>

            <?php foreach ($results as $i => $rd): $q = $rd['q']; ?>
            <div class="q-card <?= $rd['correct'] ? 'answered-correct' : 'answered-wrong' ?>">
                <div class="q-num">Question <?= $i + 1 ?> <span class="q-mark <?= $rd['correct'] ? 'ok' : 'no' ?>"><?= $rd['correct'] ? 'Correct' : 'Incorrect' ?></span></div>
                <div class="q-text"><?= htmlspecialchars($q['question_text']) ?></div>
                <?php foreach ($rd['opts'] as $opt):
                    $isSelected = $rd['selectedId'] && (int)$opt['id'] === $rd['selectedId'];
                    $cls = '';
                    if ($opt['is_correct']) $cls = 'opt-correct';
                    elseif ($isSelected && !$opt['is_correct']) $cls = 'opt-wrong';
                ?>
                <div class="opt-label <?= $cls ?>" style="cursor:default;">
                    <span class="opt-key"><?= htmlspecialchars($opt['option_label']) ?></span>
                    <span class="opt-text"><?= htmlspecialchars($opt['option_text']) ?></span>
                    <?php if ($opt['is_correct']): ?><i class="bi bi-check-circle-fill text-success ms-auto"></i><?php endif; ?>
                    <?php if ($isSelected && !$opt['is_correct']): ?><i class="bi bi-x-circle-fill text-danger ms-auto"></i><?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>

            <div class="d-flex gap-2 mt-2">
                <a href="class_quiz.php?test_code=<?= urlencode($testCode) ?>&return=<?= urlencode($returnUrl) ?>" class="btn btn-outline-secondary flex-fill">Try Again</a>
                <?php if ($returnUrl): ?>
                <a href="<?= htmlspecialchars($returnUrl) ?>" class="btn btn-primary flex-fill" style="background:#0b77ff;border:none;">Back to Lesson</a>
                <?php endif; ?>
            </div>

            <?php else: ?>
            <?php if ($stimulus): ?>
            <div class="stimulus-box"><div class="stim-label">Reference</div><?= htmlspecialchars($stimulus) ?></div>
            <?php endif; ?>

            <form method="POST">
                <?php foreach ($questions as $i => $q): $qid = $q['id']; ?>
                <div class="q-card">
                    <div class="q-num">Question <?= $i + 1 ?></div>
                    <div class="q-text"><?= htmlspecialchars($q['question_text']) ?></div>
                    <?php if (!empty($q['instructions'])): ?>
                        <p class="text-muted small mb-2"><?= htmlspecialchars($q['instructions']) ?></p>
                    <?php endif; ?>
                    <?php foreach ($optsByQ[$qid] ?? [] as $opt): ?>
                    <label class="opt-label">
                        <input type="radio" name="q_<?= $qid ?>" value="<?= $opt['id'] ?>" required>
                        <span class="opt-key"><?= htmlspecialchars($opt['option_label']) ?></span>
                        <span class="opt-text"><?= htmlspecialchars($opt['option_text']) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>

                <button type="submit" class="submit-btn">Submit Answers <i class="bi bi-arrow-right ms-1"></i></button>
            </form>
            <?php endif; ?>

            <?= item_nav_html($db, $quizPath) ?>
    </div>
</main>

<?php include INCLUDES_PATH . '/adverts.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
