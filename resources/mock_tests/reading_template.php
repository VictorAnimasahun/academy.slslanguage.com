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
    die("Reading section not found.");
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
        .reading-container {
            max-width: 1300px;
            margin: 0 auto;
        }
        .passage-panel {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 2.2rem;
            height: 100%;
            overflow-y: auto;
        }
        .questions-panel {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 2rem;
            height: 100%;
        }
        .badge-mock {
            background: linear-gradient(135deg, #10b981, #34d399);
            color: white;
            padding: 0.65rem 1.8rem;
            border-radius: 50px;
            font-weight: 600;
        }
        .timer-display {
            font-size: 2.4rem;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            color: #1e40af;
        }
        .passage-text {
            line-height: 1.85;
            font-size: 1.05rem;
            white-space: pre-line;
        }
        .question-item {
            background: #f8fafc;
            border-left: 5px solid #3b82f6;
            padding: 1.4rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
        }
        .question-number {
            display: inline-block;
            background: #3b82f6;
            color: white;
            width: 32px;
            height: 32px;
            text-align: center;
            line-height: 32px;
            border-radius: 50%;
            font-weight: 700;
            margin-right: 12px;
        }
        .answer-input {
            width: 100%;
            padding: 0.9rem 1.2rem;
            border: 2px solid #cbd5e1;
            border-radius: 8px;
            font-size: 1.05rem;
        }
        .answer-input:focus {
            border-color: #3b82f6;
            outline: none;
        }
        .bottom-bar {
            position: sticky;
            bottom: 0;
            background: white;
            padding: 1rem 0;
            border-top: 1px solid #e5e7eb;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <main class="main-wrapper">
        <div class="reading-container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../resources.php">Resources</a></li>
                    <li class="breadcrumb-item"><a href="index.php">Mock Tests</a></li>
                    <li class="breadcrumb-item"><a href="take.php?code=<?= urlencode(str_replace('_R', '', $testCode)) ?>">Back to Mock</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($test['title']) ?></li>
                </ol>
            </nav>

            <div class="row g-4">
                <!-- Left: Passage -->
                <div class="col-lg-7">
                    <div class="passage-panel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="badge-mock">Reading Section</div>
                            <div class="timer-display" id="timer">60:00</div>
                        </div>

                        <h4 class="mb-4"><?= htmlspecialchars($test['title']) ?></h4>

                        <div class="passage-text">
                            <?= nl2br(htmlspecialchars($test['description'] ?? $test['instructions'] ?? 'Reading passages will be loaded here.')) ?>
                            <p class="text-muted mt-5"><em>Note: In a real implementation, the full reading passages would be stored in a separate table or as rich text.</em></p>
                        </div>
                    </div>
                </div>

                <!-- Right: Questions -->
                <div class="col-lg-5">
                    <div class="questions-panel">
                        <h5 class="mb-4">Questions</h5>
                        
                        <!-- Example Questions - In real system these would come from test_questions table -->
                        <div class="question-item">
                            <div><span class="question-number">1</span> What is the main purpose of the text?</div>
                            <input type="text" class="answer-input mt-3" placeholder="Type your answer here...">
                        </div>

                        <div class="question-item">
                            <div><span class="question-number">2</span> TRUE / FALSE / NOT GIVEN</div>
                            <div class="mt-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="q2" id="q2t">
                                    <label class="form-check-label" for="q2t">TRUE</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="q2" id="q2f">
                                    <label class="form-check-label" for="q2f">FALSE</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="q2" id="q2n">
                                    <label class="form-check-label" for="q2n">NOT GIVEN</label>
                                </div>
                            </div>
                        </div>

                        <div class="bottom-bar text-end">
                            <button onclick="submitReading()" class="btn btn-success px-5 py-2">
                                Submit Section & Continue →
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
    let timeLeft = <?= (int)$test['duration_minutes'] * 60; ?>;

    function startTimer() {
        const timerEl = document.getElementById('timer');
        const interval = setInterval(() => {
            timeLeft--;
            const min = Math.floor(timeLeft / 60);
            const sec = timeLeft % 60;
            timerEl.textContent = `${min}:${sec.toString().padStart(2, '0')}`;

            if (timeLeft <= 600) timerEl.style.color = '#ef4444'; // Last 10 minutes warning

            if (timeLeft <= 0) {
                clearInterval(interval);
                alert("Time's up for Reading section!");
                submitReading();
            }
        }, 1000);
    }

    function submitReading() {
        if (confirm("Submit this Reading section and move to the next part of the mock test?")) {
            alert("Reading section submitted successfully!");
            // Later: save answers + redirect to next section
            window.location.href = `take.php?code=<?= urlencode(str_replace('_R', '', $testCode)) ?>`;
        }
    }

    window.onload = startTimer;
    </script>
</body>
</html>