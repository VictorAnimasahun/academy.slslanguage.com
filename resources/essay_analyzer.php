<?php
require_once dirname(__DIR__) . '/bootstrap.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: " . ACADEMY_URL . "edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

// Check if test data was passed via URL parameters
$practiceMode = false;
$practiceData = null;

if (isset($_GET['type']) && isset($_GET['question'])) {
    $practiceMode = true;
    $practiceData = [
        'type' => htmlspecialchars($_GET['type'], ENT_QUOTES, 'UTF-8'),
        'title' => htmlspecialchars($_GET['title'] ?? 'Practice Test', ENT_QUOTES, 'UTF-8'),
        'question' => $_GET['question'],
        'timeLimit' => intval($_GET['time'] ?? 40),
        'wordTarget' => intval($_GET['words'] ?? 250),
        'testType' => htmlspecialchars($_GET['testType'] ?? 'Writing Test', ENT_QUOTES, 'UTF-8'),
        'visualType' => $_GET['visualType'] ?? null,
        'chartConfig' => $_GET['chartConfig'] ?? null,
        'tableData' => $_GET['tableData'] ?? null,
        'processSteps' => $_GET['processSteps'] ?? null,
        'mapDescription' => $_GET['mapDescription'] ?? null,
        'imageUrl' => $_GET['imageUrl'] ?? null,
        'imageAlt' => $_GET['imageAlt'] ?? null
    ];
}

