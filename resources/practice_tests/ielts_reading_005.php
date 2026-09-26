<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}
require_once INCLUDES_PATH . '/course_lock.php';
// IELTS General Training Reading Practice Test 5 = Cambridge IELTS 15 General Training, Test 4, Reading: three sections, 40 questions, 60 minutes.
// Source: documentation/test_bank/cambridge_ielts15_gt/test4.json. Generated from the bank so the page and the answer key (migration 137) agree.
// Open to students enrolled in the IELTS General courses (staff, admins and testers always pass); course ids are looked up by folder, never typed.
require_course_enrollment(course_ids_for_folders(['IELTS_Gen_Mst', 'IELTS_Gen_1Mo', 'IELTS_Gen_2Mo']), 'this IELTS Reading practice test');

$testCode  = 'IELTS_PT_R_005';
$timeLimit = 60 * 60;

$parts = [
    1 => [
        'title' => 'Section 1',
        'description' => 'Read the text(s) below and answer Questions 1–14.',
        'q_range' => [1, 14],
        'type' => 'mixed',
        'sections' => [
            [
                'type' => 'section_matching',
                'passage_title' => null,
                'passage_subtitle' => null,
                'passage' => '<h5 class="fw-bold mt-3">New cycle path to Marshbrook Country Park</h5>
<p><strong>A</strong> &nbsp; A new dual-purpose cycle and pedestrian route has been built from Atherton bus station to the country park\'s main entrance at Marshbrook. It avoids the main road into Atherton on the south side, and keeps mainly to less busy roads. Once the path leaves the built-up area, it goes through countryside until it reaches Marshbrook.</p>
<p><strong>B</strong> &nbsp; Funding for the cycle path has come largely from the county and town councils, while almost a third of it was raised through crowdfunding. Maintenance of the path is the responsibility of the county council. The cycle path was completed ahead of schedule - partly thanks to perfect weather for construction - and under budget.</p>
<p><strong>C</strong> &nbsp; Annie Newcome is the chief executive of Cycle Atherton, the organisation that aims to get people cycling more often and more safely. Cycle Atherton proposed the 12-kilometre-long cycle path initially, and has been active in promoting it. Ms Newcome says she is delighted that all the hard work to achieve the funding proved successful.</p>
<p><strong>D</strong> &nbsp; Marshbrook Country Park is a very popular recreational area, and the new path makes it much easier to reach from the town in an environmentally friendly way. At 2.5 metres wide, it is also suitable for users of wheelchairs, mobility scooters and buggies, who have not previously had access to the park without using motor vehicles.</p>
<p><strong>E</strong> &nbsp; Although the path is now open, work is continuing to improve the signs along it, such as warnings when the path approaches a road. New hedges and trees will also be planted along stretches of the path, to provide some shelter from the wind and to benefit wildlife.</p>
<p><strong>F</strong> &nbsp; Further information and a detailed map of the path including a proposed 5-kilometre extension are available online. The map can easily be downloaded and printed. Visit the county council website and follow the links to Atherton Cycle Path.</p>
<h5 class="fw-bold mt-3">Study dramatic arts at Thornley</h5>
<p>If you are hoping for a career in the theatre, Thornley College of Dramatic Arts is the place to come. For fifty years we have been providing top-quality courses for actors, directors, producers, musicians and everyone else who wishes to work professionally in the theatre or related industries. We also have expertise in preparing students for the specialised requirements of TV, film and radio. We\'ll make sure you\'re thoroughly prepared for the reality of work in your chosen field.</p>
<p>Our college-based tutors all have extensive practical experience in the entertainment industry as well as academic qualifications, and we also collaborate with some of the country\'s best directors, writers and actors to create challenging, inspiring and exciting projects with our students.</p>
<p>We are well-known around the world, with our students coming from every continent. Every year, we receive two thousand applications for the one hundred places on our degree courses. Only the most talented get places, and we are proud that over ninety percent of our students gain professional work within a year of graduating - a figure few other drama colleges in the UK can match.</p>
<p>To mark our fiftieth anniversary this year, we are putting on a production of Theatre 500. Written by two staff members especially for this occasion, this multimedia show celebrates five hundred years of drama, and involves all our students in one way or another.</p>
<p>Another major development is that the college is about to move. Our new premises are now under construction in the heart of Thornley, next to the council building, which has won a prize for its architecture. For the last two years, we have been developing designs with Miller Furbank Architects for our new home, and one aim has been to ensure the buildings complement the council offices. Work started on the foundations of the buildings in March last year, and we plan to move to the new site this coming September.</p>
<p>We have also been talking to cultural organisations in the district, and considering how we can bring cost-free benefits to the local community, as well as to our students. As a result, part of the space in the new buildings has been designed to be adaptable, in order to accommodate classes, performances and workshops for different-sized groups of local people.</p>',
                'instructions' => '<strong>Questions 1–7.</strong> Which paragraph mentions the following? Write the correct letter, A-F (any letter may be used more than once).',
                'options' => ['A', 'B', 'C', 'D', 'E', 'F'],
                'questions' => [
                    [
                        'q' => 1,
                        'text' => 'what still needs to be done',
                    ],
                    [
                        'q' => 2,
                        'text' => 'the original suggestion for creating the path',
                    ],
                    [
                        'q' => 3,
                        'text' => 'a reason why the path opened early',
                    ],
                    [
                        'q' => 4,
                        'text' => 'people who no longer need to get to the park by car',
                    ],
                    [
                        'q' => 5,
                        'text' => 'the route of the path',
                    ],
                    [
                        'q' => 6,
                        'text' => 'the length of the path',
                    ],
                    [
                        'q' => 7,
                        'text' => 'who paid for the path',
                    ],
                ],
            ],
            [
                'type' => 'true_false_ng',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 8–14.</strong> Do the following statements agree with the information given in the text? Write <strong>TRUE</strong> if the statement agrees with the information, <strong>FALSE</strong> if the statement contradicts the information, <strong>NOT GIVEN</strong> if there is no information on this.',
                'questions' => [
                    [
                        'q' => 8,
                        'text' => 'The college has introduced new courses since it opened.',
                    ],
                    [
                        'q' => 9,
                        'text' => 'The college provides training for work in the film industry.',
                    ],
                    [
                        'q' => 10,
                        'text' => 'Students have the chance to work with relevant professionals.',
                    ],
                    [
                        'q' => 11,
                        'text' => 'Many more people apply to study at the college than are accepted.',
                    ],
                    [
                        'q' => 12,
                        'text' => 'Theatre 500 was created by students.',
                    ],
                    [
                        'q' => 13,
                        'text' => 'The new building and the council building were designed by the same architects.',
                    ],
                    [
                        'q' => 14,
                        'text' => 'Local groups will be charged for using college premises.',
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
                'passage' => '<h5 class="fw-bold mt-3">How to make your working day more enjoyable</h5>
<p>Research shows that work takes up approximately a third of our lives. Most of us get so bogged down with day-to-day tasks though, that we easily forget why we originally applied for the job and what we can get out of it. Here are a few ideas for how to make your working day better.</p>
<p>Physical changes to your work environment can make a massive difference to how you feel. Get some green plants or a family photo for your desk. File all those odd bits of paper or throw them away. All of these little touches can make your work environment feel like it\'s yours. Make sure any screens you have are at a suitable height so you\'re not straining your neck and shoulders.</p>
<p>Humans need a change of environment every now and then to improve productivity. Go out at lunchtime for a quick walk. If you have the option, it\'s a good idea to work from home occasionally. And if there\'s a conference coming up, ask if you can go along to it. Not only will you practise your networking skills, but you\'ll also have a day away from the office.</p>
<p>Use coffee time to get to know a colleague you don\'t usually speak to. There\'s no point in getting away from staring at one thing though, only to replace it with another; so leave your mobile alone! Another tip is to try and stay out of office gossip. In the long run it could get you in more trouble than you realise.</p>
<p>When you\'re trying to focus on something, hunger is the worst thing. If you can, keep some healthy snacks in your desk because if you have something you can nibble on, it will make you work more effectively and you\'ll enjoy it more. Also, if you\'re dehydrated, you won\'t be able to focus properly. So keep drinking water.</p>
<p>Finally, if you\'ve been dreaming about starting up a big project for some time, do it! There are so many different things you can do to get you enjoying work more each day.</p>
<h5 class="fw-bold mt-3">How to get promoted</h5>
<p>If you\'re sitting at your desk wondering whether this will be the year you finally get promoted, here are some tips.</p>
<p>It starts with you. You are perhaps the most important part in the \'promotion process\', so you need to know what you want - and why you want it. Take an honest look at yourself - your achievements and also your skills, particularly those you could exploit to take on a different role.</p>
<p>Your boss is the gatekeeper. If you think your boss is likely to be on your side, ask for a meeting to discuss your serious commitment to the organisation and how this could translate into a more defined career plan. If you are less sure about your boss\'s view of your prospects and how they may react, start softly with a more deliberate focus on increasing your boss\'s understanding of the work you do and the added value you deliver.</p>
<p>Think about how you are perceived at work. In order for you to get your promotion, who needs to know about you? Who would be on the interview panel and whose opinion and input would they seek? And once you\'ve got a list of people to impress, ask yourself - do they know enough about you? And I mean really know - what you do day to day at your desk, your contribution to the team, and perhaps most importantly, your potential.</p>
<p>The chances are that those decision-makers won\'t know all they should about you. Raising your profile in your organisation is critical so that when those in charge start looking at that empty office and considering how best to fill it, the first name that pops into their heads is yours. If your firm has a newsletter, volunteer to write a feature to include in it. If they arrange regular client events, get involved in the organisation of them. And so on.</p>
<p>If you think your experience needs enhancing, then look at ways you can continue to improve it. If you are confident in your professional expertise but lack the latest management theory, enrol on some relevant courses that fit around your day job.</p>
<p>So what are you waiting for?</p>',
                'instructions' => '<strong>Questions 15–20.</strong> Complete the sentences below, choose ONE WORD ONLY from the text.',
                'form_title' => null,
                'groups' => [
                    [
                        'heading' => null,
                        'rows' => [
                            [
                                'prefix' => 'Bringing a personal',
                                'q' => 15,
                                'suffix' => 'to work will make the place feel more homely.',
                            ],
                            [
                                'prefix' => 'It is important to check the position of all',
                                'q' => 16,
                                'suffix' => 'before use to avoid pulling any muscles.',
                            ],
                            [
                                'prefix' => 'Leaving the office in the middle of the day may help to raise',
                                'q' => 17,
                                'suffix' => 'later on.',
                            ],
                            [
                                'prefix' => 'It is advisable to avoid checking a',
                                'q' => 18,
                                'suffix' => 'during breaks.',
                            ],
                            [
                                'prefix' => 'Getting involved in',
                                'q' => 19,
                                'suffix' => 'at work may have negative results.',
                            ],
                            [
                                'prefix' => 'Having a few',
                                'q' => 20,
                                'suffix' => 'available can help people concentrate better at work.',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'type' => 'form_fill',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 21–27.</strong> Complete the notes below, choose ONE WORD ONLY from the text.',
                'form_title' => null,
                'groups' => [
                    [
                        'heading' => null,
                        'rows' => [
                            [
                                'prefix' => 'First step: examine past successes and any',
                                'q' => 21,
                                'suffix' => 'that would help gain promotion',
                            ],
                            [
                                'prefix' => 'how best to use your high level of',
                                'q' => 22,
                                'suffix' => 'in future',
                            ],
                            [
                                'prefix' => 'or how much extra',
                                'q' => 23,
                                'suffix' => 'you already bring to the company',
                            ],
                            [
                                'prefix' => 'find out which ones will be members of the',
                                'q' => 24,
                                'suffix' => 'who decide on the promotion',
                            ],
                            [
                                'prefix' => 'consider how much they are aware of your',
                                'q' => 25,
                                'suffix' => 'for the future',
                            ],
                            [
                                'prefix' => 'participating in the',
                                'q' => 26,
                                'suffix' => 'of events for customers',
                            ],
                            [
                                'prefix' => 'take any',
                                'q' => 27,
                                'suffix' => 'that fill in gaps in knowledge',
                            ],
                        ],
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
                'type' => 'form_fill',
                'passage_title' => null,
                'passage_subtitle' => null,
                'passage' => '<h5 class="fw-bold mt-3">Animals can tell right from wrong</h5>
<p>Until recently, humans were thought to be the only species to experience complex emotions and have a sense of morality. But Professor Marc Bekoff, an ecologist at University of Colorado, Boulder, US, believes that morals are \'hard-wired\' into the brains of all mammals and provide the \'social glue\' that allows animals to live together in groups.</p>
<p>His conclusions will assist animal welfare groups pushing to have animals treated more humanely. Professor Bekoff, who presents his case in his book Wild Justice, said: \'Just as in humans, the moral nuances of a particular culture or group will be different from another, but they are certainly there. Moral codes are species specific, so they can be difficult to compare with each other or with humans.\' Professor Bekoff believes morals developed in animals to help regulate behaviour in social groups. He claims that these help to limit fighting within the group and encourage co-operative behaviour.</p>
<p>His ideas have met with some controversy in the scientific community. Professor Frans de Waal, who examines the behaviour of primates, including chimpanzees, at Emory University, Atlanta, Georgia, US, said: \'I don\'t believe animals are moral in the sense we humans are - with a well-developed and reasoned sense of right and wrong - rather that human morality incorporates a set of psychological tendencies and capacities such as empathy, reciprocity, a desire for co-operation and harmony that are older than our species. Human morality was not formed from scratch, but grew out of our primate psychology. Primate psychology has ancient roots, and I agree that other animals show many of the same tendencies and have an intense sociality.\'</p>
<p>Wolves live in tight-knit social groups that are regulated by strict rules. Wolves also demonstrate fairness. During play, dominant wolves will appear to exchange roles with lower-ranking wolves. They pretend to be submissive and go so far as to allow biting by the lower-ranking wolves, provided it is not too hard. Prof Bekoff argues that without a moral code governing their actions, this kind of behaviour would not be possible. Astonishingly, if an animal becomes aggressive, it will perform a \'play bow\' to ask forgiveness before play resumes.</p>
<p>In other members of the dog family, play is controlled in a similar way. Among coyotes, cubs which are too aggressive are ignored by the rest of the group and often end up having to leave entirely. Experiments with domestic dogs, where one animal was given some \'sweets\' and another wasn\'t, have shown that they possess a sense of fairness as they allowed their companion to eat some.</p>
<p>Elephants are intensely sociable and emotional animals. Research by Iain Douglas-Hamilton, from the department of zoology at Oxford University, suggests elephants experience compassion and has found evidence of elephants helping injured members of their herd. In 2003, a herd of 11 elephants rescued antelopes which were being held inside an enclosure in KwaZulu-Natal, South Africa. The top female elephant unfastened all of the metal latches holding the gates closed and swung them open, allowing the antelopes to escape. This is thought to be a rare example of animals showing empathy for members of another species - a trait previously thought to be the exclusive preserve of humankind.</p>
<p>A laboratory experiment involved training Diana monkeys to insert a token into a slot to obtain food. A male who had become skilled at the task was found to be helping the oldest female, who had not learned how to do it. On three occasions the male monkey picked up tokens she dropped and inserted them into the slot and allowed her to have the food. As there was no benefit for the male monkey, Professor Bekoff argues that this is a clear example of an animal\'s actions being driven by some internal moral compass.</p>
<p>Since chimpanzees are known to be among the most cognitively advanced of the great apes and our closest cousins, it is perhaps not remarkable that scientists should suggest they live by moral codes. A chimpanzee known as Knuckles is the only known captive chimpanzee to suffer from cerebral palsy, which leaves him physically and mentally impaired. What is extraordinary is that scientists have observed other chimpanzees interacting with him differently and he is rarely subjected to intimidating displays of aggression from older males. Chimpanzees also demonstrate a sense of justice and those who deviate from the code of conduct of a group are set upon by other members as punishment.</p>
<p>Experiments with rats have shown that they will not take food if they know their actions will cause pain to another rat. In lab tests, rats were given food which then caused a second group of rats to receive an electric shock. The rats with the food stopped eating rather than see this happen.</p>
<p>Whales have been found to have spindle cells in their brains. These specialised cells were thought to be restricted to humans and great apes, and appear to play a role in empathy and understanding the emotions of others. Humpback whales, fin whales, killer whales and sperm whales have all been found to have spindle cells. They also have three times as many spindle cells as humans and are thought to be older in evolutionary terms. This finding suggests that emotional judgements such as empathy may have evolved considerably earlier in history than formerly thought and could be widespread in the animal kingdom.</p>',
                'instructions' => '<strong>Questions 28–32.</strong> Complete the summary, choose ONE WORD ONLY from the text.',
                'form_title' => null,
                'groups' => [
                    [
                        'heading' => null,
                        'rows' => [
                            [
                                'prefix' => 'Wolves live in packs and it is clear that there are a number of',
                                'q' => 28,
                                'suffix' => 'concerning their behaviour.',
                            ],
                            [
                                'prefix' => 'Some observers believe they exhibit a sense of',
                                'q' => 29,
                                'suffix' => '.',
                            ],
                            [
                                'prefix' => 'They act as if they are',
                                'q' => 30,
                                'suffix' => 'to the juniors',
                            ],
                            [
                                'prefix' => 'and even permit some gentle',
                                'q' => 31,
                                'suffix' => '.',
                            ],
                            [
                                'prefix' => 'it bends down begging for',
                                'q' => 32,
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
                'instructions' => '<strong>Questions 33–37.</strong> Match each animal with the correct description, A-G.',
                'options' => ['A', 'B', 'C', 'D', 'E', 'F', 'G'],
                'questions' => [
                    [
                        'q' => 33,
                        'text' => 'coyotes',
                    ],
                    [
                        'q' => 34,
                        'text' => 'domestic dogs',
                    ],
                    [
                        'q' => 35,
                        'text' => 'elephants',
                    ],
                    [
                        'q' => 36,
                        'text' => 'Diana monkeys',
                    ],
                    [
                        'q' => 37,
                        'text' => 'rats',
                    ],
                ],
            ],
            [
                'type' => 'passage_mcq',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 38–40.</strong> Choose the correct letter, <strong>A, B, C</strong> or <strong>D</strong>.',
                'questions' => [
                    [
                        'q' => 38,
                        'text' => 'What view is expressed by Professor de Waal?',
                        'options' => [
                            'A' => 'Apes have advanced ideas about the difference between good and evil.',
                            'B' => 'The social manners of some animals prove that they are highly moral.',
                            'C' => 'Some human moral beliefs developed from our animal ancestors.',
                            'D' => 'The desire to live in peace with others is a purely human quality.',
                        ],
                    ],
                    [
                        'q' => 39,
                        'text' => 'Why does Professor Bekoff mention the experiment on Diana monkeys?',
                        'options' => [
                            'A' => 'It shows that this species of monkey is not very easy to train.',
                            'B' => 'It confirms his view on the value of research into certain monkeys.',
                            'C' => 'It proves that female monkeys are generally less intelligent than males.',
                            'D' => 'It illustrates a point he wants to make about monkeys and other creatures.',
                        ],
                    ],
                    [
                        'q' => 40,
                        'text' => 'What does the writer find most surprising about chimpanzees?',
                        'options' => [
                            'A' => 'They can suffer from some of the same illnesses as humans.',
                            'B' => 'They appear to treat disabled peers with consideration.',
                            'C' => 'They have sets of social conventions that they follow.',
                            'D' => 'The males can be quite destructive at times.',
                        ],
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
    <title>IELTS General Training Reading Practice Test 5 – EduHub</title>
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
                <li class="breadcrumb-item active">IELTS General Training Reading – Practice 5</li>
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