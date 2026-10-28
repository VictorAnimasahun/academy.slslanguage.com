<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

$test_num = isset($_GET['test']) ? intval($_GET['test']) : 0;
if ($test_num < 1 || $test_num > 5) {
    header("Location: model_answers.php");
    exit();
}

require_once __DIR__ . '/_writing_tests_data.php';
$all_tests = writingTestsData();

$test = $all_tests[$test_num];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Writing — <?php echo $test['label']; ?> | EduHub</title>
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

        .task-card {
            background: #fff; border-radius: 3px;
            box-shadow: 0 2px 10px rgba(15,23,42,0.07);
            margin-bottom: 1.5rem; overflow: hidden; border:1px solid rgba(15,23,42,.12);
        }
        .task-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 1.4rem;
            color: #fff;
        }
        .task-header.blue { background: #0b77ff; }
        .task-header.pink { background: #ec4899; }
        .task-header-left { display: flex; align-items: center; gap: 0.75rem; }
        .task-badge {
            width: 30px; height: 30px; border-radius: 50%;
            background: rgba(255,255,255,0.25);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.85rem; flex-shrink: 0;
        }
        .task-title { font-weight: 600; font-size: 1rem; }
        .task-type  { font-size: 0.78rem; opacity: 0.85; margin-top: 0.1rem; }
        .band-pill {
            background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.4);
            border-radius: 3px; padding: 0.2rem 0.75rem;
            font-weight: 700; font-size: 0.9rem; white-space: nowrap;
        }

        .task-body { padding: 1.4rem; }
        .question-label {
            font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.07em; color: #94a3b8; margin-bottom: 0.6rem;
        }
        .question-text {
            white-space: pre-line; line-height: 1.65; color: #334155;
            font-size: 0.93rem; margin-bottom: 1.2rem;
        }
        .btn-view-blue {
            display: inline-flex; align-items: center; gap: 0.4rem;
            background: #0b77ff; color: #fff; border: none;
            padding: 0.55rem 1.2rem; border-radius: 3px;
            font-weight: 600; font-size: 0.88rem; cursor: pointer;
            text-decoration: none; transition: background 0.2s;
        }
        .btn-view-blue:hover { background: #0062d6; color: #fff; }
        .btn-view-pink {
            display: inline-flex; align-items: center; gap: 0.4rem;
            background: #ec4899; color: #fff; border: none;
            padding: 0.55rem 1.2rem; border-radius: 3px;
            font-weight: 600; font-size: 0.88rem; cursor: pointer;
            text-decoration: none; transition: background 0.2s;
        }
        .btn-view-pink:hover { background: #db2777; color: #fff; }

        .modal-passage { white-space: pre-line; line-height: 1.65; margin-bottom: 0; }
        .modal-section-label {
            font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.07em; color: #94a3b8; margin-bottom: 0.4rem;
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
            <div>

                <a href="model_answers.php" class="back-link">
                    <i class="bi bi-arrow-left"></i> Model Answers
                </a>

                <h1 style="font-size:1.5rem;font-weight:700;margin-bottom:0.2rem;">
                    IELTS Writing — <?php echo htmlspecialchars($test['label']); ?>
                </h1>
                <p class="text-muted mb-4" style="font-size:0.9rem;"><?= isset($test['source_note']) ? htmlspecialchars($test['source_note']) : 'Cambridge IELTS 16 General Training · Sample answers with examiner feedback' ?></p>

                <?php
                $colors = ['blue', 'pink'];
                foreach ($test['tasks'] as $i => $task):
                    $color = $colors[$i % 2];
                    $btn_class = 'btn-view-' . $color;
                ?>
                    <div class="task-card">
                        <div class="task-header <?php echo $color; ?>">
                            <div class="task-header-left">
                                <div class="task-badge"><?php echo $task['task_num']; ?></div>
                                <div>
                                    <div class="task-title"><?php echo htmlspecialchars($task['title']); ?></div>
                                    <div class="task-type"><?php echo htmlspecialchars($task['type']); ?></div>
                                </div>
                            </div>
                            <div class="band-pill">Band <?php echo htmlspecialchars($task['band']); ?></div>
                        </div>
                        <div class="task-body">
                            <div class="question-label">Question</div>
                            <div class="question-text"><?php echo htmlspecialchars($task['question']); ?></div>
                            <button class="<?php echo $btn_class; ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-<?php echo $task['id']; ?>">
                                <i class="bi bi-file-text"></i> Read Sample Answer
                            </button>
                        </div>
                    </div>

                    <div class="modal fade" id="modal-<?php echo $task['id']; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header" style="background:<?php echo $color === 'blue' ? '#0b77ff' : '#ec4899'; ?>;color:#fff;">
                                    <h5 class="modal-title">
                                        <?php echo htmlspecialchars($task['title']); ?>
                                        &nbsp;<span style="opacity:0.85;font-size:0.9rem;">Band <?php echo htmlspecialchars($task['band']); ?></span>
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="font-size:0.93rem;">
                                    <div class="modal-section-label">Sample Answer</div>
                                    <p class="modal-passage"><?php echo htmlspecialchars($task['answer']); ?></p>
                                    <hr>
                                    <div class="modal-section-label"><?= htmlspecialchars($task['comment_label'] ?? "Examiner's Comment") ?></div>
                                    <p class="modal-passage"><?php echo htmlspecialchars($task['comment']); ?></p>
                                </div>
                            </div>
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
