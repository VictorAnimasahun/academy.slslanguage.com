<?php
// /academy/courses/CELPIP_Gen/lessons/class_day.php
// One reusable "test + focused micro-lesson" class-day page, reused for all
// 16 non-mock, non-diagnostic, non-final-week classes in the CELPIP 3-Month
// course's 12-week schedule (course_id=14): C3-C14, C17-C20. Each class day
// pairs ONE complete practice test (one skill) with ONE narrow teaching
// topic on a different skill, so no class day has more than one complete
// test and every class day still teaches something new. Selected via
// ?slot=c3 (etc.) -- see $SLOTS below. Real, already-built practice tests
// are linked directly; the 4th Reading/Writing/Speaking test and all
// Listening tests (no real Listening practice tests exist yet) show an
// honest "not built yet" card instead of a dead or fake link.
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once __DIR__ . '/course_context.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../edu_hub_registration.php?message=Please+login+to+access+this+course");
    exit();
}

$MOCK1 = ACADEMY_URL . 'resources/mock_tests/celpip_full_mock_a.php';
$MOCK2 = ACADEMY_URL . 'resources/mock_tests/celpip_full_mock_b.php';
$MOCK1_REVIEW = 'week8_mock1_review.php';

// Each slot: week, class number, page title, the day's complete test
// (skill + real link or null for "not built yet"), the day's focused
// micro-lesson on a different skill (topic + body HTML), and prev/next nav.
$SLOTS = [

    'c3' => ['week' => 'Week 2', 'class_num' => 3,
        'test' => ['skill' => 'Listening', 'sub' => 'Full 6-part test, real time limits', 'link' => ACADEMY_URL . 'courses/CELPIP_Gen/lessons/celpip_listening_001.php'],
        'lesson' => ['skill' => 'Reading', 'topic' => 'Part 1 — Correspondence', 'icon' => 'bi-envelope-open',
            'body' => '<p>Reading Part 1 gives you a short exchange of letters, emails, or text messages — usually a request followed by a reply — with several blanks to fill in from a word bank or drop-down list. It tests grammar and register, not just vocabulary.</p>
                <ul class="custom-list">
                    <li><strong>Read the whole exchange first.</strong> Understand who is writing to whom, and why, before touching a single blank — many blanks only make sense once you know the reply\'s purpose.</li>
                    <li><strong>Match the register.</strong> A blank in a formal business letter needs a different word choice than the same grammatical slot in a casual note to a friend.</li>
                    <li><strong>Watch for grammar signals around the blank</strong> — a preposition, verb tense, or connector is often determined by the word immediately before or after it, not by meaning alone.</li>
                </ul>'],
        'prev' => ACADEMY_URL . 'courses/CELPIP_intro/celpip_mini_mock.php', 'prev_label' => 'Previous: Mini Diagnostic',
        'next' => 'class_day.php?slot=c4', 'next_label' => 'Next Class'],

    'c4' => ['week' => 'Week 2', 'class_num' => 4,
        'test' => ['skill' => 'Reading', 'sub' => 'All 4 parts', 'link' => ACADEMY_URL . 'resources/practice_tests/celpip_reading_001.php'],
        'lesson' => ['skill' => 'Writing', 'topic' => 'Task 1 & 2 Structure', 'icon' => 'bi-pencil-square',
            'body' => '<p>Both CELPIP Writing tasks reward a clear, predictable structure more than they reward fancy vocabulary.</p>
                <div class="info-grid">
                    <div class="info-card"><h4>Task 1 — Email (27 min, 150-200 words)</h4><p class="mb-0">Greeting → state your purpose in sentence one → 2-3 body points → closing line matching the reader\'s tone → sign-off. Formal or informal register depends entirely on who you\'re writing to.</p></div>
                    <div class="info-card"><h4>Task 2 — Survey Response (26 min, 150-200 words)</h4><p class="mb-0">Pick ONE of the two options and commit — don\'t hedge. State your opinion clearly, give 2 supporting reasons with a specific example each, then a one-sentence conclusion.</p></div>
                </div>'],
        'prev' => 'class_day.php?slot=c3', 'prev_label' => 'Previous Class',
        'next' => 'class_day.php?slot=c5', 'next_label' => 'Next Class'],

    'c5' => ['week' => 'Week 3', 'class_num' => 5,
        'test' => ['skill' => 'Speaking', 'sub' => 'All 8 tasks, recorded + transcribed', 'link' => ACADEMY_URL . 'resources/practice_tests/celpip_speaking_001.php'],
        'lesson' => ['skill' => 'Listening', 'topic' => 'Inference & Signal Words', 'icon' => 'bi-ear',
            'body' => '<p>Not every Listening question asks what was literally said — many ask what a speaker <em>meant</em>. These questions hide behind signal words that shift or soften meaning: <em>"however," "actually," "to be honest," "I suppose," "I mean."</em></p>
                <ul class="custom-list">
                    <li>A sentence that starts positive and then adds "but" or "however" usually means the speaker\'s <strong>real</strong> opinion is in the second half.</li>
                    <li>Hesitation words ("I suppose," "I guess") signal reluctant agreement, not full enthusiasm — CELPIP Parts 5 and 6 test exactly this distinction.</li>
                    <li>Practice: don\'t just transcribe what you hear — after each signal word, ask "did their opinion just change?"</li>
                </ul>'],
        'prev' => 'class_day.php?slot=c4', 'prev_label' => 'Previous Class',
        'next' => 'class_day.php?slot=c6', 'next_label' => 'Next Class'],

    'c6' => ['week' => 'Week 3', 'class_num' => 6,
        'test' => ['skill' => 'Writing', 'sub' => 'Task 1 (Email) + Task 2 (Survey Response)', 'link' => null, 'dual' => [
            ['name' => 'Task 1 — Email', 'link' => ACADEMY_URL . 'resources/practice_tests/celpip_writing_t1_001.php'],
            ['name' => 'Task 2 — Survey Response', 'link' => ACADEMY_URL . 'resources/practice_tests/celpip_writing_t2_001.php'],
        ]],
        'lesson' => ['skill' => 'Speaking', 'topic' => 'Clarity & Structure', 'icon' => 'bi-mic',
            'body' => '<p>Across all 8 Speaking tasks, graders reward a response they can follow easily more than one packed with advanced vocabulary.</p>
                <ul class="custom-list">
                    <li><strong>Open with a direct sentence</strong> that answers the task immediately — don\'t warm up for 10 seconds before getting to the point.</li>
                    <li><strong>Use simple transition words</strong> ("first," "also," "because of this," "in the end") so the grader always knows which part of your answer they\'re hearing.</li>
                    <li><strong>Pace yourself to the full time.</strong> A response that ends 15 seconds early with silence scores lower than one that uses the full window, even if the content is similar.</li>
                </ul>'],
        'prev' => 'class_day.php?slot=c5', 'prev_label' => 'Previous Class',
        'next' => 'class_day.php?slot=c7', 'next_label' => 'Next Class'],

    'c7' => ['week' => 'Week 4', 'class_num' => 7,
        'test' => ['skill' => 'Listening', 'sub' => 'Full 6-part test, real time limits', 'link' => null],
        'lesson' => ['skill' => 'Reading', 'topic' => 'Part 2 — Schedules & Diagrams', 'icon' => 'bi-table',
            'body' => '<p>Reading Part 2 gives you a diagram, schedule, or table (like a class timetable or event schedule) and asks questions that require cross-referencing more than one piece of it at once.</p>
                <ul class="custom-list">
                    <li><strong>Scan the diagram\'s structure first</strong> — what are the rows, what are the columns, what units are used — before reading any question.</li>
                    <li>The most common trap is <strong>confusing two similar-looking rows or time slots.</strong> Always re-check which exact row/column a question is pointing to before answering.</li>
                    <li>Several questions require combining two data points (e.g. a time AND a location) — don\'t stop reading the diagram after finding the first matching value.</li>
                </ul>'],
        'prev' => 'class_day.php?slot=c6', 'prev_label' => 'Previous Class',
        'next' => 'class_day.php?slot=c8', 'next_label' => 'Next Class'],

    'c8' => ['week' => 'Week 4', 'class_num' => 8,
        'test' => ['skill' => 'Reading', 'sub' => 'All 4 parts', 'link' => ACADEMY_URL . 'resources/practice_tests/celpip_reading_002.php'],
        'lesson' => ['skill' => 'Writing', 'topic' => 'Argument Vocabulary & Sentence Variety', 'icon' => 'bi-chat-quote',
            'body' => '<p>Task 2 argument writing scores higher with controlled variety, not longer sentences for their own sake.</p>
                <div class="info-grid">
                    <div class="info-card"><h4>Opinion vocabulary</h4><p class="mb-0">"I believe," "in my view," "arguably," "it seems clear that" — rotate these instead of repeating "I think" in every sentence.</p></div>
                    <div class="info-card"><h4>Connecting reasons</h4><p class="mb-0">"Furthermore," "in addition," "on the other hand," "this is because" — these words do double duty: they raise your Coherence score while organizing your argument.</p></div>
                </div>
                <p class="mb-0">Sentence variety tip: combine two short, choppy sentences into one using a subordinate clause ("Although X, Y" or "Because X, Y") — this alone measurably raises the Vocabulary and Sentence Structure scoring categories.</p>'],
        'prev' => 'class_day.php?slot=c7', 'prev_label' => 'Previous Class',
        'next' => 'class_day.php?slot=c9', 'next_label' => 'Next Class'],

    'c9' => ['week' => 'Week 5', 'class_num' => 9,
        'test' => ['skill' => 'Speaking', 'sub' => 'All 8 tasks, recorded + transcribed', 'link' => ACADEMY_URL . 'resources/practice_tests/celpip_speaking_002.php'],
        'lesson' => ['skill' => 'Listening', 'topic' => 'Supporting Details', 'icon' => 'bi-list-check',
            'body' => '<p>Not every question asks for the main idea — many ask for one specific supporting detail buried in the audio (a number, a name, a reason, a time).</p>
                <ul class="custom-list">
                    <li><strong>Read the question stem before the audio plays</strong> and predict what kind of detail it\'s asking for — a number? a reason? a comparison? This tells your ear exactly what to listen for.</li>
                    <li>Use quick shorthand while listening: abbreviate names to initials, write numbers as digits, and don\'t try to write full sentences — you\'ll miss the next detail while writing the last one.</li>
                    <li>If you miss one detail, let it go immediately — dwelling on it costs you the next two details in the same clip.</li>
                </ul>'],
        'prev' => 'class_day.php?slot=c8', 'prev_label' => 'Previous Class',
        'next' => 'class_day.php?slot=c10', 'next_label' => 'Next Class'],

    'c10' => ['week' => 'Week 5', 'class_num' => 10,
        'test' => ['skill' => 'Writing', 'sub' => 'Task 1 (Email) + Task 2 (Survey Response)', 'link' => null, 'dual' => [
            ['name' => 'Task 1 — Email', 'link' => ACADEMY_URL . 'resources/practice_tests/celpip_writing_t1_002.php'],
            ['name' => 'Task 2 — Survey Response', 'link' => ACADEMY_URL . 'resources/practice_tests/celpip_writing_t2_002.php'],
        ]],
        'lesson' => ['skill' => 'Speaking', 'topic' => 'Storytelling & Predictions (Tasks 3 & 4)', 'icon' => 'bi-image',
            'body' => '<p>Task 3 (Describing a Scene) and Task 4 (Making Predictions) both reward a simple narrative shape rather than a list of disconnected observations.</p>
                <ul class="custom-list">
                    <li><strong>Set the scene first:</strong> where are we, who is in the picture, what is the overall situation — one sentence is enough.</li>
                    <li><strong>Describe 2-3 key visual details</strong> that support your scene-setting, not every object you can see.</li>
                    <li><strong>For predictions, always give a reason:</strong> "I think X will happen, because Y" scores higher than a prediction with no logical support.</li>
                </ul>'],
        'prev' => 'class_day.php?slot=c9', 'prev_label' => 'Previous Class',
        'next' => 'class_day.php?slot=c11', 'next_label' => 'Next Class'],

    'c11' => ['week' => 'Week 6', 'class_num' => 11,
        'test' => ['skill' => 'Listening', 'sub' => 'Full 6-part test, real time limits', 'link' => null],
        'lesson' => ['skill' => 'Reading', 'topic' => 'Part 3 — Key Ideas', 'icon' => 'bi-file-text',
            'body' => '<p>Reading Part 3 gives a longer informational passage on a general-interest topic, testing main ideas, supporting details, and vocabulary in context.</p>
                <ul class="custom-list">
                    <li><strong>Find the topic sentence of each paragraph first</strong> — it usually signals what that paragraph\'s questions will be about.</li>
                    <li><strong>Tell main-idea questions apart from detail questions</strong> by the wording: "What is this passage mainly about?" needs the big picture; "According to paragraph 2..." needs one specific fact.</li>
                    <li><strong>Vocabulary-in-context questions</strong> can almost always be answered from the surrounding sentence alone — don\'t rely on an outside definition of the word if it conflicts with the passage\'s usage.</li>
                </ul>'],
        'prev' => 'class_day.php?slot=c10', 'prev_label' => 'Previous Class',
        'next' => 'class_day.php?slot=c12', 'next_label' => 'Next Class'],

    'c12' => ['week' => 'Week 6', 'class_num' => 12,
        'test' => ['skill' => 'Reading', 'sub' => 'All 4 parts', 'link' => ACADEMY_URL . 'resources/practice_tests/celpip_reading_003.php'],
        'lesson' => ['skill' => 'Writing', 'topic' => 'Transitions & Collocations', 'icon' => 'bi-link-45deg',
            'body' => '<p>Two small vocabulary habits raise both the Vocabulary and Coherence scoring categories at once.</p>
                <div class="info-grid">
                    <div class="info-card"><h4>Collocations</h4><p class="mb-0">"Make a decision," "take into consideration," "come to a conclusion," "raise a concern" — natural word pairings score higher than technically-correct-but-awkward phrasing.</p></div>
                    <div class="info-card"><h4>Transition categories</h4><p class="mb-0">Addition (moreover, in addition), contrast (however, on the other hand), cause-effect (as a result, therefore), sequence (first, subsequently) — use a different category in each paragraph rather than repeating one.</p></div>
                </div>'],
        'prev' => 'class_day.php?slot=c11', 'prev_label' => 'Previous Class',
        'next' => 'class_day.php?slot=c13', 'next_label' => 'Next Class'],

    'c13' => ['week' => 'Week 7', 'class_num' => 13,
        'test' => ['skill' => 'Speaking', 'sub' => 'All 8 tasks, recorded + transcribed', 'link' => ACADEMY_URL . 'resources/practice_tests/celpip_speaking_003.php'],
        'lesson' => ['skill' => 'Listening', 'topic' => 'Speaker Attitude', 'icon' => 'bi-people',
            'body' => '<p>CELPIP Listening Part 5 (Discussion) puts two speakers in conversation and tests whether you can track who holds which opinion — a much harder skill than following a single speaker.</p>
                <ul class="custom-list">
                    <li><strong>Listen for tone cues</strong> — sarcasm, hesitation, enthusiasm, disagreement — not just the literal words each speaker uses.</li>
                    <li><strong>Actively tag each opinion to a speaker</strong> as you listen (e.g. "Speaker A = for it, Speaker B = against it") rather than trying to reconstruct who said what afterward.</li>
                    <li>When two speakers disagree, the questions almost always test the <em>reason</em> behind each position, not just which side they\'re on.</li>
                </ul>'],
        'prev' => 'class_day.php?slot=c12', 'prev_label' => 'Previous Class',
        'next' => 'class_day.php?slot=c14', 'next_label' => 'Next Class'],

    'c14' => ['week' => 'Week 7', 'class_num' => 14,
        'test' => ['skill' => 'Writing', 'sub' => 'Task 1 (Email) + Task 2 (Survey Response)', 'link' => null, 'dual' => [
            ['name' => 'Task 1 — Email', 'link' => ACADEMY_URL . 'resources/practice_tests/celpip_writing_t1_003.php'],
            ['name' => 'Task 2 — Survey Response', 'link' => ACADEMY_URL . 'resources/practice_tests/celpip_writing_t2_003.php'],
        ]],
        'lesson' => ['skill' => 'Speaking', 'topic' => 'Persuasion & Comparison (Tasks 6 & 7)', 'icon' => 'bi-chat-left-dots',
            'body' => '<p>Task 6 (Dealing with a Difficult Situation) and Task 7 (Expressing Opinions) both reward a clear persuasive structure.</p>
                <ul class="custom-list">
                    <li><strong>State your position in the first sentence</strong> — don\'t make the grader wait to find out what you actually think.</li>
                    <li><strong>Give one strong reason</strong>, well-explained, rather than three weak ones rushed together.</li>
                    <li><strong>Briefly acknowledge the other side</strong> before restating your position — this single move noticeably raises perceived argument quality even in a 60-90 second response.</li>
                </ul>'],
        'prev' => 'class_day.php?slot=c13', 'prev_label' => 'Previous Class',
        'next' => $MOCK1, 'next_label' => 'Next: Mock Test 1'],

    'c17' => ['week' => 'Week 9', 'class_num' => 17,
        'test' => ['skill' => 'Listening', 'sub' => 'Full 6-part test, real time limits', 'link' => null],
        'lesson' => ['skill' => 'Reading', 'topic' => 'Part 4 — Viewpoints', 'icon' => 'bi-chat-square-quote',
            'body' => '<p>Reading Part 4 presents a passage with multiple viewpoints on a debated topic — the hardest reading section for most students, because the questions test whose opinion is whose, not just what was said.</p>
                <ul class="custom-list">
                    <li><strong>Track each named person\'s position</strong> as you read — a quick margin note ("Smith = pro, Lee = con") pays off immediately in the questions.</li>
                    <li><strong>Watch for a person changing or qualifying their position</strong> partway through the passage — this is a frequent trap.</li>
                    <li><strong>Don\'t assume the author\'s own opinion</strong> is one of the ones quoted — CELPIP passages are often deliberately neutral.</li>
                </ul>'],
        'prev' => $MOCK1_REVIEW, 'prev_label' => 'Previous: Mock 1 Review',
        'next' => 'class_day.php?slot=c18', 'next_label' => 'Next Class'],

    'c18' => ['week' => 'Week 9', 'class_num' => 18,
        'test' => ['skill' => 'Reading', 'sub' => 'All 4 parts — Practice Test 4', 'link' => null],
        'lesson' => ['skill' => 'Writing', 'topic' => 'Self-Editing Strategy', 'icon' => 'bi-check2-square',
            'body' => '<p>With 53 minutes for two tasks, build in 2-3 minutes of self-editing per task rather than writing until the timer runs out.</p>
                <div class="info-grid">
                    <div class="info-card"><h4>Pass 1 — Task Coverage</h4><p class="mb-0">Did you actually answer everything the prompt asked? A perfectly written response to half the prompt still loses marks.</p></div>
                    <div class="info-card"><h4>Pass 2 — Grammar Scan</h4><p class="mb-0">Quick sweep for subject-verb agreement, verb tense consistency, and article usage (a/an/the) — the most common small errors under time pressure.</p></div>
                    <div class="info-card"><h4>Pass 3 — Register Check</h4><p class="mb-0">Does the tone match who you\'re writing to? A response that drifts from formal to casual (or vice versa) partway through loses coherence points.</p></div>
                </div>'],
        'prev' => 'class_day.php?slot=c17', 'prev_label' => 'Previous Class',
        'next' => 'class_day.php?slot=c19', 'next_label' => 'Next Class'],

    'c19' => ['week' => 'Week 10', 'class_num' => 19,
        'test' => ['skill' => 'Speaking', 'sub' => 'All 8 tasks — Practice Test 4', 'link' => null],
        'lesson' => ['skill' => 'Listening', 'topic' => 'Fast-Audio Drills', 'icon' => 'bi-lightning-charge',
            'body' => '<p>CELPIP Listening plays every clip only once — there is no replay. This class builds the specific skill of processing native-speed audio in a single pass.</p>
                <ul class="custom-list">
                    <li><strong>Chunk what you hear</strong> into short meaning-groups rather than trying to catch every individual word.</li>
                    <li><strong>Predict the next few words</strong> from sentence structure and context — this reduces how much raw processing your brain has to do in real time.</li>
                    <li><strong>Let go of anything you miss immediately.</strong> Fixating on one lost word or phrase causes you to miss the next two sentences while your brain is still stuck on it.</li>
                </ul>'],
        'prev' => 'class_day.php?slot=c18', 'prev_label' => 'Previous Class',
        'next' => 'class_day.php?slot=c20', 'next_label' => 'Next Class'],

    'c20' => ['week' => 'Week 10', 'class_num' => 20,
        'test' => ['skill' => 'Writing', 'sub' => 'Both tasks — Practice Test 4', 'link' => null],
        'lesson' => ['skill' => 'Speaking', 'topic' => 'Full-Timed-Task Strategy', 'icon' => 'bi-stopwatch',
            'body' => '<p>A general timing strategy that applies across all 8 Speaking tasks, useful heading into Mock 2 and the real exam.</p>
                <ul class="custom-list">
                    <li><strong>Use prep time for keywords, not sentences.</strong> Jot 2-3 keywords per point — writing full sentences wastes prep time you need for planning structure.</li>
                    <li><strong>Pace your speech to use most of the response window</strong> without long pauses — ending 15+ seconds early scores lower than a response that uses the full time, even with similar content.</li>
                    <li><strong>Keep 2-3 flexible opening and closing sentence templates in memory</strong> that work across multiple task types, so you are never caught with nothing to say in the first two seconds.</li>
                </ul>'],
        'prev' => 'class_day.php?slot=c19', 'prev_label' => 'Previous Class',
        'next' => $MOCK2, 'next_label' => 'Next: Mock Test 2'],

];

$slot = $_GET['slot'] ?? '';
if (!isset($SLOTS[$slot])) {
    die("Unknown class slot.");
}
$cfg = $SLOTS[$slot];
$page_title = 'Complete ' . $cfg['test']['skill'] . ' Test + ' . $cfg['lesson']['topic'];

if (!can_access('intermediate')) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <title>CELPIP Masterclass — <?= htmlspecialchars($page_title) ?></title>
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
                <?php render_upgrade_prompt('intermediate', $page_title); ?>
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
    <title>CELPIP Masterclass — <?= htmlspecialchars($page_title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../../../assets/css/courses.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .test-block { background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:1.5rem; margin-bottom:1.5rem; }
        .test-not-built { background:#fef3c7; border:1px dashed #fcd34d; border-radius:10px; padding:1rem 1.25rem; color:#92400e; font-size:.9rem; }
        .test-dual-links { display:flex; flex-direction:column; gap:.6rem; }
    </style>
</head>
<body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <main class="main-wrapper">
        <div class="course-card">

            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li>
                    <li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($page_title) ?></li>
                </ol>
            </nav>

            <h1 class="mb-3"><i class="bi bi-calendar2-week me-2" style="color:#0b77ff;"></i><?= htmlspecialchars($page_title) ?></h1>
            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div><h4 class="mb-2" style="color:var(--accent);">Class Overview</h4>
                        <p class="mb-0"><strong>Week:</strong> <?= htmlspecialchars($cfg['week']) ?> &nbsp;|&nbsp; <strong>Class:</strong> <?= (int) $cfg['class_num'] ?> of 24</p></div>
                    <div class="d-flex flex-wrap gap-2"><span class="badge-custom"><?= htmlspecialchars($cfg['test']['skill']) ?> Test</span><span class="badge-custom"><?= htmlspecialchars($cfg['lesson']['skill']) ?> Lesson</span></div>
                </div>
            </div>

            <h2 class="mb-2"><i class="bi bi-flag-fill me-2" style="color:#0b77ff;"></i>Today's Complete Test: <?= htmlspecialchars($cfg['test']['skill']) ?></h2>
            <div class="test-block">
                <p class="text-muted mb-3"><?= htmlspecialchars($cfg['test']['sub']) ?></p>
                <?php if (!empty($cfg['test']['dual'])): ?>
                    <div class="test-dual-links">
                        <?php foreach ($cfg['test']['dual'] as $d): ?>
                            <?php if ($d['link']): ?>
                                <a href="<?= htmlspecialchars($d['link']) ?>" class="btn btn-primary" style="background-color:#0b77ff;border-color:#0b77ff;"><?= htmlspecialchars($d['name']) ?> <i class="bi bi-arrow-right ms-1"></i></a>
                            <?php else: ?>
                                <div class="test-not-built"><i class="bi bi-hourglass-split me-2"></i><?= htmlspecialchars($d['name']) ?> — not built yet</div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php elseif ($cfg['test']['link']): ?>
                    <a href="<?= htmlspecialchars($cfg['test']['link']) ?>" class="btn btn-primary btn-lg" style="background-color:#0b77ff;border-color:#0b77ff;">Start <?= htmlspecialchars($cfg['test']['skill']) ?> Test <i class="bi bi-arrow-right ms-1"></i></a>
                <?php else: ?>
                    <div class="test-not-built"><i class="bi bi-hourglass-split me-2"></i>This complete <?= htmlspecialchars($cfg['test']['skill']) ?> test hasn't been built yet. This class is wired into the schedule as a placeholder so the 12-week structure is complete — check back once it's added, or ask your instructor for interim material.</div>
                <?php endif; ?>
            </div>

            <div class="content-section">
                <h2><i class="bi <?= htmlspecialchars($cfg['lesson']['icon']) ?> me-2" style="color:#0b77ff;"></i><?= htmlspecialchars($cfg['lesson']['skill']) ?>: <?= htmlspecialchars($cfg['lesson']['topic']) ?></h2>
                <?= $cfg['lesson']['body'] ?>
            </div>

            <div class="action-buttons">
                <a href="<?= htmlspecialchars($cfg['prev']) ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> <?= htmlspecialchars($cfg['prev_label']) ?></a>
                <a href="<?= htmlspecialchars($cfg['next']) ?>" class="btn btn-primary" style="background-color:#0b77ff;border-color:#0b77ff;"><?= htmlspecialchars($cfg['next_label']) ?> <i class="bi bi-arrow-right ms-1"></i></a>
                <a href="<?= htmlspecialchars($back['url']) ?>" class="btn btn-outline-primary"><i class="bi bi-grid me-1"></i> Course Overview</a>
            </div>

        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
