<?php
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once __DIR__ . '/course_context.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../edu_hub_registration.php?message=Please+login+to+access+this+course");
    exit();
}

if (!can_access('intermediate')) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <title>IELTS General Masterclass — Class 2: Diagnostic Mini Mock & Band Descriptor Review</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <link href="../../../assets/css/courses.css" rel="stylesheet">
        <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    </head>
    <body>
        <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
        <div class="mobile-overlay" id="mobileOverlay"></div>
        <?php include INCLUDES_PATH . '/navbar.php'; ?>
        <main class="main-wrapper">
            <div class="course-card">
                <?php render_upgrade_prompt('intermediate', 'Class 2: Diagnostic Mini Mock & Band Descriptor Review'); ?>
            </div>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS General Masterclass — Class 2: Diagnostic Mini Mock & Band Descriptor Review</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../../../assets/css/courses.css" rel="stylesheet">
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
                    <li class="breadcrumb-item">
                        <a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Class 2: Diagnostic Mini Mock &amp; Band Descriptor Review</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-clipboard-check me-2" style="color: #0b77ff;"></i>
                Class 2: Diagnostic Mini Mock &amp; Band Descriptor Review
            </h1>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="mb-2" style="color: var(--accent);">Class Overview</h4>
                        <p class="mb-0">
                            <strong>Module:</strong> Month 1 — Foundations &nbsp;|&nbsp;
                            <strong>Class:</strong> 2 of 24 &nbsp;|&nbsp;
                            <strong>Duration:</strong> 90 min
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-custom">Diagnostic Mock</span>
                        <span class="badge-custom">Band Descriptors</span>
                        <span class="badge-custom">Self-Assessment</span>
                    </div>
                </div>
            </div>

            <!-- Section 1: What is a Diagnostic Mock? -->
            <div class="content-section">
                <h2><i class="bi bi-clipboard-check me-2" style="color: #0b77ff;"></i>What is a Diagnostic Mock?</h2>
                <p>
                    A diagnostic mock is not about scoring well — it is about establishing a clear, honest baseline
                    across all four IELTS skills before any targeted teaching begins. Without a baseline, it is
                    impossible to know where your time and effort should go. With one, every subsequent class
                    has a purpose.
                </p>
                <p>
                    The abridged diagnostic mock in this class covers a representative sample from each skill:
                </p>
                <div class="info-grid">
                    <div class="info-card" style="border-color: #0b77ff;">
                        <h4 style="color: #0b77ff;"><i class="bi bi-headphones me-2"></i>Listening</h4>
                        <p class="mb-0">Parts 1 and 2 only. This covers an everyday social dialogue and a monologue in a social context — the two most accessible parts of the Listening test. You will encounter form completion and multiple-choice question types.</p>
                    </div>
                    <div class="info-card" style="border-color: #0b77ff;">
                        <h4 style="color: #0b77ff;"><i class="bi bi-book me-2"></i>Reading</h4>
                        <p class="mb-0">One passage only — a general-interest text of approximately 600 words with 12 questions. This is equivalent to Section 3 of the IELTS General Reading paper: the most demanding of the three reading sections.</p>
                    </div>
                    <div class="info-card" style="border-color: #0b77ff;">
                        <h4 style="color: #0b77ff;"><i class="bi bi-pencil me-2"></i>Writing</h4>
                        <p class="mb-0">Task 1 letter only, 20 minutes. You will be given a prompt that requires either a formal, semi-formal, or informal letter. The aim is to see your instinctive register choices and vocabulary range before any instruction.</p>
                    </div>
                    <div class="info-card" style="border-color: #0b77ff;">
                        <h4 style="color: #0b77ff;"><i class="bi bi-mic me-2"></i>Speaking</h4>
                        <p class="mb-0">Part 1 only, approximately 4 minutes. The examiner (or a recorded simulation) will ask 4–6 questions on familiar topics such as your home, your studies or work, and a leisure activity. You respond naturally, without preparation.</p>
                    </div>
                </div>
                <p class="mt-3">
                    <strong>Important:</strong> The diagnostic is not scored against an official band scale for ranking purposes.
                    It is scored against <em>your own performance</em> to identify patterns — where you run out of time,
                    where vocabulary fails you, where confidence drops. These patterns drive your Month 1 focus.
                </p>
            </div>

            <!-- Section 2: Band Descriptors -->
            <div class="content-section">
                <h2><i class="bi bi-bar-chart-line me-2" style="color: #0b77ff;"></i>IELTS Band Descriptors — How You Are Marked</h2>
                <p>
                    Understanding exactly how IELTS examiners mark your responses is the single biggest shortcut
                    available to you. Each skill has its own marking criteria. Knowing these criteria means you
                    can target your effort precisely — rather than studying everything and improving nothing.
                </p>

                <h3>Listening &amp; Reading</h3>
                <p>
                    These two skills use a raw score system. Every correct answer is worth one mark. Your raw
                    score out of 40 is converted to a band score using a published conversion table. For IELTS
                    General Reading, the conversion is more generous than for Academic — for example, 30 correct
                    answers typically converts to Band 7.0 in General Training.
                </p>
                <ul class="custom-list">
                    <li><strong>39–40 correct:</strong> Band 9.0</li>
                    <li><strong>34–35 correct:</strong> Band 8.0</li>
                    <li><strong>30 correct:</strong> Band 7.0</li>
                    <li><strong>23–24 correct:</strong> Band 6.0</li>
                    <li><strong>15–18 correct:</strong> Band 5.0</li>
                </ul>
                <p>There is no penalty for wrong answers — always attempt every question.</p>

                <h3 class="mt-4">Writing — Four Criteria</h3>
                <p>Writing Task 1 and Task 2 are each marked by a trained examiner on four equally-weighted criteria:</p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-check2-circle me-2" style="color: #0b77ff;"></i>Task Achievement (TA)</h4>
                        <p class="mb-0">Have you done what the task asked? For Task 1, this means: Did you address all three bullet points? Did you use an appropriate register? Did you write at least 150 words? For Task 2: Did you answer the question fully and support your position?</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-diagram-3 me-2" style="color: #0b77ff;"></i>Coherence &amp; Cohesion (CC)</h4>
                        <p class="mb-0">Does your writing flow logically? Are ideas sequenced clearly? Do you use cohesive devices (linking words, pronouns, referencing) appropriately — not excessively? A common error is overusing "Furthermore" and "Moreover" to the point of mechanical repetition.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-translate me-2" style="color: #0b77ff;"></i>Lexical Resource (LR)</h4>
                        <p class="mb-0">How wide and accurate is your vocabulary? Can you paraphrase the question? Do you use word forms correctly (noun/verb/adjective)? Do you spell accurately? Examiners notice both the range of vocabulary you use and the precision with which you use it.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-braces me-2" style="color: #0b77ff;"></i>Grammatical Range &amp; Accuracy (GRA)</h4>
                        <p class="mb-0">Do you use a variety of sentence structures — not just simple sentences? Are your complex sentences accurate? Occasional errors are acceptable at Band 7. Systematic errors in basic structures (articles, subject-verb agreement, tense) indicate a lower band.</p>
                    </div>
                </div>

                <h3 class="mt-4">Speaking — Four Criteria</h3>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-chat-dots me-2" style="color: #0b77ff;"></i>Fluency &amp; Coherence (FC)</h4>
                        <p class="mb-0">Do you speak at a natural pace without excessive hesitation? Are your ideas logically connected? Fluency does not mean speaking without pausing — it means pausing naturally, not searching desperately for words. Coherence means your listener can follow your reasoning.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-translate me-2" style="color: #0b77ff;"></i>Lexical Resource (LR)</h4>
                        <p class="mb-0">Same principle as Writing — range and precision of vocabulary. In Speaking, you are also assessed on your ability to use less common or idiomatic language naturally. Paraphrasing when you cannot recall a word (rather than stopping) demonstrates lexical flexibility.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-braces me-2" style="color: #0b77ff;"></i>Grammatical Range &amp; Accuracy (GRA)</h4>
                        <p class="mb-0">Spoken grammar is assessed differently from written grammar — spontaneous speech naturally contains some self-correction. Examiners look for a mix of simple and complex structures. Consistently using only simple sentences limits your band regardless of accuracy.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-soundwave me-2" style="color: #0b77ff;"></i>Pronunciation (P)</h4>
                        <p class="mb-0">Can the examiner understand you without effort? Pronunciation is not about having a British or American accent — it is about clarity. Word stress, sentence stress, and intonation all contribute. A strong accent does not penalise you; consistently mispronouncing common words does.</p>
                    </div>
                </div>
            </div>

            <!-- Section 3: Self-Assessment Framework -->
            <div class="content-section">
                <h2><i class="bi bi-person-check me-2" style="color: #0b77ff;"></i>Self-Assessment Framework</h2>
                <p>
                    Once you have completed the diagnostic mock and reviewed your answers, the next step is
                    structured self-assessment. This is not about being harsh on yourself — it is about being
                    precise. Vague feedback ("my writing was bad") produces vague improvement. Precise feedback
                    ("I only addressed 2 of the 3 bullet points in Task 1 and my sign-off was wrong for the register")
                    produces a clear action.
                </p>
                <h3>The Guided Correction Approach</h3>
                <ol class="custom-list">
                    <li>Review each of your answers against the correct answers or marking criteria provided.</li>
                    <li>For Writing and Speaking, use the four band descriptor criteria as a checklist — score yourself honestly on each one from 1–9.</li>
                    <li>Identify your <strong>2 strongest areas</strong> — the criteria where you performed best. These are your foundation; they need maintenance, not major work.</li>
                    <li>Identify your <strong>2 weakest areas</strong> — the criteria where the gap between your current performance and your target band is largest. These drive your Month 1 focus.</li>
                    <li>Create a <strong>personal learning priority list</strong>: a ranked list of 3–5 specific skills or habits to build before the end of Month 1.</li>
                </ol>
                <div class="highlight-box mt-3">
                    <h5><i class="bi bi-lightbulb me-2"></i>Why This Matters</h5>
                    <p class="mb-0">
                        Most IELTS candidates study everything equally and improve nothing measurably. The self-assessment
                        framework forces you to make deliberate choices about where your study time goes. A student who
                        identifies "Task Achievement" as their weakest Writing criterion and spends 30 minutes per day
                        practising addressing bullet points fully will improve faster than a student who reads generic
                        grammar exercises for an hour.
                    </p>
                </div>
            </div>

            <!-- Videos -->
            <div class="content-section">
                <h2><i class="bi bi-play-circle me-2" style="color: #0b77ff;"></i>Class 2 Videos</h2>
                <p>Watch these videos as part of today's class or as a follow-up review.</p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>IELTS Writing Section Overview</h4>
                        <p class="text-muted mb-2">An introduction to both Writing tasks — what is required, how much time to allocate, and how the marking criteria apply to each task type.</p>
                        <div class="p-3 text-center" style="background: #f1f5f9; border-radius: 8px;">
                            <i class="bi bi-play-circle-fill" style="font-size: 2rem; color: #0b77ff;"></i>
                            <p class="mt-1 mb-0 small text-muted">Video coming soon</p>
                        </div>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>Writing Test Overview</h4>
                        <p class="text-muted mb-2">A walkthrough of the full Writing paper — timing, word counts, and the most common mistakes candidates make in Tasks 1 and 2.</p>
                        <div class="p-3 text-center" style="background: #f1f5f9; border-radius: 8px;">
                            <i class="bi bi-play-circle-fill" style="font-size: 2rem; color: #0b77ff;"></i>
                            <p class="mt-1 mb-0 small text-muted">Video coming soon</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quiz -->
            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2" style="color: #0b77ff;"></i>Class 2 Quiz</h2>
                <div class="highlight-box" style="border-left-color: #16a34a; background: #f0fdf4;">
                    <h5 style="color: #16a34a;"><i class="bi bi-check2-square me-2"></i>Band Descriptor Matching Quiz</h5>
                    <p class="mb-2">
                        Match the descriptor phrase to the correct marking criterion. For example: "uses a range of
                        cohesive devices although there may be some inaccuracies" — is this Task Achievement, Coherence &amp;
                        Cohesion, Lexical Resource, or Grammatical Range &amp; Accuracy?
                    </p>
                    <p class="mb-2"><strong>Format:</strong> 10 questions | Matching exercise</p>
                    <p class="text-muted mb-0"><i class="bi bi-clock me-1"></i>Quiz questions coming soon</p>
                </div>
            </div>

            <!-- Take-Home -->
            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color: #0b77ff;"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h5><i class="bi bi-pencil-square me-2"></i>Your Task Before Class 3</h5>
                    <p class="mb-2">
                        <strong>1. Informal letter:</strong> Draft your first informal letter of 150+ words on any topic of your
                        choice. Do not worry about perfection — write freely and see what vocabulary and structures come
                        naturally. This letter will be reviewed and annotated against the band descriptors in Class 3.
                    </p>
                    <p class="mb-0">
                        <strong>2. Speaking recording:</strong> Record a 2-minute Speaking Part 1 response on the topic
                        "Tell me about your hometown." Speak without notes. Listen back once and note: How many times did
                        you pause for more than 2 seconds? Did you answer with one sentence or did you extend your response?
                        Both pieces of work will be reviewed in Class 3.
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="action-buttons">
                <a href="intro.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Previous: Class 1
                </a>
                <a href="class03.php" class="btn btn-primary" style="background-color: #0b77ff; border-color: #0b77ff;">
                    Next: Class 3 <i class="bi bi-arrow-right ms-1"></i>
                </a>
                <a href="<?= htmlspecialchars($back['url']) ?>" class="btn btn-outline-primary">
                    <i class="bi bi-grid me-1"></i> Course Overview
                </a>
            </div>

        </div><!-- /.course-card -->

        <aside class="advert-sidebar">
            <div class="ad-container">
                <div class="ad-placeholder" style="width:300px; height:250px;">
                    <i class="bi bi-image" style="font-size:2rem; opacity:0.3;"></i>
                    <p class="mb-0 small">Advertisement 300×250</p>
                </div>
            </div>
            <div class="ad-container mt-3">
                <div class="ad-placeholder" style="width:300px; height:250px;">
                    <i class="bi bi-image" style="font-size:2rem; opacity:0.3;"></i>
                    <p class="mb-0 small">Advertisement 300×250</p>
                </div>
            </div>
            <div class="ad-container mt-3">
                <div class="p-3 rounded-3 text-white" style="background: linear-gradient(135deg, #0b77ff, #6366f1);">
                    <h6 class="fw-bold mb-3"><i class="bi bi-map me-2"></i>Course Navigation</h6>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2"><a href="intro.php" class="text-white text-decoration-none"><i class="bi bi-arrow-left-circle me-1"></i>Class 1: Orientation</a></li>
                        <li class="mb-2"><a href="intro.php" class="text-white text-decoration-none"><i class="bi bi-chevron-left me-1"></i>Previous Class</a></li>
                        <li class="mb-2"><a href="class03.php" class="text-white text-decoration-none"><i class="bi bi-chevron-right me-1"></i>Next Class</a></li>
                        <li><a href="<?= htmlspecialchars($back['url']) ?>" class="text-white text-decoration-none"><i class="bi bi-grid me-1"></i>Course Overview</a></li>
                    </ul>
                </div>
            </div>
        </aside>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar    = document.querySelector('.sidebar');
        const overlay    = document.getElementById('mobileOverlay');
        function toggleMenu() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            const icon = menuToggle.querySelector('i');
            icon.className = sidebar.classList.contains('active') ? 'bi bi-x-lg' : 'bi bi-list';
        }
        menuToggle.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu);
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', () => { if (window.innerWidth < 1200) toggleMenu(); });
        });
    </script>
</body>
</html>
