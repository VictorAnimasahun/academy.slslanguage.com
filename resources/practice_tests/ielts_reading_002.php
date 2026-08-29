<?php
// IELTS Reading Practice 2 — Placeholder
// Populate $parts or reading passages per academy/documentation/ai_test_page_template.md
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}

$testCode  = 'IELTS_PT_R_002';
$timeLimit = 60 * 60; // seconds (example)

// TODO: add $parts with passages, question types and answers. Use existing ielts_reading_001.php as example.
$parts = [];

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>IELTS Reading – Practice 2 (Placeholder)</title>
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
</head>
<body>
<?php include INCLUDES_PATH . '/navbar.php'; ?>
<main class="p-4">
    <div class="container">
        <h1>IELTS Reading – Practice 2 (Placeholder)</h1>
        <p class="text-muted">Add passages, questions and follow the migration pattern from academy/database/migrations/010_seed_ielts_reading_pt1.sql</p>
    </div>
</main>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
