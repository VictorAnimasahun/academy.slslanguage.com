<?php
// IELTS Speaking Practice 004 — Jewellery / Science TV Programme / Scientific Discoveries
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}

$testCode = 'IELTS_PT_S_004';

$parts = [
    1 => [
        'title'    => 'Part 1 – Introduction & Interview',
        'duration' => '4–5 min',
        'icon'     => 'bi-person-circle',
        'color'    => '#10b981',
        'intro'    => 'The examiner will ask you general questions about yourself and familiar topics such as home, family, work, studies, and interests.',
        'topic'    => 'Jewellery',
        'prompts'  => [
            'How often do you wear jewellery? [Why/Why not?]',
            'What type of jewellery do you like best? [Why/Why not?]',
            'When do people like to give jewellery in your country? [Why?]',
            'Have you ever given jewellery to someone as a gift? [Why/Why not?]',
        ],
    ],
    2 => [
        'title'     => 'Part 2 – Individual Long Turn',
        'duration'  => '3–4 min',
        'icon'      => 'bi-card-text',
        'color'     => '#3b82f6',
        'intro'     => 'You will be given a topic card. You have 1 minute to prepare, then you should speak for 1–2 minutes.',
        'prompts'   => [
            "Describe an interesting TV programme you watched about a science topic.\n\nYou should say:\n• what science topic this TV programme was about\n• when you saw this TV programme\n• what you learnt from this TV programme about a science topic\n\nand explain why you found this TV programme interesting.",
        ],
        'prep_time' => 60,
    ],
    3 => [
        'title'    => 'Part 3 – Discussion',
        'duration' => '4–5 min',
        'icon'     => 'bi-chat-dots',
        'color'    => '#8b5cf6',
        'intro'    => 'The examiner will ask further questions connected to the topic in Part 2.',
        'prompts'  => [
            'How interested are most people in your country in science?',
            'Why do you think children today might be better at science than their parents?',
            'How do you suggest the public can learn more about scientific developments?',
            'What do you think are the most important scientific discoveries in the last 100 years?',
            'Do you agree or disagree that there are no more major scientific discoveries left to make?',
            'Who should pay for scientific research – governments or private companies?',
        ],
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Speaking – Practice 4: Science | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .main-wrapper { padding:1.5rem; min-height:100vh; }
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
<body class="light">
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <div class="main-wrapper flex-grow-1" style="flex:1;">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>

    <main class="content p-4">
        <div class="test-container">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../resources_home.php">Resources</a></li>
                    <li class="breadcrumb-item"><a href="index.php">Practice Tests</a></li>
                    <li class="breadcrumb-item active">IELTS Speaking – Practice 4</li>
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
                        <?php foreach ($part['prompts'] as $prompt): ?>
                        <li><?= htmlspecialchars($prompt) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <button class="rec-btn" id="recBtn-<?= $pNum ?>" onclick="toggleRecording(<?= $pNum ?>)">
                            <i class="bi bi-mic-fill" id="recIcon-<?= $pNum ?>"></i>
                        </button>
                        <div>
                            <div class="rec-time" id="recTime-<?= $pNum ?>">0:00</div>
                            <div class="text-muted small" id="recStatus-<?= $pNum ?>">Press mic to start — your speech will be transcribed automatically</div>
                        </div>
                    </div>
                    <textarea id="transcript-<?= $pNum ?>" class="form-control" rows="4"
                        placeholder="Your transcription appears here as you speak. You can also type or edit it directly."></textarea>
                </div>
            </div>
            <?php endforeach; ?>

            <div class="text-center mt-2 mb-4">
                <button class="btn btn-success btn-lg px-5" onclick="submitAllParts()">
                    <i class="bi bi-stars me-2"></i>Submit All Parts for AI Feedback
                </button>
            </div>

            <div id="loadingSection" class="text-center py-5 d-none">
                <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;"></div>
                <p class="fw-bold">Analysing your speaking with AI…</p>
                <p class="text-muted small">This may take 15–30 seconds</p>
            </div>

            <div id="resultsSection" class="d-none mb-5">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-success text-white rounded-top-4 py-3">
                        <h5 class="mb-0"><i class="bi bi-trophy-fill me-2"></i>AI Examiner Feedback</h5>
                    </div>
                    <div class="card-body p-4" id="feedbackContent"></div>
                </div>
            </div>
        </div>
    </main>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <?php include INCLUDES_PATH . '/footer.php'; ?>
    <script>
    const recState  = {};
    let prepInterval = null;
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

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

    function toggleRecording(pNum) {
        const btn      = document.getElementById('recBtn-' + pNum);
        const icon     = document.getElementById('recIcon-' + pNum);
        const timeEl   = document.getElementById('recTime-' + pNum);
        const status   = document.getElementById('recStatus-' + pNum);
        const textarea = document.getElementById('transcript-' + pNum);

        if (recState[pNum] && recState[pNum].active) {
            recState[pNum].recognition.stop();
            clearInterval(recState[pNum].timer);
            recState[pNum].active = false;
            btn.classList.remove('recording');
            btn.style.background = '#6b7280';
            icon.className = 'bi bi-mic-fill';
            status.textContent = 'Done — review your transcription above, then edit if needed';
            return;
        }

        if (!SpeechRecognition) {
            Swal.fire({
                title: 'Browser not supported',
                html: 'Speech recognition requires Chrome or Edge. You can <strong>type your response directly</strong> into the text box instead.',
                icon: 'warning',
            });
            return;
        }

        const recognition = new SpeechRecognition();
        recognition.continuous     = true;
        recognition.interimResults = true;
        recognition.lang           = 'en-US';

        let savedText = textarea.value;
        recognition.onresult = e => {
            let interim = '', final = '';
            for (let i = e.resultIndex; i < e.results.length; i++) {
                if (e.results[i].isFinal) final   += e.results[i][0].transcript + ' ';
                else                       interim += e.results[i][0].transcript;
            }
            savedText += final;
            textarea.value = savedText + interim;
            if (final) savedText = textarea.value.replace(interim, '');
        };
        recognition.onerror = e => {
            if (e.error !== 'no-speech') status.textContent = 'Mic error: ' + e.error + ' — check permissions';
        };
        recognition.onend = () => {
            if (recState[pNum] && recState[pNum].active) recognition.start();
        };
        recognition.start();

        let elapsed = 0;
        const timer = setInterval(() => {
            elapsed++;
            timeEl.textContent = Math.floor(elapsed/60) + ':' + String(elapsed%60).padStart(2,'0');
        }, 1000);

        recState[pNum] = { active: true, recognition, timer };
        btn.classList.add('recording');
        btn.style.background = '#ef4444';
        icon.className = 'bi bi-stop-fill';
        status.textContent = 'Recording & transcribing — press again to stop';
    }

    function buildTasksPayload() {
        const parts = <?= json_encode(array_map(fn($p) => [
            'title'   => $p['title'],
            'prompts' => $p['prompts'],
        ], $parts)) ?>;

        return Object.keys(parts).map((key, i) => {
            const pNum       = i + 1;
            const transcript = (document.getElementById('transcript-' + pNum)?.value || '').trim();
            const promptText = parts[key].prompts.join('\n');
            return { part: pNum, prompt: parts[key].title + '\n\n' + promptText, transcription: transcript };
        });
    }

    async function submitAllParts() {
        const tasks = buildTasksPayload();
        const empty = tasks.filter(t => t.transcription.length < 10);
        if (empty.length > 0) {
            const partNames = empty.map(t => 'Part ' + t.part).join(', ');
            const r = await Swal.fire({
                title: 'Missing responses',
                html: `${partNames} ${empty.length > 1 ? 'have' : 'has'} no transcription. Submit anyway?`,
                icon: 'warning', showCancelButton: true,
                confirmButtonText: 'Submit anyway', cancelButtonText: 'Go back',
            });
            if (!r.isConfirmed) return;
        }

        document.querySelector('[onclick="submitAllParts()"]').disabled = true;
        document.getElementById('loadingSection').classList.remove('d-none');

        try {
            const res = await fetch('../../api/api_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action:    'analyze_speaking_batch',
                    exam_type: 'IELTS',
                    tasks:     tasks,
                }),
            });
            const data = await res.json();
            document.getElementById('loadingSection').classList.add('d-none');
            document.getElementById('resultsSection').classList.remove('d-none');

            if (data.success) {
                document.getElementById('feedbackContent').innerHTML =
                    '<pre style="white-space:pre-wrap;font-family:inherit;line-height:1.7;">' +
                    data.feedback.replace(/</g,'&lt;') + '</pre>';
                document.getElementById('resultsSection').scrollIntoView({ behavior: 'smooth' });
            } else {
                document.getElementById('feedbackContent').innerHTML =
                    '<div class="alert alert-danger">' + (data.error || 'Unknown error') + '</div>';
            }
        } catch (err) {
            document.getElementById('loadingSection').classList.add('d-none');
            Swal.fire({ title: 'Error', text: err.message, icon: 'error' });
        }
    }
    </script>
</body>
</html>
