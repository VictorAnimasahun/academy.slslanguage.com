<?php
// CELPIP Reading Practice 1 — transcribed from Downloads/CELPIP TASKS/Celpip Reading/Test 1
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}

$testCode  = 'CELPIP_PT_R_001';
$timeLimit = 55 * 60;

$parts = [
    1 => [
        'title'   => 'Part 1',
        'label'   => 'Reading Correspondence',
        'q_range' => [1, 11],
        'sections' => [
            [
                'type'          => 'mcq',
                'passage_title' => 'Message from Mrs. Birch',
                'passage'       => "<p>Dear Ms. Green,</p>
<p>I am sorry to bother you, as I know you are very busy, but I would like to change my vacation time request. I asked to take the first 2 weeks of July off, but now, if it is okay, I would like the first 2 weeks of August instead.</p>
<p>The rule is that no changes may be made once requests have been submitted in writing, but I think that requiring requests to be made in January is a little unreasonable. It is difficult for some people to know, 6 months in advance, what their summer plans will be. I understand that time is needed to hire temporary replacements to take care of things while people are away. I am also aware that everyone needs to coordinate their vacation plans in a way that ensures no single department is understaffed. However, I believe 2 or 3 months' notice should be sufficient. According to my friends, that's the amount of notice required by other places of employment here in Canada.</p>
<p>The reason I am changing my request is that I had originally planned to take a trip to Japan during the first 2 weeks of July. However, I have now decided to go to Africa instead, and that tour is scheduled for the first 2 weeks of August. My husband and his retired parents have already made a non-refundable deposit for the trip to Africa for the four of us, and they are really looking forward to it. In fact, my husband, who will be taking a solo business trip to Mexico afterwards, bought six suitcases yesterday and has already started planning everything. I do not want to disappoint my family with news that I cannot get the time off. As I am still giving 5 months' notice, I hope it will be sufficient.</p>
<p>Please let me know at your earliest convenience whether I can change my vacation time to the first 2 weeks of August, as I will need to either finalize the booking with Global Travel Agency or try to get our non-refundable deposit back!</p>",
                'instructions' => 'Choose the best option according to the information given in the message.',
                'questions'    => [
                    ['q'=>1, 'text'=>'It was unnecessary for the writer to mention the', 'options'=>['A'=>'date of her initial request','B'=>'expected period of absence','C'=>'name of the travel agency','D'=>'type of deposit she made']],
                    ['q'=>2, 'text'=>'Mrs. Birch accepts that', 'options'=>['A'=>'advance notice of vacation is required','B'=>'most departments are understaffed','C'=>'replacement workers are unavailable','D'=>"6 month's notice is the minimum"]],
                    ['q'=>3, 'text'=>'Mrs. Birch disagrees with the', 'options'=>['A'=>'"changes not allowed" rule','B'=>'"no other employment" rule','C'=>'"requests must be in writing" rule','D'=>'"6 months\' notice" rule']],
                    ['q'=>4, 'text'=>'The trip to Africa', 'options'=>['A'=>'is offered only in the summer','B'=>'occurs when July is over','C'=>'takes 4 weeks in total','D'=>'will depart from Japan']],
                    ['q'=>5, 'text'=>"Mrs. Birch's travel plans include", 'options'=>['A'=>'family members','B'=>'her close friends','C'=>'only her husband','D'=>'her co-workers']],
                    ['q'=>6, 'text'=>'In general, Mrs. Birch seems', 'options'=>['A'=>'confused','B'=>'happy','C'=>'pressured','D'=>'sorry']],
                ],
            ],
            [
                'type'          => 'mcq',
                'passage_title' => "Ms. Green's Reply",
                'passage'       => "<p>Dear Else,</p>
<p>Thank you for your recent email asking me to <strong>(7)</strong>___. I would like to assure you that I very much appreciate your comments about our policy. You are certainly not the first employee here at the Fabulous Furniture Company to <strong>(8)</strong>___ the requirements. I am aware, as your email suggests, that other companies are <strong>(9)</strong>___ policies.</p>
<p>Given the information you have provided, I'm pleased to say that I have decided to make an exception and <strong>(10)</strong>___.</p>
<p>I do hope you enjoy your adventure—I myself have wanted to visit <strong>(11)</strong>___ for some time now.</p>
<p>Regards,<br>Ms. Green</p>",
                'instructions' => 'Complete the response by filling in the blanks. Select the best choice for each blank.',
                'questions'    => [
                    ['q'=>7,  'text'=>'Blank (7)',  'options'=>['A'=>"change the dates you'll be away",'B'=>'confirm your travel insurance','C'=>'give you extra time off work','D'=>'revise our vacation policy']],
                    ['q'=>8,  'text'=>'Blank (8)',  'options'=>['A'=>'have difficulty meeting','B'=>'insist on changes to','C'=>'need an explanation of','D'=>'refuse to follow']],
                    ['q'=>9,  'text'=>'Blank (9)',  'options'=>['A'=>'different in terms of their','B'=>'ignoring such vacation','C'=>'now changing such strict','D'=>'popular because of their']],
                    ['q'=>10, 'text'=>'Blank (10)', 'options'=>['A'=>"approve 2 months' vacation",'B'=>'forward your message upwards','C'=>'grant your revised request','D'=>'hire your temporary replacement']],
                    ['q'=>11, 'text'=>'Blank (11)', 'options'=>['A'=>'Africa','B'=>'Canada','C'=>'Japan','D'=>'Mexico']],
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
                'type'          => 'diagram',
                'passage_title' => 'Lemongrass Gardening Centre',
                'image'         => 'lemongrass_gardening_centre.png',
                'image_dir'     => 'CELPIP_PT_R_001',
                'passage'       => '<p class="text-muted small">Grow your own organic garden with our selection of fruit and vegetable seeds! Gardening workshops held every Tuesday from 6:00–7:00 p.m. Or, simply drop by our store and speak to any of our knowledgeable staff. We are here to make your garden a success!</p>',
                'diagram_rows'  => [
                    ['plant'=>'Tomatoes',  'difficulty'=>'Moderate','season'=>'Spring', 'notes'=>['need plenty of direct sunlight','plant seeds deeply in the soil','require moderate watering per week','grow well next to carrots']],
                    ['plant'=>'Carrots',   'difficulty'=>'Easy',    'season'=>'Spring', 'notes'=>['grow well in full sun or partial shade','must be planted shallowly','keep seeds moist but do not drench','can be harvested at any size']],
                    ['plant'=>'Watermelon','difficulty'=>'Difficult','season'=>'Summer','notes'=>['likes lots of direct sun','place seeds at least half a metre apart','water roots of plant frequently','requires a lot of food, heavily compost the soil']],
                    ['plant'=>'Spinach',   'difficulty'=>'Easy',    'season'=>'Spring', 'notes'=>['grows best in shady conditions','plant seeds 1/2 inch deep in soil','water daily to keep the soil cool','pick when the leaves are 7-10 cm in length']],
                    ['plant'=>'Peas',      'difficulty'=>'Easy',    'season'=>'Winter or Spring','notes'=>['prefer full sun but can grow in partial shade','plant seeds 1 inch deep','avoid over-watering the seeds','grow very tall','plant near a fence or trellis']],
                    ['plant'=>'Pumpkins',  'difficulty'=>'Difficult','season'=>'Summer','notes'=>['require lots of direct sun','keep soil well-watered','require lots of room to grow','plant seeds in equal parts compost and soil']],
                ],
            ],
            [
                'type'          => 'mcq',
                'passage_title' => "Alan's Email to Lucy",
                'passage'       => "<p><em>Subject: Gardening</em></p>
<p>Hey Lucy,<br>Any interest in planting a vegetable garden? It'd be a great way to get cheap, nutritious food. I know you're not a fan of dirt, but it could be fun! Check out the brochure!</p>
<p>Since we're first time gardeners, I'd suggest starting with something simple. Obviously, we'd want to avoid growing <strong>(12)</strong>___. Also, our yard is very sunny, so it could be difficult to grow <strong>(13)</strong>___. Of course, we'd definitely need to plant pumpkin, since, as you know, I love pumpkin pies. We could work something out even though they require <strong>(14)</strong>___. For fresh pie, we accept the challenge!</p>
<p>Let's check out the gardening center after dinner. It sounds like the <strong>(15)</strong>___ place in town. Also, I'll stop by the library for a gardening guide since the brochure doesn't provide complete information for each plant. The brochure mentions harvest time for only <strong>(16)</strong>___. That's something we'd definitely need to know!</p>
<p>Best,<br>Alan</p>",
                'instructions' => 'Complete the email by filling in the blanks. Select the best choice for each blank.',
                'questions'    => [
                    ['q'=>12, 'text'=>'Blank (12)', 'options'=>['A'=>'watermelon','B'=>'peas','C'=>'tomatoes','D'=>'carrots']],
                    ['q'=>13, 'text'=>'Blank (13)', 'options'=>['A'=>'spinach','B'=>'peas','C'=>'watermelon','D'=>'carrots']],
                    ['q'=>14, 'text'=>'Blank (14)', 'options'=>['A'=>'plenty of space','B'=>'warm and dry soil','C'=>'lots of shade','D'=>'specialized seed compost']],
                    ['q'=>15, 'text'=>'Blank (15)', 'options'=>['A'=>'most helpful','B'=>'busiest','C'=>'largest','D'=>'most popular']],
                    ['q'=>16, 'text'=>'Blank (16)', 'options'=>['A'=>'carrots and spinach','B'=>'watermelon and peas','C'=>'spinach and tomatoes','D'=>'pumpkin and carrots']],
                ],
            ],
            [
                'type'          => 'mcq',
                'passage_title' => null,
                'passage'       => null,
                'instructions'  => 'Choose the best option according to the information given in the message.',
                'questions'    => [
                    ['q'=>17, 'text'=>'What does Lucy want Alan to do?', 'options'=>['A'=>'call the garden center for information','B'=>'research the harvest time of each plant','C'=>'make a pumpkin pie for dinner','D'=>'plant a vegetable garden with him']],
                    ['q'=>18, 'text'=>'Why does Alan think a garden is a good idea?', 'options'=>['A'=>'He knows Lucy would enjoy it.','B'=>'It is an affordable way to eat healthy food.','C'=>'It will fill empty space in their yard.','D'=>"He feels they don't eat enough healthy food."]],
                    ['q'=>19, 'text'=>'Why did Alan attach the brochure to his email?', 'options'=>['A'=>'to show her the new gardening in town','B'=>'to provide examples of things they can grow','C'=>'to encourage Lucy to plant a garden again','D'=>"to explain what he'll be doing this summer"]],
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
                'passage_title' => 'Camping in Canada',
                'paragraphs'    => [
                    'A' => "With most Canadians living in the southern part of the country, much of Canada's 9.9 million square kilometers is uninhabited. Consequently, outdoor wilderness activities—such as hunting, fishing, camping, hiking, and canoeing—are suitable pastimes. Of these, camping has the widest appeal. More than one third of Canadian households contain camping equipment, and about one quarter of the population camps each and every year. More than 37 national parks plus innumerable provincial parks and private campgrounds regularly attract camping enthusiasts and issue thousands of permits each year.",
                    'B' => "The most common camping choices in Canada range from posh, fully-equipped motorhomes to rustic wilderness camping with whatever the hiker can carry in a backpack. The most lavish option, the 6- to 13-meter-long motorhome, consumes tremendous amounts of fuel and requires large parking spaces in campgrounds. These campers should reserve their campsites well in advance. Retired couples sometimes take a year or more to travel around North America this way, perhaps in a cavalcade of up to a hundred vehicles. Another option is the RV trailer, which is shorter but requires a towing vehicle. RV trailers are far cheaper than motorhomes but still provide the conveniences of home.",
                    'C' => "In Canada, motorhomes and RVs are costly to buy or rent and to use. For those requiring a cheaper alternative and willing to forgo homey conveniences, the lightweight tent trailer is a possibility. Typically costing less than $10,000, it's easy to pull and maneuver. Essentially, it's a collapsible tent-like structure mounted on a rectangular four-sided box attached to two wheels. The interior varies with the model, the more expensive ones including a rustic kitchen, beds, running water, and an electrical hookup. More stable than a tent, which sits on the ground, its construction may still lead to camper discomfort during extreme weather.",
                    'D' => "Less restrictive and more economical than other mobile accommodations, tents can be used along designated hiking trails or in established campgrounds; though, regulations prevent tenters from pitching a tent anywhere they choose. Driving or biking from campsite to campsite stretch of the Trans-Canada highway, for example, has campgrounds that cater to motorhomes, tent trailers, and tenters. Some campgrounds have running water and showers, whereas others have only outhouses and outdoor cold-water taps. Reservations are accepted for any type of camping. Websites and books are available with detailed information about campsites in each province, facilitating vacation planning for adventurous campers.",
                ],
                'instructions' => 'Decide which paragraph, A to D, has the information given in each statement below. Select E if the information is not given in any of the paragraphs.',
                'options'      => ['A','B','C','D','E'],
                'option_labels'=> ['E' => 'Not given'],
                'questions'    => [
                    ['q'=>20, 'text'=>"Camping's popularity in Canada is evidenced by the percentage of frequent campers."],
                    ['q'=>21, 'text'=>'Certain types of camping cannot be done spontaneously.'],
                    ['q'=>22, 'text'=>'Some people who prefer one style of camping enjoy travelling in groups.'],
                    ['q'=>23, 'text'=>'Camping is a growing trend among wilderness enthusiasts.'],
                    ['q'=>24, 'text'=>'Campers should check where they are permitted to set up camp.'],
                    ['q'=>25, 'text'=>'Campsites are evenly spaced across the country.'],
                    ['q'=>26, 'text'=>'People who use motorhomes bring a second vehicle with them.'],
                    ['q'=>27, 'text'=>'Some campers choose a middle ground between luxury and rustic camping.'],
                    ['q'=>28, 'text'=>'Some camping equipment is less able to withstand unpleasant conditions.'],
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
                'passage_title' => 'Language Decline',
                'passage'       => "<p>The numbers are in, and they're grim: Three thousand of the world's seven thousand languages are in decline and expected to perish by around 2100. While the most prevalent languages are taking a firmer hold across the globe, the extinction rate for languages is 25 per year. Charting language demise, UNESCO ranks dwindling languages on a scale ranging from \"vulnerable\" to \"critically endangered.\" The question is how, or whether, UNESCO or sovereign governments should intervene?</p>
<p>Concerned language preservation organizations include the Canadian Association for Language Diversity (CALD), a charity whose goal is to prevent language extinctions. CALD spokesperson Norman Reideger says all endangered languages should be saved. \"For individuals, language fosters a sense of personal identity. Language extinction means loss of priceless, irreplaceable cultural knowledge—the grammar, music, narratives, and even medical knowledge embedded in a language. A linguistically diverse planet is a healthy planet.\"</p>
<p>Concordia University linguistics professor Marianne Houseman deplores such use of biodiversity rhetoric in linguistics discourse. \"Life is life, and language is language,\" she clarifies. Houseman is skeptical about whether speakers of endangered languages benefit from linguistic preservation, noting that those who abandon their language may be acting in their own best interests by adapting to a naturally evolving socio-economic climate. \"Whose needs are served by government-funded social—or socio-linguistic—engineering schemes?\" asks Houseman, \"Their proponents are typically nationalist regimes advancing their own territorial, political and economic agendas.\"</p>
<p>Annalisa Ducharme, a Memorial University doctoral candidate, points out that a confounding factor is variation within a language. \"One language can have multiple dialects—regional varieties,\" says Ducharme, \"If an endangered language is to be artificially propped up with government funded schools and preschools, as they do with some First Nation languages in Canada, then which—whose—version of the language should be deemed 'essential' and therefore worth saving?\" As well as First Nation languages, Ducharme points to French, which \"has many dialects worth sustaining. Globally, France, Louisiana, and Africa have their own versions. In New Brunswick, French dialects include Quebecois, Acadian, and Chiac, a sub-dialect that blends Acadian French, English, and [aboriginal] Mi'kmaq.\"</p>",
                'instructions' => 'Choose the best option according to the information given on the website.',
                'questions'    => [
                    ['q'=>29, 'text'=>'Annalisa Ducharme most likely objects to', 'options'=>['A'=>'inflated claims about language preservation outcomes','B'=>'public programs to revitalize dying languages for political reasons','C'=>'denials about the rate of language extinction','D'=>'a simplistic categorization or definition of a language']],
                    ['q'=>30, 'text'=>'Marianne Houseman thinks language preservation programs should be', 'options'=>['A'=>'modified','B'=>'continued','C'=>'consolidated','D'=>'abandoned']],
                    ['q'=>31, 'text'=>'Who holds directly opposing viewpoints?', 'options'=>['A'=>'Ducharme and UNESCO','B'=>'Houseman and Reideger','C'=>'Reideger and Ducharme','D'=>'Ducharme and Houseman']],
                    ['q'=>32, 'text'=>'Marianne Houseman would most likely agree that', 'options'=>['A'=>'investments in language immersion schools are defensible','B'=>'language preservation policies deserve public attention','C'=>'people stand to gain from a lingua franca','D'=>'dying languages are a priceless cultural resource']],
                    ['q'=>33, 'text'=>'Overall, the article suggests that efforts to preserve dying languages are', 'options'=>['A'=>'hampered by the link between language and identity','B'=>'fraught with an array of sociopolitical complications','C'=>'warranted on the basis of disturbing global statistics','D'=>'essential for the sake of preserving indigenous cultures']],
                ],
            ],
            [
                'type'          => 'mcq',
                'passage_title' => 'Visitor Comment',
                'passage'       => "<p>I'm Metis, of French and Cree descent. Having just 1,000 remaining speakers, Michif, the language of my people, is being sustained through Canadian federal government funded programs like those described in the article. The goal is to save it through transmission to <strong>(34)</strong>___. I have two points. First, I remind the <strong>(35)</strong>___ that historically, the demise of Michif was anything but an organic process; Michif endured systematic government efforts to erase it. Michif therefore merits government efforts to help it <strong>(36)</strong>___. I strongly disagree with the notion that language-targeted government redress and reconciliation programs are <strong>(37)</strong>___. Second, Michif has several dialects; we chose to revitalize two. While Ms. Ducharme may think targeting languages for revival is <strong>(38)</strong>___, we think Michif is worth reviving.</p>",
                'instructions' => 'Complete the comment by choosing the best option to fill in each blank.',
                'questions'    => [
                    ['q'=>34, 'text'=>'Blank (34)', 'options'=>['A'=>'the next generation','B'=>'linguistic researchers','C'=>'a range of First Nation groups','D'=>'Michif speakers']],
                    ['q'=>35, 'text'=>'Blank (35)', 'options'=>['A'=>'professor','B'=>'graduate students','C'=>'CALD representative','D'=>'United Nations']],
                    ['q'=>36, 'text'=>'Blank (36)', 'options'=>['A'=>'continue evolving','B'=>'grow more diverse','C'=>'make a comeback','D'=>'become official']],
                    ['q'=>37, 'text'=>'Blank (37)', 'options'=>['A'=>'language standardization efforts','B'=>'aggressive nationalist agendas','C'=>'likely to proliferate','D'=>'indulgent charity campaigns']],
                    ['q'=>38, 'text'=>'Blank (38)', 'options'=>['A'=>'unnecessary','B'=>'linguistic','C'=>'essential','D'=>'arbitrary']],
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
    <title>CELPIP Reading Practice Test 1 – EduHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <?php include __DIR__ . '/celpip_screen_styles.php'; ?>
</head>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="mobile-overlay" id="mobileOverlay"></div>
<?php include INCLUDES_PATH . '/navbar.php'; ?>

<div class="main-wrapper flex-grow-1" style="flex:1;">
    <?php include INCLUDES_PATH . '/topbar.php'; ?>

<main class="content p-4">

    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb mb-0" style="font-size:.8rem;">
            <li class="breadcrumb-item"><a href="../resources_home.php">Resources</a></li>
            <li class="breadcrumb-item"><a href="index.php">Practice Tests</a></li>
            <li class="breadcrumb-item active">CELPIP Reading – Practice 1</li>
        </ol>
    </nav>

    <?php require __DIR__ . '/celpip_reading_shell.php'; ?>

</main>
</div><!-- /.main-wrapper -->

<?php include INCLUDES_PATH . '/adverts.php'; ?>

<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php include __DIR__ . '/celpip_reading_script.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
