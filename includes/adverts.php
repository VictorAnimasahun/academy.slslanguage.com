<?php if (defined('ADVERTS_RENDERED')) return; define('ADVERTS_RENDERED', true); ?>
<!-- Right Advertisement Sidebar -->
    <aside class="advert-sidebar">
		 <!-- Internal Promo -->
        <div class="course-card" style="background: #fff; color: #0f172a;">
            <h6 class="mb-2">Quick Access</h6>
            <div class="d-grid gap-2">
                <a href="<?= ACADEMY_URL ?>resources/quizzes/quizzes_home.php" class="btn btn-sm" style="background:#0b77ff; border-color:#0b77ff; color:#fff;">
                    <i class="bi bi-question-circle me-2"></i>Knowledge Quiz
                </a>
                <a href="<?= ACADEMY_URL ?>resources/practice_tests/practice_tests_home.php" class="btn btn-sm" style="background:#0b77ff; border-color:#0b77ff; color:#fff;">
                    <i class="bi bi-pencil-square me-2"></i>Practice Tasks
                </a>
                <a href="<?= ACADEMY_URL ?>courses/courses_catalogue.php" class="btn btn-sm" style="border:1px solid #0b77ff; color:#0b77ff; background:#fff;">
                    Browse All Courses
                </a>
            </div>
        </div>

		<h6 class="mb-3 text-muted">
            <i class="bi bi-megaphone me-2"></i>Sponsored
        </h6>
		<!-- Ad Container 1 -->
        <div class="ad-container" id="ad-slot-2">
            <div class="ad-placeholder">
                <i class="bi bi-badge-ad" style="font-size: 1.5rem; opacity: 0.3;"></i>
                <p class="mt-2 mb-0">Advertisement Space</p>
                <small>300x250</small>
            </div>
        </div>

        <!-- Ad Container 2 -->
        <div class="ad-container" id="ad-slot-2">
            <div class="ad-placeholder">
                <i class="bi bi-badge-ad" style="font-size: 1.5rem; opacity: 0.3;"></i>
                <p class="mt-2 mb-0">Advertisement Space</p>
                <small>300x250</small>
            </div>
        </div>
    </aside>