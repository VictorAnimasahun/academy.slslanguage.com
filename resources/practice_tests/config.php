<?php
/**
 * Database-driven test configuration
 * Uses $db from bootstrap.php
 */

// Get single test by code
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
   
    // Map database fields to template-friendly names
    return [
        'code'          => $test['code'],
        'type'          => str_replace('_', ' ', $test['test_type']),
        'task'          => $test['task_number'],
        'title'         => $test['title'],
        'description'   => $test['description'],
        'instructions'  => $test['instructions'],
        'time_limit'    => $test['duration_minutes'],
        'word_target'   => $test['word_target'],
        'word_max'      => $test['word_max'],
        'media_file'    => $test['media_file'],
        'category'      => $test['category'],
        // Add these for safety (in case code uses them)
        'test_type'     => $test['test_type'],
        'duration_minutes' => $test['duration_minutes'],
        'max_attempts'  => $test['max_attempts'] ?? 999,
        'cooldown_days' => $test['cooldown_days'] ?? 90
    ];
}

// Get tests by type
function getTestsByType($type) {
    global $db;
   
    $dbType = str_replace(' ', '_', $type);
   
    $stmt = $db->prepare("
        SELECT * FROM tests
        WHERE test_type LIKE ? AND is_active = 1
        ORDER BY code
    ");
    $stmt->execute(["%$dbType%"]);
    $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
   
    return array_map(function($test) {
        return [
            'code'          => $test['code'],
            'type'          => str_replace('_', ' ', $test['test_type']),
            'task'          => $test['task_number'],
            'title'         => $test['title'],
            'description'   => $test['description'],
            'instructions'  => $test['instructions'],
            'time_limit'    => $test['duration_minutes'],
            'word_target'   => $test['word_target'],
            'word_max'      => $test['word_max'],
            'media_file'    => $test['media_file'],
            'category'      => $test['category'],
            'test_type'     => $test['test_type']
        ];
    }, $tests);
}

// Media file path helper
function getMediaPath($filename) {
    return $filename ? 'media/' . $filename : null;
}