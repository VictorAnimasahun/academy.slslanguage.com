<?php
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

$student_id = (int)$_SESSION['user_id'];

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

if (!is_null($session['reading_attempt_id'])) {
    $map  = require INCLUDES_PATH . '/mock_test_map.php';
    $file = $map[$session['mock_code']]['writing']['file'] ?? 'mock_writing.php';
    header("Location: {$file}?session_id={$session_id}");
    exit();
}

// Enforce listening must be done first
if (is_null($session['listening_attempt_id'])) {
    $map  = require INCLUDES_PATH . '/mock_test_map.php';
    $file = $map[$session['mock_code']]['listening']['file'] ?? 'mock_start.php';
    header("Location: {$file}?session_id={$session_id}");
    exit();
}

// Load questions from DB
$map      = require INCLUDES_PATH . '/mock_test_map.php';
$testCode = $map[$session['mock_code']]['reading']['test_code'] ?? '';

$stmt = $db->prepare("SELECT id FROM tests WHERE code = ? AND is_active = 1 LIMIT 1");
$stmt->execute([$testCode]);
$test = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$test) {
    die("Reading test not configured. Please contact support.");
}
$test_id = (int)$test['id'];

$stmt = $db->prepare("
    SELECT q.id, q.question_number, q.question_type, q.question_text, q.instructions, q.part_number
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

// Group by part (passage)
$parts = [];
foreach ($questions as $q) {
    $parts[(int)($q['part_number'] ?? 1)][] = $q;
}
ksort($parts);

$DURATION_SECONDS = 60 * 60; // 60 minutes
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reading — <?= htmlspecialchars($session['mock_title']) ?> | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        body { background: #f8fafc; }
        .reading-header {
            background: linear-gradient(135deg, #8b5cf6, #a78bfa);
            color: #fff;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(139,92,246,.3);
        }
        .timer-box {
            background: rgba(255,255,255,.2);
            border-radius: 8px;
            padding: .35rem .9rem;
            font-size: 1.1rem;
            font-weight: 700;
            font-variant-numeric: tabular-nums;
            min-width: 90px;
            text-align: center;
        }
        .timer-box.warning { background: rgba(239,68,68,.4); }
        .passage-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
        }
        .passage-title {
            font-weight: 700;
            font-size: 1rem;
            color: #8b5cf6;
            border-bottom: 2px solid #f5f3ff;
            padding-bottom: .5rem;
            margin-bottom: 1rem;
        }
        .passage-text {
            font-size: .95rem;
            line-height: 1.8;
            color: #374151;
            background: #fafafa;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            max-height: 420px;
            overflow-y: auto;
            white-space: pre-wrap;
        }
        .questions-section { border-top: 2px solid #f5f3ff; padding-top: 1rem; }
        .q-block { margin-bottom: 1.25rem; }
        .q-num {
            font-weight: 700;
            color: #7c3aed;
            margin-bottom: .25rem;
            font-size: .9rem;
        }
        .q-text { font-size: .95rem; margin-bottom: .5rem; }
        .form-control.answer-input { max-width: 360px; }
        .submit-bar {
            background: #fff;
            border-top: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
            position: sticky;
            bottom: 0;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            z-index: 100;
        }
        .progress-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin-bottom: .5rem;
        }
        .progress-grid span {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .7rem;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            transition: background .15s;
        }
        .progress-grid span.answered { background: #8b5cf6; color: #fff; }
        .instructions-box {
            background: #f5f3ff;
            border-left: 3px solid #8b5cf6;
            border-radius: 6px;
            padding: .6rem 1rem;
            font-size: .875rem;
            color: #5b21b6;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

<div class="reading-header">
    <div>
        <span class="fw-bold fs-5"><i class="bi bi-book me-2"></i>Reading</span>
        <span class="ms-2 opacity-75 small"><?= htmlspecialchars($session['mock_title']) ?></span>
    </div>
    <div class="d-flex align-items-center gap-3">
        <div class="small opacity-75">Time Remaining</div>
        <div class="timer-box" id="timerDisplay">60:00</div>
    </div>
</div>

<div class="main-wrapper flex-grow-1">
    <main class="content p-3" style="max-width: 920px; margin: 0 auto;">

        <!-- Answer progress tracker -->
        <div class="passage-card mb-3 py-2 px-3">
            <div class="small fw-semibold text-muted mb-1">Answered Questions</div>
            <div class="progress-grid" id="progressGrid">
                <?php foreach ($questions as $q): ?>
                    <span id="pg-<?= $q['question_number'] ?>" title="Q<?= $q['question_number'] ?>"><?= $q['question_number'] ?></span>
                <?php endforeach; ?>
            </div>
        </div>

        <form id="readingForm">
            <?php foreach ($parts as $partNum => $partQuestions): ?>
            <?php
                $firstQ = $partQuestions[0];
                $lastQ  = end($partQuestions);
                $passageLabel = match((int)$partNum) {
                    1 => 'Passage 1',
                    2 => 'Passage 2',
                    3 => 'Passage 3',
                    default => "Passage {$partNum}"
                };
            ?>
            <div class="passage-card">
                <div class="passage-title">
                    <i class="bi bi-journal-text me-1"></i><?= $passageLabel ?>
                    <span class="fw-normal text-muted small ms-2">Questions <?= $firstQ['question_number'] ?>–<?= $lastQ['question_number'] ?></span>
                </div>

                <?php
                // If the first question has instructions that look like a passage, show it as passage text
                // Otherwise show a placeholder
                $passageText = $firstQ['instructions'] ?? '';
                if ($passageText):
                ?>
                <div class="passage-text"><?= htmlspecialchars($passageText) ?></div>
                <?php else: ?>
                <div class="alert alert-secondary small mb-3">
                    <i class="bi bi-info-circle me-1"></i>Reading passage for <?= $passageLabel ?> will appear here once uploaded.
                </div>
                <?php endif; ?>

                <div class="questions-section">
                    <h6 class="fw-bold mb-3">Questions <?= $firstQ['question_number'] ?>–<?= $lastQ['question_number'] ?></h6>

                    <?php foreach ($partQuestions as $q):
                        $qid   = (int)$q['id'];
                        $qnum  = (int)$q['question_number'];
                        $qtype = $q['question_type'];
                        $qopts = $options[$qid] ?? [];
                    ?>
                    <div class="q-block" id="qblock-<?= $qnum ?>">
                        <div class="q-num">Question <?= $qnum ?></div>

                        <?php if ($q['question_text']): ?>
                            <div class="q-text"><?= nl2br(htmlspecialchars($q['question_text'])) ?></div>
                        <?php endif; ?>

                        <?php if (in_array($qtype, ['multiple_choice_single', 'multiple_choice_multiple'])): ?>
                            <div class="ms-2">
                                <?php foreach ($qopts as $opt): ?>
                                    <div class="form-check mb-1">
                                        <input
                                            class="form-check-input answer-field"
                                            type="<?= $qtype === 'multiple_choice_multiple' ? 'checkbox' : 'radio' ?>"
                                            name="answers[<?= $qnum ?>]"
                                            id="q<?= $qnum ?>_<?= htmlspecialchars($opt['option_label']) ?>"
                                            value="<?= htmlspecialchars($opt['option_label']) ?>"
                                            data-qnum="<?= $qnum ?>"
                                        >
                                        <label class="form-check-label" for="q<?= $qnum ?>_<?= htmlspecialchars($opt['option_label']) ?>">
                                            <strong><?= htmlspecialchars($opt['option_label']) ?>.</strong>
                                            <?= htmlspecialchars($opt['option_text']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php elseif (in_array($qtype, ['true_false_not_given', 'yes_no_not_given'])): ?>
                            <div class="ms-2">
                                <?php
                                $tfOptions = $qtype === 'true_false_not_given'
                                    ? ['TRUE', 'FALSE', 'NOT GIVEN']
                                    : ['YES', 'NO', 'NOT GIVEN'];
                                foreach ($tfOptions as $tfOpt):
                                ?>
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input answer-field"
                                            type="radio"
                                            name="answers[<?= $qnum ?>]"
                                            id="q<?= $qnum ?>_<?= $tfOpt ?>"
                                            value="<?= $tfOpt ?>"
                                            data-qnum="<?= $qnum ?>"
                                        >
                                        <label class="form-check-label" for="q<?= $qnum ?>_<?= $tfOpt ?>">
                                            <?= $tfOpt ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php elseif ($qtype === 'matching_headings' || $qtype === 'matching_information'): ?>
                            <select name="answers[<?= $qnum ?>]" class="form-select answer-field" style="max-width:320px;" data-qnum="<?= $qnum ?>">
                                <option value="">— Select —</option>
                                <?php foreach ($qopts as $opt): ?>
                                    <option value="<?= htmlspecialchars($opt['option_label']) ?>">
                                        <?= htmlspecialchars($opt['option_label']) ?>. <?= htmlspecialchars($opt['option_text']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <!-- gap_fill, short_answer, sentence_completion, summary_completion, etc. -->
                            <input
                                type="text"
                                name="answers[<?= $qnum ?>]"
                                class="form-control answer-input answer-field"
                                data-qnum="<?= $qnum ?>"
                                placeholder="Write your answer"
                                autocomplete="off"
                            >
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>

            <?php if (empty($questions)): ?>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    No questions have been loaded for this reading test yet. Please contact your instructor.
                </div>
            <?php endif; ?>

        </form>

        <div style="height:80px;"></div>
    </main>
</div>

<div class="submit-bar">
    <div class="text-muted small align-self-center" id="answeredCount">0 of <?= count($questions) ?> answered</div>
    <button type="button" class="btn btn-primary btn-lg fw-bold px-4" id="submitBtn"
            style="background:#8b5cf6;border-color:#8b5cf6;"
            onclick="submitReading()">
        <i class="bi bi-arrow-right-circle me-2"></i>Submit &amp; Continue to Writing
    </button>
</div>

<script>
const DURATION = <?= $DURATION_SECONDS ?>;
const SESSION_ID = <?= $session_id ?>;
let elapsed = 0;
let timerInterval;

const timerEl = document.getElementById('timerDisplay');
const totalQs = <?= count($questions) ?>;

function formatTime(sec) {
    const m = Math.floor(sec / 60).toString().padStart(2, '0');
    const s = (sec % 60).toString().padStart(2, '0');
    return `${m}:${s}`;
}

function startTimer() {
    timerEl.textContent = formatTime(DURATION);
    timerInterval = setInterval(() => {
        elapsed++;
        const remaining = DURATION - elapsed;
        timerEl.textContent = formatTime(Math.max(0, remaining));
        if (remaining <= 300) timerEl.classList.add('warning');
        if (remaining <= 0) {
            clearInterval(timerInterval);
            submitReading(true);
        }
    }, 1000);
}

function collectAnswers() {
    const answers = {};
    document.querySelectorAll('.answer-field').forEach(el => {
        const qnum = el.dataset.qnum;
        if (!qnum) return;
        if (el.type === 'radio' && el.checked) answers[qnum] = el.value;
        else if (el.type === 'checkbox' && el.checked) answers[qnum] = el.value;
        else if (el.tagName === 'SELECT' && el.value) answers[qnum] = el.value;
        else if (el.type === 'text' && el.value.trim()) answers[qnum] = el.value.trim();
    });
    return answers;
}

function updateProgress() {
    const answers = collectAnswers();
    let count = 0;
    document.querySelectorAll('[id^="pg-"]').forEach(el => {
        const qnum = el.id.replace('pg-', '');
        if (answers[qnum]) { el.classList.add('answered'); count++; }
        else el.classList.remove('answered');
    });
    document.getElementById('answeredCount').textContent = `${count} of ${totalQs} answered`;
}

document.querySelectorAll('.answer-field').forEach(el => {
    el.addEventListener('input', updateProgress);
    el.addEventListener('change', updateProgress);
});

let submitting = false;
function submitReading(auto = false) {
    if (submitting) return;
    const answers = collectAnswers();
    const unanswered = totalQs - Object.keys(answers).length;

    if (!auto && unanswered > 0) {
        if (!confirm(`You have ${unanswered} unanswered question(s). Submit anyway?`)) return;
    }

    submitting = true;
    clearInterval(timerInterval);
    document.getElementById('submitBtn').disabled = true;
    document.getElementById('submitBtn').innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting…';

    fetch('mock_save_section.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            session_id: SESSION_ID,
            section: 'reading',
            time_spent: elapsed,
            answers: answers
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.redirect) {
            window.location.href = data.redirect;
        } else {
            alert(data.error || 'An error occurred. Please try again.');
            submitting = false;
            document.getElementById('submitBtn').disabled = false;
            document.getElementById('submitBtn').innerHTML = '<i class="bi bi-arrow-right-circle me-2"></i>Submit & Continue to Writing';
        }
    })
    .catch(() => {
        alert('Network error. Please check your connection and try again.');
        submitting = false;
        document.getElementById('submitBtn').disabled = false;
        document.getElementById('submitBtn').innerHTML = '<i class="bi bi-arrow-right-circle me-2"></i>Submit & Continue to Writing';
    });
}

window.addEventListener('beforeunload', e => {
    if (!submitting) {
        e.preventDefault();
        e.returnValue = '';
    }
});

startTimer();
updateProgress();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
