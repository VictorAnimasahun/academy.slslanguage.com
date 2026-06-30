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
//   - "A  Bold heading" pattern (letter + 2+ spaces) → lettered section heading
//   - Tab-indented lines → body paragraph under a heading
//   - Plain lines → standard paragraphs
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

        // Lettered section heading: "A  Rib riding"
        if (preg_match('/^([A-H])\s{2,}(.+)$/', $trimmed, $m)) {
            $html .= '<p class="para-heading" style="margin-top:.9rem;margin-bottom:.15rem;">'
                   . '<span class="para-label">' . htmlspecialchars($m[1]) . '</span>'
                   . '<strong>' . htmlspecialchars($m[2]) . '</strong></p>';
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
// Section 1: Q1–8  "The best hiking boots"            (matching A–G)
//            Q9–14 "Beekeeping workshop at Elm Farm"   (TRUE/FALSE/NOT GIVEN)
// Section 2: Q15–20 "Should you pay someone to write your CV?" (sentence completion)
//            Q21–27 "Starting a new job"               (note completion)
// Section 3: Q28–40 "History of women's football in Britain" (MCQ + matching + summary, shared passage)
$passages = [

    // ── Section 1 · Text A ───────────────────────────────────────────────────
    1 => <<<'PASSAGE'
The best hiking boots

Whether you're climbing a mountain or walking in the country, be sure to buy the right boots, writes Sian Lewis

A  Hanwag Tatra Boots
	These boots are expensive but will give you a lifetime of wear. They are a wide fit and offer excellent ankle support. They passed our waterproof test when worn on long, rainy walks, although they are a bit heavy.

B  Scarpa Peak Gore-tex Boots
	These are good all-round boots that have kept our feet dry in heavy rain, snow and mud. They are warm and comfortable to wear straight out of the box and continue to be so even after many kilometres. A great choice for all seasons.

C  Keen Terradora Ethos
	These are meant for spring and summer walks and for putting in your backpack for treks in hot climates. They will never weigh you down. Their soles grip well and despite not being waterproof, they are quick-drying when they get wet.

D  Danner Jag
	Danner's retro boots are one of the heavier ones we reviewed. They take a week or two for your feet to get used to them, but we found them waterproof even in heavy rain. These are boots for the style conscious, but still suitable for demanding walks.

E  Merrell Siren Sport Q2 Mid Boots
	We've worn these boots in freezing cold conditions and our feet felt comfortable. Remember to pull the laces firmly when you put these boots on as they are rather wide around the ankles.

F  Teva Arrowood Mid WP
	The soft leather might not be tough enough for extreme environments, but these boots get top marks for comfort. They're waterproof, but we found this wore off after about 20 wet walks. You can, however, get round this problem by using a protective spray on them.

G  Regatta Clydebank Mid Boots
	These boots are reasonably priced and they performed well in heavy rain. They don't grip the ground as well as some other boots and aren't very warm in cold winter weather so we'd say they're best for country walks in spring and summer.
PASSAGE,

    // ── Section 1 · Text B ───────────────────────────────────────────────────
    9 => <<<'PASSAGE'
Beekeeping workshop at Elm Farm

If you've ever wanted to keep bees and have your own delicious honey, there's no better time to begin!

Whether you're keen to learn everything you need to know to get you started, or simply extremely interested in the idea of keeping bees, this one-day interactive workshop will teach you the fascinating secrets of the honeybee and how to care for and keep bees.

Our day begins here on the farm, getting to know about the honeybee, specifically the kind we keep here, and their fascinating history. You will find out about and try for yourself the equipment beekeepers use to care for their bees and discover the many different types of hives bee colonies live in and their different uses. You'll learn about the life cycle of a colony, disease prevention and caring for bees and of course how to harvest honey for your personal use or for sale.

Then it's time to try on your bee suit and meet our bees. We'll teach you how to open the hive, recognise the different bees in it (including how to spot the queen!) and explain what they're doing in different parts of the hive.

What's included in the price?

We'll provide everything you need, including unlimited organic tea or coffee, lunch cooked in our outdoor, wood-fired oven and beekeeping suits for the day. Just bring a pair of thick boots with you. You'll leave with plenty of notes and resources, including a packet of bee-friendly wildflower seeds and, courtesy of BJ Sherriff, the leading supplier of beekeeping clothing, an exclusive 25% discount for anything in their online store.

We like to run our workshops fairly and honestly. Your booking secures a very limited place, so is non-refundable – if you can't make it, you can send a friend or colleague instead though. If at the end of any of our workshops, you don't believe that it has helped you to achieve what it set out to, we will gladly provide a full refund.

Places are strictly limited so please do book early to avoid disappointment.
PASSAGE,

    // ── Section 2 · Text C ───────────────────────────────────────────────────
    15 => <<<'PASSAGE'
Should you pay someone to write your CV?

In my view, the belief that the individual is the best person to write their own CV is not always true. Although many people can write their own CVs, and do it well, others struggle with a variety of problems initially, such as not knowing how to structure a CV or how to highlight their most relevant strengths.

Through in-depth consultation, a professional CV writer can help identify exactly what is necessary for a particular role, cut out unnecessary or irrelevant details, and pinpoint what makes the individual stand out. This level of objectivity is one of the major benefits of working with a professional writer. It's often difficult to stand back from your own career history to assess what's relevant or not, or to choose the most appropriate qualities.

If you do choose to work with a professional CV writer, here are some tips:

Ask for a CV writer who has experience in your sector. HR professionals and recruiters with relevant experience can also have valuable insights into what companies are looking for.

Look for someone who's prepared to take the time to find out your core qualities, who can choose exactly the right words for maximum impact and who understands what and where to edit. Ask to see samples of their work or use personal recommendations before you choose a CV writing service.

You'll probably need to answer an in-depth email questionnaire or be interviewed before any writing actually starts. The more you can give your CV writer to work with, the better, so the promise of a quick turnaround time isn't always going to result in the best possible CV. Take the time to think about and jot down your career aims, your past successes, and the value you bring, before you start the whole process. Your CV will probably be used as a springboard for questions at interview, so you need to make sure you feel happy with the way it's being written and with the choice of words. Being involved in the writing process means your CV sounds authentic.
PASSAGE,

    // ── Section 2 · Text D ───────────────────────────────────────────────────
    21 => <<<'PASSAGE'
Starting a new job

First impressions really do last, so it's important you perform well on your first day in the new job. Here are our top tips that will help you sail through your first day with ease.

A new job is a great opportunity to hit the reset button. If you got into the habit of skipping breakfast at your last job, fit it in now or experiment with getting a workout in before going to the office. Having a routine you like and sticking to it definitely impacts on your overall happiness.

You've probably already been into the office for an interview, so you'll have some idea of what the dress code is. While you definitely want to feel comfortable, it's best to play it safe, leaning towards a smarter and more polished look on your first day.

You don't want to be late, but getting to the office way too early can also potentially upset not only your schedule but other people's too. A good rule of thumb is to try and arrive 15 minutes ahead of the agreed start time.

Accepting an invite to lunch with your boss and co-workers will allow you to get to know the people you'll be working with on a more personal level. It will also help you get a handle on personalities and work styles. To ensure the lunch goes well, have a few conversation starters in mind. That way, if the talk dries up, you can get it going again.

One of the big outcomes of going through a job search is you learn loads about yourself. In particular, you learn what you want and don't want, and what skills you bring to the table. With this new-found understanding, take some time over the initial period to think about what goals you have for your new role. In identifying these early on, you'll be one step closer to positioning yourself for success.

It's important that you approach your new job with an open mind, and that you're ready to soak it all in. Be patient with yourself as you figure out how you fit in, and make sure you understand the way things are done before rushing into giving suggestions on improvements.

Remember they hired you for a reason, so smile, relax a little and enjoy the first day of your next big thing.
PASSAGE,

    // ── Section 3 · Passage (Q28–40, shared) ─────────────────────────────────
    28 => <<<'PASSAGE'
History of women's football in Britain

Women's football in Britain has deeper roots than might be expected. In one town in 18th-century Scotland, single women played an annual match against their married counterparts, though the motives behind the contest were not purely sporting. Some accounts say that the games were watched by a crowd of single men, who hoped to pick out a potential bride based on her footballing ability.

By the late 19th century, with the men's game spreading across Britain like wildfire, women also began to take up association football. Early pioneers included Nettie J Honeyball, who founded the British Ladies' Football Club (BLFC) in 1895. Honeyball was an alias: like many of the middle- and upper-class women who played in the late 19th century, she was not keen to publicise her involvement with a contact sport played on muddy fields. We know more about Lady Florence Dixie, who was appointed president of the BLFC in 1895 and who was an ardent believer in equality between the sexes.

The BLFC arranged games between teams representing the north and the south of England, where money would be raised for those in need. These initially attracted healthy numbers of supporters although early newspaper reports were not particularly generous, with one reporter suggesting 'when the novelty has worn off, I do not think women's football will attract the crowds'. And crowds did drop off as the growing popularity of the men's game came to dominate public interest. In a country where women were not yet allowed to vote, it would take extraordinary circumstances for their efforts on the football pitch to attract widespread attention.

Those circumstances arose in 1914 with the outbreak of the First World War. With many men leaving their jobs to join the army, women started to work in factories and just as men had done before them, they began to play informal games of football during their lunch breaks. After some initial uncertainty, their superiors came to see these games as a means to boost morale and thus increase productivity. Teams soon formed and friendly matches were arranged.

In the town of Preston in the north of England, the female workers at a manufacturing company called Dick, Kerr & Co showed a particular aptitude for the game. Watching from a window above the yard where they played, office worker Alfred Frankland spotted their talent and he set about forming a team. Under Frankland's management, they soon drew significant crowds to see their games. Known as Dick, Kerr's Ladies, they beat rival factory Arundel Coulthard 4–0 on Christmas Day 1917, with 10,000 watching at Preston stadium.

After the war ended in 1918 the Dick, Kerr's side and other women's teams continued to draw large crowds. In 1920 there were around 150 women's sides in England and Dick, Kerr's Ladies packed 53,000 into Everton's Goodison Park stadium. The same year, the team found their one true genius: Lily Parr. Parr grew up playing football with her brothers, and began her career with her town's ladies' team at the age of 14. When they played against the Dick, Kerr's side, she caught Frankland's eye and was offered a job at the factory – as well as a spot on the team. Close to six-feet tall and with jet-black hair, she had a ferocious appetite and a fierce left foot. She is credited with 43 goals during her first season playing for Dick, Kerr's Ladies and around 1,000 in total.

By 1921 Dick, Kerr's Ladies were regularly attracting crowds in the tens of thousands. But the year ended in catastrophe for the women's game. The Football Association (FA) – officially the governing body for the sport as a whole, but really only concerned with men's competitions – had always taken a poor view of female participation. Women's football was tolerated during the war, but in the years that followed, driven by the fear that the women's game could affect Football League attendances, the FA sought to assert itself.

Its solution was decisive and brutal. On 5 December 1921, the FA banned its members from allowing women's football to be played at its grounds, saying that football was 'quite unsuitable for females'. The FA also forbade its members from acting as referees at women's games. To all intents and purposes, women's football in England was outlawed.

The FA also suggested that an excessive proportion of the gate receipts were absorbed in expenses and an inadequate percentage devoted to charity. No such obligation to donate profits existed for men's clubs and no proof of financial mismanagement was presented, but there was little the women's clubs could do in response.

There was outrage from players, with the captain of Plymouth Ladies remarking that the FA was 'a hundred years behind the times' and calling its decision 'purely sex prejudice'.

It was not until 1966 that serious efforts to revive the women's game began, but progress remained painfully slow. It took pressure from the Union of European Football Associations (UEFA), to finally force the FA to end restrictions on women's football in 1971. By this time, half a century of progress had been lost.
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
                <a href="full_mock_004_listening.php?session_id=<?= $session_id ?>" style="color:#a5b4fc;text-decoration:none;">🎧 Listening</a>
                <a href="full_mock_004_reading.php?session_id=<?= $session_id ?>"   style="color:#c7d2fe;text-decoration:none;border-bottom:2px solid #6366f1;padding-bottom:2px;">📖 Reading</a>
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
