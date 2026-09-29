<?php
// IELTS Academic Writing Task 1 (Cambridge IELTS 17 Academic, Test 1) – Practice Test 5. Maps task, 20 minutes, AI-marked via essay_analyzer.php.
// General resources, free-range (see ielts_writing_academic_005.php for why).
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

$testCode   = 'IELTS_PT_W1_ACA_005';
$timeLimit  = 20 * 60;  // 20 minutes in seconds
$wordTarget = 150;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Academic Writing Task 1 – Practice Test 5 | EduHub</title>
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
        .map-legend { display:flex; flex-wrap:wrap; gap:.6rem 1.1rem; font-size:.78rem; margin-top:.5rem; }
        .map-legend span { display:inline-flex; align-items:center; gap:.35rem; }
        .map-legend i { width:12px; height:12px; border-radius:3px; display:inline-block; border:1px solid #111; }
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
                    <li class="breadcrumb-item"><a href="ielts_writing_academic_005.php">Academic Writing – Practice Test 5</a></li>
                    <li class="breadcrumb-item active">Task 1</li>
                </ol>
            </nav>

            <div class="row g-4">
                <!-- LEFT: Prompt + maps -->
                <div class="col-lg-5">
                    <div class="panel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="section-badge">Writing Task 1</span>
                            <small class="text-muted">IELTS Academic</small>
                        </div>

                        <div class="prompt-box">
                            <p class="mb-2"><strong>The maps below show an industrial area in the town of Norbiton, and planned future development of the site.</strong></p>
                            <p class="mb-0">Summarise the information by selecting and reporting the main features, and make comparisons where relevant.</p>
                        </div>

                        <div class="mb-2" style="border:1px solid #e5e7eb;border-radius:10px;overflow:hidden">
                            <svg viewBox="0 0 700 380" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Two maps of Norbiton industrial area: now, and after planned development" style="width:100%;height:auto;background:#fff;font-family:system-ui,sans-serif">
                                <text x="175" y="22" text-anchor="middle" font-size="14" font-weight="700">Norbiton industrial area now</text>
                                <text x="525" y="22" text-anchor="middle" font-size="14" font-weight="700">Planned future development</text>

                                <!-- ===== MAP 1: now ===== -->
                                <g>
                                    <rect x="15" y="32" width="320" height="260" fill="#fafafa" stroke="#9ca3af" stroke-width="1.5"/>
                                    <!-- river -->
                                    <path d="M15,55 Q60,45 105,55 T195,55 T285,55 T335,52" fill="none" stroke="#60a5fa" stroke-width="7"/>
                                    <text x="175" y="46" text-anchor="middle" font-size="10" fill="#1e3a8a">River</text>
                                    <!-- farmland north of the river -->
                                    <rect x="15" y="60" width="320" height="34" fill="#dcfce7" stroke="#86efac"/>
                                    <text x="175" y="82" text-anchor="middle" font-size="10" fill="#166534">Farmland</text>
                                    <!-- town, west -->
                                    <rect x="15" y="94" width="70" height="198" fill="#e5e7eb" stroke="#9ca3af"/>
                                    <text x="50" y="200" text-anchor="middle" font-size="11" font-weight="700" fill="#374151" transform="rotate(-90 50 200)">Town</text>
                                    <!-- main road, south -->
                                    <rect x="15" y="270" width="320" height="22" fill="#4b5563"/>
                                    <text x="175" y="285" text-anchor="middle" font-size="10" fill="#fff">Main road</text>
                                    <!-- road east from roundabout -->
                                    <rect x="205" y="185" width="130" height="10" fill="#9ca3af"/>
                                    <!-- roundabout -->
                                    <circle cx="205" cy="190" r="20" fill="#d1d5db" stroke="#374151" stroke-width="1.5"/>
                                    <text x="205" y="219" text-anchor="middle" font-size="9" fill="#374151">Roundabout</text>
                                    <!-- factories around the roundabout -->
                                    <rect x="150" y="150" width="26" height="22" fill="#f59e0b" stroke="#111"/>
                                    <rect x="150" y="205" width="26" height="22" fill="#f59e0b" stroke="#111"/>
                                    <rect x="230" y="150" width="26" height="22" fill="#f59e0b" stroke="#111"/>
                                    <!-- factories along the eastbound road -->
                                    <rect x="255" y="160" width="26" height="20" fill="#f59e0b" stroke="#111"/>
                                    <rect x="295" y="160" width="26" height="20" fill="#f59e0b" stroke="#111"/>
                                </g>

                                <!-- ===== MAP 2: planned ===== -->
                                <g transform="translate(350,0)">
                                    <rect x="15" y="32" width="320" height="260" fill="#fafafa" stroke="#9ca3af" stroke-width="1.5"/>
                                    <path d="M15,55 Q60,45 105,55 T195,55 T285,55 T335,52" fill="none" stroke="#60a5fa" stroke-width="7"/>
                                    <text x="175" y="46" text-anchor="middle" font-size="10" fill="#1e3a8a">River</text>
                                    <rect x="15" y="60" width="320" height="34" fill="#dcfce7" stroke="#86efac"/>
                                    <!-- new bridge + road north into the farmland, to new housing -->
                                    <rect x="198" y="40" width="14" height="20" fill="#78350f"/>
                                    <text x="205" y="38" text-anchor="middle" font-size="8" fill="#78350f">Bridge</text>
                                    <rect x="190" y="60" width="30" height="34" fill="#fbcfe8" stroke="#db2777"/>
                                    <rect x="15" y="94" width="70" height="198" fill="#e5e7eb" stroke="#9ca3af"/>
                                    <text x="50" y="200" text-anchor="middle" font-size="11" font-weight="700" fill="#374151" transform="rotate(-90 50 200)">Town</text>
                                    <rect x="15" y="270" width="320" height="22" fill="#4b5563"/>
                                    <text x="175" y="285" text-anchor="middle" font-size="10" fill="#fff">Main road</text>
                                    <rect x="205" y="185" width="130" height="10" fill="#9ca3af"/>
                                    <circle cx="205" cy="190" r="20" fill="#d1d5db" stroke="#374151" stroke-width="1.5"/>
                                    <text x="205" y="219" text-anchor="middle" font-size="9" fill="#374151">Roundabout</text>
                                    <!-- factories replaced by housing -->
                                    <rect x="150" y="150" width="26" height="22" fill="#fbcfe8" stroke="#db2777"/>
                                    <rect x="150" y="205" width="26" height="22" fill="#fbcfe8" stroke="#db2777"/>
                                    <rect x="230" y="150" width="26" height="22" fill="#fbcfe8" stroke="#db2777"/>
                                    <!-- shops and medical centre around the roundabout -->
                                    <rect x="178" y="165" width="16" height="14" fill="#5eead4" stroke="#111"/>
                                    <text x="186" y="176" text-anchor="middle" font-size="7">Shop</text>
                                    <rect x="178" y="202" width="16" height="14" fill="#fca5a5" stroke="#111"/>
                                    <text x="186" y="213" text-anchor="middle" font-size="6.5">Medical</text>
                                    <!-- school + playground to the east -->
                                    <rect x="255" y="150" width="26" height="20" fill="#93c5fd" stroke="#111"/>
                                    <text x="268" y="165" text-anchor="middle" font-size="8" fill="#111">School</text>
                                    <circle cx="305" cy="165" r="13" fill="#bbf7d0" stroke="#111"/>
                                    <text x="305" y="169" text-anchor="middle" font-size="6.5">Play</text>
                                </g>
                            </svg>
                            <div class="px-2 pb-2">
                                <div class="map-legend text-muted">
                                    <span><i style="background:#f59e0b"></i>factory</span>
                                    <span><i style="background:#fbcfe8"></i>housing</span>
                                    <span><i style="background:#93c5fd"></i>school</span>
                                    <span><i style="background:#bbf7d0"></i>playground</span>
                                    <span><i style="background:#5eead4"></i>shops</span>
                                    <span><i style="background:#fca5a5"></i>medical centre</span>
                                </div>
                                <p class="text-muted small mt-1 mb-0">Redrawn schematic of the book's figure (page 28) — approximate layout, not to scale.</p>
                            </div>
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
                            placeholder="The maps show..."></textarea>

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

    const QUESTION = 'The maps below show an industrial area in the town of Norbiton, and planned future development of the site.\n\nSummarise the information by selecting and reporting the main features, and make comparisons where relevant.';

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
            title:     'IELTS Academic Writing Task 1 – Practice Test 5',
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
