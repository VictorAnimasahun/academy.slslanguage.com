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

// Group by part (passage)
$parts = [];
foreach ($questions as $q) {
    $parts[(int)($q['part_number'] ?? 1)][] = $q;
}
ksort($parts);

$DURATION_SECS = 60 * 60; // 60 minutes

// ── Passage renderer — converts plain-text passage to styled HTML ──────────
function renderPassage(string $text): string {
    $lines  = explode("\n", trim($text));
    $html   = '';
    $first  = true;
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '') continue;

        // Title line (first non-empty line of the passage)
        if ($first) {
            $html .= '<p class="passage-title-main">' . htmlspecialchars($line) . '</p>';
            $first = false;
            continue;
        }

        // Pupil label: single letter + two spaces + name  e.g. "A  Krishnan"
        if (preg_match('/^([A-G])\s{2,}(.+)$/', $line, $m)) {
            $html .= '<p class="pupil-name"><span class="para-label">' . htmlspecialchars($m[1]) . '</span>' . htmlspecialchars($m[2]) . '</p>';
            continue;
        }

        // Paragraph label: e.g. "A  In 2017..."
        if (preg_match('/^([A-G])\s{2}(.+)$/', $line, $m)) {
            $html .= '<p><span class="para-label">' . htmlspecialchars($m[1]) . '</span>' . htmlspecialchars($m[2]) . '</p>';
            continue;
        }

        $html .= '<p>' . htmlspecialchars($line) . '</p>';
    }
    return $html;
}

