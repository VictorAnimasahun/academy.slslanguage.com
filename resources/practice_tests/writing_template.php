<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
require_once 'config.php';
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}

$testCode = $_GET['code'] ?? '';
$test = getTest($testCode);
$userId = $_SESSION['user_id'];

if (!$test) {
    die("Test not found");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($test['title']) ?> | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>

    <style>
		body {
			background: #f8f9fa;
		}

		/* Override navbar styles - remove right margin/padding for adverts */
		.main-wrapper {
			padding-top: 1.5rem;
			padding-bottom: 1.5rem;
			margin-right: 0 !important;
			padding-right: 0 !important;
		}

		/* Breadcrumb */
		.breadcrumb {
			background: transparent;
			padding: 0;
			margin-bottom: 1.5rem;
		}

		/* Container fluid adjustments */
		.container-fluid {
			max-width: 100%;
			padding-left: 0.1rem !important;   /* Reduced from 2rem */
			padding-right: 2rem;
		}

		/* Left Section */
		.left-section {
			height: calc(100vh - 120px);
		}
		/* Question Container - Full height */
		.question-container {
			background: white;
			border-radius: 12px;
			padding: 2rem;
			box-shadow: 0 2px 8px rgba(0,0,0,0.06);
			height: 100%;
			overflow-y: auto;
		}

		/* Writing Container - Bottom half */
		.writing-container {
			background: white;
			border-radius: 12px;
			padding: 2rem;
			box-shadow: 0 2px 8px rgba(0,0,0,0.06);
			flex: 1;
			display: flex;
			flex-direction: column;
		}

		/* Right Section */
		.right-section {
			background: white;
			border-radius: 12px;
			padding: 2rem;
			box-shadow: 0 2px 8px rgba(0,0,0,0.06);
			height: calc(100vh - 120px);
			overflow-y: auto;
			position: sticky;
			top: 1.5rem;
		}

		/* Test Badge */
		.test-badge {
			display: inline-block;
			padding: 0.5rem 1rem;
			background: linear-gradient(135deg, #0d6efd, #0a58ca);
			color: white;
			border-radius: 20px;
			font-size: 0.85rem;
			font-weight: 600;
			margin-bottom: 1rem;
		}

		/* Section Title */
		.section-title {
			font-size: 1.1rem;
			font-weight: 600;
			color: #212529;
			margin-bottom: 1.5rem;
			display: flex;
			align-items: center;
			gap: 0.5rem;
		}

		/* Media Display */
		.media-display {
			background: #f8f9fa;
			border-radius: 8px;
			padding: 1.5rem;
			margin-bottom: 1.5rem;
			text-align: center;
		}

		.media-display img {
			max-width: 100%;
			height: auto;
			border-radius: 8px;
		}

		/* Question Text */
		.question-text {
			background: #e7f3ff;
			border-left: 4px solid #0d6efd;
			padding: 1.5rem;
			border-radius: 8px;
			line-height: 1.8;
			color: #212529;
		}
		/* Writing Header - Title + Timer Side by Side */
		.writing-header {
			display: flex;
			justify-content: space-between;
			align-items: flex-start;
			margin-bottom: 1rem;
		}

		.writing-header .section-title {
			margin-bottom: 0;
		}

		/* Timer Container */
		.timer-container {
			text-align: right;
		}

		.timer-container .control-label {
			font-size: 0.75rem;
			color: #6c757d;
			margin-bottom: 0.3rem;
		}

		.timer-display {
			font-size: 1.9rem;
			font-weight: 700;
			font-family: 'Courier New', monospace;
			color: #0d6efd;
		}

		/* Textarea */
		.essay-textarea {
			flex: 1;
			width: 100%;
			padding: 1.5rem;
			border: 2px solid #dee2e6;
			border-radius: 8px;
			font-size: 1.05rem;
			line-height: 1.8;
			font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
			resize: none;
			transition: all 0.3s;
			min-height: 420px;
			margin-bottom: 1.25rem;
		}

		.essay-textarea:focus {
			outline: none;
			border-color: #0d6efd;
			box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
		}

		/* Bottom Controls */
		.bottom-controls {
			display: flex;
			align-items: flex-end;
			gap: 1.5rem;
			padding-top: 1rem;
			border-top: 1px solid #e9ecef;
		}

		.word-count-box {
			flex: 1;
		}

		.word-count-display {
			font-size: 1.95rem;
			font-weight: 700;
			color: #212529;
			line-height: 1;
		}

		.word-count-display.below { color: #dc3545; }
		.word-count-display.good  { color: #198754; }

		.word-target {
			display: block;
			font-size: 0.82rem;
			color: #6c757d;
			margin-top: 0.3rem;
		}

		.btn-analyze {
			padding: 0.85rem 2rem;
			background: linear-gradient(135deg, #0d6efd, #0a58ca);
			color: white;
			border: none;
			border-radius: 8px;
			font-weight: 600;
			font-size: 1rem;
			transition: all 0.3s;
			cursor: pointer;
			display: flex;
			align-items: center;
			gap: 8px;
			height: fit-content;
			white-space: nowrap;
		}

		.btn-analyze:hover:not(:disabled) {
			transform: translateY(-2px);
			box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4);
		}

		.btn-analyze:disabled {
			opacity: 0.65;
			cursor: not-allowed;
		}

		/* Responsive */
		@media (max-width: 768px) {
			.bottom-controls {
				flex-direction: column;
				align-items: stretch;
			}
			
			.btn-analyze {
				width: 100%;
			}
		}

		/* Responsive */
		@media (max-width: 992px) {
			.left-section {
				height: auto;
			}

			.question-container,
			.writing-container {
				min-height: 400px;
			}

			.right-section {
				position: static;
				height: auto;
				margin-top: 1rem;
			}
		}
	</style>
</head>
<body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <main class="main-wrapper">
		<div class="container-fluid px-4">
			<!-- Breadcrumb -->
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="../resources_home.php">Resources</a></li>
					<li class="breadcrumb-item"><a href="index.php">Practice Tests</a></li>
					<li class="breadcrumb-item active"><?= htmlspecialchars($test['title']) ?></li>
				</ol>
			</nav>

			<!-- Two Column Layout -->
			<div class="row g-3">
				<!-- Left Side: Question + Writing Box -->
				<div class="col-lg-6">
					<div class="left-section">
						<!-- Question area -->
						<div class="question-container">
							<!-- Badge -->
							<div class="test-badge">
								<?= htmlspecialchars($test['type'] . ' - ' . $test['task']) ?>
							</div>
							
							<!-- Section Title -->
							<h5 class="section-title">
								<i class="bi bi-card-text"></i> Question
							</h5>
							
							<!-- Chart/Image (conditional) -->
							<?php if ($test['media_file']): ?>
							<div class="media-display">
								<img src="media/<?= htmlspecialchars($test['media_file']) ?>" alt="Chart">
							</div>
							<?php endif; ?>
							
							<!-- Question Text -->
							<div class="question-text">
								<?= nl2br(htmlspecialchars($test['description'])) ?>
							</div>
						</div>
					</div>
				</div>

				<!-- Right Side: Writing Pane -->
				<div class="col-lg-6">
					<div class="right-section">
						
						<!-- Header: Title + Timer -->
						<div class="writing-header">
							<h5 class="section-title">
								<i class="bi bi-pencil-square"></i> Your Response
							</h5>
							
							<!-- Timer on the right -->
							<div class="timer-container">
								<span class="control-label">Time Remaining</span>
								<div id="timer" class="timer-display">
									<?= str_pad($test['time_limit'] ?? 40, 2, '0', STR_PAD_LEFT) ?>:00
								</div>
							</div>
						</div>
						
						<!-- Textarea -->
						<textarea 
							id="essayInput" 
							class="essay-textarea" 
							placeholder="Start writing your IELTS response here..."
							autofocus></textarea>
						
						<!-- Bottom Controls: Word Count + Analyze Button -->
						<div class="bottom-controls">
							
							<!-- Word Count -->
							<div class="word-count-box">
								<span class="control-label">Words</span>
								<div id="wordCount" class="word-count-display">0</div>
								<span class="word-target">
									Target: <?= $test['word_target'] ?? '150' ?> words
								</span>
							</div>
							
							<!-- Analyze Button -->
							<button id="analyzeBtn" class="btn-analyze" disabled>
								<i class="bi bi-check-circle"></i> Analyze My Response
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>

	<!-- Analysis Results Modal -->
	<div class="modal fade" id="analysisModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">
						<i class="bi bi-bar-chart-fill"></i> Analysis Results
					</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<!-- Analysis content will go here -->
					<div id="analysisContent">
						<!-- Loading spinner or results -->
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" onclick="resetTest()">Try Again</button>
				</div>
			</div>
		</div>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const textarea = document.getElementById('essayInput');
			const wordCountEl = document.getElementById('wordCount');
			const timerEl = document.getElementById('timer');
			const analyzeBtn = document.getElementById('analyzeBtn');

			let timeLeft = <?= ($test['time_limit'] ?? 40) * 60; ?>; // in seconds

			// Word Count
			function updateWordCount() {
				const text = textarea.value.trim();
				const words = text === '' ? 0 : text.split(/\s+/).length;
				wordCountEl.textContent = words;

				if (words < 120) {
					wordCountEl.className = 'word-count-display below';
				} else if (words >= 150) {
					wordCountEl.className = 'word-count-display good';
				} else {
					wordCountEl.className = 'word-count-display';
				}

				analyzeBtn.disabled = words < 100; // Enable only after ~100 words
			}

			textarea.addEventListener('input', updateWordCount);

			// Timer
			function startTimer() {
				const timerInterval = setInterval(() => {
					timeLeft--;
					const minutes = Math.floor(timeLeft / 60);
					const seconds = timeLeft % 60;
					timerEl.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

					if (timeLeft <= 300) { // Last 5 minutes
						timerEl.style.color = '#dc3545';
					}

					if (timeLeft <= 0) {
						clearInterval(timerInterval);
						Swal.fire({
							title: "Time's up!",
							text: 'Your response has been submitted.',
							icon: 'warning',
							timer: 2500,
							timerProgressBar: true,
							showConfirmButton: false,
						});
					}
				}, 1000);
			}

			startTimer();
			updateWordCount();
		});
	</script>
</body>
</html>