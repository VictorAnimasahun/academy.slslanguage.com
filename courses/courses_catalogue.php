<?php
require_once dirname(__DIR__) . '/bootstrap.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../edu_hub_registration.php?message=Please+login+to+access+courses");
    exit();
}

$user_id = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT);
if (!$user_id) {
    header("Location: ../../../login.php");
    exit();
}

// Get filter parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$price_filter = isset($_GET['price']) ? trim($_GET['price']) : '';

// Build query conditions
$conditions = [];
$params = [];

if (!empty($search)) {
    $conditions[] = "(c.title LIKE ? OR c.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($price_filter === 'free') {
    $conditions[] = "c.is_free = 1";
} elseif ($price_filter === 'paid') {
    $conditions[] = "c.is_free = 0";
}

$whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

// Get all courses with enrollment status
$sql = "SELECT c.*, 
        CASE WHEN e.student_id IS NOT NULL THEN 1 ELSE 0 END as is_enrolled,
        COALESCE(e.progress_percentage, 0) as progress_percentage
        FROM courses c
        LEFT JOIN enrollments e ON c.id = e.course_id AND e.student_id = ?
        $whereClause
        ORDER BY c.created_at DESC";

array_unshift($params, $user_id);

try {
    // Prepare and execute
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Debug output (hidden in HTML comments)
    echo "\n<!-- ===== DEBUG START ===== -->\n";
    echo "<!-- SQL Query: " . htmlspecialchars($sql) . " -->\n";
    echo "<!-- Parameters: " . htmlspecialchars(print_r($params, true)) . " -->\n";
    echo "<!-- Number of courses found: " . count($courses) . " -->\n";
    echo "<!-- Courses data: " . htmlspecialchars(print_r($courses, true)) . " -->\n";
    echo "<!-- ===== DEBUG END ===== -->\n\n";
    
} catch (PDOException $e) {
    echo "\n<!-- ERROR: " . htmlspecialchars($e->getMessage()) . " -->\n\n";
    error_log("Database error: " . $e->getMessage());
    $courses = [];
}

// For now, use static categories until you add the column
$categories = ['IELTS', 'PTE', 'Design', 'Programming', 'Business'];

$userName = isset($_SESSION['user_firstname']) ? htmlspecialchars($_SESSION['user_firstname']) : 'Learner';

// Base URL to reach /includes/ from /courses/CELPIP_intro/

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Course Catalog - EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/courses_catalogue.css">
	<?php include INCLUDES_PATH . '/navbar_styles.php'; ?>	
