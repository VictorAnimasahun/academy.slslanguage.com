<?php
// Foundational English Assessment -- general English level (do they need English lessons alongside exam prep?).
// Class 1 of every paid course. Opens straight onto the test (founder, 2026-10-21: no start screen that leads to another
// screen). The old public quiz at mini_tests/english-placement-test stays for the marketing site.
//
// Two forms built from the question bank (migration 167; database/placement/): Form A first, Form B on a retake, so a
// retake never repeats a question. 50 questions, 60 minutes, three parts (migration 168; 167's 30-question forms are
// off but their attempts still show). Each question belongs to a level (the sections table: Basic = A1-A2, B1, B2, C1, C2). The result is the highest level passed with every level below it
// passed too. Students see their level and what to review, never the answers (founder's bank, 2026-10-24).
require_once dirname(__DIR__, 2) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . ACADEMY_URL . 'edu_hub_registration.php?message=Please+login+to+take+the+assessment');
    exit();
}
$studentId = (int) $_SESSION['user_id'];

const FEA_FORMS   = ['FEA50_A', 'FEA50_B'];
const FEA_OLD     = ['FEA_FORM_A', 'FEA_FORM_B'];   // 30-question forms (migration 167): results only
const FEA_SECONDS = 3600;
const FEA_LEVELS  = ['Basic', 'B1', 'B2', 'C1', 'C2'];
// Pass mark per level: 70% of its questions, rounded up (7 of 10; on the old forms 4 of 5, 5 of 7, 3 of 4, as before).
// Provisional: the founder's bank asks for a pilot with 10-20 students of known level before it is relied on.
const FEA_PASS    = 0.7;
const FEA_PARTS   = [
    1 => ['Part 1: Grammar and vocabulary', 'Choose the word or phrase that best completes each sentence.'],
    2 => ['Part 2: Language in use', 'Choose the best answer. Each question tells you what to look for.'],
    3 => ['Part 3: Reading', 'Read each passage, then answer the questions about it.'],
];

$h = fn ($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
// **word** in the bank marks the word in bold (synonym and antonym questions).
$rich = fn ($v) => preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $h($v));

function fea_test(PDO $db, string $code): ?array {
    $st = $db->prepare("SELECT * FROM tests WHERE code = ? AND is_active = 1");
    $st->execute([$code]);
    return $st->fetch(PDO::FETCH_ASSOC) ?: null;
}

