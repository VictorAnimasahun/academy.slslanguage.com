<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../edu_hub_registration.php?message=Please+login+to+access+mock+tests");
    exit();
}

$mockCode = $_GET['code'] ?? '';
$mock = getMockExam($mockCode);

if (!$mock) {
    die("Mock test not found or is inactive.");
}

$sections = getMockExamSections($mockCode);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($mock['title']) ?> | Mock Test | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>

    <style>
        .main-wrapper { 
            padding: 2rem 1.5rem; 
            min-height: 100vh; 
            background: #f8f9fa; 
        }
        .mock-overview {
            background: white;
            border-radius: 16px;
            padding: 3rem 2.5rem;
            box-shadow: 0 6px 30px rgba(0,0,0,0.1);
            max-width: 950px;
            margin: 0 auto;
        }
        .mock-badge {
            background: linear-gradient(135deg, #10b981, #34d399);
            color: white;
            padding: 0.65rem 2rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.15rem;
        }
        .section-item {
            background: #f8f9fa;
            padding: 1.5rem 1.8rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            transition: all 0.3s;
        }
        .section-item:hover {
            background: #e9f5ee;
            transform: translateX(8px);
        }
        .start-btn {
            padding: 1.1rem 3.8rem;
            font-size: 1.3rem;
            font-weight: 600;
            border-radius: 12px;
        }
        .total-duration {
            font-size: 1.4rem;
            font-weight: 700;
            color: #10b981;
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
                    <li class="breadcrumb-item"><a href="index.php">Mock Tests</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($mock['title']) ?></li>
                </ol>
            </nav>

            <div class="mock-overview">
                <div class="text-center mb-5">
                    <span class="mock-badge"><?= htmlspecialchars($mock['exam_type']) ?> GENERAL TRAINING</span>
                    <h1 class="mt-4 mb-3"><?= htmlspecialchars($mock['title']) ?></h1>
                    <p class="lead text-muted"><?= htmlspecialchars($mock['description']) ?></p>
                </div>

                <div class="text-center mb-5">
                    <div class="total-duration">
                        <i class="bi bi-clock-fill"></i> 
                        Total Time: <?= $mock['total_duration_minutes'] ?> minutes
                    </div>
                </div>

                <h4 class="mb-4 text-center">Test Sections</h4>

                <?php if (empty($sections)): ?>
                    <div class="alert alert-warning text-center">No sections configured for this mock yet.</div>
                <?php else: ?>
                    <?php foreach ($sections as $sec): ?>
                        <div class="section-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi <?= getSectionIcon($sec['section_type']) ?> fs-3 text-success"></i>
                                <div>
                                    <strong><?= htmlspecialchars(getSectionDisplayName($sec['section_type'])) ?></strong><br>
                                    <small class="text-muted">
                                        <?= htmlspecialchars($sec['section_title']) ?> • 
                                        <?= $sec['duration_minutes'] ?> minutes
                                        <?php if ($sec['total_questions'] > 0): ?>
                                            • <?= $sec['total_questions'] ?> questions
                                        <?php endif; ?>
                                    </small>
                                </div>
                            </div>
                            <span class="badge bg-secondary px-3 py-2"><?= $sec['section_order'] ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div class="text-center mt-5 pt-4 border-top">
                    <button onclick="startFullMock()" class="btn btn-success start-btn">
                        <i class="bi bi-play-circle-fill me-2"></i> 
                        Start Full Mock Test
                    </button>
                </div>

                <p class="text-center text-muted mt-4 small">
                    This is a timed full mock. Once started, you should complete all sections under real exam conditions.
                </p>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>

    <script>
    function startFullMock() {
        if (confirm("Are you ready to start the full mock test?\n\nTotal duration: <?= $mock['total_duration_minutes'] ?> minutes\n\nYou cannot pause once started.")) {
            alert("✅ Full mock test starting...\n\n(We will build the actual multi-section interface in the next step)");
            // Future line:
            // window.location.href = 'start.php?code=<?= urlencode($mockCode) ?>';
        }
    }
    </script>
</body>
</html>