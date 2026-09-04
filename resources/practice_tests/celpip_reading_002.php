<?php
// CELPIP Reading Practice 2 — Part 1 rewritten by the instructor; Parts 2-4
// are cleaned-up rewrites of Downloads/CELPIP TASKS/Celpip Reading/Test 2
// (the source PDFs had broken/non-native English throughout).
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}

$testCode  = 'CELPIP_PT_R_002';
$timeLimit = 55 * 60;

$parts = [
    1 => [
        'title'   => 'Part 1',
        'label'   => 'Reading Correspondence',
        'q_range' => [1, 11],
        'sections' => [
            [
                'type'          => 'mcq',
                'passage_title' => 'Confirmation of Your Dental Appointment',
                'passage'       => "<p>Dear Patient,</p>
<p>We are pleased to confirm your upcoming dental appointment at SmileBright Dental Clinic. Your appointment is scheduled for November 29, 2024 at 11am. To ensure ample time for completing any necessary paperwork and to help us accommodate you efficiently, please plan to arrive at least 15 minutes before your scheduled time.</p>
<p>If you have any new insurance information, please bring your updated insurance card with you to the appointment. This will help us process your claims accurately and in a timely manner. If you are seeing us for the first time, kindly also bring a valid photo ID to ensure your identity and personal health information are protected.</p>
<p>For patients who are not covered by insurance, payment for services rendered will be due at the time of your visit, payable by cash or debit card. If you have any questions about fees or payment methods, feel free to reach out to our office prior to your appointment.</p>
<p>If you are transferring from another practice, please request that your previous dental records be sent to us before your appointment. This will help us confirm any past treatments and provide you with the best possible care.</p>
<p>We look forward to welcoming you to SmileBright Dental Clinic and are grateful that you have chosen us for your dental health needs.</p>
<p>Sincerely,<br>Dr. John Williams<br>SmileBright Dental Clinic</p>",
                'instructions' => 'Choose the best option according to the information given in the message.',
                'questions'    => [
                    ['q'=>1, 'text'=>'The letter is mainly about', 'options'=>['A'=>'Changing the date of the appointment','B'=>'Apologizing for a billing error','C'=>"Explaining the clinic's policies",'D'=>'Confirming the appointment and providing related information']],
                    ['q'=>2, 'text'=>'If a patient has new insurance information, they should', 'options'=>['A'=>'Bring their updated insurance card only for their first appointment','B'=>'Call the clinic before the appointment to report the change','C'=>'Wait until after the appointment to update their information','D'=>'Show proof of their current coverage at the appointment']],
                    ['q'=>3, 'text'=>'Patients without insurance must', 'options'=>['A'=>'Provide proof of income before being treated','B'=>'Settle their bill on the day of the appointment','C'=>'Pay in advance of the appointment date','D'=>'Discuss a payment plan with clinic staff']],
                    ['q'=>4, 'text'=>'Patients are asked to', 'options'=>['A'=>'Arrive exactly at their scheduled time','B'=>'Arrive fifteen minutes after their scheduled time','C'=>'Get to the clinic at least a quarter of an hour before their scheduled time','D'=>'Arrive at least thirty minutes early']],
                    ['q'=>5, 'text'=>'Previous dental records help the clinic', 'options'=>['A'=>'Check whether the patient currently has valid insurance','B'=>'See what dental treatment the patient has already received','C'=>'Decide whether the clinic will accept the patient as new','D'=>'Confirm treatments already performed at SmileBright itself']],
                    ['q'=>6, 'text'=>'Patients with insurance would likely', 'options'=>['A'=>'Have their payment handled through their coverage rather than paid directly at the visit','B'=>'Be exempt from arriving early','C'=>'Not need to bring a photo ID','D'=>'Receive a discount on treatment']],
                ],
            ],
            [
                'type'          => 'mcq',
                'passage_title' => 'Response to Dr. Williams',
                'passage'       => "<p>Dear Dr. Williams,</p>
<p>I am writing to let you know that, unfortunately, <strong>(7)</strong>___ on November 29. My father has been <strong>(8)</strong>___ for knee surgery, and it's critical that I be with him at this time. I anticipate that I will be in Chicago for the next three weeks.</p>
<p>I was really looking forward to my appointment with you. <strong>(9)</strong>___, given the circumstances, I would like to find a suitable time when we can reschedule. I apologize for the change and for any inconvenience it may cause. <strong>(10)</strong>___.</p>
<p>Sincerely,<br>[Patient's name]</p>",
                'instructions' => 'Complete the email by selecting the most appropriate phrase for each blank.',
                'questions'    => [
                    ['q'=>7,  'text'=>'Blank (7)',  'options'=>['A'=>"I'm really sorry, but I need to cancel our appointment",'B'=>'I needed to cancel our appointment','C'=>'I have to cancel our appointment','D'=>'I am cancelling our appointment']],
                    ['q'=>8,  'text'=>'Blank (8)',  'options'=>['A'=>'scheduled','B'=>'hospitalized','C'=>'treated','D'=>'prepared']],
                    ['q'=>9,  'text'=>'Blank (9)',  'options'=>['A'=>'However,','B'=>'As a result,','C'=>'In addition,','D'=>'For example,']],
                    ['q'=>10, 'text'=>'Blank (10)', 'options'=>['A'=>'Thank you for understanding','B'=>'I look forward to seeing you','C'=>'Thank you for your support','D'=>'I request you to look into this']],
                    ['q'=>11, 'text'=>'According to the response, the writer expects to be away for approximately', 'options'=>['A'=>'One week','B'=>'Two weeks','C'=>'One month','D'=>'Three weeks']],
                ],
            ],
        ],
    ],
    2 => [
        'title'   => 'Part 2',
        'label'   => 'Reading to Apply a Diagram',
        'q_range' => [12, 19],
        'sections' => [
            [
                'type'          => 'schedule',
                'passage_title' => 'Rehearsal Schedule',
                'group_legend'  => ['MPF' => 'Robots', 'Discover' => 'Relationships', 'Explore' => 'Voice / Choice / Agency'],
                'schedule_rows' => [
                    ['week'=>'Week 6', 'mon'=>'10:15–10:40 Share ideas with group (introduction)<br>1:30–2:40 Whole group (break in the middle)', 'tue'=>'1:30–2:40 Year 3–8 (break in the middle)', 'wed'=>'1:30–2:40 Whole group (break in the middle)'],
                    ['week'=>'Week 7', 'mon'=>'11:00 Year 3–8<br>1:30–2:40 Whole group (break in the middle)', 'tue'=>'11:00 Year 3–8<br>1:30–2:40 Whole group (break in the middle)', 'wed'=>'11:00 Year 3–8<br>1:30–2:40 Whole group (break in the middle)'],
                    ['week'=>'Week 8', 'mon'=>'All day (as required)', 'tue'=>'All day (as required)', 'wed'=>'All day (as required)'],
                    ['week'=>'Week 9', 'mon'=>'Dress rehearsal (afternoon)<br>Night performance', 'tue'=>'Night performance', 'wed'=>'—'],
                ],
            ],
            [
                'type'          => 'mcq',
                'passage_title' => "J.K. Young's Email to James",
                'passage'       => "<p><em>Subject: Week 6 Rehearsal Update</em></p>
<p>Hi, whanau!</p>
<p>I hope you've all been staying safe and dry, and please remember to check in on your neighbours, family, and friends to make sure everyone's okay after that big earthquake.</p>
<p>To welcome everyone back and hear your ideas before we dive into rehearsals, I'd like to meet with you all <strong>(12)</strong>___.</p>
<p><strong>(13)</strong>___ begin on the first day of our final week, so please make sure your costumes are sorted well before then. Please <strong>(14)</strong>___ if you have any skills or spare time you'd like to offer us — we could really use the extra hands! We'll be together with the whole group throughout the final week, though please note that <strong>(15)</strong>___. We're sending <strong>(16)</strong>___ thoughts to everyone affected by the earthquake — stay safe, everyone.</p>
<p>Best,<br>J.K. Young</p>",
                'instructions' => 'Complete the email by filling in the blanks. Select the best choice for each blank.',
                'questions'    => [
                    ['q'=>12, 'text'=>'Blank (12)', 'options'=>['A'=>'all days in Week 9','B'=>'in the morning on the first day','C'=>'on the stage every morning','D'=>'the Tuesday afternoon office session']],
                    ['q'=>13, 'text'=>'Blank (13)', 'options'=>['A'=>'Whole-group rehearsals','B'=>'Individual auditions','C'=>'Costume fittings','D'=>'Dress rehearsals']],
                    ['q'=>14, 'text'=>'Blank (14)', 'options'=>['A'=>'come and watch a preview performance','B'=>'invite your friends and family','C'=>'send us an email','D'=>'bring a packed lunch']],
                    ['q'=>15, 'text'=>'Blank (15)', 'options'=>['A'=>'the last day has no scheduled session','B'=>'everyone needs a special costume','C'=>'we need to meet all day in Week 8','D'=>'the Tuesday schedule will change']],
                    ['q'=>16, 'text'=>'Blank (16)', 'options'=>['A'=>'threatening','B'=>'negative','C'=>'positive','D'=>'cautious']],
                ],
            ],
            [
                'type'          => 'mcq',
                'passage_title' => null,
                'passage'       => null,
                'instructions'  => 'Choose the best option according to the information given in the message.',
                'questions'    => [
                    ['q'=>17, 'text'=>'J.K Young is most likely a', 'options'=>['A'=>'student','B'=>'teacher','C'=>'assistant','D'=>'developer']],
                    ['q'=>18, 'text'=>'The main purpose of this email is to', 'options'=>['A'=>'change an appointment','B'=>'complain about a service','C'=>'announce a schedule','D'=>'request a reschedule']],
                    ['q'=>19, 'text'=>'J.K Young comes across as', 'options'=>['A'=>'volatile','B'=>'despondent','C'=>'offensive','D'=>'enthusiastic']],
                ],
            ],
        ],
    ],
    3 => [
        'title'   => 'Part 3',
        'label'   => 'Reading for Information',
        'q_range' => [20, 28],
        'sections' => [
            [
                'type'          => 'paragraph_match',
                'passage_title' => 'The Emperor Penguin',
                'paragraphs'    => [
                    'A' => "The emperor penguin (Aptenodytes forsteri) is the tallest and heaviest of all living penguin species and is endemic to Antarctica. The male and female are similar in plumage and size, reaching 122 cm (48 in) in height and weighing from 22 to 45 kg (49 to 99 lb). The dorsal side and head are black and sharply delineated from the white belly, pale-yellow breast and bright-yellow ear patches. Like all penguins it is flightless, with a streamlined body, and wings stiffened and flattened into flippers for a marine habitat.",
                    'B' => "The emperor penguin is a social animal in its nesting and its foraging behaviour; birds hunting together may coordinate their diving and surfacing. Individuals may be active day or night. A mature adult travels throughout most of the year between the nesting area and ocean foraging areas; the species disperses into the oceans from January to March.",
                    'C' => "As the species has no fixed nest sites that individuals can use to locate their own partner or chick, emperor penguins must rely on vocal calls alone for identification. They use a complex set of calls that are critical to individual recognition between parents, offspring, and mates, displaying the widest variation in individual calls of all penguins. Vocalizing emperor penguins use two frequency bands simultaneously. Chicks use a frequency-modulated whistle to beg for food and to contact parents.",
                    'D' => "In 2012 the emperor penguin was uplisted from a species of least concern to near threatened by the IUCN. Along with nine other species of penguin, it is currently under consideration for inclusion under the US Endangered Species Act. The primary causes for an increased risk of species endangerment are declining food availability, due to the effects of climate change and industrial fisheries on the crustacean and fish populations. Other reasons for the species's placement on the Endangered Species Act's list include disease, habitat destruction, and disturbance at breeding colonies by humans. Of particular concern is the impact of tourism. One study concluded that emperor penguin chicks in a crèche become more apprehensive following helicopter approach to 1,000 m (3,281 ft).",
                ],
                'instructions' => 'Decide which paragraph, A to D, has the information given in each statement below. Select E if the information is not given in any of the paragraphs.',
                'options'      => ['A','B','C','D','E'],
                'option_labels'=> ['E' => 'Not given'],
                'questions'    => [
                    ['q'=>20, 'text'=>'Emperor penguins are currently at risk of extinction.'],
                    ['q'=>21, 'text'=>'Male and female emperor penguins look similar to one another.'],
                    ['q'=>22, 'text'=>'Emperor penguins mainly eat krill and squid.'],
                    ['q'=>23, 'text'=>'Emperor penguins show highly social behaviour when hunting.'],
                    ['q'=>24, 'text'=>'Because they have no fixed nest sites, emperor penguins depend on vocal calls to identify each other.'],
                    ['q'=>25, 'text'=>'Climate change, human activity, and disease all threaten emperor penguin populations.'],
                    ['q'=>26, 'text'=>"The emperor penguin's body is physically adapted for life in the water."],
                    ['q'=>27, 'text'=>'Emperor penguins have successfully bred in captivity outside Antarctica.'],
                    ['q'=>28, 'text'=>'Emperor penguins can vocalize using two frequency bands at once.'],
                ],
            ],
        ],
    ],
    4 => [
        'title'   => 'Part 4',
        'label'   => 'Reading for Viewpoints',
        'q_range' => [29, 38],
        'sections' => [
            [
                'type'          => 'mcq',
                'passage_title' => "Helping Kids Build Stronger Bones",
                'passage'       => "<p>It's been ingrained in our heads since we were little: building strong bones is important. The problem is that these days many children aren't getting the daily recommended diet and exercise needed to do so. And, with technology use at an all-time high, children are spending less time playing outdoors, which increases the risk of obesity.</p>
<p>Today, approximately 32 percent of American children and adolescents ages 2 to 19 are considered overweight or obese. The American Academy of Orthopaedic Surgeons (AAOS) wants to help empower families to get up, get out, and get moving to ensure optimal bone growth and reduce the risk of osteoporosis and other diseases later in life.</p>
<p>\"Building your child's bone bank is like a college savings plan: the earlier you start investing, the better,\" says AAOS spokesperson Dr. Jennifer Weiss, a pediatric orthopaedic surgeon at Kaiser Permanente in Los Angeles. \"Parents should ensure that kids are getting adequate calcium to keep their bones strong, as well as appropriate levels of vitamins D and C to allow the body to absorb the calcium.\"</p>
<p>So what's a parent to do? The following tips from the AAOS will help get your kids (and you) moving while building better, stronger bones:</p>
<p><strong>Move it.</strong> Make physical activity a part of a child's schedule for at least 30 to 60 minutes per day. Make it fun — walking around the block or riding a bike is a great way to engage with your kids and still get in some exercise. As a bonus, being outside gets you and the kids some much-needed vitamin D, which also helps build strong bones.</p>
<p><strong>Watch what you eat.</strong> Busy schedules make on-the-run snacks and meals an easy fix for harried parents. Unfortunately, most of this kind of food doesn't have the nutrients needed for good bone health. Adolescents should consume a healthy diet with calcium to maintain strong bones and lower the risk of excessive weight gain.</p>",
                'instructions' => 'Choose the best option according to the information given in the article.',
                'questions'    => [
                    ['q'=>29, 'text'=>'What is this article mainly about?', 'options'=>['A'=>'Raising a healthy child in general','B'=>"How vitamins affect a child's growth",'C'=>'The causes of childhood obesity','D'=>'How to help children build stronger bones']],
                    ['q'=>30, 'text'=>'According to the AAOS, families should', 'options'=>['A'=>'encourage children to be self-reliant','B'=>'take responsibility for helping their children stay active','C'=>"rely on their doctor to manage bone health",'D'=>'leave physical activity to the school']],
                    ['q'=>31, 'text'=>'According to Dr. Jennifer Weiss, vitamins D and C are important because', 'options'=>['A'=>'they help the body absorb calcium','B'=>'they help children eat more evenly','C'=>'they should be taken three times a day','D'=>'they are made naturally by sunlight']],
                    ['q'=>32, 'text'=>'The AAOS recommends that children', 'options'=>['A'=>'keep learning constantly','B'=>'have a healthy diet provided by their school','C'=>'get plenty of physical activity and eat a healthy diet','D'=>'exercise for exactly one hour a day']],
                    ['q'=>33, 'text'=>'Overall, the article suggests that childhood habits around bone health', 'options'=>['A'=>"can have a long-term impact on a child's health",'B'=>'do not affect bone growth','C'=>'should be managed from birth','D'=>'are closely tied to obesity']],
                ],
            ],
            [
                'type'          => 'mcq',
                'passage_title' => 'A Reader Comment',
                'passage'       => "<p>This is a great article, but I see it a little differently. Most children actually have <strong>(34)</strong>___. In fact, children in Japan, China, and many other countries consume far less calcium than their North American peers, yet they still <strong>(35)</strong>___. That's because the human body is an incredibly efficient <strong>(36)</strong>___. Like our hair, bone is <strong>(37)</strong>___ that is constantly being built up, broken down, and renewed. Throughout our lives, bones take up and release calcium and other minerals as part of <strong>(38)</strong>___ by factors such as diet, exercise, hormones, genetics, and certain diseases.</p>",
                'instructions' => 'Complete the comment by choosing the best option to fill in each blank.',
                'questions'    => [
                    ['q'=>34, 'text'=>'Blank (34)', 'options'=>['A'=>'enough nutrients','B'=>'a lot of bones','C'=>'no trouble developing strong bones','D'=>'studied bone health a lot']],
                    ['q'=>35, 'text'=>'Blank (35)', 'options'=>['A'=>'grow well','B'=>'gain knowledge about bones','C'=>'have parents managing it for them','D'=>'develop strong, healthy bones']],
                    ['q'=>36, 'text'=>'Blank (36)', 'options'=>['A'=>'regulator of bone growth','B'=>'consumer of food','C'=>'circulator of blood','D'=>'generator of new cells only']],
                    ['q'=>37, 'text'=>'Blank (37)', 'options'=>['A'=>'tissue that stops growing','B'=>'tissue that is dying','C'=>'tissue that keeps changing shape','D'=>'a living tissue']],
                    ['q'=>38, 'text'=>'Blank (38)', 'options'=>['A'=>'a routine affected by low-level exercise','B'=>'a rate determined by insurance','C'=>'a cycle that is influenced','D'=>'a level determined by vitamins']],
                ],
            ],
        ],
    ],
];

require_once __DIR__ . '/functions.php';
/** @var \PDO $db */
$answers  = loadTestAnswers($db, $testCode);
$maxScore = 38;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CELPIP Reading Practice Test 2 – EduHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .main-wrapper { padding: 1.5rem; min-height: 100vh; }
        .sec-tabs { display: flex; border-bottom: 1px solid #dee2e6; margin-bottom: 1.5rem; flex-wrap:wrap; }
        .sec-tab { border: none; background: transparent; padding: .55rem 1.2rem; font-weight: 600; color: #6b7280; border-bottom: 3px solid transparent; cursor: pointer; font-size: .88rem; }
        .sec-tab.active { color: #0d6efd; border-bottom-color: #0d6efd; }
        .sec-panel { display: none; }
        .sec-panel.active { display: block; }
        .content-col { padding: 0 0 3rem; }
        .passage-box { background: #f8fafc; border-radius: 10px; padding: 1.25rem 1.5rem; margin-bottom: 1rem; font-size: .92rem; line-height: 1.8; }
        .passage-box h4 { font-size: 1rem; font-weight: 700; margin-bottom: .4rem; }
        .sub-divider { border-top: 2px dashed #dee2e6; margin: 1.5rem 0; }
        .q-num { font-weight: 700; color: #0d6efd; min-width: 2rem; display: inline-block; }
        .question-row { display: flex; align-items: center; gap: .5rem; margin-bottom: .75rem; flex-wrap: wrap; }
        .match-select { border: 2px solid #dee2e6; border-radius: 6px; padding: .28rem .55rem; font-size: .88rem; background: #fff; }
        .mcq-card { background: #f8f9fa; border-radius: 8px; padding: .9rem; margin-bottom: .9rem; }
        .mcq-option { display: flex; align-items: flex-start; gap: .5rem; margin-bottom: .35rem; cursor: pointer; font-size: .9rem; }
        .mcq-option input[type=radio] { margin-top: 3px; flex-shrink: 0; }
        .section-badge { background: linear-gradient(135deg,#f59e0b,#fbbf24); color: white; padding: .3rem 1.1rem; border-radius: 50px; font-weight: 700; font-size: .82rem; }
        .timer-display { font-size: 1.5rem; font-weight: 700; color: #0d6efd; font-family: monospace; }
        .timer-display.warning { color: #dc3545; animation: blink 1s infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.5} }
        .result-badge { display: inline-block; color: #fff; border-radius: 8px; padding: .4rem 1rem; font-size: .95rem; font-weight: 700; margin: .25rem; }
        .schedule-table { width:100%; border-collapse: collapse; font-size:.82rem; }
        .schedule-table th, .schedule-table td { border:1px solid #e2e8f0; padding:.5rem .6rem; vertical-align:top; }
        .schedule-table th { background:#eef2ff; }
        .legend-pill { display:inline-block; background:#eef2ff; border-radius:6px; padding:.2rem .6rem; margin:.15rem; font-size:.78rem; }
        @media (max-width: 767px) { .content-col { padding: 1rem 1rem 2rem; } }
    </style>
</head>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="mobile-overlay" id="mobileOverlay"></div>
<?php include INCLUDES_PATH . '/navbar.php'; ?>

<div class="main-wrapper flex-grow-1" style="flex:1;">
    <?php include INCLUDES_PATH . '/topbar.php'; ?>

<main class="content p-4">

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <nav aria-label="breadcrumb" class="mb-0">
            <ol class="breadcrumb mb-0" style="font-size:.8rem;">
                <li class="breadcrumb-item"><a href="../resources_home.php">Resources</a></li>
                <li class="breadcrumb-item"><a href="index.php">Practice Tests</a></li>
                <li class="breadcrumb-item active">CELPIP Reading – Practice 2</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center gap-3">
            <span class="section-badge">Reading</span>
            <span class="text-muted small">38 Questions · 55 min</span>
            <span id="timerDisplay" class="timer-display">55:00</span>
            <button class="btn btn-primary btn-sm px-3" id="submitBtn" onclick="handleSubmit()">
                <i class="bi bi-check2-circle me-1"></i>Submit
            </button>
        </div>
    </div>

    <div class="sec-tabs">
        <?php foreach ($parts as $pNum => $part): ?>
        <button class="sec-tab <?= $pNum === 1 ? 'active' : '' ?>" onclick="switchSec(<?= $pNum ?>)" id="stab-<?= $pNum ?>">
            <?= htmlspecialchars($part['title']) ?> <span class="text-muted" style="font-size:.7rem;"><?= htmlspecialchars($part['label']) ?></span>
            <span class="text-muted ms-1" style="font-size:.72rem;">Q<?= $part['q_range'][0] ?>–<?= $part['q_range'][1] ?></span>
        </button>
        <?php endforeach; ?>
    </div>

    <form id="testForm" onsubmit="return false;">
    <?php foreach ($parts as $pNum => $part): ?>
    <div class="sec-panel <?= $pNum === 1 ? 'active' : '' ?>" id="spanel-<?= $pNum ?>">
        <div class="content-col">
            <?php foreach ($part['sections'] as $si => $sec): ?>
                <?php if ($si > 0): ?><div class="sub-divider"></div><?php endif; ?>
                <?php renderCelpip2Section($sec); ?>
            <?php endforeach; ?>

            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                <?php if ($pNum < count($parts)): ?>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="switchSec(<?= $pNum + 1 ?>)">
                    Part <?= $pNum + 1 ?> <i class="bi bi-arrow-right ms-1"></i>
                </button>
                <?php else: ?>
                <button type="button" class="btn btn-success px-4 btn-sm" onclick="handleSubmit()">
                    Submit Test <i class="bi bi-send ms-1"></i>
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    </form>

</main>
</div><!-- /.main-wrapper -->

<?php include INCLUDES_PATH . '/adverts.php'; ?>

<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const CORRECT   = <?= json_encode($answers) ?>;
const MAX_SCORE = <?= $maxScore ?>;
const TEST_CODE = <?= json_encode($testCode) ?>;
const startTime = Date.now();
let userAnswers = {}, timeLeft = <?= $timeLimit ?>, submitted = false;

const timerEl = document.getElementById('timerDisplay');
const timerInterval = setInterval(() => {
    if (submitted) return;
    timeLeft--;
    const m = Math.floor(timeLeft / 60), s = timeLeft % 60;
    timerEl.textContent = `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
    if (timeLeft <= 300) timerEl.classList.add('warning');
    if (timeLeft <= 0) { clearInterval(timerInterval); handleSubmit(true); }
}, 1000);

function switchSec(n) {
    document.querySelectorAll('.sec-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.sec-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('spanel-' + n).classList.add('active');
    document.getElementById('stab-' + n).classList.add('active');
}

function collectAnswers() {
    document.querySelectorAll('select[data-q]').forEach(el => { userAnswers[el.dataset.q] = el.value.trim().toLowerCase(); });
    document.querySelectorAll('input[type=radio]:checked[data-q]').forEach(el => { userAnswers[el.dataset.q] = el.value.trim().toLowerCase(); });
}

function gradeAnswers() {
    let score = 0;
    for (let q in CORRECT) {
        const given = (userAnswers[q] || '').toLowerCase().trim();
        if (CORRECT[q].includes(given)) score++;
    }
    return score;
}

function showFeedback() {
    document.querySelectorAll('select[data-q]').forEach(el => {
        const q = el.dataset.q, given = el.value.trim().toLowerCase(), correct = CORRECT[q] || [];
        el.style.borderColor = correct.includes(given) ? '#198754' : '#dc3545';
        el.style.background  = correct.includes(given) ? '#d1e7dd' : '#f8d7da';
    });
    document.querySelectorAll('.mcq-card').forEach(card => {
        const q = card.dataset.q;
        const given = (userAnswers[q] || '').toLowerCase();
        const correct = (CORRECT[q] || [])[0] || '';
        card.querySelectorAll('.mcq-option').forEach(opt => {
            const val = opt.querySelector('input').value.toLowerCase();
            opt.style.background = '';
            if (val === correct) opt.style.background = '#d1e7dd';
            else if (val === given) opt.style.background = '#f8d7da';
        });
    });
}

function saveAttempt(score, timeSpent) {
    fetch('save_attempt.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            test_code:  TEST_CODE,
            score,
            max_score:  MAX_SCORE,
            band_score: null,
            time_spent: timeSpent,
            answers:    userAnswers,
        }),
    }).catch(err => console.error('save_attempt:', err));
}

async function handleSubmit(auto = false) {
    if (submitted) return;
    if (!auto) {
        const r = await Swal.fire({
            title: 'Submit Test?', text: 'You cannot change your answers after submitting.', icon: 'question',
            showCancelButton: true, confirmButtonText: 'Yes, submit', cancelButtonText: 'Continue working', confirmButtonColor: '#0d6efd',
        });
        if (!r.isConfirmed) return;
    }
    submitted = true;
    clearInterval(timerInterval);
    document.getElementById('submitBtn').disabled = true;
    collectAnswers();
    const score     = gradeAnswers();
    const timeSpent = Math.round((Date.now() - startTime) / 1000);
    showFeedback();
    saveAttempt(score, timeSpent);
    document.querySelectorAll('input, select').forEach(el => el.disabled = true);
    Swal.fire({
        title: 'Test Complete!',
        html: `<div class="text-center">
                    <div class="result-badge" style="background:#0d6efd;">Score: ${score} / ${MAX_SCORE}</div>
                    <p class="mt-3 text-muted small">Correct answers are highlighted below.</p>
                </div>`,
        icon: 'success', confirmButtonText: 'View Feedback', confirmButtonColor: '#0d6efd',
    }).then(() => switchSec(1));
}
</script>

<?php
function renderCelpip2Section(array $s): void {
    switch ($s['type']) {
        case 'mcq':             renderCelpip2Mcq($s);           break;
        case 'paragraph_match':  renderCelpip2ParagraphMatch($s); break;
        case 'schedule':         renderCelpip2Schedule($s);       break;
    }
}

function renderCelpip2Mcq(array $s): void {
    if (!empty($s['passage'])): ?>
    <div class="passage-box">
        <?php if (!empty($s['passage_title'])): ?><h4><?= htmlspecialchars($s['passage_title']) ?></h4><?php endif; ?>
        <?= $s['passage'] ?>
    </div>
    <?php endif; ?>
    <p class="fw-semibold small"><?= $s['instructions'] ?></p>
    <?php foreach ($s['questions'] as $q): ?>
    <div class="mcq-card" data-q="<?= $q['q'] ?>">
        <p class="fw-semibold small mb-2"><span class="q-num"><?= $q['q'] ?>.</span><?= htmlspecialchars($q['text']) ?></p>
        <?php foreach ($q['options'] as $letter => $text): ?>
        <label class="mcq-option">
            <input type="radio" name="q<?= $q['q'] ?>" value="<?= strtolower($letter) ?>" data-q="<?= $q['q'] ?>">
            <span class="small"><strong><?= $letter ?></strong> &nbsp; <?= htmlspecialchars($text) ?></span>
        </label>
        <?php endforeach; ?>
    </div>
    <?php endforeach;
}

function renderCelpip2ParagraphMatch(array $s): void { ?>
    <div class="passage-box">
        <h4><?= htmlspecialchars($s['passage_title']) ?></h4>
        <?php foreach ($s['paragraphs'] as $letter => $text): ?>
        <p><strong><?= $letter ?>.</strong> <?= htmlspecialchars($text) ?></p>
        <?php endforeach; ?>
        <p class="text-muted fst-italic mb-0"><strong>E.</strong> Not given in any of the above paragraphs.</p>
    </div>
    <p class="fw-semibold small"><?= $s['instructions'] ?></p>
    <?php foreach ($s['questions'] as $row): ?>
    <div class="question-row">
        <span class="q-num"><?= $row['q'] ?>.</span>
        <span class="flex-grow-1 small"><?= htmlspecialchars($row['text']) ?></span>
        <select class="match-select" data-q="<?= $row['q'] ?>">
            <option value="">–</option>
            <?php foreach ($s['options'] as $opt): ?>
            <option value="<?= strtolower($opt) ?>"><?= $opt ?><?= isset($s['option_labels'][$opt]) ? ' – ' . $s['option_labels'][$opt] : '' ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php endforeach;
}

function renderCelpip2Schedule(array $s): void { ?>
    <div class="passage-box">
        <h4><?= htmlspecialchars($s['passage_title']) ?></h4>
        <div class="mb-2">
            <?php foreach ($s['group_legend'] as $code => $desc): ?>
            <span class="legend-pill"><strong><?= htmlspecialchars($code) ?></strong> – <?= htmlspecialchars($desc) ?></span>
            <?php endforeach; ?>
        </div>
        <div class="table-responsive">
        <table class="schedule-table">
            <thead><tr><th></th><th>Monday</th><th>Tuesday</th><th>Wednesday</th></tr></thead>
            <tbody>
            <?php foreach ($s['schedule_rows'] as $row): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['week']) ?></strong></td>
                    <td><?= $row['mon'] ?></td>
                    <td><?= $row['tue'] ?></td>
                    <td><?= $row['wed'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
<?php }
?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
