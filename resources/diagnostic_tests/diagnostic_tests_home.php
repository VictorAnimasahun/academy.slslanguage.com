<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Diagnostic Tests | EduHub</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
	<?php include INCLUDES_PATH . '/navbar_styles.php'; ?>

	<style>
		/* page_specific styles */
		.main-wrapper {
			padding: 2rem 1.5rem;
			min-height: 100vh;
		}

		/* Test Type Selection Cards */
		.test-selection-grid {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
			gap: 1.5rem;
			margin-bottom: 2rem;
		}

		.test-type-card {
			border-radius: 16px;
			overflow: hidden;
			box-shadow: 0 4px 20px rgba(0,0,0,0.08);
			transition: all 0.3s ease;
			cursor: pointer;
			border: 3px solid transparent;
			text-decoration: none;
			display: block;
		}

		.test-type-card:hover {
			transform: translateY(-5px);
			box-shadow: 0 12px 30px rgba(0,0,0,0.15);
		}

		.test-type-card .card-body {
			padding: 2rem;
			text-align: center;
			color: white;
		}

		.test-icon {
			font-size: 3rem;
			margin-bottom: 1rem;
		}

		.breadcrumb {
			background: transparent;
			padding: 0;
			margin-bottom: 1.5rem;
		}

		.breadcrumb-item + .breadcrumb-item::before {
			content: "›";
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

	<main class="main-wrapper">
		<div class="container">
			<!-- Breadcrumb -->
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="resources.php">Resources</a></li>
					<li class="breadcrumb-item active">Diagnostic Tests</li>
				</ol>
			</nav>

			<h1 class="page-title display-5 mb-2">Diagnostic Tests</h1>
			<p class="page-subtitle mb-4">Assess your current level in IELTS, CELPIP, or Basic English</p>

			<!-- Test Type Selection -->
			<div class="test-selection-grid">
				<!-- Basic English Card -->
				<a href="diagnostic_basic_english.php" class="test-type-card" 
					style="background: linear-gradient(135deg, #3b82f6, #60a5fa);">
					<div class="card-body">
						<i class="bi bi-book test-icon"></i>
						<h3 class="mb-2">Basic English</h3>
						<p class="mb-0 opacity-90">Grammar & Vocabulary</p>
						<p class="mb-0 opacity-90 small">Level Assessment</p>
					</div>
				</a>

				<!-- IELTS Card -->
				<a href="diagnostic_ielts.php" class="test-type-card" 
					style="background: linear-gradient(135deg, #ec4899, #f43f5e);">
					<div class="card-body">
						<i class="bi bi-pencil-square test-icon"></i>
						<h3 class="mb-2">IELTS</h3>
						<p class="mb-0 opacity-90">Academic & General Training</p>
						<p class="mb-0 opacity-90 small">Diagnostic Assessment</p>
					</div>
				</a>

				<!-- CELPIP Card -->
				<a href="diagnostic_celpip.php" class="test-type-card" 
					style="background: linear-gradient(135deg, #8b5cf6, #a78bfa);">
					<div class="card-body">
						<i class="bi bi-chat-left-text test-icon"></i>
						<h3 class="mb-2">CELPIP</h3>
						<p class="mb-0 opacity-90">General & General LS</p>
						<p class="mb-0 opacity-90 small">Diagnostic Assessment</p>
					</div>
				</a>
			</div>

		</div>
	</main>

	<?php include INCLUDES_PATH . '/adverts.php'; ?>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
</body>
</html>