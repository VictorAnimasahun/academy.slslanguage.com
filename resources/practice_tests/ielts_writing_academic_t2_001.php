<?php
// IELTS Academic Writing Task 2 (Task 2A) – Writing Test 1. Essay, 40 minutes, AI-marked via essay_analyzer.php.
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

require_once INCLUDES_PATH . '/course_lock.php';
require_course_enrollment([16, 17], 'this IELTS Academic Writing test');

$testCode   = 'IELTS_PT_W2_ACA_001';
$timeLimit  = 40 * 60;
$wordTarget = 250;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Academic Writing Task 2 – Test 1 | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .main-wrapper { padding: 0; min-height: 100vh; }
        .test-container { max-width: none; }
        .panel { background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.07); height: 100%; }
        .section-badge { background: linear-gradient(135deg,#3b82f6,#60a5fa); color:white; padding:.5rem 1.5rem; border-radius:50px; font-weight:700; font-size:.9rem; }
        .timer-display { font-size: 2.2rem; font-weight: 700; font-family: monospace; color: #1e40af; }
        .timer-display.warning { color: #ef4444; }
        .prompt-box { background:#eff6ff; border-left:4px solid #3b82f6; border-radius:8px; padding:1.25rem 1.5rem; margin-bottom:1.25rem; }
        .essay-textarea { width:100%; min-height:calc(100vh - 310px); padding:1.25rem; border:2px solid #e5e7eb; border-radius:10px; font-size:1rem; line-height:1.8; resize:vertical; font-family: system-ui,sans-serif; }
        .essay-textarea:focus { border-color:#3b82f6; outline:none; }
        .word-count { font-size:1.6rem; font-weight:700; }
        .word-count.below { color:#ef4444; }
        .word-count.ok    { color:#10b981; }
        .bottom-bar { display:flex; justify-content:space-between; align-items:center; margin-top:1rem; padding-top:1rem; border-top:1px solid #e5e7eb; }
    </style>
</head>
<body class="light">
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <div class="main-wrapper flex-grow-1" style="flex:1;">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>

    <main class="content p-2">
        <div class="test-container">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="ielts_writing_academic_001.php">Academic Writing Test 1</a></li>
                    <li class="breadcrumb-item active">Task 2</li>
                </ol>
            </nav>

            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="panel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="section-badge">Writing Task 2</span>
                            <small class="text-muted">IELTS Academic</small>
                        </div>

                        <div class="prompt-box">
                            <p class="mb-2"><strong>The first car appeared on British roads in 1888. By the year 2000 there may be as many as 29 million vehicles on British roads.</strong></p>
                            <p class="mb-2"><strong>Alternative forms of transport should be encouraged and international laws introduced to control car ownership and use.</strong></p>
                            <p class="mb-2"><strong>To what extent do you agree or disagree?</strong></p>
                            <p class="mb-0">Give reasons for your answer and include any relevant examples from your knowledge or experience.</p>
                        </div>

                        <div class="alert alert-light border small mb-0">
                            <i class="bi bi-info-circle me-1 text-primary"></i>
                            Write <strong>at least <?= $wordTarget ?> words</strong>. You should spend about <strong>40 minutes</strong> on this task, including planning time.
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="panel d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Your Response</h5>
                            <div class="timer-display" id="timerEl">40:00</div>
                        </div>

                        <textarea id="responseText" class="essay-textarea flex-grow-1"
                            placeholder="Begin your essay here..."></textarea>

                        <div class="bottom-bar">
                            <div>
                                <span id="wordCount" class="word-count below">0</span>
                                <span class="text-muted ms-1">/ <?= $wordTarget ?>+ words</span>
                            </div>
                            <button id="submitBtn" class="btn btn-primary px-4 py-2" disabled onclick="submitResponse()">
                                Submit <i class="bi bi-send ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    </div><!-- /.main-wrapper -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <?php include INCLUDES_PATH . '/footer.php'; ?>
    <script>
    const TARGET = <?= $wordTarget ?>;
    let timeLeft = <?= $timeLimit ?>;

    const timerEl  = document.getElementById('timerEl');
    const textarea = document.getElementById('responseText');
    const wordEl   = document.getElementById('wordCount');
    const submitBtn = document.getElementById('submitBtn');

    function fmtTime(s) { return String(Math.floor(s/60)).padStart(2,'0') + ':' + String(s%60).padStart(2,'0'); }
    function countWords(t) { return t.trim() === '' ? 0 : t.trim().split(/\s+/).length; }

    function updateWordCount() {
        const n = countWords(textarea.value);
        wordEl.textContent = n;
        wordEl.className   = 'word-count ' + (n >= TARGET ? 'ok' : 'below');
        submitBtn.disabled = n < TARGET - 20;
    }

    const interval = setInterval(() => {
        timeLeft--;
        timerEl.textContent = fmtTime(timeLeft);
        if (timeLeft <= 300) timerEl.classList.add('warning');
        if (timeLeft <= 0) {
            clearInterval(interval);
            Swal.fire({ title:"Time's up!", text:'Submitting your response.', icon:'warning', timer:2500, timerProgressBar:true, showConfirmButton:false }).then(() => doSubmit());
        }
    }, 1000);

    function submitResponse() {
        Swal.fire({
            title: 'Submit essay?',
            html: `Words: <strong>${countWords(textarea.value)}</strong>`,
            icon: 'question', showCancelButton: true,
            confirmButtonText: 'Submit', cancelButtonText: 'Keep writing',
            confirmButtonColor: '#3b82f6',
        }).then(r => { if (r.isConfirmed) doSubmit(); });
    }

    function doSubmit() {
        clearInterval(interval);
        const params = new URLSearchParams({
            test_code: '<?= $testCode ?>',
            type:      'writing_task2',
            title:     'IELTS Academic Writing Task 2 – Test 1',
            testType:  'IELTS Academic',
            task_type: 'writing_task2',
            question:  'The first car appeared on British roads in 1888. By the year 2000 there may be as many as 29 million vehicles on British roads.\n\nAlternative forms of transport should be encouraged and international laws introduced to control car ownership and use.\n\nTo what extent do you agree or disagree?\n\nGive reasons for your answer and include any relevant examples from your knowledge or experience.',
            response:  textarea.value,
            words:     countWords(textarea.value),
            time:      <?= $timeLimit ?> - timeLeft,
        });
        window.location.href = '../essay_analyzer.php?' + params.toString();
    }

    textarea.addEventListener('input', updateWordCount);
    updateWordCount();
    </script>
</body>
</html>