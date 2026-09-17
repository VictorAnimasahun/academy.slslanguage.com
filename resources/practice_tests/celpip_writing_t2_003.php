<?php
$writingConfig = [
    'test_code' => 'CELPIP_PT_W2_003',
    'task_type' => 'writing_task2',
    'task_title' => 'CELPIP Writing Task 2 – Practice 3',
    'test_number' => 3,
    'scenario' => 'Some people believe that working from home benefits both employees and employers, while others argue that it reduces productivity and teamwork.',
    'lead' => 'Choose the option that you prefer. Why do you prefer your choice? Explain the reasons for your choice. Write about 150-200 words.',
    'options' => ['A' => 'Agree with the statement.', 'B' => 'Disagree with the statement.'],
    'placeholder' => "Option A or B: I believe that...\n\nExplain your choice and support it with reasons and examples.",
];
require __DIR__ . '/celpip_writing_runner.php';
