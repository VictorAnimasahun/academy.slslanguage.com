<?php
$writingConfig = [
    'test_code' => 'CELPIP_PT_W2_001',
    'task_type' => 'writing_task2',
    'task_title' => 'CELPIP Writing Task 2 – Practice 1',
    'test_number' => 1,
    // Real official CELPIP question, from the CELPIP Writing Pro: Target 5/9
    // Study Packs (Prometric, 2022) — used as the Task 2 example in both.
    'scenario' => "Vacation Time or Job Training?\n\nYou work in a small office. The boss wants to reduce vacation time by two days, but send everyone in the office to a nice beachside hotel for 4 days of job training. Some of the staff are very happy with this idea. Others are upset. The boss asked you to respond to an opinion survey.",
    'lead' => 'Choose the option that you prefer. Why do you prefer your choice? Explain the reasons for your choice. Write about 150-200 words.',
    'options' => ['A' => 'I think we should keep our vacation time instead of going to the training.', 'B' => 'I think we should do the training and cut back our vacation time.'],
    'placeholder' => "Option A or B: I believe that...\n\nExplain your choice and support it with reasons and examples.",
];
require __DIR__ . '/celpip_writing_runner.php';
