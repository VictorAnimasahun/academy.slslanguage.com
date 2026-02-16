<?php
session_start();
// Restrict access if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../../registration.php?message=Please+login+to+access+this+course");
    exit();
}

// Base URL to reach /includes/ from /courses/CELPIP_intro/
$base_url = "../../";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CELPIP Masterclass - Module 5: Course Recap & Final Readiness</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Navbar Styles -->
    <?php include $base_url . 'includes/navbar_styles.php'; ?>

    <!-- Page-specific styles -->
    <style>
        body {
            background: #f1f6fb;
            color: #0f172a;
            font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        }

        .course-container {
            max-width: 900px;
            margin: 2rem auto;
            background: #fff;
            padding: 2rem;
            border-radius: 14px;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
        }

        h1, h2 {
            color: #2c3e50;
        }

        .section-box {
            background: #f8faff;
            border-left: 4px solid #0b77ff;
            padding: 1.2rem;
            margin: 1.5rem 0;
            border-radius: 8px;
        }

        .activity {
            background: #f0f7ff;
            border-left: 4px solid #0b77ff;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-radius: 8px;
        }

        .nav-links {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .nav-links a {
            display: inline-block;
            padding: 0.7rem 1.2rem;
            background: linear-gradient(90deg, #0b77ff, #6f8cff);
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-links a:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(11, 119, 255, 0.3);
        }
    </style>
</head>
<body>
    <?php include $base_url . 'includes/mobile_header.php'; ?>
    <?php include $base_url . 'includes/navbar.php'; ?>

    <main class="main-content">
        <div class="course-container">
            <h1>Module 5: Course Recap & Final Readiness</h1>
            <p>
                Congratulations on completing the CELPIP Masterclass! You’ve explored all four skill areas — <strong>Listening, Reading, Writing, and Speaking</strong> — and learned what to expect on test day.
                Let’s review the most important points and common challenges from each module before your final readiness quiz.
            </p>

            <div class="section-box">
                <h2>Listening Test Recap</h2>
                <ul>
                    <li>The Listening Test begins with one <strong>unscored practice task</strong> followed by six scored parts.</li>
                    <li>All questions are <strong>multiple-choice</strong> with four answer options.</li>
                    <li>Each passage is either a conversation or a short report — one part even includes a <strong>video</strong>.</li>
                    <li>The <strong>Next button</strong> allows early progression, but once you move on, you cannot go back.</li>
                    <li>You’ll hear each recording <strong>only once</strong> — stay focused and use your notepaper wisely.</li>
                </ul>
            </div>

            <div class="section-box">
                <h2>Reading Test Recap</h2>
                <ul>
                    <li>Begins with one <strong>unscored practice task</strong>, then four scored parts.</li>
                    <li>Passages appear on one side of the screen and questions on the other — a <strong>split-screen layout</strong>.</li>
                    <li>All questions use a <strong>drop-down menu</strong> format.</li>
                    <li>Clicking <strong>Next</strong> early ends that part permanently — use your full time to review answers.</li>
                    <li>You’ll receive notepaper and a pen for optional note-taking.</li>
                </ul>
            </div>

            <div class="section-box">
                <h2>Writing Test Recap</h2>
                <ul>
                    <li>Includes <strong>two tasks</strong> — writing an email and responding to a survey question.</li>
                    <li>Each task allows about <strong>30 minutes</strong> and expects 150–200 words.</li>
                    <li>The computer displays your <strong>word count</strong> and allows basic tools like <strong>spellcheck</strong>, <strong>copy</strong>, and <strong>paste</strong>.</li>
                    <li>There’s <strong>no separate planning time</strong> — use your first few minutes to outline your ideas.</li>
                    <li>Always review your response before clicking <strong>Next</strong>.</li>
                </ul>
            </div>

            <div class="section-box">
                <h2>Speaking Test Recap</h2>
                <ul>
                    <li>Consists of <strong>8 speaking tasks</strong> after one short unscored practice task.</li>
                    <li>Each task has <strong>Preparation Time (30–60 seconds)</strong> and <strong>Recording Time (60–90 seconds)</strong>.</li>
                    <li>During preparation, <em>do not speak</em> — plan your response instead.</li>
                    <li>Four tasks include <strong>text only</strong>, and four include <strong>text with images</strong>.</li>
                    <li>Total Speaking time: approximately <strong>20 minutes</strong>.</li>
                </ul>
            </div>

            <div class="activity">
                <h3>🚫 Common Errors and How to Avoid Them</h3>
                <ul>
                    <li><strong>Clicking “Next” too early:</strong> Once you move forward, you can’t return or use remaining time — always review before advancing.</li>
                    <li><strong>Not using the preparation time effectively:</strong> Especially in Speaking and Writing, take a few moments to organize your ideas before responding.</li>
                    <li><strong>Ignoring note-taking:</strong> Having key words written down helps with focus and organization, particularly in Listening and Speaking.</li>
                    <li><strong>Overlooking simple review:</strong> Always use spare seconds to check for grammar, spelling, or clarity errors.</li>
                    <li><strong>Not staying focused during audio tasks:</strong> You’ll hear recordings only once — practice maintaining concentration.</li>
                </ul>
            </div>

            <div class="activity">
                <h3>🧠 Mini Mock Test / Integrated Review Activity</h3>
                <p>
                    This short integrated activity will allow you to apply all four CELPIP skills together — Listening, Reading, Writing, and Speaking.  
                    (Coming soon: this section will include your interactive <strong>Mini Mock Test</strong>.)
                </p>
            </div>

            <div class="activity">
                <h3>🎓 Final CELPIP Readiness Quiz</h3>
                <p>
                    Test your overall understanding of the CELPIP Test format and key strategies before moving to full-length practice tests.  
                    (Coming soon: the <strong>Final CELPIP Readiness Quiz</strong> will appear here.)
                </p>
            </div>

            <div class="nav-links">
                <a href="module4.php">⬅ Back to Module 4 – Speaking Skills</a>
                <a href="celpip_mini_mock.php">🏁 Finish Course & Take Mini Mock ➡</a>
            </div>
        </div>
    </main>

    <?php include $base_url . 'includes/navbar_scripts.php'; ?>
</body>
</html>
