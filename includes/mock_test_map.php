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
];
