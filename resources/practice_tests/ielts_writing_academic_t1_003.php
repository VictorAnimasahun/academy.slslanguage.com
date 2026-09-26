<?php
// IELTS Academic Writing Task 1 (Cambridge IELTS 17 Academic, Test 3) – Writing Test 3. Chart task, 20 minutes, AI-marked via essay_analyzer.php.
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}
require_once INCLUDES_PATH . '/course_lock.php';
require_course_enrollment(course_ids_for_folders(['IELTS_Aca_2Mo', 'IELTS_Aca_3Mo']), 'this IELTS Academic Writing test');

$testCode   = 'IELTS_PT_W1_ACA_003';
$timeLimit  = 20 * 60;  // 20 minutes in seconds
$wordTarget = 150;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Academic Writing Task 1 – Test 3 | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .main-wrapper { padding: 0; min-height: 100vh; }
        .test-container { max-width: none; }
        .panel { background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.07); height: 100%; }
        .section-badge { background: linear-gradient(135deg,#10b981,#34d399); color:white; padding:.5rem 1.5rem; border-radius:50px; font-weight:700; font-size:.9rem; }
        .timer-display { font-size: 2.2rem; font-weight: 700; font-family: monospace; color: #1e40af; }
        .timer-display.warning { color: #ef4444; }
        .prompt-box { background:#f0fdf4; border-left:4px solid #10b981; border-radius:8px; padding:1.25rem 1.5rem; margin-bottom:1.5rem; }
        .essay-textarea { width:100%; min-height:calc(100vh - 310px); padding:1.25rem; border:2px solid #e5e7eb; border-radius:10px; font-size:1rem; line-height:1.8; resize:vertical; font-family: system-ui,sans-serif; }
        .essay-textarea:focus { border-color:#10b981; outline:none; }
        .word-count { font-size:1.6rem; font-weight:700; }
        .word-count.below { color:#ef4444; }
        .word-count.ok    { color:#10b981; }
        .bottom-bar { display:flex; justify-content:space-between; align-items:center; margin-top:1rem; padding-top:1rem; border-top:1px solid #e5e7eb; }
        /* TODO: replace with actual chart/diagram image */
        .chart-placeholder { background:#f3f4f6; border:2px dashed #d1d5db; border-radius:10px; height:220px; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#9ca3af; font-size:.9rem; }
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
                    <li class="breadcrumb-item"><a href="ielts_writing_academic_003.php">Academic Writing Test 3</a></li>
                    <li class="breadcrumb-item active">Task 1</li>
                </ol>
            </nav>

            <div class="row g-4">
                <!-- LEFT: Prompt + chart -->
                <div class="col-lg-5">
                    <div class="panel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="section-badge">Writing Task 1</span>
                            <small class="text-muted">IELTS Academic</small>
                        </div>

                        <div class="prompt-box">
                            <p class="mb-2"><strong>The chart below gives information about how families in one country spent their weekly income in 1968 and in 2018.</strong></p>
                            <p class="mb-0">Summarise the information by selecting and reporting the main features, and make comparisons where relevant.</p>
                        </div>
                        <div class="mb-3" style="border:1px solid #e5e7eb;border-radius:10px;overflow:hidden"><svg viewBox="0 0 680 470" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Bar chart: how families in one country spent their weekly income in 1968 and in 2018" style="width:100%;height:auto;background:#fff;font-family:system-ui,sans-serif"><text x="340.0" y="24" text-anchor="middle" font-size="15" font-weight="700">1968 and 2018: average weekly spending by families</text><rect x="270.0" y="32" width="12" height="12" fill="#111"/><text x="286.0" y="43" font-size="12">1968</text><rect x="350.0" y="32" width="12" height="12" fill="#9ca3af"/><text x="366.0" y="43" font-size="12">2018</text><line x1="170.0" y1="60" x2="170.0" y2="428" stroke="#e5e7eb"/><text x="170.0" y="446" text-anchor="middle" font-size="11">0</text><line x1="230.0" y1="60" x2="230.0" y2="428" stroke="#e5e7eb"/><text x="230.0" y="446" text-anchor="middle" font-size="11">5</text><line x1="290.0" y1="60" x2="290.0" y2="428" stroke="#e5e7eb"/><text x="290.0" y="446" text-anchor="middle" font-size="11">10</text><line x1="350.0" y1="60" x2="350.0" y2="428" stroke="#e5e7eb"/><text x="350.0" y="446" text-anchor="middle" font-size="11">15</text><line x1="410.0" y1="60" x2="410.0" y2="428" stroke="#e5e7eb"/><text x="410.0" y="446" text-anchor="middle" font-size="11">20</text><line x1="470.0" y1="60" x2="470.0" y2="428" stroke="#e5e7eb"/><text x="470.0" y="446" text-anchor="middle" font-size="11">25</text><line x1="530.0" y1="60" x2="530.0" y2="428" stroke="#e5e7eb"/><text x="530.0" y="446" text-anchor="middle" font-size="11">30</text><line x1="590.0" y1="60" x2="590.0" y2="428" stroke="#e5e7eb"/><text x="590.0" y="446" text-anchor="middle" font-size="11">35</text><line x1="650.0" y1="60" x2="650.0" y2="428" stroke="#e5e7eb"/><text x="650.0" y="446" text-anchor="middle" font-size="11">40</text><text x="162" y="77" text-anchor="end" font-size="12">Food</text><rect x="170" y="60" width="420.0" height="15" fill="#111"/><rect x="170" y="75" width="204.0" height="15" fill="#9ca3af"/><text x="162" y="123" text-anchor="end" font-size="12">Housing</text><rect x="170" y="106" width="120.0" height="15" fill="#111"/><rect x="170" y="121" width="228.0" height="15" fill="#9ca3af"/><text x="162" y="169" text-anchor="end" font-size="12">Fuel and power</text><rect x="170" y="152" width="72.0" height="15" fill="#111"/><rect x="170" y="167" width="48.0" height="15" fill="#9ca3af"/><text x="162" y="215" text-anchor="end" font-size="12">Clothing and footwear</text><rect x="170" y="198" width="120.0" height="15" fill="#111"/><rect x="170" y="213" width="60.0" height="15" fill="#9ca3af"/><text x="162" y="261" text-anchor="end" font-size="12">Household goods</text><rect x="170" y="244" width="96.0" height="15" fill="#111"/><rect x="170" y="259" width="96.0" height="15" fill="#9ca3af"/><text x="162" y="307" text-anchor="end" font-size="12">Personal goods</text><rect x="170" y="290" width="96.0" height="15" fill="#111"/><rect x="170" y="305" width="48.0" height="15" fill="#9ca3af"/><text x="162" y="353" text-anchor="end" font-size="12">Transport</text><rect x="170" y="336" width="96.0" height="15" fill="#111"/><rect x="170" y="351" width="168.0" height="15" fill="#9ca3af"/><text x="162" y="399" text-anchor="end" font-size="12">Leisure</text><rect x="170" y="382" width="108.0" height="15" fill="#111"/><rect x="170" y="397" width="264.0" height="15" fill="#9ca3af"/><text x="410.0" y="462" text-anchor="middle" font-size="12" font-weight="700">% of weekly income</text></svg></div>assets/images/ielts_academic/writing_001_task1_chart.png" alt="Bar chart: thousands of men and women in further education in Britain, full-time and part-time, 1970/71, 1980/81 and 1990/91" class="img-fluid mb-3" style="border:1px solid #e5e7eb;border-radius:10px;width:100%;">

                                        <div class="alert alert-light border small mb-0">
                            <i class="bi bi-info-circle me-1 text-success"></i>
                            Write <strong>at least <?= $wordTarget ?> words</strong>. You should spend about <strong>20 minutes</strong> on this task.
                        </div>
                    </div>
                </div>

                <!-- RIGHT: Response + timer -->
                <div class="col-lg-7">
                    <div class="panel d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Your Response</h5>
                            <div class="timer-display" id="timerEl">20:00</div>
                        </div>

                        <textarea id="responseText" class="essay-textarea flex-grow-1"
                            placeholder="The chart shows..."></textarea>

                        <div class="bottom-bar">
                            <div>
                                <span id="wordCount" class="word-count below">0</span>
                                <span class="text-muted ms-1">/ <?= $wordTarget ?>+ words</span>
                            </div>
                            <button id="submitBtn" class="btn btn-success px-4 py-2 fw-600" disabled onclick="submitResponse()">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <?php include INCLUDES_PATH . '/footer.php'; ?>
    <script>
    const TARGET = <?= $wordTarget ?>;
    let timeLeft = <?= $timeLimit ?>;
    let submitted = false;

    // Build the question string from the prompt box HTML for practice mode
    const QUESTION = 'The chart below gives information about how families in one country spent their weekly income in 1968 and in 2018.\n\nSummarise the information by selecting and reporting the main features, and make comparisons where relevant.';

    const timerEl   = document.getElementById('timerEl');
    const textarea  = document.getElementById('responseText');
    const wordEl    = document.getElementById('wordCount');
    const submitBtn = document.getElementById('submitBtn');

    function fmtTime(s) {
        return String(Math.floor(s/60)).padStart(2,'0') + ':' + String(s%60).padStart(2,'0');
    }

    function countWords(txt) {
        return txt.trim() === '' ? 0 : txt.trim().split(/\s+/).length;
    }

    function updateWordCount() {
        const n = countWords(textarea.value);
        wordEl.textContent = n;
        wordEl.className   = 'word-count ' + (n >= TARGET ? 'ok' : 'below');
        submitBtn.disabled = n < TARGET - 10;
    }

    const interval = setInterval(() => {
        timeLeft--;
        timerEl.textContent = fmtTime(timeLeft);
        if (timeLeft <= 300) timerEl.classList.add('warning');
        if (timeLeft <= 0) {
            clearInterval(interval);
            Swal.fire({
                title: "Time's up!",
                text: 'Your response has been automatically submitted.',
                icon: 'warning', timer: 2500, timerProgressBar: true, showConfirmButton: false,
            }).then(() => doSubmit());
        }
    }, 1000);

    function submitResponse() {
        const words = countWords(textarea.value);
        Swal.fire({
            title: 'Submit response?',
            html: `Words written: <strong>${words}</strong>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Submit',
            cancelButtonText: 'Keep writing',
            confirmButtonColor: '#10b981',
        }).then(r => { if (r.isConfirmed) doSubmit(); });
    }

    function doSubmit() {
        if (submitted) return;
        submitted = true;
        clearInterval(interval);
        const words    = countWords(textarea.value);
        const timeUsed = <?= $timeLimit ?> - timeLeft;
        const params = new URLSearchParams({
            test_code: '<?= $testCode ?>',
            task_type: 'writing_task1',
            type:      'writing_task1',
            title:     'IELTS Academic Writing Task 1 – Test 3',
            testType:  'IELTS Academic',
            question:  QUESTION,
            response:  textarea.value,
            words:     words,
            time:      timeUsed,
        });
        window.location.href = '../essay_analyzer.php?' + params.toString();
    }

    textarea.addEventListener('input', updateWordCount);
    updateWordCount();
    </script>
</body>
</html>