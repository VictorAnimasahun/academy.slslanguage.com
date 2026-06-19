<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>From Thin to Thick | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>

    <style>
        body { overflow: hidden; }
        .main-wrapper { height: 100vh; overflow-y: auto; padding: 1rem; }
        .exercise-container {
            max-width: 100%; min-height: calc(100vh - 2rem);
            background: white; border-radius: 12px; padding: 1rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08); display: flex; flex-direction: column;
        }
        .exercise-header { border-bottom: 2px solid #f0f0f0; margin-bottom: 1rem; padding-bottom: 0.75rem; }
        .level-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white; padding: 0.4rem 1rem; border-radius: 20px; font-weight: 700;
        }
        .progress-bar-container { flex: 1; margin: 0 1rem; height: 8px; background: #e2e8f0; border-radius: 10px; overflow: hidden; }
        .progress-bar-fill { height: 100%; background: linear-gradient(90deg, #10b981, #059669); transition: width 0.5s ease; }
        
        .thin-sentence-box {
            background: #f1f5f9; border-left: 5px solid #10b981;
            padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;
        }
        .thin-sentence-box h3 { font-size: 0.9rem; text-transform: uppercase; color: #64748b; letter-spacing: 1px; }
        .thin-sentence-box p { font-size: 1.4rem; font-weight: 700; color: #1e293b; margin: 0; }
        
        textarea {
            width: 100%; border: 2px solid #e2e8f0; border-radius: 8px;
            padding: 1rem; font-size: 1.1rem; resize: none; transition: border 0.3s;
            min-height: 150px;
        }
        textarea:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); }
        
        .word-count { text-align: right; color: #64748b; font-size: 0.9rem; margin-top: 0.5rem; }
        
        .btn-check {
            background: #10b981; color: white; border: none; padding: 0.8rem 2rem;
            border-radius: 6px; font-weight: 600; font-size: 1rem; margin-top: 1rem;
        }
        .btn-check:hover { background: #059669; }
        .btn-check:disabled { background: #9ca3af; cursor: not-allowed; }
        
        /* Modal Styles */
        .level-complete-modal {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.7); display: none; align-items: center; justify-content: center; z-index: 9999;
        }
        .level-complete-modal.show { display: flex; }
        .modal-content {
            background: white; padding: 2rem; border-radius: 12px; text-align: center;
            max-width: 400px; animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn { from { transform: translateY(-50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    </style>
</head>
<body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <main class="main-wrapper">
        <div class="exercise-container">
            <div class="exercise-header">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="level-badge" id="levelBadge">Level 1</span>
                    <div class="progress-bar-container">
                        <div class="progress-bar-fill" id="progressBar" style="width: 10%"></div>
                    </div>
                    <span style="font-weight: 600; color: #64748b;">10 Levels</span>
                </div>
                <h1 style="font-size: 1.5rem; font-weight: 700;">From Thin to Thick</h1>
                <p class="text-muted">Take the "Thin" sentence below and expand it into a "Thick" paragraph by adding details, reasons, and examples.</p>
            </div>

            <div class="row h-100">
                <div class="col-md-8 mx-auto">
                    <div class="thin-sentence-box">
                        <h3>The Thin Sentence:</h3>
                        <p id="thinText">Loading...</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label fw-bold">Your "Thick" Version:</label>
                        <textarea id="studentInput" placeholder="Expand the idea here... add 'because', 'for example', or descriptive details."></textarea>
                        <div class="word-count">Words: <span id="wordCounter">0</span></div>
                    </div>

                    <div class="d-flex gap-2 mt-3">
						<button type="button" class="btn btn-success btn-lg" id="checkButton" onclick="checkAnswer()">
							<i class="bi bi-magic"></i> Check My Writing
						</button>
						<button type="button" class="btn btn-outline-secondary btn-lg" onclick="resetInput()">
							<i class="bi bi-arrow-counterclockwise"></i> Clear
						</button>
					</div>
                    
                    <div id="feedbackBox" class="alert mt-3 d-none"></div>
                </div>
            </div>
        </div>
    </main>

    <div class="level-complete-modal" id="levelCompleteModal">
        <div class="modal-content">
            <h2><i class="bi bi-stars" style="color: #fbbf24;"></i> Awesome!</h2>
            <p>You added great depth to that sentence.</p>
            <div class="score" id="modalScore" style="font-size: 2.5rem; font-weight: 800; color: #10b981;">100%</div>
            <button class="btn btn-primary" onclick="nextLevel()">Next Level</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>

    <script>
        let currentLevel = 1;
        
        // 10 distinct levels for Thin to Thick
        const levelData = {
            1: "The dog barked.",
            2: "I was tired.",
            3: "The movie was good.",
            4: "Social media is bad.",
            5: "It rained all day.",
            6: "Exercise is important.",
            7: "The city is noisy.",
            8: "Technology changes fast.",
            9: "Traveling teaches you things.",
            10: "Climate change is real."
        };

        function init() {
            document.getElementById('thinText').textContent = levelData[currentLevel];
            document.getElementById('levelBadge').textContent = `Level ${currentLevel}`;
            document.getElementById('progressBar').style.width = (currentLevel * 10) + '%';
            document.getElementById('studentInput').value = '';
            document.getElementById('wordCounter').textContent = '0';
            document.getElementById('feedbackBox').className = 'alert mt-3 d-none';
        }

        document.getElementById('studentInput').addEventListener('input', function() {
            const words = this.value.trim().split(/\s+/).filter(word => word.length > 0).length;
            document.getElementById('wordCounter').textContent = words;
        });

        async function checkAnswer() {
            const input = document.getElementById('studentInput').value;
            if(input.length < 5) {
                alert("Please write something first!");
                return;
            }

            // Show loading state
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Evaluating...';

            try {
                const response = await fetch('<?php echo ACADEMY_URL; ?>api/api_handler.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        action: 'evaluate_thin_to_thick',
                        thin_sentence: levelData[currentLevel],
                        expansion: input,
                        level: currentLevel
                    })
                });

                const data = await response.json();
                
                console.log("API Response:", data);

                // Reset button
                btn.disabled = false;
                btn.innerHTML = originalText;
                
                if (!response.ok) {
                    throw new Error(data.error || 'Analysis failed');
                }

                const fb = document.getElementById('feedbackBox');
                fb.classList.remove('d-none', 'alert-success', 'alert-danger');
                fb.textContent = data.feedback;
                
                if(data.passed) {
                    fb.classList.add('alert-success');
                    setTimeout(() => {
                        document.getElementById('modalScore').textContent = data.score + '%';
                        document.getElementById('levelCompleteModal').classList.add('show');
                    }, 1000);
                } else {
                    fb.classList.add('alert-danger');
                }
            } catch (error) {
                btn.disabled = false;
                btn.innerHTML = originalText;
                alert("Error: " + error.message);
                console.error("Full error:", error);
            }
        }

        function nextLevel() {
            if(currentLevel < 10) {
                currentLevel++;
                document.getElementById('levelCompleteModal').classList.remove('show');
                init();
            } else {
                alert("You've completed all levels!");
                location.reload();
            }
        }
        
        function resetInput() {
            document.getElementById('studentInput').value = '';
            document.getElementById('wordCounter').textContent = '0';
        }

        init();
    </script>
</body>
</html>