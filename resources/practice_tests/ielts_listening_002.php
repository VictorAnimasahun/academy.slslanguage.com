<?php
// IELTS Listening Practice Test 2
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}
require_once INCLUDES_PATH . '/admin_check.php';
$isAdmin = is_platform_admin();
require_once INCLUDES_PATH . '/course_lock.php';
// Listening content is identical between Academic and General Training, so
// this test is a legitimate, permanent fit for both course families.
require_course_enrollment([9, 10, 11, 16, 17], 'this IELTS Listening practice test');

$testCode  = 'IELTS_PT_L_002';
$timeLimit = 30 * 60;
$audioBase = ACADEMY_URL . 'assets/audio/' . $testCode . '/';

// ══════════════════════════════════════════════════════
// TEST DATA — content confirmed against the printed paper 2026-09-21
// ══════════════════════════════════════════════════════
$parts = [
    1 => [
        'title'       => 'Part 1',
        'description' => 'A conversation about a local arts festival.',
        'audio_url'   => $audioBase . 'part1.mp4',
        'q_range'     => [1, 10],
        'type'        => 'mixed',
        'sections'    => [
            [
                'type'         => 'info_table',
                'instructions' => 'Complete the table below. Write <strong>ONE WORD ONLY</strong> for each answer.',
                'table_title'  => 'Festival information',
                'columns'      => ['Date', 'Type of event', 'Details'],
                // Cells are lists of segments: a string is trusted HTML, an int is a question number.
                'rows'         => [
                    [['17th'], ['a concert'], ['performers from Canada']],
                    [['18th'], ['a ballet'], ['company called ', 1]],
                    [['19th–20th (afternoon)'], ['a play'], ['type of play: a comedy called <em>Jemima</em><br>has had a good ', 2]],
                    [['20th (evening)'], ['a ', 3, ' show'], ['show is called ', 4]],
                ],
            ],
            [
                'type'         => 'notes',
                'instructions' => 'Complete the notes below. Write <strong>ONE WORD ONLY</strong> for each answer.',
                'groups'       => [
                    [
                        'heading' => 'Workshops',
                        'rows' => [
                            ['prefix' => 'Making', 'q' => 5, 'suffix' => 'food'],
                            ['prefix' => '(children only) Making', 'q' => 6, 'suffix' => ''],
                            ['prefix' => '(adults only) Making toys from', 'q' => 7, 'suffix' => 'using various tools'],
                        ],
                    ],
                    [
                        'heading' => 'Outdoor activities',
                        'rows' => [
                            ['prefix' => 'Swimming in the', 'q' => 8, 'suffix' => ''],
                            ['prefix' => 'Walking in the woods, led by an expert on', 'q' => 9, 'suffix' => ''],
                            ['prefix' => "See the festival organiser's", 'q' => 10, 'suffix' => 'for more information'],
                        ],
                    ],
                ],
            ],
        ],
    ],

    2 => [
        'title'       => 'Part 2',
        'description' => 'A talk about the history and layout of Minster Park.',
        'audio_url'   => $audioBase . 'part2.mp4',
        'q_range'     => [11, 20],
        'type'        => 'mixed',
        'sections'    => [
            [
                'type'         => 'multiple_choice',
                'instructions' => 'Choose the correct letter, <strong>A</strong>, <strong>B</strong> or <strong>C</strong>.',
                'heading'      => 'Minster Park',
                'questions'    => [
                    [
                        'q'       => 11,
                        'text'    => 'The park was originally established',
                        'options' => [
                            'A' => 'as an amenity provided by the city council.',
                            'B' => 'as land belonging to a private house.',
                            'C' => 'as a shared area set up by the local community.',
                        ],
                    ],
                    [
                        'q'       => 12,
                        'text'    => 'Why is there a statue of Diane Gosforth in the park?',
                        'options' => [
                            'A' => 'She was a resident who helped to lead a campaign.',
                            'B' => 'She was a council member responsible for giving the public access.',
                            'C' => 'She was a senior worker at the park for many years.',
                        ],
                    ],
                    [
                        'q'       => 13,
                        'text'    => 'During the First World War, the park was mainly used for',
                        'options' => ['A' => 'exercises by troops.', 'B' => 'growing vegetables.', 'C' => 'public meetings.'],
                    ],
                    [
                        'q'       => 14,
                        'text'    => 'When did the physical transformation of the park begin?',
                        'options' => ['A' => '2013', 'B' => '2015', 'C' => '2016'],
                    ],
                ],
            ],
            [
                'type'         => 'map_label',
                'instructions' => 'Label the map below. Write the correct letter, <strong>A–I</strong>, next to Questions 15–20.',
                'image'        => ACADEMY_URL . 'assets/images/ielts_listening_002_map.png',
                'image_alt'    => 'Map of Minster Park with locations labelled A to I',
                'letters'      => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'],
                'questions'    => [
                    ['q' => 15, 'label' => 'statue of Diane Gosforth'],
                    ['q' => 16, 'label' => 'wooden sculptures'],
                    ['q' => 17, 'label' => 'playground'],
                    ['q' => 18, 'label' => 'maze'],
                    ['q' => 19, 'label' => 'tennis courts'],
                    ['q' => 20, 'label' => 'fitness area'],
                ],
            ],
        ],
    ],

    3 => [
        'title'       => 'Part 3',
        'description' => 'Two students plan a university display about Charles Dickens.',
        'audio_url'   => $audioBase . 'part3.mp4',
        'q_range'     => [21, 30],
        'type'        => 'mixed',
        'sections'    => [
            [
                'type'         => 'multi_select',
                'instructions' => 'Choose <strong>TWO</strong> letters, <strong>A–E</strong>.<br><br>Which <strong>TWO</strong> groups of people is the display primarily intended for?',
                'q'            => [21, 22],
                'correct'      => ['b', 'd'],
                'options'      => [
                    'A' => 'students from the English department',
                    'B' => 'residents of the local area',
                    'C' => "the university's teaching staff",
                    'D' => 'potential new students',
                    'E' => 'students from other departments',
                ],
            ],
            [
                'type'         => 'multi_select',
                'instructions' => "Choose <strong>TWO</strong> letters, <strong>A–E</strong>.<br><br>What are Cathy and Graham's <strong>TWO</strong> reasons for choosing the novelist Charles Dickens?",
                'q'            => [23, 24],
                'correct'      => ['b', 'c'],
                'options'      => [
                    'A' => 'His speeches inspired others to try to improve society.',
                    'B' => 'He used his publications to draw attention to social problems.',
                    'C' => 'His novels are well-known now.',
                    'D' => 'He was consulted on a number of social issues.',
                    'E' => 'His reputation has changed in recent times.',
                ],
            ],
            [
                'type'         => 'matching',
                'instructions' => 'What topic do Cathy and Graham choose to illustrate with each novel?<br><br>Choose <strong>SIX</strong> answers from the box and write the correct letter, <strong>A–H</strong>, next to Questions 25–30.',
                'box_title'    => 'Topics',
                'list_title'   => 'Novels by Dickens',
                'options_box'  => [
                    'A' => 'poverty',      'B' => 'education',
                    'C' => "Dickens's travels", 'D' => 'entertainment',
                    'E' => 'crime and the law', 'F' => 'wealth',
                    'G' => 'medicine',     'H' => "a woman's life",
                ],
                'questions' => [
                    ['q' => 25, 'label' => 'The Pickwick Papers'],
                    ['q' => 26, 'label' => 'Oliver Twist'],
                    ['q' => 27, 'label' => 'Nicholas Nickleby'],
                    ['q' => 28, 'label' => 'Martin Chuzzlewit'],
                    ['q' => 29, 'label' => 'Bleak House'],
                    ['q' => 30, 'label' => 'Little Dorrit'],
                ],
            ],
        ],
    ],

    4 => [
        'title'        => 'Part 4',
        'description'  => 'A talk about an agricultural programme in Mozambique.',
        'audio_url'    => $audioBase . 'part4.mp4',
        'q_range'      => [31, 40],
        'type'         => 'form_fill',
        'instructions' => 'Complete the notes below. Write <strong>ONE WORD ONLY</strong> for each answer.',
        'form_title'   => 'Agricultural programme in Mozambique',
        'groups'       => [
            [
                'heading' => 'How the programme was organised',
                'rows' => [
                    ['prefix' => 'It focused on a dry and arid region in Chicualacuala district, near the Limpopo River.', 'q' => null, 'suffix' => ''],
                    ['prefix' => 'People depended on the forest to provide charcoal as a source of income.', 'q' => null, 'suffix' => ''],
                    ['prefix' => '', 'q' => 31, 'suffix' => 'was seen as the main priority to ensure the supply of water.'],
                    ['prefix' => "Most of the work organised by farmers' associations was done by", 'q' => 32, 'suffix' => ''],
                    ['prefix' => 'Fenced areas were created to keep animals away from crops.', 'q' => null, 'suffix' => ''],
                    ['prefix' => 'The programme provided', 'q' => null, 'suffix' => ''],
                    ['prefix' => '–', 'q' => 33, 'suffix' => 'for the fences'],
                    ['prefix' => '–', 'q' => 34, 'suffix' => 'for suitable crops'],
                    ['prefix' => '– water pumps.', 'q' => null, 'suffix' => ''],
                    ['prefix' => 'The farmers provided', 'q' => null, 'suffix' => ''],
                    ['prefix' => '– labour', 'q' => null, 'suffix' => ''],
                    ['prefix' => '–', 'q' => 35, 'suffix' => 'for the fences on their land.'],
                ],
            ],
            [
                'heading' => 'Further developments',
                'rows' => [
                    ['prefix' => 'The marketing of produce was sometimes difficult due to lack of', 'q' => 36, 'suffix' => ''],
                    ['prefix' => 'Training was therefore provided in methods of food', 'q' => 37, 'suffix' => ''],
                    ['prefix' => 'Farmers made special places where', 'q' => 38, 'suffix' => 'could be kept.'],
                    ['prefix' => 'Local people later suggested keeping', 'q' => 39, 'suffix' => ''],
                ],
            ],
            [
                'heading' => 'Evaluation and lessons learned',
                'rows' => [
                    ['prefix' => 'Agricultural production increased, improving incomes and food security.', 'q' => null, 'suffix' => ''],
                    ['prefix' => 'Enough time must be allowed, particularly for the', 'q' => 40, 'suffix' => 'phase of the programme.'],
                ],
            ],
        ],
    ],
];

