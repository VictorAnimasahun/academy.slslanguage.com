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
        <title>CELPIP Masterclass — Advanced Writing: CLB 10+ Email Structures</title>
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
                <?php render_upgrade_prompt('intermediate', 'Advanced Writing — CLB 10+ Email Structures'); ?>
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
    <title>CELPIP Masterclass — Advanced Writing: CLB 10+ Email Structures</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Advanced Writing — CLB 10+ Email Structures</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-envelope-paper-fill me-2" style="color: #0b77ff;"></i>
                Advanced Writing — CLB 10+ Email Structures
            </h1>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="mb-2" style="color: var(--accent);">Class Overview</h4>
                        <p class="mb-0">
                            <strong>Module:</strong> Month 2 — Advanced CELPIP Strategies &nbsp;|&nbsp;
                            <strong>Focus:</strong> Writing Task 1 (CLB 10+) &nbsp;|&nbsp;
                            <strong>Duration:</strong> 75 min
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-custom">Complex Register</span>
                        <span class="badge-custom">Tone Management</span>
                        <span class="badge-custom">CLB 10-12</span>
                    </div>
                </div>
            </div>

            <!-- Section 1: what separates CLB 9 from CLB 10+ -->
            <div class="content-section">
                <h2><i class="bi bi-graph-up-arrow me-2" style="color: #0b77ff;"></i>What Separates CLB 9 from CLB 10-12</h2>
                <p>
                    A CLB 9 email fully addresses the task with clear organization and minor errors. To push
                    into CLB 10-12 territory, examiners look for control over <strong>tone shifts</strong> within
                    a single email, more precise vocabulary (not just "correct" but well-chosen), and structural
                    sophistication — like combining two ideas into one complex sentence instead of two simple
                    ones back to back.
                </p>
            </div>

            <!-- Section 2: tone shifting -->
            <div class="content-section">
                <h2><i class="bi bi-arrow-left-right me-2" style="color: #0b77ff;"></i>Managing a Tone Shift Within One Email</h2>
                <p>
                    Higher-level prompts often require you to be simultaneously firm and polite — for example,
                    complaining about a service failure while still requesting future business, or delivering
                    unwelcome news to a colleague while maintaining a collaborative tone.
                </p>
                <div class="highlight-box mt-2">
                    <h5><i class="bi bi-lightbulb me-2"></i>Technique: The Buffer Sentence</h5>
                    <p class="mb-0">
                        Before delivering criticism or a firm request, use a one-sentence "buffer" that
                        acknowledges the relationship or context positively: "I have generally been very happy
                        with your service, which is why I wanted to raise the following issue directly." This
                        single sentence signals emotional control — a marker examiners associate with higher CLB
                        levels — before you state your complaint.
                    </p>
                </div>
            </div>

            <!-- Section 3: sentence combining -->
            <div class="content-section">
                <h2><i class="bi bi-braces-asterisk me-2" style="color: #0b77ff;"></i>Sentence Combining for Readability</h2>
                <p>Compare these two versions responding to the same prompt:</p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4>Simple (CLB 7-8 range)</h4>
                        <p class="mb-0">"The delivery was late. I was not able to use the item for the event. I am requesting a refund."</p>
                    </div>
                    <div class="info-card" style="border-color:#0b77ff;">
                        <h4 style="color:#0b77ff;">Combined (CLB 10+ range)</h4>
                        <p class="mb-0">"Because the delivery arrived three days late, I was unable to use the item for the event it was intended for, which is why I am requesting a full refund."</p>
                    </div>
                </div>
                <p class="mt-3">
                    The combined version uses subordination ("because... which is why...") to show clear
                    cause-and-effect reasoning in one flowing sentence — exactly the kind of grammatical range
                    examiners reward at the top of the scale. Aim for 1-2 combined sentences like this per email,
                    not every sentence — overdoing it reads as unnatural.
                </p>
            </div>

            <!-- Vocabulary precision -->
            <div class="content-section">
                <h2><i class="bi bi-translate me-2" style="color: #0b77ff;"></i>Precision Over Impressiveness</h2>
                <p>
                    A common mistake at this level is reaching for the most "advanced-sounding" word rather than
                    the most accurate one. "Utilize" is not automatically better than "use" — it's only better
                    when it actually fits the register. Examiners notice unnatural word choice more than they
                    reward vocabulary that sounds impressive but doesn't quite fit.
                </p>
            </div>

            <!-- Quiz -->
            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2" style="color: #0b77ff;"></i>Class Quiz</h2>
                <div class="highlight-box" style="border-left-color: #16a34a; background: #f0fdf4;">
                    <h5 style="color: #16a34a;"><i class="bi bi-check2-square me-2"></i>Sentence Combining Practice</h5>
                    <p class="mb-2">Combine pairs of simple sentences into one higher-level complex sentence using subordination.</p>
                    <p class="mb-2"><strong>Format:</strong> 8 questions | Sentence rewriting</p>
                    <a href="<?= ACADEMY_URL ?>resources/quizzes/class_quiz.php?test_code=CELPIP_GM_M2_WRITING_QUIZ&return=<?= urlencode('../../courses/CELPIP_Gen/lessons/month2_writing.php') ?>" class="btn btn-success btn-sm"><i class="bi bi-play-fill me-1"></i>Start Quiz</a>
                </div>
            </div>

            <!-- Take-home -->
            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color: #0b77ff;"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h5><i class="bi bi-pencil-square me-2"></i>Before Next Class</h5>
                    <p class="mb-0">
                        Rewrite an email you wrote in Month 1 using at least one buffer sentence and one combined
                        sentence. Compare the two versions side by side — note specifically what changed in tone
                        and flow.
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="action-buttons">
                <a href="month2_listening.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Previous: Listening
                </a>
                <a href="month2_speaking.php" class="btn btn-primary" style="background-color: #0b77ff; border-color: #0b77ff;">
                    Next: Advanced Speaking <i class="bi bi-arrow-right ms-1"></i>
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
