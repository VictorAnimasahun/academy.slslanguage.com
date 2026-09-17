<?php
$writingConfig = [
    'test_code' => 'CELPIP_PT_W1_001',
    'task_type' => 'writing_task1',
    'task_title' => 'CELPIP Writing Task 1 – Practice 1',
    'test_number' => 1,
    'scenario' => 'You are studying a short course in another country. Your accommodation was arranged by the course provider, but there is a major problem with it.',
    'lead' => 'Write a letter to the course provider. Your letter should do the following things:',
    'bullets' => ['Say what the problem is.', 'Describe the accommodation you thought you were getting.', 'Ask the provider to solve the problem.'],
    'placeholder' => "Dear Course Provider,\n\nI am writing to complain about the accommodation arranged for me...",
];
require __DIR__ . '/celpip_writing_runner.php';
