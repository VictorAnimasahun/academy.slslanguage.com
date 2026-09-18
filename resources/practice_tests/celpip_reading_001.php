<?php
// CELPIP Reading Practice 1 — "Reading Practice Test C" (labelled C since the
// two Full Mocks are A and B), replaces the original Test 1 content per
// instructor request 2026-09-18. Source: Downloads/files (3)/
// Reading_Practice_Test_C.docx + its Answer Key docx.
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}
require_once INCLUDES_PATH . '/course_lock.php';
require_course_enrollment([14], 'this CELPIP Reading practice test');

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
                'passage_title' => 'Message from Sofia',
                'passage'       => "<p>Hi Priya,</p>
<p>I'm sorry it's taken two weeks to write back—between unpacking and the new job, the days have blurred together. Halifax has been a whirlwind, but a good one.</p>
<p>The move itself started rough. The moving company lost track of our truck for almost a full day, so Tomas and I slept on the apartment floor with nothing but two suitcases and Max's food bowl. Everything showed up the next afternoon. Max spent three days flattened under the bed like a throw rug, barely touching his food. This morning I found him shredding the corner of a moving box like it owed him money, so I think we've turned a corner.</p>
<p>Work has been the real bright spot. I started at Larkspur Analytics on Monday, and it's already clear I made the right choice. My new manager, Devon, spent the first two days walking me through the client accounts personally instead of leaving me with a manual. The team is small—only nine people—and everyone genuinely seems to like working together, which wasn't always true at my last job. Our office is two floors above a bagel shop, which the whole team apparently treats as a running joke about the smell getting into the carpets.</p>
<p>Tomas is still job hunting. He's had two promising interviews, but nothing's come through, and I've caught myself doing the math on our savings some nights before bed, which I never used to do. He tells anyone who asks that it's \"just a matter of time.\" If I'm honest, most days my head is less on the apartment and more on whether we can keep affording two of everything on one income for a while.</p>
<p>I know your firm took on that massive new logistics account back in August, and last I heard, the onboarding was expected to keep you slammed through most of September. I could really use a friendly face around here whenever you surface. The drive along the coast is supposed to be stunning about now, if the timing works out on your end.</p>
<p>We still haven't found bath towels or a shower curtain, so the place feels a little bare in spots, but none of that matters much next to everything else going right. We also haven't figured out yet whether my new office health plan covers dental for Tomas while he's between jobs; I'm supposed to call HR about it next week.</p>
<p>Love,<br>Sofia</p>",
                'instructions' => 'Choose the best option according to the information given in the message.',
                'questions'    => [
                    ['q'=>1, 'text'=>"Max, Sofia's cat, is now…", 'options'=>['A'=>'refusing to eat.','B'=>'showing signs of settling in.','C'=>'confined to a crate at the vet.','D'=>'staying with a neighbour while they finish unpacking.']],
                    ['q'=>2, 'text'=>'Based on the letter, Sofia most likely wants Priya to visit…', 'options'=>['A'=>'sometime after September.','B'=>'before Labour Day.','C'=>"during Priya's busiest week at work.",'D'=>'once Tomas starts a new job.']],
                    ['q'=>3, 'text'=>'Overall, Sofia seems to feel…', 'options'=>['A'=>'regretful about the move.','B'=>'anxious about Tomas above all else.','C'=>'quietly optimistic despite some rough patches.','D'=>'frustrated with the moving company.']],
                    ['q'=>4, 'text'=>'Devon is described as someone who…', 'options'=>['A'=>'hired Sofia over other candidates.','B'=>'works remotely most days.','C'=>'left Sofia to learn independently.','D'=>"personally trained Sofia during her first days."]],
                    ['q'=>5, 'text'=>"Based on the letter, Sofia's most urgent concern is…", 'options'=>['A'=>'whether their savings will hold up while Tomas is between jobs.','B'=>'whether Max will fully settle into the new apartment.','C'=>'whether Tomas regrets leaving his previous job.','D'=>"whether her new health plan covers Tomas's dental care."]],
                    ['q'=>6, 'text'=>"The overall tone of Sofia's letter is best described as…", 'options'=>['A'=>'formal and reserved.','B'=>'excited and carefree.','C'=>'apologetic and regretful.','D'=>'warm and candid.']],
                ],
            ],
            [
                'type'          => 'mcq',
                'inline_blanks' => true,
                'passage_title' => "Priya's Reply",
                'passage'       => "<p>Hi Sofia,</p>
<p>Don't apologize—I know how much chaos a big move brings. I'm glad to hear Max is coming around; cats really do take moving personally.</p>
<p>It sounds like I should <strong>(7)</strong>___, since it's clearly been a big few weeks on more than one front. I'll check flights once things ease up on my end—my own crunch time should be winding down soon enough.</p>
<p>I have to say, it's such a relief that work is going well, especially after everything you dealt with at your last job. It sounds like Devon is exactly the kind of manager you needed—someone who actually <strong>(8)</strong>___ instead of just handing you a binder and walking away.</p>
<p>As for Tomas, tell him not to lose heart. Two interviews in this market is a good sign, even if it doesn't feel that way some nights. Waiting is always the hardest part.</p>
<p>One more thing—should I bring anything for the apartment? If there's anything specific you're still missing, from <strong>(9)</strong>___ to kitchen basics, let me know and I'll try to track it down before I fly out.</p>
<p><strong>(10)</strong>___ about the HR question—most plans I've seen cover a spouse's dental even during a job search, but it's worth double-checking before you assume either way.</p>
<p>Either way, I can't wait to <strong>(11)</strong>___!</p>
<p>Talk soon,<br>Priya</p>",
                'instructions' => 'Complete the response by filling in the blanks. Select the best choice for each blank.',
                'questions'    => [
                    ['q'=>7,  'text'=>'Blank (7)',  'options'=>['A'=>'send flowers for the new apartment.','B'=>'ask Devon for career advice.','C'=>'check in properly, not just by email.','D'=>'recommend a better moving company.']],
                    ['q'=>8,  'text'=>'Blank (8)',  'options'=>['A'=>'reviews your work daily.','B'=>'takes the time to show you the ropes.','C'=>'assigns you a mentor.','D'=>'checks in by email.']],
                    ['q'=>9,  'text'=>'Blank (9)',  'options'=>['A'=>'shower supplies.','B'=>'shower fixtures.','C'=>'cat food.','D'=>'moving boxes.']],
                    ['q'=>10, 'text'=>'Blank (10)', 'options'=>['A'=>'Congratulations','B'=>"I'm sorry",'C'=>"Don't stress",'D'=>'Good luck']],
                    ['q'=>11, 'text'=>'Blank (11)', 'options'=>["A"=>"hear how Tomas's interviews go.",'B'=>'help you look for a new apartment.','C'=>'meet your new coworkers.','D'=>'see the new place for myself.']],
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
                'type'          => 'diagram_cards',
                'passage_title' => 'Retreat Venue Options',
                'cards' => [
                    ['title' => 'Lakeside Lodge', 'bullets' => ['Private beach access', 'Canoe rentals included', 'Catered dinner each night', 'No Wi-Fi'], 'stats' => ['Price' => '$340/person', 'Duration' => '2 hr drive', 'Group capacity' => 'Up to 40']],
                    ['title' => 'Mountain View Ranch', 'bullets' => ['Guided hiking trails', 'Campfire storytelling', 'Horseback riding available', 'Limited cell service'], 'stats' => ['Price' => '$290/person', 'Duration' => '3 hr drive', 'Group capacity' => 'Up to 30']],
                    ['title' => 'City Loft', 'bullets' => ['Rooftop lounge', 'Catered lunch only', 'Full Wi-Fi/AV setup', 'Day-use only, no overnight rooms'], 'stats' => ['Price' => '$180/person', 'Duration' => '15 min drive', 'Group capacity' => 'Up to 60']],
                    ['title' => 'Riverside Camp', 'bullets' => ['Team-building ropes course', 'Shared cabins (4 people/cabin)', 'Campfire meals', 'No Wi-Fi'], 'stats' => ['Price' => '$250/person', 'Duration' => '3 hr 30 min drive', 'Group capacity' => 'Up to 25']],
                ],
            ],
            [
                'type'          => 'mcq',
                'inline_blanks' => true,
                'passage_title' => "Aisha's Email to Marcus",
                'passage'       => "<p><em>Subject: Retreat venue decision</em><br>To: Marcus Webb &lt;mwebb@brightpath.ca&gt;<br>From: Aisha Rahman &lt;arahman@brightpath.ca&gt;</p>
<p>Hi Marcus,</p>
<p>I've finally gone through the four retreat venues from the coordinator's file. Quick thoughts before we decide—and heads up, we're expecting around 28 people this year if all the new hires confirm.</p>
<p>City Loft is the only one within walking distance of the office, but everything else about it works against the point of a retreat — it's <strong>(1)</strong>___, so we'd basically just be at work with better snacks. Mountain View Ranch looks incredible, but I worry a full day on the trails might be too much for anyone who isn't especially active, and unlike the other outdoor option, there's <strong>(2)</strong>___ if someone needs to call for a ride partway through. I noticed Lakeside Lodge and Riverside Camp both skip Wi-Fi, which might make you assume they're similar in every other way too, but <strong>(3)</strong>___. Between those two, I actually still lean toward Lakeside now — beyond the price gap, both venues <strong>(4)</strong>___, so catering at least isn't something we'd need to arrange separately, but Lakeside's the only one of the two that could actually fit everyone. My one hesitation is that it's the <strong>(5)</strong>___ of the four, so I want to be sure the extra cost is worth it before I commit us to it.</p>
<p>Let's decide before Friday — I need to confirm numbers with the coordinator by then.</p>
<p>Best,<br>Aisha</p>",
                'instructions' => 'Complete the email by filling in the blanks. Select the best choice for each blank.',
                'questions'    => [
                    ['q'=>12, 'text'=>'Blank (1)', 'options'=>['A'=>'the most expensive option','B'=>'missing a kitchen','C'=>'not an overnight stay','D'=>'fully booked already']],
                    ['q'=>13, 'text'=>'Blank (2)', 'options'=>['A'=>'no shuttle back to the lodge','B'=>'no reliable phone signal','C'=>'no space in the group vehicle','D'=>'no refund for missed activities']],
                    ['q'=>14, 'text'=>'Blank (3)', 'options'=>['A'=>"they're both the cheapest options available",'B'=>'neither one publishes pricing upfront','C'=>'Riverside might actually be too small for our group this year','D'=>'Lakeside actually costs far more per person']],
                    ['q'=>15, 'text'=>'Blank (4)', 'options'=>['A'=>'provide a private beach for guests','B'=>'offer horseback riding','C'=>'include unlimited meeting-room time','D'=>'include meals as part of the stay']],
                    ['q'=>16, 'text'=>'Blank (5)', 'options'=>['A'=>'least affordable','B'=>'newest','C'=>'least popular','D'=>'hardest to book']],
                ],
            ],
            [
                'type'          => 'mcq',
                'passage_title' => null,
                'passage'       => null,
                'instructions'  => 'Choose the best option according to the information given in the message.',
                'questions'    => [
                    ['q'=>17, 'text'=>'Aisha and Marcus most likely…', 'options'=>['A'=>'have never met.','B'=>'are romantic partners.','C'=>'are friends outside of work.','D'=>'are colleagues.']],
                    ['q'=>18, 'text'=>"The main purpose of Aisha's email is…", 'options'=>['A'=>'to walk through her reasoning and arrive at a recommendation before a deadline.','B'=>'to cancel the retreat entirely.','C'=>'to argue that City Loft is secretly the best choice.','D'=>'to request a bigger budget for the retreat.']],
                    ['q'=>19, 'text'=>"Aisha's tone in the email is best described as…", 'options'=>['A'=>'undecided and anxious.','B'=>'methodical, working through options before landing on one.','C'=>'dismissive of the other options.','D'=>'frustrated with the coordinator.']],
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
                'passage_title' => 'Octopuses',
                'paragraphs'    => [
                    'A' => "Octopuses belong to a group of marine animals called cephalopods, a word that comes from the Greek for \"head-foot,\" referring to the way their arms attach directly to their heads. There are more than 300 known species of octopus, ranging from the inch-long star-sucker pygmy octopus to the giant Pacific octopus, which can span more than four metres across. Octopuses live in oceans worldwide, from shallow coral reefs to depths of several thousand metres, and every species is exclusively marine—none can survive in fresh water.",
                    'B' => "Unlike most animals with a backbone, an octopus has no internal or external skeleton at all, which allows it to squeeze through any opening larger than its beak, the only hard part of its body. Its skin is covered in thousands of pigment-filled cells called chromatophores, which the octopus can expand or contract within a fraction of a second to change colour, and specialized muscles beneath the skin let it alter its texture to mimic rock or coral. An octopus also has three hearts: two pump blood to the gills, while the third circulates it to the rest of the body.",
                    'C' => "Octopuses are solitary hunters that rely on both stealth and intelligence rather than speed. They typically hunt at night, using their sensitive arms—each lined with hundreds of suckers capable of both touch and taste—to probe crevices for crabs, clams, and small fish. Once prey is located, an octopus may inject it with a paralyzing venom before eating it. Researchers have also documented octopuses using coconut shells and empty bottles as portable shelters, carrying the objects beneath their bodies until they need to hide.",
                    'D' => "Scientists continue to be surprised by octopus behaviour. In laboratory settings, octopuses have learned to open jars, navigate mazes, and in a few documented cases, recognize individual human caretakers by sight. Some researchers believe this intelligence evolved separately from that of vertebrates, making the octopus one of the best examples of convergent evolution in the animal kingdom. Because most species live only one to two years, researchers are especially puzzled by how such complex learning develops in such a short lifespan.",
                ],
                'instructions' => 'Decide which paragraph, A to D, has the information given in each statement below. Select E if the information is not given in any of the paragraphs.',
                'options'      => ['A','B','C','D','E'],
                'option_labels'=> ['E' => 'Not given'],
                'questions'    => [
                    ['q'=>20, 'text'=>'Octopuses can change both the colour and texture of their skin.'],
                    ['q'=>21, 'text'=>'The star-sucker pygmy octopus can grow to more than four metres across.'],
                    ['q'=>22, 'text'=>"An octopus's only rigid body part is its beak."],
                    ['q'=>23, 'text'=>'Octopuses sometimes carry found objects to use as makeshift hiding spots.'],
                    ['q'=>24, 'text'=>'An octopus can squeeze through narrow gaps because of its flexible, muscular arms.'],
                    ['q'=>25, 'text'=>'Octopus intelligence may have developed independently from the intelligence found in animals with backbones.'],
                    ['q'=>26, 'text'=>'Octopuses use their arms to sense both texture and taste.'],
                    ['q'=>27, 'text'=>'Every species of octopus lives exclusively in salt water.'],
                    ['q'=>28, 'text'=>"An octopus's short lifespan makes some aspects of its behaviour puzzling to researchers."],
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
                'passage_title' => 'The Four-Day Work Week',
                'passage'       => "<p>Across Canada, a small but growing number of companies have begun experimenting with a four-day work week, compressing a standard forty-hour schedule into four longer days while keeping employee pay unchanged. Advocates say the model boosts productivity and staff retention. Critics argue it simply isn't realistic for most workplaces.</p>
<p>Jordan Kessler, owner of a twelve-person marketing firm in Kitchener, made the four-day schedule permanent company policy eighteen months ago: no employee may be scheduled past Thursday without a director's sign-off. \"I was honestly skeptical it would work,\" Kessler admitted. \"But our client deadlines are still being met, and turnover dropped from three resignations the year before the change to zero since we made it. People aren't wasting the last hour of Friday pretending to be busy anymore—they're just gone, and somehow everything still gets done.\" Kessler's office still keeps a framed photo of the old five-day schedule taped to the breakroom wall, a memento from the switch.</p>
<p>Not everyone is convinced the model can scale the way Kessler's has. Renata Price, an operations consultant who has advised dozens of small businesses, remains doubtful. \"What works for a twelve-person marketing agency won't necessarily work for a manufacturing plant running on shift schedules, or a hospital that needs coverage seven days a week,\" Price explained. \"I worry that businesses adopt this because it sounds progressive, without doing the harder work of figuring out whether their operations can actually absorb a twenty percent reduction in scheduled hours.\"</p>
<p>Dr. Amina Osei, a labour economist at a Canadian university, has studied several of the pilot programs currently underway. \"The data so far is more encouraging than skeptics might expect,\" Osei said. \"Across the pilots we've tracked, self-reported stress levels dropped substantially, and in most cases, output per employee actually increased—not because people were paid more or given new equipment, but because employees appear to work with more focus when they know their time is limited. That said, our sample sizes are still small, and we don't yet have enough long-term data to know whether these gains hold up after the novelty wears off.\"</p>
<p>Whatever the eventual verdict, more organizations appear willing to test the idea rather than dismiss it outright. Kessler, for one, doesn't find that surprising: \"Five years ago, nobody would have taken this seriously. Now I get calls almost every month from other owners asking how we did it.\"</p>",
                'instructions' => 'Choose the best option according to the information given on the website.',
                'questions'    => [
                    ['q'=>29, 'text'=>'The article is mainly about…', 'options'=>['A'=>'whether the four-day work week is a realistic option for more businesses.','B'=>'why manufacturing companies should avoid schedule changes.','C'=>"a labour economist's year-long research project.",'D'=>"how Jordan Kessler's results prove Dr. Osei's stress findings apply to every industry."]],
                    ['q'=>30, 'text'=>'Paragraph one provides…', 'options'=>['A'=>'a detailed case study.','B'=>'a historical account of workplace scheduling.','C'=>'a brief framing of the ongoing debate.','D'=>"a summary of Dr. Osei's findings."]],
                    ['q'=>31, 'text'=>"Renata Price's concerns would most likely be shared by…", 'options'=>['A'=>'a marketing firm owner with a small, flexible team.','B'=>'a hospital administrator responsible for round-the-clock staffing.','C'=>'an economist studying self-reported stress levels.','D'=>'an employee hoping for more scheduled time off.']],
                    ['q'=>32, 'text'=>'According to Dr. Osei, the rise in output per employee she observed is best explained by…', 'options'=>['A'=>'employees receiving higher pay for the same hours.','B'=>'employees being given new equipment and tools.','C'=>'staff becoming more efficient once their working hours are constrained.','D'=>'employees being monitored more closely by managers.']],
                    ['q'=>33, 'text'=>"The author's tone throughout the article is best described as…", 'options'=>['A'=>'firmly in favour of the four-day work week.','B'=>'dismissive of businesses that have tried it.','C'=>"mocking Renata Price's professional judgment.",'D'=>'cautiously balanced, presenting both promise and uncertainty.']],
                ],
            ],
            [
                'type'          => 'mcq',
                'inline_blanks' => true,
                'passage_title' => 'Visitor Comment',
                'passage'       => "<p>I run a fourteen-person accounting firm, and I think Ms. Price's shift-coverage concern is real for hospitals and factories, but she <strong>(6)</strong>___ when she extends that same logic to every kind of business.</p>
<p>My clients don't call at 2 a.m. the way a hospital patient might. I looked into how firms like mine have handled this elsewhere: several UK accounting practices moved to four-day weeks starting back in 2022, and by most accounts they <strong>(7)</strong>___ despite carrying compliance deadlines just as tight as ours. If firms juggling audit season can make it work, I don't think <strong>(8)</strong>___ is really the obstacle Ms. Price suggests it is.</p>
<p><strong>(9)</strong>___, Dr. Osei's caution about long-term data is worth taking seriously — eighteen months, like Mr. Kessler's case, still isn't very long. <strong>(10)</strong>___ before I'd recommend this to every accounting firm I know, I'd want to see how it holds up through a few more busy seasons first.</p>",
                'instructions' => 'Complete the comment by choosing the best option to fill in each blank.',
                'questions'    => [
                    ['q'=>34, 'text'=>'Blank (6)', 'options'=>['A'=>'overreaches','B'=>'is right','C'=>'hesitates','D'=>'apologizes']],
                    ['q'=>35, 'text'=>'Blank (7)', 'options'=>['A'=>'struggled to keep up','B'=>'reduced their client base','C'=>'kept pace with their workload','D'=>'hired additional staff']],
                    ['q'=>36, 'text'=>'Blank (8)', 'options'=>['A'=>'staff shortages','B'=>'client complaints','C'=>'government regulation','D'=>'deadline pressure']],
                    ['q'=>37, 'text'=>'Blank (9)', 'options'=>['A'=>'Similarly,','B'=>'Because of this,','C'=>'That said,','D'=>'In other words,']],
                    ['q'=>38, 'text'=>'Blank (10)', 'options'=>['A'=>'Still,','B'=>'Therefore,','C'=>'Meanwhile,','D'=>'In other words,']],
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


<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php include __DIR__ . '/celpip_reading_script.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
