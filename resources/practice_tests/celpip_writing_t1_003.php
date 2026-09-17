<?php
$writingConfig = [
    'test_code' => 'CELPIP_PT_W1_003',
    'task_type' => 'writing_task1',
    'task_title' => 'CELPIP Writing Task 1 – Practice 3',
    'test_number' => 3,
    'scenario' => 'You recently ordered a piece of furniture online for your new apartment. When it arrived, several parts were missing and the furniture could not be assembled.',
    'lead' => "Write an email to the company's customer service department. Your email should do the following things:",
    'bullets' => ['Describe the problem with your order.', 'Explain the inconvenience this has caused you.', 'Ask the company to resolve the problem.'],
    'placeholder' => "Dear Customer Service,\n\nI am writing to report a problem with a recent order...",
];
require __DIR__ . '/celpip_writing_runner.php';
