<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

// Real transcript from official IELTS.org Speaking sample task (2026-09-17).
// Speaking is content-neutral between Academic and General Training.
// Extracted to _speaking_1_data.php (2026-10-14) so the mobile API shares this exact content.
require_once __DIR__ . '/_speaking_1_data.php';
$parts = speaking1Data();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Speaking — Model Answer | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .back-link {
            display: inline-flex; align-items: center; gap: 0.4rem;
            font-size: 0.85rem; color: #0b77ff; text-decoration: none;
            margin-bottom: 1.5rem;
        }
        .back-link:hover { text-decoration: underline; color: #0b77ff; }
        .part-card {
            background: #fff; border-radius: 14px;
            box-shadow: 0 2px 10px rgba(15,23,42,0.07);
            margin-bottom: 1.5rem; overflow: hidden;
        }
        .part-header {
            padding: 1rem 1.4rem; color: #fff; background: #10b981;
            font-weight: 600; font-size: 1rem;
        }
        .part-body { padding: 1.4rem; }
        .cue-card {
            background: linear-gradient(135deg,#eff6ff,#dbeafe); border-radius: 12px;
            padding: 1.2rem; font-size: 0.93rem; line-height: 1.7; white-space: pre-line;
            border: 1px solid #bfdbfe; margin-bottom: 1.2rem;
        }
        .exchange { margin-bottom: 1.1rem; }
        .exchange:last-child { margin-bottom: 0; }
        .q-label, .a-label {
            font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.06em; margin-bottom: 0.2rem;
        }
        .q-label { color: #0b77ff; }
        .a-label { color: #10b981; }
        .q-text { font-weight: 600; color: #1e293b; font-size: 0.93rem; margin-bottom: 0.5rem; }
        .a-text { color: #334155; font-size: 0.93rem; line-height: 1.6; background:#f8fafc; border-radius:8px; padding:0.75rem 1rem; }
    </style>
</head>
<body class="light">
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <div class="main-wrapper flex-grow-1" style="flex:1;">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>

        <main class="content p-4">
            <div style="max-width:720px;">

                <a href="model_answers.php" class="back-link">
                    <i class="bi bi-arrow-left"></i> Model Answers
                </a>

                <h1 style="font-size:1.5rem;font-weight:700;margin-bottom:0.2rem;">IELTS Speaking — Test 1</h1>
                <p class="text-muted mb-4" style="font-size:0.9rem;">Official IELTS sample-task transcript · Valid for both Academic and General Training</p>

                <?php foreach ($parts as $pNum => $part): ?>
                <div class="part-card">
                    <div class="part-header"><?= htmlspecialchars($part['label']) ?></div>
                    <div class="part-body">
                        <?php if (!empty($part['cue_card'])): ?>
                        <div class="cue-card"><?= htmlspecialchars($part['cue_card']) ?></div>
                        <?php endif; ?>
                        <?php foreach ($part['exchanges'] as $ex): ?>
                        <div class="exchange">
                            <div class="q-label">Examiner</div>
                            <div class="q-text"><?= htmlspecialchars($ex['q']) ?></div>
                            <div class="a-label">Candidate</div>
                            <div class="a-text"><?= htmlspecialchars($ex['a']) ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </main>
    </div>

    <?php include INCLUDES_PATH . '/adverts.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
