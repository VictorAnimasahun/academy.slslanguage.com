<?php
require_once __DIR__ . '/bootstrap.php'; 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: edu_hub_registration.php?message=Please+login+to+access+courses");
    exit();
}

$user_id = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT);
if (!$user_id) {
    header("Location: edu_hub_registration.php");
    exit();
}

// Database queries with error handling


// Combined dashboard metrics query
$metricsSQL = "SELECT 
    COUNT(CASE WHEN progress_percentage = 100 THEN 1 END) as completed,
    COUNT(CASE WHEN progress_percentage > 0 AND progress_percentage < 100 THEN 1 END) as in_progress,
    COUNT(*) as total_enrolled,
    COALESCE(AVG(progress_percentage), 0) as avg_progress
FROM enrollments WHERE student_id = ?";

$metricsStmt = executeQuery($db, $metricsSQL, [$user_id]);
$metrics = $metricsStmt ? $metricsStmt->fetch(PDO::FETCH_ASSOC) : [
    'completed' => 0, 'in_progress' => 0, 'total_enrolled' => 0, 'avg_progress' => 0
];

// Certificates count
$certSQL = "SELECT COUNT(*) as cnt FROM user_certificates WHERE student_id = ?";
$certStmt = executeQuery($db, $certSQL, [$user_id]);
$certificatesEarned = $certStmt ? $certStmt->fetchColumn() : 0;

// Enrolled courses
$enrolledSQL = "SELECT c.*, e.progress_percentage, e.enrolled_at 
    FROM courses c 
    JOIN enrollments e ON c.id = e.course_id 
    WHERE e.student_id = ?
    ORDER BY e.enrolled_at DESC";
$enrolledStmt = executeQuery($db, $enrolledSQL, [$user_id]);
$enrolled_courses = $enrolledStmt ? $enrolledStmt->fetchAll(PDO::FETCH_ASSOC) : [];

// Available courses
$availableSQL = "SELECT c.* FROM courses c 
    WHERE c.id NOT IN (SELECT course_id FROM enrollments WHERE student_id = ?)
    ORDER BY c.created_at DESC LIMIT " . MAX_COURSE_RECOMMENDATIONS;
$availableStmt = executeQuery($db, $availableSQL, [$user_id]);
$available_courses = $availableStmt ? $availableStmt->fetchAll(PDO::FETCH_ASSOC) : [];

// Assignments
$assignmentsSQL = "SELECT a.*, c.title AS course_title 
    FROM assignments a
    JOIN courses c ON a.course_id = c.id
    JOIN enrollments e ON e.course_id = c.id
    WHERE e.student_id = ?
    ORDER BY a.due_date ASC
    LIMIT " . MAX_ASSIGNMENTS_DISPLAY;
$assignmentsStmt = executeQuery($db, $assignmentsSQL, [$user_id]);
$assignments = $assignmentsStmt ? $assignmentsStmt->fetchAll(PDO::FETCH_ASSOC) : [];

// User activity for charts - with fallback sample data
$activitySQL = "SELECT MONTHNAME(created_at) as month, 
    MONTH(created_at) as month_num,
    SUM(CASE WHEN type='study' THEN duration_minutes ELSE 0 END) / 60 AS study_hours,
    SUM(CASE WHEN type='test' THEN duration_minutes ELSE 0 END) / 60 AS test_hours
    FROM students_activity
    WHERE student_id = ? AND YEAR(created_at) = YEAR(CURDATE())
    GROUP BY MONTH(created_at)
    ORDER BY MONTH(created_at)";
$activityStmt = executeQuery($db, $activitySQL, [$user_id]);
$activity = $activityStmt ? $activityStmt->fetchAll(PDO::FETCH_ASSOC) : [];

// Prepare chart data with fallback sample data
$months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
$studyHours = array_fill(0, 12, 0);
$testHours = array_fill(0, 12, 0);

// If no real data, use sample data for demonstration
if (empty($activity)) {
    $studyHours = [12, 19, 15, 25, 32, 28, 20, 24, 18, 22, 26, 30];
    $testHours = [8, 12, 10, 18, 24, 20, 14, 16, 12, 15, 18, 20];
} else {
    foreach ($activity as $row) {
        $monthIndex = $row['month_num'] - 1;
        $studyHours[$monthIndex] = (float)$row['study_hours'];
        $testHours[$monthIndex] = (float)$row['test_hours'];
    }
}

// Prepare calendar events
$calendarEvents = [];
foreach ($assignments as $a) {
    $calendarEvents[] = [
        'title' => $a['title'],
        'start' => $a['due_date'],
        'url' => "assignment.php?id=" . $a['id']
    ];
}

