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
	<title>Process Description Quiz Master | EduHub</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
	<?php include INCLUDES_PATH . '/navbar_styles.php'; ?>

	<style>
		body {
			background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
			background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
			border-left: 4px solid #f5576c;
			border-radius: 8px;
		}

		.question-number {
			font-weight: 700;
			color: #f5576c;
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
			border-color: #f5576c;
			background: #fff3e0;
		}

		.option input[type="radio"] {
			cursor: pointer;
			width: 18px;
			height: 18px;
			accent-color: #f5576c;
		}

		.option label {
			cursor: pointer;
			margin: 0;
			flex: 1;
		}

		.option.selected {
			background: #ffe0b2;
			border-color: #f5576c;
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
			background: #f5576c;
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
			background: #f093fb;
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
			background: #ffe0b2;
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
			background: linear-gradient(90deg, #f093fb, #f5576c);
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
			color: #f5576c;
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
			background: #fff3e0;
			border-radius: 8px;
			text-align: center;
		}

		.breakdown-value {
			font-size: 1.8rem;
			font-weight: 800;
			color: #f5576c;
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

		.review-section {
			margin-top: 2rem;
			border-top: 2px solid #ddd;
			padding-top: 1.5rem;
			max-height: 400px;
			overflow-y: auto;
		}

		.review-question {
			margin-bottom: 1.5rem;
			padding: 1rem;
			background: #f8f9fa;
			border-left: 4px solid #f5576c;
			border-radius: 6px;
		}

		.review-question.correct {
			background: #d4edda;
			border-color: #28a745;
		}

		.review-question.incorrect {
			background: #f8d7da;
			border-color: #dc3545;
		}

		.review-header {
			display: flex;
			align-items: center;
			gap: 0.5rem;
			margin-bottom: 0.75rem;
			font-weight: 600;
		}

		.review-header.correct {
			color: #28a745;
		}

		.review-header.incorrect {
			color: #dc3545;
		}

		.review-qnum {
			font-weight: 700;
			color: #f5576c;
		}

		.review-answer {
			margin: 0.5rem 0;
			font-size: 0.95rem;
			line-height: 1.5;
		}

		.review-answer.user-answer {
			padding: 0.5rem;
			background: white;
			border-radius: 4px;
			margin: 0.5rem 0;
		}

		.review-answer.correct-answer {
			padding: 0.5rem;
			background: #d4edda;
			border-left: 3px solid #28a745;
			border-radius: 4px;
			margin: 0.5rem 0;
		}

		.answer-label {
			font-weight: 600;
			margin-right: 0.5rem;
		}

		.answer-icon {
			margin-right: 0.5rem;
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
				<h1><i class="bi bi-diagram-3"></i> Process Description Quiz Master</h1>
				<p>Test your IELTS Task 1 process knowledge - 30 challenging questions</p>
			</div>

			<div class="quiz-content" id="quizContent">
				<div class="progress-info">
					<span style="font-weight: 600; font-size: 0.9rem;">Progress</span>
					<div class="progress-bar-container">
						<div class="progress-bar-fill" id="progressBar" style="width: 0%"></div>
					</div>
					<span style="font-weight: 600; color: #f5576c;" id="progressText">0/30</span>
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
				<p style="margin: 0; font-size: 0.95rem;">You have completed the Process Description Quiz Master</p>
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

			<div class="review-section" id="reviewSection">
				<!-- Review answers will be populated here -->
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
				question: "What is the primary focus of process description questions in IELTS Task 1?",
				options: {
					A: "Describing personal experiences",
					B: "Describing stages involved in making something or how something works",
					C: "Expressing personal opinions",
					D: "Comparing two different activities"
				},
				correct: "B",
				category: "Concept"
			},
			{
				id: 2,
				question: "Which of the following is NOT a type of process diagram?",
				options: {
					A: "Manufacturing processes",
					B: "Natural processes",
					C: "Opinion-based processes",
					D: "Man-made systems"
				},
				correct: "C",
				category: "Types"
			},
			{
				id: 3,
				question: "What tense should be used in process descriptions?",
				options: {
					A: "Present simple",
					B: "Past tense",
					C: "Future tense",
					D: "Present perfect"
				},
				correct: "A",
				category: "Grammar"
			},
			{
				id: 4,
				question: "What is the recommended word count for Task 1?",
				options: {
					A: "100-120 words",
					B: "At least 150 words (aim for 170-190)",
					C: "200+ words minimum",
					D: "300+ words"
				},
				correct: "B",
				category: "Requirements"
			},
			{
				id: 5,
				question: "Which of the following is a characteristic of process descriptions?",
				options: {
					A: "Spatial organization",
					B: "Personal opinions",
					C: "Sequential order of stages",
					D: "Chronological history"
				},
				correct: "C",
				category: "Structure"
			},
			{
				id: 6,
				question: "What should the introduction of a process essay contain?",
				options: {
					A: "Paraphrase what the diagram shows and state the number of stages",
					B: "Personal experiences",
					C: "A detailed description of the first stage",
					D: "Your opinion on the process"
				},
				correct: "A",
				category: "Structure"
			},
			{
				id: 7,
				question: "Which phrase is appropriate for starting a process description?",
				options: {
					A: "I think that...",
					B: "In my opinion...",
					C: "The diagram illustrates/depicts the process of...",
					D: "From my perspective..."
				},
				correct: "C",
				category: "Vocabulary"
			},
			{
				id: 8,
				question: "How many stages should typically be in the overview paragraph?",
				options: {
					A: "Just mention 'several stages'",
					B: "State the total number of stages and starting/ending points",
					C: "Describe all stages in detail",
					D: "Mention only the most important stage"
				},
				correct: "B",
				category: "Structure"
			},
			{
				id: 9,
				question: "Which voice is preferred in process descriptions?",
				options: {
					A: "Passive voice (The beans are roasted)",
					B: "Active voice (Workers roast the beans)",
					C: "First person (I think...)",
					D: "Questions (Why are the beans roasted?)"
				},
				correct: "A",
				category: "Grammar"
			},
			{
				id: 10,
				question: "What is the advantage of using passive voice?",
				options: {
					A: "It emphasizes the person doing the action",
					B: "It focuses on what happens rather than who does it",
					C: "It is shorter and easier to write",
					D: "It sounds more informal"
				},
				correct: "B",
				category: "Grammar"
			},
			{
				id: 11,
				question: "Which sequencing word is best for beginning a process?",
				options: {
					A: "Eventually",
					B: "First, Initially, To begin with, The process begins/starts with",
					C: "Finally",
					D: "Meanwhile"
				},
				correct: "B",
				category: "Vocabulary"
			},
			{
				id: 12,
				question: "Which phrase indicates the final stage of a process?",
				options: {
					A: "Meanwhile",
					B: "Subsequently",
					C: "Finally, Lastly, In the final stage, Ultimately, Eventually",
					D: "Next"
				},
				correct: "C",
				category: "Vocabulary"
			},
			{
				id: 13,
				question: "What does 'simultaneously' mean in process descriptions?",
				options: {
					A: "At the same time, occurring together",
					B: "One after another",
					C: "Finally and completely",
					D: "In a different location"
				},
				correct: "A",
				category: "Vocabulary"
			},
			{
				id: 14,
				question: "Which is the most appropriate passive construction?",
				options: {
					A: "The beans roast in the sun",
					B: "The beans are roasted at 350°C",
					C: "I roast the beans",
					D: "The beans will be roasting"
				},
				correct: "B",
				category: "Grammar"
			},
			{
				id: 15,
				question: "What is the purpose of describing equipment in a process?",
				options: {
					A: "To show your knowledge",
					B: "To provide technical details about how the process works",
					C: "To make the essay longer",
					D: "To express your opinion"
				},
				correct: "B",
				category: "Content"
			},
			{
				id: 16,
				question: "Which verb is used for mixing ingredients?",
				options: {
					A: "Extract",
					B: "Compress",
					C: "Mix, Blend, Combine",
					D: "Filter"
				},
				correct: "C",
				category: "Vocabulary"
			},
			{
				id: 17,
				question: "What does 'extract' mean in process descriptions?",
				options: {
					A: "To remove or obtain a substance from a mixture",
					B: "To increase the temperature",
					C: "To pour into a container",
					D: "To cool down"
				},
				correct: "A",
				category: "Vocabulary"
			},
			{
				id: 18,
				question: "Which phrase shows causality (cause and effect)?",
				options: {
					A: "Meanwhile",
					B: "Subsequently",
					C: "So that, In order to, To",
					D: "While"
				},
				correct: "C",
				category: "Cohesion"
			},
			{
				id: 19,
				question: "Which transition is best for connecting sequential stages?",
				options: {
					A: "Moreover",
					B: "After that, Subsequently, Following this, At the next stage",
					C: "In contrast",
					D: "Similarly"
				},
				correct: "B",
				category: "Cohesion"
			},
			{
				id: 20,
				question: "What should be avoided in a process description?",
				options: {
					A: "Technical vocabulary",
					B: "Passive voice",
					C: "Personal opinions and subjective comments",
					D: "Sequential ordering"
				},
				correct: "C",
				category: "Common Mistakes"
			},
			{
				id: 21,
				question: "How should you connect stages smoothly?",
				options: {
					A: "By stating 'and then' repeatedly",
					B: "By using purpose clauses like 'in order to' or 'so that'",
					C: "By writing 'next' before each stage",
					D: "By avoiding any transitions"
				},
				correct: "B",
				category: "Cohesion"
			},
			{
				id: 22,
				question: "Which of these is a manufacturing process?",
				options: {
					A: "The water cycle",
					B: "How chocolate is made",
					C: "A plant's life cycle",
					D: "Seasonal changes"
				},
				correct: "B",
				category: "Types"
			},
			{
				id: 23,
				question: "What is a natural process example?",
				options: {
					A: "Making cement",
					B: "Producing electricity",
					C: "The life cycle of a butterfly",
					D: "Manufacturing paper"
				},
				correct: "C",
				category: "Types"
			},
			{
				id: 24,
				question: "How many body paragraphs should a process essay typically have?",
				options: {
					A: "One long paragraph",
					B: "Two paragraphs describing stages",
					C: "Three paragraphs minimum",
					D: "Four or more"
				},
				correct: "B",
				category: "Structure"
			},
			{
				id: 25,
				question: "Which sentence uses correct passive voice appropriately?",
				options: {
					A: "The workers are mixing the flour and water together",
					B: "The flour and water are mixed together",
					C: "They mix flour and water together",
					D: "We should mix the flour with water"
				},
				correct: "B",
				category: "Grammar"
			},
			{
				id: 26,
				question: "What does 'ferment' mean?",
				options: {
					A: "To freeze completely",
					B: "To undergo a chemical change (often in food/beverage production)",
					C: "To heat at high temperatures",
					D: "To dry in the sun"
				},
				correct: "B",
				category: "Vocabulary"
			},
			{
				id: 27,
				question: "Which phrase shows the relationship between two stages?",
				options: {
					A: "Nevertheless",
					B: "For this reason",
					C: "Before being ground, the beans are dried",
					D: "On the other hand"
				},
				correct: "C",
				category: "Cohesion"
			},
			{
				id: 28,
				question: "How much time should be allocated for planning?",
				options: {
					A: "1 minute",
					B: "3 minutes for analyzing the diagram",
					C: "10 minutes",
					D: "Just start writing immediately"
				},
				correct: "B",
				category: "Time Management"
			},
			{
				id: 29,
				question: "What should you verify after writing a process description?",
				options: {
					A: "Only word count",
					B: "That all stages are included, tense is correct, and in logical order",
					C: "Only spelling",
					D: "Your personal feelings"
				},
				correct: "B",
				category: "Final Checks"
			},
			{
				id: 30,
				question: "Which is a key success factor for process descriptions?",
				options: {
					A: "Including personal experiences",
					B: "Making the sequence easy to follow with clear transitions",
					C: "Expressing opinions about the process",
					D: "Using the active voice exclusively"
				},
				correct: "B",
				category: "Success Factors"
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

			// Generate review section
			const reviewSection = document.getElementById('reviewSection');
			reviewSection.innerHTML = '<h3 style="margin-bottom: 1rem; color: #f5576c;">Review Your Answers</h3>';

			Object.keys(data.detailedResults).forEach(qNum => {
				const result = data.detailedResults[qNum];
				const question = quizData.find(q => q.id == qNum);
				const isCorrect = result.isCorrect;

				const reviewDiv = document.createElement('div');
				reviewDiv.className = `review-question ${isCorrect ? 'correct' : 'incorrect'}`;

				let html = `
					<div class="review-header ${isCorrect ? 'correct' : 'incorrect'}">
						<span class="answer-icon">${isCorrect ? '✅' : '❌'}</span>
						<span class="review-qnum">Question ${qNum}</span>
						<span style="margin-left: auto; font-size: 0.85rem;">${question.category}</span>
					</div>
					<div style="margin: 0.75rem 0; font-weight: 600; color: #333;">${question.question}</div>
				`;

				if (!isCorrect) {
					html += `
						<div class="review-answer user-answer">
							<span class="answer-label">Your answer:</span>
							${result.userAnswer}: ${question.options[result.userAnswer]}
						</div>
					`;
				}

				html += `
					<div class="review-answer correct-answer">
						<span class="answer-label">${isCorrect ? '✓ Your answer:' : '✓ Correct answer:'}</span>
						${result.correctAnswer}: ${question.options[result.correctAnswer]}
					</div>
				`;

				reviewDiv.innerHTML = html;
				reviewSection.appendChild(reviewDiv);
			});

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