<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

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
	<title>Sentence Sequencing | EduHub</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
	<?php include INCLUDES_PATH . '/navbar_styles.php'; ?>

	<style>
		.main-wrapper { padding: 2rem 1.5rem; }
		.exercise-container { max-width: 900px; margin: 0 auto; background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
		.exercise-header { margin-bottom: 2rem; border-bottom: 2px solid #f0f0f0; padding-bottom: 1.5rem; }
		.exercise-title { font-size: 2rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem; }
		.exercise-description { color: #6b7280; font-size: 1rem; }
		
		.sentences-area { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin: 2rem 0; }
		.source-box, .answer-box { background: #f8f9fa; border-radius: 8px; padding: 1.5rem; }
		.source-box h3, .answer-box h3 { color: #667eea; font-weight: 600; margin-bottom: 1rem; }
		
		.sentence-item {
			background: white; border: 2px solid #ddd; border-radius: 6px; padding: 1rem; margin-bottom: 0.75rem;
			cursor: grab; user-select: none; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
		}
		.sentence-item:hover { box-shadow: 0 4px 8px rgba(0,0,0,0.1); transform: translateY(-2px); }
		.sentence-item.dragging { opacity: 0.5; cursor: grabbing; }
		.sentence-item.placed { background: #f0f4ff; border-color: #667eea; cursor: default; }
		
		.answer-sequence { min-height: 200px; }
		.ordered-item { display: flex; gap: 1rem; align-items: center; margin-bottom: 0.5rem; }
		.order-number { background: #667eea; color: white; width: 2rem; height: 2rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }
		.remove-btn { background: #ef4444; color: white; border: none; padding: 0.25rem 0.75rem; border-radius: 4px; cursor: pointer; font-size: 0.8rem; }
		
		.button-group { display: flex; gap: 1rem; justify-content: center; margin-top: 2rem; }
		button { background: #667eea; color: white; border: none; padding: 0.75rem 2rem; border-radius: 6px; cursor: pointer; font-weight: 500; transition: all 0.3s; }
		button:hover { background: #764ba2; transform: translateY(-2px); }
		
		.feedback { text-align: center; margin: 1.5rem 0; padding: 1rem; border-radius: 8px; font-weight: 600; display: none; }
		.feedback.show { display: block; }
		.feedback.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
		.feedback.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
		
		@media (max-width: 768px) {
			.sentences-area { grid-template-columns: 1fr; }
			.exercise-container { padding: 1.5rem; }
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
				<h1 class="exercise-title"><i class="bi bi-shuffle"></i> Sentence Sequencing</h1>
				<p class="exercise-description">Arrange the sentences in the correct logical order</p>
			</div>

			<div class="feedback" id="feedback"></div>

			<div class="sentences-area">
				<div class="source-box">
					<h3><i class="bi bi-question-circle"></i> Scrambled Sentences</h3>
					<div id="sourceSentences"></div>
				</div>

				<div class="answer-box">
					<h3><i class="bi bi-check-circle"></i> Correct Order</h3>
					<div class="answer-sequence" id="answerSequence"></div>
				</div>
			</div>

			<div class="button-group">
				<button onclick="checkAnswers()"><i class="bi bi-check-circle"></i> Check Answers</button>
				<button onclick="resetExercise()"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
			</div>
		</div>
	</main>

	<?php include INCLUDES_PATH . '/adverts.php'; ?>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>

	<script>
		const sentences = [
			{ id: 1, text: "Today, many students struggle with time management.", order: 1 },
			{ id: 2, text: "As a result, their academic performance suffers significantly.", order: 4 },
			{ id: 3, text: "This is mainly because they have numerous distractions.", order: 2 },
			{ id: 4, text: "Therefore, learning to prioritize tasks is essential.", order: 3 }
		];

		let draggedElement = null;
		let answerOrder = [];

		function initializeExercise() {
			const source = document.getElementById('sourceSentences');
			source.innerHTML = '';
			answerOrder = [];

			// Shuffle sentences for display
			const shuffled = [...sentences].sort(() => Math.random() - 0.5);
			
			shuffled.forEach(item => {
				const div = document.createElement('div');
				div.className = 'sentence-item';
				div.draggable = true;
				div.textContent = item.text;
				div.dataset.id = item.id;
				div.dataset.order = item.order;
				div.addEventListener('dragstart', e => { draggedElement = e.target; div.classList.add('dragging'); });
				div.addEventListener('dragend', () => div.classList.remove('dragging'));
				source.appendChild(div);
			});

			setupDropZone();
		}

		function setupDropZone() {
			const zone = document.getElementById('answerSequence');
			zone.addEventListener('dragover', e => { e.preventDefault(); e.dataTransfer.dropEffect = 'move'; });
			zone.addEventListener('drop', e => {
				e.preventDefault();
				if (!draggedElement) return;
				const clone = draggedElement.cloneNode(true);
				clone.draggable = false;
				clone.classList.add('placed');
				zone.appendChild(clone);
				answerOrder.push({ id: clone.dataset.id, order: clone.dataset.order, text: clone.textContent });
				renderAnswer();
			});
		}

		function renderAnswer() {
			const zone = document.getElementById('answerSequence');
			zone.innerHTML = '';
			answerOrder.forEach((item, idx) => {
				const div = document.createElement('div');
				div.className = 'ordered-item';
				div.innerHTML = `<div class="order-number">${idx + 1}</div><span>${item.text}</span><button class="remove-btn" onclick="removeItem(${idx})">Remove</button>`;
				zone.appendChild(div);
			});
		}

		function removeItem(idx) {
			answerOrder.splice(idx, 1);
			renderAnswer();
		}

		function checkAnswers() {
			const correctOrder = sentences.map(s => s.order).sort((a, b) => a - b);
			const studentOrder = answerOrder.map(item => parseInt(item.order));
			const isCorrect = JSON.stringify(studentOrder) === JSON.stringify(correctOrder);

			const feedback = document.getElementById('feedback');
			feedback.classList.add('show');
			if (isCorrect) {
				feedback.className = 'feedback success show';
				feedback.innerHTML = '<i class="bi bi-check-circle"></i> Perfect! You got the correct sequence!';
			} else {
				feedback.className = 'feedback error show';
				feedback.innerHTML = '<i class="bi bi-x-circle"></i> Not quite right. Try again!';
			}
		}

		function resetExercise() {
			answerOrder = [];
			document.getElementById('feedback').classList.remove('show');
			initializeExercise();
		}

		initializeExercise();
	</script>
</body>
</html>