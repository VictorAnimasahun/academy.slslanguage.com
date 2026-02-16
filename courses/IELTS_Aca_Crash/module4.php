<?php
require_once (dirname(dirname(__DIR__))) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../../registration.php?message=Please+login+to+access+this+course");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS Academic Crash Course - Module 4 (Writing Task 1)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../../assets/css/courses.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
</head>
<body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <main class="main-wrapper">
        <div class="course-card">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../courses_catalogue.php" class="text-decoration-none">Courses</a></li>
                    <li class="breadcrumb-item"><a href="course_overview.php" class="text-decoration-none">IELTS Academic</a></li>
                    <li class="breadcrumb-item active">Module 4 – Writing Task 1</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-bar-chart-line me-2" style="color:#dc2626;"></i>
                Module 4 – Writing Task 1 (Academic)
            </h1>

            <p class="lead">
                Task 1 is not an essay. It is a 150-word love letter to data.  
                Your job: transform numbers and diagrams into clear, accurate, elegant English — in 20 minutes.
            </p>

            <!-- Expanded Section -->
            <div class="content-section">
                <h2>Task 1 Overview & Assessment Criteria</h2>
                <p>
                    Writing Task 1 is not about opinions or arguments — it is about <strong>reporting what you see</strong>. 
                    Imagine you are a calm, intelligent narrator describing data to someone who cannot see the chart. 
                    Your mission is accuracy, clarity, and smart organisation.
                </p>

                <p>Examiners judge you based on four pillars. Understand these pillars and Task 1 becomes much easier:</p>

                <ul class="custom-list">
                    <li>
                        <strong>Task Achievement</strong> – Did you complete the job correctly?  
                        You must identify the <em>main features</em>, report <em>important numbers</em>, and avoid unnecessary detail.  
                        If the chart has 10 items, you do not need all 10 — only the most important patterns.
                    </li>

                    <li>
                        <strong>Coherence & Cohesion</strong> – Your writing should feel like a smooth journey.  
                        Use paragraphs properly, connect ideas with clear linking words (e.g., <em>overall, in contrast, meanwhile</em>),  
                        and avoid jumping randomly between data points. Think of it like telling a short story about numbers.
                    </li>

                    <li>
                        <strong>Lexical Resource</strong> – This is your vocabulary power.  
                        Task 1 requires accurate words such as: <em>increase steadily, remain stable, reach a peak, account for, represent</em>.  
                        Using the right expressions makes you sound precise and confident.
                    </li>

                    <li>
                        <strong>Grammatical Range & Accuracy</strong> – This is the balance between variety and correctness.  
                        Show that you can use:
                        <ul>
                            <li>comparatives (higher than, slightly lower)</li>
                            <li>simple & complex sentences</li>
                            <li>correct tenses</li>
                            <li>passive voice, especially for processes</li>
                        </ul>
                        Accuracy is more important than showing off.
                    </li>
                </ul>
            </div>

            <!-- Expanded Section -->
            <div class="content-section">
                <h2>Line / Bar / Pie Charts, Tables, Processes, Maps</h2>
                <p>
                    Every visual type tests a different skill. Think of each one as a different kind of student asking for a different explanation style.
                </p>
                <ul class="custom-list">
                    <li>
                        <strong>Line Graphs</strong> → These show <strong>change over time</strong>.  
                        Focus on patterns: rises, drops, fluctuations, stability, and peaks.  
                        Summarise the journey instead of reporting every number.
                    </li>

                    <li>
                        <strong>Bar Charts</strong> → These test your ability to <strong>compare categories</strong>.  
                        Use expressions like: <em>twice as high, slightly lower, by far the largest</em>.
                    </li>

                    <li>
                        <strong>Pie Charts</strong> → These show <strong>proportions</strong>.  
                        Useful language includes: <em>majority, minority, roughly one-third, almost half</em>.
                    </li>

                    <li>
                        <strong>Tables</strong> → Think of these as bar charts without bars.  
                        Your job is to group related data and highlight contrasts or similarities.  
                        Don’t describe row by row — group intelligently.
                    </li>

                    <li>
                        <strong>Processes</strong> → These describe <strong>how something is made or how it works</strong>.  
                        Use passive voice (<em>is heated, is transformed, is packaged</em>) and sequence words (<em>first, next, subsequently, finally</em>).
                    </li>

                    <li>
                        <strong>Maps</strong> → These show <strong>changes to a place</strong>.  
                        Focus on what was added, removed, expanded, or replaced.  
                        Useful phrases: <em>was converted into, was replaced by, constructed, demolished</em>.
                    </li>
                </ul>

                <p class="mt-3"><strong>Golden Structure (4 paragraphs):</strong></p>
                <ol>
                    <li>
                        <strong>Paraphrase the question</strong> – Rewrite it using synonyms without changing the meaning.
                    </li>
                    <li>
                        <strong>Overview</strong> – The most important paragraph.  
                        Identify the <strong>big picture</strong>: general trends, main comparisons, or major changes — without numbers.
                    </li>
                    <li>
                        <strong>Key features paragraph 1</strong> – Present the first group of important details.  
                        Group similar behaviour together (highest values, lowest values, similar trends).
                    </li>
                    <li>
                        <strong>Key features paragraph 2</strong> – Present the second group of key details.  
                        Avoid repetition. Use only the numbers that support your trends.
                    </li>
                </ol>

                <p>Think of yourself as a news reporter: clear, factual, and organised.</p>
            </div>

            <!-- Expanded Section -->
            <div class="content-section">
				<h2>Full Task 1 Practice Session</h2>

				<p>
					Below is one complete IELTS Task 1 practice activity.  
					Use the dataset provided to create your own chart and insert it into the placeholder image area.
				</p>

				<h4 class="mt-4">Practice Chart: Percentage of Households With Internet Access (2010–2020)</h4>

				

				<p class="mt-3"><strong>Insert Your Chart Below:</strong></p>

				<div class="text-center my-4">
					<img src="../../assets/images/md4chart1.png" alt="Internet Access Chart 2010–2020" style="max-width: 100%; height: auto; border: 1px solid #ddd; padding: 10px;">
					<p class="text-muted mt-2">(Replace this image with your generated chart)</p>
				</div>

				<h4 class="mt-4">Band 8 Model Answer</h4>

				<p>
					The line chart illustrates the proportion of households with Internet access in a particular country over an eleven-year period, from 2010 to 2020.
				</p>

				<p>
					Overall, Internet adoption increased steadily throughout the period, with the most rapid growth occurring between 2014 and 2017. By the end of the timeframe, access levels were significantly higher than at the start.
				</p>

				<p>
					In 2010, just 45% of households had an Internet connection. This figure rose gradually over the next three years, reaching 56% in 2013. Growth accelerated from 2014 onward, rising from 61% to 77% in just three years. This represents one of the sharpest climbs in the entire period.
				</p>

				<p>
					After 2017, the upward trend continued but at a slightly slower rate. Internet access increased from 81% in 2018 to 85% in 2019 and reached a peak of 88% in 2020. By this final year, the proportion of connected households had nearly doubled compared to 2010.
				</p>

				<p>
					Overall, the data clearly shows that Internet access became almost universal by 2020, reflecting consistent technological adoption and expanding digital infrastructure.
				</p>
