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
        <title>IELTS General Masterclass — Class 3: Listening — Form Completion & Gap-fill · Speaking — Introduction & Fluency</title>
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
                <?php render_upgrade_prompt('intermediate', 'Class 3: Listening — Form Completion & Gap-fill · Speaking — Introduction & Fluency'); ?>
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
    <title>IELTS General Masterclass — Class 3: Listening — Form Completion & Gap-fill · Speaking — Introduction & Fluency</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Class 3: Listening — Form Completion &amp; Gap-fill · Speaking — Introduction &amp; Fluency</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-headphones me-2" style="color: #0b77ff;"></i>
                Class 3: Listening — Form Completion &amp; Gap-fill &nbsp;&middot;&nbsp; Speaking — Introduction &amp; Fluency
            </h1>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="mb-2" style="color: var(--accent);">Class Overview</h4>
                        <p class="mb-0">
                            <strong>Module:</strong> Month 1 — Foundations &nbsp;|&nbsp;
                            <strong>Class:</strong> 3 of 24 &nbsp;|&nbsp;
                            <strong>Duration:</strong> 90 min
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-custom">Form Completion</span>
                        <span class="badge-custom">Gap-fill</span>
                        <span class="badge-custom">Speaking Part 1</span>
                    </div>
                </div>
            </div>

            <!-- Practice Test -->
            <div class="content-section">
                <h2><i class="bi bi-stopwatch me-2" style="color: #0b77ff;"></i>Practice Test</h2>
                <div class="highlight-box" style="border-left-color: #0b77ff; background: #eff6ff;">
                    <h5 style="color: #0b77ff;"><i class="bi bi-journal-text me-2"></i>Practice Test Set 1 — Listening Section</h5>
                    <p class="mb-1">Parts 1–4, full timed conditions. 40 questions, 30 minutes.</p>
                    <p class="mb-2 text-muted"><i class="bi bi-clock me-1"></i>Complete under timed conditions. Do not pause the recording once it starts.</p>
                    <a href="<?= ACADEMY_URL ?>resources/practice_tests/ielts_listening_001.php"
                       class="btn btn-primary">
                        <i class="bi bi-play-circle me-2"></i>Start Listening Practice Test 1
                    </a>
                </div>
            </div>

            <!-- Section 1: Form Completion -->
            <div class="content-section">
                <h2><i class="bi bi-ui-checks me-2" style="color: #0b77ff;"></i>Listening — Form Completion</h2>
                <p>
                    Form completion is one of the most predictable question types in IELTS Listening — and
                    predictability is your advantage. IELTS always gives you a partially-filled form before the
                    recording plays. Those pre-filled answers are your orientation points; the gaps are what you
                    need to supply.
                </p>
                <h3>Before the Recording Plays</h3>
                <p>
                    You are given 30–45 seconds to preview the form. Use every second. For each gap, ask yourself:
                    what <em>type</em> of information is missing? You are not guessing the answer — you are predicting
                    its category. This primes your brain to hear the right kind of information.
                </p>
                <div class="info-grid">
                    <div class="info-card" style="border-color: #0b77ff;">
                        <h4 style="color: #0b77ff;"><i class="bi bi-person me-2"></i>Name gaps</h4>
                        <p class="mb-0">Names are almost always spelled out in the recording. Write each letter as you hear it — do not try to hold the whole name in memory. If you miss a letter, leave a blank and move on; do not stop listening.</p>
                    </div>
                    <div class="info-card" style="border-color: #0b77ff;">
                        <h4 style="color: #0b77ff;"><i class="bi bi-123 me-2"></i>Number gaps</h4>
                        <p class="mb-0">Phone numbers, reference numbers, prices, and dates. Write exactly what you hear. The biggest trap: "seventeen" (17) versus "seventy" (70). Listen for the stress — "sevENteen" ends with a stressed syllable; "SEVenty" is stressed at the front.</p>
                    </div>
                    <div class="info-card" style="border-color: #0b77ff;">
                        <h4 style="color: #0b77ff;"><i class="bi bi-geo-alt me-2"></i>Address &amp; place gaps</h4>
                        <p class="mb-0">Street names and postcodes are often spelled out. Road types ("Avenue," "Close," "Boulevard") are rarely spelled — listen for the full word. Postcodes in British English alternate letters and numbers: "SW1A 2AA" for example.</p>
                    </div>
                    <div class="info-card" style="border-color: #0b77ff;">
                        <h4 style="color: #0b77ff;"><i class="bi bi-calendar me-2"></i>Date &amp; time gaps</h4>
                        <p class="mb-0">Dates may be expressed multiple ways: "the fourteenth of March" or "March fourteenth." Write them in a consistent format. Times use either 12-hour ("half past three") or 24-hour ("fifteen thirty") expressions — convert if needed.</p>
                    </div>
                </div>
                <div class="highlight-box mt-3">
                    <h5><i class="bi bi-exclamation-triangle me-2"></i>Critical Rule: IELTS Paraphrases</h5>
                    <p class="mb-0">
                        IELTS almost never uses the exact words from the form in the recording. If the form says
                        "Membership type," the speaker might say "Which category of membership would you like?" or
                        "What kind of account are you opening?" Your job is to match the meaning, not the words.
                        Train yourself to listen for paraphrases, not repetition.
                    </p>
                </div>
            </div>

            <!-- Section 2: Gap-fill Strategies -->
            <div class="content-section">
                <h2><i class="bi bi-input-cursor-text me-2" style="color: #0b77ff;"></i>Listening — Gap-fill Strategies</h2>
                <p>
                    Gap-fill tasks (also called sentence completion or note completion) require you to complete
                    a sentence, a set of notes, or a short paragraph with words taken directly from the recording.
                    The golden rule: copy the word(s) exactly as you hear them — do not paraphrase, do not change
                    the form.
                </p>
                <h3>Word Limit: Read it Carefully</h3>
                <p>
                    Every gap-fill task specifies a word limit: "Write <strong>NO MORE THAN TWO WORDS AND/OR A NUMBER</strong>"
                    is the most common instruction. This means your answer can be:
                </p>
                <ul class="custom-list">
                    <li>One word: "Thursday"</li>
                    <li>Two words: "Thursday morning"</li>
                    <li>A number: "35"</li>
                    <li>A word and a number: "Gate 7" or "Floor 3"</li>
                </ul>
                <p>
                    Exceeding the word limit — even with a correct answer — will be marked wrong. Articles ("a," "the")
                    count as words. Hyphenated words (e.g., "well-known") are typically counted as one word. If you
                    are unsure, err on the side of fewer words.
                </p>
                <h3>The Prediction Technique</h3>
                <p>
                    Before the recording plays, read each gap in context. The words surrounding the gap tell you what
                    grammatical form and semantic category you need. This is the prediction technique:
                </p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-arrow-right-circle me-2" style="color: #0b77ff;"></i>Grammatical prediction</h4>
                        <p class="mb-0">"The museum is open every day except ___." The gap follows "except" — predict a day of the week or a date. "Participants must bring ___ identification." The gap is preceded by an article — predict a noun, probably an adjective-noun pair like "photo identification."</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-lightbulb me-2" style="color: #0b77ff;"></i>Semantic prediction</h4>
                        <p class="mb-0">"The course costs ___ per term." Predict a price. "Contact the office by ___." Predict a date or deadline. Building this habit means you listen with focus — you know what category of answer you need, so your brain filters out irrelevant information automatically.</p>
                    </div>
                </div>
                <div class="highlight-box mt-3">
                    <h5><i class="bi bi-info-circle me-2"></i>Order Matters</h5>
                    <p class="mb-0">
                        IELTS gap-fill tasks always follow the order of the recording. Gap 1 is answered before Gap 2,
                        which is answered before Gap 3. If you miss an answer, do not go back — write your best guess
                        and keep your focus on the next gap. Missing one question because you are still thinking about
                        the previous one is the most common and most avoidable Listening error.
                    </p>
                </div>
            </div>

            <!-- Section 3: Speaking Part 1 -->
            <div class="content-section">
                <h2><i class="bi bi-mic me-2" style="color: #0b77ff;"></i>Speaking — Introduction &amp; Fluency</h2>
                <p>
                    Speaking Part 1 is the opening section of your IELTS Speaking test. It lasts 4–5 minutes
                    and consists of questions on familiar, everyday topics: your home, your work or studies,
                    your hobbies and interests, your daily routines. The examiner is not trying to trick you —
                    they want to hear you speak comfortably and naturally.
                </p>
                <h3>What the Examiner Wants to Hear</h3>
                <p>
                    In Part 1, <strong>fluency matters more than accuracy</strong>. An answer delivered smoothly
                    with minor grammar errors will score higher than a grammatically perfect answer delivered in
                    halting, hesitant speech with long pauses. The examiner is assessing your ability to communicate,
                    not to recite memorised paragraphs. Scripted, rehearsed-sounding answers are penalised.
                </p>
                <h3 class="mt-3">The IDEA Framework</h3>
                <p>Use the IDEA framework for every Part 1 answer to ensure you extend your response naturally:</p>
                <div class="info-grid">
                    <div class="info-card" style="border-color: #0b77ff;">
                        <h4 style="color: #0b77ff;"><span class="fw-bold" style="font-size: 1.3rem;">I</span> — Identify</h4>
                        <p class="mb-0">Identify the question type and topic. This happens in your head — it takes less than a second. Is this a yes/no question? A preference question? A frequency question? Your answer structure depends on the question type.</p>
                    </div>
                    <div class="info-card" style="border-color: #0b77ff;">
                        <h4 style="color: #0b77ff;"><span class="fw-bold" style="font-size: 1.3rem;">D</span> — Direct Answer</h4>
                        <p class="mb-0">Answer the question directly in your first sentence. Do not warm up with filler phrases like "Well, that's an interesting question..." — go straight to the answer. "Yes, I do." / "Not really." / "I'd say about three times a week."</p>
                    </div>
                    <div class="info-card" style="border-color: #0b77ff;">
                        <h4 style="color: #0b77ff;"><span class="fw-bold" style="font-size: 1.3rem;">E</span> — Expand</h4>
                        <p class="mb-0">Add a reason, a feeling, or a qualification. "Yes, I enjoy cooking. I find it quite relaxing after a long day, actually." The word "because" is your expansion trigger — use it instinctively after every direct answer.</p>
                    </div>
                    <div class="info-card" style="border-color: #0b77ff;">
                        <h4 style="color: #0b77ff;"><span class="fw-bold" style="font-size: 1.3rem;">A</span> — Add</h4>
                        <p class="mb-0">Add a specific example, a contrast, or an extra detail. "I usually make pasta or stir-fries — nothing too complicated, but I enjoy experimenting occasionally." This final layer is what separates a Band 6 answer from a Band 7+ answer.</p>
                    </div>
                </div>
                <div class="highlight-box mt-3">
                    <h5><i class="bi bi-chat-quote me-2"></i>IDEA in Action</h5>
                    <p class="mb-1"><strong>Question:</strong> "Do you like cooking?"</p>
                    <p class="mb-1"><strong>Weak answer (Band 5):</strong> "Yes, I like cooking. It is nice."</p>
                    <p class="mb-0"><strong>Strong answer (Band 7):</strong> "Yes, I do. <em>[D]</em> I find cooking quite relaxing, actually — it's a good way to unwind after work. <em>[E]</em> I usually stick to simple dishes like pasta or stir-fries, but I do enjoy trying new recipes at the weekend when I have more time. <em>[A]</em>"</p>
                </div>
            </div>

            <!-- Videos -->
            <div class="content-section">
                <h2><i class="bi bi-play-circle me-2" style="color: #0b77ff;"></i>Class 3 Videos</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>L1 Part 1 Listening Class 1</h4>
                        <p class="text-muted mb-2">Form completion and gap-fill strategies for Listening Parts 1 and 2, with worked examples.</p>
                        <div class="p-3 text-center" style="background: #f1f5f9; border-radius: 8px;">
                            <i class="bi bi-play-circle-fill" style="font-size: 2rem; color: #0b77ff;"></i>
                            <p class="mt-1 mb-0 small text-muted">Video coming soon</p>
                        </div>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>Listening Question Types</h4>
                        <p class="text-muted mb-2">An overview of all IELTS Listening question types and when each appears in the four parts.</p>
                        <div class="p-3 text-center" style="background: #f1f5f9; border-radius: 8px;">
                            <i class="bi bi-play-circle-fill" style="font-size: 2rem; color: #0b77ff;"></i>
                            <p class="mt-1 mb-0 small text-muted">Video coming soon</p>
                        </div>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>Speaking Coherence</h4>
                        <p class="text-muted mb-2">How to connect your ideas clearly and avoid the common trap of answering in unlinked fragments.</p>
                        <div class="p-3 text-center" style="background: #f1f5f9; border-radius: 8px;">
                            <i class="bi bi-play-circle-fill" style="font-size: 2rem; color: #0b77ff;"></i>
                            <p class="mt-1 mb-0 small text-muted">Video coming soon</p>
                        </div>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>Speaking Part 1 Mind Map</h4>
                        <p class="text-muted mb-2">A visual guide to the most common Part 1 topic areas and the vocabulary associated with each.</p>
                        <div class="p-3 text-center" style="background: #f1f5f9; border-radius: 8px;">
                            <i class="bi bi-play-circle-fill" style="font-size: 2rem; color: #0b77ff;"></i>
                            <p class="mt-1 mb-0 small text-muted">Video coming soon</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quiz -->
            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2" style="color: #0b77ff;"></i>Class 3 Quiz</h2>
                <div class="highlight-box" style="border-left-color: #16a34a; background: #f0fdf4;">
                    <h5 style="color: #16a34a;"><i class="bi bi-check2-square me-2"></i>Gap-fill Prediction Strategies Quiz</h5>
                    <p class="mb-2">
                        For each of 10 gap contexts, identify the expected answer type: name / number / place / date / noun / adjective.
                        For example: "The appointment is scheduled for ___" — what type of answer do you expect?
                    </p>
                    <p class="mb-2"><strong>Format:</strong> 10 questions | Prediction categorisation exercise</p>
                    <p class="text-muted mb-0"><i class="bi bi-clock me-1"></i>Quiz questions coming soon</p>
                </div>
            </div>

            <!-- Take-Home -->
            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color: #0b77ff;"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h5><i class="bi bi-pencil-square me-2"></i>Your Task Before Class 4</h5>
                    <p class="mb-0">
                        Record Speaking Part 1 answers on 3 topics: your hometown, your job or studies, and your
                        free time. Aim for 2–3 sentences per answer using the IDEA framework. Listen back and
                        count how many times you paused for more than 2 seconds — note this number, as you will
                        track it improving over the coming weeks.
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="action-buttons">
                <a href="class02.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Previous: Class 2
                </a>
                <a href="class04.php" class="btn btn-primary" style="background-color: #0b77ff; border-color: #0b77ff;">
                    Next: Class 4 <i class="bi bi-arrow-right ms-1"></i>
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
                        <li class="mb-2"><a href="class02.php" class="text-white text-decoration-none"><i class="bi bi-chevron-left me-1"></i>Previous Class</a></li>
                        <li class="mb-2"><a href="class04.php" class="text-white text-decoration-none"><i class="bi bi-chevron-right me-1"></i>Next Class</a></li>
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
