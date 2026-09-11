<?php
// /academy/api/api_handler.php
require_once dirname(__DIR__) . '/bootstrap.php';
require_once CONFIG_PATH . '/api_keys.php';
require_once INCLUDES_PATH . '/rate_limiter.php';
require_once INCLUDES_PATH . '/ai_client.php';

// Set JSON response header
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized. Please log in.']);
    exit();
}

$userId = $_SESSION['user_id'];

// Initialize rate limiter
$rateLimiter = new RateLimiter($db);

// Get request data
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['action'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request. Action required.']);
    exit();
}

$action = $input['action'];

// Handle different actions
switch ($action) {
    case 'analyze_essay':
        handleEssayAnalysis($input, $userId, $rateLimiter);
        break;
    
    case 'transcribe_audio':
        handleAudioTranscription($input, $userId, $rateLimiter);
        break;
    
    case 'analyze_speaking':
        handleSpeakingAnalysis($input, $userId, $rateLimiter);
        break;
    
    // NEW: Batch speaking analysis (e.g., CELPIP 8 tasks, IELTS Part 1 multiple questions)
    case 'analyze_speaking_batch':
        handleSpeakingBatchAnalysis($input, $userId, $rateLimiter);
        break;
    
    // NEW: Check if user can start a multi-part test
    case 'check_test_availability':
        handleTestAvailabilityCheck($input, $userId, $rateLimiter);
        break;
    
    // NEW: Get detailed usage statistics
    case 'get_usage_stats':
        handleUsageStats($userId, $rateLimiter);
        break;
    
    case 'check_rate_limit':
        handleRateLimitCheck($userId, $rateLimiter);
        break;

	case 'evaluate_thin_to_thick':
		handleThinToThickEvaluation($input, $userId, $rateLimiter);
		break;
    
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action: ' . $action]);
        break;
}

/**
 * Handle Essay Analysis
 */
