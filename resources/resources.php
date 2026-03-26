<?php
require_once dirname(__DIR__) . '/bootstrap.php';

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
	<title>Resources & Tools | EduHub</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
	<?php include INCLUDES_PATH . '/navbar_styles.php'; ?>

	<style>
		.main-wrapper {
		overflow: hidden !important;           /* ← No scrolling at all */
		padding: 2rem 1.5rem !important;
		height: 100vh;                         /* Full viewport height */
		display: flex;
		flex-direction: column;
	}

	/* Container takes remaining space after title */
	.resources-container {
		flex: 1;
		overflow: hidden;
		display: flex;
		flex-direction: column;
	}

	/* Perfect square cards + ultra-tight grid that auto-fits */
	.resources-grid {
		display: grid;
		grid-template-columns: repeat(4, 1fr);   /* Exactly 4 columns on large screens */
		grid-template-rows: repeat(3, 1fr);      /* Exactly 3 rows → 12 cards max visible */
		gap: 0.75rem;                            /* Minimal spacing */
		height: 100%;                            /* Fill remaining space */
		max-width: 100%;
	}

	/* Force perfect squares */
	.resource-card {
		aspect-ratio: 1 / 1;
		border-radius: 16px;
		overflow: hidden;
		box-shadow: 0 4px 20px rgba(0,0,0,0.08);
		transition: all 0.3s ease;
	}

	.resource-card:hover {
		transform: translateY(-10px);
		box-shadow: 0 20px 40px rgba(0,0,0,0.2);
	}

	.resource-card .card-body {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		text-align: center;
		padding: 1.8rem 1rem;
		color: white;
		height: 100%;
	}

	.card-icon { font-size: 3.4rem; margin-bottom: 0.8rem; }
	.card-title { font-size: 1.18rem; font-weight: 600; margin-bottom: 0.4rem; }
	.card-text { font-size: 0.88rem; opacity: 0.9; }

	/* Responsive fallbacks – still no scrolling */
	@media (max-width: 1399px) {
		.resources-grid {
			grid-template-columns: repeat(3, 1fr);
			grid-template-rows: repeat(4, 1fr);
		}
	}
	@media (max-width: 1199px) {
		.resources-grid {
			grid-template-columns: repeat(3, 1fr);
			grid-template-rows: repeat(4, 1fr);
			gap: 0.9rem;
		}
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

	<!-- MAIN CONTENT AREA - uses your existing .main-wrapper class -->
	<main class="main-wrapper">
		<div class="container">

			<h1 class="page-title display-5">Resources & Tools</h1>

			<div class="resources-grid">
				<!-- 0. Diagnostic Tests -->
				<div class="col">
					<a href="diagnostic_tests/diagnostic_tests_home.php" class="text-decoration-none">
						<div class="resource-card" style="background: linear-gradient(135deg, #ec4899, #f43f5e);">
							<div class="card-body">
								<i class="bi bi-pencil-square card-icon"></i>
								<h3 class="card-title">Diagnostic Tests</h3>
								<p class="card-text">Assess your current level: IELTS, CELPIP & Basic English</p>
							</div>
						</div>
					</a>
				</div>

				<!-- 1. Essay Analyser -->
				<div class="col">
					<a href="essay_analyzer.php" class="text-decoration-none">
						<div class="resource-card" style="background: linear-gradient(135deg, #ec4899, #f43f5e);">
							<div class="card-body">
								<i class="bi bi-pencil-square card-icon"></i>
								<h3 class="card-title">IELTS & CELPIP Essay Analyser</h3>
								<p class="card-text">Instant band score + detailed feedback</p>
							</div>
						</div>
					</a>
				</div>

				<!-- 2. Mock Tests -->
				<div class="col">
					<a href="mock_tests/mock_tests_home.php" class="text-decoration-none">
						<div class="resource-card" style="background: linear-gradient(135deg, #eeecf3ff, #18975cff);">
							<div class="card-body">
								<i class="bi bi-file-earmark-check card-icon"></i>
								<h3 class="card-title">Mock Tests</h3>
								<p class="card-text">Take one of our simulated complete tests</p>
							</div>
						</div>
					</a>
				</div>

				<!-- 3. Quizzes -->
				<div class="col">
					<a href="quizzes/quizzes_home.php" class="text-decoration-none">
						<div class="resource-card" style="background: linear-gradient(135deg, #10b981, #34d399);">
							<div class="card-body">
								<i class="bi bi-stopwatch card-icon"></i>
								<h3 class="card-title">Quizzes</h3>
								<p class="card-text">Test your knowledge on a range of topics</p>
							</div>
						</div>
					</a>
				</div>

				<!-- 4. Model Answers -->
				<div class="col">
					<a href="model_answers/model_answers.php" class="text-decoration-none">
						<div class="resource-card" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
							<div class="card-body">
								<i class="bi bi-file-earmark-text card-icon"></i>
								<h3 class="card-title">Model Answers Library</h3>
								<p class="card-text">Band 8–9 essays & Task 1 reports</p>
							</div>
						</div>
					</a>
				</div>

				<!-- 5. Vocabulary Banks -->
				<div class="col">
					<a href="vocabulary-banks.php" class="text-decoration-none">
						<div class="resource-card" style="background: linear-gradient(135deg, #3b82f6, #60a5fa);">
							<div class="card-body">
								<i class="bi bi-journal-bookmark card-icon"></i>
								<h3 class="card-title">Topic Vocabulary Banks</h3>
								<p class="card-text">30+ topics with audio & examples</p>
							</div>
						</div>
					</a>
				</div>

				<!-- 6. Speaking Anaylzer -->
				<div class="col">
					<a href="audio_analyzer.php" class="text-decoration-none">
						<div class="resource-card" style="background: linear-gradient(135deg, #ef4444, #f87171);">
							<div class="card-body">
								<i class="bi bi-mic-fill card-icon"></i>
								<h3 class="card-title">Audio Analyzer</h3>
								<p class="card-text">Record & Analyze your speaking samples</p>
							</div>
						</div>
					</a>
				</div>

				<!-- 7. Practice Tests -->
				<div class="col">
					<a href="practice_tests/index.php" class="text-decoration-none">
						<div class="resource-card" style="background: linear-gradient(135deg, #c4d0caff, #dbd8e7ff);">
							<div class="card-body">
								<i class="bi bi-file-earmark-check card-icon"></i>
								<h3 class="card-title">Practice Tests</h3>
								<p class="card-text">Prepare for any test with these practice questions</p>
							</div>
						</div>
					</a>
				</div>

				<!-- 8. Exercises -->
				<div class="col">
					<a href="exercises/exercises.php" class="text-decoration-none">
						<div class="resource-card" style="background: linear-gradient(135deg, #5c2fe3ff, #d2f107ff);">
							<div class="card-body">
								<i class="bi bi-file-earmark-check card-icon"></i>
								<h3 class="card-title">Exercises</h3>
								<p class="card-text">-</p>
							</div>
						</div>
					</a>
				</div>

				
				<!-- Add more cards as needed... -->

			</div>
			<!-- /.row -->
		</div>
		<!-- /.container -->
	</main>
	<!-- END MAIN-WRAPPER -->
    <?php include INCLUDES_PATH . '/adverts.php'; ?>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>

	</body>
</html>