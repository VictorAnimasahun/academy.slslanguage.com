<?php
require_once __DIR__ . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) { header("Location: edu_hub_registration.php"); exit(); }
$userName = isset($_SESSION['user_firstname']) ? htmlspecialchars($_SESSION['user_firstname']) : 'Learner';
$studentId = (int) $_SESSION['user_id'];

if (!function_exists('e')) {
    function e($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }
}

// One open (requested or confirmed) request per student per mentor is enough to show a pill;
// MEN-01/MBK-03: tapping a mentor with no open request opens the booking screen, otherwise the
// list shows the pill and the mentor row still opens the booking screen to manage it.
$mentors = executeQuery($db, "
    SELECT m.id, m.display_name, m.focus_area, m.bio, m.tint,
           r.id AS request_id, r.status AS request_status, r.preferred_at
    FROM mentors m
    LEFT JOIN mentor_requests r
        ON r.mentor_id = m.id AND r.student_id = ? AND r.status IN ('requested','confirmed')
    WHERE m.is_active = 1
    ORDER BY m.display_name
", [$studentId])->fetchAll(PDO::FETCH_ASSOC);
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
        .mentor-pill { display:inline-block; padding:.3rem .9rem; border-radius:20px; font-size:.8rem; font-weight:700; margin-top:.75rem; }
        .pill-requested { background:#fef3c7; color:#92400e; }
        .pill-confirmed { background:#dcfce7; color:#166534; }
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

            <?php if (!$mentors): ?>
                <div class="stat-card text-center py-5">
                    <i class="bi bi-person-badge fs-1 text-muted mb-3 d-block"></i>
                    <h5 class="text-muted">No mentors available right now</h5>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($mentors as $m): ?>
                        <div class="col-md-4">
                            <a href="mentor_book.php?id=<?= (int) $m['id'] ?>" class="text-decoration-none text-reset">
                                <div class="mentor-card">
                                    <div class="mentor-avatar"><?= e(mb_substr($m['display_name'], 0, 1)) ?></div>
                                    <h5 class="mb-1"><?= e($m['display_name']) ?></h5>
                                    <p class="text-muted mb-0"><?= e($m['focus_area']) ?></p>
                                    <?php if ($m['request_status'] === 'confirmed'): ?>
                                        <span class="mentor-pill pill-confirmed"><i class="bi bi-check-circle-fill"></i> Confirmed</span>
                                    <?php elseif ($m['request_status'] === 'requested'): ?>
                                        <span class="mentor-pill pill-requested"><i class="bi bi-clock-fill"></i> Requested</span>
                                    <?php else: ?>
                                        <span class="mentor-pill" style="background:#e0e7ff;color:#3730a3;">Book time</span>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>
<?php include INCLUDES_PATH . '/adverts.php'; ?>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