</div>


            <div class="action-buttons">
                <a href="module3.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Back to Module 3</a>
                <a href="module5.php" class="btn btn-success btn-lg"><i class="bi bi-play-circle me-2"></i>Start Module 5</a>
                <a href="../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>

    <aside class="advert-sidebar">
        <div class="course-card" style="background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%); color: white;">
            <h6 class="mb-2">Task 1 Resources</h6>
            <div class="d-grid gap-2">
                <a href="module4_practice.php" class="btn btn-light btn-sm"><i class="bi bi-graph-up me-2"></i>Task 1 Practice</a>
                <a href="course_overview.php" class="btn btn-outline-light btn-sm">Course Overview</a>
            </div>
        </div>
        <!-- ads -->
        <h6 class="mb-3 text-muted"><i class="bi bi-megaphone me-2"></i>Sponsored</h6>
        <div class="ad-container"><div class="ad-placeholder"><i class="bi bi-badge-ad" style="font-size: 1.5rem; opacity: 0.3;"></i><p class="mt-2 mb-0">Advertisement Space</p><small>300x250</small></div></div>
        <div class="ad-container"><div class="ad-placeholder"><i class="bi bi-badge-ad" style="font-size: 1.5rem; opacity: 0.3;"></i><p class="mt-2 mb-0">Advertisement Space</p><small>300x250</small></div></div>
    </aside>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
</body>
</html>
