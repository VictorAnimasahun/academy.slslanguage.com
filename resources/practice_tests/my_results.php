<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}

if (!function_exists('e')) {
    function e(mixed $v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
}

$student_id   = (int)$_SESSION['user_id'];
$userFirstname = $_SESSION['user_firstname'] ?? 'Learner';
$userLastname  = $_SESSION['user_lastname']  ?? '';
$userFullName  = trim($userFirstname . ' ' . $userLastname) ?: 'Learner';

// ── Determine mode ─────────────────────────────────────────────────────────────
$sessionId = isset($_GET['session_id'])  ? (int)$_GET['session_id']  : 0;
$attemptId = isset($_GET['attempt_id']) ? (int)$_GET['attempt_id'] : 0;

// ── Mock session detail ────────────────────────────────────────────────────────
$mockSession = null;
$listeningAnswers = $readingAnswers = $writingEssays = $readingGroups = [];

if ($sessionId) {
    $st = $db->prepare("
        SELECT ms.*, t.title AS mock_title, t.code AS mock_code,
               ta_l.id AS l_att_id, ta_l.band_score AS l_band, ta_l.score AS l_score, ta_l.max_score AS l_max,
               ta_r.id AS r_att_id, ta_r.band_score AS r_band, ta_r.score AS r_score, ta_r.max_score AS r_max,
               ta_w.id AS w_att_id
        FROM mock_sessions ms
        JOIN tests t ON t.id = ms.mock_test_id
        LEFT JOIN test_attempts ta_l ON ta_l.id = ms.listening_attempt_id
        LEFT JOIN test_attempts ta_r ON ta_r.id = ms.reading_attempt_id
        LEFT JOIN test_attempts ta_w ON ta_w.id = ms.writing_attempt_id
        WHERE ms.id = ? AND ms.student_id = ? AND ms.status = 'results_released'
    ");
    $st->execute([$sessionId, $student_id]);
    $mockSession = $st->fetch(PDO::FETCH_ASSOC);

    if ($mockSession) {
        // Listening answers
        $st = $db->prepare("
            SELECT q.question_number, q.question_text, q.question_type,
                   aa.answer_text AS student_answer, aa.score_awarded,
                   qo.option_label AS student_label, qo.option_text AS student_option,
                   (SELECT c.answer_text FROM question_correct_answers c WHERE c.question_id=q.id AND c.is_alternative=0 LIMIT 1) AS correct_answer,
                   (SELECT o2.option_label FROM question_options o2 WHERE o2.question_id=q.id AND o2.is_correct=1 LIMIT 1) AS correct_label,
                   (SELECT o2.option_text  FROM question_options o2 WHERE o2.question_id=q.id AND o2.is_correct=1 LIMIT 1) AS correct_option
            FROM attempt_answers aa
            JOIN questions q ON q.id = aa.question_id
            LEFT JOIN question_options qo ON qo.id = aa.selected_option_id
            WHERE aa.attempt_id = ?
            ORDER BY q.question_number
        ");
        $st->execute([$mockSession['l_att_id']]);
        $listeningAnswers = $st->fetchAll(PDO::FETCH_ASSOC);

        // Reading answers
        $st = $db->prepare("
            SELECT q.question_number, q.question_text, q.question_type, q.stimulus_text,
                   aa.answer_text AS student_answer, aa.score_awarded,
                   qo.option_label AS student_label, qo.option_text AS student_option,
                   (SELECT c.answer_text FROM question_correct_answers c WHERE c.question_id=q.id AND c.is_alternative=0 LIMIT 1) AS correct_answer,
                   (SELECT o2.option_label FROM question_options o2 WHERE o2.question_id=q.id AND o2.is_correct=1 LIMIT 1) AS correct_label,
                   (SELECT o2.option_text  FROM question_options o2 WHERE o2.question_id=q.id AND o2.is_correct=1 LIMIT 1) AS correct_option
            FROM attempt_answers aa
            JOIN questions q ON q.id = aa.question_id
            LEFT JOIN question_options qo ON qo.id = aa.selected_option_id
            WHERE aa.attempt_id = ?
            ORDER BY q.question_number
        ");
        $st->execute([$mockSession['r_att_id']]);
        $readingAnswers = $st->fetchAll(PDO::FETCH_ASSOC);

        // Group reading by passage
        $lastStim = null; $gi = -1;
        foreach ($readingAnswers as $a) {
            if ($a['stimulus_text'] && $a['stimulus_text'] !== $lastStim) {
                $lastStim = $a['stimulus_text']; $gi++;
                $readingGroups[$gi] = ['stimulus' => $a['stimulus_text'], 'questions' => []];
            }
            if ($gi < 0) { $gi = 0; $readingGroups[$gi] = ['stimulus' => null, 'questions' => []]; }
            $readingGroups[$gi]['questions'][] = $a;
        }

        // Writing essays
        $st = $db->prepare("
            SELECT q.question_number, q.question_text AS prompt,
                   aa.answer_text AS essay, aa.score_awarded AS band
            FROM attempt_answers aa
            JOIN questions q ON q.id = aa.question_id
            WHERE aa.attempt_id = ?
            ORDER BY q.question_number
        ");
        $st->execute([$mockSession['w_att_id']]);
        $writingEssays = $st->fetchAll(PDO::FETCH_ASSOC);
    }
}

// ── Practice test detail ───────────────────────────────────────────────────────
$practiceDetail = null;
$practiceAnswers = [];

if ($attemptId) {
    $st = $db->prepare("
        SELECT ta.id, ta.score, ta.max_score, ta.band_score, ta.completed_at,
               t.title, t.category
        FROM test_attempts ta
        JOIN tests t ON t.id = ta.test_id
        WHERE ta.id = ? AND ta.student_id = ?
          AND (ta.mode = 'practice' OR ta.mode IS NULL)
    ");
    $st->execute([$attemptId, $student_id]);
    $practiceDetail = $st->fetch(PDO::FETCH_ASSOC);

    if ($practiceDetail) {
        $st = $db->prepare("
            SELECT q.question_number, q.question_text, q.question_type,
                   aa.answer_text AS student_answer, aa.score_awarded,
                   qo.option_text AS student_option,
                   (SELECT answer_text FROM question_correct_answers WHERE question_id=q.id AND is_alternative=0 LIMIT 1) AS correct_answer,
                   (SELECT option_text  FROM question_options       WHERE question_id=q.id AND is_correct=1 LIMIT 1)    AS correct_option
            FROM attempt_answers aa
            JOIN questions q ON q.id = aa.question_id
            LEFT JOIN question_options qo ON qo.id = aa.selected_option_id
            WHERE aa.attempt_id = ?
            ORDER BY q.question_number
        ");
        $st->execute([$attemptId]);
        $practiceAnswers = $st->fetchAll(PDO::FETCH_ASSOC);
    }
}

// ── List data ──────────────────────────────────────────────────────────────────
$mockSessions = [];
$practiceAttempts = [];

if (!$sessionId && !$attemptId) {
    // Released mock sessions
    $st = $db->prepare("
        SELECT ms.id, ms.status, ms.overall_band, ms.writing_band, ms.created_at, ms.released_at,
               ms.speaking_band, ms.speaking_notes,
               t.title AS mock_title, t.code AS mock_code,
               ta_l.band_score AS l_band, ta_l.score AS l_score, ta_l.max_score AS l_max,
               ta_r.band_score AS r_band, ta_r.score AS r_score, ta_r.max_score AS r_max
        FROM mock_sessions ms
        JOIN tests t ON t.id = ms.mock_test_id
        LEFT JOIN test_attempts ta_l ON ta_l.id = ms.listening_attempt_id
        LEFT JOIN test_attempts ta_r ON ta_r.id = ms.reading_attempt_id
        WHERE ms.student_id = ?
        ORDER BY ms.created_at DESC
    ");
    $st->execute([$student_id]);
    $mockSessions = $st->fetchAll(PDO::FETCH_ASSOC);

    // Practice attempts
    $st = $db->prepare("
        SELECT ta.id, ta.score, ta.max_score, ta.band_score, ta.completed_at,
               t.title, t.category, t.code
        FROM test_attempts ta
        JOIN tests t ON t.id = ta.test_id
        WHERE ta.student_id = ? AND ta.status = 'completed'
          AND (ta.mode = 'practice' OR ta.mode IS NULL)
        ORDER BY ta.completed_at DESC
    ");
    $st->execute([$student_id]);
    $practiceAttempts = $st->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Results | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        :root {
            --blue:       #0ea5e9;
            --blue-light: #e0f2fe;
            --blue-dark:  #0369a1;
            --pink:       #ec4899;
            --pink-light: #fce7f3;
            --pink-dark:  #be185d;
        }

        /* ── List cards ─────────────────────────────── */
        .result-card { background:#fff; border-radius:14px; padding:1.1rem 1.4rem; margin-bottom:.75rem; box-shadow:0 2px 8px rgba(0,0,0,.06); display:flex; align-items:center; gap:1.1rem; border-left:4px solid var(--blue); }
        .result-card.mock-card { border-left-color: var(--pink); }
        .result-card:hover { box-shadow:0 4px 18px rgba(0,0,0,.1); }
        .band-circle { width:52px; height:52px; border-radius:50%; background:linear-gradient(135deg,var(--blue),#38bdf8); display:flex; flex-direction:column; align-items:center; justify-content:center; flex-shrink:0; color:#fff; }
        .band-circle.pink { background:linear-gradient(135deg,var(--pink),#f472b6); }
        .band-circle .bval { font-size:1rem; font-weight:800; line-height:1; }
        .band-circle .blbl { font-size:.52rem; font-weight:600; opacity:.85; }
        .status-pill { border-radius:999px; padding:.2rem .65rem; font-size:.72rem; font-weight:700; }
        .pill-released { background:var(--blue-light); color:var(--blue-dark); }
        .pill-pending  { background:#fef3c7; color:#92400e; }
        .pill-progress { background:#f0fdf4; color:#166534; }
        .mini-bands { display:flex; gap:.4rem; font-size:.72rem; color:#64748b; }
        .mini-bands span { font-weight:700; }
        .btn-pdf { border:1px solid var(--blue); color:var(--blue); border-radius:6px; padding:.2rem .65rem; font-size:.75rem; font-weight:700; background:none; cursor:pointer; white-space:nowrap; }
        .btn-pdf:hover { background:var(--blue-light); }
        .btn-view { background:var(--blue); color:#fff; border:none; border-radius:7px; padding:.3rem .85rem; font-size:.78rem; font-weight:700; text-decoration:none; white-space:nowrap; }
        .btn-view:hover { background:var(--blue-dark); color:#fff; }
        .section-head { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:#64748b; margin:1.5rem 0 .6rem; display:flex; align-items:center; gap:.5rem; }
        .section-divider { flex:1; height:1px; background:#e2e8f0; }

        /* ── Band bar (mock detail) ──────────────────── */
        .band-bar { display:grid; grid-template-columns:repeat(5,1fr); gap:.6rem; margin-bottom:1.5rem; }
        .band-box { background:#fff; border-radius:12px; padding:.85rem; text-align:center; border:1px solid #e2e8f0; box-shadow:0 1px 4px rgba(0,0,0,.04); }
        .band-box.overall { background:linear-gradient(135deg,var(--blue-light),var(--pink-light)); }
        .band-num { font-size:1.75rem; font-weight:900; color:var(--blue); line-height:1; }
        .band-num.pink { color:var(--pink); }
        .band-name { font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#64748b; margin-top:.25rem; }
        .band-raw  { font-size:.65rem; color:#94a3b8; margin-top:.1rem; }

        /* ── Section trigger buttons ─────────────────── */
        .section-btns { display:grid; grid-template-columns:repeat(3,1fr); gap:.75rem; margin-bottom:1.25rem; }
        .section-btn { background:#fff; border:2px solid #e2e8f0; border-radius:12px; padding:1rem; cursor:pointer; text-align:left; transition:all .18s; }
        .section-btn:hover { border-color:var(--blue); box-shadow:0 4px 12px rgba(14,165,233,.15); }
        .section-btn .sicon { font-size:1.3rem; margin-bottom:.35rem; display:block; }
        .section-btn .stitle { font-weight:700; font-size:.88rem; color:#0f172a; }
        .section-btn .ssub   { font-size:.72rem; color:#64748b; margin-top:.15rem; }
        .section-btn .sscore { font-size:1rem; font-weight:800; color:var(--blue); margin-top:.3rem; }

        /* ── Modal ───────────────────────────────────── */
        .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.55); z-index:2000; align-items:center; justify-content:center; }
        .modal-overlay.open { display:flex; }
        .modal-box { background:#fff; border-radius:16px; width:92vw; max-width:1000px; height:88vh; display:flex; flex-direction:column; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,.25); }
        .modal-head { padding:1rem 1.4rem; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; flex-shrink:0; background:linear-gradient(135deg,var(--blue-light),var(--pink-light)); }
        .modal-title { font-weight:800; font-size:.95rem; color:#0f172a; }
        .modal-close { background:none; border:none; font-size:1.3rem; cursor:pointer; color:#64748b; padding:.2rem .4rem; border-radius:6px; }
        .modal-close:hover { background:#e2e8f0; }
        .modal-body { flex:1; overflow-y:auto; padding:1.25rem; }

        /* ── Answer table ────────────────────────────── */
        .ans-table { width:100%; border-collapse:collapse; font-size:.84rem; }
        .ans-table th { position:sticky; top:0; background:#f8fafc; padding:.5rem .7rem; text-align:left; font-size:.72rem; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:.05em; border-bottom:2px solid #e2e8f0; z-index:1; }
        .ans-table td { padding:.55rem .7rem; border-bottom:1px solid #f3f4f6; vertical-align:top; }
        .ans-table tr.correct td { background:#f0f9ff; }
        .ans-table tr.wrong   td { background:#fff5f5; }
        .ans-table tr:last-child td { border-bottom:none; }
        .q-num { font-weight:700; color:#94a3b8; text-align:center; width:32px; }
        .pill-c { background:var(--blue-light); color:var(--blue-dark); padding:.12rem .45rem; border-radius:999px; font-size:.68rem; font-weight:700; white-space:nowrap; }
        .pill-w { background:var(--pink-light);  color:var(--pink-dark);  padding:.12rem .45rem; border-radius:999px; font-size:.68rem; font-weight:700; white-space:nowrap; }
        .correct-val { color:var(--blue-dark); font-weight:600; font-size:.82rem; }
        .passage-block { background:#f8fafc; border-left:4px solid var(--blue); border-radius:0 10px 10px 0; padding:.9rem 1.1rem; margin-bottom:1rem; font-size:.84rem; line-height:1.8; color:#1e293b; white-space:pre-wrap; }
        .passage-label { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--blue-dark); margin-bottom:.4rem; }

        /* ── Writing ─────────────────────────────────── */
        .task-block { margin-bottom:1.75rem; }
        .task-head  { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--pink-dark); margin-bottom:.4rem; }
        .prompt-box { background:var(--blue-light); border-radius:10px; padding:.9rem 1.1rem; font-size:.86rem; line-height:1.75; color:#0f172a; margin-bottom:.65rem; white-space:pre-wrap; }
        .essay-box  { background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:.9rem 1.1rem; font-size:.86rem; line-height:1.8; color:#374151; white-space:pre-wrap; }
        .ai-box     { background:var(--blue-light); border-left:3px solid var(--blue); border-radius:6px; padding:.9rem 1rem; font-size:.82rem; white-space:pre-wrap; color:#0f172a; margin-top:1.25rem; }

        /* ── Speaking notes ──────────────────────────── */
        .speaking-panel { background:linear-gradient(135deg,var(--blue-light),var(--pink-light)); border-radius:14px; padding:1.25rem 1.5rem; margin-bottom:1.25rem; }
        .speaking-panel .snotes { background:var(--pink-light); border-left:3px solid var(--pink); border-radius:6px; padding:.8rem 1rem; font-size:.86rem; color:#0f172a; margin-top:.5rem; }

        /* ── Practice detail ─────────────────────────── */
        .detail-card { background:#fff; border-radius:14px; padding:1.4rem; box-shadow:0 2px 10px rgba(0,0,0,.06); }
        .stat-box { background:#f8fafc; border-radius:10px; padding:.7rem 1rem; text-align:center; }
        .stat-box .val { font-size:1.35rem; font-weight:800; color:var(--blue); }
        .stat-box .lbl { font-size:.7rem; color:#64748b; }
        .q-row { display:flex; align-items:flex-start; gap:.9rem; padding:.55rem 0; border-bottom:1px solid #f3f4f6; font-size:.86rem; }
        .q-row:last-child { border-bottom:none; }
        .q-row .q-n { width:26px; text-align:center; font-weight:700; color:#94a3b8; flex-shrink:0; }
        .q-row .q-t { flex:1; color:#374151; }
        .q-row .q-a { min-width:110px; text-align:right; flex-shrink:0; }
        .correct   { color:#10b981; font-weight:600; }
        .incorrect { color:#ef4444; font-weight:600; }
        .back-link { display:inline-flex; align-items:center; gap:.35rem; color:#64748b; font-size:.86rem; text-decoration:none; margin-bottom:1.1rem; }
        .back-link:hover { color:#111; }
    </style>
</head>
<body>
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="mobile-overlay" id="mobileOverlay"></div>
<?php include INCLUDES_PATH . '/navbar.php'; ?>

<div class="flex-grow-1" style="padding:1.75rem 1.5rem;">
    <?php include INCLUDES_PATH . '/topbar.php'; ?>
    <main class="content p-2" style="max-width:900px;">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../../learning_dashboard.php">Dashboard</a></li>
            <?php if ($mockSession): ?>
                <li class="breadcrumb-item"><a href="my_results.php">My Results</a></li>
                <li class="breadcrumb-item active"><?= e($mockSession['mock_title']) ?></li>
            <?php elseif ($practiceDetail): ?>
                <li class="breadcrumb-item"><a href="my_results.php">My Results</a></li>
                <li class="breadcrumb-item active"><?= e($practiceDetail['title']) ?></li>
            <?php else: ?>
                <li class="breadcrumb-item active">My Results</li>
            <?php endif; ?>
        </ol>
    </nav>

<?php if ($mockSession): ?>
<!-- ══════════════════════════════════════════════════════════════════
     MOCK SESSION DETAIL
══════════════════════════════════════════════════════════════════════ -->
    <a href="my_results.php" class="back-link"><i class="bi bi-arrow-left"></i> All Results</a>

    <!-- Header -->
    <div style="background:linear-gradient(135deg,var(--blue-light),var(--pink-light));border-radius:14px;padding:1.25rem 1.5rem;margin-bottom:1.25rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;">
        <div>
            <div style="font-size:1.15rem;font-weight:800;color:#0f172a;"><?= e($mockSession['mock_title']) ?></div>
            <div style="font-size:.82rem;color:#475569;margin-top:.2rem;">
                <span style="background:var(--blue);color:#fff;padding:.2rem .6rem;border-radius:6px;font-size:.72rem;font-weight:700;margin-right:.5rem;"><?= e($mockSession['mock_code']) ?></span>
                Released <?= date('d M Y', strtotime($mockSession['released_at'])) ?>
            </div>
        </div>
        <button onclick="downloadMockPDF(<?= htmlspecialchars(json_encode([
            'title'   => $mockSession['mock_title'],
            'date'    => date('d M Y', strtotime($mockSession['released_at'])),
            'overall' => number_format((float)$mockSession['overall_band'], 1),
            'l'       => number_format((float)$mockSession['l_band'], 1),
            'l_score' => (int)$mockSession['l_score'] . '/' . (int)$mockSession['l_max'],
            'r'       => number_format((float)$mockSession['r_band'], 1),
            'r_score' => (int)$mockSession['r_score'] . '/' . (int)$mockSession['r_max'],
            'w'       => number_format((float)$mockSession['writing_band'], 1),
            's'       => number_format((float)$mockSession['speaking_band'], 1),
            's_notes' => $mockSession['speaking_notes'] ?? '',
            'name'    => $userFullName,
        ])) ?>)" class="btn-pdf" style="font-size:.82rem;padding:.35rem 1rem;">
            ↓ Download PDF Report
        </button>
    </div>

    <!-- Band bar -->
    <div class="band-bar">
        <div class="band-box">
            <div class="band-num"><?= number_format((float)$mockSession['l_band'],1) ?></div>
            <div class="band-name">Listening</div>
            <div class="band-raw"><?= (int)$mockSession['l_score'] ?>/<?= (int)$mockSession['l_max'] ?></div>
        </div>
        <div class="band-box">
            <div class="band-num"><?= number_format((float)$mockSession['r_band'],1) ?></div>
            <div class="band-name">Reading</div>
            <div class="band-raw"><?= (int)$mockSession['r_score'] ?>/<?= (int)$mockSession['r_max'] ?></div>
        </div>
        <div class="band-box">
            <div class="band-num"><?= number_format((float)$mockSession['writing_band'],1) ?></div>
            <div class="band-name">Writing</div>
            <div class="band-raw">AI-graded</div>
        </div>
        <div class="band-box">
            <div class="band-num"><?= number_format((float)$mockSession['speaking_band'],1) ?></div>
            <div class="band-name">Speaking</div>
            <div class="band-raw">Instructor</div>
        </div>
        <div class="band-box overall">
            <div class="band-num pink"><?= number_format((float)$mockSession['overall_band'],1) ?></div>
            <div class="band-name">Overall</div>
        </div>
    </div>

    <!-- Speaking notes -->
    <?php if ($mockSession['speaking_notes']): ?>
    <div class="speaking-panel">
        <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--pink-dark);">Instructor Speaking Notes</div>
        <div class="snotes"><?= e($mockSession['speaking_notes']) ?></div>
    </div>
    <?php endif; ?>

    <!-- Section buttons -->
    <div class="section-btns">
        <button class="section-btn" onclick="openModal('listening')">
            <span class="sicon">🎧</span>
            <div class="stitle">Listening</div>
            <div class="ssub"><?= count($listeningAnswers) ?> questions</div>
            <div class="sscore">Band <?= number_format((float)$mockSession['l_band'],1) ?> · <?= (int)$mockSession['l_score'] ?>/<?= (int)$mockSession['l_max'] ?></div>
        </button>
        <button class="section-btn" onclick="openModal('reading')">
            <span class="sicon">📖</span>
            <div class="stitle">Reading</div>
            <div class="ssub"><?= count($readingAnswers) ?> questions · <?= count($readingGroups) ?> passage<?= count($readingGroups) !== 1 ? 's' : '' ?></div>
            <div class="sscore">Band <?= number_format((float)$mockSession['r_band'],1) ?> · <?= (int)$mockSession['r_score'] ?>/<?= (int)$mockSession['r_max'] ?></div>
        </button>
        <button class="section-btn" onclick="openModal('writing')">
            <span class="sicon">✍️</span>
            <div class="stitle">Writing</div>
            <div class="ssub"><?= count($writingEssays) ?> task<?= count($writingEssays) !== 1 ? 's' : '' ?></div>
            <div class="sscore">Band <?= number_format((float)$mockSession['writing_band'],1) ?> (AI)</div>
        </button>
    </div>

    <!-- LISTENING MODAL -->
    <div class="modal-overlay" id="modal-listening" onclick="closeOnBg(event,'listening')">
        <div class="modal-box">
            <div class="modal-head">
                <div class="modal-title">🎧 Listening — Band <?= number_format((float)$mockSession['l_band'],1) ?> &nbsp;·&nbsp; <?= (int)$mockSession['l_score'] ?>/<?= (int)$mockSession['l_max'] ?></div>
                <button class="modal-close" onclick="closeModal('listening')">✕</button>
            </div>
            <div class="modal-body">
                <?php if (empty($listeningAnswers)): ?>
                    <p class="text-muted">No answers recorded.</p>
                <?php else: ?>
                <table class="ans-table">
                    <thead><tr><th>#</th><th>Question</th><th>Your Answer</th><th>Correct Answer</th><th>Result</th></tr></thead>
                    <tbody>
                    <?php foreach ($listeningAnswers as $a):
                        $ok = (float)$a['score_awarded'] >= 1.0;
                        $stu = $a['student_label'] ? $a['student_label'].'. '.$a['student_option'] : ($a['student_answer'] ?: '—');
                        $cor = $a['correct_label']  ? $a['correct_label'].'. '.$a['correct_option']  : ($a['correct_answer']  ?: '—');
                    ?>
                    <tr class="<?= $ok ? 'correct' : 'wrong' ?>">
                        <td class="q-num"><?= $a['question_number'] ?></td>
                        <td><?= e($a['question_text'] ?? '') ?></td>
                        <td><?= e($stu) ?></td>
                        <td class="correct-val"><?= e($cor) ?></td>
                        <td><span class="<?= $ok ? 'pill-c' : 'pill-w' ?>"><?= $ok ? '✓ Correct' : '✗ Wrong' ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- READING MODAL -->
    <div class="modal-overlay" id="modal-reading" onclick="closeOnBg(event,'reading')">
        <div class="modal-box">
            <div class="modal-head">
                <div class="modal-title">📖 Reading — Band <?= number_format((float)$mockSession['r_band'],1) ?> &nbsp;·&nbsp; <?= (int)$mockSession['r_score'] ?>/<?= (int)$mockSession['r_max'] ?></div>
                <button class="modal-close" onclick="closeModal('reading')">✕</button>
            </div>
            <div class="modal-body">
                <?php if (empty($readingGroups)): ?>
                    <p class="text-muted">No answers recorded.</p>
                <?php else: ?>
                    <?php foreach ($readingGroups as $gi => $grp): ?>
                        <?php if ($grp['stimulus']): ?>
                        <div class="passage-label">Passage <?= $gi+1 ?></div>
                        <div class="passage-block"><?= e($grp['stimulus']) ?></div>
                        <?php endif; ?>
                        <table class="ans-table" style="margin-bottom:1.75rem;">
                            <thead><tr><th>#</th><th>Question</th><th>Your Answer</th><th>Correct Answer</th><th>Result</th></tr></thead>
                            <tbody>
                            <?php foreach ($grp['questions'] as $a):
                                $ok = (float)$a['score_awarded'] >= 1.0;
                                $stu = $a['student_label'] ? $a['student_label'].'. '.$a['student_option'] : ($a['student_answer'] ?: '—');
                                $cor = $a['correct_label']  ? $a['correct_label'].'. '.$a['correct_option']  : ($a['correct_answer']  ?: '—');
                            ?>
                            <tr class="<?= $ok ? 'correct' : 'wrong' ?>">
                                <td class="q-num"><?= $a['question_number'] ?></td>
                                <td><?= e($a['question_text'] ?? '') ?></td>
                                <td><?= e($stu) ?></td>
                                <td class="correct-val"><?= e($cor) ?></td>
                                <td><span class="<?= $ok ? 'pill-c' : 'pill-w' ?>"><?= $ok ? '✓ Correct' : '✗ Wrong' ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- WRITING MODAL -->
    <div class="modal-overlay" id="modal-writing" onclick="closeOnBg(event,'writing')">
        <div class="modal-box">
            <div class="modal-head">
                <div class="modal-title">✍️ Writing — Band <?= number_format((float)$mockSession['writing_band'],1) ?> (AI-graded)</div>
                <button class="modal-close" onclick="closeModal('writing')">✕</button>
            </div>
            <div class="modal-body">
                <?php if (empty($writingEssays)): ?>
                    <p class="text-muted">No writing submitted.</p>
                <?php else: ?>
                    <?php foreach ($writingEssays as $task): ?>
                    <div class="task-block">
                        <div class="task-head">Task <?= $task['question_number'] ?><?= $task['band'] ? ' — Band '.number_format((float)$task['band'],1) : '' ?></div>
                        <?php if ($task['prompt']): ?>
                            <div style="font-size:.7rem;font-weight:700;color:var(--blue-dark);margin-bottom:.3rem;">Prompt</div>
                            <div class="prompt-box"><?= e($task['prompt']) ?></div>
                        <?php endif; ?>
                        <div style="font-size:.7rem;font-weight:700;color:#475569;margin-bottom:.3rem;">Your Response</div>
                        <div class="essay-box"><?= e($task['essay'] ?: '(no response submitted)') ?></div>
                    </div>
                    <?php endforeach; ?>
                    <?php if (!empty($mockSession['writing_ai_feedback'])): ?>
                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--blue-dark);margin-bottom:.4rem;">AI Feedback</div>
                    <div class="ai-box"><?= e($mockSession['writing_ai_feedback']) ?></div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php elseif ($practiceDetail): ?>
<!-- ══════════════════════════════════════════════════════════════════
     PRACTICE TEST DETAIL
══════════════════════════════════════════════════════════════════════ -->
    <a href="my_results.php" class="back-link"><i class="bi bi-arrow-left"></i> All Results</a>

    <div class="detail-card mb-3">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h5 class="fw-bold mb-1"><?= e($practiceDetail['title']) ?></h5>
                <span style="background:var(--blue-light);color:var(--blue-dark);border-radius:6px;padding:.2rem .55rem;font-size:.72rem;font-weight:700;">
                    <?= e($practiceDetail['category'] ?: 'Practice') ?>
                </span>
                <span class="text-muted small ms-2"><?= date('d M Y', strtotime($practiceDetail['completed_at'])) ?></span>
            </div>
            <div class="d-flex gap-2">
                <div class="stat-box"><div class="val"><?= (int)$practiceDetail['score'] ?>/<?= (int)$practiceDetail['max_score'] ?></div><div class="lbl">Score</div></div>
                <div class="stat-box"><div class="val"><?= $practiceDetail['band_score'] ? number_format((float)$practiceDetail['band_score'],1) : '–' ?></div><div class="lbl">Band</div></div>
                <div class="stat-box"><div class="val"><?= $practiceDetail['max_score'] > 0 ? round(($practiceDetail['score']/$practiceDetail['max_score'])*100) : 0 ?>%</div><div class="lbl">Accuracy</div></div>
            </div>
        </div>

        <?php if (empty($practiceAnswers)): ?>
            <p class="text-muted">No answer breakdown available.</p>
        <?php else: ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-semibold" style="font-size:.88rem;">Question Breakdown</span>
                <span class="text-muted small">
                    <?= array_sum(array_column($practiceAnswers,'score_awarded')) ?> correct
                    · <?= count($practiceAnswers) - array_sum(array_column($practiceAnswers,'score_awarded')) ?> incorrect
                </span>
            </div>
            <?php foreach ($practiceAnswers as $row):
                $ok = (float)$row['score_awarded'] >= 1.0;
                $stu = $row['student_option'] ?: $row['student_answer'] ?: '—';
                $cor = $row['correct_option']  ?: $row['correct_answer']  ?: '—';
            ?>
            <div class="q-row">
                <div class="q-n"><?= $row['question_number'] ?></div>
                <div class="q-t"><?= e(mb_strimwidth($row['question_text'] ?? '', 0, 120, '…')) ?></div>
                <div class="q-a">
                    <div class="<?= $ok ? 'correct' : 'incorrect' ?>">
                        <?= e($stu) ?> <span class="ms-1 <?= $ok ? 'pill-c' : 'pill-w' ?>"><?= $ok ? '✓' : '✗' ?></span>
                    </div>
                    <?php if (!$ok): ?>
                    <div class="text-muted" style="font-size:.75rem;">Correct: <strong><?= e($cor) ?></strong></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

<?php else: ?>
<!-- ══════════════════════════════════════════════════════════════════
     LIST VIEW
══════════════════════════════════════════════════════════════════════ -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">My Results</h4>
    </div>

    <!-- Mock test sessions -->
    <div class="section-head">
        <i class="bi bi-clipboard-check-fill" style="color:var(--pink);"></i> Full Mock Tests
        <div class="section-divider"></div>
        <span><?= count($mockSessions) ?></span>
    </div>

    <?php if (empty($mockSessions)): ?>
        <div class="text-center py-4 text-muted" style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;">
            <i class="bi bi-clipboard-x fs-3 mb-2 d-block"></i>
            No mock tests completed yet.
            <div class="mt-2 small"><a href="../mock_tests/index.php">Start a full mock exam →</a></div>
        </div>
    <?php else: ?>
        <?php foreach ($mockSessions as $ms):
            $msDate = date('d M Y', strtotime($ms['created_at']));
        ?>
        <div class="result-card mock-card">
            <div class="band-circle pink">
                <?php if ($ms['status'] === 'results_released'): ?>
                    <div class="bval"><?= number_format((float)$ms['overall_band'],1) ?></div>
                    <div class="blbl">Band</div>
                <?php elseif ($ms['status'] === 'awaiting_speaking_grade'): ?>
                    <div style="font-size:.6rem;font-weight:700;text-align:center;line-height:1.2;">Awaiting<br>Speaking</div>
                <?php else: ?>
                    <div style="font-size:.6rem;font-weight:700;text-align:center;line-height:1.2;">In<br>Progress</div>
                <?php endif; ?>
            </div>
            <div style="flex:1;min-width:0;">
                <div class="fw-semibold text-dark" style="font-size:.9rem;"><?= e($ms['mock_title']) ?></div>
                <div class="mini-bands mt-1">
                    <?php if ($ms['l_band']): ?><span>L:</span><?= number_format((float)$ms['l_band'],1) ?><?php endif; ?>
                    <?php if ($ms['r_band']): ?>&nbsp;<span>R:</span><?= number_format((float)$ms['r_band'],1) ?><?php endif; ?>
                    <?php if ($ms['writing_band']): ?>&nbsp;<span>W:</span><?= number_format((float)$ms['writing_band'],1) ?><?php endif; ?>
                    <?php if ($ms['speaking_band']): ?>&nbsp;<span>S:</span><?= number_format((float)$ms['speaking_band'],1) ?><?php endif; ?>
                </div>
                <div class="text-muted" style="font-size:.75rem;margin-top:.2rem;"><?= $msDate ?></div>
            </div>
            <div class="d-flex flex-column align-items-end gap-1">
                <?php if ($ms['status'] === 'results_released'): ?>
                    <a href="my_results.php?session_id=<?= $ms['id'] ?>" class="btn-view">View Results →</a>
                    <button onclick="downloadMockPDF(<?= htmlspecialchars(json_encode([
                        'title'   => $ms['mock_title'],
                        'date'    => date('d M Y', strtotime($ms['released_at'])),
                        'overall' => number_format((float)$ms['overall_band'],1),
                        'l'       => number_format((float)$ms['l_band'],1),
                        'l_score' => (int)$ms['l_score'].'/'.(int)$ms['l_max'],
                        'r'       => number_format((float)$ms['r_band'],1),
                        'r_score' => (int)$ms['r_score'].'/'.(int)$ms['r_max'],
                        'w'       => number_format((float)$ms['writing_band'],1),
                        's'       => number_format((float)$ms['speaking_band'],1),
                        's_notes' => $ms['speaking_notes'] ?? '',
                        'name'    => $userFullName,
                    ])) ?>)" class="btn-pdf">↓ PDF</button>
                <?php elseif ($ms['status'] === 'awaiting_speaking_grade'): ?>
                    <span class="status-pill pill-pending">⏳ Awaiting Speaking</span>
                <?php else: ?>
                    <span class="status-pill pill-progress">▶ In Progress</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Practice tests -->
    <div class="section-head mt-4">
        <i class="bi bi-pencil-fill" style="color:var(--blue);"></i> Practice Tests
        <div class="section-divider"></div>
        <span><?= count($practiceAttempts) ?></span>
    </div>

    <?php if (empty($practiceAttempts)): ?>
        <div class="text-center py-4 text-muted" style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;">
            <i class="bi bi-clipboard-x fs-3 mb-2 d-block"></i>
            No practice tests completed yet.
            <div class="mt-2 small"><a href="index.php">Browse practice tests →</a></div>
        </div>
    <?php else: ?>
        <?php foreach ($practiceAttempts as $a):
            $pct    = $a['max_score'] > 0 ? round(($a['score']/$a['max_score'])*100) : 0;
            $band   = $a['band_score'] ? number_format((float)$a['band_score'],1) : '–';
            $barCol = $pct >= 70 ? '#10b981' : ($pct >= 50 ? '#f59e0b' : '#ef4444');
        ?>
        <a href="my_results.php?attempt_id=<?= $a['id'] ?>" class="result-card text-decoration-none">
            <div class="band-circle">
                <div class="bval"><?= $band ?></div>
                <div class="blbl">Band</div>
            </div>
            <div style="flex:1;min-width:0;">
                <div class="fw-semibold text-dark mb-1" style="font-size:.9rem;"><?= e($a['title']) ?></div>
                <div style="height:4px;background:#e5e7eb;border-radius:4px;margin:.3rem 0;">
                    <div style="width:<?= $pct ?>%;height:100%;background:<?= $barCol ?>;border-radius:4px;"></div>
                </div>
                <div class="text-muted" style="font-size:.75rem;"><?= date('d M Y, g:i a', strtotime($a['completed_at'])) ?></div>
            </div>
            <div class="text-end" style="flex-shrink:0;">
                <div class="fw-bold" style="color:var(--blue);"><?= (int)$a['score'] ?>/<?= (int)$a['max_score'] ?></div>
                <div class="text-muted small"><?= $pct ?>%</div>
            </div>
            <i class="bi bi-chevron-right text-muted"></i>
        </a>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
function openModal(name) { document.getElementById('modal-'+name).classList.add('open'); document.body.style.overflow='hidden'; }
function closeModal(name) { document.getElementById('modal-'+name).classList.remove('open'); document.body.style.overflow=''; }
function closeOnBg(e,name) { if (e.target===document.getElementById('modal-'+name)) closeModal(name); }
document.addEventListener('keydown', e => { if (e.key==='Escape') ['listening','reading','writing'].forEach(closeModal); });

function downloadMockPDF(data) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ unit:'mm', format:'a4' });
    const navy=[14,44,96], blue=[14,165,233], pink=[236,72,153], dark=[15,23,42], muted=[100,116,139], light=[241,245,249], white=[255,255,255];
    const L=15, R=195, W=180;

    doc.setFillColor(...navy); doc.rect(0,0,210,46,'F');
    doc.setTextColor(180,210,255); doc.setFontSize(7.5); doc.setFont('helvetica','italic');
    doc.text('Confidential Assessment Report',R,8,{align:'right'});
    doc.setTextColor(...white); doc.setFontSize(22); doc.setFont('helvetica','bold');
    doc.text(data.title.toUpperCase(),L,22);
    doc.setFontSize(10.5); doc.setFont('helvetica','italic');
    doc.text('Full Band Assessment Report',L,31);
    doc.setDrawColor(100,140,200); doc.setLineWidth(0.25); doc.line(L,35,R,35);
    doc.setFontSize(8); doc.setFont('helvetica','normal'); doc.setTextColor(180,210,255);
    doc.text('Scholarly Language Services',R,43,{align:'right'});

    doc.setTextColor(...dark); doc.setFontSize(12.5); doc.setFont('helvetica','bold');
    doc.text('Candidate: '+data.name,L,59);
    doc.setFontSize(9); doc.setFont('helvetica','normal'); doc.setTextColor(...muted);
    doc.text('Date: '+data.date,L,66);

    const tY=73, colW=45;
    const cols=[{label:'Listening',band:data.l,sub:data.l_score||''},{label:'Reading',band:data.r,sub:data.r_score||''},{label:'Writing',band:data.w,sub:'AI-graded'},{label:'Speaking',band:data.s,sub:'Instructor'}];
    doc.setFillColor(...navy); doc.rect(L,tY,W,10,'F');
    doc.setTextColor(...white); doc.setFontSize(8.5); doc.setFont('helvetica','bold');
    cols.forEach((c,i)=>doc.text(c.label,L+colW*i+colW/2,tY+7,{align:'center'}));
    doc.setFillColor(...light); doc.rect(L,tY+10,W,22,'F');
    doc.setDrawColor(226,232,240); doc.setLineWidth(0.2);
    for(let i=1;i<4;i++) doc.line(L+colW*i,tY+10,L+colW*i,tY+32);
    doc.setFontSize(22); doc.setFont('helvetica','bold'); doc.setTextColor(...navy);
    cols.forEach((c,i)=>doc.text(c.band,L+colW*i+colW/2,tY+26,{align:'center'}));
    doc.setFontSize(7.5); doc.setFont('helvetica','normal'); doc.setTextColor(...muted);
    cols.forEach((c,i)=>{ if(c.sub) doc.text(c.sub,L+colW*i+colW/2,tY+31,{align:'center'}); });

    const oY=tY+34;
    doc.setFillColor(...navy); doc.rect(L,oY,120,14,'F');
    doc.setTextColor(...white); doc.setFontSize(10); doc.setFont('helvetica','bold');
    doc.text('OVERALL BAND SCORE',L+5,oY+9.5);
    doc.setFillColor(...pink); doc.rect(L+120,oY,60,14,'F');
    doc.setFontSize(20); doc.text(data.overall,L+150,oY+10.5,{align:'center'});
    const avg=((parseFloat(data.l)+parseFloat(data.r)+parseFloat(data.w)+parseFloat(data.s))/4).toFixed(2);
    doc.setFontSize(7.5); doc.setFont('helvetica','italic'); doc.setTextColor(...muted);
    doc.text('('+data.l+' + '+data.r+' + '+data.w+' + '+data.s+') ÷ 4 = '+avg+'  →  rounded to '+data.overall,L,oY+21);

    const panels=[
        {label:'LISTENING',band:data.l,score:data.l_score,note:'Raw score shown. Full breakdown available in your online results.'},
        {label:'READING',  band:data.r,score:data.r_score,note:'Raw score shown. Passage breakdown available in your online results.'},
        {label:'WRITING',  band:data.w,score:'',note:'AI-graded. Full task feedback available in your online results.'},
        {label:'SPEAKING', band:data.s,score:'',note:data.s_notes||'Instructor-graded. Feedback available in your online results.'},
    ];
    let pY=oY+29;
    panels.forEach(p=>{
        doc.setFillColor(...navy); doc.rect(L,pY,W,10,'F');
        doc.setTextColor(...white); doc.setFontSize(9.5); doc.setFont('helvetica','bold');
        doc.text(p.label,L+5,pY+7); doc.text('Band '+p.band,R,pY+7,{align:'right'});
        doc.setFillColor(...light); doc.rect(L,pY+10,W,14,'F');
        if(p.score){ doc.setFontSize(8.5); doc.setFont('helvetica','bold'); doc.setTextColor(...dark); doc.text('Raw Score: '+p.score,L+5,pY+18); doc.setFont('helvetica','normal'); doc.setTextColor(...muted); doc.setFontSize(7.5); doc.text(p.note,L+50,pY+18); }
        else { doc.setFontSize(7.5); doc.setFont('helvetica','normal'); doc.setTextColor(...muted); doc.text(p.note,L+5,pY+18); }
        pY+=26;
    });

    doc.setFillColor(224,242,254); doc.rect(L,pY+4,W,22,'F');
    doc.setDrawColor(...blue); doc.setLineWidth(0.4); doc.line(L,pY+4,L,pY+26);
    doc.setTextColor(...dark); doc.setFontSize(9); doc.setFont('helvetica','bold');
    doc.text('Full detailed results: academy.slslanguage.com',L+5,pY+13);
    doc.setFontSize(8); doc.setFont('helvetica','normal'); doc.setTextColor(...muted);
    doc.text('Log in to view correct/incorrect answers, AI writing feedback, and speaking notes.',L+5,pY+21);

    doc.setFillColor(...navy); doc.rect(0,282,210,15,'F');
    doc.setTextColor(180,210,255); doc.setFontSize(7.5); doc.setFont('helvetica','normal');
    doc.text('Scholarly Language Services  ·  slslanguage.com',L,290);
    doc.text('Generated '+new Date().toLocaleDateString('en-GB',{day:'2-digit',month:'long',year:'numeric'}),R,290,{align:'right'});

    doc.save('IELTS_Report_'+data.name.replace(/\s+/g,'_')+'_'+data.date.replace(/\s+/g,'_')+'.pdf');
}
</script>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
