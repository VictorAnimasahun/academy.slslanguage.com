<?php
// POST /api/v1/exercise_thin_to_thick.php {thin_sentence, expansion, level} -- the mobile "Thin to
// Thick" exercise (prototype's thin_to_thick.php, which calls api/api_handler.php's
// evaluate_thin_to_thick action over the web session). That handler is session-gated and lives
// inside a big switch, so this mirrors its body under token auth instead of trying to make a
// session-based endpoint reachable from the app -- same prompt, same AI provider, same rate-limit
// feature key, so grading behaves identically either way.
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
require_once CONFIG_PATH . '/api_keys.php';
require_once INCLUDES_PATH . '/rate_limiter.php';
require_once INCLUDES_PATH . '/ai_client.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$student = requireMobileAuth($db);
$input = json_decode(file_get_contents('php://input'), true) ?? [];

if (!isset($input['thin_sentence']) || !isset($input['expansion']) || !isset($input['level'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields: thin_sentence, expansion, level']);
    exit();
}

$rateLimiter = new RateLimiter($db);
$limitCheck = $rateLimiter->checkLimit($student['id'], 'speaking_analysis');
if (!$limitCheck['allowed']) {
    http_response_code(429);
    echo json_encode(['error' => $limitCheck['message'], 'reset_time' => $limitCheck['reset_time']]);
    exit();
}

$thinSentence = trim((string) $input['thin_sentence']);
$expansion = trim((string) $input['expansion']);
$level = (int) $input['level'];

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

// Occasionally the model returns success=true with its JSON cut off mid-string -- not flagged by
// callGemini()'s own finishReason check (that only catches an explicit MAX_TOKENS finish, not a
// short/truncated-but-"complete" response). One retry clears this in practice; seen directly while
// testing this endpoint (3 real calls, same prompt: 1 truncated, 2 complete).
$evaluation = null;
for ($attempt = 0; $attempt < 2 && !$evaluation; $attempt++) {
    $response = callAI($evaluationPrompt, ANALYSIS_API);
    if (!$response['success']) {
        continue;
    }
    $content = trim(preg_replace('/```json\s*|\s*```/', '', $response['content']));
    $evaluation = json_decode($content, true);
    if (!$evaluation || !isset($evaluation['score']) || !isset($evaluation['feedback'])) {
        $evaluation = null;
    }
}

if (!$evaluation) {
    http_response_code(502);
    echo json_encode(['error' => "Couldn't grade that. Please try again."]);
    exit();
}

echo json_encode([
    'score' => (int) $evaluation['score'],
    'feedback' => $evaluation['feedback'],
    'passed' => (bool) ($evaluation['passed'] ?? ($evaluation['score'] >= 70)),
]);
