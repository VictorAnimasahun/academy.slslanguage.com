<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}
require_once INCLUDES_PATH . '/course_lock.php';
require_course_enrollment([14], 'this CELPIP Writing practice test');

$testCode = $writingConfig['test_code'];
$taskType = $writingConfig['task_type'];
$taskTitle = $writingConfig['task_title'];
$testNumber = $writingConfig['test_number'];
$scenario = $writingConfig['scenario'] ?? null;
$lead = $writingConfig['lead'] ?? null;
$bullets = $writingConfig['bullets'] ?? [];
$options = $writingConfig['options'] ?? [];
$promptHtml = $writingConfig['prompt_html'] ?? null; // fallback for not-yet-transcribed scaffolds
$placeholder = $writingConfig['placeholder'];
$timeLimit = $taskType === 'writing_task1' ? 27 * 60 : 26 * 60;
$wordMin = 150;
$wordMax = 200;
$isTask1 = $taskType === 'writing_task1';
$headerTitle = $isTask1 ? 'Writing Task 1: Writing an Email' : 'Writing Task 2: Responding to Survey Questions';
$submitLabel = $isTask1 ? 'Submit email' : 'Submit response';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($taskTitle) ?> | EduHub</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
<link rel="stylesheet" href="<?= ACADEMY_URL ?>assets/css/exam_theme.css">
<style>
.main-wrapper{padding:1.5rem;min-height:100vh}.test-container{max-width:1100px;margin:0 auto}
/* CELPIP Writing — official two-pane layout: scenario left, task
   instructions + response right — matches the real exam screen exactly
   (see mock_writing.php's identical CELPIP layout / reference screenshots
   2026-09-17). Built entirely from exam_theme.css's shared design tokens
   (muted blue-gray palette, 4-8px radii, flat/no-shadow) rather than a
   one-off palette. Kept in lockstep with that file's .celpip-w-* rules.
   overflow:auto on the textarea keeps its native resize handle usable, so
   the student can pull it taller than the default 260px. */
