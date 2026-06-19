<?php
/**
 * Full Mock Test map.
 * Each entry maps a full mock test code → its section files and DB test codes.
 *
 * 'file'      — the PHP layout file inside resources/mock_tests/ that renders the section
 * 'test_code' — the code in the tests table used for DB scoring and question lookup
 *
 * To add a new full mock test:
 *   1. Add an entry here with a new mock code and section files
 *   2. Create the layout files (full_mock_002_listening.php, etc.)
 *   3. Seed the tests table with the new section test codes
 *   4. Upload questions and correct answers via sls-admin
 */
return [
    'IELTS_FULL_MOCK_001' => [
        'listening' => ['file' => 'full_mock_001_listening.php', 'test_code' => 'IELTS_FM1_L'],
        'reading'   => ['file' => 'full_mock_001_reading.php',  'test_code' => 'IELTS_FM1_R'],
        'writing'   => ['file' => 'mock_writing.php',           'test_code' => 'IELTS_FM1_W'],
    ],
    'IELTS_FULL_MOCK_002' => [
        'listening' => ['file' => 'full_mock_002_listening.php', 'test_code' => 'IELTS_FM2_L'],
        'reading'   => ['file' => 'full_mock_002_reading.php',  'test_code' => 'IELTS_FM2_R'],
        'writing'   => ['file' => 'mock_writing.php',           'test_code' => 'IELTS_FM2_W'],
    ],
    // PLACEHOLDER — layout files exist but no content migrations seeded yet.
    'IELTS_FULL_MOCK_003' => [
        'listening' => ['file' => 'full_mock_003_listening.php', 'test_code' => 'IELTS_FM3_L'],
        'reading'   => ['file' => 'full_mock_003_reading.php',  'test_code' => 'IELTS_FM3_R'],
        'writing'   => ['file' => 'mock_writing.php',           'test_code' => 'IELTS_FM3_W'],
    ],
    // PLACEHOLDER — layout files exist but no content migrations seeded yet.
    'IELTS_FULL_MOCK_004' => [
        'listening' => ['file' => 'full_mock_004_listening.php', 'test_code' => 'IELTS_FM4_L'],
        'reading'   => ['file' => 'full_mock_004_reading.php',  'test_code' => 'IELTS_FM4_R'],
        'writing'   => ['file' => 'mock_writing.php',           'test_code' => 'IELTS_FM4_W'],
    ],
];
