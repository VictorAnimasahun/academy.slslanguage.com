<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../registration.php?message=Please+login+to+access+resources");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Listening Practice | EduHub</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>

    <style>
        .main-wrapper { padding: 2rem 1.5rem; }
        .content-container { max-width: 1100px; margin: 0 auto; }
        .page-header { margin-bottom: 2rem; text-align: center; }
        .page-header h1 { font-size: 2.4rem; font-weight: 700; color: #1f2937; }

        .nav-tabs { border-bottom: 2px solid #e5e7eb; }
        .nav-link { font-weight: 600; color: #6b7280; padding: 1rem 1.5rem; }
        .nav-link.active { color: #667eea; border-bottom: 3px solid #667eea; }

        .audio-box {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 2rem;
        }
        .section-content {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }
        .listening-table td {
            padding: 1.1rem !important;
            vertical-align: middle;
        }
        .answer-input {
            max-width: 320px;
        }
        .results {
            animation: fadeIn 0.4s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- Mobile Header -->
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <!-- Sidebar Navigation (Left) -->
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <!-- MAIN CONTENT AREA -->
    <main class="main-wrapper">
        <div class="content-container">

            <div class="page-header">
                <h1><i class="bi bi-headphones"></i> IELTS Listening Practice</h1>
                <p class="text-muted">Removal Booking Confirmation • Questions 1–10</p>
                <small class="text-danger fw-bold">Write NO MORE THAN TWO WORDS AND/OR NUMBERS for each answer.</small>
            </div>

            <!-- Section Tabs -->
            <ul class="nav nav-tabs mb-4" id="sectionTabs">
                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#listening">Listening</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#reading">Reading</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#writing">Writing</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#speaking">Speaking</a></li>
            </ul>

            <div class="tab-content">

                <!-- LISTENING SECTION -->
                <div class="tab-pane fade show active" id="listening">

                    <!-- Audio Status Box -->
                    <div class="audio-box" id="audioBox">
                        <h5><i class="bi bi-volume-up-fill me-2"></i> Listening Audio</h5>
                        <p class="mb-2" id="audioStatusText">Click anywhere on the page to start the audio automatically</p>
                        <small class="text-white-50">The audio plays once. You cannot pause or rewind.</small>
                    </div>

                    <!-- Hidden Audio (No controls) -->
                    <audio id="listeningAudio" preload="auto">
                        <source src="media/section-1-ielts-listening-recording-1.mp3" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>

                    <div class="section-content">

                        <!-- Questions 1-6 -->
                        <h4 class="mb-3">Questions 1–6</h4>
                        <p class="text-muted mb-4">Complete the form below.</p>

                        <table class="table table-bordered listening-table">
                            <tr><td><strong>Customer's name is</strong></td><td>Ruby Thompson</td></tr>
                            <tr>
                                <td><strong>Contact phone number is</strong></td>
                                <td><input type="text" id="q1" class="form-control answer-input" placeholder="(1)" maxlength="25"></td>
                            </tr>
                            <tr>
                                <td><strong>collect from</strong></td>
                                <td>119 <input type="text" id="q2" class="form-control answer-input d-inline" placeholder="(2)" maxlength="30"> Hamilton, Waikato, New Zealand.</td>
                            </tr>
                            <tr>
                                <td><strong>Ship to</strong></td>
                                <td>2096 <input type="text" id="q3" class="form-control answer-input d-inline" placeholder="(3)" maxlength="30"> Edmonton, Alberta, Canada.</td>
                            </tr>
                            <tr>
                                <td><strong>Prepare for shipment on</strong></td>
                                <td><input type="text" id="q4" class="form-control answer-input" placeholder="(4)" maxlength="25">, January the 9th</td>
                            </tr>
                            <tr>
                                <td><strong>Tidy up the collection site by 9 AM on</strong></td>
                                <td><input type="text" id="q5" class="form-control answer-input" placeholder="(5)" maxlength="25">, January the 12th</td>
                            </tr>
                            <tr>
                                <td><strong>Store before shipment for</strong></td>
                                <td><input type="text" id="q6" class="form-control answer-input" placeholder="(6)" maxlength="15"> months</td>
                            </tr>
                        </table>

                        <!-- Questions 7-10 -->
                        <h4 class="mb-4 mt-5">Questions 7–10</h4>
                        <p class="mb-3">Where does the agent suggest packing the following items?</p>
                        
                        <div class="border p-4 mb-4 bg-light rounded">
                            <strong>A.</strong> readily accessible<br>
                            <strong>B.</strong> personal objects<br>
                            <strong>C.</strong> precious items
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3">
                                    <label class="fw-bold mb-0" style="min-width: 130px;">7. clothes</label>
                                    <input type="text" id="q7" class="form-control text-center" style="width: 85px;" maxlength="1" placeholder="A/B/C">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3">
                                    <label class="fw-bold mb-0" style="min-width: 130px;">8. coffee maker</label>
                                    <input type="text" id="q8" class="form-control text-center" style="width: 85px;" maxlength="1" placeholder="A/B/C">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3">
                                    <label class="fw-bold mb-0" style="min-width: 130px;">9. family photos</label>
                                    <input type="text" id="q9" class="form-control text-center" style="width: 85px;" maxlength="1" placeholder="A/B/C">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3">
                                    <label class="fw-bold mb-0" style="min-width: 130px;">10. computers</label>
                                    <input type="text" id="q10" class="form-control text-center" style="width: 85px;" maxlength="1" placeholder="A/B/C">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-3 mt-5">
                            <button onclick="submitTest()" class="btn btn-success btn-lg px-5">
                                <i class="bi bi-check-circle"></i> Submit Answers
                            </button>
                            <button onclick="resetTest()" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                        </div>

                        <!-- Results -->
                        <div id="results" class="results mt-5" style="display: none;">
                            <h4>Your Score: <span id="scoreDisplay" class="text-success"></span></h4>
                            <div id="feedback" class="mt-3"></div>
                        </div>

                    </div>
                </div>

                <!-- Other sections remain unchanged -->
                <div class="tab-pane fade" id="reading">
                    <div class="section-content">
                        <h4>Reading Section</h4>
                        <p class="text-muted">Content for Reading will go here.</p>
                    </div>
                </div>
                <div class="tab-pane fade" id="writing">
                    <div class="section-content">
                        <h4>Writing Section</h4>
                        <p class="text-muted">IELTS Writing practice area (coming soon).</p>
                    </div>
                </div>
                <div class="tab-pane fade" id="speaking">
                    <div class="section-content">
                        <h4>Speaking Section</h4>
                        <p class="text-muted">Speaking practice area (coming soon).</p>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- Right Side / Adverts -->
    <?php include INCLUDES_PATH . '/adverts.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>

    <script>
        const audio = document.getElementById('listeningAudio');
        const audioStatusText = document.getElementById('audioStatusText');
        let audioStarted = false;

        // Auto-start audio on first click anywhere on the page
        document.addEventListener('click', function startAudio() {
            if (!audioStarted && audio) {
                audio.play().then(() => {
                    audioStatusText.textContent = "♪ Audio is playing...";
                    document.getElementById('audioBox').style.background = 'linear-gradient(135deg, #10b981, #34d399)';
                }).catch(() => {
                    console.log('Autoplay blocked - try clicking again');
                });

                audioStarted = true;
                // Remove the listener after first successful trigger
                document.removeEventListener('click', startAudio);
            }
        }, { once: false });

        // Hide native audio controls completely
        audio.controls = false;

        // Correct Answers - UPDATE THESE WITH THE REAL ONES FROM YOUR AUDIO TRANSCRIPT
        const correctAnswers = {
            q1: "0215551234",     // Change to actual phone number from audio
            q2: "Queen Street",   // Change to actual
            q3: "Maple Road",     // Change to actual
            q4: "January 6th",    // Change to actual
            q5: "January 11th",   // Change to actual
            q6: "2",
            q7: "B",
            q8: "A",
            q9: "C",
            q10: "C"
        };

        function submitTest() {
            let score = 0;
            let feedbackHTML = '<h5 class="mb-3">Detailed Feedback</h5>';

            for (let i = 1; i <= 10; i++) {
                const input = document.getElementById('q' + i);
                if (!input) continue;

                let userAnswer = input.value.trim();
                let correct = correctAnswers['q' + i] || '';

                let isCorrect = false;
                if (i <= 6) {
                    isCorrect = userAnswer.toLowerCase().replace(/\s/g, '') === correct.toLowerCase().replace(/\s/g, '');
                } else {
                    isCorrect = userAnswer.toUpperCase() === correct;
                }

                if (isCorrect) score++;

                feedbackHTML += `
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                        <div><strong>Q${i}:</strong> ${userAnswer || '(empty)'}</div>
                        <div>
                            ${isCorrect 
                                ? '<span class="text-success">✓ Correct</span>' 
                                : `<span class="text-danger">✗ Incorrect</span> <small class="text-muted">(Expected: ${correct})</small>`
                            }
                        </div>
                    </div>`;
            }

            const percentage = Math.round((score / 10) * 100);
            document.getElementById('scoreDisplay').innerHTML = `${score}/10 <span class="text-muted">(${percentage}%)</span>`;
            document.getElementById('feedback').innerHTML = feedbackHTML;
            document.getElementById('results').style.display = 'block';
            document.getElementById('results').scrollIntoView({ behavior: 'smooth' });
        }

        function resetTest() {
            for (let i = 1; i <= 10; i++) {
                const input = document.getElementById('q' + i);
                if (input) input.value = '';
            }
            document.getElementById('results').style.display = 'none';
        }
    </script>
</body>
</html>