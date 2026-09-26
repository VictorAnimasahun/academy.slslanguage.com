<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}
require_once INCLUDES_PATH . '/course_lock.php';
// IELTS General Training Reading Practice Test 4 = Cambridge IELTS 15 General Training, Test 3, Reading: three sections, 40 questions, 60 minutes.
// Source: documentation/test_bank/cambridge_ielts15_gt/test3.json. Generated from the bank so the page and the answer key (migration 137) agree.
// Open to students enrolled in the IELTS General courses (staff, admins and testers always pass); course ids are looked up by folder, never typed.
require_course_enrollment(course_ids_for_folders(['IELTS_Gen_Mst', 'IELTS_Gen_1Mo', 'IELTS_Gen_2Mo']), 'this IELTS Reading practice test');

$testCode  = 'IELTS_PT_R_004';
$timeLimit = 60 * 60;

$parts = [
    1 => [
        'title' => 'Section 1',
        'description' => 'Read the text(s) below and answer Questions 1–14.',
        'q_range' => [1, 14],
        'type' => 'mixed',
        'sections' => [
            [
                'type' => 'true_false_ng',
                'passage_title' => null,
                'passage_subtitle' => null,
                'passage' => '<h5 class="fw-bold mt-3">Young Fashion Designer UK competition</h5>
<p>Young Fashion Designer UK is an exciting national competition which aims to showcase and promote the exceptional work achieved by students studying courses in textile design, product design and fashion throughout the UK.</p>
<p>The competition is designed for students to enter the coursework they are currently working on rather than specifically producing different pieces of work. If you would like to add to your coursework, that is for you and your teacher to decide.</p>
<p>You can apply independently or through your school/college. To enter please ensure you follow these steps: 1) Provide three A3 colour copies from your design folder. You must include: initial ideas about the clothing; a close-up photograph of the front and back view of the finished clothing. 2) Please label each sheet clearly with your name and school (on the back). 3) Print off a copy of your registration form and attach it to your work. 4) Post your entry to the Young Fashion Designer Centre.</p>
<p>Once the entry deadline has passed, the judges will select the shortlist of students who will be invited to the Finals. You will be notified if you are shortlisted. You will need to bring originals of the work that you entered. Each finalist will have their own stand consisting of a table and tabletop cardboard display panels. Feel free to add as much creativity to your stand as possible. Some students bring tablets/laptops with slideshows or further images of work but it should be emphasised that these may not necessarily improve your chances of success.</p>
<p>The judges will assess your work and will ask various questions about it. They will look through any supporting information and the work you have on display before coming together as a judging panel to decide on the winners. You are welcome to ask the judges questions. In fact, you should make the most of having experts on hand!</p>
<p>There are 1st, 2nd and 3rd prize winners for each category. The judges can also decide to award special prizes if the work merits this. The 1st, 2nd and 3rd place winners will receive a glass trophy and prize from a kind donor.</p>
<h5 class="fw-bold mt-3">Which keyboard should you buy?</h5>
<p><strong>A</strong> &nbsp; Logitech K120 - Logitech\'s K120 offers a number of extra features. It\'s spill-resistant, draining small amounts of liquid if you have an accident. It isn\'t particularly eye-catching, but it feels very solid. For the price, it\'s a tempting choice.</p>
<p><strong>B</strong> &nbsp; Cherry MX 3.0 Keyboard - The Cherry MX 3.0 looks simple and neat, thanks to its compact build. It\'s solid, durable and you don\'t need to push keys all the way down to activate them. It\'s also rather loud though, which can take some getting used to.</p>
<p><strong>C</strong> &nbsp; Logitech K780 - The K780 is a compact, pleasantly modern-looking keyboard. There\'s an integrated stand for smartphones and tablets too. It\'s quiet to type on, and the circular keys are easy to familiarise yourself with, well-spaced and large enough to hit accurately. For this price though, the lack of backlighting is disappointing.</p>
<p><strong>D</strong> &nbsp; Microsoft Sculpt Ergonomic - The Sculpt\'s curved, strange-looking build serves a purpose. It provides wrist support and lifts your forearms into a relaxed position so you don\'t hurt yourself from typing for lengthy periods. It feels weird, but it seems to do the trick.</p>
<p><strong>E</strong> &nbsp; Microsoft Universal Bluetooth Keyboard - Microsoft\'s Bluetooth keyboard has one very handy feature - you can fold it in half and carry it around in your jacket pocket or bag, and it feels rather like a large wallet. It has generously sized keys, though the two-piece spacebar takes some getting used to. Another useful feature is that you can get up to three months\' use from a single charge.</p>
<p><strong>F</strong> &nbsp; Corsair Strafe RGB Keyboard - Corsair\'s keyboard is expensive, flashy and extremely impressive. All of its keys are programmable, there\'s eye-catching backlighting and the buttons are textured for improved grip. All this is because it\'s designed for gamers. However, it\'s also silent, meaning it is suitable for everyday office work too.</p>',
                'instructions' => '<strong>Questions 1–7.</strong> Do the following statements agree with the information given in the text? Write <strong>TRUE</strong> if the statement agrees with the information, <strong>FALSE</strong> if the statement contradicts the information, <strong>NOT GIVEN</strong> if there is no information on this.',
                'questions' => [
                    [
                        'q' => 1,
                        'text' => 'Participants are required to create a new item of clothing for the Young Fashion Designer UK competition.',
                    ],
                    [
                        'q' => 2,
                        'text' => 'Participants must send information about the thoughts that led to the item they are entering for the competition.',
                    ],
                    [
                        'q' => 3,
                        'text' => 'The shortlist will consist of a fixed number of finalists.',
                    ],
                    [
                        'q' => 4,
                        'text' => 'Finalists can choose how to present their work to the judges on their stand.',
                    ],
                    [
                        'q' => 5,
                        'text' => 'It is strongly recommended that finalists support their entry with additional photographs.',
                    ],
                    [
                        'q' => 6,
                        'text' => 'Questions that the students ask the judges may count towards the final decisions.',
                    ],
                    [
                        'q' => 7,
                        'text' => 'Extra prizes may be awarded depending on the standard of the entries submitted.',
                    ],
                ],
            ],
            [
                'type' => 'section_matching',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 8–14.</strong> Look at the six reviews of computer keyboards, A-F. Write the correct letter A-F (any letter may be used more than once).',
                'options' => ['A', 'B', 'C', 'D', 'E', 'F'],
                'questions' => [
                    [
                        'q' => 8,
                        'text' => 'This keyboard may not suit users who prefer the keys to be almost silent.',
                    ],
                    [
                        'q' => 9,
                        'text' => 'This keyboard is easily portable because it can be made to fit into a small space.',
                    ],
                    [
                        'q' => 10,
                        'text' => 'This keyboard includes a special place to put small devices.',
                    ],
                    [
                        'q' => 11,
                        'text' => 'This keyboard is designed to prevent injury to those who spend a lot of time on the computer.',
                    ],
                    [
                        'q' => 12,
                        'text' => 'This keyboard offers good value for money.',
                    ],
                    [
                        'q' => 13,
                        'text' => 'This keyboard is primarily aimed at people who use their computer for entertainment.',
                    ],
                    [
                        'q' => 14,
                        'text' => 'It shouldn\'t take long for users to get used to the shape of the keys on this keyboard.',
                    ],
                ],
            ],
        ],
    ],
    2 => [
        'title' => 'Section 2',
        'description' => 'Read the text(s) below and answer Questions 15–27.',
        'q_range' => [15, 27],
        'type' => 'mixed',
        'sections' => [
            [
                'type' => 'form_fill',
                'passage_title' => null,
                'passage_subtitle' => null,
                'passage' => '<h5 class="fw-bold mt-3">Working for a small company may be better than you think</h5>
<p>Recent research shows that many job-seekers believe their ideal position would be in a large company. However, working for a small or medium-sized business has many advantages that are too easily overlooked. Here are just a few of them.</p>
<p>Working in a small organisation with a small workforce means it\'s likely to be easy to become part of it. It won\'t be long before you\'re familiar with the staff and the departments that you need to deal with. This can provide a feeling of comfort that takes much longer to develop in a large company. Departments are likely to be small and have close connections with each other, which helps to make internal communication work well - everyone knows what\'s going on. You\'ll also gain a better understanding of how your own role fits into the company as a whole.</p>
<p>In a small business you\'re likely to have considerable variety in your workload, including opportunities to work in different areas of the company, which will allow you to identify abilities that you didn\'t know you had. An introduction to new activities could even lead to a change of career. This variety in your work will help to make it stimulating, so you have a good reason for getting out of bed in the morning.</p>
<p>There will be plenty of opportunities to show initiative, and you\'ll also learn to function well as part of a team. Because it\'s much harder to overlook someone within a small workforce than a large one, your efforts are more likely to attract the attention of those higher up. You\'ll have plenty of opportunity to show what you can do, and to have your potential noticed. The result is very likely to be that promotion comes to you faster.</p>
<p>Small businesses are usually flexible, something that is rarely true of large organisations. This means that if they\'re well managed, they can adapt to make the most of changes in the wider economy, which in turn can help you. Don\'t dismiss them as a place to work because of the myths about them. Small firms can be ideal places for developing your career.</p>
<h5 class="fw-bold mt-3">Starting a new job</h5>
<p><strong>A</strong> &nbsp; Make sure you know when and where you are expected to report on your first day. If the route from home is unfamiliar to you, make a practice run first: the normal first activity in a new job is a meeting with your boss, and it would be embarrassing to be late. Dress formally until you\'re sure of the dress code.</p>
<p><strong>B</strong> &nbsp; You should expect to have an induction programme planned for you: a security pass; visits to whatever parts of the organisation you need to understand to do your job properly; meetings with anyone who could affect your success in the role; and someone to show you where everything is and tell you all the real rules of the culture - the ones that are never written down but which everyone is meant to follow.</p>
<p><strong>C</strong> &nbsp; It can be a shock to join a new organisation. When you are a newcomer, feeling uncertain and perhaps a little confused, there can be a strong temptation to talk about your old job and organisation as a way of reminding yourself and telling others that you really know what you are doing, because you did it in your previous role. Unfortunately, this will suggest that you have a high opinion of yourself, and that you think your old place was better. It has enormous power to annoy, so don\'t do it.</p>
<p><strong>D</strong> &nbsp; All employers have a core product or service paid for by customers which justifies their existence. If you are not part of this core activity, remember that your role is to provide a service to the people who are part of it. Understanding their concerns and passions is essential for understanding why your own role exists, and for knowing how to work alongside these colleagues. This is why you must see this product or service in action.</p>
<p><strong>E</strong> &nbsp; When I worked for a television company, all of us, whatever our job, were strongly encouraged to visit a studio and see how programmes were made. This was wise. Make sure you do the equivalent for whatever is the core activity of your new employer.</p>
<p><strong>F</strong> &nbsp; Don\'t try to do the job too soon. This may seem strange because, after all, you have been appointed to get on and do the job. But in your first few weeks your task is to learn what the job really is, rather than immediately starting to do what you assume it is.</p>
<p><strong>G</strong> &nbsp; Starting a new job is one of life\'s major transitions. Treat it with the attention it deserves and you will find that all your work in preparing and then going through the selection process has paid off magnificently.</p>',
                'instructions' => '<strong>Questions 15–20.</strong> Complete the sentences below, choose ONE WORD ONLY from the text.',
                'form_title' => null,
                'groups' => [
                    [
                        'heading' => null,
                        'rows' => [
                            [
                                'prefix' => 'In a small business it is easy to become',
                                'q' => 15,
                                'suffix' => 'with colleagues and other departments.',
                            ],
                            [
                                'prefix' => 'You may find you have',
                                'q' => 16,
                                'suffix' => 'you were not aware of.',
                            ],
                            [
                                'prefix' => 'Finding that your work is',
                                'q' => 17,
                                'suffix' => 'will make you enjoy doing it.',
                            ],
                            [
                                'prefix' => 'Other people are likely to realise that you have',
                                'q' => 18,
                                'suffix' => '.',
                            ],
                            [
                                'prefix' => 'Opportunities for',
                                'q' => 19,
                                'suffix' => 'will come sooner than in a larger business.',
                            ],
                            [
                                'prefix' => 'You can benefit from a small company being more',
                                'q' => 20,
                                'suffix' => 'than a large one.',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'type' => 'section_matching',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 21–27.</strong> Which paragraph mentions the following? Write the correct letter, A-G.',
                'options' => ['A', 'B', 'C', 'D', 'E', 'F', 'G'],
                'questions' => [
                    [
                        'q' => 21,
                        'text' => 'the emotions that new employees are likely to experience at first',
                    ],
                    [
                        'q' => 22,
                        'text' => 'a warning to be patient at first',
                    ],
                    [
                        'q' => 23,
                        'text' => 'how colleagues might react to certain behaviour',
                    ],
                    [
                        'q' => 24,
                        'text' => 'travelling to your new workplace before you start working there',
                    ],
                    [
                        'q' => 25,
                        'text' => 'an example of observing an activity carried out within an organisation',
                    ],
                    [
                        'q' => 26,
                        'text' => 'some things that the organisation should arrange for when you begin',
                    ],
                    [
                        'q' => 27,
                        'text' => 'a division of jobs within an organisation into two categories',
                    ],
                ],
            ],
        ],
    ],
    3 => [
        'title' => 'Section 3',
        'description' => 'Read the text(s) below and answer Questions 28–40.',
        'q_range' => [28, 40],
        'type' => 'mixed',
        'sections' => [
            [
                'type' => 'passage_mcq',
                'passage_title' => null,
                'passage_subtitle' => null,
                'passage' => '<h5 class="fw-bold mt-3">How animals keep fit</h5>
<p>No one would dream of running a marathon without first making a serious effort to train for it. But no matter how well they have stuck to their training regime, contestants will find that running non-stop for 42 kilometres is going to hurt.</p>
<p>Now consider the barnacle goose. Every year this bird carries out a 3000-kilometre migration. So how do the birds prepare for this? Do they spend months gradually building up fitness? That\'s not really the barnacle goose\'s style. Instead, says environmental physiologist Lewis Halsey, \'They just basically sit on the water and eat a lot.\'</p>
<p>Until recently, nobody had really asked whether exercise is as tightly connected to fitness in the rest of the animal kingdom as it is for us. The question is tied up in a broader assumption: that animals maintain fitness because of the exercise they get finding food and escaping predators.</p>
<p>Halsey points out that this may not necessarily be the case. Take the house cat. Most domestic cats spend much of the day lounging around, apparently doing nothing, rather than hunting for food. But over short distances, even the laziest can move incredibly fast when they want to. Similarly, black and brown bears manage to come out of several months\' hibernation with their muscle mass intact - without having to lift so much as a paw during this time.</p>
<p>Barnacle geese go one better. In the process of sitting around, they don\'t just maintain their fitness. They also develop stronger hearts and bigger flight muscles, enabling them to fly for thousands of kilometres in a migration that may last as little as two days.</p>
<p>So, if exercise isn\'t necessarily the key to physical strength, then what is? One clue comes from a broader view of the meaning of physical fitness. Biologically speaking, all it means is that the body has undergone changes that make it stronger and more efficient. In animals such as bears these changes appear to be triggered by cues such as falling temperatures or insufficient food. In the months of hibernation, these factors seem to prompt the release of muscle-protecting compounds which are then carried to the bears\' muscles in their blood and prevent muscle loss.</p>
<p>Barnacle geese, Halsey suggests, may be responding to an environmental change such as temperature, which helps their bodies somehow \'know\' that a big physical challenge is looming. In other bird species, that cue may be something different. Chris Guglielmo, a physiological ecologist, has studied the effect of subjecting migratory songbirds known as yellow-rumped warblers to changing hours of daylight. \'We don\'t need to take little songbirds and train them up to do a 6- or 10-hour flight,\' he says. If they are subjected to the right daylight cycle, \'we can take them out of the cage and put them in the wind tunnel, and they fly for 10 hours.\'</p>
<p>Unlike migratory birds, however, humans have no biological shortcut to getting fit. Instead, pressures in our evolutionary history made our bodies tie fitness to exercise.</p>
<p>Our ancestors\' lives were unpredictable. They had to do a lot of running to catch food and escape danger, but they also needed to keep muscle mass to a minimum because muscle is biologically expensive. Each kilogram contributes about 10 to 15 kilocalories a day to our metabolism when resting - which doesn\'t sound like much until you realise that muscles account for about 40 percent of the average person\'s body mass. \'Most of us are spending 20 percent of our basic energy budget taking care of muscle mass,\' says Daniel Lieberman, an evolutionary biologist and marathon runner.</p>
<p>So our physiology evolved to let our weight and fitness fluctuate depending on how much food was available. \'This makes us evolutionarily different from most other animals,\' says Lieberman. In general, animals merely need to be capable of short bouts of intense activity, whether it\'s the cheetah chasing prey or the gazelle escaping. Cats are fast, but they don\'t need to run very far. Perhaps a few mad dashes around the house are all it takes to keep a domestic one fit enough for feline purposes. \'Humans, on the other hand, needed to adapt to run slower, but for longer,\' says Lieberman.</p>
<p>He argues that long ago on the African savannah, natural selection made us into \'supremely adapted\' endurance athletes, capable of running prey into the ground and ranging over long distances with unusual efficiency. But only, it appears, if we train. Otherwise we quickly degenerate into couch potatoes.</p>
<p>As for speed, even those animals that do cover impressive distances don\'t have to be the fastest they can possibly be. Barnacle geese needn\'t set world records when crossing the North Atlantic; they just need to be able to get to their destination. \'And,\' says exercise physiologist Ross Tucker, \'humans may be the only animal that actually cares about reaching peak performance.\' Other than racehorses and greyhounds, both of which we have bred to race, animals aren\'t directly competing against one another. \'I don\'t know that all animals are the same, performance-wise ... and we don\'t know whether training would enhance their ability,\' he says.</p>
<p>Researchers: A Lewis Halsey, B Chris Guglielmo, C Daniel Lieberman, D Ross Tucker</p>',
                'instructions' => '<strong>Questions 28–30.</strong> Choose the correct letter, <strong>A, B, C</strong> or <strong>D</strong>.',
                'questions' => [
                    [
                        'q' => 28,
                        'text' => 'The writer discusses marathon runners and barnacle geese to introduce the idea that',
                        'options' => [
                            'A' => 'marathon runners may be using inefficient training methods.',
                            'B' => 'the role of diet in achieving fitness has been underestimated.',
                            'C' => 'barnacle geese spend much longer preparing to face a challenge.',
                            'D' => 'serious training is not always necessary for physical achievement.',
                        ],
                    ],
                    [
                        'q' => 29,
                        'text' => 'The writer says that human muscles',
                        'options' => [
                            'A' => 'use up a lot of energy even when resting.',
                            'B' => 'are heavier than other types of body tissue.',
                            'C' => 'were more efficiently used by our ancestors.',
                            'D' => 'have become weaker than they were in the past.',
                        ],
                    ],
                    [
                        'q' => 30,
                        'text' => 'The writer says that in order to survive, early humans developed the ability to',
                        'options' => [
                            'A' => 'hide from their prey.',
                            'B' => 'run long distances.',
                            'C' => 'adapt their speeds to different situations.',
                            'D' => 'predict different types of animal movements.',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'form_fill',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 31–35.</strong> Complete the summary, choose ONE WORD ONLY from the text.',
                'form_title' => null,
                'groups' => [
                    [
                        'heading' => null,
                        'rows' => [
                            [
                                'prefix' => 'In biological terms, when an animal is physically fit, its body changes, becoming more powerful and',
                                'q' => 31,
                                'suffix' => '.',
                            ],
                            [
                                'prefix' => 'For bears, this change may be initially caused by colder weather or a lack of',
                                'q' => 32,
                                'suffix' => '',
                            ],
                            [
                                'prefix' => 'which during',
                                'q' => 33,
                                'suffix' => 'causes certain compounds to be released into their',
                            ],
                            [
                                'prefix' => '',
                                'q' => 34,
                                'suffix' => 'and to travel around the body.',
                            ],
                            [
                                'prefix' => 'In the case of barnacle geese, the change may be due to a variation in',
                                'q' => 35,
                                'suffix' => '.',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'type' => 'section_matching',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 36–40.</strong> Match each statement with the correct researcher, A-D (any letter may be used more than once).',
                'options' => ['A', 'B', 'C', 'D'],
                'questions' => [
                    [
                        'q' => 36,
                        'text' => 'One belief about how animals stay fit is possibly untrue.',
                    ],
                    [
                        'q' => 37,
                        'text' => 'It may not be possible to train all animals to improve their speed.',
                    ],
                    [
                        'q' => 38,
                        'text' => 'One type of bird has demonstrated fitness when exposed to a stimulus in experimental conditions.',
                    ],
                    [
                        'q' => 39,
                        'text' => 'Human energy use developed in a different way from that of animals.',
                    ],
                    [
                        'q' => 40,
                        'text' => 'One type of bird may develop more strength when the weather becomes warmer or cooler.',
                    ],
                ],
            ],
        ],
    ],
];

require_once __DIR__ . '/functions.php';
/** @var \PDO $db */
$answers = loadTestAnswers($db, $testCode);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS General Training Reading Practice Test 4 – EduHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .main-wrapper { padding: 1.5rem; min-height: 100vh; }

        /* ── Section tabs ────────────────────────────────────────── */
        .sec-tabs {
            display: flex;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 1.5rem;
        }
        .sec-tab {
            border: none;
            background: transparent;
            padding: .55rem 1.2rem;
            font-weight: 600;
            color: #6b7280;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            font-size: .88rem;
            transition: color .2s;
        }
        .sec-tab.active { color: #0d6efd; border-bottom-color: #0d6efd; }

        /* ── Section panels ──────────────────────────────────────── */
        .sec-panel { display: none; }
        .sec-panel.active { display: block; }

        .content-col {
            padding: 0 0 3rem;
        }

        /* ── Passage elements ────────────────────────────────────── */
        .passage-box {
            background: #f8fafc;
            border-radius: 10px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1rem;
            font-size: .92rem;
            line-height: 1.8;
        }
        .passage-box h4 { font-size: 1rem; font-weight: 700; margin-bottom: .4rem; }
        .sub-divider { border-top: 2px dashed #dee2e6; margin: 1.5rem 0; }
        .passage-items-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .75rem;
            margin-top: .75rem;
        }
        .passage-item {
            background: #eef2ff;
            border-radius: 6px;
            padding: .75rem;
            font-size: .83rem;
            line-height: 1.6;
        }
        .passage-item strong { color: #4338ca; }

        /* ── Question elements ───────────────────────────────────── */
        .q-num { font-weight: 700; color: #0d6efd; min-width: 2rem; display: inline-block; }
        .question-row {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: .75rem;
            flex-wrap: wrap;
        }
        .q-input {
            border: 2px solid #dee2e6;
            border-radius: 6px;
            padding: .28rem .55rem;
            min-width: 130px;
            font-size: .88rem;
            transition: border-color .2s;
        }
        .q-input:focus { border-color: #0d6efd; outline: none; }
        .q-input.correct   { border-color: #198754; background: #d1e7dd; }
        .q-input.incorrect { border-color: #dc3545; background: #f8d7da; }
        .tfng-select, .match-select {
            border: 2px solid #dee2e6;
            border-radius: 6px;
            padding: .28rem .55rem;
            font-size: .88rem;
            background: #fff;
        }
        .notes-group-heading {
            font-weight: 700;
            background: #e9ecef;
            padding: .35rem .75rem;
            border-radius: 4px;
            margin: .75rem 0 .4rem;
            font-size: .85rem;
        }
        .mcq-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: .9rem;
            margin-bottom: .9rem;
        }
        .mcq-option {
            display: flex;
            align-items: flex-start;
            gap: .5rem;
            margin-bottom: .35rem;
            cursor: pointer;
            font-size: .9rem;
        }
        .mcq-option input[type=radio] { margin-top: 3px; flex-shrink: 0; }
        .feedback-correct   { color: #198754; font-size: .78rem; font-weight: 600; }
        .feedback-incorrect { color: #dc3545; font-size: .78rem; font-weight: 600; }

        /* ── Misc ────────────────────────────────────────────────── */
        .section-badge {
            background: linear-gradient(135deg,#0b77ff,#6f8cff);
            color: white;
            padding: .3rem 1.1rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: .82rem;
        }
        .timer-display {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0d6efd;
            font-family: monospace;
        }
        .timer-display.warning { color: #dc3545; animation: blink 1s infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.5} }
        .result-badge {
            display: inline-block;
            color: #fff;
            border-radius: 8px;
            padding: .4rem 1rem;
            font-size: .95rem;
            font-weight: 700;
            margin: .25rem;
        }

        @media (max-width: 767px) {
            .content-col { padding: 1rem 1rem 2rem; }
        }
    </style>
</head>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="mobile-overlay" id="mobileOverlay"></div>
<?php include INCLUDES_PATH . '/navbar.php'; ?>

<div class="main-wrapper flex-grow-1" style="flex:1;">
    <?php include INCLUDES_PATH . '/topbar.php'; ?>

<main class="content p-4">

    <!-- Breadcrumb + badge + timer + submit -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <nav aria-label="breadcrumb" class="mb-0">
            <ol class="breadcrumb mb-0" style="font-size:.8rem;">
                <li class="breadcrumb-item"><a href="../resources_home.php">Resources</a></li>
                <li class="breadcrumb-item"><a href="index.php">Practice Tests</a></li>
                <li class="breadcrumb-item active">IELTS General Training Reading – Practice 4</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center gap-3">
            <span class="section-badge">Reading · General Training</span>
            <span class="text-muted small">40 Questions · 60 min</span>
            <span id="timerDisplay" class="timer-display">60:00</span>
            <button class="btn btn-primary btn-sm px-3" id="submitBtn" onclick="handleSubmit()">
                <i class="bi bi-check2-circle me-1"></i>Submit
            </button>
        </div>
    </div>

    <!-- ── Section Tabs ── -->
    <div class="sec-tabs">
        <?php foreach ($parts as $pNum => $part): ?>
        <button class="sec-tab <?= $pNum === 1 ? 'active' : '' ?>"
                onclick="switchSec(<?= $pNum ?>)" id="stab-<?= $pNum ?>">
            <?= htmlspecialchars($part['title']) ?>
            <span class="text-muted ms-1" style="font-size:.72rem;">
                Q<?= $part['q_range'][0] ?>–<?= $part['q_range'][1] ?>
            </span>
        </button>
        <?php endforeach; ?>
    </div>

    <!-- ── Section Panels ── -->
    <form id="testForm" onsubmit="return false;">
    <?php foreach ($parts as $pNum => $part): ?>
    <div class="sec-panel <?= $pNum === 1 ? 'active' : '' ?>" id="spanel-<?= $pNum ?>">
        <div class="content-col">

            <p class="text-muted small mb-4"><?= htmlspecialchars($part['description']) ?></p>

            <?php foreach ($part['sections'] as $si => $sec): ?>
                <?php if ($si > 0): ?><div class="sub-divider"></div><?php endif; ?>
                <?php renderSection($sec, 'passage'); ?>
                <?php renderSection($sec, 'questions'); ?>
            <?php endforeach; ?>

            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                <?php if ($pNum < count($parts)): ?>
                <button type="button" class="btn btn-outline-primary btn-sm"
                        onclick="switchSec(<?= $pNum + 1 ?>)">
                    <?= htmlspecialchars($parts[$pNum + 1]['title']) ?>
                    <i class="bi bi-arrow-right ms-1"></i>
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


<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const CORRECT   = <?= json_encode($answers) ?>;
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
    document.querySelectorAll('input[type=text][data-q]').forEach(el => {
        userAnswers[el.dataset.q] = el.value.trim().toLowerCase();
    });
    document.querySelectorAll('select[data-q]').forEach(el => {
        userAnswers[el.dataset.q] = el.value.trim().toLowerCase();
    });
    document.querySelectorAll('input[type=radio]:checked[data-q]').forEach(el => {
        userAnswers[el.dataset.q] = el.value.trim().toLowerCase();
    });
}

function gradeAnswers() {
    let score = 0;
    for (let q in CORRECT) {
        const given = (userAnswers[q] || '').toLowerCase().trim();
        if (CORRECT[q].includes(given)) score++;
    }
    return score;
}

// Estimated band from the raw score out of 40 (the usual IELTS General Training Reading conversion). An estimate, not an official result.
function toBand(score) {
    if (score >= 40) return '9.0';
    if (score >= 39) return '8.5';
    if (score >= 37) return '8.0';
    if (score >= 36) return '7.5';
    if (score >= 34) return '7.0';
    if (score >= 32) return '6.5';
    if (score >= 30) return '6.0';
    if (score >= 27) return '5.5';
    if (score >= 23) return '5.0';
    if (score >= 19) return '4.5';
    if (score >= 15) return '4.0';
    return '<4.0';
}

function showFeedback() {
    document.querySelectorAll('input[type=text][data-q]').forEach(el => {
        const q      = el.dataset.q;
        const given  = el.value.trim().toLowerCase();
        const correct = CORRECT[q] || [];
        el.classList.remove('correct', 'incorrect');
        el.classList.add(correct.includes(given) ? 'correct' : 'incorrect');
        let fb = el.nextElementSibling;
        if (!fb || !fb.classList.contains('feedback-text')) {
            fb = document.createElement('span');
            fb.className = 'feedback-text ms-1';
            el.after(fb);
        }
        fb.className  = correct.includes(given) ? 'feedback-correct ms-1' : 'feedback-incorrect ms-1';
        fb.textContent = correct.includes(given) ? '✓' : `✗ ${correct[0]}`;
    });
    document.querySelectorAll('select[data-q]').forEach(el => {
        const q      = el.dataset.q;
        const given  = el.value.trim().toLowerCase();
        const correct = CORRECT[q] || [];
        el.style.borderColor = correct.includes(given) ? '#198754' : '#dc3545';
        el.style.background  = correct.includes(given) ? '#d1e7dd' : '#f8d7da';
        let fb = el.nextElementSibling;
        if (!fb || !fb.classList.contains('feedback-text')) {
            fb = document.createElement('span');
            fb.className = 'feedback-text ms-1';
            el.after(fb);
        }
        fb.className  = correct.includes(given) ? 'feedback-correct ms-1' : 'feedback-incorrect ms-1';
        fb.textContent = correct.includes(given) ? '✓' : `✗ ${correct[0].toUpperCase()}`;
    });
    document.querySelectorAll('.mcq-card').forEach(card => {
        const q      = card.dataset.q;
        const given  = (userAnswers[q] || '').toLowerCase();
        const correct = (CORRECT[q] || [])[0] || '';
        card.querySelectorAll('.mcq-option').forEach(opt => {
            const val = opt.querySelector('input').value.toLowerCase();
            opt.style.background = '';
            if (val === correct)              opt.style.background = '#d1e7dd';
            else if (val === given)           opt.style.background = '#f8d7da';
        });
    });
}

function saveAttempt(score, band, timeSpent) {
    fetch('save_attempt.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            test_code:  TEST_CODE,
            score,
            max_score:  40,
            band_score: band,
            time_spent: timeSpent,
            answers:    userAnswers,
        }),
    }).catch(err => console.error('save_attempt:', err));
}

async function handleSubmit(auto = false) {
    if (submitted) return;
    if (!auto) {
        const r = await Swal.fire({
            title: 'Submit Test?',
            text:  'You cannot change your answers after submitting.',
            icon:  'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit',
            cancelButtonText:  'Continue working',
            confirmButtonColor: '#0d6efd',
        });
        if (!r.isConfirmed) return;
    }
    submitted = true;
    clearInterval(timerInterval);
    document.getElementById('submitBtn').disabled = true;
    collectAnswers();
    const score     = gradeAnswers();
    const band      = toBand(score);
    const timeSpent = Math.round((Date.now() - startTime) / 1000);
    showFeedback();
    saveAttempt(score, band, timeSpent);
    document.querySelectorAll('input, select').forEach(el => el.disabled = true);
    Swal.fire({
        title: 'Test Complete!',
        html:  `<div class="text-center">
                    <div class="result-badge" style="background:#0d6efd;">Score: ${score} / 40</div>
                    <div class="result-badge" style="background:#198754;">Band: ${band}</div>
                    <p class="mt-3 text-muted small">Correct answers are highlighted below.</p>
                </div>`,
        icon:  'success',
        confirmButtonText:  'View Feedback',
        confirmButtonColor: '#0d6efd',
    }).then(() => {
        // Switch to section 1 so feedback is visible
        switchSec(1);
    });
}
</script>

<?php
// ── Render functions ──────────────────────────────────────────────────────────

function renderSection(array $section, string $mode): void {
    switch ($section['type']) {
        case 'true_false_ng':    renderTFNG($section, $mode);            break;
        case 'matching_passage': renderMatchingPassage($section, $mode); break;
        case 'form_fill':        renderFormFill($section, $mode);        break;
        case 'passage_mcq':      renderPassageMCQ($section, $mode);      break;
        case 'section_matching': renderSectionMatching($section, $mode); break;
        case 'table':             renderTable($section, $mode);          break;
    }
}

function renderTFNG(array $s, string $mode): void {
    if ($mode === 'passage'): if (empty($s['passage'])) return; ?>
        <div class="passage-box">
            <h4><?= htmlspecialchars($s['passage_title']) ?></h4>
            <?php if (!empty($s['passage_subtitle'])): ?>
            <p class="fst-italic text-muted small mb-2"><?= htmlspecialchars($s['passage_subtitle']) ?></p>
            <?php endif; ?>
            <?= $s['passage'] ?>
        </div>
    <?php elseif ($mode === 'questions'):
        // 'yn' is used for "identifying writer's views/claims" tasks (an
        // opinion/claim, judged YES/NO/NOT GIVEN) as distinct from the
        // default true_false_ng, used for factual statements (TRUE/FALSE/
        // NOT GIVEN) — same 3-way judgment UI, different wording per the
        // real exam's own convention for these two question types.
        $isYN = ($s['labels'] ?? 'tf') === 'yn';
        ?>
        <p class="fw-semibold small"><?= $s['instructions'] ?></p>
        <?php foreach ($s['questions'] as $row): ?>
        <div class="question-row">
            <span class="q-num"><?= $row['q'] ?>.</span>
            <span class="flex-grow-1 small"><?= htmlspecialchars($row['text']) ?></span>
            <select class="tfng-select" data-q="<?= $row['q'] ?>">
                <option value="">– Select –</option>
                <?php if ($isYN): ?>
                <option value="yes">YES</option>
                <option value="no">NO</option>
                <?php else: ?>
                <option value="true">TRUE</option>
                <option value="false">FALSE</option>
                <?php endif; ?>
                <option value="not given">NOT GIVEN</option>
            </select>
        </div>
        <?php endforeach;
    endif;
}

function renderMatchingPassage(array $s, string $mode): void {
    if ($mode === 'passage'): ?>
        <div class="passage-box">
            <h4><?= htmlspecialchars($s['passage_title']) ?></h4>
            <?php if (!empty($s['passage_subtitle'])): ?>
            <p class="fst-italic text-muted small mb-2"><?= htmlspecialchars($s['passage_subtitle']) ?></p>
            <?php endif; ?>
            <div class="passage-items-grid">
            <?php foreach ($s['passage_items'] as $letter => $item): ?>
                <div class="passage-item">
                    <strong><?= $letter ?> &nbsp; <?= htmlspecialchars($item['title']) ?></strong>
                    <p class="mb-0 mt-1"><?= htmlspecialchars($item['text']) ?></p>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    <?php elseif ($mode === 'questions'): ?>
        <p class="fw-semibold small"><?= $s['instructions'] ?></p>
        <?php foreach ($s['questions'] as $row): ?>
        <div class="question-row">
            <span class="q-num"><?= $row['q'] ?>.</span>
            <span class="flex-grow-1 small"><?= htmlspecialchars($row['text']) ?></span>
            <select class="match-select" data-q="<?= $row['q'] ?>">
                <option value="">–</option>
                <?php foreach ($s['options'] as $opt): ?>
                <option value="<?= strtolower($opt) ?>"><?= $opt ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endforeach;
    endif;
}

function renderFormFill(array $s, string $mode): void {
    if ($mode === 'passage') {
        if (!empty($s['passage_title']) && !empty($s['passage'])): ?>
            <div class="passage-box">
                <h4><?= htmlspecialchars($s['passage_title']) ?></h4>
                <?php if (!empty($s['passage_subtitle'])): ?>
                <p class="fst-italic text-muted small mb-2"><?= htmlspecialchars($s['passage_subtitle']) ?></p>
                <?php endif; ?>
                <?= $s['passage'] ?>
            </div>
        <?php elseif (!empty($s['passage'])): ?>
            <div class="passage-box"><?= $s['passage'] ?></div>
        <?php endif;
        // Diagram label completion: the image the blanks refer to (e.g. a
        // cross-section diagram) — shown once, in the passage pane, right
        // alongside the text it came from.
        if (!empty($s['image'])): ?>
            <div class="passage-box text-center">
                <img src="<?= ACADEMY_URL ?>assets/img/practice_tests/<?= htmlspecialchars($GLOBALS['testCode']) ?>/<?= htmlspecialchars($s['image']) ?>" alt="<?= htmlspecialchars($s['image_alt'] ?? 'Diagram') ?>" style="max-width:100%;border:1px solid #dee2e6;border-radius:8px;">
            </div>
        <?php endif;
        return;
    }
    // questions mode
    ?>
    <p class="fw-semibold small"><?= $s['instructions'] ?></p>
    <div class="p-3 border rounded-3 bg-light">
        <?php if (!empty($s['form_title'])): ?>
        <h6 class="text-center fw-bold mb-3"><?= htmlspecialchars($s['form_title']) ?></h6>
        <?php endif; ?>
        <?php foreach ($s['groups'] as $group): ?>
            <?php if (!empty($group['heading'])): ?>
            <div class="notes-group-heading"><?= htmlspecialchars($group['heading']) ?></div>
            <?php endif; ?>
            <?php foreach ($group['rows'] as $row): ?>
            <div class="question-row ps-1" style="flex-wrap:wrap;">
                <?php if ($row['q'] !== null): ?>
                    <span class="q-num"><?= $row['q'] ?>.</span>
                <?php else: ?>
                    <span class="q-num text-muted">&bull;</span>
                <?php endif; ?>
                <?php if (!empty($row['prefix'])): ?>
                <span class="small"><?= $row['prefix'] ?></span>
                <?php endif; ?>
                <?php if ($row['q'] !== null): ?>
                <input type="text" class="q-input" data-q="<?= $row['q'] ?>" placeholder="answer">
                <?php endif; ?>
                <?php if (!empty($row['suffix'])): ?>
                <span class="small"><?= $row['suffix'] ?></span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
    <?php
}

function renderPassageMCQ(array $s, string $mode): void {
    // passage is optional here — when this MCQ set follows another section
    // on the same passage (already shown once), $s['passage'] is left
    // empty so it isn't duplicated.
    if ($mode === 'passage' && !empty($s['passage'])): ?>
        <div class="passage-box">
            <?php if (!empty($s['passage_title'])): ?>
            <h4><?= htmlspecialchars($s['passage_title']) ?></h4>
            <?php endif; ?>
            <?php if (!empty($s['passage_subtitle'])): ?>
            <p class="fst-italic text-muted small mb-2"><?= htmlspecialchars($s['passage_subtitle']) ?></p>
            <?php endif; ?>
            <?= $s['passage'] ?>
        </div>
    <?php elseif ($mode === 'questions'): ?>
        <p class="fw-semibold small"><?= $s['instructions'] ?></p>
        <?php foreach ($s['questions'] as $q): ?>
        <div class="mcq-card" data-q="<?= $q['q'] ?>">
            <p class="fw-semibold small mb-2">
                <span class="q-num"><?= $q['q'] ?>.</span><?= htmlspecialchars($q['text']) ?>
            </p>
            <?php foreach ($q['options'] as $letter => $text): ?>
            <label class="mcq-option">
                <input type="radio" name="q<?= $q['q'] ?>" value="<?= strtolower($letter) ?>" data-q="<?= $q['q'] ?>">
                <span class="small"><strong><?= $letter ?></strong> &nbsp; <?= htmlspecialchars($text) ?></span>
            </label>
            <?php endforeach; ?>
        </div>
        <?php endforeach;
    endif;
}

function renderSectionMatching(array $s, string $mode): void {
    // Unlike the Gold Rush test's usage (always paired with a preceding
    // passage_mcq section on the same passage), a standalone
    // "read passage, match against a list" task has nowhere else to show
    // its passage — so this renders one itself when $s['passage'] is set.
    if ($mode === 'passage') {
        if (!empty($s['passage'])): ?>
        <div class="passage-box">
            <?php if (!empty($s['passage_title'])): ?>
            <h4><?= htmlspecialchars($s['passage_title']) ?></h4>
            <?php endif; ?>
            <?php if (!empty($s['passage_subtitle'])): ?>
            <p class="fst-italic text-muted small mb-2"><?= htmlspecialchars($s['passage_subtitle']) ?></p>
            <?php endif; ?>
            <?= $s['passage'] ?>
        </div>
        <?php endif;
        return;
    }
    ?>
    <p class="fw-semibold small"><?= $s['instructions'] ?></p>
    <?php if (!empty($s['headings_list'])): ?>
    <div class="p-3 mb-3 border rounded-3 bg-light">
        <p class="fw-bold small mb-2">List of Headings</p>
        <?php foreach ($s['headings_list'] as $num => $heading): ?>
        <div class="small mb-1"><strong><?= $num ?></strong> &nbsp; <?= htmlspecialchars($heading) ?></div>
        <?php endforeach; ?>
    </div>
    <?php elseif (!empty($s['options_key'])): ?>
    <div class="p-3 mb-3 border rounded-3 bg-light">
        <?php foreach ($s['options_key'] as $letter => $label): ?>
        <div class="small mb-1"><strong><?= $letter ?></strong> &nbsp; <?= htmlspecialchars($label) ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php foreach ($s['questions'] as $row): ?>
    <div class="question-row">
        <span class="q-num"><?= $row['q'] ?>.</span>
        <span class="flex-grow-1 small"><?= htmlspecialchars($row['text']) ?></span>
        <select class="match-select" data-q="<?= $row['q'] ?>">
            <option value="">–</option>
            <?php foreach ($s['options'] as $opt): ?>
            <option value="<?= strtolower($opt) ?>"><?= $opt ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php endforeach;
}

// Table completion: a real HTML table with an <input> in each blank cell
// (a cell is either a literal string, or ['q' => N] for a blank), instead
// of the prose-style prefix/suffix blanks form_fill uses — closer to how
// the real exam actually presents this question type.
function renderTable(array $s, string $mode): void {
    if ($mode === 'passage') {
        return; // shares the preceding section's passage — nothing to add here
    }
    ?>
    <p class="fw-semibold small"><?= $s['instructions'] ?></p>
    <div class="table-responsive">
        <table class="table table-bordered table-sm small">
            <thead>
                <tr class="table-light">
                    <?php foreach ($s['columns'] as $col): ?>
                    <th><?= htmlspecialchars($col) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($s['rows'] as $row): ?>
                <tr>
                    <?php foreach ($row as $cell): ?>
                    <td>
                        <?php if (is_array($cell)): ?>
                            <span class="q-num"><?= $cell['q'] ?>.</span>
                            <input type="text" class="q-input" data-q="<?= $cell['q'] ?>" placeholder="answer" style="width:110px;display:inline-block;">
                        <?php else: ?>
                            <?= htmlspecialchars($cell) ?>
                        <?php endif; ?>
                    </td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}
?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>