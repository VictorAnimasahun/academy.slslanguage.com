<?php
/**
 * CELPIP Full Mock — Listening.
 * Generic across CELPIP_FULL_MOCK_A / CELPIP_FULL_MOCK_B (and any future CELPIP
 * full mock added to mock_test_map.php) — the DB test_code, audio/video asset
 * base path, and timer duration are all derived from the session's mock_code,
 * exactly like full_mock_00N_listening.php does for IELTS. Do not hardcode a
 * specific mock code anywhere in this file.
 *
 * CELPIP Listening has 6 parts. Per the real CELPIP interface (confirmed via
 * official Prometric/Paragon reference material: "CELPIP Listening Pro Study
 * Pack" and "CELPIP General Complete Guide" screenshots), Parts 1-3 and Parts
 * 4-6 use genuinely different UIs, not just different content:
 *   - Parts 1-3: ONE question appears onscreen at a time, each with its own
 *     30-second countdown and audio (played once, no pausing/scrubbing). You
 *     cannot go back once you move on.
 *   - Parts 4-6: ALL of that part's questions appear onscreen together after
 *     one audio/video play, with a single timer for the whole part.
 * Students get this real, linear, no-going-back flow; admins keep free
 * tab-navigation for previewing content (existing $isAdmin bypass pattern).
 *
 * Audio assets are nested per part (assets/audio/{mockCode}/partN/...):
 *   part1/track1.mp3, track2.mp3, track3.mp3  — Q1-3, Q4-6, Q7-8 (fixed 3/3/2 split)
 *   part1/q1.mp3 .. q8.mp3                    — each question read aloud individually
 *   part2/track1.mp3 + part2/q1.mp3..q5.mp3   — all of Part 2
 *   part3/track1.mp3 + part3/q1.mp3..q6.mp3   — all of Part 3
 *   part4/track1.mp3                          — all of Part 4 (no per-question audio)
 *   part5/video1.mp4                          — Part 5 is a VIDEO, not audio
 *   part6/track1.mp3                          — all of Part 6 (no per-question audio)
 */
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}

$session_id = (int)($_GET['session_id'] ?? 0);
if (!$session_id) {
    header("Location: mock_start.php");
    exit();
}

$student_id  = (int)$_SESSION['user_id'];
require_once INCLUDES_PATH . '/admin_check.php';
$isAdmin     = is_platform_admin();

$stmt = $db->prepare("
    SELECT ms.*, t.title AS mock_title, t.code AS mock_code
    FROM mock_sessions ms
    JOIN tests t ON t.id = ms.mock_test_id
    WHERE ms.id = ? AND ms.student_id = ?
");
$stmt->execute([$session_id, $student_id]);
$session = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$session || $session['status'] !== 'in_progress') {
    header("Location: mock_start.php");
    exit();
}

// Admins can revisit any section freely; students are forwarded once a section is done
if (!$isAdmin && !is_null($session['listening_attempt_id'])) {
    $map  = require INCLUDES_PATH . '/mock_test_map.php';
    $file = $map[$session['mock_code']]['reading']['file'] ?? 'mock_start.php';
    header("Location: {$file}?session_id={$session_id}");
    exit();
}

// Load questions from DB
$map      = require INCLUDES_PATH . '/mock_test_map.php';
$testCode = $map[$session['mock_code']]['listening']['test_code'] ?? '';

$stmt = $db->prepare("SELECT id, duration_minutes FROM tests WHERE code = ? AND is_active = 1 LIMIT 1");
$stmt->execute([$testCode]);
$test = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$test) {
    die("Listening test not configured. Please contact support.");
}
$test_id = (int)$test['id'];

