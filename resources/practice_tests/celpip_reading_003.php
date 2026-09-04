<?php
// CELPIP Reading Practice 3 — transcribed from Downloads/CELPIP TASKS/Celpip Reading/Test 3
// (source prose was already native-quality; only light grammar cleanup applied)
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}

$testCode  = 'CELPIP_PT_R_003';
$timeLimit = 55 * 60;

$parts = [
    1 => [
        'title'   => 'Part 1',
        'label'   => 'Reading Correspondence',
        'q_range' => [1, 11],
        'sections' => [
            [
                'type'          => 'mcq',
                'passage_title' => "Cara's Letter",
                'passage'       => "<p>Dear Christen,</p>
<p>How was your trip to Korea? I heard the weather there is very pleasant this time of the year. I'm not sure but I think it's warmer than -5°C from what Derek told me. Which city did you go to? I heard the food scene there is pretty awesome! It would've been nice if Carrie would let me have a vacation, but she and I had prior commitments. You know how work gets!</p>
<p>It has been freezing here. Global warming is for real! You know, they say it's not just about hot temperatures but about extreme temperatures! Maybe that's why this winter is a killer! And don't get me started on the mountains of snow that are piling up! The city is doing a lot and we are doing all we can to not slip or fall on the ice; no one would bet against the ice in Canada!</p>
<p>Once again, the city is contributing in a way by creating some heated bus shelters. You will find that especially useful! The commute needs to be made more comfortable for the winter months. It's almost like we assume we're like other countries by keeping some bus stops outdoors, but we clearly could use a model tweak! How is the commute there? Are you renting a car or doing the usual? I have so many questions to ask you! Oh, one more thing — due to the pandemic, interest rates are really down on cars. Getting a good deal could have been very possible if cars weren't selling like pancakes, but I guess I'm late to everything! Sigh. Do you want me to look at a few deals for you? What are your thoughts on peer-to-peer commuting? When you get back, we can probably think about that.</p>
<p>On top of the cost savings, what I really need is a little entertainment in my morning commute. This snow is not doing my mood any good! As you can see, I'm already planning everything, expecting your return! Oh, and I just got the latest edition of the Korean DVDs that we watch. Still, I'm curious to know if you find deals cheaper than $10 per DVD for those dramas; let me know!</p>
<p>Can't wait to have you back. Miss you lots!</p>
<p>Take care,<br>Cara</p>",
                'instructions' => 'Choose the best way to complete each statement using the appropriate information from the passage.',
                'questions'    => [
                    ['q'=>1, 'text'=>'According to Cara, the winter weather in Korea is usually', 'options'=>['A'=>'minus 10 degrees Celsius','B'=>'minus 5 degrees Celsius','C'=>'30 degrees Celsius','D'=>'minus 2 degrees Celsius']],
                    ['q'=>2, 'text'=>'Cara says global warming', 'options'=>['A'=>'has a different meaning than what people think','B'=>'kills people in the winter','C'=>'is making things extremely hot','D'=>'is causing ice']],
                    ['q'=>3, 'text'=>'The bus stops in Canada', 'options'=>['A'=>'are heated','B'=>'need to be modelled on ones in other countries','C'=>'are mostly cold inside','D'=>'are all being upgraded by the city']],
                    ['q'=>4, 'text'=>'Christen usually', 'options'=>['A'=>'rents a car','B'=>'takes the bus','C'=>'drives','D'=>'uses peer-to-peer commuting']],
                    ['q'=>5, 'text'=>'Cara', 'options'=>['A'=>'can get a good deal if she buys a car now','B'=>'cannot find the right car since she was late','C'=>'cannot find a good deal','D'=>'will rather go for peer-to-peer commuting']],
                    ['q'=>6, 'text'=>'Cara', 'options'=>['A'=>'loves the snow','B'=>"has anxiety about Christen's return",'C'=>'will buy Korean DVDs if Christen finds a cheaper deal','D'=>'probably paid over $10 for Korean DVDs']],
                ],
            ],
            [
                'type'          => 'mcq',
                'passage_title' => "Christen's Reply",
                'passage'       => "<p>Hi Cara!</p>
<p>You are crazy! I was reading things twice to catch up with all your thoughts jumbled into one letter! Well, you're right that the ice scene in Korea <strong>(7)</strong>___; I do miss it though. I haven't been commuting much actually, as buses can be pretty crowded in Seoul, so <strong>(8)</strong>___. That's where I am staying for now, and yes, <strong>(9)</strong>___!</p>
<p>I don't ever think <strong>(10)</strong>___. Update me on your work situation though. Why are you <strong>(11)</strong>___? After all, you are a hard worker and you deserve better!</p>
<p>Well, I have to go now but I will write back to you ASAP!</p>
<p>Love,<br>Christen</p>",
                'instructions' => 'Complete the response by selecting the best choice for each blank.',
                'questions'    => [
                    ['q'=>7,  'text'=>'Blank (7)',  'options'=>['A'=>'is very comparable to Canada','B'=>'is less slippery than Canada','C'=>'is nothing like Canada','D'=>'can be betted against']],
                    ['q'=>8,  'text'=>'Blank (8)',  'options'=>['A'=>"life hasn't changed much",'B'=>'not the usual','C'=>'I am renting a car','D'=>'I am avoiding the cold bus stops']],
                    ['q'=>9,  'text'=>'Blank (9)',  'options'=>['A'=>'the food is great','B'=>'the bus stops are heated','C'=>"we don't have mountains of snow here",'D'=>"people don't fall on ice here"]],
                    ['q'=>10, 'text'=>'Blank (10)', 'options'=>['A'=>'your thirst for entertainment will die','B'=>'you will find good deals on cars','C'=>'you will find good deals on DVDs','D'=>'the snowfall will lessen']],
                    ['q'=>11, 'text'=>'Blank (11)', 'options'=>['A'=>'not being allowed to travel','B'=>'not too optimistic','C'=>'and Carrie working so hard','D'=>'taking instructions from Carrie']],
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
                'type'          => 'brochure',
                'passage_title' => 'Brochure Styles',
                'styles' => [
                    ['name'=>'Book Style', 'desc'=>'Offers a plethora of information with visuals and text, providing a detailed overview of features included, as well as catering to the volume of information. It could be overwhelming to see the book format for the client, on top of causing financial strain for the business owner.'],
                    ['name'=>'Mac Style', 'desc'=>'A non-vintage style offering a variety of colors. Total pages per brochure are just 2. Two pages with jam-packed information regarding all contents and features; emphasis is more on the graphics while the text size reduces to compensate for the number of pages.'],
                    ['name'=>'Landscape Style', 'desc'=>'A reasonable mix from the previous two, the landscape format offers one entire page for graphics while the other is exclusively for text. The landscape format is by far the most dominant revenue-generating product for brochure producers owing to its effectiveness in delivering sales for businesses.'],
                ],
            ],
            [
                'type'          => 'mcq',
                'passage_title' => "Charles's Email to Maria",
                'passage'       => "<p><em>Subject: Brochures</em></p>
<p>Hi Maria,</p>
<p>Hope you are well. I just forwarded you the email regarding the three brochure styles. With regards to the Book Style, while it appeals to us <strong>(12)</strong>___, it might cause strain due to the time delay from the overwhelming amount of pages that need to be produced.</p>
<p>On the other hand, the Mac Style might appeal to the younger audience more, given <strong>(13)</strong>___. I think we should really think about that!</p>
<p>Statistically, however, for businesses, the Landscape Style blows the other two out of the water in terms of <strong>(14)</strong>___, and we can really leverage that. Not only that, it might also <strong>(15)</strong>___ when compared with the Book Style. Plus, comparatively, I feel like it will overcome the <strong>(16)</strong>___ issue that the Mac Style faces.</p>
<p>Curious to know your thoughts!</p>
<p>Best,<br>Charles</p>",
                'instructions' => 'Read the passage as it relates to the diagram above. Select the best answer to fill in each blank.',
                'questions'    => [
                    ['q'=>12, 'text'=>'Blank (12)', 'options'=>['A'=>'financially','B'=>'visually','C'=>'content-wise','D'=>'business-wise']],
                    ['q'=>13, 'text'=>'Blank (13)', 'options'=>['A'=>'its latest design','B'=>'the jam-packed information','C'=>'the visuals and graphics','D'=>'the lesser pages']],
                    ['q'=>14, 'text'=>'Blank (14)', 'options'=>['A'=>'its sales','B'=>'its effectiveness for businesses','C'=>'its dominance','D'=>'a reasonable mix']],
                    ['q'=>15, 'text'=>'Blank (15)', 'options'=>['A'=>'save us money','B'=>'give more text','C'=>'be more detailed','D'=>'be more underwhelming']],
                    ['q'=>16, 'text'=>'Blank (16)', 'options'=>['A'=>'non-vintage design','B'=>'excessive graphics','C'=>'number of pages','D'=>'lack of information']],
                ],
            ],
            [
                'type'          => 'mcq',
                'passage_title' => null,
                'passage'       => null,
                'instructions'  => 'Complete the following statements by selecting the best answer.',
                'questions'    => [
                    ['q'=>17, 'text'=>'Part of the reason why Charles is inclined to go for the Landscape style comes from', 'options'=>['A'=>'definite facts','B'=>'curiosity','C'=>'his dislike of the other two styles','D'=>'his dislike of the Book Style']],
                    ['q'=>18, 'text'=>'Charles is', 'options'=>['A'=>'definite about his choice','B'=>'asking Maria for help in deciding the right brochure','C'=>'presenting his opinions with doubt','D'=>'presenting his opinions with reasonable confidence']],
                    ['q'=>19, 'text'=>'Charles', 'options'=>['A'=>'just wants to present his opinion','B'=>'is waiting for feedback','C'=>'has come to a final conclusion','D'=>"needs confirmation from Maria on her decision"]],
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
                'passage_title' => 'Blockchain Technology',
                'paragraphs'    => [
                    'A' => "Blockchain is a new technology that is taking over the old ways of how money was managed. Blockchain is used mainly for cryptocurrency, where the nodes of computer networks record transactions and keep an online log of all transactions taking place between different people. This is managed very accurately in order to create transparency and a complete record of every transaction that has taken place. A blockchain compiles pieces of information into what is known as a block; however, due to storage capacity, it has to be combined with other blocks within the same chain of transactions, which leads the blocks to form a chain — and hence, the name blockchain.",
                    'B' => "The aspect of decentralization makes a lot of people fond of blockchain technology. With different nodes of computers storing information, the complete sequence of transactions of, say, a company or an individual's payment history is all recorded in multiple locations by multiple computers worldwide. This has the benefit of giving the user access to their information in case their own hard drive fails or their data is lost — the information will still be stored on the blockchain network and can be retrieved at any time. Moreover, if someone tries to commit fraud by changing one transaction, it doesn't derail the other sequence of transactions in the blockchain: if there is faulty data or any discrepancy, the other nodes will pinpoint it and make it easily detectable.",
                    'C' => "Although the records stored in most blockchains are completely encrypted, they can be decrypted by the owner. This has the benefit of preventing fraud. There have been, and there will continue to be, frauds and scams; by allowing transparency in the blockchain, a scammer can easily be detected and caught by authorities, as has happened in the past. Any amount of crypto spent by the scammer can easily be detected and pinpointed as to where it was found, who spent it, and how much was spent.",
                    'D' => "When comparing blockchain technology to banks, there are a lot of differences to keep in mind. Although the fees for both are competitive, the speed of transactions for blockchain far outweighs what a bank can provide in the same area. Moreover, while banks can have control over your money and see your accounts if the government wants to take action against you, it is virtually impossible to do that on blockchain networks. Also, identification is needed for banks — not the case with blockchain! Finally, blockchain operates 24/7, compared to the limited business hours of banks.",
                ],
                'instructions' => 'Decide which paragraph, A to D, matches each statement below. Select E if the information is not given.',
                'options'      => ['A','B','C','D','E'],
                'option_labels'=> ['E' => 'Not given'],
                'questions'    => [
                    ['q'=>20, 'text'=>'The person who spends a certain amount of money can be found.'],
                    ['q'=>21, 'text'=>'A name for the concept is formulated.'],
                    ['q'=>22, 'text'=>'Blockchain leaves no digital footprint due to no identification.'],
                    ['q'=>23, 'text'=>'Government control.'],
                    ['q'=>24, 'text'=>'It is impossible for governments to take action against you if you use blockchain.'],
                    ['q'=>25, 'text'=>'Another component is needed due to storage constraints.'],
                    ['q'=>26, 'text'=>'Protects the hard drive.'],
                    ['q'=>27, 'text'=>'Several devices are used to execute the workings of blockchain.'],
                    ['q'=>28, 'text'=>'A global application.'],
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
                'passage_title' => 'German Cars vs. Japanese Cars',
                'passage'       => "<p>Francis Goodwin, CEO of Audi's Eastern branch, claims that German cars are the most popular in the world due to demand, but also due to the basic human needs they satisfy — comfort, performance, and luxury. He deems these aspects of cars essential to providing value. In his opinion, the pros outweigh the concerns over the expense associated with German cars.</p>
<p>George Pallister, Chief Financial Officer at St. Jose's Toyota branch, has quite the contrary opinion. For George, the financial conditions of people are something to be catered to. He believes that when reasonable reliability and warranties are offered, savings — both long-term and short-term — go through the roof, in a good way! Car maintenance, repairs, or even the purchase of a new car are not deemed necessary if a car can push beyond 200,000 km. Reliability is one of Toyota's chief offerings, and it's also something German car manufacturers really need to compete against.</p>
<p>But what about profitability? With the majority of people owning Japanese cars worldwide, it would seem Pallister scores in this area; nonetheless, it's possibly the competitors who maintain an edge. The quality and value claimed by Goodwin — delivered by companies like Audi, Mercedes, and BMW — command a certain price level which, though paid by a few, results in the numbers needed to keep German car companies in the lead. Areas such as interior, comfort, and elegance all come at a price tag that investors and CEOs of companies like Audi relish.</p>
<p>Statistically, in terms of safety ratings, German cars are once again a step ahead. The German car industry goes through intense testing, innovation, and safety protocols to ensure security. Nonetheless, George is adamant that, despite this debatable area, Japanese cars have the overall edge in terms of being the available product for the majority — which automatically labels them as winners in the race of car manufacturers. Nevertheless, if the profitability of both competitors is noted, he might not be completely accurate.</p>",
                'instructions' => 'Complete each statement by selecting the best option.',
                'questions'    => [
                    ['q'=>29, 'text'=>'In terms of profitability', 'options'=>['A'=>'Goodwin prefers charging people more for German cars','B'=>'Japanese cars do much better due to higher sales','C'=>'Goodwin clearly wins the debate','D'=>'German cars may have an edge']],
                    ['q'=>30, 'text'=>'Francis Goodwin', 'options'=>['A'=>'has made a pros and cons list','B'=>'thinks that the pros of German cars cost more','C'=>'feels the high cost of German cars is justified','D'=>'claims that German cars offer more warranties']],
                    ['q'=>31, 'text'=>'Pallister', 'options'=>['A'=>'cares too much about the safety ratings','B'=>'is concerned about the safety ratings','C'=>'has self-assurance that his industry leads the race','D'=>'is a very stubborn person']],
                    ['q'=>32, 'text'=>'Francis Goodwin is the type of person who', 'options'=>['A'=>'insists his customers pay more','B'=>"is appreciative if his company's revenues are good",'C'=>"is proud of German cars' safety ratings",'D'=>'debates George Pallister on what "basic needs" are']],
                    ['q'=>33, 'text'=>'Japanese cars', 'options'=>['A'=>'can push beyond 200,000 km with no issues','B'=>'offer more warranties than German cars','C'=>'have more focus on reliability as a selling point','D'=>'are preferred for long-term use']],
                ],
            ],
            [
                'type'          => 'mcq',
                'passage_title' => 'A Visitor Comment',
                'passage'       => "<p>Though I am not really a minimalist, I cannot help but <strong>(34)</strong>___. I currently drive a Mercedes, and I must tell you that the comfort I find in this car is out of this world! However, a Japanese automobile could have provided me <strong>(35)</strong>___. Then again, it's also a matter of the social world — do I need to show off with my car, or do I simply consider <strong>(36)</strong>___?</p>
<p>Honestly, in the future, a change of car may be necessary as I get older. After retirement, <strong>(37)</strong>___ will be an important factor as I live off what I have left. Safety will become another very crucial priority, and considering <strong>(38)</strong>___, it gives me more things to think about.</p>",
                'instructions' => 'Pick the best answer to complete each blank.',
                'questions'    => [
                    ['q'=>34, 'text'=>'Blank (34)', 'options'=>['A'=>'support Francis','B'=>'support George','C'=>'support price tags','D'=>'support justified prices']],
                    ['q'=>35, 'text'=>'Blank (35)', 'options'=>['A'=>'more savings on maintenance','B'=>'more warranties','C'=>'more mileage','D'=>'more luxury']],
                    ['q'=>36, 'text'=>'Blank (36)', 'options'=>['A'=>'worrying about mileage','B'=>'joining the majority that does not','C'=>'worrying about luxury','D'=>'going for a car with no value']],
                    ['q'=>37, 'text'=>'Blank (37)', 'options'=>['A'=>'luxury','B'=>'costs','C'=>'reliability','D'=>'mileage']],
                    ['q'=>38, 'text'=>'Blank (38)', 'options'=>['A'=>'Japanese cars offering more reliability','B'=>'Japanese cars seeming confident with their warranties','C'=>'German cars coming up with new safety measures','D'=>'both industries rating high on safety']],
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
    <title>CELPIP Reading Practice Test 3 – EduHub</title>
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
        .brochure-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:.75rem; margin-top:.5rem; }
        .brochure-card { background:#eef2ff; border-radius:8px; padding:.85rem; font-size:.82rem; line-height:1.6; }
        .brochure-card strong { color:#4338ca; display:block; margin-bottom:.3rem; }
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
                <li class="breadcrumb-item active">CELPIP Reading – Practice 3</li>
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
                <?php renderCelpip3Section($sec); ?>
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
function renderCelpip3Section(array $s): void {
    switch ($s['type']) {
        case 'mcq':             renderCelpip3Mcq($s);           break;
        case 'paragraph_match':  renderCelpip3ParagraphMatch($s); break;
        case 'brochure':         renderCelpip3Brochure($s);       break;
    }
}

function renderCelpip3Mcq(array $s): void {
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

function renderCelpip3ParagraphMatch(array $s): void { ?>
    <div class="passage-box">
        <h4><?= htmlspecialchars($s['passage_title']) ?></h4>
        <?php foreach ($s['paragraphs'] as $letter => $text): ?>
        <p><strong><?= $letter ?>.</strong> <?= htmlspecialchars($text) ?></p>
        <?php endforeach; ?>
        <p class="text-muted fst-italic mb-0"><strong>E.</strong> Not given.</p>
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

function renderCelpip3Brochure(array $s): void { ?>
    <div class="passage-box">
        <h4><?= htmlspecialchars($s['passage_title']) ?></h4>
        <div class="brochure-grid">
            <?php foreach ($s['styles'] as $style): ?>
            <div class="brochure-card">
                <strong><?= htmlspecialchars($style['name']) ?></strong>
                <?= htmlspecialchars($style['desc']) ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php }
?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
