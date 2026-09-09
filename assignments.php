<?php
require_once __DIR__ . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) { header("Location: edu_hub_registration.php"); exit(); }
$user_id  = (int)$_SESSION['user_id'];
$userName = isset($_SESSION['user_firstname']) ? htmlspecialchars($_SESSION['user_firstname']) : 'Learner';

$sql = "
    SELECT
        a.id, a.test_id, a.type, a.title, a.description, a.due_date,
        c.title  AS course_title,
        t.title  AS test_title,
        t.code   AS test_code,
        t.test_type,
        vw.id    AS vocab_word_id,
        latest.score        AS attempt_score,
        latest.max_score    AS attempt_max,
        latest.band_score   AS attempt_band,
        latest.status       AS attempt_status,
        latest.completed_at AS attempt_date
    FROM assignments a
    JOIN courses c      ON c.id = a.course_id
    JOIN enrollments e  ON e.course_id = a.course_id AND e.student_id = ?
    LEFT JOIN tests t   ON t.id = a.test_id
    LEFT JOIN vocabulary_words vw
        ON t.test_type = 'Vocabulary'
        AND vw.sort_order = CAST(SUBSTRING(t.code, 12) AS UNSIGNED)
    LEFT JOIN (
        SELECT ta1.test_id, ta1.score, ta1.max_score, ta1.band_score,
               ta1.status, ta1.completed_at
        FROM test_attempts ta1
        JOIN (
            SELECT test_id, MAX(id) AS max_id
            FROM test_attempts
            WHERE student_id = ?
            GROUP BY test_id
        ) ta2 ON ta1.id = ta2.max_id
    ) latest ON latest.test_id = a.test_id
    WHERE a.student_id IS NULL OR a.student_id = ?
    ORDER BY
        CASE WHEN a.due_date IS NULL THEN 1 ELSE 0 END,
        a.due_date ASC,
        a.created_at DESC
";
$stmt        = executeQuery($db, $sql, [$user_id, $user_id, $user_id]);
$assignments = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];

// URL derivation for standalone practice tests: their codes follow
// {PREFIX}_PT_{SECTION}_{NNN} (see documentation/ai_test_page_template.md),
// which maps 1:1 onto resources/practice_tests/{prefix}_{section}_{nnn}.php.
// Deriving the path instead of hardcoding it means every practice test
// (CELPIP included) is assignable/launchable without maintaining a list
// here by hand -- previously only 4 IELTS codes were mapped, so every
// other assigned test (all 15 CELPIP tests included) showed up for the
// student with no way to actually open it.
require_once INCLUDES_PATH . '/assignment_helpers.php';

$today = date('Y-m-d');

