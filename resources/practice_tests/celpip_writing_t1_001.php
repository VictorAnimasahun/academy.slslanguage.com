<?php
// CELPIP Writing Task 1 – Email (Practice 001)
// TODO: Replace prompt with real CELPIP content
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../../edu_hub_registration.php?message=Please+login"); exit(); }
$testCode  = 'CELPIP_PT_W1_001';
$timeLimit = 27 * 60;
$wordMin   = 150; $wordMax = 200;
?>
<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CELPIP Writing Task 1 – Practice 1 | EduHub</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
<style>
.main-wrapper{padding:1.5rem;background:#f8f9fa;min-height:100vh}
.test-container{max-width:1200px;margin:0 auto}
.panel{background:white;border-radius:16px;padding:2rem;box-shadow:0 4px 20px rgba(0,0,0,0.07);height:100%}
.section-badge{background:linear-gradient(135deg,#3b82f6,#60a5fa);color:white;padding:.45rem 1.4rem;border-radius:50px;font-weight:700;font-size:.85rem}
.timer-display{font-size:2.2rem;font-weight:700;font-family:monospace;color:#1e40af}
.timer-display.warning{color:#ef4444}
.prompt-box{background:#eff6ff;border-left:4px solid #3b82f6;border-radius:8px;padding:1.25rem 1.5rem;margin-bottom:1.25rem}
.essay-textarea{width:100%;min-height:340px;padding:1.25rem;border:2px solid #e5e7eb;border-radius:10px;font-size:1rem;line-height:1.8;resize:vertical;font-family:system-ui,sans-serif}
.essay-textarea:focus{border-color:#3b82f6;outline:none}
.word-count{font-size:1.6rem;font-weight:700}
.word-count.below{color:#ef4444}.word-count.ok{color:#10b981}.word-count.over{color:#f59e0b}
.bottom-bar{display:flex;justify-content:space-between;align-items:center;margin-top:1rem;padding-top:1rem;border-top:1px solid #e5e7eb}
</style>
</head><body>
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="mobile-overlay" id="mobileOverlay"></div>
<?php include INCLUDES_PATH . '/navbar.php'; ?>
<main class="main-wrapper"><div class="test-container">
<nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb">
<li class="breadcrumb-item"><a href="../resources_home.php">Resources</a></li>
<li class="breadcrumb-item"><a href="index.php">Practice Tests</a></li>
<li class="breadcrumb-item active">CELPIP Writing Task 1 – Practice 1</li>
</ol></nav>
<div class="row g-4">
<div class="col-lg-5"><div class="panel">
<div class="d-flex justify-content-between align-items-center mb-3">
<span class="section-badge">Writing Task 1 – Email</span>
<small class="text-muted">CELPIP</small></div>
<div class="prompt-box">
<!-- ══ TODO: Replace with actual CELPIP Task 1 email scenario ══ -->
<p class="mb-2"><strong>You recently moved to a new neighbourhood and would like to meet your neighbours.</strong></p>
<p class="mb-0">Write an email to the neighbourhood community group. In your email:</p>
<ul class="mt-2 mb-0">
<li>introduce yourself</li>
<li>explain why you are writing</li>
<li>suggest a way to get to know your neighbours</li>
</ul>
</div>
<div class="alert alert-light border small mb-0">
<i class="bi bi-info-circle me-1 text-primary"></i>
Write <strong><?= $wordMin ?>–<?= $wordMax ?> words</strong>. You have <strong>27 minutes</strong>.
</div>
</div></div>
<div class="col-lg-7"><div class="panel d-flex flex-column">
<div class="d-flex justify-content-between align-items-center mb-3">
<h5 class="mb-0">Your Email</h5>
<div class="timer-display" id="timerEl">27:00</div>
</div>
<textarea id="responseText" class="essay-textarea flex-grow-1"
placeholder="Subject: Introduction from a New Neighbour&#10;&#10;Dear Community Group,&#10;&#10;I am writing to..."></textarea>
<div class="bottom-bar">
<div>
<span id="wordCount" class="word-count below">0</span>
<span class="text-muted ms-1">words (<?= $wordMin ?>–<?= $wordMax ?>)</span>
</div>
<button id="submitBtn" class="btn btn-primary px-4 py-2" disabled onclick="submitResponse()">
Submit <i class="bi bi-send ms-1"></i></button>
</div>
</div></div>
</div>
</div></main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<script>
const MIN=<?=$wordMin?>,MAX=<?=$wordMax?>;
let timeLeft=<?=$timeLimit?>;
const timerEl=document.getElementById('timerEl'),textarea=document.getElementById('responseText'),
      wordEl=document.getElementById('wordCount'),submitBtn=document.getElementById('submitBtn');
function fmtTime(s){return String(Math.floor(s/60)).padStart(2,'0')+':'+String(s%60).padStart(2,'0')}
function countWords(t){return t.trim()===''?0:t.trim().split(/\s+/).length}
function updateWordCount(){
const n=countWords(textarea.value);wordEl.textContent=n;
wordEl.className='word-count '+(n<MIN?'below':n<=MAX?'ok':'over');
submitBtn.disabled=n<MIN-10||n>MAX+20;}
const interval=setInterval(()=>{timeLeft--;timerEl.textContent=fmtTime(timeLeft);
if(timeLeft<=300)timerEl.classList.add('warning');
if(timeLeft<=0){clearInterval(interval);
Swal.fire({title:"Time's up!",text:'Submitting your email.',icon:'warning',timer:2500,timerProgressBar:true,showConfirmButton:false}).then(()=>doSubmit());}
},1000);
function submitResponse(){
Swal.fire({title:'Submit email?',html:`Words: <strong>${countWords(textarea.value)}</strong>`,icon:'question',
showCancelButton:true,confirmButtonText:'Submit',cancelButtonText:'Keep writing',confirmButtonColor:'#3b82f6'})
.then(r=>{if(r.isConfirmed)doSubmit();});}
function doSubmit(){clearInterval(interval);
const params=new URLSearchParams({test_code:'<?=$testCode?>',type:'writing_task1',title:'CELPIP Writing Task 1 – Practice 1',
response:textarea.value,words:countWords(textarea.value),time:<?=$timeLimit?>-timeLeft});
window.location.href='../essay_analyzer.php?'+params.toString();}
textarea.addEventListener('input',updateWordCount);updateWordCount();
</script>
</body></html>
