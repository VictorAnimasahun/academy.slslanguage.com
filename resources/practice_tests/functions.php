<?php
/**
 * Shared Functions for All Tests
 * Include this in every test file
 */

/**
 * Save test attempt to database
 */
function saveTestAttempt($testCode, $userId, $responseText, $wordCount, $timeSpent) {
    $pdo = getDB();
    try {
        $stmt = $pdo->prepare("
            INSERT INTO test_attempts 
            (test_code, user_id, response_text, word_count, time_spent, submitted_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$testCode, $userId, $responseText, $wordCount, $timeSpent]);
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Error saving test attempt: " . $e->getMessage());
        return false;
    }
}

/**
 * Get user's previous attempts for a test
 */
function getUserAttempts($testCode, $userId, $limit = 5) {
    $pdo = getDB();
    try {
        $stmt = $pdo->prepare("
            SELECT attempt_id, submitted_at, word_count, time_spent, score
            FROM test_attempts
            WHERE test_code = ? AND user_id = ?
            ORDER BY submitted_at DESC
            LIMIT ?
        ");
        $stmt->execute([$testCode, $userId, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting user attempts: " . $e->getMessage());
        return [];
    }
}

/**
 * Format time in MM:SS
 */
function formatTime($seconds) {
    $minutes = floor($seconds / 60);
    $secs = $seconds % 60;
    return sprintf("%02d:%02d", $minutes, $secs);
}

/**
 * Count words in text
 */
function countWords($text) {
    $text = trim($text);
    if (empty($text)) return 0;
    return count(preg_split('/\s+/', $text));
}

/**
 * Generate breadcrumb HTML
 */
function getBreadcrumb($testCode) {
    $test = getTest($testCode);
    return '
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../resources_home.php">Resources</a></li>
            <li class="breadcrumb-item"><a href="index.php">Practice Tests</a></li>
            <li class="breadcrumb-item active">' . htmlspecialchars($test['title']) . '</li>
        </ol>
    </nav>';
}

/**
 * Get badge color based on test type
 */
function getBadgeColor($type) {
    if (strpos($type, 'Writing') !== false) return 'primary';
    if (strpos($type, 'Speaking') !== false) return 'success';
    if (strpos($type, 'Reading') !== false) return 'info';
    if (strpos($type, 'Listening') !== false) return 'warning';
    return 'secondary';
}

/**
 * Get icon based on test type
 */
function getTestIcon($type) {
    if (strpos($type, 'Writing') !== false) return 'pencil-square';
    if (strpos($type, 'Speaking') !== false) return 'mic';
    if (strpos($type, 'Reading') !== false) return 'book';
    if (strpos($type, 'Listening') !== false) return 'headphones';
    return 'clipboard';
}

/**
 * Redirect to analyzer with test data
 */
function redirectToAnalyzer($testCode, $type, $data = []) {
    $test = getTest($testCode);
    
    $params = [
        'test_code' => $testCode,
        'type' => $type,
        'title' => $test['title'],
        'time' => $test['time_limit'],
        'words' => $test['word_target'],
        'testType' => $test['type']
    ];
    
    // Merge additional data
    $params = array_merge($params, $data);
    
    // Determine analyzer
    $analyzer = 'essay_analyzer.php';
    if (strpos($test['type'], 'Speaking') !== false) {
        $analyzer = 'audio_analyzer.php';
    }
    
    $url = '../' . $analyzer . '?' . http_build_query($params);
    header("Location: $url");
    exit();
}

/**
 * Save audio file for speaking tests
 */
function saveAudioFile($testCode, $userId, $audioData) {
    $uploadDir = 'media/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $filename = $testCode . '_' . $userId . '_' . time() . '.wav';
    $filepath = $uploadDir . $filename;
    
    if (file_put_contents($filepath, $audioData)) {
        return $filename;
    }
    
    return false;
}

/**
 * Load correct answers from the database for a given test code.
 *
 * Returns an array keyed by question_number where each value is a list of
 * accepted lowercase strings. Text/fill answers come from
 * question_correct_answers; MCQ correct answers come from question_options
 * (is_correct = 1).
 *
 * If the test hasn't been migrated yet the function returns [] and logs an
 * error — callers should detect this and show a graceful message.
 */
function loadTestAnswers(PDO $db, string $testCode): array {
    $answers = [];

    try {
        $stmt = $db->prepare("SELECT id FROM tests WHERE code = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$testCode]);
        $testId = $stmt->fetchColumn();
        if (!$testId) {
            error_log("loadTestAnswers: no active test found for code '$testCode' — run the seed migration.");
            return [];
        }

        // Text / fill / matching / sentence-completion answers
        $stmt = $db->prepare("
            SELECT q.question_number, qca.answer_text
            FROM   questions q
            JOIN   question_correct_answers qca ON qca.question_id = q.id
            WHERE  q.test_id = ?
            ORDER  BY q.question_number ASC, qca.is_alternative ASC
        ");
        $stmt->execute([$testId]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $answers[(int)$row['question_number']][] = strtolower($row['answer_text']);
        }

        // MCQ answers — the correct option label stored in question_options
        $stmt = $db->prepare("
            SELECT q.question_number, LOWER(qo.option_label) AS answer
            FROM   questions q
            JOIN   question_options qo ON qo.question_id = q.id AND qo.is_correct = 1
            WHERE  q.test_id = ?
            ORDER  BY q.question_number ASC
        ");
        $stmt->execute([$testId]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $answers[(int)$row['question_number']] = [strtolower($row['answer'])];
        }
    } catch (PDOException $e) {
        error_log("loadTestAnswers DB error for '$testCode': " . $e->getMessage());
    }

    return $answers;
}