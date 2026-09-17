<?php
$writingConfig = [
    'test_code' => 'CELPIP_PT_W2_002',
    'task_type' => 'writing_task2',
    'task_title' => 'CELPIP Writing Task 2 – Practice 2',
    'test_number' => 2,
    'scenario' => 'A report suggests that the rise of online shopping is leading to the decline of traditional brick-and-mortar stores.',
    'lead' => 'Choose the option that you prefer. Why do you prefer your choice? Explain the reasons for your choice. Write about 150-200 words.',
    'options' => ['A' => 'Agree with the statement.', 'B' => 'Disagree with the statement.'],
    'placeholder' => "Option A or B: I believe that...\n\nExplain your choice and support it with reasons and examples.",
];
require __DIR__ . '/celpip_writing_runner.php';
