<?php
// IELTS Listening — Question Formats: Sample Question Set (Class 5, Academic 2Mo/3Mo).
// Source: the seven official IELTS.org Listening sample tasks (question papers, tapescripts
// and answer keys), supplied by the instructor 2026-09-21. Self-marking practice — not a
// timed test and not saved. The official recordings were not supplied, so each task has an
// optional computer-voice read-through of its tapescript (instructors may read it aloud).
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}
require_once INCLUDES_PATH . '/course_lock.php';
require_course_enrollment([16, 17], 'this IELTS Listening practice set');

$img = ACADEMY_URL . 'assets/images/ielts_listening_samples/';
$tapescripts = json_decode(<<<'JSON'
{
 "115005": {
  "intro": "You will hear a telephone conversation between a customer and an agent at a company which ships large boxes overseas.",
  "turns": [
   [
    "A",
    "Good morning Packham’s Shipping Agents. Can I help you?"
   ],
   [
    "B",
    "Oh yes, I’m ringing to make enquiries about sending a large box, a container, back home to Kenya from the UK."
   ],
   [
    "A",
    "Yes, of course. Would you like me to try and find some quotations for you?"
   ],
   [
    "B",
    "Yes, that’d be great. Thank you."
   ],
   [
    "A",
    "Well first of all, I need a few details from you."
   ],
   [
    "B",
    "Fine."
   ],
   [
    "A",
    "Can I take your name?"
   ],
   [
    "B",
    "It’s Jacob Mkere."
   ],
   [
    "A",
    "Can you spell your surname, please?"
   ],
   [
    "B",
    "Yes, it’s M-K-E-R-E."
   ],
   [
    "A",
    "Is that ‘M’ for mother?"
   ],
   [
    "B",
    "Yes."
   ],
   [
    "A",
    "Thank you, and you say that you will be sending the box to Kenya?"
   ],
   [
    "B",
    "That’s right."
   ],
   [
    "A",
    "And where would you like the box picked up from?"
   ],
   [
    "B",
    "From college, if possible."
   ],
   [
    "A",
    "Yes, of course. I’ll take down the address now."
   ],
   [
    "B",
    "It’s Westall College."
   ],
   [
    "A",
    "Is that W-E-S-T-A-L-L?"
   ],
   [
    "B",
    "Yes, ... college."
   ],
   [
    "A",
    "Westall College. And where’s that?"
   ],
   [
    "B",
    "It’s Downlands Road, in Bristol."
   ],
   [
    "A",
    "Oh yes, I know it. And the postcode?"
   ],
   [
    "B",
    "It’s BS8 9PU."
   ],
   [
    "A",
    "Right ... and I need to know the size."
   ],
   [
    "B",
    "Yes, I’ve measured it carefully and it’s 1.5m long ..."
   ],
   [
    "A",
    "Right."
   ],
   [
    "B",
    "0.75m wide ..."
   ],
   [
    "A",
    "OK."
   ],
   [
    "B",
    "And it’s 0.5m high or deep."
   ],
   [
    "A",
    "Great. So I’ll calculate the volume in a moment and get some quotes for that. But first can you tell me, you know, very generally, what will be in the box?"
   ],
   [
    "B",
    "Yes there’s mostly clothes."
   ],
   [
    "A",
    "OK. [writing down]"
   ],
   [
    "B",
    "And there’s some books."
   ],
   [
    "A",
    "OK. Good. Um ... Anything else?"
   ],
   [
    "B",
    "Yes, there’s also some toys."
   ],
   [
    "A",
    "OK and what is the total value, do you think, of the contents?"
   ],
   [
    "B",
    "Well the main costs are the clothes and the books – they’ll be about £1500 but then the toys are about another two hundred – so I’d put down £1700."
   ]
  ]
 },
 "115006": {
  "intro": "You will hear a Communication Studies student talking to his tutor about optional courses for the next semester.",
  "turns": [
   [
    "Dr Ray",
    "Come in. Oh hello Alan. Have a seat. Right ... you said you wanted to see me to talk about your options next semester?"
   ],
   [
    "Jack",
    "That's right. We have to decide by the end of next week. Really, I'd like to do all five options but we have to choose two, don't we."
   ],
   [
    "Dr Ray",
    "Yes, but the choice depends on your major to some extent. You're majoring in Communication Studies, aren't you?"
   ],
   [
    "Jack",
    "That's right."
   ],
   [
    "Dr Ray",
    "So for example the Media Studies Option will cover quite a lot of the same area you did in the core module on mass communications this semester - the development of the media through the last two centuries, in relation to political and social issues."
   ],
   [
    "Jack",
    "Mmm. Well that was interesting, but I’ve decided I'd rather do something completely new. There's a Women's Studies option, isn't there?"
   ],
   [
    "Dr Ray",
    "Yes, 'Women and Power' – again it has a historical focus, it aims to contextualise women's studies by looking at the legal and social situation in the nineteenth and early twentieth centuries …"
   ],
   [
    "Jack",
    "So it would be useful if I intended to specialise in women's studies ... but I'm not sure I do actually."
   ],
   [
    "Dr Ray",
    "Well, it might still be useful to give you an idea of the issues involved. It's taught by Dr Steed."
   ],
   [
    "Jack",
    "Oh, really? I'll sign up for that, then. What about the option on Culture and Society?"
   ],
   [
    "Dr Ray",
    "That addresses the historical debate on the place of culture since the Industrial Revolution in Britain."
   ],
   [
    "Jack",
    "So a historical focus again ..."
   ],
   [
    "Dr Ray",
    "Do I get the message you're not so keen on history?"
   ],
   [
    "Jack",
    "Well, it's just we seem to have done quite a lot this semester … anyway I'll think about that one."
   ],
   [
    "Dr Ray",
    "If you're interested in a course focusing on current issues there's the option on Identity and Popular Culture – that approaches the subject through things like contemporary film, adverts, soap operas and so on."
   ],
   [
    "Jack",
    "Oh? That sounds interesting. Can you tell me who runs it?"
   ],
   [
    "Dr Ray",
    "Well, it's normally Dr Stevens but he's on sabbatical next semester, so I'm not sure who'll be running it. It should be decided by next week though."
   ],
   [
    "Jack",
    "Right, well I might wait until then to decide ... And the last option is Introduction to Cultural Theory, isn't it. I'm quite interested in that too – I was talking to one of the second year students, and she said it was really useful, it made a lot of things fall into place."
   ],
   [
    "Dr Ray",
    "Yes, but in fact in your major, you'll have covered a lot of that already in Communications 102, so that might be less useful than some of the others."
   ],
   [
    "Jack",
    "Oh, I'll forget about that one, then."
   ],
   [
    "Dr Ray",
    "Now while you're here, we could also discuss how you're getting on with your Core Module assignment ..."
   ]
  ]
 },
 "115007": {
  "intro": "You will hear a man talking to an official at a tourist information office.",
  "turns": [
   [
    "Official",
    "Can I help you?"
   ],
   [
    "Man",
    "Yes, I was wanting somewhere to stay for a few days - a four or five star hotel. Can you tell me something about the possibilities?"
   ],
   [
    "Official",
    "OK, right, well there are five hotels that might interest you. Were you wanting a city centre location, or would you be interested in something a bit further out?"
   ],
   [
    "Man",
    "Well, I do have a car so I could go for either."
   ],
   [
    "Official",
    "Well, there are three central hotels in the range you're looking for – there's Carlton House and The Imperial, they're both near the main square, but if you've got your own transport you might be interested in the Royal Oak – that’s out in the country, about ten kilometres away, very peaceful. Then there's the Bridge hotel and the Majestic – they're both in town but not in the centre, they're out on the airport road."
   ],
   [
    "Man",
    "Mmm that might be a bit far out actually. OK, now the other two you mentioned, in the city centre. Can you tell me a bit about them?"
   ],
   [
    "Official",
    "Well, they're both excellent hotels. If you want something with a bit of character, Carlton House is quite unusual – it's a very old building that was originally a large private house, it was bought by the Vannis chain and they completely refurbished it – they took their first guests just a few months ago but it's already got an excellent reputation. That's a five star hotel. Or there's the Imperial, which is a much more modern building. That's also has its own gym and it also has internet connection and meetings rooms – it's used for conferences and corporate events as well as private guests. That's five star as well."
   ],
   [
    "Man",
    "Does it have a swimming pool as well as a gym?"
   ],
   [
    "Official",
    "No – the Royal Oak has an outdoor pool, which is lovely in the summer, but the only hotel with an indoor pool is the Bridge Hotel. It doesn't have a gym though. The Majestic is planning to build a swimming pool and a fitness centre, but it's not finished yet."
   ],
   [
    "Man",
    "I see. Well, I think I'll probably go for one of the city centre hotels."
   ]
  ]
 },
 "115008": {
  "intro": "(A customer has been arranging with a shipping agent to send a large box overseas. This is the last part of the conversation.)",
  "turns": [
   [
    "A",
    "OK right. Now obviously insurance is an important thing to consider and our companies are able to offer very good rates in a number of different all-inclusive packages."
   ],
   [
    "B",
    "Sorry, could you explain a bit more?"
   ],
   [
    "A",
    "Yes, sorry, um. There’s really three rates according to quality of insurance cover – there’s the highest comprehensive cover which is Premium rate, then there’s standard rate and then there’s economy rate. That one will only cover the cost of the contents second hand."
   ],
   [
    "B",
    "Oh I’ve been stung before with economy insurance so I’ll go for the highest."
   ],
   [
    "A",
    "Mh’hm and can I just check would you want home delivery or to a local depot or would you want to pick it up at the nearest port?"
   ],
   [
    "B",
    "The port’d be fine – I’ve got transport that end."
   ],
   [
    "A",
    "Fine and will you be paying by credit card?"
   ],
   [
    "B",
    "Can I pay by cheque?"
   ]
  ]
 },
 "115009": {
  "intro": "",
  "turns": [
   [
    "",
    "You will hear the librarian of a new town library talking to a group of people who are visiting the library. OK everyone. So here we are at the entrance to the town library. My name is Ann, and I'm the chief librarian here, and you'll usually find me at the desk just by the main entrance here. So I'd like to tell you a bit about the way the library is organised, and what you'll find where … and you should all have a plan in front of you. Well, as you see my desk is just on your right as you go in, and opposite this the first room on your left has an excellent collection of reference books and is also a place where people can read or study peacefully. Just beyond the librarian's desk on the right is a room where we have up to date periodicals such as newspapers and magazines and this room also has a photocopier in case you want to copy any of the articles. If you carry straight on you'll come into a large room and this is the main library area. There is fiction in the shelves on the left, and non-fiction materials on your right, and on the shelves on the far wall there is an excellent collection of books relating to local history. We're hoping to add a section on local tourist attractions too, later in the year. Through the far door in the library just past the fiction shelves is a seminar room, and that can be booked for meetings or talks, and next door to that is the children's library, which has a good collection of stories and picture books for the under elevens. Then there's a large room to the right of the library area – that's the multimedia collection, where you can borrow videos and DVDs and so on, and we also have CD-Roms you can borrow to use on your computer at home. It was originally the art collection but that's been moved to another building. And that's about it – oh, there's also the Library Office, on the left of the librarian's desk. OK, now does anyone have any questions?"
   ]
  ]
 },
 "115010": {
  "intro": "Two friends, Rachel and Paul, are discussing studying with the Open University. Rachel has already done a course at the university, but Paul has not. The extract relating to these questions comes from the last part of the recording.",
  "turns": [
   [
    "Paul",
    "The other thing I wanted to ask you was, did you find it hard, studying with the Open University?"
   ],
   [
    "Rachel",
    "You mean, because you’re studying on your own, most of the time?"
   ],
   [
    "Paul",
    "Mm."
   ],
   [
    "Rachel",
    "Well it took me a while to get used to it. I found I needed to maintain a high level of motivation, because it’s so different from school. There’s no-one saying, ‘Why haven’t you written your assignment yet?' and that sort of thing."
   ],
   [
    "Paul",
    "Oh dear."
   ],
   [
    "Rachel",
    "You’ll learn it, Paul. Another thing was that I got very good at time- management because I had to fit time for studying round a full-time job."
   ],
   [
    "Paul",
    "Well I’m hoping to change to working part-time, so that’ll help."
   ],
   [
    "Rachel",
    "What makes it easier is that the degree is made up of modules, so you can take time off between them if you need to. It isn’t like a traditional three-or four-year course, where you’ve got to do the whole thing of it in one go."
   ],
   [
    "Paul",
    "That’s good, because I’d like to spend six months travelling next year."
   ],
   [
    "Rachel",
    "Huh, it’s all right for some. Then even though you’re mostly studying at home, remember you’ve got tutors to help you, and from time to time there are summer schools. They usually last a week. They’re great, because you meet all the other people struggling with the same things as you. I’ve made some really good friends that way."
   ],
   [
    "Paul",
    "Sounds good. So how do I apply?"
   ]
  ]
 },
 "115011": {
  "intro": "",
  "turns": [
   [
    "",
    "You will hear an extract from a talk given to a group who are going to stay in the UK. Good evening, and welcome to the British Council. My name is John Parker and I’ve been asked to talk to you briefly about certain aspects of life in the UK before you actually go there. So I'm going to talk first about the best ways of making social contacts there. Now you might be wondering why it should be necessary. After all, we meet people all the time. But when you’re living in a foreign country it can be more difficult, not just because of the language, but because customs may be different. If you’re going to work in the UK you will probably be living in private accommodation, so it won’t be quite so easy to meet people. But there are still things that you can do to help yourself. First of all, you can get involved in activities in your local community, join a group of some kind. For example, you’ll probably find that there are theatre groups who might be looking for actors, set designers and so on, or if you play an instrument you could join music groups in your area. Or if you like the idea of finding out about local history there’ll be a group for that too. These are just examples. And the best places to get information about things like this are either the town hall or the public library. Libraries in the UK perform quite a broad range of functions nowadays – they’re not just confined to lending books, although that’s their main role of course."
   ]
  ]
 }
}
JSON, true);

