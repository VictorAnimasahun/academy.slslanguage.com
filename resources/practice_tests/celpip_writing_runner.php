<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}

$testCode = $writingConfig['test_code'];
$taskType = $writingConfig['task_type'];
$taskTitle = $writingConfig['task_title'];
$testNumber = $writingConfig['test_number'];
$promptHtml = $writingConfig['prompt_html'];
$placeholder = $writingConfig['placeholder'];
$timeLimit = $taskType === 'writing_task1' ? 27 * 60 : 26 * 60;
$wordMin = 150;
$wordMax = 200;
$submitLabel = $taskType === 'writing_task1' ? 'Submit email' : 'Submit response';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($taskTitle) ?> | EduHub</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
<style>
.main-wrapper{padding:1.5rem;background:#f8f9fa;min-height:100vh}.test-container{max-width:1200px;margin:0 auto}.panel{background:#fff;border-radius:16px;padding:2rem;box-shadow:0 4px 20px rgba(0,0,0,.07);height:100%}.section-badge{background:#3b82f6;color:#fff;padding:.45rem 1.4rem;border-radius:50px;font-weight:700;font-size:.85rem}.timer-display{font-size:2.2rem;font-weight:700;font-family:monospace;color:#1e40af}.timer-display.warning{color:#ef4444}.prompt-box{background:#eff6ff;border-left:4px solid #3b82f6;border-radius:8px;padding:1.25rem 1.5rem;margin-bottom:1.25rem}.prompt-box p:last-child{margin-bottom:0}.essay-textarea{width:100%;min-height:340px;padding:1.25rem;border:2px solid #e5e7eb;border-radius:10px;font-size:1rem;line-height:1.8;resize:vertical;font-family:system-ui,sans-serif}.essay-textarea:focus{border-color:#3b82f6;outline:none}.word-count{font-size:1.6rem;font-weight:700}.word-count.below{color:#ef4444}.word-count.ok{color:#10b981}.word-count.over{color:#f59e0b}.bottom-bar{display:flex;justify-content:space-between;align-items:center;margin-top:1rem;padding-top:1rem;border-top:1px solid #e5e7eb}
</style>
</head>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?><div class="mobile-overlay" id="mobileOverlay"></div><?php include INCLUDES_PATH . '/navbar.php'; ?>
<div class="main-wrapper flex-grow-1" style="flex:1;"><?php include INCLUDES_PATH . '/topbar.php'; ?><main class="content p-2"><div class="test-container">
<nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../resources_home.php">Resources</a></li><li class="breadcrumb-item"><a href="index.php">Practice Tests</a></li><li class="breadcrumb-item active"><?= htmlspecialchars($taskTitle) ?></li></ol></nav>
<div class="row g-4">
<div class="col-lg-5"><div class="panel"><div class="d-flex justify-content-between align-items-center mb-3"><span class="section-badge"><?= htmlspecialchars($taskTitle) ?></span><small class="text-muted">CELPIP</small></div><div class="prompt-box"><p class="small text-muted mb-2">Practice Test <?= (int) $testNumber ?> · Source: CELPIP Writing task bundle</p><?= $promptHtml ?></div><div class="alert alert-light border small mb-0"><i class="bi bi-info-circle me-1 text-primary"></i>Write <strong><?= $wordMin ?>–<?= $wordMax ?> words</strong>. You have <strong><?= $taskType === 'writing_task1' ? 27 : 26 ?> minutes</strong>.</div></div></div>
<div class="col-lg-7"><div class="panel d-flex flex-column"><div class="d-flex justify-content-between align-items-center mb-3"><h5 class="mb-0">Your response</h5><div class="timer-display" id="timerEl"><?= $taskType === 'writing_task1' ? '27:00' : '26:00' ?></div></div><textarea id="responseText" class="essay-textarea flex-grow-1" placeholder="<?= htmlspecialchars($placeholder, ENT_QUOTES) ?>"></textarea><div class="bottom-bar"><div><span id="wordCount" class="word-count below">0</span><span class="text-muted ms-1">words (<?= $wordMin ?>–<?= $wordMax ?>)</span></div><button id="submitBtn" class="btn btn-primary px-4 py-2" disabled><?= htmlspecialchars($submitLabel) ?> <i class="bi bi-send ms-1"></i></button></div></div></div>
</div></div></main></div>
<?php include INCLUDES_PATH . '/adverts.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script><script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script><?php include INCLUDES_PATH . '/navbar_scripts.php'; ?><?php include INCLUDES_PATH . '/footer.php'; ?>
<script>
const MIN=<?= $wordMin ?>,MAX=<?= $wordMax ?>,testCode=<?= json_encode($testCode) ?>,taskType=<?= json_encode($taskType) ?>,taskTitle=<?= json_encode($taskTitle) ?>;
let timeLeft=<?= $timeLimit ?>;
let submitted=false;
const timerEl=document.getElementById('timerEl'),textarea=document.getElementById('responseText'),wordEl=document.getElementById('wordCount'),submitBtn=document.getElementById('submitBtn');
const QUESTION=<?= json_encode(strip_tags(str_replace(['</p>','</li>'], ["\n", "\n"], $promptHtml))) ?>;
function fmtTime(seconds){return String(Math.floor(seconds/60)).padStart(2,'0')+':'+String(seconds%60).padStart(2,'0')}
function countWords(text){return text.trim()===''?0:text.trim().split(/\s+/).length}
function updateWordCount(){const count=countWords(textarea.value);wordEl.textContent=count;wordEl.className='word-count '+(count<MIN?'below':count<=MAX?'ok':'over');submitBtn.disabled=count<MIN-10||count>MAX+20}
function doSubmit(){if(submitted)return;submitted=true;clearInterval(interval);const params=new URLSearchParams({test_code:testCode,task_type:taskType,type:taskType,title:taskTitle,testType:'CELPIP',question:QUESTION,response:textarea.value,words:countWords(textarea.value),time:<?= $timeLimit ?>-timeLeft});window.location.href='../essay_analyzer.php?'+params.toString()}
function submitResponse(){Swal.fire({title:'Submit response?',html:`Words written: <strong>${countWords(textarea.value)}</strong>`,icon:'question',showCancelButton:true,confirmButtonText:'Submit',cancelButtonText:'Keep writing',confirmButtonColor:'#3b82f6'}).then(result=>{if(result.isConfirmed)doSubmit()})}
const interval=setInterval(()=>{timeLeft--;timerEl.textContent=fmtTime(timeLeft);if(timeLeft<=300)timerEl.classList.add('warning');if(timeLeft<=0){clearInterval(interval);Swal.fire({title:"Time's up!",text:'Your response has been automatically submitted.',icon:'warning',timer:2500,timerProgressBar:true,showConfirmButton:false}).then(()=>doSubmit())}},1000);
textarea.addEventListener('input',updateWordCount);submitBtn.addEventListener('click',submitResponse);updateWordCount();
</script>
</body></html>
