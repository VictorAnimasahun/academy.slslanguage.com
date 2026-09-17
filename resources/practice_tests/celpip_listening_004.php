<?php
/**
 * CELPIP Listening — Practice Test 4 (SCAFFOLD, no real content yet).
 *
 * Same real, interactive engine as celpip_listening_001.php (see that file
 * and courses/CELPIP_Gen/lessons/celpip_listening_001.php — sequential
 * Parts 1-3, all-screen Parts 4-6, Instructions screen, split-panel audio,
 * dropdown Parts 4-6, admin skip/PREVIOUS/untimed controls) — deliberately
 * kept byte-for-byte identical in CSS/JS to that file. $PARTS/$QUESTIONS/
 * $ANSWER_KEY below are placeholders (one dummy question per part, no real
 * audio files) so a real transcript can be dropped straight in later
 * without touching any engine code.
 *
 * IMPORTANT: any future change to the shared Listening engine (CSS, JS
 * state machine, markup) must be ported to this file AND to
 * celpip_listening_002.php / celpip_listening_003.php / the real PT1 —
 * per explicit instruction, these scaffolds are meant to stay in lockstep
 * with PT1 so inserting real content later is a pure data change.
 *
 * No real audio exists at assets/audio/CELPIP_PT_L_004/ yet — every widget
 * gracefully falls back to "Audio not available yet — click Continue"
 * (see playWithFallback() below), so this renders and runs cleanly today.
 * Submitting will fail gracefully too (save_attempt.php returns "Test not
 * found") until a migration seeds CELPIP_PT_L_004 into the tests table.
 */
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}
require_once INCLUDES_PATH . '/course_lock.php';
require_course_enrollment([14], 'this CELPIP Listening practice test');
$isAdmin = is_platform_admin();

$testCode  = 'CELPIP_PT_L_004';
$audioBase = ACADEMY_URL . 'assets/audio/' . $testCode . '/';
$timeLimit = 47 * 60;
$backUrl   = ACADEMY_URL . 'resources/practice_tests/index.php';

$COMING_SOON = 'Content for this practice test has not been added yet. Check back soon.';

$PARTS = [
    1 => ['title' => 'Part 1: Listening to Problem Solving',         'blurb' => $COMING_SOON, 'sequential' => true,  'groups' => [['label' => 'Recording', 'audio' => 'part1/track1.mp3', 'qnums' => [1]]]],
    2 => ['title' => 'Part 2: Listening to a Daily Life Conversation','blurb' => $COMING_SOON, 'sequential' => true,  'groups' => [['label' => 'Recording', 'audio' => 'part2/track1.mp3', 'qnums' => [2]]]],
    3 => ['title' => 'Part 3: Listening for Information',            'blurb' => $COMING_SOON, 'sequential' => true,  'groups' => [['label' => 'Recording', 'audio' => 'part3/track1.mp3', 'qnums' => [3]]]],
    4 => ['title' => 'Part 4: Listening to a News Item',             'blurb' => $COMING_SOON, 'sequential' => false, 'audio' => 'part4/track1.mp3', 'qnums' => [4]],
    5 => ['title' => 'Part 5: Listening to a Discussion',            'blurb' => $COMING_SOON, 'sequential' => false, 'audio' => 'part5/track1.mp3', 'qnums' => [5]],
    6 => ['title' => 'Part 6: Listening for Viewpoints',             'blurb' => $COMING_SOON, 'sequential' => false, 'audio' => 'part6/track1.mp3', 'qnums' => [6]],
];

$QUESTIONS = [
    1 => ['text' => 'Placeholder — this question has not been added yet.', 'options' => ['A' => 'Placeholder option A', 'B' => 'Placeholder option B', 'C' => 'Placeholder option C', 'D' => 'Placeholder option D']],
    2 => ['text' => 'Placeholder — this question has not been added yet.', 'options' => ['A' => 'Placeholder option A', 'B' => 'Placeholder option B', 'C' => 'Placeholder option C', 'D' => 'Placeholder option D']],
    3 => ['text' => 'Placeholder — this question has not been added yet.', 'options' => ['A' => 'Placeholder option A', 'B' => 'Placeholder option B', 'C' => 'Placeholder option C', 'D' => 'Placeholder option D']],
    4 => ['text' => 'Placeholder — this question has not been added yet.', 'options' => ['A' => 'Placeholder option A', 'B' => 'Placeholder option B', 'C' => 'Placeholder option C', 'D' => 'Placeholder option D']],
    5 => ['text' => 'Placeholder — this question has not been added yet.', 'options' => ['A' => 'Placeholder option A', 'B' => 'Placeholder option B', 'C' => 'Placeholder option C', 'D' => 'Placeholder option D']],
    6 => ['text' => 'Placeholder — this question has not been added yet.', 'options' => ['A' => 'Placeholder option A', 'B' => 'Placeholder option B', 'C' => 'Placeholder option C', 'D' => 'Placeholder option D']],
];

