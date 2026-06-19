<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

// ——————— AJAX HANDLER ———————
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);

    if (!$data || !isset($data['action'])) {
        $data = $_POST;
    }

    if (isset($data['action']) && $data['action'] === 'check_answers') {
        header('Content-Type: application/json');

        try {
            $inputs = $data['inputs'] ?? [];
            $level = (int)$data['level'];

            // LOGIC ADJUSTED FOR 10 LEVELS:
            // Min length requirement increases per level to ensure thoughtful answers.
            $filledFields = 0;
            $totalFields = 5;
            $minLength = 5 + ($level * 2); // Level 1 needs 7+ chars, Level 10 needs 25+ chars per input

            foreach ($inputs as $key => $val) {
                if (strlen(trim($val)) >= $minLength) {
                    $filledFields++;
                }
            }

            $score = round(($filledFields / $totalFields) * 100);
            $passed = $score == 100; // Must be 100% complete and detailed enough to pass

            // Construct the final paragraph for display
            $paragraph = implode(' ', $inputs);

            echo json_encode([
                'status' => 'success',
                'score' => $score,
                'passed' => $passed,
                'final_paragraph' => $paragraph,
                'feedback' => $passed ? "You successfully built a strong, evidence-based paragraph!" : "All fields must be filled with sufficient detail (Min {$minLength} characters per step)."
            ]);
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Build Your Evidence | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>

    <style>
        body { background-color: #f8fafc; }
        .main-wrapper { padding: 2rem 1rem; }
        
        .builder-card {
            background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            padding: 2rem; border: 1px solid #e2e8f0;
        }

        .step-container {
            border-left: 3px solid #cbd5e1; padding-left: 1.5rem; margin-bottom: 1.5rem; position: relative;
        }
        .step-container.active { border-left-color: #6366f1; }
        
        .step-label {
            font-size: 0.85rem; text-transform: uppercase; font-weight: 700; color: #64748b; margin-bottom: 0.5rem; display: block;
        }
        .step-container.active .step-label { color: #6366f1; }

        .form-control {
            background: #f8fafc; border: 2px solid #e2e8f0; padding: 0.8rem; transition: all 0.2s;
        }
        .form-control:focus { background: white; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }

        .preview-box {
            background: #e0e7ff; border: 2px dashed #6366f1; border-radius: 12px;
            padding: 1.5rem; margin-top: 2rem; color: #312e81; font-size: 1.1rem; line-height: 1.6;
        }

        .topic-badge {
            background: #312e81; color: white; padding: 0.5rem 1rem; border-radius: 8px;
            display: inline-block; margin-bottom: 2rem; font-weight: 600;
        }
        
        .btn-build {
            background: #6366f1; color: white; width: 100%; padding: 1rem; font-weight: 700; border: none; border-radius: 8px;
        }
        .btn-build:hover { background: #4f46e5; }
    </style>
</head>
<body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <main class="main-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="builder-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="topic-badge" id="topicBadge">Topic: Loading...</div>
                            <span class="badge bg-secondary" id="lvlIndicator">Level 1</span>
                        </div>
                        <p class="text-muted">Fill out each step below to build a complete, argumentative paragraph.</p>

                        <form id="evidenceForm">
                            <div class="step-container active">
                                <label class="step-label">1. The Claim (What do you believe?)</label>
                                <input type="text" class="form-control" id="inputClaim" placeholder="e.g. Social media is harmful to teens.">
                            </div>

                            <div class="step-container">
                                <label class="step-label">2. The Reason (Why is this true?)</label>
                                <input type="text" class="form-control" id="inputReason" placeholder="This is because...">
                            </div>

                            <div class="step-container">
                                <label class="step-label">3. The Evidence (Specific Fact/Statistic/Example)</label>
                                <input type="text" class="form-control" id="inputEvidence" placeholder="For example, studies show...">
                            </div>

                            <div class="step-container">
                                <label class="step-label">4. The Explanation (How does the evidence prove the claim?)</label>
                                <input type="text" class="form-control" id="inputExplain" placeholder="This clearly demonstrates that...">
                            </div>

                            <div class="step-container">
                                <label class="step-label">5. The Conclusion (So what? What should be done?)</label>
                                <input type="text" class="form-control" id="inputConclusion" placeholder="Therefore, we must...">
                            </div>

                            <button type="button" class="btn-build" onclick="submitBuild()"><i class="bi bi-bricks"></i> Build & Check Paragraph</button>
                        </form>

                        <div id="resultArea" style="display:none;">
                            <div class="preview-box">
                                <h5><i class="bi bi-file-text"></i> Your Built Paragraph:</h5>
                                <p id="finalParagraph"></p>
                            </div>
                            <div class="mt-3 text-center">
                                <button class="btn btn-success" onclick="nextLevel()">Next Challenge <i class="bi bi-arrow-right"></i></button>
                            </div>
                        </div>
                        
                         <div id="feedbackBox" class="alert mt-3 d-none"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>

    <script>
        let currentLevel = 1;
        
        // 10 distinct topics/prompts
        const topics = {
            1: "The importance of reading books.",
            2: "The best pet to own.",
            3: "The necessity of school uniforms.",
            4: "Why recycling should be mandatory.",
            5: "The impact of plastic on the oceans.",
            6: "Should all higher education be free?",
            7: "The ethics of self-driving cars.",
            8: "Should governments regulate social media content?",
            9: "The benefits and dangers of Artificial Intelligence.",
            10: "Is a Universal Basic Income (UBI) feasible for modern economies?"
        };

        function init() {
            document.getElementById('topicBadge').textContent = "Topic: " + topics[currentLevel];
            document.getElementById('lvlIndicator').textContent = "Level " + currentLevel;
            document.getElementById('evidenceForm').reset();
            document.getElementById('resultArea').style.display = 'none';
            document.getElementById('feedbackBox').className = 'alert mt-3 d-none';
        }

        function submitBuild() {
            const inputs = {
                claim: document.getElementById('inputClaim').value,
                reason: document.getElementById('inputReason').value,
                evidence: document.getElementById('inputEvidence').value,
                explain: document.getElementById('inputExplain').value,
                conclusion: document.getElementById('inputConclusion').value
            };

            fetch('', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    action: 'check_answers',
                    inputs: inputs,
                    level: currentLevel
                })
            })
            .then(res => res.json())
            .then(data => {
                const fb = document.getElementById('feedbackBox');
                fb.classList.remove('d-none', 'alert-success', 'alert-danger');
                fb.textContent = data.feedback;
                
                if(data.passed) {
                    fb.classList.add('alert-success');
                    document.getElementById('finalParagraph').textContent = data.final_paragraph;
                    document.getElementById('resultArea').style.display = 'block';
                    // Scroll to result
                    document.getElementById('resultArea').scrollIntoView({behavior: 'smooth'});
                } else {
                    fb.classList.add('alert-danger');
                }
            });
        }

        function nextLevel() {
            if(currentLevel < 10) {
                currentLevel++;
                init();
            } else {
                alert("Course Complete! You've mastered evidence building!");
            }
        }

        // Add focus effects to highlight active step
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                document.querySelectorAll('.step-container').forEach(c => c.classList.remove('active'));
                this.closest('.step-container').classList.add('active');
            });
        });

        init();
    </script>
</body>
</html>