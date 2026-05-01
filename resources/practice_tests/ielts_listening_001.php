<?php
// IELTS Listening Practice 001
// TODO: Replace $parts data with actual recording + question content
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}

$testCode  = 'IELTS_PT_L_001';
$timeLimit = 30 * 60; // 30 minutes

// ══════════════════════════════════════════════════════
// LISTENING DATA – replace with real audio + questions
// ══════════════════════════════════════════════════════
$parts = [
    1 => [
        'title'       => 'Part 1',
        'description' => 'A conversation between two people in an everyday social context.',
        'audio_url'   => '/assets/audio/' . $testCode . '/part1.mp3',
        'q_range'     => [1, 10],
        'type'        => 'form_fill',
        'form_title'  => 'TODO: Form title',
        'rows'        => [
            ['label' => 'TODO Question 1', 'q' => 1],
            ['label' => 'TODO Question 2', 'q' => 2],
            ['label' => 'TODO Question 3', 'q' => 3],
            ['label' => 'TODO Question 4', 'q' => 4],
            ['label' => 'TODO Question 5', 'q' => 5],
            ['label' => 'TODO Question 6', 'q' => 6],
            ['label' => 'TODO Question 7', 'q' => 7],
            ['label' => 'TODO Question 8', 'q' => 8],
            ['label' => 'TODO Question 9', 'q' => 9],
            ['label' => 'TODO Question 10', 'q' => 10],
        ],
    ],
    2 => [
        'title'       => 'Part 2',
        'description' => 'A monologue in an everyday social context.',
        'audio_url'   => '/assets/audio/' . $testCode . '/part2.mp3',
        'q_range'     => [11, 20],
        'type'        => 'multiple_choice',
        'questions'   => [
            ['q' => 11, 'text' => 'TODO Question 11', 'options' => ['A' => 'Option A', 'B' => 'Option B', 'C' => 'Option C']],
            ['q' => 12, 'text' => 'TODO Question 12', 'options' => ['A' => 'Option A', 'B' => 'Option B', 'C' => 'Option C']],
            ['q' => 13, 'text' => 'TODO Question 13', 'options' => ['A' => 'Option A', 'B' => 'Option B', 'C' => 'Option C']],
            ['q' => 14, 'text' => 'TODO Question 14', 'options' => ['A' => 'Option A', 'B' => 'Option B', 'C' => 'Option C']],
            ['q' => 15, 'text' => 'TODO Question 15', 'options' => ['A' => 'Option A', 'B' => 'Option B', 'C' => 'Option C']],
            ['q' => 16, 'text' => 'TODO Question 16', 'options' => ['A' => 'Option A', 'B' => 'Option B', 'C' => 'Option C']],
            ['q' => 17, 'text' => 'TODO Question 17', 'options' => ['A' => 'Option A', 'B' => 'Option B', 'C' => 'Option C']],
            ['q' => 18, 'text' => 'TODO Question 18', 'options' => ['A' => 'Option A', 'B' => 'Option B', 'C' => 'Option C']],
            ['q' => 19, 'text' => 'TODO Question 19', 'options' => ['A' => 'Option A', 'B' => 'Option B', 'C' => 'Option C']],
            ['q' => 20, 'text' => 'TODO Question 20', 'options' => ['A' => 'Option A', 'B' => 'Option B', 'C' => 'Option C']],
        ],
    ],
    3 => [
        'title'       => 'Part 3',
        'description' => 'A conversation between up to four people in an educational or training context.',
        'audio_url'   => '/assets/audio/' . $testCode . '/part3.mp3',
        'q_range'     => [21, 30],
        'type'        => 'form_fill',
        'form_title'  => 'TODO: Notes title',
        'rows'        => array_map(fn($q) => ['label' => "TODO Question $q", 'q' => $q], range(21, 30)),
    ],
    4 => [
        'title'       => 'Part 4',
        'description' => 'A monologue on an academic subject.',
        'audio_url'   => '/assets/audio/' . $testCode . '/part4.mp3',
        'q_range'     => [31, 40],
        'type'        => 'form_fill',
        'form_title'  => 'TODO: Lecture notes title',
        'rows'        => array_map(fn($q) => ['label' => "TODO Question $q", 'q' => $q], range(31, 40)),
    ],
];

