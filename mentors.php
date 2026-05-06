<?php
require_once __DIR__ . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) { header("Location: edu_hub_registration.php"); exit(); }
$userName = isset($_SESSION['user_firstname']) ? htmlspecialchars($_SESSION['user_firstname']) : 'Learner';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Mentors - EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <link href="assets/css/dashboard.css" rel="stylesheet">
    <style>
        .mentor-card { background:#fff; border-radius:16px; padding:2rem; text-align:center;
            box-shadow:0 4px 16px rgba(0,0,0,.06); transition:transform .2s; }
        .mentor-card:hover { transform:translateY(-4px); }
        body.dark .mentor-card { background:#1f1f1f; }
        .mentor-avatar { width:72px; height:72px; border-radius:50%;
            background:linear-gradient(135deg,#0b77ff,#7c3aed);
            display:flex; align-items:center; justify-content:center;
            color:#fff; font-size:1.5rem; font-weight:700; margin:0 auto 1rem; }
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
                <i class="bi bi-person-badge-fill fs-2 text-primary"></i>
                <div>
                    <h2 class="mb-0">Mentors</h2>
                    <p class="text-muted mb-0">Meet the coaches guiding your learning journey</p>
                </div>
            </div>
            <div class="stat-card text-center py-5">
                <i class="bi bi-person-badge fs-1 text-muted mb-3 d-block"></i>
                <h5 class="text-muted">Mentor Profiles Coming Soon</h5>
                <p class="text-muted">Coach profiles and booking will be available here shortly.</p>
                <a href="learning_dashboard.php" class="btn btn-primary mt-2">Back to Dashboard</a>
            </div>
        </main>
    </div>
</div>
<?php include INCLUDES_PATH . '/adverts.php'; ?>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
