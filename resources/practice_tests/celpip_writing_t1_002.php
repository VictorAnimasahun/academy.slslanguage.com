<?php
$writingConfig = [
    'test_code' => 'CELPIP_PT_W1_002',
    'task_type' => 'writing_task1',
    'task_title' => 'CELPIP Writing Task 1 – Practice 2',
    'test_number' => 2,
    'scenario' => 'You are a member of a community gym. Several pieces of exercise equipment have been broken for weeks without being repaired.',
    'lead' => 'Write an email to the gym manager. Your email should do the following things:',
    'bullets' => ['Describe the problem with the broken equipment.', 'Explain how this has affected your workouts.', 'Suggest what the gym should do to fix the situation.'],
    'placeholder' => "Dear Gym Manager,\n\nI am writing to bring an issue to your attention...",
];
require __DIR__ . '/celpip_writing_runner.php';
