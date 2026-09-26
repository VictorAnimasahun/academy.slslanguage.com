<?php
// IELTS Academic Writing Task 1 (Cambridge IELTS 17 Academic, Test 2) – Writing Test 2. Chart task, 20 minutes, AI-marked via essay_analyzer.php.
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}
require_once INCLUDES_PATH . '/course_lock.php';
require_course_enrollment(course_ids_for_folders(['IELTS_Aca_2Mo', 'IELTS_Aca_3Mo']), 'this IELTS Academic Writing test');

$testCode   = 'IELTS_PT_W1_ACA_002';
$timeLimit  = 20 * 60;  // 20 minutes in seconds
$wordTarget = 150;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Academic Writing Task 1 – Test 2 | EduHub</title>
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
                    <li class="breadcrumb-item"><a href="ielts_writing_academic_002.php">Academic Writing Test 2</a></li>
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
                            <p class="mb-2"><strong>The table and charts below give information on the police budget for 2017 and 2018 in one area of Britain. The table shows where the money came from and the charts show how it was distributed.</strong></p>
                            <p class="mb-0">Summarise the information by selecting and reporting the main features, and make comparisons where relevant.</p>
                        </div>
                        <div class="table-responsive mb-3"><table class="table table-bordered table-sm" style="background:#fff"><caption class="fw-bold text-dark" style="caption-side:top">Police Budget 2017–2018 (in £m)</caption><thead class="table-light"><tr><th>Sources</th><th>2017</th><th>2018</th></tr></thead><tbody><tr><td>National Government</td><td>175.5m</td><td>177.8m</td></tr><tr><td>Local Taxes</td><td>91.2m</td><td>102.3m</td></tr><tr><td>Other sources (eg grants)</td><td>38m</td><td>38.5m</td></tr><tr class="fw-bold"><td>Total</td><td>304.7m</td><td>318.6m</td></tr></tbody></table></div><div class="mb-3" style="border:1px solid #e5e7eb;border-radius:10px;overflow:hidden"><svg viewBox="0 0 680 330" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Two pie charts: how the police money was spent in 2017 and 2018" style="width:100%;height:auto;background:#fff;font-family:system-ui,sans-serif"><text x="340.0" y="22" text-anchor="middle" font-size="15" font-weight="700">How the money was spent</text><text x="170" y="43" text-anchor="middle" font-size="14" font-weight="700">2017</text><path d="M170,150 L170.0,55.0 A95,95 0 1 1 75.0,150.0 Z" fill="#6b7280" stroke="#111" stroke-width="1"/><text x="211.6" y="195.6" text-anchor="middle" font-size="13" font-weight="700" fill="#fff">75%</text><path d="M170,150 L75.0,150.0 A95,95 0 0 1 86.8,104.2 Z" fill="#ffffff" stroke="#111" stroke-width="1"/><text x="113.0" y="139.4" text-anchor="middle" font-size="13" font-weight="700" fill="#111">8%</text><path d="M170,150 L86.8,104.2 A95,95 0 0 1 170.0,55.0 Z" fill="#111111" stroke="#111" stroke-width="1"/><text x="140.0" y="103.3" text-anchor="middle" font-size="13" font-weight="700" fill="#fff">17%</text><text x="510" y="43" text-anchor="middle" font-size="14" font-weight="700">2018</text><path d="M510,150 L510.0,55.0 A95,95 0 1 1 421.7,185.0 Z" fill="#6b7280" stroke="#111" stroke-width="1"/><text x="558.7" y="187.1" text-anchor="middle" font-size="13" font-weight="700" fill="#fff">69%</text><path d="M510,150 L421.7,185.0 A95,95 0 0 1 426.8,104.2 Z" fill="#ffffff" stroke="#111" stroke-width="1"/><text x="451.2" y="150.3" text-anchor="middle" font-size="13" font-weight="700" fill="#111">14%</text><path d="M510,150 L426.8,104.2 A95,95 0 0 1 510.0,55.0 Z" fill="#111111" stroke="#111" stroke-width="1"/><text x="480.0" y="103.3" text-anchor="middle" font-size="13" font-weight="700" fill="#fff">17%</text><rect x="235.0" y="265" width="12" height="12" fill="#6b7280" stroke="#111"/><text x="252.0" y="276" font-size="12">Salaries (officers and staff)</text><rect x="235.0" y="285" width="12" height="12" fill="#ffffff" stroke="#111"/><text x="252.0" y="296" font-size="12">Technology</text><rect x="235.0" y="305" width="12" height="12" fill="#111111" stroke="#111"/><text x="252.0" y="316" font-size="12">Buildings and transport</text></svg></div>assets/images/ielts_academic/writing_001_task1_chart.png" alt="Bar chart: thousands of men and women in further education in Britain, full-time and part-time, 1970/71, 1980/81 and 1990/91" class="img-fluid mb-3" style="border:1px solid #e5e7eb;border-radius:10px;width:100%;">

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
    const QUESTION = 'The table and charts below give information on the police budget for 2017 and 2018 in one area of Britain. The table shows where the money came from and the charts show how it was distributed.\n\nSummarise the information by selecting and reporting the main features, and make comparisons where relevant.';

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
            title:     'IELTS Academic Writing Task 1 – Test 2',
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