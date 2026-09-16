<?php
// SCAFFOLD — no real content yet. Same real runner as t1_001-003 (timer,
// word count, submit-to-essay_analyzer.php all work); only prompt_html/
// placeholder are placeholders. Replace them with a real Task 1 prompt
// when one is transcribed — no other change needed.
$writingConfig = [
    'test_code' => 'CELPIP_PT_W1_004',
    'task_type' => 'writing_task1',
    'task_title' => 'CELPIP Writing Task 1 – Practice 4',
    'test_number' => 4,
    'prompt_html' => '<p class="mb-0"><strong>Content for this practice test has not been added yet.</strong> Check back soon, or head to <a href="index.php">Practice Tests</a> for a set that\'s ready now.</p>',
    'placeholder' => "This practice test's prompt hasn't been added yet.",
];
require __DIR__ . '/celpip_writing_runner.php';