/** Questions with their level (section name), in order. */
function fea_questions(PDO $db, int $testId): array {
    $st = $db->prepare("SELECT q.*, s.name AS level FROM questions q LEFT JOIN sections s ON s.id = q.section_id
                         WHERE q.test_id = ? ORDER BY q.display_order, q.question_number");
    $st->execute([$testId]);
    return $st->fetchAll(PDO::FETCH_ASSOC);
}

/** The student's completed attempts at any form, old ones included, newest first. */
function fea_attempts(PDO $db, int $studentId): array {
    $codes = [...FEA_FORMS, ...FEA_OLD];
    $ph = implode(',', array_fill(0, count($codes), '?'));
    $st = $db->prepare("SELECT a.*, t.code FROM test_attempts a JOIN tests t ON t.id = a.test_id
                         WHERE a.student_id = ? AND a.status = 'completed' AND t.code IN ($ph) ORDER BY a.completed_at DESC, a.id DESC");
    $st->execute([$studentId, ...$codes]);
    return $st->fetchAll(PDO::FETCH_ASSOC);
}

/** Level, per-level and per-part scores for one attempt. */
function fea_result(PDO $db, array $attempt): array {
    $st = $db->prepare("SELECT s.name AS level, q.part_number, COALESCE(aa.score_awarded, 0) AS got
                          FROM questions q LEFT JOIN sections s ON s.id = q.section_id
                          LEFT JOIN attempt_answers aa ON aa.question_id = q.id AND aa.attempt_id = ?
                         WHERE q.test_id = ?");
    $st->execute([$attempt['id'], $attempt['test_id']]);
    $levels = []; $parts = [];
    foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) {
        $levels[$r['level']]['n'] = ($levels[$r['level']]['n'] ?? 0) + 1;
        $levels[$r['level']]['got'] = ($levels[$r['level']]['got'] ?? 0) + ($r['got'] > 0 ? 1 : 0);
        $parts[(int) $r['part_number']]['n'] = ($parts[(int) $r['part_number']]['n'] ?? 0) + 1;
        $parts[(int) $r['part_number']]['got'] = ($parts[(int) $r['part_number']]['got'] ?? 0) + ($r['got'] > 0 ? 1 : 0);
    }
    $placed = null;
    foreach (FEA_LEVELS as $level) {
        if (!isset($levels[$level]) || $levels[$level]['got'] < ceil(FEA_PASS * $levels[$level]['n'] - 1e-9)) break;
        $placed = $level;
    }
    ksort($parts);
    return ['placed' => $placed, 'levels' => $levels, 'parts' => $parts];
}

// ── Submit ───────────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($_POST['form'] ?? '', FEA_FORMS, true)) {
    $code = $_POST['form'];
    $test = fea_test($db, $code);
    if ($test) {
        $questions = fea_questions($db, (int) $test['id']);
        $ids = array_map('intval', array_column($questions, 'id'));
        $ph = implode(',', array_fill(0, count($ids), '?'));
        $st = $db->prepare("SELECT id, question_id, is_correct FROM question_options WHERE question_id IN ($ph)");
        $st->execute($ids);
        $opts = [];
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $o) $opts[(int) $o['id']] = $o;

        $answers = []; $score = 0;
        foreach ($questions as $q) {
            $sel = (int) ($_POST['q_' . $q['id']] ?? 0);
            $ok = isset($opts[$sel]) && (int) $opts[$sel]['question_id'] === (int) $q['id'];
            $got = ($ok && $opts[$sel]['is_correct']) ? (float) $q['points'] : 0.0;
            $score += $got;
            $answers[] = [(int) $q['id'], $ok ? $sel : null, $got];
        }
        $started = (int) ($_SESSION['fea_started'][$code] ?? time());
        $spent = max(0, min(time() - $started, 4 * FEA_SECONDS));

        $db->beginTransaction();
        $n = $db->prepare("SELECT COALESCE(MAX(attempt_number), 0) + 1 FROM test_attempts WHERE student_id = ? AND test_id = ?");
        $n->execute([$studentId, $test['id']]);
        $db->prepare("INSERT INTO test_attempts (student_id, test_id, attempt_number, mode, time_spent, started_at, completed_at, score, max_score, status)
                      VALUES (?, ?, ?, 'diagnostic', ?, FROM_UNIXTIME(?), NOW(), ?, ?, 'completed')")
           ->execute([$studentId, $test['id'], (int) $n->fetchColumn(), $spent, $started, $score, count($questions)]);
        $attemptId = (int) $db->lastInsertId();
        $ins = $db->prepare("INSERT INTO attempt_answers (attempt_id, question_id, selected_option_id, score_awarded) VALUES (?, ?, ?, ?)");
        foreach ($answers as [$qid, $sel, $got]) $ins->execute([$attemptId, $qid, $sel, $got]);
        $db->commit();

        unset($_SESSION['fea_started'][$code]);
        header('Location: foundational_english_assessment.php?result=' . $attemptId);
        exit();
    }
}

// ── What to show: a result, or the test ─────────────────────────────────────
$attempts = fea_attempts($db, $studentId);
$view = null;
if (isset($_GET['result'])) {
    foreach ($attempts as $a) if ((int) $a['id'] === (int) $_GET['result']) $view = $a;
} elseif ($attempts && !isset($_GET['retake'])) {
    $view = $attempts[0];
}

if ($view) {
    $result = fea_result($db, $view);
} else {
    // Form A first (also after an old 30-question form); after that, the form not taken last time.
    $last = $attempts ? array_search($attempts[0]['code'], FEA_FORMS, true) : false;
    $code = $last === false ? FEA_FORMS[0] : FEA_FORMS[($last + 1) % count(FEA_FORMS)];
    $test = fea_test($db, $code);
    $questions = $test ? fea_questions($db, (int) $test['id']) : [];
    if ($questions) {
        $ids = array_map('intval', array_column($questions, 'id'));
        $ph = implode(',', array_fill(0, count($ids), '?'));
        $st = $db->prepare("SELECT id, question_id, option_text FROM question_options WHERE question_id IN ($ph)");
        $st->execute($ids);
        $optsByQ = [];
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $o) $optsByQ[(int) $o['question_id']][] = $o;
        foreach ($optsByQ as &$list) shuffle($list);   // options in a new order each time (founder's bank)
        unset($list);
        // The full hour from when the test is opened. It used to keep running from the FIRST opening, so coming back
        // later showed 30 minutes left, or handed in a blank test (founder, 2026-10-24). Answers are not kept across a
        // reload, so opening it again gains nothing.
        $_SESSION['fea_started'][$code] = time();
        $secondsLeft = FEA_SECONDS;
    }
}

