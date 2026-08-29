<?php
// IELTS Listening Practice 3 — Placeholder
// Fill $parts per academy/documentation/ai_test_page_template.md
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}

$testCode  = 'IELTS_PT_L_003';
$timeLimit = 20 * 60; // seconds

// TODO: populate $parts with the part structure and questions.
$parts = []; // see ai_test_page_template.md for format

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>IELTS Listening – Practice 3 (Placeholder)</title>
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
</head>
<body>
<?php include INCLUDES_PATH . '/navbar.php'; ?>
<main class="p-4">
    <div class="container">
        <h1>IELTS Listening – Practice 3 (Placeholder)</h1>
        <p class="text-muted">This is a placeholder page. Populate the $parts array and client-side JS according to academy/documentation/ai_test_page_template.md</p>
    </div>
</main>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