// Small render helpers ------------------------------------------------------
function ls_input(string $key, string $ans, string $group = '', string $w = '150px'): string {
    return '<input type="text" class="ls-in" style="width:' . $w . '" autocomplete="off" data-key="' . $key . '"'
         . ($group !== '' ? ' data-group="' . $group . '"' : ' data-ans="' . htmlspecialchars($ans) . '"') . '>';
}
function ls_select(string $key, string $ans, array $letters): string {
    $o = '<option value="">–</option>';
    foreach ($letters as $l) $o .= '<option value="' . $l . '">' . $l . '</option>';
    return '<select class="ls-in ls-sel" data-key="' . $key . '" data-ans="' . $ans . '">' . $o . '</select>';
}
function ls_tapescript(array $t): string {
    $h = '';
    if (!empty($t['intro'])) $h .= '<p class="fst-italic">' . htmlspecialchars($t['intro']) . '</p>';
    foreach ($t['turns'] as [$who, $text]) {
        $h .= '<p class="mb-2">' . ($who !== '' ? '<strong>' . htmlspecialchars($who) . '</strong>&nbsp; ' : '') . htmlspecialchars($text) . '</p>';
    }
    return $h;
}
function ls_speak_text(array $t): string {
    $s = $t['intro'] ?? '';
    foreach ($t['turns'] as [, $text]) $s .= ' ' . $text;
    return trim($s);
}
function ls_task_open(string $id, string $title, string $meta, string $instr, string $tip): void { ?>
    <section class="ls-task" id="task-<?= $id ?>" data-task="<?= $id ?>">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
            <div>
                <h4 class="mb-0"><?= htmlspecialchars($title) ?></h4>
                <div class="text-muted small"><?= htmlspecialchars($meta) ?></div>
            </div>
            <div class="ls-tools">
                <button type="button" class="btn btn-outline-secondary btn-sm ls-listen" data-target="<?= $id ?>"><i class="bi bi-volume-up me-1"></i>Listen (computer voice)</button>
            </div>
        </div>
        <div class="ls-tip"><i class="bi bi-lightbulb me-1"></i><?= $tip ?></div>
        <p class="small text-secondary mb-3"><?= $instr ?></p>
<?php }
function ls_task_close(string $id, int $total, array $tapescript): void { ?>
        <div class="d-flex align-items-center gap-3 mt-3 flex-wrap">
            <button type="button" class="btn btn-success btn-sm ls-check" data-task="<?= $id ?>"><i class="bi bi-check2-circle me-1"></i>Check answers</button>
            <span class="ls-score fw-semibold" id="score-<?= $id ?>" data-total="<?= $total ?>"></span>
        </div>
        <div class="ls-script d-none" id="script-<?= $id ?>" data-speak="<?= htmlspecialchars(ls_speak_text($tapescript)) ?>">
            <div class="fw-semibold mb-2"><i class="bi bi-card-text me-1"></i>Tapescript</div>
            <?= ls_tapescript($tapescript) ?>
        </div>
    </section>
<?php }
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Listening – Question Formats Sample Set | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .ls-task { background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:1.5rem; margin-bottom:1.5rem; }
        .ls-tip { background:#f0fdf4; border-left:3px solid #10b981; padding:.5rem .8rem; font-size:.85rem; color:#065f46; border-radius:6px; margin-bottom:.9rem; }
        .ls-in { border:1.5px solid #d1d5db; border-radius:6px; padding:.25rem .6rem; font-size:.9rem; }
        .ls-in:focus { border-color:#10b981; outline:none; }
        .ls-in.correct { border-color:#10b981; background:#f0fdf4; }
        .ls-in.wrong { border-color:#ef4444; background:#fff1f2; }
        .ls-hint { font-size:.78rem; color:#ef4444; margin-left:.4rem; }
        .ls-row { display:flex; flex-wrap:wrap; align-items:center; gap:.4rem; padding:.4rem 0; border-bottom:1px solid #f3f4f6; font-size:.92rem; }
        .ls-num { font-weight:700; color:#10b981; min-width:26px; }
        .ls-box { display:flex; flex-wrap:wrap; gap:.4rem 1rem; background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; padding:.7rem 1rem; margin-bottom:1rem; font-size:.9rem; }
        .ls-opt { display:block; padding:.25rem .5rem; border-radius:6px; cursor:pointer; font-size:.9rem; }
        .ls-opt.correct-answer { background:#dcfce7; } .ls-opt.wrong-selected { background:#fee2e2; }
        .ls-script { background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:1rem 1.2rem; margin-top:1rem; font-size:.88rem; line-height:1.6; }
        .ls-form { border:1.5px solid #374151; border-radius:8px; padding:1rem 1.25rem; max-width:640px; }
        .ls-form h5 { font-family:Georgia,serif; text-align:center; margin-bottom:1rem; }
        .ls-fig { max-width:100%; height:auto; border:1px solid #e5e7eb; border-radius:8px; background:#fff; }
    </style>
</head>
<body class="light">
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>
    <div class="main-wrapper flex-grow-1" style="flex:1;">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>
        <main class="content p-4">
            <div style="max-width:900px;">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="../../courses/courses_catalogue.php">Courses</a></li>
                        <li class="breadcrumb-item active">IELTS Listening – Question Formats</li>
                    </ol>
                </nav>
                <div style="display:inline-block;padding:.12rem .6rem;border-radius:999px;font-size:.68rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;background:#e0f2fe;color:#075985;margin-bottom:.4rem;">Lesson</div>
                <h1 style="font-size:1.6rem;font-weight:700;margin-bottom:.25rem;">IELTS Listening – Question Formats</h1>
                <p class="text-muted mb-2">Seven official IELTS sample tasks, one for each common question format. Work through each one, check your answers, then read the tapescript to see where the answer — and the distractor — came from.</p>
                <p class="small text-secondary mb-4"><i class="bi bi-info-circle me-1"></i>This is a <strong>lesson worksheet</strong>, not a test: these are practice tasks, not timed, and your answers are not saved. Where a task has a <em>Listen</em> button it is a computer voice reading the official tapescript, not the real recording — your instructor may also read the tapescript aloud in class.</p>

<?php
// ── 1. Form completion ────────────────────────────────────────────────
ls_task_open('form', 'Form completion', 'Section 1 · Questions 1–8',
    'Complete the form below. Write <strong>NO MORE THAN THREE WORDS AND/OR A NUMBER</strong> for each answer.',
    'Before the audio starts, look at what each gap needs: a name, a place, a postcode, a measurement, an amount. Names are usually spelled out — write exactly what you hear.'); ?>
        <div class="ls-form">
            <h5>PACKHAM’S SHIPPING AGENCY – customer quotation form</h5>
            <div class="ls-row"><em>Example</em>&nbsp; Country of destination: <strong>Kenya</strong></div>
            <div class="ls-row">Name: Jacob <span class="ls-num">1</span><?= ls_input('f1', 'mkere') ?></div>
            <div class="ls-row">Address to be collected from: <span class="ls-num">2</span><?= ls_input('f2', 'westall') ?> College, Downlands Rd</div>
            <div class="ls-row">Town: Bristol</div>
            <div class="ls-row">Postcode: <span class="ls-num">3</span><?= ls_input('f3', 'bs89pu') ?></div>
            <div class="mt-2 mb-1">Size of container:</div>
            <img class="ls-fig mb-2" src="<?= $img ?>container.png" alt="Diagram of a box: length 1.5 m, with width and height to be completed">
            <div class="ls-row">Width: <span class="ls-num">4</span><?= ls_input('f4', '0.75|0.75m|0.75metre|0.75metres|0.75meter|0.75meters|75cm|75cms|¾m|¾|threequarterofametre|threequartersofametre|threequarterofameter|threequartersofameter|0.75mwide|0.75metrewide|0.75metreswide|0.75meterwide|0.75meterswide|75cmwide|75cmswide|¾mwide', '', '110px') ?>
                &nbsp; Height: <span class="ls-num">5</span><?= ls_input('f5', '0.5|0.5m|0.5metre|0.5metres|0.5meter|0.5meters|50cm|50cms|halfametre|halfameter|½m|½|0.5mhigh|0.5mdeep|0.5metrehigh|0.5metredeep|0.5metreshigh|0.5metresdeep|0.5meterhigh|0.5meterdeep|0.5metershigh|0.5metersdeep|50cmhigh|50cmdeep|50cmshigh|50cmsdeep|½mhigh|½mdeep', '', '110px') ?></div>
            <div class="ls-row">Contents: clothes</div>
            <div class="ls-row"><span class="ls-num">6</span><?= ls_input('f6', '', 'g-f67') ?></div>
            <div class="ls-row"><span class="ls-num">7</span><?= ls_input('f7', '', 'g-f67') ?></div>
            <div class="ls-row">Total estimated value: <span class="ls-num">8</span> £ <?= ls_input('f8', '1700|1,700', '', '110px') ?></div>
        </div>
<?php ls_task_close('form', 8, $tapescripts['115005']);

// ── 2. Multiple choice ────────────────────────────────────────────────
ls_task_open('mc', 'Multiple choice', 'Section 1 · Questions 9–10',
    'Choose the correct letter, <strong>A</strong>, <strong>B</strong> or <strong>C</strong>.',
    'All three options are usually mentioned in the audio. The answer is the one the speaker finally confirms — listen for the change of mind, not the first thing said.'); ?>
        <?php foreach ([
            ['m9', 'C', '9', 'Type of insurance chosen', ['A' => 'Economy', 'B' => 'Standard', 'C' => 'Premium']],
            ['m10', 'A', '10', 'Customer wants goods delivered to', ['A' => 'port', 'B' => 'home', 'C' => 'depot']],
        ] as [$k, $ans, $n, $text, $opts]): ?>
        <div class="mb-3" data-key="<?= $k ?>" data-ans="<?= $ans ?>" data-kind="radio">
            <p class="mb-1 fw-semibold"><span class="ls-num"><?= $n ?></span> <?= $text ?></p>
            <?php foreach ($opts as $l => $o): ?>
            <label class="ls-opt" data-letter="<?= $l ?>"><input type="radio" name="<?= $k ?>" value="<?= $l ?>" class="me-2"><strong><?= $l ?></strong>&nbsp; <?= $o ?></label>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
<?php ls_task_close('mc', 2, $tapescripts['115008']);

// ── 3. Matching (options box) ─────────────────────────────────────────
ls_task_open('match1', 'Matching — choose from a box', 'Section 1 · Questions 1–4',
    'Which hotel matches each description? Choose your answers from the box and write the correct letter <strong>A–E</strong> next to Questions 1–4.',
    'Every option in the box is discussed, so you must connect each feature to the right item. Listen for the feature words (rural, recently, business, indoor) and which hotel they are attached to.'); ?>
        <div class="ls-box"><span><strong>A</strong> The Bridge Hotel</span><span><strong>B</strong> Carlton House</span><span><strong>C</strong> The Imperial</span><span><strong>D</strong> The Majestic</span><span><strong>E</strong> The Royal Oak</span></div>
        <?php foreach ([['h1','E','1','is in a rural area'],['h2','B','2','only opened recently'],['h3','C','3','offers facilities for business functions'],['h4','A','4','has an indoor swimming pool']] as [$k,$a,$n,$t]): ?>
        <div class="ls-row"><span class="ls-num"><?= $n ?></span><span style="flex:1"><?= $t ?></span><?= ls_select($k, $a, ['A','B','C','D','E']) ?></div>
        <?php endforeach; ?>
<?php ls_task_close('match1', 4, $tapescripts['115007']);

// ── 4. Matching (A/B/C reused) ────────────────────────────────────────
ls_task_open('match2', 'Matching — letters can be reused', 'Section 3 · Questions 21–25',
    'What does Jack tell his tutor about each of the following course options? Write the correct letter, <strong>A</strong>, <strong>B</strong> or <strong>C</strong> next to Questions 21–25. You may choose any letter more than once.',
    'Here the choices are opinions (will / might / won’t), not items. Listen for the speaker’s reaction to each course, and expect a change of mind partway through a sentence.'); ?>
        <div class="ls-box flex-column"><span><strong>A</strong> He’ll definitely do it.</span><span><strong>B</strong> He may or may not do it.</span><span><strong>C</strong> He won’t do it.</span></div>
        <?php foreach ([['j21','C','21','Media Studies'],['j22','A','22','Women and Power'],['j23','B','23','Culture and Society'],['j24','B','24','Identity and Popular Culture'],['j25','C','25','Introduction to Cultural Theory']] as [$k,$a,$n,$t]): ?>
        <div class="ls-row"><span class="ls-num"><?= $n ?></span><span style="flex:1"><?= $t ?></span><?= ls_select($k, $a, ['A','B','C']) ?></div>
        <?php endforeach; ?>
<?php ls_task_close('match2', 5, $tapescripts['115006']);

// ── 5. Plan labelling ─────────────────────────────────────────────────
ls_task_open('plan', 'Plan / map / diagram labelling', 'Section 2 · Questions 11–15',
    'Label the plan below. Choose <strong>FIVE</strong> answers from the box and write the correct letters <strong>A–I</strong> next to Questions 11–15.',
    'Find the entrance on the plan first — the speaker walks you through in order. Follow the direction words: <em>on your right, opposite, just beyond, through the far door, next door to</em>.'); ?>
        <div class="row g-3">
            <div class="col-md-7"><img class="ls-fig" src="<?= $img ?>library_plan.png" alt="Plan of a town library with rooms numbered 11 to 15"></div>
            <div class="col-md-5">
                <div class="ls-box flex-column"><span><strong>A</strong> Art collection</span><span><strong>B</strong> Children’s books</span><span><strong>C</strong> Computers</span><span><strong>D</strong> Local history collection</span><span><strong>E</strong> Meeting room</span><span><strong>F</strong> Multimedia</span><span><strong>G</strong> Periodicals</span><span><strong>H</strong> Reference books</span><span><strong>I</strong> Tourist information</span></div>
                <?php foreach ([['p11','H','11'],['p12','G','12'],['p13','D','13'],['p14','B','14'],['p15','F','15']] as [$k,$a,$n]): ?>
                <div class="ls-row"><span class="ls-num"><?= $n ?></span><?= ls_select($k, $a, ['A','B','C','D','E','F','G','H','I']) ?></div>
                <?php endforeach; ?>
            </div>
        </div>
<?php ls_task_close('plan', 5, $tapescripts['115009']);

// ── 6. Sentence completion ────────────────────────────────────────────
ls_task_open('sent', 'Sentence completion', 'Section 3 · Questions 27–30',
    'Complete the sentences below. Write <strong>NO MORE THAN TWO WORDS</strong> for each answer.',
    'Read the sentences first and decide what kind of word fits each gap (a noun? a skill?). The audio paraphrases the sentence, so the words you write come from the recording but the surrounding words will differ.'); ?>
        <div class="ls-row"><span class="ls-num">27</span>Studying with the Open University demanded a great deal of <?= ls_input('s27', 'motivation') ?>.</div>
        <div class="ls-row"><span class="ls-num">28</span>Studying and working at the same time improved Rachel’s <?= ls_input('s28', 'timemanagement') ?> skills.</div>
        <div class="ls-row"><span class="ls-num">29</span>It was helpful that the course was structured in <?= ls_input('s29', 'modules') ?>.</div>
        <div class="ls-row"><span class="ls-num">30</span>She enjoyed meeting other students at <?= ls_input('s30', 'summerschool|summerschools') ?>.</div>
<?php ls_task_close('sent', 4, $tapescripts['115010']);

// ── 7. Short answer ───────────────────────────────────────────────────
ls_task_open('short', 'Short-answer questions', 'Section 2 · Questions 11–16',
    'Answer the questions below. Write <strong>NO MORE THAN THREE WORDS AND/OR A NUMBER</strong> for each answer.',
    'Each question asks for a set number of items — here, two each. The two answers in a pair can come in either order, and you only score if you give two <em>different</em> answers.'); ?>
        <p class="fw-semibold mb-1">What TWO factors can make social contact in a foreign country difficult?</p>
        <div class="ls-row"><span class="ls-num">11</span><?= ls_input('q11', '', 'g-s1112') ?></div>
        <div class="ls-row"><span class="ls-num">12</span><?= ls_input('q12', '', 'g-s1112') ?></div>
        <p class="fw-semibold mt-3 mb-1">Which types of community group does the speaker give examples of?</p>
        <div class="ls-row">• theatre</div>
        <div class="ls-row"><span class="ls-num">13</span><?= ls_input('q13', '', 'g-s1314') ?></div>
        <div class="ls-row"><span class="ls-num">14</span><?= ls_input('q14', '', 'g-s1314') ?></div>
        <p class="fw-semibold mt-3 mb-1">In which TWO places can information about community activities be found?</p>
        <div class="ls-row"><span class="ls-num">15</span><?= ls_input('q15', '', 'g-s1516') ?></div>
        <div class="ls-row"><span class="ls-num">16</span><?= ls_input('q16', '', 'g-s1516') ?></div>
<?php ls_task_close('short', 6, $tapescripts['115011']); ?>
            </div>
        </main>
    </div>

    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <?php include INCLUDES_PATH . '/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // "Either order" groups: each input must match a different acceptable-answer set.
    const GROUPS = {
        'g-f67':   [['books', 'somebooks'], ['toys', 'sometoys']],
        'g-s1112': [['language'], ['customs']],
        'g-s1314': [['music', 'musicgroups', 'musicgroup'], ['localhistory', 'localhistorygroups', 'localhistorygroup']],
        'g-s1516': [['library', 'thelibrary', 'publiclibrary', 'thepubliclibrary', 'libraries', 'publiclibraries'],
                    ['townhall', 'thetownhall']],
    };
    const norm = s => (s || '').toLowerCase().replace(/[\s,\-()£]/g, '').replace(/\.$/, '');

    function markInput(el, ok, shown) {
        el.classList.remove('correct', 'wrong');
        el.classList.add(ok ? 'correct' : 'wrong');
        el.disabled = true;
        if (!ok && shown) {
            const h = document.createElement('span');
            h.className = 'ls-hint';
            h.textContent = '✓ ' + shown;
            el.insertAdjacentElement('afterend', h);
        }
    }

    document.querySelectorAll('.ls-check').forEach(btn => btn.addEventListener('click', () => {
        const task = document.getElementById('task-' + btn.dataset.task);
        let right = 0;

        // Single-answer boxes and dropdowns
        task.querySelectorAll('.ls-in[data-ans]').forEach(el => {
            const accepted = el.dataset.ans.split('|').map(norm);
            const ok = el.classList.contains('ls-sel') ? el.value === el.dataset.ans : accepted.includes(norm(el.value));
            if (ok) right++;
            markInput(el, ok, el.classList.contains('ls-sel') ? el.dataset.ans : el.dataset.ans.split('|')[0]);
        });

        // Either-order groups
        const seen = {};
        task.querySelectorAll('.ls-in[data-group]').forEach(el => (seen[el.dataset.group] = seen[el.dataset.group] || []).push(el));
        Object.entries(seen).forEach(([g, els]) => {
            const used = new Set();
            els.forEach(el => {
                const idx = GROUPS[g].findIndex((set, i) => !used.has(i) && set.includes(norm(el.value)));
                if (idx >= 0) { used.add(idx); right++; }
                markInput(el, idx >= 0, null);
            });
            if (used.size < els.length) {
                const missing = GROUPS[g].filter((_, i) => !used.has(i)).map(s => s[0]).join(' / ');
                const h = document.createElement('div');
                h.className = 'ls-hint ms-4';
                h.textContent = '✓ Answers: ' + GROUPS[g].map(s => s[0]).join(' and ') + ' (either order)';
                els[els.length - 1].closest('.ls-row').after(h);
            }
        });

        // Multiple-choice radios
        task.querySelectorAll('[data-kind="radio"]').forEach(q => {
            const picked = q.querySelector('input:checked');
            const ok = picked && picked.value === q.dataset.ans;
            if (ok) right++;
            q.querySelectorAll('.ls-opt').forEach(l => {
                l.querySelector('input').disabled = true;
                if (l.dataset.letter === q.dataset.ans) l.classList.add('correct-answer');
                else if (l.querySelector('input').checked) l.classList.add('wrong-selected');
            });
        });

        const total = +document.getElementById('score-' + btn.dataset.task).dataset.total;
        const out = document.getElementById('score-' + btn.dataset.task);
        out.textContent = right + ' / ' + total + ' correct';
        out.style.color = right === total ? '#059669' : '#374151';
        btn.disabled = true;
        document.getElementById('script-' + btn.dataset.task).classList.remove('d-none');
    }));

    // Computer-voice read-through of the tapescript (no official recording supplied).
    const synth = window.speechSynthesis;
    let speakingBtn = null;
    function stopSpeaking() {
        if (synth) synth.cancel();
        if (speakingBtn) { speakingBtn.innerHTML = '<i class="bi bi-volume-up me-1"></i>Listen (computer voice)'; speakingBtn = null; }
    }
    document.querySelectorAll('.ls-listen').forEach(btn => {
        if (!synth) { btn.remove(); return; }
        btn.addEventListener('click', () => {
            const wasMe = speakingBtn === btn;
            stopSpeaking();
            if (wasMe) return;
            const text = document.getElementById('script-' + btn.dataset.target).dataset.speak;
            const chunks = text.match(/[^.!?…]+[.!?…]*/g) || [text];
            speakingBtn = btn;
            btn.innerHTML = '<i class="bi bi-stop-circle me-1"></i>Stop';
            const voice = synth.getVoices().find(v => /en[-_]GB/i.test(v.lang)) || synth.getVoices().find(v => /^en/i.test(v.lang));
            chunks.forEach((c, i) => {
                const u = new SpeechSynthesisUtterance(c.trim());
                u.lang = 'en-GB'; u.rate = 0.92;
                if (voice) u.voice = voice;
                if (i === chunks.length - 1) u.onend = stopSpeaking;
                synth.speak(u);
            });
        });
    });
    window.addEventListener('beforeunload', () => { if (synth) synth.cancel(); });
    </script>
</body>
</html>