$levelNames = ['Basic' => 'A1–A2 (basic)', 'B1' => 'B1 (intermediate)', 'B2' => 'B2 (upper intermediate)', 'C1' => 'C1 (advanced)', 'C2' => 'C2 (proficient)'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Foundational English Assessment | EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= ACADEMY_URL ?>assets/css/courses.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        /* Flat exam look: borders only, no shadows. */
        .fea { max-width: 1160px; }
        .fea-intro { max-width: 780px; }
        /* Test: questions on the left at a readable width, a sticky panel on the right (timer, answered, jump to a
           question, submit). Below 992px the panel becomes a timer bar across the top. */
        .fea-layout { display: grid; grid-template-columns: minmax(0, 1fr) 270px; gap: 2.25rem; align-items: start; }
        .fea-side { position: sticky; top: 88px; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1rem; background: #fff; }
        .fea-side .fea-timer { position: static; border: 0; padding: 0; margin: 0 0 .2rem; }
        .fea-count { font-size: .82rem; color: #5b6678; }
        .fea-nav-part { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #64748b; margin: .9rem 0 .35rem; }
        .fea-dots { display: grid; grid-template-columns: repeat(6, 1fr); gap: .3rem; }
        .fea-dot { border: 1px solid #e2e8f0; border-radius: 6px; text-align: center; font-size: .78rem; padding: .3rem 0; color: #475569; text-decoration: none; }
        .fea-dot:hover { border-color: #0b77ff; color: #0b77ff; }
        .fea-dot.is-done { background: #0b77ff; border-color: #0b77ff; color: #fff; }
        .fea-side .fea-submit { width: 100%; margin-top: 1rem; }
        .fea-q { scroll-margin-top: 90px; }
        .fea-results { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        @media (max-width: 991.98px) {
            .fea-layout { grid-template-columns: minmax(0, 1fr); gap: 0; }
            .fea-side { order: -1; top: 72px; z-index: 5; padding: .6rem 1rem; margin: 1rem 0 .5rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
            .fea-side .fea-timer { margin: 0; gap: .6rem; }
            .fea-side .fea-nav, .fea-side .fea-submit { display: none; }
        }
        @media (max-width: 767.98px) { .fea-results { grid-template-columns: minmax(0, 1fr); gap: 0; } }
        .fea h1 { font-size: 1.5rem; font-weight: 700; color: #16202f; margin-bottom: .35rem; }
        .fea-intro { color: #5b6678; font-size: .92rem; }
        .fea-timer { position: sticky; top: 72px; z-index: 5; display: flex; justify-content: space-between; align-items: center; gap: 1rem;
                     background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: .6rem 1rem; margin: 1rem 0 1.25rem; font-size: .9rem; }
        .fea-timer strong { font-variant-numeric: tabular-nums; font-size: 1.05rem; }
        .fea-timer.is-low strong { color: #b91c1c; }
        .fea-part { margin: 2rem 0 .75rem; padding-bottom: .4rem; border-bottom: 1px solid #e2e8f0; }
        .fea-part h2 { font-size: 1.1rem; font-weight: 700; color: #16202f; margin: 0; }
        .fea-part p { margin: .2rem 0 0; color: #5b6678; font-size: .86rem; }
        .fea-passage { border: 1px solid #e2e8f0; border-radius: 10px; padding: 1rem 1.2rem; margin: 1.25rem 0 .75rem; background: #f8fafc;
                       font-size: .93rem; line-height: 1.7; color: #1e293b; }
        .fea-passage .lbl { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #64748b; margin-bottom: .4rem; }
        .fea-q { border-bottom: 1px solid #eef1f5; padding: 1rem 0; }
        .fea-q-num { font-size: .75rem; font-weight: 700; color: #64748b; margin-bottom: .25rem; }
        .fea-q-ins { font-size: .8rem; color: #64748b; margin-bottom: .3rem; }
        .fea-q-text { font-size: .97rem; color: #16202f; margin-bottom: .6rem; }
        .fea-opt { display: flex; gap: .6rem; align-items: flex-start; border: 1px solid #e2e8f0; border-radius: 8px; padding: .5rem .75rem; margin-bottom: .35rem; cursor: pointer; font-size: .92rem; }
        .fea-opt:hover { border-color: #0b77ff; }
        .fea-opt input { margin-top: .25rem; }
        .fea-opt:has(input:checked) { border-color: #0b77ff; background: #eaf3ff; }
        .fea-opt b { color: #475569; min-width: 1rem; }
        .fea-submit { background: #0b77ff; color: #fff; border: 0; border-radius: 8px; padding: .7rem 1.6rem; font-weight: 600; margin-top: 1.5rem; }
        .fea-box { border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.2rem 1.4rem; margin-bottom: 1rem; }
        .fea-level { font-size: 1.6rem; font-weight: 700; color: #0b3d91; }
        .fea-table { width: 100%; font-size: .9rem; }
        .fea-table td { padding: .35rem 0; border-bottom: 1px solid #eef1f5; }
        .fea-table td:last-child { text-align: right; color: #5b6678; }
        body.dark .fea h1, body.dark .fea-part h2, body.dark .fea-q-text { color: #e2e8f0; }
        body.dark .fea-timer, body.dark .fea-passage { background: #1e293b; border-color: #334155; color: #e2e8f0; }
        body.dark .fea-opt, body.dark .fea-box, body.dark .fea-dot { border-color: #334155; }
        body.dark .fea-side { background: #1e293b; border-color: #334155; }
        body.dark .fea-dot { color: #cbd5e1; }
        body.dark .fea-dot.is-done { background: #0b77ff; border-color: #0b77ff; color: #fff; }
        body.dark .fea-level { color: #93c5fd; }
    </style>
</head>
<body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <main class="main-wrapper">
        <div class="course-card fea">
            <h1>Foundational English Assessment</h1>

<?php if ($view): $r = $result; ?>
            <p class="fea-intro">Taken <?= $h(date('j F Y', strtotime($view['completed_at']))) ?> &middot; <?= (int) $view['score'] ?> of <?= (int) $view['max_score'] ?> correct</p>

            <div class="fea-box">
                <div class="text-muted small mb-1">Your general English level</div>
                <div class="fea-level"><?= $r['placed'] ? $h($levelNames[$r['placed']]) : 'Below A2' ?></div>
                <p class="mb-0 mt-2" style="font-size:.93rem;">
                <?php if (!$r['placed'] || $r['placed'] === 'Basic'): ?>
                    We recommend general English lessons alongside your exam preparation. Your tutor will talk to you about the best way to do this.
                <?php elseif ($r['placed'] === 'B1'): ?>
                    You are ready for exam preparation. Strengthening your general English in the areas below will help your score.
                <?php else: ?>
                    Your general English is a strong base for exam preparation.
                <?php endif; ?>
                </p>
            </div>

            <div class="fea-results">
            <div class="fea-box">
                <h2 style="font-size:1rem;font-weight:700;margin:0 0 .5rem;">By part</h2>
                <table class="fea-table">
                    <?php foreach ($r['parts'] as $p => $s): ?>
                        <tr><td><?= $h(substr(FEA_PARTS[$p][0] ?? "Part $p", strpos(FEA_PARTS[$p][0] ?? '', ':') + 2)) ?></td><td><?= $s['got'] ?> of <?= $s['n'] ?></td></tr>
                    <?php endforeach; ?>
                </table>
                <?php $review = array_filter($r['parts'], fn ($s) => $s['got'] < 0.7 * $s['n']); ?>
                <?php if ($review): ?>
                    <p class="mt-3 mb-0" style="font-size:.9rem;"><strong>To review:</strong>
                        <?= $h(implode(', ', array_map(fn ($p) => strtolower(substr(FEA_PARTS[$p][0], strpos(FEA_PARTS[$p][0], ':') + 2)), array_keys($review)))) ?>.</p>
                <?php endif; ?>
            </div>

            <div class="fea-box">
                <h2 style="font-size:1rem;font-weight:700;margin:0 0 .5rem;">By level</h2>
                <table class="fea-table">
                    <?php foreach (FEA_LEVELS as $lv): if (!isset($r['levels'][$lv])) continue; $s = $r['levels'][$lv]; ?>
                        <tr><td><?= $h($levelNames[$lv]) ?></td><td><?= $s['got'] ?> of <?= $s['n'] ?></td></tr>
                    <?php endforeach; ?>
                </table>
            </div>
            </div>

            <a href="foundational_english_assessment.php?retake=1" class="btn btn-outline-secondary btn-sm">Take it again (new questions)</a>
            <a href="<?= ACADEMY_URL ?>learning_dashboard.php" class="btn btn-link btn-sm">Back to dashboard</a>

<?php elseif (empty($questions)): ?>
            <p class="fea-intro">This assessment isn't available right now. Please check back soon.</p>

<?php else: ?>
            <p class="fea-intro"><?= count($questions) ?> questions, one hour. Answer on your own, without a dictionary or translator. If you don't know an answer, choose the one you think is best. Your result shows your general English level and what to work on.</p>

            <div class="fea-layout">
            <form method="post" id="feaForm">
                <input type="hidden" name="form" value="<?= $h($code) ?>">
                <?php $part = 0; $passage = null; foreach ($questions as $i => $q): ?>
                    <?php if ((int) $q['part_number'] !== $part): $part = (int) $q['part_number']; $passage = null; ?>
                        <div class="fea-part"><h2><?= $h(FEA_PARTS[$part][0] ?? "Part $part") ?></h2><p><?= $h(FEA_PARTS[$part][1] ?? '') ?></p></div>
                    <?php endif; ?>
                    <?php if ($q['stimulus_text'] && $q['stimulus_text'] !== $passage): $passage = $q['stimulus_text']; ?>
                        <div class="fea-passage"><div class="lbl">Passage</div><?= $h($passage) ?></div>
                    <?php endif; ?>
                    <div class="fea-q" id="q<?= $i + 1 ?>">
                        <div class="fea-q-num">Question <?= $i + 1 ?></div>
                        <?php if ($part === 2 && $q['instructions']): ?><div class="fea-q-ins"><?= $h($q['instructions']) ?></div><?php endif; ?>
                        <div class="fea-q-text"><?= $rich($q['question_text']) ?></div>
                        <?php foreach ($optsByQ[(int) $q['id']] ?? [] as $k => $o): ?>
                            <label class="fea-opt"><input type="radio" name="q_<?= (int) $q['id'] ?>" value="<?= (int) $o['id'] ?>">
                                <b><?= chr(65 + $k) ?></b><span><?= $h($o['option_text']) ?></span></label>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
                <button type="submit" class="fea-submit">Submit my answers</button>
            </form>
            <aside class="fea-side">
                <div class="fea-timer" id="feaTimer"><span>Time left</span><strong data-left="<?= (int) $secondsLeft ?>">60:00</strong></div>
                <div class="fea-count"><span id="feaAnswered">0</span> of <?= count($questions) ?> answered</div>
                <div class="fea-nav">
                    <?php $part = 0; foreach ($questions as $i => $q): ?>
                        <?php if ((int) $q['part_number'] !== $part): ?>
                            <?php if ($part): ?></div><?php endif; $part = (int) $q['part_number']; ?>
                            <div class="fea-nav-part"><?= $h(preg_replace('/^Part \d+: /', '', FEA_PARTS[$part][0] ?? "Part $part")) ?></div><div class="fea-dots">
                        <?php endif; ?>
                        <a href="#q<?= $i + 1 ?>" class="fea-dot" data-q="<?= (int) $q['id'] ?>"><?= $i + 1 ?></a>
                    <?php endforeach; ?></div>
                </div>
                <button type="submit" form="feaForm" class="fea-submit">Submit my answers</button>
            </aside>
            </div>
            <script>
            (function () {
                var el = document.querySelector('#feaTimer strong'), box = document.getElementById('feaTimer'),
                    form = document.getElementById('feaForm'), end = Date.now() + parseInt(el.dataset.left, 10) * 1000, sent = false;
                var count = document.getElementById('feaAnswered');
                form.addEventListener('change', function (e) {   // mark the question's number in the panel as answered
                    var dot = document.querySelector('.fea-dot[data-q="' + e.target.name.slice(2) + '"]');
                    if (dot) dot.classList.add('is-done');
                    count.textContent = form.querySelectorAll('.fea-q input:checked').length;
                });
                form.addEventListener('submit', function (e) {
                    if (sent) return;
                    var blank = form.querySelectorAll('.fea-q').length - form.querySelectorAll('.fea-q input:checked').length;
                    if (blank > 0 && Date.now() < end && !confirm(blank + ' question' + (blank > 1 ? 's are' : ' is') + ' not answered. Submit anyway?')) { e.preventDefault(); return; }
                    sent = true;
                });
                function tick() {
                    var s = Math.max(0, Math.round((end - Date.now()) / 1000));
                    el.textContent = Math.floor(s / 60) + ':' + ('0' + s % 60).slice(-2);
                    box.classList.toggle('is-low', s <= 300);
                    if (s === 0) { if (!sent) { sent = true; form.submit(); } return; }   // time's up: hand it in as it is
                    setTimeout(tick, 1000);
                }
                tick();
            })();
            </script>
<?php endif; ?>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
