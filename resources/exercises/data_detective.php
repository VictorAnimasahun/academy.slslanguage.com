<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Data Detective | IELTS Writing Task 1</title>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
	<style>
		* { margin: 0; padding: 0; box-sizing: border-box; }
		body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 20px; }
		
		.container { max-width: 1200px; margin: 0 auto; }
		
		.header { text-align: center; color: white; margin-bottom: 30px; }
		.header h1 { font-size: 2.5rem; margin-bottom: 10px; }
		.header p { font-size: 1.1rem; opacity: 0.9; }
		
		.main-content { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
		
		.card { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 8px 32px rgba(0,0,0,0.1); }
		
		.chart-card { grid-column: span 1; }
		.chart-container { position: relative; height: 300px; margin-bottom: 15px; }
		.chart-info { font-size: 0.9rem; color: #666; text-align: center; }
		
		.questions-card { grid-column: span 1; }
		.questions-card h2 { color: #667eea; margin-bottom: 20px; font-size: 1.5rem; }
		
		.question-group { margin-bottom: 20px; }
		.question-title { font-weight: 700; color: #1f2937; margin-bottom: 12px; font-size: 1rem; }
		
		.answer-options { display: flex; flex-direction: column; gap: 10px; }
		
		.option { 
			background: #f8f9fa; 
			border: 2px solid #e2e8f0; 
			border-radius: 8px; 
			padding: 12px 15px; 
			cursor: pointer; 
			transition: all 0.3s ease;
			font-size: 0.95rem;
		}
		
		.option:hover { background: #e8eaf6; border-color: #667eea; }
		
		.option input[type="radio"] { margin-right: 10px; cursor: pointer; }
		
		.option.selected { background: #e8eaf6; border-color: #667eea; font-weight: 600; }
		
		.button-group { display: flex; gap: 15px; margin-top: 25px; }
		
		button { 
			flex: 1; 
			padding: 12px 20px; 
			border: none; 
			border-radius: 8px; 
			font-size: 1rem; 
			font-weight: 600; 
			cursor: pointer; 
			transition: all 0.3s ease;
		}
		
		.submit-btn { background: #667eea; color: white; }
		.submit-btn:hover { background: #764ba2; transform: translateY(-2px); }
		.submit-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
		
		.reset-btn { background: #64748b; color: white; }
		.reset-btn:hover { background: #475569; }
		
		.next-btn { background: #16a34a; color: white; }
		.next-btn:hover { background: #15803d; transform: translateY(-2px); }
		
		.feedback-box { 
			background: white; 
			border-radius: 12px; 
			padding: 20px; 
			margin-bottom: 20px; 
			box-shadow: 0 8px 32px rgba(0,0,0,0.1);
		}
		
		.feedback-box.success { border-left: 5px solid #16a34a; }
		.feedback-box.error { border-left: 5px solid #dc2626; }
		.feedback-box.neutral { border-left: 5px solid #667eea; }
		
		.feedback-title { font-weight: 700; font-size: 1.1rem; margin-bottom: 10px; }
		.feedback-box.success .feedback-title { color: #16a34a; }
		.feedback-box.error .feedback-title { color: #dc2626; }
		.feedback-box.neutral .feedback-title { color: #667eea; }
		
		.feedback-content { color: #4b5563; line-height: 1.6; }
		
		.score-display { text-align: center; font-size: 2rem; font-weight: 800; color: #667eea; margin: 20px 0; }
		
		.level-info { background: #f0f4ff; border-left: 4px solid #667eea; padding: 15px; margin-bottom: 20px; border-radius: 6px; }
		.level-info strong { color: #667eea; }
		
		@media (max-width: 768px) {
			.main-content { grid-template-columns: 1fr; }
			.header h1 { font-size: 1.8rem; }
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="header">
			<h1>🔍 Data Detective</h1>
			<p>Master IELTS Writing Task 1 by analyzing data like a pro</p>
		</div>

		<div class="level-info" id="levelInfo">
			<strong>Level <span id="currentLevel">1</span>/5:</strong> <span id="levelDesc">Line Graph - Simple Trend</span>
		</div>

		<div class="feedback-box" id="feedbackBox" style="display: none;"></div>

		<div class="main-content">
			<!-- Chart Card -->
			<div class="card chart-card">
				<h2 style="color: #667eea; margin-bottom: 15px;">📊 Analyze This Graph</h2>
				<div class="chart-container">
					<canvas id="dataChart"></canvas>
				</div>
				<p class="chart-info" id="chartInfo"></p>
			</div>

			<!-- Questions Card -->
			<div class="card questions-card">
				<h2>Your Task</h2>
				
				<form id="detectionForm">
					<!-- Question 1: Main Trend -->
					<div class="question-group">
						<div class="question-title">1️⃣ What is the MAIN trend?</div>
						<div class="answer-options" id="q1Options"></div>
					</div>

					<!-- Question 2: Unusual Pattern -->
					<div class="question-group">
						<div class="question-title">2️⃣ What's UNUSUAL or INTERESTING?</div>
						<div class="answer-options" id="q2Options"></div>
					</div>

					<!-- Question 3: Key Comparison -->
					<div class="question-group">
						<div class="question-title">3️⃣ What SHOULD YOU COMPARE?</div>
						<div class="answer-options" id="q3Options"></div>
					</div>

					<div class="button-group">
						<button type="button" class="submit-btn" onclick="checkAnswers()">Check Answers</button>
						<button type="button" class="reset-btn" onclick="resetExercise()">Reset</button>
					</div>
				</form>

				<div style="margin-top: 25px; text-align: center;" id="nextLevelContainer" style="display: none;">
					<button class="next-btn" onclick="nextLevel()">Next Level →</button>
				</div>
			</div>
		</div>
	</div>

	<script>
		let currentLevel = 1;
		let chart = null;
		let levelAnswered = false;

		const levels = {
			1: {
				title: "Line Graph - Simple Trend",
				description: "Website Visits (2019-2024)",
				type: "line",
				data: {
					labels: ['2019', '2020', '2021', '2022', '2023', '2024'],
					values: [5000, 7500, 12000, 18000, 24000, 31000]
				},
				questions: [
					{
						id: 'q1',
						question: "What is the MAIN trend?",
						options: [
							{ text: "Website visits remained stable", correct: false },
							{ text: "Website visits increased consistently throughout the period", correct: true },
							{ text: "Website visits fluctuated unpredictably", correct: false }
						]
					},
					{
						id: 'q2',
						question: "What's UNUSUAL or INTERESTING?",
						options: [
							{ text: "There was a sharp drop in 2021", correct: false },
							{ text: "The growth accelerated - bigger increases each year", correct: true },
							{ text: "The growth stopped after 2022", correct: false }
						]
					},
					{
						id: 'q3',
						question: "What SHOULD YOU COMPARE?",
						options: [
							{ text: "Just describe when the graph went up", correct: false },
							{ text: "Compare early years (2019-2021) with later years (2022-2024) to show acceleration", correct: true },
							{ text: "Compare each year individually without grouping", correct: false }
						]
					}
				]
			},
			2: {
				title: "Bar Chart - Multiple Categories",
				description: "Sales by Department (2024)",
				type: "bar",
				data: {
					labels: ['Marketing', 'Sales', 'IT', 'HR', 'Operations'],
					values: [45000, 78000, 52000, 28000, 65000]
				},
				questions: [
					{
						id: 'q1',
						question: "What is the MAIN observation?",
						options: [
							{ text: "All departments earned the same amount", correct: false },
							{ text: "Sales generated significantly more revenue than other departments", correct: true },
							{ text: "Marketing generated the most revenue", correct: false }
						]
					},
					{
						id: 'q2',
						question: "What's UNUSUAL or INTERESTING?",
						options: [
							{ text: "HR had the lowest sales performance", correct: true },
							{ text: "Operations performed better than Sales", correct: false },
							{ text: "Marketing and IT had identical figures", correct: false }
						]
					},
					{
						id: 'q3',
						question: "What SHOULD YOU COMPARE?",
						options: [
							{ text: "Compare each department individually without grouping", correct: false },
							{ text: "Group strong performers (Sales, Operations) vs weaker ones (HR, Marketing)", correct: true },
							{ text: "Only mention the highest figure", correct: false }
						]
					}
				]
			},
			3: {
				title: "Line Graph - Multiple Lines",
				description: "Coffee vs Tea Sales (2020-2024)",
				type: "line",
				data: {
					labels: ['2020', '2021', '2022', '2023', '2024'],
					datasets: [
						{ label: 'Coffee', values: [20, 25, 35, 45, 50] },
						{ label: 'Tea', values: [35, 32, 28, 25, 22] }
					]
				},
				questions: [
					{
						id: 'q1',
						question: "What is the MAIN trend?",
						options: [
							{ text: "Coffee and tea sales moved in the same direction", correct: false },
							{ text: "Coffee increased while tea decreased - they moved in opposite directions", correct: true },
							{ text: "Both stayed stable throughout the period", correct: false }
						]
					},
					{
						id: 'q2',
						question: "What's UNUSUAL or INTERESTING?",
						options: [
							{ text: "They crossed over - coffee started lower but ended higher", correct: true },
							{ text: "They maintained the same gap throughout", correct: false },
							{ text: "Tea was always higher", correct: false }
						]
					},
					{
						id: 'q3',
						question: "What SHOULD YOU COMPARE?",
						options: [
							{ text: "Describe each line separately without mentioning the relationship", correct: false },
							{ text: "Highlight the crossover point and compare the overall trends", correct: true },
							{ text: "Only mention which is higher in 2024", correct: false }
						]
					}
				]
			},
			4: {
				title: "Pie Chart with Breakdown",
				description: "Market Share Distribution",
				type: "pie",
				data: {
					labels: ['Company A', 'Company B', 'Company C', 'Others'],
					values: [35, 28, 22, 15]
				},
				questions: [
					{
						id: 'q1',
						question: "What is the MAIN observation?",
						options: [
							{ text: "All companies have equal market share", correct: false },
							{ text: "Company A dominates with over one-third of the market", correct: true },
							{ text: "Company B is the market leader", correct: false }
						]
					},
					{
						id: 'q2',
						question: "What's UNUSUAL or INTERESTING?",
						options: [
							{ text: "The top 3 companies control about 85% of the market", correct: true },
							{ text: "The market is perfectly balanced", correct: false },
							{ text: "'Others' has the largest share", correct: false }
						]
					},
					{
						id: 'q3',
						question: "What SHOULD YOU COMPARE?",
						options: [
							{ text: "List each percentage without grouping", correct: false },
							{ text: "Group: leader (A) vs strong competitors (B, C) vs rest", correct: true },
							{ text: "Only mention the percentages in order", correct: false }
						]
					}
				]
			},
			5: {
				title: "Table with Multiple Metrics",
				description: "Student Performance Data",
				type: "table",
				data: {
					rows: [
						{ label: 'Reading', 2020: 72, 2021: 75, 2022: 78, 2023: 81 },
						{ label: 'Writing', 2020: 68, 2021: 70, 2022: 72, 2023: 74 },
						{ label: 'Speaking', 2020: 70, 2021: 72, 2022: 75, 2023: 79 },
						{ label: 'Listening', 2020: 75, 2021: 78, 2022: 81, 2023: 84 }
					]
				},
				questions: [
					{
						id: 'q1',
						question: "What is the MAIN trend?",
						options: [
							{ text: "All skills improved over the 4-year period", correct: true },
							{ text: "Some skills improved while others declined", correct: false },
							{ text: "There was no consistent pattern", correct: false }
						]
					},
					{
						id: 'q2',
						question: "What's UNUSUAL or INTERESTING?",
						options: [
							{ text: "Listening showed the greatest improvement (9 points)", correct: true },
							{ text: "All skills improved equally", correct: false },
							{ text: "Writing showed the strongest growth", correct: false }
						]
					},
					{
						id: 'q3',
						question: "What SHOULD YOU COMPARE?",
						options: [
							{ text: "List each score individually", correct: false },
							{ text: "Group stronger skills (Reading, Listening) vs weaker (Writing) and highlight growth rates", correct: true },
							{ text: "Only mention 2023 scores", correct: false }
						]
					}
				]
			}
		};

		function renderChart() {
			const level = levels[currentLevel];
			const ctx = document.getElementById('dataChart').getContext('2d');
			
			if (chart) chart.destroy();

			if (level.type === 'line' && level.data.datasets) {
				// Multi-line chart
				chart = new Chart(ctx, {
					type: 'line',
					data: {
						labels: level.data.labels,
						datasets: [
							{
								label: level.data.datasets[0].label,
								data: level.data.datasets[0].values,
								borderColor: '#667eea',
								backgroundColor: 'rgba(102, 126, 234, 0.1)',
								tension: 0.4,
								fill: false
							},
							{
								label: level.data.datasets[1].label,
								data: level.data.datasets[1].values,
								borderColor: '#764ba2',
								backgroundColor: 'rgba(118, 75, 162, 0.1)',
								tension: 0.4,
								fill: false
							}
						]
					},
					options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: true } } }
				});
			} else if (level.type === 'line') {
				chart = new Chart(ctx, {
					type: 'line',
					data: {
						labels: level.data.labels,
						datasets: [{
							label: level.description,
							data: level.data.values,
							borderColor: '#667eea',
							backgroundColor: 'rgba(102, 126, 234, 0.1)',
							tension: 0.4
						}]
					},
					options: { responsive: true, maintainAspectRatio: false }
				});
			} else if (level.type === 'bar') {
				chart = new Chart(ctx, {
					type: 'bar',
					data: {
						labels: level.data.labels,
						datasets: [{
							label: level.description,
							data: level.data.values,
							backgroundColor: '#667eea'
						}]
					},
					options: { responsive: true, maintainAspectRatio: false }
				});
			} else if (level.type === 'pie') {
				chart = new Chart(ctx, {
					type: 'doughnut',
					data: {
						labels: level.data.labels,
						datasets: [{
							data: level.data.values,
							backgroundColor: ['#667eea', '#764ba2', '#16a34a', '#f59e0b']
						}]
					},
					options: { responsive: true, maintainAspectRatio: false }
				});
			}
		}

		function renderQuestions() {
			const level = levels[currentLevel];
			
			level.questions.forEach((q, idx) => {
				const container = document.getElementById(`q${idx + 1}Options`);
				container.innerHTML = '';
				
				q.options.forEach((opt, optIdx) => {
					const label = document.createElement('label');
					label.className = 'option';
					label.innerHTML = `
						<input type="radio" name="q${idx + 1}" value="${optIdx}">
						${opt.text}
					`;
					label.onclick = () => label.classList.add('selected');
					container.appendChild(label);
				});
			});
		}

		function initializeLevel() {
			const level = levels[currentLevel];
			document.getElementById('currentLevel').textContent = currentLevel;
			document.getElementById('levelDesc').textContent = level.title;
			document.getElementById('chartInfo').textContent = level.description;
			document.getElementById('feedbackBox').style.display = 'none';
			document.getElementById('nextLevelContainer').style.display = 'none';
			levelAnswered = false;

			renderChart();
			renderQuestions();

			// Clear form
			document.getElementById('detectionForm').reset();
			document.querySelectorAll('.option').forEach(el => el.classList.remove('selected'));
		}

		function checkAnswers() {
			const level = levels[currentLevel];
			let correct = 0;

			level.questions.forEach((q, idx) => {
				const selected = document.querySelector(`input[name="q${idx + 1}"]:checked`);
				if (selected) {
					const value = parseInt(selected.value);
					if (q.options[value].correct) correct++;
				}
			});

			const feedback = document.getElementById('feedbackBox');
			const score = Math.round((correct / level.questions.length) * 100);

			if (correct === level.questions.length) {
				feedback.className = 'feedback-box success';
				feedback.innerHTML = `
					<div class="feedback-title">🎉 Perfect! All Correct!</div>
					<div class="score-display">${score}%</div>
					<div class="feedback-content">
						You've identified the key features of this data perfectly. You're ready to write about this type of graph!
						<br><br>
						<strong>Writing tip:</strong> Always start by identifying these three elements before you write your Task 1 response.
					</div>
				`;
				document.getElementById('nextLevelContainer').style.display = currentLevel < 5 ? 'block' : 'none';
			} else if (correct >= 2) {
				feedback.className = 'feedback-box neutral';
				feedback.innerHTML = `
					<div class="feedback-title">Good! ${correct}/3 Correct</div>
					<div class="score-display">${score}%</div>
					<div class="feedback-content">
						You've got the main ideas, but let's strengthen your analysis. Review the incorrect answer and try again.
					</div>
				`;
			} else {
				feedback.className = 'feedback-box error';
				feedback.innerHTML = `
					<div class="feedback-title">Keep Trying! ${correct}/3 Correct</div>
					<div class="score-display">${score}%</div>
					<div class="feedback-content">
						Look more carefully at the data. Before writing, always ask:<br>
						1️⃣ What's the overall pattern?<br>
						2️⃣ What stands out?<br>
						3️⃣ What would a reader want to compare?
					</div>
				`;
			}

			feedback.style.display = 'block';
			levelAnswered = true;
		}

		function resetExercise() {
			document.getElementById('detectionForm').reset();
			document.querySelectorAll('.option').forEach(el => el.classList.remove('selected'));
			document.getElementById('feedbackBox').style.display = 'none';
			levelAnswered = false;
		}

		function nextLevel() {
			if (currentLevel < 5) {
				currentLevel++;
				initializeLevel();
			}
		}

		initializeLevel();
	</script>
</body>
</html>