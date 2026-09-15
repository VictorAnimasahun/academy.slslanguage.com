<?php
/**
 * CELPIP Listening — Class content (Course 14, Week 2 Class 3 / slot c3).
 * Source: Downloads/CELPIP Practice Test C - Listening Transcripts (2).docx
 * Audio: Downloads/Listening Test C Audios/ (real, wired in 2026-09-15).
 *
 * Lives inside the course (courses/CELPIP_Gen/lessons/), not under
 * resources/practice_tests/ — this is course content, not a standalone
 * catalog practice test. No course-enrollment guard: for now, any
 * registered student can take any course's content for free to test it
 * (see project memory) — only a login check gates this page.
 *
 * Ports the real, locked, sequential-then-all-screen interaction engine
 * already proven in resources/mock_tests/celpip_full_mock_listening.php
 * (Parts 1-3: one question at a time, own audio, no going back; Parts 4-6:
 * all questions shown after one shared recording). The DB (migration 084,
 * test_code CELPIP_PT_L_001) only backs scoring via
 * loadTestAnswers()/save_attempt.php — it is not used for rendering.
 */
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once __DIR__ . '/course_context.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../edu_hub_registration.php?message=Please+login+to+access+this+course");
    exit();
}
require_once INCLUDES_PATH . '/admin_check.php';
$isAdmin = is_platform_admin();

$testCode  = 'CELPIP_PT_L_001';
$audioBase = ACADEMY_URL . 'assets/audio/' . $testCode . '/';
$timeLimit = 47 * 60;

// Parts 1-3 use the real one-question-at-a-time flow; Parts 4-6 show
// everything on one screen after a single shared recording — matches the
// official CELPIP interface exactly (see celpip_full_mock_listening.php).
$PARTS = [
    1 => [
        'title' => 'Part 1: Listening to Problem Solving',
        'blurb' => 'You will hear a conversation between a man and a woman. The man is a librarian and the woman is a patron trying to find a book.',
        'sequential' => true,
        'groups' => [
            ['label' => 'Section 1', 'audio' => 'part1/track1.mp3', 'qnums' => [1, 2, 3]],
            ['label' => 'Section 2', 'audio' => 'part1/track2.mp3', 'qnums' => [4, 5]],
            ['label' => 'Section 3', 'audio' => 'part1/track3.mp3', 'qnums' => [6, 7, 8]],
        ],
    ],
    2 => [
        'title' => 'Part 2: Listening to a Daily Life Conversation',
        'blurb' => 'You will hear a conversation between two coworkers, a man and a woman. They are preparing for a conference presentation next week. It is about 2.5 to 3 minutes long.',
        'sequential' => true,
        'groups' => [
            ['label' => 'Recording', 'audio' => 'part2/track1.mp3', 'qnums' => [9, 10, 11, 12, 13]],
        ],
    ],
    3 => [
        'title' => 'Part 3: Listening for Information',
        'blurb' => 'You will hear a conversation between a customer and a worker at a bicycle shop. It is about 2.5 to 3 minutes long.',
        'sequential' => true,
        'groups' => [
            ['label' => 'Recording', 'audio' => 'part3/track1.mp3', 'qnums' => [14, 15, 16, 17, 18, 19]],
        ],
    ],
    4 => [
        'title' => 'Part 4: Listening to a News Item',
        'blurb' => 'You will hear a news item about a community garden project. It is about 1 to 1.5 minutes long.',
        'sequential' => false,
        'audio' => 'part4/track1.mp3',
        'qnums' => [20, 21, 22, 23, 24],
    ],
    5 => [
        'title' => 'Part 5: Listening to a Discussion',
        'blurb' => 'You will hear a discussion among three coworkers who are planning a fundraiser for a local shelter: one woman, Mia, and two men, Dev and Sam. It is about 3 minutes long.',
        'sequential' => false,
        'audio' => 'part5/track1.mp3',
        'qnums' => [25, 26, 27, 28, 29, 30, 31, 32],
    ],
    6 => [
        'title' => 'Part 6: Listening for Viewpoints',
        'blurb' => 'You will hear a report about a proposed bylaw to ban single-use plastic bags in the city of Ashford. It is about 3 minutes long.',
        'sequential' => false,
        'audio' => 'part6/track1.mp3',
        'qnums' => [33, 34, 35, 36, 37, 38],
    ],
];

