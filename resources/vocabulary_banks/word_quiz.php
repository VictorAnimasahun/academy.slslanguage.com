<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

$wordId    = isset($_GET['word_id']) ? (int)$_GET['word_id'] : 0;
$studentId = (int)$_SESSION['user_id'];

if (!$wordId) { header("Location: vocab_home.php"); exit(); }

// Load word
$wstmt = $db->prepare("SELECT * FROM vocabulary_words WHERE id=? AND is_active=1");
$wstmt->execute([$wordId]);
$word = $wstmt->fetch(PDO::FETCH_ASSOC);
if (!$word) { header("Location: vocab_home.php"); exit(); }

// Load questions for this word (with options and correct answers)
$qstmt = $db->prepare("SELECT * FROM questions WHERE word_id=? ORDER BY display_order, question_number");
$qstmt->execute([$wordId]);
$questions = $qstmt->fetchAll(PDO::FETCH_ASSOC);

if (!$questions) {
    header("Location: word.php?id=$wordId");
    exit();
}

// Load options and correct answers keyed by question_id
$qids = array_column($questions, 'id');
$placeholders = implode(',', array_fill(0, count($qids), '?'));

$options = $db->prepare("SELECT * FROM question_options WHERE question_id IN ($placeholders) ORDER BY display_order");
$options->execute($qids);
$optsByQ = [];
foreach ($options->fetchAll(PDO::FETCH_ASSOC) as $o) {
    $optsByQ[$o['question_id']][] = $o;
}

$answers = $db->prepare("SELECT * FROM question_correct_answers WHERE question_id IN ($placeholders)");
$answers->execute($qids);
$answersByQ = [];
foreach ($answers->fetchAll(PDO::FETCH_ASSOC) as $a) {
    $answersByQ[$a['question_id']][] = $a;
}

