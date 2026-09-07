<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}

$session_id = (int)($_GET['session_id'] ?? 0);
if (!$session_id) {
    header("Location: mock_start.php");
    exit();
}

$student_id  = (int)$_SESSION['user_id'];
$adminEmails = ['v.animasahun@slslanguage.com', 'animasahunvictor1@gmail.com', 'ashonibarevik@gmail.com'];
$isAdmin     = in_array($_SESSION['user_email'] ?? '', $adminEmails);

$stmt = $db->prepare("
    SELECT ms.*, t.title AS mock_title, t.code AS mock_code
    FROM mock_sessions ms
    JOIN tests t ON t.id = ms.mock_test_id
    WHERE ms.id = ? AND ms.student_id = ?
");
$stmt->execute([$session_id, $student_id]);
$session = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$session || $session['status'] !== 'in_progress') {
    header("Location: mock_start.php");
    exit();
}

// Admins can revisit freely; students are forwarded once done
if (!$isAdmin && !is_null($session['reading_attempt_id'])) {
    $map  = require INCLUDES_PATH . '/mock_test_map.php';
    $file = $map[$session['mock_code']]['writing']['file'] ?? 'mock_writing.php';
    header("Location: {$file}?session_id={$session_id}");
    exit();
}

// Students must complete listening first; admins can skip ahead
if (!$isAdmin && is_null($session['listening_attempt_id'])) {
    $map  = require INCLUDES_PATH . '/mock_test_map.php';
    $file = $map[$session['mock_code']]['listening']['file'] ?? 'mock_start.php';
    header("Location: {$file}?session_id={$session_id}");
    exit();
}

// Load questions from DB
$map      = require INCLUDES_PATH . '/mock_test_map.php';
$testCode = $map[$session['mock_code']]['reading']['test_code'] ?? '';

$stmt = $db->prepare("SELECT id FROM tests WHERE code = ? AND is_active = 1 LIMIT 1");
$stmt->execute([$testCode]);
$test = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$test) {
    die("Reading test not configured. Please contact support.");
}
$test_id = (int)$test['id'];

