<?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

$mockCode = $_GET['code'] ?? 'IELTS_GT_MOCK03';

/* ══════════════════════════════════════════════════════════════
   LISTENING DATA  (Q1–30 = real IELTS sample tasks; Q31–40 = placeholder)
   ══════════════════════════════════════════════════════════════

   Audio file mapping (place these MP3s under /assets/audio/<mockCode>/):
     listening_part1.mp3  →  IELTS Listening Recording 1  (Section 1, Q1–8)
     listening_part2.mp3  →  IELTS Listening Recording 2+3 (Section 2, Q9–16)
     listening_part3.mp3  →  IELTS Listening Recording 3+4 (Section 3, Q21–30)
     listening_part4.mp3  →  Your own Section 4 recording  (Q31–40, placeholder)

   Worksheet images (place under /assets/img/listening/<mockCode>/):
     section1_form.png        – scan / screenshot of the form completion sheet
     section2_shortanswer.png – scan of the short-answer question sheet (Q11–16)
     section3_diagram.png     – the "Operational Cycle" diagram image (Q23–25)
   ══════════════════════════════════════════════════════════════ */
$parts = [
    /* ── SECTION 1 ──────────────────────────────────────────── */
    1 => [
        'title'     => 'Section 1',
        'audio_url' => '/assets/audio/' . $mockCode . '/listening_part1.mp3',
        'preview_s' => 30,
        /* Optional worksheet image shown above the questions */
        'sheet_img' => '/assets/img/listening/' . $mockCode . '/section1_form.png',
        'sections'  => [
            /* Q1–8  Form completion – Packham's Shipping Agency */
            [
                'type'         => 'form_fill',
                'q_range'      => [1, 8],
                'instructions' => 'Complete the form below. Write <strong>NO MORE THAN THREE WORDS AND/OR A NUMBER</strong> for each answer.',
                'form_title'   => "PACKHAM'S SHIPPING AGENCY – Customer Quotation Form",
                'rows'         => [
                    ['label' => 'Country of destination',  'before' => '',        'q' => null, 'after' => 'Kenya'],
                    ['label' => 'Name',                    'before' => 'Jacob ',   'q' => 1,    'after' => ''],
                    ['label' => 'Address to be collected from', 'before' => '',    'q' => 2,    'after' => ' College, Downlands Rd'],
                    ['label' => 'Town',                    'before' => '',        'q' => null, 'after' => 'Bristol'],
                    ['label' => 'Postcode',                'before' => '',        'q' => 3,    'after' => ''],
                    ['label' => 'Container size – Width',  'before' => '',        'q' => 4,    'after' => ''],
                    ['label' => 'Container size – Height', 'before' => '',        'q' => 5,    'after' => ''],
                    ['label' => 'Contents (besides clothes)', 'before' => '',     'q' => 6,    'after' => ''],
                    ['label' => 'Contents (item 2)',       'before' => '',        'q' => 7,    'after' => ''],
                    ['label' => 'Total estimated value',   'before' => '£',       'q' => 8,    'after' => ''],
                ],
            ],
        ],
    ],

    /* ── SECTION 2 ──────────────────────────────────────────── */
    2 => [
        'title'     => 'Section 2',
        'audio_url' => '/assets/audio/' . $mockCode . '/listening_part2.mp3',
        'preview_s' => 30,
        'sheet_img' => '/assets/img/listening/' . $mockCode . '/section2_shortanswer.png',
        'sections'  => [
            /* Q9–10  Multiple choice – Insurance & delivery (Recording 2) */
            [
                'type'         => 'multiple_choice',
                'q_range'      => [9, 10],
                'instructions' => 'Choose the correct letter, <strong>A, B or C</strong>.',
                'questions'    => [
                    ['q' => 9,  'text' => 'Type of insurance chosen', 'options' => ['A' => 'Economy', 'B' => 'Standard', 'C' => 'Premium']],
                    ['q' => 10, 'text' => 'Customer wants goods delivered to', 'options' => ['A' => 'port', 'B' => 'home', 'C' => 'depot']],
                ],
            ],
            /* Q11–16  Short-answer questions – Social contact in the UK (Recording 3) */
            [
                'type'         => 'short_answer_grouped',
                'q_range'      => [11, 16],
                'instructions' => 'Answer the questions below. Write <strong>NO MORE THAN THREE WORDS AND/OR A NUMBER</strong> for each answer.',
                'groups'       => [
                    [
                        'prompt'    => 'What <strong>TWO</strong> factors can make social contact in a foreign country difficult?',
                        'questions' => [
                            ['q' => 11, 'bullet' => '•'],
                            ['q' => 12, 'bullet' => '•'],
                        ],
                    ],
                    [
                        'prompt'    => 'Which types of community group does the speaker give examples of?<br><em>(besides theatre)</em>',
                        'questions' => [
                            ['q' => 13, 'bullet' => '•'],
                            ['q' => 14, 'bullet' => '•'],
                        ],
                    ],
                    [
                        'prompt'    => 'In which <strong>TWO</strong> places can information about community activities be found?',
                        'questions' => [
                            ['q' => 15, 'bullet' => '•'],
                            ['q' => 16, 'bullet' => '•'],
                        ],
                    ],
                ],
            ],
            /* Q17–20  Sample B answer-key items (matching/short answer) */
            [
                'type'         => 'form_fill',
                'q_range'      => [17, 20],
                'instructions' => 'Write <strong>NO MORE THAN THREE WORDS AND/OR A NUMBER</strong> for each answer.',
                'form_title'   => 'Community Arts Centre – Information',
                'rows'         => [
                    ['label' => 'The centre hosts',                  'before' => '',    'q' => 17, 'after' => ' in the main hall'],
                    ['label' => 'Name of the garden venue',          'before' => '',    'q' => 18, 'after' => ''],
                    ['label' => 'Evening ticket price',              'before' => '£',   'q' => 19, 'after' => ''],
                    ['label' => 'Current photography exhibition',    'before' => '',    'q' => 20, 'after' => ''],
                ],
            ],
        ],
    ],

    /* ── SECTION 3 ──────────────────────────────────────────── */
    3 => [
        'title'     => 'Section 3',
        'audio_url' => '/assets/audio/' . $mockCode . '/listening_part3.mp3',
        'preview_s' => 30,
        'sheet_img' => '/assets/img/listening/' . $mockCode . '/section3_diagram.png',
        'sections'  => [
            /* Q21–22  Note completion – Robotic Float */
            [
                'type'         => 'form_fill',
                'q_range'      => [21, 22],
                'instructions' => 'Complete the notes below. Write <strong>NO MORE THAN THREE WORDS AND/OR A NUMBER</strong> for each answer.',
                'form_title'   => 'Understanding the World\'s Oceans – The Robotic Float Project',
                'rows'         => [
                    ['label' => 'Shape of float',                           'before' => 'Like a ',   'q' => 21, 'after' => ''],
                    ['label' => 'Scientists involved (countries)',           'before' => '',           'q' => 22, 'after' => ' have worked on the project so far'],
                ],
            ],
            /* Q23–25  Diagram completion – The Operational Cycle
               NOTE: The diagram image is shown via sheet_img above.
               Students refer to the image; these inputs capture their answers. */
            [
                'type'         => 'form_fill',
                'q_range'      => [23, 25],
                'instructions' => 'Complete the notes on the diagram (see diagram above). Write <strong>NO MORE THAN THREE WORDS AND/OR A NUMBER</strong> for each answer.',
                'form_title'   => 'The Operational Cycle – Diagram Labels',
                'rows'         => [
                    ['label' => 'Float dropped into ocean and (Q23) ___ by satellite', 'before' => '', 'q' => 23, 'after' => ''],
                    ['label' => 'Average distance travelled (Q24)',                     'before' => '', 'q' => 24, 'after' => ''],
                    ['label' => 'Float also records changes in (Q25)',                  'before' => '', 'q' => 25, 'after' => ''],
                ],
            ],
            /* Q26–30  Multiple choice – Float project timelines */
            [
                'type'         => 'multiple_choice',
                'q_range'      => [26, 30],
                'instructions' => 'In what time period can the float projects help with the issues below? Choose <strong>A, B or C</strong>.',
                'legend'       => ['A' => 'At present', 'B' => 'In the near future', 'C' => 'In the long-term future'],
                'questions'    => [
                    ['q' => 26, 'text' => 'El Niño'],
                    ['q' => 27, 'text' => 'Global warming'],
                    ['q' => 28, 'text' => 'Naval rescues'],
                    ['q' => 29, 'text' => 'Sustainable fishing practices'],
                    ['q' => 30, 'text' => 'Crop selection'],
                ],
            ],
        ],
    ],

    /* ── SECTION 4  (placeholder – replace with your own content) ── */
    4 => [
        'title'     => 'Section 4',
        'audio_url' => '/assets/audio/' . $mockCode . '/listening_part4.mp3',
        'preview_s' => 45,
        'sheet_img' => null,
        'sections'  => [
            [
                'type'         => 'form_fill',
                'q_range'      => [31, 40],
                'instructions' => 'Complete the notes below. Write <strong>ONE WORD AND/OR A NUMBER</strong> for each answer.',
                'form_title'   => 'Lecture Notes: Urban Water Management',
                'rows'         => [
                    ['label' => 'Lecture topic',            'before' => 'Managing water in ', 'q' => 31, 'after' => ' environments'],
                    ['label' => 'Primary concern',          'before' => '',                   'q' => 32, 'after' => ' scarcity by 2050'],
                    ['label' => 'Key statistic',            'before' => 'Over ',              'q' => 33, 'after' => '% of water wasted in distribution'],
                    ['label' => 'Solution 1',               'before' => 'Smart ',             'q' => 34, 'after' => ' sensors'],
                    ['label' => 'Solution 2',               'before' => '',                   'q' => 35, 'after' => ' water recycling systems'],
                    ['label' => 'Case study city',          'before' => '',                   'q' => 36, 'after' => ', Australia'],
                    ['label' => 'Result achieved',          'before' => '',                   'q' => 37, 'after' => '% reduction in household usage'],
                    ['label' => 'Main barrier to adoption', 'before' => '',                   'q' => 38, 'after' => ' costs'],
                    ['label' => 'Government role',          'before' => 'Providing ',         'q' => 39, 'after' => ' and subsidies'],
                    ['label' => 'Recommended next step',    'before' => 'International ',     'q' => 40, 'after' => ' agreements'],
                ],
            ],
        ],
    ],
];

$partRanges = [];
foreach ($parts as $pNum => $p) {
    $first = $p['sections'][0]['q_range'][0];
    $last  = array_slice($p['sections'], -1)[0]['q_range'][1];
    $partRanges[$pNum] = [$first, $last];
}

/* ══════════════════════════════════════════════════════════════
   CORRECT ANSWERS – Listening
   Sources:
     Q1–8   : IELTS Listening Recording 1 answer key (form completion)
     Q9–10  : IELTS Listening Recording 2 answer key (multiple choice)
     Q11–16 : IELTS Listening Recording 3 answer key (short answer)
     Q17–20 : Sample Listening B answer key
     Q21–30 : Sample Listening A answer key
     Q31–40 : Placeholder (replace when you add your own Section 4 audio)
   ══════════════════════════════════════════════════════════════ */
$correctAnswers = [
    /* Section 1 – Packham's Shipping Agency */
    1  => 'Mkere',
    2  => 'Westall',
    3  => 'BS8 9PU',
    4  => '0.75m',        // also accept: 0.75 metre(s), ¾ m, 75 cm – JS normalises
    5  => '0.5m',         // also accept: 0.5 metre(s), ½ m, 50 cm
    6  => 'books',        // Q6 & Q7 either order; JS checks individually
    7  => 'toys',
    8  => '1700',

    /* Section 2 – Insurance / Delivery */
    9  => 'C',
    10 => 'A',

    /* Section 2 – Social contact in the UK (Q11–16, either-order pairs handled in JS) */
    11 => 'language',
    12 => 'customs',
    13 => 'music',        // also: music groups
    14 => 'local history', // also: local history groups
    15 => 'library',      // also: public library / libraries / town hall (either order)
    16 => 'town hall',

    /* Section 2 – Community Arts Centre (Sample B Q17–20) */
    17 => 'classical music concerts',
    18 => 'Garden Hall',
    19 => '4.50',
    20 => 'Faces of China',

    /* Section 3 – Robotic Float Q21–22 */
    21 => 'cigar',
    22 => '13',           // also: 13 countries / 13 different countries

    /* Section 3 – Operational Cycle diagram Q23–25 */
    23 => 'activated',
    24 => '50 kilometres', // also: 50 km / around 50 kilometres
    25 => 'temperature',   // also: water temperature / sea temperature / ocean temperature

    /* Section 3 – Float project timelines Q26–30 */
    26 => 'A',
    27 => 'C',
    28 => 'A',
    29 => 'B',
    30 => 'C',

    /* Section 4 – Placeholder answers (replace with real ones) */
    31 => 'urban',
    32 => 'Water',
    33 => '35',
    34 => 'pressure',
    35 => 'Greywater',
    36 => 'Perth',
    37 => '40',
    38 => 'Infrastructure',
    39 => 'incentives',
    40 => 'cooperation',
];

