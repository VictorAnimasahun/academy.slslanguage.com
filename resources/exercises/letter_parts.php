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

    if (isset($data['action']) && $data['action'] === 'check_answers') {
        header('Content-Type: application/json');

        try {
            $level = isset($data['level']) ? (int)$data['level'] : 1;
            
            // Define answer keys for all 10 levels
            $levelAnswers = [
                1 => ['1' => 'intro', '2' => 'main', '3' => 'intro', '4' => 'main', '5' => 'conclusion'],
                2 => ['1' => 'intro', '2' => 'main', '3' => 'intro', '4' => 'main', '5' => 'main', '6' => 'conclusion', '7' => 'conclusion'],
                3 => ['1' => 'intro', '2' => 'intro', '3' => 'main', '4' => 'main', '5' => 'main', '6' => 'conclusion', '7' => 'conclusion', '8' => 'conclusion'],
                4 => ['1' => 'intro', '2' => 'intro', '3' => 'main', '4' => 'main', '5' => 'main', '6' => 'main', '7' => 'conclusion', '8' => 'conclusion', '9' => 'conclusion'],
                5 => ['1' => 'intro', '2' => 'intro', '3' => 'main', '4' => 'main', '5' => 'main', '6' => 'main', '7' => 'main', '8' => 'conclusion', '9' => 'conclusion', '10' => 'conclusion'],
                6 => ['1' => 'intro', '2' => 'intro', '3' => 'intro', '4' => 'main', '5' => 'main', '6' => 'main', '7' => 'main', '8' => 'main', '9' => 'conclusion', '10' => 'conclusion', '11' => 'conclusion'],
                7 => ['1' => 'intro', '2' => 'intro', '3' => 'intro', '4' => 'main', '5' => 'main', '6' => 'main', '7' => 'main', '8' => 'main', '9' => 'main', '10' => 'conclusion', '11' => 'conclusion', '12' => 'conclusion'],
                8 => ['1' => 'intro', '2' => 'intro', '3' => 'intro', '4' => 'main', '5' => 'main', '6' => 'main', '7' => 'main', '8' => 'main', '9' => 'main', '10' => 'main', '11' => 'conclusion', '12' => 'conclusion', '13' => 'conclusion', '14' => 'conclusion'],
                9 => ['1' => 'intro', '2' => 'intro', '3' => 'intro', '4' => 'intro', '5' => 'main', '6' => 'main', '7' => 'main', '8' => 'main', '9' => 'main', '10' => 'main', '11' => 'main', '12' => 'conclusion', '13' => 'conclusion', '14' => 'conclusion', '15' => 'conclusion'],
                10 => ['1' => 'intro', '2' => 'intro', '3' => 'intro', '4' => 'intro', '5' => 'main', '6' => 'main', '7' => 'main', '8' => 'main', '9' => 'main', '10' => 'main', '11' => 'main', '12' => 'main', '13' => 'conclusion', '14' => 'conclusion', '15' => 'conclusion', '16' => 'conclusion', '17' => 'conclusion']
            ];

            $answer_key = $levelAnswers[$level] ?? $levelAnswers[1];
            $student_answers = is_array($data['answers']) ? $data['answers'] : [];

            $correct = 0;
            $total = count($answer_key);

            foreach ($answer_key as $id => $category) {
                if (isset($student_answers[$id]) && $student_answers[$id] === $category) {
                    $correct++;
                }
            }

            $score = $total > 0 ? round(($correct / $total) * 100) : 0;
            $passed = $score >= 70;

            echo json_encode([
                'status' => 'success',
                'correct' => $correct,
                'total' => $total,
                'score' => $score,
                'passed' => $passed,
                'level' => $level
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
	<title>Letter Parts Exercise | EduHub</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
	<?php include INCLUDES_PATH . '/navbar_styles.php'; ?>

	<style>
		body {
			overflow: hidden;
		}

		.main-wrapper {
			height: 100vh;
			overflow-y: auto;
			padding: 1rem;
		}

		.exercise-container {
			max-width: 100%;
			height: calc(100vh - 2rem);
			background: white;
			border-radius: 12px;
			padding: 1rem;
			box-shadow: 0 4px 20px rgba(0,0,0,0.08);
			display: flex;
			flex-direction: column;
		}

		.exercise-header {
			padding-bottom: 0.75rem;
			border-bottom: 2px solid #f0f0f0;
			margin-bottom: 1rem;
		}

		.level-indicator {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 0.75rem;
		}

		.level-badge {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			color: white;
			padding: 0.4rem 1rem;
			border-radius: 20px;
			font-weight: 700;
			font-size: 0.9rem;
		}

		.progress-bar-container {
			flex: 1;
			margin: 0 1rem;
			height: 8px;
			background: #e2e8f0;
			border-radius: 10px;
			overflow: hidden;
		}

		.progress-bar-fill {
			height: 100%;
			background: linear-gradient(90deg, #667eea, #764ba2);
			transition: width 0.5s ease;
		}

		.exercise-title {
			font-size: 1.3rem;
			font-weight: 700;
			color: #1f2937;
			margin: 0;
		}

		.exercise-content {
			flex: 1;
			display: flex;
			gap: 1rem;
			overflow: hidden;
		}

		.left-panel {
			flex: 0 0 40%;
			display: flex;
			flex-direction: column;
		}

		.right-panel {
			flex: 1;
			display: flex;
			flex-direction: column;
			gap: 0.75rem;
		}

		.source-sentences {
			flex: 1;
			background: #f8f9fa;
			border: 2px solid #e2e8f0;
			border-radius: 8px;
			padding: 0.75rem;
			overflow-y: auto;
		}

		.source-sentences h3 {
			color: #1f2937;
			font-size: 0.9rem;
			font-weight: 600;
			margin-bottom: 0.75rem;
		}

		.drop-zone {
			flex: 1;
			background: #f8f9fa;
			border: 2px dashed #667eea;
			border-radius: 8px;
			padding: 0.75rem;
			transition: all 0.3s ease;
			overflow-y: auto;
		}

		.drop-zone h3 {
			color: #667eea;
			font-size: 0.85rem;
			font-weight: 600;
			margin-bottom: 0.5rem;
			display: flex;
			align-items: center;
			gap: 0.3rem;
		}

		.drop-zone.active {
			background: #e8eaf6;
			border-color: #764ba2;
			transform: scale(1.02);
		}

		.sentences-container {
			display: flex;
			flex-direction: column;
			gap: 0.5rem;
		}

		.sentence {
			background: white;
			border: 2px solid #ddd;
			border-radius: 6px;
			padding: 0.5rem 0.6rem;
			cursor: grab;
			user-select: none;
			transition: all 0.2s ease;
			box-shadow: 0 1px 3px rgba(0,0,0,0.05);
			font-size: 0.8rem;
			line-height: 1.3;
		}

		.sentence:hover {
			box-shadow: 0 2px 6px rgba(0,0,0,0.1);
			transform: translateY(-1px);
		}

		.sentence.dragging {
			opacity: 0.5;
			cursor: grabbing;
		}

		.sentence.placed {
			background: #f0f4ff;
			border-color: #667eea;
			cursor: default;
		}

		.button-group {
			display: flex;
			gap: 0.75rem;
			margin-top: 1rem;
		}

		button {
			background: #667eea;
			color: white;
			border: none;
			padding: 0.6rem 1.5rem;
			border-radius: 6px;
			cursor: pointer;
			font-size: 0.9rem;
			font-weight: 600;
			transition: all 0.3s ease;
			display: flex;
			align-items: center;
			gap: 0.4rem;
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

		.feedback {
			padding: 0.75rem;
			border-radius: 8px;
			font-size: 0.9rem;
			font-weight: 600;
			display: none;
			margin-bottom: 1rem;
		}

		.feedback.show {
			display: block;
		}

		.feedback.success {
			background: #d4edda;
			color: #155724;
			border: 1px solid #c3e6cb;
		}

		.feedback.error {
			background: #f8d7da;
			color: #721c24;
			border: 1px solid #f5c6cb;
		}

		.level-complete-modal {
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

		.level-complete-modal.show {
			display: flex;
		}

		.modal-content {
			background: white;
			padding: 2rem;
			border-radius: 12px;
			text-align: center;
			max-width: 400px;
			animation: slideIn 0.3s ease-out;
		}

		@keyframes slideIn {
			from { transform: translateY(-50px); opacity: 0; }
			to { transform: translateY(0); opacity: 1; }
		}

		.modal-content h2 {
			color: #16a34a;
			font-size: 2rem;
			margin-bottom: 1rem;
		}

		.modal-content .score {
			font-size: 3rem;
			font-weight: 800;
			color: #667eea;
			margin: 1rem 0;
		}

		.modal-content button {
			margin: 0.5rem;
		}

		@media (max-width: 768px) {
			.exercise-content {
				flex-direction: column;
			}
			
			.left-panel {
				flex: 0 0 40%;
			}
		}
	</style>
</head>
<body>
	<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
	<div class="mobile-overlay" id="mobileOverlay"></div>
	<?php include INCLUDES_PATH . '/navbar.php'; ?>

	<main class="main-wrapper">
		<div class="exercise-container">
			<div class="exercise-header">
				<div class="level-indicator">
					<span class="level-badge" id="levelBadge">Level 1</span>
					<div class="progress-bar-container">
						<div class="progress-bar-fill" id="progressBar" style="width: 10%"></div>
					</div>
					<span style="font-weight: 600; color: #64748b; font-size: 0.9rem;">10 Levels</span>
				</div>
				<h1 class="exercise-title"><i class="bi bi-envelope"></i> Letter Parts Challenge</h1>
			</div>

			<div class="feedback" id="feedback"></div>

			<div class="exercise-content">
				<div class="left-panel">
					<div class="source-sentences">
						<h3><i class="bi bi-shuffle"></i> Drag sentences to correct sections:</h3>
						<div class="sentences-container" id="sourceSentences"></div>
					</div>
				</div>

				<div class="right-panel">
					<div class="drop-zone" id="introZone">
						<h3><i class="bi bi-1-circle"></i> Introduction</h3>
						<div class="sentences-container" id="introSentences"></div>
					</div>

					<div class="drop-zone" id="mainZone">
						<h3><i class="bi bi-2-circle"></i> Main Paragraphs</h3>
						<div class="sentences-container" id="mainSentences"></div>
					</div>

					<div class="drop-zone" id="conclusionZone">
						<h3><i class="bi bi-3-circle"></i> Conclusion</h3>
						<div class="sentences-container" id="conclusionSentences"></div>
					</div>
				</div>
			</div>

			<div class="button-group">
				<button onclick="checkAnswers()" id="checkBtn"><i class="bi bi-check-circle"></i> Check Answers</button>
				<button onclick="resetExercise()"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
			</div>
		</div>
	</main>

	<div class="level-complete-modal" id="levelCompleteModal">
		<div class="modal-content">
			<h2><i class="bi bi-trophy-fill"></i> Level Complete!</h2>
			<div class="score" id="modalScore">100%</div>
			<p id="modalMessage">Congratulations! Ready for the next challenge?</p>
			<button onclick="nextLevel()"><i class="bi bi-arrow-right-circle"></i> Next Level</button>
			<button onclick="closeModal()" style="background: #64748b;"><i class="bi bi-x-circle"></i> Stay Here</button>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>

	<script>
		let currentLevel = 1;
		let draggedElement = null;

		const levelData = {
			1: [
				{ id: 1, text: "Dear Sir/Madam,", category: "intro" },
				{ id: 2, text: "I am writing to complain about the service.", category: "main" },
				{ id: 3, text: "I am writing regarding your advertisement.", category: "intro" },
				{ id: 4, text: "The product was defective.", category: "main" },
				{ id: 5, text: "I look forward to your response.", category: "conclusion" }
			],
			2: [
				{ id: 1, text: "Dear Manager,", category: "intro" },
				{ id: 2, text: "On 10th March, I purchased a laptop.", category: "main" },
				{ id: 3, text: "I am writing to express my dissatisfaction.", category: "intro" },
				{ id: 4, text: "The device stopped working after two days.", category: "main" },
				{ id: 5, text: "Additionally, the customer service was unhelpful.", category: "main" },
				{ id: 6, text: "I would appreciate a full refund.", category: "conclusion" },
				{ id: 7, text: "Yours sincerely,", category: "conclusion" }
			],
			3: [
				{ id: 1, text: "To whom it may concern,", category: "intro" },
				{ id: 2, text: "I am writing with reference to your recent email.", category: "intro" },
				{ id: 3, text: "Last week, I ordered three items from your store.", category: "main" },
				{ id: 4, text: "Two of the items arrived damaged.", category: "main" },
				{ id: 5, text: "The packaging was clearly insufficient.", category: "main" },
				{ id: 6, text: "I request an immediate replacement.", category: "conclusion" },
				{ id: 7, text: "Please respond within 7 working days.", category: "conclusion" },
				{ id: 8, text: "Yours faithfully,", category: "conclusion" }
			],
			4: [
				{ id: 1, text: "Dear Customer Service Team,", category: "intro" },
				{ id: 2, text: "I am writing to lodge a formal complaint.", category: "intro" },
				{ id: 3, text: "On 15th January, I stayed at your hotel in London.", category: "main" },
				{ id: 4, text: "The room was not cleaned properly.", category: "main" },
				{ id: 5, text: "Furthermore, the air conditioning did not work.", category: "main" },
				{ id: 6, text: "I raised these issues with reception, but nothing was done.", category: "main" },
				{ id: 7, text: "I believe I am entitled to a partial refund.", category: "conclusion" },
				{ id: 8, text: "I await your prompt reply.", category: "conclusion" },
				{ id: 9, text: "Kind regards,", category: "conclusion" }
			],
			5: [
				{ id: 1, text: "Dear Sir or Madam,", category: "intro" },
				{ id: 2, text: "I am writing concerning the delayed delivery.", category: "intro" },
				{ id: 3, text: "I placed an order on 1st February (Order #12345).", category: "main" },
				{ id: 4, text: "The estimated delivery was 5-7 business days.", category: "main" },
				{ id: 5, text: "However, it has now been three weeks.", category: "main" },
				{ id: 6, text: "I have contacted customer service twice with no response.", category: "main" },
				{ id: 7, text: "This delay has caused significant inconvenience.", category: "main" },
				{ id: 8, text: "I would like either immediate delivery or a full refund.", category: "conclusion" },
				{ id: 9, text: "Thank you for your attention to this matter.", category: "conclusion" },
				{ id: 10, text: "Yours faithfully,", category: "conclusion" }
			],
			6: [
				{ id: 1, text: "Dear Complaints Department,", category: "intro" },
				{ id: 2, text: "I am writing to express my extreme disappointment.", category: "intro" },
				{ id: 3, text: "I am a long-standing customer of your company.", category: "intro" },
				{ id: 4, text: "Last month, I attended a conference at your venue.", category: "main" },
				{ id: 5, text: "The facilities were below the standard advertised.", category: "main" },
				{ id: 6, text: "The audio equipment malfunctioned multiple times.", category: "main" },
				{ id: 7, text: "Moreover, the catering was inadequate for the number of guests.", category: "main" },
				{ id: 8, text: "Several attendees complained about the poor service.", category: "main" },
				{ id: 9, text: "I request a full refund of the £2,500 venue fee.", category: "conclusion" },
				{ id: 10, text: "I trust this matter will be resolved promptly.", category: "conclusion" },
				{ id: 11, text: "Yours sincerely,", category: "conclusion" }
			],
			7: [
				{ id: 1, text: "Dear Manager,", category: "intro" },
				{ id: 2, text: "I am writing to bring to your attention a serious issue.", category: "intro" },
				{ id: 3, text: "I have been a customer of your bank for over ten years.", category: "intro" },
				{ id: 4, text: "On 20th April, I discovered unauthorized transactions on my account.", category: "main" },
				{ id: 5, text: "These transactions totaled £1,200.", category: "main" },
				{ id: 6, text: "I immediately reported this to your fraud department.", category: "main" },
				{ id: 7, text: "However, I was told the investigation would take 30 days.", category: "main" },
				{ id: 8, text: "This is unacceptable given the urgency of the situation.", category: "main" },
				{ id: 9, text: "I have been left without access to my funds.", category: "main" },
				{ id: 10, text: "I expect an immediate refund and a formal apology.", category: "conclusion" },
				{ id: 11, text: "Please contact me within 48 hours.", category: "conclusion" },
				{ id: 12, text: "Yours faithfully,", category: "conclusion" }
			],
			8: [
				{ id: 1, text: "To the Director,", category: "intro" },
				{ id: 2, text: "I am writing to formally complain about recent events.", category: "intro" },
				{ id: 3, text: "I am a professional photographer who hired equipment from your company.", category: "intro" },
				{ id: 4, text: "I reserved a camera and lenses for a wedding on 5th May.", category: "main" },
				{ id: 5, text: "Upon collection, I was given the wrong camera model.", category: "main" },
				{ id: 6, text: "I discovered this error only when I arrived at the venue.", category: "main" },
				{ id: 7, text: "As a result, I was unable to take high-quality photographs.", category: "main" },
				{ id: 8, text: "The client was extremely dissatisfied with the outcome.", category: "main" },
				{ id: 9, text: "I have now lost this client and suffered reputational damage.", category: "main" },
				{ id: 10, text: "Your company's negligence has cost me approximately £3,000.", category: "main" },
				{ id: 11, text: "I am seeking compensation for my losses.", category: "conclusion" },
				{ id: 12, text: "I expect a response within five working days.", category: "conclusion" },
				{ id: 13, text: "Should this not be resolved, I will seek legal advice.", category: "conclusion" },
				{ id: 14, text: "Yours sincerely,", category: "conclusion" }
			],
			9: [
				{ id: 1, text: "Dear Head of Customer Relations,", category: "intro" },
				{ id: 2, text: "I am writing following several unsuccessful attempts to resolve an issue.", category: "intro" },
				{ id: 3, text: "I am a frequent flyer with your airline.", category: "intro" },
				{ id: 4, text: "I have held Gold status for the past three years.", category: "intro" },
				{ id: 5, text: "On 12th June, my flight from London to New York was delayed by 8 hours.", category: "main" },
				{ id: 6, text: "No food vouchers or accommodation were provided.", category: "main" },
				{ id: 7, text: "Staff were unhelpful and provided no information.", category: "main" },
				{ id: 8, text: "As a result, I missed an important business meeting.", category: "main" },
				{ id: 9, text: "This cost me a potential contract worth £50,000.", category: "main" },
				{ id: 10, text: "I have written twice to your complaints department with no reply.", category: "main" },
				{ id: 11, text: "This lack of response is completely unacceptable.", category: "main" },
				{ id: 12, text: "I am entitled to compensation under EU regulations.", category: "conclusion" },
				{ id: 13, text: "I expect £600 compensation plus reimbursement of expenses.", category: "conclusion" },
				{ id: 14, text: "Please respond within 14 days or I will escalate to the Aviation Authority.", category: "conclusion" },
				{ id: 15, text: "Yours faithfully,", category: "conclusion" }
			],
			10: [
				{ id: 1, text: "Dear Chief Executive Officer,", category: "intro" },
				{ id: 2, text: "I am writing as a last resort after exhausting all other channels.", category: "intro" },
				{ id: 3, text: "I have been a loyal customer of your retail chain for 15 years.", category: "intro" },
				{ id: 4, text: "I am writing regarding a serious incident that occurred at your Oxford Street branch.", category: "intro" },
				{ id: 5, text: "On 28th July, I purchased a washing machine priced at £799.", category: "main" },
				{ id: 6, text: "The sales assistant assured me it would be delivered within 3 days.", category: "main" },
				{ id: 7, text: "After one week, the machine had still not arrived.", category: "main" },
				{ id: 8, text: "I contacted the store repeatedly and was given conflicting information.", category: "main" },
				{ id: 9, text: "Eventually, the machine was delivered but it was damaged.", category: "main" },
				{ id: 10, text: "The delivery drivers refused to take it back without authorization.", category: "main" },
				{ id: 11, text: "I spent over 12 hours on the phone trying to resolve this.", category: "main" },
				{ id: 12, text: "I was passed between seven different departments with no resolution.", category: "main" },
				{ id: 13, text: "I am demanding a full refund, compensation for my time, and a written apology.", category: "conclusion" },
				{ id: 14, text: "I will also be sharing this experience on social media and consumer forums.", category: "conclusion" },
				{ id: 15, text: "I expect a response from you personally within 7 days.", category: "conclusion" },
				{ id: 16, text: "Failure to respond will result in legal action.", category: "conclusion" },
				{ id: 17, text: "Yours sincerely,", category: "conclusion" }
			]
		};

		function updateLevelDisplay() {
			document.getElementById('levelBadge').textContent = `Level ${currentLevel}`;
			document.getElementById('progressBar').style.width = (currentLevel * 10) + '%';
		}

		function initializeExercise() {
			const sourceSentences = document.getElementById('sourceSentences');
			sourceSentences.innerHTML = '';

			const sentences = levelData[currentLevel] || levelData[1];

			sentences.forEach((item) => {
				const sentenceEl = document.createElement('div');
				sentenceEl.className = 'sentence';
				sentenceEl.draggable = true;
				sentenceEl.textContent = item.text;
				sentenceEl.dataset.id = item.id;
				sentenceEl.dataset.category = item.category;

				sentenceEl.addEventListener('dragstart', handleDragStart);
				sentenceEl.addEventListener('dragend', handleDragEnd);

				sourceSentences.appendChild(sentenceEl);
			});

			setupDropZones();
			updateLevelDisplay();
		}

		function setupDropZones() {
			const zones = ['introZone', 'mainZone', 'conclusionZone'];
			zones.forEach(zoneId => {
				const zone = document.getElementById(zoneId);
				zone.addEventListener('dragover', handleDragOver);
				zone.addEventListener('drop', handleDrop);
				zone.addEventListener('dragleave', handleDragLeave);
			});
		}

		function handleDragStart(e) {
			draggedElement = this;
			this.classList.add('dragging');
			e.dataTransfer.effectAllowed = 'move';
		}

		function handleDragEnd(e) {
			this.classList.remove('dragging');
			document.querySelectorAll('.drop-zone').forEach(zone => {
				zone.classList.remove('active');
			});
		}

		function handleDragOver(e) {
			e.preventDefault();
			e.dataTransfer.dropEffect = 'move';
			this.classList.add('active');
		}

		function handleDragLeave(e) {
			if (e.target === this) {
				this.classList.remove('active');
			}
		}

		function handleDrop(e) {
			e.preventDefault();
			this.classList.remove('active');

			if (!draggedElement) return;

			const sentencesContainer = this.querySelector('.sentences-container');
			const clonedElement = draggedElement.cloneNode(true);
			clonedElement.draggable = false;
			clonedElement.classList.add('placed');
			clonedElement.classList.remove('dragging');

			sentencesContainer.appendChild(clonedElement);
			draggedElement.remove();
			draggedElement = null;
		}

		function checkAnswers() {
			const studentAnswers = {};

			document.querySelectorAll('#introSentences .placed').forEach(el => {
				studentAnswers[el.dataset.id] = 'intro';
			});
			document.querySelectorAll('#mainSentences .placed').forEach(el => {
				studentAnswers[el.dataset.id] = 'main';
			});
			document.querySelectorAll('#conclusionSentences .placed').forEach(el => {
				studentAnswers[el.dataset.id] = 'conclusion';
			});

			document.getElementById('checkBtn').disabled = true;

			fetch('', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
				body: JSON.stringify({
					action: 'check_answers',
					answers: studentAnswers,
					level: currentLevel
				})
			})
			.then(response => {
				if (!response.ok) {
					return response.text().then(text => {
						throw new Error('Server returned: ' + text);
					});
				}
				return response.json();
			})
			.then(data => {
				const feedback = document.getElementById('feedback');
				feedback.classList.add('show');

				if (data.status === 'success') {
					if (data.passed) {
						feedback.className = 'feedback success show';
						feedback.innerHTML = `<i class="bi bi-check-circle-fill"></i> Congratulations! ${data.correct}/${data.total} correct (${data.score}%)`;
						
						// Show level complete modal
						setTimeout(() => {
							document.getElementById('modalScore').textContent = data.score + '%';
							document.getElementById('modalMessage').textContent = 
								currentLevel < 10 
									? `Great job! You've completed Level ${currentLevel}. Ready for Level ${currentLevel + 1}?`
									: 'Congratulations! You have completed all 10 levels!';
							document.getElementById('levelCompleteModal').classList.add('show');
						}, 800);
					} else {
						feedback.className = 'feedback error show';
						feedback.innerHTML = `<i class="bi bi-x-circle-fill"></i> You got ${data.correct}/${data.total} (${data.score}%). Try again!`;
						document.getElementById('checkBtn').disabled = false;
					}
				} else {
					feedback.className = 'feedback error show';
					feedback.textContent = data.message || 'Unknown error';
					document.getElementById('checkBtn').disabled = false;
				}
			})
			.catch(err => {
				console.error('Error:', err);
				const feedback = document.getElementById('feedback');
				feedback.className = 'feedback error show';
				feedback.innerHTML = `<i class="bi bi-exclamation-triangle-fill"></i> Error: ${err.message}`;
				document.getElementById('checkBtn').disabled = false;
			});
		}

		function resetExercise() {
			document.getElementById('introSentences').innerHTML = '';
			document.getElementById('mainSentences').innerHTML = '';
			document.getElementById('conclusionSentences').innerHTML = '';
			document.getElementById('feedback').classList.remove('show');
			document.getElementById('checkBtn').disabled = false;
			initializeExercise();
		}

		function nextLevel() {
			if (currentLevel < 10) {
				currentLevel++;
				closeModal();
				resetExercise();
			} else {
				closeModal();
				alert('You have completed all levels! Congratulations!');
			}
		}

		function closeModal() {
			document.getElementById('levelCompleteModal').classList.remove('show');
		}

		// Initialize on page load
		initializeExercise();
		</script>
	</body>
</html>