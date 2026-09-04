<?php
$writingConfig = [
    'test_code' => 'CELPIP_PT_W1_002',
    'task_type' => 'writing_task1',
    'task_title' => 'CELPIP Writing Task 1 – Practice 2',
    'test_number' => 2,
    'prompt_html' => '<p class="mb-2"><strong>You recently visited a local restaurant that was highly recommended to you by friends.</strong></p><p class="mb-2">However, your experience was far from satisfactory. Write a letter to the restaurant manager. In your letter:</p><ul><li>describe what went wrong during your visit</li><li>explain what you were expecting from the dining experience</li><li>suggest how the restaurant can improve and address the issue</li></ul>',
    'placeholder' => "Dear Restaurant Manager,\n\nI am writing to complain about my recent visit to your restaurant...",
];
require __DIR__ . '/celpip_writing_runner.php';
