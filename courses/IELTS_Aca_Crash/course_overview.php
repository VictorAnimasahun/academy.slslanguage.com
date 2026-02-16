<?php
require_once (dirname(dirname(__DIR__))) . '/bootstrap.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+courses");
    exit();
}

$user_id = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT);
$course_id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

if (!$user_id || !$course_id) {
    header("Location: ../courses_catalogue.php");
    exit();
}

// === FETCH COURSE + ENROLLMENT STATUS ===
$sql = "SELECT c.*,
               CASE WHEN e.student_id IS NOT NULL THEN 1 ELSE 0 END as is_enrolled,
               COALESCE(e.progress_percentage, 0) as progress_percentage,
               e.enrolled_at
        FROM courses c
        LEFT JOIN enrollments e ON c.id = e.course_id AND e.student_id = ?
        WHERE c.id = ?";

try {
    $stmt = $db->prepare($sql);
    $stmt->execute([$user_id, $course_id]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $course = null;
}

if (!$course) {
    header("Location: ../courses_catalogue.php?message=Course+not+found");
    exit();
}

// === HANDLE ENROLLMENT ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll']) && !$course['is_enrolled']) {
    try {
        $enrollSQL = "INSERT INTO enrollments (student_id, course_id, enrolled_at, progress_percentage) 
                      VALUES (?, ?, NOW(), 0)";
        $enrollStmt = $db->prepare($enrollSQL);
        $enrollStmt->execute([$user_id, $course_id]);
        header("Location: courses_detail.php?id=" . $course_id . "&enrolled=1");
        exit();
    } catch (PDOException $e) {
        error_log("Enrollment error: " . $e->getMessage());
        $error_message = "Failed to enroll. Please try again.";
    }
}

$courseFolder = $course['folder_name'];
$userName     = $_SESSION['user_firstname'] ?? 'Learner';

// === CALCULATE TOTAL LESSONS & HOURS ===
$totalLessonsSql = "SELECT COUNT(*) FROM lessons WHERE course_id = ?";
$totalLessonsStmt = $db->prepare($totalLessonsSql);
$totalLessonsStmt->execute([$course_id]);
$total_lessons = $totalLessonsStmt->fetchColumn();