$stmt = $db->prepare("
    SELECT q.id, q.question_number, q.question_type, q.question_text, q.instructions, q.part_number, q.stimulus_text
    FROM questions q
    WHERE q.test_id = ?
    ORDER BY q.question_number
");
$stmt->execute([$test_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("
    SELECT qo.question_id, qo.option_label, qo.option_text, qo.display_order
    FROM question_options qo
    JOIN questions q ON q.id = qo.question_id
    WHERE q.test_id = ?
    ORDER BY q.question_number, qo.display_order
");
$stmt->execute([$test_id]);
$optionsRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);
$options = [];
foreach ($optionsRaw as $o) {
    $options[(int)$o['question_id']][] = $o;
}

// Only render questions that have actual content entered
$questions = array_values(array_filter($questions, fn($q) => trim($q['question_text'] ?? '') !== ''));

// Group by part (section)
$parts = [];
foreach ($questions as $q) {
    $parts[(int)($q['part_number'] ?? 1)][] = $q;
}
ksort($parts);

$DURATION_SECS = 60 * 60; // 60 minutes

// ── Passage renderer ─────────────────────────────────────────────────────────
// Handles:
//   - First non-empty line → passage title
//   - "A  Short heading" (letter + 2+ spaces, short text) → bold lettered heading
//   - "A  Long paragraph..." (letter + 2+ spaces, long text) → plain lettered
//     paragraph (whole-paragraph-per-letter passages, e.g. Test 3's "Maps" and
//     "Feathers" texts, where there's no separate heading/body split)
//   - Tab-indented lines → body paragraph under a heading
//   - Plain lines → standard paragraphs (e.g. unlabelled continuation
//     paragraphs that belong to the previous lettered section)
function renderPassage(string $text): string {
    $lines = explode("\n", $text);
    $html  = '';
    $first = true;
    foreach ($lines as $raw) {
        $trimmed = trim($raw);
        if ($trimmed === '') continue;

        if ($first) {
            $html .= '<p class="passage-title-main">' . htmlspecialchars($trimmed) . '</p>';
            $first = false;
            continue;
        }

        // Lettered section: "A  Rib riding" (heading) or "A  Today, we do not..." (full paragraph)
        if (preg_match('/^([A-H])\s{2,}(.+)$/', $trimmed, $m)) {
            if (strlen($m[2]) > 60) {
                // Long form: whole paragraph immediately after the letter label
                $html .= '<p style="margin-top:.9rem;margin-bottom:.55rem;">'
                       . '<span class="para-label">' . htmlspecialchars($m[1]) . '</span>'
                       . htmlspecialchars($m[2]) . '</p>';
            } else {
                // Short form: bold heading, body expected on a following tab-indented line
                $html .= '<p class="para-heading" style="margin-top:.9rem;margin-bottom:.15rem;">'
                       . '<span class="para-label">' . htmlspecialchars($m[1]) . '</span>'
                       . '<strong>' . htmlspecialchars($m[2]) . '</strong></p>';
            }
            continue;
        }

        // Tab-indented body (continuation under a heading)
        if (str_starts_with($raw, "\t") || str_starts_with($raw, '    ')) {
            $html .= '<p class="para-body" style="margin-left:1.1rem;margin-bottom:.55rem;">'
                   . htmlspecialchars($trimmed) . '</p>';
            continue;
        }

        $html .= '<p>' . htmlspecialchars($trimmed) . '</p>';
    }
    return $html;
}

// ── Reading passages ─────────────────────────────────────────────────────────
// Keyed by the FIRST question number that uses each passage.
// Section 1: Q1–5  "Maps showing walks starting from Bingham Town Hall" (paragraph matching A–E)
//            Q6–14 "The Maplehampton scarecrow competition"             (TRUE/FALSE/NOT GIVEN)
// Section 2: Q15–22 "Qualities that make a great barista"               (note completion, ONE WORD ONLY)
//            Q23–27 "Running a meeting"                                  (flow-chart completion, NO MORE THAN TWO WORDS)
// Section 3: Q28–40 "Feathers as decoration in European history"        (heading matching + MCQ + sentence completion)
$passages = [

    // ── Section 1 · Passage 1 ────────────────────────────────────────────────
    1 => <<<'PASSAGE'
Maps showing walks starting from Bingham Town Hall

A  The walk described in this leaflet takes you to one of the many places in the district where bricks were made for hundreds of years, until it was closed in the late 19th century. This brickworks is now the largest and best-known nature reserve in the area. Please note that the ground is very uneven, and under-sixes should not be taken on this walk.

B  This walk will take you to the top of Burley Hill, along a nice easy path that people of all ages will be able to manage. From the summit you can see for a great distance to the north and west, across a landscape that includes half a dozen lakes and the entrance to Butter Caves. Bear in mind, though, that mist often comes in from the sea and covers the hilltop.

C  This route leads you through the village of Cottesloe, which was created in the 1930s and is famous for its strange-looking houses and ceramics factory, which is still the largest employer in the area. An artificial lake was originally created beside the village, and has since been filled in and turned into an adventure playground. After you leave Cottesloe, you have a choice of routes to return to the starting point, so either via Thurley Park, or if it's raining, take the shorter direct route.

D  This walk is ideal in fine weather, as it takes you to the shore of a lake, at a spot convenient for swimming. Children will want to enjoy themselves in the adventure playground nearby. From there you continue to Starling Cottage, which draws people from around the world to visit the home, from 1920 to 1927, of the poet Barbara Cottam.

E  If you want an easy, undemanding walk over flat ground, this walk will suit you perfectly. It passes the entrance to the famous Butter Caves visitor attraction, so you can combine a visit there with the walk, or just take shelter if it starts raining! On the final stage of the walk you pass through Wimpole, the village where Richard Merton, the architect of a number of local buildings, lived for much of his life.
PASSAGE,

    // ── Section 1 · Passage 2 ────────────────────────────────────────────────
    6 => <<<'PASSAGE'
The Maplehampton scarecrow competition – a great success!

There was once a time when farmers all over the country put scarecrows in fields of growing crops. A traditional scarecrow was a model – usually life-size – of a man or woman dressed in old clothes, and their purpose was to frighten the birds away; though how successful they were is a matter of opinion!

Maplehampton's scarecrow competition took place on September 12th. Local farmers supplied everything needed to make a scarecrow – like pieces of wood to form a frame, and straw to stuff the scarecrow. The scarecrows were dressed in old clothes which the competitors brought with them.

The festival was held in the village hall, instead of outdoors as planned, due to the unusually high temperature. There were two classes, one for adults and one for children, all of them working in small teams. Over 20 teams took part, each creating one scarecrow. They were encouraged by an audience of around 50, and had ideas and guidance from local artist Tracey Sanzo.

The scarecrows were judged by a team of people from the village. The winning children's team made a scarecrow that looked like a giant bird – which would surely keep every real bird away! The winning adult team's scarecrow was dressed as an alien from another planet, and its face was painted to make it look very frightening – at least to human beings!

After the judging, many of the participants and the spectators had a picnic which they had brought. Some of the scarecrows then went home to their creators' gardens. Alice Cameron, a local farmer, liked one of the scarecrows so much, she bought it to stand on her balcony: she said she didn't need it to scare birds away from her crops, as only bird-scarers that made a noise were effective. She just wanted to be able to see it!

The event raised over £300 for village funds.
PASSAGE,

    // ── Section 2 · Passage 1 ────────────────────────────────────────────────
    15 => <<<'PASSAGE'
Qualities that make a great barista

How to become a great maker and server of espresso-based coffee drinks

Truly great baristas take the time to develop the key skills that will enable them to deliver the highest possible quality of coffee-based beverage and service. As a barista, you must make a concerted effort to listen to your clientele and make sure the drinks you produce are correct in all respects. This is particularly important when you consider the sheer range and complexity of modern coffee drinks, which may start from a single (or double) shot of espresso but can include many additional elements. If you become distracted by the conversation that is going on nearby, you may ultimately miss the mark from a service perspective.

One thing that separates a great from a good barista is that the former is constantly busy and has a strong work ethic. You will often catch a great barista rinsing out the filter in their machines, for example, as this erodes the build-up of burnt coffee oil that can begin to impact on the quality and taste of each espresso shot. Similarly, do not be surprised to hear the sound of the coffee grinder at work. This highlights the keen attention to detail that distinguishes skilled baristas, as they have the desire and the awareness to make every drink with completely fresh ground coffee. This type of attentiveness helps baristas to get the most from the coffee that they use, as many of the delicate aromas found in espresso are lost when exposed to the open air.

Timing is everything when it comes to producing the perfect cup of coffee. A great barista knows precisely when to finish the extraction of espresso, at the point when the balance of flavour has reached its optimum levels. They also understand how important this is; those who act too soon are left with a drink without flavour while those who delay the finish risk burning the beverage and tainting it with a bitter after-taste.

When it comes to customer service, there is so much more to a coffee shop experience than drinking perfectly roasted blends. The atmosphere and the ambience also play a central role, and the interaction that the customer has with their barista sets the tone for an enjoyable experience. Great baristas ask their customers how their day is going or what they're going to do later; they read local newspapers and keep up with issues that really matter, all of which make a real difference in a competitive marketplace.
PASSAGE,

    // ── Section 2 · Passage 2 ────────────────────────────────────────────────
    23 => <<<'PASSAGE'
Running a meeting

If you're running a meeting for the first time, here are a few tips to help you

Prior to the meeting, think about the seating and arrange it in an appropriate way. A circle can work well for informal meetings, but sometimes the furniture cannot be re-arranged or rows are more suitable. Consider the participants and decide what is best. Before people arrive, it's a good idea to designate someone to stand at the entrance and greet everyone.

If the meeting is small, start by requesting everyone to introduce themselves and to give a bit of relevant information in addition to their name. This may be what they do or why they are there. For all meetings, you need to introduce the chairperson, i.e., yourself, and any other outside speakers you have invited.

Next, make sure everyone can see the agenda or has a copy of it. Briefly run through the items then take one point at a time, and make sure the group doesn't stray from that point until it has been dealt with. Encourage participation at all times so that attendees can contribute but don't let everyone talk at the same time. Try to keep discussions positive, but don't ignore conflicts – find a solution for them and make sure they are resolved before they grow.

Summarise points regularly and make clear action points. Write these down and don't forget to note who's doing what, and by when. Encourage everyone to feel able to volunteer for tasks and roles. It can help if the more experienced members of the group offer to share skills and knowledge, but don't let the same people take on all the work as this can lead to tension within the group.

At the end, remember to thank everyone for turning up and contributing. It can be nice to follow the meeting with a social activity like sharing a meal or going to a café.
PASSAGE,

    // ── Section 3 · Passage (Q28–40, shared) ────────────────────────────────
    28 => <<<'PASSAGE'
Feathers as decoration in European history

A  Today, we do not generally associate feathers with the military in Europe, yet history shows that in fact feathers have played an intriguing role in European military clothing. The Bersaglieri of the Italian Army, for example, still wear a bunch of long black feathers in their hats hanging down to one side, while British fusiliers have a clipped feather plume whose colour varies according to their regiment. The Royalists in the English Civil War adorned their headgear with ostrich feathers. 'Historically, feathers were an incredibly expressive accessory for men,' observes Cambridge historian, Professor Ulinka Rublack. 'Nobody has really looked at why this was the case. That's a story that I want to tell.'

Rublack is beginning to study the use of featherwork in early modern fashion as part of a joint project between the Universities of Cambridge, Basel and Bern. To the outsider, its preoccupations (her co-researchers are studying gold, glass and veils) might seem surprising. Yet such materials sustained significant economies and expertise.

B  Rublack has spotted that something unusual started to happen with feathers during the 16th century. In 1500, they were barely worn at all in Europe; 100 years later they had become an indispensable accessory for the fashionable European man. In prosperous trading centres, the citizens started wearing hats bedecked with feathers from cranes and swallows. Headgear was specially manufactured so that feathers could be inserted more easily. By 1573, Plantin's Flemish–French dictionary was even obliged to offer words to describe people who chose not to wear them, recommending such terms as: 'the featherless' and 'unfeathered'.

Featherworking became big business. From Prague and Nuremberg to Paris and Madrid, people started to make a living from decorating feathers for clothing. Impressive efforts went into dyeing them. A 1548 recipe recommends using ashes, lead monoxide and river water to create a 'very beautiful' black, for example.

C  Why this happened will become clearer as Rublack's project develops. One crucial driver, however, was exploration – the discovery of new lands, especially in South America. Compared with many of the other species that early European colonists encountered, exotic birds could be captured, transported and kept with relative ease. Europe experienced a sudden 'bird-craze', as exotic birds became a relatively common sight in the continent's largest markets.

Given the link with new territories and conquest, ruling elites wore feathers partly to express their power and reach. But there were also more complex reasons. In 1599, for example, Duke Frederick of Württemberg held a display at his court at which he personally appeared wearing a costume covered in exotic feathers and representing the Americas. This was not just a symbol of power, but of cultural connectedness, Rublack suggests: 'The message seems to be that he was embracing the global in a duchy that was quite insular and territorial.'

D  Nor were feathers worn by the powerful alone. In 1530, a legislative assembly at Augsburg imposed restrictions on peasants and traders adopting what it clearly felt should be an elite fashion. The measure did not last, perhaps because health manuals of the era recommended that feathers could keep the wearer safe from 'bad' air – cold, miasma, damp or excessive heat – all of which were regarded as hazardous. During the 1550s, Eleanor of Toledo had hats made from peacock feathers to keep her dry in the rain. Gradually, feathers came to indicate that the wearer was healthy and civilised. Artists and musicians took to wearing them as a mark of subtlety and style.

E  As with most fads, this enthusiasm eventually wore off. By the mid-17th century, feathers were out of style, with one striking exception. Within the armies of Europe feathers remained an essential part of military costume.

Rublack thinks that there may have been several reasons for this strange contradiction. 'It's associated with the notion of graceful warfaring,' she says. 'This was a period when there were no standing armies and it was hard to draft soldiers. One solution was to aestheticise the military, to make it seem graceful and powerful.' Feathers became associated with the idea of an art of warfare.

They were also already a part of military garb among many native American peoples and in the Ottoman empire. Rublack believes that just as some of these cultures considered the feathers of certain birds to be highly significant, and sometimes sacred, European soldiers saw the feathers as imparting noble passions, bravery and courage.

F  In time, her research may therefore reveal a tension about the ongoing use of feathers in this unlikely context. But, as she also notes, she is perhaps the first historian to have spotted the curious emotional resonance of feathers in military fashion at all. All this shows a sea-change in methodologies: historians now chart the ways in which our identities are shaped through deep connections with 'stuff' – the material objects that are parts of our lives.
PASSAGE,

];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reading — <?= htmlspecialchars($session['mock_title']) ?> | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        /* ── Part tabs ──────────────────────────────────────── */
        .part-tabs-bar { display:flex; align-items:center; border-bottom:1px solid #e5e7eb; margin-bottom:1.5rem; }
        .part-tabs-scrollable { display:flex; flex:1; overflow-x:auto; }
        .part-tab-btn { display:flex; flex-direction:column; align-items:center; padding:.5rem 1.1rem; border:none; border-bottom:3px solid transparent; background:transparent; cursor:pointer; font-size:.82rem; font-weight:600; color:#6b7280; transition:all .2s; gap:1px; position:relative; white-space:nowrap; }
        .part-tab-btn:hover { color:#667eea; }
        .part-tab-btn.active { color:#667eea; border-bottom-color:#667eea; }
        .tab-qrange { font-size:.67rem; color:#9ca3af; }
        .part-tab-btn.active .tab-qrange { color:#a5b4fc; }
        .done-dot { position:absolute; top:5px; right:6px; width:7px; height:7px; border-radius:50%; background:#10b981; display:none; }
        .part-tab-btn.all-answered .done-dot { display:block; }
        .inline-timer { display:flex; align-items:center; gap:.35rem; font-size:.88rem; font-weight:700; color:#facc15; background:#1a2236; border-radius:8px; padding:.3rem .85rem; margin-left:auto; flex-shrink:0; white-space:nowrap; }
        .inline-timer.warning { color:#ef4444; animation:blink 1s infinite; }
        @keyframes blink { 0%,100%{opacity:1}50%{opacity:.4} }

        /* ── Layout ─────────────────────────────────────────── */
        .section-content { background:#fff; border-radius:12px; padding:2rem; box-shadow:0 4px 20px rgba(0,0,0,.06); }
        .btn-exit { background:#f3f4f6; border:1.5px solid #e5e7eb; color:#374151; border-radius:6px; padding:.28rem .85rem; font-size:.8rem; font-weight:600; cursor:pointer; transition:all .2s; }
        .btn-exit:hover { background:#ef4444; border-color:#ef4444; color:#fff; }
        .part-panel { display:none; }
        .part-panel.active { display:block; }

        /* ── Passage ────────────────────────────────────────── */
        .passage-box { background:#fafafa; border:1px solid #e5e7eb; border-radius:8px; padding:1.25rem 1.5rem; margin-bottom:1.5rem; max-height:420px; overflow-y:auto; }
        .passage-box p { font-size:.9rem; line-height:1.75; color:#374151; margin-bottom:.55rem; }
        .passage-box .passage-title-main { font-size:1rem; font-weight:700; color:#111827; margin-bottom:.85rem; }
        .passage-box .para-label { font-weight:700; color:#374151; margin-right:.4rem; }

        /* ── Question blocks ────────────────────────────────── */
        .q-instructions { background:#eff6ff; border-left:3px solid #667eea; border-radius:0 6px 6px 0; padding:.6rem 1rem; font-size:.875rem; color:#1e40af; margin-bottom:1rem; line-height:1.5; }
        .q-block { margin-bottom:1.25rem; }
        .q-badge { display:inline-flex; align-items:center; justify-content:center; background:#667eea; color:#fff; font-size:.68rem; font-weight:700; border-radius:4px; min-width:20px; height:18px; padding:0 4px; margin-right:4px; vertical-align:middle; }
        .q-text { font-size:.9rem; color:#1f2937; line-height:1.6; }

        /* TRUE/FALSE/NOT GIVEN */
        .tfng-wrap { display:flex; gap:.5rem; flex-wrap:wrap; margin-top:.4rem; }
        .tfng-opt { display:flex; align-items:center; gap:.35rem; padding:.3rem .75rem; border:1.5px solid #d1d5db; border-radius:6px; cursor:pointer; font-size:.82rem; font-weight:600; color:#374151; transition:all .15s; }
        .tfng-opt:has(input:checked) { background:#667eea; border-color:#667eea; color:#fff; }

        /* MCQ */
        .mc-option { display:flex; align-items:flex-start; gap:.5rem; padding:.35rem .65rem; border-radius:7px; cursor:pointer; font-size:.85rem; color:#374151; transition:background .15s; margin-bottom:.15rem; line-height:1.4; }
        .mc-option:hover { background:#f3f4f6; }
        .mc-option input { accent-color:#667eea; margin-top:3px; flex-shrink:0; }

        /* Matching dropdown */
        .match-select { border:1.5px solid #d1d5db; border-radius:7px; padding:.3rem .65rem; font-size:.85rem; outline:none; color:#111827; cursor:pointer; transition:border-color .2s; max-width:420px; width:100%; margin-top:.35rem; }
        .match-select:focus { border-color:#667eea; }
        .match-select.answered { border-color:#10b981; color:#059669; }

        /* Text input (sentence / note / summary completion) */
        .ff-input { border:none; border-bottom:2px solid #c4b5fd; outline:none; width:220px; font-size:.87rem; padding:2px 4px; background:transparent; color:#111827; transition:border-color .2s; vertical-align:middle; margin-top:.35rem; display:block; }
        .ff-input:focus { border-bottom-color:#667eea; }
        .ff-input.answered { border-bottom-color:#10b981; }

        /* Submit bar */
        .submit-bar { position:fixed; bottom:0; left:var(--sidebar-w,220px); right:280px; background:#fff; border-top:1px solid #e2e8f0; padding:.85rem 1.5rem; display:flex; justify-content:space-between; align-items:center; gap:1rem; z-index:200; box-shadow:0 -2px 8px rgba(0,0,0,.06); }
        @media (max-width:1399px) { .submit-bar { right:0; } }
        @media (max-width:1199px) { .submit-bar { left:0; right:0; } }
        .progress-steps { display:flex; gap:.5rem; align-items:center; }
        .step { display:flex; align-items:center; gap:.35rem; font-size:.8rem; color:#94a3b8; }
        .step.done { color:#10b981; } .step.current { color:#f59e0b; font-weight:600; }
        .step-dot { width:8px; height:8px; border-radius:50%; background:currentColor; }
        .sticky-header { position:fixed; top:var(--topbar-h,60px); left:var(--sidebar-w,220px); right:280px; z-index:150; background:#f1f5f9; padding:.6rem 1.5rem .5rem; border-bottom:1px solid #e2e8f0; box-shadow:0 2px 6px rgba(0,0,0,.05); }
        @media (max-width:1399px) { .sticky-header { right:0; } }
        @media (max-width:1199px) { .sticky-header { left:0; right:0; } }
        body.sidebar-collapsed .sticky-header { left:0; }
        body.sidebar-collapsed .submit-bar  { left:0; }
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
            <div style="background:#1e1b4b;color:#c7d2fe;padding:.6rem 1.25rem;border-radius:8px;margin-bottom:.5rem;display:flex;align-items:center;gap:1.5rem;font-size:.82rem;font-weight:600;">
                <span style="color:#a5b4fc;text-transform:uppercase;letter-spacing:.08em;font-size:.7rem;">Admin Preview</span>
                <a href="full_mock_003_listening.php?session_id=<?= $session_id ?>" style="color:#a5b4fc;text-decoration:none;">🎧 Listening</a>
                <a href="full_mock_003_reading.php?session_id=<?= $session_id ?>"   style="color:#c7d2fe;text-decoration:none;border-bottom:2px solid #6366f1;padding-bottom:2px;">📖 Reading</a>
                <a href="mock_writing.php?session_id=<?= $session_id ?>"            style="color:#a5b4fc;text-decoration:none;">✍️ Writing</a>
                <a href="mock_speaking.php?session_id=<?= $session_id ?>"           style="color:#a5b4fc;text-decoration:none;">🎤 Speaking</a>
            </div>
            <?php endif; ?>
            <div class="d-flex align-items-center justify-content-between">
                <div class="progress-steps">
                    <div class="step done"><div class="step-dot"></div>Listening</div>
                    <i class="bi bi-chevron-right text-muted" style="font-size:.7rem;"></i>
                    <div class="step current"><div class="step-dot"></div>Reading</div>
                    <i class="bi bi-chevron-right text-muted" style="font-size:.7rem;"></i>
                    <div class="step"><div class="step-dot"></div>Writing</div>
                    <i class="bi bi-chevron-right text-muted" style="font-size:.7rem;"></i>
                    <div class="step"><div class="step-dot"></div>Speaking</div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <small class="text-muted"><?= htmlspecialchars($session['mock_title']) ?></small>
                    <button class="btn-exit" onclick="confirmExit()"><i class="bi bi-box-arrow-right me-1"></i> Exit</button>
                </div>
            </div>
        </div>

        <div class="section-content" style="padding-top:<?= $isAdmin ? '110px' : '60px' ?>;">

            <!-- Section tab bar + timer -->
            <div class="part-tabs-bar">
                <div class="part-tabs-scrollable">
                    <?php
                    $sectionLabels = [1 => 'Section 1', 2 => 'Section 2', 3 => 'Section 3'];
                    foreach ($parts as $pNum => $pqs):
                        $nums = array_column($pqs, 'question_number');
                        $f = min($nums); $l = max($nums);
                    ?>
                    <button class="part-tab-btn <?= $pNum === 1 ? 'active' : '' ?>"
                            id="ptab-<?= $pNum ?>"
                            onclick="switchSection(<?= $pNum ?>, this)">
                        <span class="done-dot"></span>
                        <?= $sectionLabels[$pNum] ?? "Section {$pNum}" ?>
                        <span class="tab-qrange">Q<?= $f ?>–<?= $l ?></span>
                    </button>
                    <?php endforeach; ?>
                </div>
                <div class="inline-timer" id="inlineTimer"><i class="bi bi-clock-fill"></i> 60:00</div>
            </div>

            <?php if (empty($questions)): ?>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                No questions loaded yet. Please run the database migration for this test and contact your instructor.
            </div>
            <?php endif; ?>

            <form id="readingForm">
            <?php foreach ($parts as $partNum => $partQuestions): ?>
            <div class="part-panel <?= $partNum === 1 ? 'active' : '' ?>" id="panel-<?= $partNum ?>">

                <?php
                $prevInstructions = null;
                foreach ($partQuestions as $q):
                    $qid   = (int)$q['id'];
                    $qnum  = (int)$q['question_number'];
                    $qtype = $q['question_type'];
                    $qopts = $options[$qid] ?? [];
                ?>

                <?php // Show passage box at the first question that has one ?>
                <?php if (isset($passages[$qnum])): ?>
                <div class="passage-box"><?= renderPassage($passages[$qnum]) ?></div>
                <?php endif; ?>

                <?php // New instruction bar when instructions change ?>
                <?php if ($q['instructions'] !== $prevInstructions):
                    $prevInstructions = $q['instructions'];
                ?>
                <div class="q-instructions"><?= htmlspecialchars($q['instructions']) ?></div>
                <?php endif; ?>

                <div class="q-block" id="qblock-<?= $qnum ?>">
                    <div style="margin-bottom:.3rem;">
                        <span class="q-badge"><?= $qnum ?></span>
                        <?php if (trim($q['question_text'] ?? '')): ?>
                        <span class="q-text"><?= nl2br(htmlspecialchars($q['question_text'])) ?></span>
                        <?php endif; ?>
                    </div>

                    <?php if (in_array($qtype, ['multiple_choice_single','multiple_choice_multiple'])): ?>
                        <?php foreach ($qopts as $opt): ?>
                        <label class="mc-option">
                            <input type="<?= $qtype === 'multiple_choice_multiple' ? 'checkbox' : 'radio' ?>"
                                   name="answers[<?= $qnum ?>]"
                                   value="<?= htmlspecialchars($opt['option_label']) ?>"
                                   class="answer-field" data-qnum="<?= $qnum ?>">
                            <strong><?= htmlspecialchars($opt['option_label']) ?>.</strong>&nbsp;<?= htmlspecialchars($opt['option_text']) ?>
                        </label>
                        <?php endforeach; ?>

                    <?php elseif (in_array($qtype, ['true_false_not_given','yes_no_not_given'])): ?>
                        <?php $tfOpts = $qtype === 'true_false_not_given'
                                ? ['TRUE','FALSE','NOT GIVEN']
                                : ['YES','NO','NOT GIVEN']; ?>
                        <div class="tfng-wrap">
                            <?php foreach ($tfOpts as $tfo): ?>
                            <label class="tfng-opt">
                                <input type="radio" name="answers[<?= $qnum ?>]" value="<?= $tfo ?>"
                                       class="answer-field" data-qnum="<?= $qnum ?>">
                                <?= $tfo ?>
                            </label>
                            <?php endforeach; ?>
                        </div>

                    <?php elseif ($qtype === 'matching'): ?>
                        <select name="answers[<?= $qnum ?>]"
                                class="match-select answer-field"
                                data-qnum="<?= $qnum ?>"
                                onchange="this.classList.toggle('answered',this.value!=='')">
                            <option value="">— Select —</option>
                            <?php foreach ($qopts as $opt): ?>
                            <option value="<?= htmlspecialchars($opt['option_label']) ?>">
                                <?= htmlspecialchars($opt['option_label']) ?>.&nbsp;<?= htmlspecialchars($opt['option_text']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>

                    <?php else: // sentence_completion, note_completion, summary_completion etc. ?>
                        <input type="text"
                               name="answers[<?= $qnum ?>]"
                               class="ff-input answer-field"
                               data-qnum="<?= $qnum ?>"
                               autocomplete="off"
                               placeholder="Your answer"
                               oninput="this.classList.toggle('answered',this.value.trim()!=='')">
                    <?php endif; ?>
                </div>

                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
            </form>

            <div style="height:80px;"></div>
        </div>
    </main>
</div>

<?php include INCLUDES_PATH . '/adverts.php'; ?>

<div class="submit-bar">
    <div style="display:flex;align-items:center;gap:.5rem;">
        <i class="bi bi-check2-circle text-success fs-5"></i>
        <span id="answeredCount" style="font-size:.9rem;font-weight:600;color:#374151;">0 of <?= count($questions) ?> answered</span>
    </div>
    <button type="button" class="btn btn-primary fw-bold px-4" id="submitBtn" onclick="submitReading()">
        <i class="bi bi-arrow-right-circle me-2"></i>Submit &amp; Continue to Writing
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>

<script>
const DURATION   = <?= $DURATION_SECS ?>;
const SESSION_ID = <?= $session_id ?>;
const totalQs    = <?= count($questions) ?>;
let elapsed      = 0;
let timerInterval;
let submitting   = false;

const timerEl = document.getElementById('inlineTimer');

function fmt(sec) {
    return String(Math.floor(sec/60)).padStart(2,'0') + ':' + String(sec%60).padStart(2,'0');
}
function startTimer() {
    timerEl.querySelector('i').nextSibling.textContent = ' ' + fmt(DURATION);
    timerInterval = setInterval(() => {
        elapsed++;
        const rem = DURATION - elapsed;
        timerEl.querySelector('i').nextSibling.textContent = ' ' + fmt(Math.max(0, rem));
        if (rem <= 300) timerEl.classList.add('warning');
        if (rem <= 0)   { clearInterval(timerInterval); submitReading(true); }
    }, 1000);
}

function switchSection(pNum, btn) {
    document.querySelectorAll('.part-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.part-tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('panel-' + pNum).classList.add('active');
    btn.classList.add('active');
}

function collectAnswers() {
    const ans = {};
    document.querySelectorAll('.answer-field').forEach(el => {
        const n = el.dataset.qnum;
        if (!n) return;
        if (el.type === 'radio'    && el.checked)          ans[n] = el.value;
        if (el.type === 'checkbox' && el.checked)          ans[n] = el.value;
        if (el.tagName === 'SELECT' && el.value)           ans[n] = el.value;
        if (el.type === 'text'     && el.value.trim())     ans[n] = el.value.trim();
    });
    return ans;
}

function updateProgress() {
    const ans   = collectAnswers();
    const count = Object.keys(ans).length;
    <?php foreach ($parts as $pNum => $pqs): ?>
    (function() {
        const pNums   = [<?= implode(',', array_column($pqs,'question_number')) ?>];
        const allDone = pNums.every(n => ans[n]);
        document.getElementById('ptab-<?= $pNum ?>').classList.toggle('all-answered', allDone);
    })();
    <?php endforeach; ?>
    document.getElementById('answeredCount').textContent = count + ' of ' + totalQs + ' answered';
}
document.querySelectorAll('.answer-field').forEach(el => {
    el.addEventListener('input',  updateProgress);
    el.addEventListener('change', updateProgress);
});

function submitReading(auto = false) {
    if (submitting) return;
    const ans     = collectAnswers();
    const missing = totalQs - Object.keys(ans).length;
    if (!auto && missing > 0) {
        if (!confirm(`You have ${missing} unanswered question(s). Submit anyway?`)) return;
    }
    submitting = true;
    clearInterval(timerInterval);
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting…';

    fetch('mock_save_section.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ session_id: SESSION_ID, section: 'reading', time_spent: elapsed, answers: ans })
    })
    .then(r => r.json())
    .then(d => {
        if (d.redirect) { window.location.href = d.redirect; }
        else {
            alert(d.error || 'An error occurred.');
            submitting = false;
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-arrow-right-circle me-2"></i>Submit &amp; Continue to Writing';
        }
    })
    .catch(() => {
        alert('Network error. Please try again.');
        submitting = false;
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-arrow-right-circle me-2"></i>Submit &amp; Continue to Writing';
    });
}

function confirmExit() {
    if (confirm('Exit the test? Your progress will be lost.')) window.location.href = 'index.php';
}

window.addEventListener('beforeunload', e => { if (!submitting) { e.preventDefault(); e.returnValue = ''; } });

startTimer();
updateProgress();
</script>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
