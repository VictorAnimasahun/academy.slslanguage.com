<?php
$writingConfig = [
    'test_code' => 'CELPIP_PT_W1_001',
    'task_type' => 'writing_task1',
    'task_title' => 'CELPIP Writing Task 1 – Practice 1',
    'test_number' => 1,
    'prompt_html' => '<p class="mb-2"><strong>You are studying a short course in another country.</strong></p><p class="mb-2">Your accommodation was arranged by the course provider, but there is a major problem with it. Write a letter to the course provider. In your letter:</p><ul><li>say what the problem is</li><li>describe the accommodation you thought you were getting</li><li>ask the provider to solve the problem</li></ul>',
    'placeholder' => "Dear Course Provider,\n\nI am writing to complain about the accommodation arranged for me...",
];
require __DIR__ . '/celpip_writing_runner.php';