$stmt = $db->prepare("
    SELECT q.id, q.question_number, q.question_type, q.question_text,
           q.instructions, q.part_number, q.stimulus_text
    FROM questions q
    WHERE q.test_id = ?
    ORDER BY q.question_number
");
$stmt->execute([$test_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("
    SELECT qo.question_id, qo.option_label, qo.option_text, qo.display_order
    FROM question_options qo
    JOIN questions q ON q.id = qo.question_id
    WHERE q.test_id = ?
    ORDER BY q.question_number, qo.display_order
");
$stmt->execute([$test_id]);
$optionsRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);
$options = [];
foreach ($optionsRaw as $o) {
    $options[(int)$o['question_id']][] = $o;
}

// Only render questions that have actual content entered
$questions = array_values(array_filter($questions, fn($q) => trim($q['question_text'] ?? '') !== ''));

// Group by part
$parts = [];
foreach ($questions as $q) {
    $parts[(int)($q['part_number'] ?? 1)][] = $q;
}
ksort($parts);
foreach ($parts as $pNum => $pqs) {
    $parts[$pNum] = array_values($pqs);
}

// Compute Q ranges per part
$partRanges = [];
foreach ($parts as $pNum => $pqs) {
    $nums = array_column($pqs, 'question_number');
    $partRanges[$pNum] = [min($nums), max($nums)];
}

// Audio/video assets derive from the session's mock_code automatically
$mockCode      = $session['mock_code']; // CELPIP_FULL_MOCK_A or CELPIP_FULL_MOCK_B
$audioBase     = ACADEMY_URL . 'assets/audio/' . $mockCode . '/';
$DURATION_SECS = (int)($test['duration_minutes'] ?? 50) * 60;

// Parts 1-3 use the real one-question-at-a-time flow; Parts 4-6 show everything
// on one screen (matches the official interface exactly — see file header).
$SEQUENTIAL_PARTS = [1, 2, 3];

// A fill-in-the-blank style MC question (Listening Parts 4 & 6 — "Choose the
// best way to complete each statement", e.g. "The news item is about ___")
// renders as an inline dropdown instead of a radio list. Detected generically
// by a run of 2+ underscores in the question text — the same convention used
// for Reading's blank-style items in celpip_full_mock_reading.php. All of
// these are still stored/scored as plain multiple_choice_single rows; only
// the on-page rendering differs (see migration 073's header comment).
if (!function_exists('celpipIsBlankStyle')) {
    function celpipIsBlankStyle(string $text): bool
    {
        return (bool) preg_match('/_{2,}/', $text);
    }
}

// Custom dropdown widget matching the real CELPIP interface — same component
// as celpip_full_mock_reading.php: a small trigger that opens a floating panel
// of radio-style options, then collapses to show the chosen answer as bold
// inline text once selected. Duplicated here rather than shared via an
// include, matching this file-set's existing convention of each mock page
// being self-contained (see e.g. celpipIsBlankStyle in both files already).
function renderCelpipDropdown(int $qnum, array $qopts): void
{
    ?>
    <span class="celpip-dd" data-qnum="<?= $qnum ?>">
        <button type="button" class="celpip-dd-trigger" data-role="dd-trigger">
            <span data-role="dd-label">— Select —</span> <i class="bi bi-caret-down-fill"></i>
        </button>
        <input type="hidden" name="answers[<?= $qnum ?>]" class="answer-field celpip-dd-value" data-qnum="<?= $qnum ?>" value="">
        <span class="celpip-dd-panel" data-role="dd-panel" hidden>
            <?php foreach ($qopts as $opt): ?>
            <label class="celpip-dd-option">
                <input type="radio" name="celpip_dd_radio_<?= $qnum ?>" value="<?= htmlspecialchars($opt['option_label']) ?>" data-text="<?= htmlspecialchars($opt['option_label'] . '. ' . $opt['option_text']) ?>">
                <?= htmlspecialchars($opt['option_label']) ?>.&nbsp;<?= htmlspecialchars($opt['option_text']) ?>
            </label>
            <?php endforeach; ?>
        </span>
    </span>
    <?php
}

// Renders one Listening question's answer control: an inline-dropdown sentence
// for blank-style MC questions, otherwise a standard radio-button MC block.
function renderCelpipListeningQuestion(array $q, array $options): void
{
    $qid     = (int)$q['id'];
    $qnum    = (int)$q['question_number'];
    $qopts   = $options[$qid] ?? [];
    $isBlank = celpipIsBlankStyle($q['question_text'] ?? '');
    ?>
    <?php if ($isBlank): ?>
    <div class="ff-sentence celpip-inline-q">
        <?php
        $escaped = htmlspecialchars($q['question_text']);
        $badge   = '<span class="q-badge">' . $qnum . '</span>';
        ob_start();
        renderCelpipDropdown($qnum, $qopts);
        $ddHtml = ob_get_clean();
        echo preg_replace('/_{2,}/', $badge . $ddHtml, $escaped, 1);
        ?>
    </div>
    <?php else: ?>
    <div class="mc-question">
        <div class="mc-q-label">
            <span class="q-badge"><?= $qnum ?></span>
            <?= htmlspecialchars($q['question_text']) ?>
        </div>
        <?php foreach ($qopts as $opt): ?>
        <label class="mc-option">
            <input type="radio"
                   name="answers[<?= $qnum ?>]"
                   value="<?= htmlspecialchars($opt['option_label']) ?>"
                   class="answer-field"
                   data-qnum="<?= $qnum ?>">
            <strong><?= htmlspecialchars($opt['option_label']) ?></strong>&nbsp;
            <?= htmlspecialchars($opt['option_text']) ?>
        </label>
        <?php endforeach; ?>
    </div>
    <?php endif;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listening — <?= htmlspecialchars($session['mock_title']) ?> | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <link rel="stylesheet" href="<?= ACADEMY_URL ?>assets/css/exam_theme.css">
    <style>
        /* CELPIP-authentic sequential question flow (Parts 1-3). Reuses the
           exam_theme.css palette so it stays visually consistent with the
           rest of the platform, not a separate skin. */

        /* Stable frame: the outer box never resizes as content changes
           (media stage <-> question 1 <-> question with a long passage,
           etc.) -- it fills the remaining viewport height and its content
           centers within it, matching the reading pane's stable box. */
        .part-panel.active { min-height: calc(100vh - 280px); }
        .celpip-seq {
            display: flex; flex-direction: column; justify-content: center;
            min-height: calc(100vh - 280px);
        }
        .celpip-seq > .celpip-seq-card { margin-bottom: 0; }

        .celpip-seq-card {
            background: var(--exam-surface); border: 1px solid var(--exam-line);
            border-radius: var(--exam-radius-lg); overflow-y: auto; margin-bottom: 1.5rem;
            max-height: calc(100vh - 280px);
        }
        .celpip-seq-header {
            display: flex; align-items: center; justify-content: space-between;
            background: var(--exam-accent-soft); padding: .6rem 1rem;
            border-bottom: 1px solid var(--exam-line); font-size: .85rem; font-weight: 700;
            color: var(--exam-ink);
        }
        .celpip-seq-timer-wrap { display: flex; align-items: center; gap: .75rem; }
        .celpip-seq-timer { font-variant-numeric: tabular-nums; color: var(--exam-warn); font-weight: 700; }
        .celpip-seq-timer.calm { color: var(--exam-ink-muted); }
        .celpip-seq-body { padding: 1.25rem 1.5rem; }

        /* Media stage: the main conversation/video, played alone and centered —
           not tucked in a thin top strip — before any question appears. */
        .celpip-media-stage { padding: 2rem 1.5rem; text-align: center; }
        .celpip-media-stage-main { padding: 3rem 1.5rem; }
        .celpip-media-caption { font-size: .85rem; color: var(--exam-accent); margin-bottom: 1rem; }
        .celpip-media-caption .bi { margin-right: .3rem; }
        .celpip-audio-widget {
            display: flex; align-items: center; gap: 1rem; background: var(--exam-bg);
            border: 1px solid var(--exam-line); border-radius: var(--exam-radius-lg);
            padding: 1rem 1.25rem; max-width: 480px; margin: 0 auto;
        }
        .celpip-audio-widget i { font-size: 1.4rem; color: var(--exam-ink-muted); flex-shrink: 0; }
        .celpip-audio-widget-lg { max-width: 560px; padding: 1.5rem 1.75rem; }
        .celpip-audio-widget-lg i { font-size: 2rem; }
        .celpip-progress-track {
            flex: 1; height: 8px; background: var(--exam-surface); border: 1px solid var(--exam-line);
            border-radius: 999px; overflow: hidden;
        }
        .celpip-progress-fill { height: 100%; width: 0%; background: var(--exam-accent); transition: width .2s linear; }
        .celpip-playing-label { font-size: .8rem; color: var(--exam-ink-muted); margin-top: .6rem; font-style: italic; }

        /* Answer stage (shown only after the question's audio finishes —
           never at the same time as the audio stage). */
        .celpip-q-of { font-size: .82rem; color: var(--exam-ink-muted); margin-bottom: .5rem; }
        .celpip-q-instr { font-size: .85rem; color: var(--exam-accent); margin-bottom: 1rem; }
        .celpip-q-instr .bi { margin-right: .3rem; }

        .celpip-next-btn {
            background: var(--exam-accent); color: #fff; border: none; border-radius: var(--exam-radius);
            padding: .45rem 1.4rem; font-weight: 700; font-size: .85rem;
        }
        .celpip-next-btn-inline { padding: .3rem 1.1rem; font-size: .8rem; }
        .celpip-tap-to-play {
            display: block; margin: 1rem auto 0; background: var(--exam-warn); color: #fff; border: none;
            border-radius: var(--exam-radius); padding: .55rem 1.5rem; font-weight: 700; font-size: .85rem;
        }
        .celpip-tap-to-play .bi { margin-right: .35rem; }
        .celpip-next-btn:disabled { opacity: .5; }
        .celpip-locked { opacity: .55; pointer-events: none; }
        video.celpip-media, audio.celpip-media { display: none; }

        /* Custom dropdown widget matching the real CELPIP interface — same
           component as celpip_full_mock_reading.php: a small trigger that
           opens a floating panel of radio-style options, then collapses to
           show the chosen answer as bold inline text. */
        .celpip-inline-q { line-height: 2.4; }
        .celpip-dd { position: relative; display: inline-block; margin: 0 .25rem; }
        .celpip-dd-trigger {
            background: var(--exam-surface); border: 1px solid var(--exam-accent);
            border-radius: var(--exam-radius); padding: .25rem .7rem; font-size: .85rem;
            color: var(--exam-ink-muted); cursor: pointer; display: inline-flex;
            align-items: center; gap: .4rem; min-width: 90px;
        }
        .celpip-dd-trigger.answered { color: var(--exam-ink); font-weight: 700; border-color: var(--exam-good); }
        .celpip-dd-trigger .bi { font-size: .65rem; color: var(--exam-ink-muted); }
        .celpip-dd-panel {
            position: absolute; z-index: 50; top: calc(100% + 4px); left: 0; min-width: 260px;
            background: var(--exam-surface); border: 1px solid var(--exam-line); border-radius: var(--exam-radius-lg);
            box-shadow: 0 8px 24px rgba(0,0,0,.12); padding: .5rem 0;
        }
        .celpip-dd-option {
            display: flex; align-items: flex-start; gap: .5rem; padding: .5rem .9rem;
            font-size: .85rem; font-weight: 400; cursor: pointer; white-space: normal;
        }
        .celpip-dd-option:hover { background: var(--exam-bg); }
        .celpip-dd-option input { accent-color: var(--exam-accent); margin-top: 3px; flex-shrink: 0; }
    </style>
</head>
<body>

<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="mobile-overlay" id="mobileOverlay"></div>
<?php include INCLUDES_PATH . '/navbar.php'; ?>

<div class="main-wrapper flex-grow-1" style="flex:1;">
    <?php include INCLUDES_PATH . '/topbar.php'; ?>

    <main class="content p-3">

        <div class="sticky-header">
            <?php if ($isAdmin): ?>
            <div style="background:#1e1b4b;color:#c7d2fe;padding:.6rem 1.25rem;border-radius:8px;margin-bottom:.5rem;display:flex;align-items:center;gap:1.5rem;font-size:.82rem;font-weight:600;">
                <span style="color:#a5b4fc;text-transform:uppercase;letter-spacing:.08em;font-size:.7rem;">Admin Preview — free navigation (students get the real linear flow)</span>
                <a href="celpip_full_mock_listening.php?session_id=<?= $session_id ?>" style="color:#c7d2fe;text-decoration:none;border-bottom:2px solid #6366f1;padding-bottom:2px;">🎧 Listening</a>
                <a href="celpip_full_mock_reading.php?session_id=<?= $session_id ?>"   style="color:#a5b4fc;text-decoration:none;">📖 Reading</a>
                <a href="mock_writing.php?session_id=<?= $session_id ?>"               style="color:#a5b4fc;text-decoration:none;">✍️ Writing</a>
                <a href="mock_speaking.php?session_id=<?= $session_id ?>"              style="color:#a5b4fc;text-decoration:none;">🎤 Speaking</a>
            </div>
            <?php endif; ?>
            <div class="d-flex align-items-center justify-content-between">
                <div class="progress-steps">
                    <div class="step current"><div class="step-dot"></div>Listening</div>
                    <i class="bi bi-chevron-right text-muted" style="font-size:.7rem;"></i>
                    <div class="step"><div class="step-dot"></div>Reading</div>
                    <i class="bi bi-chevron-right text-muted" style="font-size:.7rem;"></i>
                    <div class="step"><div class="step-dot"></div>Writing</div>
                    <i class="bi bi-chevron-right text-muted" style="font-size:.7rem;"></i>
                    <div class="step"><div class="step-dot"></div>Speaking</div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <small class="text-muted"><?= htmlspecialchars($session['mock_title']) ?></small>
                    <button class="btn-exit" onclick="confirmExit()"><i class="bi bi-box-arrow-right me-1"></i> Exit</button>
                </div>
            </div>
        </div>

        <div class="section-content" style="padding-top:<?= $isAdmin ? '110px' : '60px' ?>;">

            <!-- Part tab bar + timer. Tabs are click-navigable for admins only —
                 students follow the real, enforced linear flow (see JS below). -->
            <div class="part-tabs-bar">
                <div class="part-tabs-scrollable">
                    <?php foreach ($parts as $pNum => $pqs):
                        [$f, $l] = $partRanges[$pNum];
                    ?>
                    <button class="part-tab-btn <?= $pNum === 1 ? 'active' : '' ?>"
                            id="ptab-<?= $pNum ?>"
                            onclick="switchPart(<?= $pNum ?>, this)"
                            <?= (!$isAdmin && $pNum !== 1) ? 'disabled style="cursor:default;"' : '' ?>>
                        <span class="done-dot"></span>
                        Part <?= $pNum ?>
                        <span class="tab-qrange">Q<?= $f ?>–<?= $l ?></span>
                    </button>
                    <?php endforeach; ?>
                </div>
                <div class="inline-timer" id="inlineTimer"><i class="bi bi-clock-fill"></i> 00:00</div>
            </div>

            <?php if (empty($questions)): ?>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                No questions loaded yet. Please run the database migration for this test and contact your instructor.
            </div>
            <?php endif; ?>

            <form id="listeningForm">

            <?php foreach ($parts as $partNum => $partQuestions):
                $isSequential = in_array($partNum, $SEQUENTIAL_PARTS, true);
                $isVideoPart  = ($partNum === 5);
            ?>
            <div class="part-panel <?= $partNum === 1 ? 'active' : '' ?>" id="panel-<?= $partNum ?>" data-part="<?= $partNum ?>" data-sequential="<?= $isSequential ? '1' : '0' ?>">

                <?php if ($isSequential): ?>
                <?php
                // Build the track "groups" this part's questions are split into.
                // Part 1 = three tracks (fixed 3/3/2 split); Parts 2-3 = one
                // track covering every question in the part.
                if ($partNum === 1 && count($partQuestions) === 8) {
                    $groups = [
                        ['label' => 'Recording 1 of 3', 'src' => $audioBase . 'part1/track1.mp3', 'qs' => array_slice($partQuestions, 0, 3)],
                        ['label' => 'Recording 2 of 3', 'src' => $audioBase . 'part1/track2.mp3', 'qs' => array_slice($partQuestions, 3, 3)],
                        ['label' => 'Recording 3 of 3', 'src' => $audioBase . 'part1/track3.mp3', 'qs' => array_slice($partQuestions, 6, 2)],
                    ];
                } else {
                    $groups = [
                        ['label' => 'Recording', 'src' => $audioBase . "part{$partNum}/track1.mp3", 'qs' => $partQuestions],
                    ];
                }
                // Part title/instructions are set once (on the first question of the
                // part) per migration 073's convention — reuse them in every question
                // card's header/body rather than only showing them on question 1.
                $partTitle = $partQuestions[0]['stimulus_text'] ?? ('Listening Part ' . $partNum);
                $partInstr = $partQuestions[0]['instructions'] ?? 'Listen to the question. You will hear it only once.';
                ?>
                <div class="celpip-seq" data-groups='<?= htmlspecialchars(json_encode(array_map(fn($g) => [
                    'label' => $g['label'], 'src' => $g['src'], 'qnums' => array_column($g['qs'], 'question_number'),
                ], $groups))) ?>'>

                    <!-- Media stage: main conversation/video plays alone, centered, before
                         any question appears — matches the real CELPIP interface exactly. -->
                    <div class="celpip-seq-card celpip-media-stage celpip-media-stage-main" data-role="media-stage">
                        <div class="celpip-media-caption"><i class="bi bi-info-circle-fill"></i> Listen to <span data-role="media-label">the recording</span>. You will hear it only once.</div>
                        <?php if ($isVideoPart): ?>
                        <video class="celpip-media" data-role="media-el" preload="none" playsinline style="width:100%;max-width:640px;"></video>
                        <?php else: ?>
                        <div class="celpip-audio-widget celpip-audio-widget-lg">
                            <i class="bi bi-volume-up-fill"></i>
                            <div class="celpip-progress-track"><div class="celpip-progress-fill" data-role="progress-fill"></div></div>
                        </div>
                        <?php endif; ?>
                        <div class="celpip-playing-label">Playing…</div>
                    </div>

                    <!-- One question card per question; JS shows exactly one at a time.
                         Split layout: audio widget on the left (auto-plays this question
                         read aloud, ~5s), question + options on the right. -->
                    <?php foreach ($partQuestions as $qi => $q):
                        $qid  = (int)$q['id'];
                        $qnum = (int)$q['question_number'];
                        $qopts = $options[$qid] ?? [];
                    ?>
                    <div class="celpip-seq-card" data-role="q-card" data-qnum="<?= $qnum ?>" data-audio="<?= htmlspecialchars($audioBase . "part{$partNum}/q" . ($qi + 1) . ".mp3") ?>" style="display:none;">
                        <div class="celpip-seq-header">
                            <span><?= htmlspecialchars($partTitle) ?></span>
                            <span class="celpip-seq-timer-wrap">
                                <span class="celpip-seq-timer" data-role="q-timer">Time remaining: <strong data-role="q-timer-val">25</strong> seconds</span>
                                <button type="button" class="celpip-next-btn celpip-next-btn-inline" data-role="next-btn">NEXT</button>
                            </span>
                        </div>
                        <!-- Audio and options are NEVER shown at once — the question's
                             audio plays alone first (centered, like the main recording),
                             then this stage is replaced entirely by the answer stage. -->
                        <div class="celpip-media-stage" data-role="q-audio-stage">
                            <div class="celpip-media-caption"><i class="bi bi-info-circle-fill"></i> Listen to the question. You will hear it only once.</div>
                            <div class="celpip-audio-widget">
                                <i class="bi bi-volume-up-fill"></i>
                                <div class="celpip-progress-track"><div class="celpip-progress-fill" data-role="q-progress-fill"></div></div>
                            </div>
                            <div class="celpip-playing-label" data-role="q-playing-label">Playing…</div>
                        </div>
                        <div class="celpip-seq-body" data-role="q-answer-stage" style="display:none;">
                            <div class="celpip-q-of">Question <?= $qi + 1 ?> of <?= count($partQuestions) ?></div>
                            <p class="celpip-q-instr"><i class="bi bi-info-circle-fill"></i> <?= htmlspecialchars($partInstr) ?></p>
                            <?php renderCelpipListeningQuestion($q, $options); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php else: /* Parts 4-6: everything visible on one screen, matches real CELPIP */ ?>
                <?php
                $playerLabel = $isVideoPart ? 'Video' : 'Recording';
                $playerSrc   = $audioBase . "part{$partNum}/" . ($isVideoPart ? 'video1.mp4' : 'track1.mp3');
                ?>
                <div class="celpip-seq-card celpip-media-stage" data-role="allscreen-media" data-src="<?= htmlspecialchars($playerSrc) ?>" data-video="<?= $isVideoPart ? '1' : '0' ?>">
                    <div class="celpip-media-label">
                        <i class="bi bi-<?= $isVideoPart ? 'camera-video-fill' : 'volume-up-fill' ?>"></i>
                        <?= htmlspecialchars($playerLabel) ?> — <?= $isVideoPart ? 'watch' : 'listen to' ?> once. The questions will appear once it finishes.
                    </div>
                    <?php if ($isVideoPart): ?>
                    <video class="celpip-media" data-role="media-el" preload="none" playsinline style="width:100%;max-width:640px;display:block;margin:0 auto;border:1px solid var(--exam-line);border-radius:var(--exam-radius);"></video>
                    <?php endif; ?>
                    <div class="celpip-progress-track"><div class="celpip-progress-fill" data-role="progress-fill"></div></div>
                </div>

                <!-- Hidden until the media above finishes -- audio/video and
                     questions are never shown at once, same rule as Parts 1-3. -->
                <div data-role="allscreen-questions" style="display:none;">
                <?php
                $prevInstr = null;
                $prevStim  = null;
                $blockOpen = false;
                foreach ($partQuestions as $q):
                    if (!empty($q['instructions']) && $q['instructions'] !== $prevInstr):
                        if ($blockOpen) echo '</div>';
                        $prevInstr = $q['instructions'];
                        $blockOpen = true;
                        echo '<div class="section-block"><p class="q-instructions">' . htmlspecialchars($q['instructions']) . '</p>';
                    elseif (!$blockOpen):
                        $blockOpen = true;
                        echo '<div class="section-block">';
                    endif;

                    if (!empty($q['stimulus_text']) && $q['stimulus_text'] !== $prevStim):
                        $prevStim = $q['stimulus_text'];
                        echo '<div class="ff-title">' . htmlspecialchars($q['stimulus_text']) . '</div>';
                    endif;

                    renderCelpipListeningQuestion($q, $options);
                endforeach;
                if ($blockOpen) echo '</div>';
                ?>

                <?php if ($partNum !== 6): ?>
                <button type="button" class="celpip-next-btn" data-role="part-continue-btn" data-next-part="<?= $partNum + 1 ?>">Continue to Part <?= $partNum + 1 ?> →</button>
                <div style="clear:both;"></div>
                <?php endif; ?>
                </div><!-- end allscreen-questions -->
                <?php endif; ?>

            </div><!-- end part-panel -->
            <?php endforeach; ?>

            </form>
        </div><!-- end section-content -->

        <div style="height:80px;"></div>
    </main>