// ── Reading passages — hardcoded by first question number of each passage ──
$passages = [

    1 => <<<'PASSAGE'
Helping pupils to choose optional subjects when they're aged 14–15: what some pupils say

A  Krishnan
I'm studying Spanish, because it's important to learn foreign languages – and I'm very pleased when I can watch a video in class and understand it. Mr Peckham really pushes us, and offers us extra assignments, to help us improve. That's good for me, because otherwise I'd be quite lazy.

B  Lucy
History is my favourite subject, and it's fascinating to see how what we learn about the past is relevant to what's going on in the world now. It's made me understand much more about politics, for instance. My plan is to study history at university, and maybe go into the diplomatic service, so I can apply a knowledge of history.

C  Mark
Thursdays are my favourite days, because that's when we have computing. It's the high spot of the week for me – I love learning how to program. I began when I was about eight, so when I started doing it at school, I didn't think I'd have any problem with it, but I was quite wrong! When I leave school, I'm going into my family retail business, so sadly I can't see myself becoming a programmer.

D  Violeta
My parents both work in leisure and tourism, and they've always talked about their work a lot at home. I find it fascinating. I'm studying it at school, and the teacher is very knowledgeable, though I think we spend too much time listening to her: I'd like to meet more people working in the sector, and learn from their experience.

E  Walid
I've always been keen on art, so I chose it as an optional subject, though I was afraid the lessons might be a bit dull. I needn't have worried, though – our teacher gets us to do lots of fun things, so there's no risk of getting bored. At the end of the year the class puts on an exhibition for the school, and I'm looking forward to showing some of my work to other people.
PASSAGE,

    7 => <<<'PASSAGE'
It's almost time for the next Ripton Festival!

As usual, the festival will be held in the last weekend of June, this year on Saturday to Monday, 27–29 June. Ever since last year's festival, the committee has been hard at work to make this year's the best ever! The theme is Ripton through the ages. Scenes will be acted out showing how the town has developed since it was first established. But there's also plenty that's up-to-date, from the latest music to local crafts.

The Craft Fair is a regular part of the festival. Come and meet professional artists, designers and craftsmen and women, who will display their jewellery, paintings, ceramics, and much more. They'll also take orders, so if you want one of them to make something especially for you, just ask! You'll get it within a month of the festival ending.

The Saturday barbecue will start at 2 pm and continue until 10 pm, with a bouncy castle for kids. The barbecue will be held in Palmer's Field, or in the town hall if there's rain. Book your tickets now, as they always sell out very quickly! Entry for under 16s is free all day; adults can come for free until 6 pm and pay £5 after that. There'll be live music throughout, with local amateur bands in the afternoon and professional musicians in the evening.

On Sunday we're delighted to introduce an afternoon of boat races, arranged by the Ripton Rowing Club. The spectator area by the bridge has plenty of room to stand and cheer the boats home, in addition to a number of benches. The winners of the races will be presented with trophies by the mayor of Ripton.

All money raised by the festival will go to support the sports clubs in Ripton.
PASSAGE,

    15 => <<<'PASSAGE'
Reducing injuries on the farm

Farms tend to be full of activity. There are always jobs to be done and some tasks require physical manual work. While it is good for people to be active, there are risk factors associated with this, and efforts need to be made to reduce them.

The first risk relates to the carrying of an excessive load or weight. This places undue demands on the spine and can cause permanent damage. Examples of tasks that involve this risk are moving 50-kilogramme fertiliser bags from one site to another or carrying heavy buckets of animal feed around fields. According to the UK Health and Safety Executive, activities such as these 'should be avoided at all times'. Their documentation states that other methods should be considered, such as breaking down the load into smaller containers prior to movement or transporting the materials using a tractor or other vehicle. The risk posed by excessive force is made worse if the person lifting is also bending over as this increases pressure on the discs in the back.

If a load is bulky or hard to grasp, such as a lively or agitated animal, it will be more difficult to hold while lifting and carrying. The holder may adopt an awkward posture, which is tiring and increases the risk of injury. Sometimes a load has to be held away from the body because there is a large obstacle in the area and the person lifting needs to be able to see where their feet are going. This results in increased stress on the back; holding a load at arm's length imposes about five times the stress of a close-to-the-body position. In such cases, handling aids should be purchased that can take the weight off the load and minimise the potential for injury.

Another risk that relates to awkward posture is repetitive bending when carrying out a task. An example might be repairing a gate that has collapsed onto the ground. This type of activity increases the stress on the lower back because the back muscles have to support the weight of the upper body. The farmer should think about whether the job can be performed on a workbench, reducing the need for prolonged awkward posture.
PASSAGE,

    21 => <<<'PASSAGE'
Good customer service in retail

Without customers, your retail business would not exist. It stands to reason, therefore, that how you treat your customers has a direct impact on your profit margins.

Some customers just want to browse and not be bothered by sales staff. Try to be sensitive to how much help a customer wants; be proactive in offering help without being annoying. Suggest a product that naturally accompanies what the customer is considering or point out products for which there are special offers, but don't pressure a customer into buying an item they don't want.

Build up a comprehensive knowledge of all the products in your shop, including the pros and cons of products that are alike but that have been produced under a range of brand names. If you have run out of a particular item, make sure you know when the next orders are coming in. Negativity can put customers off instantly. If a customer asks a question to which the answer is 'no', do not just leave it at that – follow it with a positive, for example: 'we're expecting more of that product on Tuesday'.

Meanwhile, if you see a product in the wrong place on a shelf, don't ignore it – put it back where it belongs. This attention to presentation keeps the shop tidy, giving the right impression to your customers. Likewise, if you notice a fault with a product, remove it and replace it with another.

When necessary, be discreet. For example, if the customer's credit card is declined at the till, keep your voice down and enquire about an alternative payment method quietly so that the customer doesn't feel humiliated. If they experience uncomfortable emotions in your shop, it's unlikely that they'll come back.

Finally, good manners are probably the most important aspect of dealing with customers. Treat each person with respect at all times, even when you are faced with rudeness. Being discourteous yourself will only add more fuel to the fire.

Build a reputation for polite, helpful staff and you'll find that customers not only keep giving you their custom, but also tell their friends about you.
PASSAGE,

    28 => <<<'PASSAGE'
Plastic is no longer fantastic

A  In 2017, Carlos Ferrando, a Spanish engineer-turned-entrepreneur, saw a piece of art in a museum that profoundly affected him. 'What Lies Under', a photographic composition by Indonesian digital artist Ferdi Rizkiyanto, shows a child crouching by the edge of the ocean and 'lifting up' a wave, to reveal a cluster of assorted plastic waste, from polyethylene bags to water bottles. The artwork, designed to raise public awareness, left Ferrando angry – and fuelled with entrepreneurial ideas.

B  Ferrando runs a Spanish-based design company, Closca, that produces an ingenious foldable bicycle helmet. But he has now also designed a stylish glass water bottle with a stretchy silicone strap and magnetic closure mechanism that means it can be attached to almost anything, from a bike to a bag to a pushchair handle. The product comes with an app that tells people where they can fill their bottles with water for free.

C  The intention is to persuade people to stop buying water in plastic bottles, thus saving consumers money and reducing the plastic waste piling up in our oceans. 'Bottled water is now a $100 billion business, and 81 per cent of the bottles are not recycled. It's a complete waste – water is only 1.5 per cent of the price of the bottle!' Ferrando cries. Indeed, environmentalists estimate that by 2050 there will be more plastic in our oceans than fish and that's mainly down to such bottles. 'We are trying to create a sense that being environmentally sophisticated is a status symbol,' he adds. 'We want people to clip their bottles onto what they are wearing, to show that they are recycling – and to look cool.'

D  Ferrando's story is fascinating because it seems like an indicator of something unexpected. Three decades ago, conspicuous consumption – the purchase of luxuries, such as handbags, shoes, cars, etc. on a lavish scale – heightened people's social status. Indeed, the closing decades of the 20th century were a time when it seemed that anything could be turned into a commodity. Hence the fact that water became a consumer item, sold in plastic bottles, instead of just emerging, for free, from a tap.

E  Today, though, conspicuous extravagance no longer seems desirable among consumers. Now, recycling is fashionable – as is cycling rather than driving. Plastic water bottles have become so common that they do not command status; instead, what many millennials – young people born in the late 20th century – prefer to post on social media are 'real' (refillable) bottles or even the once widespread Thermos bottles. Some teenagers currently think that these stainless-steel vacuum-insulated water bottles that are coming back onto the market are ultra 'cool'; never mind the fact that they feel oddly out-of-date to anyone over the age of 40 or that teenagers in the 1970s would have avoided ever being seen with one.

F  It is uncertain whether Closca will succeed in its goal. Although its foldable bike helmet is available in some outlets in New York, including the Museum of Modern Art, it can be very hard for any design entrepreneur to really take off in the global mass market, though not as hard as it might have been in the past. If an entrepreneur had wanted to fund a smart invention a few decades ago, he or she would have had to either raise a bank loan, borrow money from a family member or use a credit card. Things have moved on slightly since then.

G  Entrepreneurs are still using the last two options, but some are also tapping into the ever-growing pot of money that is becoming available in the management world for 'corporate social responsibility' (CSR) investments. And then there are other options for those who wish to raise money straight away. Ferrando posted details about his water-bottle venture on a large, recognised platform for funding creative projects. He appealed for people to donate $30,000 of seed money – the money he needed to get his project going – and promised to give a bottle to anyone who provided more than $39 in 'donations'. If he received the funds, he stated that the company would produce bottles in grey and white; if $60,000 was raised, a multicoloured one would be made. Using this approach, none of the donors has a stake in his idea, nor does he have any debt. Instead, it is almost a pre-sale of the product, in a manner that tests demand in advance and creates a potential crowd of enthusiasts. This old-fashioned community funding with a digital twist is supporting a growing array of projects ranging from films to card games, videos, watches and so on. And, at last count, Closca had raised some $52,838 from 803 backers. Maybe, 20 years from now, it will be the plastic bottle that seems peculiarly old-fashioned.
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
        .test-header-row { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem; }
        .test-meta { display:flex; align-items:center; gap:.5rem; }
        .test-meta-tag { font-size:.72rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#9ca3af; }
        .test-meta-name { font-size:.9rem; font-weight:700; color:#1a2236; }
        .btn-exit { background:#f3f4f6; border:1.5px solid #e5e7eb; color:#374151; border-radius:6px; padding:.28rem .85rem; font-size:.8rem; font-weight:600; cursor:pointer; transition:all .2s; }
        .btn-exit:hover { background:#ef4444; border-color:#ef4444; color:#fff; }
        .part-panel { display:none; }
        .part-panel.active { display:block; }

        /* ── Passage ────────────────────────────────────────── */
        .passage-box { background:#fafafa; border:1px solid #e5e7eb; border-radius:8px; padding:1.25rem 1.5rem; margin-bottom:1.5rem; max-height:420px; overflow-y:auto; }
        .passage-box p { font-size:.9rem; line-height:1.75; color:#374151; margin-bottom:.6rem; }
        .passage-box .passage-title-main { font-size:1rem; font-weight:700; color:#111827; margin-bottom:.75rem; }
        .passage-box .pupil-name { font-size:.9rem; font-weight:700; color:#374151; margin-top:.85rem; margin-bottom:.2rem; }
        .passage-box .para-label { font-weight:700; color:#374151; margin-right:.35rem; }
        .passage-divider { border:none; border-top:2px dashed #e5e7eb; margin:1rem 0; }

        /* ── Question blocks ────────────────────────────────── */
        .section-block { margin-bottom:2rem; }
        .q-instructions { background:#eff6ff; border-left:3px solid #667eea; border-radius:0 6px 6px 0; padding:.6rem 1rem; font-size:.875rem; color:#1e40af; margin-bottom:1rem; line-height:1.5; }
        .q-block { margin-bottom:1.25rem; }
        .q-badge { display:inline-flex; align-items:center; justify-content:center; background:#667eea; color:#fff; font-size:.68rem; font-weight:700; border-radius:4px; min-width:20px; height:18px; padding:0 4px; margin-right:4px; vertical-align:middle; }
        .q-text { font-size:.9rem; color:#1f2937; margin-bottom:.5rem; line-height:1.6; }

        /* T/F/NG */
        .tfng-wrap { display:flex; gap:.5rem; flex-wrap:wrap; }
        .tfng-opt { display:flex; align-items:center; gap:.35rem; padding:.3rem .75rem; border:1.5px solid #d1d5db; border-radius:6px; cursor:pointer; font-size:.82rem; font-weight:600; color:#374151; transition:all .15s; }
        .tfng-opt:has(input:checked) { background:#667eea; border-color:#667eea; color:#fff; }

        /* MCQ */
        .mc-option { display:flex; align-items:flex-start; gap:.5rem; padding:.35rem .65rem; border-radius:7px; cursor:pointer; font-size:.85rem; color:#374151; transition:background .15s; margin-bottom:.15rem; line-height:1.4; }
        .mc-option:hover { background:#f3f4f6; }
        .mc-option input { accent-color:#667eea; margin-top:3px; flex-shrink:0; }

        /* Matching dropdown */
        .match-select { border:1.5px solid #d1d5db; border-radius:7px; padding:.3rem .65rem; font-size:.85rem; outline:none; color:#111827; cursor:pointer; transition:border-color .2s; max-width:380px; width:100%; }
        .match-select:focus { border-color:#667eea; }
        .match-select.answered { border-color:#10b981; color:#059669; }

        /* Text input */
        .ff-input { border:none; border-bottom:2px solid #c4b5fd; outline:none; width:200px; font-size:.87rem; padding:2px 4px; background:transparent; color:#111827; transition:border-color .2s; vertical-align:middle; }
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
        body.sidebar-collapsed .submit-bar { left:0; }
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
                <a href="full_mock_001_listening.php?session_id=<?= $session_id ?>" style="color:#a5b4fc;text-decoration:none;">🎧 Listening</a>
                <a href="full_mock_001_reading.php?session_id=<?= $session_id ?>"   style="color:#c7d2fe;text-decoration:none;border-bottom:2px solid #6366f1;padding-bottom:2px;">📖 Reading</a>
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
                No questions loaded yet. Please run database migrations 017 and contact your instructor.
            </div>
            <?php endif; ?>

            <form id="readingForm">
            <?php foreach ($parts as $partNum => $partQuestions):
                $firstQ = $partQuestions[0];
                $lastQ  = end($partQuestions);
            ?>
            <div class="part-panel <?= $partNum === 1 ? 'active' : '' ?>" id="panel-<?= $partNum ?>">

                <?php
                $prevInstructions = null;
                $prevQtext        = null;
                foreach ($partQuestions as $q):
                    $qid   = (int)$q['id'];
                    $qnum  = (int)$q['question_number'];
                    $qtype = $q['question_type'];
                    $qopts = $options[$qid] ?? [];
                ?>

                <?php if (isset($passages[$qnum])): ?>
                <div class="passage-box"><?= renderPassage($passages[$qnum]) ?></div>
                <?php endif; ?>

                <?php if ($q['instructions'] !== $prevInstructions):
                    $prevInstructions = $q['instructions'];
                ?>
                <div class="q-instructions"><?= htmlspecialchars($q['instructions']) ?></div>
                <?php endif; ?>

                <div class="q-block" id="qblock-<?= $qnum ?>">
                    <div style="margin-bottom:.35rem;">
                        <span class="q-badge"><?= $qnum ?></span>
                        <?php if ($q['question_text']): ?>
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
                        <?php $tfOpts = $qtype === 'true_false_not_given' ? ['TRUE','FALSE','NOT GIVEN'] : ['YES','NO','NOT GIVEN']; ?>
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
                        <select name="answers[<?= $qnum ?>]" class="match-select answer-field" data-qnum="<?= $qnum ?>"
                                onchange="this.classList.toggle('answered',this.value!=='')">
                            <option value="">— Select —</option>
                            <?php foreach ($qopts as $opt): ?>
                            <option value="<?= htmlspecialchars($opt['option_label']) ?>">
                                <?= htmlspecialchars($opt['option_label']) ?>. <?= htmlspecialchars($opt['option_text']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>

                    <?php else: ?>
                        <input type="text" name="answers[<?= $qnum ?>]"
                               class="ff-input answer-field" data-qnum="<?= $qnum ?>"
                               autocomplete="off" placeholder="Write your answer"
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
        if (rem <= 0) { clearInterval(timerInterval); submitReading(true); }
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
        if (el.type === 'radio'    && el.checked)    ans[n] = el.value;
        if (el.type === 'checkbox' && el.checked)    ans[n] = el.value;
        if (el.tagName === 'SELECT' && el.value)     ans[n] = el.value;
        if (el.type === 'text' && el.value.trim())   ans[n] = el.value.trim();
    });
    return ans;
}

function updateProgress() {
    const ans = collectAnswers();
    let count = Object.keys(ans).length;
    <?php foreach ($parts as $pNum => $pqs): ?>
    (function() {
        const pNums = [<?= implode(',', array_column($pqs,'question_number')) ?>];
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
    const ans = collectAnswers();
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
            alert(d.error || 'An error occurred.'); submitting = false; btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-arrow-right-circle me-2"></i>Submit & Continue to Writing';
        }
    })
    .catch(() => {
        alert('Network error. Please try again.'); submitting = false; btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-arrow-right-circle me-2"></i>Submit & Continue to Writing';
    });
}

function confirmExit() {
    if (confirm('Exit the test? Your progress will be lost.')) window.location.href = 'index.php';
}

window.addEventListener('beforeunload', e => { if (!submitting) { e.preventDefault(); e.returnValue=''; } });

startTimer();
updateProgress();
</script>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
