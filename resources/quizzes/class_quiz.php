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
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .quiz-header { background: linear-gradient(135deg, #0b77ff, #6366f1); border-radius: 16px; padding: 1.5rem 2rem; color: #fff; margin-bottom: 1.75rem; }
        .quiz-header h1 { font-size: 1.4rem; font-weight: 800; margin-bottom: 0.2rem; }
        .quiz-header p  { opacity: 0.85; margin: 0; font-size: 0.9rem; }

        .stimulus-box { background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:1.25rem 1.5rem; margin-bottom:1.5rem; white-space:pre-line; font-size:.92rem; line-height:1.7; color:#334155; }
        .stimulus-box .stim-label { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#64748b; margin-bottom:.6rem; }

        .q-card { background: #fff; border-radius: 12px; padding: 1.4rem 1.5rem; box-shadow: 0 1px 6px rgba(15,23,42,0.06); margin-bottom: 1.1rem; border-left: 4px solid #e2e8f0; }
        .q-card.answered-correct { border-left-color: #22c55e; }
        .q-card.answered-wrong   { border-left-color: #ef4444; }
        .q-num  { font-size: 0.74rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: #94a3b8; margin-bottom: 0.5rem; }
        .q-text { font-size: 1rem; font-weight: 600; color: #1e293b; line-height: 1.55; margin-bottom: 1rem; }

        .opt-label { display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.7rem 0.9rem; border-radius: 8px; border: 1.5px solid #e2e8f0; background: #fff; margin-bottom: 0.45rem; cursor: pointer; transition: border-color 0.15s, background 0.15s; }
        .opt-label:hover { border-color: #0b77ff; background: #f0f7ff; }
        .opt-label input[type=radio] { margin-top: 2px; flex-shrink: 0; }
        .opt-key  { font-weight: 700; color: #475569; min-width: 20px; }
        .opt-text { font-size: 0.93rem; color: #1e293b; }
        .opt-correct { border-color: #22c55e !important; background: #f0fdf4 !important; }
        .opt-wrong   { border-color: #ef4444 !important; background: #fff1f2 !important; }

        .score-banner { border-radius: 16px; padding: 1.5rem 2rem; margin-bottom: 1.75rem; text-align: center; }
        .score-banner.perfect  { background: linear-gradient(135deg, #22c55e, #16a34a); color: #fff; }
        .score-banner.good     { background: linear-gradient(135deg, #3b82f6, #0b77ff); color: #fff; }
        .score-banner.fair     { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
        .score-banner.low      { background: linear-gradient(135deg, #ef4444, #b91c1c); color: #fff; }
        .score-num  { font-size: 3rem; font-weight: 800; line-height: 1; }
        .score-sub  { opacity: 0.85; font-size: 0.95rem; margin-top: 0.4rem; }

        .submit-btn { width: 100%; padding: 0.85rem; border-radius: 10px; background: linear-gradient(135deg, #0b77ff, #6366f1); color: #fff; border: none; font-size: 1rem; font-weight: 700; cursor: pointer; margin-top: 0.5rem; transition: opacity 0.15s; }
        .submit-btn:hover { opacity: 0.9; }
    </style>
</head>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="mobile-overlay" id="mobileOverlay"></div>
<?php include INCLUDES_PATH . '/navbar.php'; ?>

<div class="main-wrapper flex-grow-1" style="flex:1;">
    <?php include INCLUDES_PATH . '/topbar.php'; ?>

    <main class="content p-4">
        <div style="max-width:720px;">

            <?php if ($returnUrl): ?>
            <a href="<?= htmlspecialchars($returnUrl) ?>" style="font-size:0.85rem;color:#64748b;text-decoration:none;" class="d-inline-flex align-items-center gap-1 mb-3">
                <i class="bi bi-chevron-left"></i> Back to lesson
            </a>
            <?php endif; ?>

            <div class="quiz-header">
                <h1><i class="bi bi-lightning-charge-fill me-1"></i><?= htmlspecialchars($test['title']) ?></h1>
                <p><?= htmlspecialchars($test['description'] ?? '') ?></p>
            </div>

            <?php if ($results !== null): ?>
            <?php
            $pct = $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0;
            $bannerClass = $pct >= 100 ? 'perfect' : ($pct >= 67 ? 'good' : ($pct >= 34 ? 'fair' : 'low'));
            $msg = $pct >= 100 ? 'Perfect score! Excellent work.' : ($pct >= 67 ? 'Great job!' : ($pct >= 34 ? 'Keep practising.' : 'Review this topic and try again.'));
            ?>
            <div class="score-banner <?= $bannerClass ?>">
                <div class="score-num"><?= (int)$totalScore ?> / <?= (int)$maxScore ?></div>
                <div class="score-sub"><?= $msg ?></div>
            </div>

            <?php if ($stimulus): ?>
            <div class="stimulus-box"><div class="stim-label">Reference</div><?= htmlspecialchars($stimulus) ?></div>
            <?php endif; ?>

            <?php foreach ($results as $i => $rd): $q = $rd['q']; ?>
            <div class="q-card <?= $rd['correct'] ? 'answered-correct' : 'answered-wrong' ?>">
                <div class="q-num">Question <?= $i + 1 ?></div>
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

        </div>
    </main>
</div>

<?php include INCLUDES_PATH . '/adverts.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