</div><!-- end main-wrapper -->

<?php include INCLUDES_PATH . '/adverts.php'; ?>

<div class="submit-bar">
    <div style="display:flex; align-items:center; gap:.5rem;">
        <i class="bi bi-check2-circle text-success fs-5"></i>
        <span id="answeredCount" style="font-size:.9rem; font-weight:600; color:#374151;">0 of <?= count($questions) ?> answered</span>
    </div>
    <button type="button" class="btn btn-primary fw-bold px-4" id="submitBtn" onclick="submitListening()">
        <i class="bi bi-arrow-right-circle me-2"></i>Submit &amp; Continue to Reading
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>

<script>
const DURATION   = <?= $DURATION_SECS ?>;
const SESSION_ID = <?= $session_id ?>;
const totalQs    = <?= count($questions) ?>;
const IS_ADMIN   = <?= $isAdmin ? 'true' : 'false' ?>;
const Q_SECONDS  = 25; // per-question countdown AFTER the ~5s question audio finishes (real CELPIP budget is ~30s total: audio + 25s to answer)
let elapsed      = 0;
let timerInterval;
let submitting   = false;

const timerEl = document.getElementById('inlineTimer');

// ── Overall test timer (always running, total time budget) ──────────
function fmt(sec) {
    return String(Math.floor(sec/60)).padStart(2,'0') + ':' + String(sec%60).padStart(2,'0');
}
function startTimer() {
    timerEl.querySelector('i').nextSibling.textContent = ' ' + fmt(DURATION);
    timerInterval = setInterval(() => {
        elapsed++;
        const rem = DURATION - elapsed;
        timerEl.querySelector('i').nextSibling.textContent = ' ' + fmt(Math.max(0, rem));
        if (rem <= 300) timerEl.classList.add('warning');
        if (rem <= 0) { clearInterval(timerInterval); submitListening(true); }
    }, 1000);
}

