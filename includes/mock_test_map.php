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
        'speaking'  => ['file' => 'mock_speaking.php'],
    ],
    'IELTS_FULL_MOCK_002' => [
        'listening' => ['file' => 'full_mock_002_listening.php', 'test_code' => 'IELTS_FM2_L'],
        'reading'   => ['file' => 'full_mock_002_reading.php',  'test_code' => 'IELTS_FM2_R'],
        'writing'   => ['file' => 'mock_writing.php',           'test_code' => 'IELTS_FM2_W'],
        'speaking'  => ['file' => 'mock_speaking.php'],
    ],
    // PLACEHOLDER — layout files exist but no content migrations seeded yet.
    'IELTS_FULL_MOCK_003' => [
        'listening' => ['file' => 'full_mock_003_listening.php', 'test_code' => 'IELTS_FM3_L'],
        'reading'   => ['file' => 'full_mock_003_reading.php',  'test_code' => 'IELTS_FM3_R'],
        'writing'   => ['file' => 'mock_writing.php',           'test_code' => 'IELTS_FM3_W'],
        'speaking'  => ['file' => 'mock_speaking.php'],
    ],
    // PLACEHOLDER — layout files exist but no content migrations seeded yet.
    'IELTS_FULL_MOCK_004' => [
        'listening' => ['file' => 'full_mock_004_listening.php', 'test_code' => 'IELTS_FM4_L'],
        'reading'   => ['file' => 'full_mock_004_reading.php',  'test_code' => 'IELTS_FM4_R'],
        'writing'   => ['file' => 'mock_writing.php',           'test_code' => 'IELTS_FM4_W'],
        'speaking'  => ['file' => 'mock_speaking.php'],
    ],
    // Abridged diagnostic (IELTS Academic Masterclass, Week 1 Class 2) — its own
    // contained set of section files (diagnostic_aca_*.php), separate from the
    // Full Mock templates so its shorter content (1 writing task, 20 min) never
    // has to bend a template built for the Full Mock shape (2 tasks, 60 min).
    'IELTS_ACA_DIAGNOSTIC' => [
        'listening' => ['file' => 'diagnostic_aca_listening.php', 'test_code' => 'IELTS_ACA_DIAG_L'],
        'reading'   => ['file' => 'diagnostic_aca_reading.php',  'test_code' => 'IELTS_ACA_DIAG_R'],
        'writing'   => ['file' => 'diagnostic_aca_writing.php',  'test_code' => 'IELTS_ACA_DIAG_W'],
        'speaking'  => ['file' => 'diagnostic_aca_speaking.php'],
    ],
];