$QUESTIONS = [
    1 => ['text' => 'What is the woman ultimately hoping to do?', 'options' => ['A' => 'Buy a book as a gift', 'B' => 'Continue a mystery series she started years ago', 'C' => 'Meet the author in person', 'D' => 'Return an overdue book']],
    2 => ['text' => "What best describes the man's help in this section?", 'options' => ['A' => 'reluctant', 'B' => 'dismissive', 'C' => 'knowledgeable', 'D' => 'confused']],
    3 => ['text' => 'What will the woman probably do next?', 'options' => ['A' => 'Follow the man to see where the books are', 'B' => 'Leave the library without the books', 'C' => 'Ask for a refund', 'D' => 'Call a different branch']],
    4 => ['text' => 'What problem does the woman have?', 'options' => ['A' => 'She forgot her library card', 'B' => 'She does not have enough money', 'C' => 'The library is about to close', 'D' => "The books aren't where she was told they'd be"]],
    5 => ['text' => 'Which statement is true?', 'options' => ['A' => 'All three books were checked out by another patron', 'B' => 'Two of the books are misplaced somewhere in the building', 'C' => 'The woman decides not to wait for the third book', 'D' => 'The man refuses to place a hold on the missing book']],
    6 => ['text' => 'What happened to the two misplaced books?', 'options' => ['A' => 'They were checked out by another patron', 'B' => 'They were sent to another branch', 'C' => 'They were found behind the returns desk', 'D' => 'They were damaged']],
    7 => ['text' => 'How will the woman know when the third book is ready?', 'options' => ['A' => 'She will get an email', 'B' => 'She will get a phone call', 'C' => 'She has to check back in person', 'D' => 'A librarian will mail it to her']],
    8 => ['text' => 'What can we tell about the man from this conversation?', 'options' => ['A' => 'He is new to the job', 'B' => 'He dislikes his job', 'C' => 'He is unfamiliar with the catalogue system', 'D' => 'He goes out of his way to help patrons']],

    9  => ['text' => 'What are the man and woman preparing for?', 'options' => ['A' => 'A client dinner', 'B' => 'A team building event', 'C' => 'A conference presentation', 'D' => 'A product launch']],
    10 => ['text' => 'Why is the woman behind on her part of the work?', 'options' => ['A' => 'She has been busy with client calls', 'B' => 'She was on vacation', 'C' => 'Her computer broke', 'D' => 'She forgot about it']],
    11 => ['text' => 'Which flight do they decide to book?', 'options' => ['A' => 'Friday morning', 'B' => 'Thursday evening', 'C' => 'Friday evening', 'D' => 'Thursday 7am']],
    12 => ['text' => 'Which statement is most likely true?', 'options' => ['A' => 'The man has presented to this audience before', 'B' => 'Neither of them has presented to this specific audience before', 'C' => 'The woman prefers the Friday morning flight', 'D' => "Mr. Alvarez's request was part of the original plan"]],
    13 => ['text' => 'What new request from Mr. Alvarez surprises them?', 'options' => ['A' => 'They need to add a Q&A session', 'B' => 'The venue changed', 'C' => 'The budget was cut', 'D' => 'The presentation was moved to Monday']],

    14 => ['text' => 'Why does the woman want to buy a bike?', 'options' => ['A' => 'To train for a race', 'B' => 'To start commuting to work instead of driving', 'C' => 'As a gift for her child', 'D' => 'For weekend leisure rides']],
    15 => ['text' => 'What kind of bike does the man recommend, and why?', 'options' => ['A' => "A mountain bike, because it's the toughest", 'B' => "A road bike, because it's the fastest", 'C' => "A folding bike, because it's compact", 'D' => 'A hybrid bike, because it handles both pavement and light gravel']],
    16 => ['text' => 'According to the man, which of these is true of a pure road bike?', 'options' => ['A' => 'Its thin tires might struggle on gravel', 'B' => 'It has better gears than a hybrid', 'C' => "It's the cheapest option in the store", 'D' => 'It comes with lights and a lock included']],
    17 => ['text' => 'According to the man, which of these is true of the mid-range hybrid model?', 'options' => ['A' => "It is the store's most expensive option", 'B' => 'It has hydraulic disc brakes', 'C' => 'It offers good value and should last several years', 'D' => 'It does not come with a warranty']],
    18 => ['text' => "Which of these best describes the man's approach as a salesperson?", 'options' => ['A' => 'knowledgeable and attentive to her specific needs', 'B' => 'pushy and focused on the most expensive option', 'C' => 'indifferent to her budget concerns', 'D' => 'dismissive of beginner cyclists']],
    19 => ['text' => 'What will most likely happen next?', 'options' => ['A' => 'The woman will decide not to buy a bike', 'B' => 'The woman will pay and leave immediately', 'C' => 'The man will grab a bike in her size for a test ride', 'D' => 'They will discuss financing paperwork first']],

    20 => ['text' => 'This news item is about ______', 'options' => ['A' => 'a new city park with walking trails', 'B' => 'the opening of a new community garden', 'C' => 'a change to city zoning laws', 'D' => 'a fundraiser for local schools']],
    21 => ['text' => 'Fatima Noor spent the past year ______', 'options' => ['A' => 'building the raised garden beds herself', 'B' => 'negotiating with the fire department', 'C' => 'raising funds through grants, crowdfunding, and donations', 'D' => 'teaching gardening classes at a local school']],
    22 => ['text' => 'According to Councillor Doyle, community gardens can improve ______', 'options' => ['A' => 'neighborhood relationships and access to fresh produce', 'B' => 'property values and tourism', 'C' => 'public transit ridership', 'D' => 'school enrollment numbers']],
    23 => ['text' => "Councillor Doyle's comments suggest that the city is ______", 'options' => ['A' => 'uncertain whether the garden will succeed', 'B' => 'reluctant to fund any further garden projects', 'C' => 'regretting the cost of the project', 'D' => 'planning to expand this kind of initiative']],
    24 => ['text' => 'The news item ends by mentioning ______', 'options' => ['A' => 'criticism from nearby residents', 'B' => 'free workshops planned for the summer', 'C' => 'a delay in the second phase', 'D' => 'a dispute over funding']],

    25 => ['text' => 'What are the three coworkers deciding in this discussion?', 'options' => ['A' => "How to spend last year's fundraiser profits", 'B' => 'Whether to cancel the fundraiser entirely', 'C' => 'What format the fundraiser should take this year', 'D' => 'Who will replace Mia as event organizer']],
    26 => ['text' => 'How much did the silent auction raise last year, after expenses?', 'options' => ['A' => '$200', 'B' => '$20,000', 'C' => '$700', 'D' => '$2,000']],
    27 => ['text' => 'What does Dev believe about the trivia night idea?', 'options' => ['A' => 'It will be too hard to get people excited about it', 'B' => 'It should be cancelled in favor of a raffle', 'C' => 'It requires more staff than the club has', 'D' => 'It is cheaper and simpler to organize than an auction']],
    28 => ['text' => 'What do all three coworkers agree on by the end of the discussion?', 'options' => ['A' => 'Trivia night, at the pub, on a Friday, is the way to go', 'B' => 'The fundraiser should be postponed until next year', 'C' => 'The silent auction should be brought back next year', 'D' => 'Mia should write all the trivia questions herself']],
    29 => ['text' => "What best describes Sam's attitude toward writing the trivia questions?", 'options' => ['A' => 'reluctant', 'B' => 'indifferent', 'C' => 'enthusiastic', 'D' => 'anxious']],
    30 => ['text' => "What would Mia most likely have done if Dev hadn't pointed out the cost of renting equipment for the auction?", 'options' => ['A' => 'She would have insisted on repeating the silent auction', 'B' => 'She would have cancelled the fundraiser altogether', 'C' => 'She would have immediately proposed trivia night herself', 'D' => 'She would have asked Sam to organize everything alone']],
    31 => ['text' => 'Which saying would Sam most likely agree with, given his approach to organizing the trivia night?', 'options' => ['A' => '"If it ain\'t broke, don\'t fix it."', 'B' => '"Don\'t reinvent the wheel."', 'C' => '"The squeaky wheel gets the grease."', 'D' => '"Too many cooks spoil the broth."']],
    32 => ['text' => 'What does Dev offer to do regarding the trivia questions Sam writes?', 'options' => ['A' => 'Write his own set of questions instead', 'B' => 'Review them for accuracy before the event', 'C' => 'Ask the pub to hire a professional writer', 'D' => 'Cancel the deadline Sam set']],

    33 => ['text' => 'This is a report about a ______', 'options' => ['A' => 'new curbside recycling program for Ashford', 'B' => 'proposed bylaw to ban single-use plastic bags', 'C' => 'shoreline cleanup organized by volunteers', 'D' => 'fee increase on paper products in stores']],
    34 => ['text' => 'Large retailers would need to comply ______', 'options' => ['A' => 'within three months of the bylaw passing', 'B' => 'within one year of the bylaw passing', 'C' => 'within six months of the bylaw passing', 'D' => 'immediately after the vote is held']],
    35 => ['text' => 'Money collected from the paper bag fee would go toward ______', 'options' => ['A' => 'lowering property taxes', 'B' => 'a city environmental fund', 'C' => 'compensating retailers', 'D' => 'building a new recycling center']],
    36 => ['text' => 'Tom Whitfield argues that the bylaw ______', 'options' => ['A' => 'will raise costs and confuse some customers', 'B' => 'does not go far enough to cut plastic waste', 'C' => 'should apply to paper bags as well', 'D' => 'will have no effect on local retailers']],
    37 => ['text' => 'Dr. Priya Anand, who helped draft the bylaw, believes it ______', 'options' => ['A' => 'will fail since plastic bags are a small share of waste', 'B' => 'can help shift public habits toward less plastic overall', 'C' => 'should be delayed until more research is done', 'D' => 'will only work if all plastics are banned at once']],
    38 => ['text' => 'The bylaw will most likely be voted on ______', 'options' => ['A' => 'immediately, without any public input', 'B' => 'within two months, after public consultations', 'C' => 'next year, following a province-wide review', 'D' => 'never, since it was already rejected']],
];

