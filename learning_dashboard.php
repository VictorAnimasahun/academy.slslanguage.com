<?php
require_once __DIR__ . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: edu_hub_registration.php?message=Please+login+to+access+courses");
    exit();
}

$user_id = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT);
if (!$user_id) {
    header("Location: edu_hub_registration.php");
    exit();
}

// Enrollment metrics
$metricsStmt = executeQuery($db, "SELECT
    COUNT(CASE WHEN progress_percentage = 100 THEN 1 END) as completed,
    COUNT(CASE WHEN progress_percentage > 0 AND progress_percentage < 100 THEN 1 END) as in_progress,
    COALESCE(AVG(progress_percentage), 0) as avg_progress
FROM enrollments WHERE student_id = ?", [$user_id]);
$metrics = $metricsStmt ? $metricsStmt->fetch(PDO::FETCH_ASSOC) : ['completed' => 0, 'in_progress' => 0, 'avg_progress' => 0];

// Certificates
$certStmt = executeQuery($db, "SELECT COUNT(*) FROM user_certificates WHERE student_id = ?", [$user_id]);
$certificatesEarned = $certStmt ? $certStmt->fetchColumn() : 0;

// Enrolled courses
$enrolledStmt = executeQuery($db, "SELECT c.*, e.progress_percentage, e.enrolled_at
    FROM courses c JOIN enrollments e ON c.id = e.course_id
    WHERE e.student_id = ? ORDER BY e.enrolled_at DESC", [$user_id]);
$enrolled_courses = $enrolledStmt ? $enrolledStmt->fetchAll(PDO::FETCH_ASSOC) : [];

// Recommended courses (not yet enrolled)
$availableStmt = executeQuery($db, "SELECT c.* FROM courses c
    WHERE c.id NOT IN (SELECT course_id FROM enrollments WHERE student_id = ?)
    ORDER BY c.created_at DESC LIMIT " . MAX_COURSE_RECOMMENDATIONS, [$user_id]);
$available_courses = $availableStmt ? $availableStmt->fetchAll(PDO::FETCH_ASSOC) : [];

// Most recent practice test attempt
$recentAttemptsStmt = executeQuery($db, "
    SELECT ta.score, ta.max_score, ta.band_score, ta.completed_at, t.title
    FROM test_attempts ta JOIN tests t ON t.id = ta.test_id
    WHERE ta.student_id = ? AND ta.status = 'completed'
      AND (ta.mode = 'practice' OR ta.mode IS NULL)
    ORDER BY ta.completed_at DESC LIMIT 1", [$user_id]);
$recentAttempts = $recentAttemptsStmt ? $recentAttemptsStmt->fetchAll(PDO::FETCH_ASSOC) : [];

// Most recent mock session
$mockSessionsStmt = executeQuery($db, "
    SELECT ms.id, ms.status, ms.overall_band, ms.writing_band, ms.created_at,
           ms.listening_attempt_id, ms.reading_attempt_id, ms.writing_attempt_id,
           ms.speaking_notes, ms.speaking_band,
           t.title AS mock_title,
           ta_l.band_score AS l_band, ta_l.score AS l_score, ta_l.max_score AS l_max,
           ta_r.band_score AS r_band, ta_r.score AS r_score, ta_r.max_score AS r_max
    FROM mock_sessions ms
    JOIN tests t ON t.id = ms.mock_test_id
    LEFT JOIN test_attempts ta_l ON ta_l.id = ms.listening_attempt_id
    LEFT JOIN test_attempts ta_r ON ta_r.id = ms.reading_attempt_id
    WHERE ms.student_id = ? ORDER BY ms.created_at DESC LIMIT 1", [$user_id]);
$mockSessions = $mockSessionsStmt ? $mockSessionsStmt->fetchAll(PDO::FETCH_ASSOC) : [];

// Daily vocabulary word
$vocabWords = $db->query(
    "SELECT id, headword, phonetic, word_class, cefr_level, is_awl, definition
     FROM vocabulary_words WHERE is_active=1 ORDER BY sort_order, headword"
)->fetchAll(PDO::FETCH_ASSOC);
$dailyWord = !empty($vocabWords)
    ? $vocabWords[(int)(date('z') + date('Y')) % count($vocabWords)]
    : null;

$userName     = isset($_SESSION['user_firstname']) ? htmlspecialchars($_SESSION['user_firstname']) : 'Learner';
$userLastname = isset($_SESSION['user_lastname'])  ? htmlspecialchars($_SESSION['user_lastname'])  : '';
$userFullName = trim($userName . ' ' . $userLastname) ?: 'Learner';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>My Learning Dashboard - EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <link href="assets/css/dashboard.css" rel="stylesheet">
    <style>
        .wotd-header {
            background: linear-gradient(135deg, #0b77ff 0%, #6366f1 100%);
            margin: -1.25rem -1.25rem 1rem;
            padding: 1.25rem;
            border-radius: 12px 12px 0 0;
        }
        .wotd-badge {
            display: inline-block;
            background: rgba(255,255,255,.2);
            color: #fff;
            padding: .15rem .55rem;
            border-radius: 999px;
            font-size: .7rem;
            font-weight: 600;
        }
        .wotd-badge-awl { background: rgba(236,72,153,.75); }

        .progress-course-label {
            font-size: .8rem;
            font-weight: 600;
            color: #1f2937;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }
    </style>
</head>

<body class="light">
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>
    <?php include INCLUDES_PATH . '/topbar.php'; ?>

    <main class="content container-fluid">
        <div class="row align-items-start">

            <!-- Left column -->
            <div class="col-lg-8" style="max-width:750px;">

                <!-- Stat cards -->
                <div class="d-flex gap-2 mb-4">
                    <div class="stat-card d-flex align-items-center gap-2" style="flex:1;min-width:0;">
                        <div class="blue-pill" style="width:32px;height:32px;">
                            <i class="bi bi-book-half" style="font-size:.8rem;"></i>
                        </div>
                        <div style="min-width:0;">
                            <div style="font-weight:700;font-size:.9rem;"><?php echo $metrics['completed']; ?>+</div>
                            <div style="color:var(--muted);font-size:.7rem;white-space:nowrap;">Completed</div>
                        </div>
                    </div>
                    <div class="stat-card d-flex align-items-center gap-2" style="flex:1;min-width:0;">
                        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(90deg,#10b981,#34d399);display:flex;align-items:center;justify-content:center;color:#fff;">
                            <i class="bi bi-award" style="font-size:.8rem;"></i>
                        </div>
                        <div style="min-width:0;">
                            <div style="font-weight:700;font-size:.9rem;"><?php echo $certificatesEarned; ?></div>
                            <div style="color:var(--muted);font-size:.7rem;white-space:nowrap;">Certificates</div>
                        </div>
                    </div>
                    <div class="stat-card d-flex align-items-center gap-2" style="flex:1;min-width:0;">
                        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(90deg,#7c3aed,#c084fc);display:flex;align-items:center;justify-content:center;color:#fff;">
                            <i class="bi bi-mortarboard-fill" style="font-size:.8rem;"></i>
                        </div>
                        <div style="min-width:0;">
                            <div style="font-weight:700;font-size:.9rem;"><?php echo $metrics['in_progress']; ?></div>
                            <div style="color:var(--muted);font-size:.7rem;white-space:nowrap;">In Progress</div>
                        </div>
                    </div>
                </div>

                <!-- Ongoing / Registered Courses -->
                <?php if (!empty($enrolled_courses)): ?>
                <div class="small-card mb-4">
                    <h5 class="mb-3">My Courses</h5>
                    <div class="row g-3">
                        <?php foreach (array_slice($enrolled_courses, 0, 3) as $course):
                            $progress = (int)($course['progress_percentage'] ?? 0);
                            $barCol   = $progress >= 70 ? 'linear-gradient(90deg,#10b981,#34d399)'
                                      : ($progress >= 30 ? 'linear-gradient(90deg,#0b77ff,#6f8cff)'
                                      : 'linear-gradient(90deg,#f59e0b,#fbbf24)');
                            $base = __DIR__ . '/courses/' . $course['folder_name'];
                            if (file_exists($base . '/intro.php')) {
                                $courseLink = "courses/{$course['folder_name']}/intro.php?id={$course['id']}";
                            } elseif (file_exists($base . '/course_overview.php')) {
                                $courseLink = "courses/{$course['folder_name']}/course_overview.php?id={$course['id']}";
                            } else {
                                $courseLink = "courses/courses_detail.php?id={$course['id']}";
                            }
                        ?>
                        <div class="col-md-4">
                            <div class="course-card h-100">
                                <h6 style="font-weight:700;font-size:.9rem;"><?php echo htmlspecialchars($course['title']); ?></h6>
                                <?php if (!empty($course['description'])): ?>
                                <div class="small text-muted mb-3" style="font-size:.78rem;">
                                    <?php
                                    $desc = $course['description'];
                                    echo htmlspecialchars(strlen($desc) > MAX_DESCRIPTION_LENGTH
                                        ? substr($desc, 0, MAX_DESCRIPTION_LENGTH) . '...' : $desc);
                                    ?>
                                </div>
                                <?php endif; ?>
                                <div class="mt-auto">
                                    <div style="height:6px;background:#eef2ff;border-radius:4px;margin-bottom:.4rem;">
                                        <div style="width:<?php echo $progress; ?>%;height:100%;background:<?php echo $barCol; ?>;border-radius:4px;transition:width .6s;"></div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted" style="font-size:.75rem;"><?php echo $progress; ?>% complete</small>
                                        <a href="<?php echo $courseLink; ?>"
                                           class="btn btn-sm btn-primary" style="font-size:.78rem;">Continue</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Recommended Courses -->
                <?php if (!empty($available_courses)): ?>
                <div class="small-card mb-4">
                    <h5 class="mb-3">Recommended for You</h5>
                    <div class="row g-3">
                        <?php foreach ($available_courses as $course): ?>
                        <div class="col-md-4">
                            <div class="course-card h-100 d-flex flex-column">
                                <div style="height:80px;background:linear-gradient(135deg,#eef4ff,#f5f3ff);border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:.8rem;flex-shrink:0;">
                                    <?php if (!empty($course['thumbnail'])): ?>
                                        <img src="<?php echo htmlspecialchars($course['thumbnail']); ?>"
                                             alt="" style="max-height:100%;max-width:100%;object-fit:cover;border-radius:8px;" loading="lazy">
                                    <?php else: ?>
                                        <i class="bi bi-book-half" style="font-size:2rem;color:#93c5fd;"></i>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($course['category'])): ?>
                                <div class="mb-1">
                                    <span class="badge bg-light text-dark" style="font-size:.7rem;"><?php echo htmlspecialchars($course['category']); ?></span>
                                </div>
                                <?php endif; ?>
                                <h6 style="font-weight:700;font-size:.88rem;"><?php echo htmlspecialchars($course['title'] ?? 'Untitled Course'); ?></h6>
                                <?php if (!empty($course['description'])): ?>
                                <div class="text-muted small mb-3" style="font-size:.78rem;flex:1;">
                                    <?php
                                    $desc = $course['description'];
                                    echo htmlspecialchars(strlen($desc) > MAX_DESCRIPTION_LENGTH
                                        ? substr($desc, 0, MAX_DESCRIPTION_LENGTH) . '...' : $desc);
                                    ?>
                                </div>
                                <?php endif; ?>
                                <a href="courses/courses_detail.php?id=<?php echo (int)$course['id']; ?>"
                                   class="btn btn-outline-primary btn-sm mt-auto" style="font-size:.78rem;">View Course</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Recent Practice Test -->
                <?php if (!empty($recentAttempts)): ?>
                <div class="small-card mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Recent Practice Test</h5>
                        <a href="resources/practice_tests/my_results.php" class="small text-decoration-none">View all results →</a>
                    </div>
                    <?php foreach ($recentAttempts as $attempt):
                        $pct    = $attempt['max_score'] > 0 ? round(($attempt['score'] / $attempt['max_score']) * 100) : 0;
                        $band   = $attempt['band_score'] ? number_format($attempt['band_score'], 1) : '–';
                        $date   = date('d M Y', strtotime($attempt['completed_at']));
                        $barCol = $pct >= 70 ? '#10b981' : ($pct >= 50 ? '#f59e0b' : '#ef4444');
                    ?>
                    <div class="d-flex align-items-center gap-3 py-2">
                        <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#0b77ff,#6366f1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-journal-check text-white" style="font-size:.85rem;"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div class="fw-semibold text-truncate" style="font-size:.88rem;"><?php echo htmlspecialchars($attempt['title']); ?></div>
                            <div style="height:5px;background:#e5e7eb;border-radius:4px;margin:.25rem 0;">
                                <div style="width:<?php echo $pct; ?>%;height:100%;background:<?php echo $barCol; ?>;border-radius:4px;"></div>
                            </div>
                            <div class="text-muted" style="font-size:.75rem;"><?php echo $date; ?></div>
                        </div>
                        <div class="text-end" style="flex-shrink:0;">
                            <div class="fw-bold" style="font-size:.9rem;"><?php echo (int)$attempt['score'] . '/' . (int)$attempt['max_score']; ?></div>
                            <div class="small text-muted">Band <?php echo $band; ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Mock Test Results -->
                <div class="small-card mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Mock Test Results</h5>
                        <a href="resources/practice_tests/my_results.php" class="small text-decoration-none">My Results →</a>
                    </div>
                    <?php if (empty($mockSessions)): ?>
                        <p class="text-muted small mb-0">No mock tests attempted yet. <a href="resources/mock_tests/index.php">Start a full mock exam →</a></p>
                    <?php else: ?>
                        <?php foreach ($mockSessions as $ms):
                            $msDate = date('d M Y', strtotime($ms['created_at']));
                        ?>
                        <div class="d-flex align-items-start gap-3 py-2">
                            <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#10b981,#34d399);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-clipboard-check text-white" style="font-size:.85rem;"></i>
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div class="fw-semibold text-truncate" style="font-size:.88rem;"><?php echo htmlspecialchars($ms['mock_title']); ?></div>
                                <div class="text-muted" style="font-size:.75rem;"><?php echo $msDate; ?></div>
                            </div>
                            <div class="text-end" style="flex-shrink:0;">
                                <?php if ($ms['status'] === 'in_progress'): ?>
                                    <?php
                                    if (is_null($ms['listening_attempt_id']))   $resumeUrl = "resources/mock_tests/full_mock_001_listening.php?session_id={$ms['id']}";
                                    elseif (is_null($ms['reading_attempt_id'])) $resumeUrl = "resources/mock_tests/full_mock_001_reading.php?session_id={$ms['id']}";
                                    elseif (is_null($ms['writing_attempt_id'])) $resumeUrl = "resources/mock_tests/mock_writing.php?session_id={$ms['id']}";
                                    else                                        $resumeUrl = "resources/mock_tests/mock_speaking.php?session_id={$ms['id']}";
                                    ?>
                                    <a href="<?php echo $resumeUrl; ?>" class="btn btn-sm btn-outline-warning py-0 px-2" style="font-size:.75rem;">Resume</a>
                                <?php elseif ($ms['status'] === 'awaiting_speaking_grade'): ?>
                                    <span style="background:#fef3c7;color:#92400e;padding:.2rem .6rem;border-radius:999px;font-size:.72rem;font-weight:600;">Awaiting Speaking</span>
                                <?php elseif ($ms['status'] === 'results_released'): ?>
                                    <div class="d-flex flex-column align-items-end gap-1">
                                        <span style="font-size:1.1rem;font-weight:800;color:#10b981;">Band <?php echo number_format((float)$ms['overall_band'], 1); ?></span>
                                        <div style="font-size:.7rem;color:#94a3b8;display:flex;gap:.4rem;">
                                            <span>L:<?php echo number_format((float)$ms['l_band'],1); ?></span>
                                            <span>R:<?php echo number_format((float)$ms['r_band'],1); ?></span>
                                            <span>W:<?php echo number_format((float)$ms['writing_band'],1); ?></span>
                                            <span>S:<?php echo number_format((float)$ms['speaking_band'],1); ?></span>
                                        </div>
                                        <button onclick="downloadMockPDF(<?php echo htmlspecialchars(json_encode([
                                            'title'   => $ms['mock_title'],
                                            'date'    => $msDate,
                                            'overall' => number_format((float)$ms['overall_band'], 1),
                                            'l'       => number_format((float)$ms['l_band'], 1),
                                            'l_score' => (int)$ms['l_score'] . '/' . (int)$ms['l_max'],
                                            'r'       => number_format((float)$ms['r_band'], 1),
                                            'r_score' => (int)$ms['r_score'] . '/' . (int)$ms['r_max'],
                                            'w'       => number_format((float)$ms['writing_band'], 1),
                                            's'       => number_format((float)$ms['speaking_band'], 1),
                                            's_notes' => $ms['speaking_notes'] ?? '',
                                            'name'    => $userFullName,
                                        ])); ?>)"
                                            style="background:none;border:1px solid #10b981;color:#10b981;border-radius:6px;padding:.15rem .5rem;font-size:.72rem;cursor:pointer;">
                                            ↓ PDF
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            </div>

            <!-- Right column -->
            <div class="col-lg-4">
                <div class="position-sticky" style="top:96px;">

                    <!-- Word of the Day -->
                    <?php if ($dailyWord): ?>
                    <div class="small-card mb-3" style="overflow:hidden;">
                        <div class="wotd-header">
                            <div style="font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.7);margin-bottom:.5rem;">Word of the Day</div>
                            <div style="font-size:1.65rem;font-weight:800;color:#fff;line-height:1.1;"><?= htmlspecialchars($dailyWord['headword']) ?></div>
                            <?php if ($dailyWord['phonetic']): ?>
                            <div style="font-size:.85rem;color:rgba(255,255,255,.72);margin-top:.25rem;"><?= htmlspecialchars($dailyWord['phonetic']) ?></div>
                            <?php endif; ?>
                            <div style="display:flex;gap:.4rem;flex-wrap:wrap;margin-top:.65rem;">
                                <span class="wotd-badge"><?= htmlspecialchars($dailyWord['word_class']) ?></span>
                                <span class="wotd-badge"><?= htmlspecialchars($dailyWord['cefr_level']) ?></span>
                                <?php if ($dailyWord['is_awl']): ?>
                                <span class="wotd-badge wotd-badge-awl">AWL</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <p style="font-size:.88rem;color:#374151;line-height:1.6;margin-bottom:1rem;"><?= htmlspecialchars($dailyWord['definition']) ?></p>
                        <div class="d-flex gap-2">
                            <a href="resources/vocabulary_banks/word.php?id=<?= $dailyWord['id'] ?>"
                               class="btn btn-primary btn-sm" style="flex:1;font-size:.8rem;">Explore word</a>
                            <a href="resources/vocabulary_banks/word_quiz.php?word_id=<?= $dailyWord['id'] ?>"
                               class="btn btn-outline-secondary btn-sm" style="flex:1;font-size:.8rem;">Practice</a>
                        </div>
                    </div>
                    <div class="text-center mb-3">
                        <a href="resources/vocabulary_banks/vocab_home.php"
                           style="font-size:.78rem;color:#6b7280;text-decoration:none;">Browse all vocabulary →</a>
                    </div>
                    <?php endif; ?>

                    <!-- Per-course progress -->
                    <?php if (!empty($enrolled_courses)): ?>
                    <div class="small-card">
                        <h6 class="mb-3">My Progress</h6>
                        <?php foreach ($enrolled_courses as $course):
                            $pct    = (int)($course['progress_percentage'] ?? 0);
                            $barCol = $pct >= 70 ? '#10b981' : ($pct >= 30 ? '#0b77ff' : '#f59e0b');
                        ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-baseline mb-1">
                                <span class="progress-course-label"><?= htmlspecialchars($course['title']) ?></span>
                                <span style="font-size:.75rem;color:#6b7280;flex-shrink:0;margin-left:.5rem;"><?= $pct ?>%</span>
                            </div>
                            <div style="height:6px;background:#f0f4ff;border-radius:4px;">
                                <div style="width:<?= $pct ?>%;height:100%;background:<?= $barCol ?>;border-radius:4px;transition:width .6s;"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
    function downloadMockPDF(data) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ unit: 'mm', format: 'a4' });

        const navy  = [14,  44,  96];
        const blue  = [14, 165, 233];
        const pink  = [236, 72, 153];
        const dark  = [15,  23,  42];
        const muted = [100, 116, 139];
        const light = [241, 245, 249];
        const white = [255, 255, 255];
        const L = 15, R = 195, W = 180;

        doc.setFillColor(...navy);
        doc.rect(0, 0, 210, 46, 'F');
        doc.setTextColor(180, 210, 255);
        doc.setFontSize(7.5);
        doc.setFont('helvetica', 'italic');
        doc.text('Confidential Assessment Report', R, 8, { align: 'right' });
        doc.setTextColor(...white);
        doc.setFontSize(22);
        doc.setFont('helvetica', 'bold');
        doc.text(data.title.toUpperCase(), L, 22);
        doc.setFontSize(10.5);
        doc.setFont('helvetica', 'italic');
        doc.text('Full Band Assessment Report', L, 31);
        doc.setDrawColor(100, 140, 200);
        doc.setLineWidth(0.25);
        doc.line(L, 35, R, 35);
        doc.setFontSize(8);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(180, 210, 255);
        doc.text('Scholarly Language Services', R, 43, { align: 'right' });

        doc.setTextColor(...dark);
        doc.setFontSize(12.5);
        doc.setFont('helvetica', 'bold');
        doc.text('Candidate: ' + data.name, L, 59);
        doc.setFontSize(9);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(...muted);
        doc.text('Date: ' + data.date, L, 66);

        const tY = 73;
        const colW = 45;
        const cols = [
            { label: 'Listening', band: data.l, sub: data.l_score || '' },
            { label: 'Reading',   band: data.r, sub: data.r_score || '' },
            { label: 'Writing',   band: data.w, sub: 'AI-graded' },
            { label: 'Speaking',  band: data.s, sub: 'Instructor' },
        ];

        doc.setFillColor(...navy);
        doc.rect(L, tY, W, 10, 'F');
        doc.setTextColor(...white);
        doc.setFontSize(8.5);
        doc.setFont('helvetica', 'bold');
        cols.forEach((c, i) => doc.text(c.label, L + colW*i + colW/2, tY + 7, { align: 'center' }));

        doc.setFillColor(...light);
        doc.rect(L, tY + 10, W, 22, 'F');
        doc.setDrawColor(226, 232, 240);
        doc.setLineWidth(0.2);
        for (let i = 1; i < 4; i++) doc.line(L + colW*i, tY+10, L + colW*i, tY+32);
        doc.setFontSize(22);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(...navy);
        cols.forEach((c, i) => doc.text(c.band, L + colW*i + colW/2, tY + 26, { align: 'center' }));
        doc.setFontSize(7.5);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(...muted);
        cols.forEach((c, i) => { if (c.sub) doc.text(c.sub, L + colW*i + colW/2, tY + 31, { align: 'center' }); });

        const oY = tY + 34;
        doc.setFillColor(...navy);
        doc.rect(L, oY, 120, 14, 'F');
        doc.setTextColor(...white);
        doc.setFontSize(10);
        doc.setFont('helvetica', 'bold');
        doc.text('OVERALL BAND SCORE', L + 5, oY + 9.5);
        doc.setFillColor(...pink);
        doc.rect(L + 120, oY, 60, 14, 'F');
        doc.setTextColor(...white);
        doc.setFontSize(20);
        doc.setFont('helvetica', 'bold');
        doc.text(data.overall, L + 150, oY + 10.5, { align: 'center' });

        const avg = ((parseFloat(data.l) + parseFloat(data.r) + parseFloat(data.w) + parseFloat(data.s)) / 4).toFixed(2);
        doc.setFontSize(7.5);
        doc.setFont('helvetica', 'italic');
        doc.setTextColor(...muted);
        doc.text('(' + data.l + ' + ' + data.r + ' + ' + data.w + ' + ' + data.s + ') ÷ 4 = ' + avg + '  →  rounded to ' + data.overall, L, oY + 21);

        const panels = [
            { label: 'LISTENING', band: data.l, score: data.l_score, note: 'Raw score shown. Part-by-part breakdown available in your online results.' },
            { label: 'READING',   band: data.r, score: data.r_score, note: 'Raw score shown. Section breakdown available in your online results.' },
            { label: 'WRITING',   band: data.w, score: '',            note: 'AI-graded. Full task feedback and criteria scores available in your online results.' },
            { label: 'SPEAKING',  band: data.s, score: '',            note: data.s_notes || 'Instructor-graded. Detailed feedback available in your online results.' },
        ];

        let pY = oY + 29;
        panels.forEach(p => {
            doc.setFillColor(...navy);
            doc.rect(L, pY, W, 10, 'F');
            doc.setTextColor(...white);
            doc.setFontSize(9.5);
            doc.setFont('helvetica', 'bold');
            doc.text(p.label, L + 5, pY + 7);
            doc.text('Band ' + p.band, R, pY + 7, { align: 'right' });
            doc.setFillColor(...light);
            doc.rect(L, pY + 10, W, 14, 'F');
            if (p.score) {
                doc.setFontSize(8.5);
                doc.setFont('helvetica', 'bold');
                doc.setTextColor(...dark);
                doc.text('Raw Score: ' + p.score, L + 5, pY + 18);
                doc.setFont('helvetica', 'normal');
                doc.setTextColor(...muted);
                doc.setFontSize(7.5);
                doc.text(p.note, L + 50, pY + 18);
            } else {
                doc.setFontSize(7.5);
                doc.setFont('helvetica', 'normal');
                doc.setTextColor(...muted);
                doc.text(p.note, L + 5, pY + 18);
            }
            pY += 26;
        });

        doc.setFillColor(224, 242, 254);
        doc.rect(L, pY + 4, W, 22, 'F');
        doc.setDrawColor(...blue);
        doc.setLineWidth(0.4);
        doc.line(L, pY + 4, L, pY + 26);
        doc.setTextColor(...dark);
        doc.setFontSize(9);
        doc.setFont('helvetica', 'bold');
        doc.text('Full detailed results available at: academy.slslanguage.com', L + 5, pY + 13);
        doc.setFontSize(8);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(...muted);
        doc.text('Log in to your EduHub account to view correct/incorrect answers, AI writing feedback, and speaking notes.', L + 5, pY + 21);

        doc.setFillColor(...navy);
        doc.rect(0, 282, 210, 15, 'F');
        doc.setTextColor(180, 210, 255);
        doc.setFontSize(7.5);
        doc.setFont('helvetica', 'normal');
        doc.text('Scholarly Language Services  ·  slslanguage.com', L, 290);
        doc.text('Generated ' + new Date().toLocaleDateString('en-GB', { day:'2-digit', month:'long', year:'numeric' }), R, 290, { align: 'right' });

        const filename = 'IELTS_Report_' + data.name.replace(/\s+/g,'_') + '_' + data.date.replace(/\s+/g,'_') + '.pdf';
        doc.save(filename);
    }
    </script>

    <script>
        // Theme restore before first paint
        (function() {
            const saved = localStorage.getItem('eduhub-theme') || 'light';
            document.body.classList.remove('light', 'dark');
            document.body.classList.add(saved);
            const btn = document.getElementById('themeToggle');
            if (btn) btn.textContent = saved === 'dark' ? '☀️ Light Mode' : '🌙 Dark Mode';
        })();

        // Mobile menu
        const menuToggle    = document.getElementById('menuToggle');
        const sidebarEl     = document.querySelector('.sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');

        function toggleMenu() {
            sidebarEl.classList.toggle('active');
            mobileOverlay.classList.toggle('active');
            const icon = menuToggle.querySelector('i');
            icon.className = sidebarEl.classList.contains('active') ? 'bi bi-x-lg' : 'bi bi-list';
        }

        if (menuToggle)    menuToggle.addEventListener('click', toggleMenu);
        if (mobileOverlay) mobileOverlay.addEventListener('click', toggleMenu);

        const sidebarToggle = document.getElementById('sidebarToggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                if (window.innerWidth >= 1200) {
                    const collapsed = document.body.classList.toggle('sidebar-collapsed');
                    localStorage.setItem('eduhub-sidebar-collapsed', collapsed);
                } else {
                    toggleMenu();
                }
            });
        }
        if (window.innerWidth >= 1200 && localStorage.getItem('eduhub-sidebar-collapsed') === 'true') {
            document.body.classList.add('sidebar-collapsed');
        }

        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1200 && sidebarEl.classList.contains('active')) toggleMenu();
            });
        });

        // Theme toggle
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', function() {
                const newTheme = document.body.classList.contains('dark') ? 'light' : 'dark';
                document.body.classList.remove('light', 'dark');
                document.body.classList.add(newTheme);
                themeToggle.textContent = newTheme === 'dark' ? '☀️ Light Mode' : '🌙 Dark Mode';
                localStorage.setItem('eduhub-theme', newTheme);
            });
        }

        // Card entrance animation
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.stat-card, .small-card').forEach((card, i) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, i * 60);
            });
        });
    </script>

    <?php define('ADVERTS_RENDERED', true); ?>
    <?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
