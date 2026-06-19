<?php
require_once (dirname(dirname(__DIR__))) . '/bootstrap.php';

// Restrict access if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+this+course");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS Academic Crash Course - EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
	<link href="../../assets/css/courses.css" rel="stylesheet">
	<?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    
</head>
<body>
    <!-- Mobile Header -->
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <!-- Sidebar Navigation -->
    <?php include INCLUDES_PATH . '/navbar.php'; ?>


    <!-- Main Content Area -->
    <main class="main-wrapper">
        <div class="course-card">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="../courses_catalogue.php" class="text-decoration-none">Courses</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="course_overview.php" class="text-decoration-none">IELTS Academic</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Crash Course</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-rocket-takeoff-fill me-2" style="color: #ec4899;"></i>
                IELTS Academic Crash Course
            </h1>
            
            <p class="lead">
                Intensive 4-week program designed to boost your Writing & Speaking skills to match your strong Listening & Reading performance.
            </p>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="mb-2" style="color: var(--accent);">Course Overview</h4>
                        <p class="mb-0"><strong>Duration:</strong> 4 Weeks | <strong>Classes:</strong> 8 Sessions | <strong>Time:</strong> 2 Hours Each</p>
                    </div>
                    <div>
                        <span class="badge-custom">📅 2 Classes/Week</span>
                        <span class="badge-custom">⏱️ 16 Hours Total</span>
                    </div>
                </div>
            </div>

            <!-- Course Introduction -->
            <div class="content-section">
                <h2>What is IELTS Academic?</h2>
                <p>IELTS Academic is the world's most popular English language test for higher education and global migration.</p>
                
                <h3>Key Facts</h3>
                <ul class="custom-list">
                    <li>Accepted by over 11,000 organizations worldwide</li>
                    <li>Required for university admissions in English-speaking countries</li>
                    <li>Tests real-life English communication skills</li>
                    <li>Valid for 2 years from test date</li>
                    <li>Scored on a band scale from 1 to 9</li>
                </ul>
            </div>

            <!-- Test Format -->
            <div class="content-section">
                <h2>Test Format Overview</h2>
                <p>The IELTS Academic test consists of four sections:</p>
                
                <div class="info-grid">
                    <div class="info-card" style="border-color: #1e3a8a;">
                        <h4 style="color: #1e3a8a;"><i class="bi bi-headphones me-2"></i>Listening</h4>
                        <p class="mb-1"><strong>Duration:</strong> 30 minutes + 10 min transfer</p>
                        <p class="mb-0"><strong>Questions:</strong> 40</p>
                        <p class="text-muted mt-2"><small>✓ You've mastered this section!</small></p>
                    </div>
                    
                    <div class="info-card" style="border-color: #1e3a8a;">
                        <h4 style="color: #1e3a8a;"><i class="bi bi-book me-2"></i>Reading</h4>
                        <p class="mb-1"><strong>Duration:</strong> 60 minutes</p>
                        <p class="mb-0"><strong>Questions:</strong> 40</p>
                        <p class="text-muted mt-2"><small>✓ You've mastered this section!</small></p>
                    </div>
                    
                    <div class="info-card" style="border-color: #ec4899;">
                        <h4 style="color: #ec4899;"><i class="bi bi-pencil me-2"></i>Writing</h4>
                        <p class="mb-1"><strong>Duration:</strong> 60 minutes</p>
                        <p class="mb-0"><strong>Tasks:</strong> 2</p>
                        <p class="text-danger mt-2"><small><strong>★ Course Focus Area</strong></small></p>
                    </div>
                    
                    <div class="info-card" style="border-color: #ec4899;">
                        <h4 style="color: #ec4899;"><i class="bi bi-mic me-2"></i>Speaking</h4>
                        <p class="mb-1"><strong>Duration:</strong> 11-14 minutes</p>
                        <p class="mb-0"><strong>Parts:</strong> 3</p>
                        <p class="text-danger mt-2"><small><strong>★ Course Focus Area</strong></small></p>
                    </div>
                </div>
            </div>

            <!-- Writing Section -->
            <div class="content-section">
                <h2>Writing Section - Our Focus!</h2>
                
                <h3>Task 1 (20 minutes | Minimum 150 words)</h3>
                <p>Describe visual data presented in graphs, charts, tables, or diagrams.</p>
                
                <h4>Question Types:</h4>
                <ul>
                    <li><strong>Line Graph:</strong> Show trends over time (e.g., tourist numbers 2000-2020)</li>
                    <li><strong>Bar Chart:</strong> Compare quantities across categories</li>
                    <li><strong>Pie Chart:</strong> Show proportions and percentages</li>
                    <li><strong>Table:</strong> Present detailed numerical information</li>
                    <li><strong>Process Diagram:</strong> Illustrate how something is made or works</li>
                    <li><strong>Map:</strong> Show changes in locations over time</li>
                    <li><strong>Multiple Charts:</strong> Combination of the above</li>
                </ul>

                <h3>Task 2 (40 minutes | Minimum 250 words)</h3>
                <p><strong>Worth 2× Task 1!</strong> Write an essay responding to a point of view, argument, or problem.</p>
                
                <h4>Essay Types:</h4>
                <ul>
                    <li><strong>Opinion:</strong> "To what extent do you agree or disagree?"</li>
                    <li><strong>Discussion:</strong> "Discuss both views and give your opinion"</li>
                    <li><strong>Problem-Solution:</strong> "What are the causes and solutions?"</li>
                    <li><strong>Advantage-Disadvantage:</strong> "Do advantages outweigh disadvantages?"</li>
                    <li><strong>Two-Part Question:</strong> Answer two related questions</li>
                </ul>
            </div>

            <!-- Speaking Section -->
            <div class="content-section">
                <h2>Speaking Section - Our Focus!</h2>
                
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-person-circle me-2"></i>Part 1 (4-5 minutes)</h4>
                        <p><strong>Format:</strong> Introduction and familiar questions</p>
                        <p class="mb-0"><strong>Topics:</strong> Home, work/studies, hobbies, hometown, daily routine</p>
                    </div>
                    
                    <div class="info-card">
                        <h4><i class="bi bi-card-text me-2"></i>Part 2 (3-4 minutes)</h4>
                        <p><strong>Format:</strong> Long turn (1 min prep, 2 min speech)</p>
                        <p class="mb-0"><strong>Topics:</strong> Describe a person, place, event, object, or experience</p>
                    </div>
                    
                    <div class="info-card">
                        <h4><i class="bi bi-chat-dots me-2"></i>Part 3 (4-5 minutes)</h4>
                        <p><strong>Format:</strong> Abstract discussion</p>
                        <p class="mb-0"><strong>Focus:</strong> In-depth analysis with reasons, examples, and perspectives</p>
                    </div>
                </div>
            </div>

            <!-- Band Scores -->
            <div class="content-section">
                <h2>Understanding Band Scores</h2>
                <p>Each section is scored from 1-9. Your overall band score is the average of all four sections.</p>
                
                <h3>Common Requirements</h3>
                <ul class="custom-list">
                    <li><strong>Band 6.0-6.5:</strong> Undergraduate programs</li>
                    <li><strong>Band 6.5-7.0:</strong> Graduate programs</li>
                    <li><strong>Band 7.0-7.5:</strong> Professional registration (doctors, nurses)</li>
                    <li><strong>Band 7.5+:</strong> Competitive graduate programs</li>
                </ul>

                <div class="highlight-box pink-highlight">
                    <h4 style="color: #ec4899; margin-top: 0;"><i class="bi bi-target me-2"></i>Your Goal</h4>
                    <p class="mb-0">Boost your Writing & Speaking scores to match your strong Listening & Reading performance!</p>
                </div>
            </div>

           <!-- New 9-Module Curriculum -->
			<div class="content-section">
			<h2>Course Curriculum – 9 Modules</h2>

			<div class="week-section">
				<div class="week-header">Module 1 – Introduction & Course Overview</div>
				<ul class="module-list">
					<li>Welcome & Course Structure</li>
					<li>Understanding IELTS Test Format</li>
					<li>Band Scores Explained</li>
					<li>Study Strategies & Time Management</li>
					<li>Knowledge Check Quiz</li>
				</ul>
			</div>

			<div class="week-section">
				<div class="week-header">Module 2 – Listening Skills</div>
				<ul class="module-list">
					<li>Listening Section Overview</li>
					<li>Sections 1–4 Strategies & Question Types</li>
					<li>Note-Taking Techniques</li>
					<li>2 Full Listening Practice Tests</li>
				</ul>
			</div>

			<div class="week-section">
				<div class="week-header">Module 3 – Reading Skills</div>
					<ul class="module-list">
					<li>Skimming, Scanning & Question Types</li>
					<li>True/False/Not Given, Matching, Completion</li>
					<li>2 Full Academic Reading Passages</li>
				</ul>
			</div>

			<div class="week-section">
				<div class="week-header">Module 4 – Writing Task 1</div>
					<ul class="module-list">
					<li>Task 1 Overview & Assessment Criteria</li>
					<li>Line/Bar/Pie Charts, Tables, Processes, Maps</li>
					<li>Full Task 1 Practice Session (3 tasks)</li>
				</ul>
			</div>

			<div class="week-section">
				<div class="week-header">Module 5 – Writing Task 2</div>
				<ul class="module-list">
					<li>Essay Structure & PEEL Method</li>
					<li>Opinion, Discussion, Problem-Solution, Adv/Disadv Essays</li>
					<li>Cohesive Devices & Academic Vocabulary</li>
					<li>Full Task 2 Practice Session (2 essays)</li>
				</ul>
			</div>

			<div class="week-section">
				<div class="week-header">Module 6 – Speaking Parts 1 & 2</div>
				<ul class="module-list">
					<li>Part 1 Fluency & Familiar Topics</li>
					<li>Part 2 Long Turn (cue card) Mastery</li>
					<li>Extending Answers + Mock Parts 1 & 2</li>
				</ul>
			</div>

			<div class="week-section">
				<div class="week-header">Module 7 – Speaking Part 3 & Mock Tests</div>
				<ul class="module-list">
					<li>Abstract Discussion & Advanced Expressions</li>
					<li>Justifying Opinions, Comparing, Speculating</li>
					<li>2 Full Mock Speaking Tests + Pronunciation Tips</li>
				</ul>
			</div>

			<div class="week-section">
				<div class="week-header">Module 8 – Full Practice Test – Listening & Reading</div>
				<ul class="module-list">
					<li>Full Timed Listening Test + Review</li>
					<li>Full Timed Reading Test + Review</li>
					<li>Error Pattern Analysis</li>
				</ul>
			</div>

			<div class="week-section">
				<div class="week-header">Module 9 – Full Practice Test – Writing & Speaking + Final Tips</div>
				<ul class="module-list">
					<li>Full Writing Test (Task 1 + Task 2)</li>
					<li>Full Speaking Test Simulation</li>
					<li>Common Mistakes & Last-Minute Strategies</li>
					<li>Test Day Checklist & Final Q&A</li>
				</ul>
			</div>

			<div class="highlight-box pink-highlight mt-4">
			<h4 style="color: #ec4899; margin-top: 0;"><i class="bi bi-stars me-2"></i>Your Path to Success</h4>
			<p class="mb-0">Complete all 9 modules → Take the full practice tests → Walk into your real IELTS exam with total confidence!</p>
			</div>
			</div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button onclick="toggleQuiz()" class="btn btn-warning btn-lg" id="quizToggleBtn">
					<i class="bi bi-question-circle me-2"></i>Take Knowledge Quiz
				</button>
                <a href="intro_practice_tasks.php" class="btn btn-primary btn-lg">
                    <i class="bi bi-pencil-square me-2"></i>20-Minute Practice Tasks
                </a>
                <a href="module2.php" class="btn btn-success btn-lg">
                    <i class="bi bi-play-circle me-2"></i>Go to Module 2
                </a>
                <a href="../../learning_dashboard.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left-circle me-2"></i>Back to Dashboard
                </a>
            </div>

			<!-- Quiz Section (Hidden by default) -->
			<div id="quizSection" style="display: none; margin-top: 3rem;">
				<div class="quiz-container">
					<div class="quiz-header">
						<h2><i class="bi bi-question-circle-fill me-2"></i>Knowledge Check Quiz</h2>
						<p class="mb-0">Test Your Understanding of IELTS Academic</p>
					</div>

					<div class="progress-bar-container">
						<div class="progress-bar-fill" id="progressBar" style="width: 0%"></div>
					</div>

					<!-- Questions Container -->
					<div id="questionsContainer">
						<!-- Questions will be dynamically loaded here -->
					</div>

					<!-- Results Container -->
					<div class="results-container" id="resultsContainer">
						<h2>Quiz Complete! 🎉</h2>
						<div class="score-circle" id="scoreCircle">
							<span id="scoreDisplay">0/15</span>
						</div>
						<h3 id="performanceTitle"></h3>
						<div class="feedback-box" id="feedbackBox"></div>
						
						<div class="mt-4">
							<button class="btn btn-primary btn-lg me-2" onclick="restartQuiz()">
								<i class="bi bi-arrow-clockwise me-2"></i>Retake Quiz
							</button>
							<button class="btn btn-outline-secondary btn-lg" onclick="toggleQuiz()">
								<i class="bi bi-arrow-left-circle me-2"></i>Back to Course Content
							</button>
						</div>
					</div>
				</div>
			</div>
        </div>
    </main>

    <!-- Right Advertisement Sidebar -->
    <aside class="advert-sidebar">
		 <!-- Internal Promo -->
        <div class="course-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h6 class="mb-2">🎯 Quick Access</h6>
            <div class="d-grid gap-2">
                <a href="crash_course_quiz.php" class="btn btn-light btn-sm">
                    <i class="bi bi-question-circle me-2"></i>Knowledge Quiz
                </a>
                <a href="practice_tasks.php" class="btn btn-light btn-sm">
                    <i class="bi bi-pencil-square me-2"></i>Practice Tasks
                </a>
                <a href="../courses_catalogue.php" class="btn btn-outline-light btn-sm">
                    Browse All Courses
                </a>
            </div>
        </div>

		<h6 class="mb-3 text-muted">
            <i class="bi bi-megaphone me-2"></i>Sponsored
        </h6>
		<!-- Ad Container 1 -->
        <div class="ad-container" id="ad-slot-2">
            <div class="ad-placeholder">
                <i class="bi bi-badge-ad" style="font-size: 1.5rem; opacity: 0.3;"></i>
                <p class="mt-2 mb-0">Advertisement Space</p>
                <small>300x250</small>
            </div>
        </div>

        <!-- Ad Container 2 -->
        <div class="ad-container" id="ad-slot-2">
            <div class="ad-placeholder">
                <i class="bi bi-badge-ad" style="font-size: 1.5rem; opacity: 0.3;"></i>
                <p class="mt-2 mb-0">Advertisement Space</p>
                <small>300x250</small>
            </div>
        </div>
    </aside>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script src="../../assets/js/quizzes.js"></script>
    
    <!-- Mobile Menu Script -->
    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.querySelector('.sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');

        function toggleMenu() {
            sidebar.classList.toggle('active');
            mobileOverlay.classList.toggle('active');
            
            const icon = menuToggle.querySelector('i');
            if (sidebar.classList.contains('active')) {
                icon.className = 'bi bi-x-lg';
            } else {
                icon.className = 'bi bi-list';
            }
        }

        menuToggle.addEventListener('click', toggleMenu);
        mobileOverlay.addEventListener('click', toggleMenu);

        const navLinks = document.querySelectorAll('.sidebar .nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1200) {
                    toggleMenu();
                }
            });
        });
    </script>
	
</body>
</html>