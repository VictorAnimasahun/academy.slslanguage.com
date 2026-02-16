<?php
require_once dirname(__DIR__) . '/bootstrap.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: " . ACADEMY_URL . "edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

// Check if test data was passed via URL parameters (from practice_tests.php)
$practiceMode = false;
$practiceData = null;

if (isset($_GET['type']) && isset($_GET['question'])) {
    $practiceMode = true;
    $practiceData = [
        'type' => htmlspecialchars($_GET['type'], ENT_QUOTES, 'UTF-8'),
        'title' => htmlspecialchars($_GET['title'] ?? 'Speaking Practice', ENT_QUOTES, 'UTF-8'),
        'question' => $_GET['question'],
        'timeLimit' => intval($_GET['time'] ?? 5),
        'wordTarget' => intval($_GET['words'] ?? 0),
        'testType' => htmlspecialchars($_GET['testType'] ?? 'Speaking Test', ENT_QUOTES, 'UTF-8')
    ];
}

// Check if coming from practice test with batch mode
$batchMode = isset($_GET['batch']) && $_GET['batch'] === 'true';
$testType = $_GET['test_type'] ?? 'ielts_speaking';
$taskCount = isset($_GET['tasks']) ? intval($_GET['tasks']) : 1;



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
<title>Speaking Analyzer | EduHub</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<?php include INCLUDES_PATH . '/navbar_styles.php'; ?>

