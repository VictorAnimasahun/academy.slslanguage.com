<?php
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once __DIR__ . '/course_context.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../edu_hub_registration.php?message=Please+login+to+access+this+course");
    exit();
}

if (!can_access('intermediate')) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <title>CELPIP Masterclass — Writing: Email Task</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <link href="../../../assets/css/courses.css" rel="stylesheet">
        <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    </head>
    <body>
        <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
        <div class="mobile-overlay" id="mobileOverlay"></div>
        <?php include INCLUDES_PATH . '/navbar.php'; ?>
        <main class="main-wrapper">
            <div class="course-card">
                <?php render_upgrade_prompt('intermediate', 'Writing — Email Task (CLB Bands)'); ?>
            </div>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CELPIP Masterclass — Writing: Email Task</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../../../assets/css/courses.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
</head>
<body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <main class="main-wrapper">
        <div class="course-card">

            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li>
                    <li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Writing — Email Task (CLB Bands)</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-envelope-paper me-2" style="color: #0b77ff;"></i>
                Writing — Email Task (CLB Bands)
            </h1>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="mb-2" style="color: var(--accent);">Class Overview</h4>
                        <p class="mb-0">
                            <strong>Module:</strong> Month 1 — CELPIP Foundations &nbsp;|&nbsp;
                            <strong>Focus:</strong> Writing Task 1 &nbsp;|&nbsp;
                            <strong>Duration:</strong> 75 min
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-custom">Writing Task 1</span>
                        <span class="badge-custom">Email</span>
                        <span class="badge-custom">CLB Scoring</span>
                    </div>
                </div>
            </div>

            <!-- Section 1: Task overview -->
            <div class="content-section">
                <h2><i class="bi bi-clipboard-data me-2" style="color: #0b77ff;"></i>CELPIP Writing Task 1 — The Email</h2>
                <p>
                    CELPIP Writing gives you two tasks in 53 minutes. Task 1 is an <strong>email</strong> —
                    you're given a situation (a problem, a request, or news to share) and asked to write to a
                    specific person about it. You get 27 minutes and need at least 150 words.
                </p>
                <p>
                    Unlike IELTS, there's no fixed "formal vs informal" split decided in advance — the prompt
                    itself tells you who you're writing to (a landlord, a friend, a coworker, a company), and
                    your job is to choose the right register and structure to match.
                </p>
                <div class="info-grid">
                    <div class="info-card" style="border-color:#0b77ff;">
                        <h4 style="color:#0b77ff;"><i class="bi bi-briefcase me-2"></i>Formal Register</h4>
                        <p class="mb-0">Complaints to a business, requests to an institution, emails to an employer you don't know well. Use full sentences, no contractions, "Dear Sir/Madam" or "Dear [Name]," and a formal sign-off ("Sincerely," / "Best regards,").</p>
                    </div>
                    <div class="info-card" style="border-color:#0b77ff;">
                        <h4 style="color:#0b77ff;"><i class="bi bi-people me-2"></i>Informal Register</h4>
                        <p class="mb-0">Emails to friends or family. Contractions are fine, tone is warm and conversational, sign-off can be a first name only.</p>
                    </div>
                </div>
            </div>

            <!-- Section 2: Structure -->
            <div class="content-section">
                <h2><i class="bi bi-diagram-3 me-2" style="color: #0b77ff;"></i>A Reliable 3-Paragraph Structure</h2>
                <ol class="custom-list">
                    <li><strong>Opening:</strong> state why you're writing in 1-2 sentences. Don't make the reader guess.</li>
                    <li><strong>Body:</strong> give the necessary details — this is usually 2-3 specific pieces of information the prompt asks you to cover. Address <em>every</em> bullet point in the prompt; missing one caps your Content/Coherence score regardless of how well the rest is written.</li>
                    <li><strong>Closing:</strong> state clearly what you want to happen next (a refund, a reply, a meeting) and thank the reader or express goodwill.</li>
                </ol>
                <div class="highlight-box mt-3">
                    <h5><i class="bi bi-lightbulb me-2"></i>Why "Address Every Bullet Point" Matters So Much</h5>
                    <p class="mb-0">
                        CELPIP's CLB scale rewards <em>task fulfillment</em> heavily. A grammatically flawless email
                        that skips one of the three things the prompt asked for will score noticeably lower than a
                        simpler email that covers all three. Before you start writing, underline every task the
                        prompt gives you and check them off as you write.
                    </p>
                </div>
            </div>

            <!-- Section 3: CLB scoring for writing -->
            <div class="content-section">
                <h2><i class="bi bi-bar-chart-line me-2" style="color: #0b77ff;"></i>How CELPIP Writing Is Scored</h2>
                <p>CELPIP Writing is marked on four criteria, each mapped to the CLB (Canadian Language Benchmark) scale from 1–12:</p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-check2-circle me-2" style="color:#0b77ff;"></i>Content/Coherence</h4>
                        <p class="mb-0">Did you address the task fully? Is the email logically organized with clear paragraphing?</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-translate me-2" style="color:#0b77ff;"></i>Vocabulary</h4>
                        <p class="mb-0">Range and accuracy of word choice — can you avoid repeating the same 3 adjectives throughout?</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-braces me-2" style="color:#0b77ff;"></i>Readability</h4>
                        <p class="mb-0">Sentence variety and flow — a mix of simple and complex sentences reads better than a string of short, identical ones.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-pencil me-2" style="color:#0b77ff;"></i>Task Fulfillment</h4>
                        <p class="mb-0">Register match, word count (150+), and whether every part of the prompt was answered.</p>
                    </div>
                </div>
                <p class="mt-3">A CLB 9 email (a common immigration target) typically has: full task coverage, a couple of minor grammar slips at most, clear organization, and natural — not necessarily advanced — vocabulary.</p>
            </div>

            <!-- Sample walkthrough -->
            <div class="content-section">
                <h2><i class="bi bi-file-earmark-text me-2" style="color: #0b77ff;"></i>Worked Example</h2>
                <div class="highlight-box">
                    <h5>Prompt (paraphrased)</h5>
                    <p class="mb-0">You booked a table at a restaurant for a special occasion, but when you arrived the reservation had been lost and you had to wait an hour. Write an email to the restaurant manager. Explain what happened, describe how this affected your evening, and say what you would like the restaurant to do about it.</p>
                </div>
                <p class="mt-3"><strong>Three tasks embedded here:</strong> (1) explain what happened, (2) describe the impact, (3) state what you want done. A strong response gives each one its own moment — usually its own paragraph or clearly its own sentence-group — rather than blending them together.</p>
            </div>

            <!-- Quiz -->
            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2" style="color: #0b77ff;"></i>Class Quiz</h2>
                <div class="highlight-box" style="border-left-color: #16a34a; background: #f0fdf4;">
                    <h5 style="color: #16a34a;"><i class="bi bi-check2-square me-2"></i>Register &amp; Structure Check</h5>
                    <p class="mb-2">Identify formal vs. informal register from sample prompts, and put a scrambled 3-paragraph email back in the correct order.</p>
                    <p class="mb-2"><strong>Format:</strong> 8 questions | Mixed</p>
                    <a href="<?= ACADEMY_URL ?>resources/quizzes/class_quiz.php?test_code=CELPIP_GM_M1_WRITING_QUIZ&return=<?= urlencode('../../courses/CELPIP_Gen/lessons/month1_writing.php') ?>" class="btn btn-success btn-sm"><i class="bi bi-play-fill me-1"></i>Start Quiz</a>
                </div>
            </div>

            <!-- Take-home -->
            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color: #0b77ff;"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h5><i class="bi bi-pencil-square me-2"></i>Before Next Class</h5>
                    <p class="mb-0">
                        Write a 150+ word email responding to the restaurant prompt above, timed to 27 minutes.
                        Underline your three task-coverage points before you submit it to yourself for review —
                        this habit is what you'll bring into the timed practice classes later this month.
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="action-buttons">
                <a href="month1_listening.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Previous: Listening
                </a>
                <a href="<?= ACADEMY_URL ?>resources/protected_viewer/celpip_reading.php?part=celpip_reading_pt1" class="btn btn-primary" style="background-color: #0b77ff; border-color: #0b77ff;">
                    Next: Reading — Correspondence &amp; Diagram <i class="bi bi-arrow-right ms-1"></i>
                </a>
                <a href="<?= htmlspecialchars($back['url']) ?>" class="btn btn-outline-primary">
                    <i class="bi bi-grid me-1"></i> Course Overview
                </a>
            </div>

        </div>

        <aside class="advert-sidebar">
            <div class="ad-container">
                <div class="ad-placeholder" style="width:300px; height:250px;">
                    <i class="bi bi-image" style="font-size:2rem; opacity:0.3;"></i>
                    <p class="mb-0 small">Advertisement 300×250</p>
                </div>
            </div>
            <div class="ad-container mt-3">
                <div class="p-3 rounded-3 text-white" style="background: linear-gradient(135deg, #0b77ff, #6366f1);">
                    <h6 class="fw-bold mb-3"><i class="bi bi-map me-2"></i>Course Navigation</h6>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2"><a href="month1_listening.php" class="text-white text-decoration-none"><i class="bi bi-chevron-left me-1"></i>Previous Class</a></li>
                        <li><a href="<?= htmlspecialchars($back['url']) ?>" class="text-white text-decoration-none"><i class="bi bi-grid me-1"></i>Course Overview</a></li>
                    </ul>
                </div>
            </div>
        </aside>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar    = document.querySelector('.sidebar');
        const overlay    = document.getElementById('mobileOverlay');
        function toggleMenu() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            const icon = menuToggle.querySelector('i');
            icon.className = sidebar.classList.contains('active') ? 'bi bi-x-lg' : 'bi bi-list';
        }
        menuToggle.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu);
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', () => { if (window.innerWidth < 1200) toggleMenu(); });
        });
    </script>
</body>
</html>