$TOTAL_QS = count($QUESTIONS);
$ANSWER_KEY = [1=>'A', 2=>'A', 3=>'A', 4=>'A', 5=>'A', 6=>'A'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CELPIP Listening — Practice Test 4 | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <link rel="stylesheet" href="<?= ACADEMY_URL ?>assets/css/exam_theme.css">
    <style>
        /* Same CELPIP-authentic sequential flow as celpip_listening_001.php,
           reusing the exam_theme.css palette for visual consistency. */
        /* Same min-height for every part (sequential or all-screen) — so the
           white box's height stays consistent as you move between parts,
           instead of visibly jumping around based on how much each part's
           content happens to need. The one visible card (data-role toggles
           the rest to display:none, which drops them out of flex layout
           entirely) then stretches via flex:1 to actually FILL that box
           edge to edge, rather than floating centered inside a taller
           invisible area — since the card's background is the same color
           as .section-content's, any such gap just read as one big unfilled
           white area. Its own content is centered vertically within it
           instead (see .celpip-instructions-stage etc. below). */
        /* Tighter than exam_theme.css's default .section-content padding
           (1.5rem/1.75rem) — scoped to just this page's id rather than
           edited globally, since that shared class backs every other exam
           page too. */
        #sectionContent { padding: 10px; }
        .celpip-seq { display: flex; flex-direction: column; min-height: var(--seq-min-height, calc(100vh - 280px)); }
        .celpip-seq > .celpip-seq-card { flex: 1; display: flex; flex-direction: column; }
        .celpip-seq-card { background: var(--exam-surface); border: 1px solid var(--exam-line); border-radius: var(--exam-radius-lg); }
        /* Plain gray title bar with a NEXT/timer cluster on the right — matches
           the real CELPIP interface's persistent per-screen header exactly,
           rather than the earlier tinted-accent ribbon. */
        .celpip-seq-header { display: flex; align-items: center; justify-content: space-between; background: #eef0f2; padding: .65rem 1.25rem; border-bottom: 1px solid var(--exam-line); font-size: .85rem; font-weight: 700; color: #374151; }
        .celpip-seq-timer-wrap { display: flex; align-items: center; gap: .75rem; }
        .celpip-seq-timer { font-variant-numeric: tabular-nums; color: var(--exam-warn); font-weight: 700; }
        .celpip-seq-timer.calm { color: var(--exam-ink-muted); }
        .celpip-seq-body { padding: 1.25rem 1.5rem; flex: 1; display: flex; flex-direction: column; justify-content: center; }
        .celpip-media-stage, .celpip-media-stage-main, [data-role="q-audio-stage"] { position: relative; }
        .celpip-media-stage-body { padding: 2rem 1.5rem; text-align: center; flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; }
        .celpip-media-stage-main .celpip-media-stage-body { padding: 3rem 1.5rem; }
        .celpip-media-caption { font-size: .85rem; color: var(--exam-accent); margin-bottom: 1rem; }
        .celpip-media-caption .bi { margin-right: .3rem; }
        .celpip-audio-widget { display: flex; align-items: center; gap: 1rem; background: var(--exam-bg); border: 1px solid var(--exam-line); border-radius: var(--exam-radius-lg); padding: 1rem 1.25rem; max-width: 480px; margin: 0 auto; }
        .celpip-audio-widget i { font-size: 1.4rem; color: var(--exam-ink-muted); flex-shrink: 0; }
        .celpip-audio-widget-lg { max-width: 560px; padding: 1.5rem 1.75rem; }
        .celpip-audio-widget-lg i { font-size: 2rem; }
        .celpip-progress-track { flex: 1; height: 8px; background: var(--exam-surface); border: 1px solid var(--exam-line); border-radius: 999px; overflow: hidden; }
        .celpip-progress-fill { height: 100%; width: 0%; background: var(--exam-accent); transition: width .2s linear; }
        .celpip-playing-label { font-size: .8rem; color: var(--exam-ink-muted); margin-top: .6rem; font-style: italic; }
        /* Instructions-only screen shown before any audio starts, matching
           the real exam's separate "Instructions:" step. */
        .celpip-instructions-stage { padding: 3rem 2rem; flex: 1; display: flex; flex-direction: column; justify-content: center; }
        .celpip-instructions-label { display: flex; align-items: center; gap: .5rem; color: var(--exam-accent); font-weight: 700; margin-bottom: 1.25rem; font-size: .95rem; }
        .celpip-instructions-text { font-size: 1.05rem; font-weight: 600; color: var(--exam-ink); line-height: 1.6; }
        /* Split layout for Parts 1-3's per-question screen: audio (or the
           post-audio "click NEXT" notice) on the left, the answer options —
           visible from the moment the question starts, not gated behind the
           audio finishing — on the right. Matches the real interface, where
           students can read/select while the question is still playing. */
        .celpip-split-row { display: flex; flex: 1; }
        .celpip-split-left { flex: 1 1 45%; padding: 2rem 1.5rem; text-align: center; border-right: 1px solid var(--exam-line); display: flex; flex-direction: column; justify-content: center; align-items: center; }
        .celpip-split-left .celpip-media-caption { padding-right: 5rem; }
        .celpip-split-right { flex: 1 1 55%; padding: 1.5rem 1.75rem; display: flex; flex-direction: column; justify-content: center; }
        @media (max-width: 700px) {
            .celpip-split-row { flex-direction: column; }
            .celpip-split-left { border-right: none; border-bottom: 1px solid var(--exam-line); }
        }
        .celpip-continue-notice { display: flex; align-items: center; justify-content: center; gap: .5rem; background: var(--exam-bg); border: 1px solid var(--exam-line); border-radius: var(--exam-radius-lg); padding: 1.5rem 1.25rem; color: var(--exam-ink-muted); font-weight: 600; max-width: 480px; margin: 0 auto; }
        .celpip-q-of { font-size: .82rem; color: var(--exam-ink-muted); margin-bottom: .5rem; }
        .celpip-q-instr { font-size: .85rem; color: var(--exam-accent); margin-bottom: 1rem; }
        .celpip-q-instr .bi { margin-right: .3rem; }
        .celpip-next-btn { background: var(--exam-accent); color: #fff; border: none; border-radius: var(--exam-radius); padding: .45rem 1.4rem; font-weight: 700; font-size: .85rem; }
        .celpip-next-btn-inline { padding: .3rem 1.1rem; font-size: .8rem; }
        .celpip-tap-to-play { display: block; margin: 1rem auto 0; background: var(--exam-warn); color: #fff; border: none; border-radius: var(--exam-radius); padding: .55rem 1.5rem; font-weight: 700; font-size: .85rem; }
        /* Admin-only skip control — visually distinct (purple, top-right) from
           the real student controls so it's never mistaken for part of the
           actual test experience. */
        .celpip-admin-skip { position: absolute; top: .75rem; right: .75rem; background: #6366f1; color: #fff; border: none; border-radius: var(--exam-radius); padding: .4rem 1rem; font-weight: 700; font-size: .78rem; margin: 0; }
        .celpip-admin-prev { background: #64748b; }
        .celpip-admin-prev:disabled { opacity: .35; }
        .celpip-tap-to-play .bi { margin-right: .35rem; }
        .celpip-next-btn:disabled { opacity: .5; }
        .celpip-locked { opacity: .55; pointer-events: none; }
        .celpip-no-audio-notice { background: #fef3c7; border: 1px dashed #fcd34d; border-radius: var(--exam-radius); padding: .75rem 1.25rem; color: #92400e; font-size: .85rem; margin-top: 1rem; }
        video.celpip-media, audio.celpip-media { display: none; }
        .mc-question { margin-bottom: 1rem; }
        .mc-q-label { font-weight: 600; margin-bottom: .6rem; }
        .mc-option { display: block; padding: .5rem .75rem; border: 1px solid var(--exam-line); border-radius: var(--exam-radius); margin-bottom: .5rem; cursor: pointer; }
        .mc-option:hover { background: var(--exam-bg); }
        .q-badge { display: inline-flex; align-items: center; justify-content: center; min-width: 22px; height: 22px; background: var(--exam-accent); color: #fff; border-radius: 50%; font-size: .75rem; font-weight: 700; margin-right: .4rem; }
        /* Parts 4-6: sentence-completion style with an inline dropdown per
           blank, matching the real CELPIP interface (never a visible radio
           list for these parts). */
        .celpip-dropdown-q { margin-bottom: 1.35rem; font-size: .95rem; line-height: 1.8; color: var(--exam-ink); }
        .celpip-dropdown-q select { margin-left: .4rem; padding: .35rem .6rem; border: 1px solid var(--exam-line); border-radius: 6px; font-size: .9rem; max-width: 100%; background: var(--exam-surface); color: var(--exam-ink); }
    </style>
</head>
<body>

<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="mobile-overlay" id="mobileOverlay"></div>
<?php include INCLUDES_PATH . '/navbar.php'; ?>

<div class="main-wrapper flex-grow-1" style="flex:1;">
    <?php include INCLUDES_PATH . '/topbar.php'; ?>

    <main class="content p-3">

        <div class="sticky-header" id="stickyHeader">
            <?php if ($isAdmin): ?>
            <div style="background:#1e1b4b;color:#c7d2fe;padding:.3rem 1.25rem;border-radius:8px;margin-bottom:.3rem;font-size:.82rem;font-weight:600;">
                <span style="color:#a5b4fc;text-transform:uppercase;letter-spacing:.08em;font-size:.7rem;">Admin Preview — free navigation (students get the real linear flow)</span>
            </div>
            <?php endif; ?>
            <div class="d-flex align-items-center justify-content-between">
                <nav aria-label="breadcrumb" class="mb-0">
                    <ol class="breadcrumb mb-0" style="font-size:.8rem;">
                        <li class="breadcrumb-item"><a href="../resources_home.php">Resources</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Practice Tests</a></li>
                        <li class="breadcrumb-item active">Listening Practice 4</li>
                    </ol>
                </nav>
                <div class="inline-timer" id="inlineTimer"><i class="bi bi-clock-fill"></i> 00:00</div>
            </div>

            <!-- Part tab bar. Tabs are click-navigable for admins only —
                 students follow the real, enforced linear flow (see JS below). -->
            <div class="part-tabs-bar">
                <div class="part-tabs-scrollable">
                    <?php foreach ($PARTS as $pNum => $p): ?>
                    <button class="part-tab-btn <?= $pNum === 1 ? 'active' : '' ?>"
                            id="ptab-<?= $pNum ?>"
                            onclick="switchPart(<?= $pNum ?>, this)"
                            <?= (!$isAdmin && $pNum !== 1) ? 'disabled style="cursor:default;"' : '' ?>>
                        <span class="done-dot"></span>
                        Part <?= $pNum ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="section-content" id="sectionContent" style="padding-top:<?= $isAdmin ? '110px' : '60px' ?>;">

            <form id="listeningForm">
            <?php foreach ($PARTS as $partNum => $p): ?>
            <div class="part-panel <?= $partNum === 1 ? 'active' : '' ?>" id="panel-<?= $partNum ?>" data-part="<?= $partNum ?>" data-sequential="<?= $p['sequential'] ? '1' : '0' ?>">

                <?php if ($p['sequential']): ?>
                <div class="celpip-seq" data-groups='<?= htmlspecialchars(json_encode(array_map(fn($g) => ['label' => $g['label'], 'src' => $audioBase . $g['audio'], 'qnums' => $g['qnums']], $p['groups']))) ?>'>

                    <div class="celpip-seq-card" data-role="instructions-stage">
                        <div class="celpip-seq-header">
                            <span><?= htmlspecialchars($p['title']) ?></span>
                            <button type="button" class="celpip-next-btn celpip-next-btn-inline" data-role="instr-next-btn">NEXT</button>
                        </div>
                        <div class="celpip-instructions-stage">
                            <div class="celpip-instructions-label"><i class="bi bi-info-circle-fill"></i> Instructions:</div>
                            <div class="celpip-instructions-text"><?= htmlspecialchars($p['blurb']) ?></div>
                        </div>
                    </div>

                    <div class="celpip-seq-card celpip-media-stage celpip-media-stage-main" data-role="media-stage" style="display:none;">
                        <div class="celpip-seq-header">
                            <span><?= htmlspecialchars($p['title']) ?></span>
                        </div>
                        <div class="celpip-media-stage-body">
                            <div class="celpip-media-caption"><i class="bi bi-info-circle-fill"></i> Listen to <span data-role="media-label">the recording</span>. You will hear it only once.</div>
                            <div class="celpip-audio-widget celpip-audio-widget-lg">
                                <i class="bi bi-volume-up-fill"></i>
                                <div class="celpip-progress-track"><div class="celpip-progress-fill" data-role="progress-fill"></div></div>
                            </div>
                            <div class="celpip-playing-label">Playing…</div>
                        </div>
                    </div>

                    <?php
                    $flatQ = [];
                    foreach ($p['groups'] as $g) { foreach ($g['qnums'] as $qn) { $flatQ[] = $qn; } }
                    foreach ($flatQ as $qi => $qnum):
                        $q = $QUESTIONS[$qnum];
                    ?>
                    <div class="celpip-seq-card" data-role="q-card" data-qnum="<?= $qnum ?>" data-audio="<?= htmlspecialchars($audioBase . "part{$partNum}/q" . ($qi + 1) . ".mp3") ?>" style="display:none;">
                        <div class="celpip-seq-header">
                            <span><?= htmlspecialchars($p['title']) ?></span>
                            <span class="celpip-seq-timer-wrap">
                                <?php if ($isAdmin): ?>
                                <button type="button" class="celpip-next-btn celpip-next-btn-inline celpip-admin-prev" data-role="prev-btn"><i class="bi bi-skip-backward-fill me-1"></i>PREVIOUS</button>
                                <?php endif; ?>
                                <span class="celpip-seq-timer" data-role="q-timer">Time remaining: <strong data-role="q-timer-val">25</strong><span data-role="q-timer-unit"> seconds</span></span>
                                <button type="button" class="celpip-next-btn celpip-next-btn-inline" data-role="next-btn">NEXT</button>
                            </span>
                        </div>
                        <div class="celpip-split-row">
                            <div class="celpip-split-left" data-role="q-audio-stage">
                                <div class="celpip-media-caption" data-role="q-media-caption"><i class="bi bi-info-circle-fill"></i> Listen to the question. You will hear it only once.</div>
                                <div class="celpip-audio-widget" data-role="q-audio-widget">
                                    <i class="bi bi-volume-up-fill"></i>
                                    <div class="celpip-progress-track"><div class="celpip-progress-fill" data-role="q-progress-fill"></div></div>
                                </div>
                                <div class="celpip-playing-label" data-role="q-playing-label">Playing…</div>
                            </div>
                            <div class="celpip-split-right" data-role="q-answer-stage">
                                <div class="celpip-q-of">Question <?= $qi + 1 ?> of <?= count($flatQ) ?></div>
                                <div class="mc-question">
                                    <div class="mc-q-label"><span class="q-badge"><?= $qnum ?></span>Choose the best answer.</div>
                                    <?php foreach ($q['options'] as $letter => $optText): ?>
                                    <label class="mc-option">
                                        <input type="radio" name="answers[<?= $qnum ?>]" value="<?= $letter ?>" class="answer-field" data-qnum="<?= $qnum ?>">
                                        <strong><?= $letter ?>.</strong> <?= htmlspecialchars($optText) ?>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php else: ?>
                <div class="celpip-seq" data-role="allscreen-wrap">

                    <div class="celpip-seq-card" data-role="instructions-stage">
                        <div class="celpip-seq-header">
                            <span><?= htmlspecialchars($p['title']) ?></span>
                            <button type="button" class="celpip-next-btn celpip-next-btn-inline" data-role="instr-next-btn">NEXT</button>
                        </div>
                        <div class="celpip-instructions-stage">
                            <div class="celpip-instructions-label"><i class="bi bi-info-circle-fill"></i> Instructions:</div>
                            <div class="celpip-instructions-text"><?= htmlspecialchars($p['blurb']) ?></div>
                        </div>
                    </div>

                    <div class="celpip-seq-card celpip-media-stage celpip-media-stage-main" data-role="allscreen-media" data-src="<?= htmlspecialchars($audioBase . $p['audio']) ?>" style="display:none;">
                        <div class="celpip-seq-header">
                            <span><?= htmlspecialchars($p['title']) ?></span>
                        </div>
                        <div class="celpip-media-stage-body">
                            <div class="celpip-media-caption"><i class="bi bi-volume-up-fill"></i> Listen once — the questions will appear once it finishes.</div>
                            <div class="celpip-audio-widget celpip-audio-widget-lg">
                                <i class="bi bi-volume-up-fill"></i>
                                <div class="celpip-progress-track"><div class="celpip-progress-fill" data-role="progress-fill"></div></div>
                            </div>
                            <div class="celpip-playing-label">Playing…</div>
                        </div>
                    </div>

                    <div class="celpip-seq-card" data-role="allscreen-questions" style="display:none;">
                        <div class="celpip-seq-header">
                            <span><?= htmlspecialchars($p['title']) ?></span>
                            <?php if ($partNum !== 6): ?>
                            <button type="button" class="celpip-next-btn celpip-next-btn-inline" data-role="part-continue-btn" data-next-part="<?= $partNum + 1 ?>">NEXT</button>
                            <?php endif; ?>
                        </div>
                        <div class="celpip-seq-body">
                            <?php foreach ($p['qnums'] as $qnum): $q = $QUESTIONS[$qnum]; ?>
                            <div class="celpip-dropdown-q">
                                <span class="q-badge"><?= $qnum ?></span><?= htmlspecialchars($q['text']) ?>
                                <select class="answer-field" data-qnum="<?= $qnum ?>">
                                    <option value="" selected disabled>choose ⌄</option>
                                    <?php foreach ($q['options'] as $letter => $optText): ?>
                                    <option value="<?= $letter ?>"><?= $letter ?>. <?= htmlspecialchars($optText) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div>
            <?php endforeach; ?>
            </form>

        </div>
        <div style="height:80px;"></div>
    </main>
</div>

<div class="submit-bar">
    <div style="display:flex; align-items:center; gap:.5rem;">
        <i class="bi bi-check2-circle text-success fs-5"></i>
        <span id="answeredCount" style="font-size:.9rem; font-weight:600; color:#374151;">0 of <?= $TOTAL_QS ?> answered</span>
    </div>
    <button type="button" class="btn btn-primary fw-bold px-4" id="submitBtn" onclick="submitListening()">
        <i class="bi bi-send me-2"></i>Submit Test
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>

<script>
const DURATION    = <?= $timeLimit ?>;
const TEST_CODE   = <?= json_encode($testCode) ?>;
const SAVE_URL    = <?= json_encode(ACADEMY_URL . 'resources/practice_tests/save_attempt.php') ?>;
const BACK_URL    = <?= json_encode($backUrl) ?>;
const totalQs     = <?= $TOTAL_QS ?>;
const CORRECT     = <?= json_encode($ANSWER_KEY) ?>;
const IS_ADMIN    = <?= $isAdmin ? 'true' : 'false' ?>;
const TOTAL_PARTS = <?= count($PARTS) ?>;
const Q_SECONDS   = 25;
let elapsed       = 0;
let timerInterval;
let submitting    = false;

// Every playable clip is a plain `new Audio(...)` object, never inserted
// into the DOM (see playGroupMedia / showSequentialQuestion /
// initAllScreenPart below) — so a DOM query like
// "querySelectorAll('audio').pause()" can never find or stop them. Track
// the single currently-playing clip here instead, and always stop it
// before starting a new one (including on admin free-nav part switches),
// so two recordings are never audible at once.
let currentAudioEl = null;
function stopCurrentAudio() {
    if (currentAudioEl) currentAudioEl.pause();
    currentAudioEl = null;
    // A "Tap to play"/"Continue" button left over from a part the admin
    // navigated away from before tapping it would otherwise sit in the
    // hidden panel forever, fully clickable — tapping it later plays that
    // old part's audio directly (its onclick closure calls mediaEl.play()
    // itself), bypassing this tracker entirely and causing two recordings
    // to play at once. Removing every such leftover on every part switch
    // prevents that regardless of which part it belonged to.
    document.querySelectorAll('.celpip-tap-to-play, .celpip-no-audio-notice').forEach(el => el.remove());
}

const timerEl = document.getElementById('inlineTimer');

function fmt(sec) { return String(Math.floor(sec/60)).padStart(2,'0') + ':' + String(sec%60).padStart(2,'0'); }
function startTimer() {
    if (IS_ADMIN) {
        // Admins previewing content shouldn't get auto-submitted mid-review.
        timerEl.querySelector('i').nextSibling.textContent = '';
        return;
    }
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
    stopCurrentAudio();
    document.querySelectorAll('.part-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.part-tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('panel-' + pNum).classList.add('active');
    if (btn) btn.classList.add('active');
    maybeStartPart(pNum);
    syncContentOffset();
}

function goToNextPart(fromPartNum) {
    const tab = document.getElementById('ptab-' + fromPartNum);
    if (tab) tab.classList.add('all-answered');
    const nextPanel = document.getElementById('panel-' + (fromPartNum + 1));
    if (!nextPanel) return;
    if (!IS_ADMIN) {
        const nextTab = document.getElementById('ptab-' + (fromPartNum + 1));
        if (nextTab) nextTab.disabled = true;
    }
    switchPart(fromPartNum + 1, document.getElementById('ptab-' + (fromPartNum + 1)), true);
}

// Attempts to autoplay `mediaEl`. If the browser blocks it (no prior user
// gesture), or the file simply doesn't exist yet (audio not recorded), shows
// a button that lets the student continue instead of getting stuck forever
// waiting for an 'ended' event that will never fire.
// Two genuinely different failure modes, handled differently:
//   1. The file itself is missing/corrupt (a real 'error' event) — nothing
//      to play, so skip forward and say so honestly.
//   2. The browser blocked autoplay because there's been no user gesture on
//      this page yet (mediaEl.play() rejects, no 'error' fires) — the file
//      is perfectly fine, it just needs one tap to satisfy the browser's
//      policy. That tap must actually PLAY the real audio, not skip past it.
function playWithFallback(mediaEl, containerEl, onSkip) {
    let settled = false;

    function showMissing() {
        if (settled) return;
        settled = true;
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'celpip-tap-to-play';
        btn.innerHTML = '<i class="bi bi-play-fill"></i> Continue';
        const notice = document.createElement('div');
        notice.className = 'celpip-no-audio-notice';
        notice.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Audio not available yet for this recording — click Continue to proceed.';
        btn.onclick = () => { btn.remove(); notice.remove(); if (onSkip) onSkip(); };
        containerEl.appendChild(btn);
        containerEl.appendChild(notice);
    }

    function showTapToPlay() {
        if (settled) return;
        settled = true;
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'celpip-tap-to-play';
        btn.innerHTML = '<i class="bi bi-play-fill"></i> Tap to play';
        btn.onclick = () => {
            btn.remove();
            settled = false;
            stopCurrentAudio();
            currentAudioEl = mediaEl;
            mediaEl.play().catch(() => showMissing());
        };
        containerEl.appendChild(btn);
    }

    mediaEl.addEventListener('error', showMissing, { once: true });
    mediaEl.play().catch(() => showTapToPlay());
}

const seqState = {};
function initSequentialPart(panel) {
    const partNum = parseInt(panel.dataset.part, 10);
    if (seqState[partNum]) return;
    const seqEl  = panel.querySelector('.celpip-seq');
    const groups = JSON.parse(seqEl.dataset.groups);
    const flat   = [];
    groups.forEach((g, gi) => g.qnums.forEach((qn, qiInGroup) => flat.push({ qnum: qn, groupIdx: gi, isFirstInGroup: qiInGroup === 0 })));
    seqState[partNum] = { groups, flat, idx: -1, qTimerInterval: null, panel, seqEl, instrShown: false };
}

// The real exam always shows a standalone "Instructions:" screen before any
// audio starts. Shown once per part; clicking NEXT hides it and starts the
// normal sequential flow (first group's recording).
function showInstructions(partNum) {
    const st = seqState[partNum];
    st.instrShown = true;
    const stage = st.seqEl.querySelector('[data-role="instructions-stage"]');
    stage.style.display = '';
    const btn = stage.querySelector('[data-role="instr-next-btn"]');
    btn.onclick = () => { stage.style.display = 'none'; advanceSequential(partNum); };
}

function advanceSequential(partNum) {
    const st = seqState[partNum];
    if (!st) return;
    if (st.idx >= st.flat.length) return; // already finished — ignore a stray late call (e.g. a leftover audio event)
    if (st.qTimerInterval) { clearInterval(st.qTimerInterval); st.qTimerInterval = null; }
    if (st.idx >= 0) {
        const prevCard = st.seqEl.querySelector(`[data-role="q-card"][data-qnum="${st.flat[st.idx].qnum}"]`);
        if (prevCard) { prevCard.style.display = 'none'; prevCard.classList.add('celpip-locked'); }
    }
    st.idx++;
    if (st.idx >= st.flat.length) { goToNextPart(partNum); return; }
    const item = st.flat[st.idx];
    if (item.isFirstInGroup) playGroupMedia(partNum, item.groupIdx, () => showSequentialQuestion(partNum));
    else showSequentialQuestion(partNum);
}

// The left-hand panel of a Part 1-3 question toggles between the audio
// widget (while its question audio plays) and a plain "Click NEXT to
// continue" notice (once it's ended, or an admin skipped it) — matching the
// real interface, which never hides the answer options on the right, only
// swaps out the left side. These two helpers keep that toggle reversible,
// since a card can be shown more than once (admin goes back, then forward
// again re-triggers showSequentialQuestion for the same card).
function resetAudioStageForPlay(audioStage) {
    const notice = audioStage.querySelector('[data-role="q-continue-notice"]');
    if (notice) notice.remove();
    audioStage.querySelectorAll('[data-role="q-media-caption"], [data-role="q-audio-widget"], [data-role="q-playing-label"]').forEach(el => el.style.display = '');
}
function showContinueNotice(audioStage) {
    audioStage.querySelectorAll('.celpip-tap-to-play, .celpip-no-audio-notice, .celpip-admin-skip').forEach(el => el.remove());
    audioStage.querySelectorAll('[data-role="q-media-caption"], [data-role="q-audio-widget"], [data-role="q-playing-label"]').forEach(el => el.style.display = 'none');
    if (audioStage.querySelector('[data-role="q-continue-notice"]')) return;
    const notice = document.createElement('div');
    notice.className = 'celpip-continue-notice';
    notice.setAttribute('data-role', 'q-continue-notice');
    notice.innerHTML = '<i class="bi bi-info-circle-fill"></i> Click "NEXT" to continue.';
    audioStage.appendChild(notice);
}

// Lets an admin step back to the previous question within the same part for a
// quick re-check, without replaying its audio — jumps straight to its answer
// stage (mirroring revealAnswerStage's end-state). No-op at the part's first
// question, and only ever reachable via the admin-only PREVIOUS button.
function previousSequential(partNum) {
    const st = seqState[partNum];
    if (!st || !IS_ADMIN || st.idx <= 0) return;
    if (st.qTimerInterval) { clearInterval(st.qTimerInterval); st.qTimerInterval = null; }
    stopCurrentAudio();

    const curCard = st.seqEl.querySelector(`[data-role="q-card"][data-qnum="${st.flat[st.idx].qnum}"]`);
    if (curCard) { curCard.style.display = 'none'; curCard.classList.add('celpip-locked'); }

    st.idx--;
    const item = st.flat[st.idx];
    const card = st.seqEl.querySelector(`[data-role="q-card"][data-qnum="${item.qnum}"]`);
    card.style.display = '';
    card.classList.remove('celpip-locked');

    const audioStage  = card.querySelector('[data-role="q-audio-stage"]');
    const answerStage = card.querySelector('[data-role="q-answer-stage"]');
    const timerVal    = card.querySelector('[data-role="q-timer-val"]');
    const timerUnit   = card.querySelector('[data-role="q-timer-unit"]');
    const timerWrap   = card.querySelector('[data-role="q-timer"]');
    const nextBtn     = card.querySelector('[data-role="next-btn"]');
    const prevBtn     = card.querySelector('[data-role="prev-btn"]');

    showContinueNotice(audioStage);
    answerStage.style.display = '';
    timerVal.textContent = '∞';
    timerUnit.textContent = '';
    timerWrap.classList.add('calm');
    nextBtn.disabled = false;
    nextBtn.onclick = () => advanceSequential(partNum);
    if (prevBtn) { prevBtn.disabled = (st.idx === 0); prevBtn.onclick = () => previousSequential(partNum); }

    updateProgress();
}

// Admins previewing content shouldn't have to sit through every recording
// to see how a part looks — adds a visible skip control that does exactly
// what the audio finishing naturally would (same finish/reveal callback),
// just immediately. Never shown to students.
function addAdminSkip(containerEl, onSkip) {
    if (!IS_ADMIN) return;
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'celpip-next-btn celpip-admin-skip';
    btn.innerHTML = 'NEXT <i class="bi bi-skip-forward-fill ms-1"></i>';
    btn.onclick = () => { btn.remove(); stopCurrentAudio(); onSkip(); };
    containerEl.appendChild(btn);
}

function playGroupMedia(partNum, groupIdx, onDone) {
    const st = seqState[partNum];
    const group = st.groups[groupIdx];
    const stage = st.seqEl.querySelector('[data-role="media-stage"]');
    stage.style.display = '';
    stage.querySelector('[data-role="media-label"]').textContent = group.label;
    const fill = stage.querySelector('[data-role="progress-fill"]');
    fill.style.width = '0%';
    stopCurrentAudio();
    const mediaEl = new Audio(group.src);
    currentAudioEl = mediaEl;
    const onTime = () => { if (mediaEl.duration) fill.style.width = Math.min(100, (mediaEl.currentTime / mediaEl.duration) * 100) + '%'; };
    const finish = () => {
        mediaEl.removeEventListener('timeupdate', onTime);
        mediaEl.removeEventListener('ended', finish);
        stage.style.display = 'none';
        stage.querySelectorAll('.celpip-tap-to-play, .celpip-no-audio-notice').forEach(el => el.remove());
        onDone();
    };
    mediaEl.addEventListener('timeupdate', onTime);
    mediaEl.addEventListener('ended', finish);
    playWithFallback(mediaEl, stage, finish);
    addAdminSkip(stage, finish);
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
    const timerUnit   = card.querySelector('[data-role="q-timer-unit"]');
    const timerWrap   = card.querySelector('[data-role="q-timer"]');
    const nextBtn     = card.querySelector('[data-role="next-btn"]');
    const prevBtn     = card.querySelector('[data-role="prev-btn"]');

    resetAudioStageForPlay(audioStage);
    // Options are visible on the right from the moment the question starts —
    // never gated behind the audio finishing — matching the real interface.
    answerStage.style.display = '';
    timerVal.textContent = Q_SECONDS;
    timerWrap.classList.add('calm');
    nextBtn.disabled = true;
    nextBtn.onclick = () => advanceSequential(partNum);
    if (prevBtn) { prevBtn.disabled = (st.idx === 0); prevBtn.onclick = () => previousSequential(partNum); }

    function revealAnswerStage() {
        showContinueNotice(audioStage);
        nextBtn.disabled = false;
        if (IS_ADMIN) {
            // Admins click NEXT whenever they're done looking — no forced
            // countdown that yanks them to the next question mid-review.
            timerVal.textContent = '∞';
    timerUnit.textContent = '';
            return;
        }
        let remaining = Q_SECONDS;
        timerVal.textContent = remaining;
        timerWrap.classList.remove('calm');
        st.qTimerInterval = setInterval(() => {
            remaining--;
            timerVal.textContent = Math.max(0, remaining);
            if (remaining <= 0) { clearInterval(st.qTimerInterval); st.qTimerInterval = null; advanceSequential(partNum); }
        }, 1000);
    }

    const qAudioSrc = card.dataset.audio;
    const playingLabel = card.querySelector('[data-role="q-playing-label"]');
    const qFill = card.querySelector('[data-role="q-progress-fill"]');
    stopCurrentAudio();
    const a = new Audio(qAudioSrc);
    currentAudioEl = a;
    if (playingLabel) playingLabel.textContent = 'Playing…';
    if (qFill) qFill.style.width = '0%';
    a.addEventListener('timeupdate', () => { if (a.duration && qFill) qFill.style.width = Math.min(100, (a.currentTime / a.duration) * 100) + '%'; });
    a.addEventListener('ended', revealAnswerStage);
    playWithFallback(a, audioStage, revealAnswerStage);
    addAdminSkip(audioStage, revealAnswerStage);

    updateProgress();
}

function maybeStartPart(pNum) {
    const panel = document.getElementById('panel-' + pNum);
    if (!panel) return;
    if (panel.dataset.sequential === '1') {
        initSequentialPart(panel);
        if (seqState[pNum].idx === -1 && !seqState[pNum].instrShown) showInstructions(pNum);
    } else {
        initAllScreenPart(panel);
    }
}

const allScreenStarted = {};
function initAllScreenPart(panel) {
    const partNum = parseInt(panel.dataset.part, 10);
    if (allScreenStarted[partNum]) return;
    allScreenStarted[partNum] = true;

    const instrStage = panel.querySelector('[data-role="instructions-stage"]');
    const stage       = panel.querySelector('[data-role="allscreen-media"]');
    const qs          = panel.querySelector('[data-role="allscreen-questions"]');
    if (!stage) return;

    function startMedia() {
        instrStage.style.display = 'none';
        stage.style.display = '';
        const fill = stage.querySelector('[data-role="progress-fill"]');
        stopCurrentAudio();
        const mediaEl = new Audio(stage.dataset.src);
        currentAudioEl = mediaEl;
        const onTime = () => { if (mediaEl.duration) fill.style.width = Math.min(100, (mediaEl.currentTime / mediaEl.duration) * 100) + '%'; };
        const finish = () => {
            mediaEl.removeEventListener('timeupdate', onTime);
            mediaEl.removeEventListener('ended', finish);
            stage.style.display = 'none';
            stage.querySelectorAll('.celpip-tap-to-play, .celpip-no-audio-notice').forEach(el => el.remove());
            if (qs) qs.style.display = '';
            updateProgress();
        };
        mediaEl.addEventListener('timeupdate', onTime);
        mediaEl.addEventListener('ended', finish);
        playWithFallback(mediaEl, stage, finish);
        addAdminSkip(stage, finish);
    }

    const instrNext = instrStage.querySelector('[data-role="instr-next-btn"]');
    instrNext.onclick = startMedia;

    const contBtn = panel.querySelector('[data-role="part-continue-btn"]');
    if (contBtn) contBtn.onclick = () => goToNextPart(partNum);
}

function collectAnswers() {
    const ans = {};
    document.querySelectorAll('.answer-field').forEach(el => {
        const n = el.dataset.qnum;
        if (el.type === 'radio' && el.checked) ans[n] = el.value;
        else if (el.tagName === 'SELECT' && el.value) ans[n] = el.value;
    });
    return ans;
}
function updateProgress() {
    const ans = collectAnswers();
    const count = Object.keys(ans).length;
    document.getElementById('answeredCount').textContent = count + ' of ' + totalQs + ' answered';
}
document.querySelectorAll('.answer-field').forEach(el => {
    el.addEventListener('change', updateProgress);
});

function submitListening(auto = false) {
    if (submitting) return;
    const ans = collectAnswers();
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

    let score = 0;
    Object.keys(CORRECT).forEach(q => {
        if ((ans[q] || '').toUpperCase() === CORRECT[q]) score++;
    });

    fetch(SAVE_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ test_code: TEST_CODE, score: score, max_score: totalQs, band_score: 0, time_spent: elapsed, answers: ans })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            alert(`Test submitted! Score: ${score}/${totalQs}. Check My Results for details.`);
            window.location.href = BACK_URL;
        } else {
            alert(d.error || 'An error occurred. Please try again.');
            submitting = false; btn.disabled = false; btn.innerHTML = '<i class="bi bi-send me-2"></i>Submit Test';
        }
    })
    .catch(() => {
        alert('Network error. Please try again.');
        submitting = false; btn.disabled = false; btn.innerHTML = '<i class="bi bi-send me-2"></i>Submit Test';
    });
}

window.addEventListener('beforeunload', e => { if (!submitting) { e.preventDefault(); e.returnValue = ''; } });

// The sticky header's real height varies (admin banner adds a row, the part
// tabs can wrap on narrow screens) — a hardcoded padding-top guess either
// leaves a gap or lets the header overlap the content. Measure it for real
// instead, with a small breathing gap, and keep it in sync on resize. Also
// size the sequential-part frame to reach exactly down to the fixed submit
// bar (minus a little breathing room) instead of a hardcoded vh guess, so
// its vertical-centering is accurate regardless of how tall the header or
// submit bar end up being.
function syncContentOffset() {
    const header = document.getElementById('stickyHeader');
    const content = document.getElementById('sectionContent');
    if (header && content) {
        content.style.paddingTop = (header.getBoundingClientRect().height + 10) + 'px';
    }
    // Measure from wherever the active sequential frame actually sits (which
    // already accounts for the header, any admin banner, part tabs, etc. —
    // whatever precedes it) down to the fixed submit bar, rather than trying
    // to reconstruct that from the header's height alone.
    const submitBar = document.querySelector('.submit-bar');
    const activeSeq = document.querySelector('.part-panel.active .celpip-seq');
    if (submitBar && activeSeq) {
        const available = submitBar.getBoundingClientRect().top - activeSeq.getBoundingClientRect().top - 10;
        document.documentElement.style.setProperty('--seq-min-height', Math.max(200, available) + 'px');
    }
}
window.addEventListener('resize', syncContentOffset);

startTimer();
updateProgress();
maybeStartPart(1);
syncContentOffset();
</script>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
