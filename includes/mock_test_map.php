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
    // General Training counterpart to IELTS_ACA_DIAGNOSTIC above. Listening is
    // deliberately NOT its own test — real IELTS Listening is identical between
    // Academic and General Training, so this reuses the Academic diagnostic's
    // exact Listening test/audio rather than duplicating it. Writing reuses
    // diagnostic_aca_writing.php unmodified — that template already renders
    // GT-style letter tasks correctly (see its own comment on the
    // `instructions`-as-image-path convention). Only Reading gets its own file,
    // since diagnostic_aca_reading.php hardcodes the Academic passage text.
    'IELTS_GT_DIAGNOSTIC' => [
        'listening' => ['file' => 'diagnostic_aca_listening.php', 'test_code' => 'IELTS_ACA_DIAG_L'],
        'reading'   => ['file' => 'diagnostic_gt_reading.php',   'test_code' => 'IELTS_GT_DIAG_R'],
        'writing'   => ['file' => 'diagnostic_aca_writing.php',  'test_code' => 'IELTS_GT_DIAG_W'],
        'speaking'  => ['file' => 'diagnostic_aca_speaking.php'],
    ],
    // CELPIP Full Mock Tests A & B (CELPIP Masterclass, course_id=13 — end of Month 1
    // and end of Month 2). Both share the same generic celpip_full_mock_listening.php /
    // celpip_full_mock_reading.php templates (audio/image asset paths are derived from
    // the session's mock_code, same pattern as full_mock_00N_listening.php but reused
    // across both tests since their part structure is identical). Content seeded by
    // migration 073.
    // Speaking uses its own online recorded flow (celpip_full_mock_speaking.php),
    // NOT mock_speaking.php's in-person-instructor notice -- CELPIP is the only
    // exam type with a recorded-audio speaking pipeline. Do not point IELTS
    // entries at this file; mock_speaking.php still handles IELTS speaking.
    'CELPIP_FULL_MOCK_A' => [
        'listening' => ['file' => 'celpip_full_mock_listening.php', 'test_code' => 'CELPIP_FMA_L'],
        'reading'   => ['file' => 'celpip_full_mock_reading.php',  'test_code' => 'CELPIP_FMA_R'],
        'writing'   => ['file' => 'mock_writing.php',              'test_code' => 'CELPIP_FMA_W'],
        'speaking'  => ['file' => 'celpip_full_mock_speaking.php'],
    ],
    'CELPIP_FULL_MOCK_B' => [
        'listening' => ['file' => 'celpip_full_mock_listening.php', 'test_code' => 'CELPIP_FMB_L'],
        'reading'   => ['file' => 'celpip_full_mock_reading.php',  'test_code' => 'CELPIP_FMB_R'],
        'writing'   => ['file' => 'mock_writing.php',              'test_code' => 'CELPIP_FMB_W'],
        'speaking'  => ['file' => 'celpip_full_mock_speaking.php'],
    ],
];
