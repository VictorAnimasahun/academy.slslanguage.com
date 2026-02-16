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
	<title>Basic English Diagnostic Test | EduHub</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
	<?php include INCLUDES_PATH . '/navbar_styles.php'; ?>

	<style>
		.main-wrapper {
			padding: 0;
			min-height: 100vh;
			background: linear-gradient(to bottom right, #dff3ff, #ffeaea);
			display: flex;
			flex-direction: column;
		}

		/* Fixed Breadcrumb at Top */
		.breadcrumb-container {
			position: sticky;
			top: 0;
			z-index: 100;
			background: white;
			padding: 1rem 0;
			box-shadow: 0 2px 8px rgba(0,0,0,0.1);
			flex-shrink: 0;
		}

		.breadcrumb {
			background: transparent;
			padding: 0;
			margin-bottom: 0;
		}

		/* Scrollable Content Container */
		.content-wrapper {
			flex: 1;
			overflow-y: auto;
			padding: 2rem 1.5rem;
		}

		.test-container {
			max-width: 900px;
			margin: 0 auto 2rem;
			background: white;
			border-radius: 16px;
			box-shadow: 0 6px 20px rgba(0,0,0,0.1);
			overflow: hidden;
		}

		.test-header {
			background: linear-gradient(135deg, #0E4C92, #1e5fa8);
			color: white;
			padding: 2.5rem 2rem;
			text-align: center;
		}

		.test-header h1 {
			margin: 0;
			font-size: 2.5rem;
			font-weight: 300;
		}

		.test-header p {
			margin-top: 0.5rem;
			opacity: 0.95;
		}

		.section-content {
			padding: 2.5rem 2rem;
		}

		.section {
			display: none;
		}

		.section.active {
			display: block;
			animation: fadeIn 0.4s ease-in;
		}

		@keyframes fadeIn {
			from { opacity: 0; transform: translateY(15px); }
			to { opacity: 1; transform: translateY(0); }
		}

		.intro-section {
			text-align: center;
		}

		.intro-cards {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
			gap: 1.5rem;
			margin: 2rem 0;
		}

		.intro-card {
			background: linear-gradient(135deg, #f8f9ff, #fff);
			border: 2px solid #e6f0f8;
			border-radius: 12px;
			padding: 1.5rem;
			transition: all 0.3s;
		}

		.intro-card:hover {
			transform: translateY(-5px);
			box-shadow: 0 8px 20px rgba(14, 76, 146, 0.1);
			border-color: #0E4C92;
		}

		.intro-card h3 {
			color: #0E4C92;
			margin-bottom: 0.8rem;
			font-size: 1.3rem;
		}

		.task-card {
			background: #f9fcff;
			border-left: 4px solid #0E4C92;
			border-radius: 8px;
			padding: 1.5rem;
			margin-bottom: 1.5rem;
		}

		.task-card h4 {
			color: #0E4C92;
			margin-bottom: 1rem;
		}

		.reading-passage {
			max-height: 300px;
			overflow-y: auto;
			background: #fefcff;
			border: 1px solid #e6e6fa;
			border-radius: 8px;
			padding: 1.5rem;
			margin-bottom: 1.5rem;
			line-height: 1.8;
		}

		.form-control, .form-select {
			border: 2px solid #e1e5e9;
			border-radius: 8px;
			padding: 0.75rem;
		}

		.form-control:focus, .form-select:focus {
			border-color: #0E4C92;
			box-shadow: 0 0 0 0.2rem rgba(14, 76, 146, 0.15);
		}

		textarea.form-control {
			min-height: 150px;
		}

		.form-check-input:checked {
			background-color: #0E4C92;
			border-color: #0E4C92;
		}

		/* Fixed Navigation at Bottom */
		.navigation-buttons {
			position: sticky;
			bottom: 0;
			z-index: 100;
			background: white;
			display: flex;
			justify-content: space-between;
			padding: 1.5rem 2rem;
			border-top: 2px solid #f0f2ff;
			gap: 1rem;
			box-shadow: 0 -2px 8px rgba(0,0,0,0.1);
			flex-shrink: 0;
		}

		.btn-nav {
			padding: 0.75rem 2rem;
			border-radius: 25px;
			font-weight: 500;
			transition: all 0.3s;
		}

		.btn-primary {
			background: linear-gradient(135deg, #0E4C92, #1e5fa8);
			border: none;
		}

		.btn-primary:hover {
			background: linear-gradient(135deg, #E76F51, #f08a6f);
			transform: translateY(-2px);
		}

		.alert-info {
			background: #fff5f5;
			border-left: 6px solid #E76F51;
			border-radius: 8px;
		}

		.results-container {
			padding: 2rem;
		}

		.results-container h2 {
			color: #0E4C92;
			text-align: center;
			margin-bottom: 2rem;
		}

		.result-item {
			padding: 1rem;
			margin: 0.5rem 0;
			border-radius: 8px;
		}

		.result-correct {
			background: #d4edda;
			border-left: 4px solid #28a745;
		}

		.result-incorrect {
			background: #f8d7da;
			border-left: 4px solid #dc3545;
		}

		.final-score {
			background: linear-gradient(135deg, #0E4C92, #1e5fa8);
			color: white;
			padding: 2rem;
			border-radius: 12px;
			text-align: center;
			font-size: 1.5rem;
			margin: 2rem 0;
		}

		.section-title {
			color: #E76F51;
			font-size: 1.8rem;
			margin-bottom: 1.5rem;
			padding-bottom: 0.5rem;
			border-bottom: 3px solid #f0f2ff;
		}

		.recorder-container {
			background: #f8f9ff;
			border: 2px dashed #0E4C92;
			border-radius: 8px;
			padding: 2rem;
			text-align: center;
			margin: 1rem 0;
		}

		/* Responsive adjustments */
		@media (max-width: 768px) {
			.test-header h1 {
				font-size: 2rem;
			}
			
			.navigation-buttons {
				padding: 1rem;
			}

			.btn-nav {
				padding: 0.5rem 1rem;
				font-size: 0.9rem;
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

	<main class="main-wrapper">
		<!-- Fixed Breadcrumb at Top -->
		<div class="breadcrumb-container">
			<div class="container">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="resources.php">Resources</a></li>
						<li class="breadcrumb-item"><a href="diagnostic_tests_home.php">Diagnostic Tests</a></li>
						<li class="breadcrumb-item active">Basic English</li>
					</ol>
				</nav>
			</div>
		</div>

		<!-- Scrollable Content Area -->
		<div class="content-wrapper">
			<div class="container">
				<div class="test-container">
					<!-- Test Header -->
					<div class="test-header">
						<h1>Basic English Diagnostic Test</h1>
						<p>Complete all sections to assess your English proficiency level</p>
					</div>

					<!-- Introduction Section -->
					<div class="section active" id="intro-section">
						<div class="section-content intro-section">
							<h2 class="section-title">Welcome to Your Assessment</h2>
							<p class="lead">This diagnostic test will help us understand your current English level across five key areas.</p>
							
							<div class="intro-cards">
								<div class="intro-card">
									<h3><i class="bi bi-mic-fill"></i> Speaking</h3>
									<p>Record your responses to IELTS-style prompts with time limits</p>
								</div>
								<div class="intro-card">
									<h3><i class="bi bi-pencil-fill"></i> Writing</h3>
									<p>Complete essays and formal writing tasks</p>
								</div>
								<div class="intro-card">
									<h3><i class="bi bi-book-fill"></i> Reading</h3>
									<p>Analyze texts and answer comprehension questions</p>
								</div>
								<div class="intro-card">
									<h3><i class="bi bi-headphones"></i> Listening</h3>
									<p>Respond to audio clips in exam-like conditions</p>
								</div>
								<div class="intro-card">
									<h3><i class="bi bi-journal-text"></i> Grammar & Vocab</h3>
									<p>Test your command of advanced English usage</p>
								</div>
							</div>

							<div class="alert alert-info">
								<strong><i class="bi bi-info-circle"></i> Important:</strong> This test provides a roadmap for your learning. You'll receive detailed feedback on strengths and areas for improvement, plus a personalized learning plan.
							</div>
						</div>
					</div>

					<!-- Section 1: Writing -->
					<div class="section" id="section-1">
						<div class="section-content">
							<h2 class="section-title"><i class="bi bi-pencil-fill"></i> Section 1: Writing Skills</h2>
							<p class="mb-4"><strong>Instructions:</strong> Choose ONE task and write 120-150 words.</p>

							<div class="task-card">
								<h4>Task A – Formal Email</h4>
								<p class="text-muted">You recently attended an international conference where you met professionals in your field. Write a formal email to one of the keynote speakers to thank them for their talk and explain how it has influenced your future academic or career plans.</p>
								<textarea class="form-control" name="writing-a" placeholder="Type your email here (120-150 words)..."></textarea>
							</div>

							<div class="task-card">
								<h4>Task B – Opinion Essay</h4>
								<p class="text-muted">Some people argue that the rapid development of artificial intelligence will create more jobs than it destroys, while others believe it will lead to widespread unemployment. Discuss both views and give your own opinion.</p>
								<textarea class="form-control" name="writing-b" placeholder="Type your essay here (120-150 words)..."></textarea>
							</div>

							<div class="task-card">
								<h4>Task C – Survey Response</h4>
								<p class="text-muted">Your city council has sent out a survey asking residents whether they should invest in building more public parks or expanding digital infrastructure (such as free citywide Wi-Fi). Write a response explaining your preference and supporting reasons.</p>
								<textarea class="form-control" name="writing-c" placeholder="Type your response here (120-150 words)..."></textarea>
							</div>

							<div class="task-card">
								<h4>Task D – Graph Description</h4>
								<p class="text-muted">The chart below shows the percentage of energy consumption from renewable and non-renewable sources in five countries. Summarize the key trends and make comparisons where relevant.</p>
								<p><small><strong>Note:</strong> A chart would be displayed here in the actual test</small></p>
								<textarea class="form-control" name="writing-d" placeholder="Type your analysis here (120-150 words)..."></textarea>
							</div>
						</div>
					</div>

					<!-- Section 2: Speaking -->
					<div class="section" id="section-2">
						<div class="section-content">
							<h2 class="section-title"><i class="bi bi-mic-fill"></i> Section 2: Speaking & Pronunciation</h2>
							<p class="mb-4"><strong>Instructions:</strong> Answer all three tasks. Speak for no more than 1.5 minutes per task.</p>

							<div class="task-card">
								<h4>Task 1 – Abstract Discussion</h4>
								<p class="text-muted">To what extent do you think freedom of speech should be limited in modern societies? Discuss possible consequences of both unrestricted and restricted expression.</p>
								<div class="recorder-container">
									<i class="bi bi-mic-fill" style="font-size: 3rem; color: #0E4C92;"></i>
									<p class="mt-3">Recording functionality would be implemented here</p>
									<button class="btn btn-outline-primary btn-sm mt-2"><i class="bi bi-record-circle"></i> Start Recording</button>
								</div>
							</div>

							<div class="task-card">
								<h4>Task 2 – Interpretation of Data</h4>
								<p class="text-muted">Summarize the main findings and predict two long-term consequences that may arise from these trends.</p>
								<p><small><strong>Note:</strong> A chart/infographic would be displayed here</small></p>
								<div class="recorder-container">
									<i class="bi bi-mic-fill" style="font-size: 3rem; color: #0E4C92;"></i>
									<p class="mt-3">Recording functionality would be implemented here</p>
									<button class="btn btn-outline-primary btn-sm mt-2"><i class="bi bi-record-circle"></i> Start Recording</button>
								</div>
							</div>

							<div class="task-card">
								<h4>Task 3 – Persuasive Response</h4>
								<p class="text-muted">Imagine that your city is about to ban private cars in the downtown area. Convince the mayor either to support or oppose this ban, addressing both environmental and economic concerns.</p>
								<div class="recorder-container">
									<i class="bi bi-mic-fill" style="font-size: 3rem; color: #0E4C92;"></i>
									<p class="mt-3">Recording functionality would be implemented here</p>
									<button class="btn btn-outline-primary btn-sm mt-2"><i class="bi bi-record-circle"></i> Start Recording</button>
								</div>
							</div>
						</div>
					</div>

					<!-- Section 3: Reading -->
					<div class="section" id="section-3">
						<div class="section-content">
							<h2 class="section-title"><i class="bi bi-book-fill"></i> Section 3: Reading Comprehension</h2>

							<div class="reading-passage">
								<p>The concept of resilience has been widely adopted across disciplines, from ecology to psychology and economics. At first glance, resilience appears to denote a straightforward quality: the ability of a system or individual to 'bounce back' from adversity. Yet, on closer examination, the notion is far less simplistic. In ecological studies, resilience does not imply returning to a prior state, but rather the capacity of an ecosystem to absorb disturbances while continuing to function, even if in a transformed state. Likewise, in psychology, resilience is less about eliminating distress and more about sustaining meaningful engagement with life despite ongoing hardship.</p>
								
								<p>However, the widespread embrace of resilience as a buzzword has generated criticism. Some scholars argue that valorizing resilience risks obscuring structural inequities by placing responsibility on individuals to adapt, rather than on institutions to reform. For example, exhorting workers to develop resilience in the face of exploitative labor conditions may normalize injustice rather than challenge it. In this sense, resilience can become complicit with systems of power, quietly reinforcing the very problems it purports to address.</p>
								
								<p>On the other hand, rejecting resilience outright is equally problematic. To deny its significance is to overlook the undeniable fact that human and ecological systems alike do adapt, often in astonishingly creative ways. The challenge, therefore, lies in distinguishing between resilience as an empowering capacity and resilience as a rhetorical tool that excuses structural failure. Only by maintaining this distinction can we appreciate the concept's complexity and avoid its misuse.</p>
								
								<p>Ultimately, resilience is neither an unqualified virtue nor an empty cliché. It is a contested terrain, reflecting both the promise of human adaptability and the perils of overlooking the conditions that necessitate it in the first place.</p>
							</div>

							<div class="task-card">
								<h4>Question 1 – Vocabulary in Context</h4>
								<p>In the phrase "resilience can become complicit with systems of power," the word <em>complicit</em> most nearly means:</p>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="r1" value="a" id="r1a">
									<label class="form-check-label" for="r1a">Actively resisting</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="r1" value="b" id="r1b">
									<label class="form-check-label" for="r1b">Passively supporting wrongdoing</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="r1" value="c" id="r1c">
									<label class="form-check-label" for="r1c">Operating independently</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="r1" value="d" id="r1d">
									<label class="form-check-label" for="r1d">Inevitably collapsing</label>
								</div>
							</div>

							<div class="task-card">
								<h4>Question 2 – Gap Fill</h4>
								<p>In ecological terms, resilience refers to the ability of a system to <input type="text" class="form-control d-inline-block" name="r2" style="width: 300px;" placeholder="complete the sentence"> while still continuing to function, even if in a different form.</p>
							</div>

							<div class="task-card">
								<h4>Question 3 – Inference</h4>
								<p>What criticism does the passage raise about the widespread use of resilience?</p>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="r3" value="a" id="r3a">
									<label class="form-check-label" for="r3a">It distracts from systemic injustices by overemphasizing individual coping</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="r3" value="b" id="r3b">
									<label class="form-check-label" for="r3b">It is a meaningless concept with no real-world applications</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="r3" value="c" id="r3c">
									<label class="form-check-label" for="r3c">It suggests ecosystems always return to their original state</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="r3" value="d" id="r3d">
									<label class="form-check-label" for="r3d">It proves individuals cannot adapt without institutional help</label>
								</div>
							</div>

							<div class="task-card">
								<h4>Question 4 – Detail</h4>
								<p>According to the passage, why is rejecting resilience altogether problematic?</p>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="r4" value="a" id="r4a">
									<label class="form-check-label" for="r4a">Because resilience has no universally accepted definition</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="r4" value="b" id="r4b">
									<label class="form-check-label" for="r4b">Because it ignores the genuine adaptive capacities of humans and ecosystems</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="r4" value="c" id="r4c">
									<label class="form-check-label" for="r4c">Because resilience always improves exploitative systems</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="r4" value="d" id="r4d">
									<label class="form-check-label" for="r4d">Because it proves institutions can never reform</label>
								</div>
							</div>

							<div class="task-card">
								<h4>Question 5 – Critical Evaluation</h4>
								<p>Which of the following best reflects the author's stance on resilience?</p>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="r5" value="a" id="r5a">
									<label class="form-check-label" for="r5a">It is always positive and should be encouraged uncritically</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="r5" value="b" id="r5b">
									<label class="form-check-label" for="r5b">It is meaningless and should be abandoned</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="r5" value="c" id="r5c">
									<label class="form-check-label" for="r5c">It has both empowering potential and risks of misuse, depending on context</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="r5" value="d" id="r5d">
									<label class="form-check-label" for="r5d">It is only relevant in ecological and psychological studies</label>
								</div>
							</div>
						</div>
					</div>

					<!-- Section 4: Listening -->
					<div class="section" id="section-4">
						<div class="section-content">
							<h2 class="section-title"><i class="bi bi-headphones"></i> Section 4: Listening Comprehension</h2>

							<div class="alert alert-info">
								<strong>Note:</strong> In a real test, you would listen to an audio recording. For this demonstration, please read the transcript below.
							</div>

							<button type="button" class="btn btn-outline-secondary mb-3" onclick="toggleScript()">
								<i class="bi bi-eye"></i> Show/Hide Transcript
							</button>

							<div id="listening-script" class="reading-passage" style="display: none;">
								<p>When people imagine the history of innovation, they often picture a lone genius working tirelessly in isolation, eventually unveiling a revolutionary breakthrough. Yet, this narrative is misleading. Most transformative inventions were not sudden strokes of brilliance, but rather the culmination of countless incremental steps, frequently shaped by collaboration, error, and even sheer accident. The discovery of penicillin, for instance, was hardly the outcome of a meticulous experiment; instead, it emerged from Alexander Fleming's seemingly trivial oversight—neglecting to clean a petri dish.</p>
								
								<p>Equally significant is the social and economic context in which innovations unfold. The steam engine did not transform society merely because of its mechanical efficiency, but because it aligned with the needs of an industrializing Europe eager for faster transport and mass production. Similarly, our current fascination with artificial intelligence is not solely due to its technical sophistication; it reflects broader cultural anxieties about human obsolescence and an appetite for efficiency that borders on obsession.</p>
								
								<p>Thus, to attribute innovation to individual brilliance alone is to misunderstand its essence. What truly propels human progress is the intricate interplay between chance, collaboration, and societal demand—factors far messier, and far more human, than the myth of the solitary genius would suggest.</p>
							</div>

							<div class="task-card mt-4">
								<h4>Question 1 – Main Idea</h4>
								<p>The central argument of the talk is that:</p>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="q1" value="a" id="q1a">
									<label class="form-check-label" for="q1a">Innovation primarily arises from solitary genius working in isolation</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="q1" value="b" id="q1b">
									<label class="form-check-label" for="q1b">Most inventions are products of chance, teamwork, and societal needs rather than individual brilliance</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="q1" value="c" id="q1c">
									<label class="form-check-label" for="q1c">The steam engine was more important than artificial intelligence</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="q1" value="d" id="q1d">
									<label class="form-check-label" for="q1d">Fleming's discovery of penicillin was entirely deliberate and systematic</label>
								</div>
							</div>

							<div class="task-card">
								<h4>Question 2 – Inference</h4>
								<p>What does the speaker imply by describing society's fascination with AI as "an appetite for efficiency that borders on obsession"?</p>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="q2" value="a" id="q2a">
									<label class="form-check-label" for="q2a">Society values efficiency above almost everything else, sometimes irrationally</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="q2" value="b" id="q2b">
									<label class="form-check-label" for="q2b">AI is already more efficient than humans in every context</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="q2" value="c" id="q2c">
									<label class="form-check-label" for="q2c">Obsession with AI efficiency has eliminated all cultural anxieties</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="q2" value="d" id="q2d">
									<label class="form-check-label" for="q2d">The desire for efficiency is unique to the modern technological era</label>
								</div>
							</div>

							<div class="task-card">
								<h4>Question 3 – Gap Fill</h4>
								<p>The speaker argues that innovation is not merely a technical achievement but also depends on <input type="text" class="form-control d-inline-block" name="q3" style="width: 400px;" placeholder="two key factors"></p>
							</div>

							<div class="task-card">
								<h4>Question 4 – Detail</h4>
								<p>According to the passage, what role did context play in the impact of the steam engine?</p>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="q4" value="a" id="q4a">
									<label class="form-check-label" for="q4a">It became influential because its invention coincided with Europe's industrialization needs</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="q4" value="b" id="q4b">
									<label class="form-check-label" for="q4b">It was efficient enough to transform society without external factors</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="q4" value="c" id="q4c">
									<label class="form-check-label" for="q4c">It spread widely because of its affordability to ordinary citizens</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="q4" value="d" id="q4d">
									<label class="form-check-label" for="q4d">It was rejected at first due to fears of unemployment</label>
								</div>
							</div>

							<div class="task-card">
								<h4>Question 5 – Critical Thinking</h4>
								<p>Which of the following best captures the contrast the speaker makes?</p>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="q5" value="a" id="q5a">
									<label class="form-check-label" for="q5a">Genius is timeless, whereas context is temporary</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="q5" value="b" id="q5b">
									<label class="form-check-label" for="q5b">Inventions succeed mainly due to accidents, not intelligence</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="q5" value="c" id="q5c">
									<label class="form-check-label" for="q5c">Collaboration, chance, and social demand outweigh the role of isolated brilliance</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="q5" value="d" id="q5d">
									<label class="form-check-label" for="q5d">The best innovations are always unexpected and unplanned</label>
								</div>
							</div>
						</div>
					</div>

					<!-- Section 5: Grammar & Vocabulary -->
					<div class="section" id="section-5">
						<div class="section-content">
							<h2 class="section-title"><i class="bi bi-journal-text"></i> Section 5: Grammar & Vocabulary</h2>

							<div class="task-card">
								<h4>Question 1 – Error Correction</h4>
								<p>The sentence below contains two errors. Rewrite it correctly:</p>
								<blockquote class="blockquote">"Hardly I had arrived to the conference when I realized I left my notes at home."</blockquote>
								<input type="text" class="form-control" name="g1" placeholder="Rewrite correctly here...">
							</div>

							<div class="task-card">
								<h4>Question 2 – Collocation</h4>
								<p>Complete the sentence with the most appropriate word:</p>
								<p class="text-muted">"Although the minister's speech was full of elegant rhetoric, it completely failed to ______ with the concerns of ordinary citizens."</p>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="g2" value="a" id="g2a">
									<label class="form-check-label" for="g2a">resonate</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="g2" value="b" id="g2b">
									<label class="form-check-label" for="g2b">reverberate</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="g2" value="c" id="g2c">
									<label class="form-check-label" for="g2c">echo</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="g2" value="d" id="g2d">
									<label class="form-check-label" for="g2d">reverence</label>
								</div>
							</div>

							<div class="task-card">
								<h4>Question 3 – Word Formation</h4>
								<p class="text-muted">"Her constant ______ (ASSERT) during the negotiations made it clear she would not concede easily."</p>
								<input type="text" class="form-control" name="g3" placeholder="Type your answer...">
							</div>

							<div class="task-card">
								<h4>Question 4 – Sentence Transformation</h4>
								<p class="text-muted">"They started the project only after the funding had been approved."</p>
								<p><strong>Begin with:</strong> "Only after..."</p>
								<input type="text" class="form-control" name="g4" placeholder="Rewrite starting with 'Only after...'">
							</div>

							<div class="task-card">
								<h4>Question 5 – Vocabulary Precision</h4>
								<p class="text-muted">"The scientist argued that the data was too ______ to justify drawing such sweeping conclusions."</p>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="g5" value="a" id="g5a">
									<label class="form-check-label" for="g5a">tenuous</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="g5" value="b" id="g5b">
									<label class="form-check-label" for="g5b">vigorous</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="g5" value="c" id="g5c">
									<label class="form-check-label" for="g5c">prodigious</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="g5" value="d" id="g5d">
									<label class="form-check-label" for="g5d">conspicuous</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Fixed Navigation at Bottom -->
		<div class="navigation-buttons">
			<div class="container" style="max-width: 900px;">
				<div class="d-flex justify-content-between">
					<button class="btn btn-secondary btn-nav" onclick="prevSection()" id="prevBtn">
						<i class="bi bi-arrow-left"></i> Previous
					</button>
					<button class="btn btn-primary btn-nav" onclick="nextSection()" id="nextBtn">
						Next <i class="bi bi-arrow-right"></i>
					</button>
					<button class="btn btn-success btn-nav" onclick="submitTest()" id="submitBtn" style="display:none;">
						<i class="bi bi-check-circle"></i> Submit Test
					</button>
				</div>
			</div>
		</div>
	</main>

	<?php include INCLUDES_PATH . '/adverts.php'; ?>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>

	<script>
		let currentSection = 0;
		const totalSections = 6; // intro + 5 test sections

		function showSection(n) {
			const sections = document.querySelectorAll('.section');
			sections.forEach((sec, i) => {
				sec.classList.remove('active');
				if (i === n) sec.classList.add('active');
			});

			// Update button visibility
			document.getElementById('prevBtn').style.display = n === 0 ? 'none' : 'inline-block';
			document.getElementById('nextBtn').style.display = n === totalSections - 1 ? 'none' : 'inline-block';
			document.getElementById('submitBtn').style.display = n === totalSections - 1 ? 'inline-block' : 'none';
		}

		function nextSection() {
			if (currentSection < totalSections - 1) {
				currentSection++;
				showSection(currentSection);
				// Scroll content wrapper to top
				document.querySelector('.content-wrapper').scrollTo({ top: 0, behavior: 'smooth' });
			}
		}

		function prevSection() {
			if (currentSection > 0) {
				currentSection--;
				showSection(currentSection);
				// Scroll content wrapper to top
				document.querySelector('.content-wrapper').scrollTo({ top: 0, behavior: 'smooth' });
			}
		}

		function toggleScript() {
			const script = document.getElementById('listening-script');
			script.style.display = script.style.display === 'none' ? 'block' : 'none';
		}

		// Answer keys
		const answerKey = {
			r1: "b", r2: "absorb disturbances", r3: "a", r4: "b", r5: "c",
			q1: "b", q2: "a", q3: ["collaboration", "societal demand", "chance"], q4: "a", q5: "c",
			g1: "Hardly had I arrived at the conference when I realized I had left my notes at home.",
			g2: "a", g3: "assertiveness",
			g4: "Only after the funding had been approved did they start the project.",
			g5: "a"
		};

		function submitTest() {
			let score = 0;
			let totalQs = 0;
			let resultsHTML = '<div class="test-header"><h1><i class="bi bi-trophy-fill"></i> Test Results</h1></div><div class="results-container">';

			// Helper functions
			function checkRadio(qName, correct, label, section) {
				totalQs++;
				let selected = document.querySelector(`input[name='${qName}']:checked`);
				if (selected && selected.value === correct) {
					score++;
					resultsHTML += `<div class="result-item result-correct"><strong>${section} - ${label}:</strong> ✅ Correct</div>`;
				} else {
					let selectedValue = selected ? selected.value : "No answer";
					resultsHTML += `<div class="result-item result-incorrect"><strong>${section} - ${label}:</strong> ❌ Your answer: ${selectedValue} | Correct: ${correct}</div>`;
				}
			}

			function checkText(qName, correct, label, section) {
				totalQs++;
				let input = document.querySelector(`[name='${qName}']`);
				if (!input) return;
				let ans = input.value.trim().toLowerCase();
				let acceptedAnswers = Array.isArray(correct) ? correct : [correct];
				let isCorrect = acceptedAnswers.some(acceptedAns => 
					ans.includes(acceptedAns.toLowerCase()) || acceptedAns.toLowerCase().includes(ans)
				);

				if (isCorrect && ans.length > 0) {
					score++;
					resultsHTML += `<div class="result-item result-correct"><strong>${section} - ${label}:</strong> ✅ Correct</div>`;
				} else {
					let correctDisplay = Array.isArray(correct) ? correct.join(" or ") : correct;
					resultsHTML += `<div class="result-item result-incorrect"><strong>${section} - ${label}:</strong> ❌ Your answer: "${input.value}" | Correct: ${correctDisplay}</div>`;
				}
			}

			// Reading
			resultsHTML += '<h3 class="mt-4 mb-3"><i class="bi bi-book-fill"></i> Reading</h3>';
			checkRadio("r1", answerKey.r1, "Q1", "Reading");
			checkText("r2", answerKey.r2, "Q2", "Reading");
			checkRadio("r3", answerKey.r3, "Q3", "Reading");
			checkRadio("r4", answerKey.r4, "Q4", "Reading");
			checkRadio("r5", answerKey.r5, "Q5", "Reading");

			// Listening
			resultsHTML += '<h3 class="mt-4 mb-3"><i class="bi bi-headphones"></i> Listening</h3>';
			checkRadio("q1", answerKey.q1, "Q1", "Listening");
			checkRadio("q2", answerKey.q2, "Q2", "Listening");
			checkText("q3", answerKey.q3, "Q3", "Listening");
			checkRadio("q4", answerKey.q4, "Q4", "Listening");
			checkRadio("q5", answerKey.q5, "Q5", "Listening");

			// Grammar
			resultsHTML += '<h3 class="mt-4 mb-3"><i class="bi bi-journal-text"></i> Grammar & Vocabulary</h3>';
			checkText("g1", answerKey.g1, "Q1", "Grammar");
			checkRadio("g2", answerKey.g2, "Q2", "Grammar");
			checkText("g3", answerKey.g3, "Q3", "Grammar");
			checkText("g4", answerKey.g4, "Q4", "Grammar");
			checkRadio("g5", answerKey.g5, "Q5", "Grammar");

			// Final score
			let percentage = Math.round((score / totalQs) * 100);
			let grade = percentage >= 90 ? "A+" : percentage >= 80 ? "A" : percentage >= 70 ? "B" : percentage >= 60 ? "C" : "Needs Improvement";
			
			resultsHTML += `<div class="final-score">
				<div><i class="bi bi-award-fill" style="font-size: 3rem;"></i></div>
				<div class="mt-3">Final Score: ${score} / ${totalQs} (${percentage}%)</div>
				<div class="mt-2">Grade: ${grade}</div>
			</div>`;
			resultsHTML += '<div class="text-center mt-4 mb-4"><a href="diagnostic_tests_home.php" class="btn btn-primary btn-lg"><i class="bi bi-arrow-left"></i> Back to Diagnostic Tests</a></div>';
			resultsHTML += '</div>';

			// Replace content
			document.querySelector('.test-container').innerHTML = resultsHTML;
			document.querySelector('.content-wrapper').scrollTo({ top: 0, behavior: 'smooth' });
		}

		// Initialize
		showSection(0);
	</script>
</body>
</html>