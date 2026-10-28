<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
	header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+this+course");
	exit();
}
// Shared by the free Introduction to CELPIP course and Class 2 of the paid CELPIP courses. ?from=<folder>
// (sent by the course pages and the app) says which, so the breadcrumb leads back to the right course.
require_once INCLUDES_PATH . '/course_shape.php';
$_from = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['from'] ?? '');
$ctxCourse = in_array($_from, ['CELPIP_Gen_1Mo', 'CELPIP_Gen_2Mo', 'CELPIP_Gen_3Mo'], true) ? course_by_folder($db, $_from) : null;
$backName = $ctxCourse['title'] ?? 'Introduction to CELPIP';
$backUrl = $ctxCourse ? ACADEMY_URL . 'courses/' . $ctxCourse['folder'] . '/course_overview.php' : 'course_overview.php';
require_once INCLUDES_PATH . '/admin_check.php';
$isAdmin = is_staff_account();   // staff take it untimed, as on the practice tests; testers are timed

// Speaking: four of the eight tasks of CELPIP Speaking Practice 3 (resources/practice_tests/celpip_speaking_003.php, its
// pictures in assets/img/practice_tests/CELPIP_PT_S_003/), in the same flow as the practice tests (founder,
// 2026-10-28: the speaking section "is not like the standard stuff"). Reused as the IELTS General diagnostic reuses
// a Cambridge mock: crash courses never meet Set 3; the 2/3-month courses meet it in Classes 14/15.
$speakTasks = [
    1 => [
        'title'  => 'Task 1: Giving Advice',
        'prep'   => 30,
        'speak'  => 90,
        'prompt' => "Your parents are traveling abroad for the first time. Give them advice on what to do in an airport.",
    ],
    2 => [
        'title'  => 'Task 2: Talking about a Personal Experience',
        'prep'   => 30,
        'speak'  => 60,
        'prompt' => "Talk about a time you made a change in your lifestyle. What was the change and how did it affect your life?",
    ],
    3 => [
        'title'  => 'Task 3: Describing a Scene',
        'prep'   => 30,
        'speak'  => 60,
        'prompt' => "Describe this scene. The person with whom you are speaking cannot see the picture.",
        'image'  => 'scene.jpg',
    ],
    4 => [
        'title'  => 'Task 4: Making Predictions',
        'prep'   => 30,
        'speak'  => 60,
        'prompt' => "In this picture, what do you think will most probably happen next?",
        'image'  => 'scene.jpg',
    ],
    5 => [
        // Real CELPIP Task 5 has a twist: two options are shown first, then a
        // third competing option appears that the candidate must also argue
        // against. Both stages are shown together here (as a single combined
        // prompt + both source images) since the practice engine only has one
        // linear prep+speak stage per task, not the real exam's two-stage
        // selection timing -- a deliberate simplification, noted here rather
        // than silently dropping the third option.
        'title'  => 'Task 5: Comparing and Persuading',
        'prep'   => 60,
        'speak'  => 60,
        'prompt' => "You are camping with your friends and discussing your plans for tomorrow. Choose the option you prefer and convince your friends that your idea is best:\n\nOption A - Hike up a mountain: 10 hour hike, amazing view from the summit, natural hot springs on the way, chance to see bighorn sheep.\nOption B - Walk along the river: 4 hour walk, easy path to follow, nice spot for a picnic lunch, lots of birds and deer nearby.\n\nYour friends then suggest a third option - swimming in the lake all afternoon, relaxing in the sun, a chance to catch some fish, and a BBQ beside the lake. Persuade them that your original choice is still best.",
        'images' => ['task5a.jpg', 'task5b.jpg'],
    ],
    6 => [
        'title'  => 'Task 6: Dealing with a Difficult Situation',
        'prep'   => 60,
        'speak'  => 60,
        'prompt' => "Your cousin recently borrowed your iPad when his family went on a road trip. After he returned it, you noticed a crack in the glass on the corner of the device. Call him up and explain the discovery. Ask him to pay for the repair.",
    ],
    7 => [
        'title'  => 'Task 7: Expressing Opinions',
        'prep'   => 30,
        'speak'  => 90,
        'prompt' => "It is becoming common these days for children to have their own smartphones. Do you think this trend is good or bad? Explain your reasons.",
    ],
    8 => [
        'title'  => 'Task 8: Describing an Unusual Situation',
        'prep'   => 30,
        'speak'  => 60,
        'prompt' => "You are on a safari in Africa and see a very strange animal. Unfortunately, your camera battery died and you couldn't take a picture! Call your friend at the end of the day and describe the animal you saw.",
        'image'  => 'painting.jpg',
    ],
];
// The diagnostic takes four of them (founder, 2026-10-28: "just 4 instead of 8"): two spoken from a prompt, two from
// pictures. Each keeps its CELPIP task number and name, so the student learns which task type is which.
$speakTasks = array_combine([1, 2, 3, 4], array_map(fn ($n) => $speakTasks[$n], [1, 3, 5, 7]));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CELPIP Diagnostic Test | EduHub</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <link rel="stylesheet" href="<?= ACADEMY_URL ?>assets/css/exam_theme.css">
    <!-- The CELPIP exam look of the practice tests and mocks (founder, 2026-10-28); was css/style.css -->
    <link rel="stylesheet" href="css/mini_mock_exam.css?v=3">
    <?php include dirname(__DIR__, 2) . '/resources/practice_tests/celpip_screen_styles.php'; /* the CELPIP speaking screen */ ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
<body>

<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="mobile-overlay" id="mobileOverlay"></div>
<?php include INCLUDES_PATH . '/navbar.php'; ?>

