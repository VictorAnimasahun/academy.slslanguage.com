<?php
$writingConfig = [
    'test_code' => 'CELPIP_PT_W1_002',
    'task_type' => 'writing_task1',
    'task_title' => 'CELPIP Writing Task 1 – Practice 2',
    'test_number' => 2,
    'scenario' => 'You recently visited a local restaurant that was highly recommended to you by friends. However, your experience was far from satisfactory.',
    'lead' => 'Write a letter to the restaurant manager. Your letter should do the following things:',
    'bullets' => ['Describe what went wrong during your visit.', 'Explain what you were expecting from the dining experience.', 'Suggest how the restaurant can improve and address the issue.'],
    'placeholder' => "Dear Restaurant Manager,\n\nI am writing to complain about my recent visit to your restaurant...",
];
require __DIR__ . '/celpip_writing_runner.php';