// "Choose TWO" pairs — collected from the multi_select sections so the page and
// grader share one source. Each pair is graded as a set, in either order.
$answers_pairs = [];
foreach ($parts as $p) {
    foreach ($p['sections'] ?? [] as $sec) {
        if ($sec['type'] === 'multi_select') $answers_pairs[] = ['q' => $sec['q'], 'correct' => $sec['correct']];
    }
}

require_once __DIR__ . '/functions.php';
/** @var \PDO $db */
$answers = loadTestAnswers($db, $testCode);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Listening – Practice Test 2 | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .main-wrapper { padding: 1.5rem; min-height: 100vh; }
        .test-container { max-width: 100%; }
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
<body class="light">
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <div class="main-wrapper flex-grow-1" style="flex:1;">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>

    <main class="content p-4">
        <div class="test-container">

            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../resources_home.php">Resources</a></li>
                    <li class="breadcrumb-item"><a href="index.php">Practice Tests</a></li>
                    <li class="breadcrumb-item active">IELTS Listening – Practice 2</li>
                </ol>
            </nav>

            <!-- Results banner (shown after submit) -->
            <div id="resultsBanner" class="panel">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="band-badge" id="bandScore">–</div>
                    <div>
                        <div class="fw-bold fs-5">Your Results</div>
                        <div class="text-muted small">IELTS Listening – Practice Test 2</div>
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

                <!-- Part tabs — click-navigable for admins only. Students follow
                     the real IELTS flow: no pausing, no jumping ahead or back,
                     each part's recording auto-advances to the next once it
                     finishes (see JS below). -->
                <div class="d-flex border-bottom mb-4" id="partTabs">
                    <?php foreach ($parts as $pNum => $p): ?>
                    <button class="part-tab <?= $pNum === 1 ? 'active' : '' ?>"
                            onclick="switchPart(<?= $pNum ?>, this)" id="ptab-<?= $pNum ?>"
                            <?= (!$isAdmin && $pNum !== 1) ? 'disabled style="cursor:default;"' : '' ?>>
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

                    <!-- Audio player — no play/pause control: the recording
                         plays automatically and continuously, exactly like the
                         real IELTS test. A "Tap to play" button appears only
                         if the browser blocks autoplay outright. -->
                    <div class="audio-bar" id="audioBar-<?= $pNum ?>">
                        <i class="bi bi-volume-up-fill" style="font-size:1.1rem;color:#10b981;flex-shrink:0;"></i>
                        <audio id="audio-<?= $pNum ?>" src="<?= htmlspecialchars($p['audio_url']) ?>"></audio>
                        <span class="text-muted small"><?= $p['title'] ?> Recording — playing…</span>
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

                            <?php elseif ($sec['type'] === 'info_table'): ?>
                                <p class="small text-secondary mb-2"><?= $sec['instructions'] ?></p>
                                <p class="fw-semibold text-center mb-2"><?= htmlspecialchars($sec['table_title']) ?></p>
                                <div class="table-responsive mb-3">
                                <table class="tbl-fill">
                                    <thead><tr>
                                        <?php foreach ($sec['columns'] as $col): ?><th><?= htmlspecialchars($col) ?></th><?php endforeach; ?>
                                    </tr></thead>
                                    <tbody>
                                    <?php foreach ($sec['rows'] as $tr): ?>
                                    <tr>
                                        <?php foreach ($tr as $cell): ?>
                                        <td>
                                            <?php foreach ($cell as $seg): ?>
                                                <?php if (is_int($seg)): ?>
                                                    <strong class="q-num"><?= $seg ?>.</strong>
                                                    <input type="text" class="q-input" id="q<?= $seg ?>" placeholder="…" style="min-width:100px;">
                                                <?php else: ?><?= $seg /* trusted HTML */ ?><?php endif; ?>
                                            <?php endforeach; ?>
                                        </td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                                </div>
                                <hr class="my-3">

                            <?php elseif ($sec['type'] === 'notes'): ?>
                                <p class="small text-secondary mb-2"><?= $sec['instructions'] ?></p>
                                <?php foreach ($sec['groups'] as $grp): ?>
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
                                        <?php if (!empty($row['suffix'])): ?><span><?= htmlspecialchars($row['suffix']) ?></span><?php endif; ?>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                                <hr class="my-3">

                            <?php elseif ($sec['type'] === 'map_label'): ?>
                                <p class="small text-secondary mb-3"><?= $sec['instructions'] ?></p>
                                <div class="text-center mb-3">
                                    <img src="<?= htmlspecialchars($sec['image']) ?>" alt="<?= htmlspecialchars($sec['image_alt']) ?>"
                                         style="max-width:100%;width:520px;height:auto;border:1px solid #e5e7eb;border-radius:8px;background:#fff;">
                                </div>
                                <?php foreach ($sec['questions'] as $mq): ?>
                                <div class="match-row">
                                    <span class="q-num"><?= $mq['q'] ?></span>
                                    <span class="match-label"><?= htmlspecialchars($mq['label']) ?></span>
                                    <select class="q-select" id="q<?= $mq['q'] ?>" onchange="setAnswer(<?= $mq['q'] ?>, this.value)">
                                        <option value="">–</option>
                                        <?php foreach ($sec['letters'] as $letter): ?>
                                        <option value="<?= $letter ?>"><?= $letter ?></option>
                                        <?php endforeach; ?>
                                    </select>
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
                                    <strong class="w-100 mb-1" style="font-size:.8rem;"><?= htmlspecialchars($sec['box_title'] ?? 'Options') ?></strong>
                                    <?php foreach ($sec['options_box'] as $letter => $trait): ?>
                                    <span><strong><?= $letter ?></strong> <?= htmlspecialchars($trait) ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <div class="mb-3"><strong style="font-size:.82rem;"><?= htmlspecialchars($sec['list_title'] ?? '') ?></strong></div>
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

                            <?php elseif ($sec['type'] === 'multi_select'): $gi = $sec['q'][0]; ?>
                                <p class="small text-secondary mb-3"><?= $sec['instructions'] ?></p>
                                <p class="text-muted small mb-2">Questions <?= $sec['q'][0] ?> &amp; <?= $sec['q'][1] ?> – select exactly two answers.</p>
                                <div>
                                <?php foreach ($sec['options'] as $letter => $opt): ?>
                                <label class="ms-option" id="ms-<?= $gi ?>-<?= $letter ?>">
                                    <input type="checkbox" class="q-check" value="<?= $letter ?>"
                                           onchange="handleMultiSelect(this, <?= $sec['q'][0] ?>, <?= $sec['q'][1] ?>)">
                                    <strong><?= $letter ?>.</strong> <?= htmlspecialchars($opt) ?>
                                </label>
                                <?php endforeach; ?>
                                </div>
                                <div id="ms-hint-<?= $gi ?>" class="small text-muted mt-1 mb-3"></div>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- Navigation — students advance automatically when each
                         part's recording finishes; admins keep a manual button
                         for previewing content. -->
                    <div class="d-flex justify-content-end mt-4">
                        <?php if ($pNum < 4 && $isAdmin): ?>
                        <button class="btn btn-outline-success" onclick="switchPart(<?= $pNum + 1 ?>, document.getElementById('ptab-<?= $pNum + 1 ?>'), true)">
                            <?= $parts[$pNum + 1]['title'] ?> <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                        <?php elseif ($pNum === 4): ?>
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
    </div><!-- /.main-wrapper -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <?php include INCLUDES_PATH . '/footer.php'; ?>
    <script>
    const CORRECT      = <?= json_encode($answers) ?>;
    const PAIRS        = <?= json_encode($answers_pairs) ?>; // [{q:[a,b], correct:['b','d']}]
    const PAIR_QS      = new Set(PAIRS.flatMap(p => p.q));
    const TEST_CODE    = <?= json_encode($testCode) ?>;
    const IS_ADMIN     = <?= $isAdmin ? 'true' : 'false' ?>;
    const TOTAL_PARTS  = <?= count($parts) ?>;
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

    // "Choose TWO" pairs — one selection set per pair, keyed by its first question number.
    const msSelected = {};
    function handleMultiSelect(cb, q1, q2) {
        const set = msSelected[q1] = msSelected[q1] || new Set();
        if (cb.checked) {
            if (set.size >= 2) { cb.checked = false; return; }
            set.add(cb.value);
        } else {
            set.delete(cb.value);
        }
        document.getElementById('ms-hint-' + q1).textContent =
            set.size === 0 ? '' : set.size + ' of 2 selected';
        const sel = [...set];
        userAnswers[q1] = sel[0] || '';
        userAnswers[q2] = sel[1] || '';
    }

    // ── Part tabs — locked for students ───────────────────
    // Students: switchPart is only ever called programmatically (force=true)
    // when a part's recording ends — direct tab clicks are blocked by the
    // disabled attribute already, this is a second guard against calling it
    // any other way. Admins keep free tab-navigation for previewing content.
    function switchPart(n, btn, force = false) {
        if (!force && !IS_ADMIN && btn && btn.disabled) return;
        document.querySelectorAll('.part-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.part-tab').forEach(t => t.classList.remove('active'));
        document.getElementById('ppanel-' + n).classList.add('active');
        const tab = document.getElementById('ptab-' + n);
        if (tab) tab.classList.add('active');
        playPartAudio(n);
    }

    // ── Audio ──────────────────────────────────────────────
    // No manual play/pause — each part's recording plays automatically and
    // continuously, exactly like the real IELTS test. playWithFallback covers
    // the case where a browser blocks autoplay outright.
    function playWithFallback(mediaEl, containerEl) {
        mediaEl.play().catch(() => {
            let btn = containerEl.querySelector('.tap-to-play-btn');
            if (btn) return;
            btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-success btn-sm tap-to-play-btn ms-2';
            btn.innerHTML = '<i class="bi bi-play-fill me-1"></i>Tap to play';
            btn.onclick = () => { mediaEl.play(); btn.remove(); };
            containerEl.appendChild(btn);
        });
    }
    function playPartAudio(pNum) {
        const audio = document.getElementById('audio-' + pNum);
        const container = document.getElementById('audioBar-' + pNum);
        audio.ontimeupdate = () => {
            document.getElementById('audioTime-' + pNum).textContent = fmtTime(Math.floor(audio.currentTime));
        };
        audio.onended = () => {
            if (IS_ADMIN) return; // admins previewing content aren't forced forward
            if (pNum >= TOTAL_PARTS) return; // last part done — nothing further to load
            switchPart(pNum + 1, document.getElementById('ptab-' + (pNum + 1)), true);
        };
        playWithFallback(audio, container);
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

        // Grade every single-answer question (pair questions are graded below)
        Object.entries(CORRECT).forEach(([q, accepted]) => {
            const num = parseInt(q);
            if (PAIR_QS.has(num)) return;
            const userVal = (userAnswers[num] || '').trim().toLowerCase();
            const correct = accepted.some(a => a.toLowerCase() === userVal);
            markInput(num, correct, accepted[0]);
            const part = getPart(num);
            if (correct && part) partScores[part]++;
        });

        // Grade each "choose TWO" pair as a set — order doesn't matter, and the
        // same letter in both slots can't earn two marks.
        PAIRS.forEach(pr => {
            const sels = pr.q.map(n => (userAnswers[n] || '').toLowerCase()).filter(Boolean);
            const chosen = new Set(sels);
            const correct = new Set(pr.correct);
            let pairScore = 0;
            correct.forEach(c => { if (chosen.has(c)) pairScore++; });
            partScores[getPart(pr.q[0])] += pairScore;
            markMultiSelect(pr.q[0], chosen, correct);
        });

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

    function markMultiSelect(gi, chosen, correct) {
        ['A','B','C','D','E'].forEach(letter => {
            const lbl = document.getElementById('ms-' + gi + '-' + letter);
            if (!lbl) return;
            lbl.querySelector('input').disabled = true;
            const lc = letter.toLowerCase();
            if (correct.has(lc)) lbl.classList.add('correct-answer');
            else if (chosen.has(lc)) lbl.classList.add('wrong-selected');
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

    // Auto-play Part 1's recording as soon as the test loads — students never
    // have to press play themselves, matching the real IELTS test.
    playPartAudio(1);
    </script>
</body>
</html>