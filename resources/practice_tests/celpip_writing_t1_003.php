<?php
$writingConfig = [
    'test_code' => 'CELPIP_PT_W1_003',
    'task_type' => 'writing_task1',
    'task_title' => 'CELPIP Writing Task 1 – Practice 3',
    'test_number' => 3,
    'scenario' => 'You recently attended an event at a venue in your city, but the experience did not meet your expectations.',
    'lead' => 'Write a letter to the event organizer. Your letter should do the following things:',
    'bullets' => ['Explain the issues you faced during the event.', 'Describe what you had expected from the event.', 'Suggest ways the organizer could improve future events.'],
    'placeholder' => "Dear Event Organizer,\n\nI am writing to express my concerns about the recent event...",
];
require __DIR__ . '/celpip_writing_runner.php';
