<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../registration.php?message=Please+login+to+access+resources");
    exit();
}

// ——————— AJAX HANDLER (MUST BE BEFORE ANY HTML) ———————
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);

    if (!$data || !isset($data['action'])) {
        $data = $_POST;
    }

    if (isset($data['action']) && $data['action'] === 'submit_quiz') {
        header('Content-Type: application/json');

        try {
            $quizAnswers = isset($data['answers']) ? $data['answers'] : [];
            
            // Answer key for 30 questions
            $answerKey = [
                1 => 'B',
                2 => 'C',
                3 => 'A',
                4 => 'B',
                5 => 'D',
                6 => 'A',
                7 => 'C',
                8 => 'B',
                9 => 'A',
                10 => 'D',
                11 => 'B',
                12 => 'C',
                13 => 'A',
                14 => 'B',
                15 => 'D',
                16 => 'C',
                17 => 'A',
                18 => 'B',
                19 => 'C',
                20 => 'A',
                21 => 'D',
                22 => 'B',
                23 => 'C',
                24 => 'A',
                25 => 'B',
                26 => 'D',
                27 => 'C',
                28 => 'A',
                29 => 'B',
                30 => 'D'
            ];

            $correct = 0;
            $total = count($answerKey);
            $detailedResults = [];

            foreach ($answerKey as $qNum => $correctAnswer) {
                $userAnswer = isset($quizAnswers[$qNum]) ? $quizAnswers[$qNum] : null;
                $isCorrect = $userAnswer === $correctAnswer;
                
                if ($isCorrect) {
                    $correct++;
                }
                
                $detailedResults[$qNum] = [
                    'userAnswer' => $userAnswer,
                    'correctAnswer' => $correctAnswer,
                    'isCorrect' => $isCorrect
                ];
            }

            $score = round(($correct / $total) * 100);
            $passed = $score >= 70;

            echo json_encode([
                'status' => 'success',
                'correct' => $correct,
                'total' => $total,
                'score' => $score,
                'passed' => $passed,
                'detailedResults' => $detailedResults
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
	<title>Maps Quiz Master | EduHub</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
	<?php include INCLUDES_PATH . '/navbar_styles.php'; ?>

	<style>
		body {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			min-height: 100vh;
			overflow-x: hidden;
		}

		.main-wrapper {
			padding: 2rem 1rem;
		}

		.quiz-container {
			max-width: 900px;
			margin: 0 auto;
			background: white;
			border-radius: 12px;
			box-shadow: 0 10px 40px rgba(0,0,0,0.2);
			overflow: hidden;
		}

		.quiz-header {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			color: white;
			padding: 2rem;
			text-align: center;
		}

		.quiz-header h1 {
			font-size: 2rem;
			font-weight: 800;
			margin: 0;
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 0.5rem;
		}

		.quiz-header p {
			margin: 0.5rem 0 0 0;
			opacity: 0.95;
			font-size: 0.95rem;
		}

		.quiz-content {
			padding: 2rem;
			max-height: calc(100vh - 300px);
			overflow-y: auto;
		}

		.question-block {
			margin-bottom: 2rem;
			padding: 1.5rem;
			background: #f8f9fa;
			border-left: 4px solid #667eea;
			border-radius: 8px;
		}

		.question-number {
			font-weight: 700;
			color: #667eea;
			font-size: 0.9rem;
			margin-bottom: 0.5rem;
		}

		.question-text {
			font-size: 1rem;
			font-weight: 600;
			color: #1f2937;
			margin-bottom: 1rem;
			line-height: 1.5;
		}

		.question-image {
			max-width: 100%;
			height: auto;
			margin: 1rem 0;
			border-radius: 8px;
			border: 2px solid #ddd;
		}

		.options-container {
			display: flex;
			flex-direction: column;
			gap: 0.75rem;
		}

		.option {
			padding: 0.75rem 1rem;
			background: white;
			border: 2px solid #ddd;
			border-radius: 6px;
			cursor: pointer;
			transition: all 0.3s ease;
			display: flex;
			align-items: center;
			gap: 0.75rem;
		}

		.option:hover {
			border-color: #667eea;
			background: #f0f4ff;
		}

		.option input[type="radio"] {
			cursor: pointer;
			width: 18px;
			height: 18px;
			accent-color: #667eea;
		}

		.option label {
			cursor: pointer;
			margin: 0;
			flex: 1;
		}

		.option.selected {
			background: #e8eaf6;
			border-color: #667eea;
		}

		.quiz-footer {
			padding: 2rem;
			background: #f8f9fa;
			border-top: 1px solid #ddd;
			display: flex;
			gap: 1rem;
			justify-content: center;
		}

		button {
			background: #667eea;
			color: white;
			border: none;
			padding: 0.75rem 2rem;
			border-radius: 6px;
			cursor: pointer;
			font-size: 1rem;
			font-weight: 600;
			transition: all 0.3s ease;
			display: flex;
			align-items: center;
			gap: 0.5rem;
		}

		button:hover {
			background: #764ba2;
			transform: translateY(-2px);
		}

		button:disabled {
			opacity: 0.5;
			cursor: not-allowed;
			transform: none;
		}

		.progress-info {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 1.5rem;
			padding: 1rem;
			background: #e8eaf6;
			border-radius: 6px;
		}

		.progress-bar-container {
			flex: 1;
			height: 10px;
			background: #ddd;
			border-radius: 10px;
			margin: 0 1rem;
			overflow: hidden;
		}

		.progress-bar-fill {
			height: 100%;
			background: linear-gradient(90deg, #667eea, #764ba2);
			transition: width 0.5s ease;
		}

		/* Results Modal */
		.results-modal {
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: rgba(0,0,0,0.7);
			display: none;
			align-items: center;
			justify-content: center;
			z-index: 9999;
		}

		.results-modal.show {
			display: flex;
		}

		.results-content {
			background: white;
			padding: 2rem;
			border-radius: 12px;
			max-width: 600px;
			max-height: 80vh;
			overflow-y: auto;
			animation: slideIn 0.3s ease-out;
		}

		@keyframes slideIn {
			from { transform: translateY(-50px); opacity: 0; }
			to { transform: translateY(0); opacity: 1; }
		}

		.results-header {
			text-align: center;
			margin-bottom: 2rem;
		}

		.results-score {
			font-size: 4rem;
			font-weight: 800;
			color: #667eea;
			margin: 1rem 0;
		}

		.results-status {
			font-size: 1.5rem;
			font-weight: 700;
			margin: 1rem 0;
		}

		.results-status.passed {
			color: #16a34a;
		}

		.results-status.failed {
			color: #dc2626;
		}

		.results-summary {
			background: #f8f9fa;
			padding: 1rem;
			border-radius: 8px;
			margin-bottom: 1rem;
			text-align: center;
		}

		.results-breakdown {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 1rem;
			margin-bottom: 1.5rem;
		}

		.breakdown-item {
			padding: 1rem;
			background: #f0f4ff;
			border-radius: 8px;
			text-align: center;
		}

		.breakdown-value {
			font-size: 1.8rem;
			font-weight: 800;
			color: #667eea;
		}

		.breakdown-label {
			font-size: 0.85rem;
			color: #666;
			margin-top: 0.3rem;
		}

		.close-btn {
			background: #64748b;
		}

		.close-btn:hover {
			background: #475569;
		}

		@media (max-width: 768px) {
			.quiz-header h1 {
				font-size: 1.5rem;
			}

			.quiz-content {
				padding: 1rem;
			}

			.question-block {
				padding: 1rem;
			}

			.quiz-footer {
				flex-direction: column;
			}

			button {
				width: 100%;
				justify-content: center;
			}
		}
	</style>
</head>
<body>
	<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
	<div class="mobile-overlay" id="mobileOverlay"></div>
	<?php include INCLUDES_PATH . '/navbar.php'; ?>

	<main class="main-wrapper">
		<div class="quiz-container">
			<div class="quiz-header">
				<h1><i class="bi bi-map"></i> Maps Quiz Master</h1>
				<p>Test your IELTS Task 1 maps knowledge - 30 challenging questions</p>
			</div>

			<div class="quiz-content" id="quizContent">
				<div class="progress-info">
					<span style="font-weight: 600; font-size: 0.9rem;">Progress</span>
					<div class="progress-bar-container">
						<div class="progress-bar-fill" id="progressBar" style="width: 0%"></div>
					</div>
					<span style="font-weight: 600; color: #667eea;" id="progressText">0/30</span>
				</div>

				<form id="quizForm">
					<!-- Questions will be populated here -->
				</form>
			</div>

			<div class="quiz-footer">
				<button type="button" onclick="resetQuiz()"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
				<button type="button" onclick="submitQuiz()" id="submitBtn"><i class="bi bi-check-circle"></i> Submit Quiz</button>
			</div>
		</div>
	</main>

	<div class="results-modal" id="resultsModal">
		<div class="results-content">
			<div class="results-header">
				<h2><i class="bi bi-trophy-fill"></i> Quiz Results</h2>
				<div class="results-score" id="finalScore">0%</div>
				<div class="results-status" id="resultStatus">Try Again!</div>
			</div>

			<div class="results-summary">
				<p style="margin: 0; font-size: 0.95rem;">You have completed the Maps Quiz Master</p>
			</div>

			<div class="results-breakdown">
				<div class="breakdown-item">
					<div class="breakdown-value" id="correctCount">0</div>
					<div class="breakdown-label">Correct Answers</div>
				</div>
				<div class="breakdown-item">
					<div class="breakdown-value" id="totalCount">30</div>
					<div class="breakdown-label">Total Questions</div>
				</div>
			</div>

			<button onclick="closeResultsModal()" class="close-btn" style="width: 100%; justify-content: center;"><i class="bi bi-x-circle"></i> Close</button>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>

	<script>
		const quizData = [
			{
				id: 1,
				question: "What is the minimum word count requirement for IELTS Task 1?",
				options: {
					A: "100 words",
					B: "150 words",
					C: "200 words",
					D: "250 words"
				},
				correct: "B",
				category: "Requirements"
			},
			{
				id: 2,
				question: "Which of the following is NOT a type of map question in IELTS Task 1?",
				options: {
					A: "Before and After maps",
					B: "Comparative maps",
					C: "Personal opinion maps",
					D: "Single location maps"
				},
				correct: "C",
				category: "Types"
			},
			{
				id: 3,
				question: "When describing a change from 1990 to 2020, which tense should you primarily use?",
				options: {
					A: "Past tense",
					B: "Present tense",
					C: "Future tense",
					D: "Present perfect"
				},
				correct: "A",
				category: "Grammar"
			},
			{
				id: 4,
				question: "What is the recommended time allocation for Task 1 out of 60 minutes?",
				options: {
					A: "10 minutes",
					B: "20 minutes",
					C: "30 minutes",
					D: "40 minutes"
				},
				correct: "B",
				category: "Time Management"
			},
			{
				id: 5,
				question: "Which phrase is used to show location in the north-eastern part?",
				options: {
					A: "In the west of",
					B: "In the middle of",
					C: "Along the main road",
					D: "In the northeast / To the northeast of"
				},
				correct: "D",
				category: "Vocabulary"
			},
			{
				id: 6,
				question: "What should the overview paragraph contain?",
				options: {
					A: "Most significant changes and general trends",
					B: "All minor details",
					C: "Personal opinions",
					D: "Specific measurements only"
				},
				correct: "A",
				category: "Structure"
			},
			{
				id: 7,
				question: "Which word means the opposite of 'constructed'?",
				options: {
					A: "Expanded",
					B: "Built",
					C: "Demolished",
					D: "Developed"
				},
				correct: "C",
				category: "Vocabulary"
			},
			{
				id: 8,
				question: "How many body paragraphs should a map description typically have?",
				options: {
					A: "One",
					B: "Two",
					C: "Three",
					D: "Four or more"
				},
				correct: "B",
				category: "Structure"
			},
			{
				id: 9,
				question: "Which passive voice construction is most appropriate for map descriptions?",
				options: {
					A: "was built, has been replaced, will be developed",
					B: "builds, replaces, develops",
					C: "building, replacing, developing",
					D: "I built, I replaced, I developed"
				},
				correct: "A",
				category: "Grammar"
			},
			{
				id: 10,
				question: "What organizing approach divides body paragraphs by location?",
				options: {
					A: "Type of Change organization",
					B: "Major vs Minor organization",
					C: "Geographical Division",
					D: "Chronological organization"
				},
				correct: "C",
				category: "Organization"
			},
			{
				id: 11,
				question: "Which cohesive device is best for adding similar information?",
				options: {
					A: "However",
					B: "Furthermore, Moreover, Additionally",
					C: "In contrast",
					D: "Initially"
				},
				correct: "B",
				category: "Cohesion"
			},
			{
				id: 12,
				question: "What should you do in the introduction of a map essay?",
				options: {
					A: "Provide all specific details",
					B: "Paraphrase the question and provide an overview",
					C: "State your personal opinion",
					D: "Describe minor changes only"
				},
				correct: "B",
				category: "Structure"
			},
			{
				id: 13,
				question: "Which is an appropriate paraphrase for 'The maps show the village of Stokeford in 1930 and 2010'?",
				options: {
					A: "The two maps illustrate how the village of Stokeford changed over an 80-year period",
					B: "Stokeford is a village",
					C: "I like this village",
					D: "The village should have changed"
				},
				correct: "A",
				category: "Paraphrasing"
			},
			{
				id: 14,
				question: "When should you use 'To Whom It May Concern'?",
				options: {
					A: "Always",
					B: "When describing a specific map location",
					C: "In the overview paragraph",
					D: "For future plans maps"
				},
				correct: "B",
				category: "Conventions"
			},
			{
				id: 15,
				question: "What is a common mistake to avoid when writing map descriptions?",
				options: {
					A: "Using location vocabulary",
					B: "Including specific details",
					C: "Expressing personal opinions about the changes",
					D: "Using passive voice"
				},
				correct: "C",
				category: "Common Mistakes"
			},
			{
				id: 16,
				question: "Which word best describes converting farmland into houses?",
				options: {
					A: "Reduced",
					B: "Demolished",
					C: "Transformed into / Converted to",
					D: "Extended"
				},
				correct: "C",
				category: "Vocabulary"
			},
			{
				id: 17,
				question: "What does 'adjacent to' mean?",
				options: {
					A: "Next to / Beside",
					B: "Far from",
					C: "Inside",
					D: "Opposite direction"
				},
				correct: "A",
				category: "Vocabulary"
			},
			{
				id: 18,
				question: "For future development maps (present to future), which tense should be used?",
				options: {
					A: "Past tense",
					B: "Future tense (will be, will be constructed)",
					C: "Present tense",
					D: "Past perfect"
				},
				correct: "B",
				category: "Grammar"
			},
			{
				id: 19,
				question: "Which transition word is best for showing contrast between areas?",
				options: {
					A: "Furthermore",
					B: "Initially",
					C: "In contrast, However, Whereas",
					D: "Additionally"
				},
				correct: "C",
				category: "Cohesion"
			},
			{
				id: 20,
				question: "What should you NOT include in the overview?",
				options: {
					A: "General trends",
					B: "Significant changes",
					C: "Specific building details and addresses",
					D: "Summary of developments"
				},
				correct: "C",
				category: "Structure"
			},
			{
				id: 21,
				question: "When describing a location, which preposition indicates 'surrounding'?",
				options: {
					A: "Near",
					B: "Close to",
					C: "Surrounded by",
					D: "Facing"
				},
				correct: "C",
				category: "Vocabulary"
			},
			{
				id: 22,
				question: "What word means to make something bigger or larger?",
				options: {
					A: "Demolished",
					B: "Extended, Enlarged, Expanded",
					C: "Converted",
					D: "Removed"
				},
				correct: "B",
				category: "Vocabulary"
			},
			{
				id: 23,
				question: "Which approach organizes body paragraphs by types of changes (residential, commercial, etc.)?",
				options: {
					A: "Geographical Division",
					B: "Chronological organization",
					C: "Type of Change organization",
					D: "Random organization"
				},
				correct: "C",
				category: "Organization"
			},
			{
				id: 24,
				question: "What is the recommended word count range to aim for?",
				options: {
					A: "170-190 words",
					B: "150-160 words",
					C: "200-250 words",
					D: "100-120 words"
				},
				correct: "A",
				category: "Requirements"
			},
			{
				id: 25,
				question: "Which phrase indicates direction running from north to south?",
				options: {
					A: "In the center",
					B: "Running from north to south, extending northward",
					C: "Close to",
					D: "Between X and Y"
				},
				correct: "B",
				category: "Vocabulary"
			},
			{
				id: 26,
				question: "What does 'knocked down' mean?",
				options: {
					A: "Built",
					B: "Expanded",
					C: "Demolished, Removed",
					D: "Constructed"
				},
				correct: "C",
				category: "Vocabulary"
			},
			{
				id: 27,
				question: "Which of these is appropriate for starting a sequential description?",
				options: {
					A: "In my opinion",
					B: "I think that",
					C: "Initially, Subsequently, Following this",
					D: "You should know"
				},
				correct: "C",
				category: "Cohesion"
			},
			{
				id: 28,
				question: "How much time should be spent on planning before writing?",
				options: {
					A: "1 minute",
					B: "3 minutes",
					C: "5 minutes",
					D: "10 minutes"
				},
				correct: "B",
				category: "Time Management"
			},
			{
				id: 29,
				question: "What should you verify during the final check?",
				options: {
					A: "Only word count",
					B: "Word count, tense consistency, and all major changes mentioned",
					C: "Only grammar",
					D: "Your personal feelings about the map"
				},
				correct: "B",
				category: "Final Checks"
			},
			{
				id: 30,
				question: "Which organizing principle divides changes into significant vs minor?",
				options: {
					A: "Geographical Division",
					B: "Type of Change",
					C: "Chronological",
					D: "Major vs Minor Changes organization"
				},
				correct: "D",
				category: "Organization"
			}
		];

		let currentQuestion = 0;
		let userAnswers = {};

		function renderQuiz() {
			const form = document.getElementById('quizForm');
			form.innerHTML = '';

			quizData.forEach((q, index) => {
				const questionDiv = document.createElement('div');
				questionDiv.className = 'question-block';
				questionDiv.id = `question-${q.id}`;

				let questionHTML = `
					<div class="question-number">Question ${q.id} / ${quizData.length}</div>
					<div class="question-text">${q.question}</div>
				`;

				if (q.imageUrl) {
					questionHTML += `<img src="${q.imageUrl}" alt="Question image" class="question-image">`;
				}

				questionHTML += '<div class="options-container">';

				Object.entries(q.options).forEach(([key, value]) => {
					const optionId = `q${q.id}_${key}`;
					questionHTML += `
						<label class="option">
							<input type="radio" name="question_${q.id}" value="${key}" id="${optionId}" onchange="selectOption(${q.id}, '${key}')">
							<label for="${optionId}">${key}: ${value}</label>
						</label>
					`;
				});

				questionHTML += '</div>';
				questionDiv.innerHTML = questionHTML;
				form.appendChild(questionDiv);
			});
		}

		function selectOption(questionId, optionValue) {
			userAnswers[questionId] = optionValue;
			updateProgress();

			// Update UI
			const block = document.getElementById(`question-${questionId}`);
			const labels = block.querySelectorAll('.option');
			labels.forEach(label => label.classList.remove('selected'));
			
			const selectedInput = block.querySelector(`input[value="${optionValue}"]`);
			if (selectedInput) {
				selectedInput.closest('.option').classList.add('selected');
			}
		}

		function updateProgress() {
			const answered = Object.keys(userAnswers).length;
			const total = quizData.length;
			const percentage = (answered / total) * 100;
			document.getElementById('progressBar').style.width = percentage + '%';
			document.getElementById('progressText').textContent = `${answered}/${total}`;
		}

		function submitQuiz() {
			const answered = Object.keys(userAnswers).length;
			if (answered < quizData.length) {
				alert(`Please answer all questions. You've answered ${answered} out of ${quizData.length}.`);
				return;
			}

			document.getElementById('submitBtn').disabled = true;

			fetch('', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
				body: JSON.stringify({
					action: 'submit_quiz',
					answers: userAnswers
				})
			})
			.then(response => response.json())
			.then(data => {
				if (data.status === 'success') {
					showResults(data);
				} else {
					alert('Error: ' + data.message);
				}
			})
			.catch(err => {
				console.error('Error:', err);
				alert('Error submitting quiz');
			})
			.finally(() => {
				document.getElementById('submitBtn').disabled = false;
			});
		}

		function showResults(data) {
			document.getElementById('finalScore').textContent = data.score + '%';
			document.getElementById('correctCount').textContent = data.correct;
			document.getElementById('totalCount').textContent = data.total;
			
			const statusEl = document.getElementById('resultStatus');
			if (data.passed) {
				statusEl.textContent = '🎉 Congratulations! You Passed!';
				statusEl.className = 'results-status passed';
			} else {
				statusEl.textContent = '📚 Keep Practicing!';
				statusEl.className = 'results-status failed';
			}

			document.getElementById('resultsModal').classList.add('show');
		}

		function closeResultsModal() {
			document.getElementById('resultsModal').classList.remove('show');
		}

		function resetQuiz() {
			if (confirm('Are you sure you want to reset the quiz?')) {
				userAnswers = {};
				currentQuestion = 0;
				document.getElementById('submitBtn').disabled = false;
				document.querySelectorAll('input[type="radio"]').forEach(input => {
					input.checked = false;
				});
				document.querySelectorAll('.option').forEach(opt => {
					opt.classList.remove('selected');
				});
				updateProgress();
			}
		}

		// Initialize
		renderQuiz();
		updateProgress();
	</script>
</body>
</html>