<?php
$writingConfig = [
    'test_code' => 'CELPIP_PT_W2_004',
    'task_type' => 'writing_task2',
    'task_title' => 'CELPIP Writing Task 2 – Practice 4',
    'test_number' => 4,
    'scenario' => "Office Dress Code Survey.\n\nYour company currently requires formal business attire every day. Management is considering allowing casual dress on Fridays. Some employees support the change; others think it may look unprofessional to clients. You have been asked to respond to an opinion survey.",
    'lead' => 'Choose the option that you prefer. Why do you prefer your choice? Explain the reasons for your choice. Write about 150-200 words.',
    'options' => ['A' => 'I think we should keep the formal dress code every day.', 'B' => 'I think we should allow casual dress on Fridays.'],
    'placeholder' => "Option A or B: I think we should...\n\nExplain your choice and support it with reasons and examples.",
];
require __DIR__ . '/celpip_writing_runner.php';
