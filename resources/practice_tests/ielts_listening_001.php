<?php
// IELTS Listening Practice Test 1
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}

$testCode  = 'IELTS_PT_L_001';
$timeLimit = 30 * 60;
$audioBase = ACADEMY_URL . 'assets/audio/' . $testCode . '/';

// ══════════════════════════════════════════════════════
// TEST DATA
// ══════════════════════════════════════════════════════
$parts = [
    1 => [
        'title'        => 'Part 1',
        'description'  => 'A conversation at a recruitment agency.',
        'audio_url'    => $audioBase . 'part1.mp4',
        'q_range'      => [1, 10],
        'type'         => 'form_fill',
        'instructions' => 'Complete the notes below. Write <strong>ONE WORD AND/OR A NUMBER</strong> for each answer.',
        'form_title'   => 'Bankside Recruitment Agency',
        'groups'       => [
            [
                'heading' => null,
                'rows' => [
                    ['prefix' => 'Address of agency: 497 Eastside, Docklands', 'q' => null, 'suffix' => ''],
                    ['prefix' => 'Name of agent: Becky', 'q' => 1, 'suffix' => ''],
                    ['prefix' => 'Phone number: 07866 510333', 'q' => null, 'suffix' => ''],
                    ['prefix' => 'Best to call her in the', 'q' => 2, 'suffix' => ''],
                ],
            ],
            [
                'heading' => 'Typical jobs',
                'rows' => [
                    ['prefix' => 'Clerical and admin roles, mainly in the finance industry', 'q' => null, 'suffix' => ''],
                    ['prefix' => 'Must have good', 'q' => 3, 'suffix' => 'skills'],
                    ['prefix' => 'Jobs are usually for at least one', 'q' => 4, 'suffix' => ''],
                    ['prefix' => 'Pay is usually £', 'q' => 5, 'suffix' => 'per hour'],
                ],
            ],
            [
                'heading' => 'Registration process',
                'rows' => [
                    ['prefix' => 'Wear a', 'q' => 6, 'suffix' => 'to the interview'],
                    ['prefix' => 'Must bring your', 'q' => 7, 'suffix' => 'to the interview'],
                    ['prefix' => "They will ask questions about each applicant's", 'q' => 8, 'suffix' => ''],
                ],
            ],
            [
                'heading' => 'Advantages of using an agency',
                'rows' => [
                    ['prefix' => 'The', 'q' => 9, 'suffix' => 'you receive at interview will benefit you'],
                    ['prefix' => 'Will get access to vacancies which are not advertised', 'q' => null, 'suffix' => ''],
                    ['prefix' => 'Less', 'q' => 10, 'suffix' => 'is involved in applying for jobs'],
                ],
            ],
        ],
    ],

    2 => [
        'title'       => 'Part 2',
        'description' => 'A travel company representative talks about a holiday package.',
        'audio_url'   => $audioBase . 'part2.mp4',
        'q_range'     => [11, 20],
        'type'        => 'mixed',
        'sections'    => [
            [
                'type'         => 'multiple_choice',
                'instructions' => 'Choose the correct letter, <strong>A</strong>, <strong>B</strong> or <strong>C</strong>.',
                'heading'      => 'Matthews Island Holidays',
                'questions'    => [
                    [
                        'q'       => 11,
                        'text'    => 'According to the speaker, the company',
                        'options' => [
                            'A' => 'has been in business for longer than most of its competitors.',
                            'B' => 'arranges holidays to more destinations than its competitors.',
                            'C' => 'has more customers than its competitors.',
                        ],
                    ],
                    [
                        'q'       => 12,
                        'text'    => 'Where can customers meet the tour manager before travelling to the Isle of Man?',
                        'options' => ['A' => 'Liverpool', 'B' => 'Heysham', 'C' => 'Luton'],
                    ],
                    [
                        'q'       => 13,
                        'text'    => 'How many lunches are included in the price of the holiday?',
                        'options' => ['A' => 'three', 'B' => 'four', 'C' => 'five'],
                    ],
                    [
                        'q'       => 14,
                        'text'    => 'Customers have to pay extra for',
                        'options' => [
                            'A' => 'guaranteeing themselves a larger room.',
                            'B' => 'booking at short notice.',
                            'C' => 'transferring to another date.',
                        ],
                    ],
                ],
            ],
            [
                'type'         => 'table_fill',
                'instructions' => 'Complete the table below. Write <strong>ONE WORD AND/OR A NUMBER</strong> for each answer.',
                'table_title'  => 'Timetable for Isle of Man holiday',
                'rows'         => [
                    [
                        'day'      => 'Day 1',
                        'activity' => ['text' => 'Arrive', 'q' => null],
                        'notes'    => ['prefix' => 'Introduction by manager<br>Hotel dining room has view of the', 'q' => 15, 'suffix' => ''],
                    ],
                    [
                        'day'      => 'Day 2',
                        'activity' => ['text' => 'Tynwald Exhibition and Peel', 'q' => null],
                        'notes'    => ['prefix' => 'Tynwald may have been founded in', 'q' => 16, 'suffix' => 'not 979.'],
                    ],
                    [
                        'day'      => 'Day 3',
                        'activity' => ['text' => 'Trip to Snaefell', 'q' => null],
                        'notes'    => ['prefix' => 'Travel along promenade in a tram; train to Laxey; train to the', 'q' => 17, 'suffix' => 'of Snaefell'],
                    ],
                    [
                        'day'      => 'Day 4',
                        'activity' => ['text' => 'Free day', 'q' => null],
                        'notes'    => ['prefix' => 'Company provides a', 'q' => 18, 'suffix' => 'for local transport and heritage sites.'],
                    ],
                    [
                        'day'      => 'Day 5',
                        'activity' => ['prefix' => 'Take the', 'q' => 19, 'suffix' => 'railway train from Douglas to Port Erin'],
                        'notes'    => ['prefix' => 'Free time, then coach to Castletown – former', 'q' => 20, 'suffix' => 'has old castle.'],
                    ],
                    [
                        'day'      => 'Day 6',
                        'activity' => ['text' => 'Leave', 'q' => null],
                        'notes'    => ['text' => 'Leave the island by ferry or plane', 'q' => null],
                    ],
                ],
            ],
        ],
    ],

    3 => [
        'title'       => 'Part 3',
        'description' => 'Two students discuss research on birth order and personality.',
        'audio_url'   => $audioBase . 'part3.mp4',
        'q_range'     => [21, 30],
        'type'        => 'mixed',
        'sections'    => [
            [
                'type'         => 'matching',
                'instructions' => 'What did findings of previous research claim about the personality traits a child is likely to have because of their position in the family?<br><br>Choose <strong>SIX</strong> answers from the box and write the correct letter, <strong>A–H</strong>, next to Questions 21–26.',
                'options_box'  => [
                    'A' => 'outgoing',        'B' => 'selfish',
                    'C' => 'independent',     'D' => 'attention-seeking',
                    'E' => 'introverted',     'F' => 'co-operative',
                    'G' => 'caring',          'H' => 'competitive',
                ],
                'questions' => [
                    ['q' => 21, 'label' => 'the eldest child'],
                    ['q' => 22, 'label' => 'a middle child'],
                    ['q' => 23, 'label' => 'the youngest child'],
                    ['q' => 24, 'label' => 'a twin'],
                    ['q' => 25, 'label' => 'an only child'],
                    ['q' => 26, 'label' => 'a child with much older siblings'],
                ],
            ],
            [
                'type'         => 'multiple_choice',
                'instructions' => 'Choose the correct letter, <strong>A</strong>, <strong>B</strong> or <strong>C</strong>.',
                'questions'    => [
                    [
                        'q'       => 27,
                        'text'    => 'What do the speakers say about the evidence relating to birth order and academic success?',
                        'options' => [
                            'A' => 'There is conflicting evidence about whether oldest children perform best in intelligence tests.',
                            'B' => 'There is little doubt that birth order has less influence on academic achievement than socio-economic status.',
                            'C' => 'Some studies have neglected to include important factors such as family size.',
                        ],
                    ],
                    [
                        'q'       => 28,
                        'text'    => "What does Ruth think is surprising about the difference in oldest children's academic performance?",
                        'options' => [
                            'A' => 'It is mainly thanks to their roles as teachers for their younger siblings.',
                            'B' => 'The advantages they have only lead to a slightly higher level of achievement.',
                            'C' => 'The extra parental attention they receive at a young age makes little difference.',
                        ],
                    ],
                ],
            ],
            [
                'type'         => 'multi_select',
                'instructions' => 'Choose <strong>TWO</strong> letters, <strong>A–E</strong>.<br><br>Which <strong>TWO</strong> experiences of sibling rivalry do the speakers agree has been valuable for them?',
                'q_labels'     => [29 => 'Answer 1', 30 => 'Answer 2'],
                'options'      => [
                    'A' => 'learning to share',
                    'B' => 'learning to stand up for oneself',
                    'C' => 'learning to be a good loser',
                    'D' => 'learning to be tolerant',
                    'E' => 'learning to say sorry',
                ],
            ],
        ],
    ],

    4 => [
        'title'        => 'Part 4',
        'description'  => 'A lecture on the eucalyptus tree in Australia.',
        'audio_url'    => $audioBase . 'part4.mp4',
        'q_range'      => [31, 40],
        'type'         => 'form_fill',
        'instructions' => 'Complete the notes below. Write <strong>ONE WORD ONLY</strong> for each answer.',
        'form_title'   => 'The Eucalyptus Tree in Australia',
        'groups'       => [
            [
                'heading' => 'Importance',
                'rows' => [
                    ['prefix' => 'it provides', 'q' => 31, 'suffix' => 'and food for a wide range of species'],
                    ['prefix' => 'its leaves provide', 'q' => 32, 'suffix' => 'which is used to make a disinfectant'],
                ],
            ],
            [
                'heading' => 'Reasons for present decline in number',
                'rows' => [],
            ],
            [
                'heading' => 'A) Diseases',
                'rows' => [],
            ],
            [
                'heading' => "(i) 'Mundulla Yellows'",
                'rows' => [
                    ['prefix' => 'Cause – lime used for making', 'q' => 33, 'suffix' => 'was absorbed'],
                    ['prefix' => '– trees were unable to take in necessary iron through their roots', 'q' => null, 'suffix' => ''],
                ],
            ],
            [
                'heading' => "(ii) 'Bell-miner Associated Die-back'",
                'rows' => [
                    ['prefix' => 'Cause –', 'q' => 34, 'suffix' => 'feed on eucalyptus leaves'],
                    ['prefix' => '– they secrete a substance containing sugar', 'q' => null, 'suffix' => ''],
                    ['prefix' => '– bell-miner birds are attracted by this and keep away other species', 'q' => null, 'suffix' => ''],
                ],
            ],
            [
                'heading' => "B) Bushfires – William Jackson's theory",
                'rows' => [
                    ['prefix' => 'high-frequency bushfires have impact on vegetation, resulting in the growth of', 'q' => 35, 'suffix' => ''],
                    ['prefix' => 'mid-frequency bushfires result in the growth of eucalyptus forests, because they:', 'q' => null, 'suffix' => ''],
                    ['prefix' => '– make more', 'q' => 36, 'suffix' => 'available to the trees'],
                    ['prefix' => '– maintain the quality of the', 'q' => 37, 'suffix' => ''],
                    ['prefix' => "low-frequency bushfires result in the growth of", 'q' => 38, 'suffix' => "'rainforest', which is:"],
                    ['prefix' => '– a', 'q' => 39, 'suffix' => 'ecosystem'],
                    ['prefix' => '– an ideal environment for the', 'q' => 40, 'suffix' => 'of the bell-miner'],
                ],
            ],
        ],
    ],
];

