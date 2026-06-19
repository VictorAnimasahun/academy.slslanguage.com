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
    <title>IELTS Full Practice Test | EduHub</title>
    
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
            min-height: 600px;
        }
        .listening-table td { padding: 1.1rem !important; vertical-align: middle; }
        .answer-input { max-width: 320px; }
        .results { animation: fadeIn 0.4s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        .reading-passage { 
            background: #f8f9fa; 
            padding: 1.5rem; 
            border-radius: 8px; 
            margin-bottom: 2rem; 
            line-height: 1.7;
        }
        .writing-task { line-height: 1.8; }
    </style>
</head>
<body>

    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <main class="main-wrapper">
        <div class="content-container">

            <div class="page-header">
                <h1><i class="bi bi-journal-text"></i> IELTS Full Practice Test</h1>
                <p class="text-muted">Listening • Reading • Writing • Speaking</p>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#listening">Listening</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#reading">Reading</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#writing">Writing</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#speaking">Speaking</a></li>
            </ul>

            <div class="tab-content">

                <!-- LISTENING -->
                <div class="tab-pane fade show active" id="listening">
                    <div class="audio-box" id="audioBox">
                        <h5><i class="bi bi-volume-up-fill me-2"></i> Listening Audio</h5>
                        <p class="mb-2" id="audioStatusText">Click anywhere on the page to start the audio automatically</p>
                        <small class="text-white-50">The audio plays once. You cannot pause or rewind.</small>
                    </div>

                    <audio id="listeningAudio" preload="auto">
                        <source src="media/section-1-ielts-listening-recording-1.mp3" type="audio/mpeg">
                    </audio>

                    <div class="section-content">
                        <h4 class="mb-3">Questions 1–6</h4>
                        <p class="text-muted mb-4">Complete the form below.</p>

                        <table class="table table-bordered listening-table">
                            <tr><td><strong>Customer's name is</strong></td><td>Ruby Thompson</td></tr>
                            <tr><td><strong>Contact phone number is</strong></td><td><input type="text" id="q1" class="form-control answer-input" placeholder="(1)" maxlength="25"></td></tr>
                            <tr><td><strong>collect from</strong></td><td>119 <input type="text" id="q2" class="form-control answer-input d-inline" placeholder="(2)" maxlength="30"> Hamilton, Waikato, New Zealand.</td></tr>
                            <tr><td><strong>Ship to</strong></td><td>2096 <input type="text" id="q3" class="form-control answer-input d-inline" placeholder="(3)" maxlength="30"> Edmonton, Alberta, Canada.</td></tr>
                            <tr><td><strong>Prepare for shipment on</strong></td><td><input type="text" id="q4" class="form-control answer-input" placeholder="(4)" maxlength="25">, January the 9th</td></tr>
                            <tr><td><strong>Tidy up the collection site by 9 AM on</strong></td><td><input type="text" id="q5" class="form-control answer-input" placeholder="(5)" maxlength="25">, January the 12th</td></tr>
                            <tr><td><strong>Store before shipment for</strong></td><td><input type="text" id="q6" class="form-control answer-input" placeholder="(6)" maxlength="15"> months</td></tr>
                        </table>

                        <h4 class="mb-4 mt-5">Questions 7–10</h4>
                        <p class="mb-3">Where does the agent suggest packing the following items?</p>
                        <div class="border p-4 mb-4 bg-light rounded">
                            <strong>A.</strong> readily accessible<br>
                            <strong>B.</strong> personal objects<br>
                            <strong>C.</strong> precious items
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6"><div class="d-flex align-items-center gap-3"><label class="fw-bold mb-0" style="min-width:130px">7. clothes</label><input type="text" id="q7" class="form-control text-center" style="width:85px" maxlength="1" placeholder="A/B/C"></div></div>
                            <div class="col-md-6"><div class="d-flex align-items-center gap-3"><label class="fw-bold mb-0" style="min-width:130px">8. coffee maker</label><input type="text" id="q8" class="form-control text-center" style="width:85px" maxlength="1" placeholder="A/B/C"></div></div>
                            <div class="col-md-6"><div class="d-flex align-items-center gap-3"><label class="fw-bold mb-0" style="min-width:130px">9. family photos</label><input type="text" id="q9" class="form-control text-center" style="width:85px" maxlength="1" placeholder="A/B/C"></div></div>
                            <div class="col-md-6"><div class="d-flex align-items-center gap-3"><label class="fw-bold mb-0" style="min-width:130px">10. computers</label><input type="text" id="q10" class="form-control text-center" style="width:85px" maxlength="1" placeholder="A/B/C"></div></div>
                        </div>

                        <div class="d-flex gap-3 mt-5">
                            <button onclick="submitListening()" class="btn btn-success btn-lg px-5">Submit Listening</button>
                            <button onclick="resetListening()" class="btn btn-outline-secondary btn-lg">Reset</button>
                        </div>

                        <div id="listeningResults" class="results mt-5" style="display:none;">
                            <h4>Your Listening Score: <span id="listeningScore" class="text-success"></span></h4>
                            <div id="listeningFeedback"></div>
                        </div>
                    </div>
                </div>

                <!-- READING -->
                <div class="tab-pane fade" id="reading">
                    <div class="section-content">
                        <h4>Reading Passage: Resigning from a job in a professional manner</h4>
                        <div class="reading-passage">
                            <p>When you take the decision to resign and move on to another job, you might really want to dance your way out of the door... But you need to resist these temptations...</p>
                            <p><strong>Letting your manager know</strong><br>Show courtesy by telling your boss first...</p>
                            <p><strong>Preparation</strong><br>Work out how you can ensure a smooth transition...</p>
                            <p><strong>Your letter of resignation</strong><br>You don't need to give lots of explanations...</p>
                            <!-- Full passage text can be expanded here if needed -->
                        </div>

                        <h5>Questions 22–27</h5>
                        <p class="text-muted">Complete the notes below. Choose ONE WORD ONLY from the text for each answer.</p>

                        <div class="mt-4">
                            <p><strong>22.</strong> Avoid all <input type="text" id="r22" class="form-control d-inline" style="width:180px"> to resign in an angry way.</p>
                            <p><strong>23.</strong> Mention any projects which are underway and give ideas for their <input type="text" id="r23" class="form-control d-inline" style="width:180px">.</p>
                            <p><strong>24.</strong> Request information on the type of <input type="text" id="r24" class="form-control d-inline" style="width:180px"> you will receive.</p>
                            <p><strong>25.</strong> Work to cause as little <input type="text" id="r25" class="form-control d-inline" style="width:180px"> as possible to the organisation.</p>
                            <p><strong>26.</strong> In the resignation letter: avoid mentioning any <input type="text" id="r26" class="form-control d-inline" style="width:180px"> in the organisation.</p>
                            <p><strong>27.</strong> Show appreciation for aspects of the job, e.g., the chance to improve your <input type="text" id="r27" class="form-control d-inline" style="width:180px">.</p>
                        </div>

                        <button onclick="submitReading()" class="btn btn-success mt-4">Submit Reading</button>
                        <div id="readingResults" class="results mt-4" style="display:none;"></div>
                    </div>
                </div>

                <!-- WRITING -->
                <div class="tab-pane fade" id="writing">
                    <div class="section-content writing-task">
                        <h4>General Training Writing Task 1</h4>
                        <p><strong>You should spend about 20 minutes on this task.</strong></p>
                        <p>A friend of yours is thinking of going to a music festival for the first time this summer. He/She has asked for your advice.</p>
                        <p>Write a letter to your friend. In your letter:</p>
                        <ul>
                            <li>Explain why you think he/she would enjoy music festivals</li>
                            <li>Describe some problems he/she might have</li>
                            <li>Say if you would like to join your friend</li>
                        </ul>
                        <p>Write at least 150 words.</p>

                        <textarea id="writingAnswer" class="form-control mt-4" rows="15" placeholder="Write your letter here..."></textarea>
                        <button onclick="submitWriting()" class="btn btn-success mt-3">Submit Writing (for feedback later)</button>
                    </div>
                </div>

                <!-- SPEAKING -->
                <div class="tab-pane fade" id="speaking">
                    <div class="section-content">
                        <h4>Speaking Part 2</h4>
                        <div class="border p-4 bg-light rounded">
                            <strong>Describe a hotel that you know.</strong><br><br>
                            You should say:<br>
                            • where this hotel is<br>
                            • what this hotel looks like<br>
                            • what facilities this hotel has<br>
                            and explain whether you think this is a nice hotel to stay in.
                        </div>
                        <p class="mt-4 text-muted">Practice speaking for 1-2 minutes on this topic. Record yourself if possible.</p>
                        <button class="btn btn-danger mt-3">Start Recording (Coming soon)</button>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <?php include INCLUDES_PATH . '/adverts.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>

    <script>
        // Audio Auto-play (Listening)
        const audio = document.getElementById('listeningAudio');
        const audioStatusText = document.getElementById('audioStatusText');
        let audioStarted = false;

        document.addEventListener('click', function startAudio() {
            if (!audioStarted && audio) {
                audio.play().then(() => {
                    audioStatusText.textContent = "♪ Audio is playing...";
                    document.getElementById('audioBox').style.background = 'linear-gradient(135deg, #10b981, #34d399)';
                }).catch(() => {});
                audioStarted = true;
                document.removeEventListener('click', startAudio);
            }
        }, { once: false });

        audio.controls = false;

        // Correct answers for Listening (update with real ones)
        const correctAnswers = {
            q1: "0215551234", q2: "Queen Street", q3: "Maple Road", q4: "January 6th",
            q5: "January 11th", q6: "2", q7: "B", q8: "A", q9: "C", q10: "C"
        };

        function submitListening() {
            let score = 0;
            let feedback = '<h5>Listening Feedback</h5>';

            for (let i = 1; i <= 10; i++) {
                const input = document.getElementById('q' + i);
                if (!input) continue;
                let ans = input.value.trim();
                let correct = correctAnswers['q' + i] || '';
                let isCorrect = (i <= 6) ? (ans.toLowerCase().replace(/\s/g,'') === correct.toLowerCase().replace(/\s/g,'')) : (ans.toUpperCase() === correct);

                if (isCorrect) score++;
                feedback += `<div>Q${i}: ${ans || '(empty)'} → ${isCorrect ? '✓ Correct' : '✗ (Answer: ' + correct + ')'}</div>`;
            }

            document.getElementById('listeningScore').innerHTML = `${score}/10`;
            document.getElementById('listeningFeedback').innerHTML = feedback;
            document.getElementById('listeningResults').style.display = 'block';
        }

        function resetListening() {
            for (let i = 1; i <= 10; i++) {
                const el = document.getElementById('q' + i);
                if (el) el.value = '';
            }
            document.getElementById('listeningResults').style.display = 'none';
        }

        // Placeholder functions for other sections
        function submitReading() {
            Swal.fire({ title: 'Reading submitted!', text: 'Scoring coming soon.', icon: 'success', confirmButtonColor: '#10b981' });
        }
        function submitWriting() {
            Swal.fire({ title: 'Writing submitted!', text: 'AI feedback coming soon.', icon: 'success', confirmButtonColor: '#10b981' });
        }
    </script>
</body>
</html>