/* ── EITHER-ORDER PAIRS for Listening (JS will accept both orders) ──
   Format: [qA, qB, answerA, answerB]
   If a student puts answerB in qA and answerA in qB, both are marked correct. */
$eitherOrderPairs = [
    [6,  7,  'books',    'toys'],
    [11, 12, 'language', 'customs'],
    [13, 14, 'music',    'local history'],
    [15, 16, 'library',  'town hall'],
];

/* ══════════════════════════════════════════════════════════════
   READING DATA  (unchanged from original – real IELTS GT passages)
   ══════════════════════════════════════════════════════════════ */
$readingAnswers = [
    1=>'D',   2=>'B',   3=>'C',   4=>'E',   5=>'A',   6=>'B',
    7=>'TRUE', 8=>'TRUE', 9=>'NOT GIVEN', 10=>'FALSE', 11=>'FALSE', 12=>'FALSE', 13=>'TRUE', 14=>'NOT GIVEN',
    15=>'fertiliser', 16=>'animal', 17=>'obstacle', 18=>'aids', 19=>'bending', 20=>'gate',
    21=>'proactive', 22=>'special offers', 23=>'brand names', 24=>'negativity', 25=>'presentation', 26=>'credit card', 27=>'rudeness',
    28=>'vi', 29=>'iv', 30=>'ii', 31=>'viii', 32=>'v', 33=>'i', 34=>'iii',
    35=>'D', 36=>'D', 37=>'A',
    38=>'family', 39=>'platform', 40=>'multicoloured',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mock Test | <?= htmlspecialchars($mockCode) ?> | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 include INCLUDES_PATH . '/navbar_styles.php'; ?>

    <style>
        /* ── Screen styles ── */
        .main-wrapper { padding: 1.5rem; }

        .nav-tabs { border-bottom: 2px solid #e5e7eb; }
        .nav-tabs .nav-link { font-weight: 600; color: #6b7280; padding: .85rem 1.5rem; border: none; border-bottom: 3px solid transparent; }
        .nav-tabs .nav-link:hover { color: #667eea; }
        .nav-tabs .nav-link.active { color: #667eea; border-bottom-color: #667eea; background: transparent; }

        .section-content { background: #fff; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,.06); }

        /* ── Audio player ── */
        .audio-box { background: #1a2236; border-radius: 10px; padding: .75rem 1rem; display: flex; align-items: center; gap: .75rem; margin-bottom: 1.75rem; }
        .btn-play { width: 34px; height: 34px; border-radius: 50%; border: none; background: #667eea; color: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; transition: background .2s; }
        .btn-play:hover { background: #764ba2; }
        .progress-wrap { flex: 1; }
        .progress-wrap input[type=range] { width: 100%; accent-color: #667eea; pointer-events: none; display: block; }
        .audio-time { font-size: .7rem; color: rgba(255,255,255,.5); text-align: right; }
        .vol-wrap { display: flex; align-items: center; gap: .4rem; }
        .vol-wrap i { font-size: .9rem; color: rgba(255,255,255,.6); cursor: pointer; }
        .vol-wrap input[type=range] { width: 60px; accent-color: #667eea; }
        .preview-pill { background: #fef3c7; border-radius: 20px; padding: .2rem .75rem; font-size: .75rem; color: #92400e; white-space: nowrap; flex-shrink: 0; }
        .preview-pill.hidden { display: none; }

        /* ── Part tabs ── */
        .part-tabs-bar { display: flex; align-items: center; border-bottom: 1px solid #e5e7eb; margin-bottom: 1.5rem; }
        .part-tabs-scrollable { display: flex; flex: 1; overflow-x: auto; }
        .part-tab-btn { display: flex; flex-direction: column; align-items: center; padding: .5rem 1.1rem; border: none; border-bottom: 3px solid transparent; background: transparent; cursor: pointer; font-size: .82rem; font-weight: 600; color: #6b7280; transition: all .2s; gap: 1px; position: relative; white-space: nowrap; }
        .part-tab-btn:hover { color: #667eea; }
        .part-tab-btn.active { color: #667eea; border-bottom-color: #667eea; }
        .tab-qrange { font-size: .67rem; color: #9ca3af; }
        .part-tab-btn.active .tab-qrange { color: #a5b4fc; }
        .done-dot { position: absolute; top: 5px; right: 6px; width: 7px; height: 7px; border-radius: 50%; background: #10b981; display: none; }
        .part-tab-btn.all-answered .done-dot { display: block; }

        .inline-timer { display: flex; align-items: center; gap: .35rem; font-size: .88rem; font-weight: 700; color: #facc15; background: #1a2236; border-radius: 8px; padding: .3rem .85rem; margin-left: auto; flex-shrink: 0; white-space: nowrap; }
        .inline-timer.warning { color: #ef4444; animation: blink 1s infinite; }
        @keyframes blink { 0%,100%{opacity:1}50%{opacity:.4} }

        .test-header-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; }
        .test-meta { display: flex; align-items: center; gap: .5rem; }
        .test-meta-tag { font-size: .72rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: #9ca3af; }
        .test-meta-name { font-size: .9rem; font-weight: 700; color: #1a2236; }
        .btn-exit { background: #f3f4f6; border: 1.5px solid #e5e7eb; color: #374151; border-radius: 6px; padding: .28rem .85rem; font-size: .8rem; font-weight: 600; cursor: pointer; transition: all .2s; }
        .btn-exit:hover { background: #ef4444; border-color: #ef4444; color: #fff; }
        .nav-link.tab-locked { opacity: 0.4; cursor: not-allowed !important; pointer-events: none; }
        .nav-link.tab-locked::after { content: ' 🔒'; font-size: .75em; }

        .part-panel { display: none; }
        .part-panel.active { display: block; }
        .r-panel { display: none; }
        .r-panel.active { display: block; }

        .audio-notice { background: #eff6ff; border-left: 4px solid #3b82f6; border-radius: 0 8px 8px 0; padding: .65rem 1rem; font-size: .84rem; color: #1e40af; margin-bottom: 1.5rem; line-height: 1.5; }

        /* ── Worksheet image ── */
        .worksheet-img-wrap { margin-bottom: 1.75rem; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; background: #f9fafb; }
        .worksheet-img-wrap img { width: 100%; height: auto; display: block; }
        .worksheet-img-caption { font-size: .75rem; color: #9ca3af; text-align: center; padding: .4rem .75rem; background: #f3f4f6; border-top: 1px solid #e5e7eb; }

        /* ── Question blocks ── */
        .section-block { margin-bottom: 2.5rem; }
        .q-range-label { font-size: .95rem; font-weight: 700; color: #111827; margin-bottom: .2rem; }
        .q-instructions { font-size: .88rem; color: #374151; margin-bottom: 1.2rem; line-height: 1.55; }

        /* Form fill */
        .ff-title { text-align: center; font-weight: 600; font-size: .9rem; color: #374151; margin-bottom: .5rem; }
        .ff-table { width: 100%; border-collapse: collapse; font-size: .875rem; }
        .ff-table td { border: 1px solid #d1d5db; padding: .6rem .85rem; vertical-align: middle; }
        .ff-table td:first-child { background: #f9fafb; font-weight: 500; width: 38%; color: #374151; }
        .q-badge { display: inline-flex; align-items: center; justify-content: center; background: #667eea; color: #fff; font-size: .68rem; font-weight: 700; border-radius: 4px; min-width: 20px; height: 18px; padding: 0 4px; margin-right: 4px; vertical-align: middle; }
        .ff-input { border: none; border-bottom: 2px solid #c4b5fd; outline: none; width: 155px; font-size: .87rem; padding: 2px 4px; background: transparent; color: #111827; transition: border-color .2s; }
        .ff-input:focus { border-bottom-color: #667eea; }
        .ff-input.answered { border-bottom-color: #10b981; }

        /* Short-answer grouped */
        .sa-group { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: .9rem 1rem; margin-bottom: 1rem; }
        .sa-group-prompt { font-size: .85rem; color: #374151; margin-bottom: .65rem; line-height: 1.5; }
        .sa-item { display: flex; align-items: center; gap: .6rem; margin-bottom: .4rem; font-size: .87rem; }
        .sa-bullet { color: #667eea; font-weight: 700; min-width: 14px; }

        /* Multiple choice */
        .mc-legend { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: .75rem 1rem; margin-bottom: 1rem; font-size: .84rem; color: #374151; display: flex; flex-direction: column; gap: .25rem; }
        .mc-legend-row { display: flex; gap: .5rem; }
        .mc-legend-key { font-weight: 700; color: #667eea; min-width: 18px; }
        .mc-question { margin-bottom: 1.1rem; }
        .mc-q-label { font-size: .88rem; font-weight: 600; color: #111827; margin-bottom: .4rem; }
        .mc-option { display: flex; align-items: flex-start; gap: .5rem; padding: .38rem .65rem; border-radius: 7px; cursor: pointer; font-size: .85rem; color: #374151; transition: background .15s; margin-bottom: .15rem; line-height: 1.4; }
        .mc-option:hover { background: #f3f4f6; }
        .mc-option input { accent-color: #667eea; margin-top: 3px; flex-shrink: 0; }

        /* Matching */
        .match-legend { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: .75rem 1rem; margin-bottom: 1rem; font-size: .84rem; color: #374151; display: grid; grid-template-columns: 1fr 1fr; gap: .25rem 1.5rem; }
        .match-legend-row { display: flex; gap: .5rem; }
        .match-legend-key { font-weight: 700; color: #667eea; min-width: 18px; }
        .match-q { display: flex; align-items: center; gap: .75rem; margin-bottom: .6rem; font-size: .87rem; color: #374151; }
        .match-q-text { flex: 1; }
        .match-select { border: 1.5px solid #d1d5db; border-radius: 7px; padding: .28rem .6rem; font-size: .85rem; outline: none; color: #111827; cursor: pointer; transition: border-color .2s; min-width: 68px; }
        .match-select:focus { border-color: #667eea; }
        .match-select.answered { border-color: #10b981; color: #059669; }

        /* Progress mini-bar */
        .part-minibar { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: .6rem 1rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-top: 2rem; }
        .minibar-inner { display: flex; align-items: center; gap: .65rem; flex-wrap: wrap; }
        .minibar-tag { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #9ca3af; }
        .q-bubbles { display: flex; flex-wrap: wrap; gap: 4px; }
        .q-bubble { width: 25px; height: 25px; border-radius: 50%; border: 1.5px solid #d1d5db; font-size: .67rem; font-weight: 700; display: flex; align-items: center; justify-content: center; color: #9ca3af; background: #fff; transition: all .2s; }
        .q-bubble.answered { background: #10b981; border-color: #10b981; color: #fff; }
        .minibar-count { white-space: nowrap; font-size: .82rem; font-weight: 700; color: #374151; }
        .minibar-count em { font-style: normal; color: #10b981; }

        /* ── Reading ── */
        .reading-split { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: start; }
        @media(max-width:900px){ .reading-split { grid-template-columns: 1fr; } }
        .reading-passage { background: #fafafa; border: 1px solid #e5e7eb; border-radius: 10px; padding: 1.5rem 1.75rem; font-size: .87rem; line-height: 1.75; color: #1f2937; position: sticky; top: 20px; max-height: calc(100vh - 60px); overflow-y: auto; }
        .reading-passage h4 { font-size: 1rem; font-weight: 700; color: #111827; text-align: center; margin-bottom: 1rem; }
        .para-chip { display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; background: #667eea; color: #fff; font-size: .72rem; font-weight: 700; margin-right: .3rem; flex-shrink: 0; vertical-align: middle; }
        .reading-questions { min-width: 0; }
        .r-section-hdr { background: #1a2236; color: #fff; border-radius: 8px; padding: .5rem 1rem; font-size: .78rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; margin-bottom: 1.25rem; margin-top: 2rem; }
        .r-section-hdr:first-child { margin-top: 0; }
        .r-tabs { display: flex; border-bottom: 2px solid #e5e7eb; margin-bottom: 1.5rem; align-items: center; }
        .r-tab-btn { flex: 1; padding: .6rem; border: none; background: transparent; font-size: .8rem; font-weight: 700; color: #6b7280; cursor: pointer; border-bottom: 3px solid transparent; transition: all .2s; display: flex; flex-direction: column; align-items: center; gap: 2px; }
        .r-tab-btn:hover { color: #667eea; }
        .r-tab-btn.active { color: #667eea; border-bottom-color: #667eea; }
        .r-tab-qrange { font-size: .67rem; color: #9ca3af; font-weight: 600; }
        .r-tab-btn.active .r-tab-qrange { color: #a5b4fc; }
        .tfng-q { margin-bottom: 1rem; }
        .tfng-q-label { font-size: .88rem; font-weight: 600; color: #111827; margin-bottom: .35rem; }
        .tfng-options { display: flex; gap: .5rem; flex-wrap: wrap; }
        .tfng-btn { padding: .28rem .75rem; border-radius: 6px; border: 1.5px solid #d1d5db; background: #fff; font-size: .78rem; font-weight: 700; cursor: pointer; color: #6b7280; transition: all .18s; }
        .tfng-btn:hover { border-color: #667eea; color: #667eea; }
        .tfng-btn.selected { background: #667eea; border-color: #667eea; color: #fff; }
        .r-match-q { display: flex; align-items: center; gap: .75rem; margin-bottom: .6rem; font-size: .87rem; color: #374151; }
        .r-match-q-text { flex: 1; }
        .heading-list { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: .75rem 1rem; margin-bottom: 1.1rem; font-size: .84rem; color: #374151; }
        .heading-list table { width: 100%; border-collapse: collapse; }
        .heading-list td { padding: .25rem .4rem; vertical-align: top; }
        .heading-list td:first-child { font-weight: 700; color: #667eea; white-space: nowrap; width: 28px; }
        .sc-q { display: flex; align-items: baseline; flex-wrap: wrap; gap: .3rem; margin-bottom: .7rem; font-size: .87rem; color: #374151; line-height: 1.7; }
        .summary-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 1.25rem 1.5rem; font-size: .87rem; color: #374151; line-height: 1.8; }
        .summary-box h5 { font-size: .9rem; font-weight: 700; text-align: center; margin-bottom: .75rem; color: #111827; }
        .r-minibar { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: .6rem 1rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-top: 2rem; }
        .r-minibar-inner { display: flex; align-items: center; gap: .65rem; flex-wrap: wrap; }
        .btn-submit-section { background: linear-gradient(135deg,#667eea,#764ba2); color: #fff; border: none; border-radius: 8px; padding: .65rem 2rem; font-size: .95rem; font-weight: 700; cursor: pointer; transition: opacity .2s; }
        .btn-submit-section:hover { opacity: .88; }

        /* ── Writing ── */
        .writing-task-card { border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; margin-bottom: 2rem; }
        .writing-task-header { background: #1a2236; color: #fff; padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between; }
        .writing-task-header h5 { margin: 0; font-size: 1rem; font-weight: 700; }
        .writing-task-header .task-meta { font-size: .78rem; color: rgba(255,255,255,.6); }
        .writing-task-body { padding: 1.5rem; }
        .writing-prompt-box { background: #f0f4ff; border: 1.5px solid #c7d2fe; border-radius: 8px; padding: 1.25rem 1.5rem; margin-bottom: 1.25rem; font-size: .9rem; color: #1e40af; line-height: 1.7; font-style: italic; }
        .writing-prompt-box strong { color: #1a2236; font-style: normal; }
        .writing-bullets { margin: .75rem 0 0 0; padding-left: 1.25rem; }
        .writing-bullets li { margin-bottom: .35rem; font-size: .88rem; color: #374151; }
        .writing-meta-row { display: flex; gap: 1.25rem; margin-bottom: 1rem; flex-wrap: wrap; }
        .writing-meta-pill { background: #f3f4f6; border-radius: 20px; padding: .25rem .85rem; font-size: .78rem; font-weight: 600; color: #374151; display: flex; align-items: center; gap: .35rem; }
        .writing-meta-pill i { color: #667eea; }
        .writing-textarea { width: 100%; min-height: 220px; border: 1.5px solid #d1d5db; border-radius: 10px; padding: 1rem; font-size: .9rem; line-height: 1.7; color: #111827; resize: vertical; outline: none; transition: border-color .2s; font-family: inherit; }
        .writing-textarea:focus { border-color: #667eea; }
        .writing-wordcount { text-align: right; font-size: .78rem; color: #9ca3af; margin-top: .4rem; }
        .writing-wordcount.ok { color: #10b981; }
        .writing-wordcount.warn { color: #f59e0b; }
        .writing-begin-note { background: #fef9c3; border-left: 4px solid #f59e0b; border-radius: 0 8px 8px 0; padding: .55rem 1rem; font-size: .84rem; color: #78350f; margin-bottom: .75rem; }

        /* ── Submit tab ── */
        .submit-hero { text-align: center; padding: 2.5rem 1rem 2rem; }
        .submit-hero h2 { font-size: 1.5rem; font-weight: 700; color: #1a2236; margin-bottom: .5rem; }
        .submit-hero p { color: #6b7280; font-size: .92rem; margin-bottom: 2rem; }
        .submit-checklist { display: flex; flex-direction: column; gap: .75rem; max-width: 420px; margin: 0 auto 2.5rem; text-align: left; }
        .submit-check-item { display: flex; align-items: center; gap: .75rem; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: .65rem 1rem; font-size: .88rem; color: #374151; }
        .submit-check-item i { font-size: 1.1rem; color: #9ca3af; }
        .submit-check-item.done i { color: #10b981; }
        .submit-check-item .check-label { flex: 1; }
        .submit-check-item .check-count { font-size: .78rem; color: #9ca3af; }
        .submit-check-item.done .check-count { color: #10b981; }
        .btn-final-submit { background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; border: none; border-radius: 10px; padding: .9rem 3rem; font-size: 1.05rem; font-weight: 700; cursor: pointer; transition: opacity .2s; display: inline-flex; align-items: center; gap: .6rem; }
        .btn-final-submit:hover { opacity: .9; }

        /* ══ Results sheet ══ */
        #resultsSheet { display: none; }
        #resultsSheet.show { display: block; }
        .sheet-header { background: #1a2236; color: #fff; border-radius: 12px; padding: 1.5rem 2rem; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
        .sheet-header-left h2 { margin: 0; font-size: 1.3rem; font-weight: 700; }
        .sheet-header-left p { margin: .25rem 0 0; font-size: .85rem; color: rgba(255,255,255,.6); }
        .btn-print { background: #fff; color: #1a2236; border: none; border-radius: 8px; padding: .55rem 1.25rem; font-size: .88rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: .5rem; transition: opacity .2s; }
        .btn-print:hover { opacity: .85; }
        .sheet-section { margin-bottom: 2rem; }
        .sheet-section-title { font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: #9ca3af; margin-bottom: .75rem; padding-bottom: .4rem; border-bottom: 1px solid #e5e7eb; }
        .score-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.25rem; }
        .score-card { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 1rem 1.25rem; }
        .score-card-label { font-size: .75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .08em; color: #9ca3af; margin-bottom: .25rem; }
        .score-card-value { font-size: 1.75rem; font-weight: 700; color: #667eea; line-height: 1; }
        .score-card-band { font-size: .82rem; color: #6b7280; margin-top: .2rem; }
        .breakdown-table { width: 100%; border-collapse: collapse; font-size: .85rem; }
        .breakdown-table th { background: #f3f4f6; padding: .45rem .75rem; text-align: left; font-weight: 600; color: #374151; border: 1px solid #e5e7eb; }
        .breakdown-table td { padding: .45rem .75rem; border: 1px solid #e5e7eb; color: #374151; }
        .breakdown-table .ok  { color: #10b981; font-weight: 600; }
        .breakdown-table .bad { color: #ef4444; }
        .writing-response-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 1.25rem 1.5rem; font-size: .9rem; line-height: 1.75; color: #1f2937; white-space: pre-wrap; min-height: 80px; }
        .writing-response-box.empty { color: #9ca3af; font-style: italic; }
        .wc-pill { display: inline-block; background: #e0e7ff; color: #3730a3; border-radius: 20px; padding: .15rem .65rem; font-size: .75rem; font-weight: 600; margin-bottom: .5rem; }
        .speaking-card { border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; margin-bottom: 1rem; }
        .speaking-card-hdr { background: #1a2236; color: #fff; padding: .65rem 1rem; font-size: .85rem; font-weight: 700; display: flex; align-items: center; gap: .5rem; }
        .speaking-card-body { padding: 1rem 1.25rem; font-size: .87rem; color: #374151; line-height: 1.6; }
        .speaking-cue { background: #f0f4ff; border: 1.5px solid #c7d2fe; border-radius: 8px; padding: 1rem 1.25rem; margin-bottom: .75rem; }
        .speaking-cue strong { color: #1a2236; display: block; margin-bottom: .5rem; }
        .speaking-cue ul { margin: 0; padding-left: 1.1rem; }
        .speaking-cue li { margin-bottom: .2rem; }
        .speaking-qs { list-style: none; padding: 0; margin: 0; }
        .speaking-qs li { padding: .4rem .75rem; border-left: 3px solid #c7d2fe; margin-bottom: .4rem; background: #f9fafb; border-radius: 0 6px 6px 0; }
        .speaking-topic-hdr { font-size: .82rem; font-weight: 700; color: #667eea; margin: .75rem 0 .4rem; }
        .grading-box { border: 2px dashed #d1d5db; border-radius: 10px; padding: 1.25rem 1.5rem; }
        .grading-row { display: flex; align-items: center; gap: 1rem; margin-bottom: .75rem; }
        .grading-row:last-child { margin-bottom: 0; }
        .grading-label { font-size: .85rem; font-weight: 600; color: #374151; min-width: 160px; }
        .grading-line { flex: 1; border-bottom: 1.5px solid #d1d5db; }
        .grading-score-box { width: 50px; height: 34px; border: 1.5px solid #d1d5db; border-radius: 6px; }

        /* ── Source badge (shows which IELTS recording each section uses) ── */
        .recording-badge { display: inline-flex; align-items: center; gap: .35rem; background: #e0e7ff; color: #3730a3; border-radius: 20px; padding: .2rem .75rem; font-size: .72rem; font-weight: 700; margin-bottom: 1rem; }
        .recording-badge i { font-size: .8rem; }

        /* ── Print styles ── */
        @media print {
            body * { visibility: hidden; }
            #resultsSheet, #resultsSheet * { visibility: visible; }
            #resultsSheet { position: absolute; inset: 0; display: block !important; }
            .btn-print, .no-print { display: none !important; }
            .sheet-header { background: #1a2236 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .r-section-hdr, .speaking-card-hdr { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .score-card { break-inside: avoid; }
            .speaking-card { break-inside: avoid; }
        }
    </style>
</head>
<body>
    <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 include INCLUDES_PATH . '/navbar.php'; ?>

    <main class="main-wrapper">

        <div class="test-header-row">
            <div class="test-meta">
                <span class="test-meta-tag">Mock Test</span>
                <span class="test-meta-name"><?= htmlspecialchars($mockCode) ?></span>
            </div>
            <button class="btn-exit" onclick="confirmExit()"><i class="bi bi-box-arrow-right me-1"></i> Exit</button>
        </div>

        <!-- Main section tabs -->
        <ul class="nav nav-tabs mb-4" id="mainTabs">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-listening"><i class="bi bi-headphones me-1"></i> Listening</a></li>
            <li class="nav-item"><a class="nav-link tab-locked" data-bs-toggle="tab" href="#tab-reading"><i class="bi bi-book me-1"></i> Reading</a></li>
            <li class="nav-item"><a class="nav-link tab-locked" data-bs-toggle="tab" href="#tab-writing"><i class="bi bi-pencil me-1"></i> Writing</a></li>
            <li class="nav-item"><a class="nav-link tab-locked" data-bs-toggle="tab" href="#tab-submit"><i class="bi bi-send-check me-1"></i> Submit</a></li>
        </ul>

        <div class="tab-content">

            <!-- ══════════════════════════════════════════
                 LISTENING
                 ══════════════════════════════════════════ -->
            <div class="tab-pane fade show active" id="tab-listening">
                <div class="section-content">

                    <!-- Audio player -->
                    <div class="audio-box">
                        <button class="btn-play" onclick="togglePlay()"><i class="bi bi-play-fill" id="playIcon"></i></button>
                        <div class="progress-wrap">
                            <input type="range" id="audioBar" min="0" max="100" value="0">
                            <div class="audio-time" id="audioTime">0:00 / 0:00</div>
                        </div>
                        <div class="vol-wrap">
                            <i class="bi bi-volume-up" id="volIcon" onclick="toggleMute()"></i>
                            <input type="range" min="0" max="1" step="0.05" value="1" oninput="setVol(this.value)">
                        </div>
                        <div class="preview-pill hidden" id="previewPill"><i class="bi bi-eye me-1"></i>Preview: <strong id="previewNum">30</strong>s</div>
                    </div>

                    <!-- Section tab bar -->
                    <div class="part-tabs-bar">
                        <div class="part-tabs-scrollable">
                            <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 foreach ($parts as $pNum => $p): [$f, $l] = $partRanges[$pNum]; ?>
                            <button class="part-tab-btn <?= $pNum === 1 ? 'active' : '' ?>"
                                    id="ptab-<?= $pNum ?>"
                                    onclick="switchPart(<?= $pNum ?>, this)">
                                <span class="done-dot"></span>
                                <?= htmlspecialchars($p['title']) ?>
                                <span class="tab-qrange">Q<?= $f ?>–<?= $l ?></span>
                            </button>
                            <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endforeach; ?>
                        </div>
                        <div class="inline-timer" id="listeningTimerEl"><i class="bi bi-clock-fill"></i> 40:00</div>
                    </div>

                    <!-- ── Section panels ── -->
                    <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 foreach ($parts as $pNum => $p): ?>
                    <div class="part-panel <?= $pNum === 1 ? 'active' : '' ?>" id="panel-<?= $pNum ?>">

                        <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03
                        /* Recording badge mapping */
                        $recordingLabel = [
                            1 => 'Recording 1 – Telephone conversation (shipping agency)',
                            2 => 'Recording 2 + 3 – Insurance conversation & UK social life talk',
                            3 => 'Recording 3 + 4 – University seminar & Open University discussion',
                            4 => 'Section 4 Recording – Add your own audio',
                        ];
                        ?>
                        <div class="recording-badge">
                            <i class="bi bi-music-note-beamed"></i>
                            <?= htmlspecialchars($recordingLabel[$pNum] ?? '') ?>
                        </div>

                        <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 /* Worksheet image (if configured) */
                        if (!empty($p['sheet_img'])): ?>
                        <div class="worksheet-img-wrap">
                            <img src="<?= htmlspecialchars($p['sheet_img']) ?>"
                                 alt="<?= htmlspecialchars($p['title']) ?> worksheet"
                                 onerror="this.closest('.worksheet-img-wrap').style.display='none'">
                            <div class="worksheet-img-caption">
                                <i class="bi bi-file-earmark-image me-1"></i>
                                Official worksheet / diagram for <?= htmlspecialchars($p['title']) ?> —
                                refer to this while listening.
                            </div>
                        </div>
                        <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endif; ?>

                        <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 foreach ($p['sections'] as $si => $sec): ?>
                        <div class="section-block">

                            <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 if ($si === 0 && !empty($p['preview_s'])): ?>
                            <div class="audio-notice">
                                <i class="bi bi-info-circle-fill me-1"></i>
                                After the instruction audio you will have
                                <strong><?= $p['preview_s'] ?> seconds</strong>
                                to look at questions
                                <?= $sec['q_range'][0] ?>–<?= array_slice($p['sections'], -1)[0]['q_range'][1] ?>.
                            </div>
                            <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endif; ?>

                            <div class="q-range-label">Questions <?= $sec['q_range'][0] ?>–<?= $sec['q_range'][1] ?></div>
                            <div class="q-instructions"><?= $sec['instructions'] ?></div>

                            <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 /* ── FORM FILL ── */
                            if ($sec['type'] === 'form_fill'): ?>
                                <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 if (!empty($sec['form_title'])): ?>
                                <div class="ff-title"><?= htmlspecialchars($sec['form_title']) ?></div>
                                <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endif; ?>
                                <table class="ff-table">
                                    <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 foreach ($sec['rows'] as $row): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['label']) ?></td>
                                        <td>
                                        <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 if ($row['q'] === null): ?>
                                            <?= htmlspecialchars($row['after']) ?>
                                        <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 else: ?>
                                            <?= htmlspecialchars($row['before']) ?>
                                            <span class="q-badge"><?= $row['q'] ?></span>
                                            <input type="text"
                                                   class="ff-input"
                                                   data-q="<?= $row['q'] ?>"
                                                   data-part="<?= $pNum ?>"
                                                   autocomplete="off"
                                                   placeholder="…"
                                                   oninput="handleInput(<?= $row['q'] ?>, this.value, <?= $pNum ?>)">
                                            <?= htmlspecialchars($row['after']) ?>
                                        <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endif; ?>
                                        </td>
                                    </tr>
                                    <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endforeach; ?>
                                </table>

                            <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 /* ── MULTIPLE CHOICE ── */
                            elseif ($sec['type'] === 'multiple_choice'): ?>
                                <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 if (!empty($sec['legend'])): ?>
                                <div class="mc-legend">
                                    <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 foreach ($sec['legend'] as $letter => $desc): ?>
                                    <div class="mc-legend-row">
                                        <span class="mc-legend-key"><?= $letter ?></span>
                                        <span><?= htmlspecialchars($desc) ?></span>
                                    </div>
                                    <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endforeach; ?>
                                </div>
                                <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endif; ?>

                                <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 foreach ($sec['questions'] as $mcq):
                                    $opts = $sec['legend'] ?? ($mcq['options'] ?? []); ?>
                                <div class="mc-question">
                                    <div class="mc-q-label">
                                        <span class="q-badge"><?= $mcq['q'] ?></span>
                                        <?= htmlspecialchars($mcq['text']) ?>
                                    </div>
                                    <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 foreach ($opts as $letter => $optText): ?>
                                    <label class="mc-option">
                                        <input type="radio"
                                               name="q<?= $mcq['q'] ?>"
                                               value="<?= $letter ?>"
                                               onchange="handleInput(<?= $mcq['q'] ?>, '<?= $letter ?>', <?= $pNum ?>)">
                                        <span><strong><?= $letter ?>.</strong> <?= htmlspecialchars($optText) ?></span>
                                    </label>
                                    <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endforeach; ?>
                                </div>
                                <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endforeach; ?>

                            <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 /* ── SHORT ANSWER GROUPED ── */
                            elseif ($sec['type'] === 'short_answer_grouped'): ?>
                                <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 foreach ($sec['groups'] as $group): ?>
                                <div class="sa-group">
                                    <div class="sa-group-prompt"><?= $group['prompt'] ?></div>
                                    <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 foreach ($group['questions'] as $sq): ?>
                                    <div class="sa-item">
                                        <span class="sa-bullet"><?= $sq['bullet'] ?></span>
                                        <span class="q-badge"><?= $sq['q'] ?></span>
                                        <input type="text"
                                               class="ff-input"
                                               data-q="<?= $sq['q'] ?>"
                                               data-part="<?= $pNum ?>"
                                               autocomplete="off"
                                               placeholder="…"
                                               style="width:200px;"
                                               oninput="handleInput(<?= $sq['q'] ?>, this.value, <?= $pNum ?>)">
                                    </div>
                                    <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endforeach; ?>
                                </div>
                                <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endforeach; ?>

                            <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endif; ?>
                        </div><!-- /.section-block -->
                        <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endforeach; ?>

                        <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 [$pFirst, $pLast] = $partRanges[$pNum]; $pTotal = $pLast - $pFirst + 1; ?>
                        <div class="part-minibar">
                            <div class="minibar-inner">
                                <span class="minibar-tag">Progress</span>
                                <div class="q-bubbles">
                                    <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 for ($qi = $pFirst; $qi <= $pLast; $qi++): ?>
                                    <div class="q-bubble" id="bubble-<?= $qi ?>"><?= $qi ?></div>
                                    <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endfor; ?>
                                </div>
                            </div>
                            <div class="minibar-count">
                                <em id="acount-<?= $pNum ?>">0</em>/<?= $pTotal ?> answered
                            </div>
                        </div>

                    </div><!-- /.part-panel -->
                    <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endforeach; ?>

                    <div class="d-flex gap-3 mt-4">
                        <button onclick="finishSection('listening')" class="btn btn-success btn-lg">
                            Finish Listening &amp; Continue →
                        </button>
                    </div>
                </div>
            </div><!-- /#tab-listening -->

            <!-- ══════════════════════════════════════════
                 READING  (unchanged – real IELTS GT passages)
                 ══════════════════════════════════════════ -->
            <div class="tab-pane fade" id="tab-reading">
                <div class="section-content">
                    <div class="audio-notice" style="margin-bottom:1.5rem;">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        You have <strong>60 minutes</strong> to complete the Reading section (40 questions total).
                    </div>

                    <div class="r-tabs">
                        <button class="r-tab-btn active" id="rtab-1" onclick="switchRSection(1, this)">
                            Section 1<span class="r-tab-qrange">Q1–14</span>
                        </button>
                        <button class="r-tab-btn" id="rtab-2" onclick="switchRSection(2, this)">
                            Section 2<span class="r-tab-qrange">Q15–27</span>
                        </button>
                        <button class="r-tab-btn" id="rtab-3" onclick="switchRSection(3, this)">
                            Section 3<span class="r-tab-qrange">Q28–40</span>
                        </button>
                        <div class="inline-timer" id="readingTimerEl"><i class="bi bi-clock-fill"></i> 60:00</div>
                    </div>

                    <!-- Reading Section 1 -->
                    <div class="r-panel active" id="rpanel-1">
                        <div class="reading-split">
                            <div class="reading-passage">
                                <h4>Helping pupils choose optional subjects</h4>
                                <p><span class="para-chip">A</span><strong>Krishnan</strong><br>I'm studying Spanish, because it's important to learn foreign languages. Mr Peckham really pushes us, and offers us extra assignments, to help us improve. That's good for me, because otherwise I'd be quite lazy.</p>
                                <p><span class="para-chip">B</span><strong>Lucy</strong><br>History is my favourite subject. It's made me understand much more about politics. My plan is to study history at university, and maybe go into the diplomatic service, so I can apply a knowledge of history.</p>
                                <p><span class="para-chip">C</span><strong>Mark</strong><br>Thursdays are my favourite days — that's when we have computing. I love learning how to program. I began when I was about eight, so when I started doing it at school, I didn't think I'd have any problem with it, but I was quite wrong! When I leave school, I'm going into my family retail business.</p>
                                <p><span class="para-chip">D</span><strong>Violeta</strong><br>My parents both work in leisure and tourism. I find it fascinating. I'm studying it at school, and the teacher is very knowledgeable, though I think we spend too much time listening to her: I'd like to meet more people working in the sector.</p>
                                <p><span class="para-chip">E</span><strong>Walid</strong><br>I've always been keen on art. I was afraid the lessons might be a bit dull. I needn't have worried — our teacher gets us to do lots of fun things, so there's no risk of getting bored. At the end of the year the class puts on an exhibition for the school.</p>
                                <hr style="margin:1.25rem 0;border-color:#e5e7eb;">
                                <h4>It's almost time for the next Ripton Festival!</h4>
                                <p>The festival will be held in the last weekend of June, 27–29 June. The theme is Ripton through the ages.</p>
                                <p>The Craft Fair is a regular part of the festival. Professional artists and craftsmen will display their jewellery, paintings, ceramics, and much more. They'll also take orders.</p>
                                <p>The Saturday barbecue starts at 2 pm until 10 pm. The barbecue will be held in Palmer's Field, or in the town hall if there's rain. Entry for under 16s is free all day; adults can come for free until 6 pm and pay £5 after that. There'll be live music with local amateur bands in the afternoon and professional musicians in the evening.</p>
                                <p>On Sunday there will be an afternoon of boat races. The spectator area by the bridge has plenty of room to stand and cheer, in addition to a number of benches.</p>
                                <p>All money raised will go to support the sports clubs in Ripton.</p>
                            </div>
                            <div class="reading-questions">
                                <div class="r-section-hdr">Questions 1–6</div>
                                <p style="font-size:.85rem;color:#374151;margin-bottom:1rem;">For which comments are the following statements true? Write the correct letter <strong>A–E</strong>.</p>
                                <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03
                                $s1q1 = [
                                    1 => 'This pupil is interested in the subject despite the way it is taught.',
                                    2 => 'This pupil is hoping to have a career that makes use of the subject.',
                                    3 => 'This pupil finds the subject harder than they expected.',
                                    4 => 'This pupil finds the lessons very entertaining.',
                                    5 => 'This pupil appreciates the benefit of doing challenging work.',
                                    6 => 'This pupil has realised the connection between two things.',
                                ];
                                foreach ($s1q1 as $qn => $qt): ?>
                                <div class="r-match-q">
                                    <span class="q-badge"><?= $qn ?></span>
                                    <span class="r-match-q-text"><?= htmlspecialchars($qt) ?></span>
                                    <select class="match-select" id="rsel-<?= $qn ?>" onchange="readingHandleInput(<?= $qn ?>, this.value)">
                                        <option value="">—</option>
                                        <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 foreach (['A','B','C','D','E'] as $l): ?>
                                        <option value="<?= $l ?>"><?= $l ?></option>
                                        <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endforeach; ?>
                                    </select>
                                </div>
                                <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endforeach; ?>

                                <div class="r-section-hdr" style="margin-top:2rem;">Questions 7–14</div>
                                <p style="font-size:.85rem;color:#374151;margin-bottom:.75rem;">Write <strong>TRUE</strong>, <strong>FALSE</strong> or <strong>NOT GIVEN</strong>.</p>
                                <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03
                                $s1q2 = [
                                    7  => 'The festival is held every year.',
                                    8  => "This year's festival focuses on the town's history.",
                                    9  => 'Goods displayed in the craft fair are unlike ones found in shops.',
                                    10 => 'The barbecue will be cancelled if it rains.',
                                    11 => 'Adults can attend the barbecue at any time without charge.',
                                    12 => 'Amateur musicians will perform during the whole of the barbecue.',
                                    13 => 'Seating is available for watching the boat races.',
                                    14 => 'People attending the festival will be asked to donate some money.',
                                ];
                                foreach ($s1q2 as $qn => $qt): ?>
                                <div class="tfng-q">
                                    <div class="tfng-q-label">
                                        <span class="q-badge"><?= $qn ?></span><?= htmlspecialchars($qt) ?>
                                    </div>
                                    <div class="tfng-options">
                                        <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 foreach (['TRUE','FALSE','NOT GIVEN'] as $opt): ?>
                                        <button class="tfng-btn" data-q="<?= $qn ?>" onclick="tfngSelect(<?= $qn ?>,'<?= $opt ?>',this)"><?= $opt ?></button>
                                        <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endforeach; ?>
                                    </div>
                                </div>
                                <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endforeach; ?>

                                <div class="r-minibar">
                                    <div class="r-minibar-inner">
                                        <span class="minibar-tag">Progress</span>
                                        <div class="q-bubbles">
                                            <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 for ($qi=1; $qi<=14; $qi++): ?>
                                            <div class="q-bubble" id="rbubble-<?= $qi ?>"><?= $qi ?></div>
                                            <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endfor; ?>
                                        </div>
                                    </div>
                                    <div class="minibar-count"><em id="racount-1">0</em>/14 answered</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reading Section 2 -->
                    <div class="r-panel" id="rpanel-2">
                        <div class="reading-split">
                            <div class="reading-passage">
                                <h4>Reducing injuries on the farm</h4>
                                <p>The first risk relates to carrying an excessive load. Examples include moving 50kg fertiliser bags or carrying buckets of animal feed. Smaller containers or a tractor should be used instead. The risk is made worse if the person is also bending over.</p>
                                <p>If a load is bulky or hard to grasp, such as a lively animal, the holder may adopt an awkward posture. Sometimes a load must be held away from the body because there is a large obstacle in the area. In such cases, handling aids should be purchased.</p>
                                <p>Another risk is repetitive bending — for example, repairing a gate that has collapsed onto the ground. The farmer should think about whether the job can be performed on a workbench.</p>
                                <hr style="margin:1.25rem 0;border-color:#e5e7eb;">
                                <h4>Good customer service in retail</h4>
                                <p>Be proactive in offering help without being annoying. Suggest products for which there are special offers, but don't pressure a customer.</p>
                                <p>Build up knowledge of all the products in your shop, including products produced under a range of brand names. Negativity can put customers off — if the answer is 'no', follow it with a positive.</p>
                                <p>Keep an eye on the presentation of goods on shelves. If a customer's credit card is declined, keep your voice down and enquire quietly about an alternative.</p>
                                <p>Finally, good manners are the most important aspect. Treat each person with respect, even when faced with rudeness.</p>
                            </div>
                            <div class="reading-questions">
                                <div class="r-section-hdr">Questions 15–20</div>
                                <p style="font-size:.85rem;color:#374151;margin-bottom:1rem;">Complete the table. Choose <strong>ONE WORD ONLY</strong> from the text.</p>
                                <table class="ff-table" style="margin-bottom:1.5rem;">
                                    <thead><tr style="background:#f3f4f6;">
                                        <th style="border:1px solid #d1d5db;padding:.5rem .75rem;font-size:.8rem;">Risk factor</th>
                                        <th style="border:1px solid #d1d5db;padding:.5rem .75rem;font-size:.8rem;">Examples</th>
                                        <th style="border:1px solid #d1d5db;padding:.5rem .75rem;font-size:.8rem;">Risk reduction</th>
                                    </tr></thead>
                                    <tbody>
                                    <tr>
                                        <td rowspan="2">Heavy loads</td>
                                        <td>Lifting sacks of <span class="q-badge">15</span><input type="text" class="ff-input" data-q="15" placeholder="…" style="width:85px;" oninput="readingHandleInput(15,this.value)"></td>
                                        <td rowspan="2">Divide into smaller containers; use a tractor</td>
                                    </tr>
                                    <tr><td>Carrying food for animals</td></tr>
                                    <tr>
                                        <td rowspan="2">Awkward posture</td>
                                        <td>Lifting a restless <span class="q-badge">16</span><input type="text" class="ff-input" data-q="16" placeholder="…" style="width:85px;" oninput="readingHandleInput(16,this.value)"></td>
                                        <td rowspan="2">Buy <span class="q-badge">18</span><input type="text" class="ff-input" data-q="18" placeholder="…" style="width:65px;" oninput="readingHandleInput(18,this.value)"> to help support</td>
                                    </tr>
                                    <tr>
                                        <td>Moving around a big <span class="q-badge">17</span><input type="text" class="ff-input" data-q="17" placeholder="…" style="width:85px;" oninput="readingHandleInput(17,this.value)"></td>
                                    </tr>
                                    <tr>
                                        <td>A lot of <span class="q-badge">19</span><input type="text" class="ff-input" data-q="19" placeholder="…" style="width:75px;" oninput="readingHandleInput(19,this.value)"> while working</td>
                                        <td>Fixing a fallen <span class="q-badge">20</span><input type="text" class="ff-input" data-q="20" placeholder="…" style="width:75px;" oninput="readingHandleInput(20,this.value)"></td>
                                        <td>Use a workbench</td>
                                    </tr>
                                    </tbody>
                                </table>

                                <div class="r-section-hdr">Questions 21–27</div>
                                <p style="font-size:.85rem;color:#374151;margin-bottom:1rem;">Complete the sentences. <strong>NO MORE THAN TWO WORDS</strong> from the text.</p>
                                <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03
                                $s2s = [
                                    21 => ['pre' => 'A ',           'post' => ' approach to selling is fine as long as you do not irritate the customer.'],
                                    22 => ['pre' => 'Recommend additional products and ', 'post' => ' without being too forceful.'],
                                    23 => ['pre' => 'Know how to compare similar products which have different ', 'post' => '.'],
                                    24 => ['pre' => 'Avoid ',       'post' => " by always saying more than 'no'."],
                                    25 => ['pre' => 'Keep an eye on the ', 'post' => ' of goods on the shelves.'],
                                    26 => ['pre' => 'If a customer has problems paying with their ', 'post' => ', handle the problem with care.'],
                                    27 => ['pre' => 'Any ',         'post' => ' from a customer should not affect how you treat them.'],
                                ];
                                foreach ($s2s as $qn => $sp): ?>
                                <div class="sc-q">
                                    <span class="q-badge"><?= $qn ?></span>
                                    <?= htmlspecialchars($sp['pre']) ?>
                                    <input type="text" class="ff-input" data-q="<?= $qn ?>" placeholder="…" style="width:130px;" oninput="readingHandleInput(<?= $qn ?>,this.value)">
                                    <?= htmlspecialchars($sp['post']) ?>
                                </div>
                                <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endforeach; ?>

                                <div class="r-minibar">
                                    <div class="r-minibar-inner">
                                        <span class="minibar-tag">Progress</span>
                                        <div class="q-bubbles">
                                            <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 for ($qi=15; $qi<=27; $qi++): ?>
                                            <div class="q-bubble" id="rbubble-<?= $qi ?>"><?= $qi ?></div>
                                            <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endfor; ?>
                                        </div>
                                    </div>
                                    <div class="minibar-count"><em id="racount-2">0</em>/13 answered</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reading Section 3 -->
                    <div class="r-panel" id="rpanel-3">
                        <div class="reading-split">
                            <div class="reading-passage">
                                <h4>Plastic is no longer fantastic</h4>
                                <p><span class="para-chip">A</span>In 2017, Carlos Ferrando saw a piece of art that profoundly affected him — 'What Lies Under' by Ferdi Rizkiyanto, showing plastic waste beneath a wave. The artwork left Ferrando angry and fuelled with entrepreneurial ideas.</p>
                                <p><span class="para-chip">B</span>Ferrando runs Closca, which produces a foldable bicycle helmet. He has also designed a stylish glass water bottle with a magnetic closure that can be attached to almost anything, and an app that tells people where they can fill their bottles for free.</p>
                                <p><span class="para-chip">C</span>'Bottled water is now a $100 billion business, and 81 per cent of the bottles are not recycled. It's a complete waste!' Ferrando cries. 'We want people to clip their bottles onto what they are wearing, to show that they are recycling — and to look cool.'</p>
                                <p><span class="para-chip">D</span>Three decades ago, conspicuous consumption heightened people's social status. The closing decades of the 20th century were a time when anything could be turned into a commodity. Hence water became a consumer item sold in plastic bottles.</p>
                                <p><span class="para-chip">E</span>Today, conspicuous extravagance no longer seems desirable. Recycling is fashionable. What many millennials prefer are refillable bottles or Thermos bottles. Some teenagers think stainless-steel vacuum-insulated bottles are ultra 'cool' — never mind that they feel oddly out-of-date to anyone over 40.</p>
                                <p><span class="para-chip">F</span>It is uncertain whether Closca will succeed. It can be very hard for any design entrepreneur to really take off in the global mass market. If an entrepreneur had wanted to fund a smart invention a few decades ago, he or she would have had to raise a bank loan, borrow money from a family member or use a credit card.</p>
                                <p><span class="para-chip">G</span>Entrepreneurs are still using the last two options, but some are also tapping into 'corporate social responsibility' investments. Ferrando posted details about his water-bottle venture on a large, recognised platform for funding creative projects. He appealed for $30,000 and promised a bottle to anyone who donated more than $39. If $60,000 was raised, a multicoloured one would be made. None of the donors has a stake in his idea, nor does he have any debt. Closca had raised some $52,838 from 803 backers.</p>
                            </div>
                            <div class="reading-questions">
                                <div class="r-section-hdr">Questions 28–34</div>
                                <p style="font-size:.85rem;color:#374151;margin-bottom:.75rem;">Choose the correct heading for each paragraph.</p>
                                <div class="heading-list">
                                    <strong style="font-size:.8rem;color:#374151;display:block;margin-bottom:.4rem;">List of Headings</strong>
                                    <table>
                                    <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03
                                    $headings = [
                                        'i'   => 'A time when opportunities were limited',
                                        'ii'  => "The reasons why Ferrando's product is needed",
                                        'iii' => 'A no-risk solution',
                                        'iv'  => 'Two inventions and some physical details',
                                        'v'   => 'The contrasting views of different generations',
                                        'vi'  => 'A disturbing experience',
                                        'vii' => 'The problems with replacing a consumer item',
                                        'viii'=> 'Looking back at why water was bottled',
                                    ];
                                    foreach ($headings as $num => $title): ?>
                                    <tr><td><?= $num ?></td><td><?= htmlspecialchars($title) ?></td></tr>
                                    <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endforeach; ?>
                                    </table>
                                </div>
                                <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 foreach (['A'=>28,'B'=>29,'C'=>30,'D'=>31,'E'=>32,'F'=>33,'G'=>34] as $para => $qn): ?>
                                <div class="r-match-q">
                                    <span class="q-badge"><?= $qn ?></span>
                                    <span class="r-match-q-text">Paragraph <?= $para ?></span>
                                    <select class="match-select" id="rsel-<?= $qn ?>" onchange="readingHandleInput(<?= $qn ?>,this.value)">
                                        <option value="">—</option>
                                        <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 foreach (array_keys($headings) as $num): ?>
                                        <option value="<?= $num ?>"><?= $num ?></option>
                                        <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endforeach; ?>
                                    </select>
                                </div>
                                <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endforeach; ?>

                                <div class="r-section-hdr" style="margin-top:2rem;">Questions 35–37</div>
                                <p style="font-size:.85rem;color:#374151;margin-bottom:.75rem;">Choose the correct letter, <strong>A, B, C or D</strong>.</p>
                                <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03
                                $s3mc = [
                                    35 => ['text' => 'What does Ferrando say about his glass water bottle?',
                                           'opts' => ['A'=>'It matches his bicycle helmet.','B'=>'It is cheaper than a plastic bottle.','C'=>'He has designed it to suit all ages.','D'=>'He wants people to be proud to show it.']],
                                    36 => ['text' => "What does the writer find fascinating about Ferrando's story?",
                                           'opts' => ['A'=>'the youthfulness of his ideas','B'=>'the old-fashioned nature of his products','C'=>'the choice it is creating for consumers','D'=>"the change it is revealing in people's attitudes"]],
                                    37 => ['text' => "What does the writer suggest about Closca's bike helmet?",
                                           'opts' => ['A'=>'It has both functional and artistic value.','B'=>'Its main appeal is to older people.','C'=>'It has had extraordinary success worldwide.','D'=>'It is a more exciting invention than the glass bottle.']],
                                ];
                                foreach ($s3mc as $qn => $mc): ?>
                                <div class="mc-question">
                                    <div class="mc-q-label">
                                        <span class="q-badge"><?= $qn ?></span><?= htmlspecialchars($mc['text']) ?>
                                    </div>
                                    <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 foreach ($mc['opts'] as $letter => $text): ?>
                                    <label class="mc-option">
                                        <input type="radio" name="rq<?= $qn ?>" value="<?= $letter ?>" onchange="readingHandleInput(<?= $qn ?>,'<?= $letter ?>')">
                                        <span><strong><?= $letter ?>.</strong> <?= htmlspecialchars($text) ?></span>
                                    </label>
                                    <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endforeach; ?>
                                </div>
                                <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endforeach; ?>

                                <div class="r-section-hdr" style="margin-top:2rem;">Questions 38–40</div>
                                <div class="summary-box">
                                    <h5>Funding a smart invention</h5>
                                    <p>Thirty years ago, creators funded projects via the bank or from someone in the
                                    <span class="q-badge">38</span><input type="text" class="ff-input" data-q="38" placeholder="…" style="width:95px;" oninput="readingHandleInput(38,this.value)">.
                                    Ferrando used a well-known
                                    <span class="q-badge">39</span><input type="text" class="ff-input" data-q="39" placeholder="…" style="width:95px;" oninput="readingHandleInput(39,this.value)">
                                    to advertise his product. He told donors a
                                    <span class="q-badge">40</span><input type="text" class="ff-input" data-q="40" placeholder="…" style="width:115px;" oninput="readingHandleInput(40,this.value)">
                                    bottle would be made once more funds were raised.</p>
                                </div>

                                <div class="r-minibar">
                                    <div class="r-minibar-inner">
                                        <span class="minibar-tag">Progress</span>
                                        <div class="q-bubbles">
                                            <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 for ($qi=28; $qi<=40; $qi++): ?>
                                            <div class="q-bubble" id="rbubble-<?= $qi ?>"><?= $qi ?></div>
                                            <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 endfor; ?>
                                        </div>
                                    </div>
                                    <div class="minibar-count"><em id="racount-3">0</em>/13 answered</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button onclick="finishSection('reading')" class="btn btn-success btn-lg">Finish Reading &amp; Continue →</button>
                    </div>
                </div>
            </div><!-- /#tab-reading -->

            <!-- ══════════════════════════════════════════
                 WRITING
                 ══════════════════════════════════════════ -->
            <div class="tab-pane fade" id="tab-writing">
                <div class="section-content">
                    <div class="audio-notice" style="margin-bottom:1.5rem;">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        You have <strong>60 minutes</strong>. Spend about 20 minutes on Task 1 and 40 minutes on Task 2.
                    </div>

                    <div class="part-tabs-bar">
                        <div class="part-tabs-scrollable">
                            <button class="part-tab-btn active" id="wtab-1" onclick="switchWritingTask(1)">
                                Task 1<span class="tab-qrange">~20 min · 150+ words</span>
                            </button>
                            <button class="part-tab-btn" id="wtab-2" onclick="switchWritingTask(2)">
                                Task 2<span class="tab-qrange">~40 min · 250+ words</span>
                            </button>
                        </div>
                        <div class="inline-timer" id="writingTimerEl"><i class="bi bi-clock-fill"></i> 60:00</div>
                    </div>

                    <div class="part-panel active" id="wpanel-1">
                        <div class="writing-task-card">
                            <div class="writing-task-header">
                                <h5><i class="bi bi-envelope me-2"></i>Writing Task 1 — Letter</h5>
                                <span class="task-meta">~20 minutes &nbsp;|&nbsp; At least 150 words</span>
                            </div>
                            <div class="writing-task-body">
                                <div class="writing-prompt-box">
                                    <strong>Mrs Barrett has advertised for someone to help her in her home for a few hours a day next summer.</strong><br><br>
                                    <strong>Write a letter to Mrs Barrett. In your letter</strong>
                                    <ul class="writing-bullets">
                                        <li>suggest how you could help her in her home</li>
                                        <li>say why you would like to do this work</li>
                                        <li>explain when you will and will not be available</li>
                                    </ul>
                                </div>
                                <div class="writing-meta-row">
                                    <span class="writing-meta-pill"><i class="bi bi-check2-circle"></i> Min. 150 words</span>
                                    <span class="writing-meta-pill"><i class="bi bi-person"></i> Formal letter</span>
                                </div>
                                <div class="writing-begin-note">Begin your letter: <strong>Dear Mrs Barrett,</strong></div>
                                <textarea class="writing-textarea" id="writing-task1"
                                    placeholder="Dear Mrs Barrett,&#10;&#10;I am writing in response to your advertisement..."
                                    oninput="updateWordCount(1)"></textarea>
                                <div class="writing-wordcount" id="wc-1">0 words</div>
                            </div>
                        </div>
                        <button onclick="switchWritingTask(2)" class="btn btn-outline-secondary">Task 2 →</button>
                    </div>

                    <div class="part-panel" id="wpanel-2">
                        <div class="writing-task-card">
                            <div class="writing-task-header">
                                <h5><i class="bi bi-pencil-square me-2"></i>Writing Task 2 — Essay</h5>
                                <span class="task-meta">~40 minutes &nbsp;|&nbsp; At least 250 words</span>
                            </div>
                            <div class="writing-task-body">
                                <div class="writing-prompt-box">
                                    <strong>Plastic bags, plastic bottles and plastic packaging are bad for the environment.</strong><br><br>
                                    <strong>What damage does plastic do to the environment? What can be done by governments and individuals to solve this problem?</strong>
                                </div>
                                <div class="writing-meta-row">
                                    <span class="writing-meta-pill"><i class="bi bi-check2-circle"></i> Min. 250 words</span>
                                    <span class="writing-meta-pill"><i class="bi bi-file-text"></i> Formal essay</span>
                                </div>
                                <textarea class="writing-textarea" id="writing-task2"
                                    placeholder="Plastic pollution has become one of the most pressing environmental challenges..."
                                    oninput="updateWordCount(2)"
                                    style="min-height:300px;"></textarea>
                                <div class="writing-wordcount" id="wc-2">0 words</div>
                            </div>
                        </div>
                        <button onclick="finishSection('writing')" class="btn btn-success">Finish Writing &amp; Review →</button>
                    </div>
                </div>
            </div><!-- /#tab-writing -->

            <!-- ══════════════════════════════════════════
                 SUBMIT
                 ══════════════════════════════════════════ -->
            <div class="tab-pane fade" id="tab-submit">
                <div class="section-content">

                    <div id="preSubmitView">
                        <div class="submit-hero">
                            <h2><i class="bi bi-send-check me-2"></i>Ready to submit?</h2>
                            <p>Review your progress below, then submit to generate your results sheet for the examiner.</p>
                            <div class="submit-checklist">
                                <div class="submit-check-item" id="check-listening">
                                    <i class="bi bi-headphones"></i>
                                    <span class="check-label">Listening</span>
                                    <span class="check-count" id="check-listening-count">0 / 40 answered</span>
                                </div>
                                <div class="submit-check-item" id="check-reading">
                                    <i class="bi bi-book"></i>
                                    <span class="check-label">Reading</span>
                                    <span class="check-count" id="check-reading-count">0 / 40 answered</span>
                                </div>
                                <div class="submit-check-item" id="check-writing1">
                                    <i class="bi bi-envelope"></i>
                                    <span class="check-label">Writing Task 1</span>
                                    <span class="check-count" id="check-wc1">0 words</span>
                                </div>
                                <div class="submit-check-item" id="check-writing2">
                                    <i class="bi bi-pencil-square"></i>
                                    <span class="check-label">Writing Task 2</span>
                                    <span class="check-count" id="check-wc2">0 words</span>
                                </div>
                            </div>
                            <button class="btn-final-submit" onclick="submitFullTest()">
                                <i class="bi bi-send-fill"></i> Submit Full Test
                            </button>
                        </div>
                    </div>

                    <!-- Results sheet -->
                    <div id="resultsSheet">
                        <div class="sheet-header">
                            <div class="sheet-header-left">
                                <h2><i class="bi bi-file-earmark-text me-2"></i>EduHub — IELTS Mock Test Results</h2>
                                <p id="sheetMeta"></p>
                            </div>
                            <button class="btn-print" onclick="window.print()"><i class="bi bi-printer me-1"></i> Print Sheet</button>
                        </div>

                        <!-- Listening scores -->
                        <div class="sheet-section">
                            <div class="sheet-section-title">Listening</div>
                            <div class="score-grid">
                                <div class="score-card"><div class="score-card-label">Score</div><div class="score-card-value" id="ls-score">—</div><div class="score-card-band" id="ls-band">—</div></div>
                                <div class="score-card" id="ls-p1"><div class="score-card-label">Section 1</div><div class="score-card-value">—</div></div>
                                <div class="score-card" id="ls-p2"><div class="score-card-label">Section 2</div><div class="score-card-value">—</div></div>
                                <div class="score-card" id="ls-p3"><div class="score-card-label">Section 3</div><div class="score-card-value">—</div></div>
                                <div class="score-card" id="ls-p4"><div class="score-card-label">Section 4</div><div class="score-card-value">—</div></div>
                            </div>
                            <table class="breakdown-table">
                                <thead><tr><th>Q</th><th>Your Answer</th><th>Correct Answer</th><th>Result</th></tr></thead>
                                <tbody id="ls-breakdown-body"></tbody>
                            </table>
                        </div>

                        <!-- Reading scores -->
                        <div class="sheet-section">
                            <div class="sheet-section-title">Reading</div>
                            <div class="score-grid">
                                <div class="score-card"><div class="score-card-label">Score</div><div class="score-card-value" id="rs-score">—</div><div class="score-card-band" id="rs-band">—</div></div>
                                <div class="score-card" id="rs-s1"><div class="score-card-label">Section 1</div><div class="score-card-value">—</div></div>
                                <div class="score-card" id="rs-s2"><div class="score-card-label">Section 2</div><div class="score-card-value">—</div></div>
                                <div class="score-card" id="rs-s3"><div class="score-card-label">Section 3</div><div class="score-card-value">—</div></div>
                            </div>
                            <table class="breakdown-table">
                                <thead><tr><th>Q</th><th>Your Answer</th><th>Correct Answer</th><th>Result</th></tr></thead>
                                <tbody id="rs-breakdown-body"></tbody>
                            </table>
                        </div>

                        <!-- Writing responses -->
                        <div class="sheet-section">
                            <div class="sheet-section-title">Writing — Student Responses (for examiner grading)</div>
                            <p style="font-size:.85rem;color:#374151;margin-bottom:.75rem;"><strong>Task 1 prompt:</strong> Mrs Barrett has advertised for someone to help her in her home. Write a letter suggesting how you could help, why you want the work, and when you are/are not available.</p>
                            <div class="wc-pill" id="wc1-pill">0 words</div>
                            <div class="writing-response-box" id="writing1-display"></div>

                            <p style="font-size:.85rem;color:#374151;margin:.1.5rem 0 .75rem;margin-top:1.5rem;"><strong>Task 2 prompt:</strong> Plastic bags, bottles and packaging are bad for the environment. What damage does plastic do? What can governments and individuals do?</p>
                            <div class="wc-pill" id="wc2-pill">0 words</div>
                            <div class="writing-response-box" id="writing2-display"></div>

                            <div class="grading-box" style="margin-top:1.5rem;">
                                <p style="font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;margin-bottom:1rem;">Examiner Grading</p>
                                <div class="grading-row"><span class="grading-label">Writing Task 1 score</span><div class="grading-line"></div><div class="grading-score-box"></div></div>
                                <div class="grading-row"><span class="grading-label">Writing Task 2 score</span><div class="grading-line"></div><div class="grading-score-box"></div></div>
                                <div class="grading-row"><span class="grading-label">Writing overall band</span><div class="grading-line"></div><div class="grading-score-box"></div></div>
                            </div>
                        </div>

                        <!-- Speaking -->
                        <div class="sheet-section">
                            <div class="sheet-section-title">Speaking — Preparation Material &amp; Examiner Guide</div>
                            <div class="speaking-card">
                                <div class="speaking-card-hdr"><i class="bi bi-chat-dots"></i> Part 1 — Interview (4–5 minutes)</div>
                                <div class="speaking-card-body">
                                    <p style="font-size:.84rem;color:#6b7280;margin-bottom:.75rem;">The examiner asks questions about familiar topics.</p>
                                    <div class="speaking-topic-hdr"><i class="bi bi-people me-1"></i>People you study or work with</div>
                                    <ul class="speaking-qs">
                                        <li>Who do you spend most time studying/working with? <strong>[Why?]</strong></li>
                                        <li>What kinds of things do you study/work on with other people? <strong>[Why?]</strong></li>
                                        <li>Are there times when you study/work better by yourself? <strong>[Why/Why not?]</strong></li>
                                        <li>Is it important to like the people you study/work with? <strong>[Why/Why not?]</strong></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="speaking-card">
                                <div class="speaking-card-hdr"><i class="bi bi-mic"></i> Part 2 — Long Turn (3–4 minutes)</div>
                                <div class="speaking-card-body">
                                    <div style="background:#fef3c7;border-radius:6px;padding:.35rem .75rem;font-size:.78rem;color:#92400e;font-weight:700;display:inline-block;margin-bottom:.75rem;">1 minute to prepare &nbsp;·&nbsp; 1–2 minutes to speak</div>
                                    <div class="speaking-cue">
                                        <strong>Describe a tourist attraction you enjoyed visiting.</strong>
                                        <p style="font-size:.83rem;color:#6b7280;margin:.3rem 0 .5rem;">You should say:</p>
                                        <ul>
                                            <li>what this tourist attraction is</li>
                                            <li>when and why you visited it</li>
                                            <li>what you did there</li>
                                        </ul>
                                        <p style="font-size:.86rem;color:#374151;margin-top:.6rem;font-weight:600;">and explain why you enjoyed visiting this tourist attraction.</p>
                                    </div>
                                    <div class="grading-box">
                                        <p style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;margin-bottom:.75rem;">Examiner Notes</p>
                                        <div class="grading-row"><span class="grading-label">Fluency &amp; Coherence</span><div class="grading-line"></div><div class="grading-score-box"></div></div>
                                        <div class="grading-row"><span class="grading-label">Lexical Resource</span><div class="grading-line"></div><div class="grading-score-box"></div></div>
                                        <div class="grading-row"><span class="grading-label">Grammar Range &amp; Accuracy</span><div class="grading-line"></div><div class="grading-score-box"></div></div>
                                        <div class="grading-row"><span class="grading-label">Pronunciation</span><div class="grading-line"></div><div class="grading-score-box"></div></div>
                                        <div class="grading-row"><span class="grading-label">Speaking Overall Band</span><div class="grading-line"></div><div class="grading-score-box"></div></div>
                                    </div>
                                </div>
                            </div>
                            <div class="speaking-card">
                                <div class="speaking-card-hdr"><i class="bi bi-arrow-left-right"></i> Part 3 — Discussion (4–5 minutes)</div>
                                <div class="speaking-card-body">
                                    <div class="speaking-topic-hdr"><i class="bi bi-building me-1"></i>Different kinds of tourist attractions</div>
                                    <ul class="speaking-qs">
                                        <li>What are the most popular tourist attractions in your country?</li>
                                        <li>How do the types of tourist attractions that younger people like compare with those that older people like?</li>
                                        <li>Do you agree that some tourist attractions should be free to visit?</li>
                                    </ul>
                                    <div class="speaking-topic-hdr" style="margin-top:1rem;"><i class="bi bi-globe me-1"></i>The importance of international tourism</div>
                                    <ul class="speaking-qs">
                                        <li>Why is tourism important to a country?</li>
                                        <li>What are the benefits to individuals of visiting another country as tourists?</li>
                                        <li>How necessary is it for tourists to learn the language of the country they're visiting?</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Overall band summary -->
                        <div class="sheet-section">
                            <div class="sheet-section-title">Overall Band Score Summary</div>
                            <div class="grading-box">
                                <div class="grading-row"><span class="grading-label">Listening (auto-graded)</span><div class="grading-line"></div><div class="grading-score-box" id="overall-ls-box" style="display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;color:#667eea;border-color:#667eea;"></div></div>
                                <div class="grading-row"><span class="grading-label">Reading (auto-graded)</span><div class="grading-line"></div><div class="grading-score-box" id="overall-rs-box" style="display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;color:#667eea;border-color:#667eea;"></div></div>
                                <div class="grading-row"><span class="grading-label">Writing (examiner)</span><div class="grading-line"></div><div class="grading-score-box"></div></div>
                                <div class="grading-row"><span class="grading-label">Speaking (examiner)</span><div class="grading-line"></div><div class="grading-score-box"></div></div>
                                <div class="grading-row" style="border-top:2px solid #e5e7eb;padding-top:.75rem;margin-top:.25rem;">
                                    <span class="grading-label" style="font-weight:700;color:#1a2236;font-size:.95rem;">Overall Band Score</span>
                                    <div class="grading-line"></div>
                                    <div class="grading-score-box" style="border-width:2px;border-color:#1a2236;"></div>
                                </div>
                            </div>
                        </div>
                    </div><!-- /#resultsSheet -->

                </div>
            </div><!-- /#tab-submit -->

        </div><!-- /.tab-content -->
    </main>

    <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 include INCLUDES_PATH . '/adverts.php'; ?>
    <audio id="audioEl" preload="auto"></audio>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php
// TODO: Replace all hardcoded listening data, correct answers, and reading passages below
// with the actual content for mock test: IELTS_GT_MOCK03 include INCLUDES_PATH . '/navbar_scripts.php'; ?>

    <script>
    /* ══════════════════════════════════════════════════════
       Config – injected from PHP
       ══════════════════════════════════════════════════════ */
    const PART_AUDIO  = <?= json_encode(array_combine(
        array_keys($parts),
        array_map(fn($p) => $p['audio_url'], $parts)
    )) ?>;
    const PART_RANGES = <?= json_encode($partRanges) ?>;
    const CORRECT     = <?= json_encode($correctAnswers) ?>;
    const R_CORRECT   = <?= json_encode($readingAnswers) ?>;
    const MOCK_CODE   = '<?= htmlspecialchars($mockCode) ?>';
    const PREVIEWS    = <?= json_encode(array_map(fn($p) => $p['preview_s'], $parts)) ?>;
    const R_SECTION_RANGES = { 1:[1,14], 2:[15,27], 3:[28,40] };

    /*
     * EITHER-ORDER PAIRS for listening
     * Format: [qA, qB, answerA, answerB]
     * If the student writes answerB in qA and answerA in qB, both are still correct.
     */
    const EITHER_ORDER_PAIRS = <?= json_encode($eitherOrderPairs) ?>;

    /*
     * ACCEPTABLE ALTERNATE ANSWERS
     * Keys are question numbers; values are arrays of all accepted strings.
     * The primary answer is already in CORRECT; list extras here.
     * All comparisons are lower-cased and whitespace-collapsed.
     */
    const ALTERNATES = {
        4:  ['0.75m','0.75 m','0.75 metre','0.75 metres','0.75 meter','0.75 meters',
             '¾ m','75 cm','75 cms','three quarter metre','three quarters metre',
             'three quarter meter','three quarters meter'],
        5:  ['0.5m','0.5 m','0.5 metre','0.5 metres','0.5 meter','0.5 meters',
             '½ m','50 cm','50 cms','half metre','half meter','half a metre','half a meter'],
        8:  ['1700','£1700','1,700','£1,700'],
        13: ['music','music groups','music group'],
        14: ['local history','local history groups','local history group'],
        15: ['library','libraries','public library','public libraries','the library','the town hall','town hall'],
        16: ['town hall','the town hall','library','libraries','public library'],
        22: ['13','13 countries','13 different countries','thirteen','thirteen countries'],
        24: ['50 kilometres','50 kilometers','50km','50 km','around 50 kilometres',
             'about 50 kilometres','approximately 50 kilometres',
             'around 50 kilometers','about 50 kilometers'],
        25: ['temperature','water temperature','sea temperature','ocean temperature',
             'temperature of water','temperature of ocean','temperature of sea',
             'water temperature change','sea temperature change',
             'changes in temperature'],
    };

    /* ══════════════════════════════════════════════════════
       Timers
       ══════════════════════════════════════════════════════ */
    const timers = {
        listening: { secs: 40*60, el: null, interval: null, started: false },
        reading:   { secs: 60*60, el: null, interval: null, started: false },
        writing:   { secs: 60*60, el: null, interval: null, started: false },
    };

    function fmtTime(s) {
        return String(Math.floor(s/60)).padStart(2,'0') + ':' + String(s%60).padStart(2,'0');
    }

    const NEXT_SECTION = { listening: 'reading', reading: 'writing', writing: 'submit' };

    function unlockTab(tabName) {
        const el = document.querySelector(`a[href="#tab-${tabName}"]`);
        if (el) el.classList.remove('tab-locked');
    }

    function lockTab(tabName) {
        const el = document.querySelector(`a[href="#tab-${tabName}"]`);
        if (el) el.classList.add('tab-locked');
    }

    function finishSection(current) {
        const next = NEXT_SECTION[current];
        if (!next) return;

        const msgs = {
            listening: { title: 'Finish Listening?', text: "You won't be able to return to this section." },
            reading:   { title: 'Finish Reading?',   text: "You won't be able to return to this section." },
            writing:   { title: 'Move to Submit?',   text: 'You can still edit your writing before final submission.' },
        };
        const m = msgs[current];
        Swal.fire({
            title: m.title,
            text: m.text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, continue',
            cancelButtonText: 'Stay here',
            confirmButtonColor: '#10b981',
        }).then(result => {
            if (result.isConfirmed) advanceToSection(current, next);
        });
    }

    function advanceToSection(current, next) {
        lockTab(current);
        unlockTab(next);
        goTo(next);
        if (next === 'reading') startTimer('reading');
        if (next === 'writing') startTimer('writing');
    }

    function startTimer(key, onExpire) {
        const t = timers[key];
        if (t.started) return;
        t.started = true;
        t.interval = setInterval(() => {
            if (t.secs <= 0) {
                clearInterval(t.interval);
                const next = NEXT_SECTION[key];
                if (next) {
                    Swal.fire({
                        title: "Time's up!",
                        text: `${key.charAt(0).toUpperCase() + key.slice(1)} time has ended. Moving to the next section.`,
                        icon: 'warning',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    }).then(() => advanceToSection(key, next));
                }
                if (onExpire) onExpire();
                return;
            }
            t.secs--;
            if (t.el) {
                t.el.innerHTML = '<i class="bi bi-clock-fill"></i> ' + fmtTime(t.secs);
                t.el.classList.toggle('warning', t.secs <= 300);
            }
        }, 1000);
    }

    /* ══════════════════════════════════════════════════════
       Audio player
       ══════════════════════════════════════════════════════ */
    let answers = {}, currentPart = 1, audioPlayed = {}, previewTmr = null;
    let audioEl, playIcon, audioBar, audioTimeEl;

    function fmt(s) {
        return Math.floor(s/60) + ':' + String(Math.floor(s%60)).padStart(2,'0');
    }

    function loadAudio(pNum) {
        const src = PART_AUDIO[pNum] || '';
        if (!src) return;
        audioEl.src = src;
        audioEl.load();
        audioBar.value = 0;
        audioTimeEl.textContent = '0:00 / 0:00';
        playIcon.className = 'bi bi-play-fill';
    }

    function togglePlay() {
        if (!audioEl.src || audioEl.src === window.location.href) {
            if (previewTmr) {
                clearInterval(previewTmr);
                document.getElementById('previewPill').classList.add('hidden');
                previewTmr = null;
            }
            loadAudio(currentPart);
            if (!audioEl.src || audioEl.src === window.location.href) return;
        }
        if (audioEl.paused) {
            audioEl.play().catch(() => {});
            playIcon.className = 'bi bi-pause-fill';
            audioPlayed[currentPart] = true;
        } else {
            audioEl.pause();
            playIcon.className = 'bi bi-play-fill';
        }
    }

    function toggleMute() {
        audioEl.muted = !audioEl.muted;
        document.getElementById('volIcon').className = audioEl.muted ? 'bi bi-volume-mute' : 'bi bi-volume-up';
    }
    function setVol(v) { audioEl.volume = parseFloat(v); }

    document.addEventListener('DOMContentLoaded', () => {
        audioEl     = document.getElementById('audioEl');
        playIcon    = document.getElementById('playIcon');
        audioBar    = document.getElementById('audioBar');
        audioTimeEl = document.getElementById('audioTime');

        audioEl.addEventListener('timeupdate', () => {
            if (!audioEl.duration) return;
            audioBar.value = (audioEl.currentTime / audioEl.duration) * 100;
            audioTimeEl.textContent = fmt(audioEl.currentTime) + ' / ' + fmt(audioEl.duration);
        });
        audioEl.addEventListener('ended', () => { playIcon.className = 'bi bi-play-fill'; });

        timers.listening.el = document.getElementById('listeningTimerEl');
        timers.reading.el   = document.getElementById('readingTimerEl');
        timers.writing.el   = document.getElementById('writingTimerEl');

        startTimer('listening');
        startPreview(PREVIEWS[1] || 0, 1);

        document.querySelector('a[href="#tab-submit"]').addEventListener('shown.bs.tab', refreshChecklist);
    });

    /* ── Preview countdown ── */
    function startPreview(secs, pNum) {
        const pill   = document.getElementById('previewPill');
        const numEl  = document.getElementById('previewNum');
        if (!secs) { loadAudio(pNum); return; }
        pill.classList.remove('hidden');
        numEl.textContent = secs;
        let left = secs;
        previewTmr = setInterval(() => {
            left--;
            numEl.textContent = left;
            if (left <= 0) {
                clearInterval(previewTmr);
                previewTmr = null;
                pill.classList.add('hidden');
                loadAudio(pNum);
            }
        }, 1000);
    }

    /* ── Section switching ── */
    function switchPart(pNum, btn) {
        document.getElementById('panel-' + currentPart).classList.remove('active');
        document.getElementById('ptab-'  + currentPart).classList.remove('active');
        currentPart = pNum;
        document.getElementById('panel-' + pNum).classList.add('active');
        btn.classList.add('active');
        if (!audioEl.paused) { audioEl.pause(); playIcon.className = 'bi bi-play-fill'; }
        if (previewTmr) {
            clearInterval(previewTmr);
            previewTmr = null;
            document.getElementById('previewPill').classList.add('hidden');
        }
        if (!audioPlayed[pNum]) startPreview(PREVIEWS[pNum] || 0, pNum);
        else loadAudio(pNum);
    }

    /* ══════════════════════════════════════════════════════
       Listening answer handling
       ══════════════════════════════════════════════════════ */
    function handleInput(qNum, val, pNum) {
        answers[qNum] = val.trim();

        const bub = document.getElementById('bubble-' + qNum);
        if (bub) bub.classList.toggle('answered', !!val.trim());

        /* Style the input / select */
        const inp = document.querySelector(`.ff-input[data-q="${qNum}"]`);
        if (inp) inp.classList.toggle('answered', !!val.trim());
        const sel = document.getElementById('msel-' + qNum);
        if (sel) sel.classList.toggle('answered', !!val.trim());

        /* Update answered count for this section */
        const [f, l] = PART_RANGES[pNum];
        let count = 0;
        for (let i = f; i <= l; i++) { if (answers[i]) count++; }
        document.getElementById('acount-' + pNum).textContent = count;
        document.getElementById('ptab-'   + pNum).classList.toggle('all-answered', count === (l - f + 1));
    }

    /* ══════════════════════════════════════════════════════
       Listening answer checking (with either-order + alternates)
       ══════════════════════════════════════════════════════ */
    function normalise(str) {
        return String(str).toLowerCase().replace(/\s+/g,' ').replace(/[£$,]/g,'').trim();
    }

    function checkListeningAnswer(q) {
        const got = normalise(answers[q] || '');
        if (!got) return false;

        const expected = normalise(CORRECT[q] || '');

        /* Direct match */
        if (got === expected) return true;

        /* Alternates */
        const alts = (ALTERNATES[q] || []).map(normalise);
        if (alts.includes(got)) return true;

        /* Either-order pairs */
        for (const [qA, qB, ansA, ansB] of EITHER_ORDER_PAIRS) {
            if (q !== qA && q !== qB) continue;
            const gotA = normalise(answers[qA] || '');
            const gotB = normalise(answers[qB] || '');
            const normA = normalise(ansA);
            const normB = normalise(ansB);
            const altA  = (ALTERNATES[qA] || []).map(normalise);
            const altB  = (ALTERNATES[qB] || []).map(normalise);

            const aOk = (v) => v === normA || altA.includes(v);
            const bOk = (v) => v === normB || altB.includes(v);

            /* Normal order */
            if (aOk(gotA) && bOk(gotB)) return true;
            /* Swapped order */
            if (bOk(gotA) && aOk(gotB)) return true;
        }
        return false;
    }

    /* ══════════════════════════════════════════════════════
       Reading
       ══════════════════════════════════════════════════════ */
    let rAnswers = {}, currentRSection = 1;

    function switchRSection(n, btn) {
        document.getElementById('rpanel-' + currentRSection).classList.remove('active');
        document.getElementById('rtab-'   + currentRSection).classList.remove('active');
        currentRSection = n;
        document.getElementById('rpanel-' + n).classList.add('active');
        btn.classList.add('active');
    }

    function readingHandleInput(qNum, val) {
        rAnswers[qNum] = (val || '').trim();
        const bub = document.getElementById('rbubble-' + qNum);
        if (bub) bub.classList.toggle('answered', !!rAnswers[qNum]);
        for (const [sec, [f, l]] of Object.entries(R_SECTION_RANGES)) {
            if (qNum >= f && qNum <= l) {
                let count = 0;
                for (let i = f; i <= l; i++) { if (rAnswers[i]) count++; }
                document.getElementById('racount-' + sec).textContent = count;
                break;
            }
        }
    }

    function tfngSelect(qNum, val, btn) {
        document.querySelectorAll(`.tfng-btn[data-q="${qNum}"]`).forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        readingHandleInput(qNum, val);
    }

    /* ══════════════════════════════════════════════════════
       Writing
       ══════════════════════════════════════════════════════ */
    let currentWritingTask = 1;

    function switchWritingTask(n) {
        document.getElementById('wpanel-' + currentWritingTask).classList.remove('active');
        document.getElementById('wtab-'   + currentWritingTask).classList.remove('active');
        currentWritingTask = n;
        document.getElementById('wpanel-' + n).classList.add('active');
        document.getElementById('wtab-'   + n).classList.add('active');
    }

    function countWords(text) {
        return text.trim() === '' ? 0 : text.trim().split(/\s+/).length;
    }

    function updateWordCount(taskNum) {
        const ta  = document.getElementById('writing-task' + taskNum);
        const wc  = document.getElementById('wc-' + taskNum);
        const min = taskNum === 1 ? 150 : 250;
        const n   = countWords(ta.value);
        wc.textContent = n + ' words';
        wc.className   = 'writing-wordcount ' + (n >= min ? 'ok' : (n >= min * .7 ? 'warn' : ''));
    }

    /* ══════════════════════════════════════════════════════
       Submit tab checklist
       ══════════════════════════════════════════════════════ */
    function refreshChecklist() {
        const lTotal = Object.keys(CORRECT).length;
        let lCount = 0;
        Object.keys(CORRECT).forEach(q => { if (answers[parseInt(q)]) lCount++; });
        document.getElementById('check-listening-count').textContent = lCount + ' / ' + lTotal + ' answered';
        const lEl = document.getElementById('check-listening');
        lEl.classList.toggle('done', lCount === lTotal);
        lEl.querySelector('i').className = 'bi ' + (lCount === lTotal ? 'bi-check-circle-fill' : 'bi-headphones');

        const rTotal = Object.keys(R_CORRECT).length;
        let rCount = 0;
        Object.keys(R_CORRECT).forEach(q => { if (rAnswers[parseInt(q)]) rCount++; });
        document.getElementById('check-reading-count').textContent = rCount + ' / ' + rTotal + ' answered';
        const rEl = document.getElementById('check-reading');
        rEl.classList.toggle('done', rCount === rTotal);
        rEl.querySelector('i').className = 'bi ' + (rCount === rTotal ? 'bi-check-circle-fill' : 'bi-book');

        const w1 = countWords(document.getElementById('writing-task1').value);
        const w2 = countWords(document.getElementById('writing-task2').value);
        document.getElementById('check-wc1').textContent = w1 + ' words ' + (w1 >= 150 ? '✓' : '(min 150)');
        document.getElementById('check-wc2').textContent = w2 + ' words ' + (w2 >= 250 ? '✓' : '(min 250)');
        const w1El = document.getElementById('check-writing1');
        const w2El = document.getElementById('check-writing2');
        w1El.classList.toggle('done', w1 >= 150);
        w1El.querySelector('i').className = 'bi ' + (w1 >= 150 ? 'bi-check-circle-fill' : 'bi-envelope');
        w2El.classList.toggle('done', w2 >= 250);
        w2El.querySelector('i').className = 'bi ' + (w2 >= 250 ? 'bi-check-circle-fill' : 'bi-pencil-square');
    }

    /* ══════════════════════════════════════════════════════
       Full test submission & scoring
       ══════════════════════════════════════════════════════ */
    function submitFullTest() {
        /* ── Score listening ── */
        let lCorrect = 0;
        const lByPart = {};
        Object.keys(CORRECT).forEach(qStr => {
            const q  = parseInt(qStr);
            const ok = checkListeningAnswer(q);
            if (ok) lCorrect++;
            let pn = 1;
            for (const [p, [f, l]] of Object.entries(PART_RANGES)) {
                if (q >= f && q <= l) { pn = p; break; }
            }
            if (!lByPart[pn]) lByPart[pn] = { c: 0, t: 0 };
            lByPart[pn].t++;
            if (ok) lByPart[pn].c++;
        });
        const lTotal = Object.keys(CORRECT).length;
        const lBand  = calcBand(lCorrect, lTotal);

        document.getElementById('ls-score').textContent = lCorrect + '/' + lTotal;
        document.getElementById('ls-band').textContent  = 'Est. Band: ' + lBand;
        document.getElementById('overall-ls-box').textContent = lBand;
        [1,2,3,4].forEach(p => {
            const d    = lByPart[p] || { c: 0, t: 0 };
            const card = document.getElementById('ls-p' + p);
            card.querySelector('.score-card-value').textContent = d.c + '/' + d.t;
        });

        /* Listening breakdown */
        let lRows = '';
        Object.keys(CORRECT).sort((a,b) => parseInt(a) - parseInt(b)).forEach(qStr => {
            const q   = parseInt(qStr);
            const got = answers[q] || '(blank)';
            const exp = String(CORRECT[q]);
            const ok  = checkListeningAnswer(q);
            /* Show alternate acceptable answers in the "Correct" column */
            const alts = (ALTERNATES[q] || []);
            const altNote = alts.length ? ' <small style="color:#9ca3af;">[or: ' + alts.slice(0,3).join(' / ') + ']</small>' : '';
            lRows += `<tr>
                <td>${q}</td>
                <td>${got}</td>
                <td>${exp}${altNote}</td>
                <td class="${ok ? 'ok' : 'bad'}">${ok ? '✓' : '✗'}</td>
            </tr>`;
        });
        document.getElementById('ls-breakdown-body').innerHTML = lRows;

        /* ── Score reading ── */
        let rCorrect = 0;
        const rBySection = { 1:{c:0,t:0}, 2:{c:0,t:0}, 3:{c:0,t:0} };

        function checkReading(q) {
            const got = normalise(rAnswers[q] || '');
            if (!got) return false;
            const exp = normalise(R_CORRECT[q] || '');
            if (got === exp) return true;
            /* Accept British/American spelling for multicoloured */
            if (exp === 'multicoloured' && got === 'multicolored') return true;
            /* Strip hyphens */
            if (got.replace(/-/g,'') === exp.replace(/-/g,'')) return true;
            return false;
        }

        Object.entries(R_CORRECT).forEach(([qStr]) => {
            const q  = parseInt(qStr);
            const ok = checkReading(q);
            if (ok) rCorrect++;
            for (const [sec, [f, l]] of Object.entries(R_SECTION_RANGES)) {
                if (q >= f && q <= l) { rBySection[sec].t++; if (ok) rBySection[sec].c++; break; }
            }
        });
        const rTotal = Object.keys(R_CORRECT).length;
        const rBand  = calcBand(rCorrect, rTotal);

        document.getElementById('rs-score').textContent = rCorrect + '/' + rTotal;
        document.getElementById('rs-band').textContent  = 'Est. Band: ' + rBand;
        document.getElementById('overall-rs-box').textContent = rBand;
        [1,2,3].forEach(s => {
            const d    = rBySection[s];
            const card = document.getElementById('rs-s' + s);
            card.querySelector('.score-card-value').textContent = d.c + '/' + d.t;
        });

        /* Reading breakdown */
        let rRows = '';
        Object.keys(R_CORRECT).sort((a,b) => parseInt(a) - parseInt(b)).forEach(qStr => {
            const q   = parseInt(qStr);
            const got = rAnswers[q] || '(blank)';
            const exp = String(R_CORRECT[q]);
            const ok  = checkReading(q);
            rRows += `<tr><td>${q}</td><td>${got}</td><td>${exp}</td><td class="${ok?'ok':'bad'}">${ok?'✓':'✗'}</td></tr>`;
        });
        document.getElementById('rs-breakdown-body').innerHTML = rRows;

        /* ── Writing responses ── */
        const t1 = document.getElementById('writing-task1').value.trim();
        const t2 = document.getElementById('writing-task2').value.trim();
        const w1 = countWords(t1), w2 = countWords(t2);

        const d1 = document.getElementById('writing1-display');
        d1.textContent = t1 || '(no response submitted)';
        d1.classList.toggle('empty', !t1);
        document.getElementById('wc1-pill').textContent = w1 + ' words';

        const d2 = document.getElementById('writing2-display');
        d2.textContent = t2 || '(no response submitted)';
        d2.classList.toggle('empty', !t2);
        document.getElementById('wc2-pill').textContent = w2 + ' words';

        /* ── Sheet metadata ── */
        const now = new Date();
        document.getElementById('sheetMeta').textContent =
            'Test: ' + MOCK_CODE +
            ' · Student: <?= htmlspecialchars($_SESSION['user_id'] ?? 'Unknown') ?>' +
            ' · Submitted: ' + now.toLocaleDateString() +
            ' ' + now.toLocaleTimeString([], { hour:'2-digit', minute:'2-digit' });

        /* ── Save to DB ── */
        saveAttempt();

        /* ── Show sheet ── */
        document.getElementById('preSubmitView').style.display = 'none';
        document.getElementById('resultsSheet').classList.add('show');
    }

    function saveAttempt() {
        const timeUsed = (40*60 - timers.listening.secs) +
                         (60*60 - timers.reading.secs) +
                         (60*60 - timers.writing.secs);

        const payload = {
            mock_code:           MOCK_CODE,
            section_type:        'Listening',
            section_order:       1,
            time_taken_seconds:  timeUsed,
            answers: {}
        };

        // Listening answers (Q1-40)
        Object.keys(answers).forEach(q => {
            payload.answers['L' + q] = answers[q];
        });

        // Reading answers (Q1-40 prefixed with R)
        Object.keys(rAnswers).forEach(q => {
            payload.answers['R' + q] = rAnswers[q];
        });

        // Writing responses
        const t1 = document.getElementById('writing-task1');
        const t2 = document.getElementById('writing-task2');
        if (t1) payload.answers['W1'] = t1.value.trim();
        if (t2) payload.answers['W2'] = t2.value.trim();

        fetch('save_attempt.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                console.log('Attempt saved. ID:', data.attempt_id);
            } else {
                console.warn('Save failed:', data.error);
            }
        })
        .catch(err => console.warn('Save request failed:', err));
    }

    /* ══════════════════════════════════════════════════════
       Helpers
       ══════════════════════════════════════════════════════ */
    function calcBand(c, t) {
        const p = c / t;
        if (p >= .97) return '9.0'; if (p >= .93) return '8.5'; if (p >= .87) return '8.0';
        if (p >= .80) return '7.5'; if (p >= .73) return '7.0'; if (p >= .67) return '6.5';
        if (p >= .60) return '6.0'; if (p >= .53) return '5.5'; if (p >= .47) return '5.0';
        if (p >= .40) return '4.5'; return '4.0';
    }

    function goTo(section) {
        const el = document.querySelector(`a[href="#tab-${section}"]`);
        if (el) bootstrap.Tab.getOrCreateInstance(el).show();
    }

    function confirmExit() {
        Swal.fire({
            title: 'Exit test?',
            text: 'Your progress will not be saved.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Exit',
            cancelButtonText: 'Stay',
            confirmButtonColor: '#ef4444',
        }).then(result => {
            if (result.isConfirmed)
                window.location.href = 'take.php?code=' + encodeURIComponent(MOCK_CODE);
        });
    }
    </script>
</body>
</html>