<?php
$writingConfig = [
    'test_code' => 'CELPIP_PT_W2_002',
    'task_type' => 'writing_task2',
    'task_title' => 'CELPIP Writing Task 2 – Practice 2',
    'test_number' => 2,
    'scenario' => "Office Holiday Party Survey.\n\nYour company is planning its annual holiday party. In the past, the party has always been held at a fancy downtown restaurant. This year, some employees have suggested having a simpler potluck-style party at the office instead, to save money for other employee benefits. You have been asked to respond to an opinion survey.",
    'lead' => 'Choose the option that you prefer. Why do you prefer your choice? Explain the reasons for your choice. Write about 150-200 words.',
    'options' => ['A' => 'I think we should keep the restaurant party.', 'B' => 'I think we should have a potluck at the office instead.'],
    'placeholder' => "Option A or B: I think we should...\n\nExplain your choice and support it with reasons and examples.",
];
require __DIR__ . '/celpip_writing_runner.php';
