<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

$ielts_tests = [
    1 => ['task1' => 'Task 1: Letter · Band 5.5', 'task2' => 'Task 2: Essay · Band 7.0'],
    2 => ['task1' => 'Task 1: Letter · Band 6.5', 'task2' => 'Task 2: Essay · Band 5.5'],
    3 => ['task1' => 'Task 1: Letter · Band 7.0', 'task2' => 'Task 2: Essay · Band 6.0'],
    4 => ['task1' => 'Task 1: Email · Band 5.0',  'task2' => 'Task 2: Essay · Band 7.0'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Model Answers | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .section-label {
            font-size: 0.78rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.07em; color: #64748b; margin: 2rem 0 0.75rem;
        }
        .test-row {
            display: flex; align-items: center; gap: 1rem;
            background: #fff; border-radius: 12px;
            padding: 1rem 1.2rem; margin-bottom: 0.6rem;
            box-shadow: 0 1px 6px rgba(15,23,42,0.06);
            text-decoration: none; color: inherit;
            border-left: 4px solid #0b77ff;
            transition: box-shadow 0.2s, border-color 0.2s;
        }
        .test-row:hover { box-shadow: 0 4px 16px rgba(11,119,255,0.14); color: inherit; }
        .test-num {
            width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
            background: #0b77ff; color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.9rem;
        }
        .test-title { font-weight: 600; font-size: 0.97rem; color: #1e293b; }
        .test-meta  { font-size: 0.8rem; color: #64748b; margin-top: 0.1rem; }
        .test-arrow { margin-left: auto; color: #0b77ff; }
        .placeholder-row {
            display: flex; align-items: center; gap: 1rem;
            background: #f8fafc; border-radius: 12px;
            padding: 1rem 1.2rem; margin-bottom: 0.6rem;
            border-left: 4px solid #e2e8f0; color: #94a3b8;
        }
        .placeholder-icon {
            width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
            background: #e2e8f0;
            display: flex; align-items: center; justify-content: center;
        }
    </style>
</head>
<body class="light">
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <div class="main-wrapper flex-grow-1" style="flex:1;">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>

        <main class="content p-4">
            <div style="max-width:680px;">

                <h1 style="font-size:1.5rem;font-weight:700;margin-bottom:0.25rem;">Model Answers</h1>
                <p class="text-muted mb-2">Sample writing responses with band scores and examiner feedback.</p>

                <div class="section-label"><i class="bi bi-book me-1"></i>IELTS — Writing (Full Mock Tests)</div>

                <?php foreach ($ielts_tests as $num => $test): ?>
                    <a href="model_answers_test.php?test=<?php echo $num; ?>" class="test-row">
                        <div class="test-num"><?php echo $num; ?></div>
                        <div>
                            <div class="test-title">Test <?php echo $num; ?></div>
                            <div class="test-meta"><?php echo $test['task1']; ?> &nbsp;&middot;&nbsp; <?php echo $test['task2']; ?></div>
                        </div>
                        <i class="bi bi-chevron-right test-arrow"></i>
                    </a>
                <?php endforeach; ?>

                <div class="section-label mt-3"><i class="bi bi-person-check me-1"></i>CELPIP</div>
                <div class="placeholder-row">
                    <div class="placeholder-icon"><i class="bi bi-lock"></i></div>
                    <span>Coming soon</span>
                </div>

                <div class="section-label mt-2"><i class="bi bi-mortarboard me-1"></i>PTE</div>
                <div class="placeholder-row">
                    <div class="placeholder-icon"><i class="bi bi-lock"></i></div>
                    <span>Coming soon</span>
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