<style>
    .main-wrapper {
        padding: 2rem 1.5rem;
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .page-title {
        color: white;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .page-subtitle {
        color: rgba(255,255,255,0.9);
        margin-bottom: 2rem;
    }
    
    .usage-banner {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .batch-mode-banner {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .analyzer-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        margin-bottom: 2rem;
    }
    
    .mode-selector {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 12px;
    }
    
    .mode-btn {
        flex: 1;
        padding: 1rem;
        border: 2px solid #e5e7eb;
        background: white;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s;
        cursor: pointer;
        text-align: center;
    }
    
    .mode-btn.active {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        border-color: #f59e0b;
    }
    
    .exam-selector {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .exam-btn {
        flex: 1;
        padding: 1rem;
        border: 2px solid #e5e7eb;
        background: white;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s;
        cursor: pointer;
    }
    
    .exam-btn.active {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-color: #667eea;
    }
    
    .task-card {
        background: #f8f9fa;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s;
    }
    
    .task-card.completed {
        border-color: #10b981;
        background: #ecfdf5;
    }
    
    .task-card.recording {
        border-color: #ef4444;
        background: #fef2f2;
    }
    
    .task-number {
        display: inline-block;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 50%;
        text-align: center;
        line-height: 40px;
        font-weight: 700;
        margin-right: 1rem;
    }
    
    .prompt-display {
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        padding: 1.5rem;
        border-radius: 12px;
        border-left: 4px solid #0ea5e9;
        margin-bottom: 2rem;
    }
    
    .prompt-display h5 {
        color: #0c4a6e;
        margin-bottom: 0.5rem;
    }
    
    .prompt-display p {
        color: #075985;
        margin-bottom: 0;
        line-height: 1.6;
    }
    
    .recorder-container {
        text-align: center;
        padding: 3rem 2rem;
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border-radius: 16px;
        margin-bottom: 2rem;
    }
    
    .record-button {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        border: none;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        font-size: 3rem;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 10px 30px rgba(239, 68, 68, 0.4);
        margin-bottom: 1rem;
    }
    
    .record-button:hover {
        transform: scale(1.05);
        box-shadow: 0 15px 40px rgba(239, 68, 68, 0.6);
    }
    
    .record-button.recording {
        background: linear-gradient(135deg, #10b981, #059669);
        animation: pulse 1.5s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .timer {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }
    
    .audio-preview {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        margin-bottom: 1.5rem;
    }
    
    .analyze-btn, .submit-batch-btn {
        width: 100%;
        padding: 1rem;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .submit-batch-btn {
        background: linear-gradient(135deg, #10b981, #34d399);
        font-size: 1.2rem;
        padding: 1.25rem;
    }
    
    .analyze-btn:hover, .submit-batch-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    }
    
    .analyze-btn:disabled, .submit-batch-btn:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        transform: none;
    }
    
    .loading-container {
        display: none;
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
    
    .loading-container.active {
        display: block;
    }
    
    .spinner {
        width: 60px;
        height: 60px;
        border: 5px solid #e5e7eb;
        border-top-color: #667eea;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 1.5rem;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .progress-bar-custom {
        height: 30px;
        border-radius: 8px;
        font-weight: 600;
    }
    
    .results-container {
        display: none;
    }
    
    .results-container.active {
        display: block;
    }
    
    .result-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    
    .score-card {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        text-align: center;
    }
    
    .score-display {
        font-size: 4rem;
        font-weight: 700;
        margin: 1rem 0;
    }
    
    .feedback-content {
        line-height: 1.8;
    }
    
    .feedback-content h5 {
        color: #1e293b;
        margin: 1.5rem 0 1rem;
    }
    
    .feedback-content ul {
        padding-left: 1.5rem;
    }
    
    .feedback-content li {
        margin-bottom: 0.8rem;
        color: #475569;
    }
    
    .transcription-card {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 12px;
        border-left: 4px solid #3b82f6;
    }
    
    .transcription-text {
        color: #1e293b;
        line-height: 1.8;
        font-size: 1.05rem;
    }
    
    .batch-results-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    @media (min-width: 768px) {
        .batch-results-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
</head>
<body>

<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<?php include INCLUDES_PATH . '/navbar.php'; ?>

<main class="main-wrapper">
    <div class="container">
        <h1 class="page-title display-5 mb-2">IELTS & CELPIP Speaking Analyzer</h1>
        <p class="page-subtitle mb-4">Record your response and get instant band score with AI feedback</p>

        <!-- Usage Stats Banner -->
        <div class="usage-banner">
            <div>
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>API Usage Today:</strong> 
                <?php echo $stats['daily_used']; ?> / <?php echo $stats['daily_limit']; ?> analyses used
            </div>
            <div>
                <span class="badge bg-light text-dark" id="quotaRemaining">
                    <?php echo $stats['daily_remaining']; ?> remaining today
                </span>
            </div>
        </div>

		<?php if ($practiceMode): ?>
		<!-- Practice Mode Banner -->
		<div class="practice-mode-banner" style="background: linear-gradient(135deg, #10b981, #34d399);">
			<i class="bi bi-clipboard-check-fill"></i>
			<div class="flex-grow-1">
				<h6 class="mb-0"><strong><?php echo htmlspecialchars($practiceData['testType']); ?></strong></h6>
				<small><?php echo htmlspecialchars($practiceData['title']); ?> • <?php echo $practiceData['timeLimit']; ?> minutes</small>
			</div>
		</div>
		<?php endif; ?>

        <?php if ($batchMode): ?>
        <!-- Batch Mode Banner -->
        <div class="batch-mode-banner">
            <i class="bi bi-layers-fill fs-3"></i>
            <div class="flex-grow-1">
                <h6 class="mb-0"><strong>Batch Mode:</strong> Complete Test Practice</h6>
                <small>Complete all <?php echo $taskCount; ?> tasks, then submit for analysis</small>
            </div>
        </div>
        <?php endif; ?>

        <!-- Analyzer Form -->
        <div class="analyzer-card">
            
            <!-- Mode Selector -->
            <div class="mode-selector">
                <button type="button" class="mode-btn active" data-mode="single">
                    <i class="bi bi-mic-fill me-2"></i>Single Question
                </button>
                <button type="button" class="mode-btn" data-mode="batch">
                    <i class="bi bi-layers-fill me-2"></i>Complete Test (Multiple Questions)
                </button>
            </div>
            
            <!-- Exam Type Selector -->
            <div class="exam-selector">
                <button type="button" class="exam-btn active" data-exam="IELTS">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    IELTS Speaking
                </button>
                <button type="button" class="exam-btn" data-exam="CELPIP">
                    <i class="bi bi-circle me-2"></i>
                    CELPIP Speaking
                </button>
            </div>

            <!-- Single Mode Content -->
            <div id="singleModeContent">
                <!-- Speaking Prompt -->
                <div class="prompt-display">
                    <h5><i class="bi bi-chat-quote-fill me-2"></i>Speaking Task</h5>
                    <p id="promptText">Describe a time when you helped someone. You should say: who you helped, how you helped them, why you helped them, and explain how you felt about helping this person.</p>
                </div>

                <!-- Custom Prompt Input (Optional) -->
                <div class="mb-4">
                    <label class="form-label fw-bold">
                        <i class="bi bi-pencil me-2"></i>Custom Prompt (Optional)
                    </label>
                    <textarea 
                        id="promptInput" 
                        class="form-control"
                        style="min-height: 80px; border-radius: 10px;"
                        placeholder="Leave blank to use the default prompt above, or type your own speaking question..."
                    ></textarea>
                    <small class="text-muted">Tip: IELTS Part 2 prompts work best (2-minute responses)</small>
                </div>

                <!-- Recorder -->
                <div class="recorder-container">
                    <button id="recordButton" class="record-button">
                        <i class="bi bi-mic-fill"></i>
                    </button>
                    <div class="timer" id="timer">0:00</div>
                    <p class="mb-0 text-muted" id="recordStatus">Click the microphone to start recording</p>
                    
                    <!-- Live Transcription Preview -->
                    <div id="liveTranscriptContainer" style="display: none; margin-top: 1.5rem; padding: 1rem; background: white; border-radius: 8px; text-align: left;">
                        <small class="text-muted"><i class="bi bi-mic-fill me-1"></i>Live transcription:</small>
                        <div id="liveTranscript" style="margin-top: 0.5rem; color: #1e293b; min-height: 40px; font-size: 0.95rem;"></div>
                    </div>
                </div>

                <!-- Audio Preview (hidden initially) -->
                <div id="audioPreview" class="audio-preview" style="display: none;">
                    <h6 class="mb-3"><i class="bi bi-file-earmark-music me-2"></i>Your Recording</h6>
                    <audio id="audioPlayer" controls style="width: 100%; margin-bottom: 1rem;"></audio>
                    <button id="reRecordBtn" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-2"></i>Re-record
                    </button>
                </div>

                <!-- Analyze Button -->
                <button id="analyzeBtn" class="analyze-btn" disabled>
                    <i class="bi bi-stars me-2"></i>
                    Analyze My Speaking
                </button>
            </div>

            <!-- Batch Mode Content -->
            <div id="batchModeContent" style="display: none;">
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <strong>Complete Test Mode:</strong> Record responses for multiple questions, then submit all at once for analysis.
                </div>
                
                <div id="batchTasksContainer"></div>
                
                <button id="submitBatchBtn" class="submit-batch-btn" disabled>
                    <i class="bi bi-send-fill me-2"></i>
                    Submit All Tasks for Analysis
                </button>
                <p class="text-center text-muted mt-2" id="batchHint">Complete at least one task to enable submission</p>
            </div>
        </div>

        <!-- Loading Animation -->
        <div class="loading-container" id="loadingContainer">
            <div class="spinner"></div>
            <p class="loading-text fw-bold" style="font-size: 1.2rem;" id="loadingText">Transcribing and analyzing your speech...</p>
            <p class="text-muted">This may take 20-30 seconds</p>
            
            <!-- Progress Bar for Batch Mode -->
            <div id="batchProgress" style="display: none; margin-top: 2rem;">
                <div class="progress progress-bar-custom">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="batchProgressBar" style="width: 0%"></div>
                </div>
                <p class="mt-2 mb-0" id="batchProgressText">Processing task 0 of 0...</p>
            </div>
        </div>

        <!-- Results Section -->
        <div class="results-container" id="resultsContainer">
            <!-- Single Mode Results -->
            <div id="singleResults">
                <!-- Score Card -->
                <div class="result-card score-card">
                    <h3 class="mb-3"><i class="bi bi-trophy-fill me-2"></i>Your Score</h3>
                    <div class="score-display" id="scoreDisplay">-</div>
                    <p class="mb-0 opacity-90" id="scoreLabel">Overall Band Score</p>
                </div>

                <!-- Transcription Card -->
                <div class="result-card">
                    <h4><i class="bi bi-file-text me-2"></i>Transcription</h4>
                    <div class="transcription-card">
                        <p class="transcription-text" id="transcriptionText"></p>
                    </div>
                </div>

                <!-- Feedback Card -->
                <div class="result-card">
                    <h4><i class="bi bi-chat-left-text-fill me-2"></i>AI Examiner Feedback</h4>
                    <div class="feedback-content" id="feedbackContent"></div>
                </div>
            </div>

            <!-- Batch Mode Results -->
            <div id="batchResults" style="display: none;">
                <div class="result-card">
                    <h3 class="mb-4"><i class="bi bi-trophy-fill text-warning me-2"></i>Complete Test Results</h3>
                    <div id="batchSummary" class="mb-4"></div>
                    <div id="batchResultsGrid" class="batch-results-grid"></div>
                </div>
            </div>
        </div>

    </div>
</main>

<?php include INCLUDES_PATH . '/adverts.php'; ?>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Initialize from PHP
const INITIAL_BATCH_MODE = <?php echo $batchMode ? 'true' : 'false'; ?>;
const INITIAL_TASK_COUNT = <?php echo $taskCount; ?>;
const INITIAL_TEST_TYPE = '<?php echo $testType; ?>';

// Practice mode data from PHP
const practiceMode = <?php echo $practiceMode ? 'true' : 'false'; ?>;
const practiceData = <?php echo $practiceMode ? json_encode($practiceData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) : 'null'; ?>;

let selectedExam = 'IELTS';
let currentMode = INITIAL_BATCH_MODE ? 'batch' : 'single';
let mediaRecorder;
let audioChunks = [];
let recordingStartTime;
let timerInterval;
let audioBlob;
let recognition;
let finalTranscript = '';
let interimTranscript = '';

// Batch mode variables
let batchTasks = [];
let currentBatchTask = 0;

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    // Handle practice mode
    if (practiceMode && practiceData) {
        // Pre-fill the prompt with the practice question
        const promptText = document.getElementById('promptText');
        const promptInput = document.getElementById('promptInput');
        
        if (promptText && practiceData.question) {
            promptText.textContent = practiceData.question;
        }
        
        // Make the custom prompt input readonly (so user can't change the question)
        if (promptInput) {
            promptInput.value = practiceData.question;
            promptInput.setAttribute('readonly', true);
            promptInput.style.backgroundColor = '#f3f4f6';
            promptInput.style.cursor = 'not-allowed';
        }
        
        // Show notification
        const notification = document.createElement('div');
        notification.className = 'alert alert-success alert-dismissible fade show position-fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '9999';
        notification.style.minWidth = '300px';
        notification.innerHTML = `
            Practice test loaded: ${practiceData.title}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }
    
    // Original initialization code
    if (INITIAL_BATCH_MODE) {
        switchToBatchMode();
        initializeBatchTasks();
    }
    checkBatchQuota();
});

// Mode Selector
document.querySelectorAll('.mode-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const mode = this.dataset.mode;
        document.querySelectorAll('.mode-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        if (mode === 'single') {
            switchToSingleMode();
        } else {
            switchToBatchMode();
        }
    });
});

function switchToSingleMode() {
    currentMode = 'single';
    document.getElementById('singleModeContent').style.display = 'block';
    document.getElementById('batchModeContent').style.display = 'none';
}

function switchToBatchMode() {
    currentMode = 'batch';
    document.getElementById('singleModeContent').style.display = 'none';
    document.getElementById('batchModeContent').style.display = 'block';
    
    if (batchTasks.length === 0) {
        initializeBatchTasks();
    }
}

// Initialize batch tasks
function initializeBatchTasks() {
    const taskCount = selectedExam === 'CELPIP' ? 8 : 3;
    const container = document.getElementById('batchTasksContainer');
    container.innerHTML = '';
    
    const prompts = selectedExam === 'CELPIP' ? [
        'Give advice to a friend who wants to learn a new language.',
        'Talk about a time when you overcame a challenge.',
        'Describe what you see in this image in detail.',
        'Predict what will happen next in this image.',
        'Compare living in a city versus the countryside.',
        'Call your neighbor to resolve a noise issue.',
        'Do you agree that social media has improved lives?',
        'Describe this unusual situation and why it\'s unusual.'
    ] : [
        'Part 1: Tell me about your hometown.',
        'Part 2: Describe a memorable journey you have made.',
        'Part 3: How has technology changed the way people travel?'
    ];
    
    batchTasks = [];
    
    prompts.forEach((prompt, index) => {
        const taskCard = createTaskCard(index + 1, prompt);
        container.appendChild(taskCard);
        
        batchTasks.push({
            number: index + 1,
            prompt: prompt,
            transcription: '',
            completed: false
        });
    });
}

function createTaskCard(number, prompt) {
    const card = document.createElement('div');
    card.className = 'task-card';
    card.setAttribute('data-task-number', number);
    card.innerHTML = `
        <div class="d-flex align-items-start mb-3">
            <span class="task-number">${number}</span>
            <div class="flex-grow-1">
                <h5 class="mb-2">Task ${number}</h5>
                <p class="mb-0 text-muted">${prompt}</p>
            </div>
        </div>
        <div class="d-flex gap-2 mb-3">
            <button class="btn btn-danger btn-sm record-task-btn" data-task="${number}">
                <i class="bi bi-mic-fill me-1"></i> Record
            </button>
            <button class="btn btn-outline-secondary btn-sm play-task-btn" data-task="${number}" style="display: none;">
                <i class="bi bi-play-fill me-1"></i> Play
            </button>
            <span class="badge bg-success align-self-center task-status" style="display: none;">✓ Completed</span>
        </div>
        <div class="task-transcription" style="display: none; padding: 0.75rem; background: white; border-radius: 8px; font-size: 0.9rem;"></div>
        <audio class="task-audio" style="display: none;"></audio>
    `;
    
    // Record button handler
    const recordBtn = card.querySelector('.record-task-btn');
    recordBtn.addEventListener('click', () => startBatchTaskRecording(number));
    
    // Play button handler
    const playBtn = card.querySelector('.play-task-btn');
    playBtn.addEventListener('click', () => playBatchTaskAudio(number));
    
    return card;
}

// Batch task recording
async function startBatchTaskRecording(taskNumber) {
    currentBatchTask = taskNumber;
    const card = document.querySelector(`[data-task-number="${taskNumber}"]`);
    const recordBtn = card.querySelector('.record-task-btn');
    
    if (!mediaRecorder || mediaRecorder.state === 'inactive') {
        // Start recording
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(stream);
            audioChunks = [];
            finalTranscript = '';
            
            // Initialize speech recognition
            if (!recognition) {
                recognition = initSpeechRecognition();
            }
            if (recognition) {
                recognition.start();
            }
            
            mediaRecorder.ondataavailable = (event) => {
                audioChunks.push(event.data);
            };
            
            mediaRecorder.onstop = () => {
                const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                const audioUrl = URL.createObjectURL(audioBlob);
                
                // Save to task
                const task = batchTasks.find(t => t.number === taskNumber);
                task.transcription = finalTranscript.trim();
                task.audioBlob = audioBlob;
                task.completed = true;
                
                // Update UI
                const audio = card.querySelector('.task-audio');
                audio.src = audioUrl;
                
                const transcriptionDiv = card.querySelector('.task-transcription');
                transcriptionDiv.textContent = task.transcription;
                transcriptionDiv.style.display = 'block';
                
                const playBtn = card.querySelector('.play-task-btn');
                playBtn.style.display = 'inline-block';
                
                const status = card.querySelector('.task-status');
                status.style.display = 'inline-block';
                
                card.classList.add('completed');
                card.classList.remove('recording');
                
                recordBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i> Re-record';
                recordBtn.classList.remove('btn-danger');
                recordBtn.classList.add('btn-outline-secondary');
                
                updateBatchSubmitButton();
                
                // Stop recognition
                if (recognition) {
                    recognition.stop();
                }
            };
            
            mediaRecorder.start();
            card.classList.add('recording');
            recordBtn.innerHTML = '<i class="bi bi-stop-fill me-1"></i> Stop';
            recordBtn.classList.remove('btn-outline-secondary');
            recordBtn.classList.add('btn-danger');
            
        } catch (error) {
            alert('Error accessing microphone: ' + error.message);
        }
    } else {
        // Stop recording
        mediaRecorder.stop();
        mediaRecorder.stream.getTracks().forEach(track => track.stop());
    }
}

function playBatchTaskAudio(taskNumber) {
    const card = document.querySelector(`[data-task-number="${taskNumber}"]`);
    const audio = card.querySelector('.task-audio');
    audio.play();
}

function updateBatchSubmitButton() {
    const completedCount = batchTasks.filter(t => t.completed).length;
    const submitBtn = document.getElementById('submitBatchBtn');
    const hint = document.getElementById('batchHint');
    
    if (completedCount > 0) {
        submitBtn.disabled = false;
        hint.textContent = `Ready to submit ${completedCount} task${completedCount > 1 ? 's' : ''}`;
    } else {
        submitBtn.disabled = true;
        hint.textContent = 'Complete at least one task to enable submission';
    }
}

// Check batch quota
async function checkBatchQuota() {
    if (currentMode !== 'batch') return;
    
    const taskCount = selectedExam === 'CELPIP' ? 8 : 3;
    
    try {
        const response = await fetch('<?php echo ACADEMY_URL; ?>api/api_handler.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'check_test_availability',
                test_type: selectedExam === 'CELPIP' ? 'celpip_speaking' : 'ielts_speaking',
                question_count: taskCount
            })
        });
        
        const data = await response.json();
        const usageBanner = document.querySelector('.usage-banner');
        
        if (!data.can_start) {
            alert(`Insufficient quota: You need ${taskCount} analyses but only have ${data.remaining_quota} remaining. Please complete fewer tasks or wait until tomorrow.`);
            document.querySelector('[data-mode="batch"]').disabled = true;
        }
    } catch (error) {
        console.error('Quota check error:', error);
    }
}

// Submit batch
document.getElementById('submitBatchBtn').addEventListener('click', async () => {
    const completedTasks = batchTasks.filter(t => t.completed);
    
    if (completedTasks.length === 0) {
        alert('Please complete at least one task');
        return;
    }
    
    // Show loading
    document.querySelector('.analyzer-card').style.display = 'none';
    document.getElementById('loadingContainer').classList.add('active');
    document.getElementById('loadingText').textContent = `Analyzing ${completedTasks.length} speaking responses...`;
    document.getElementById('batchProgress').style.display = 'block';
    
    try {
        // Prepare tasks for API
        const tasksForAPI = completedTasks.map(task => ({
            title: `Task ${task.number}`,
            prompt: task.prompt,
            transcription: task.transcription
        }));
        
        const response = await fetch('<?php echo ACADEMY_URL; ?>api/api_handler.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'analyze_speaking_batch',
                exam_type: selectedExam,
                tasks: tasksForAPI
            })
        });
        
        const data = await response.json();
        
        // Hide loading
        document.getElementById('loadingContainer').classList.remove('active');
        
        if (data.success) {
            displayBatchResults(data);
            
            // Update quota
            if (data.remaining_quota !== undefined) {
                document.getElementById('quotaRemaining').textContent = `${data.remaining_quota} remaining today`;
            }
        } else {
            alert('Error: ' + data.error);
            document.querySelector('.analyzer-card').style.display = 'block';
        }
        
    } catch (error) {
        console.error('Batch submission error:', error);
        alert('Error submitting tasks. Please try again.');
        document.querySelector('.analyzer-card').style.display = 'block';
        document.getElementById('loadingContainer').classList.remove('active');
    }
});

function displayBatchResults(data) {
    document.getElementById('resultsContainer').classList.add('active');
    document.getElementById('singleResults').style.display = 'none';
    document.getElementById('batchResults').style.display = 'block';
    
    // Summary
    const summary = document.getElementById('batchSummary');
    summary.innerHTML = `
        <div class="alert alert-success">
            <strong>✓ Analysis Complete!</strong><br>
            Successfully analyzed ${data.batch_info.successful} of ${data.batch_info.total_tasks} tasks<br>
            Remaining quota: ${data.remaining_quota} analyses
        </div>
    `;
    
    // Individual results
    const resultsGrid = document.getElementById('batchResultsGrid');
    resultsGrid.innerHTML = '';
    
    data.results.forEach((result, index) => {
        const resultCard = document.createElement('div');
        resultCard.className = 'card';
        
        if (result.success) {
            const scoreMatch = result.feedback.match(/(?:overall|band score|level)[:\s]*(\d+(?:\.\d+)?)/i);
            const score = scoreMatch ? scoreMatch[1] : '-';
            
            resultCard.innerHTML = `
                <div class="card-header bg-primary text-white">
                    <strong>${result.task_title}</strong>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="display-6 fw-bold text-primary">${score}</div>
                        <small class="text-muted">Score</small>
                    </div>
                    <div class="feedback-content">
                        ${formatFeedback(result.feedback)}
                    </div>
                </div>
            `;
        } else {
            resultCard.innerHTML = `
                <div class="card-header bg-danger text-white">
                    <strong>Task ${result.task_number} - Error</strong>
                </div>
                <div class="card-body">
                    <p class="text-danger mb-0">${result.error}</p>
                </div>
            `;
        }
        
        resultsGrid.appendChild(resultCard);
    });
    
    // Scroll to results
    document.getElementById('resultsContainer').scrollIntoView({ behavior: 'smooth' });
}

// Exam type selector
document.querySelectorAll('.exam-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.exam-btn').forEach(b => {
            b.classList.remove('active');
            b.querySelector('i').className = 'bi bi-circle me-2';
        });
        this.classList.add('active');
        this.querySelector('i').className = 'bi bi-check-circle-fill me-2';
        selectedExam = this.dataset.exam;
        
        // Reinitialize batch tasks if in batch mode
        if (currentMode === 'batch') {
            initializeBatchTasks();
            checkBatchQuota();
        }
    });
});

// Single mode functionality
document.getElementById('promptInput').addEventListener('input', function() {
    const customPrompt = this.value.trim();
    if (customPrompt) {
        document.getElementById('promptText').textContent = customPrompt;
    } else {
        document.getElementById('promptText').textContent = "Describe a time when you helped someone. You should say: who you helped, how you helped them, why you helped them, and explain how you felt about helping this person.";
    }
});

const recordButton = document.getElementById('recordButton');
const timer = document.getElementById('timer');
const recordStatus = document.getElementById('recordStatus');
const audioPreview = document.getElementById('audioPreview');
const audioPlayer = document.getElementById('audioPlayer');
const analyzeBtn = document.getElementById('analyzeBtn');

recordButton.addEventListener('click', toggleRecording);

async function toggleRecording() {
    if (!mediaRecorder || mediaRecorder.state === 'inactive') {
        await startRecording();
    } else {
        stopRecording();
    }
}

function initSpeechRecognition() {
    if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
        console.warn('Speech recognition not supported');
        return null;
    }
    
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    const recog = new SpeechRecognition();
    
    recog.continuous = true;
    recog.interimResults = true;
    recog.lang = 'en-US';
    
    recog.onresult = (event) => {
        interimTranscript = '';
        for (let i = event.resultIndex; i < event.results.length; i++) {
            const transcript = event.results[i][0].transcript;
            if (event.results[i].isFinal) {
                finalTranscript += transcript + ' ';
            } else {
                interimTranscript += transcript;
            }
        }
        
        // Update live transcript if visible
        const liveContainer = document.getElementById('liveTranscriptContainer');
        const liveTranscript = document.getElementById('liveTranscript');
        if (liveContainer.style.display !== 'none') {
            liveTranscript.textContent = finalTranscript + interimTranscript;
        }
    };
    
    recog.onerror = (event) => {
        console.error('Speech recognition error:', event.error);
    };
    
    return recog;
}

async function startRecording() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        mediaRecorder = new MediaRecorder(stream);
        audioChunks = [];
        finalTranscript = '';
        interimTranscript = '';

        // Show live transcript container
        document.getElementById('liveTranscriptContainer').style.display = 'block';
        document.getElementById('liveTranscript').textContent = 'Listening...';

        // Initialize and start speech recognition
        if (!recognition) {
            recognition = initSpeechRecognition();
        }
        if (recognition) {
            recognition.start();
        }

        mediaRecorder.ondataavailable = (event) => {
            audioChunks.push(event.data);
        };

        mediaRecorder.onstop = () => {
            audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
            const audioUrl = URL.createObjectURL(audioBlob);
            audioPlayer.src = audioUrl;
            audioPreview.style.display = 'block';
            analyzeBtn.disabled = false;
            
            // Stop speech recognition
            if (recognition) {
                recognition.stop();
            }
        };

        mediaRecorder.start();
        recordButton.classList.add('recording');
        recordButton.innerHTML = '<i class="bi bi-stop-fill"></i>';
        recordStatus.textContent = 'Recording... Click to stop';
        
        recordingStartTime = Date.now();
        startTimer();

    } catch (error) {
        alert('Error accessing microphone: ' + error.message);
    }
}

function stopRecording() {
    mediaRecorder.stop();
    mediaRecorder.stream.getTracks().forEach(track => track.stop());
    
    recordButton.classList.remove('recording');
    recordButton.innerHTML = '<i class="bi bi-mic-fill"></i>';
    recordStatus.textContent = 'Recording complete! Listen to your audio below';
    
    clearInterval(timerInterval);
}

function startTimer() {
    timerInterval = setInterval(() => {
        const elapsed = Math.floor((Date.now() - recordingStartTime) / 1000);
        const minutes = Math.floor(elapsed / 60);
        const seconds = elapsed % 60;
        timer.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
    }, 1000);
}

document.getElementById('reRecordBtn').addEventListener('click', () => {
    audioPreview.style.display = 'none';
    audioBlob = null;
    analyzeBtn.disabled = true;
    timer.textContent = '0:00';
    recordStatus.textContent = 'Click the microphone to start recording';
    document.getElementById('liveTranscriptContainer').style.display = 'none';
    document.getElementById('liveTranscript').textContent = '';
});

analyzeBtn.addEventListener('click', analyzeAudio);

async function analyzeAudio() {
    if (!audioBlob) {
        alert('Please record your response first.');
        return;
    }

    // Show loading
    document.getElementById('loadingContainer').classList.add('active');
    document.getElementById('resultsContainer').classList.remove('active');
    document.querySelector('.analyzer-card').style.display = 'none';
    document.getElementById('batchProgress').style.display = 'none';

    try {
        const prompt = document.getElementById('promptInput').value.trim() || 
                      document.getElementById('promptText').textContent;

        const transcription = finalTranscript.trim();
        
        if (!transcription) {
            throw new Error('No transcription available. Please try recording again and speak clearly.');
        }

        const analysisResponse = await fetch('<?php echo ACADEMY_URL; ?>api/api_handler.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'analyze_speaking',
                prompt: prompt,
                transcription: transcription,
                exam_type: selectedExam
            })
        });

        const analysisData = await analysisResponse.json();

        if (!analysisResponse.ok) {
            throw new Error(analysisData.error || 'Analysis failed');
        }

        displayResults(transcription, analysisData.feedback, selectedExam, analysisData.remaining);

    } catch (error) {
        alert('Error: ' + error.message);
        document.querySelector('.analyzer-card').style.display = 'block';
    } finally {
        document.getElementById('loadingContainer').classList.remove('active');
    }
}

function displayResults(transcription, feedback, exam, remaining) {
    const scoreMatch = feedback.match(/(?:overall|band score|level)[:\s]*(\d+(?:\.\d+)?)/i);
    const score = scoreMatch ? scoreMatch[1] : '-';
    
    document.getElementById('scoreDisplay').textContent = score;
    document.getElementById('scoreLabel').textContent = exam === 'IELTS' ? 'Overall Band Score (out of 9.0)' : 'Overall Score (out of 12)';
    
    document.getElementById('transcriptionText').textContent = transcription;
    
    const formattedFeedback = formatFeedback(feedback);
    document.getElementById('feedbackContent').innerHTML = formattedFeedback;

    if (remaining !== undefined) {
        document.getElementById('quotaRemaining').textContent = `${remaining} remaining today`;
    }

    document.getElementById('resultsContainer').classList.add('active');
    document.getElementById('singleResults').style.display = 'block';
    document.getElementById('batchResults').style.display = 'none';
    document.getElementById('resultsContainer').scrollIntoView({ behavior: 'smooth' });
}

function formatFeedback(feedback) {
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

// Mobile menu toggle
const menuToggle = document.getElementById('menuToggle');
const sidebar = document.querySelector('.sidebar');
const overlay = document.getElementById('mobileOverlay');

if (menuToggle && sidebar && overlay) {
    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    });
    
    overlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    });
}
</script>

</body>
</html>