// Recent practice test attempts
$recentAttemptsSQL = "
    SELECT ta.score, ta.max_score, ta.band_score, ta.completed_at, ta.attempt_number,
           t.title, t.category, t.code
    FROM test_attempts ta
    JOIN tests t ON t.id = ta.test_id
    WHERE ta.student_id = ? AND ta.status = 'completed'
    ORDER BY ta.completed_at DESC
    LIMIT 5
";
$recentAttemptsStmt = executeQuery($db, $recentAttemptsSQL, [$user_id]);
$recentAttempts = $recentAttemptsStmt ? $recentAttemptsStmt->fetchAll(PDO::FETCH_ASSOC) : [];

// Mock test sessions
$mockSessionsSQL = "
    SELECT ms.id, ms.status, ms.overall_band, ms.writing_band, ms.created_at, ms.released_at,
           ms.listening_attempt_id, ms.reading_attempt_id, ms.writing_attempt_id,
           t.title AS mock_title, t.id AS mock_test_id,
           ta_l.band_score AS l_band,
           ta_r.band_score AS r_band,
           ms.speaking_band
    FROM mock_sessions ms
    JOIN tests t ON t.id = ms.mock_test_id
    LEFT JOIN test_attempts ta_l ON ta_l.id = ms.listening_attempt_id
    LEFT JOIN test_attempts ta_r ON ta_r.id = ms.reading_attempt_id
    WHERE ms.student_id = ?
    ORDER BY ms.created_at DESC
    LIMIT 5
";
$mockSessionsStmt = executeQuery($db, $mockSessionsSQL, [$user_id]);
$mockSessions = $mockSessionsStmt ? $mockSessionsStmt->fetchAll(PDO::FETCH_ASSOC) : [];

