<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}
require_once INCLUDES_PATH . '/course_lock.php';
// IELTS General Training Reading Practice Test 2 = Cambridge IELTS 15 General Training, Test 1, Reading: three sections, 40 questions, 60 minutes.
// Source: documentation/test_bank/cambridge_ielts15_gt/test1.json. Generated from the bank so the page and the answer key (migration 137) agree.
// Open to students enrolled in the IELTS General courses (staff, admins and testers always pass); course ids are looked up by folder, never typed.
require_course_enrollment(course_ids_for_folders(['IELTS_Gen_Mst', 'IELTS_Gen_1Mo', 'IELTS_Gen_2Mo']), 'this IELTS Reading practice test');

$testCode  = 'IELTS_PT_R_002';
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
                'passage' => '<h5 class="fw-bold mt-3">Consumer advice: What to do if something you ordered hasn\'t arrived</h5>
<p>If something you\'ve ordered hasn\'t arrived, you should contact the seller to find out where it is. It\'s their legal responsibility to make sure the item is delivered to you. They should chase the delivery company and let you know what\'s happened to your item. If your item wasn\'t delivered to the location you agreed (e.g. if it was left with your neighbour without your consent), it\'s the seller\'s legal responsibility to sort out the issue.</p>
<p>If the item doesn\'t turn up, you\'re legally entitled to a replacement or refund. You can ask for your money back if you don\'t receive the item within 30 days of buying it. If the seller refuses, you should put your complaint in writing. If that doesn\'t work, you could contact their trade association - look on their website for this information, or contact them to ask.</p>
<p>You might also be able to get your money back through your bank or payment provider - this depends on how you paid.</p>
<p>- If you paid by debit card, contact your bank and say you want to use the \'chargeback\' scheme. If the bank agrees, they can ask the seller\'s bank to refund the money to your account. Many bank staff don\'t know about the scheme, so you might need to speak to a supervisor or manager. You should do this within 120 days of when you paid.</p>
<p>- If you paid by credit card and the item cost less than £100, you should contact your credit card company and say you want to use the \'chargeback\' scheme. There\'s no time limit for when you need to do this. If the item cost more than £100 but less than £30,000, contact your credit card company and say that you want to make a \'section 75\' claim.</p>
<p>- If you paid using PayPal, use PayPal\'s online resolution centre to report your dispute. You must do this within 180 days of paying.</p>
<h5 class="fw-bold mt-3">Rice cookers: What\'s the best rice cooker for you?</h5>
<p><strong>A</strong> &nbsp; Ezy Rice Cooker - This has a 1.8 litre pot and a stainless steel exterior. It has a separate glass lid, and the handle on the lid stays cool. It produces perfectly cooked white rice, but tends to spit when cooking brown rice. There are slight dirt traps around the rim of the lid, and neither the pot nor the lid is dishwasher safe.</p>
<p><strong>B</strong> &nbsp; Family Rice Cooker - This has a plastic exterior and a flip-top lid. The lid locks when closed and becomes a secure handle to carry the cooker. The aluminium interior pot is quite difficult to clean, and it can\'t be put in a dishwasher. It\'s programmed to adjust the temperature once the rice is done so that it stops cooking but doesn\'t get cold.</p>
<p><strong>C</strong> &nbsp; Mini Rice Cooker - This has a flip-top lid and a 0.3 litre capacity. The interior pot is made of non-stick aluminium and is dishwasher safe. This rice cooker is ideal when cooking for one. However it does not have any handles at the side, and water sometimes overflows when cooking brown rice.</p>
<p><strong>D</strong> &nbsp; VPN Rice Cooker - This has a painted steel exterior with a handle on each side and a steel inner pot. It has a lift-off lid and comes with a booklet including a range of ideas for rice dishes. However, the keep-warm setting must be manually selected and the handles are tricky to grip.</p>
<p><strong>E</strong> &nbsp; S16 Rice cooker - This is simple to use, not spitting or boiling over even when cooking brown rice. The exterior stays cool when in use, so there\'s no danger of burning your hand. However, the lack of handles is a nuisance, and a recipe book would have been useful.</p>',
                'instructions' => '<strong>Questions 1–6.</strong> Do the following statements agree with the information given in the text? Write <strong>TRUE</strong> if the statement agrees with the information, <strong>FALSE</strong> if the statement contradicts the information, <strong>NOT GIVEN</strong> if there is no information on this.',
                'questions' => [
                    [
                        'q' => 1,
                        'text' => 'You will receive a card telling you if an item has been left with a neighbour.',
                    ],
                    [
                        'q' => 2,
                        'text' => 'It may be quicker to get a refund than a replacement for a non-delivered item.',
                    ],
                    [
                        'q' => 3,
                        'text' => 'You are entitled to a refund if the item fails to arrive by a certain time.',
                    ],
                    [
                        'q' => 4,
                        'text' => 'There is a time limit when using the \'chargeback\' scheme for a debit card payment.',
                    ],
                    [
                        'q' => 5,
                        'text' => 'You can use the \'chargeback\' scheme for a credit card payment of more than £100.',
                    ],
                    [
                        'q' => 6,
                        'text' => 'PayPal\'s online resolution centre has a good reputation for efficiency.',
                    ],
                ],
            ],
            [
                'type' => 'section_matching',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 7–14.</strong> Look at the five reviews of rice cookers, A-E. Write the correct letter A-E (any letter may be used more than once).',
                'options' => ['A', 'B', 'C', 'D', 'E'],
                'questions' => [
                    [
                        'q' => 7,
                        'text' => 'The handles at the side are hard to use.',
                    ],
                    [
                        'q' => 8,
                        'text' => 'It cooks brown rice without making a mess.',
                    ],
                    [
                        'q' => 9,
                        'text' => 'It automatically switches setting to keep the rice warm when cooked.',
                    ],
                    [
                        'q' => 10,
                        'text' => 'It\'s difficult to get the removable top really clean.',
                    ],
                    [
                        'q' => 11,
                        'text' => 'A selection of recipes is provided with the cooker.',
                    ],
                    [
                        'q' => 12,
                        'text' => 'It has a handle at the top for carrying the cooker safely.',
                    ],
                    [
                        'q' => 13,
                        'text' => 'The outside of the cooker doesn\'t get too hot.',
                    ],
                    [
                        'q' => 14,
                        'text' => 'You can put the pot in the dishwasher.',
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
                'passage' => '<h5 class="fw-bold mt-3">Safety when working on roofs</h5>
<p>A fall from height is the most serious hazard associated with roof work. Preventing falls from roofs is a priority for WorkSafe New Zealand. Investigations by WorkSafe into falls that occur while working at height show that more than 50 percent of falls are from under three metres, and most of these are from ladders and roofs. The cost of these falls is estimated to be $24 million a year. More injuries happen on residential building sites than any other workplace in the construction sector.</p>
<p>In order to prevent such injuries, a hazard assessment should be carried out for all work on roofs to assess potential dangers. It is essential that the hazards are identified before the work starts, and that the necessary equipment, appropriate precautions and systems of work are provided and implemented. Hazard identification should be repeated periodically or when there is a change in conditions, for example, the weather or numbers of staff onsite.</p>
<p>The first thing to be considered is whether it is possible to eliminate this hazard completely, so that workers are not exposed to the danger of falling. This can sometimes be done at the design, construction planning, and tendering stage. If the possibility of a fall cannot be eliminated, some form of edge protection should be used to prevent workers from falling. It may be possible to use the existing scaffolding as edge protection. If this is not practicable, then temporary work platforms should be used. In cases where such protection is not possible, then steps should be taken to minimise the likelihood of any harm resulting. This means considering the use of safety nets and other similar systems to make it less likely that injury will be caused if a fall does occur.</p>
<p>Ladders should only be employed for short-duration maintenance work such as touching up paint. People using ladders should be trained and instructed in the selection and safe use of ladders. There should be inspection of all ladders on a regular basis to ensure they are safe to use.</p>
<h5 class="fw-bold mt-3">Maternity Allowance for working women</h5>
<p>You can claim Maternity Allowance once you\'ve been pregnant for 26 weeks. Payments start 11 weeks before the date on which your baby is due.</p>
<p>The amount you can get depends on your eligibility. You could get either: £140.98 a week or 90% of your average weekly earnings (whichever is less) for 39 weeks, or £27 a week for 14 weeks.</p>
<p>Maternity Allowance for 39 weeks: You might get Maternity Allowance for 39 weeks if one of the following applies: you\'re employed; you\'re self-employed and pay Class 2 National Insurance (including voluntary National Insurance); you\'ve recently stopped working. You may still qualify even if you\'ve recently stopped working. It doesn\'t matter if you had different jobs, or periods when you were unemployed.</p>
<p>Maternity Allowance for 14 weeks: You might get Maternity Allowance for 14 weeks if for at least 26 weeks in the 66 weeks before your baby is due: you were married or in a civil partnership; you were not employed or self-employed; you took part in the business of your self-employed spouse or civil partner.</p>
<p>How to claim: You\'ll need an MA1 claim form, available online. You can print this and fill it in, or fill it in online. You also need to provide a payslip or a Certificate of Small Earnings Exemption as proof of your income, and proof of the baby\'s due date, such as a doctor\'s letter. You should get a decision on your claim within 24 working days. You should report any changes to your circumstances, for example, if you go back to work, to your local Jobcentre Plus as they can affect how much allowance you get.</p>',
                'instructions' => '<strong>Questions 15–22.</strong> Complete the notes below. Choose NO MORE THAN THREE WORDS AND/OR A NUMBER from the text.',
                'form_title' => null,
                'groups' => [
                    [
                        'heading' => null,
                        'rows' => [
                            [
                                'prefix' => 'Investigations show that: over half of falls are from less than',
                                'q' => 15,
                                'suffix' => '',
                            ],
                            [
                                'prefix' => 'the majority of falls occur on',
                                'q' => 16,
                                'suffix' => '',
                            ],
                            [
                                'prefix' => 'Controls:',
                                'q' => 17,
                                'suffix' => 'the hazard at the planning stage before the work begins if possible',
                            ],
                            [
                                'prefix' => 'prevent a fall by using edge protection, e.g. scaffolding or',
                                'q' => 18,
                                'suffix' => '',
                            ],
                            [
                                'prefix' => 'reduce the likelihood of injury, e.g. by using',
                                'q' => 19,
                                'suffix' => '',
                            ],
                            [
                                'prefix' => 'Ladders should only be used for',
                                'q' => 20,
                                'suffix' => 'which does not take a long time',
                            ],
                            [
                                'prefix' => 'training should be provided in their',
                                'q' => 21,
                                'suffix' => 'and use',
                            ],
                            [
                                'prefix' => 'regular',
                                'q' => 22,
                                'suffix' => 'of ladders is required',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'type' => 'form_fill',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 23–27.</strong> Complete the sentences below. Choose NO MORE THAN TWO WORDS AND/OR A NUMBER from the text.',
                'form_title' => null,
                'groups' => [
                    [
                        'heading' => null,
                        'rows' => [
                            [
                                'prefix' => 'The maximum amount of money a woman can get each week is £',
                                'q' => 23,
                                'suffix' => '',
                            ],
                            [
                                'prefix' => 'Being',
                                'q' => 24,
                                'suffix' => 'for a time does not necessarily mean that a woman will not be eligible for Maternity Allowance.',
                            ],
                            [
                                'prefix' => 'In order to claim, a woman must send a',
                                'q' => 25,
                                'suffix' => 'or a Small Earnings Exemption Certificate as evidence of her income.',
                            ],
                            [
                                'prefix' => 'In order to claim, a woman may need to provide a',
                                'q' => 26,
                                'suffix' => 'as evidence of the due date.',
                            ],
                            [
                                'prefix' => 'Payment may be affected by differences in someone\'s',
                                'q' => 27,
                                'suffix' => ', such as a return to work, and the local Jobcentre Plus must be informed.',
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
                'type' => 'passage_mcq',
                'passage_title' => null,
                'passage_subtitle' => null,
                'passage' => '<h5 class="fw-bold mt-3">The California Gold Rush of 1849</h5><p class="fst-italic text-muted small">the discovery of gold in the Sacramento Valley sparked the Gold Rush, arguably one of the most significant events to shape American history in the 19th century</p>
<p><strong>A</strong> &nbsp; On January 24, 1848, James Wilson Marshall, a carpenter, found small flakes of gold in the American River near Coloma, California. At the time, Marshall was working to build a water-powered sawmill for businessman John Sutter. As it happens, just days after Marshall\'s discovery, the Treaty of Guadalupe Hidalgo was signed, ending the Mexican-American War and transferring California, with its mineral deposits, into the ownership of the United States. At the time, the population of the territory consisted of 6,500 Californios (people of Spanish or Mexican descent); 700 foreigners (primarily Americans); and 150,000 Native Americans.</p>
<p><strong>B</strong> &nbsp; Though Marshall and Sutter tried to keep news of the discovery quiet, word got out, and by mid-March 1848 at least one newspaper was reporting that large quantities of gold were being found. Though the initial reaction in San Francisco was disbelief, storekeeper Sam Brannan set off a frenzy when he paraded through town displaying a small bottle containing gold from Sutter\'s Creek. By mid-June, some three-quarters of the male population of San Francisco had left town for the gold mines, and the number of miners in the area reached 4,000 by August.</p>
<p><strong>C</strong> &nbsp; As news spread of the fortunes being made in California, the first migrants to arrive were those from lands accessible by boat, such as Oregon, the Sandwich Islands (now Hawaii), Mexico, Chile, Peru and even China. Only later would the news reach the East Coast, where press reports were initially skeptical. Throughout 1849, thousands of people around the United States (mostly men) borrowed money, mortgaged their property or spent their life savings to make the arduous journey to California. In pursuit of the kind of wealth they had never dreamed of, they left their families and local areas; in turn, their wives had no option but to shoulder different responsibilities such as running farms or businesses, and many made a real success of them.</p>
<p>By the end of the year, the non-native population of California was estimated at 100,000 (as compared with 20,000 at the end of 1848 and around 800 in March 1848). To accommodate the needs of the \'49ers, as the gold miners were known, towns had sprung up all over the region, complete with shops and other businesses seeking to make their own Gold Rush fortune. The overcrowded chaos of the mining camps and towns grew ever more lawless. San Francisco, for its part, developed a bustling economy and became the central metropolis of the new frontier.</p>
<p><strong>D</strong> &nbsp; How did all these would-be miners search for gold? Panning was the oldest way. The basic procedure was to place some gold-bearing materials, such as river gravel, into a shallow pan, add some water, and then carefully swirl the mixture around so the water and light material spilled over the side. If all went well, the heavier gold nuggets or gold dust would settle to the bottom of the pan. Gold panning was slow even for the most skillful miner. On a good day, one miner could wash about 50 pans in the usual 12-hour workday.</p>
<p><strong>E</strong> &nbsp; Another way was to use what was called a \'rocker\'. Isaac Humphrey is said to have introduced it to the California gold fields. It was simply a rectangular wooden box, set at a downward angle and mounted on a rocking mechanism. The dirt and rock was dumped into the top, followed by a bucket of water. The box was rocked by hand to agitate the mixture. The big rocks were caught in a sieve at the top, the waste exited the lower end with the water, and the heavy gold fell to the bottom of the box.</p>
<p>The rocker had advantages and disadvantages. The advantages were that it was easily transportable; it did not require a constant source of water; and, most importantly, a miner could process more dirt and rock than with a pan. The primary disadvantage was that the rocker had difficulty in trapping the smallest particles of gold, commonly known as \'flour\'. Some miners added small amounts of mercury to the bottom of the rocker. Due to its chemical composition, it had a facility to trap fine gold. Periodically, the miners would remove and heat it. As it vaporized, it would leave gold behind.</p>
<p><strong>F</strong> &nbsp; After 1850, the surface gold in California had largely disappeared, even as miners continued to reach the gold fields. Mining had always been difficult and dangerous labor, and striking it rich required good luck as much as skill and hard work. Moreover, the average daily pay for an independent miner had by then dropped sharply from what it had been in 1848. As gold became more and more difficult to reach, the growing industrialization of mining drove more and more miners from independence into wage labor. The new technique of hydraulic mining, developed in 1853, brought enormous profits, but destroyed much of the region\'s landscape.</p>
<p><strong>G</strong> &nbsp; Though gold mining continued throughout the 1850s, it had reached its peak by 1852, when gold worth some $81 million was pulled from the ground. After that year, the total take declined gradually, leveling off to around $45 million per year by 1857. Settlement in California continued, however, and by the end of the decade the state\'s population was 380,000.</p>',
                'instructions' => '<strong>Questions 28–31.</strong> Choose the correct letter, <strong>A, B, C</strong> or <strong>D</strong>.',
                'questions' => [
                    [
                        'q' => 28,
                        'text' => 'The writer suggests that Marshall\'s discovery came at a good time for the US because',
                        'options' => [
                            'A' => 'the Mexican-American War was ending so there were men needing work.',
                            'B' => 'his expertise in water power would be useful in gold mining.',
                            'C' => 'the population of California had already begun to increase rapidly.',
                            'D' => 'the region was about to come under the control of the US.',
                        ],
                    ],
                    [
                        'q' => 29,
                        'text' => 'What was the reaction in 1848 to the news of the discovery of gold?',
                        'options' => [
                            'A' => 'The press played a large part in convincing the public of the riches available.',
                            'B' => 'Many men in San Francisco left immediately to check it out for themselves.',
                            'C' => 'People needed to see physical evidence before they took it seriously.',
                            'D' => 'Men in other mines in the US were among the first to respond to it.',
                        ],
                    ],
                    [
                        'q' => 30,
                        'text' => 'What was the result of thousands of people moving to California?',
                        'options' => [
                            'A' => 'San Francisco could not cope with the influx of people from around the world.',
                            'B' => 'Many miners got more money than they could ever have earned at home.',
                            'C' => 'Some of those who stayed behind had to take on unexpected roles.',
                            'D' => 'New towns were established which became good places to live.',
                        ],
                    ],
                    [
                        'q' => 31,
                        'text' => 'What does the writer say about using pans and rockers to find gold?',
                        'options' => [
                            'A' => 'Both methods required the addition of mercury.',
                            'B' => 'A rocker needed more than one miner to operate it.',
                            'C' => 'Pans were the best system for novice miners to use.',
                            'D' => 'Miners had to find a way round a design fault in one system.',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'section_matching',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 32–36.</strong> Which section (A-G) contains the following information?',
                'options' => ['A', 'B', 'C', 'D', 'E', 'F', 'G'],
                'questions' => [
                    [
                        'q' => 32,
                        'text' => 'a reference to ways of making money in California other than mining for gold',
                    ],
                    [
                        'q' => 33,
                        'text' => 'a suggestion that the gold that was found did not often compensate for the hard work undertaken',
                    ],
                    [
                        'q' => 34,
                        'text' => 'a mention of an individual who convinced many of the existence of gold in California',
                    ],
                    [
                        'q' => 35,
                        'text' => 'details of the pre-Gold Rush population of California',
                    ],
                    [
                        'q' => 36,
                        'text' => 'a contrast between shrinking revenue and increasing population',
                    ],
                ],
            ],
            [
                'type' => 'form_fill',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 37–40.</strong> Complete the summary. Choose ONE WORD ONLY from the text.',
                'form_title' => null,
                'groups' => [
                    [
                        'heading' => null,
                        'rows' => [
                            [
                                'prefix' => 'The most basic method used by many miners began with digging some',
                                'q' => 37,
                                'suffix' => 'out of a river and hoping it might contain gold.',
                            ],
                            [
                                'prefix' => 'if the miners were very lucky, there might even be some',
                                'q' => 38,
                                'suffix' => 'too.',
                            ],
                            [
                                'prefix' => 'Larger stones stuck in the',
                                'q' => 39,
                                'suffix' => ', while gold dropped to the bottom.',
                            ],
                            [
                                'prefix' => 'a process was introduced involving',
                                'q' => 40,
                                'suffix' => 'to ensure no gold was washed out in the water.',
                            ],
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
    <title>IELTS General Training Reading Practice Test 2 – EduHub</title>
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
                <li class="breadcrumb-item active">IELTS General Training Reading – Practice 2</li>
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