$TOTAL_QS = count($QUESTIONS);

// Correct answers, embedded client-side for real-time scoring on submit —
// matching the convention used by ielts_listening_001.php. Also backed by
// migration 084's question_options.is_correct rows (used by save_attempt.php
// for the per-question attempt_answers detail records).
$ANSWER_KEY = [
    1=>'B', 2=>'C', 3=>'A', 4=>'D', 5=>'B', 6=>'C', 7=>'A', 8=>'D',
    9=>'C', 10=>'A', 11=>'D', 12=>'B', 13=>'A',
    14=>'B', 15=>'D', 16=>'A', 17=>'C', 18=>'A', 19=>'C',
    20=>'B', 21=>'C', 22=>'A', 23=>'D', 24=>'B',
    25=>'C', 26=>'D', 27=>'D', 28=>'A', 29=>'C', 30=>'A', 31=>'B', 32=>'B',
    33=>'B', 34=>'A', 35=>'B', 36=>'A', 37=>'B', 38=>'B',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CELPIP Listening — Practice Test 1 | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <link rel="stylesheet" href="<?= ACADEMY_URL ?>assets/css/exam_theme.css">
    <style>
        /* Same CELPIP-authentic sequential flow as celpip_full_mock_listening.php,
           reusing the exam_theme.css palette for visual consistency. */
        /* Only sequential parts (1-3) need the fixed-height frame — their
           .celpip-seq wrapper centers a single small card in it. All-screen
           parts (4-6) have no such wrapper, so forcing this same min-height
           on them left a large empty gap below the media box while it
           played, before the questions appeared. */
        .part-panel.active[data-sequential="1"] { min-height: calc(100vh - 280px); }
        .celpip-seq { display: flex; flex-direction: column; justify-content: center; min-height: calc(100vh - 280px); }
        .celpip-seq > .celpip-seq-card { margin-bottom: 0; }
        .celpip-seq-card { background: var(--exam-surface); border: 1px solid var(--exam-line); border-radius: var(--exam-radius-lg); overflow-y: auto; margin-bottom: 1.5rem; max-height: calc(100vh - 280px); }
        .celpip-seq-header { display: flex; align-items: center; justify-content: space-between; background: var(--exam-accent-soft); padding: .6rem 1rem; border-bottom: 1px solid var(--exam-line); font-size: .85rem; font-weight: 700; color: var(--exam-ink); }
        .celpip-seq-timer-wrap { display: flex; align-items: center; gap: .75rem; }
        .celpip-seq-timer { font-variant-numeric: tabular-nums; color: var(--exam-warn); font-weight: 700; }
        .celpip-seq-timer.calm { color: var(--exam-ink-muted); }
        .celpip-seq-body { padding: 1.25rem 1.5rem; }
        .celpip-media-stage { padding: 2rem 1.5rem; text-align: center; }
        .celpip-media-stage-main { padding: 3rem 1.5rem; }
        .celpip-media-caption { font-size: .85rem; color: var(--exam-accent); margin-bottom: 1rem; }
        .celpip-media-caption .bi { margin-right: .3rem; }
        .celpip-audio-widget { display: flex; align-items: center; gap: 1rem; background: var(--exam-bg); border: 1px solid var(--exam-line); border-radius: var(--exam-radius-lg); padding: 1rem 1.25rem; max-width: 480px; margin: 0 auto; }
        .celpip-audio-widget i { font-size: 1.4rem; color: var(--exam-ink-muted); flex-shrink: 0; }
        .celpip-audio-widget-lg { max-width: 560px; padding: 1.5rem 1.75rem; }
        .celpip-audio-widget-lg i { font-size: 2rem; }
        .celpip-progress-track { flex: 1; height: 8px; background: var(--exam-surface); border: 1px solid var(--exam-line); border-radius: 999px; overflow: hidden; }
        .celpip-progress-fill { height: 100%; width: 0%; background: var(--exam-accent); transition: width .2s linear; }
        .celpip-playing-label { font-size: .8rem; color: var(--exam-ink-muted); margin-top: .6rem; font-style: italic; }
        .celpip-q-of { font-size: .82rem; color: var(--exam-ink-muted); margin-bottom: .5rem; }
        .celpip-q-instr { font-size: .85rem; color: var(--exam-accent); margin-bottom: 1rem; }
        .celpip-q-instr .bi { margin-right: .3rem; }
        .celpip-next-btn { background: var(--exam-accent); color: #fff; border: none; border-radius: var(--exam-radius); padding: .45rem 1.4rem; font-weight: 700; font-size: .85rem; }
        .celpip-next-btn-inline { padding: .3rem 1.1rem; font-size: .8rem; }
        .celpip-tap-to-play { display: block; margin: 1rem auto 0; background: var(--exam-warn); color: #fff; border: none; border-radius: var(--exam-radius); padding: .55rem 1.5rem; font-weight: 700; font-size: .85rem; }
        /* Admin-only skip control — visually distinct (purple, top-right) from
           the real student controls so it's never mistaken for part of the
           actual test experience. */
        .celpip-admin-skip { position: absolute; top: .75rem; right: .75rem; background: #6366f1; color: #fff; border: none; border-radius: var(--exam-radius); padding: .4rem 1rem; font-weight: 700; font-size: .78rem; margin: 0; }
        .celpip-media-stage, .celpip-media-stage-main, [data-role="q-audio-stage"] { position: relative; }
        .celpip-tap-to-play .bi { margin-right: .35rem; }
        .celpip-next-btn:disabled { opacity: .5; }
        .celpip-locked { opacity: .55; pointer-events: none; }
        .celpip-no-audio-notice { background: #fef3c7; border: 1px dashed #fcd34d; border-radius: var(--exam-radius); padding: .75rem 1.25rem; color: #92400e; font-size: .85rem; margin-top: 1rem; }
        video.celpip-media, audio.celpip-media { display: none; }
        .mc-question { margin-bottom: 1rem; }
        .mc-q-label { font-weight: 600; margin-bottom: .6rem; }
        .mc-option { display: block; padding: .5rem .75rem; border: 1px solid var(--exam-line); border-radius: var(--exam-radius); margin-bottom: .5rem; cursor: pointer; }
        .mc-option:hover { background: var(--exam-bg); }
        .q-badge { display: inline-flex; align-items: center; justify-content: center; min-width: 22px; height: 22px; background: var(--exam-accent); color: #fff; border-radius: 50%; font-size: .75rem; font-weight: 700; margin-right: .4rem; }
    </style>
</head>
<body>

<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="mobile-overlay" id="mobileOverlay"></div>
<?php include INCLUDES_PATH . '/navbar.php'; ?>

<div class="main-wrapper flex-grow-1" style="flex:1;">
    <?php include INCLUDES_PATH . '/topbar.php'; ?>

    <main class="content p-3">

        <div class="sticky-header">
            <?php if ($isAdmin): ?>
            <div style="background:#1e1b4b;color:#c7d2fe;padding:.6rem 1.25rem;border-radius:8px;margin-bottom:.5rem;font-size:.82rem;font-weight:600;">
                <span style="color:#a5b4fc;text-transform:uppercase;letter-spacing:.08em;font-size:.7rem;">Admin Preview — free navigation (students get the real linear flow)</span>
            </div>
            <?php endif; ?>
            <div class="d-flex align-items-center justify-content-between">
                <nav aria-label="breadcrumb" class="mb-0">
                    <ol class="breadcrumb mb-0" style="font-size:.8rem;">
                        <li class="breadcrumb-item"><a href="../../courses_catalogue.php">Courses</a></li>
                        <li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>"><?= htmlspecialchars($back['name']) ?></a></li>
                        <li class="breadcrumb-item active">Complete Listening Test — Part 1 (Correspondence)</li>
                    </ol>
                </nav>
                <div class="inline-timer" id="inlineTimer"><i class="bi bi-clock-fill"></i> 00:00</div>
            </div>

            <!-- Part tab bar. Tabs are click-navigable for admins only —
                 students follow the real, enforced linear flow (see JS below). -->
            <div class="part-tabs-bar">
                <div class="part-tabs-scrollable">
                    <?php foreach ($PARTS as $pNum => $p): $qnums = $p['sequential'] ? array_merge(...array_column($p['groups'], 'qnums')) : $p['qnums']; ?>
                    <button class="part-tab-btn <?= $pNum === 1 ? 'active' : '' ?>"
                            id="ptab-<?= $pNum ?>"
                            onclick="switchPart(<?= $pNum ?>, this)"
                            <?= (!$isAdmin && $pNum !== 1) ? 'disabled style="cursor:default;"' : '' ?>>
                        <span class="done-dot"></span>
                        Part <?= $pNum ?>
                        <span class="tab-qrange">Q<?= min($qnums) ?>–<?= max($qnums) ?></span>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="section-content" style="padding-top:<?= $isAdmin ? '110px' : '60px' ?>;">

            <form id="listeningForm">
            <?php foreach ($PARTS as $partNum => $p): ?>
            <div class="part-panel <?= $partNum === 1 ? 'active' : '' ?>" id="panel-<?= $partNum ?>" data-part="<?= $partNum ?>" data-sequential="<?= $p['sequential'] ? '1' : '0' ?>">

                <?php if ($p['sequential']): ?>
                <div class="celpip-seq" data-groups='<?= htmlspecialchars(json_encode(array_map(fn($g) => ['label' => $g['label'], 'src' => $audioBase . $g['audio'], 'qnums' => $g['qnums']], $p['groups']))) ?>'>

                    <div class="celpip-seq-card celpip-media-stage celpip-media-stage-main" data-role="media-stage">
                        <div class="celpip-media-caption"><i class="bi bi-info-circle-fill"></i> <?= htmlspecialchars($p['blurb']) ?> Listen to <span data-role="media-label">the recording</span>. You will hear it only once.</div>
                        <div class="celpip-audio-widget celpip-audio-widget-lg">
                            <i class="bi bi-volume-up-fill"></i>
                            <div class="celpip-progress-track"><div class="celpip-progress-fill" data-role="progress-fill"></div></div>
                        </div>
                        <div class="celpip-playing-label">Playing…</div>
                    </div>

                    <?php
                    $flatQ = [];
                    foreach ($p['groups'] as $g) { foreach ($g['qnums'] as $qn) { $flatQ[] = $qn; } }
                    foreach ($flatQ as $qi => $qnum):
                        $q = $QUESTIONS[$qnum];
                    ?>
                    <div class="celpip-seq-card" data-role="q-card" data-qnum="<?= $qnum ?>" data-audio="<?= htmlspecialchars($audioBase . "part{$partNum}/q" . ($qi + 1) . ".mp3") ?>" style="display:none;">
                        <div class="celpip-seq-header">
                            <span><?= htmlspecialchars($p['title']) ?></span>
                            <span class="celpip-seq-timer-wrap">
                                <span class="celpip-seq-timer" data-role="q-timer">Time remaining: <strong data-role="q-timer-val">25</strong> seconds</span>
                                <button type="button" class="celpip-next-btn celpip-next-btn-inline" data-role="next-btn">NEXT</button>
                            </span>
                        </div>
                        <div class="celpip-media-stage" data-role="q-audio-stage">
                            <div class="celpip-media-caption"><i class="bi bi-info-circle-fill"></i> Listen to the question. You will hear it only once.</div>
                            <div class="celpip-audio-widget">
                                <i class="bi bi-volume-up-fill"></i>
                                <div class="celpip-progress-track"><div class="celpip-progress-fill" data-role="q-progress-fill"></div></div>
                            </div>
                            <div class="celpip-playing-label" data-role="q-playing-label">Playing…</div>
                        </div>
                        <div class="celpip-seq-body" data-role="q-answer-stage" style="display:none;">
                            <div class="celpip-q-of">Question <?= $qi + 1 ?> of <?= count($flatQ) ?></div>
                            <!-- No question text here on purpose: the audio you just heard already
                                 spoke this question in full, matching the real CELPIP interface for
                                 Parts 1-3 — only the answer options are shown, never the stem itself. -->
                            <div class="mc-question">
                                <div class="mc-q-label"><span class="q-badge"><?= $qnum ?></span></div>
                                <?php foreach ($q['options'] as $letter => $optText): ?>
                                <label class="mc-option">
                                    <input type="radio" name="answers[<?= $qnum ?>]" value="<?= $letter ?>" class="answer-field" data-qnum="<?= $qnum ?>">
                                    <strong><?= $letter ?>.</strong> <?= htmlspecialchars($optText) ?>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php else: ?>
                <div class="celpip-seq-card celpip-media-stage" data-role="allscreen-media" data-src="<?= htmlspecialchars($audioBase . $p['audio']) ?>">
                    <div class="celpip-media-caption"><i class="bi bi-volume-up-fill"></i> <?= htmlspecialchars($p['blurb']) ?> Listen once — the questions will appear once it finishes.</div>
                    <div class="celpip-progress-track"><div class="celpip-progress-fill" data-role="progress-fill"></div></div>
                </div>

                <div data-role="allscreen-questions" style="display:none;">
                    <?php foreach ($p['qnums'] as $qnum): $q = $QUESTIONS[$qnum]; ?>
                    <div class="mc-question">
                        <div class="mc-q-label"><span class="q-badge"><?= $qnum ?></span><?= htmlspecialchars($q['text']) ?></div>
                        <?php foreach ($q['options'] as $letter => $optText): ?>
                        <label class="mc-option">
                            <input type="radio" name="answers[<?= $qnum ?>]" value="<?= $letter ?>" class="answer-field" data-qnum="<?= $qnum ?>">
                            <strong><?= $letter ?>.</strong> <?= htmlspecialchars($optText) ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                    <?php endforeach; ?>
                    <?php if ($partNum !== 6): ?>
                    <button type="button" class="celpip-next-btn" data-role="part-continue-btn" data-next-part="<?= $partNum + 1 ?>">Continue to Part <?= $partNum + 1 ?> →</button>
                    <div style="clear:both;"></div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

            </div>
            <?php endforeach; ?>
            </form>

        </div>
        <div style="height:80px;"></div>
    </main>
</div>

<div class="submit-bar">
    <div style="display:flex; align-items:center; gap:.5rem;">
        <i class="bi bi-check2-circle text-success fs-5"></i>
        <span id="answeredCount" style="font-size:.9rem; font-weight:600; color:#374151;">0 of <?= $TOTAL_QS ?> answered</span>
    </div>
    <button type="button" class="btn btn-primary fw-bold px-4" id="submitBtn" onclick="submitListening()">
        <i class="bi bi-send me-2"></i>Submit Test
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>

<script>
const DURATION    = <?= $timeLimit ?>;
const TEST_CODE   = <?= json_encode($testCode) ?>;
const SAVE_URL    = <?= json_encode(ACADEMY_URL . 'resources/practice_tests/save_attempt.php') ?>;
const BACK_URL    = <?= json_encode($back['url']) ?>;
const totalQs     = <?= $TOTAL_QS ?>;
const CORRECT     = <?= json_encode($ANSWER_KEY) ?>;
const IS_ADMIN    = <?= $isAdmin ? 'true' : 'false' ?>;
const TOTAL_PARTS = <?= count($PARTS) ?>;
const Q_SECONDS   = 25;
let elapsed       = 0;
let timerInterval;
let submitting    = false;

// Every playable clip is a plain `new Audio(...)` object, never inserted
// into the DOM (see playGroupMedia / showSequentialQuestion /
// initAllScreenPart below) — so a DOM query like
// "querySelectorAll('audio').pause()" can never find or stop them. Track
// the single currently-playing clip here instead, and always stop it
// before starting a new one (including on admin free-nav part switches),
// so two recordings are never audible at once.
let currentAudioEl = null;
function stopCurrentAudio() {
    if (currentAudioEl) currentAudioEl.pause();
    currentAudioEl = null;
    // A "Tap to play"/"Continue" button left over from a part the admin
    // navigated away from before tapping it would otherwise sit in the
    // hidden panel forever, fully clickable — tapping it later plays that
    // old part's audio directly (its onclick closure calls mediaEl.play()
    // itself), bypassing this tracker entirely and causing two recordings
    // to play at once. Removing every such leftover on every part switch
    // prevents that regardless of which part it belonged to.
    document.querySelectorAll('.celpip-tap-to-play, .celpip-no-audio-notice').forEach(el => el.remove());
}

const timerEl = document.getElementById('inlineTimer');

function fmt(sec) { return String(Math.floor(sec/60)).padStart(2,'0') + ':' + String(sec%60).padStart(2,'0'); }
function startTimer() {
    if (IS_ADMIN) {
        // Admins previewing content shouldn't get auto-submitted mid-review.
        timerEl.querySelector('i').nextSibling.textContent = ' Untimed (admin)';
        return;
    }
    timerEl.querySelector('i').nextSibling.textContent = ' ' + fmt(DURATION);
    timerInterval = setInterval(() => {
        elapsed++;
        const rem = DURATION - elapsed;
        timerEl.querySelector('i').nextSibling.textContent = ' ' + fmt(Math.max(0, rem));
        if (rem <= 300) timerEl.classList.add('warning');
        if (rem <= 0) { clearInterval(timerInterval); submitListening(true); }
    }, 1000);
}

// ── Part switching (admin free-nav only; students move via the sequence
//    controller / part-continue buttons below) ──────────────────────────
function switchPart(pNum, btn, force = false) {
    if (!force && !IS_ADMIN && btn && btn.disabled) return;
    stopCurrentAudio();
    document.querySelectorAll('.part-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.part-tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('panel-' + pNum).classList.add('active');
    if (btn) btn.classList.add('active');
    maybeStartPart(pNum);
}

function goToNextPart(fromPartNum) {
    const tab = document.getElementById('ptab-' + fromPartNum);
    if (tab) tab.classList.add('all-answered');
    const nextPanel = document.getElementById('panel-' + (fromPartNum + 1));
    if (!nextPanel) return;
    if (!IS_ADMIN) {
        const nextTab = document.getElementById('ptab-' + (fromPartNum + 1));
        if (nextTab) nextTab.disabled = true;
    }
    switchPart(fromPartNum + 1, document.getElementById('ptab-' + (fromPartNum + 1)), true);
}

// Attempts to autoplay `mediaEl`. If the browser blocks it (no prior user
// gesture), or the file simply doesn't exist yet (audio not recorded), shows
// a button that lets the student continue instead of getting stuck forever
// waiting for an 'ended' event that will never fire.
// Two genuinely different failure modes, handled differently:
//   1. The file itself is missing/corrupt (a real 'error' event) — nothing
//      to play, so skip forward and say so honestly.
//   2. The browser blocked autoplay because there's been no user gesture on
//      this page yet (mediaEl.play() rejects, no 'error' fires) — the file
//      is perfectly fine, it just needs one tap to satisfy the browser's
//      policy. That tap must actually PLAY the real audio, not skip past it.
function playWithFallback(mediaEl, containerEl, onSkip) {
    let settled = false;

    function showMissing() {
        if (settled) return;
        settled = true;
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'celpip-tap-to-play';
        btn.innerHTML = '<i class="bi bi-play-fill"></i> Continue';
        const notice = document.createElement('div');
        notice.className = 'celpip-no-audio-notice';
        notice.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Audio not available yet for this recording — click Continue to proceed.';
        btn.onclick = () => { btn.remove(); notice.remove(); if (onSkip) onSkip(); };
        containerEl.appendChild(btn);
        containerEl.appendChild(notice);
    }

    function showTapToPlay() {
        if (settled) return;
        settled = true;
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'celpip-tap-to-play';
        btn.innerHTML = '<i class="bi bi-play-fill"></i> Tap to play';
        btn.onclick = () => {
            btn.remove();
            settled = false;
            stopCurrentAudio();
            currentAudioEl = mediaEl;
            mediaEl.play().catch(() => showMissing());
        };
        containerEl.appendChild(btn);
    }

    mediaEl.addEventListener('error', showMissing, { once: true });
    mediaEl.play().catch(() => showTapToPlay());
}

const seqState = {};
function initSequentialPart(panel) {
    const partNum = parseInt(panel.dataset.part, 10);
    if (seqState[partNum]) return;
    const seqEl  = panel.querySelector('.celpip-seq');
    const groups = JSON.parse(seqEl.dataset.groups);
    const flat   = [];
    groups.forEach((g, gi) => g.qnums.forEach((qn, qiInGroup) => flat.push({ qnum: qn, groupIdx: gi, isFirstInGroup: qiInGroup === 0 })));
    seqState[partNum] = { groups, flat, idx: -1, qTimerInterval: null, panel, seqEl };
}

function advanceSequential(partNum) {
    const st = seqState[partNum];
    if (!st) return;
    if (st.idx >= st.flat.length) return; // already finished — ignore a stray late call (e.g. a leftover audio event)
    if (st.qTimerInterval) { clearInterval(st.qTimerInterval); st.qTimerInterval = null; }
    if (st.idx >= 0) {
        const prevCard = st.seqEl.querySelector(`[data-role="q-card"][data-qnum="${st.flat[st.idx].qnum}"]`);
        if (prevCard) { prevCard.style.display = 'none'; prevCard.classList.add('celpip-locked'); }
    }
    st.idx++;
    if (st.idx >= st.flat.length) { goToNextPart(partNum); return; }
    const item = st.flat[st.idx];
    if (item.isFirstInGroup) playGroupMedia(partNum, item.groupIdx, () => showSequentialQuestion(partNum));
    else showSequentialQuestion(partNum);
}

// Admins previewing content shouldn't have to sit through every recording
// to see how a part looks — adds a visible skip control that does exactly
// what the audio finishing naturally would (same finish/reveal callback),
// just immediately. Never shown to students.
function addAdminSkip(containerEl, onSkip) {
    if (!IS_ADMIN) return;
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'celpip-next-btn celpip-admin-skip';
    btn.innerHTML = 'NEXT <i class="bi bi-skip-forward-fill ms-1"></i>';
    btn.onclick = () => { btn.remove(); stopCurrentAudio(); onSkip(); };
    containerEl.appendChild(btn);
}

function playGroupMedia(partNum, groupIdx, onDone) {
    const st = seqState[partNum];
    const group = st.groups[groupIdx];
    const stage = st.seqEl.querySelector('[data-role="media-stage"]');
    stage.style.display = '';
    stage.querySelector('[data-role="media-label"]').textContent = group.label;
    const fill = stage.querySelector('[data-role="progress-fill"]');
    fill.style.width = '0%';
    stopCurrentAudio();
    const mediaEl = new Audio(group.src);
    currentAudioEl = mediaEl;
    const onTime = () => { if (mediaEl.duration) fill.style.width = Math.min(100, (mediaEl.currentTime / mediaEl.duration) * 100) + '%'; };
    const finish = () => {
        mediaEl.removeEventListener('timeupdate', onTime);
        mediaEl.removeEventListener('ended', finish);
        stage.style.display = 'none';
        stage.querySelectorAll('.celpip-tap-to-play, .celpip-no-audio-notice').forEach(el => el.remove());
        onDone();
    };
    mediaEl.addEventListener('timeupdate', onTime);
    mediaEl.addEventListener('ended', finish);
    playWithFallback(mediaEl, stage, finish);
    addAdminSkip(stage, finish);
}

function showSequentialQuestion(partNum) {
    const st = seqState[partNum];
    const item = st.flat[st.idx];
    const card = st.seqEl.querySelector(`[data-role="q-card"][data-qnum="${item.qnum}"]`);
    card.style.display = '';
    card.classList.remove('celpip-locked');
    const audioStage  = card.querySelector('[data-role="q-audio-stage"]');
    const answerStage = card.querySelector('[data-role="q-answer-stage"]');
    const timerVal    = card.querySelector('[data-role="q-timer-val"]');
    const timerWrap   = card.querySelector('[data-role="q-timer"]');
    const nextBtn     = card.querySelector('[data-role="next-btn"]');

    audioStage.style.display = '';
    answerStage.style.display = 'none';
    timerVal.textContent = Q_SECONDS;
    timerWrap.classList.add('calm');
    nextBtn.disabled = true;
    nextBtn.onclick = () => advanceSequential(partNum);

    function revealAnswerStage() {
        audioStage.style.display = 'none';
        audioStage.querySelectorAll('.celpip-tap-to-play, .celpip-no-audio-notice, .celpip-admin-skip').forEach(el => el.remove());
        answerStage.style.display = '';
        nextBtn.disabled = false;
        if (IS_ADMIN) {
            // Admins click NEXT whenever they're done looking — no forced
            // countdown that yanks them to the next question mid-review.
            timerVal.textContent = '∞';
            return;
        }
        let remaining = Q_SECONDS;
        timerVal.textContent = remaining;
        timerWrap.classList.remove('calm');
        st.qTimerInterval = setInterval(() => {
            remaining--;
            timerVal.textContent = Math.max(0, remaining);
            if (remaining <= 0) { clearInterval(st.qTimerInterval); st.qTimerInterval = null; advanceSequential(partNum); }
        }, 1000);
    }

    const qAudioSrc = card.dataset.audio;
    const playingLabel = card.querySelector('[data-role="q-playing-label"]');
    const qFill = card.querySelector('[data-role="q-progress-fill"]');
    stopCurrentAudio();
    const a = new Audio(qAudioSrc);
    currentAudioEl = a;
    if (playingLabel) playingLabel.textContent = 'Playing…';
    if (qFill) qFill.style.width = '0%';
    a.addEventListener('timeupdate', () => { if (a.duration && qFill) qFill.style.width = Math.min(100, (a.currentTime / a.duration) * 100) + '%'; });
    a.addEventListener('ended', revealAnswerStage);
    playWithFallback(a, audioStage, revealAnswerStage);
    addAdminSkip(audioStage, revealAnswerStage);

    updateProgress();
}

function maybeStartPart(pNum) {
    const panel = document.getElementById('panel-' + pNum);
    if (!panel) return;
    if (panel.dataset.sequential === '1') {
        initSequentialPart(panel);
        if (seqState[pNum].idx === -1) advanceSequential(pNum);
    } else {
        initAllScreenPart(panel);
    }
}

const allScreenStarted = {};
function initAllScreenPart(panel) {
    const partNum = parseInt(panel.dataset.part, 10);
    if (allScreenStarted[partNum]) return;
    allScreenStarted[partNum] = true;
    const stage = panel.querySelector('[data-role="allscreen-media"]');
    if (!stage) return;
    const fill = stage.querySelector('[data-role="progress-fill"]');
    stopCurrentAudio();
    const mediaEl = new Audio(stage.dataset.src);
    currentAudioEl = mediaEl;
    const onTime = () => { if (mediaEl.duration) fill.style.width = Math.min(100, (mediaEl.currentTime / mediaEl.duration) * 100) + '%'; };
    const finish = () => {
        mediaEl.removeEventListener('timeupdate', onTime);
        mediaEl.removeEventListener('ended', finish);
        stage.style.display = 'none';
        stage.querySelectorAll('.celpip-tap-to-play, .celpip-no-audio-notice').forEach(el => el.remove());
        const qs = panel.querySelector('[data-role="allscreen-questions"]');
        if (qs) qs.style.display = '';
        updateProgress();
    };
    mediaEl.addEventListener('timeupdate', onTime);
    mediaEl.addEventListener('ended', finish);
    playWithFallback(mediaEl, stage, finish);
    addAdminSkip(stage, finish);

    const contBtn = panel.querySelector('[data-role="part-continue-btn"]');
    if (contBtn) contBtn.onclick = () => goToNextPart(partNum);
}

function collectAnswers() {
    const ans = {};
    document.querySelectorAll('.answer-field').forEach(el => {
        const n = el.dataset.qnum;
        if (el.type === 'radio' && el.checked) ans[n] = el.value;
    });
    return ans;
}
function updateProgress() {
    const ans = collectAnswers();
    const count = Object.keys(ans).length;
    document.getElementById('answeredCount').textContent = count + ' of ' + totalQs + ' answered';
}
document.querySelectorAll('.answer-field').forEach(el => {
    el.addEventListener('change', updateProgress);
});

function submitListening(auto = false) {
    if (submitting) return;
    const ans = collectAnswers();
    const missing = totalQs - Object.keys(ans).length;
    if (!auto && missing > 0) {
        if (!confirm(`You have ${missing} unanswered question(s). Submit anyway?`)) return;
    }
    submitting = true;
    clearInterval(timerInterval);
    Object.values(seqState).forEach(st => { if (st.qTimerInterval) clearInterval(st.qTimerInterval); });
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting…';

    let score = 0;
    Object.keys(CORRECT).forEach(q => {
        if ((ans[q] || '').toUpperCase() === CORRECT[q]) score++;
    });

    fetch(SAVE_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ test_code: TEST_CODE, score: score, max_score: totalQs, band_score: 0, time_spent: elapsed, answers: ans })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            alert(`Test submitted! Score: ${score}/${totalQs}. Check My Results for details.`);
            window.location.href = BACK_URL;
        } else {
            alert(d.error || 'An error occurred. Please try again.');
            submitting = false; btn.disabled = false; btn.innerHTML = '<i class="bi bi-send me-2"></i>Submit Test';
        }
    })
    .catch(() => {
        alert('Network error. Please try again.');
        submitting = false; btn.disabled = false; btn.innerHTML = '<i class="bi bi-send me-2"></i>Submit Test';
    });
}

window.addEventListener('beforeunload', e => { if (!submitting) { e.preventDefault(); e.returnValue = ''; } });

startTimer();
updateProgress();
maybeStartPart(1);
</script>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