// ══════════════════════════════════════════════════════
// CORRECT ANSWERS – fill in after adding content
// ══════════════════════════════════════════════════════
$answers = [];
for ($i = 1; $i <= 40; $i++) $answers[$i] = 'TODO';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Listening – Practice 1 | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .main-wrapper { padding:1.5rem; background:#f8f9fa; min-height:100vh; }
        .test-container { max-width:1100px; margin:0 auto; }
        .panel { background:white; border-radius:16px; padding:2rem; box-shadow:0 4px 20px rgba(0,0,0,0.07); }
        .section-badge { background:linear-gradient(135deg,#10b981,#34d399); color:white; padding:.45rem 1.4rem; border-radius:50px; font-weight:700; font-size:.85rem; }
        .timer-display { font-size:2rem; font-weight:700; font-family:monospace; color:#1e40af; }
        .timer-display.warning { color:#ef4444; }
        .part-tab { cursor:pointer; padding:.5rem 1.1rem; border:none; background:transparent; font-weight:600; color:#6b7280; border-bottom:3px solid transparent; }
        .part-tab.active { color:#10b981; border-bottom-color:#10b981; }
        .part-panel { display:none; }
        .part-panel.active { display:block; }
        .q-row { display:flex; gap:.75rem; align-items:center; padding:.6rem 0; border-bottom:1px solid #f3f4f6; }
        .q-num { font-weight:700; color:#10b981; min-width:28px; }
        .q-label { flex:1; font-size:.9rem; color:#374151; }
        .q-input { border:1.5px solid #d1d5db; border-radius:6px; padding:.35rem .75rem; font-size:.9rem; min-width:160px; }
        .q-input:focus { border-color:#10b981; outline:none; }
        .mc-option { display:flex; align-items:center; gap:.5rem; padding:.4rem .75rem; border-radius:6px; cursor:pointer; }
        .mc-option:hover { background:#f0fdf4; }
        .mc-option input { accent-color:#10b981; }
        .audio-bar { background:#f3f4f6; border-radius:12px; padding:1rem 1.5rem; display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem; }
        .play-btn { width:42px; height:42px; border-radius:50%; background:#10b981; border:none; color:white; font-size:1.1rem; display:flex; align-items:center; justify-content:center; cursor:pointer; flex-shrink:0; }
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
                    <li class="breadcrumb-item active">IELTS Listening – Practice 1</li>
                </ol>
            </nav>

            <div class="panel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <span class="section-badge">Listening</span>
                        <span class="text-muted small">IELTS · 40 Questions · 30 Minutes</span>
                    </div>
                    <div class="timer-display" id="timerEl">30:00</div>
                </div>

                <!-- Part tabs -->
                <div class="d-flex border-bottom mb-4" id="partTabs">
                    <?php foreach ($parts as $pNum => $p): ?>
                    <button class="part-tab <?= $pNum === 1 ? 'active' : '' ?>"
                            onclick="switchPart(<?= $pNum ?>)" id="ptab-<?= $pNum ?>">
                        <?= $p['title'] ?>
                        <span class="text-muted" style="font-size:.72rem;">Q<?= $p['q_range'][0] ?>–<?= $p['q_range'][1] ?></span>
                    </button>
                    <?php endforeach; ?>
                </div>

                <?php foreach ($parts as $pNum => $p): ?>
                <div class="part-panel <?= $pNum === 1 ? 'active' : '' ?>" id="ppanel-<?= $pNum ?>">
                    <p class="text-muted small mb-3"><i class="bi bi-info-circle me-1"></i><?= htmlspecialchars($p['description']) ?></p>

                    <!-- Audio player -->
                    <div class="audio-bar">
                        <button class="play-btn" onclick="toggleAudio(<?= $pNum ?>)" id="playBtn-<?= $pNum ?>">
                            <i class="bi bi-play-fill" id="playIcon-<?= $pNum ?>"></i>
                        </button>
                        <audio id="audio-<?= $pNum ?>" src="<?= htmlspecialchars($p['audio_url']) ?>"></audio>
                        <span class="text-muted small"><?= htmlspecialchars($p['title']) ?> Recording</span>
                        <span class="ms-auto text-muted small" id="audioTime-<?= $pNum ?>">0:00</span>
                    </div>

                    <?php if ($p['type'] === 'form_fill'): ?>
                        <p class="fw-600 mb-3"><?= htmlspecialchars($p['form_title']) ?></p>
                        <?php foreach ($p['rows'] as $row): ?>
                        <div class="q-row">
                            <span class="q-num"><?= $row['q'] ?>.</span>
                            <span class="q-label"><?= htmlspecialchars($row['label']) ?></span>
                            <input type="text" class="q-input" id="q<?= $row['q'] ?>" placeholder="Answer...">
                        </div>
                        <?php endforeach; ?>

                    <?php elseif ($p['type'] === 'multiple_choice'): ?>
                        <?php foreach ($p['questions'] as $mc): ?>
                        <div class="mb-3">
                            <p class="mb-2"><span class="q-num"><?= $mc['q'] ?>.</span> <?= htmlspecialchars($mc['text']) ?></p>
                            <?php foreach ($mc['options'] as $letter => $opt): ?>
                            <label class="mc-option">
                                <input type="radio" name="q<?= $mc['q'] ?>" value="<?= $letter ?>"
                                    onchange="setAnswer(<?= $mc['q'] ?>, '<?= $letter ?>')">
                                <strong><?= $letter ?>.</strong> <?= htmlspecialchars($opt) ?>
                            </label>
                            <?php endforeach; ?>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <div class="d-flex justify-content-end mt-4">
                        <?php if ($pNum < 4): ?>
                        <button class="btn btn-outline-success" onclick="switchPart(<?= $pNum + 1 ?>)">
                            <?= $parts[$pNum + 1]['title'] ?> <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                        <?php else: ?>
                        <button class="btn btn-success px-4" onclick="submitTest()">
                            Submit <i class="bi bi-send ms-1"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
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
        if (timeLeft <= 300) timerEl.classList.add('warning');
        if (timeLeft <= 0) {
            clearInterval(interval);
            Swal.fire({ title:"Time's up!", text:'Submitting now.', icon:'warning', timer:2000, timerProgressBar:true, showConfirmButton:false })
                .then(() => doSubmit());
        }
    }, 1000);

    // Collect text input answers
    document.querySelectorAll('.q-input').forEach(el => {
        el.addEventListener('input', () => {
            const q = parseInt(el.id.replace('q',''));
            answers[q] = el.value.trim();
        });
    });

    function setAnswer(q, val) { answers[q] = val; }

    function switchPart(n) {
        document.querySelectorAll('.part-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.part-tab').forEach(t => t.classList.remove('active'));
        document.getElementById('ppanel-' + n).classList.add('active');
        document.getElementById('ptab-' + n).classList.add('active');
    }

    function toggleAudio(pNum) {
        const audio = document.getElementById('audio-' + pNum);
        const icon  = document.getElementById('playIcon-' + pNum);
        if (audio.paused) { audio.play(); icon.className = 'bi bi-pause-fill'; }
        else              { audio.pause(); icon.className = 'bi bi-play-fill'; }
        audio.addEventListener('timeupdate', () => {
            document.getElementById('audioTime-' + pNum).textContent = fmtTime(Math.floor(audio.currentTime));
        });
    }

    function submitTest() {
        Swal.fire({
            title: 'Submit test?',
            text: 'Make sure you have answered all 40 questions.',
            icon: 'question', showCancelButton: true,
            confirmButtonText: 'Submit', cancelButtonText: 'Review',
            confirmButtonColor: '#10b981',
        }).then(r => { if (r.isConfirmed) doSubmit(); });
    }

    function doSubmit() {
        clearInterval(interval);
        let correct = 0, total = Object.keys(CORRECT).length;
        Object.keys(CORRECT).forEach(q => {
            if ((answers[q] || '').trim().toLowerCase() === CORRECT[q].toLowerCase()) correct++;
        });
        Swal.fire({
            title: 'Results',
            html: `Score: <strong>${correct}/${total}</strong>`,
            icon: 'success', confirmButtonColor: '#10b981',
        });
    }
    </script>
</body>
</html>