// ── POST: mark quiz ───────────────────────────────────────────────────────────
$results    = null;
$totalScore = 0;
$maxScore   = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultDetails = [];

    foreach ($questions as $q) {
        $qid   = $q['id'];
        $type  = $q['question_type'];
        $pts   = (float)$q['points'];
        $maxScore += $pts;
        $awarded   = 0.0;
        $correct   = false;
        $userAnswer = '';
        $correctAnswer = '';

        if ($type === 'multiple_choice_single') {
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
        } elseif ($type === 'gap_fill') {
            $userAnswer = trim($_POST["q_$qid"] ?? '');
            foreach ($answersByQ[$qid] ?? [] as $ca) {
                if (!$ca['is_case_sensitive']) {
                    if (strtolower($userAnswer) === strtolower($ca['answer_text'])) {
                        $awarded = $pts; $correct = true;
                    }
                } else {
                    if ($userAnswer === $ca['answer_text']) {
                        $awarded = $pts; $correct = true;
                    }
                }
                if (!$correctAnswer) $correctAnswer = $ca['answer_text'];
            }
        }

        $totalScore += $awarded;
        $resultDetails[] = [
            'q'             => $q,
            'correct'       => $correct,
            'awarded'       => $awarded,
            'userAnswer'    => $userAnswer,
            'correctAnswer' => $correctAnswer,
            'opts'          => $optsByQ[$qid] ?? [],
            'selectedId'    => isset($_POST["q_$qid"]) && $q['question_type'] === 'multiple_choice_single'
                                    ? (int)$_POST["q_$qid"] : null,
        ];
    }

    // Save attempt
    $testStmt = $db->prepare("SELECT id FROM tests WHERE test_type='Vocabulary' AND code=?");
    $code = 'VOCAB_WORD_' . str_pad((int)$word['sort_order'], 3, '0', STR_PAD_LEFT);
    $testStmt->execute([$code]);
    $testRow = $testStmt->fetch(PDO::FETCH_ASSOC);

    if ($testRow) {
        $testId = (int)$testRow['id'];
        $maxAttempt = $db->query("SELECT COALESCE(MAX(attempt_number),0) FROM test_attempts WHERE student_id=$studentId AND test_id=$testId")->fetchColumn();
        $db->prepare("INSERT INTO test_attempts (student_id,test_id,attempt_number,mode,score,max_score,status,completed_at) VALUES (?,?,?,?,?,?,'completed',NOW())")
           ->execute([$studentId, $testId, $maxAttempt + 1, 'practice', $totalScore, $maxScore]);
        $attemptId = (int)$db->lastInsertId();

        foreach ($resultDetails as $rd) {
            $qid        = $rd['q']['id'];
            $selOptId   = $rd['selectedId'];
            $ansText    = $rd['q']['question_type'] === 'gap_fill' ? $rd['userAnswer'] : null;
            $db->prepare("INSERT INTO attempt_answers (attempt_id,question_id,selected_option_id,answer_text,score_awarded) VALUES (?,?,?,?,?)")
               ->execute([$attemptId, $qid, $selOptId, $ansText, $rd['awarded']]);
        }
    }

    $results = $resultDetails;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz: <?= htmlspecialchars($word['headword']) ?> | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .quiz-header {
            background: linear-gradient(135deg, #ec4899, #f43f5e);
            border-radius: 16px; padding: 1.5rem 2rem; color: #fff; margin-bottom: 1.75rem;
        }
        .quiz-header h1 { font-size: 1.5rem; font-weight: 800; margin-bottom: 0.2rem; }
        .quiz-header p  { opacity: 0.85; margin: 0; font-size: 0.9rem; }

        .q-card {
            background: #fff; border-radius: 12px; padding: 1.4rem 1.5rem;
            box-shadow: 0 1px 6px rgba(15,23,42,0.06); margin-bottom: 1.1rem;
            border-left: 4px solid #e2e8f0;
        }
        .q-card.answered-correct { border-left-color: #22c55e; }
        .q-card.answered-wrong   { border-left-color: #ef4444; }

        .q-num  { font-size: 0.74rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: #94a3b8; margin-bottom: 0.5rem; }
        .q-text { font-size: 1rem; font-weight: 600; color: #1e293b; line-height: 1.55; margin-bottom: 1rem; }

        .opt-label {
            display: flex; align-items: flex-start; gap: 0.75rem;
            padding: 0.7rem 0.9rem; border-radius: 8px;
            border: 1.5px solid #e2e8f0; background: #fff;
            margin-bottom: 0.45rem; cursor: pointer;
            transition: border-color 0.15s, background 0.15s;
        }
        .opt-label:hover { border-color: #0b77ff; background: #f0f7ff; }
        .opt-label input[type=radio] { margin-top: 2px; flex-shrink: 0; }
        .opt-key  { font-weight: 700; color: #475569; min-width: 20px; }
        .opt-text { font-size: 0.93rem; color: #1e293b; }

        /* Results styling */
        .opt-correct { border-color: #22c55e !important; background: #f0fdf4 !important; }
        .opt-wrong   { border-color: #ef4444 !important; background: #fff1f2 !important; }
        .feedback-correct { color: #166534; font-size: 0.85rem; margin-top: 0.5rem; }
        .feedback-wrong   { color: #991b1b; font-size: 0.85rem; margin-top: 0.5rem; }

        .gap-input {
            border: 1.5px solid #e2e8f0; border-radius: 8px;
            padding: 0.6rem 0.9rem; font-size: 0.95rem; width: 100%; max-width: 320px;
            outline: none; transition: border-color 0.15s;
        }
        .gap-input:focus { border-color: #0b77ff; }
        .gap-input.correct { border-color: #22c55e; background: #f0fdf4; }
        .gap-input.wrong   { border-color: #ef4444; background: #fff1f2; }

        .score-banner {
            border-radius: 16px; padding: 1.5rem 2rem; margin-bottom: 1.75rem; text-align: center;
        }
        .score-banner.perfect  { background: linear-gradient(135deg, #22c55e, #16a34a); color: #fff; }
        .score-banner.good     { background: linear-gradient(135deg, #3b82f6, #0b77ff); color: #fff; }
        .score-banner.fair     { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
        .score-banner.low      { background: linear-gradient(135deg, #ef4444, #b91c1c); color: #fff; }
        .score-num  { font-size: 3rem; font-weight: 800; line-height: 1; }
        .score-sub  { opacity: 0.85; font-size: 0.95rem; margin-top: 0.4rem; }

        .submit-btn {
            width: 100%; padding: 0.85rem; border-radius: 10px;
            background: linear-gradient(135deg, #ec4899, #f43f5e);
            color: #fff; border: none; font-size: 1rem; font-weight: 700;
            cursor: pointer; margin-top: 0.5rem; transition: opacity 0.15s;
        }
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
        <div style="max-width:680px;">

            <!-- Breadcrumb -->
            <a href="word.php?id=<?= $wordId ?>" style="font-size:0.85rem;color:#64748b;text-decoration:none;" class="d-inline-flex align-items-center gap-1 mb-3">
                <i class="bi bi-chevron-left"></i> <?= htmlspecialchars($word['headword']) ?>
            </a>

            <!-- Header -->
            <div class="quiz-header">
                <h1><i class="bi bi-lightning-charge-fill me-1"></i><?= htmlspecialchars($word['headword']) ?></h1>
                <p><?= count($questions) ?> question<?= count($questions) !== 1 ? 's' : '' ?> — test your knowledge of this word</p>
            </div>

            <?php if ($results !== null): ?>
            <!-- ═══════════════════════════════════════════════════════════════
                 RESULTS VIEW
            ════════════════════════════════════════════════════════════════ -->
            <?php
            $pct = $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0;
            $bannerClass = $pct >= 100 ? 'perfect' : ($pct >= 67 ? 'good' : ($pct >= 34 ? 'fair' : 'low'));
            $msg = $pct >= 100 ? 'Perfect score! Excellent work.' : ($pct >= 67 ? 'Great job!' : ($pct >= 34 ? 'Keep practising.' : 'Review this word and try again.'));
            ?>
            <div class="score-banner <?= $bannerClass ?>">
                <div class="score-num"><?= (int)$totalScore ?> / <?= (int)$maxScore ?></div>
                <div class="score-sub"><?= $msg ?></div>
            </div>

            <?php foreach ($results as $i => $rd): ?>
            <?php $q = $rd['q']; ?>
            <div class="q-card <?= $rd['correct'] ? 'answered-correct' : 'answered-wrong' ?>">
                <div class="q-num">Question <?= $i + 1 ?></div>
                <div class="q-text"><?= htmlspecialchars($q['question_text']) ?></div>

                <?php if ($q['question_type'] === 'multiple_choice_single'): ?>
                    <?php foreach ($rd['opts'] as $opt): ?>
                    <?php
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

                <?php elseif ($q['question_type'] === 'gap_fill'): ?>
                    <input class="gap-input <?= $rd['correct'] ? 'correct' : 'wrong' ?>" value="<?= htmlspecialchars($rd['userAnswer']) ?>" readonly>
                    <?php if (!$rd['correct']): ?>
                    <div class="feedback-wrong"><i class="bi bi-x-circle me-1"></i>Correct answer: <strong><?= htmlspecialchars($rd['correctAnswer']) ?></strong></div>
                    <?php else: ?>
                    <div class="feedback-correct"><i class="bi bi-check-circle me-1"></i>Correct!</div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>

            <div class="d-flex gap-2 mt-2">
                <a href="word_quiz.php?word_id=<?= $wordId ?>" class="btn btn-outline-secondary flex-fill">Try Again</a>
                <a href="word.php?id=<?= $wordId ?>" class="btn btn-primary flex-fill" style="background:#0b77ff;border:none;">Back to Word</a>
            </div>

            <?php else: ?>
            <!-- ═══════════════════════════════════════════════════════════════
                 QUIZ FORM
            ════════════════════════════════════════════════════════════════ -->
            <form method="POST">
                <?php foreach ($questions as $i => $q): ?>
                <?php $qid = $q['id']; ?>
                <div class="q-card">
                    <div class="q-num">Question <?= $i + 1 ?></div>
                    <div class="q-text"><?= htmlspecialchars($q['question_text']) ?></div>

                    <?php if ($q['question_type'] === 'multiple_choice_single'): ?>
                        <?php foreach ($optsByQ[$qid] ?? [] as $opt): ?>
                        <label class="opt-label">
                            <input type="radio" name="q_<?= $qid ?>" value="<?= $opt['id'] ?>" required>
                            <span class="opt-key"><?= htmlspecialchars($opt['option_label']) ?></span>
                            <span class="opt-text"><?= htmlspecialchars($opt['option_text']) ?></span>
                        </label>
                        <?php endforeach; ?>

                    <?php elseif ($q['question_type'] === 'gap_fill'): ?>
                        <input type="text" name="q_<?= $qid ?>" class="gap-input" placeholder="Type your answer here" required autocomplete="off">
                    <?php endif; ?>
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
