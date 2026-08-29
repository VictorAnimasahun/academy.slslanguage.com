<?php
// IELTS Writing Task 2 – Practice 4 (Placeholder)
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

$testCode   = 'IELTS_PT_W2_004';
$timeLimit  = 40 * 60;  // seconds
$wordTarget = 250;

$prompt = 'Placeholder Task 2 essay prompt — replace with the real question.';

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>IELTS Writing Task 2 – Practice 4 (Placeholder)</title>
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
</head>
<body>
<?php include INCLUDES_PATH . '/navbar.php'; ?>
<main class="p-4">
    <div class="container">
        <h1>IELTS Writing Task 2 – Practice 4 (Placeholder)</h1>
        <div class="alert alert-info">Prompt: <?php echo htmlspecialchars($prompt); ?></div>
        <p class="text-muted">This is a placeholder page. Keep the save flow to essay_analyzer.php and save_attempt.php when adding final content.</p>
    </div>
</main>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
