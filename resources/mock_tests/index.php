<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+mock+tests");
    exit();
}

$mockExams = [];
$errorMsg = '';

try {
    // Check if mock_exams table exists first (safe query)
    $checkStmt = $db->prepare("SHOW TABLES LIKE 'mock_exams'");
    $checkStmt->execute();
    $tableExists = $checkStmt->fetchColumn();

    if ($tableExists) {
        $stmt = $db->prepare("
            SELECT * FROM mock_exams 
            WHERE is_active = 1 
            ORDER BY exam_type ASC, title ASC
        ");
        $stmt->execute();
        $mockExams = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $errorMsg = "Mock exams tables have not been created yet.";
    }
} catch (PDOException $e) {
    $errorMsg = "Database error: " . $e->getMessage();
}

// Group by exam_type
$groupedMocks = [];
foreach ($mockExams as $mock) {
    $type = $mock['exam_type'] ?? 'Other';
    $groupedMocks[$type][] = $mock;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mock Tests | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    
    <style>
        .main-wrapper { padding: 2rem 1.5rem; min-height: 100vh; }
        .mock-card {
            background: white;
            border-radius: 16px;
            padding: 1.75rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border-left: 5px solid #10b981;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .mock-card:hover {
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        .mock-title { font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; }
        .mock-badge {
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            background: linear-gradient(135deg, #10b981, #34d399);
            color: white;
        }
        .section-title {
            margin: 2.5rem 0 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 3px solid #e5e7eb;
            font-weight: 600;
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
                    <li class="breadcrumb-item"><a href="../resources_home.php">Resources</a></li>
                    <li class="breadcrumb-item active">Mock Tests</li>
                </ol>
            </nav>

            <h1 class="display-5 mb-2">Mock Tests</h1>
            <p class="text-muted mb-5">Full-length simulated exams under real test conditions.</p>

            <?php if ($errorMsg): ?>
                <div class="alert alert-warning py-4">
                    <strong>Setup Required:</strong><br>
                    <?= htmlspecialchars($errorMsg) ?><br><br>
                    Please run the CREATE TABLE statements for <code>mock_exams</code> and <code>mock_exam_sections</code>.
                </div>
            <?php elseif (empty($mockExams)): ?>
                <div class="alert alert-info py-4 text-center">
                    <i class="bi bi-info-circle fs-4 mb-3 d-block"></i>
                    No mock tests have been added yet.<br>
                    Add some data to the <code>mock_exams</code> table to get started.
                </div>
            <?php else: ?>
                <?php foreach ($groupedMocks as $examType => $mocks): ?>
                    <h3 class="section-title"><?= htmlspecialchars($examType) ?> Mock Exams</h3>
                    
                    <?php foreach ($mocks as $mock): ?>
                        <div class="mock-card" onclick="window.location.href='take.php?code=<?= urlencode($mock['code']) ?>'">
                            <div class="d-flex justify-content-between align-items-start">
                                <h4 class="mock-title"><?= htmlspecialchars($mock['title']) ?></h4>
                                <span class="mock-badge"><?= htmlspecialchars($mock['exam_type']) ?></span>
                            </div>
                            <p class="text-muted mb-3"><?= htmlspecialchars($mock['description'] ?? 'Complete full-length mock test') ?></p>
                            <div class="text-muted">
                                <i class="bi bi-clock"></i> <?= (int)$mock['total_duration_minutes'] ?> minutes
                                &nbsp;&nbsp; <i class="bi bi-tag"></i> <?= htmlspecialchars($mock['code']) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
</body>
</html>