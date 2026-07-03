<?php
require_once __DIR__ . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: edu_hub_registration.php");
    exit();
}
$user_id  = (int)$_SESSION['user_id'];
$userName = isset($_SESSION['user_firstname']) ? htmlspecialchars($_SESSION['user_firstname']) : 'Learner';

// ── Summary stats ────────────────────────────────────────────────────────────
$summarySQL = "
    SELECT
        COUNT(*)                                                     AS total_attempts,
        COUNT(DISTINCT ta.test_id)                                   AS unique_tests,
        COALESCE(AVG(ta.band_score),0)                               AS avg_band,
        COALESCE(AVG(ta.score / NULLIF(ta.max_score,0) * 100), 0)   AS avg_pct,
        SUM(ta.time_spent)                                           AS total_seconds
    FROM test_attempts ta
    WHERE ta.student_id = ? AND ta.status = 'completed'
";
$summaryStmt = executeQuery($db, $summarySQL, [$user_id]);
$summary = $summaryStmt ? $summaryStmt->fetch(PDO::FETCH_ASSOC)
         : ['total_attempts'=>0,'unique_tests'=>0,'avg_band'=>0,'avg_pct'=>0,'total_seconds'=>0];

$totalMins  = $summary['total_seconds'] ? round($summary['total_seconds'] / 60) : 0;
$totalHours = $totalMins >= 60 ? floor($totalMins / 60) . 'h ' . ($totalMins % 60) . 'm' : $totalMins . 'm';

// ── All attempts ─────────────────────────────────────────────────────────────
$attemptsSQL = "
    SELECT
        ta.id,
        ta.score,
        ta.max_score,
        ta.band_score,
        ta.completed_at,
        ta.attempt_number,
        ta.time_spent,
        ta.answers,
        t.title,
        t.category,
        t.code
    FROM test_attempts ta
    JOIN tests t ON t.id = ta.test_id
    WHERE ta.student_id = ? AND ta.status = 'completed'
    ORDER BY ta.completed_at DESC
";
$attemptsStmt = executeQuery($db, $attemptsSQL, [$user_id]);
$attempts = $attemptsStmt ? $attemptsStmt->fetchAll(PDO::FETCH_ASSOC) : [];

// ── Category helpers ─────────────────────────────────────────────────────────
function categoryIcon(string $cat): string {
    $map = [
        'listening'  => 'bi-headphones',
        'reading'    => 'bi-book',
        'writing'    => 'bi-pencil-square',
        'speaking'   => 'bi-mic-fill',
        'quiz'       => 'bi-patch-question-fill',
        'mock'       => 'bi-file-earmark-check',
        'diagnostic' => 'bi-clipboard2-pulse',
        'essay'      => 'bi-pencil',
    ];
    $key = strtolower($cat);
    foreach ($map as $k => $v) {
        if (str_contains($key, $k)) return $v;
    }
    return 'bi-journal-text';
}

function categoryColor(string $cat): string {
    $map = [
        'listening'  => 'linear-gradient(135deg,#10b981,#34d399)',
        'reading'    => 'linear-gradient(135deg,#3b82f6,#60a5fa)',
        'writing'    => 'linear-gradient(135deg,#ec4899,#f43f5e)',
        'speaking'   => 'linear-gradient(135deg,#ef4444,#f87171)',
        'quiz'       => 'linear-gradient(135deg,#f59e0b,#fbbf24)',
        'mock'       => 'linear-gradient(135deg,#8b5cf6,#a78bfa)',
        'diagnostic' => 'linear-gradient(135deg,#06b6d4,#22d3ee)',
        'essay'      => 'linear-gradient(135deg,#ec4899,#f43f5e)',
    ];
    $key = strtolower($cat);
    foreach ($map as $k => $v) {
        if (str_contains($key, $k)) return $v;
    }
    return 'linear-gradient(135deg,#6b7280,#9ca3af)';
}

function filterGroup(string $cat): string {
    $key = strtolower($cat);
    if (str_contains($key,'listen') || str_contains($key,'read') || str_contains($key,'speak')) return 'practice';
    if (str_contains($key,'writ') || str_contains($key,'essay')) return 'essay';
    if (str_contains($key,'mock'))       return 'mock';
    if (str_contains($key,'quiz'))       return 'quiz';
    if (str_contains($key,'diagnostic')) return 'diagnostic';
    if (str_contains($key,'speak'))      return 'speaking';
    return 'practice';
}

function bandColor(float $band): string {
    if ($band >= 7)  return '#10b981';
    if ($band >= 5)  return '#f59e0b';
    return '#ef4444';
}

