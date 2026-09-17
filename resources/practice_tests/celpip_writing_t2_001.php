<?php
$writingConfig = [
    'test_code' => 'CELPIP_PT_W2_001',
    'task_type' => 'writing_task2',
    'task_title' => 'CELPIP Writing Task 2 – Practice 1',
    'test_number' => 1,
    'scenario' => 'A research suggests that damage to the environment is an inevitable consequence of worldwide improvements in the standard of living.',
    'lead' => 'Choose the option that you prefer. Why do you prefer your choice? Explain the reasons for your choice. Write about 150-200 words.',
    'options' => ['A' => 'Support the topic.', 'B' => 'Go against the topic.'],
    'placeholder' => "Option A or B: I believe that...\n\nExplain your choice and support it with reasons and examples.",
];
require __DIR__ . '/celpip_writing_runner.php';
