<?php
// IELTS Speaking Practice 001
// TODO: Replace prompts with real exam content
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}

$testCode = 'IELTS_PT_S_001';

$parts = [
    1 => [
        'title'    => 'Part 1 – Introduction & Interview',
        'duration' => '4–5 min',
        'icon'     => 'bi-person-circle',
        'color'    => '#10b981',
        'intro'    => 'The examiner will ask you general questions about yourself and familiar topics such as home, family, work, studies, and interests.',
        // TODO: Replace with actual Part 1 questions
        'prompts'  => [
            'Do you work or are you a student?',
            'What do you enjoy most about your work/studies?',
            'Do you prefer spending time indoors or outdoors? Why?',
            'How do you usually spend your weekends?',
            'Do you enjoy cooking? Why or why not?',
        ],
    ],
    2 => [
        'title'    => 'Part 2 – Individual Long Turn',
        'duration' => '3–4 min',
        'icon'     => 'bi-card-text',
        'color'    => '#3b82f6',
        'intro'    => 'You will be given a topic card. You have 1 minute to prepare, then you should speak for 1–2 minutes.',
        'prompts'  => [
            // TODO: Replace with actual cue card topic
            "Describe a time when you helped someone.\n\nYou should say:\n• who you helped\n• why they needed help\n• how you helped them\n• and explain how you felt about helping them",
        ],
        'prep_time' => 60,
    ],
    3 => [
        'title'    => 'Part 3 – Discussion',
        'duration' => '4–5 min',
        'icon'     => 'bi-chat-dots',
        'color'    => '#8b5cf6',
        'intro'    => 'The examiner will ask further questions connected to the topic in Part 2. These questions are more abstract and require you to discuss broader issues.',
        // TODO: Replace with actual Part 3 discussion questions
        'prompts'  => [
            'In what situations do people in your country usually help strangers?',
            'Do you think people are less helpful today than they were in the past? Why?',
            'What role should the government play in encouraging people to help others?',
            'Is it important for children to learn to help others from a young age?',
        ],
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Speaking – Practice 1 | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .main-wrapper { padding:1.5rem; background:#f8f9fa; min-height:100vh; }
        .test-container { max-width:900px; margin:0 auto; }
        .part-card { background:white; border-radius:16px; padding:2rem; box-shadow:0 4px 18px rgba(0,0,0,0.07); margin-bottom:1.5rem; border-left:5px solid var(--c); }
        .part-header { display:flex; align-items:center; gap:1rem; margin-bottom:1rem; }
        .part-icon { width:46px; height:46px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.2rem; color:white; background:var(--c); }
        .part-title { font-size:1.1rem; font-weight:700; margin:0; }
        .part-duration { font-size:.8rem; color:#9ca3af; }
        .prompt-list { list-style:none; padding:0; margin:1rem 0 0; }
        .prompt-list li { padding:.65rem 1rem; border-radius:8px; background:#f9fafb; margin-bottom:.5rem; font-size:.95rem; border-left:3px solid var(--c); white-space:pre-line; }
        .cue-card { background:linear-gradient(135deg,#eff6ff,#dbeafe); border-radius:12px; padding:1.5rem; font-size:1rem; line-height:1.7; white-space:pre-line; border:1px solid #bfdbfe; }
        .prep-timer { font-size:2rem; font-weight:700; font-family:monospace; color:#1e40af; }
        .rec-btn { width:64px; height:64px; border-radius:50%; border:none; font-size:1.4rem; color:white; cursor:pointer; background:#ef4444; }
        .rec-btn.recording { animation:pulse 1s infinite; }
        @keyframes pulse { 0%,100%{box-shadow:0 0 0 0 rgba(239,68,68,.4)} 50%{box-shadow:0 0 0 12px rgba(239,68,68,0)} }
        .rec-time { font-size:1.4rem; font-weight:700; font-family:monospace; }
        .section-badge { background:linear-gradient(135deg,#10b981,#34d399); color:white; padding:.45rem 1.4rem; border-radius:50px; font-weight:700; font-size:.85rem; }
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
                    <li class="breadcrumb-item active">IELTS Speaking – Practice 1</li>
                </ol>
            </nav>

            <div class="d-flex align-items-center gap-3 mb-4">
                <span class="section-badge">Speaking</span>
                <span class="text-muted small">IELTS · Parts 1–3 · ~15 minutes</span>
            </div>

            <?php foreach ($parts as $pNum => $part): ?>
            <div class="part-card" style="--c:<?= $part['color'] ?>;">
                <div class="part-header">
                    <div class="part-icon"><i class="<?= $part['icon'] ?>"></i></div>
                    <div>
                        <p class="part-title"><?= htmlspecialchars($part['title']) ?></p>
                        <span class="part-duration"><i class="bi bi-clock me-1"></i><?= $part['duration'] ?></span>
                    </div>
                </div>

                <p class="text-muted small mb-2"><?= htmlspecialchars($part['intro']) ?></p>

                <?php if ($pNum === 2): ?>
                    <div class="cue-card mb-3"><?= htmlspecialchars($part['prompts'][0]) ?></div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-muted small">Preparation time:</span>
                        <span class="prep-timer" id="prepTimer-2">1:00</span>
                        <button class="btn btn-outline-primary btn-sm" onclick="startPrep()">Start Prep</button>
                    </div>
                <?php else: ?>
                    <ul class="prompt-list">
                        <?php foreach ($part['prompts'] as $i => $prompt): ?>
                        <li><?= htmlspecialchars($prompt) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <!-- Recording interface -->
                <div class="d-flex align-items-center gap-3 mt-3 pt-3 border-top">
                    <button class="rec-btn" id="recBtn-<?= $pNum ?>" onclick="toggleRecording(<?= $pNum ?>)">
                        <i class="bi bi-mic-fill" id="recIcon-<?= $pNum ?>"></i>
                    </button>
                    <div>
                        <div class="rec-time" id="recTime-<?= $pNum ?>">0:00</div>
                        <div class="text-muted small" id="recStatus-<?= $pNum ?>">Press to start recording</div>
                    </div>
                    <audio id="playback-<?= $pNum ?>" controls class="ms-auto d-none" style="height:36px;"></audio>
                </div>
            </div>
            <?php endforeach; ?>

            <div class="text-center mt-2 mb-5">
                <a href="index.php" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-check-circle me-1"></i> Finish Practice
                </a>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <script>
    const recState = {};
    let prepInterval = null;

    function startPrep() {
        let secs = 60;
        const el = document.getElementById('prepTimer-2');
        if (prepInterval) clearInterval(prepInterval);
        prepInterval = setInterval(() => {
            secs--;
            el.textContent = Math.floor(secs/60) + ':' + String(secs%60).padStart(2,'0');
            if (secs <= 0) {
                clearInterval(prepInterval);
                Swal.fire({ title: 'Preparation time up!', text: 'Start speaking now.', icon: 'info', timer: 2000, showConfirmButton: false });
            }
        }, 1000);
    }

    async function toggleRecording(pNum) {
        const btn    = document.getElementById('recBtn-' + pNum);
        const icon   = document.getElementById('recIcon-' + pNum);
        const timeEl = document.getElementById('recTime-' + pNum);
        const status = document.getElementById('recStatus-' + pNum);

        if (!recState[pNum] || !recState[pNum].active) {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                const recorder = new MediaRecorder(stream);
                const chunks = [];
                recorder.ondataavailable = e => chunks.push(e.data);
                recorder.onstop = () => {
                    const blob = new Blob(chunks, { type: 'audio/webm' });
                    const url  = URL.createObjectURL(blob);
                    const playback = document.getElementById('playback-' + pNum);
                    playback.src = url;
                    playback.classList.remove('d-none');
                    stream.getTracks().forEach(t => t.stop());
                };
                recorder.start();

                let elapsed = 0;
                const timer = setInterval(() => {
                    elapsed++;
                    timeEl.textContent = Math.floor(elapsed/60) + ':' + String(elapsed%60).padStart(2,'0');
                }, 1000);

                recState[pNum] = { active: true, recorder, timer };
                btn.classList.add('recording');
                icon.className = 'bi bi-stop-fill';
                status.textContent = 'Recording…';
            } catch (e) {
                Swal.fire({ title: 'Microphone access denied', text: 'Please allow microphone access to record.', icon: 'error' });
            }
        } else {
            recState[pNum].recorder.stop();
            clearInterval(recState[pNum].timer);
            recState[pNum].active = false;
            btn.classList.remove('recording');
            icon.className = 'bi bi-mic-fill';
            status.textContent = 'Recording saved — press play to review';
        }
    }
    </script>
</body>
</html>
