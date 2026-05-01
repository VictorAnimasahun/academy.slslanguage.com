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
 * Load reading/listening questions and answers (for future use)
 */
function getTestQuestions($testCode) {
    $pdo = getDB();
    try {
        $stmt = $pdo->prepare("
            SELECT question_number, question_text, question_type, 
                   correct_answer, points
            FROM test_questions
            WHERE test_code = ?
            ORDER BY question_number
        ");
        $stmt->execute([$testCode]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error loading questions: " . $e->getMessage());
        return [];
    }
}

/**
 * Check answer for reading/listening tests
 */
function checkAnswer($testCode, $questionNum, $userAnswer) {
    $pdo = getDB();
    try {
        $stmt = $pdo->prepare("
            SELECT correct_answer, points
            FROM test_questions
            WHERE test_code = ? AND question_number = ?
        ");
        $stmt->execute([$testCode, $questionNum]);
        $question = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$question) return ['correct' => false, 'points' => 0];
        
        $correctAnswers = json_decode($question['correct_answer'], true);
        $isCorrect = in_array(trim($userAnswer), $correctAnswers);
        
        return [
            'correct' => $isCorrect,
            'points' => $isCorrect ? $question['points'] : 0
        ];
    } catch (PDOException $e) {
        error_log("Error checking answer: " . $e->getMessage());
        return ['correct' => false, 'points' => 0];
    }
}