<?php
session_start();

// is the user logged in?
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_firstname'] : '';
$userEmail = $isLoggedIn ? $_SESSION['user_email'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduHub - Your Learning Journey Starts Here</title>
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
	<link rel="stylesheet" href="assets/css/academy.css">
</head>

<body>
    <!-- Header -->
    <header>
        <nav class="container">
            <div class="logo">
                <div class="logo-icon"><img src="../../icons/graduation.png" alt=""></div>EduHub
            </div>
            
            <!-- Desktop Navigation -->
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>

                <li>
                    <a href="#courses">Courses</a>
                    <div class="dropdown">
                        <a href="#web-dev">Free Courses</a>
                        <a href="#data-science">Paid Courses</a>
                        <a href="#design">English Language</a>
                        <a href="#business">IELTS</a>
                        <a href="#marketing">CELPIP</a>
						<a href="#marketing">PTE</a>

                    </div>
                </li>

                <li>
                    <a href="#about">About</a>
                    <div class="dropdown">
                        <a href="#our-story">Our Story</a>
                        <a href="#team">Meet the Team</a>
                        <a href="#careers">Careers</a>
                    </div>
                </li>

                <li>
                    <a href="#resources">Resources</a>
                    <div class="dropdown">
                        <a href="#tutorials">Free Tutorials</a>
                        <a href="resources/study_materials/index.php">Study Materials</a>
                        <a href="#webinars">Webinars</a>
                        <a href="resources/essay_analyzer.php">Essay Analyser</a>
                    </div>
                </li>
				
                <li><a href="#contact">Contact</a></li>
            </ul>

            <div class="auth-buttons">
				<?php if ($isLoggedIn): ?>
					<span class="welcome-text">Hi, <?php echo htmlspecialchars($userName); ?>!</span>
					<a href="edu_hub_logout.php" class="btn btn-secondary">Logout</a>
				<?php else: ?>
					<a href="edu_hub_registration.php" class="btn btn-secondary">Sign In</a>
					<a href="edu_hub_registration.php?form=register" class="btn btn-primary">Get Started</a>
				<?php endif; ?>
			</div>

            <!-- Mobile Hamburger -->
            <button class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <!-- Mobile Menu -->
            <div class="mobile-menu" id="mobileMenu">
                <ul class="mobile-nav-links">
                    <li><a href="#home">Home</a></li>
                    <li>
                        <div class="dropdown-toggle" data-dropdown="courses">
                            <span>Courses</span>
                            <span class="dropdown-arrow">▼</span>
                        </div>
                        <div class="mobile-dropdown" id="courses-dropdown">
                            <a href="#web-dev">Web Development</a>
                            <a href="#data-science">Data Science</a>
                            <a href="#design">Design</a>
                            <a href="#business">Business</a>
                            <a href="#marketing">Marketing</a>
                        </div>
                    </li>
                    <li>
                        <div class="dropdown-toggle" data-dropdown="about">
                            <span>About</span>
                            <span class="dropdown-arrow">▼</span>
                        </div>
                        <div class="mobile-dropdown" id="about-dropdown">
                            <a href="#our-story">Our Story</a>
                            <a href="#team">Meet the Team</a>
                            <a href="#careers">Careers</a>
                        </div>
                    </li>
                    <li>
                        <div class="dropdown-toggle" data-dropdown="resources">
                            <span>Resources</span>
                            <span class="dropdown-arrow">▼</span>
                        </div>
                        <div class="mobile-dropdown" id="resources-dropdown">
                            <a href="#tutorials">Free Tutorials</a>
                            <a href="resources/study_materials/index.php">Study Materials</a>
                            <a href="#webinars">Webinars</a>
                            <a href="resources/essay_analyzer.php">Essay Analyser</a>
                        </div>
                    </li>
                    <li><a href="#contact">Contact</a></li>
                    <?php if ($isLoggedIn): ?>
						<span class="welcome-text">Hi, <?php echo htmlspecialchars($userName); ?>!</span>
						<a href="edu_hub_logout.php" class="btn btn-secondary">Logout</a>
					<?php else: ?>
						<a href="edu_hub_registration.php" class="btn btn-secondary">Sign In</a>
						<a href="edu_hub_registration.php?form=register" class="btn btn-primary">Get Started</a>
					<?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

	<?php if (isset($_GET['status'])): ?>
		<div style="background: #d4edda; color: #155724; padding: 1rem; text-align: center; border-bottom: 1px solid #c3e6cb;">
			<?php 
			if ($_GET['status'] === 'login_success') {
				echo "Welcome back, " . htmlspecialchars($userName) . "!";
			} elseif (isset($_GET['message'])) {
				echo htmlspecialchars($_GET['message']);
			}
			?>
		</div>
	<?php endif; ?>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
				<h1>Transform Your Future with SLS Eduhub</h1>
				<p>Discover dozens of language courses taught by expert instructors. Learn at your own pace, anywhere, anytime. Start your learning journey today!</p>
				<div class="hero-buttons">
			
					<?php if ($isLoggedIn): ?>
						<a href="learning_dashboard.php" class="btn btn-primary">My Dashboard</a>
					<?php else: ?>
						<a href="edu_hub_registration.php" class="btn btn-primary">Get Started</a>
					<?php endif; ?>
			
				</div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">Why Choose EduHub?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📚</div>
                    <h3>Expert-Led Courses</h3>
                    <p>Learn from industry professionals and certified instructors with years of real-world experience.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">⏰</div>
                    <h3>Learn at Your Pace</h3>
                    <p>Flexible scheduling allows you to study whenever and wherever it's convenient for you.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🏆</div>
                    <h3>Certified Learning</h3>
                    <p>Earn recognized certificates upon course completion to boost your career prospects.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== ALL COURSES SECTION ===== -->
	<section class="course-section">
		<div class="courses-container">
			<h2 class="course-heading">Our Courses</h2>

			<!-- IELTS General -->
			<h3 class="course-subheading">IELTS General Training</h3>
			<div class="course-grid">
				<div class="course-card">
					<div class="course-image">
						<img src="assets/images/Course7.png" alt="IELTS General 1 Month">
					</div>
					<div class="course-content">
						<span class="course-tag">IELTS</span>
						<h3 class="course-title">IELTS General — 1 Month</h3>
						<p class="course-desc">8 Classes · 4 Weeks · Intermediate Plan</p>
						<a href="courses/IELTS_Gen_1Mo/course_overview.php" class="enrol-button">View Course</a>
					</div>
				</div>
				<div class="course-card">
					<div class="course-image">
						<img src="assets/images/Course7.png" alt="IELTS General 2 Month">
					</div>
					<div class="course-content">
						<span class="course-tag">IELTS</span>
						<h3 class="course-title">IELTS General — 2 Month</h3>
						<p class="course-desc">16 Classes · 8 Weeks · Advanced Plan</p>
						<a href="courses/IELTS_Gen_2Mo/course_overview.php" class="enrol-button">View Course</a>
					</div>
				</div>
				<div class="course-card">
					<div class="course-image">
						<img src="assets/images/Course7.png" alt="IELTS General Masterclass">
					</div>
					<div class="course-content">
						<span class="course-tag">IELTS</span>
						<h3 class="course-title">IELTS General Masterclass</h3>
						<p class="course-desc">24 Classes · 12 Weeks · Fluent Plan</p>
						<a href="courses/IELTS_Gen_Mst/course_overview.php" class="enrol-button">View Course</a>
					</div>
				</div>
			</div>

			<!-- IELTS Academic -->
			<h3 class="course-subheading">IELTS Academic</h3>
			<div class="course-grid">
				<div class="course-card">
					<div class="course-image">
						<img src="assets/images/course1.png" alt="IELTS Academic 1 Month">
					</div>
					<div class="course-content">
						<span class="course-tag">IELTS</span>
						<h3 class="course-title">IELTS Academic — 1 Month</h3>
						<p class="course-desc">8 Classes · 4 Weeks · Intermediate Plan</p>
						<a href="courses/IELTS_Aca_1Mo/course_overview.php" class="enrol-button">View Course</a>
					</div>
				</div>
				<div class="course-card">
					<div class="course-image">
						<img src="assets/images/course1.png" alt="IELTS Academic 2 Month">
					</div>
					<div class="course-content">
						<span class="course-tag">IELTS</span>
						<h3 class="course-title">IELTS Academic — 2 Month</h3>
						<p class="course-desc">16 Classes · 8 Weeks · Advanced Plan</p>
						<a href="courses/IELTS_Aca_2Mo/course_overview.php" class="enrol-button">View Course</a>
					</div>
				</div>
				<div class="course-card">
					<div class="course-image">
						<img src="assets/images/course1.png" alt="IELTS Academic Masterclass">
					</div>
					<div class="course-content">
						<span class="course-tag">IELTS</span>
						<h3 class="course-title">IELTS Academic Masterclass</h3>
						<p class="course-desc">24 Classes · 12 Weeks · Fluent Plan</p>
						<a href="courses/IELTS_Aca_3Mo/course_overview.php" class="enrol-button">View Course</a>
					</div>
				</div>
			</div>

			<!-- CELPIP General -->
			<h3 class="course-subheading">CELPIP General</h3>
			<div class="course-grid">
				<div class="course-card">
					<div class="course-image">
						<img src="assets/images/Course8.jpg" alt="CELPIP General 1 Month">
					</div>
					<div class="course-content">
						<span class="course-tag">CELPIP</span>
						<h3 class="course-title">CELPIP General — 1 Month</h3>
						<p class="course-desc">8 Classes · 4 Weeks · Intermediate Plan</p>
						<a href="courses/CELPIP_Gen_1Mo/course_overview.php" class="enrol-button">View Course</a>
					</div>
				</div>
				<div class="course-card">
					<div class="course-image">
						<img src="assets/images/Course8.jpg" alt="CELPIP General 2 Month">
					</div>
					<div class="course-content">
						<span class="course-tag">CELPIP</span>
						<h3 class="course-title">CELPIP General — 2 Month</h3>
						<p class="course-desc">16 Classes · 8 Weeks · Advanced Plan</p>
						<a href="courses/CELPIP_Gen_2Mo/course_overview.php" class="enrol-button">View Course</a>
					</div>
				</div>
				<div class="course-card">
					<div class="course-image">
						<img src="assets/images/Course8.jpg" alt="CELPIP General Masterclass">
					</div>
					<div class="course-content">
						<span class="course-tag">CELPIP</span>
						<h3 class="course-title">CELPIP General Masterclass</h3>
						<p class="course-desc">24 Classes · 12 Weeks · Fluent Plan</p>
						<a href="courses/CELPIP_Gen_3Mo/course_overview.php" class="enrol-button">View Course</a>
					</div>
				</div>
			</div>

			<!-- PTE Academic -->
			<h3 class="course-subheading">PTE Academic <span class="coming-soon-label">— Coming Soon</span></h3>
			<div class="course-grid">
				<div class="course-card coming-soon">
					<div class="course-image">
						<img src="assets/images/course3.png" alt="PTE Academic 1 Month">
					</div>
					<div class="course-content">
						<span class="course-tag">PTE</span>
						<h3 class="course-title">PTE Academic — 1 Month</h3>
						<p class="course-desc">8 Classes · 4 Weeks · Intermediate Plan</p>
						<a href="courses/PTE_Gen_1Mo/course_overview.php" class="enrol-button">View Course</a>
					</div>
				</div>
				<div class="course-card coming-soon">
					<div class="course-image">
						<img src="assets/images/course3.png" alt="PTE Academic 2 Month">
					</div>
					<div class="course-content">
						<span class="course-tag">PTE</span>
						<h3 class="course-title">PTE Academic — 2 Month</h3>
						<p class="course-desc">16 Classes · 8 Weeks · Advanced Plan</p>
						<a href="courses/PTE_Gen_2Mo/course_overview.php" class="enrol-button">View Course</a>
					</div>
				</div>
				<div class="course-card coming-soon">
					<div class="course-image">
						<img src="assets/images/course3.png" alt="PTE Academic Masterclass">
					</div>
					<div class="course-content">
						<span class="course-tag">PTE</span>
						<h3 class="course-title">PTE Academic Masterclass</h3>
						<p class="course-desc">24 Classes · 12 Weeks · Fluent Plan</p>
						<a href="courses/PTE_Gen_3Mo/course_overview.php" class="enrol-button">View Course</a>
					</div>
				</div>
			</div>

		</div>
	</section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <h3>50,000+</h3>
                    <p>Students Enrolled</p>
                </div>
                <div class="stat-item">
                    <h3>1,200+</h3>
                    <p>Courses Available</p>
                </div>
                <div class="stat-item">
                    <h3>95%</h3>
                    <p>Completion Rate</p>
                </div>
                <div class="stat-item">
                    <h3>4.9/5</h3>
                    <p>Average Rating</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>Ready to Start Learning?</h2>
            <p>Join thousands of students who have already transformed their careers with EduHub.</p>
            
			<?php if ($isLoggedIn): ?>
				<a href="learning_dashboard.php" class="btn btn-pink" style="margin-top: 1rem;">Continue Learning</a>
			<?php else: ?>
				 <a href="edu_hub_registration.php" class="btn btn-pink" style="margin-top: 1rem;">Start Learning Today</a>
			<?php endif; ?>
	
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>EduHub</h3>
                    <p>Empowering learners worldwide with quality education and expert instruction.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#about-us">About Us</a></li>
                        <li><a href="#all-courses">Courses</a></li>
                        <li><a href="#instructors">Instructors</a></li>
                        <li><a href="#blog-page">Blog</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Support</h3>
                    <ul>
                        <li><a href="#help">Help Center</a></li>
                        <li><a href="#contact-us">Contact Us</a></li>
                        <li><a href="#terms">Terms of Service</a></li>
                        <li><a href="#privacy">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Connect</h3>
                    <ul>
                        <li><a href="#facebook">Facebook</a></li>
                        <li><a href="#twitter">Twitter</a></li>
                        <li><a href="#linkedin">LinkedIn</a></li>
                        <li><a href="#instagram">Instagram</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 SLS EduHub. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Hamburger menu functionality
        const hamburger = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobileMenu');

        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            mobileMenu.classList.toggle('active');
        });

        // Mobile dropdown functionality
        document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
            toggle.addEventListener('click', () => {
                const dropdownName = toggle.getAttribute('data-dropdown');
                const dropdown = document.getElementById(dropdownName + '-dropdown');
                
                toggle.classList.toggle('active');
                dropdown.classList.toggle('active');
            });
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!hamburger.contains(e.target) && !mobileMenu.contains(e.target)) {
                hamburger.classList.remove('active');
                mobileMenu.classList.remove('active');
            }
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    // Close mobile menu after clicking a link
                    hamburger.classList.remove('active');
                    mobileMenu.classList.remove('active');
                }
            });
        });

        // Add scroll effect to header
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            if (window.scrollY > 100) {
                header.style.background = 'rgba(255, 255, 255, 0.95)';
                header.style.backdropFilter = 'blur(10px)';
            } else {
                header.style.background = 'white';
                header.style.backdropFilter = 'none';
            }
        });

        // Animate stats when they come into view
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const statItems = entry.target.querySelectorAll('.stat-item h3');
                    statItems.forEach((item, index) => {
                        const finalNumber = parseInt(item.textContent.replace(/[^0-9]/g, ''));
                        const suffix = item.textContent.replace(/[0-9]/g, '');
                        let currentNumber = 0;
                        const increment = finalNumber / 50;
                        
                        const timer = setInterval(() => {
                            currentNumber += increment;
                            if (currentNumber >= finalNumber) {
                                currentNumber = finalNumber;
                                clearInterval(timer);
                            }
                            item.textContent = Math.floor(currentNumber).toLocaleString() + suffix;
                        }, 30);
                    });
                }
            });
        }, observerOptions);

        const statsSection = document.querySelector('.stats');
        if (statsSection) {
            observer.observe(statsSection);
        }
    </script>
</body>
</html>