// ── Part switching (admin free-nav only; students move via the sequence
//    controller / part-continue buttons below) ──────────────────────────
function switchPart(pNum, btn, force = false) {
    if (!force && !IS_ADMIN && btn && btn.disabled) return;
    document.querySelectorAll('.part-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.part-tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('panel-' + pNum).classList.add('active');
    if (btn) btn.classList.add('active');

    document.querySelectorAll('.part-panel:not(#panel-' + pNum + ') audio, .part-panel:not(#panel-' + pNum + ') video')
        .forEach(m => m.pause());

    maybeStartPart(pNum);
}

function goToNextPart(fromPartNum) {
    const tab = document.getElementById('ptab-' + fromPartNum);
    if (tab) tab.classList.add('all-answered');
    const nextPanel = document.getElementById('panel-' + (fromPartNum + 1));
    if (!nextPanel) return; // was the last part
    if (!IS_ADMIN) {
        // Unlock the next tab visually (still non-clickable for students —
        // navigation happens only through the forced sequence/continue button).
        const nextTab = document.getElementById('ptab-' + (fromPartNum + 1));
        if (nextTab) nextTab.disabled = true;
    }
    switchPart(fromPartNum + 1, document.getElementById('ptab-' + (fromPartNum + 1)), true);
}

// ── Sequential parts (1-3): one question at a time, own audio, own 30s timer ──
const seqState = {}; // partNum -> { groups, flatQuestions, idx, qTimer }

function initSequentialPart(panel) {
    const partNum = parseInt(panel.dataset.part, 10);
    if (seqState[partNum]) return; // already initialized
    const seqEl  = panel.querySelector('.celpip-seq');
    const groups = JSON.parse(seqEl.dataset.groups);
    const flat   = [];
    groups.forEach((g, gi) => g.qnums.forEach((qn, qiInGroup) => flat.push({ qnum: qn, groupIdx: gi, isFirstInGroup: qiInGroup === 0 })));
    seqState[partNum] = { groups, flat, idx: -1, qTimerInterval: null, panel, seqEl };
}

function advanceSequential(partNum) {
    const st = seqState[partNum];
    if (!st) return;
    if (st.qTimerInterval) { clearInterval(st.qTimerInterval); st.qTimerInterval = null; }

    // Lock the just-finished question card (record stays visible, frozen).
    if (st.idx >= 0) {
        const prevCard = st.seqEl.querySelector(`[data-role="q-card"][data-qnum="${st.flat[st.idx].qnum}"]`);
        if (prevCard) {
            prevCard.style.display = 'none';
            prevCard.classList.add('celpip-locked');
        }
    }

    st.idx++;
    if (st.idx >= st.flat.length) {
        // Part finished — auto-advance to the next part, exactly like the real test.
        goToNextPart(partNum);
        return;
    }

    const item = st.flat[st.idx];
    if (item.isFirstInGroup) {
        playGroupMedia(partNum, item.groupIdx, () => showSequentialQuestion(partNum));
    } else {
        showSequentialQuestion(partNum);
    }
}

// Attempts to autoplay `mediaEl`. If the browser blocks it (no prior user
// gesture on this page — common right after a fresh navigation, rare once a
// student has already clicked through "Start Full Mock Test"), shows a
// "Tap to play" button inside `containerEl` instead of silently skipping the
// audio — a student must always actually hear it, never see it skipped.
function playWithFallback(mediaEl, containerEl) {
    mediaEl.play().catch(() => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'celpip-tap-to-play';
        btn.innerHTML = '<i class="bi bi-play-fill"></i> Tap to play';
        btn.onclick = () => { mediaEl.play(); btn.remove(); };
        containerEl.appendChild(btn);
    });
}