function scoreBarColor(float $pct): string {
    if ($pct >= 70) return '#10b981';
    if ($pct >= 50) return '#f59e0b';
    return '#ef4444';
}

function fmtTime(?int $secs): string {
    if (!$secs) return '–';
    $m = floor($secs / 60);
    $s = $secs % 60;
    return $m > 0 ? "{$m}m {$s}s" : "{$s}s";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Analytics & Records – EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        /* ── Page header ─────────────────────────────────────── */
        .page-eyebrow {
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: .25rem;
        }

        /* ── Summary cards ───────────────────────────────────── */
        .analytics-stat {
            background: #fff;
            border-radius: 16px;
            padding: 1.4rem 1.6rem;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .analytics-stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #fff;
            flex-shrink: 0;
        }
        .analytics-stat-val {
            font-size: 1.75rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: .2rem;
        }
        .analytics-stat-label {
            font-size: .78rem;
            color: #6b7280;
            font-weight: 500;
        }

        /* ── Filter tabs ─────────────────────────────────────── */
        .filter-bar {
            display: flex;
            gap: .4rem;
            flex-wrap: wrap;
            margin-bottom: 1.25rem;
        }
        .filter-btn {
            padding: .38rem 1rem;
            border-radius: 50px;
            border: 1.5px solid #e5e7eb;
            background: #fff;
            font-size: .82rem;
            font-weight: 600;
            color: #6b7280;
            cursor: pointer;
            transition: all .18s;
            white-space: nowrap;
        }
        .filter-btn:hover { border-color: #0b77ff; color: #0b77ff; }
        .filter-btn.active {
            background: #0b77ff;
            border-color: #0b77ff;
            color: #fff;
        }

        /* ── Attempt rows ────────────────────────────────────── */
        .attempt-row {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,.05);
            margin-bottom: .75rem;
            overflow: hidden;
            transition: box-shadow .2s;
        }
        .attempt-row:hover { box-shadow: 0 6px 22px rgba(0,0,0,.10); }

        .attempt-summary {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
            cursor: pointer;
            user-select: none;
        }

        .attempt-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: #fff;
            flex-shrink: 0;
        }

        .attempt-title {
            font-weight: 700;
            font-size: .93rem;
            color: #111827;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 320px;
        }
        .attempt-meta {
            font-size: .75rem;
            color: #9ca3af;
            margin-top: .1rem;
        }

        .attempt-score-bar {
            flex: 1;
            min-width: 80px;
            max-width: 140px;
        }
        .score-bar-track {
            height: 6px;
            background: #f3f4f6;
            border-radius: 4px;
            overflow: hidden;
        }
        .score-bar-fill {
            height: 100%;
            border-radius: 4px;
            transition: width .5s ease;
        }
        .score-pct-label {
            font-size: .72rem;
            color: #6b7280;
            margin-top: .2rem;
        }

        .attempt-band {
            text-align: right;
            flex-shrink: 0;
            min-width: 70px;
        }
        .band-val {
            font-size: 1.05rem;
            font-weight: 800;
        }
        .band-label {
            font-size: .7rem;
            color: #9ca3af;
        }

        .attempt-raw {
            text-align: right;
            flex-shrink: 0;
            min-width: 60px;
        }
        .raw-val  { font-size: .95rem; font-weight: 700; color: #111827; }
        .raw-label { font-size: .7rem; color: #9ca3af; }

        .expand-chevron {
            color: #9ca3af;
            font-size: .85rem;
            flex-shrink: 0;
            transition: transform .25s;
        }
        .attempt-row.open .expand-chevron { transform: rotate(180deg); }

        /* ── Detail panel ────────────────────────────────────── */
        .attempt-detail {
            display: none;
            border-top: 1px solid #f3f4f6;
            padding: 1.1rem 1.25rem 1.3rem;
            background: #fafafa;
        }
        .attempt-row.open .attempt-detail { display: block; }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: .75rem;
            margin-bottom: 1rem;
        }
        .detail-chip {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: .65rem .9rem;
        }
        .detail-chip-label {
            font-size: .68rem;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: .2rem;
        }
        .detail-chip-val {
            font-size: .95rem;
            font-weight: 700;
            color: #111827;
        }

        .retake-btn {
            font-size: .8rem;
            padding: .35rem .9rem;
            border-radius: 8px;
        }

        /* ── Empty state ─────────────────────────────────────── */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,.05);
        }
        .empty-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            opacity: .25;
        }

        /* ── Trend chart card ────────────────────────────────── */
        .chart-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.4rem 1.6rem;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
            margin-bottom: 1.5rem;
        }

        /* ── Search ──────────────────────────────────────────── */
        .search-wrap {
            position: relative;
            max-width: 280px;
        }
        .search-wrap .bi {
            position: absolute;
            left: .75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: .85rem;
        }
        .search-input {
            padding-left: 2.1rem;
            border-radius: 50px;
            border: 1.5px solid #e5e7eb;
            font-size: .85rem;
            height: 36px;
        }
        .search-input:focus {
            border-color: #0b77ff;
            outline: none;
            box-shadow: none;
        }

        /* ── Responsive tweaks ───────────────────────────────── */
        @media (max-width: 768px) {
            .attempt-score-bar { display: none; }
            .attempt-title { max-width: 160px; }
            .detail-grid { grid-template-columns: 1fr 1fr; }
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

        <!-- Page header -->
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-4">
            <div>
                <div class="page-eyebrow">Analytics</div>
                <h2 class="mb-0 fw-800" style="font-size:1.65rem;">My Test Records</h2>
                <p class="text-muted mb-0" style="font-size:.88rem;">
                    Every attempt, every score — all in one place.
                </p>
            </div>
            <a href="resources/practice_tests/index.php" class="btn btn-primary btn-sm px-3" style="border-radius:50px;">
                <i class="bi bi-plus-lg me-1"></i>Take a Test
            </a>
        </div>

        <!-- Summary stat cards -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="analytics-stat">
                    <div class="analytics-stat-icon" style="background:linear-gradient(135deg,#0b77ff,#6f8cff);">
                        <i class="bi bi-journal-check"></i>
                    </div>
                    <div>
                        <div class="analytics-stat-val"><?= (int)$summary['total_attempts'] ?></div>
                        <div class="analytics-stat-label">Total Attempts</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="analytics-stat">
                    <div class="analytics-stat-icon" style="background:linear-gradient(135deg,#10b981,#34d399);">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <div>
                        <div class="analytics-stat-val"><?= number_format((float)$summary['avg_band'], 1) ?></div>
                        <div class="analytics-stat-label">Avg Band Score</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="analytics-stat">
                    <div class="analytics-stat-icon" style="background:linear-gradient(135deg,#f59e0b,#fbbf24);">
                        <i class="bi bi-percent"></i>
                    </div>
                    <div>
                        <div class="analytics-stat-val"><?= round($summary['avg_pct']) ?>%</div>
                        <div class="analytics-stat-label">Avg Score</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="analytics-stat">
                    <div class="analytics-stat-icon" style="background:linear-gradient(135deg,#8b5cf6,#a78bfa);">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <div class="analytics-stat-val"><?= $totalHours ?></div>
                        <div class="analytics-stat-label">Time Practising</div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($attempts)): ?>

        <!-- Band score trend chart -->
        <div class="chart-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="fw-bold" style="font-size:.95rem;">Band Score Trend</div>
                    <div class="text-muted" style="font-size:.78rem;">Your last <?= min(count($attempts), 20) ?> attempts</div>
                </div>
            </div>
            <div style="height:180px;">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Filter + search bar -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <div class="filter-bar mb-0">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="practice">Practice Tests</button>
                <button class="filter-btn" data-filter="mock">Mock Tests</button>
                <button class="filter-btn" data-filter="quiz">Quizzes</button>
                <button class="filter-btn" data-filter="diagnostic">Diagnostic</button>
                <button class="filter-btn" data-filter="essay">Essays</button>
                <button class="filter-btn" data-filter="speaking">Speaking</button>
            </div>
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" class="form-control search-input" id="searchInput" placeholder="Search tests…">
            </div>
        </div>

        <!-- Attempts list -->
        <div id="attemptsList">
        <?php foreach ($attempts as $i => $a):
            $pct      = $a['max_score'] > 0 ? round(($a['score'] / $a['max_score']) * 100) : 0;
            $band     = $a['band_score'] ? number_format((float)$a['band_score'], 1) : '–';
            $bandNum  = (float)($a['band_score'] ?? 0);
            $date     = date('d M Y · H:i', strtotime($a['completed_at']));
            $icon     = categoryIcon($a['category'] ?? '');
            $grad     = categoryColor($a['category'] ?? '');
            $group    = filterGroup($a['category'] ?? '');
            $barCol   = scoreBarColor($pct);
            $bCol     = $bandNum > 0 ? bandColor($bandNum) : '#9ca3af';
            $attempt  = $a['attempt_number'] ?? 1;
            $mins     = fmtTime($a['time_spent'] ?? null);

            // Build a retake URL from the test code
            $codeMap = [
                'IELTS_PT_L'  => 'resources/practice_tests/ielts_listening_001.php',
                'IELTS_PT_R'  => 'resources/practice_tests/ielts_reading_001.php',
                'IELTS_PT_W1' => 'resources/practice_tests/ielts_writing_t1_001.php',
                'IELTS_PT_W2' => 'resources/practice_tests/ielts_writing_t2_001.php',
                'IELTS_PT_S'  => 'resources/practice_tests/ielts_speaking_001.php',
            ];
            $retakeUrl = '#';
            foreach ($codeMap as $prefix => $url) {
                if (str_starts_with($a['code'] ?? '', $prefix)) { $retakeUrl = $url; break; }
            }
        ?>
        <div class="attempt-row"
             data-group="<?= htmlspecialchars($group) ?>"
             data-title="<?= htmlspecialchars(strtolower($a['title'])) ?>">

            <!-- Summary row (clickable) -->
            <div class="attempt-summary" onclick="toggleRow(this.closest('.attempt-row'))">

                <div class="attempt-icon" style="background:<?= $grad ?>;">
                    <i class="bi <?= $icon ?>"></i>
                </div>

                <div style="flex:1;min-width:0;">
                    <div class="attempt-title"><?= htmlspecialchars($a['title']) ?></div>
                    <div class="attempt-meta">
                        <span><?= $date ?></span>
                        <?php if ($attempt > 1): ?>
                        <span class="ms-2 badge bg-light text-secondary" style="font-size:.68rem;">
                            Attempt #<?= $attempt ?>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Score bar -->
                <div class="attempt-score-bar">
                    <div class="score-bar-track">
                        <div class="score-bar-fill" style="width:<?= $pct ?>%;background:<?= $barCol ?>;"></div>
                    </div>
                    <div class="score-pct-label"><?= $pct ?>%</div>
                </div>

                <!-- Raw score -->
                <div class="attempt-raw">
                    <div class="raw-val"><?= (int)$a['score'] ?>/<?= (int)$a['max_score'] ?></div>
                    <div class="raw-label">Score</div>
                </div>

                <!-- Band -->
                <div class="attempt-band">
                    <div class="band-val" style="color:<?= $bCol ?>;"><?= $band ?></div>
                    <div class="band-label">Band</div>
                </div>

                <i class="bi bi-chevron-down expand-chevron"></i>
            </div>

            <!-- Detail panel -->
            <div class="attempt-detail">
                <div class="detail-grid">
                    <div class="detail-chip">
                        <div class="detail-chip-label">Score</div>
                        <div class="detail-chip-val"><?= (int)$a['score'] ?> / <?= (int)$a['max_score'] ?></div>
                    </div>
                    <div class="detail-chip">
                        <div class="detail-chip-label">Percentage</div>
                        <div class="detail-chip-val" style="color:<?= $barCol ?>;"><?= $pct ?>%</div>
                    </div>
                    <div class="detail-chip">
                        <div class="detail-chip-label">Band Score</div>
                        <div class="detail-chip-val" style="color:<?= $bCol ?>;">
                            <?= $band !== '–' ? 'Band ' . $band : '–' ?>
                        </div>
                    </div>
                    <div class="detail-chip">
                        <div class="detail-chip-label">Time Taken</div>
                        <div class="detail-chip-val"><?= $mins ?></div>
                    </div>
                    <div class="detail-chip">
                        <div class="detail-chip-label">Category</div>
                        <div class="detail-chip-val"><?= htmlspecialchars($a['category'] ?? '–') ?></div>
                    </div>
                    <div class="detail-chip">
                        <div class="detail-chip-label">Attempt #</div>
                        <div class="detail-chip-val"><?= $attempt ?></div>
                    </div>
                    <div class="detail-chip">
                        <div class="detail-chip-label">Completed</div>
                        <div class="detail-chip-val" style="font-size:.82rem;"><?= date('d M Y', strtotime($a['completed_at'])) ?></div>
                    </div>
                    <div class="detail-chip">
                        <div class="detail-chip-label">Test Code</div>
                        <div class="detail-chip-val" style="font-size:.78rem;font-family:monospace;">
                            <?= htmlspecialchars($a['code'] ?? '–') ?>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <?php if ($retakeUrl !== '#'): ?>
                    <a href="<?= $retakeUrl ?>" class="btn btn-outline-primary retake-btn">
                        <i class="bi bi-arrow-repeat me-1"></i>Retake Test
                    </a>
                    <?php endif; ?>
                    <span class="text-muted" style="font-size:.78rem;">
                        <i class="bi bi-calendar3 me-1"></i><?= $date ?>
                    </span>
                </div>
            </div>

        </div>
        <?php endforeach; ?>
        </div>

        <!-- No results message (shown by JS when search/filter yields nothing) -->
        <div id="noResults" class="empty-state d-none">
            <div class="empty-icon"><i class="bi bi-funnel"></i></div>
            <h5 class="text-muted">No matching records</h5>
            <p class="text-muted small">Try a different filter or search term.</p>
        </div>

        <?php else: ?>
        <!-- Empty state — no attempts at all -->
        <div class="empty-state">
            <div class="empty-icon"><i class="bi bi-journal-x"></i></div>
            <h5 class="fw-bold">No test records yet</h5>
            <p class="text-muted">Complete a practice test, quiz, or mock exam and your results will appear here.</p>
            <a href="resources/practice_tests/index.php" class="btn btn-primary px-4 mt-2" style="border-radius:50px;">
                <i class="bi bi-play-fill me-1"></i>Start Practising
            </a>
        </div>
        <?php endif; ?>

    </main>