// User display data
$userName = isset($_SESSION['user_firstname']) ? htmlspecialchars($_SESSION['user_firstname']) : 'Learner';
$progressPercent = round($metrics['avg_progress']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>My Learning Dashboard - EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- External Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
	<link href="assets/css/dashboard.css" rel="stylesheet">
</head>

<body class="light">
    <!-- Mobile Header -->
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <!-- Sidebar Navigation -->
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <!-- Topbar -->
    <?php include INCLUDES_PATH . '/topbar.php'; ?>

    <!-- Main Content -->
    <main class="content container-fluid">
        <div class="row align-items-start">
            <div class="col-lg-8" style="max-width: 750px;">
                <!-- Stats Cards -->
                <div class="d-flex gap-2 mb-4">
                    <div class="stat-card d-flex align-items-center gap-2" style="flex: 1; min-width: 0;">
                        <div class="blue-pill" style="width:32px;height:32px;">
                            <i class="bi bi-book-half" aria-hidden="true" style="font-size:0.8rem;"></i>
                        </div>
                        <div style="min-width: 0;">
                            <div style="font-weight:700;font-size:0.9rem;" aria-label="Completed courses">
                                <?php echo $metrics['completed']; ?>+
                            </div>
                            <div style="color:var(--muted);font-size:.7rem;white-space:nowrap;">Completed</div>
                        </div>
                    </div>

                    <div class="stat-card d-flex align-items-center gap-2" style="flex: 1; min-width: 0;">
                        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(90deg,#10b981,#34d399);display:flex;align-items:center;justify-content:center;color:#fff;">
                            <i class="bi bi-award" aria-hidden="true" style="font-size:0.8rem;"></i>
                        </div>
                        <div style="min-width: 0;">
                            <div style="font-weight:700;font-size:0.9rem;" aria-label="Earned certificates">
                                <?php echo $certificatesEarned; ?>
                            </div>
                            <div style="color:var(--muted);font-size:.7rem;white-space:nowrap;">Certificates</div>
                        </div>
                    </div>

                    <div class="stat-card d-flex align-items-center gap-2" style="flex: 1; min-width: 0;">
                        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(90deg,#7c3aed,#c084fc);display:flex;align-items:center;justify-content:center;color:#fff;">
                            <i class="bi bi-mortarboard-fill" aria-hidden="true" style="font-size:0.8rem;"></i>
                        </div>
                        <div style="min-width: 0;">
                            <div style="font-weight:700;font-size:0.9rem;" aria-label="Courses in progress">
                                <?php echo $metrics['in_progress']; ?>
                            </div>
                            <div style="color:var(--muted);font-size:.7rem;white-space:nowrap;">In Progress</div>
                        </div>
                    </div>
                </div>

                <!-- Recent Practice Tests -->
                <?php if (!empty($recentAttempts)): ?>
                <div class="small-card mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Recent Practice Tests</h5>
                        <a href="resources/practice_tests/index.php" class="small text-decoration-none">All Tests</a>
                    </div>
                    <?php foreach ($recentAttempts as $attempt):
                        $pct    = $attempt['max_score'] > 0 ? round(($attempt['score'] / $attempt['max_score']) * 100) : 0;
                        $band   = $attempt['band_score'] ? number_format($attempt['band_score'], 1) : '–';
                        $date   = date('d M Y', strtotime($attempt['completed_at']));
                        $barCol = $pct >= 70 ? '#10b981' : ($pct >= 50 ? '#f59e0b' : '#ef4444');
                    ?>
                    <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                        <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#10b981,#34d399);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-headphones text-white" style="font-size:.85rem;"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div class="fw-semibold text-truncate" style="font-size:.88rem;">
                                <?php echo htmlspecialchars($attempt['title']); ?>
                            </div>
                            <div style="height:5px;background:#e5e7eb;border-radius:4px;margin:.25rem 0;">
                                <div style="width:<?php echo $pct; ?>%;height:100%;background:<?php echo $barCol; ?>;border-radius:4px;"></div>
                            </div>
                            <div class="text-muted" style="font-size:.75rem;"><?php echo $date; ?></div>
                        </div>
                        <div class="text-end" style="flex-shrink:0;">
                            <div class="fw-bold" style="font-size:.9rem;">
                                <?php echo (int)$attempt['score'] . '/' . (int)$attempt['max_score']; ?>
                            </div>
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
                        <a href="resources/mock_tests/index.php" class="small text-decoration-none">All Mock Tests</a>
                    </div>
                    <?php if (empty($mockSessions)): ?>
                        <p class="text-muted small mb-0">No mock tests attempted yet. <a href="resources/mock_tests/index.php">Start a full mock exam →</a></p>
                    <?php else: ?>
                        <?php foreach ($mockSessions as $ms):
                            $msDate = date('d M Y', strtotime($ms['created_at']));
                        ?>
                        <div class="d-flex align-items-start gap-3 py-2 border-bottom">
                            <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#10b981,#34d399);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-clipboard-check text-white" style="font-size:.85rem;"></i>
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div class="fw-semibold text-truncate" style="font-size:.88rem;">
                                    <?php echo htmlspecialchars($ms['mock_title']); ?>
                                </div>
                                <div class="text-muted" style="font-size:.75rem;"><?php echo $msDate; ?></div>
                            </div>
                            <div class="text-end" style="flex-shrink:0;">
                                <?php if ($ms['status'] === 'in_progress'): ?>
                                    <?php
                                    // Determine resume link
                                    if (is_null($ms['listening_attempt_id'])) $resumeUrl = "resources/mock_tests/full_mock_001_listening.php?session_id={$ms['id']}";
                                    elseif (is_null($ms['reading_attempt_id'])) $resumeUrl = "resources/mock_tests/full_mock_001_reading.php?session_id={$ms['id']}";
                                    elseif (is_null($ms['writing_attempt_id'])) $resumeUrl = "resources/mock_tests/mock_writing.php?session_id={$ms['id']}";
                                    else $resumeUrl = "resources/mock_tests/mock_speaking.php?session_id={$ms['id']}";
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
                                            'r'       => number_format((float)$ms['r_band'], 1),
                                            'w'       => number_format((float)$ms['writing_band'], 1),
                                            's'       => number_format((float)$ms['speaking_band'], 1),
                                            'name'    => $userName,
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

                <!-- Charts -->
                <div class="small-card mb-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="mb-0">Study Statistics</h5>
                            <div style="color:var(--muted);font-size:.85rem;">Study vs Test (hours)</div>
                        </div>
                        <div>
                            <span class="badge bg-light text-muted">This Year</span>
                        </div>
                    </div>
                    <div style="position: relative; height: 280px; width: 100%;">
                        <canvas id="studyChart" role="img" aria-label="Study statistics chart showing study vs test hours by month"></canvas>
                    </div>
                </div>

                <!-- Course Recommendations -->
                <div class="small-card mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Recommended Courses</h5>
                        <a href="#" class="small text-decoration-none">See All</a>
                    </div>

                    <div class="row g-3">
                        <?php
                        $recommendations = !empty($available_courses) ? $available_courses : array_slice($enrolled_courses, 0, MAX_COURSE_RECOMMENDATIONS);
                        if (empty($recommendations)) {
                            echo '<div class="col-12"><div class="alert alert-info">No course recommendations available at this time.</div></div>';
                        }
                        foreach ($recommendations as $course):
                        ?>
                        <div class="col-md-4">
                            <div class="course-card h-100 d-flex flex-column">
                                <div style="height:120px;background:linear-gradient(180deg,#f3f7ff,#fff);border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:.8rem;">
                                    <?php if (isset($course['thumbnail']) && $course['thumbnail']): ?>
                                        <img src="<?php echo htmlspecialchars($course['thumbnail']); ?>" 
                                             alt="Course thumbnail" 
                                             style="max-height:100%;max-width:100%;object-fit:cover;border-radius:8px;"
                                             loading="lazy">
                                    <?php else: ?>
                                        <i class="bi bi-book-half" style="font-size:2.6rem;color:#93c5fd;" aria-hidden="true"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="mb-2">
                                    <small class="badge bg-light text-dark">
                                        <?php echo htmlspecialchars($course['category'] ?? 'General'); ?>
                                    </small>
                                </div>
                                <h6 style="font-weight:700">
                                    <?php echo htmlspecialchars($course['title'] ?? 'Untitled Course'); ?>
                                </h6>
                                <div class="text-muted small mb-2">
                                    <?php 
                                    $description = $course['description'] ?? '';
                                    echo htmlspecialchars(strlen($description) > MAX_DESCRIPTION_LENGTH ? 
                                        substr($description, 0, MAX_DESCRIPTION_LENGTH) . '...' : $description);
                                    ?>
                                </div>

                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">
                                            <?php echo isset($course['total_lessons']) ? (int)$course['total_lessons'] . ' lessons' : '24 lessons'; ?>
                                        </small>
                                        <div class="small text-muted">
                                            <?php echo isset($course['total_hours']) ? (int)$course['total_hours'] . ' hours' : '40 hours'; ?>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="courses/courses_detail.php?id=<?php echo htmlspecialchars($course['id'] ?? ''); ?>" 
                                           class="btn btn-outline-primary btn-sm">View Course</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- My Enrolled Courses -->
                <?php if (!empty($enrolled_courses)): ?>
                <div class="small-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">My Courses</h5>
                        <a href="#" class="small text-decoration-none">View All</a>
                    </div>

                    <div class="row g-3">
                        <?php foreach (array_slice($enrolled_courses, 0, 3) as $course): 
                            $progress = isset($course['progress_percentage']) ? (int)$course['progress_percentage'] : 0;
                        ?>
                        <div class="col-md-4">
                            <div class="course-card h-100">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 style="font-weight:700;">
                                            <?php echo htmlspecialchars($course['title']); ?>
                                        </h6>
                                        <div class="small text-muted">
                                            <?php 
                                            $description = $course['description'] ?? '';
                                            echo htmlspecialchars(strlen($description) > MAX_DESCRIPTION_LENGTH ? 
                                                substr($description, 0, MAX_DESCRIPTION_LENGTH) . '...' : $description);
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div class="progress" style="height:9px;border-radius:8px;background:#eef2ff;">
                                        <div class="progress-bar" 
                                             role="progressbar" 
                                             style="width:<?php echo $progress; ?>%;background:linear-gradient(90deg,#0b77ff,#6f8cff);" 
                                             aria-valuenow="<?php echo $progress; ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100"
                                             aria-label="Course progress: <?php echo $progress; ?>% complete">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted"><?php echo $progress; ?>% complete</small>


                                        <?php
											$isEnrolled = isset($course['progress_percentage']);
											$courseFolder = $course['folder_name'];

											if ($isEnrolled) {
												$link = "courses/{$courseFolder}/intro.php?id={$course['id']}";
												$label = "Continue";
											} else {
												$link = "courses_detail.php?id={$course['id']}";
												$label = "View Course";
											}
											?>
											<a href="<?php echo $link; ?>" class="btn btn-sm btn-primary">
												<?php echo $label; ?>
											</a>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Right Sidebar -->
            <div class="col-lg-4">
                <div class="position-sticky" style="top:96px;">
                    <!-- Calendar -->
                    <div class="small-card mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Calendar</h6>
                            <small id="calendar-date" class="text-muted"></small>
                        </div>
                        <div id="miniCalendar" class="calendar"></div>
                    </div>

                    <!-- Assignments -->
                    <div class="small-card mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Upcoming Assignments</h6>
                            <a href="#" class="small text-decoration-none">See All</a>
                        </div>
                        <div class="list-group list-group-flush">
                            <?php if (empty($assignments)): ?>
                                <div class="text-center py-3 text-muted">
                                    <i class="bi bi-check-circle-fill" style="font-size:2rem;color:#10b981;"></i>
                                    <div class="mt-2">No upcoming assignments!</div>
                                </div>
                            <?php else: ?>
                                <?php foreach ($assignments as $assignment): 
                                    $due = isset($assignment['due_date']) ? date('M j', strtotime($assignment['due_date'])) : 'TBD';
                                ?>
                                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                                    <div>
                                        <div style="font-weight:600;">
                                            <?php echo htmlspecialchars($assignment['title']); ?>
                                        </div>
                                        <div class="small text-muted">
                                            <?php echo htmlspecialchars($assignment['course_title'] ?? ''); ?> • Due <?php echo htmlspecialchars($due); ?>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="assignment.php?id=<?php echo htmlspecialchars($assignment['id'] ?? ''); ?>" 
                                           class="btn btn-sm btn-outline-primary">Open</a>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Progress Chart -->
                    <div class="small-card">
                        <h6 class="mb-3">My Progress</h6>
                        <div class="text-center">
                            <div style="position: relative; height: 180px; width: 180px; margin: 0 auto;">
                                <canvas id="progressDonut" role="img" aria-label="Progress chart showing <?php echo $progressPercent; ?>% completion"></canvas>
                            </div>
                            <div class="mt-3">
                                <div style="font-weight:700;font-size:1.1rem;"><?php echo $progressPercent; ?>%</div>
                                <div class="small text-muted">Average Progress</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
    function downloadMockPDF(data) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ unit: 'mm', format: 'a4' });
        const green = [16, 185, 129];
        const dark  = [30, 41, 59];
        const muted = [100, 116, 139];

        // Header bar
        doc.setFillColor(...green);
        doc.rect(0, 0, 210, 38, 'F');
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(20);
        doc.setFont('helvetica', 'bold');
        doc.text('IELTS Mock Test Results', 15, 18);
        doc.setFontSize(10);
        doc.setFont('helvetica', 'normal');
        doc.text(data.title, 15, 27);
        doc.text(data.date, 15, 33);

        // Candidate name
        doc.setTextColor(...dark);
        doc.setFontSize(13);
        doc.setFont('helvetica', 'bold');
        doc.text('Candidate: ' + data.name, 15, 52);

        // Overall band
        doc.setFillColor(240, 253, 244);
        doc.roundedRect(15, 58, 80, 28, 4, 4, 'F');
        doc.setFontSize(11);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(...muted);
        doc.text('Overall Band Score', 25, 67);
        doc.setFontSize(26);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(...green);
        doc.text(data.overall, 40, 81);

        // Section bands
        const sections = [['Listening', data.l], ['Reading', data.r], ['Writing', data.w], ['Speaking', data.s]];
        let x = 15;
        sections.forEach(([label, band]) => {
            doc.setFillColor(248, 250, 252);
            doc.roundedRect(x, 95, 42, 26, 3, 3, 'F');
            doc.setFontSize(9);
            doc.setFont('helvetica', 'normal');
            doc.setTextColor(...muted);
            doc.text(label, x + 5, 104);
            doc.setFontSize(18);
            doc.setFont('helvetica', 'bold');
            doc.setTextColor(...green);
            doc.text(band, x + 5, 116);
            x += 46;
        });

        // Footer
        doc.setFontSize(8);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(...muted);
        doc.text('Scholarly Language Services · slslanguage.com', 15, 285);
        doc.text('Generated ' + new Date().toLocaleDateString(), 150, 285);

        doc.save('IELTS_Mock_Results_' + data.date.replace(/ /g,'_') + '.pdf');
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

		// Mobile Menu Script
		const menuToggle = document.getElementById('menuToggle');
		const sidebarElement = document.querySelector('.sidebar');
		const mobileOverlay = document.getElementById('mobileOverlay');

		function toggleMenu() {
			sidebarElement.classList.toggle('active');
			mobileOverlay.classList.toggle('active');
			
			// Change icon
			const icon = menuToggle.querySelector('i');
			if (sidebarElement.classList.contains('active')) {
				icon.className = 'bi bi-x-lg';
			} else {
				icon.className = 'bi bi-list';
			}
		}

		if (menuToggle) {
			menuToggle.addEventListener('click', toggleMenu);
		}

		if (mobileOverlay) {
			mobileOverlay.addEventListener('click', toggleMenu);
		}

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

		// Close menu when a nav link is clicked on mobile
		const navLinks = document.querySelectorAll('.sidebar .nav-link');
		navLinks.forEach(link => {
			link.addEventListener('click', () => {
				if (window.innerWidth < 1200 && sidebarElement.classList.contains('active')) {
					toggleMenu();
				}
			});
		});

		// Chart Data from PHP
		const chartData = {
			months: <?php echo json_encode($months, JSON_UNESCAPED_SLASHES); ?>,
			studyHours: <?php echo json_encode($studyHours, JSON_NUMERIC_CHECK); ?>,
			testHours: <?php echo json_encode($testHours, JSON_NUMERIC_CHECK); ?>,
			progressPercent: <?php echo json_encode($progressPercent, JSON_NUMERIC_CHECK); ?>
		};

		// Study Statistics Chart
		function initStudyChart() {
			const ctx = document.getElementById('studyChart');
			if (!ctx) return;
			
			const ctxContext = ctx.getContext('2d');
			
			new Chart(ctxContext, {
				type: 'line',
				data: {
					labels: chartData.months,
					datasets: [
						{
							label: 'Study Hours',
							data: chartData.studyHours,
							fill: true,
							backgroundColor: 'rgba(11, 119, 255, 0.1)',
							borderColor: 'rgba(11, 119, 255, 0.8)',
							borderWidth: 2,
							tension: 0.4,
							pointRadius: 4,
							pointBackgroundColor: 'rgba(11, 119, 255, 1)',
							pointBorderColor: '#fff',
							pointBorderWidth: 2
						},
						{
							label: 'Test Hours',
							data: chartData.testHours,
							fill: true,
							backgroundColor: 'rgba(16, 185, 129, 0.1)',
							borderColor: 'rgba(16, 185, 129, 0.8)',
							borderWidth: 2,
							tension: 0.4,
							pointRadius: 4,
							pointBackgroundColor: 'rgba(16, 185, 129, 1)',
							pointBorderColor: '#fff',
							pointBorderWidth: 2
						}
					]
				},
				options: {
					responsive: true,
					maintainAspectRatio: true,
					aspectRatio: 2.2,
					plugins: {
						legend: {
							display: true,
							position: 'top',
							labels: {
								usePointStyle: true,
								padding: 20,
								font: {
									size: 12,
									weight: '500'
								}
							}
						},
						tooltip: {
							backgroundColor: 'rgba(0, 0, 0, 0.8)',
							titleColor: '#fff',
							bodyColor: '#fff',
							borderColor: 'rgba(255, 255, 255, 0.1)',
							borderWidth: 1,
							cornerRadius: 8,
							displayColors: true,
							callbacks: {
								label: function(context) {
									return context.dataset.label + ': ' + context.parsed.y.toFixed(1) + ' hours';
								}
							}
						}
					},
					scales: {
						x: {
							grid: {
								display: false
							},
							ticks: {
								font: {
									size: 11
								}
							}
						},
						y: {
							beginAtZero: true,
							max: Math.max(...chartData.studyHours, ...chartData.testHours) + 5,
							grid: {
								color: 'rgba(15, 23, 42, 0.04)',
								drawBorder: false
							},
							ticks: {
								font: {
									size: 11
								},
								stepSize: 5,
								callback: function(value) {
									return value + 'h';
								}
							}
						}
					},
					interaction: {
						intersect: false,
						mode: 'index'
					},
					elements: {
						line: {
							borderCapStyle: 'round'
						}
					}
				}
			});
		}

		// Progress Donut Chart
		function initProgressChart() {
			const ctx = document.getElementById('progressDonut');
			if (!ctx) return;
			
			const ctxContext = ctx.getContext('2d');
			
			const chart = new Chart(ctxContext, {
				type: 'doughnut',
				data: {
					datasets: [{
						data: [chartData.progressPercent, 100 - chartData.progressPercent],
						backgroundColor: ['#0b77ff', '#e6eefc'],
						borderWidth: 0,
						cutout: '75%'
					}]
				},
				options: {
					responsive: true,
					maintainAspectRatio: true,
					plugins: {
						legend: {
							display: false
						},
						tooltip: {
							enabled: false
						}
					}
				}
			});
			
			// Add center text
			Chart.register({
				id: 'centerText',
				beforeDraw: function(chart) {
					if (chart.canvas.id !== 'progressDonut') return;
					
					const ctx = chart.ctx;
					const centerX = chart.chartArea.left + (chart.chartArea.right - chart.chartArea.left) / 2;
					const centerY = chart.chartArea.top + (chart.chartArea.bottom - chart.chartArea.top) / 2;
					
					ctx.save();
					ctx.textAlign = 'center';
					ctx.textBaseline = 'middle';
					ctx.font = 'bold 20px Inter, sans-serif';
					ctx.fillStyle = document.body.classList.contains('dark') ? '#e4e6eb' : '#0f172a';
					ctx.fillText(chartData.progressPercent + '%', centerX, centerY);
					ctx.restore();
				}
			});
		}

		// Mini Calendar with Navigation
		let calendarState = {
			currentYear: new Date().getFullYear(),
			currentMonth: new Date().getMonth(),
			selectedDate: null
		};

		const monthNames = [
			"January", "February", "March", "April", "May", "June",
			"July", "August", "September", "October", "November", "December"
		];

		function initMiniCalendar() {
			renderCalendar();
			
			// Add keyboard navigation
			document.addEventListener('keydown', (e) => {
				const calendarEl = document.getElementById('miniCalendar');
				if (!calendarEl || !document.body.contains(calendarEl)) return;
				
				if (e.key === 'ArrowLeft' && e.ctrlKey) {
					changeMonth(-1);
				} else if (e.key === 'ArrowRight' && e.ctrlKey) {
					changeMonth(1);
				}
			});
		}

		function renderCalendar() {
			const calendarEl = document.getElementById('miniCalendar');
			const headerEl = document.getElementById('calendar-date');
			
			if (!calendarEl) return;
			
			const { currentYear, currentMonth } = calendarState;
			const today = new Date();
			const todayDate = today.getDate();
			const todayMonth = today.getMonth();
			const todayYear = today.getFullYear();
			
			// Update header
			if (headerEl) {
				headerEl.textContent = `${monthNames[currentMonth]} ${currentYear}`;
			}
			
			const firstDay = new Date(currentYear, currentMonth, 1).getDay();
			const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
			
			let calendarHTML = `
				<div class="d-flex justify-content-between align-items-center mb-3">
					<button class="btn btn-sm btn-link text-decoration-none p-0" 
							onclick="changeMonth(-1)" 
							aria-label="Previous month"
							style="font-size:1.2rem;color:#6c757d;">
						‹
					</button>
					<div class="small text-muted fw-bold" style="letter-spacing:0.5px;">
						Su Mo Tu We Th Fr Sa
					</div>
					<button class="btn btn-sm btn-link text-decoration-none p-0" 
							onclick="changeMonth(1)" 
							aria-label="Next month"
							style="font-size:1.2rem;color:#6c757d;">
						›
					</button>
				</div>
			`;
			
			calendarHTML += '<div style="display:grid;grid-template-columns:repeat(7,1fr);gap:6px;">';
			
			// Empty cells for days before month starts
			for (let i = 0; i < firstDay; i++) {
				calendarHTML += '<div style="padding:8px;"></div>';
			}
			
			// Days of the month
			for (let day = 1; day <= daysInMonth; day++) {
				const isToday = day === todayDate && 
							currentMonth === todayMonth && 
							currentYear === todayYear;
				
				const isSelected = calendarState.selectedDate && 
								day === calendarState.selectedDate.day && 
								currentMonth === calendarState.selectedDate.month && 
								currentYear === calendarState.selectedDate.year;
				
				let dayClass = 'bg-light';
				let textClass = 'text-dark';
				let extraStyle = '';
				
				if (isToday) {
					dayClass = 'bg-primary';
					textClass = 'text-white fw-bold';
					extraStyle = 'box-shadow:0 2px 8px rgba(13,110,253,0.3);';
				} else if (isSelected) {
					dayClass = 'bg-primary bg-opacity-10';
					textClass = 'text-primary fw-semibold';
					extraStyle = 'border:2px solid var(--bs-primary);';
				}
				
				calendarHTML += `
					<button 
						class="${dayClass} ${textClass} border-0" 
						style="padding:8px;border-radius:8px;text-align:center;font-size:0.85rem;cursor:pointer;transition:all 0.2s;${extraStyle}"
						onclick="selectDate(${day}, ${currentMonth}, ${currentYear})"
						onmouseover="if(!this.classList.contains('bg-primary')) { this.style.backgroundColor='#e9ecef'; this.style.transform='scale(1.05)'; }"
						onmouseout="if(!this.classList.contains('bg-primary')) { this.style.backgroundColor=''; this.style.transform=''; }"
						aria-label="${monthNames[currentMonth]} ${day}, ${currentYear}"
						title="${monthNames[currentMonth]} ${day}, ${currentYear}">
						${day}
					</button>
				`;
			}
			
			calendarHTML += '</div>';
			
			// Add "Today" button
			if (currentMonth !== todayMonth || currentYear !== todayYear) {
				calendarHTML += `
					<button class="btn btn-sm btn-outline-primary w-100 mt-3" 
							onclick="goToToday()"
							style="border-radius:8px;">
						Go to Today
					</button>
				`;
			}
			
			calendarEl.innerHTML = calendarHTML;
		}

		function changeMonth(direction) {
			calendarState.currentMonth += direction;
			
			if (calendarState.currentMonth < 0) {
				calendarState.currentMonth = 11;
				calendarState.currentYear--;
			} else if (calendarState.currentMonth > 11) {
				calendarState.currentMonth = 0;
				calendarState.currentYear++;
			}
			
			renderCalendar();
		}

		function selectDate(day, month, year) {
			calendarState.selectedDate = { day, month, year };
			renderCalendar();
			
			// Optional: Trigger custom event for integration with other components
			const date = new Date(year, month, day);
			const event = new CustomEvent('calendarDateSelected', { 
				detail: { 
					date: date,
					formatted: date.toLocaleDateString('en-US', { 
						weekday: 'long', 
						year: 'numeric', 
						month: 'long', 
						day: 'numeric' 
					})
				} 
			});
			document.dispatchEvent(event);
			
			// Optional: Show selected date feedback
			console.log(`Selected: ${monthNames[month]} ${day}, ${year}`);
		}

		function goToToday() {
			const today = new Date();
			calendarState.currentYear = today.getFullYear();
			calendarState.currentMonth = today.getMonth();
			selectDate(today.getDate(), today.getMonth(), today.getFullYear());
		}

		// Initialize calendar when DOM is ready
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', initMiniCalendar);
		} else {
			initMiniCalendar();
		}

		// Optional: Listen for date selection events in other parts of your app
		document.addEventListener('calendarDateSelected', (e) => {
			// Example: You can filter assignments by selected date here
			console.log('Date selected:', e.detail.formatted);
		});

		// Search functionality
		function initSearch() {
			const searchInput = document.querySelector('input[placeholder="Search courses..."]');
			if (!searchInput) return;
			
			let searchTimeout;
			searchInput.addEventListener('input', function() {
				clearTimeout(searchTimeout);
				const query = this.value.trim();
				
				if (query.length === 0) return;
				
				searchTimeout = setTimeout(() => {
					// Here you would typically make an AJAX request to search
					console.log('Searching for:', query);
				}, 300);
			});
		}

		// Loading states
		function showLoading(element) {
			if (element) {
				element.classList.add('loading');
			}
		}

		function hideLoading(element) {
			if (element) {
				element.classList.remove('loading');
			}
		}

		// Error handling
		function showError(message, container) {
			if (!container) return;
			
			const errorDiv = document.createElement('div');
			errorDiv.className = 'error-message';
			errorDiv.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-2"></i>${message}`;
			
			container.appendChild(errorDiv);
			
			setTimeout(() => {
				errorDiv.remove();
			}, 5000);
		}

		// Accessibility enhancements
		function initAccessibility() {
			// Keyboard navigation for course cards
			const courseCards = document.querySelectorAll('.course-card');
			courseCards.forEach(card => {
				card.setAttribute('tabindex', '0');
				card.addEventListener('keypress', function(e) {
					if (e.key === 'Enter' || e.key === ' ') {
						const link = card.querySelector('a');
						if (link) {
							link.click();
						}
					}
				});
			});

			// Theme toggle
			const themeToggle = document.getElementById('themeToggle');
			if (themeToggle) {
				themeToggle.addEventListener('click', function() {
					const isDark = document.body.classList.contains('dark');
					const newTheme = isDark ? 'light' : 'dark';
					document.body.classList.remove('light', 'dark');
					document.body.classList.add(newTheme);
					themeToggle.textContent = newTheme === 'dark' ? '☀️ Light Mode' : '🌙 Dark Mode';
					localStorage.setItem('eduhub-theme', newTheme);
				});
			}
		}

		// Performance optimizations
		function initPerformance() {
			// Lazy load images
			const images = document.querySelectorAll('img[loading="lazy"]');
			if ('IntersectionObserver' in window) {
				const imageObserver = new IntersectionObserver((entries, observer) => {
					entries.forEach(entry => {
						if (entry.isIntersecting) {
							const img = entry.target;
							img.src = img.dataset.src || img.src;
							img.classList.remove('lazy');
							imageObserver.unobserve(img);
						}
					});
				});

				images.forEach(img => imageObserver.observe(img));
			}

			// Debounced window resize
			let resizeTimeout;
			window.addEventListener('resize', () => {
				clearTimeout(resizeTimeout);
				resizeTimeout = setTimeout(() => {
					// Redraw charts on resize
					Chart.helpers.each(Chart.instances, (instance) => {
						instance.resize();
					});
				}, 250);
			});
		}

		// Initialize everything when DOM is loaded
		document.addEventListener('DOMContentLoaded', function() {
			try {
				initStudyChart();
				initProgressChart();
				initMiniCalendar();
				initSearch();
				initAccessibility();
				initPerformance();
				
				// Add some loading animations
				document.querySelectorAll('.stat-card, .course-card, .small-card').forEach((card, index) => {
					card.style.opacity = '0';
					card.style.transform = 'translateY(20px)';
					card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
					
					setTimeout(() => {
						card.style.opacity = '1';
						card.style.transform = 'translateY(0)';
					}, index * 50);
				});
				
			} catch (error) {
				console.error('Error initializing dashboard:', error);
				showError('Some features may not be working properly. Please refresh the page.', 
						document.querySelector('.content'));
			}
		});

		// Service Worker for caching (optional)
		if ('serviceWorker' in navigator) {
			window.addEventListener('load', function() {
				navigator.serviceWorker.register('/sw.js').then(function(registration) {
					console.log('SW registered: ', registration);
				}).catch(function(registrationError) {
					console.log('SW registration failed: ', registrationError);
				});
			});
		}
	</script>

	<!-- Footer (ads suppressed on dashboard — it has its own right column) -->
	<?php define('ADVERTS_RENDERED', true); ?>
	<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>