</head>
<body>
	<!-- Mobile Header -->
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>

    <!-- Sidebar Navigation -->
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <!-- Topbar -->
    <?php include INCLUDES_PATH . '/topbar.php'; ?>

    <!-- Main Content -->
    <main class="content">
        <!-- Filters -->
        <div class="filter-card">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Search Courses</label>
                    <input type="text" class="form-control" name="search" 
                           value="<?php echo htmlspecialchars($search); ?>" 
                           placeholder="Course title or description...">
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Price</label>
                    <select class="form-select" name="price">
                        <option value="">All Courses</option>
                        <option value="free" <?php echo $price_filter === 'free' ? 'selected' : ''; ?>>Free Only</option>
                        <option value="paid" <?php echo $price_filter === 'paid' ? 'selected' : ''; ?>>Paid Only</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-semibold">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-search me-1"></i>Search
                        </button>
                        <a href="index.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Results Count -->
        <div class="mb-4">
            <h5 class="mb-0"><?php echo count($courses); ?> Courses Found</h5>
            <?php if (!empty($search) || !empty($price_filter)): ?>
                <p class="text-muted mb-0">
                    Filtered by: 
                    <?php if (!empty($search)): ?>
                        <span class="badge bg-light text-dark me-1">"<?php echo htmlspecialchars($search); ?>"</span>
                    <?php endif; ?>
                    <?php if (!empty($price_filter)): ?>
                        <span class="badge bg-light text-dark me-1"><?php echo ucfirst($price_filter); ?> courses</span>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- Course Grid -->
        <?php if (empty($courses)): ?>
            <div class="text-center py-5">
                <i class="bi bi-search" style="font-size: 4rem; color: var(--muted);"></i>
                <h4 class="mt-3">No courses found</h4>
                <p class="text-muted">Try adjusting your search criteria or browse all courses.</p>
                <a href="index.php" class="btn btn-primary">View All Courses</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($courses as $course): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="course-card d-flex flex-column">
                            <!-- Course Image -->
                            <div class="mb-3" style="height: 180px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
                                <?php if (isset($course['thumbnail']) && !empty($course['thumbnail'])): ?>
                                    <img src="<?php echo htmlspecialchars($course['thumbnail']); ?>" 
                                         alt="Course thumbnail" 
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <i class="bi bi-book-half" style="font-size: 3rem; color: rgba(255,255,255,0.7);"></i>
                                <?php endif; ?>
                                
                                <!-- Status badges -->
                                <div style="position: absolute; top: 10px; right: 10px;">
                                    <?php if ($course['is_enrolled']): ?>
                                        <span class="badge-enrolled">
                                            <i class="bi bi-check-circle me-1"></i>Enrolled
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <div style="position: absolute; top: 10px; left: 10px;">
                                    <?php if ($course['is_free']): ?>
                                        <span class="badge-free">FREE</span>
                                    <?php else: ?>
                                        <span class="badge-paid">$<?php echo number_format($course['price'], 2); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Course Info -->
                            <div class="flex-grow-1 d-flex flex-column">
                                <div class="mb-2">
                                    <!-- Simple category badge based on course title or create a basic category -->
                                    <span class="badge-category">
                                        <?php 
                                        $courseTitle = strtoupper($course['title']);
                                        if (strpos($courseTitle, 'IELTS') !== false) echo 'IELTS';
                                        elseif (strpos($courseTitle, 'PTE') !== false) echo 'PTE';
                                        elseif (strpos($courseTitle, 'DESIGN') !== false) echo 'Design';
                                        elseif (strpos($courseTitle, 'PHP') !== false || strpos($courseTitle, 'PROGRAMMING') !== false) echo 'Programming';
                                        else echo 'General';
                                        ?>
                                    </span>
                                </div>

                                <h5 class="fw-bold mb-2"><?php echo htmlspecialchars($course['title']); ?></h5>
                                
                                <p class="text-muted mb-3" style="font-size: 0.9rem; line-height: 1.5;">
                                    <?php 
                                    $description = $course['description'] ?? '';
                                    echo htmlspecialchars(strlen($description) > 120 ? substr($description, 0, 120) . '...' : $description); 
                                    ?>
                                </p>

                                <!-- Course Meta -->
                                <div class="mb-3 small text-muted">
                                    <div class="d-flex justify-content-between">
                                        <span>
                                            <i class="bi bi-play-circle me-1"></i>
                                            <?php echo (int)($course['total_lessons'] ?? 12); ?> lessons
                                        </span>
                                        <span>
                                            <i class="bi bi-clock me-1"></i>
                                            <?php echo (int)($course['total_hours'] ?? 8); ?> hours
                                        </span>
                                    </div>
                                    
                                    <div class="mt-1">
                                        <i class="bi bi-person me-1"></i>
                                        <?php echo htmlspecialchars($course['instructor_name'] ?? 'EduHub Team'); ?>
                                    </div>
                                </div>

                                <!-- Progress Bar for Enrolled Courses -->
                                <?php if ($course['is_enrolled'] && $course['progress_percentage'] > 0): ?>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="small text-muted">Progress</span>
                                            <span class="small fw-semibold"><?php echo (int)$course['progress_percentage']; ?>%</span>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" 
                                                 style="width: <?php echo (int)$course['progress_percentage']; ?>%"></div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Action Button -->
                                <div class="mt-auto">
                                    <?php if ($course['is_enrolled']): ?>
                                        <a href="<?php echo htmlspecialchars($course['folder_name']); ?>/course_overview.php?id=<?php echo $course['id']; ?>" 
										class="btn btn-success w-100">
											<i class="bi bi-play-fill me-1"></i>
											<?php echo $course['progress_percentage'] > 0 ? 'Continue Learning' : 'Start Course'; ?>
										</a>

                                    <?php else: ?>
                                        <a href="courses_detail.php?id=<?php echo $course['id']; ?>" 
                                           class="btn btn-primary w-100">
                                            <i class="bi bi-info-circle me-1"></i>View Details
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    
    <script>
        // Add some nice animations
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.course-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
	<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>