function handleEssayAnalysis($input, $userId, $rateLimiter) {
    // Check rate limit
    $limitCheck = $rateLimiter->checkLimit($userId, 'essay_analysis');
    if (!$limitCheck['allowed']) {
        http_response_code(429);
        echo json_encode([
            'error' => $limitCheck['message'],
            'reset_time' => $limitCheck['reset_time']
        ]);
        return;
    }
    
    // Validate input
    if (!isset($input['question']) || !isset($input['essay']) || !isset($input['exam_type'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields: question, essay, exam_type']);
        return;
    }
    
    $question = trim($input['question']);
    $essay = trim($input['essay']);
    $examType = $input['exam_type'];
    
    $taskType = $input['task_type'] ?? 'writing_task2';
    $wordCount = isset($input['word_count']) ? (int)$input['word_count'] : null;

    $rubric = essayRubric($examType, $taskType, $wordCount);

    $prompt = "Question: $question\n\n$rubric\n\nResponse:\n$essay";
    
    // Call AI API
    $response = callAI($prompt, ANALYSIS_API);
    
    if ($response['success']) {
        // Log the request
        $rateLimiter->logRequest($userId, 'essay_analysis', 'analyze');
        
        echo json_encode([
            'success' => true,
            'feedback' => $response['content'],
            'remaining' => $limitCheck['remaining']
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => $response['error']]);
    }
}

/**
 * Handle Audio Transcription (Optional - only if using paid services)
 */
function handleAudioTranscription($input, $userId, $rateLimiter) {
    // Check if OpenAI key is configured
    if (!defined('OPENAI_API_KEY') || empty(OPENAI_API_KEY) || OPENAI_API_KEY === 'sk-YOUR_OPENAI_KEY_HERE') {
        http_response_code(400);
        echo json_encode([
            'error' => 'Audio transcription via API is not configured. Please use the built-in browser speech recognition instead.',
            'suggestion' => 'The app uses free Web Speech API for transcription.'
        ]);
        return;
    }
    
    // Check rate limit
    $limitCheck = $rateLimiter->checkLimit($userId, 'audio_transcription');
    if (!$limitCheck['allowed']) {
        http_response_code(429);
        echo json_encode([
            'error' => $limitCheck['message'],
            'reset_time' => $limitCheck['reset_time']
        ]);
        return;
    }
    
    if (!isset($input['audio_base64'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing audio data']);
        return;
    }
    
    // Decode base64 audio
    $audioData = base64_decode($input['audio_base64']);
    
    // Create temporary file
    $tempFile = sys_get_temp_dir() . '/audio_' . uniqid() . '.webm';
    file_put_contents($tempFile, $audioData);
    
    // Transcribe with OpenAI Whisper
    $transcription = transcribeWithWhisper($tempFile);
    
    // Clean up temp file
    unlink($tempFile);
    
    if ($transcription['success']) {
        // Log the request
        $rateLimiter->logRequest($userId, 'audio_transcription', 'transcribe');
        
        echo json_encode([
            'success' => true,
            'transcription' => $transcription['text'],
            'remaining' => $limitCheck['remaining']
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => $transcription['error']]);
    }
}

/**
 * Handle Speaking Analysis
 */
function handleSpeakingAnalysis($input, $userId, $rateLimiter) {
    // Check rate limit
    $limitCheck = $rateLimiter->checkLimit($userId, 'speaking_analysis');
    if (!$limitCheck['allowed']) {
        http_response_code(429);
        echo json_encode([
            'error' => $limitCheck['message'],
            'reset_time' => $limitCheck['reset_time']
        ]);
        return;
    }
    
    if (!isset($input['prompt']) || !isset($input['transcription']) || !isset($input['exam_type'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }
    
    $prompt = trim($input['prompt']);
    $transcription = trim($input['transcription']);
    $examType = $input['exam_type'];

    $rubric = speakingRubric($examType);

    $analysisPrompt = "Speaking Task: $prompt\n\n$rubric\n\nCandidate's Response (Transcribed):\n$transcription";

    $response = callAI($analysisPrompt, ANALYSIS_API);

    if ($response['success']) {
        $rateLimiter->logRequest($userId, 'speaking_analysis', 'analyze');
        
        echo json_encode([
            'success' => true,
            'feedback' => $response['content'],
            'remaining' => $limitCheck['remaining']
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => $response['error']]);
    }
}

/**
 * NEW: Handle Batch Speaking Analysis
 * For tests with multiple tasks (e.g., CELPIP 8 tasks, IELTS Part 1 multiple questions)
 */
function handleSpeakingBatchAnalysis($input, $userId, $rateLimiter) {
    // Validate input
    if (!isset($input['tasks']) || !is_array($input['tasks']) || empty($input['tasks'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing or invalid tasks array']);
        return;
    }
    
    if (!isset($input['exam_type'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing exam_type']);
        return;
    }
    
    $tasks = $input['tasks'];
    $taskCount = count($tasks);
    $examType = $input['exam_type'];

    // Check batch limit BEFORE processing
    $batchCheck = $rateLimiter->checkBatchLimit($userId, 'speaking_analysis', $taskCount);

    if (!$batchCheck['allowed']) {
        http_response_code(429);
        echo json_encode([
            'error' => $batchCheck['message'],
            'remaining' => $batchCheck['remaining'],
            'requested' => $taskCount,
            'current_usage' => $batchCheck['current_usage'] ?? 0
        ]);
        return;
    }

    // Process all tasks
    $results = [];
    $batchId = uniqid('speaking_batch_', true);
    $successCount = 0;

    $rubric = speakingRubric($examType);

    foreach ($tasks as $index => $task) {
        if (!isset($task['prompt']) || !isset($task['transcription'])) {
            $results[] = [
                'task_number' => $index + 1,
                'success' => false,
                'error' => 'Missing prompt or transcription for this task'
            ];
            continue;
        }
        
        $prompt = trim($task['prompt']);
        $transcription = trim($task['transcription']);
        $taskTitle = $task['title'] ?? "Task " . ($index + 1);
        
        $analysisPrompt = "Speaking Task: $prompt\n\n$rubric\n\nCandidate's Response (Transcribed):\n$transcription";
        
        $response = callAI($analysisPrompt, ANALYSIS_API);
        
        if ($response['success']) {
            $results[] = [
                'task_number' => $index + 1,
                'task_title' => $taskTitle,
                'success' => true,
                'feedback' => $response['content']
            ];
            $successCount++;
        } else {
            $results[] = [
                'task_number' => $index + 1,
                'task_title' => $taskTitle,
                'success' => false,
                'error' => $response['error']
            ];
        }
    }
    
    // Log batch request (only log successful analyses)
    if ($successCount > 0) {
        $logResult = $rateLimiter->logBatchRequest(
            $userId, 
            'speaking_analysis', 
            'speaking_batch', 
            $successCount
        );
    }
    
    echo json_encode([
        'success' => true,
        'results' => $results,
        'batch_info' => [
            'batch_id' => $batchId,
            'total_tasks' => $taskCount,
            'successful' => $successCount,
            'failed' => $taskCount - $successCount
        ],
        'remaining_quota' => $batchCheck['remaining'],
        'quota_used' => $successCount
    ]);
}

/**
 * NEW: Check if user can start a multi-part test
 * Call this BEFORE starting a test to show quota availability
 */
function handleTestAvailabilityCheck($input, $userId, $rateLimiter) {
    if (!isset($input['test_type']) || !isset($input['question_count'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing test_type or question_count']);
        return;
    }
    
    $testType = $input['test_type'];
    $questionCount = (int)$input['question_count'];
    
    // Map test types to features
    $featureMap = [
        'ielts_speaking' => 'speaking_analysis',
        'celpip_speaking' => 'speaking_analysis',
        'ielts_writing' => 'essay_analysis',
        'celpip_writing' => 'essay_analysis'
    ];
    
    $feature = $featureMap[$testType] ?? 'general';
    
    // Check if user has enough quota for the entire test
    $batchCheck = $rateLimiter->checkBatchLimit($userId, $feature, $questionCount);
    
    echo json_encode([
        'can_start' => $batchCheck['allowed'],
        'message' => $batchCheck['message'] ?? 'Test can be started',
        'remaining_quota' => $batchCheck['remaining'] ?? 0,
        'required_quota' => $questionCount,
        'feature' => $feature
    ]);
}

/**
 * Handle Thin to Thick Writing Evaluation
 */
function handleThinToThickEvaluation($input, $userId, $rateLimiter) {
    // Check rate limit (using speaking_analysis feature for now, or create new feature)
    $limitCheck = $rateLimiter->checkLimit($userId, 'speaking_analysis');
    if (!$limitCheck['allowed']) {
        http_response_code(429);
        echo json_encode([
            'error' => $limitCheck['message'],
            'reset_time' => $limitCheck['reset_time']
        ]);
        return;
    }
    
    // Validate input
    if (!isset($input['thin_sentence']) || !isset($input['expansion']) || !isset($input['level'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields: thin_sentence, expansion, level']);
        return;
    }
    
    $thinSentence = trim($input['thin_sentence']);
    $expansion = trim($input['expansion']);
    $level = (int)$input['level'];
    
    // Create evaluation prompt
    $evaluationPrompt = "You are evaluating a student's writing exercise called 'Thin to Thick'.

THIN SENTENCE: \"{$thinSentence}\"
STUDENT'S EXPANSION: \"{$expansion}\"
LEVEL: {$level} (higher levels require more depth)

Evaluate if the student successfully expanded the thin sentence into a thick, detailed version. Consider:
1. Did they add specific details, examples, or descriptions?
2. Did they explain WHY or HOW (reasoning/causation)?
3. Is the expansion logical and coherent?
4. Does it show depth appropriate for Level {$level}?

Respond ONLY with valid JSON in this exact format (no markdown, no backticks):
{\"score\": <number 0-100>, \"feedback\": \"<specific feedback in 1-2 sentences>\", \"passed\": <true or false>}

A passing score is 70+. Be encouraging but honest.";
    
    // Call AI API (using your existing function)
    $response = callAI($evaluationPrompt, ANALYSIS_API);
    
    if ($response['success']) {
        // Parse the AI response to extract JSON
        $content = $response['content'];
        
        // Remove markdown code blocks if present
        $content = preg_replace('/```json\s*|\s*```/', '', $content);
        $content = trim($content);
        
        // Try to parse JSON
        $evaluation = json_decode($content, true);
        
        if (!$evaluation || !isset($evaluation['score']) || !isset($evaluation['feedback'])) {
            http_response_code(500);
            echo json_encode(['error' => 'Could not parse AI evaluation response']);
            return;
        }
        
        // Log the request
        $rateLimiter->logRequest($userId, 'speaking_analysis', 'thin_to_thick');
        
        echo json_encode([
            'success' => true,
            'score' => (int)$evaluation['score'],
            'passed' => $evaluation['passed'],
            'feedback' => $evaluation['feedback'],
            'remaining' => $limitCheck['remaining']
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => $response['error']]);
    }
}

/**
 * NEW: Get detailed usage statistics
 * Shows overall and feature-specific usage
 */
function handleUsageStats($userId, $rateLimiter) {
    // Get overall stats
    $overallStats = $rateLimiter->getUserStats($userId);
    
    // Get feature-specific stats
    $essayStats = $rateLimiter->getFeatureStats($userId, 'essay_analysis');
    $speakingStats = $rateLimiter->getFeatureStats($userId, 'speaking_analysis');
    
    echo json_encode([
        'success' => true,
        'overall' => $overallStats,
        'by_feature' => [
            'essay_analysis' => $essayStats,
            'speaking_analysis' => $speakingStats
        ]
    ]);
}

/**
 * Check Rate Limit Status
 */
function handleRateLimitCheck($userId, $rateLimiter) {
    $stats = $rateLimiter->getUserStats($userId);
    echo json_encode([
        'success' => true,
        'stats' => $stats
    ]);
}

// callAI(), callClaude(), callGemini() and transcribeWithWhisper() now live in
// includes/ai_client.php so the tutor-facing analyzer in sls-admin can reuse them.