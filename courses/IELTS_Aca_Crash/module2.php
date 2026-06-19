<?php
require_once (dirname(dirname(__DIR__))) . '/bootstrap.php';

// Restrict access if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+this+course");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS Academic Crash Course - Module 2 (Listening Skills)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
	<link href="../../assets/css/courses.css" rel="stylesheet">
	<?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
</head>

<body>
    <!-- Mobile Header -->
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <!-- Sidebar Navigation -->
    <?php include INCLUDES_PATH . '/navbar.php'; ?>


    <!-- Main Content -->
    <main class="main-wrapper">
        <div class="course-card">

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="../courses_catalogue.php" class="text-decoration-none">Courses</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="course_overview.php" class="text-decoration-none">IELTS Academic</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Module 2 – Listening Skills</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-headphones me-2" style="color:#1e3a8a;"></i>
                Module 2 – Listening Skills
            </h1>

            <p class="lead">
                In this module, we refine the skill that most students underestimate: the art of listening with purpose. 
                IELTS Listening is not about “hearing English”; it’s about recognizing patterns, predicting information, 
                and staying calm through four challenging recordings.
            </p>

            <!-- SECTION 1 -->
            <div class="content-section">
                <h2>Listening Section Overview</h2>
                <p>
                    The IELTS Listening test is your opportunity to demonstrate how well you understand English in real-life situations. 
                    Across four sections, the test moves from simple, everyday conversations to more academic and complex discussions.
                </p>
                <p>
                    A wise strategy here is to approach each section with a clear expectation of what’s coming. 
                    When you know the “shape” of each recording, your brain can relax and focus on catching details instead of guessing blindly.
                </p>

                <ul class="custom-list">
                    <li><strong>Total Duration:</strong> 30 minutes + 10 minutes to transfer answers</li>
                    <li><strong>Number of Questions:</strong> 40</li>
                    <li><strong>Recordings:</strong> Increasing difficulty from Sections 1 to 4</li>
                    <li><strong>Accents:</strong> British, Australian, American, Canadian (mixed intentionally)</li>
                </ul>

                <p>
                    Remember: IELTS does not expect you to understand every word — only to recognize meaningful clues. 
                    You’re listening for information, not perfection.
                </p>
            </div>

            <!-- SECTION 2 -->
            <div class="content-section">
                <h2>Sections 1–4 Strategies & Question Types</h2>

                <p>
                    The Listening paper is predictable. Once you know the personality of each section, you will stop feeling surprised and start feeling in control.
                </p>

                <h3>Section 1 – Everyday Conversation</h3>
                <p>
                    Usually a dialogue: booking a room, asking for information, setting appointments.  
                    This is your warm-up. Expect <strong>form completion, short answers, note-taking</strong>, and simple details like numbers, dates, and names.
                </p>

                <h4>Teacher’s Tip:</h4>
                <p class="mb-3">
                    When you hear numbers, slow down mentally. IELTS loves giving 17th vs. 70th or fifteen vs. fifty.
                </p>

                <h3>Section 2 – Monologue (Everyday Topic)</h3>
                <p>
                    A single speaker: a tour guide, a manager, an organizer.
                    Common tasks include <strong>map labeling</strong>, <strong>multiple choice</strong>, and <strong>table completion</strong>.
                </p>

                <p>
                    In map questions, always locate <strong>north</strong> first. IELTS is quietly testing your ability to follow directions.
                </p>

                <h3>Section 3 – Academic Discussion</h3>
                <p>
                    Two or more students talking with a tutor.  
                    This is where accents vary and sentences start running longer.
                </p>

                <p>
                    Expect <strong>matching questions</strong>, <strong>multiple choice</strong>, and <strong>statement completion</strong>.
                    Watch how speakers politely disagree — IELTS hides answers in polite corrections.
                </p>

                <h3>Section 4 – Academic Lecture</h3>
                <p>
                    A single speaker giving an academic talk. No breaks. No pauses.  
                    Don’t panic — the lecture follows a clear structure.
                </p>

                <p>
                    You will usually answer <strong>notes, summaries, or table completion</strong>.
                    Follow the lecturer’s keywords: “first,” “next,” “however,” “in contrast,” “finally.”  
                    These signpost the direction your answer will come from.
                </p>
            </div>

            <!-- SECTION 3 -->
            <div class="content-section">
                <h2>Note-Taking Techniques</h2>
                <p>
                    Note-taking in IELTS is about capturing meaning without slowing down your listening. 
                    Think of it as catching fish with a net, not with your bare hands — you only need the important ones.
                </p>

                <ul class="custom-list">
                    <li><strong>Use symbols:</strong> “+” for increase, “–” for decrease, “→” for result.</li>
                    <li><strong>Avoid full sentences:</strong> write keywords like <em>“delay – new policy – cost ↑”</em>.</li>
                    <li><strong>Predict before listening:</strong> glance at the question type and imagine possible answers.</li>
                    <li><strong>Follow the speaker’s rhythm:</strong> write down shifts when you hear “however,” “on the other hand,” or “therefore.”</li>
                </ul>

                <p>
                    The best listeners don’t chase every word. They glide with the speaker and catch the words that matter.
                </p>
            </div>

            <!-- SECTION 4 -->
            <div class="content-section">
                <h2>Full Practice Tests</h2>
                <p>
                    To make sure everything sticks, this module comes with two full Listening practice tests.  
                    Treat them like dress rehearsals: follow the timing strictly, resist the temptation to pause, and mark your answers confidently.
                </p>

                <ul class="custom-list">
                    <li>✔ Listening Practice Test 1 — Section 1 to 4</li>
                    <li>✔ Listening Practice Test 2 — Section 1 to 4</li>
                </ul>

                <p>
                    After completing each test, don’t just check your score — analyze your mistakes.  
                    Ask yourself: “Was it vocabulary, distraction, or timing?”  
                    This kind of reflection is where real improvement lives.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="intro.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left-circle me-2"></i>Back to Module 1
                </a>
                <a href="module3.php" class="btn btn-success btn-lg">
                    <i class="bi bi-play-circle me-2"></i>Start Module 3
                </a>
                <a href="../../learning_dashboard.php" class="btn btn-outline-secondary">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
            </div>
        </div>
    </main>

    <!-- Right Sidebar -->
    <aside class="advert-sidebar">
        <div class="course-card" style="background: linear-gradient(135deg, #1e3a8a 0%, #233a94 100%); color: white;">
            <h6 class="mb-2">🎧 Listening Tools</h6>
            <div class="d-grid gap-2">
                <a href="module2_practice.php" class="btn btn-light btn-sm">
                    <i class="bi bi-mortarboard me-2"></i>Practice Tests
                </a>
                <a href="course_overview.php" class="btn btn-outline-light btn-sm">
                    Course Overview
                </a>
            </div>
        </div>

        <h6 class="mb-3 text-muted">
            <i class="bi bi-megaphone me-2"></i>Sponsored
        </h6>

        <div class="ad-container">
            <div class="ad-placeholder">
                <i class="bi bi-badge-ad" style="font-size: 1.5rem; opacity: 0.3;"></i>
                <p class="mt-2 mb-0">Advertisement Space</p>
                <small>300x250</small>
            </div>
        </div>

        <div class="ad-container">
            <div class="ad-placeholder">
                <i class="bi bi-badge-ad" style="font-size: 1.5rem; opacity: 0.3;"></i>
                <p class="mt-2 mb-0">Advertisement Space</p>
                <small>300x250</small>
            </div>
        </div>
    </aside>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.querySelector('.sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');

        function toggleMenu() {
            sidebar.classList.toggle('active');
            mobileOverlay.classList.toggle('active');
            
            const icon = menuToggle.querySelector('i');
            if (sidebar.classList.contains('active')) {
                icon.className = 'bi bi-x-lg';
            } else {
                icon.className = 'bi bi-list';
            }
        }

        menuToggle.addEventListener('click', toggleMenu);
        mobileOverlay.addEventListener('click', toggleMenu);

        const navLinks = document.querySelectorAll('.sidebar .nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1200) {
                    toggleMenu();
                }
            });
        });
    </script>

</body>
</html>
