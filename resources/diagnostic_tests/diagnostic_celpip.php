<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

$sections = [
    ['name' => 'Listening', 'icon' => 'bi-headphones', 'duration' => '15 minutes', 'questions' => '10 questions'],
    ['name' => 'Reading', 'icon' => 'bi-book', 'duration' => '15 minutes', 'questions' => '11 questions'],
    ['name' => 'Writing', 'icon' => 'bi-pencil-square', 'duration' => '15 minutes', 'questions' => '1 task'],
    ['name' => 'Speaking', 'icon' => 'bi-mic', 'duration' => '15 minutes', 'questions' => '8 tasks'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CELPIP Mini Mock Test | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .main-wrapper { padding: 2rem 1.5rem; min-height: 100vh; background: #f8f9fa; }
        .mock-overview { background: #fff; border-radius: 16px; padding: 3rem 2.5rem; max-width: 950px; margin: 0 auto; box-shadow: 0 6px 30px rgba(0,0,0,.1); }
        .mock-badge { display: inline-block; padding: .65rem 2rem; border-radius: 50px; background: linear-gradient(135deg, #2563eb, #38bdf8); color: #fff; font-size: 1.05rem; font-weight: 700; }
        .section-item { background: #f8f9fa; border-radius: 12px; padding: 1.5rem 1.8rem; margin-bottom: 1rem; }
        .section-icon { color: #2563eb; font-size: 1.8rem; }
        .status-note { border-left: 4px solid #f59e0b; }
    </style>
</head>
<body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <main class="main-wrapper">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../resources_home.php">Resources</a></li>
                    <li class="breadcrumb-item"><a href="diagnostic_tests_home.php">Diagnostic Tests</a></li>
                    <li class="breadcrumb-item active">CELPIP Mini Mock</li>
                </ol>
            </nav>

            <div class="mock-overview">
                <div class="text-center mb-5">
                    <span class="mock-badge">CELPIP DIAGNOSTIC</span>
                    <h1 class="mt-4 mb-3">CELPIP Mini Mock Test</h1>
                    <p class="lead text-muted">A short, four-skill assessment to establish your starting point.</p>
                </div>

                <div class="row text-center mb-5">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="fs-4 fw-bold text-primary">60 minutes</div>
                        <div class="text-muted">Total time</div>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="fs-4 fw-bold text-primary">4 skills</div>
                        <div class="text-muted">Complete assessment</div>
                    </div>
                    <div class="col-md-4">
                        <div class="fs-4 fw-bold text-primary">Diagnostic</div>
                        <div class="text-muted">Not an official score</div>
                    </div>
                </div>

                <h4 class="mb-4 text-center">Test Sections</h4>
                <?php foreach ($sections as $index => $section): ?>
                    <div class="section-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi <?= $section['icon'] ?> section-icon"></i>
                            <div>
                                <strong><?= htmlspecialchars($section['name']) ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($section['duration']) ?> · <?= htmlspecialchars($section['questions']) ?></small>
                            </div>
                        </div>
                        <span class="badge bg-secondary px-3 py-2"><?= $index + 1 ?></span>
                    </div>
                <?php endforeach; ?>

                <div class="alert alert-warning status-note mt-4 mb-0">
                    <strong><i class="bi bi-hourglass-split me-2"></i>Content setup in progress</strong><br>
                    The four-section structure is ready. Questions, answer keys, audio, and saved diagnostic results will be added next.
                </div>

                <div class="text-center mt-5 pt-4 border-top">
                    <button class="btn btn-primary btn-lg px-5" type="button" disabled>
                        <i class="bi bi-play-circle me-2"></i>Start Mini Mock
                    </button>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
</body>
</html>
