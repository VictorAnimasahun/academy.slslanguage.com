<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../edu_hub_registration.php?message=Please+login+to+access+mock+tests");
    exit();
}

$testCode = $_GET['code'] ?? '';
$test = getTest($testCode);

if (!$test || empty($test['is_mock_section'])) {
    die("Writing section not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($test['title']) ?> | Mock Test | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>

    <style>
        .main-wrapper {
            padding: 1.5rem;
            background: #f8f9fa;
            min-height: 100vh;
        }
        .mock-writing-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .left-panel, .right-panel {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 2rem;
            height: 100%;
        }
        .badge-mock {
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
            color: white;
            padding: 0.65rem 1.8rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.05rem;
        }
        .timer-display {
            font-size: 2.6rem;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            color: #1e40af;
        }
        .question-area h4 {
            font-size: 1.35rem;
            line-height: 1.4;
        }
        .instructions-box {
            background: #f0f9ff;
            border-left: 5px solid #3b82f6;
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 1.5rem;
        }
        .response-area {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .essay-textarea {
            flex: 1;
            width: 100%;
            padding: 1.8rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1.1rem;
            line-height: 1.85;
            resize: none;
            font-family: system-ui, -apple-system, sans-serif;
        }
        .essay-textarea:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.3rem rgba(59, 130, 246, 0.12);
        }
        .bottom-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }
        .word-count {
            font-size: 1.8rem;
            font-weight: 700;
        }
        .word-count.below { color: #ef4444; }
        .word-count.good  { color: #10b981; }
        .submit-btn {
            padding: 0.9rem 3rem;
            font-size: 1.15rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <main class="main-wrapper">
        <div class="mock-writing-container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../resources_home.php">Resources</a></li>
                    <li class="breadcrumb-item"><a href="index.php">Mock Tests</a></li>
                    <li class="breadcrumb-item"><a href="take.php?code=<?= urlencode(str_replace(['_W1','_W2'], '', $testCode)) ?>">Back to Mock</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($test['title']) ?></li>
                </ol>
            </nav>

            <div class="row g-4">
                <!-- Left Panel: Question -->
                <div class="col-lg-5">
                    <div class="left-panel">
                        <div class="badge-mock mb-3">Mock Section — <?= htmlspecialchars($test['task']) ?></div>
                        
                        <h3 class="mb-4"><?= htmlspecialchars($test['title']) ?></h3>
                        
                        <div class="question-area">
                            <?= nl2br(htmlspecialchars($test['description'] ?? '')) ?>
                        </div>

                        <?php if (!empty($test['instructions'])): ?>
                            <div class="instructions-box">
                                <strong>Instructions:</strong><br>
                                <?= nl2br(htmlspecialchars($test['instructions'])) ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($test['media_file']): ?>
                            <div class="mt-4 text-center">
                                <img src="../media/<?= htmlspecialchars($test['media_file']) ?>" 
                                     class="img-fluid rounded border" style="max-height: 320px;" alt="Task visual">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Right Panel: Response -->
                <div class="col-lg-7">
                    <div class="right-panel response-area">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4>Your Response</h4>
                            <div class="timer-display" id="timer">00:00</div>
                        </div>

                        <textarea id="responseText" class="essay-textarea" 
                            placeholder="Start writing your response here..."></textarea>

                        <div class="bottom-bar">
                            <div>
                                <span id="wordCount" class="word-count">0</span>
                                <span class="text-muted ms-2">words</span>
                                <?php if ($test['word_target']): ?>
                                    <small class="d-block text-muted">Target: <?= $test['word_target'] ?> words</small>
                                <?php endif; ?>
                            </div>
                            <button id="submitBtn" class="btn btn-success submit-btn" disabled>
                                Submit & Continue →
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>

    <script>
    const textarea = document.getElementById('responseText');
    const wordCountEl = document.getElementById('wordCount');
    const timerEl = document.getElementById('timer');
    const submitBtn = document.getElementById('submitBtn');

    let timeLeft = <?= (int)$test['duration_minutes'] * 60; ?>;

    function updateWordCount() {
        const words = textarea.value.trim().split(/\s+/).length || 0;
        wordCountEl.textContent = words;

        if (words < 100) wordCountEl.className = 'word-count below';
        else if (words >= <?= $test['word_target'] ?? 150 ?>) wordCountEl.className = 'word-count good';
        else wordCountEl.className = 'word-count';

        submitBtn.disabled = words < 90;
    }

    function startTimer() {
        const interval = setInterval(() => {
            timeLeft--;
            const min = Math.floor(timeLeft / 60);
            const sec = timeLeft % 60;
            timerEl.textContent = `${min}:${sec.toString().padStart(2, '0')}`;

            if (timeLeft <= 300) timerEl.style.color = '#ef4444';

            if (timeLeft <= 0) {
                clearInterval(interval);
                Swal.fire({
                    title: "Time's up!",
                    text: 'Writing time has ended. Your response has been submitted.',
                    icon: 'warning',
                    timer: 2500,
                    timerProgressBar: true,
                    showConfirmButton: false,
                }).then(() => submitResponse(true));
            }
        }, 1000);
    }

    function submitResponse(forced = false) {
        const words = textarea.value.trim().split(/\s+/).filter(Boolean).length;
        const doSubmit = () => {
            window.location.href = `take.php?code=<?= urlencode(str_replace(['_W1','_W2'], '', $testCode)) ?>`;
        };
        if (forced) { doSubmit(); return; }
        Swal.fire({
            title: 'Submit response?',
            html: `Words written: <strong>${words}</strong>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Submit',
            cancelButtonText: 'Keep writing',
            confirmButtonColor: '#10b981',
        }).then(result => { if (result.isConfirmed) doSubmit(); });
    }

    textarea.addEventListener('input', updateWordCount);
    submitBtn.addEventListener('click', submitResponse);

    window.onload = () => {
        updateWordCount();
        startTimer();
    };
    </script>
</body>
</html>