function playGroupMedia(partNum, groupIdx, onDone) {
    const st = seqState[partNum];
    const group = st.groups[groupIdx];
    const stage = st.seqEl.querySelector('[data-role="media-stage"]');
    stage.style.display = '';
    stage.querySelector('[data-role="media-label"]').textContent = group.label;
    const fill = stage.querySelector('[data-role="progress-fill"]');
    fill.style.width = '0%';

    const videoEl = stage.querySelector('video[data-role="media-el"]');
    const mediaEl = videoEl || new Audio();
    if (videoEl) { videoEl.style.display = 'block'; videoEl.src = group.src; }
    else mediaEl.src = group.src;

    const onTime = () => {
        if (mediaEl.duration) fill.style.width = Math.min(100, (mediaEl.currentTime / mediaEl.duration) * 100) + '%';
    };
    const onEnded = () => {
        mediaEl.removeEventListener('timeupdate', onTime);
        mediaEl.removeEventListener('ended', onEnded);
        stage.style.display = 'none';
        onDone();
    };
    mediaEl.addEventListener('timeupdate', onTime);
    mediaEl.addEventListener('ended', onEnded);
    playWithFallback(mediaEl, stage);
}

function showSequentialQuestion(partNum) {
    const st = seqState[partNum];
    const item = st.flat[st.idx];
    const card = st.seqEl.querySelector(`[data-role="q-card"][data-qnum="${item.qnum}"]`);
    card.style.display = '';
    card.classList.remove('celpip-locked');

    const audioStage  = card.querySelector('[data-role="q-audio-stage"]');
    const answerStage = card.querySelector('[data-role="q-answer-stage"]');
    const timerVal    = card.querySelector('[data-role="q-timer-val"]');
    const timerWrap   = card.querySelector('[data-role="q-timer"]');
    const nextBtn     = card.querySelector('[data-role="next-btn"]');

    // Audio and options are NEVER shown at once: audio stage first, then it's
    // swapped out entirely for the answer stage once the audio finishes.
    audioStage.style.display = '';
    answerStage.style.display = 'none';
    timerVal.textContent = Q_SECONDS;
    timerWrap.classList.add('calm'); // grey while the question is being read aloud, not counting yet
    nextBtn.disabled = true;
    nextBtn.onclick = () => advanceSequential(partNum);

    function revealAnswerStage() {
        audioStage.style.display = 'none';
        answerStage.style.display = '';
        nextBtn.disabled = false;

        let remaining = Q_SECONDS;
        timerVal.textContent = remaining;
        timerWrap.classList.remove('calm');
        st.qTimerInterval = setInterval(() => {
            remaining--;
            timerVal.textContent = Math.max(0, remaining);
            if (remaining <= 0) { clearInterval(st.qTimerInterval); st.qTimerInterval = null; advanceSequential(partNum); }
        }, 1000);
    }

    // Play this question's individual "read aloud" audio (~5s) first; the
    // answer stage (and its 25-second countdown) only appears once that
    // finishes — matches the real CELPIP timing and never overlaps the audio.
    const qAudioSrc = card.dataset.audio;
    const playingLabel = card.querySelector('[data-role="q-playing-label"]');
    const qFill = card.querySelector('[data-role="q-progress-fill"]');
    if (qAudioSrc) {
        const a = new Audio(qAudioSrc);
        if (playingLabel) playingLabel.textContent = 'Playing…';
        if (qFill) qFill.style.width = '0%';
        a.addEventListener('timeupdate', () => { if (a.duration && qFill) qFill.style.width = Math.min(100, (a.currentTime / a.duration) * 100) + '%'; });
        a.addEventListener('ended', revealAnswerStage);
        playWithFallback(a, audioStage);
    } else {
        revealAnswerStage();
    }

    updateProgress();
}

