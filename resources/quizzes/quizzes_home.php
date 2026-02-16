<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../registration.php?message=Please+login+to+access+resources");
    exit();
}

// Define exercise categories and exercises
$exercises = [
    'ielts' => [
        'name' => 'IELTS',
        'icon' => 'bi-book',
        'color' => '#667eea',
        'items' => [
            'writing' => [
                'name' => 'Writing',
                'exercises' => [
                    ['title' => 'Maps Quiz Master', 'description' => 'Test your IELTS Task 1 maps knowledge', 'url' => 'maps_quiz.php'],
					['title' => 'Process Quiz Master', 'description' => 'Test your IELTS Task 1 process knowledge', 'url' => 'process_desc_quiz.php'],
                    ['title' => 'IELTS Listening Section Quiz', 'description' => 'Test your knowledge on the listening test', 'url' => 'IELTS_Listening_Quiz.php'],
                    //['title' => 'Grammar Correction', 'description' => 'Fix grammatical errors', 'url' => 'grammar_correction.php'],
                    //['title' => 'Vocabulary Matching', 'description' => 'Match words to definitions', 'url' => 'vocab_matching.php'],
                    //['title' => 'Formal vs Informal', 'description' => 'Categorize language register', 'url' => 'formal_informal.php'],
                    //['title' => 'Linker Junction', 'description' => 'Complete sentences with connectors', 'url' => 'linker_junction.php'],
					//['title' => 'Data Detective', 'description' => 'Master IELTS Academic Writing Task 1 by analyzing data like a pro', 'url' => 'data_detective.php'],
					//['title' => 'Build your Evidence', 'description' => 'Master IELTS Academic Writing Task 1 by analyzing data like a pro', 'url' => 'build_your_evidence.php'],
					//['title' => 'Thin to Thick', 'description' => 'Master IELTS Academic Writing Task 1 by analyzing data like a pro', 'url' => 'thin_to_thick.php']
                ]
            ],
            'speaking' => [
                'name' => 'Speaking',
                'exercises' => [
                    //['title' => 'Pronunciation Guide', 'description' => 'Learn correct word pronunciation', 'url' => 'pronunciation.php'],
                    //['title' => 'Part 1 Responses', 'description' => 'Practice common speaking questions', 'url' => 'part1_responses.php'],
                    //['title' => 'Filler Words Practice', 'description' => 'Learn natural hesitation phrases', 'url' => 'filler_words.php'],
                    //['title' => 'Stress & Intonation', 'description' => 'Master word stress patterns', 'url' => 'stress_intonation.php'],
                ]
            ],
            'reading' => [
                'name' => 'Reading',
                'exercises' => [
                    //['title' => 'Skimming Practice', 'description' => 'Extract main ideas quickly', 'url' => 'skimming.php'],
                    //['title' => 'Vocabulary Context', 'description' => 'Guess words from context', 'url' => 'vocab_context.php'],
                ]
            ]
        ]
    ],
    'celpip' => [
        'name' => 'CELPIP',
        'icon' => 'bi-person-check',
        'color' => '#f43f5e',
        'items' => [
            'writing' => [
                'name' => 'Writing',
                'exercises' => [
                    //['title' => 'Email Structure', 'description' => 'Organize email parts correctly', 'url' => 'email_structure.php'],
                    //['title' => 'Opinion Essays', 'description' => 'Build persuasive arguments', 'url' => 'opinion_essays.php'],
                    //['title' => 'Task Completion', 'description' => 'Write to specified requirements', 'url' => 'task_completion.php'],
                ]
            ],
            'speaking' => [
                'name' => 'Speaking',
                'exercises' => [
                    //['title' => 'Response Building', 'description' => 'Construct full-length answers', 'url' => 'response_building.php'],
                    //['title' => 'Idea Organization', 'description' => 'Structure your thoughts clearly', 'url' => 'idea_organization.php'],
                ]
            ]
        ]
    ],
    'pte' => [
        'name' => 'PTE',
        'icon' => 'bi-mortarboard',
        'color' => '#10b981',
        'items' => [
            'writing' => [
                'name' => 'Writing',
                'exercises' => [
                    //['title' => 'Summarize Written Text', 'description' => 'Condense passage into one sentence', 'url' => 'summarize_text.php'],
                    //['title' => 'Essay Planning', 'description' => 'Create essay outlines', 'url' => 'essay_planning.php'],
                ]
            ],
            'speaking' => [
                'name' => 'Speaking',
                'exercises' => [
                    //['title' => 'Read Aloud', 'description' => 'Practice fluent reading', 'url' => 'read_aloud.php'],
                    //['title' => 'Repeat Sentence', 'description' => 'Listen and repeat accurately', 'url' => 'exercises/repeat_sentence.php'],
                ]
            ]
        ]
    ]
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Quizzes | EduHub</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
	<?php include INCLUDES_PATH . '/navbar_styles.php'; ?>

	<style>
		.main-wrapper {
			padding: 2rem 1.5rem;
		}

		.exercises-container {
			max-width: 1200px;
			margin: 0 auto;
		}

		.page-header {
			margin-bottom: 3rem;
			text-align: center;
		}

		.page-header h1 {
			font-size: 2.5rem;
			font-weight: 700;
			color: #1f2937;
			margin-bottom: 0.5rem;
		}

		.page-header p {
			color: #6b7280;
			font-size: 1.1rem;
		}

		/* Test Category Tabs */
		.test-tabs {
			display: flex;
			gap: 1rem;
			margin-bottom: 2.5rem;
			flex-wrap: wrap;
		}

		.test-tab {
			padding: 0.75rem 1.5rem;
			border: 2px solid #e5e7eb;
			border-radius: 8px;
			background: white;
			cursor: pointer;
			font-weight: 600;
			transition: all 0.3s ease;
			display: flex;
			align-items: center;
			gap: 0.5rem;
		}

		.test-tab:hover {
			border-color: #667eea;
			color: #667eea;
		}

		.test-tab.active {
			background: #667eea;
			color: white;
			border-color: #667eea;
		}

		/* Section Tabs */
		.section-tabs {
			display: flex;
			gap: 0.75rem;
			margin-bottom: 2rem;
			border-bottom: 2px solid #e5e7eb;
			overflow-x: auto;
		}

		.section-tab {
			padding: 0.75rem 1.2rem;
			border: none;
			background: transparent;
			cursor: pointer;
			font-weight: 500;
			color: #6b7280;
			border-bottom: 3px solid transparent;
			transition: all 0.3s ease;
			white-space: nowrap;
		}

		.section-tab:hover {
			color: #667eea;
		}

		.section-tab.active {
			color: #667eea;
			border-bottom-color: #667eea;
		}

		/* Exercise Grid */
		.exercises-grid {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
			gap: 1.5rem;
			margin-bottom: 2rem;
		}

		.exercise-card {
			background: white;
			border-radius: 12px;
			overflow: hidden;
			box-shadow: 0 2px 12px rgba(0,0,0,0.08);
			transition: all 0.3s ease;
			text-decoration: none;
			color: inherit;
			display: flex;
			flex-direction: column;
			height: 100%;
		}

		.exercise-card:hover {
			transform: translateY(-8px);
			box-shadow: 0 12px 24px rgba(0,0,0,0.15);
		}

		.exercise-header {
			padding: 1.5rem;
			background: linear-gradient(135deg, #667eea, #764ba2);
			color: white;
		}

		.exercise-header h3 {
			font-size: 1.2rem;
			font-weight: 600;
			margin: 0;
		}

		.exercise-body {
			padding: 1.5rem;
			flex: 1;
			display: flex;
			flex-direction: column;
		}

		.exercise-description {
			color: #6b7280;
			font-size: 0.95rem;
			margin-bottom: 1rem;
			flex: 1;
		}

		.exercise-link {
			display: inline-block;
			padding: 0.6rem 1.2rem;
			background: #667eea;
			color: white;
			border-radius: 6px;
			text-decoration: none;
			font-weight: 500;
			transition: background 0.3s ease;
			text-align: center;
			align-self: flex-start;
		}

		.exercise-link:hover {
			background: #764ba2;
			color: white;
			text-decoration: none;
		}

		.content-section {
			display: none;
		}

		.content-section.active {
			display: block;
			animation: fadeIn 0.3s ease;
		}

		@keyframes fadeIn {
			from {
				opacity: 0;
				transform: translateY(10px);
			}
			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		.empty-state {
			text-align: center;
			padding: 3rem 2rem;
			color: #9ca3af;
		}

		.empty-state i {
			font-size: 3rem;
			margin-bottom: 1rem;
			opacity: 0.5;
		}

		@media (max-width: 768px) {
			.exercises-grid {
				grid-template-columns: 1fr;
			}

			.test-tabs {
				justify-content: center;
			}

			.page-header h1 {
				font-size: 1.8rem;
			}

			.section-tabs {
				justify-content: flex-start;
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

	<!-- MAIN CONTENT AREA -->
	<main class="main-wrapper">
		<div class="exercises-container">

			<div class="page-header">
				<h1><i class="bi bi-gamepad2"></i> Quizzes</h1>
				<p>Master any topic with one of these interactive quizzes</p>
			</div>

			<!-- Test Category Tabs -->
			<div class="test-tabs">
				<?php foreach ($exercises as $key => $test): ?>
					<button class="test-tab <?php echo $key === 'ielts' ? 'active' : ''; ?>" onclick="switchTest('<?php echo $key; ?>')">
						<i class="bi <?php echo $test['icon']; ?>"></i>
						<?php echo $test['name']; ?>
					</button>
				<?php endforeach; ?>
			</div>

			<!-- Content for each test -->
			<?php foreach ($exercises as $testKey => $test): ?>
				<div id="test-<?php echo $testKey; ?>" class="content-section <?php echo $testKey === 'ielts' ? 'active' : ''; ?>">
					
					<!-- Section Tabs (Writing, Speaking, etc) -->
					<div class="section-tabs">
						<?php foreach ($test['items'] as $sectionKey => $section): ?>
							<button class="section-tab <?php echo $sectionKey === 'writing' ? 'active' : ''; ?>" 
									onclick="switchSection('<?php echo $testKey; ?>', '<?php echo $sectionKey; ?>')">
								<?php echo $section['name']; ?>
							</button>
						<?php endforeach; ?>
					</div>

					<!-- Exercises for each section -->
					<?php foreach ($test['items'] as $sectionKey => $section): ?>
						<div id="section-<?php echo $testKey; ?>-<?php echo $sectionKey; ?>" 
							 class="content-section <?php echo $sectionKey === 'writing' ? 'active' : ''; ?>">
							
							<?php if (!empty($section['exercises'])): ?>
								<div class="exercises-grid">
									<?php foreach ($section['exercises'] as $exercise): ?>
										<a href="<?php echo $exercise['url']; ?>" class="exercise-card">
											<div class="exercise-header">
												<h3><i class="bi bi-play-circle"></i> <?php echo $exercise['title']; ?></h3>
											</div>
											<div class="exercise-body">
												<p class="exercise-description"><?php echo $exercise['description']; ?></p>
												<div class="exercise-link">Start Exercise →</div>
											</div>
										</a>
									<?php endforeach; ?>
								</div>
							<?php else: ?>
								<div class="empty-state">
									<i class="bi bi-inbox"></i>
									<p>No exercises available yet</p>
								</div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>

				</div>
			<?php endforeach; ?>

		</div>
		<!-- /.exercises-container -->
	</main>
	<!-- END MAIN-WRAPPER -->

    <?php include INCLUDES_PATH . '/adverts.php'; ?>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>

	<script>
		function switchTest(testKey) {
			// Hide all test sections
			document.querySelectorAll('[id^="test-"]').forEach(el => {
				el.classList.remove('active');
			});
			
			// Show selected test
			document.getElementById('test-' + testKey).classList.add('active');
			
			// Update tab styling
			document.querySelectorAll('.test-tab').forEach(tab => {
				tab.classList.remove('active');
			});
			event.target.closest('.test-tab').classList.add('active');
		}

		function switchSection(testKey, sectionKey) {
			// Hide all sections for this test
			document.querySelectorAll('[id^="section-' + testKey + '-"]').forEach(el => {
				el.classList.remove('active');
			});
			
			// Show selected section
			document.getElementById('section-' + testKey + '-' + sectionKey).classList.add('active');
			
			// Update tab styling
			document.querySelectorAll('[id="test-' + testKey + '"] .section-tab').forEach(tab => {
				tab.classList.remove('active');
			});
			event.target.classList.add('active');
		}
	</script>

	</body>
</html>