$totalMinutesSql = "SELECT COALESCE(SUM(duration_minutes), 0) FROM lessons WHERE course_id = ?";
$totalMinutesStmt = $db->prepare($totalMinutesSql);
$totalMinutesStmt->execute([$course_id]);
$total_minutes = $totalMinutesStmt->fetchColumn();
$total_hours = round($total_minutes / 60, 1);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($course['title']); ?> - EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
	<?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    
    <style>

        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.3);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .content {
            margin-left: 260px;
        }

        .course-card {
            background: #fff;
            border-radius: var(--card-radius);
            padding: 2rem;
            box-shadow: 0 6px 18px rgba(15,23,42,0.04);
        }

        .badge-free {
            background: linear-gradient(90deg, #059669, #10b981);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
        }

        .badge-paid {
            background: linear-gradient(90deg, #dc2626, #f87171);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
        }

        .badge-enrolled {
            background: linear-gradient(90deg, #10b981, #34d399);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
        }

        @media (max-width: 1199px) {
            .sidebar { position: relative; width: 100%; min-height: auto; }
            .content { margin-left: 0; }
        }
    </style>
</head>
<body>
    <!-- Mobile Header -->
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <!-- Sidebar Navigation -->
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <!-- Main Content -->
    <main class="content">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container hero-content">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <nav aria-label="breadcrumb" class="mb-3">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="../courses_catalogue.php" class="text-white-50 text-decoration-none">Courses</a>
                                </li>
                                <li class="breadcrumb-item active text-white" aria-current="page">
                                    <?php echo htmlspecialchars($course['title']); ?>
                                </li>
                            </ol>
                        </nav>

                        <h1 class="display-5 fw-bold mb-3"><?php echo htmlspecialchars($course['title']); ?></h1>
                        
                        <p class="lead mb-4">
                            <?php echo htmlspecialchars($course['description'] ?? 'Comprehensive course designed to help you master new skills.'); ?>
                        </p>

                        <div class="d-flex flex-wrap gap-3 mb-4">
                            <?php if ($course['is_free']): ?>
                                <span class="badge-free">
                                    <i class="bi bi-gift me-1"></i>FREE COURSE
                                </span>
                            <?php else: ?>
                                <span class="badge-paid">
                                    $<?php echo number_format($course['price'], 2); ?>
                                </span>
                            <?php endif; ?>

                            <?php if ($course['is_enrolled']): ?>
                                <span class="badge-enrolled">
                                    <i class="bi bi-check-circle me-1"></i>ENROLLED
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex flex-wrap gap-4 text-sm">
                            <div>
                                <i class="bi bi-play-circle me-1"></i>
                                <strong><?= $total_lessons ?></strong> Lessons
                            </div>
                            <div>
                                <i class="bi bi-clock me-1"></i>
                                <strong><?= $total_hours ?></strong> Hours
                            </div>

                            <div>
                                <i class="bi bi-person me-1"></i>
                                <strong><?php echo htmlspecialchars($course['instructor_name'] ?? 'EduHub Team'); ?></strong>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="course-card text-center">
                            <?php if (isset($_GET['enrolled'])): ?>
                                <div class="alert alert-success">
                                    <i class="bi bi-check-circle me-2"></i>Successfully enrolled!
                                </div>
                            <?php endif; ?>

                            <?php if (isset($error_message)): ?>
                                <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                            <?php endif; ?>

                            <?php if ($course['is_enrolled']): ?>
                                <!-- Already Enrolled -->
                                <div class="mb-3">
                                    <i class="bi bi-check-circle-fill text-success" style="font-size: 2.5rem;"></i>
                                    <h5 class="mt-2">You're Enrolled!</h5>
                                </div>
                                
                                <?php if ($course['progress_percentage'] > 0): ?>
                                    <div class="mb-3">
                                        <h6>Your Progress</h6>
                                        <div class="progress mb-2" style="height: 10px;">
                                            <div class="progress-bar bg-success" 
                                                 style="width: <?php echo (int)$course['progress_percentage']; ?>%"></div>
                                        </div>
                                        <small class="text-muted"><?php echo (int)$course['progress_percentage']; ?>% Complete</small>
                                    </div>
                                <?php endif; ?>
                                
                                <a href="../<?php echo $courseFolder; ?>/intro.php?id=<?php echo $course['id']; ?>" 
                                   class="btn btn-success btn-lg w-100 mb-2">
                                    <i class="bi bi-play-fill me-2"></i>
                                    <?php echo $course['progress_percentage'] > 0 ? 'Continue Learning' : 'Start Course'; ?>
                                </a>
                                
                                <small class="text-muted">
                                    Enrolled <?php echo date('M j, Y', strtotime($course['enrolled_at'] ?? 'now')); ?>
                                </small>
                                
                            <?php else: ?>
                                <!-- Not Enrolled - Show Enrollment Options -->
                                <div class="mb-3">
                                    <i class="bi bi-mortarboard-fill text-primary" style="font-size: 2.5rem;"></i>
                                    <h5 class="mt-2">Ready to Start Learning?</h5>
                                </div>
                                
                                <!-- Price Display -->
                                <div class="mb-3">
                                    <?php if ($course['is_free']): ?>
                                        <div class="h3 text-success mb-0">FREE</div>
                                        <small class="text-muted">No payment required</small>
                                    <?php else: ?>
                                        <div class="h3 text-primary mb-0">$<?php echo number_format($course['price'], 2); ?></div>
                                        <small class="text-muted">One-time payment</small>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Enrollment Form -->
                                <form method="POST" class="d-grid gap-2">
                                    <?php if ($course['is_free']): ?>
                                        <!-- Free Course Enrollment -->
                                        <button type="submit" name="enroll" class="btn btn-success btn-lg">
                                            <i class="bi bi-plus-circle me-2"></i>Enroll for Free
                                        </button>
                                    <?php else: ?>
                                        <!-- Paid Course Options -->
                                        <button type="submit" name="enroll" class="btn btn-primary btn-lg">
                                            <i class="bi bi-credit-card me-2"></i>Enroll Now - $<?php echo number_format($course['price'], 2); ?>
                                        </button>
                                        <small class="text-muted mt-1">
                                            <i class="bi bi-shield-check me-1"></i>30-day money-back guarantee
                                        </small>
                                    <?php endif; ?>
                                </form>
                                
                                <!-- Course Includes -->
                                <div class="mt-4 text-start">
                                    <h6 class="mb-2">This course includes:</h6>
                                    <ul class="list-unstyled small">
                                        <li class="mb-1">
                                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                                            <?php echo (int)($course['total_lessons'] ?? 12); ?> lessons
                                        </li>
                                        <li class="mb-1">
                                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                                            <?php echo (int)($course['total_hours'] ?? 8); ?> hours of content
                                        </li>
                                        <li class="mb-1">
                                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                                            Lifetime access
                                        </li>
                                        <li class="mb-1">
                                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                                            Certificate of completion
                                        </li>
                                        <li class="mb-1">
                                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                                            Mobile and desktop access
                                        </li>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Course Details -->
        <section class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="course-card mb-4">
                            <h3 class="mb-3">About This Course</h3>
                            <p><?php echo nl2br(htmlspecialchars($course['description'] ?? 'This comprehensive course will provide you with all the knowledge and skills needed to succeed.')); ?></p>
                            
                            <h4 class="mt-4 mb-3">What You'll Learn</h4>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Master fundamental concepts</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Apply practical skills</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Build confidence through practice</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Prepare for real-world applications</li>
                            </ul>
                        </div>

                        <div class="course-card">
                            <h3 class="mb-3">Course Content</h3>

                            <?php
							// === DYNAMICALLY LOAD MODULES & LESSONS FOR THIS COURSE ===
							try {
								// First: Get all modules for this course, ordered correctly
								$moduleSql = "SELECT id, module_title, module_order 
											FROM modules 
											WHERE course_id = ? 
											ORDER BY module_order ASC";
								$moduleStmt = $db->prepare($moduleSql);
								$moduleStmt->execute([$course_id]);
								$modules = $moduleStmt->fetchAll(PDO::FETCH_ASSOC);

								if (empty($modules)) {
									echo '<p class="text-muted">No modules available yet for this course.</p>';
								} else {
									?>
									<div class="accordion" id="courseAccordion">
										<?php 
										foreach ($modules as $index => $module):
											$moduleId = 'module' . $module['id']; // unique ID using real module ID
											$isFirst = $index === 0;
											?>
											<div class="accordion-item">
												<h2 class="accordion-header">
													<button class="accordion-button <?= !$isFirst ? 'collapsed' : '' ?>" 
															type="button" 
															data-bs-toggle="collapse" 
															data-bs-target="#<?= $moduleId ?>">
														<i class="bi bi-folder me-2"></i>
														<?= htmlspecialchars($module['module_title']) ?>
														<span class="badge bg-light text-dark ms-auto me-2">
															<?php
															// Count lessons in this module
															$countSql = "SELECT COUNT(*) FROM lessons WHERE module_id = ? AND course_id = ?";
															$countStmt = $db->prepare($countSql);
															$countStmt->execute([$module['id'], $course_id]);
															echo $countStmt->fetchColumn();
															?> lessons
														</span>
													</button>
												</h2>
												<div id="<?= $moduleId ?>" 
													class="accordion-collapse collapse <?= $isFirst ? 'show' : '' ?>" 
													data-bs-parent="#courseAccordion">
													<div class="accordion-body">
														<?php
														// Now load lessons for this specific module
														$lessonSql = "SELECT title, file_path, video_url, icon, duration_minutes
																	FROM lessons 
																	WHERE module_id = ? AND course_id = ?
																	ORDER BY lesson_order ASC";
														$lessonStmt = $db->prepare($lessonSql);
														$lessonStmt->execute([$module['id'], $course_id]);
														$lessons = $lessonStmt->fetchAll(PDO::FETCH_ASSOC);

														if (empty($lessons)): ?>
															<p class="text-muted small">No lessons in this module yet.</p>
														<?php else: ?>
															<ul class="list-unstyled">
																<?php foreach ($lessons as $lesson): 
																	$hasContent = !empty($lesson['file_path']) || !empty($lesson['video_url']);
																	$icon = $lesson['icon'] ?? 'bi-play-circle';
																	$duration = $lesson['duration_minutes'] ?? 0;
																	?>
																	<li class="d-flex justify-content-between align-items-center py-2 border-bottom">
																		<div class="d-flex align-items-center">
																			<?php if ($course['is_enrolled'] && $hasContent): 
																				// Build the correct path: /courses/course_folder_name/lesson_file_or_video
																				$lessonLink = "../{$courseFolder}/";
																				if ($lesson['file_path']) {
																					$lessonLink .= basename($lesson['file_path']); // e.g., reading_passage_1.pdf
																				} elseif ($lesson['video_url']) {
																					$lessonLink .= "video.php?url=" . urlencode($lesson['video_url']);
																				} else {
																					$lessonLink .= "#"; // fallback
																				}
																				?>
																				<a href="<?= $lessonLink ?>?id=<?= $course_id ?>" 
																				class="text-decoration-none text-dark d-flex align-items-center">
																					<i class="bi <?= $icon ?> me-2 text-primary"></i>
																					<?= htmlspecialchars($lesson['title']) ?>
																				</a>
																			<?php else: ?>
																				<span class="d-flex align-items-center text-muted">
																					<i class="bi <?= $icon ?> me-2"></i>
																					<?= htmlspecialchars($lesson['title']) ?>
																					<?php if (!$course['is_enrolled']): ?>
																						<i class="bi bi-lock-fill ms-2" title="Enroll to access"></i>
																					<?php endif; ?>
																				</span>
																			<?php endif; ?>
																		</div>
																		<small class="text-muted">
																			<?= $duration > 0 ? $duration . ' min' : '' ?>
																		</small>
																	</li>
																<?php endforeach; ?>
															</ul>
														<?php endif; ?>
													</div>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
									<?php
								}
							} catch (Exception $e) {
								error_log("Error loading modules/lessons: " . $e->getMessage());
								echo '<div class="alert alert-danger">Failed to load course content. Please try again later.</div>';
							}
							?>
                            
                            <?php if (!$course['is_enrolled']): ?>
                                <div class="mt-3 p-3 bg-light rounded">
                                    <div class="text-center">
                                        <i class="bi bi-lock-fill text-muted mb-2" style="font-size: 2rem;"></i>
                                        <p class="text-muted mb-2">Course content is locked</p>
                                        <p class="small text-muted">Enroll in this course to access all modules and lessons</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>


                    </div>

                    <div class="col-lg-4">
                        <div class="course-card">
                            <h5 class="mb-3">Course Features</h5>
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <i class="bi bi-laptop text-primary me-2"></i>
                                    <strong>Online Learning</strong><br>
                                    <small class="text-muted">Access from anywhere, anytime</small>
                                </li>
                                <li class="mb-3">
                                    <i class="bi bi-infinity text-primary me-2"></i>
                                    <strong>Lifetime Access</strong><br>
                                    <small class="text-muted">Learn at your own pace</small>
                                </li>
                                <li class="mb-3">
                                    <i class="bi bi-award text-primary me-2"></i>
                                    <strong>Certificate</strong><br>
                                    <small class="text-muted">Get certified upon completion</small>
                                </li>
                                <li class="mb-3">
                                    <i class="bi bi-headset text-primary me-2"></i>
                                    <strong>Support</strong><br>
                                    <small class="text-muted">Direct access to instructors</small>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>