<?php
// IELTS Reading (General Training) Practice 001
// TODO: Replace passages and questions with real content
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}

$testCode  = 'IELTS_PT_R_GT_001';
$timeLimit = 60 * 60;

// ══ CORRECT ANSWERS ══════════════════════════════════════
// TODO: Fill in after replacing question content
$answers = [];
for ($i = 1; $i <= 40; $i++) $answers[$i] = 'TODO';

// ══ SECTION / PASSAGE DATA ════════════════════════════════
$sections = [
    1 => [
        'title'     => 'Section 1 – Everyday Texts',
        'q_range'   => [1, 14],
        // TODO: Replace passage with real text
        'passage'   => "TODO: Replace with actual reading passage for Section 1.\n\nThis section typically contains two short texts on everyday topics such as advertisements, notices, timetables, or social letters.\n\nAdd the full passage text here.",
        'questions' => [
            // TODO: Replace with actual questions matching the passage
            ['type' => 'tfng', 'q' => 1,  'text' => 'TODO: Statement 1'],
            ['type' => 'tfng', 'q' => 2,  'text' => 'TODO: Statement 2'],
            ['type' => 'tfng', 'q' => 3,  'text' => 'TODO: Statement 3'],
            ['type' => 'tfng', 'q' => 4,  'text' => 'TODO: Statement 4'],
            ['type' => 'tfng', 'q' => 5,  'text' => 'TODO: Statement 5'],
            ['type' => 'tfng', 'q' => 6,  'text' => 'TODO: Statement 6'],
            ['type' => 'tfng', 'q' => 7,  'text' => 'TODO: Statement 7'],
            ['type' => 'text', 'q' => 8,  'text' => 'TODO: Short answer question 8'],
            ['type' => 'text', 'q' => 9,  'text' => 'TODO: Short answer question 9'],
            ['type' => 'text', 'q' => 10, 'text' => 'TODO: Short answer question 10'],
            ['type' => 'text', 'q' => 11, 'text' => 'TODO: Short answer question 11'],
            ['type' => 'text', 'q' => 12, 'text' => 'TODO: Short answer question 12'],
            ['type' => 'text', 'q' => 13, 'text' => 'TODO: Short answer question 13'],
            ['type' => 'text', 'q' => 14, 'text' => 'TODO: Short answer question 14'],
        ],
    ],
    2 => [
        'title'     => 'Section 2 – Work-related Texts',
        'q_range'   => [15, 27],
        'passage'   => "TODO: Replace with actual reading passage for Section 2.\n\nThis section typically contains two texts related to work, such as job descriptions, employment contracts, workplace notices, or training materials.\n\nAdd the full passage text here.",
        'questions' => array_map(fn($q) => ['type' => 'text', 'q' => $q, 'text' => "TODO: Question $q"], range(15, 27)),
    ],
    3 => [
        'title'     => 'Section 3 – General Interest Text',
        'q_range'   => [28, 40],
        'passage'   => "TODO: Replace with actual reading passage for Section 3.\n\nThis section contains one longer, more complex text on a topic of general interest.\n\nAdd the full passage text here.",
        'questions' => array_map(fn($q) => ['type' => 'text', 'q' => $q, 'text' => "TODO: Question $q"], range(28, 40)),
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Reading (GT) – Practice 1 | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .main-wrapper { padding:1.5rem; background:#f8f9fa; min-height:100vh; }
        .test-container { max-width:1300px; margin:0 auto; }
        .section-badge { background:linear-gradient(135deg,#10b981,#34d399); color:white; padding:.45rem 1.4rem; border-radius:50px; font-weight:700; font-size:.85rem; }
        .timer-display { font-size:2rem; font-weight:700; font-family:monospace; color:#1e40af; }
        .timer-display.warning { color:#ef4444; }
        .passage-panel { background:white; border-radius:14px; padding:1.75rem; box-shadow:0 3px 14px rgba(0,0,0,0.07); height:calc(100vh - 200px); overflow-y:auto; position:sticky; top:80px; line-height:1.8; font-size:.95rem; }
        .questions-panel { background:white; border-radius:14px; padding:1.75rem; box-shadow:0 3px 14px rgba(0,0,0,0.07); }
        .sec-tab { cursor:pointer; padding:.5rem 1.1rem; border:none; background:transparent; font-weight:600; color:#6b7280; border-bottom:3px solid transparent; font-size:.9rem; }
        .sec-tab.active { color:#10b981; border-bottom-color:#10b981; }
        .sec-panel { display:none; }
        .sec-panel.active { display:block; }
        .q-item { padding:.75rem 0; border-bottom:1px solid #f3f4f6; }
        .q-num { font-weight:700; color:#10b981; margin-right:.5rem; }
        .q-input { border:1.5px solid #d1d5db; border-radius:6px; padding:.3rem .65rem; font-size:.88rem; margin-top:.4rem; width:100%; max-width:260px; }
        .q-input:focus { border-color:#10b981; outline:none; }
        .tfng-options { display:flex; gap:.5rem; margin-top:.4rem; flex-wrap:wrap; }
        .tfng-btn { padding:.28rem .85rem; border:1.5px solid #d1d5db; border-radius:6px; background:white; font-size:.82rem; font-weight:600; cursor:pointer; color:#6b7280; }
        .tfng-btn.selected { background:#10b981; border-color:#10b981; color:white; }
    </style>
</head>
<body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <main class="main-wrapper">
        <div class="test-container">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../resources_home.php">Resources</a></li>
                    <li class="breadcrumb-item"><a href="index.php">Practice Tests</a></li>
                    <li class="breadcrumb-item active">IELTS Reading (GT) – Practice 1</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="section-badge">Reading · General Training</span>
                <div class="timer-display" id="timerEl">60:00</div>
            </div>

            <!-- Section tabs -->
            <div class="d-flex border-bottom mb-4">
                <?php foreach ($sections as $sNum => $sec): ?>
                <button class="sec-tab <?= $sNum === 1 ? 'active' : '' ?>"
                        onclick="switchSection(<?= $sNum ?>)" id="stab-<?= $sNum ?>">
                    Section <?= $sNum ?>
                    <span class="text-muted" style="font-size:.72rem;"> Q<?= $sec['q_range'][0] ?>–<?= $sec['q_range'][1] ?></span>
                </button>
                <?php endforeach; ?>
            </div>

            <?php foreach ($sections as $sNum => $sec): ?>
            <div class="sec-panel <?= $sNum === 1 ? 'active' : '' ?>" id="spanel-<?= $sNum ?>">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="passage-panel">
                            <h5 class="mb-3"><?= htmlspecialchars($sec['title']) ?></h5>
                            <div style="white-space:pre-line;"><?= htmlspecialchars($sec['passage']) ?></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="questions-panel">
                            <h6 class="mb-3 text-muted">Questions <?= $sec['q_range'][0] ?>–<?= $sec['q_range'][1] ?></h6>
                            <?php foreach ($sec['questions'] as $qdata): ?>
                            <div class="q-item">
                                <div><span class="q-num"><?= $qdata['q'] ?>.</span><?= htmlspecialchars($qdata['text']) ?></div>
                                <?php if ($qdata['type'] === 'tfng'): ?>
                                <div class="tfng-options">
                                    <?php foreach (['TRUE', 'FALSE', 'NOT GIVEN'] as $opt): ?>
                                    <button class="tfng-btn" onclick="setAnswer(<?= $qdata['q'] ?>, '<?= $opt ?>', this)"><?= $opt ?></button>
                                    <?php endforeach; ?>
                                </div>
                                <?php else: ?>
                                <input type="text" class="q-input" placeholder="Answer…"
                                    oninput="setAnswer(<?= $qdata['q'] ?>, this.value)">
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>

                            <div class="d-flex justify-content-end mt-4">
                                <?php if ($sNum < 3): ?>
                                <button class="btn btn-outline-success" onclick="switchSection(<?= $sNum + 1 ?>)">
                                    Section <?= $sNum + 1 ?> <i class="bi bi-arrow-right ms-1"></i>
                                </button>
                                <?php else: ?>
                                <button class="btn btn-success px-4" onclick="submitTest()">
                                    Submit <i class="bi bi-send ms-1"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <script>
    const CORRECT = <?= json_encode($answers) ?>;
    let answers = {}, timeLeft = <?= $timeLimit ?>;

    const timerEl = document.getElementById('timerEl');
    function fmtTime(s) { return String(Math.floor(s/60)).padStart(2,'0') + ':' + String(s%60).padStart(2,'0'); }

    const interval = setInterval(() => {
        timeLeft--;
        timerEl.textContent = fmtTime(timeLeft);
        if (timeLeft <= 600) timerEl.classList.add('warning');
        if (timeLeft <= 0) {
            clearInterval(interval);
            Swal.fire({ title:"Time's up!", text:'Submitting now.', icon:'warning', timer:2000, timerProgressBar:true, showConfirmButton:false })
                .then(() => doSubmit());
        }
    }, 1000);

    function setAnswer(q, val, btn) {
        answers[q] = val;
        if (btn) {
            btn.closest('.tfng-options').querySelectorAll('.tfng-btn').forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
        }
    }

    function switchSection(n) {
        document.querySelectorAll('.sec-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.sec-tab').forEach(t => t.classList.remove('active'));
        document.getElementById('spanel-' + n).classList.add('active');
        document.getElementById('stab-' + n).classList.add('active');
    }

    function submitTest() {
        Swal.fire({
            title: 'Submit Reading?',
            text: 'Answer all 40 questions before submitting.',
            icon: 'question', showCancelButton: true,
            confirmButtonText: 'Submit', cancelButtonText: 'Review',
            confirmButtonColor: '#10b981',
        }).then(r => { if (r.isConfirmed) doSubmit(); });
    }

    function doSubmit() {
        clearInterval(interval);
        let correct = 0, total = Object.keys(CORRECT).length;
        Object.keys(CORRECT).forEach(q => {
            const got = (answers[q] || '').trim().toLowerCase();
            if (got === CORRECT[q].toLowerCase()) correct++;
        });
        Swal.fire({ title:'Results', html:`Score: <strong>${correct}/${total}</strong>`, icon:'success', confirmButtonColor:'#10b981' });
    }
    </script>
</body>
</html>