function maybeStartPart(pNum) {
    const panel = document.getElementById('panel-' + pNum);
    if (!panel) return;
    if (panel.dataset.sequential === '1') {
        initSequentialPart(panel);
        if (seqState[pNum].idx === -1) advanceSequential(pNum);
    } else {
        initAllScreenPart(panel);
    }
}

// ── Parts 4-6: play the shared media once, then reveal the continue button ──
const allScreenStarted = {};
function initAllScreenPart(panel) {
    const partNum = parseInt(panel.dataset.part, 10);
    if (allScreenStarted[partNum]) return;
    allScreenStarted[partNum] = true;

    const stage = panel.querySelector('[data-role="allscreen-media"]');
    if (!stage) return;
    const fill = stage.querySelector('[data-role="progress-fill"]');
    const videoEl = stage.querySelector('video[data-role="media-el"]');
    const mediaEl = videoEl || new Audio();
    if (videoEl) { videoEl.style.display = 'block'; videoEl.src = stage.dataset.src; }
    else mediaEl.src = stage.dataset.src;

    const onTime = () => { if (mediaEl.duration) fill.style.width = Math.min(100, (mediaEl.currentTime / mediaEl.duration) * 100) + '%'; };
    const onEnded = () => {
        mediaEl.removeEventListener('timeupdate', onTime);
        mediaEl.removeEventListener('ended', onEnded);
        // Audio/video and questions are never shown at once: the media stage
        // disappears entirely, then the questions appear — same rule as Parts 1-3.
        stage.style.display = 'none';
        const qs = panel.querySelector('[data-role="allscreen-questions"]');
        if (qs) qs.style.display = '';
        updateProgress();
    };
    mediaEl.addEventListener('timeupdate', onTime);
    mediaEl.addEventListener('ended', onEnded);
    playWithFallback(mediaEl, stage);

    const contBtn = panel.querySelector('[data-role="part-continue-btn"]');
    if (contBtn) contBtn.onclick = () => goToNextPart(partNum);
}