.celpip-w-shell { border: 1px solid var(--exam-line); border-radius: var(--exam-radius-lg); overflow: hidden; background: var(--exam-surface); }
.celpip-w-header { display: flex; align-items: center; justify-content: space-between; background: var(--exam-bg); padding: .7rem 1.25rem; border-bottom: 1px solid var(--exam-line); font-size: .95rem; font-weight: 700; color: var(--exam-ink); }
.celpip-w-timerwrap { display: flex; align-items: center; gap: .9rem; font-weight: 400; }
.celpip-w-timer { font-size: .88rem; color: var(--exam-ink-muted); }
.celpip-w-timer strong { color: var(--exam-ink); font-weight: 700; }
.celpip-w-next { background: var(--exam-accent); color: #fff; border: none; border-radius: var(--exam-radius); padding: .5rem 1.3rem; font-weight: 700; font-size: .85rem; cursor: pointer; }
.celpip-w-next:hover { opacity: .88; }
.celpip-w-next:disabled { opacity:.6; cursor:not-allowed; }
.celpip-w-split { display: grid; grid-template-columns: 1fr 1fr; min-height: 480px; }
.celpip-w-pane { padding: 1.5rem 1.75rem; }
.celpip-w-pane.left { background: var(--exam-surface); border-right: 1px solid var(--exam-line); }
.celpip-w-pane.right { background: var(--exam-accent-soft); display: flex; flex-direction: column; }
.celpip-w-heading { display: flex; align-items: flex-start; gap: .5rem; font-weight: 700; color: var(--exam-accent); font-size: .95rem; margin-bottom: 1rem; }
.celpip-w-heading .bi { margin-top: .15rem; flex-shrink: 0; }
.celpip-w-scenario { color: var(--exam-ink); font-size: .92rem; line-height: 1.7; white-space: pre-line; }
.celpip-w-bullets { color: var(--exam-ink); font-size: .92rem; line-height: 1.6; padding-left: 1.25rem; margin-bottom: 1rem; }
.celpip-w-bullets li { margin-bottom: .4rem; }
.celpip-w-options { display: flex; flex-direction: column; gap: .9rem; margin-bottom: 1.25rem; }
.celpip-w-option { display: flex; align-items: flex-start; gap: .6rem; font-size: .92rem; color: var(--exam-ink); cursor: pointer; }
.celpip-w-option input { margin-top: .25rem; flex-shrink: 0; }
.celpip-w-textarea { flex: 1; min-height: 260px; width: 100%; border: 1px solid var(--exam-line); border-radius: var(--exam-radius); padding: 1rem; font-size: .95rem; line-height: 1.6; resize: vertical; overflow: auto; font-family: inherit; background: var(--exam-surface); }
.celpip-w-textarea:focus { outline: none; border-color: var(--exam-accent); }
.celpip-w-wc { text-align: center; margin-top: .75rem; font-size: .85rem; color: var(--exam-ink-muted); font-weight: 600; }
@media (max-width: 900px) { .celpip-w-split { grid-template-columns: 1fr; } .celpip-w-pane.left { border-right: none; border-bottom: 1px solid var(--exam-line); } }
/* Fallback single-column layout, used only when a config has no
   scenario/lead yet (a not-yet-transcribed future scaffold). */
.fallback-panel{background:var(--exam-surface);border:1px solid var(--exam-line);border-radius:var(--exam-radius-lg);padding:2rem;}
.fallback-prompt{background:var(--exam-accent-soft);border-left:3px solid var(--exam-accent);border-radius:0 var(--exam-radius) var(--exam-radius) 0;padding:1.25rem 1.5rem}
</style>
</head>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?><div class="mobile-overlay" id="mobileOverlay"></div><?php include INCLUDES_PATH . '/navbar.php'; ?>
<div class="main-wrapper flex-grow-1" style="flex:1;"><?php include INCLUDES_PATH . '/topbar.php'; ?><main class="content p-2"><div class="test-container">
<nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../resources_home.php">Resources</a></li><li class="breadcrumb-item"><a href="index.php">Practice Tests</a></li><li class="breadcrumb-item active"><?= htmlspecialchars($taskTitle) ?></li></ol></nav>

<?php if ($scenario): ?>
<div class="celpip-w-shell">
    <div class="celpip-w-header">
        <span><?= htmlspecialchars($headerTitle) ?></span>
        <span class="celpip-w-timerwrap">
            <span class="celpip-w-timer">Time remaining: <strong id="timerEl"></strong></span>
            <button type="button" class="celpip-w-next" id="submitBtn" disabled><?= htmlspecialchars($submitLabel) ?></button>
        </span>
    </div>
    <div class="celpip-w-split">
        <div class="celpip-w-pane left">
            <p class="celpip-w-heading"><i class="bi bi-info-circle-fill"></i> Read the following information.</p>
            <div class="celpip-w-scenario"><?= nl2br(htmlspecialchars($scenario)) ?></div>
        </div>
        <div class="celpip-w-pane right">
            <p class="celpip-w-heading"><i class="bi bi-info-circle-fill"></i> <?= htmlspecialchars($lead ?? '') ?></p>
            <?php if (!empty($options)): ?>
            <div class="celpip-w-options">
                <?php foreach ($options as $label => $text): ?>
                <label class="celpip-w-option">
                    <input type="radio" name="celpipSurveyChoice">
                    <span><strong>Option <?= htmlspecialchars($label) ?>:</strong> <?= htmlspecialchars($text) ?></span>
                </label>
                <?php endforeach; ?>
            </div>
            <?php elseif (!empty($bullets)): ?>
            <ul class="celpip-w-bullets">
                <?php foreach ($bullets as $b): ?><li><?= htmlspecialchars($b) ?></li><?php endforeach; ?>
            </ul>
            <?php endif; ?>
            <textarea id="responseText" class="celpip-w-textarea" placeholder="<?= htmlspecialchars($placeholder, ENT_QUOTES) ?>"></textarea>
            <div class="celpip-w-wc"><span id="wordCount">0</span> words</div>
        </div>
    </div>
</div>
<?php
$questionForApi = trim(($scenario ?? '') . "\n\n" . ($lead ?? '') . (!empty($bullets) ? "\n" . implode("\n", $bullets) : '') . (!empty($options) ? "\nOption A: {$options['A']}\nOption B: {$options['B']}" : ''));
?>
<?php else: ?>
<!-- Fallback: content not transcribed yet (PT4 scaffolds) -->
<div class="row g-4">
<div class="col-lg-5"><div class="fallback-panel"><div class="d-flex justify-content-between align-items-center mb-3"><span class="badge" style="background:var(--exam-accent);padding:.45rem 1.4rem;border-radius:50px;font-weight:700;font-size:.85rem;"><?= htmlspecialchars($taskTitle) ?></span><small class="text-muted">CELPIP</small></div><div class="fallback-prompt"><p class="small text-muted mb-2">Practice Test <?= (int) $testNumber ?></p><?= $promptHtml ?></div></div></div>
<div class="col-lg-7"><div class="fallback-panel d-flex flex-column"><div class="d-flex justify-content-between align-items-center mb-3"><h5 class="mb-0">Your response</h5><div style="font-size:1.6rem;font-weight:700;font-family:monospace;color:var(--exam-ink);" id="timerEl"></div></div><textarea id="responseText" class="celpip-w-textarea flex-grow-1" placeholder="<?= htmlspecialchars($placeholder, ENT_QUOTES) ?>"></textarea><div class="d-flex justify-content-between align-items-center mt-3"><div><span id="wordCount">0</span><span class="text-muted ms-1">words</span></div><button id="submitBtn" class="btn btn-primary px-4 py-2" disabled><?= htmlspecialchars($submitLabel) ?> <i class="bi bi-send ms-1"></i></button></div></div></div>
</div>
<?php $questionForApi = strip_tags($promptHtml ?? ''); ?>
<?php endif; ?>

</div></main></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script><script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script><?php include INCLUDES_PATH . '/navbar_scripts.php'; ?><?php include INCLUDES_PATH . '/footer.php'; ?>
<script>
const MIN=<?= $wordMin ?>,MAX=<?= $wordMax ?>,testCode=<?= json_encode($testCode) ?>,taskType=<?= json_encode($taskType) ?>,taskTitle=<?= json_encode($taskTitle) ?>;
let timeLeft=<?= $timeLimit ?>;
let submitted=false;
const timerEl=document.getElementById('timerEl'),textarea=document.getElementById('responseText'),wordEl=document.getElementById('wordCount'),submitBtn=document.getElementById('submitBtn');
const QUESTION=<?= json_encode($questionForApi) ?>;
function fmtMinutes(s){const m=Math.floor(s/60),sec=s%60;return sec===0?`${m} minute${m===1?'':'s'}`:`${m} minute${m===1?'':'s'} ${sec} second${sec===1?'':'s'}`}
function countWords(text){return text.trim()===''?0:text.trim().split(/\s+/).length}
function updateWordCount(){const count=countWords(textarea.value);wordEl.textContent=count;submitBtn.disabled=count<MIN-10||count>MAX+20}
function doSubmit(){if(submitted)return;submitted=true;clearInterval(interval);const params=new URLSearchParams({test_code:testCode,task_type:taskType,type:taskType,title:taskTitle,testType:'CELPIP',question:QUESTION,response:textarea.value,words:countWords(textarea.value),time:<?= $timeLimit ?>-timeLeft});window.location.href='../essay_analyzer.php?'+params.toString()}
function submitResponse(){Swal.fire({title:'Submit response?',html:`Words written: <strong>${countWords(textarea.value)}</strong>`,icon:'question',showCancelButton:true,confirmButtonText:'Submit',cancelButtonText:'Keep writing',confirmButtonColor:'#2F5D8A'}).then(result=>{if(result.isConfirmed)doSubmit()})}
timerEl.textContent = fmtMinutes(timeLeft);
const interval=setInterval(()=>{timeLeft--;timerEl.textContent=fmtMinutes(timeLeft);if(timeLeft<=0){clearInterval(interval);Swal.fire({title:"Time's up!",text:'Your response has been automatically submitted.',icon:'warning',timer:2500,timerProgressBar:true,showConfirmButton:false}).then(()=>doSubmit())}},1000);
textarea.addEventListener('input',updateWordCount);submitBtn.addEventListener('click',submitResponse);updateWordCount();
</script>
</body></html>
