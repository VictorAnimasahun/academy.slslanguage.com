<?php
$writingConfig = [
    'test_code' => 'CELPIP_PT_W1_004',
    'task_type' => 'writing_task1',
    'task_title' => 'CELPIP Writing Task 1 – Practice 4',
    'test_number' => 4,
    'scenario' => 'You recently started taking English classes at a local language school. You are happy with the class overall, but you think the class size is too large for students to get enough speaking practice.',
    'lead' => "Write an email to the school's program coordinator. Your email should do the following things:",
    'bullets' => ['Explain your concern about the class size.', 'Describe how this affects your learning.', 'Suggest what the school could do to improve the situation.'],
    'placeholder' => "Dear Program Coordinator,\n\nI am writing about a concern I have with my current class...",
];
require __DIR__ . '/celpip_writing_runner.php';
