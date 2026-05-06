<?php
require_once __DIR__ . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) { header("Location: edu_hub_registration.php"); exit(); }
$user_id = (int)$_SESSION['user_id'];
$userName = isset($_SESSION['user_firstname']) ? htmlspecialchars($_SESSION['user_firstname']) : 'Learner';

$assignmentsSQL = "SELECT a.*, c.title AS course_title
    FROM assignments a
    JOIN courses c ON a.course_id = c.id
    JOIN enrollments e ON e.course_id = c.id
    WHERE e.student_id = ?
    ORDER BY a.due_date ASC";
$stmt = executeQuery($db, $assignmentsSQL, [$user_id]);
$assignments = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Assignments - EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <link href="assets/css/dashboard.css" rel="stylesheet">
    <style>
        .assignment-card { background:#fff; border-radius:12px; padding:1.5rem; margin-bottom:1rem;
            box-shadow:0 1px 3px rgba(0,0,0,.08); border-left:4px solid #0b77ff; }
        body.dark .assignment-card { background:#1f1f1f; }
        .due-badge { font-size:.78rem; }
        @media(min-width:1400px){.content{max-width:calc(100vw - 500px);}}
    </style>
</head>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="d-flex">
    <?php include INCLUDES_PATH . '/navbar.php'; ?>
    <div class="main-wrapper flex-grow-1">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>
        <main class="content p-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <i class="bi bi-stickies-fill fs-2 text-primary"></i>
                <div>
                    <h2 class="mb-0">Assignments</h2>
                    <p class="text-muted mb-0">Your pending and completed assignments</p>
                </div>
            </div>

            <?php if (empty($assignments)): ?>
                <div class="stat-card text-center py-5">
                    <i class="bi bi-stickies fs-1 text-muted mb-3 d-block"></i>
                    <h5 class="text-muted">No assignments yet</h5>
                    <p class="text-muted">Enrol in a course to receive assignments.</p>
                    <a href="courses/courses_catalogue.php" class="btn btn-primary mt-2">Browse Courses</a>
                </div>
            <?php else: ?>
                <?php foreach ($assignments as $a): ?>
                    <div class="assignment-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1"><?= htmlspecialchars($a['title']) ?></h6>
                                <small class="text-muted"><?= htmlspecialchars($a['course_title']) ?></small>
                            </div>
                            <?php if (!empty($a['due_date'])): ?>
                                <span class="badge bg-warning text-dark due-badge">
                                    Due <?= date('M j, Y', strtotime($a['due_date'])) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($a['description'])): ?>
                            <p class="text-muted mt-2 mb-0" style="font-size:.9rem;">
                                <?= htmlspecialchars(mb_substr($a['description'], 0, 160)) ?>…
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </main>
    </div>
</div>
<?php include INCLUDES_PATH . '/adverts.php'; ?>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
