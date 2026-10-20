<?php
// Foundational English Assessment -- general English level (do they need English lessons before exam prep?).
// Class 1 of every paid course. A plain platform page that opens straight onto the test (founder, 2026-10-21: no start
// screen that leads to another screen). The old public quiz at mini_tests/english-placement-test stays for the
// marketing site. OUTLINE ONLY: the founder writes the questions; until then its course item is Coming Soon.
require_once dirname(__DIR__, 2) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . ACADEMY_URL . 'edu_hub_registration.php?message=Please+login+to+take+the+assessment');
    exit();
}

$sections = [
    ['bi-chat-dots', 'Part 1: Everyday English', 'Choosing the right response in everyday conversations.'],
    ['bi-spellcheck', 'Part 2: Grammar', 'Sentence structure and grammar in context.'],
    ['bi-book', 'Part 3: Vocabulary', 'Word choice and meaning.'],
    ['bi-file-text', 'Part 4: Reading', 'Understanding short everyday texts.'],
    ['bi-headphones', 'Part 5: Listening', 'Understanding short everyday recordings.'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Foundational English Assessment | EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= ACADEMY_URL ?>assets/css/courses.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
</head>
<body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <main class="main-wrapper">
        <div class="course-card">
            <h1 class="mb-2"><i class="bi bi-translate me-2" style="color:#0b77ff;"></i>Foundational English Assessment</h1>
            <p class="text-muted mb-4">Answer every question on your own, without a dictionary. Your result shows whether to strengthen your general English alongside exam preparation.</p>

            <?php foreach ($sections as [$icon, $heading, $about]): ?>
                <div class="content-section">
                    <h2><i class="bi <?= $icon ?> me-2" style="color:#0b77ff;"></i><?= htmlspecialchars($heading) ?></h2>
                    <p class="text-muted mb-0"><?= htmlspecialchars($about) ?> <em>Coming Soon.</em></p>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
