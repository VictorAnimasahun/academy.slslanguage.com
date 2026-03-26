<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
require_once 'config.php';
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

// Get all tests grouped by type
$ieltsAcademicWriting = getTestsByType('IELTS Academic Writing');
$ieltsGeneralWriting = getTestsByType('IELTS General Writing');
$ieltsAcademicSpeaking = getTestsByType('IELTS Academic Speaking');
$ieltsGeneralSpeaking = getTestsByType('IELTS General Speaking');

// Temporary debug
echo "<pre>";
echo "IELTS Academic Writing count: " . count($ieltsAcademicWriting) . "\n";
print_r($ieltsAcademicWriting);
echo "</pre>";
die();
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
        .main-wrapper {
            padding: 2rem 1.5rem;
            min-height: 100vh;
        }

        .test-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 4px solid #3b82f6;
            transition: all 0.3s;
            cursor: pointer;
        }

        .test-card:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .test-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .test-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        .test-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .test-meta {
            display: flex;
            gap: 1.5rem;
            font-size: 0.9rem;
            color: #6b7280;
            margin-bottom: 0.75rem;
        }

        .test-meta span {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .section-title {
            margin-top: 2rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
        }
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
                    <li class="breadcrumb-item"><a href="../resources.php">Resources</a></li>
                    <li class="breadcrumb-item active">Practice Tests</li>
                </ol>
            </nav>

            <h1 class="display-5 mb-2">Practice Tests</h1>
            <p class="text-muted mb-4">Choose a test and start practicing</p>

            <!-- IELTS Academic Writing -->
            <?php if (!empty($ieltsAcademicWriting)): ?>
            <h3 class="section-title">IELTS Academic Writing</h3>
            <?php foreach ($ieltsAcademicWriting as $test): ?>
            <div class="test-card" onclick="window.location.href='writing_template.php?code=<?= $test['code'] ?>'">
                <div class="test-card-header">
                    <h4 class="test-title"><?= htmlspecialchars($test['title']) ?></h4>
                    <span class="test-badge bg-primary text-white"><?= htmlspecialchars($test['task']) ?></span>
                </div>
                <p class="text-muted mb-2"><?= htmlspecialchars($test['description']) ?></p>
                <div class="test-meta">
                    <span><i class="bi bi-clock"></i> <?= $test['time_limit'] ?> min</span>
                    <span><i class="bi bi-file-text"></i> <?= $test['word_target'] ?> words</span>
                    <span><i class="bi bi-tag"></i> <?= htmlspecialchars($test['category']) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>

            <!-- IELTS General Writing -->
            <?php if (!empty($ieltsGeneralWriting)): ?>
            <h3 class="section-title">IELTS General Writing</h3>
            <?php foreach ($ieltsGeneralWriting as $test): ?>
            <div class="test-card" onclick="window.location.href='writing_template.php?code=<?= $test['code'] ?>'">
                <div class="test-card-header">
                    <h4 class="test-title"><?= htmlspecialchars($test['title']) ?></h4>
                    <span class="test-badge bg-success text-white"><?= htmlspecialchars($test['task']) ?></span>
                </div>
                <p class="text-muted mb-2"><?= htmlspecialchars($test['description']) ?></p>
                <div class="test-meta">
                    <span><i class="bi bi-clock"></i> <?= $test['time_limit'] ?> min</span>
                    <span><i class="bi bi-file-text"></i> <?= $test['word_target'] ?> words</span>
                    <span><i class="bi bi-tag"></i> <?= htmlspecialchars($test['category']) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>

            <!-- IELTS Academic Speaking -->
            <?php if (!empty($ieltsAcademicSpeaking)): ?>
            <h3 class="section-title">IELTS Academic Speaking</h3>
            <?php foreach ($ieltsAcademicSpeaking as $test): ?>
            <div class="test-card" onclick="window.location.href='speaking_template.php?code=<?= $test['code'] ?>'">
                <div class="test-card-header">
                    <h4 class="test-title"><?= htmlspecialchars($test['title']) ?></h4>
                    <span class="test-badge bg-warning text-dark"><?= htmlspecialchars($test['task']) ?></span>
                </div>
                <p class="text-muted mb-2"><?= htmlspecialchars($test['description']) ?></p>
                <div class="test-meta">
                    <span><i class="bi bi-mic"></i> <?= $test['time_limit'] ?> min</span>
                    <span><i class="bi bi-tag"></i> <?= htmlspecialchars($test['category']) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
</body>
</html>