// Get rate limit stats
require_once INCLUDES_PATH . '/rate_limiter.php';
$rateLimiter = new RateLimiter($db);
$stats = $rateLimiter->getUserStats($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Essay Analyzer | EduHub</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
<link href="<?php echo ACADEMY_URL; ?>assets/css/essay_analyzer.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<style>
/* Same styles as before */
.usage-banner {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.practice-mode-banner {
    background: linear-gradient(135deg, #10b981, #34d399);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
	position: sticky;
    top: 1px;
    z-index: 999;
}

.practice-mode-banner i {
    font-size: 1.5rem;
	
}

.timer-display {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1.2rem;
    font-family: 'Courier New', monospace;
}

.timer-controls {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.timer-controls button {
    background: rgba(255, 255, 255, 0.3);
    border: none;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
}

.timer-controls button:hover {
    background: rgba(255, 255, 255, 0.5);
}

.question-readonly {
    background-color: #f8f9fa !important;
    cursor: not-allowed;
}

/* Visual Display Styles */
.visual-container {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.visual-container h5 {
    color: #1f2937;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.chart-wrapper {
    position: relative;
    height: 400px;
    max-width: 100%;
}

.image-wrapper {
    text-align: center;
}

.image-wrapper img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Word Counter Styles */
.word-counter {
    font-size: 0.95rem;
    font-weight: 600;
    color: #1f2937;
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
    border-radius: 8px;
    border: 1px solid #bae6fd;
    transition: all 0.3s ease;
}

.word-counter.warning {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border-color: #fcd34d;
    color: #92400e;
}

.word-counter.success {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    border-color: #6ee7b7;
    color: #065f46;
}

.word-counter.danger {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    border-color: #fca5a5;
    color: #991b1b;
}

/* Results Modal */
.results-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    padding: 1rem;
    overflow-y: auto;
}

.results-modal.active {
    display: flex;
}

.results-modal-content {
    background: white;
    border-radius: 16px;
    max-width: 1200px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from {
        transform: translateY(50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-header-custom {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 2rem;
    border-radius: 16px 16px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header-custom h2 {
    margin: 0;
    font-size: 1.8rem;
    font-weight: 700;
}

.close-modal-btn {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1.5rem;
    transition: all 0.3s;
}

.close-modal-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

.modal-body-custom {
    padding: 2rem;
}

.modal-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 12px;
    border-left: 4px solid #667eea;
}

.modal-section h3 {
    color: #1f2937;
    font-size: 1.3rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.original-essay {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    font-family: 'Georgia', serif;
    line-height: 1.8;
    color: #374151;
}

.score-banner {
    background: linear-gradient(135deg, #10b981, #34d399);
    color: white;
    padding: 1.5rem;
    border-radius: 12px;
    text-align: center;
    margin-bottom: 2rem;
}

.score-banner .score {
    font-size: 3rem;
    font-weight: 800;
    margin: 0.5rem 0;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid #e5e7eb;
}

.btn-download-pdf {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    border: none;
    padding: 0.8rem 2rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-download-pdf:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
}

.btn-close-modal {
    background: #6b7280;
    color: white;
    border: none;
    padding: 0.8rem 2rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-close-modal:hover {
    background: #4b5563;
}
</style>
</head>
<body>

<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<?php include INCLUDES_PATH . '/navbar.php'; ?>

<main class="main-wrapper">
    <div class="container">
        <h1 class="page-title display-5 mb-2">IELTS & CELPIP Essay Analyzer</h1>
        <p class="page-subtitle mb-4">Get instant band score with detailed AI feedback</p>

        <?php if ($practiceMode): ?>
        <!-- Practice Mode Banner with Timer -->
        <div class="practice-mode-banner floating-timer">
            <i class="bi bi-clipboard-check-fill"></i>
            <div class="flex-grow-1">
                <h6 class="mb-0"><strong><?php echo $practiceData['testType']; ?></strong></h6>
                <small><?php echo $practiceData['title']; ?> • Target: <?php echo $practiceData['wordTarget']; ?> words</small>
            </div>
            <div class="timer-controls">
                <button id="startTimerBtn" type="button">
                    <i class="bi bi-play-fill"></i> Start Timer
                </button>
                <div class="timer-display" id="timerDisplay">
                    <?php echo sprintf('%02d:00', $practiceData['timeLimit']); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

		<!-- Visual Display (Image Only) -->
		<?php if ($practiceMode && $practiceData['visualType'] === 'image'): ?>
			<div class="visual-container">
				<h5>
					<i class="bi bi-image-fill"></i>
					Diagram
				</h5>

				<div class="image-wrapper">
					<img src="<?php echo htmlspecialchars($practiceData['imageUrl']); ?>" 
						alt="<?php echo htmlspecialchars($practiceData['imageAlt']); ?>"
						id="practiceImage">
				</div>
			</div>
		<?php endif; ?>


        <!-- Analyzer Form -->
        <div class="analyzer-card">
            <form id="analyzerForm">
                
                <!-- Exam Type Selector -->
                <div class="exam-selector">
                    <button type="button" class="exam-btn active" data-exam="IELTS">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        IELTS Writing Task 2
                    </button>
                    <button type="button" class="exam-btn" data-exam="CELPIP">
                        <i class="bi bi-circle me-2"></i>
                        CELPIP Writing
                    </button>
                </div>

                <!-- Question Display -->
                <div class="question-display">
                    <h5><i class="bi bi-question-circle-fill me-2"></i>Essay Question</h5>
                    <p id="questionText">Paste or type your essay question here first, then write your essay below.</p>
                </div>

                <!-- Question Input -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Your Essay Question</label>
                    <textarea 
                        id="questionInput" 
                        class="essay-input" 
                        style="min-height: 100px;"
                        placeholder="Example: Some people think that governments should spend money on faster means of public transport..."
                        required
                    ></textarea>
                </div>

                <!-- Essay Input with Word Counter -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-bold mb-0">Your Essay</label>
                        <div class="word-counter">
                            <span id="wordCount">0</span> words
                            <span id="charCount" class="ms-2 text-muted">| 0 characters</span>
                        </div>
                    </div>
                    <textarea 
                        id="essayInput" 
                        class="essay-input"
                        placeholder="Paste or type your complete essay here..."
                        required
                    ></textarea>
                    <?php if ($practiceMode): ?>
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Target: <strong><?php echo $practiceData['wordTarget']; ?> words</strong>
                    </small>
                    <?php endif; ?>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="analyze-btn">
                    <i class="bi bi-stars me-2"></i>
                    Analyze My Essay
                </button>
            </form>
        </div>

        <!-- Loading Animation -->
        <div class="loading-container" id="loadingContainer">
            <div class="spinner"></div>
            <p class="loading-text">Analyzing your essay with AI...</p>
            <p class="text-muted">This may take 10-20 seconds</p>
        </div>

        <!-- Results Section -->
        <div class="results-container" id="resultsContainer">
            <!-- Score Card -->
            <div class="result-card score-card">
                <h3 class="mb-3"><i class="bi bi-trophy-fill me-2"></i>Your Score</h3>
                <div class="score-display" id="scoreDisplay">-</div>
                <p class="mb-0 opacity-90" id="scoreLabel">Overall Band Score</p>
            </div>

            <!-- Feedback Card -->
            <div class="result-card">
                <h4><i class="bi bi-chat-left-text-fill me-2"></i>AI Examiner Feedback</h4>
                <div class="feedback-content" id="feedbackContent"></div>
            </div>

            <!-- Grammar Card -->
            <div class="result-card grammar-card" id="grammarCard">
                <h4><i class="bi bi-spell-check me-2"></i>Grammar & Spelling Check</h4>
                <div id="grammarContent"></div>
            </div>
        </div>

    </div>

	<!-- Results Modal -->
	<div class="results-modal" id="resultsModal">
		<div class="results-modal-content">
			<div class="modal-header-custom">
				<h2><i class="bi bi-file-earmark-text-fill me-2"></i>Essay Analysis Report</h2>
				<button class="close-modal-btn" onclick="closeResultsModal()">×</button>
			</div>
			
			<div class="modal-body-custom">
				<!-- Score Banner -->
				<div class="score-banner">
					<div><i class="bi bi-trophy-fill" style="font-size: 2rem;"></i></div>
					<div class="score" id="modalScore">-</div>
					<div id="modalScoreLabel">Overall Band Score</div>
				</div>

				<!-- Original Question -->
				<div class="modal-section">
					<h3><i class="bi bi-question-circle-fill"></i>Essay Question</h3>
					<div class="original-essay" id="modalQuestion"></div>
				</div>

				<!-- Original Essay -->
				<div class="modal-section">
					<h3><i class="bi bi-pencil-fill"></i>Your Essay</h3>
					<div class="original-essay" id="modalEssay"></div>
				</div>

				<!-- AI Feedback -->
				<div class="modal-section">
					<h3><i class="bi bi-chat-left-text-fill"></i>AI Examiner Feedback</h3>
					<div id="modalFeedback"></div>
				</div>

				<!-- Grammar Check -->
				<div class="modal-section" id="modalGrammarSection">
					<h3><i class="bi bi-spell-check"></i>Grammar & Spelling</h3>
					<div id="modalGrammar"></div>
				</div>

				<!-- Action Buttons -->
				<div class="action-buttons">
					<button class="btn-download-pdf" onclick="downloadPDF()">
						<i class="bi bi-file-pdf-fill me-2"></i>Download as PDF
					</button>
					<button class="btn-close-modal" onclick="closeResultsModal()">Close</button>
				</div>
			</div>
		</div>
	</div>
</main>

<?php include INCLUDES_PATH . '/adverts.php'; ?>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js for graphs -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Practice mode data from PHP
const practiceMode = <?php echo $practiceMode ? 'true' : 'false'; ?>;
const practiceData = <?php echo $practiceMode ? json_encode($practiceData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) : 'null'; ?>;

// Timer variables
let timerInterval = null;
let timeRemaining = 0;
let practiceChart = null;

// Initialize practice mode
if (practiceMode && practiceData) {
    document.addEventListener('DOMContentLoaded', function() {
        // Pre-fill the question
        const questionInput = document.getElementById('questionInput');
        const questionText = document.getElementById('questionText');
        
        questionInput.value = practiceData.question;
        questionText.textContent = practiceData.question;
        
        // Make question readonly and style it
        questionInput.setAttribute('readonly', true);
        questionInput.classList.add('question-readonly');
        
        // Set exam type based on practice data
        if (practiceData.type.includes('celpip')) {
            document.querySelector('.exam-btn[data-exam="CELPIP"]').click();
        }
        
        // Initialize chart if present
        if (practiceData.visualType === 'chart' && practiceData.chartConfig) {
            try {
                const chartConfig = JSON.parse(practiceData.chartConfig);
                const ctx = document.getElementById('practiceChart');
                if (ctx) {
                    practiceChart = new Chart(ctx, chartConfig);
                }
            } catch (error) {
                console.error('Error loading chart:', error);
            }
        }
        
        // Show notification
        showNotification(`Practice test loaded: ${practiceData.title}`, 'success');
        
        // Initialize timer
        timeRemaining = practiceData.timeLimit * 60;
        updateTimerDisplay();

		// ⏱️ Auto-start timer as soon as page loads
		startTimer();
    });
}

// Timer functions
function startTimer() {
    if (timerInterval) return;
    
    const startBtn = document.getElementById('startTimerBtn');
    startBtn.innerHTML = '<i class="bi bi-pause-fill"></i> Pause';
    startBtn.onclick = pauseTimer;
    
    timerInterval = setInterval(() => {
        timeRemaining--;
        updateTimerDisplay();
        
        if (timeRemaining <= 0) {
            stopTimer();
            playAlarm();
            alert('Time is up! Your ' + practiceData.timeLimit + ' minutes are over.');
        }
    }, 1000);
}

function pauseTimer() {
    if (timerInterval) {
        clearInterval(timerInterval);
        timerInterval = null;
        
        const startBtn = document.getElementById('startTimerBtn');
        startBtn.innerHTML = '<i class="bi bi-play-fill"></i> Resume';
        startBtn.onclick = startTimer;
    }
}

function stopTimer() {
    if (timerInterval) {
        clearInterval(timerInterval);
        timerInterval = null;
    }
}

function updateTimerDisplay() {
    const minutes = Math.floor(timeRemaining / 60);
    const seconds = timeRemaining % 60;
    const display = document.getElementById('timerDisplay');
    
    if (display) {
        display.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        
        if (timeRemaining <= 60) {
            display.style.background = 'rgba(239, 68, 68, 0.3)';
        } else if (timeRemaining <= 300) {
            display.style.background = 'rgba(251, 191, 36, 0.3)';
        }
    }
}

function playAlarm() {
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();
    
    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);
    
    oscillator.frequency.value = 800;
    oscillator.type = 'sine';
    
    gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
    
    oscillator.start(audioContext.currentTime);
    oscillator.stop(audioContext.currentTime + 0.5);
}

// Attach timer start button
if (practiceMode) {
    document.addEventListener('DOMContentLoaded', function() {
        const startBtn = document.getElementById('startTimerBtn');
        if (startBtn) {
            startBtn.onclick = startTimer;
        }
    });
}

// Notification helper
function showNotification(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.top = '20px';
    alertDiv.style.right = '20px';
    alertDiv.style.zIndex = '9999';
    alertDiv.style.minWidth = '300px';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Exam selector
let selectedExam = 'IELTS';
document.querySelectorAll('.exam-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        if (practiceMode) {
            showNotification('Exam type is locked for this practice test', 'warning');
            return;
        }
        
        document.querySelectorAll('.exam-btn').forEach(b => {
            b.classList.remove('active');
            b.querySelector('i').className = 'bi bi-circle me-2';
        });
        this.classList.add('active');
        this.querySelector('i').className = 'bi bi-check-circle-fill me-2';
        selectedExam = this.dataset.exam;
    });
});

// Question input handler
document.getElementById('questionInput').addEventListener('input', function() {
    if (!practiceMode) {
        const questionText = this.value.trim();
        document.getElementById('questionText').textContent = questionText || 'Paste or type your essay question here first, then write your essay below.';
    }
});

// WORD COUNTER - NEW FEATURE
const essayInput = document.getElementById('essayInput');
const wordCountDisplay = document.getElementById('wordCount');
const charCountDisplay = document.getElementById('charCount');
const wordCounterContainer = document.querySelector('.word-counter');

essayInput.addEventListener('input', updateWordCount);

function updateWordCount() {
    const text = essayInput.value.trim();
    
    // Count words (split by whitespace and filter empty strings)
    const words = text.length > 0 ? text.split(/\s+/).filter(word => word.length > 0) : [];
    const wordCount = words.length;
    
    // Count characters (excluding whitespace)
    const charCount = text.length;
    
    // Update displays
    wordCountDisplay.textContent = wordCount;
    charCountDisplay.textContent = `| ${charCount} characters`;
    
    // Color coding based on practice mode target or IELTS standards
    if (practiceMode && practiceData.wordTarget) {
        const target = practiceData.wordTarget;
        
        wordCounterContainer.classList.remove('warning', 'success', 'danger');
        
        if (wordCount < target * 0.8) {
            // Less than 80% of target
            wordCounterContainer.classList.add('warning');
        } else if (wordCount >= target * 0.8 && wordCount <= target * 1.2) {
            // Within 80-120% of target (good range)
            wordCounterContainer.classList.add('success');
        } else if (wordCount > target * 1.2) {
            // More than 120% of target
            wordCounterContainer.classList.add('danger');
        }
    } else {
        // Standard IELTS Task 2: minimum 250 words
        wordCounterContainer.classList.remove('warning', 'success', 'danger');
        
        if (wordCount < 250) {
            wordCounterContainer.classList.add('warning');
        } else if (wordCount >= 250 && wordCount <= 350) {
            wordCounterContainer.classList.add('success');
        } else if (wordCount > 350) {
            // Still okay but might be too long for time constraints
            wordCounterContainer.classList.remove('warning', 'success', 'danger');
        }
    }
}

// Form submission
document.getElementById('analyzerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const question = document.getElementById('questionInput').value.trim();
    const essay = document.getElementById('essayInput').value.trim();
    
    if (!question || !essay) {
        alert('Please enter both the question and your essay.');
        return;
    }
    
    if (practiceMode && timerInterval) {
        stopTimer();
    }

    document.getElementById('loadingContainer').classList.add('active');
    document.getElementById('resultsContainer').classList.remove('active');
    document.querySelector('.analyzer-card').style.display = 'none';

    try {
        const response = await fetch('<?php echo ACADEMY_URL; ?>api/api_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'analyze_essay',
                question: question,
                essay: essay,
                exam_type: selectedExam
            })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Analysis failed');
        }

        const lt = await fetch('https://api.languagetool.org/v2/check', {
            method: 'POST',
            body: new URLSearchParams({ text: essay, language: 'en-US' })
        }).then(r => r.json());

        displayResults(data.feedback, lt, selectedExam, data.remaining);

    } catch (error) {
        alert('Error: ' + error.message);
        document.querySelector('.analyzer-card').style.display = 'block';
    } finally {
        document.getElementById('loadingContainer').classList.remove('active');
    }
});

function displayResults(feedback, grammarData, exam, remaining) {
    const overallScoreMatch = feedback.match(/(?:overall|band score|level)[:\s]*(?:high proficiency\s*)?\(?(\d+(?:\.\d+)?)/i);
    const overallScore = overallScoreMatch ? overallScoreMatch[1] : '-';
    
    // Populate modal
    document.getElementById('modalScore').textContent = overallScore;
    document.getElementById('modalScoreLabel').textContent = exam === 'IELTS' ? 'Overall Band Score (out of 9.0)' : 'Overall Score (out of 12)';
    
    document.getElementById('modalQuestion').textContent = document.getElementById('questionInput').value;
    document.getElementById('modalEssay').textContent = document.getElementById('essayInput').value;
    
    const formattedFeedback = formatFeedback(feedback, exam);
    document.getElementById('modalFeedback').innerHTML = formattedFeedback;
    
    // Grammar section
    const modalGrammar = document.getElementById('modalGrammar');
    if (grammarData.matches && grammarData.matches.length > 0) {
        let html = `<p class="mb-3"><strong>${grammarData.matches.length} issues found</strong></p>`;
        
        grammarData.matches.slice(0, 15).forEach(m => {
            html += `
                <div style="background: white; padding: 1rem; margin-bottom: 0.5rem; border-radius: 8px; border-left: 3px solid #ef4444;">
                    <strong>Issue:</strong> ${m.message}<br>
                    <span style="color: #6b7280;">Context: "${m.context.text}"</span><br>
                    ${m.replacements.length > 0 ? `<span style="color: #10b981;"><strong>Suggestion:</strong> ${m.replacements.slice(0, 3).map(r => r.value).join(', ')}</span>` : ''}
                </div>
            `;
        });
        
        if (grammarData.matches.length > 15) {
            html += `<p style="color: #6b7280; font-style: italic;">+ ${grammarData.matches.length - 15} more issues...</p>`;
        }
        
        modalGrammar.innerHTML = html;
    } else {
        modalGrammar.innerHTML = '<p><strong><i class="bi bi-check-circle-fill me-2" style="color: #10b981;"></i>Excellent! No major grammar or spelling issues detected.</strong></p>';
    }

    // Update remaining quota
    if (remaining !== undefined) {
        const usageBanner = document.querySelector('.usage-banner');
        if (usageBanner) {
            const badge = usageBanner.querySelector('.badge');
            if (badge) {
                badge.textContent = `${remaining} remaining today`;
            }
        }
    }

    // Hide loading and show modal
    document.getElementById('loadingContainer').classList.remove('active');
    document.getElementById('resultsModal').classList.add('active');
}

function formatFeedback(feedback, exam) {
    let html = '';
    let content = feedback;
    
    content = content.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
    
    const sections = content.split(/(?=\*\s+\*\*)/);
    
    sections.forEach(section => {
        section = section.trim();
        if (!section) return;
        
        if (section.startsWith('*')) {
            const headingMatch = section.match(/^\*\s+<strong>([^<]+)<\/strong>/);
            if (headingMatch) {
                const heading = headingMatch[1];
                const content = section.substring(headingMatch[0].length).trim();
                
                html += `<h5><i class="bi bi-check-circle-fill me-2"></i>${heading}</h5>`;
                
                const bullets = content.split(/\n\s*\*\s+/).filter(b => b.trim());
                if (bullets.length > 0) {
                    html += '<ul>';
                    bullets.forEach(bullet => {
                        if (bullet.trim()) {
                            html += `<li>${bullet.trim()}</li>`;
                        }
                    });
                    html += '</ul>';
                } else {
                    html += `<p>${content}</p>`;
                }
            }
        } else {
            const lines = section.split('\n').filter(l => l.trim());
            lines.forEach(line => {
                if (line.trim() && !line.includes('---')) {
                    html += `<p>${line.trim()}</p>`;
                }
            });
        }
    });
    
    return html;
}

function closeResultsModal() {
    document.getElementById('resultsModal').classList.remove('active');
    document.querySelector('.analyzer-card').style.display = 'block';
}

function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    const question = document.getElementById('modalQuestion').textContent;
    const essay = document.getElementById('modalEssay').textContent;
    const score = document.getElementById('modalScore').textContent;
    const scoreLabel = document.getElementById('modalScoreLabel').textContent;
    const feedback = document.getElementById('modalFeedback').innerText;
    const grammar = document.getElementById('modalGrammar').innerText;
    
    let yPos = 20;
    const pageWidth = doc.internal.pageSize.getWidth();
    const margin = 20;
    const maxWidth = pageWidth - (margin * 2);
    
    // Title
    doc.setFontSize(20);
    doc.setTextColor(102, 126, 234);
    doc.text('Essay Analysis Report', margin, yPos);
    yPos += 15;
    
    // Score
    doc.setFontSize(16);
    doc.setTextColor(16, 185, 129);
    doc.text(`${scoreLabel}: ${score}`, margin, yPos);
    yPos += 15;
    
    // Question
    doc.setFontSize(12);
    doc.setTextColor(0, 0, 0);
    doc.setFont(undefined, 'bold');
    doc.text('Essay Question:', margin, yPos);
    yPos += 7;
    doc.setFont(undefined, 'normal');
    const questionLines = doc.splitTextToSize(question, maxWidth);
    doc.text(questionLines, margin, yPos);
    yPos += (questionLines.length * 7) + 10;
    
    // Essay
    if (yPos > 250) {
        doc.addPage();
        yPos = 20;
    }
    doc.setFont(undefined, 'bold');
    doc.text('Your Essay:', margin, yPos);
    yPos += 7;
    doc.setFont(undefined, 'normal');
    const essayLines = doc.splitTextToSize(essay, maxWidth);
    doc.text(essayLines, margin, yPos);
    yPos += (essayLines.length * 7) + 10;
    
    // Feedback
    if (yPos > 250) {
        doc.addPage();
        yPos = 20;
    }
    doc.setFont(undefined, 'bold');
    doc.text('AI Examiner Feedback:', margin, yPos);
    yPos += 7;
    doc.setFont(undefined, 'normal');
    const feedbackLines = doc.splitTextToSize(feedback, maxWidth);
    doc.text(feedbackLines, margin, yPos);
    yPos += (feedbackLines.length * 7) + 10;
    
    // Grammar
    if (yPos > 250) {
        doc.addPage();
        yPos = 20;
    }
    doc.setFont(undefined, 'bold');
    doc.text('Grammar & Spelling:', margin, yPos);
    yPos += 7;
    doc.setFont(undefined, 'normal');
    const grammarLines = doc.splitTextToSize(grammar, maxWidth);
    doc.text(grammarLines, margin, yPos);
    
    // Save
    doc.save(`Essay_Analysis_${score}_${new Date().toISOString().split('T')[0]}.pdf`);
}
</script>

</body>
</html>