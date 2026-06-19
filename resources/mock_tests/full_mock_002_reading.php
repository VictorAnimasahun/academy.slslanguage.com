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
    SELECT q.id, q.question_number, q.question_type, q.question_text,
           q.instructions, q.part_number, q.stimulus_text
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
// Section 1: Q1–7  "How to choose your builder"  (TRUE/FALSE/NOT GIVEN)
//            Q8–14 "Island adventure activities"  (matching A–H)
// Section 2: Q15–20 "Barrington Music Service"   (note completion, ONE WORD ONLY)
//            Q21–27 "Health and safety in small businesses" (sentence completion, ONE WORD ONLY)
// Section 3: Q28–36 "Jobs in ancient Egypt"      (MCQ + job matching)
//            Q37–40 summary uses same passage, no separate passage block needed
$passages = [

    // ── Section 1 · Passage 1 ────────────────────────────────────────────────
    1 => <<<'PASSAGE'
How to choose your builder

Building a new home is a significant investment, and it's essential to find the right builder for the job. Before you look for a builder, it's important to develop a comprehensive budget and have clear plans. Once you have a design in mind, it is time to start narrowing down your builder shortlist, and this starts with assessing how qualified each builder is. In Australia, this means checking that the builder holds a residential building licence. Most states have their own building authority who you can contact to check a builder's licence.

You can also check if the builder is a member of an industry association such as the Housing Industry Association (HIA), and whether they have won any industry awards. For instance, the HIA runs a state and national awards programme, with a category that recognises the level of customer service that a builder delivers.

Most experts agree that display homes (homes constructed by the builder that are open to the public) offer a great opportunity to study their work up close. Display homes are usually offered by major project builders who work on a large scale and can deliver good quality and value. You can also talk to the salesperson and find out about the home design and what is and isn't included in the sale price. And it may be possible to talk to other customers you meet there and ask their opinion of the workmanship in the display home.

Finally, avoid signing any business contract before you have read and understood it thoroughly. Ask your builder to use a standard building contract that has been designed to comply with the Domestic Building Contracts Act, and to be fair to both client and builder. You have five business days within which you may withdraw from the contract after signing it.
PASSAGE,

    // ── Section 1 · Passage 2 ────────────────────────────────────────────────
    8 => "Island adventure activities\n"
       . "\n"
       . "A  Rib riding\n"
       . "\tConquer stormy seas on a high-speed ride in a RIB (Rigid Inflatable Boat). These powerful boats cut through choppy waters with ease. You'll need to hold on tight as the boat bounces across the wake of awesome cruise liners in one of the world's busiest shipping lanes.\n"
       . "\n"
       . "B  Horse riding\n"
       . "\tExperience the thrill of riding on horseback along peaceful country lanes and secluded bridleways with the help of expert guides. Even a novice can quickly take the reins and feel the thrill of riding one of nature's most magnificent beasts.\n"
       . "\n"
       . "C  Kayaking\n"
       . "\tTest your kayak nerves paddling around a deserted military fort built on a rocky outcrop out at sea, then explore the island's busy harbours before gliding back to dry land where a hot shower and a cup of tea await.\n"
       . "\n"
       . "D  Cycling\n"
       . "\tTest your endurance on the famous Round the Island Cycle Route. Grit your teeth and tackle the brutal hills in the south of the island, or for something less challenging, discover our car-free cycle tracks on former railway lines.\n"
       . "\n"
       . "E  Segway riding\n"
       . "\tHave you got what it takes to master a Segway? In theory, these quirky electric machines are simple to control, with users leaning forwards to go faster and back to slow down. In reality, you'll need some practice before you can master the skill and glide around the island.\n"
       . "\n"
       . "F  Tree climbing\n"
       . "\tA climb into the canopy of a 25-metre oak tree is an amazing experience. Supported by a rope and harness, you can stand on branches no bigger than your wrist, and swing out between the boughs, or simply take the opportunity to lie in a tree-top hammock and absorb the stunning bird's eye views.\n"
       . "\n"
       . "G  Coasteering\n"
       . "\tTackle the spectacular coast in the north of the island. Scrabble over the rocks around cliff edges as the waves crash around you, dive through submerged caves and emerge onto a beach once used by smugglers. This is a thrilling experience, but not an adventure to attempt alone.\n"
       . "\n"
       . "H  Mountain boarding\n"
       . "\tFirst developed as an off-season alternative to winter sports and now a sport in its own right, mountain boarding has the speed of snowboarding but with a harder landing when you fall. After a bit of practice and a few bruises, you'll learn to control the ride and can join the few people who can call themselves mountain boarders.\n",

    // ── Section 2 · Passage 1 ────────────────────────────────────────────────
    15 => <<<'PASSAGE'
Barrington Music Service: Business and Development Manager

Barrington Music Service organises a wide range of music activities for children and young people resident in and around Barrington. It provides singing and specialist instrumental lessons in schools, and it owns a collection of instruments for use in schools, some of which are available for hire by the parents of children having lessons. The Service also arranges a number of music-related events, including festivals bringing together choirs and soloists from schools in both Barrington and other areas. The Music Service provides administrative and financial support for the Barrington Youth Orchestra, which takes part in workshops with professional artists and gives performances.

Barrington Music Service is seeking to recruit a Business and Development Manager to manage the administrative function and build on the success of the Service. We are looking for an individual with a passion for delivering the best possible music provision for the benefit of our children and young people.

As the Business and Development Manager, you will be responsible for managing the administrative and financial systems of the Music Service, ensuring it does not exceed its budget, which is currently around £1m a year. You will take the lead on marketing the Service and ensuring the generation of new income. The Music Service is involved in several partnerships with schools and with music and community organisations in the district, and you will be expected to increase the number and scope of these, as well as take the lead in fundraising. The Service recently embarked on a programme to broaden what is taught in school music lessons, to include instruments and musical styles from around the world, and you will be required to further develop this emphasis on diversity.

You will need to improve systems for ensuring that the records of the Service's activities are accurate, and maintain a database of all music teachers, students, and instruments belonging to the Service.

The person appointed will have experience of a supervisory role and the skills to motivate members of a team. You will have an understanding of accounting, at a non-specialist level, and of standard financial procedures. High-level IT skills and excellent verbal and written communication skills are essential. Although experience in music education is not crucial, good knowledge of the field, or of other areas of arts management, would be an advantage.
PASSAGE,

    // ── Section 2 · Passage 2 ────────────────────────────────────────────────
    21 => <<<'PASSAGE'
Health and safety in small businesses

The rate of accidents at work is almost 75% higher in small businesses than in larger companies. One possible reason is that many managers of small businesses have an inadequate knowledge of health and safety issues.

Many managers of small businesses claim their situation is made worse by bureaucracy, arguing that the huge number of regulations — not just on health and safety but also on tax, the minimum wage, and much, much more — makes their work difficult.

Many managers are simply not aware of their responsibilities. They are too busy running their companies to read manuals, employ consultants or go to seminars. Moreover, the average business person doesn't know where and how to get information.

The Federation of Small Businesses argues that the special nature of small businesses should be recognised by health and safety inspectors, with an emphasis on education and how to comply with the law, rather than simply on enforcement. For instance, inspectors could make employers aware of what they really need to know, rather than swamping them with mountains of leaflets which may not be relevant.

Improvements are being made, however. The Health and Safety Executive has issued a free guide to the most important health and safety laws for employers. All employers must have their own health and safety policy statement and, for businesses with more than five employees, this must be in writing. It should be specific to the business and clear about the arrangements for and organisation of health and safety at work.

It should state a strategy, detail how it will be implemented and by whom, and say when it will be reviewed and updated. It is advisable to involve employees in this process, as they have direct experience.

Assessing and identifying risks is the starting point. But to comply with the law, businesses must train their employees about health and safety, and provide information to others who need to know, such as the contractors working for them. These are often smaller companies that carry out most of the dangerous work. Helping them to get into good safety habits makes it easier for them to tender for work from big companies.

Other advice from the Health and Safety Executive for small businesses tackles specific issues, such as helping small companies to deal with work-related stress.
PASSAGE,

    // ── Section 3 · Passage (Q28–40, shared) ────────────────────────────────
    28 => <<<'PASSAGE'
Jobs in ancient Egypt

In order to be engaged in the higher professions in ancient Egypt, a person had to be literate and so first had to become a scribe. The apprenticeship for this job lasted many years and was tough and challenging. It principally involved memorizing hieroglyphic symbols and practicing handwritten lettering. Scribes noted the everyday activities in ancient Egypt and wrote about everything from grain stocks to tax records. Therefore, most of our information on this rich culture comes from their records. Most scribes were men from privileged backgrounds. The occupation of scribe was among the most sought-after in ancient Egypt. Craftspeople endeavored to get their sons into the school for scribes, but they were rarely successful.

As in many civilizations, the lower classes provided the means for those above them to live comfortable lives. You needed to work if you wanted to eat, but there was no shortage of jobs at any time in Egypt's history. The commonplace items taken for granted today, such as a brush or bowl, had to be made by hand; laundry had to be washed by hand, clothing sewn, and sandals made from papyrus and palm leaves. In order to make these and have paper to write on, papyrus plants had to be harvested, processed, and distributed and all these jobs needed workers. There were rewards and sometimes difficulties. The reed cutter, for example, who harvested papyrus plants along the Nile, had to bear in mind that he worked in an area that was also home to wildlife that, at times, could prove fatal.

At the bottom rung of all these jobs were the people who served as the basis for the entire economy: the farmers. Farmers usually did not own the land they worked. They were given food, implements, and living quarters as payment for their labor. Although there were many more glamorous jobs than farming, farmers were the backbone of the Egyptian economy and sustained everyone else.

The details of lower-class jobs are known from medical reports on the treatment of injuries, letters, and documents written on various professions, literary works, tomb inscriptions, and artistic representations. This evidence presents a comprehensive view of daily work in ancient Egypt — how the jobs were done, and sometimes how people felt about the work. In general, the Egyptians seem to have felt pride in their work no matter what their occupation. Everyone had something to contribute to the community, and no skills seem to have been considered non-essential. The potter who produced cups and bowls was as important to the community as the scribe, and the amulet-maker as vital as the pharmacist.

Part of making a living, regardless of one's special skills, was taking part in the king's monumental building projects. Although it is commonly believed that the great monuments and temples of Egypt were achieved through slave labor, there is absolutely no evidence to support this. The pyramids and other monuments were built by Egyptian laborers who either donated their time as community service or were paid for their labor, and Egyptians from every occupation could be called on to do this.

Stone had to first be quarried and this required workers to split the blocks from the rock cliffs. It was done by inserting wooden wedges in the rock which would swell and cause the stone to break from the face. The often huge blocks were then pushed onto sleds, devices better suited than wheeled vehicles to moving weighty objects over shifting sand. They were then rolled to a different location where they could be cut and shaped. This job was done by skilled stonemasons working with copper chisels and wooden mallets. As the chisels could get blunt, a specialist in sharpening would take the tool, sharpen it, and bring it back. This would have been constant daily work as the masons could wear down their tools on a single block.

The blocks were then moved into position by unskilled laborers. These people were mostly farmers who could do nothing with their land during the months when the Nile River overflowed its banks. Egyptologists Bob Brier and Hoyt Hobbs explain: 'For two months annually, workmen gathered by the tens of thousands from all over the country to transport the blocks a permanent crew had quarried during the rest of the year. Overseers organized the men into teams to transport the stones on the sleds.' Once the pyramid was complete, the inner chambers needed to be decorated by scribes who painted elaborate images on the walls. Interior work on tombs and temples also required sculptors who could expertly cut away the stone around certain figures or scenes that had been painted.

While these artists were highly skilled, everyone — no matter what their job for the rest of the year — was expected to contribute to communal projects. This practice was in keeping with the value of ma'at (harmony and balance) which was central to Egyptian culture. One was expected to care for others as much as oneself and contributing to the common good was an expression of this. There is no doubt there were many people who did not love their job every day, but the Egyptian government was aware of how hard the people worked and so staged a number of festivals throughout the year to show gratitude and give them days off to relax.
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
                <a href="full_mock_002_listening.php?session_id=<?= $session_id ?>" style="color:#a5b4fc;text-decoration:none;">🎧 Listening</a>
                <a href="full_mock_002_reading.php?session_id=<?= $session_id ?>"   style="color:#c7d2fe;text-decoration:none;border-bottom:2px solid #6366f1;padding-bottom:2px;">📖 Reading</a>
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
