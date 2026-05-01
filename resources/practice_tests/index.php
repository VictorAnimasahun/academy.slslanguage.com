<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

$sections = [
    'IELTS' => [
        'color'   => '#10b981',
        'icon'    => 'bi-award',
        'tests'   => [
            ['file' => 'ielts_listening_001.php',    'title' => 'Listening Practice 1',       'section' => 'Listening',       'icon' => 'bi-headphones',    'meta' => '30 min · 40 Questions'],
            ['file' => 'ielts_reading_gt_001.php',   'title' => 'Reading (GT) Practice 1',    'section' => 'Reading',         'icon' => 'bi-book',          'meta' => '60 min · 40 Questions'],
            ['file' => 'ielts_writing_t1_001.php',   'title' => 'Writing Task 1 – Letter',    'section' => 'Writing Task 1',  'icon' => 'bi-envelope',      'meta' => '20 min · 150+ words'],
            ['file' => 'ielts_writing_t2_001.php',   'title' => 'Writing Task 2 – Essay',     'section' => 'Writing Task 2',  'icon' => 'bi-pencil-square', 'meta' => '40 min · 250+ words'],
            ['file' => 'ielts_speaking_001.php',     'title' => 'Speaking Practice 1',        'section' => 'Speaking',        'icon' => 'bi-mic',           'meta' => '~15 min · Parts 1–3'],
        ],
    ],
    'CELPIP' => [
        'color'   => '#3b82f6',
        'icon'    => 'bi-flag',
        'tests'   => [
            ['file' => 'celpip_listening_001.php',   'title' => 'Listening Practice 1',       'section' => 'Listening',       'icon' => 'bi-headphones',    'meta' => '47 min · 8 Parts'],
            ['file' => 'celpip_reading_001.php',     'title' => 'Reading Practice 1',         'section' => 'Reading',         'icon' => 'bi-book',          'meta' => '55 min · 4 Parts'],
            ['file' => 'celpip_writing_t1_001.php',  'title' => 'Writing Task 1 – Email',     'section' => 'Writing Task 1',  'icon' => 'bi-envelope',      'meta' => '27 min · 150–200 words'],
            ['file' => 'celpip_writing_t2_001.php',  'title' => 'Writing Task 2 – Survey',    'section' => 'Writing Task 2',  'icon' => 'bi-pencil-square', 'meta' => '26 min · Structured response'],
            ['file' => 'celpip_speaking_001.php',    'title' => 'Speaking Practice 1',        'section' => 'Speaking',        'icon' => 'bi-mic',           'meta' => '~16 min · 8 Tasks'],
        ],
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practice Tests | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .main-wrapper { padding: 2rem 1.5rem; min-height: 100vh; }
        .exam-block { margin-bottom: 3rem; }
        .exam-header {
            display: flex; align-items: center; gap: 1rem;
            padding: 1.1rem 1.5rem; border-radius: 12px;
            color: white; margin-bottom: 1.5rem;
            font-size: 1.2rem; font-weight: 700;
        }
        .test-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1.1rem;
        }
        .test-card {
            background: white; border-radius: 14px;
            padding: 1.4rem 1.6rem;
            box-shadow: 0 3px 14px rgba(0,0,0,0.07);
            border-left: 4px solid var(--accent);
            cursor: pointer; transition: box-shadow 0.25s;
            text-decoration: none; color: inherit; display: block;
        }
        .test-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.14); color: inherit; }
        .test-card-top { display: flex; align-items: center; gap: .75rem; margin-bottom: .6rem; }
        .test-icon-circle {
            width: 42px; height: 42px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem; color: white; flex-shrink: 0;
            background: var(--accent);
        }
        .test-card-title { font-weight: 700; font-size: 1rem; color: #111827; margin: 0; }
        .test-section-tag {
            display: inline-block; font-size: .75rem; font-weight: 600;
            padding: .2rem .7rem; border-radius: 20px;
            background: #f3f4f6; color: #6b7280; margin-bottom: .5rem;
        }
        .test-meta { font-size: .82rem; color: #9ca3af; }
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
                    <li class="breadcrumb-item active">Practice Tests</li>
                </ol>
            </nav>

            <h1 class="display-5 mb-1">Practice Tests</h1>
            <p class="text-muted mb-5">Standalone timed practice for every section of IELTS and CELPIP.</p>

            <?php foreach ($sections as $exam => $data): ?>
            <div class="exam-block">
                <div class="exam-header" style="background:<?= $data['color'] ?>;">
                    <i class="<?= $data['icon'] ?> fs-4"></i>
                    <?= $exam ?> Practice Tests
                </div>
                <div class="test-grid">
                    <?php foreach ($data['tests'] as $t): ?>
                    <a class="test-card" href="<?= $t['file'] ?>" style="--accent:<?= $data['color'] ?>;">
                        <div class="test-card-top">
                            <div class="test-icon-circle"><i class="<?= $t['icon'] ?>"></i></div>
                            <p class="test-card-title"><?= htmlspecialchars($t['title']) ?></p>
                        </div>
                        <span class="test-section-tag"><?= htmlspecialchars($t['section']) ?></span>
                        <div class="test-meta"><i class="bi bi-clock me-1"></i><?= htmlspecialchars($t['meta']) ?></div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
</body>
</html>
