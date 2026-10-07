<?php
require_once __DIR__ . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) { header("Location: edu_hub_registration.php"); exit(); }
$userName = isset($_SESSION['user_firstname']) ? htmlspecialchars($_SESSION['user_firstname']) : 'Learner';
$studentId = (int) $_SESSION['user_id'];

if (!function_exists('e')) {
    function e($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }
}

// STU-04: only name, initials and target band -- no email or phone. Cohort = any other student
// enrolled in a course the viewer is also enrolled in (prototype's "Your Academic Masterclass
// group" generalised to "every course you share", since a student can be in more than one).
$classmates = executeQuery($db, "
    SELECT DISTINCT s.id, s.firstname, s.lastname, s.target_band,
           (s.last_seen_at IS NOT NULL AND s.last_seen_at > NOW() - INTERVAL 2 MINUTE) AS online
    FROM students s
    JOIN enrollments e ON e.student_id = s.id
    WHERE e.course_id IN (SELECT course_id FROM enrollments WHERE student_id = ?)
      AND s.id != ?
    ORDER BY s.firstname, s.lastname
", [$studentId, $studentId])->fetchAll(PDO::FETCH_ASSOC);

$tints = ['blue', 'green', 'orange', 'purple', 'pink', 'teal', 'yellow'];
$tintHex = ['blue' => '#2F6FE0', 'green' => '#1E9E5A', 'orange' => '#D9771A', 'purple' => '#6D3FE0', 'pink' => '#D6336C', 'teal' => '#0F9488', 'yellow' => '#B7860B'];
$tintBg = ['blue' => '#E3EEFF', 'green' => '#DDF5E6', 'orange' => '#FFE9CC', 'purple' => '#EAE3FF', 'pink' => '#FFE0EA', 'teal' => '#D6F3F0', 'yellow' => '#FFF0C2'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Students - EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <link href="assets/css/dashboard.css" rel="stylesheet">
    <style>
        .classmate-row { display:flex; align-items:center; gap:1rem; background:#fff; border-radius:14px; padding:1rem 1.25rem; margin-bottom:.6rem; box-shadow:0 1px 6px rgba(15,23,42,.06); }
        body.dark .classmate-row { background:#1f1f1f; }
        .classmate-avatar { width:44px; height:44px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; flex-shrink:0; position:relative; }
        .online-dot { position:absolute; right:-2px; bottom:-2px; width:12px; height:12px; border-radius:50%; background:#16a34a; border:2px solid #fff; }
        @media(min-width:1400px){.content{max-width:calc(100vw - 500px);}}
    </style>
</head>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="d-flex">
    <?php include INCLUDES_PATH . '/navbar.php'; ?>
    <div class="main-wrapper flex-grow-1">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>
        <main class="content p-4" style="max-width:720px;">
            <div class="d-flex align-items-center gap-3 mb-4">
                <i class="bi bi-people-fill fs-2 text-primary"></i>
                <div>
                    <h2 class="mb-0">Students</h2>
                    <p class="text-muted mb-0">Your classmates in the courses you're taking</p>
                </div>
            </div>

            <div class="mb-3">
                <input type="text" id="searchBox" class="form-control" placeholder="Search by name" aria-label="Search by name">
            </div>

            <div id="classmateList">
                <?php if (!$classmates): ?>
                    <div class="stat-card text-center py-5">
                        <i class="bi bi-people fs-1 text-muted mb-3 d-block"></i>
                        <h5 class="text-muted">No classmates yet</h5>
                    </div>
                <?php else: ?>
                    <?php foreach ($classmates as $i => $c): $tint = $tints[crc32($c['id']) % count($tints)]; $name = trim($c['firstname'] . ' ' . $c['lastname']); ?>
                        <div class="classmate-row" data-name="<?= e(mb_strtolower($name)) ?>">
                            <div class="classmate-avatar" style="background:<?= $tintBg[$tint] ?>;color:<?= $tintHex[$tint] ?>;">
                                <?= e(mb_substr($c['firstname'], 0, 1) . mb_substr($c['lastname'], 0, 1)) ?>
                                <?php if ($c['online']): ?><span class="online-dot"></span><?php endif; ?>
                            </div>
                            <div>
                                <div class="fw-semibold"><?= e($name) ?></div>
                                <div class="text-muted small">
                                    <?= $c['target_band'] !== null ? 'Target ' . e(number_format((float) $c['target_band'], 1)) : 'Target not set' ?>
                                    <?= $c['online'] ? ' · online now' : '' ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <p id="noMatch" class="text-muted text-center py-4" style="display:none;">No classmates match that name.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>
<?php include INCLUDES_PATH . '/adverts.php'; ?>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
<script>
const searchBox = document.getElementById('searchBox');
if (searchBox) {
    searchBox.addEventListener('input', () => {
        const q = searchBox.value.trim().toLowerCase();
        const rows = document.querySelectorAll('.classmate-row');
        let anyVisible = false;
        rows.forEach(row => {
            const match = row.dataset.name.includes(q);
            row.style.display = match ? '' : 'none';
            if (match) anyVisible = true;
        });
        const noMatch = document.getElementById('noMatch');
        if (noMatch) noMatch.style.display = (q && !anyVisible) ? '' : 'none';
    });
}
</script>
</body>
</html>
