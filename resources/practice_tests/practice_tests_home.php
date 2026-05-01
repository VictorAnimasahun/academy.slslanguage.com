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
	<title>Practice Tests | EduHub</title>
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
		}

		.test-type-card:hover {
			transform: translateY(-5px);
			box-shadow: 0 12px 30px rgba(0,0,0,0.15);
		}

		.test-type-card.active {
			border-color: #ffffff;
			box-shadow: 0 8px 30px rgba(0,0,0,0.2);
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

		/* Navigation Tabs */
		.test-nav {
			background: white;
			border-radius: 12px;
			padding: 1rem;
			margin-bottom: 2rem;
			box-shadow: 0 2px 10px rgba(0,0,0,0.05);
		}

		.test-nav .nav-pills .nav-link {
			border-radius: 8px;
			padding: 0.75rem 1.5rem;
			color: #6b7280;
			font-weight: 500;
			transition: all 0.3s;
		}

		.test-nav .nav-pills .nav-link.active {
			background: linear-gradient(135deg, #3b82f6, #60a5fa);
		}

		.test-nav .nav-pills .nav-link:hover:not(.active) {
			background: #f3f4f6;
		}

		/* Test Content */
		.test-content {
			background: white;
			border-radius: 16px;
			padding: 2.5rem;
			box-shadow: 0 4px 20px rgba(0,0,0,0.08);
			min-height: 400px;
		}

		.test-item {
			background: #f9fafb;
			border-radius: 12px;
			padding: 1.5rem;
			margin-bottom: 1rem;
			border-left: 4px solid #3b82f6;
			transition: all 0.3s;
		}

		.test-item:hover {
			background: #f3f4f6;
			transform: translateX(5px);
		}

		.test-item-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 0.75rem;
		}

		.test-title {
			font-size: 1.1rem;
			font-weight: 600;
			color: #1f2937;
			margin: 0;
		}

		.test-badge {
			padding: 0.25rem 0.75rem;
			border-radius: 20px;
			font-size: 0.8rem;
			font-weight: 600;
		}

		.test-description {
			color: #6b7280;
			margin-bottom: 1rem;
			line-height: 1.6;
		}

		.test-meta {
			display: flex;
			gap: 1.5rem;
			font-size: 0.9rem;
			color: #9ca3af;
			margin-bottom: 1rem;
		}

		.test-meta span {
			display: flex;
			align-items: center;
			gap: 0.25rem;
		}

		.btn-start-test {
			background: linear-gradient(135deg, #3b82f6, #60a5fa);
			color: white;
			border: none;
			padding: 0.5rem 1.5rem;
			border-radius: 8px;
			font-weight: 500;
			transition: all 0.3s;
		}

		.btn-start-test:hover {
			transform: translateY(-2px);
			box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
			color: white;
		}

		.back-btn {
			margin-bottom: 1.5rem;
		}

		.section-hidden {
			display: none;
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
				<ol class="breadcrumb" id="breadcrumb">
					<li class="breadcrumb-item"><a href="resources_home.php">Resources</a></li>
					<li class="breadcrumb-item active">Practice Tests</li>
				</ol>
			</nav>

			<h1 class="page-title display-5 mb-2">Practice Tests</h1>
			<p class="page-subtitle mb-4">Choose your test type and start practicing</p>

			<!-- Test Type Selection (Initial View) -->
			<div id="testTypeSelection">
				<div class="test-selection-grid">
					<!-- IELTS Card -->
					<div class="test-type-card" onclick="showTestSection('ielts')" 
						style="background: linear-gradient(135deg, #ec4899, #f43f5e);">
						<div class="card-body">
							<i class="bi bi-pencil-square test-icon"></i>
							<h3 class="mb-2">IELTS</h3>
							<p class="mb-0 opacity-90">Academic & General Training</p>
							<p class="mb-0 opacity-90 small">Writing • Speaking</p>
						</div>
					</div>

					<!-- CELPIP Card -->
					<div class="test-type-card" onclick="showTestSection('celpip')" 
						style="background: linear-gradient(135deg, #8b5cf6, #a78bfa);">
						<div class="card-body">
							<i class="bi bi-chat-left-text test-icon"></i>
							<h3 class="mb-2">CELPIP</h3>
							<p class="mb-0 opacity-90">General & General LS</p>
							<p class="mb-0 opacity-90 small">Writing • Speaking</p>
						</div>
					</div>

					<!-- PTE Card -->
					<div class="test-type-card" onclick="showTestSection('pte')" 
						style="background: linear-gradient(135deg, #10b981, #34d399);">
						<div class="card-body">
							<i class="bi bi-laptop test-icon"></i>
							<h3 class="mb-2">PTE Academic</h3>
							<p class="mb-0 opacity-90">Pearson Test of English</p>
							<p class="mb-0 opacity-90 small">Writing • Speaking</p>
						</div>
					</div>
				</div>
			</div>

			<!-- IELTS Section -->
			<div id="ieltsSection" class="section-hidden">
				<button class="btn btn-outline-secondary back-btn" onclick="backToSelection()">
					<i class="bi bi-arrow-left"></i> Back to Test Types
				</button>

				<div class="test-nav">
					<ul class="nav nav-pills" id="ieltsTabs" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-link active" data-bs-toggle="pill" data-bs-target="#ielts-writing-academic">
								Writing - Academic
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" data-bs-toggle="pill" data-bs-target="#ielts-writing-general">
								Writing - General
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" data-bs-toggle="pill" data-bs-target="#ielts-speaking">
								Speaking
							</button>
						</li>
					</ul>
				</div>

				<div class="tab-content">
					<!-- IELTS Writing Academic -->
					<div class="tab-pane fade show active" id="ielts-writing-academic">
						<div class="test-content">
							<h3 class="mb-4">IELTS Academic Writing</h3>
							
							<!-- IELTS Writing Academic - Task 1 Tests -->
							<h5 class="text-muted mb-3">Task 1 - Report Writing (150 words, 20 minutes)</h5>
							
							<!-- Exercise 1 Line Graph -->
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Line Graph: Internet Usage</h4>
									<span class="test-badge bg-primary text-white">Task 1</span>
								</div>
								<p class="test-description">
									The line graph shows the percentage of internet users across different age groups between 2010 and 2020. Includes actual chart visualization.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 20 min</span>
									<span><i class="bi bi-file-text"></i> 150 words</span>
									<span><i class="bi bi-graph-up"></i> Line Graph</span>
								</div>
								<button class="btn btn-start-test" data-test-id="ielts-ac-task1-line">Start Test</button>
							</div>

							<!-- Exercise 2 Bar Chart -->
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Bar Chart: Education Spending</h4>
									<span class="test-badge bg-primary text-white">Task 1</span>
								</div>
								<p class="test-description">
									The bar chart compares government spending on education as a percentage of GDP in five countries. Includes actual chart visualization.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 20 min</span>
									<span><i class="bi bi-file-text"></i> 150 words</span>
									<span><i class="bi bi-bar-chart"></i> Bar Chart</span>
								</div>
								<button class="btn btn-start-test" data-test-id="ielts-ac-task1-bar">Start Test</button>
							</div>

							<!-- Exercise 3 Pie Chart -->
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Pie Chart: Energy Sources</h4>
									<span class="test-badge bg-primary text-white">Task 1</span>
								</div>
								<p class="test-description">
									The pie charts show the sources of electricity generation in Australia in 2000 and 2020. Includes actual chart visualization.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 20 min</span>
									<span><i class="bi bi-file-text"></i> 150 words</span>
									<span><i class="bi bi-pie-chart"></i> Pie Chart</span>
								</div>
								<button class="btn btn-start-test" data-test-id="ielts-ac-task1-pie">Start Test</button>
							</div>

							<!-- Exercise 4 Table -->
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Table: Population Growth</h4>
									<span class="test-badge bg-primary text-white">Task 1</span>
								</div>
								<p class="test-description">
									The table shows population figures for four countries between 2000 and 2020. Includes actual table visualization.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 20 min</span>
									<span><i class="bi bi-file-text"></i> 150 words</span>
									<span><i class="bi bi-table"></i> Table</span>
								</div>
								<button class="btn btn-start-test" data-test-id="ielts-ac-task1-table">Start Test</button>
							</div>

							<!-- Exercise 5 Process-->
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Process Diagram: Coffee Production</h4>
									<span class="test-badge bg-primary text-white">Task 1</span>
								</div>
								<p class="test-description">
									The diagram illustrates the process of producing coffee from picking to packaging. Includes actual process diagram.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 20 min</span>
									<span><i class="bi bi-file-text"></i> 150 words</span>
									<span><i class="bi bi-diagram-3"></i> Process</span>
								</div>
								<button class="btn btn-start-test" data-test-id="ielts-ac-task1-process">Start Test</button>
							</div>

							<!-- Exercise 6 Map-->
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Map: Town Development</h4>
									<span class="test-badge bg-primary text-white">Task 1</span>
								</div>
								<p class="test-description">
									The maps show how a town changed between 1990 and 2020. Includes actual map comparison.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 20 min</span>
									<span><i class="bi bi-file-text"></i> 150 words</span>
									<span><i class="bi bi-map"></i> Map</span>
								</div>
								<button class="btn btn-start-test" data-test-id="ielts-ac-task1-map">Start Test</button>
							</div>

							<!-- IELTS Writing Academic - Task 2 Tests -->
							<h5 class="text-muted mb-3 mt-4">Task 2 - Essay Writing (250 words, 40 minutes)</h5>
							
							<!-- Exercise 1 Education-->
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Education: Technology in Classrooms</h4>
									<span class="test-badge bg-success text-white">Task 2</span>
								</div>
								<p class="test-description">
									Some people believe technology in classrooms enhances learning, while others think it is a distraction. Discuss both views and give your opinion.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 40 min</span>
									<span><i class="bi bi-file-text"></i> 250 words</span>
									<span><i class="bi bi-chat-square-text"></i> Discussion</span>
								</div>
								<button class="btn btn-start-test" data-test-id="ielts-ac-task2-tech">Start Test</button>
							</div>

							<!-- Exercise 2 Environment-->
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Environment: Climate Change Solutions</h4>
									<span class="test-badge bg-success text-white">Task 2</span>
								</div>
								<p class="test-description">
									Climate change is a pressing issue. What are the main causes and what solutions can individuals and governments implement?
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 40 min</span>
									<span><i class="bi bi-file-text"></i> 250 words</span>
									<span><i class="bi bi-lightbulb"></i> Problem/Solution</span>
								</div>
								<button class="btn btn-start-test" data-test-id="ielts-ac-task2-climate">Start Test</button>
							</div>

							<!-- Exercise 3 Remote-->
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Work: Remote Work Advantages</h4>
									<span class="test-badge bg-success text-white">Task 2</span>
								</div>
								<p class="test-description">
									Working from home has become increasingly common. Do the advantages of remote work outweigh the disadvantages?
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 40 min</span>
									<span><i class="bi bi-file-text"></i> 250 words</span>
									<span><i class="bi bi-balance-scale"></i> Advantage/Disadvantage</span>
								</div>
								<button class="btn btn-start-test" data-test-id="ielts-ac-task2-remote">Start Test</button>
							</div>
						</div>
					</div>

					<!-- IELTS Writing General -->
					<div class="tab-pane fade" id="ielts-writing-general">
						<div class="test-content">
							<h3 class="mb-4">IELTS General Training Writing</h3>
							
							<!-- IELTS Writing General - Task 1 Tests -->
							<h5 class="text-muted mb-3">Task 1 - Letter Writing (150 words, 20 minutes)</h5>
							
							<!-- Exercise 1 Complaint -->
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Formal Letter: Complaint to Company</h4>
									<span class="test-badge bg-danger text-white">Formal</span>
								</div>
								<p class="test-description">
									You recently bought a product online but it arrived damaged. Write a letter to the company to complain and request a replacement.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 20 min</span>
									<span><i class="bi bi-file-text"></i> 150 words</span>
									<span><i class="bi bi-envelope"></i> Complaint</span>
								</div>
								<button class="btn btn-start-test">Start Test</button>
							</div>

							<!-- Exercise 2 Invitation -->
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Semi-Formal: Invitation to Colleague</h4>
									<span class="test-badge bg-warning text-dark">Semi-Formal</span>
								</div>
								<p class="test-description">
									Write a letter to a colleague inviting them to your farewell party. Explain when and where it will be held and why you're leaving.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 20 min</span>
									<span><i class="bi bi-file-text"></i> 150 words</span>
									<span><i class="bi bi-calendar-event"></i> Invitation</span>
								</div>
								<button class="btn btn-start-test">Start Test</button>
							</div>

							<!-- Exercise 3 Advice -->
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Informal Letter: Advice to Friend</h4>
									<span class="test-badge bg-info text-white">Informal</span>
								</div>
								<p class="test-description">
									Your friend is planning to visit your city. Write a letter suggesting places to visit and things to do.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 20 min</span>
									<span><i class="bi bi-file-text"></i> 150 words</span>
									<span><i class="bi bi-chat-heart"></i> Advice</span>
								</div>
								<button class="btn btn-start-test">Start Test</button>
							</div>

							
							<!-- IELTS Writing General - Task 2 Tests -->
							<h5 class="text-muted mb-3 mt-4">Task 2 - Essay Writing (250 words, 40 minutes)</h5>
							
							<!-- Exercise 1 Fast food -->
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Health: Fast Food Consumption</h4>
									<span class="test-badge bg-success text-white">Task 2</span>
								</div>
								<p class="test-description">
									Fast food consumption is increasing worldwide. What are the effects on individuals and society? What measures can be taken?
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 40 min</span>
									<span><i class="bi bi-file-text"></i> 250 words</span>
									<span><i class="bi bi-lightbulb"></i> Problem/Solution</span>
								</div>
								<button class="btn btn-start-test">Start Test</button>
							</div>

							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Technology: Social Media Impact</h4>
									<span class="test-badge bg-success text-white">Task 2</span>
								</div>
								<p class="test-description">
									Social media has changed how people communicate. Do you think this is a positive or negative development?
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 40 min</span>
									<span><i class="bi bi-file-text"></i> 250 words</span>
									<span><i class="bi bi-hand-thumbs-up"></i> Opinion</span>
								</div>
								<button class="btn btn-start-test">Start Test</button>
							</div>
						</div>
					</div>

					<!-- IELTS Speaking -->
					<div class="tab-pane fade" id="ielts-speaking">
						<div class="test-content">
							<h3 class="mb-4">IELTS Speaking Test</h3>
							
							<!-- Part 1 -->
							<h5 class="text-muted mb-3">Part 1 - Introduction (4-5 minutes)</h5>
							
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Home & Family</h4>
									<span class="test-badge bg-primary text-white">Part 1</span>
								</div>
								<p class="test-description">
									Questions about your home, family, work/studies, and familiar topics.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 4-5 min</span>
									<span><i class="bi bi-mic"></i> 8-10 questions</span>
								</div>
								<button class="btn btn-start-test" data-test-id="ielts-speak-p1-home">Start Practice</button>
							</div>

							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Hobbies & Interests</h4>
									<span class="test-badge bg-primary text-white">Part 1</span>
								</div>
								<p class="test-description">
									Questions about your hobbies, free time activities, and interests.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 4-5 min</span>
									<span><i class="bi bi-mic"></i> 8-10 questions</span>
								</div>
								<button class="btn btn-start-test" data-test-id="ielts-speak-p1-hobbies">Start Practice</button>
							</div>

							<!-- Part 2 -->
							<h5 class="text-muted mb-3 mt-4">Part 2 - Long Turn (3-4 minutes)</h5>
							
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Describe a Memorable Journey</h4>
									<span class="test-badge bg-success text-white">Part 2</span>
								</div>
								<p class="test-description">
									Describe a memorable journey you have made. You should say: where you went, who you went with, what you did, and explain why it was memorable.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 2 min speech</span>
									<span><i class="bi bi-hourglass"></i> 1 min preparation</span>
								</div>
								<button class="btn btn-start-test" data-test-id="ielts-speak-p2-journey">Start Practice</button>
							</div>

							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Describe a Person Who Inspired You</h4>
									<span class="test-badge bg-success text-white">Part 2</span>
								</div>
								<p class="test-description">
									Describe a person who has inspired you. You should say:
								</p>
								<ul>
									<li>who this person is</li>
									<li>how you know them, what they did</li>
									<li>what they did</li>
									<li>and explain why they inspired you.</li>
								</ul>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 2 min speech</span>
									<span><i class="bi bi-hourglass"></i> 1 min preparation</span>
								</div>
								<button class="btn btn-start-test" data-test-id="ielts-speak-p2-person">Start Practice</button>
							</div>

							<!-- Part 3 -->
							<h5 class="text-muted mb-3 mt-4">Part 3 - Discussion (4-5 minutes)</h5>
							
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Travel & Tourism</h4>
									<span class="test-badge bg-warning text-dark">Part 3</span>
								</div>
								<p class="test-description">
									Abstract discussion questions about travel trends, tourism impact, and cultural exchange.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 4-5 min</span>
									<span><i class="bi bi-chat-square-quote"></i> Abstract discussion</span>
								</div>
								<button class="btn btn-start-test" data-test-id="ielts-speak-p3-travel">Start Practice</button>
							</div>

							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Education & Learning</h4>
									<span class="test-badge bg-warning text-dark">Part 3</span>
								</div>
								<p class="test-description">
									Discussion about education systems, learning methods, and the role of technology in education.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 4-5 min</span>
									<span><i class="bi bi-chat-square-quote"></i> Abstract discussion</span>
								</div>
								<button class="btn btn-start-test" data-test-id="ielts-speak-p3-education">Start Practice</button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- CELPIP Section -->
			<div id="celpipSection" class="section-hidden">
				<button class="btn btn-outline-secondary back-btn" onclick="backToSelection()">
					<i class="bi bi-arrow-left"></i> Back to Test Types
				</button>

				<div class="test-nav">
					<ul class="nav nav-pills" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-link active" data-bs-toggle="pill" data-bs-target="#celpip-writing">
								Writing
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" data-bs-toggle="pill" data-bs-target="#celpip-speaking">
								Speaking
							</button>
						</li>
					</ul>
				</div>

				<div class="tab-content">
					<!-- CELPIP Writing -->
					<div class="tab-pane fade show active" id="celpip-writing">
						<div class="test-content">
							<h3 class="mb-4">CELPIP Writing Tasks</h3>
							
							<!-- Task 1 -->
							<h5 class="text-muted mb-3">Task 1 - Email Writing (150-200 words, 27 minutes)</h5>
							
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Email to Property Manager</h4>
									<span class="test-badge bg-primary text-white">Task 1</span>
								</div>
								<p class="test-description">
									Write an email to your property manager about a maintenance issue in your apartment. Describe the problem and suggest a solution.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 27 min</span>
									<span><i class="bi bi-file-text"></i> 150-200 words</span>
									<span><i class="bi bi-envelope"></i> Email</span>
								</div>
								<button class="btn btn-start-test">Start Test</button>
							</div>

							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Email to Friend About Event</h4>
									<span class="test-badge bg-primary text-white">Task 1</span>
								</div>
								<p class="test-description">
									Write an email to a friend inviting them to a community event. Provide details and explain why they should attend.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 27 min</span>
									<span><i class="bi bi-file-text"></i> 150-200 words</span>
									<span><i class="bi bi-envelope"></i> Email</span>
								</div>
								<button class="btn btn-start-test">Start Test</button>
							</div>

							<!-- Task 2 -->
							<h5 class="text-muted mb-3 mt-4">Task 2 - Survey Response (150-200 words, 26 minutes)</h5>
							
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Survey: Public Transportation</h4>
									<span class="test-badge bg-success text-white">Task 2</span>
								</div>
								<p class="test-description">
									Your city is conducting a survey about public transportation. Choose one option and explain your choice with reasons and examples.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 26 min</span>
									<span><i class="bi bi-file-text"></i> 150-200 words</span>
									<span><i class="bi bi-card-checklist"></i> Survey</span>
								</div>
								<button class="btn btn-start-test">Start Test</button>
							</div>

							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Survey: Community Center Activities</h4>
									<span class="test-badge bg-success text-white">Task 2</span>
								</div>
								<p class="test-description">
									A local community center is asking for opinions on new activities. Choose your preferred option and support your choice.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 26 min</span>
									<span><i class="bi bi-file-text"></i> 150-200 words</span>
									<span><i class="bi bi-card-checklist"></i> Survey</span>
								</div>
								<button class="btn btn-start-test">Start Test</button>
							</div>
						</div>
					</div>

					<!-- CELPIP Speaking -->
					<div class="tab-pane fade" id="celpip-speaking">
						<div class="test-content">
							<h3 class="mb-4">CELPIP Speaking Tasks</h3>
							
							<!-- Tasks 1-4 -->
							<h5 class="text-muted mb-3">Tasks 1-4 - Personal & Descriptive (Various times)</h5>
							
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Task 1: Giving Advice</h4>
									<span class="test-badge bg-primary text-white">Personal</span>
								</div>
								<p class="test-description">
									Give advice to a friend who is planning to buy their first car. What should they consider?
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 90 sec</span>
									<span><i class="bi bi-hourglass"></i> 30 sec prep</span>
								</div>
								<button class="btn btn-start-test">Start Practice</button>
							</div>

							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Task 2: Talking About Personal Experience</h4>
									<span class="test-badge bg-primary text-white">Personal</span>
								</div>
								<p class="test-description">
									Talk about a time when you had to make an important decision. What was the situation and what did you decide?
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 60 sec</span>
									<span><i class="bi bi-hourglass"></i> 30 sec prep</span>
								</div>
								<button class="btn btn-start-test">Start Practice</button>
							</div>

							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Task 3: Describing a Scene</h4>
									<span class="test-badge bg-info text-white">Descriptive</span>
								</div>
								<p class="test-description">
									Look at an image and describe what you see in as much detail as possible.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 60 sec</span>
									<span><i class="bi bi-hourglass"></i> 30 sec prep</span>
								</div>
								<button class="btn btn-start-test">Start Practice</button>
							</div>

							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Task 4: Making Predictions</h4>
									<span class="test-badge bg-info text-white">Descriptive</span>
								</div>
								<p class="test-description">
									Look at an image and predict what you think will happen next. Explain your reasoning.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 60 sec</span>
									<span><i class="bi bi-hourglass"></i> 30 sec prep</span>
								</div>
								<button class="btn btn-start-test">Start Practice</button>
							</div>

							<!-- Tasks 5-8 -->
							<h5 class="text-muted mb-3 mt-4">Tasks 5-8 - Problem Solving & Opinion (Various times)</h5>
							
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Task 5: Comparing and Persuading</h4>
									<span class="test-badge bg-success text-white">Problem Solving</span>
								</div>
								<p class="test-description">
									Compare two options for a vacation destination and persuade your friend which one to choose.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 60 sec</span>
									<span><i class="bi bi-hourglass"></i> 60 sec prep</span>
								</div>
								<button class="btn btn-start-test">Start Practice</button>
							</div>

							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Task 6: Dealing with a Difficult Situation</h4>
									<span class="test-badge bg-success text-white">Problem Solving</span>
								</div>
								<p class="test-description">
									Your neighbor plays loud music late at night. Call them to resolve the situation politely.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 60 sec</span>
									<span><i class="bi bi-hourglass"></i> 60 sec prep</span>
								</div>
								<button class="btn btn-start-test">Start Practice</button>
							</div>

							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Task 7: Expressing Opinions</h4>
									<span class="test-badge bg-warning text-dark">Opinion</span>
								</div>
								<p class="test-description">
									Do you agree that technology has improved quality of life? Explain your opinion with specific examples.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 90 sec</span>
									<span><i class="bi bi-hourglass"></i> 30 sec prep</span>
								</div>
								<button class="btn btn-start-test">Start Practice</button>
							</div>

							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Task 8: Describing an Unusual Situation</h4>
									<span class="test-badge bg-warning text-dark">Opinion</span>
								</div>
								<p class="test-description">
									Look at an unusual image and describe what might be happening and why it's unusual.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 60 sec</span>
									<span><i class="bi bi-hourglass"></i> 30 sec prep</span>
								</div>
								<button class="btn btn-start-test">Start Practice</button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- PTE Section -->
			<div id="pteSection" class="section-hidden">
				<button class="btn btn-outline-secondary back-btn" onclick="backToSelection()">
					<i class="bi bi-arrow-left"></i> Back to Test Types
				</button>

				<div class="test-nav">
					<ul class="nav nav-pills" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-link active" data-bs-toggle="pill" data-bs-target="#pte-writing">
								Writing
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" data-bs-toggle="pill" data-bs-target="#pte-speaking">
								Speaking
							</button>
						</li>
					</ul>
				</div>

				<div class="tab-content">
					<!-- PTE Writing -->
					<div class="tab-pane fade show active" id="pte-writing">
						<div class="test-content">
							<h3 class="mb-4">PTE Academic Writing</h3>
							
							<!-- Summarize Written Text -->
							<h5 class="text-muted mb-3">Summarize Written Text (10 minutes per task)</h5>
							
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Artificial Intelligence in Healthcare</h4>
									<span class="test-badge bg-primary text-white">Summarize</span>
								</div>
								<p class="test-description">
									Read a passage about AI applications in healthcare and summarize it in one sentence (5-75 words).
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 10 min</span>
									<span><i class="bi bi-file-text"></i> 5-75 words</span>
									<span><i class="bi bi-file-earmark-text"></i> Summary</span>
								</div>
								<button class="btn btn-start-test">Start Test</button>
							</div>

							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Renewable Energy Transition</h4>
									<span class="test-badge bg-primary text-white">Summarize</span>
								</div>
								<p class="test-description">
									Read a passage about the global shift to renewable energy and write a one-sentence summary.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 10 min</span>
									<span><i class="bi bi-file-text"></i> 5-75 words</span>
									<span><i class="bi bi-file-earmark-text"></i> Summary</span>
								</div>
								<button class="btn btn-start-test">Start Test</button>
							</div>

							<!-- Write Essay -->
							<h5 class="text-muted mb-3 mt-4">Write Essay (200-300 words, 20 minutes)</h5>
							
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Online Education vs Traditional Learning</h4>
									<span class="test-badge bg-success text-white">Essay</span>
								</div>
								<p class="test-description">
									Online education is becoming increasingly popular. Discuss the advantages and disadvantages compared to traditional classroom learning.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 20 min</span>
									<span><i class="bi bi-file-text"></i> 200-300 words</span>
									<span><i class="bi bi-chat-square-text"></i> Argumentative</span>
								</div>
								<button class="btn btn-start-test">Start Test</button>
							</div>

							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Urbanization and Its Effects</h4>
									<span class="test-badge bg-success text-white">Essay</span>
								</div>
								<p class="test-description">
									Cities are growing rapidly worldwide. What are the causes and effects of urbanization on society and the environment?
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 20 min</span>
									<span><i class="bi bi-file-text"></i> 200-300 words</span>
									<span><i class="bi bi-lightbulb"></i> Cause/Effect</span>
								</div>
								<button class="btn btn-start-test">Start Test</button>
							</div>

							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Work-Life Balance in Modern Society</h4>
									<span class="test-badge bg-success text-white">Essay</span>
								</div>
								<p class="test-description">
									Many people struggle to maintain work-life balance. What factors contribute to this and what solutions can be implemented?
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 20 min</span>
									<span><i class="bi bi-file-text"></i> 200-300 words</span>
									<span><i class="bi bi-lightbulb"></i> Problem/Solution</span>
								</div>
								<button class="btn btn-start-test">Start Test</button>
							</div>
						</div>
					</div>

					<!-- PTE Speaking -->
					<div class="tab-pane fade" id="pte-speaking">
						<div class="test-content">
							<h3 class="mb-4">PTE Academic Speaking</h3>
							
							<!-- Read Aloud -->
							<h5 class="text-muted mb-3">Read Aloud (30-40 seconds per task)</h5>
							
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Scientific Discovery Text</h4>
									<span class="test-badge bg-primary text-white">Read Aloud</span>
								</div>
								<p class="test-description">
									Read a text about a recent scientific discovery aloud with correct pronunciation and intonation.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 30-40 sec</span>
									<span><i class="bi bi-hourglass"></i> 30-40 sec prep</span>
								</div>
								<button class="btn btn-start-test">Start Practice</button>
							</div>

							<!-- Repeat Sentence -->
							<h5 class="text-muted mb-3 mt-4">Repeat Sentence (15 seconds)</h5>
							
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Sentence Repetition Practice</h4>
									<span class="test-badge bg-info text-white">Repeat</span>
								</div>
								<p class="test-description">
									Listen to a sentence and repeat it exactly as you hear it. Tests memory and fluency.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 15 sec</span>
									<span><i class="bi bi-volume-up"></i> Audio prompt</span>
								</div>
								<button class="btn btn-start-test">Start Practice</button>
							</div>

							<!-- Describe Image -->
							<h5 class="text-muted mb-3 mt-4">Describe Image (40 seconds)</h5>
							
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Population Growth Chart</h4>
									<span class="test-badge bg-success text-white">Describe</span>
								</div>
								<p class="test-description">
									Describe a chart showing population growth trends across different regions.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 40 sec</span>
									<span><i class="bi bi-hourglass"></i> 25 sec prep</span>
								</div>
								<button class="btn btn-start-test">Start Practice</button>
							</div>

							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Process Diagram</h4>
									<span class="test-badge bg-success text-white">Describe</span>
								</div>
								<p class="test-description">
									Describe a diagram showing the recycling process from start to finish.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 40 sec</span>
									<span><i class="bi bi-hourglass"></i> 25 sec prep</span>
								</div>
								<button class="btn btn-start-test">Start Practice</button>
							</div>

							<!-- Re-tell Lecture -->
							<h5 class="text-muted mb-3 mt-4">Re-tell Lecture (40 seconds)</h5>
							
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Climate Change Lecture</h4>
									<span class="test-badge bg-warning text-dark">Re-tell</span>
								</div>
								<p class="test-description">
									Listen to a lecture about climate change and re-tell it in your own words.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 40 sec</span>
									<span><i class="bi bi-volume-up"></i> Audio lecture</span>
								</div>
								<button class="btn btn-start-test">Start Practice</button>
							</div>

							<!-- Answer Short Question -->
							<h5 class="text-muted mb-3 mt-4">Answer Short Question (10 seconds)</h5>
							
							<div class="test-item">
								<div class="test-item-header">
									<h4 class="test-title">Quick Answer Practice</h4>
									<span class="test-badge bg-danger text-white">Quick Answer</span>
								</div>
								<p class="test-description">
									Answer short general knowledge questions in one or a few words.
								</p>
								<div class="test-meta">
									<span><i class="bi bi-clock"></i> 10 sec</span>
									<span><i class="bi bi-lightning"></i> Instant response</span>
								</div>
								<button class="btn btn-start-test">Start Practice</button>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</main>

	<?php include INCLUDES_PATH . '/adverts.php'; ?>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>

	<script>
		// Navigation Functions
		function showTestSection(testType) {
			// Hide test type selection
			document.getElementById('testTypeSelection').style.display = 'none';
			
			// Hide all test sections
			document.getElementById('ieltsSection').classList.add('section-hidden');
			document.getElementById('celpipSection').classList.add('section-hidden');
			document.getElementById('pteSection').classList.add('section-hidden');
			
			// Show selected test section
			const sectionId = testType + 'Section';
			document.getElementById(sectionId).classList.remove('section-hidden');
			
			// Update breadcrumb
			updateBreadcrumb(testType);
		}

		function backToSelection() {
			// Show test type selection
			document.getElementById('testTypeSelection').style.display = 'block';
			
			// Hide all test sections
			document.getElementById('ieltsSection').classList.add('section-hidden');
			document.getElementById('celpipSection').classList.add('section-hidden');
			document.getElementById('pteSection').classList.add('section-hidden');
			
			// Reset breadcrumb
			resetBreadcrumb();
		}

		function updateBreadcrumb(testType) {
			const breadcrumb = document.getElementById('breadcrumb');
			const testNames = {
				'ielts': 'IELTS',
				'celpip': 'CELPIP',
				'pte': 'PTE Academic'
			};
			
			breadcrumb.innerHTML = `
				<li class="breadcrumb-item"><a href="resources_home.php">Resources</a></li>
				<li class="breadcrumb-item"><a href="#" onclick="backToSelection(); return false;">Practice Tests</a></li>
				<li class="breadcrumb-item active">${testNames[testType]}</li>
			`;
		}

		function resetBreadcrumb() {
			const breadcrumb = document.getElementById('breadcrumb');
			breadcrumb.innerHTML = `
				<li class="breadcrumb-item"><a href="resources_home.php">Resources</a></li>
				<li class="breadcrumb-item active">Practice Tests</li>
			`;
		}

		// Test data structure
		const testData = {
			// IELTS Academic Writing Task 1
			'ielts-ac-task1-line': {
				type: 'ielts_academic_task1',
				title: 'Line Graph: Internet Usage',
				question: 'The line graph below shows the percentage of internet users in different age groups from 2010 to 2020.\n\nSummarize the information by selecting and reporting the main features, and make comparisons where relevant.\n\nWrite at least 150 words.',
				timeLimit: 20,
				wordTarget: 150,
				testType: 'IELTS Academic Writing Task 1',
				visualType: 'image',
				imageUrl: '../assets/images/ielts-ac-task1-line.png',
				imageAlt: 'Line graph showing internet usage by age group'
			},

			'ielts-ac-task1-bar': {
				type: 'ielts_academic_task1',
				title: 'Bar Chart: Education Spending',
				question: 'The bar chart below compares education spending as a percentage of GDP across five countries in 2020.\n\nSummarize the information by selecting and reporting the main features, and make comparisons where relevant.\n\nWrite at least 150 words.',
				timeLimit: 20,
				wordTarget: 150,
				testType: 'IELTS Academic Writing Task 1',
				visualType: 'image',
				imageUrl: '../assets/images/ielts-ac-task1-bar.png',
				imageAlt: 'Bar chart showing education spending by country'				
			},

			'ielts-ac-task1-pie': {
				type: 'ielts_academic_task1',
				title: 'Pie Chart: Energy Sources',
				question: 'The pie charts show the sources of electricity generation in Australia in 2000 and 2020.\n\nSummarize the information by selecting and reporting the main features, and make comparisons where relevant.\n\nWrite at least 150 words.',
				timeLimit: 20,
				wordTarget: 150,
				testType: 'IELTS Academic Writing Task 1',
				visualType: 'image',
				imageUrl: '../assets/images/ielts-ac-task1-pie.png',
				imageAlt: 'Pie chart showing energy sources'
			},

			'ielts-ac-task1-table': {
				type: 'ielts_academic_task1',
				title: 'Table: Population Growth',
				question: 'The table shows population figures for four countries between 2000 and 2020.\n\nSummarize the information by selecting and reporting the main features, and make comparisons where relevant.\n\nWrite at least 150 words.',
				timeLimit: 20,
				wordTarget: 150,
				testType: 'IELTS Academic Writing Task 1',	
				visualType: 'image',
				imageUrl: '../assets/images/ielts-ac-task1-table.png',
				imageAlt: 'Table showing population growth data'	
			},

			'ielts-ac-task1-process': {
				type: 'ielts_academic_task1',
				title: 'Process Diagram: Coffee Production',
				question: 'The diagram illustrates the process of producing coffee from picking to packaging.\n\nSummarize the information by selecting and reporting the main features.\n\nWrite at least 150 words.',
				timeLimit: 20,
				wordTarget: 150,
				testType: 'IELTS Academic Writing Task 1',
				visualType: 'image',
				imageUrl: '../assets/images/ielts-ac-task1-process.png',
				imageAlt: 'Coffee production process diagram'
			},
			'ielts-ac-task1-map': {
				type: 'ielts_academic_task1',
				title: 'Map: Town Development',
				question: 'The maps show how a town changed between 1990 and 2020.\n\nSummarize the information by selecting and reporting the main features, and make comparisons where relevant.\n\nWrite at least 150 words.',
				timeLimit: 20,
				wordTarget: 150,
				testType: 'IELTS Academic Writing Task 1',
				visualType: 'image',
				imageUrl: '../assets/images/ielts-ac-task1-map.jpeg',
				imageAlt: 'Town development maps 1990 vs 2020'
			},

			// IELTS Academic Writing Task 2
			'ielts-ac-task2-tech': {
				type: 'ielts_academic_task2',
				title: 'Education: Technology in Classrooms',
				question: 'Some people believe that technology in classrooms enhances learning, while others think it is a distraction.\n\nDiscuss both views and give your own opinion.\n\nGive reasons for your answer and include any relevant examples from your own knowledge or experience.\n\nWrite at least 250 words.',
				timeLimit: 40,
				wordTarget: 250,
				testType: 'IELTS Academic Writing Task 2'
			},
			'ielts-ac-task2-climate': {
				type: 'ielts_academic_task2',
				title: 'Environment: Climate Change Solutions',
				question: 'Climate change is one of the most pressing issues facing the world today.\n\nWhat are the main causes of climate change and what solutions can individuals and governments implement to address this problem?\n\nGive reasons for your answer and include any relevant examples from your own knowledge or experience.\n\nWrite at least 250 words.',
				timeLimit: 40,
				wordTarget: 250,
				testType: 'IELTS Academic Writing Task 2'
			},
			'ielts-ac-task2-remote': {
				type: 'ielts_academic_task2',
				title: 'Work: Remote Work Advantages',
				question: 'Working from home has become increasingly common in recent years.\n\nDo the advantages of remote work outweigh the disadvantages?\n\nGive reasons for your answer and include any relevant examples from your own knowledge or experience.\n\nWrite at least 250 words.',
				timeLimit: 40,
				wordTarget: 250,
				testType: 'IELTS Academic Writing Task 2'
			},

			// IELTS General Writing Task 1
			'ielts-gt-task1-complaint': {
				type: 'ielts_general_task1',
				title: 'Formal Letter: Complaint to Company',
				question: 'You recently bought a product online but it arrived damaged.\n\nWrite a letter to the company. In your letter:\n• Explain what you ordered and when\n• Describe the damage\n• Say what action you would like the company to take\n\nWrite at least 150 words.',
				timeLimit: 20,
				wordTarget: 150,
				testType: 'IELTS General Writing Task 1'
			},
			'ielts-gt-task1-invitation': {
				type: 'ielts_general_task1',
				title: 'Semi-Formal: Invitation to Colleague',
				question: 'You are leaving your job and want to invite a colleague to your farewell party.\n\nWrite a letter to your colleague. In your letter:\n• Invite them to the party\n• Explain when and where it will be held\n• Say why you are leaving\n\nWrite at least 150 words.',
				timeLimit: 20,
				wordTarget: 150,
				testType: 'IELTS General Writing Task 1'
			},
			'ielts-gt-task1-advice': {
				type: 'ielts_general_task1',
				title: 'Informal Letter: Advice to Friend',
				question: 'A friend is planning to visit your city for the first time.\n\nWrite a letter to your friend. In your letter:\n• Suggest places they should visit\n• Recommend things to do\n• Give advice about transportation and accommodation\n\nWrite at least 150 words.',
				timeLimit: 20,
				wordTarget: 150,
				testType: 'IELTS General Writing Task 1'
			},

			// IELTS General Writing Task 2
			'ielts-gt-task2-health': {
				type: 'ielts_general_task2',
				title: 'Health: Fast Food Consumption',
				question: 'Fast food consumption is increasing worldwide.\n\nWhat are the effects of this trend on individuals and society? What measures can be taken to address this issue?\n\nGive reasons for your answer and include any relevant examples from your own knowledge or experience.\n\nWrite at least 250 words.',
				timeLimit: 40,
				wordTarget: 250,
				testType: 'IELTS General Writing Task 2'
			},
			'ielts-gt-task2-social': {
				type: 'ielts_general_task2',
				title: 'Technology: Social Media Impact',
				question: 'Social media has changed the way people communicate with each other.\n\nDo you think this is a positive or negative development?\n\nGive reasons for your answer and include any relevant examples from your own knowledge or experience.\n\nWrite at least 250 words.',
				timeLimit: 40,
				wordTarget: 250,
				testType: 'IELTS General Writing Task 2'
			},

			// CELPIP Writing Task 1
			'celpip-task1-property': {
				type: 'celpip_task1',
				title: 'Email to Property Manager',
				question: 'Your apartment has a maintenance issue that needs to be addressed.\n\nWrite an email to your property manager (in about 150-200 words). Your email should:\n• Describe the maintenance issue\n• Explain how it is affecting you\n• Suggest a solution or request action',
				timeLimit: 27,
				wordTarget: 175,
				testType: 'CELPIP Writing Task 1'
			},
			'celpip-task1-friend': {
				type: 'celpip_task1',
				title: 'Email to Friend About Event',
				question: 'There is a community event happening next week that you think your friend would enjoy.\n\nWrite an email to your friend (in about 150-200 words). Your email should:\n• Describe the event\n• Explain why you think they would enjoy it\n• Suggest meeting up to attend together',
				timeLimit: 27,
				wordTarget: 175,
				testType: 'CELPIP Writing Task 1'
			},

			// CELPIP Writing Task 2
			'celpip-task2-transport': {
				type: 'celpip_task2',
				title: 'Survey: Public Transportation',
				question: 'Your city is conducting a survey about improving public transportation. Choose ONE option that you think is most important:\n\nOption A: Increase the frequency of buses and trains\nOption B: Reduce ticket prices\nOption C: Add more routes to underserved areas\n\nExplain your choice with reasons and examples (150-200 words).',
				timeLimit: 26,
				wordTarget: 175,
				testType: 'CELPIP Writing Task 2'
			},

			'celpip-task2-community': {
				type: 'celpip_task2',
				title: 'Survey: Community Center Activities',
				question: 'A local community center is asking for opinions on new activities to offer. Choose ONE option:\n\nOption A: Fitness and sports programs\nOption B: Arts and crafts workshops\nOption C: Technology and computer classes\n\nExplain your choice with reasons and examples (150-200 words).',
				timeLimit: 26,
				wordTarget: 175,
				testType: 'CELPIP Writing Task 2'
			},

			// PTE Writing - Summarize Written Text
			'pte-swt-ai': {
				type: 'pte_summarize',
				title: 'Artificial Intelligence in Healthcare',
				question: 'Read the following passage and summarize it in ONE sentence (5-75 words):\n\nArtificial intelligence is revolutionizing healthcare by enabling faster and more accurate diagnoses, personalizing treatment plans, and predicting patient outcomes. Machine learning algorithms can analyze vast amounts of medical data to identify patterns that human doctors might miss. However, concerns about data privacy, algorithmic bias, and the need for human oversight remain significant challenges that must be addressed as AI becomes more prevalent in medical settings.',
				timeLimit: 10,
				wordTarget: 40,
				testType: 'PTE Summarize Written Text'
			},
			'pte-swt-energy': {
				type: 'pte_summarize',
				title: 'Renewable Energy Transition',
				question: 'Read the following passage and summarize it in ONE sentence (5-75 words):\n\nThe global transition to renewable energy sources is accelerating as countries recognize the urgent need to reduce carbon emissions and combat climate change. Solar and wind power technologies have become increasingly cost-effective, making them competitive with traditional fossil fuels. Governments worldwide are implementing policies to support renewable energy development, including subsidies, tax incentives, and renewable energy targets, though the transition faces challenges including energy storage limitations and the need for grid infrastructure upgrades.',
				timeLimit: 10,
				wordTarget: 40,
				testType: 'PTE Summarize Written Text'
			},
			// PTE Writing - Essay
			'pte-essay-education': {
				type: 'pte_essay',
				title: 'Online Education vs Traditional Learning',
				question: 'Online education is becoming increasingly popular.\n\nDiscuss the advantages and disadvantages of online education compared to traditional classroom learning.\n\nSupport your answer with reasons and examples.\n\nWrite 200-300 words.',
				timeLimit: 20,
				wordTarget: 250,
				testType: 'PTE Academic Essay'
			},
			'pte-essay-urban': {
				type: 'pte_essay',
				title: 'Urbanization and Its Effects',
				question: 'Cities are growing rapidly worldwide.\n\nWhat are the causes and effects of urbanization on society and the environment?\n\nSupport your answer with reasons and examples.\n\nWrite 200-300 words.',
				timeLimit: 20,
				wordTarget: 250,
				testType: 'PTE Academic Essay'
			},
			'pte-essay-work': {
				type: 'pte_essay',
				title: 'Work-Life Balance in Modern Society',
				question: 'Many people struggle to maintain a healthy work-life balance.\n\nWhat factors contribute to this problem and what solutions can be implemented?\n\nSupport your answer with reasons and examples.\n\nWrite 200-300 words.',
				timeLimit: 20,
				wordTarget: 250,
				testType: 'PTE Academic Essay'
			},
			// IELTS Speaking - Part 1
			'ielts-speak-p1-home': {
				type: 'ielts_speaking_part1',
				title: 'Part 1: Home & Family',
				question: 'Part 1 - Introduction & Interview (4-5 minutes)\n\nAnswer questions about:\n• Your home and where you live\n• Your family members\n• Your work or studies\n• Daily routines and familiar topics\n\nPractice speaking naturally and giving extended answers (not just yes/no).',
				timeLimit: 5,
				wordTarget: 0, // Speaking has no word count
				testType: 'IELTS Speaking Part 1'
			},
			'ielts-speak-p1-hobbies': {
				type: 'ielts_speaking_part1',
				title: 'Part 1: Hobbies & Interests',
				question: 'Part 1 - Introduction & Interview (4-5 minutes)\n\nAnswer questions about:\n• Your hobbies and free time activities\n• Things you enjoy doing\n• How you spend your weekends\n• Your interests and preferences\n\nPractice speaking naturally and giving extended answers.',
				timeLimit: 5,
				wordTarget: 0,
				testType: 'IELTS Speaking Part 1'
			},

			// IELTS Speaking - Part 2
			'ielts-speak-p2-journey': {
				type: 'ielts_speaking_part2',
				title: 'Part 2: Describe a Memorable Journey',
				question: 'Part 2 - Long Turn (3-4 minutes total: 1 min preparation + 2 min speech)\n\nDescribe a memorable journey you have made.\n\nYou should say:\n• Where you went\n• Who you went with\n• What you did during the journey\n• And explain why it was memorable\n\nYou will have 1 minute to prepare and make notes. Then speak for 1-2 minutes.',
				timeLimit: 3,
				wordTarget: 0,
				testType: 'IELTS Speaking Part 2'
			},
			'ielts-speak-p2-person': {
				type: 'ielts_speaking_part2',
				title: 'Part 2: Describe a Person Who Inspired You',
				question: 'Part 2 - Long Turn (3-4 minutes total: 1 min preparation + 2 min speech)\n\nDescribe a person who has inspired you.\n\nYou should say:\n• Who this person is\n• How you know them\n• What they did that inspired you\n• And explain why they inspired you\n\nYou will have 1 minute to prepare and make notes. Then speak for 1-2 minutes.',
				timeLimit: 3,
				wordTarget: 0,
				testType: 'IELTS Speaking Part 2'
			},

			// IELTS Speaking - Part 3
			'ielts-speak-p3-travel': {
				type: 'ielts_speaking_part3',
				title: 'Part 3: Travel & Tourism',
				question: 'Part 3 - Two-way Discussion (4-5 minutes)\n\nDiscuss abstract questions about:\n• Travel trends in your country\n• The impact of tourism on local communities\n• Cultural exchange through travel\n• The future of international travel\n• Benefits and drawbacks of mass tourism\n\nGive detailed answers with reasons, examples, and personal opinions.',
				timeLimit: 5,
				wordTarget: 0,
				testType: 'IELTS Speaking Part 3'
			},
			'ielts-speak-p3-education': {
				type: 'ielts_speaking_part3',
				title: 'Part 3: Education & Learning',
				question: 'Part 3 - Two-way Discussion (4-5 minutes)\n\nDiscuss abstract questions about:\n• Education systems in different countries\n• Traditional vs modern learning methods\n• The role of technology in education\n• Skills needed for the future workforce\n• The value of formal education vs self-learning\n\nGive detailed answers with reasons, examples, and personal opinions.',
				timeLimit: 5,
				wordTarget: 0,
				testType: 'IELTS Speaking Part 3'
			}
		};

		// Function to start a writing test
		function startWritingTest(testId) {
			const test = testData[testId];
			if (!test) {
				console.error('Test not found:', testId);
				return;
			}

			// Build URL with parameters
			const params = new URLSearchParams({
				type: test.type,
				title: test.title,
				question: test.question,
				time: test.timeLimit,
				words: test.wordTarget,
				testType: test.testType
			});

			// Add visual data if present
			if (test.visualType === 'image' && test.imageUrl) {
				params.append('visualType', 'image');
				params.append('imageUrl', test.imageUrl);
				params.append('imageAlt', test.imageAlt || '');
			}
			
			// Open essay analyzer in new tab with test data
			window.open('essay_analyzer.php?' + params.toString(), '_blank');
		}

		// Function to start a SPEAKING test
		function startSpeakingTest(testId) {
			const test = testData[testId];
			if (!test) {
				console.error('Test not found:', testId);
				return;
			}

			// Build URL with parameters (same as writing tests)
			const params = new URLSearchParams({
				type: test.type,
				title: test.title,
				question: test.question,
				time: test.timeLimit,
				words: test.wordTarget,
				testType: test.testType
			});

			// DIFFERENCE: Open speaking_analyzer.php instead of essay_analyzer.php
			window.open('audio_analyzer.php?' + params.toString(), '_blank');
		}

		// Add click handlers to "Start Test" buttons
		document.addEventListener('DOMContentLoaded', function() {
			// Assign data-test-id to each writing test button
			const writingTests = [
				// IELTS Academic - Simple selectors using data-test-id
				{ selector: '[data-test-id="ielts-ac-task1-line"]', id: 'ielts-ac-task1-line' },
				{ selector: '[data-test-id="ielts-ac-task1-bar"]', id: 'ielts-ac-task1-bar' },
				{ selector: '[data-test-id="ielts-ac-task1-pie"]', id: 'ielts-ac-task1-pie' },
				{ selector: '[data-test-id="ielts-ac-task1-table"]', id: 'ielts-ac-task1-table' },
				{ selector: '[data-test-id="ielts-ac-task1-process"]', id: 'ielts-ac-task1-process' },
				{ selector: '[data-test-id="ielts-ac-task1-map"]', id: 'ielts-ac-task1-map' },
				{ selector: '[data-test-id="ielts-ac-task2-tech"]', id: 'ielts-ac-task2-tech' },
				{ selector: '[data-test-id="ielts-ac-task2-climate"]', id: 'ielts-ac-task2-climate' },
				{ selector: '[data-test-id="ielts-ac-task2-remote"]', id: 'ielts-ac-task2-remote' },

				// IELTS General
				{ selector: '[data-test-id="ielts-gt-task1-complaint"]', id: 'ielts-gt-task1-complaint' },
				{ selector: '[data-test-id="ielts-gt-task1-invitation"]', id: 'ielts-gt-task1-invitation' },
				{ selector: '[data-test-id="ielts-gt-task1-advice"]', id: 'ielts-gt-task1-advice' },
				{ selector: '[data-test-id="ielts-gt-task2-health"]', id: 'ielts-gt-task2-health' },
				{ selector: '[data-test-id="ielts-gt-task2-social"]', id: 'ielts-gt-task2-social' },

				// CELPIP
				{ selector: '[data-test-id="celpip-task1-property"]', id: 'celpip-task1-property' },
				{ selector: '[data-test-id="celpip-task1-friend"]', id: 'celpip-task1-friend' },
				{ selector: '[data-test-id="celpip-task2-transport"]', id: 'celpip-task2-transport' },
				{ selector: '[data-test-id="celpip-task2-community"]', id: 'celpip-task2-community' },

				// PTE
				{ selector: '[data-test-id="pte-swt-ai"]', id: 'pte-swt-ai' },
				{ selector: '[data-test-id="pte-swt-energy"]', id: 'pte-swt-energy' },
				{ selector: '[data-test-id="pte-essay-education"]', id: 'pte-essay-education' },
				{ selector: '[data-test-id="pte-essay-urban"]', id: 'pte-essay-urban' },
				{ selector: '[data-test-id="pte-essay-work"]', id: 'pte-essay-work' },

				// IELTS Speaking
				{ selector: '[data-test-id="ielts-speak-p1-home"]', id: 'ielts-speak-p1-home' },
				{ selector: '[data-test-id="ielts-speak-p1-hobbies"]', id: 'ielts-speak-p1-hobbies' },
				{ selector: '[data-test-id="ielts-speak-p2-journey"]', id: 'ielts-speak-p2-journey' },
				{ selector: '[data-test-id="ielts-speak-p2-person"]', id: 'ielts-speak-p2-person' },
				{ selector: '[data-test-id="ielts-speak-p3-travel"]', id: 'ielts-speak-p3-travel' },
				{ selector: '[data-test-id="ielts-speak-p3-education"]', id: 'ielts-speak-p3-education' }
			];

			writingTests.forEach(test => {
				const button = document.querySelector(test.selector);
				if (button) {
					button.addEventListener('click', function(e) {
						e.preventDefault();
						
						// Check if it's a speaking test by looking at the ID
						if (test.id.includes('speak')) {
							startSpeakingTest(test.id);  // Opens speaking_analyzer.php
						} else {
							startWritingTest(test.id);   // Opens essay_analyzer.php
						}
					});
				} else {
					console.warn('Button not found:', test.selector);
				}
			});

		});
	</script>
</body>
</html>