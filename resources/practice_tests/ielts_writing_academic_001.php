<?php
// IELTS Academic Writing – Test 1 (hub). Task 1A (chart, 20 min) + Task 2A (essay, 40 min).
// Source: official IELTS.org Academic Writing sample tasks 1A / 2A (supplied by the instructor).
// Each task runs on its own page and is marked by essay_analyzer.php.
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}
require_once INCLUDES_PATH . '/course_lock.php';
require_course_enrollment([16, 17], 'this IELTS Academic Writing test');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Academic Writing – Test 1 | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .task-card { background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:1.5rem; height:100%; }
        .task-card h4 { font-weight:700; margin-bottom:.25rem; }
        .task-meta { color:#64748b; font-size:.9rem; margin-bottom:1rem; }
        .criteria li { margin-bottom:.35rem; }
    </style>
</head>
<body class="light">
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>
    <div class="main-wrapper flex-grow-1" style="flex:1;">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>
        <main class="content p-4">
            <div style="max-width:980px;">
                <h1 style="font-size:1.6rem;font-weight:700;margin-bottom:.25rem;">IELTS Academic Writing – Test 1</h1>
                <p class="text-muted mb-4">A full timed Writing test: <strong>Task 1</strong> (20 minutes) then <strong>Task 2</strong> (40 minutes), 60 minutes in total. Sit both tasks in one go, in this order, without notes.</p>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="task-card">
                            <h4><i class="bi bi-bar-chart-line me-2 text-success"></i>Task 1</h4>
                            <div class="task-meta">Describe a chart &middot; 20 minutes &middot; at least 150 words</div>
                            <p>You will see a bar chart and write a report selecting and reporting the main features, with comparisons where relevant.</p>
                            <a href="ielts_writing_academic_t1_001.php" class="btn btn-success btn-lg"><i class="bi bi-play-fill me-1"></i>Start Task 1</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="task-card">
                            <h4><i class="bi bi-pencil-square me-2 text-primary"></i>Task 2</h4>
                            <div class="task-meta">Essay &middot; 40 minutes &middot; at least 250 words</div>
                            <p>You will write an essay giving and supporting your position. Task 2 carries more weight than Task 1 in your Writing band.</p>
                            <a href="ielts_writing_academic_t2_001.php" class="btn btn-primary btn-lg"><i class="bi bi-play-fill me-1"></i>Start Task 2</a>
                        </div>
                    </div>
                </div>

                <div class="task-card mb-4">
                    <h5 class="fw-bold">How you are marked</h5>
                    <ul class="criteria mb-2">
                        <li><strong>Task 1:</strong> Task Achievement, Coherence and Cohesion, Lexical Resource, Grammatical Range and Accuracy.</li>
                        <li><strong>Task 2:</strong> Task Response, Coherence and Cohesion, Lexical Resource, Grammatical Range and Accuracy.</li>
                    </ul>
                    <p class="mb-0 text-muted small">Your answer loses marks if it is under the minimum word length, is copied, or is not written as full connected text (no bullet points or note form).</p>
                </div>

                <div class="task-card">
                    <h5 class="fw-bold">After you finish</h5>
                    <p class="mb-2">Compare your answers with real candidate scripts at Band 5 and Band 6, with the official examiner comments.</p>
                    <a href="../model_answers/model_answers_academic_writing_1.php" class="btn btn-outline-primary"><i class="bi bi-journal-check me-1"></i>See the model answers</a>
                </div>
            </div>
        </main>
    </div>
    <?php include INCLUDES_PATH . '/adverts.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
