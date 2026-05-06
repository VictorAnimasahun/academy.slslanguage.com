<?php
require_once __DIR__ . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) { header("Location: edu_hub_registration.php"); exit(); }
$user_id = (int)$_SESSION['user_id'];
$userName = isset($_SESSION['user_firstname']) ? htmlspecialchars($_SESSION['user_firstname']) : 'Learner';

$stmt = executeQuery($db,
    "SELECT COUNT(*) as total, SUM(progress_percentage=100) as done,
            COALESCE(AVG(progress_percentage),0) as avg_prog
     FROM enrollments WHERE student_id = ?", [$user_id]);
$stats = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : ['total'=>0,'done'=>0,'avg_prog'=>0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Analytics - EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <link href="assets/css/dashboard.css" rel="stylesheet">
    <style>@media(min-width:1400px){.content{max-width:calc(100vw - 500px);}}</style>
</head>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="d-flex">
    <?php include INCLUDES_PATH . '/navbar.php'; ?>
    <div class="main-wrapper flex-grow-1">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>
        <main class="content p-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <i class="bi bi-bar-chart-fill fs-2 text-primary"></i>
                <div>
                    <h2 class="mb-0">Analytics</h2>
                    <p class="text-muted mb-0">Your learning progress at a glance</p>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-sm-4">
                    <div class="stat-card text-center p-4">
                        <div style="font-size:2.5rem;font-weight:800;color:#0b77ff;">
                            <?= (int)$stats['total'] ?>
                        </div>
                        <div class="text-muted">Courses Enrolled</div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="stat-card text-center p-4">
                        <div style="font-size:2.5rem;font-weight:800;color:#22c55e;">
                            <?= (int)$stats['done'] ?>
                        </div>
                        <div class="text-muted">Completed</div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="stat-card text-center p-4">
                        <div style="font-size:2.5rem;font-weight:800;color:#f59e0b;">
                            <?= round($stats['avg_prog']) ?>%
                        </div>
                        <div class="text-muted">Average Progress</div>
                    </div>
                </div>
            </div>

            <div class="stat-card p-4 text-center">
                <i class="bi bi-graph-up fs-1 text-muted mb-3 d-block"></i>
                <h5 class="text-muted">Detailed Analytics Coming Soon</h5>
                <p class="text-muted">Study time charts, test scores, and streak tracking will appear here.</p>
            </div>
        </main>
    </div>
</div>
<?php include INCLUDES_PATH . '/adverts.php'; ?>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
