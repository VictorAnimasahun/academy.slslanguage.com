<?php
// IELTS Writing Task 1 – Practice 001
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

$testCode   = 'IELTS_PT_W1_001';
$timeLimit  = 20 * 60;  // 20 minutes in seconds
$wordTarget = 150;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Writing Task 1 – Practice 1 | EduHub</title>
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
                    <li class="breadcrumb-item"><a href="../resources_home.php">Resources</a></li>
                    <li class="breadcrumb-item"><a href="index.php">Practice Tests</a></li>
                    <li class="breadcrumb-item active">IELTS Writing Task 1 – Practice 1</li>
                </ol>
            </nav>

            <div class="row g-4">
                <!-- LEFT: Prompt + chart -->
                <div class="col-lg-5">
                    <div class="panel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="section-badge">Writing Task 1</span>
                            <small class="text-muted">IELTS General Training</small>
                        </div>

                        <div class="prompt-box">
                            <!-- ══ TODO: Replace with actual Task 1 prompt ══ -->
                            <p class="mb-2"><strong>You recently stayed at a hotel and had a problem with your room.</strong></p>
                            <p class="mb-0">Write a letter to the hotel manager. In your letter:</p>
                            <ul class="mt-2 mb-0">
                                <li>describe the problem you had</li>
                                <li>explain how it affected your stay</li>
                                <li>say what action you would like the hotel to take</li>
                            </ul>
                        </div>

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
                            placeholder="Dear Sir / Madam,&#10;&#10;I am writing to..."></textarea>

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
    const QUESTION = "You recently stayed at a hotel and had a problem with your room.\n\nWrite a letter to the hotel manager. In your letter:\n- describe the problem you had\n- explain how it affected your stay\n- say what action you would like the hotel to take\n\nWrite at least 150 words.";

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
            title:     'IELTS Writing Task 1 – Practice 1',
            testType:  'IELTS General Training',
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