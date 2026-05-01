<?php
/**
 * Mock Tests Configuration File
 * Uses $db from bootstrap.php
 */

// Get single mock exam by code
function getMockExam($mockCode) {
    global $db;

    $stmt = $db->prepare("
        SELECT * FROM mock_exams 
        WHERE code = ? AND is_active = 1 
        LIMIT 1
    ");
    $stmt->execute([$mockCode]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get all sections for a mock exam in correct order
function getMockExamSections($mockCode) {
    global $db;

    $stmt = $db->prepare("
        SELECT 
            ms.*,
            t.title as section_title,
            t.duration_minutes,
            t.test_type,
            t.task_number,
            t.instructions,
            t.media_file,
            t.total_questions
        FROM mock_exam_sections ms
        JOIN tests t ON ms.test_code = t.code
        WHERE ms.mock_code = ?
        ORDER BY ms.section_order ASC
    ");
    $stmt->execute([$mockCode]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get single test by code (improved version for mock sections)
function getTest($code) {
    global $db;

    $stmt = $db->prepare("
        SELECT * FROM tests
        WHERE code = ? AND is_active = 1
        LIMIT 1
    ");
    $stmt->execute([$code]);
    $test = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$test) return null;

    return [
        'code'              => $test['code'],
        'type'              => str_replace('_', ' ', $test['test_type']),
        'task'              => $test['task_number'],
        'title'             => $test['title'],
        'description'       => $test['description'],
        'instructions'      => $test['instructions'],
        'time_limit'        => $test['duration_minutes'],
        'word_target'       => $test['word_target'] ?? null,
        'word_max'          => $test['word_max'] ?? null,
        'media_file'        => $test['media_file'],
        'category'          => $test['category'],
        'test_type'         => $test['test_type'],
        'duration_minutes'  => $test['duration_minutes'],
        'total_questions'   => $test['total_questions'],
        'max_attempts'      => $test['max_attempts'] ?? 999,
        'cooldown_days'     => $test['cooldown_days'] ?? 90,
        'is_mock_section'   => $test['is_mock_section'] ?? 0
    ];
}

// Updated getTestsByType - excludes mock sections from normal practice list
function getTestsByType($type) {
    global $db;

    $dbType = str_replace(' ', '_', $type);

    $stmt = $db->prepare("
        SELECT * FROM tests
        WHERE test_type LIKE ? 
          AND is_active = 1 
          AND (is_mock_section = 0 OR is_mock_section IS NULL)
        ORDER BY code
    ");
    $stmt->execute(["%$dbType%"]);
    $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return array_map(function($test) {
        return [
            'code'         => $test['code'],
            'type'         => str_replace('_', ' ', $test['test_type']),
            'task'         => $test['task_number'],
            'title'        => $test['title'],
            'description'  => $test['description'],
            'instructions' => $test['instructions'],
            'time_limit'   => $test['duration_minutes'],
            'word_target'  => $test['word_target'],
            'word_max'     => $test['word_max'],
            'media_file'   => $test['media_file'],
            'category'     => $test['category'],
            'test_type'    => $test['test_type']
        ];
    }, $tests);
}

// Helper to get icon for section type
function getSectionIcon($sectionType) {
    $type = strtolower($sectionType);
    if (strpos($type, 'listening') !== false) return 'bi-headphones';
    if (strpos($type, 'reading') !== false) return 'bi-book';
    if (strpos($type, 'writing') !== false) return 'bi-pencil-square';
    if (strpos($type, 'speaking') !== false) return 'bi-mic';
    return 'bi-file-earmark-text';
}

// Helper to get nice section name
function getSectionDisplayName($sectionType) {
    $map = [
        'Listening'      => 'Listening',
        'Reading'        => 'Reading',
        'Writing_Task1'  => 'Writing Task 1',
        'Writing_Task2'  => 'Writing Task 2',
        'Speaking'       => 'Speaking'
    ];
    return $map[$sectionType] ?? ucfirst(str_replace('_', ' ', $sectionType));
}

// Map section_type to its PHP file
function getSectionFile($sectionType) {
    $map = [
        'Reading'       => 'reading_template.php',
        'Writing_Task1' => 'writing_template.php',
        'Writing_Task2' => 'writing_template.php',
        'Speaking'      => 'speaking_template.php',
    ];
    return $map[$sectionType] ?? null;
}

// Get the URL for a specific section (by section_type + test_code)
// Listening routes to the mock-specific file named after the mock code (e.g. ielts_gt_mock01.php)
function getSectionUrl($sectionType, $testCode, $mockCode) {
    if ($sectionType === 'Listening') {
        $file = strtolower($mockCode) . '.php';
        return $file . '?code=' . urlencode($mockCode);
    }
    $file = getSectionFile($sectionType);
    if (!$file) return null;
    return $file . '?code=' . urlencode($testCode);
}

// Get the URL for the first section of a mock
function getFirstSectionUrl($sections, $mockCode) {
    if (empty($sections)) return 'index.php';
    $first = $sections[0];
    return getSectionUrl($first['section_type'], $first['test_code'], $mockCode) ?? 'index.php';
}

// Get the URL for the next section after the current one (by current section_order)
function getNextSectionUrl($sections, $currentOrder, $mockCode) {
    foreach ($sections as $sec) {
        if ((int)$sec['section_order'] === $currentOrder + 1) {
            return getSectionUrl($sec['section_type'], $sec['test_code'], $mockCode);
        }
    }
    return null; // no next section = end of mock
}