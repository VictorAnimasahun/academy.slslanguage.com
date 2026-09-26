<?php
// IELTS Academic Writing Task 1 (Cambridge IELTS 17 Academic, Test 4) – Writing Test 4. Chart task, 20 minutes, AI-marked via essay_analyzer.php.
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}
require_once INCLUDES_PATH . '/course_lock.php';
require_course_enrollment(course_ids_for_folders(['IELTS_Aca_2Mo', 'IELTS_Aca_3Mo']), 'this IELTS Academic Writing test');

$testCode   = 'IELTS_PT_W1_ACA_004';
$timeLimit  = 20 * 60;  // 20 minutes in seconds
$wordTarget = 150;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Academic Writing Task 1 – Test 4 | EduHub</title>
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
                    <li class="breadcrumb-item"><a href="ielts_writing_academic_004.php">Academic Writing Test 4</a></li>
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
                            <p class="mb-2"><strong>The graph below shows the number of shops that closed and the number of new shops that opened in one country between 2011 and 2018.</strong></p>
                            <p class="mb-0">Summarise the information by selecting and reporting the main features, and make comparisons where relevant.</p>
                        </div>
                        <div class="mb-3" style="border:1px solid #e5e7eb;border-radius:10px;overflow:hidden"><svg viewBox="0 0 700 420" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Line graph: number of shop closures and openings, 2011 to 2018" style="width:100%;height:auto;background:#fff;font-family:system-ui,sans-serif"><text x="350.0" y="22" text-anchor="middle" font-size="15" font-weight="700">Number of shop closures and openings 2011–2018</text><line x1="200.0" y1="40" x2="240.0" y2="40" stroke="#111" stroke-width="2"/><circle cx="220.0" cy="40" r="4" fill="#111"/><text x="246.0" y="44" font-size="12">Closures</text><line x1="360.0" y1="40" x2="400.0" y2="40" stroke="#111" stroke-width="2" stroke-dasharray="6 4"/><circle cx="380.0" cy="40" r="4" fill="#111"/><text x="406.0" y="44" font-size="12">Openings</text><line x1="70" y1="370.0" x2="670" y2="370.0" stroke="#e5e7eb"/><text x="62" y="374.0" text-anchor="end" font-size="11">0</text><line x1="70" y1="335.55555555555554" x2="670" y2="335.55555555555554" stroke="#e5e7eb"/><text x="62" y="339.55555555555554" text-anchor="end" font-size="11">1,000</text><line x1="70" y1="301.1111111111111" x2="670" y2="301.1111111111111" stroke="#e5e7eb"/><text x="62" y="305.1111111111111" text-anchor="end" font-size="11">2,000</text><line x1="70" y1="266.6666666666667" x2="670" y2="266.6666666666667" stroke="#e5e7eb"/><text x="62" y="270.6666666666667" text-anchor="end" font-size="11">3,000</text><line x1="70" y1="232.22222222222223" x2="670" y2="232.22222222222223" stroke="#e5e7eb"/><text x="62" y="236.22222222222223" text-anchor="end" font-size="11">4,000</text><line x1="70" y1="197.77777777777777" x2="670" y2="197.77777777777777" stroke="#e5e7eb"/><text x="62" y="201.77777777777777" text-anchor="end" font-size="11">5,000</text><line x1="70" y1="163.33333333333334" x2="670" y2="163.33333333333334" stroke="#e5e7eb"/><text x="62" y="167.33333333333334" text-anchor="end" font-size="11">6,000</text><line x1="70" y1="128.88888888888889" x2="670" y2="128.88888888888889" stroke="#e5e7eb"/><text x="62" y="132.88888888888889" text-anchor="end" font-size="11">7,000</text><line x1="70" y1="94.44444444444446" x2="670" y2="94.44444444444446" stroke="#e5e7eb"/><text x="62" y="98.44444444444446" text-anchor="end" font-size="11">8,000</text><line x1="70" y1="60.0" x2="670" y2="60.0" stroke="#e5e7eb"/><text x="62" y="64.0" text-anchor="end" font-size="11">9,000</text><text x="70.0" y="390" text-anchor="middle" font-size="12">2011</text><text x="155.71428571428572" y="390" text-anchor="middle" font-size="12">2012</text><text x="241.42857142857142" y="390" text-anchor="middle" font-size="12">2013</text><text x="327.14285714285717" y="390" text-anchor="middle" font-size="12">2014</text><text x="412.85714285714283" y="390" text-anchor="middle" font-size="12">2015</text><text x="498.57142857142856" y="390" text-anchor="middle" font-size="12">2016</text><text x="584.2857142857143" y="390" text-anchor="middle" font-size="12">2017</text><text x="670.0" y="390" text-anchor="middle" font-size="12">2018</text><polyline fill="none" stroke="#111" stroke-width="2" points="70.0,149.55555555555554 155.71428571428572,166.77777777777777 241.42857142857142,121.99999999999999 327.14285714285717,146.11111111111111 412.85714285714283,349.3333333333333 498.57142857142856,190.8888888888889 584.2857142857143,197.77777777777777 670.0,190.8888888888889"/><polyline fill="none" stroke="#111" stroke-width="2" stroke-dasharray="6 4" points="70.0,77.22222222222223 155.71428571428572,235.66666666666666 241.42857142857142,197.77777777777777 327.14285714285717,156.44444444444446 412.85714285714283,232.22222222222223 498.57142857142856,232.22222222222223 584.2857142857143,225.33333333333334 670.0,266.6666666666667"/><circle cx="70.0" cy="149.55555555555554" r="4" fill="#111"/><circle cx="70.0" cy="77.22222222222223" r="4" fill="#111"/><circle cx="155.71428571428572" cy="166.77777777777777" r="4" fill="#111"/><circle cx="155.71428571428572" cy="235.66666666666666" r="4" fill="#111"/><circle cx="241.42857142857142" cy="121.99999999999999" r="4" fill="#111"/><circle cx="241.42857142857142" cy="197.77777777777777" r="4" fill="#111"/><circle cx="327.14285714285717" cy="146.11111111111111" r="4" fill="#111"/><circle cx="327.14285714285717" cy="156.44444444444446" r="4" fill="#111"/><circle cx="412.85714285714283" cy="349.3333333333333" r="4" fill="#111"/><circle cx="412.85714285714283" cy="232.22222222222223" r="4" fill="#111"/><circle cx="498.57142857142856" cy="190.8888888888889" r="4" fill="#111"/><circle cx="498.57142857142856" cy="232.22222222222223" r="4" fill="#111"/><circle cx="584.2857142857143" cy="197.77777777777777" r="4" fill="#111"/><circle cx="584.2857142857143" cy="225.33333333333334" r="4" fill="#111"/><circle cx="670.0" cy="190.8888888888889" r="4" fill="#111"/><circle cx="670.0" cy="266.6666666666667" r="4" fill="#111"/></svg></div>assets/images/ielts_academic/writing_001_task1_chart.png" alt="Bar chart: thousands of men and women in further education in Britain, full-time and part-time, 1970/71, 1980/81 and 1990/91" class="img-fluid mb-3" style="border:1px solid #e5e7eb;border-radius:10px;width:100%;">

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
    const QUESTION = 'The graph below shows the number of shops that closed and the number of new shops that opened in one country between 2011 and 2018.\n\nSummarise the information by selecting and reporting the main features, and make comparisons where relevant.';

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
            title:     'IELTS Academic Writing Task 1 – Test 4',
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