<?php
/**
 * Full per-course schedule — every test/quiz/task assignment for one
 * course, in due-date order, with each item's due date already computed
 * from THIS student's own enrollment date (see includes/course_pacing.php).
 * Sibling of assignments.php (which lists across all enrolled courses);
 * this is the single-course "syllabus" view.
 */
require_once __DIR__ . '/../bootstrap.php';
require_once INCLUDES_PATH . '/assignment_helpers.php';

if (!isset($_SESSION['user_id'])) { header("Location: ../edu_hub_registration.php"); exit(); }

$user_id   = (int) $_SESSION['user_id'];
$course_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if (!$course_id) { header("Location: courses_catalogue.php"); exit(); }

$stmt = $db->prepare("SELECT c.*, e.enrolled_at, e.progress_percentage
    FROM courses c
    JOIN enrollments e ON e.course_id = c.id AND e.student_id = ?
    WHERE c.id = ?");
$stmt->execute([$user_id, $course_id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) {
    // Not enrolled (or course doesn't exist) — send to the detail/enroll page.
    header("Location: courses_detail.php?id={$course_id}");
    exit();
}

$sql = "
    SELECT
        a.id, a.test_id, a.type, a.title, a.description, a.due_date,
        t.title AS test_title, t.code AS test_code, t.test_type,
        vw.id AS vocab_word_id,
        latest.score AS attempt_score, latest.max_score AS attempt_max,
        latest.band_score AS attempt_band, latest.status AS attempt_status,
        latest.completed_at AS attempt_date
    FROM assignments a
    LEFT JOIN tests t ON t.id = a.test_id
    LEFT JOIN vocabulary_words vw
        ON t.test_type = 'Vocabulary'
        AND vw.sort_order = CAST(SUBSTRING(t.code, 12) AS UNSIGNED)
    LEFT JOIN (
        SELECT ta1.test_id, ta1.score, ta1.max_score, ta1.band_score, ta1.status, ta1.completed_at
        FROM test_attempts ta1
        JOIN (SELECT test_id, MAX(id) AS max_id FROM test_attempts WHERE student_id = ? GROUP BY test_id) ta2
            ON ta1.id = ta2.max_id
    ) latest ON latest.test_id = a.test_id
    WHERE a.course_id = ? AND (a.student_id IS NULL OR a.student_id = ?)
    ORDER BY CASE WHEN a.due_date IS NULL THEN 1 ELSE 0 END, a.due_date ASC, a.created_at ASC
";
$stmt = executeQuery($db, $sql, [$user_id, $course_id, $user_id]);
$items = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];

$today       = date('Y-m-d');
$enrolledAt  = $course['enrolled_at'] ? date('Y-m-d', strtotime($course['enrolled_at'])) : $today;
$completedN  = 0;
$overdueN    = 0;

// Group by "week since enrollment" (ceil, 1-indexed) rather than calendar
// month — every student's schedule is anchored to their own enrollment
// date, so a calendar grouping would mean nothing (see includes/course_pacing.php).
$weeks = [];
foreach ($items as $it) {
    $completed = $it['attempt_status'] === 'completed';
    if ($completed) $completedN++;
    if (!$completed && !empty($it['due_date']) && $it['due_date'] < $today) $overdueN++;

    if (!empty($it['due_date'])) {
        $daysSince = (int) floor((strtotime($it['due_date']) - strtotime($enrolledAt)) / 86400);
        $weekNum   = intdiv(max($daysSince, 0), 7) + 1;
    } else {
        $weekNum = 0; // "No due date" bucket
    }
    $weeks[$weekNum][] = $it;
}
ksort($weeks);

$totalN = count($items);
$pct    = $totalN > 0 ? round(($completedN / $totalN) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Schedule — <?= htmlspecialchars($course['title']) ?> | EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .sched-header { background:linear-gradient(135deg,#0b77ff,#6366f1); border-radius:18px; padding:1.75rem 2rem; color:#fff; margin-bottom:1.5rem; }
        .sched-header h1 { font-size:1.4rem; font-weight:800; margin-bottom:.3rem; }
        .sched-header p { opacity:.85; margin:0; font-size:.88rem; }
        .sched-stats { display:flex; gap:1.75rem; margin-top:1.1rem; flex-wrap:wrap; }
        .sched-stat-num { font-size:1.5rem; font-weight:800; line-height:1; }
        .sched-stat-label { font-size:.72rem; opacity:.8; text-transform:uppercase; letter-spacing:.05em; margin-top:.15rem; }
        .sched-progress { height:8px; background:rgba(255,255,255,.25); border-radius:4px; margin-top:1rem; overflow:hidden; }
        .sched-progress-bar { height:100%; background:#fff; border-radius:4px; }

        .week-group { margin-bottom:1.75rem; }
        .week-title { font-size:.78rem; font-weight:800; text-transform:uppercase; letter-spacing:.06em; color:#64748b; margin-bottom:.7rem; padding-left:.2rem; }

        .sched-card { background:#fff; border-radius:14px; box-shadow:0 2px 10px rgba(0,0,0,.05); padding:1.1rem 1.3rem; margin-bottom:.75rem; border-left:4px solid #0b77ff; }
        .sched-card.completed { border-left-color:#10b981; }
        .sched-card.overdue   { border-left-color:#ef4444; }
        .due-badge { display:inline-block; padding:.2rem .65rem; border-radius:999px; font-size:.72rem; font-weight:700; }
        .due-upcoming { background:#fef3c7; color:#92400e; }
        .due-overdue  { background:#fee2e2; color:#b91c1c; }
        .due-none     { background:#f3f4f6; color:#6b7280; }
    </style>
</head>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="mobile-overlay" id="mobileOverlay"></div>
<?php include INCLUDES_PATH . '/navbar.php'; ?>
<div class="main-wrapper flex-grow-1" style="flex:1;">
    <?php include INCLUDES_PATH . '/topbar.php'; ?>
    <main class="content p-4">

        <a href="courses_detail.php?id=<?= $course_id ?>" style="font-size:.85rem;color:#64748b;text-decoration:none;" class="d-inline-flex align-items-center gap-1 mb-3">
            <i class="bi bi-chevron-left"></i> Back to course
        </a>

        <div class="sched-header">
            <h1><i class="bi bi-calendar-check me-2"></i><?= htmlspecialchars($course['title']) ?> — Schedule</h1>
            <p>Every test, quiz and task for this course, due dates calculated from when you enrolled (<?= date('M j, Y', strtotime($enrolledAt)) ?>).</p>
            <div class="sched-stats">
                <div><div class="sched-stat-num"><?= $totalN ?></div><div class="sched-stat-label">Total items</div></div>
                <div><div class="sched-stat-num"><?= $completedN ?></div><div class="sched-stat-label">Completed</div></div>
                <div><div class="sched-stat-num"><?= $overdueN ?></div><div class="sched-stat-label">Overdue</div></div>
                <div><div class="sched-stat-num"><?= $pct ?>%</div><div class="sched-stat-label">Progress</div></div>
            </div>
            <?php if ($totalN > 0): ?>
            <div class="sched-progress"><div class="sched-progress-bar" style="width:<?= $pct ?>%"></div></div>
            <?php endif; ?>
        </div>

        <?php if (empty($items)): ?>
        <div class="text-center py-5" style="background:#fff;border-radius:16px;box-shadow:0 2px 10px rgba(0,0,0,.05);">
            <i class="bi bi-calendar-x fs-1 text-muted mb-3 d-block"></i>
            <h5 class="text-muted">No scheduled items yet</h5>
            <p class="text-muted small">This course doesn't have a pacing schedule configured yet.</p>
        </div>
        <?php else: ?>

        <?php foreach ($weeks as $weekNum => $weekItems): ?>
        <div class="week-group">
            <div class="week-title"><?= $weekNum === 0 ? 'No due date' : 'Week ' . $weekNum ?></div>
            <?php foreach ($weekItems as $a):
                $completed = $a['attempt_status'] === 'completed';
                $overdue   = !$completed && !empty($a['due_date']) && $a['due_date'] < $today;
                $cardClass = $completed ? 'completed' : ($overdue ? 'overdue' : '');
                $displayTitle = !empty($a['title']) ? $a['title'] : ($a['test_title'] ?? 'Assignment');
                $url = assignmentUrl($a, $SECTION_FILE_MAP);

                if (empty($a['due_date'])) {
                    $dueBadge = "<span class='due-badge due-none'>No due date</span>";
                } elseif ($overdue) {
                    $dueBadge = "<span class='due-badge due-overdue'><i class='bi bi-exclamation-circle me-1'></i>Overdue · " . date('M j', strtotime($a['due_date'])) . "</span>";
                } else {
                    $dueBadge = "<span class='due-badge due-upcoming'>Due " . date('D, M j', strtotime($a['due_date'])) . "</span>";
                }

                $scoreStr = '';
                if ($completed && $a['attempt_score'] !== null) {
                    $pctScore = $a['attempt_max'] > 0 ? round(($a['attempt_score'] / $a['attempt_max']) * 100) : 0;
                    $scoreStr = (int)$a['attempt_score'] . '/' . (int)$a['attempt_max'] . ' (' . $pctScore . '%)';
                    if ($a['attempt_band']) $scoreStr .= ' · Band ' . number_format((float)$a['attempt_band'], 1);
                }
            ?>
            <div class="sched-card <?= $cardClass ?>">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div style="flex:1;min-width:0;">
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <?= typeBadge($a['type']) ?>
                            <?= $dueBadge ?>
                        </div>
                        <h6 class="mb-0 fw-bold" style="font-size:.93rem;"><?= htmlspecialchars($displayTitle) ?></h6>
                        <?php if ($scoreStr): ?><div class="text-muted mt-1" style="font-size:.78rem;"><?= $scoreStr ?></div><?php endif; ?>
                    </div>
                    <div style="flex-shrink:0;">
                        <?php if ($completed): ?>
                            <span style="display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .75rem;border-radius:999px;background:#dcfce7;color:#15803d;font-size:.75rem;font-weight:700;"><i class="bi bi-check-circle-fill"></i> Done</span>
                        <?php elseif ($url): ?>
                            <a href="<?= ACADEMY_URL . $url ?>" class="btn btn-sm btn-primary" style="font-size:.78rem;">Start</a>
                        <?php else: ?>
                            <span style="display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .75rem;border-radius:999px;background:#f3f4f6;color:#6b7280;font-size:.75rem;font-weight:700;"><i class="bi bi-hourglass-split"></i> Not yet available</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>

        <?php endif; ?>
    </main>
</div>
<?php include INCLUDES_PATH . '/adverts.php'; ?>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
