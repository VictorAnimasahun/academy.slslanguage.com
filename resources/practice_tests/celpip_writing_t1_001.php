<?php
$writingConfig = [
    'test_code' => 'CELPIP_PT_W1_001',
    'task_type' => 'writing_task1',
    'task_title' => 'CELPIP Writing Task 1 – Practice 1',
    'test_number' => 1,
    // Real official CELPIP question, from the CELPIP Writing Pro: Target 5/9
    // Study Packs (Prometric, 2022) — used as the Task 1 example in both.
    'scenario' => 'You are an elementary school teacher. There is a famous writer who lives near your school.',
    'lead' => 'In about 150-200 words, write to this writer and invite her to speak to the children in your class. Your message must include the following points:',
    'bullets' => ['An introduction to the school and your class.', "Why the children like this writer's books.", 'An invitation to visit the classroom.', 'What the writer can do with the children.'],
    'placeholder' => "Dear Course Provider,\n\nI am writing to complain about the accommodation arranged for me...",
];
require __DIR__ . '/celpip_writing_runner.php';