// Due-this-week / due-this-month counts for the summary strip at the top of
// the page — computed from the same $assignments list the cards below use,
// so the numbers can never drift out of sync with what's actually shown.
$dueWeekCount = $dueMonthCount = $overdueCount = 0;
$dueWeekItems = [];
foreach ($assignments as $a) {
    $tags = assignmentFilterTags($a, $today);
    if (in_array('overdue', $tags, true)) $overdueCount++;
    if (in_array('week', $tags, true))  { $dueWeekCount++;  $dueWeekItems[] = $a; }
    if (in_array('month', $tags, true)) $dueMonthCount++;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Assignments – EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .filter-bar { display:flex; gap:.4rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .filter-btn {
            padding:.35rem 1rem; border-radius:999px; border:1.5px solid #e5e7eb;
            background:#fff; font-size:.82rem; font-weight:600; color:#6b7280;
            cursor:pointer; transition:all .15s;
        }
        .filter-btn:hover  { border-color:#0b77ff; color:#0b77ff; }
        .filter-btn.active { background:#0b77ff; border-color:#0b77ff; color:#fff; }

        .asgn-card {
            background:#fff; border-radius:14px;
            box-shadow:0 2px 10px rgba(0,0,0,.05);
            padding:1.25rem 1.4rem; margin-bottom:.85rem;
            border-left:4px solid #0b77ff;
            transition:box-shadow .2s;
        }
        .asgn-card:hover { box-shadow:0 6px 20px rgba(0,0,0,.09); }
        .asgn-card.completed { border-left-color:#10b981; }
        .asgn-card.overdue   { border-left-color:#ef4444; }

        .due-badge {
            display:inline-block; padding:.2rem .65rem;
            border-radius:999px; font-size:.72rem; font-weight:700;
        }
        .due-upcoming { background:#fef3c7; color:#92400e; }
        .due-overdue  { background:#fee2e2; color:#b91c1c; }
        .due-none     { background:#f3f4f6; color:#6b7280; }

        .due-soon-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:1rem; }
        .due-soon-tile {
            text-align:left; border:1.5px solid #e5e7eb; background:#fff; border-radius:16px;
            padding:1.1rem 1.25rem; cursor:pointer; transition:border-color .15s, box-shadow .15s;
        }
        .due-soon-tile:hover { box-shadow:0 6px 20px rgba(0,0,0,.08); }
        .due-soon-tile.active { border-color:#0b77ff; box-shadow:0 0 0 3px rgba(11,119,255,.12); }
        .due-soon-num { font-size:1.9rem; font-weight:800; line-height:1; color:#1e293b; }
        .due-soon-tile.week .due-soon-num   { color:#0b77ff; }
        .due-soon-tile.month .due-soon-num  { color:#6366f1; }
        .due-soon-tile.overdue .due-soon-num{ color:#dc2626; }
        .due-soon-label { font-size:.82rem; font-weight:700; color:#475569; margin:.15rem 0 .65rem; }
        .due-soon-preview { border-top:1px dashed #e5e7eb; padding-top:.6rem; }
        .due-soon-preview-row { display:flex; justify-content:space-between; gap:.5rem; font-size:.78rem; color:#334155; margin-bottom:.3rem; }
        .due-soon-preview-title { overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .due-soon-preview-date { flex-shrink:0; color:#64748b; font-weight:600; }
        .due-soon-preview-more { font-size:.75rem; color:#94a3b8; }
        .due-soon-empty { font-size:.78rem; color:#94a3b8; }
    </style>
</head>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="mobile-overlay" id="mobileOverlay"></div>
<?php include INCLUDES_PATH . '/navbar.php'; ?>
<div class="main-wrapper flex-grow-1" style="flex:1;">
    <?php include INCLUDES_PATH . '/topbar.php'; ?>
    <main class="content p-4">

        <!-- Page header -->
        <div class="d-flex align-items-center gap-3 mb-4">
            <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#0b77ff,#6366f1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-stickies-fill text-white fs-5"></i>
            </div>
            <div>
                <h4 class="mb-0 fw-bold">Assignments</h4>
                <p class="text-muted mb-0" style="font-size:.85rem;">Your tasks, tests and quizzes across all enrolled courses</p>
            </div>
        </div>

        <?php if (empty($assignments)): ?>
            <div class="text-center py-5" style="background:#fff;border-radius:16px;box-shadow:0 2px 10px rgba(0,0,0,.05);">
                <i class="bi bi-stickies fs-1 text-muted mb-3 d-block"></i>
                <h5 class="text-muted">No assignments yet</h5>
                <p class="text-muted small">Enrol in a course to receive assignments.</p>
                <a href="courses/courses_catalogue.php" class="btn btn-primary mt-1">Browse Courses</a>
            </div>
        <?php else: ?>

            <!-- Due soon summary -->
            <div class="due-soon-grid mb-4">
                <button type="button" class="due-soon-tile week" data-quickfilter="week">
                    <div class="due-soon-num"><?= $dueWeekCount ?></div>
                    <div class="due-soon-label"><i class="bi bi-calendar-week me-1"></i>Due this week</div>
                    <?php if ($dueWeekItems): ?>
                    <div class="due-soon-preview">
                        <?php foreach (array_slice($dueWeekItems, 0, 3) as $item): ?>
                        <div class="due-soon-preview-row">
                            <span class="due-soon-preview-title"><?= htmlspecialchars(!empty($item['title']) ? $item['title'] : ($item['test_title'] ?? 'Assignment')) ?></span>
                            <span class="due-soon-preview-date"><?= date('D j', strtotime($item['due_date'])) ?></span>
                        </div>
                        <?php endforeach; ?>
                        <?php if (count($dueWeekItems) > 3): ?>
                        <div class="due-soon-preview-more">+<?= count($dueWeekItems) - 3 ?> more</div>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                    <div class="due-soon-preview due-soon-empty">Nothing due this week</div>
                    <?php endif; ?>
                </button>

                <button type="button" class="due-soon-tile month" data-quickfilter="month">
                    <div class="due-soon-num"><?= $dueMonthCount ?></div>
                    <div class="due-soon-label"><i class="bi bi-calendar-month me-1"></i>Due this month</div>
                    <div class="due-soon-preview due-soon-empty">Includes this week's items above</div>
                </button>

                <button type="button" class="due-soon-tile overdue" data-quickfilter="overdue">
                    <div class="due-soon-num"><?= $overdueCount ?></div>
                    <div class="due-soon-label"><i class="bi bi-exclamation-circle me-1"></i>Overdue</div>
                    <div class="due-soon-preview due-soon-empty"><?= $overdueCount ? 'Needs attention' : 'You\'re all caught up' ?></div>
                </button>
            </div>

            <!-- Filter tabs -->
            <div class="filter-bar">
                <button class="filter-btn active" data-filter="all">All <span class="ms-1 badge bg-secondary" id="cnt-all"></span></button>
                <button class="filter-btn" data-filter="pending">Not Started</button>
                <button class="filter-btn" data-filter="completed">Completed</button>
                <button class="filter-btn" data-filter="overdue">Overdue</button>
            </div>

            <!-- Assignment cards -->
            <?php foreach ($assignments as $a):
                $completed = $a['attempt_status'] === 'completed';
                $overdue   = !$completed && !empty($a['due_date']) && $a['due_date'] < $today;
                $cardClass = $completed ? 'completed' : ($overdue ? 'overdue' : '');
                $filterTag = implode(' ', assignmentFilterTags($a, $today));

                $displayTitle = !empty($a['title']) ? $a['title'] : ($a['test_title'] ?? 'Assignment');
                $url = assignmentUrl($a, $SECTION_FILE_MAP);

                // Due date badge
                if (empty($a['due_date'])) {
                    $dueBadge = "<span class='due-badge due-none'>No due date</span>";
                } elseif ($overdue) {
                    $dueBadge = "<span class='due-badge due-overdue'><i class='bi bi-exclamation-circle me-1'></i>Overdue · " . date('M j', strtotime($a['due_date'])) . "</span>";
                } else {
                    $dueBadge = "<span class='due-badge due-upcoming'>Due " . date('M j, Y', strtotime($a['due_date'])) . "</span>";
                }

                // Score string
                $scoreStr = '';
                if ($completed && $a['attempt_score'] !== null) {
                    $pct = $a['attempt_max'] > 0 ? round(($a['attempt_score'] / $a['attempt_max']) * 100) : 0;
                    $scoreStr = (int)$a['attempt_score'] . '/' . (int)$a['attempt_max'] . ' (' . $pct . '%)';
                    if ($a['attempt_band']) $scoreStr .= ' · Band ' . number_format((float)$a['attempt_band'], 1);
                }
            ?>
            <div class="asgn-card <?= $cardClass ?>" data-filter="<?= $filterTag ?>">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div style="flex:1;min-width:0;">
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <?= typeBadge($a['type']) ?>
                            <?= $dueBadge ?>
                        </div>
                        <h6 class="mb-0 fw-bold" style="font-size:.95rem;"><?= htmlspecialchars($displayTitle) ?></h6>
                        <div class="text-muted" style="font-size:.78rem;margin-top:.2rem;"><?= htmlspecialchars($a['course_title']) ?></div>
                        <?php if (!empty($a['description'])): ?>
                        <p class="text-muted mb-0 mt-2" style="font-size:.83rem;"><?= htmlspecialchars(mb_substr($a['description'], 0, 180)) ?><?= mb_strlen($a['description']) > 180 ? '…' : '' ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Status + action -->
                    <div class="d-flex flex-column align-items-end gap-2" style="flex-shrink:0;">
                        <?php if ($completed): ?>
                            <span style="display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .75rem;border-radius:999px;background:#dcfce7;color:#15803d;font-size:.75rem;font-weight:700;">
                                <i class="bi bi-check-circle-fill"></i> Completed
                            </span>
                            <?php if ($scoreStr): ?>
                            <div class="text-muted" style="font-size:.75rem;text-align:right;"><?= $scoreStr ?></div>
                            <?php endif; ?>
                            <?php if ($url): ?>
                            <a href="<?= $url ?>" class="btn btn-sm btn-outline-success" style="font-size:.78rem;">Redo</a>
                            <?php endif; ?>
                        <?php else: ?>
                            <span style="display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .75rem;border-radius:999px;background:#f3f4f6;color:#6b7280;font-size:.75rem;font-weight:700;">
                                <i class="bi bi-circle"></i> Not started
                            </span>
                            <?php if ($url): ?>
                            <a href="<?= $url ?>" class="btn btn-sm btn-primary" style="font-size:.78rem;">Start</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </main>
</div>
<?php include INCLUDES_PATH . '/adverts.php'; ?>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>

<script>
(function() {
    const saved = localStorage.getItem('eduhub-theme') || 'light';
    document.body.classList.add(saved);
})();

// Filter tabs + due-soon quick filters — a card's data-filter can hold
// several space-separated tags (e.g. "pending week month"), so both sets of
// controls just test tag membership rather than exact string equality.
const cards        = document.querySelectorAll('.asgn-card');
const buttons      = document.querySelectorAll('.filter-btn');
const quickTiles   = document.querySelectorAll('.due-soon-tile');
const cardTags     = Array.from(cards).map(c => c.dataset.filter.split(' '));

const counts = { all: cards.length, pending: 0, completed: 0, overdue: 0 };
cardTags.forEach(tags => tags.forEach(t => { if (counts[t] !== undefined) counts[t]++; }));
const cntEl = document.getElementById('cnt-all');
if (cntEl) cntEl.textContent = counts.all;

function applyFilter(filter) {
    cards.forEach((c, i) => {
        c.style.display = (filter === 'all' || cardTags[i].includes(filter)) ? '' : 'none';
    });
    buttons.forEach(b => b.classList.toggle('active', b.dataset.filter === filter));
    quickTiles.forEach(t => t.classList.toggle('active', t.dataset.quickfilter === filter));
}

buttons.forEach(btn => btn.addEventListener('click', () => applyFilter(btn.dataset.filter)));
quickTiles.forEach(tile => tile.addEventListener('click', () => applyFilter(tile.dataset.quickfilter)));
</script>
</body>
</html>