<div class="main-wrapper flex-grow-1" style="flex:1;">
    <?php include INCLUDES_PATH . '/topbar.php'; ?>

    <main class="content p-3">
        <!-- The CELPIP exam header, as on the practice tests: breadcrumb, timer, section tabs (they follow the test,
             they don't navigate it). The timer keeps its #time element, which scripts/script.js counts down. -->
        <div class="sticky-header" id="stickyHeader">
            <div class="d-flex align-items-center justify-content-between">
                <nav aria-label="breadcrumb" class="mb-0">
                    <ol class="breadcrumb mb-0" style="font-size:.8rem;">
                        <li class="breadcrumb-item"><a href="../courses_catalogue.php">Courses</a></li>
                        <li class="breadcrumb-item"><a href="<?= htmlspecialchars($backUrl) ?>"><?= htmlspecialchars($backName) ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page">CELPIP Diagnostic Test</li>
                    </ol>
                </nav>
                <div class="inline-timer" id="quiz-timer"><i class="bi bi-clock-fill"></i> <span id="time">60:00</span></div>
            </div>
            <div class="part-tabs-bar mini-tabs">
                <div class="part-tabs-scrollable">
                    <?php foreach (['intro' => 'Introduction', 'section1' => 'Listening', 'section2' => 'Reading', 'section3' => 'Writing', 'section4' => 'Speaking', 'results' => 'Results'] as $tabPage => $tabLabel): ?>
                    <button type="button" class="part-tab-btn <?= $tabPage === 'intro' ? 'active' : '' ?>" data-tab-page="<?= $tabPage ?>" tabindex="-1" aria-disabled="true">
                        <span class="done-dot"></span><?= $tabLabel ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="mock-shell mock-content">

            <!-- Introduction Page -->
            <div id="intro" class="page active">
                <div class="wrap">
                    <div class="card">
                        <h1>CELPIP Diagnostic Test</h1>
                        <div class="intro-text">
                            <p>Welcome to the CELPIP Diagnostic Test (also called the Mini Mock). It shows your CELPIP level today. It simulates parts of the CELPIP exam so you can practice under timed conditions. The full CELPIP exam has four sections (Listening, Reading, Writing, and Speaking). This test covers the same four sections.</p>
                            <p>You have a total of 1 hour to complete all four sections. The timer will begin counting down as soon as you click the button below:</p>
                            <ul>
                                <li>Listening (10 questions)</li>
                                <li>Reading (11 questions)</li>
                                <li>Writing (1 task)</li>
                                <li>Speaking (4 recorded tasks, marked by your tutor)</li>
                            </ul>
                            <p>Click on the <strong>Start Test</strong> button below when you are ready to begin. At the end, you'll receive a detailed assessment of your English level and personalized feedback on areas for improvement.</p>
                        </div>
                        <div class="navigation">
                            <div></div>
                            <button class="btn btn-primary" onclick="startQuiz()">Start Test</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 1: Listening -->
            <div id="section1" class="page">
                <div class="progress-bar"><div class="progress" style="width: 25%"></div></div>
                <h2>Section 1: Listening</h2>

                <div class="audio-section">
                    <div class="wrap">
                        <div class="card">
                            <h3><i class="bi bi-headphones me-2"></i>Audio Instructions</h3>

                            <div class="audio-info">
                                <p><strong>Important:</strong> Click the play button on the video below to begin listening. You can play the video only ONCE for this test — just like the real CELPIP test.</p>
                                <p>Listen carefully and answer the questions as you hear the information. The relevant discussion begins at 2:18 in the video.</p>
                                <p><em>Note: This video is used for educational purposes only.</em></p>
                            </div>

                            <div id="youtubePlayer">
                                <iframe
                                    id="listeningVideo"
                                    width="100%"
                                    height="315"
                                    src="https://www.youtube.com/embed/elNy7TmUvWA?start=138&enablejsapi=1&controls=1&rel=0&modestbranding=1"
                                    title="Universal Basic Income Discussion"
                                    frameborder="0"
                                    allow="autoplay; encrypted-media"
                                    allowfullscreen>
                                </iframe>
                            </div>

                            <!-- Backup audio element (for future local files) -->
                            <audio id="listeningAudio" preload="auto" style="display: none;">
                                <source src="audio/celpip_ubi_audio.mp3" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>

                            <div class="audio-status" id="audioStatus">
                                <div class="status-indicator">
                                    <span id="audioIndicator" class="audio-dot" data-state="ready" aria-hidden="true"></span>
                                    <span id="audioText">Click "Start Listening" to begin</span>
                                </div>
                                <div id="audioStatusText">Ready</div>
                                <div class="audio-timer">Audio Time: <span id="audioTimeDisplay">00:00</span></div>
                            </div>

                            <div class="audio-warning">
                                <p><strong><i class="bi bi-exclamation-triangle-fill me-1"></i>Test Rules:</strong> Play the video only once, then proceed to answer all questions before moving to the next section!</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="wrap">
                    <div class="card" role="main" aria-labelledby="title">
                        <h1 id="title">Listening Section</h1>
                        <div class="subtitle">Questions 1–10 — Choose the best answer for each question.</div>

                        <h2 style="text-align:center; font-size:1.15rem;">Universal Basic Income Discussion</h2>

                        <div class="question-container">
                            <div class="question-number">Question 1 of 10</div>
                            <div class="question-text">What is Megan Gilmore's profession?</div>
                            <ul class="options">
                                <li><label><input type="radio" name="l1" id="l1a" value="A"> She is a government policy advisor.</label></li>
                                <li><label><input type="radio" name="l1" id="l1b" value="B"> She is a journalist.</label></li>
                                <li><label><input type="radio" name="l1" id="l1c" value="C"> She is a university researcher.</label></li>
                                <li><label><input type="radio" name="l1" id="l1d" value="D"> She is a social worker.</label></li>
                            </ul>
                        </div>

                        <div class="question-container">
                            <div class="question-number">Question 2 of 10</div>
                            <div class="question-text">Why was Ontario's UBI pilot program terminated?</div>
                            <ul class="options">
                                <li><label><input type="radio" name="l2" id="l2a" value="A"> The program was too expensive to maintain.</label></li>
                                <li><label><input type="radio" name="l2" id="l2b" value="B"> Research results showed negative outcomes.</label></li>
                                <li><label><input type="radio" name="l2" id="l2c" value="C"> A new government chose to end it.</label></li>
                                <li><label><input type="radio" name="l2" id="l2d" value="D"> Participants requested program cancellation.</label></li>
                            </ul>
                        </div>

                        <div class="question-container">
                            <div class="question-number">Question 3 of 10</div>
                            <div class="question-text">What was the intended duration of Ontario's pilot program?</div>
                            <ul class="options">
                                <li><label><input type="radio" name="l3" id="l3a" value="A"> One year</label></li>
                                <li><label><input type="radio" name="l3" id="l3b" value="B"> Two years</label></li>
                                <li><label><input type="radio" name="l3" id="l3c" value="C"> Three years</label></li>
                                <li><label><input type="radio" name="l3" id="l3d" value="D"> Five years</label></li>
                            </ul>
                        </div>

                        <div class="question-container">
                            <div class="question-number">Question 4 of 10</div>
                            <div class="question-text">According to the interview, what was lost when Ontario's program ended?</div>
                            <ul class="options">
                                <li><label><input type="radio" name="l4" id="l4a" value="A"> Comprehensive research data about UBI impacts</label></li>
                                <li><label><input type="radio" name="l4" id="l4b" value="B"> Federal funding for future programs</label></li>
                                <li><label><input type="radio" name="l4" id="l4c" value="C"> Public support for basic income</label></li>
                                <li><label><input type="radio" name="l4" id="l4d" value="D"> Employment opportunities for participants</label></li>
                            </ul>
                        </div>

                        <div class="question-container">
                            <div class="question-number">Question 5 of 10</div>
                            <div class="question-text">How did participants react to the program's cancellation?</div>
                            <ul class="options">
                                <li><label><input type="radio" name="l5" id="l5a" value="A"> They expressed anger toward the government.</label></li>
                                <li><label><input type="radio" name="l5" id="l5b" value="B"> They felt disappointed about lost opportunities.</label></li>
                                <li><label><input type="radio" name="l5" id="l5c" value="C"> They were relieved the program ended.</label></li>
                                <li><label><input type="radio" name="l5" id="l5d" value="D"> They organized protests against the decision.</label></li>
                            </ul>
                        </div>

                        <div class="question-container">
                            <div class="question-number">Question 6 of 10</div>
                            <div class="question-text">Which Ontario municipalities have supported UBI initiatives?</div>
                            <ul class="options">
                                <li><label><input type="radio" name="l6" id="l6a" value="A"> Toronto, Ottawa, and Hamilton</label></li>
                                <li><label><input type="radio" name="l6" id="l6b" value="B"> Kingston, London, and Windsor</label></li>
                                <li><label><input type="radio" name="l6" id="l6c" value="C"> Guelph, Niagara Falls, and Waterloo</label></li>
                                <li><label><input type="radio" name="l6" id="l6d" value="D"> Sudbury, Thunder Bay, and Barrie</label></li>
                            </ul>
                        </div>

                        <div class="question-container">
                            <div class="question-number">Question 7 of 10</div>
                            <div class="question-text">What is the current status of British Columbia's UBI involvement?</div>
                            <ul class="options">
                                <li><label><input type="radio" name="l7" id="l7a" value="A"> The province has launched a pilot program.</label></li>
                                <li><label><input type="radio" name="l7" id="l7b" value="B"> Some communities are considering municipal programs.</label></li>
                                <li><label><input type="radio" name="l7" id="l7c" value="C"> The provincial government rejected UBI proposals.</label></li>
                                <li><label><input type="radio" name="l7" id="l7d" value="D"> BC is waiting for federal approval to proceed.</label></li>
                            </ul>
                        </div>

                        <div class="question-container">
                            <div class="question-number">Question 8 of 10</div>
                            <div class="question-text">What did the 2021 federal bills propose?</div>
                            <ul class="options">
                                <li><label><input type="radio" name="l8" id="l8a" value="A"> Immediate implementation of UBI across Canada</label></li>
                                <li><label><input type="radio" name="l8" id="l8b" value="B"> Specific payment amounts for program participants</label></li>
                                <li><label><input type="radio" name="l8" id="l8c" value="C"> Creation of a framework for basic income</label></li>
                                <li><label><input type="radio" name="l8" id="l8d" value="D"> Replacement of existing social assistance programs</label></li>
                            </ul>
                        </div>

                        <div class="question-container">
                            <div class="question-number">Question 9 of 10</div>
                            <div class="question-text">According to Gilmore, what typically happens to UBI payments when recipients work?</div>
                            <ul class="options">
                                <li><label><input type="radio" name="l9" id="l9a" value="A"> Payments increase to encourage employment.</label></li>
                                <li><label><input type="radio" name="l9" id="l9b" value="B"> Payments remain unchanged regardless of earnings.</label></li>
                                <li><label><input type="radio" name="l9" id="l9c" value="C"> Payments are reduced based on employment income.</label></li>
                                <li><label><input type="radio" name="l9" id="l9d" value="D"> Payments are suspended until employment ends.</label></li>
                            </ul>
                        </div>

                        <div class="question-container">
                            <div class="question-number">Question 10 of 10</div>
                            <div class="question-text">What does Gilmore suggest people should monitor regarding UBI's future?</div>
                            <ul class="options">
                                <li><label><input type="radio" name="l10" id="l10a" value="A"> Provincial election outcomes and policy changes</label></li>
                                <li><label><input type="radio" name="l10" id="l10b" value="B"> Senate committee decisions and disability benefit discussions</label></li>
                                <li><label><input type="radio" name="l10" id="l10c" value="C"> Public opinion polls and media coverage</label></li>
                                <li><label><input type="radio" name="l10" id="l10d" value="D"> Economic indicators and employment rates</label></li>
                            </ul>
                        </div>

                        <p class="note">Tip: you can change your answer by selecting a different option — only one answer per question is allowed.</p>
                    </div>
                </div>

                <div class="navigation">
                    <button class="btn btn-secondary" onclick="goToPage('intro')">← Back</button>
                    <button class="btn btn-primary" onclick="goToSection(2)" id="nextSectionBtn">Next Section →</button>
                </div>
            </div>

            <!-- Section 2: Reading -->
            <div id="section2" class="page">
                <div class="progress-bar"><div class="progress" style="width: 50%"></div></div>
                <h2>Section 2: Reading</h2>
                <p class="note" style="margin-bottom:1.2rem;">Reading Part 1: Reading Correspondence — 11 minutes</p>

                <div class="wrap">
                    <div class="card" role="main">
                        <h1>Reading Section</h1>
                        <div class="subtitle"><strong>Read the following message.</strong></div>

                        <div class="passage">
                            <p>Hi Sarah,</p>
                            <p>I hope you're doing well! I wanted to reach out about the community garden project
                                we discussed at last month's neighborhood meeting. The city council has finally
                                approved our proposal, and we can start planting in the vacant lot behind the
                                community center next spring.</p>
                            <p>We've received a grant of $3,500 from the Green Spaces Initiative, which should
                                cover most of our startup costs. I've been researching what vegetables grow best
                                in our climate zone, and I think we should focus on tomatoes, peppers,
                                and leafy greens for the first year. These are popular with families and
                                relatively easy to maintain.</p>
                            <p>The plot will be divided into 20 individual garden beds, each measuring 4x6 feet.
                                We're charging a seasonal fee of $25 per bed to cover water costs and basic maintenance supplies.
                                So far, eight people have signed up, including the Martinez family and Mrs. Chen from Oak Street.</p>
                            <p>I'm organizing a planning meeting for this Saturday at 2 PM at the library.
                                We need to discuss tool storage, a watering schedule, and rules for the garden.
                                Could you help me create a simple flyer to post around the neighborhood?
                                Your graphic design skills would be perfect for this.</p>
                            <p>Also, my cousin Jake is a landscaper and has offered to donate his time to help with the
                                initial soil preparation. He's available the first weekend of March if that works for everyone.</p>
                            <p>Let me know if you can make it to Saturday's meeting. I'm really excited to get
                                this project off the ground!</p>
                            <p>Best regards,<br>Emma</p>
                        </div>

                        <div class="questions-section">
                            <p><strong>Choose the best option according to the information given in the message.</strong></p>

                            <div class="question-container">
                                <div class="question-number">1.</div>
                                <div class="question-text">Emma's community garden project is now ___________</div>
                                <ul class="options">
                                    <li><label><input type="radio" name="r1" id="r1a" value="A"> approved by the city</label></li>
                                    <li><label><input type="radio" name="r1" id="r1b" value="B"> receiving grant funding</label></li>
                                    <li><label><input type="radio" name="r1" id="r1c" value="C"> ready for planting</label></li>
                                    <li><label><input type="radio" name="r1" id="r1d" value="D"> supported by neighbors</label></li>
                                </ul>
                            </div>

                            <div class="question-container">
                                <div class="question-number">2.</div>
                                <div class="question-text">In a few months, the community garden will ___________</div>
                                <ul class="options">
                                    <li><label><input type="radio" name="r2" id="r2a" value="A"> start growing vegetables</label></li>
                                    <li><label><input type="radio" name="r2" id="r2b" value="B"> host neighborhood meetings</label></li>
                                    <li><label><input type="radio" name="r2" id="r2c" value="C"> expand to more locations</label></li>
                                    <li><label><input type="radio" name="r2" id="r2d" value="D"> require additional funding</label></li>
                                </ul>
                            </div>

                            <div class="question-container">
                                <div class="question-number">3.</div>
                                <div class="question-text">Emma is feeling ___________</div>
                                <ul class="options">
                                    <li><label><input type="radio" name="r3" id="r3a" value="A"> worried about the costs</label></li>
                                    <li><label><input type="radio" name="r3" id="r3b" value="B"> uncertain about plant choices</label></li>
                                    <li><label><input type="radio" name="r3" id="r3c" value="C"> enthusiastic about the project</label></li>
                                    <li><label><input type="radio" name="r3" id="r3d" value="D"> concerned about participation</label></li>
                                </ul>
                            </div>

                            <div class="question-container">
                                <div class="question-number">4.</div>
                                <div class="question-text">The garden participants are ___________</div>
                                <ul class="options">
                                    <li><label><input type="radio" name="r4" id="r4a" value="A"> experienced with landscaping</label></li>
                                    <li><label><input type="radio" name="r4" id="r4b" value="B"> paying for individual plots</label></li>
                                    <li><label><input type="radio" name="r4" id="r4c" value="C"> providing their own tools</label></li>
                                    <li><label><input type="radio" name="r4" id="r4d" value="D"> meeting every weekend</label></li>
                                </ul>
                            </div>

                            <div class="question-container">
                                <div class="question-number">5.</div>
                                <div class="question-text">Emma and Sarah ___________</div>
                                <ul class="options">
                                    <li><label><input type="radio" name="r5" id="r5a" value="A"> are neighbors</label></li>
                                    <li><label><input type="radio" name="r5" id="r5b" value="B"> work together</label></li>
                                    <li><label><input type="radio" name="r5" id="r5c" value="C"> are family members</label></li>
                                    <li><label><input type="radio" name="r5" id="r5d" value="D"> attend the same school</label></li>
                                </ul>
                            </div>

                            <div class="question-container">
                                <div class="question-number">6.</div>
                                <div class="question-text">Emma and Jake ___________</div>
                                <ul class="options">
                                    <li><label><input type="radio" name="r6" id="r6a" value="A"> are co-workers</label></li>
                                    <li><label><input type="radio" name="r6" id="r6b" value="B"> are cousins</label></li>
                                    <li><label><input type="radio" name="r6" id="r6c" value="C"> are neighbors</label></li>
                                    <li><label><input type="radio" name="r6" id="r6d" value="D"> are old friends</label></li>
                                </ul>
                            </div>
                        </div>

                        <div class="passage" style=" background:#fffbeb;">
                            <p><strong>Here is a response to the message. Complete the response by filling in the blanks.</strong></p>

                            <div style="background:#fff; padding:1.2rem 1.4rem; margin-top:1rem; border-radius:3px; border: 1px solid #dbe1ea;">
                                <p>Hi Emma,</p>
                                <p>This is such wonderful news! Count us in, we would love to have
                                7. <select id="r7">
                                    <option value=""></option>
                                    <option value="A">the planning meeting</option>
                                    <option value="B">the grant application</option>
                                    <option value="C">the garden plot</option>
                                    <option value="D">the tool storage</option>
                                </select>.</p>

                                <p>We'll reserve
                                8. <select id="r8">
                                    <option value=""></option>
                                    <option value="A">tomatoes</option>
                                    <option value="B">peppers</option>
                                    <option value="C">two plots</option>
                                    <option value="D">March</option>
                                </select>
                                at 6am Saturday morning. We'll be there by early afternoon. That way if you need any help setting up the
                                9. <select id="r9">
                                    <option value=""></option>
                                    <option value="A">flyer</option>
                                    <option value="B">meeting</option>
                                    <option value="C">soil</option>
                                    <option value="D">tools</option>
                                </select>
                                you'll have some extra help. Sarah is great with decorations.</p>

                                <p>Also, we want to give the garden a welcome gift. Initially we thought about a sofa for the community area, but I guess that
                                10. <select id="r10">
                                    <option value=""></option>
                                    <option value="A">isn't practical for outdoors</option>
                                    <option value="B">is too expensive for us</option>
                                    <option value="C">won't fit in the space</option>
                                    <option value="D">would be damaged by rain</option>
                                </select>.</p>

                                <p>Do you have any suggestions? Does the garden have everything it needs for the
                                11. <select id="r11">
                                    <option value=""></option>
                                    <option value="A">planting season</option>
                                    <option value="B">watering schedule</option>
                                    <option value="C">community meetings</option>
                                    <option value="D">soil preparation</option>
                                </select>?
                                Winter preparations, perhaps?</p>
                            </div>
                        </div>

                        <p class="hint">Click "Next Section" to continue with the Writing test.</p>
                    </div>
                </div>

                <div class="navigation">
                    <button class="btn btn-secondary" onclick="goToSection(1)">← Previous</button>
                    <button class="btn btn-primary" onclick="goToSection(3)">Next Section →</button>
                </div>
            </div>

            <!-- Section 3: Writing -->
            <div id="section3" class="page">
                <div class="progress-bar"><div class="progress" style="width: 75%"></div></div>
                <h2>Section 3: Writing</h2>

                <div class="wrap">
                    <div class="card" role="main">
                        <h1>Writing Section</h1>
                        <div class="subtitle">Writing Task 1 — You should spend about 20 minutes on this task.</div>

                        <div class="writing-task">
                            <div class="task-prompt">
                                <h3>Task:</h3>
                                <p>You recently bought a piece of equipment for your kitchen but it did not work. You phoned the shop but no action was taken.</p>
                                <p><strong>Write a letter to the shop manager. In your letter:</strong></p>
                                <ul>
                                    <li>describe the problem with the equipment</li>
                                    <li>explain what happened when you phoned the shop</li>
                                    <li>say what you would like the manager to do</li>
                                </ul>
                                <p><strong>Write at least 150 words.</strong> You do NOT need to write any addresses.</p>
                                <p>Begin your letter as follows: <em>Dear Sir or Madam,</em></p>
                            </div>

                            <div class="writing-area">
                                <div class="writing-box">
                                    <textarea
                                        id="writingResponse"
                                        class="writing-textarea"
                                        placeholder="Begin your letter here...&#10;&#10;Dear Sir or Madam,&#10;&#10;"
                                        rows="20"
                                        maxlength="2000"
                                    ></textarea>
                                    <div class="word-count">
                                        Word count: <span id="wordCount">0</span> / 150 minimum
                                    </div>
                                </div>
                            </div>

                            <div class="writing-tips">
                                <h4>Tips:</h4>
                                <ul>
                                    <li>Use formal language appropriate for a complaint letter</li>
                                    <li>Organize your letter with clear paragraphs</li>
                                    <li>Include all three bullet points in your response</li>
                                    <li>Aim for 150–200 words</li>
                                    <li>End with an appropriate closing (e.g., "Yours faithfully,")</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="navigation">
                    <button class="btn btn-secondary" onclick="goToSection(2)">← Previous</button>
                    <button class="btn btn-primary" onclick="goToSection(4)">Next Section →</button>
                </div>
            </div>

            <!-- Section 4: Speaking -->
            <div id="section4" class="page">
                <!-- Speaking, as on the CELPIP speaking practice tests: a microphone check, then one task per screen --
                     preparation counts down, recording starts by itself and counts down, NEXT moves on. -->
                <div class="card" id="micGate">
                    <h1>Speaking Section</h1>
                    <p>The Speaking section has 4 tasks. Each task gives you time to prepare, then records your answer automatically &mdash; just like the real CELPIP test. Your tutor listens to your recordings and marks them.</p>
                    <p>Click below and choose <strong>Allow</strong> when your browser asks for your microphone.</p>
                    <div class="navigation">
                        <div></div>
                        <button type="button" class="btn btn-primary" id="micGateBtn" onclick="requestMicAccess()"><i class="bi bi-mic-fill me-1"></i> Enable Microphone &amp; Start Speaking</button>
                    </div>
                    <div id="micGateError" class="audio-warning" style="display:none;"></div>
                </div>

                <div class="celpip-shell" id="celpipShell" style="display:none;">
                    <?php foreach ($speakTasks as $tNum => $task):
                        $images = !empty($task['images']) ? $task['images'] : (!empty($task['image']) ? [$task['image']] : []);
                        $slots = count($images); ?>
                    <div class="celpip-screen" data-task="<?= $tNum ?>" style="<?= $tNum === 1 ? '' : 'display:none;' ?>">
                        <div class="celpip-header">
                            <div class="title"><?= htmlspecialchars($task['title']) ?></div>
                            <div class="meta">
                                <span>Preparation: <?= $task['prep'] ?> seconds</span>
                                <span>Recording: <?= $task['speak'] ?> seconds</span>
                                <button type="button" class="celpip-next-btn" onclick="speakingNext()"><?= $tNum < count($speakTasks) ? 'Next' : 'Finish Test' ?></button>
                            </div>
                        </div>
                        <div class="celpip-body speaking<?= $slots === 0 ? ' no-image' : '' ?>">
                            <div class="speaking-prompt"><?= htmlspecialchars($task['prompt']) ?></div>
                            <div class="speaking-stage slots-<?= $slots ?>">
                                <?php foreach ($images as $i => $img): ?>
                                    <div class="speaking-tile">
                                        <img src="<?= ACADEMY_URL ?>assets/img/practice_tests/CELPIP_PT_S_003/<?= $img ?>" alt="Task <?= $tNum ?> picture <?= $i + 1 ?>">
                                        <?php if ($slots === 2): ?><div class="tile-label">Option <?= $i === 0 ? 'A' : 'B' ?></div><?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="speaking-timerbar">
                                <div class="phase-pill" id="phasePill-<?= $tNum ?>"><span class="dot"></span><span id="phaseLabel-<?= $tNum ?>">Preparing</span></div>
                                <div class="progress-wrap">
                                    <div class="progress-label" id="progressLabel-<?= $tNum ?>">Preparation time</div>
                                    <div class="progress-track"><div class="progress-fill" id="progressFill-<?= $tNum ?>"></div></div>
                                </div>
                                <div class="timer-digits" id="timerDigits-<?= $tNum ?>"><?= $task['prep'] ?>s</div>
                            </div>
                            <div class="mic-indicator" id="micIndicator-<?= $tNum ?>"><span class="dot"></span> <span id="micText-<?= $tNum ?>">Recording starts after the preparation time</span></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <div class="celpip-progress">
                        <?php for ($i = 1; $i <= count($speakTasks); $i++): ?><div class="dot <?= $i === 1 ? 'current' : '' ?>" id="progressDot-<?= $i ?>"></div><?php endfor; ?>
                    </div>
                </div>
            </div>

            <div id="results" class="page">
                <div class="download-buttons">
                    <button class="btn btn-download" id="downloadSummaryPDF"><i class="bi bi-file-earmark-text me-1"></i> Download Summary PDF</button>
                    <button class="btn btn-download" id="downloadFullPDF"><i class="bi bi-file-earmark-richtext me-1"></i> Download Full Results PDF</button>
                </div>

                <div class="results-section" id="quiz-results">
                    <div class="wrap">
                        <div class="card">
                            <h1>CELPIP Diagnostic Test Results</h1>
                            <div id="summary" class="scoreRow" aria-live="polite"></div>
                            <div class="total" id="totalArea"></div>
                            <div class="details" id="detailsArea"></div>
                            <div class="controls">
                                <button class="btn btn-restart" id="restartBtn">Take Test Again</button>
                            </div>
                            <div class="ielts-info">
                                <p><strong>Note:</strong> This is a practice test. Your actual CELPIP score may vary.</p>
                                <p>For official CELPIP testing, visit <a href="https://celpip.ca" target="_blank" rel="noopener">celpip.ca</a></p>
                            </div>
                            <?php require_once INCLUDES_PATH . '/item_nav.php'; echo item_nav_html($db, 'courses/CELPIP_intro/celpip_mini_mock.php'); ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

<?php include INCLUDES_PATH . '/adverts.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<script src="scripts/script.js?v=164" defer></script>
<style>
    /* The speaking screen of the practice tests, without their fixed-height page (this page scrolls). */
    #section4 .celpip-shell { display: flex; flex-direction: column; border: 1px solid #d7dce0; }
    #section4 .celpip-body.speaking { display: grid; grid-template-rows: auto minmax(260px, 46vh) auto auto; gap: .75rem; padding: 1rem 1.25rem; background: #fff; }
    #section4 .celpip-body.speaking.no-image { grid-template-rows: auto 0 auto auto; }
    #section4 .celpip-body.speaking.no-image .speaking-stage { display: none; }
    #section4 .speaking-prompt { color: #1f2937; font-size: .95rem; line-height: 1.6; white-space: pre-line; }
    #section4 .speaking-stage { min-height: 0; display: grid; gap: 3px; background: #e9ebef; overflow: hidden; }
    #section4 .speaking-stage.slots-1 { grid-template-columns: 1fr; } #section4 .speaking-stage.slots-2 { grid-template-columns: 1fr 1fr; }
    #section4 .speaking-tile { position: relative; overflow: hidden; background: #e9ebef; display: flex; align-items: center; justify-content: center; }
    #section4 .speaking-tile img { width: 100%; height: 100%; object-fit: cover; display: block; }
    #section4 .speaking-tile .tile-label { position: absolute; top: 6px; left: 6px; font-size: 11px; padding: 2px 7px; background: rgba(27,35,48,.72); color: #fff; }
    :root { --prep-fill:#e9932e; --prep-tint:#fdf1e2; --prep-ink:#8a4f0c; --speak-fill:#1f9aa0; --speak-tint:#e3f4f4; --speak-ink:#0e5c60; --done-fill:#b23b3b; --done-tint:#fbe9e9; --done-ink:#7c2626; }
    #section4 .speaking-timerbar { display: grid; grid-template-columns: 110px 1fr 100px; align-items: center; gap: 14px; }
    #section4 .phase-pill { height: 28px; font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 6px; background: #f1f3f5; color: #8a92a0; white-space: nowrap; }
    #section4 .phase-pill .dot { width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
    #section4 .phase-pill.is-prep { background: var(--prep-tint); color: var(--prep-ink); }
    #section4 .phase-pill.is-speak { background: var(--speak-tint); color: var(--speak-ink); }
    #section4 .phase-pill.is-done { background: var(--done-tint); color: var(--done-ink); }
    #section4 .progress-wrap { display: flex; flex-direction: column; gap: 6px; min-width: 0; }
    #section4 .progress-label { font-size: 11px; color: #8a92a0; }
    #section4 .progress-track { height: 8px; background: #f1f3f5; overflow: hidden; }
    #section4 .progress-fill { height: 100%; width: 0%; background: #8a92a0; transition: width 1s linear; }
    #section4 .progress-fill.is-prep { background: var(--prep-fill); } #section4 .progress-fill.is-speak { background: var(--speak-fill); } #section4 .progress-fill.is-done { background: var(--done-fill); }
    #section4 .timer-digits { height: 36px; background: #f1f3f5; display: flex; align-items: center; justify-content: center; font-family: 'Courier New', monospace; font-weight: 700; font-size: 18px; color: #1f2937; }
    #section4 .timer-digits.is-prep { background: var(--prep-tint); color: var(--prep-ink); } #section4 .timer-digits.is-speak { background: var(--speak-tint); color: var(--speak-ink); } #section4 .timer-digits.is-done { background: var(--done-tint); color: var(--done-ink); }
    #section4 .mic-indicator { display: flex; align-items: center; gap: .5rem; justify-content: center; color: #6b7280; font-size: .85rem; }
    #section4 .mic-indicator.recording { color: #b91c1c; font-weight: 600; }
    #section4 .mic-indicator .dot { width: 8px; height: 8px; border-radius: 50%; background: currentColor; }
    #section4 .celpip-progress .dot.done { background: #9c1f2e; }
</style>
<script>
// Speaking, as on the CELPIP speaking practice tests (celpip_speaking_00N.php): each task prepares, then records by
// itself; NEXT during preparation starts the recording rather than skipping the task; the last NEXT finishes the
// test (finishQuiz, scripts/script.js). Each recording keeps its own chunks (a shared array once swapped recordings).
// Recordings go to the speaking store as CELPIP_DIAGNOSTIC, where a tutor listens and marks them.
const SPEAK = <?= json_encode(array_map(fn ($t) => ['title' => $t['title'], 'prompt' => $t['prompt'], 'prep' => $t['prep'], 'speak' => $t['speak']], $speakTasks)) ?>;
const SPEAK_TOTAL = <?= count($speakTasks) ?>;
const SPEAK_UNTIMED = <?= $isAdmin ? 'true' : 'false' ?>;
let spTask = 1, spPrep = null, spRec = null, spRecorder = null, spStream = null;
const spPhase = {}, spStarted = {};

function spSetPhase(n, phase) {
    ['phasePill-', 'progressFill-', 'timerDigits-'].forEach(id => {
        const el = document.getElementById(id + n); el.classList.remove('is-prep', 'is-speak', 'is-done'); el.classList.add('is-' + phase);
    });
}
function spCountdown(n, secs0, onEnd) {
    const digits = document.getElementById('timerDigits-' + n), fill = document.getElementById('progressFill-' + n);
    let secs = secs0; digits.textContent = secs + 's'; fill.style.width = '100%';
    return setInterval(() => {
        secs--; digits.textContent = Math.max(secs, 0) + 's'; fill.style.width = Math.max(0, secs / secs0 * 100) + '%';
        if (secs <= 0) onEnd();
    }, 1000);
}
function spStartTask(n) {
    spPhase[n] = 'prep'; spSetPhase(n, 'prep');
    document.getElementById('phaseLabel-' + n).textContent = 'Preparing';
    document.getElementById('progressLabel-' + n).textContent = 'Preparation time';
    if (SPEAK_UNTIMED) { document.getElementById('timerDigits-' + n).textContent = ''; return; }
    spPrep = spCountdown(n, SPEAK[n].prep, () => { clearInterval(spPrep); spBeginRecording(n); });
}
function spBeginRecording(n) {
    spPhase[n] = 'speak'; spStarted[n] = Date.now(); spSetPhase(n, 'speak');
    document.getElementById('phaseLabel-' + n).textContent = 'Speaking';
    document.getElementById('progressLabel-' + n).textContent = 'Recording your answer';
    const mic = document.getElementById('micIndicator-' + n); mic.classList.add('recording');
    document.getElementById('micText-' + n).textContent = 'Recording';
    spCapture(n);
    spRec = spCountdown(n, SPEAK[n].speak, () => {
        clearInterval(spRec); spStopAndUpload(n); spPhase[n] = 'done'; spSetPhase(n, 'done');
        document.getElementById('phaseLabel-' + n).textContent = "Time's up";
        document.getElementById('progressLabel-' + n).textContent = 'Response complete';
        mic.classList.remove('recording'); document.getElementById('micText-' + n).textContent = 'Response recorded';
    });
}
async function spCapture(n) {
    if (spRecorder && spRecorder.state === 'recording') return;   // never two recordings at once
    const chunks = [];
    try {
        spStream = await navigator.mediaDevices.getUserMedia({ audio: true });
        const rec = new MediaRecorder(spStream); rec._chunks = chunks;
        rec.ondataavailable = e => { if (e.data.size > 0) chunks.push(e.data); };
        rec.start(); spRecorder = rec;
    } catch (err) { console.error('Microphone failed for task ' + n, err); spRecorder = null; }
}
function spStopAndUpload(n) {
    if (!spRecorder || spRecorder.state === 'inactive') return;
    const rec = spRecorder, stream = spStream, chunks = rec._chunks || [];
    rec.onstop = () => {
        const blob = new Blob(chunks, { type: 'audio/webm' });
        if (blob.size > 0) {
            const fd = new FormData();
            fd.append('audio', blob, 'speaking_task_' + n + '.webm');
            fd.append('test_code', 'CELPIP_DIAGNOSTIC'); fd.append('task_number', n);
            fd.append('task_title', SPEAK[n].title); fd.append('prompt', SPEAK[n].prompt);
            fetch('../../api/speaking_upload.php', { method: 'POST', body: fd }).catch(err => console.error('Saving task ' + n + ' failed', err));
        }
        stream.getTracks().forEach(t => t.stop());
    };
    rec.stop(); spRecorder = null;
}
function spShow(n) {
    document.querySelectorAll('#section4 .celpip-screen').forEach(s => s.style.display = 'none');
    document.querySelector('#section4 .celpip-screen[data-task="' + n + '"]').style.display = '';
    for (let i = 1; i <= SPEAK_TOTAL; i++) {
        const d = document.getElementById('progressDot-' + i); d.classList.remove('current', 'done');
        if (i < n) d.classList.add('done'); else if (i === n) d.classList.add('current');
    }
    spTask = n; spStartTask(n); window.scrollTo(0, 0);
}
function speakingNext() {
    const phase = spPhase[spTask];
    if (phase === 'prep') { clearInterval(spPrep); spBeginRecording(spTask); return; }   // start it, don't skip it
    if (phase === 'speak' && Date.now() - (spStarted[spTask] || 0) < 1200) return;      // a double-click right after starting
    clearInterval(spPrep); clearInterval(spRec); spStopAndUpload(spTask);
    if (spTask < SPEAK_TOTAL) spShow(spTask + 1); else finishQuiz();
}
async function requestMicAccess() {
    const btn = document.getElementById('micGateBtn'), errEl = document.getElementById('micGateError');
    btn.disabled = true; errEl.style.display = 'none';
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        stream.getTracks().forEach(t => t.stop());
        document.getElementById('micGate').style.display = 'none';
        document.getElementById('celpipShell').style.display = '';
        spShow(1);
    } catch (err) {
        btn.disabled = false; btn.innerHTML = '<i class="bi bi-mic-fill me-1"></i> Try Again';
        errEl.textContent = (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError')
            ? 'Microphone access was denied. Allow it from the padlock icon in your address bar, then click Try Again.'
            : (err.name === 'NotFoundError' ? 'No microphone was found. Connect one and click Try Again.' : 'Could not access your microphone: ' + (err.message || err.name) + '.');
        errEl.style.display = '';
    }
}
</script>
<script>
// The section tabs follow whichever page the test is on (scripts/script.js switches .page.active); earlier
// sections show as done.
(function () {
    const order = ['intro', 'section1', 'section2', 'section3', 'section4', 'results'];
    const tabs = document.querySelectorAll('.mini-tabs [data-tab-page]');
    const sync = () => {
        const cur = document.querySelector('.page.active');
        const at = cur ? order.indexOf(cur.id) : 0;
        tabs.forEach(t => {
            const i = order.indexOf(t.dataset.tabPage);
            t.classList.toggle('active', i === at);
            t.classList.toggle('done', i < at);
        });
        window.scrollTo(0, 0);
    };
    document.querySelectorAll('.page').forEach(p => new MutationObserver(sync).observe(p, { attributes: true, attributeFilter: ['class'] }));
    sync();
})();
</script>
</body>
</html>
