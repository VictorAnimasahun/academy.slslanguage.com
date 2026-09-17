<?php
$writingConfig = [
    'test_code' => 'CELPIP_PT_W2_003',
    'task_type' => 'writing_task2',
    'task_title' => 'CELPIP Writing Task 2 – Practice 3',
    'test_number' => 3,
    'scenario' => "Neighbourhood Park Survey.\n\nYour city council is planning to renovate a small park in your neighbourhood. Two designs have been proposed, and the council has asked residents to respond to an opinion survey.",
    'lead' => 'Choose the option that you prefer. Why do you prefer your choice? Explain the reasons for your choice. Write about 150-200 words.',
    'options' => ['A' => 'A quiet garden with walking paths and benches.', 'B' => 'A playground and sports court for children and teenagers.'],
    'placeholder' => "Option A or B: I think we should...\n\nExplain your choice and support it with reasons and examples.",
];
require __DIR__ . '/celpip_writing_runner.php';
