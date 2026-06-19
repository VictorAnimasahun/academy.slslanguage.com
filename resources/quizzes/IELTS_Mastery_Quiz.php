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
    <title>IELTS Mastery Knowledge Test | EduHub</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>

    <style>
        .main-wrapper { padding: 2rem 1.5rem; }
        .content-container { max-width: 1100px; margin: 0 auto; }
        .page-header { margin-bottom: 1.5rem; text-align: center; }
        .page-header h1 { font-size: 2.4rem; font-weight: 700; color: #1f2937; }

        .timer-box {
            background: linear-gradient(135deg, #dc3545, #f56b6b);
            color: white;
            border-radius: 12px;
            padding: 1rem 2rem;
            text-align: center;
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: 4px;
            box-shadow: 0 4px 20px rgba(220, 53, 69, 0.3);
            margin-bottom: 2rem;
        }
        .timer-box small { font-size: 1rem; font-weight: 400; display: block; }

        .section-content {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }
        .question-card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .question-card:hover {
            border-color: #667eea;
        }
        .form-check-label { cursor: pointer; }
        .results { animation: fadeIn 0.4s ease; }
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
    <!-- Sidebar Navigation -->
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="main-wrapper">
        <div class="content-container">

            <div class="page-header">
                <h1><i class="bi bi-trophy-fill"></i> IELTS Mastery Knowledge Test</h1>
                <p class="text-muted mb-0">40 Tough Multiple-Choice Questions • Covers ALL 4 IELTS Sections</p>
                <small class="text-danger fw-bold">Very challenging but fair • 20 minutes total</small>
            </div>

            <!-- Timer -->
            <div class="timer-box" id="timerBox">
                <i class="bi bi-clock-fill me-2"></i>
                <span id="timer">20:00</span>
                <small>Time remaining • Test starts automatically</small>
            </div>

            <div class="section-content">

                <form id="testForm">

                    <?php
                    $questions = [
                        // Listening 1-10
                        1 => ['text' => 'How many times is each recording played in the IELTS Listening test?', 
                              'options' => ['A' => 'Once', 'B' => 'Twice', 'C' => 'Three times', 'D' => 'Four times']],
                        2 => ['text' => 'The total duration of the IELTS Listening test (including transfer time) is?', 
                              'options' => ['A' => '20 minutes', 'B' => '30 minutes', 'C' => '40 minutes', 'D' => '60 minutes']],
                        3 => ['text' => 'Which section is usually a monologue on a social or everyday topic?', 
                              'options' => ['A' => 'Section 1', 'B' => 'Section 2', 'C' => 'Section 3', 'D' => 'Section 4']],
                        4 => ['text' => 'In Listening, spelling must be:', 
                              'options' => ['A' => 'Approximately correct', 'B' => 'Exactly as heard', 'C' => 'Grammatically correct only', 'D' => 'Any spelling is accepted']],
                        5 => ['text' => 'How many questions are there in total in the Listening test?', 
                              'options' => ['A' => '30', 'B' => '40', 'C' => '50', 'D' => '25']],
                        6 => ['text' => 'Section 3 of Listening typically features:', 
                              'options' => ['A' => 'Two speakers on a social topic', 'B' => 'A discussion between up to 4 people in an academic context', 'C' => 'A monologue on a general topic', 'D' => 'A lecture by one professor']],
                        7 => ['text' => 'You are given how much time to check answers after each section?', 
                              'options' => ['A' => '10 seconds', 'B' => '30 seconds', 'C' => '1 minute', 'D' => '2 minutes']],
                        8 => ['text' => 'Which accent is NOT used in IELTS Listening recordings?', 
                              'options' => ['A' => 'British', 'B' => 'Australian', 'C' => 'American', 'D' => 'All native-speaker accents are used']],
                        9 => ['text' => 'The most difficult section for most candidates is usually:', 
                              'options' => ['A' => 'Section 1', 'B' => 'Section 2', 'C' => 'Section 3', 'D' => 'Section 4']],
                        10 => ['text' => 'Answers in Listening can be written in:', 
                               'options' => ['A' => 'Capital letters only', 'B' => 'Lowercase only', 'C' => 'Either capital or lowercase', 'D' => 'Numbers only']],

                        // Reading 11-20
                        11 => ['text' => 'How many passages are there in the IELTS Academic Reading test?', 
                               'options' => ['A' => '2', 'B' => '3', 'C' => '4', 'D' => '5']],
                        12 => ['text' => 'Total time allowed for the entire Reading test?', 
                               'options' => ['A' => '40 minutes', 'B' => '60 minutes', 'C' => '75 minutes', 'D' => '90 minutes']],
                        13 => ['text' => 'IELTS Reading has how many questions in total?', 
                               'options' => ['A' => '30', 'B' => '40', 'C' => '50', 'D' => '60']],
                        14 => ['text' => 'The recommended time per passage is approximately:', 
                               'options' => ['A' => '15 minutes', 'B' => '20 minutes', 'C' => '25 minutes', 'D' => '30 minutes']],
                        15 => ['text' => 'General Training Reading is generally considered:', 
                               'options' => ['A' => 'Easier than Academic', 'B' => 'Harder than Academic', 'C' => 'The same difficulty', 'D' => 'Only for migration']],
                        16 => ['text' => 'Matching headings questions test your ability to:', 
                               'options' => ['A' => 'Understand main ideas', 'B' => 'Find specific details', 'C' => 'Guess vocabulary', 'D' => 'Identify opinions']],
                        17 => ['text' => 'In Academic Reading, the difficulty of passages:', 
                               'options' => ['A' => 'Increases with each passage', 'B' => 'Decreases with each passage', 'C' => 'Is random', 'D' => 'Is the same for all']],
                        18 => ['text' => 'True / False / Not Given questions appear in:', 
                               'options' => ['A' => 'Only Academic Reading', 'B' => 'Only General Training', 'C' => 'Both Academic and General Training', 'D' => 'Neither']],
                        19 => ['text' => 'The maximum number of words you can use in a summary question is usually:', 
                               'options' => ['A' => 'No more than 2 words', 'B' => 'No more than 3 words', 'C' => 'No more than 5 words', 'D' => 'No limit']],
                        20 => ['text' => 'Which skill is NOT directly tested in Reading?', 
                               'options' => ['A' => 'Skimming', 'B' => 'Scanning', 'C' => 'Grammar accuracy', 'D' => 'Inference']],

                        // Writing 21-30
                        21 => ['text' => 'IELTS Writing Task 1 (Academic) minimum word count is:', 
                               'options' => ['A' => '100 words', 'B' => '150 words', 'C' => '200 words', 'D' => '250 words']],
                        22 => ['text' => 'IELTS Writing Task 2 minimum word count is:', 
                               'options' => ['A' => '150 words', 'B' => '200 words', 'C' => '250 words', 'D' => '300 words']],
                        23 => ['text' => 'Total time for both Writing tasks:', 
                               'options' => ['A' => '40 minutes', 'B' => '60 minutes', 'C' => '75 minutes', 'D' => '90 minutes']],
                        24 => ['text' => 'Task 2 is worth what percentage of your Writing score?', 
                               'options' => ['A' => '33%', 'B' => '50%', 'C' => '66%', 'D' => '75%']],
                        25 => ['text' => 'The four criteria used to mark Writing are:', 
                               'options' => ['A' => 'Task Achievement, Coherence, Vocabulary, Grammar', 'B' => 'Content, Length, Style, Creativity', 'C' => 'Fluency, Accuracy, Complexity, Relevance', 'D' => 'Ideas, Examples, Linking, Spelling']],
                        26 => ['text' => 'In Task 1, you should NOT:', 
                               'options' => ['A' => 'Give your opinion', 'B' => 'Copy words from the question', 'C' => 'Use bullet points', 'D' => 'Include a conclusion']],
                        27 => ['text' => 'A band 9 in Writing requires:', 
                               'options' => ['A' => 'No errors at all', 'B' => 'Very few errors and sophisticated language', 'C' => 'Perfect handwriting', 'D' => 'Exactly 250 words']],
                        28 => ['text' => 'Which is NOT part of a good Task 2 essay structure?', 
                               'options' => ['A' => 'Introduction', 'B' => 'Body paragraphs', 'C' => 'Conclusion', 'D' => 'Personal story in every paragraph']],
                        29 => ['text' => 'You should spend approximately how long planning Task 2?', 
                               'options' => ['A' => '2 minutes', 'B' => '5 minutes', 'C' => '10 minutes', 'D' => '15 minutes']],
                        30 => ['text' => 'Coherence and Cohesion is worth how many marks out of 9?', 
                               'options' => ['A' => '9', 'B' => '6', 'C' => '3', 'D' => '4.5']],

                        // Speaking 31-40
                        31 => ['text' => 'Total duration of the IELTS Speaking test is:', 
                               'options' => ['A' => '8–10 minutes', 'B' => '11–14 minutes', 'C' => '15–18 minutes', 'D' => '20 minutes']],
                        32 => ['text' => 'How many parts does the Speaking test have?', 
                               'options' => ['A' => '2', 'B' => '3', 'C' => '4', 'D' => '5']],
                        33 => ['text' => 'Part 2 (Long Turn) lasts:', 
                               'options' => ['A' => '1 minute', 'B' => '2 minutes', 'C' => '3–4 minutes (including preparation)', 'D' => '5 minutes']],
                        34 => ['text' => 'In Part 1, the examiner asks questions about:', 
                               'options' => ['A' => 'Academic topics', 'B' => 'Familiar everyday topics', 'C' => 'Future plans', 'D' => 'Global issues']],
                        35 => ['text' => 'You are assessed on four criteria. Which one is NOT used?', 
                               'options' => ['A' => 'Fluency and Coherence', 'B' => 'Lexical Resource', 'C' => 'Grammatical Range and Accuracy', 'D' => 'Pronunciation', 'E' => 'Handwriting']],
                        36 => ['text' => 'The Speaking test is recorded because:', 
                               'options' => ['A' => 'To check the examiner', 'B' => 'For quality control and re-marking', 'C' => 'To help the candidate', 'D' => 'It is not recorded']],
                        37 => ['text' => 'A good strategy for Part 2 is to:', 
                               'options' => ['A' => 'Speak for only 30 seconds', 'B' => 'Use the 1-minute preparation time to make notes', 'C' => 'Ask the examiner questions', 'D' => 'Repeat the prompt']],
                        38 => ['text' => 'Band 7 in Fluency requires:', 
                               'options' => ['A' => 'No hesitation at all', 'B' => 'Natural hesitation with good recovery', 'C' => 'Very slow speech', 'D' => 'Only short answers']],
                        39 => ['text' => 'The examiner in Part 3 will:', 
                               'options' => ['A' => 'Ask personal questions', 'B' => 'Discuss abstract ideas related to Part 2', 'C' => 'Correct your grammar', 'D' => 'Give you the topic']],
                        40 => ['text' => 'You should speak in:', 
                               'options' => ['A' => 'Only formal English', 'B' => 'Natural, fluent English (not too formal or too casual)', 'C' => 'Only British English', 'D' => 'As fast as possible']]
                    ];
                    ?>

                    <?php foreach ($questions as $q => $data): ?>
                        <?php 
                        $partTitle = '';
                        if ($q == 1) $partTitle = 'Part 1: Listening Knowledge (Questions 1–10)';
                        elseif ($q == 11) $partTitle = 'Part 2: Reading Knowledge (Questions 11–20)';
                        elseif ($q == 21) $partTitle = 'Part 3: Writing Knowledge (Questions 21–30)';
                        elseif ($q == 31) $partTitle = 'Part 4: Speaking Knowledge (Questions 31–40)';
                        if ($partTitle) echo "<h4 class='mb-4 mt-5 text-primary'>$partTitle</h4>";
                        ?>
                        <div class="question-card">
                            <p><strong><?= $q ?>. <?= htmlspecialchars($data['text']) ?></strong></p>
                            <div class="mt-3">
                                <?php foreach ($data['options'] as $letter => $text): ?>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="q<?= $q ?>" value="<?= $letter ?>" id="q<?= $q ?><?= $letter ?>">
                                    <label class="form-check-label" for="q<?= $q ?><?= $letter ?>">
                                        <strong><?= $letter ?>.</strong> <?= htmlspecialchars($text) ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </form>

                <div class="d-flex gap-3 mt-4">
                    <button onclick="submitTest()" class="btn btn-success btn-lg px-5">
                        <i class="bi bi-check-circle"></i> Submit Test
                    </button>
                    <button onclick="resetTest()" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset All Answers
                    </button>
                </div>

                <!-- Results -->
                <div id="results" class="results mt-5" style="display: none;">
                    <h3 class="text-center mb-4">Test Complete!</h3>
                    <h4 class="text-center">Your Score: <span id="scoreDisplay" class="text-success fw-bold"></span></h4>
                    <div id="feedback" class="mt-4"></div>
                    <div class="text-center mt-4">
                        <button onclick="location.reload()" class="btn btn-primary">Take Test Again</button>
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
        let timeLeft = 1200;
        let timerInterval = null;

        function startTimer() {
            const timerEl = document.getElementById('timer');
            const timerBox = document.getElementById('timerBox');

            timerInterval = setInterval(() => {
                timeLeft--;
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timerEl.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

                if (timeLeft <= 300) {
                    timerBox.style.background = 'linear-gradient(135deg, #ffc107, #ff9800)';
                }

                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    timerEl.textContent = "00:00";
                    autoSubmitTest();
                }
            }, 1000);
        }

        function autoSubmitTest() {
            alert("⏰ Time's up! Your test has been submitted automatically.");
            submitTest(true);
        }

        const correctAnswers = {
            q1: "B", q2: "B", q3: "B", q4: "B", q5: "B", q6: "B", q7: "B", q8: "D", q9: "D", q10: "C",
            q11: "B", q12: "B", q13: "B", q14: "B", q15: "A", q16: "A", q17: "A", q18: "C", q19: "B", q20: "C",
            q21: "B", q22: "C", q23: "B", q24: "C", q25: "A", q26: "B", q27: "B", q28: "D", q29: "B", q30: "A",
            q31: "B", q32: "B", q33: "C", q34: "B", q35: "E", q36: "B", q37: "B", q38: "B", q39: "B", q40: "B"
        };

        function submitTest(isAuto = false) {
            if (timerInterval) clearInterval(timerInterval);

            let score = 0;
            let feedbackHTML = `<h5 class="mb-3">${isAuto ? '⏰ Time\'s Up – Here is your result:' : 'Your Results:'}</h5>`;

            for (let i = 1; i <= 40; i++) {
                const selected = document.querySelector(`input[name="q${i}"]:checked`);
                const userAnswer = selected ? selected.value : null;
                const correct = correctAnswers[`q${i}`] ?? 'N/A';

                const isCorrect = userAnswer === correct;

                if (isCorrect) score++;

                feedbackHTML += `
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <div><strong>Q${i}:</strong> ${userAnswer ? userAnswer : '<span class="text-muted">(not answered)</span>'}</div>
                        <div>
                            ${isCorrect 
                                ? '<span class="text-success">✓ Correct</span>' 
                                : `<span class="text-danger">✗</span> <small class="text-muted">(Correct: ${correct})</small>`
                            }
                        </div>
                    </div>`;
            }

            const percentage = Math.round((score / 40) * 100);
            document.getElementById('scoreDisplay').innerHTML = `${score}/40 <span class="text-muted">(${percentage}%)</span>`;
            document.getElementById('feedback').innerHTML = feedbackHTML;
            document.getElementById('results').style.display = 'block';
            document.getElementById('results').scrollIntoView({ behavior: 'smooth' });
        }

        function resetTest() {
            if (confirm("Reset all answers and restart the timer?")) {
                document.getElementById('testForm').reset();
                document.getElementById('results').style.display = 'none';
                timeLeft = 1200;
                document.getElementById('timer').textContent = "20:00";
                document.getElementById('timerBox').style.background = 'linear-gradient(135deg, #dc3545, #f56b6b)';
                startTimer();
            }
        }

        window.onload = function() {
            startTimer();
        };
    </script>
</body>
</html>