// ── Progress ───────────────────────────────────────────────
function collectAnswers() {
    const ans = {};
    document.querySelectorAll('.answer-field').forEach(el => {
        const n = el.dataset.qnum;
        if (!n) return;
        if (el.type === 'radio'     && el.checked)      ans[n] = el.value;
        if (el.type === 'checkbox'  && el.checked)      ans[n] = el.value;
        if (el.tagName === 'SELECT' && el.value)         ans[n] = el.value;
        if (el.type === 'text'      && el.value.trim())  ans[n] = el.value.trim();
        if (el.type === 'hidden'    && el.value)         ans[n] = el.value;
    });
    return ans;
}

// ── Custom dropdown widget (celpip-dd) — same behavior as the Reading page ──
function closeAllDropdowns(except = null) {
    document.querySelectorAll('.celpip-dd-panel').forEach(p => { if (p !== except) p.hidden = true; });
}
document.addEventListener('click', (e) => {
    const trigger = e.target.closest('.celpip-dd-trigger');
    if (trigger) {
        const dd = trigger.closest('.celpip-dd');
        const panel = dd.querySelector('[data-role="dd-panel"]');
        const willOpen = panel.hidden;
        closeAllDropdowns();
        panel.hidden = !willOpen;
        return;
    }
    const option = e.target.closest('.celpip-dd-option');
    if (option) {
        const dd = option.closest('.celpip-dd');
        const radio = option.querySelector('input[type=radio]');
        const hidden = dd.querySelector('.celpip-dd-value');
        const label = dd.querySelector('[data-role="dd-label"]');
        radio.checked = true;
        hidden.value = radio.value;
        label.textContent = radio.dataset.text;
        dd.querySelector('.celpip-dd-trigger').classList.add('answered');
        closeAllDropdowns();
        hidden.dispatchEvent(new Event('change', { bubbles: true }));
        return;
    }
    if (!e.target.closest('.celpip-dd-panel')) closeAllDropdowns();
});