</div><!-- /.main-wrapper -->

<?php include INCLUDES_PATH . '/adverts.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>

<script>
// ── Expand / collapse ────────────────────────────────────────────────────────
function toggleRow(row) {
    row.classList.toggle('open');
}

// ── Filter tabs ──────────────────────────────────────────────────────────────
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        applyFilters();
    });
});

// ── Search ───────────────────────────────────────────────────────────────────
document.getElementById('searchInput')?.addEventListener('input', applyFilters);

function applyFilters() {
    const filter = document.querySelector('.filter-btn.active')?.dataset.filter ?? 'all';
    const query  = (document.getElementById('searchInput')?.value ?? '').toLowerCase().trim();
    let visible  = 0;

    document.querySelectorAll('.attempt-row').forEach(row => {
        const groupMatch = filter === 'all' || row.dataset.group === filter;
        const textMatch  = !query || row.dataset.title.includes(query);
        const show = groupMatch && textMatch;
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    const noRes = document.getElementById('noResults');
    if (noRes) noRes.classList.toggle('d-none', visible > 0);
}

// ── Band trend chart ─────────────────────────────────────────────────────────
(function() {
    const ctx = document.getElementById('trendChart');
    if (!ctx) return;

    // Pull last 20 attempts in chronological order for chart
    const rawData = <?php
        $chartData = array_reverse(array_slice($attempts, 0, 20));
        echo json_encode(array_map(fn($a) => [
            'label' => date('d M', strtotime($a['completed_at'])),
            'band'  => $a['band_score'] ? round((float)$a['band_score'], 1) : null,
            'pct'   => $a['max_score'] > 0
                        ? round(($a['score'] / $a['max_score']) * 100)
                        : 0,
        ], $chartData));
    ?>;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: rawData.map(d => d.label),
            datasets: [
                {
                    label: 'Band Score',
                    data: rawData.map(d => d.band),
                    borderColor: '#0b77ff',
                    backgroundColor: 'rgba(11,119,255,.08)',
                    borderWidth: 2.5,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#0b77ff',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    yAxisID: 'yBand',
                    spanGaps: true,
                },
                {
                    label: '% Score',
                    data: rawData.map(d => d.pct),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16,185,129,.06)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    yAxisID: 'yPct',
                    borderDash: [4,3],
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    labels: { usePointStyle: true, font: { size: 11 }, padding: 16 }
                },
                tooltip: {
                    backgroundColor: 'rgba(15,23,42,.88)',
                    cornerRadius: 8,
                    callbacks: {
                        label: ctx => ctx.datasetIndex === 0
                            ? ' Band: ' + (ctx.parsed.y ?? '–')
                            : ' Score: ' + ctx.parsed.y + '%'
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                yBand: {
                    position: 'left',
                    min: 0, max: 9,
                    grid: { color: 'rgba(0,0,0,.04)' },
                    ticks: { stepSize: 1, font: { size: 10 },
                             callback: v => 'B' + v }
                },
                yPct: {
                    position: 'right',
                    min: 0, max: 100,
                    grid: { display: false },
                    ticks: { font: { size: 10 },
                             callback: v => v + '%' }
                },
            },
        }
    });
})();
</script>
</body>
</html>