<?php
require_once dirname(__DIR__) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../registration.php?message=Please+login+to+access+resources");
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
<?php $userName = isset($_SESSION['user_firstname']) ? htmlspecialchars($_SESSION['user_firstname']) : 'Learner'; ?>
<body class="light">
	<!-- Mobile Header -->
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <!-- Sidebar Navigation -->
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <div class="main-wrapper flex-grow-1" style="flex:1;">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>

	<!-- MAIN CONTENT AREA -->
	<main class="content p-4">
		<div class="container">

			<h1 class="page-title display-5">Resources & Tools</h1>

			<div class="resources-grid">
				<!-- 0. Diagnostic Tests -->
				<div class="col">
					<a href="diagnostic_tests/diagnostic_tests_home.php" class="text-decoration-none">
						<div class="resource-card" style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
							<div class="card-body">
								<i class="bi bi-clipboard2-pulse card-icon"></i>
								<h3 class="card-title">Diagnostic Tests</h3>
							</div>
						</div>
					</a>
				</div>

				<!-- 1. Essay Analyser -->
				<div class="col">
					<a href="essay_analyzer.php" class="text-decoration-none">
						<div class="resource-card" style="background: linear-gradient(135deg, #ea580c, #f97316);">
							<div class="card-body">
								<i class="bi bi-pencil-fill card-icon"></i>
								<h3 class="card-title">Essay Analyser</h3>
							</div>
						</div>
					</a>
				</div>

				<!-- 2. Mock Tests -->
				<div class="col">
					<a href="mock_tests/index.php" class="text-decoration-none">
						<div class="resource-card" style="background: linear-gradient(135deg, #0f766e, #14b8a6);">
							<div class="card-body">
								<i class="bi bi-journal-check card-icon"></i>
								<h3 class="card-title">Mock Tests</h3>
							</div>
						</div>
					</a>
				</div>

				<!-- 3. Quizzes -->
				<div class="col">
					<a href="quizzes/quizzes_home.php" class="text-decoration-none">
						<div class="resource-card" style="background: linear-gradient(135deg, #16a34a, #22c55e);">
							<div class="card-body">
								<i class="bi bi-patch-question-fill card-icon"></i>
								<h3 class="card-title">Quizzes</h3>
							</div>
						</div>
					</a>
				</div>

				<!-- 4. Model Answers -->
				<div class="col">
					<a href="model_answers/model_answers.php" class="text-decoration-none">
						<div class="resource-card" style="background: linear-gradient(135deg, #b45309, #d97706);">
							<div class="card-body">
								<i class="bi bi-award-fill card-icon"></i>
								<h3 class="card-title">Model Answers</h3>
							</div>
						</div>
					</a>
				</div>

				<!-- 5. Vocabulary Banks -->
				<div class="col">
					<a href="vocabulary-banks.php" class="text-decoration-none">
						<div class="resource-card" style="background: linear-gradient(135deg, #1e40af, #3b82f6);">
							<div class="card-body">
								<i class="bi bi-journal-bookmark-fill card-icon"></i>
								<h3 class="card-title">Vocabulary Banks</h3>
							</div>
						</div>
					</a>
				</div>

				<!-- 6. Speaking Analyser -->
				<div class="col">
					<a href="audio_analyzer.php" class="text-decoration-none">
						<div class="resource-card" style="background: linear-gradient(135deg, #9d174d, #ec4899);">
							<div class="card-body">
								<i class="bi bi-mic-fill card-icon"></i>
								<h3 class="card-title">Speaking Analyser</h3>
							</div>
						</div>
					</a>
				</div>

				<!-- 7. Practice Tests -->
				<div class="col">
					<a href="practice_tests/index.php" class="text-decoration-none">
						<div class="resource-card" style="background: linear-gradient(135deg, #0369a1, #0ea5e9);">
							<div class="card-body">
								<i class="bi bi-mortarboard-fill card-icon"></i>
								<h3 class="card-title">Practice Tests</h3>
							</div>
						</div>
					</a>
				</div>

				<!-- 8. Exercises -->
				<div class="col">
					<a href="exercises/exercises.php" class="text-decoration-none">
						<div class="resource-card" style="background: linear-gradient(135deg, #b91c1c, #ef4444);">
							<div class="card-body">
								<i class="bi bi-activity card-icon"></i>
								<h3 class="card-title">Exercises</h3>
							</div>
						</div>
					</a>
				</div>

				<!-- 9. Study Materials -->
				<div class="col">
					<a href="study_materials/index.php" class="text-decoration-none">
						<div class="resource-card" style="background: linear-gradient(135deg, #0369a1, #0ea5e9);">
							<div class="card-body">
								<i class="bi bi-file-earmark-arrow-down-fill card-icon"></i>
								<h3 class="card-title">Study Materials</h3>
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
    </div><!-- /.main-wrapper -->

    <?php include INCLUDES_PATH . '/adverts.php'; ?>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
	<?php include INCLUDES_PATH . '/footer.php'; ?>

	</body>
</html>