// Correct answers (lowercase, multiple accepted forms as array)
// Q29-30 are handled separately as a pair
$answers = [
    1  => ['jamieson'],
    2  => ['afternoon'],
    3  => ['communication'],
    4  => ['week'],
    5  => ['10', 'ten'],
    6  => ['suit'],
    7  => ['passport'],
    8  => ['personality'],
    9  => ['feedback'],
    10 => ['time'],
    11 => ['a'],
    12 => ['b'],
    13 => ['a'],
    14 => ['c'],
    15 => ['river'],
    16 => ['1422'],
    17 => ['top'],
    18 => ['pass'],
    19 => ['steam'],
    20 => ['capital'],
    21 => ['g'],
    22 => ['f'],
    23 => ['a'],
    24 => ['e'],
    25 => ['b'],
    26 => ['c'],
    27 => ['c'],
    28 => ['a'],
    31 => ['shelter'],
    32 => ['oil'],
    33 => ['roads'],
    34 => ['insects'],
    35 => ['grass', 'grasses'],
    36 => ['water'],
    37 => ['soil'],
    38 => ['dry'],
    39 => ['simple'],
    40 => ['nest', 'nests'],
];
$answers_pair = ['b', 'd']; // Q29 & Q30 together must include both
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Listening – Practice Test 1 | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .main-wrapper { padding: 1.5rem; background: #f8f9fa; min-height: 100vh; }
        .test-container { max-width: 1100px; margin: 0 auto; }
        .panel { background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,.07); }
        .section-badge { background: linear-gradient(135deg,#10b981,#34d399); color: white; padding: .45rem 1.4rem; border-radius: 50px; font-weight: 700; font-size: .85rem; }
        .timer-display { font-size: 2rem; font-weight: 700; font-family: monospace; color: #1e40af; }
        .timer-display.warning { color: #ef4444; animation: pulse 1s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.5} }

        /* Tabs */
        .part-tab { cursor: pointer; padding: .5rem 1.1rem; border: none; background: transparent; font-weight: 600; color: #6b7280; border-bottom: 3px solid transparent; }
        .part-tab.active { color: #10b981; border-bottom-color: #10b981; }
        .part-panel { display: none; }
        .part-panel.active { display: block; }

        /* Audio bar */
        .audio-bar { background: #f3f4f6; border-radius: 12px; padding: 1rem 1.5rem; display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
        .play-btn { width: 42px; height: 42px; border-radius: 50%; background: #10b981; border: none; color: white; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; }

        /* Form fill */
        .ff-group-heading { font-weight: 700; font-size: .82rem; text-transform: uppercase; letter-spacing: .05em; color: #374151; margin: 1.2rem 0 .4rem; border-left: 3px solid #10b981; padding-left: .6rem; }
        .ff-row { display: flex; flex-wrap: wrap; align-items: center; gap: .4rem; padding: .45rem 0; border-bottom: 1px solid #f3f4f6; font-size: .9rem; color: #374151; }
        .q-num { font-weight: 700; color: #10b981; min-width: 24px; }
        .q-input { border: 1.5px solid #d1d5db; border-radius: 6px; padding: .3rem .7rem; font-size: .88rem; min-width: 140px; }
        .q-input:focus { border-color: #10b981; outline: none; }

        /* MCQ */
        .mc-option { display: flex; align-items: flex-start; gap: .5rem; padding: .4rem .75rem; border-radius: 6px; cursor: pointer; margin-bottom: .2rem; font-size: .9rem; }
        .mc-option:hover { background: #f0fdf4; }
        .mc-option input { accent-color: #10b981; margin-top: 3px; flex-shrink: 0; }

        /* Table fill */
        .tbl-fill { width: 100%; border-collapse: collapse; font-size: .88rem; }
        .tbl-fill th { background: #f3f4f6; font-weight: 700; padding: .6rem .8rem; border: 1px solid #e5e7eb; }
        .tbl-fill td { padding: .55rem .8rem; border: 1px solid #e5e7eb; vertical-align: middle; }
        .tbl-fill .day-col { font-weight: 600; white-space: nowrap; color: #1e40af; }
        .tbl-cell-inline { display: flex; flex-wrap: wrap; align-items: center; gap: .35rem; }

        /* Matching */
        .options-box { display: flex; flex-wrap: wrap; gap: .4rem .8rem; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: .75rem 1rem; margin-bottom: 1.2rem; font-size: .88rem; }
        .options-box span { white-space: nowrap; }
        .q-select { border: 1.5px solid #d1d5db; border-radius: 6px; padding: .3rem .6rem; font-size: .88rem; }
        .q-select:focus { border-color: #10b981; outline: none; }
        .match-row { display: flex; align-items: center; gap: .75rem; padding: .45rem 0; border-bottom: 1px solid #f3f4f6; }
        .match-label { flex: 1; font-size: .9rem; }

        /* Multi-select */
        .ms-option { display: flex; align-items: center; gap: .5rem; padding: .4rem .75rem; border-radius: 6px; cursor: pointer; font-size: .9rem; }
        .ms-option:hover { background: #f0fdf4; }
        .ms-option input { accent-color: #10b981; }

        /* Results feedback */
        .q-input.correct, .q-select.correct { border-color: #10b981; background: #f0fdf4; }
        .q-input.wrong,   .q-select.wrong   { border-color: #ef4444; background: #fff1f2; }
        .mc-option.correct-answer { background: #dcfce7; }
        .mc-option.wrong-selected { background: #fee2e2; }
        .ms-option.correct-answer { background: #dcfce7; }
        .ms-option.wrong-selected { background: #fee2e2; }
        .hint { font-size: .78rem; color: #10b981; margin-top: .15rem; }
        .hint.wrong { color: #ef4444; }

        /* Results summary */
        #resultsBanner { display: none; border-radius: 12px; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; }
        .band-badge { font-size: 2rem; font-weight: 800; color: #10b981; }
        .part-score-row { display: flex; justify-content: space-between; font-size: .88rem; border-bottom: 1px solid #e5e7eb; padding: .3rem 0; }
    </style>
</head>
<body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <main class="main-wrapper">
        <div class="test-container">

            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../resources_home.php">Resources</a></li>
                    <li class="breadcrumb-item"><a href="index.php">Practice Tests</a></li>
                    <li class="breadcrumb-item active">IELTS Listening – Practice 1</li>
                </ol>
            </nav>

            <!-- Results banner (shown after submit) -->
            <div id="resultsBanner" class="panel">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="band-badge" id="bandScore">–</div>
                    <div>
                        <div class="fw-bold fs-5">Your Results</div>
                        <div class="text-muted small">IELTS Listening – Practice Test 1</div>
                    </div>
                    <div class="ms-auto text-end">
                        <div class="fw-bold fs-4" id="totalScore">–/40</div>
                        <div class="text-muted small">Total score</div>
                    </div>
                </div>
                <div id="partBreakdown"></div>
                <p class="text-muted small mt-2 mb-0">Answers highlighted below — <span style="color:#10b981">green</span> = correct, <span style="color:#ef4444">red</span> = incorrect.</p>
            </div>

            <div class="panel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <span class="section-badge"><i class="bi bi-headphones me-1"></i>Listening</span>
                        <span class="text-muted small">IELTS · 40 Questions · 30 Minutes</span>
                    </div>
                    <div class="timer-display" id="timerEl">30:00</div>
                </div>

                <!-- Part tabs -->
                <div class="d-flex border-bottom mb-4" id="partTabs">
                    <?php foreach ($parts as $pNum => $p): ?>
                    <button class="part-tab <?= $pNum === 1 ? 'active' : '' ?>"
                            onclick="switchPart(<?= $pNum ?>)" id="ptab-<?= $pNum ?>">
                        <?= $p['title'] ?>
                        <span class="text-muted" style="font-size:.72rem;">
                            Q<?= $p['q_range'][0] ?>–<?= $p['q_range'][1] ?>
                        </span>
                    </button>
                    <?php endforeach; ?>
                </div>

                <!-- Part panels -->
                <?php foreach ($parts as $pNum => $p): ?>
                <div class="part-panel <?= $pNum === 1 ? 'active' : '' ?>" id="ppanel-<?= $pNum ?>">
                    <p class="text-muted small mb-3">
                        <i class="bi bi-info-circle me-1"></i><?= htmlspecialchars($p['description']) ?>
                    </p>

                    <!-- Audio player -->
                    <div class="audio-bar">
                        <button class="play-btn" onclick="toggleAudio(<?= $pNum ?>)" id="playBtn-<?= $pNum ?>">
                            <i class="bi bi-play-fill" id="playIcon-<?= $pNum ?>"></i>
                        </button>
                        <audio id="audio-<?= $pNum ?>" src="<?= htmlspecialchars($p['audio_url']) ?>"></audio>
                        <span class="text-muted small"><?= $p['title'] ?> Recording</span>
                        <span class="ms-auto text-muted small" id="audioTime-<?= $pNum ?>">0:00</span>
                    </div>

                    <?php if ($p['type'] === 'form_fill'): ?>
                        <p class="small text-secondary mb-2"><?= $p['instructions'] ?></p>
                        <div class="fw-semibold mb-3 text-center"><?= htmlspecialchars($p['form_title']) ?></div>
                        <?php foreach ($p['groups'] as $grp): ?>
                            <?php if ($grp['heading']): ?>
                            <div class="ff-group-heading"><?= htmlspecialchars($grp['heading']) ?></div>
                            <?php endif; ?>
                            <?php foreach ($grp['rows'] as $row): ?>
                            <div class="ff-row">
                                <?php if ($row['q'] !== null): ?><span class="q-num"><?= $row['q'] ?>.</span><?php endif; ?>
                                <span><?= htmlspecialchars($row['prefix']) ?></span>
                                <?php if ($row['q'] !== null): ?>
                                    <input type="text" class="q-input" id="q<?= $row['q'] ?>" placeholder="…">
                                <?php endif; ?>
                                <?php if (!empty($row['suffix'])): ?>
                                    <span><?= htmlspecialchars($row['suffix']) ?></span>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        <?php endforeach; ?>

                    <?php elseif ($p['type'] === 'mixed'): ?>
                        <?php foreach ($p['sections'] as $sec): ?>

                            <?php if ($sec['type'] === 'multiple_choice'): ?>
                                <?php if (!empty($sec['heading'])): ?>
                                    <div class="fw-semibold mb-2 text-center"><?= htmlspecialchars($sec['heading']) ?></div>
                                <?php endif; ?>
                                <p class="small text-secondary mb-3"><?= $sec['instructions'] ?></p>
                                <?php foreach ($sec['questions'] as $mc): ?>
                                <div class="mb-4">
                                    <p class="mb-2 fw-semibold" style="font-size:.9rem;">
                                        <span class="q-num"><?= $mc['q'] ?>.</span> <?= htmlspecialchars($mc['text']) ?>
                                    </p>
                                    <?php foreach ($mc['options'] as $letter => $opt): ?>
                                    <label class="mc-option" id="opt-<?= $mc['q'] ?>-<?= $letter ?>">
                                        <input type="radio" name="q<?= $mc['q'] ?>" value="<?= $letter ?>"
                                               onchange="setAnswer(<?= $mc['q'] ?>, '<?= $letter ?>')">
                                        <strong><?= $letter ?>.</strong> <?= htmlspecialchars($opt) ?>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                                <?php endforeach; ?>
                                <hr class="my-3">

                            <?php elseif ($sec['type'] === 'table_fill'): ?>
                                <p class="small text-secondary mb-2"><?= $sec['instructions'] ?></p>
                                <p class="fw-semibold text-center mb-2"><?= htmlspecialchars($sec['table_title']) ?></p>
                                <div class="table-responsive mb-3">
                                <table class="tbl-fill">
                                    <thead>
                                        <tr>
                                            <th style="width:80px"></th>
                                            <th>Activity</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($sec['rows'] as $tr): ?>
                                    <tr>
                                        <td class="day-col"><?= htmlspecialchars($tr['day']) ?></td>
                                        <td>
                                            <?php $act = $tr['activity']; ?>
                                            <?php if (!empty($act['q'])): ?>
                                            <div class="tbl-cell-inline">
                                                <span><?= htmlspecialchars($act['prefix'] ?? '') ?></span>
                                                <strong class="q-num"><?= $act['q'] ?>.</strong>
                                                <input type="text" class="q-input" id="q<?= $act['q'] ?>" placeholder="…" style="min-width:100px;">
                                                <?php if (!empty($act['suffix'])): ?><span><?= htmlspecialchars($act['suffix']) ?></span><?php endif; ?>
                                            </div>
                                            <?php else: ?>
                                                <?= htmlspecialchars($act['text'] ?? '') ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php $nt = $tr['notes']; ?>
                                            <?php if (!empty($nt['q'])): ?>
                                            <div class="tbl-cell-inline">
                                                <span><?= $nt['prefix'] /* trusted HTML (br tags) */ ?></span>
                                                <strong class="q-num"><?= $nt['q'] ?>.</strong>
                                                <input type="text" class="q-input" id="q<?= $nt['q'] ?>" placeholder="…" style="min-width:80px;">
                                                <?php if (!empty($nt['suffix'])): ?><span><?= htmlspecialchars($nt['suffix']) ?></span><?php endif; ?>
                                            </div>
                                            <?php else: ?>
                                                <?= htmlspecialchars($nt['text'] ?? '') ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                                </div>
                                <hr class="my-3">

                            <?php elseif ($sec['type'] === 'matching'): ?>
                                <p class="small text-secondary mb-3"><?= $sec['instructions'] ?></p>
                                <div class="options-box mb-3">
                                    <strong class="w-100 mb-1" style="font-size:.8rem;">Personality Traits</strong>
                                    <?php foreach ($sec['options_box'] as $letter => $trait): ?>
                                    <span><strong><?= $letter ?></strong> <?= htmlspecialchars($trait) ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <div class="mb-3"><strong style="font-size:.82rem;">Position in family</strong></div>
                                <?php foreach ($sec['questions'] as $mq): ?>
                                <div class="match-row">
                                    <span class="q-num"><?= $mq['q'] ?></span>
                                    <span class="match-label"><?= htmlspecialchars($mq['label']) ?></span>
                                    <select class="q-select" id="q<?= $mq['q'] ?>"
                                            onchange="setAnswer(<?= $mq['q'] ?>, this.value)">
                                        <option value="">–</option>
                                        <?php foreach ($sec['options_box'] as $letter => $trait): ?>
                                        <option value="<?= $letter ?>"><?= $letter ?> – <?= htmlspecialchars($trait) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <?php endforeach; ?>
                                <hr class="my-3">

                            <?php elseif ($sec['type'] === 'multi_select'): ?>
                                <p class="small text-secondary mb-3"><?= $sec['instructions'] ?></p>
                                <p class="text-muted small mb-2">Questions 29 &amp; 30 – select exactly two answers.</p>
                                <div id="multiSelectGroup">
                                <?php foreach ($sec['options'] as $letter => $opt): ?>
                                <label class="ms-option" id="ms-<?= $letter ?>">
                                    <input type="checkbox" class="q-check" value="<?= $letter ?>"
                                           onchange="handleMultiSelect(this)">
                                    <strong><?= $letter ?>.</strong> <?= htmlspecialchars($opt) ?>
                                </label>
                                <?php endforeach; ?>
                                </div>
                                <div id="ms-hint" class="small text-muted mt-1"></div>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- Navigation -->
                    <div class="d-flex justify-content-end mt-4">
                        <?php if ($pNum < 4): ?>
                        <button class="btn btn-outline-success" onclick="switchPart(<?= $pNum + 1 ?>)">
                            <?= $parts[$pNum + 1]['title'] ?> <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                        <?php else: ?>
                        <button class="btn btn-success px-4" id="submitBtn" onclick="submitTest()">
                            Submit <i class="bi bi-send ms-1"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div><!-- .panel -->
        </div><!-- .test-container -->
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <script>
    const CORRECT      = <?= json_encode($answers) ?>;
    const CORRECT_PAIR = <?= json_encode($answers_pair) ?>; // Q29 & 30
    const TEST_CODE    = <?= json_encode($testCode) ?>;
    const startTime    = Date.now();
    let userAnswers = {}, timeLeft = <?= $timeLimit ?>, submitted = false;

    // ── Timer ────────────────────────────────────────────
    const timerEl = document.getElementById('timerEl');
    function fmtTime(s) {
        return String(Math.floor(s / 60)).padStart(2, '0') + ':' + String(s % 60).padStart(2, '0');
    }
    const timerInterval = setInterval(() => {
        timeLeft--;
        timerEl.textContent = fmtTime(timeLeft);
        if (timeLeft <= 300) timerEl.classList.add('warning');
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            Swal.fire({ title: "Time's up!", text: 'Submitting your answers now.', icon: 'warning',
                        timer: 2000, timerProgressBar: true, showConfirmButton: false })
                .then(() => doSubmit());
        }
    }, 1000);

    // ── Answer collection ────────────────────────────────
    document.querySelectorAll('.q-input').forEach(el => {
        el.addEventListener('input', () => {
            const q = parseInt(el.id.replace('q', ''));
            userAnswers[q] = el.value.trim();
        });
    });

    function setAnswer(q, val) { userAnswers[q] = val; }

    const msSelected = new Set();
    function handleMultiSelect(cb) {
        if (cb.checked) {
            if (msSelected.size >= 2) { cb.checked = false; return; }
            msSelected.add(cb.value);
        } else {
            msSelected.delete(cb.value);
        }
        document.getElementById('ms-hint').textContent =
            msSelected.size === 0 ? '' :
            msSelected.size === 1 ? '1 of 2 selected' :
            '2 of 2 selected';
        // Assign to Q29 and Q30
        const sel = [...msSelected];
        userAnswers[29] = sel[0] || '';
        userAnswers[30] = sel[1] || '';
    }

    // ── Part tabs ────────────────────────────────────────
    function switchPart(n) {
        document.querySelectorAll('.part-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.part-tab').forEach(t => t.classList.remove('active'));
        document.getElementById('ppanel-' + n).classList.add('active');
        document.getElementById('ptab-' + n).classList.add('active');
    }

    // ── Audio ────────────────────────────────────────────
    function toggleAudio(pNum) {
        const audio = document.getElementById('audio-' + pNum);
        const icon  = document.getElementById('playIcon-' + pNum);
        if (audio.paused) {
            audio.play().catch(() => {});
            icon.className = 'bi bi-pause-fill';
        } else {
            audio.pause();
            icon.className = 'bi bi-play-fill';
        }
        audio.ontimeupdate = () => {
            document.getElementById('audioTime-' + pNum).textContent = fmtTime(Math.floor(audio.currentTime));
        };
        audio.onended = () => { icon.className = 'bi bi-play-fill'; };
    }

    // ── Submit ───────────────────────────────────────────
    function submitTest() {
        Swal.fire({
            title: 'Submit test?',
            text: 'Make sure you have answered all 40 questions.',
            icon: 'question', showCancelButton: true,
            confirmButtonText: 'Submit', cancelButtonText: 'Review',
            confirmButtonColor: '#10b981',
        }).then(r => { if (r.isConfirmed) doSubmit(); });
    }

    function doSubmit() {
        if (submitted) return;
        submitted = true;
        clearInterval(timerInterval);
        document.getElementById('submitBtn') && (document.getElementById('submitBtn').disabled = true);

        let partScores = { 1: 0, 2: 0, 3: 0, 4: 0 };
        const partRanges = { 1: [1,10], 2: [11,20], 3: [21,28], 4: [31,40] };

        // Grade Q1-28 and Q31-40
        Object.entries(CORRECT).forEach(([q, accepted]) => {
            const num = parseInt(q);
            const userVal = (userAnswers[num] || '').trim().toLowerCase();
            const correct = accepted.some(a => a.toLowerCase() === userVal);
            markInput(num, correct, accepted[0]);
            const part = getPart(num);
            if (correct && part) partScores[part]++;
        });

        // Grade Q29-30 as a pair
        const sel29 = (userAnswers[29] || '').toLowerCase();
        const sel30 = (userAnswers[30] || '').toLowerCase();
        const pairSet = new Set(CORRECT_PAIR);
        const selectedSet = new Set([sel29, sel30].filter(Boolean));
        let pairScore = 0;
        pairSet.forEach(c => { if (selectedSet.has(c)) pairScore++; });
        partScores[3] += pairScore;
        markMultiSelect(sel29, sel30, pairSet);

        const total    = Object.values(partScores).reduce((a, b) => a + b, 0);
        const band     = toBand(total);
        const timeSecs = Math.round((Date.now() - startTime) / 1000);
        showResults(total, partScores, band);
        saveAttempt(total, 40, parseFloat(band), timeSecs);
    }

    function saveAttempt(score, maxScore, bandScore, timeSpent) {
        fetch('save_attempt.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                test_code:  TEST_CODE,
                score:      score,
                max_score:  maxScore,
                band_score: bandScore,
                time_spent: timeSpent,
                answers:    userAnswers,
            }),
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) console.error('save_attempt error:', data.error);
        })
        .catch(err => console.error('save_attempt fetch error:', err));
    }

    function getPart(q) {
        if (q >= 1  && q <= 10) return 1;
        if (q >= 11 && q <= 20) return 2;
        if (q >= 21 && q <= 30) return 3;
        if (q >= 31 && q <= 40) return 4;
        return null;
    }

    function markInput(q, correct, correctVal) {
        const el = document.getElementById('q' + q);
        if (!el) return;
        el.disabled = true;
        el.classList.add(correct ? 'correct' : 'wrong');
        if (!correct) {
            const hint = document.createElement('div');
            hint.className = 'hint';
            hint.textContent = '✓ ' + correctVal;
            el.after(hint);
        }
        // MCQ: highlight options
        const opts = document.querySelectorAll(`[id^="opt-${q}-"]`);
        opts.forEach(lbl => {
            const letter = lbl.id.split('-')[2];
            const radio = lbl.querySelector('input');
            if (radio) radio.disabled = true;
            if (letter === CORRECT[q]?.[0]?.toUpperCase()) lbl.classList.add('correct-answer');
            else if (radio?.checked) lbl.classList.add('wrong-selected');
        });
    }

    function markMultiSelect(s29, s30, pairSet) {
        const selectedArr = [s29, s30].filter(Boolean);
        ['A','B','C','D','E'].forEach(letter => {
            const lbl = document.getElementById('ms-' + letter);
            if (!lbl) return;
            lbl.querySelector('input').disabled = true;
            const lc = letter.toLowerCase();
            if (pairSet.has(lc)) lbl.classList.add('correct-answer');
            else if (selectedArr.includes(lc)) lbl.classList.add('wrong-selected');
        });
    }

    // IELTS raw score → band estimate
    function toBand(score) {
        if (score >= 39) return '9.0';
        if (score >= 37) return '8.5';
        if (score >= 35) return '8.0';
        if (score >= 32) return '7.5';
        if (score >= 30) return '7.0';
        if (score >= 26) return '6.5';
        if (score >= 23) return '6.0';
        if (score >= 18) return '5.5';
        if (score >= 16) return '5.0';
        if (score >= 13) return '4.5';
        if (score >= 10) return '4.0';
        return '<4.0';
    }

    function showResults(total, partScores, band) {
        const banner = document.getElementById('resultsBanner');
        document.getElementById('totalScore').textContent = total + '/40';
        document.getElementById('bandScore').textContent  = 'Band ' + toBand(total);

        const breakdown = document.getElementById('partBreakdown');
        breakdown.innerHTML = [
            ['Part 1', partScores[1], 10],
            ['Part 2', partScores[2], 10],
            ['Part 3', partScores[3], 10],
            ['Part 4', partScores[4], 10],
        ].map(([label, score, max]) =>
            `<div class="part-score-row"><span>${label}</span><span>${score}/${max}</span></div>`
        ).join('');

        banner.style.display = 'block';
        banner.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    </script>
</body>
</html>