function updateProgress() {
    const ans   = collectAnswers();
    const count = Object.keys(ans).length;
    <?php foreach ($parts as $pNum => $pqs): ?>
    (function() {
        const pNums   = [<?= implode(',', array_column($pqs,'question_number')) ?>];
        const allDone = pNums.every(n => ans[n]);
        document.getElementById('ptab-<?= $pNum ?>').classList.toggle('all-answered', allDone);
    })();
    <?php endforeach; ?>
    document.getElementById('answeredCount').textContent = count + ' of ' + totalQs + ' answered';
}
document.querySelectorAll('.answer-field').forEach(el => {
    el.addEventListener('input',  updateProgress);
    el.addEventListener('change', updateProgress);
});

// ── Submit ─────────────────────────────────────────────────
function submitListening(auto = false) {
    if (submitting) return;
    const ans     = collectAnswers();
    const missing = totalQs - Object.keys(ans).length;
    if (!auto && missing > 0) {
        if (!confirm(`You have ${missing} unanswered question(s). Submit anyway?`)) return;
    }
    submitting = true;
    clearInterval(timerInterval);
    Object.values(seqState).forEach(st => { if (st.qTimerInterval) clearInterval(st.qTimerInterval); });
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting…';

    fetch('mock_save_section.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ session_id: SESSION_ID, section: 'listening', time_spent: elapsed, answers: ans })
    })
    .then(r => r.json())
    .then(d => {
        if (d.redirect) { window.location.href = d.redirect; }
        else {
            alert(d.error || 'An error occurred. Please try again.');
            submitting = false;
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-arrow-right-circle me-2"></i>Submit &amp; Continue to Reading';
        }
    })
    .catch(() => {
        alert('Network error. Please try again.');
        submitting = false;
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-arrow-right-circle me-2"></i>Submit &amp; Continue to Reading';
    });
}

function confirmExit() {
    if (confirm('Exit the test? Your progress will be lost.')) window.location.href = 'index.php';
}

window.addEventListener('beforeunload', e => { if (!submitting) { e.preventDefault(); e.returnValue = ''; } });

startTimer();
updateProgress();
maybeStartPart(1);
</script>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
