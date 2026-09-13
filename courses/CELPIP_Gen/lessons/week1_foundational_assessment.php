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
        <title>CELPIP Masterclass — Foundational English Assessment</title>
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
                <?php render_upgrade_prompt('intermediate', 'Foundational English Assessment'); ?>
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
    <title>CELPIP Masterclass — Foundational English Assessment</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Foundational English Assessment</li>
                </ol>
            </nav>

            <h1 class="mb-3"><i class="bi bi-compass me-2" style="color:#0b77ff;"></i>Foundational English Assessment</h1>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="mb-2" style="color:var(--accent);">Class Overview</h4>
                        <p class="mb-0"><strong>Week:</strong> 1 — Orientation + Diagnostics &nbsp;|&nbsp; <strong>Class:</strong> 1 of 24 &nbsp;|&nbsp; <strong>Duration:</strong> 75 min</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-custom">Orientation</span>
                        <span class="badge-custom">Self-Assessment</span>
                        <span class="badge-custom">Goal-Setting</span>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-map me-2" style="color:#0b77ff;"></i>Welcome to the 3-Month CELPIP Masterclass</h2>
                <p>
                    Before any CELPIP-specific content, this class establishes where you're actually starting
                    from in general English — not the exam format, your underlying language level. The exam
                    format is learnable in a few weeks; underlying vocabulary range, grammar accuracy, and
                    listening comprehension speed take longer, and knowing your starting point now shapes how
                    the next 12 weeks should be spent.
                </p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-clipboard-data me-2" style="color:#0b77ff;"></i>The 12-Week Shape of This Course</h2>
                <p>You'll see this structure repeat throughout the course, so it's worth understanding now:</p>
                <ul class="custom-list">
                    <li><strong>Weeks 2-3:</strong> Core teaching — every CELPIP task type, taught once, in full.</li>
                    <li><strong>Weeks 4, 6, 9, 10 (Checkpoints 1-4):</strong> A complete, full-length practice test in every skill — this is how your progress actually gets measured, not just how confident you feel.</li>
                    <li><strong>Week 5:</strong> Targeted correction, built directly from your Checkpoint 1 results.</li>
                    <li><strong>Week 7:</strong> Timing and strategy refinement, ahead of Mock 1.</li>
                    <li><strong>Weeks 8 &amp; 11:</strong> Full 4-skill mock exams — the closest simulation of the real test day you'll get before the real test day.</li>
                    <li><strong>Week 12:</strong> Final targeted prep and test-day logistics.</li>
                </ul>
                <div class="highlight-box mt-3">
                    <h5><i class="bi bi-lightbulb me-2"></i>Why So Many Checkpoints?</h5>
                    <p class="mb-0">
                        Four checkpoints plus two mocks means you sit a complete test in every skill at least
                        four separate times before your real exam. Familiarity with the exam experience itself —
                        not just the content — is a real, measurable part of performing well under test conditions.
                    </p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-person-check me-2" style="color:#0b77ff;"></i>Your Self-Assessment</h2>
                <p>Before the Mini Diagnostic in the next class, answer these honestly — there's no scoring here, only your own baseline:</p>
                <ol class="custom-list">
                    <li>How comfortable are you having an unscripted conversation in English for 5+ minutes on an everyday topic?</li>
                    <li>When reading a page of English text you haven't seen before, how much do you understand without a dictionary?</li>
                    <li>When writing an email in English, how long does composing 150 words typically take you?</li>
                    <li>When listening to English spoken at a natural, non-slowed pace, what percentage do you feel you follow?</li>
                </ol>
                <div class="highlight-box mt-3">
                    <h5><i class="bi bi-lightbulb me-2"></i>Why This Matters</h5>
                    <p class="mb-0">
                        Your answers here, compared against your Mini Diagnostic and Checkpoint 1 results, tell
                        your instructor whether a gap is a <em>confidence</em> problem (you know more than you
                        think) or a genuine <em>skill</em> gap (more foundational work is needed before exam
                        strategy will help). These require very different Week 5 correction plans.
                    </p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-bullseye me-2" style="color:#0b77ff;"></i>Setting Your Target CLB</h2>
                <p>
                    Know your target Canadian Language Benchmark (CLB) level before you start — most permanent
                    residence and citizenship pathways require specific CLB thresholds per skill, and knowing
                    yours now means every checkpoint result can be read against a real target, not just a
                    generic "higher is better."
                </p>
            </div>

            <!-- Take-home -->
            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color:#0b77ff;"></i>Before Next Class</h2>
                <div class="highlight-box">
                    <p class="mb-0">Write down your target CLB level per skill and your answers to the self-assessment above. Bring both to the Mini Diagnostic next class.</p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="action-buttons">
                <a href="<?= ACADEMY_URL ?>courses/CELPIP_intro/celpip_mini_mock.php" class="btn btn-primary" style="background-color:#0b77ff;border-color:#0b77ff;">Next: Mini Diagnostic <i class="bi bi-arrow-right ms-1"></i></a>
                <a href="<?= htmlspecialchars($back['url']) ?>" class="btn btn-outline-primary"><i class="bi bi-grid me-1"></i> Course Overview</a>
            </div>

        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
