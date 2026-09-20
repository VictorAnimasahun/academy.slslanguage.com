<?php
// Model answers: IELTS Academic Writing Test 1 (Tasks 1A and 2A).
// Source: official IELTS.org "Sample Candidate Writing Scripts and Examiner Comments" (supplied by the instructor).
// The scripts are scanned handwriting, shown as images with the official band and examiner comment.
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Model Answers – IELTS Academic Writing Test 1 | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .task-title { font-size:1.15rem; font-weight:700; margin:2.25rem 0 .75rem; padding-bottom:.4rem; border-bottom:2px solid #0b77ff; }
        .prompt-card { background:#f0f7ff; border-left:4px solid #0b77ff; border-radius:8px; padding:1rem 1.25rem; margin-bottom:1rem; }
        .script-card { background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:1.25rem; margin-bottom:1.25rem; }
        .script-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:.75rem; }
        .script-name { font-weight:700; }
        .band-pill { background:#0b77ff; color:#fff; border-radius:999px; padding:.25rem .8rem; font-weight:700; font-size:.85rem; }
        .script-img { width:100%; max-width:640px; border:1px solid #e5e7eb; border-radius:8px; display:block; margin:0 auto 1rem; }
        .examiner { background:#f8fafc; border-radius:10px; padding:1rem 1.25rem; font-size:.93rem; line-height:1.6; }
        .crit li { margin-bottom:.3rem; }
    </style>
</head>
<body class="light">
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>
    <div class="main-wrapper flex-grow-1" style="flex:1;">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>
        <main class="content p-4">
            <div style="max-width:820px;">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="model_answers.php">Model Answers</a></li>
                        <li class="breadcrumb-item active">IELTS Academic Writing – Test 1</li>
                    </ol>
                </nav>
                <h1 style="font-size:1.5rem;font-weight:700;margin-bottom:.25rem;">IELTS Academic Writing – Test 1</h1>
                <p class="text-muted mb-3">Real candidate scripts with the official examiner comments. Read your own answer against these to see what moves a response from Band 5 to Band 6 and beyond.</p>

                <div class="script-card">
                    <div class="fw-bold mb-2">How Writing is marked</div>
                    <ul class="crit mb-2">
                        <li><strong>Task 1:</strong> Task Achievement, Coherence and Cohesion, Lexical Resource, Grammatical Range and Accuracy.</li>
                        <li><strong>Task 2:</strong> Task Response, Coherence and Cohesion, Lexical Resource, Grammatical Range and Accuracy.</li>
                    </ul>
                    <p class="text-muted small mb-0">Task 2 carries more weight than Task 1. Scripts are penalised if they are under the minimum word length (150 / 250), copied, or not written as full connected text. These scripts are examples, not definitive models of any band.</p>
                </div>

                <div class="task-title">Task 1A &middot; The chart</div>
                <div class="prompt-card">
                    <p class="mb-2"><strong>The chart below shows the number of men and women in further education in Britain in three periods and whether they were studying full-time or part-time.</strong></p>
                    <p class="mb-2">Summarise the information by selecting and reporting the main features, and make comparisons where relevant.</p>
                    <p class="mb-0 text-muted small">Write at least 150 words &middot; about 20 minutes.</p>
                </div>
                <img src="<?= ACADEMY_URL ?>assets/images/ielts_academic/writing_001_task1_chart.png" alt="Bar chart: thousands of men and women in further education in Britain, full-time and part-time, 1970/71, 1980/81 and 1990/91" class="script-img mb-3" style="max-width:520px;">
                <div class="script-card">
                    <div class="script-head"><span class="script-name">Sample Script A</span><span class="band-pill">Band 5</span></div>
                    <img src="<?= ACADEMY_URL ?>assets/images/ielts_academic/writing_001_task1_script_a.png" alt="Handwritten candidate response, Task 1A, Sample Script A, Band 5" class="script-img">
                    <div class="examiner"><strong>Examiner comment</strong><p class="mb-0">The length of the answer is just acceptable. There is a good attempt to describe the overall trends but the content would have been greatly improved if the candidate had included some reference to the figures given on the graph. Without these, the reader is lacking some important information. The answer is quite difficult to follow and there are some punctuation errors that cause confusion. The structures are fairly simple and efforts to produce more complex sentences are not successful.</p></div>
                </div>
                <div class="script-card">
                    <div class="script-head"><span class="script-name">Sample Script B</span><span class="band-pill">Band 6</span></div>
                    <img src="<?= ACADEMY_URL ?>assets/images/ielts_academic/writing_001_task1_script_b.png" alt="Handwritten candidate response, Task 1A, Sample Script B, Band 6" class="script-img">
                    <div class="examiner"><strong>Examiner comment</strong><p class="mb-0">The candidate has made a good attempt to describe the graphs looking at global trends and more detailed figures. There is, however, some information missing and the information is inaccurate in minor areas. The answer flows quite smoothly although connectives are overused or inappropriate, and some of the points do not link up well. The grammatical accuracy is quite good and the language used to describe the trends is well-handled. However, there are problems with expression and the appropriate choice of words and whilst there is good structural control, the complexity and variation in the sentences are limited.</p></div>
                </div>

                <div class="task-title">Task 2A &middot; The essay</div>
                <div class="prompt-card">
                    <p class="mb-2"><strong>The first car appeared on British roads in 1888. By the year 2000 there may be as many as 29 million vehicles on British roads.</strong></p>
                    <p class="mb-2"><strong>Alternative forms of transport should be encouraged and international laws introduced to control car ownership and use.</strong></p>
                    <p class="mb-2"><strong>To what extent do you agree or disagree?</strong></p>
                    <p class="mb-2">Give reasons for your answer and include any relevant examples from your knowledge or experience.</p>
                    <p class="mb-0 text-muted small">Write at least 250 words &middot; about 40 minutes.</p>
                </div>
                <div class="script-card">
                    <div class="script-head"><span class="script-name">Sample Script A</span><span class="band-pill">Band 5</span></div>
                    <img src="<?= ACADEMY_URL ?>assets/images/ielts_academic/writing_001_task2_script_a.png" alt="Handwritten candidate response, Task 2A, Sample Script A, Band 5" class="script-img">
                    <div class="examiner"><strong>Examiner comment</strong><p class="mb-0">The answer is short at just over 200 words and thus loses marks for content. There are some relevant arguments but these are not very well developed and become unclear in places. The organisation of the answer is evident through the use of fairly simple connectives but there are problems for the reader in that there are many missing words and word order is often incorrect. The structures are quite ambitious but often faulty and vocabulary is kept quite simple.</p></div>
                </div>
                <div class="script-card">
                    <div class="script-head"><span class="script-name">Sample Script B</span><span class="band-pill">Band 6</span></div>
                    <img src="<?= ACADEMY_URL ?>assets/images/ielts_academic/writing_001_task2_script_b.png" alt="Handwritten candidate response, Task 2A, Sample Script B, Band 6" class="script-img">
                    <div class="examiner"><strong>Examiner comment</strong><p class="mb-0">There are quite a lot of ideas and while some of these are supported better than others, there is an overall coherence to the answer. The introduction is perhaps slightly long and more time could have been devoted to answering the question. The answer is fairly easy to follow and there is good punctuation. Organisational devices are evident although some areas of the answer become unclear and would benefit from more accurate use of connectives. There are some errors in the structures but there is also evidence of the production of complex sentence forms. Grammatical errors interfere slightly with comprehension.</p></div>
                </div>

                <p class="text-muted small mt-4">Source: IELTS.org, Academic Writing sample tasks and candidate scripts.</p>
            </div>
        </main>
    </div>
    <?php include INCLUDES_PATH . '/adverts.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
