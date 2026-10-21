<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}
require_once INCLUDES_PATH . '/course_lock.php';
// IELTS General Training Reading Practice Test 3 = Cambridge IELTS 15 General Training, Test 2, Reading: three sections, 40 questions, 60 minutes.
// Source: documentation/test_bank/cambridge_ielts15_gt/test2.json. Generated from the bank so the page and the answer key (migration 137) agree.
// Open to students enrolled in the IELTS General courses (staff, admins and testers always pass); course ids are looked up by folder, never typed.
require_course_enrollment(course_ids_for_folders(['IELTS_Gen_Mst', 'IELTS_Gen_2Mo']), 'this IELTS Reading practice test');

$testCode  = 'IELTS_PT_R_003';
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
                'passage' => '<h5 class="fw-bold mt-3">Harvey\'s Storage</h5>
<p>Harvey\'s Storage is a well-established independent company. We are centrally located in the city and provide excellent facilities for all your storage requirements.</p>
<p>We provide safe and secure units for both long- and short-term storage dependent on your needs. Our rates are competitive and tailored to your specific requirements and your choice of storage unit. Heavy-duty locks and keys are provided to all of our customers and included in the prices listed. You can hire the unit with the storage capacity you need, for the period of time that the storage is required, in a sound and secure environment, monitored by CCTV. With 24-hour access, customers can deliver and collect items when it is convenient to do so, unrestricted by business or office hours. Tarmac roadways allow customers to park cars and lorries immediately outside their units, minimising the effort required to collect or drop items off.</p>
<p>Household storage: Self-storage is ideal for families or individuals with either a short- or long-term need to store their belongings. Some of our clients are de-cluttering, or they may be getting their property decorated, or planning to go abroad for a time.</p>
<p>Student storage: You may be travelling or going home to see family and friends in the vacation, or need time to find a place to stay. You may want to store all your books and personal items, or just a few boxes or a musical instrument. We offer no-nonsense competitive pricing with flexible hire periods and with no hidden extras. We can provide you with short- or long-term affordable hire in a safe and secure environment. You are responsible for organising transport but we can also recommend local van and driver hire companies.</p>
<p>Business storage: Free up your expensive retail space with affordable self-storage. We have three different business storage centres to choose from so you can choose the location that is most convenient for you.</p>
<h5 class="fw-bold mt-3">Local museums</h5>
<p><strong>A</strong> &nbsp; Whittlesey Museum - The museum is located in the Old Town Hall, which was originally built to house horse-drawn fire engines. It has eight rooms, and the exhibits cover topics such as archive photographs, costume, domestic life and local celebrities.</p>
<p><strong>B</strong> &nbsp; Octavia Hill\'s Birthplace House - Built in 1740, this is the birthplace of pioneer social reformer Octavia Hill, who was active in the late 19th and early 20th centuries in social housing and the arts, as well as in conservation issues. Visitors are taken on a guided tour and are then free to explore the gardens.</p>
<p><strong>C</strong> &nbsp; Chatteris Museum - The old market town of Chatteris was largely rebuilt, after two serious fires in 1706 and 1864 destroyed many of the town\'s ancient buildings. The museum\'s exhibits illustrate traditional aspects of the life of local farmers as well as the railway boom of the 19th century. The museum has a touch-screen kiosk which contains over 9,000 historic photographs and texts, reproductions of which can be made on request.</p>
<p><strong>D</strong> &nbsp; March and District Museum - Located in the middle of the market town of March, the museum is housed in a former school built in 1851. Its wide-ranging collections include reconstructions of an early 20th-century kitchen, sitting room and nursery. There is also an interesting display of historic cameras and radios, and a medal which was awarded to train driver Ben Gimbert for his bravery in preventing loss of life when a train full of explosives caught fire in 1944.</p>
<p><strong>E</strong> &nbsp; Wisbech and Fenland Museum - This 19th-century gem holds collections from around the world including Ancient Egypt. Its library, which is open to the public on the first Saturday of each month, contains the manuscript of Great Expectations by the 19th-century novelist Charles Dickens, and the Research Room can be booked for researching local records.</p>',
                'instructions' => '<strong>Questions 1–6.</strong> Do the following statements agree with the information given in the text? Write <strong>TRUE</strong> if the statement agrees with the information, <strong>FALSE</strong> if the statement contradicts the information, <strong>NOT GIVEN</strong> if there is no information on this.',
                'questions' => [
                    [
                        'q' => 1,
                        'text' => 'There is an extra charge for locks and keys.',
                    ],
                    [
                        'q' => 2,
                        'text' => 'It is possible to arrange to share a storage unit with someone else.',
                    ],
                    [
                        'q' => 3,
                        'text' => 'You can pick up your property from the storage unit during the night-time.',
                    ],
                    [
                        'q' => 4,
                        'text' => 'You can drive your vehicle right next to your storage unit.',
                    ],
                    [
                        'q' => 5,
                        'text' => 'Students\' possessions can only be stored during vacation periods.',
                    ],
                    [
                        'q' => 6,
                        'text' => 'The storage company will collect and deliver students\' property.',
                    ],
                ],
            ],
            [
                'type' => 'section_matching',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 7–14.</strong> Look at the five descriptions of museums, A-E. Write the correct letter A-E (any letter may be used more than once).',
                'options' => ['A', 'B', 'C', 'D', 'E'],
                'questions' => [
                    [
                        'q' => 7,
                        'text' => 'There are exhibits related to the history of agriculture in the region.',
                    ],
                    [
                        'q' => 8,
                        'text' => 'Equipment for putting out fires used to be kept in this building.',
                    ],
                    [
                        'q' => 9,
                        'text' => 'You can find information on the rise of one type of transport.',
                    ],
                    [
                        'q' => 10,
                        'text' => 'There are things to see both inside and outside.',
                    ],
                    [
                        'q' => 11,
                        'text' => 'It is possible to obtain copies of old pictures and documents.',
                    ],
                    [
                        'q' => 12,
                        'text' => 'On certain days you can see an original work by a writer of fiction.',
                    ],
                    [
                        'q' => 13,
                        'text' => 'Someone who was interested in environmental matters lived here for a time.',
                    ],
                    [
                        'q' => 14,
                        'text' => 'This museum has an exhibit related to a heroic achievement.',
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
                'passage' => '<h5 class="fw-bold mt-3">Workplace health and safety considerations for plumbers</h5>
<p>Like many trades, plumbing can be a dangerous job. It is important to take all reasonably practicable measures to keep customers and yourself incident and injury free.</p>
<p>Biohazard waste: Plumbers regularly come into contact with biohazard waste. It\'s the nature of the job, but that doesn\'t mean you should be complacent about it. According to Safe Work Australia, communicable diseases from work-related exposures to biological hazards such as sewage have been estimated to cause 320,000 deaths across the globe each year. In addition to this, plumbers are regularly exposed to other biohazards such as mould, bacteria and algae. Don\'t risk it - make sure appropriate protective clothing and equipment is used.</p>
<p>Confined spaces: Plumbers may spend much of their time working in confined spaces, where they are at risk from contaminants, including airborne gases, vapours and dusts, that may cause injury from fire or explosion. They may also be exposed to high concentrations of airborne contaminants that may be harmful to health. For example, one plumber was fined $220,000 after an employee suffered from carbon-monoxide poisoning. Another potential hazard for plumbers in confined spaces is that of drowning, if water sources are not adequately cut off.</p>
<p>The Safe Work Australia confined spaces code of practice outlines the necessary steps and precautions for avoiding illness and injury.</p>
<p>Electricity: The Master Plumbers\' Association calls electricity \'plumbing\'s hidden killer\'. Metal pipes are often conductive and so gloves which provide insulation should form part of a plumber\'s tool kit, as should a plumbing voltage monitor and a volt tester. Gloves should be checked prior to every use and replaced every 12 to 14 months. Electrical equipment like bridging conductors should be regularly checked, with appropriate tags on the equipment to verify its safety. The project should be stopped immediately if there is any sign of electricity, so that the power can be disconnected by a qualified electrician prior to continuing work.</p>
<h5 class="fw-bold mt-3">How to manage flexible working with your employees</h5>
<p>There is no denying that flexible working has grown enormously in recent years. It does, however, require careful management.</p>
<p>When it comes to implementing flexible working one word is key: trust. All flexible workers should be trusted and given well-defined objectives from the start and their contribution should be assessed according to their output, as opposed to the time they spend on the job. It can be a big step to implement such a change in your business, so if you are slightly cautious then I recommend perhaps setting up an end-of-the-day review to see how much progress has been made. As all parties find their feet with the new set-up, this contact can slowly be reduced.</p>
<p>In my eyes, it is also vital that there is shared calendar access for everyone so that people can see where their colleagues are each day. This way if they need to catch up with someone they can plan when to do so. Technology now exists to enable employees to stay in touch with other members of staff and external partners. iMeet, for example, is a tool which allows all forms of collaboration for remote working, from video conferencing, live chat and file sharing to screen sharing. The new breed of worker is therefore fully equipped to work productively away from the office, and can still feel like they\'re in the same room as others when necessary.</p>
<p>In my experience, employees are often more productive working at home as they can work the exact hours they want and do not have to cope with distraction caused by other employees. Being outside the confines of the office walls also appears to foster creativity. In addition, we find staff are more motivated as they have a better work-life balance. In terms of the business, we find this helps with top talent recruitment and staff retention, and a happy workforce is a more successful one.</p>',
                'instructions' => '<strong>Questions 15–20.</strong> Complete the table below, choose ONE WORD ONLY from the text.',
                'form_title' => null,
                'groups' => [
                    [
                        'heading' => null,
                        'rows' => [
                            [
                                'prefix' => 'Biohazard - Examples:',
                                'q' => 15,
                                'suffix' => ', mould, bacteria, algae',
                            ],
                            [
                                'prefix' => 'Confined spaces - high concentrations of harmful airborne contaminants e.g. carbon monoxide - Risks:',
                                'q' => 16,
                                'suffix' => '',
                            ],
                            [
                                'prefix' => 'Confined spaces - water - Risks:',
                                'q' => 17,
                                'suffix' => '',
                            ],
                            [
                                'prefix' => 'Electricity - use insulated',
                                'q' => 18,
                                'suffix' => 'and appropriate equipment',
                            ],
                            [
                                'prefix' => 'Electricity - ensure equipment has',
                                'q' => 19,
                                'suffix' => 'on to show it is safe',
                            ],
                            [
                                'prefix' => 'Electricity - make sure electricity has been',
                                'q' => 20,
                                'suffix' => '',
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
                                'prefix' => 'provide them with clear',
                                'q' => 21,
                                'suffix' => '',
                            ],
                            [
                                'prefix' => 'initially, have a',
                                'q' => 22,
                                'suffix' => 'of progress each day',
                            ],
                            [
                                'prefix' => 'make sure a',
                                'q' => 23,
                                'suffix' => 'is accessible to give details of colleague locations',
                            ],
                            [
                                'prefix' => 'use a program to encourage different types of',
                                'q' => 24,
                                'suffix' => 'between workers',
                            ],
                            [
                                'prefix' => 'less',
                                'q' => 25,
                                'suffix' => 'from colleagues',
                            ],
                            [
                                'prefix' => 'increase in',
                                'q' => 26,
                                'suffix' => '',
                            ],
                            [
                                'prefix' => 'greater success for the company with staff recruitment and',
                                'q' => 27,
                                'suffix' => '',
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
                'type' => 'section_matching',
                'passage_title' => null,
                'passage_subtitle' => null,
                'passage' => '<h5 class="fw-bold mt-3">Preventing the theft of turtle eggs</h5><p class="fst-italic text-muted small">conservationists and law enforcement have struggled to prevent wildlife trafficking; could plastic eggs and GPS trackers change the game?</p>
<div class="p-3 my-2 border rounded-3 bg-light"><p class="fw-bold small mb-2">List of Headings</p><div class="small mb-1"><strong>i</strong> &nbsp; Developing an item that appears true to life</div><div class="small mb-1"><strong>ii</strong> &nbsp; Extending the project to other endangered species</div><div class="small mb-1"><strong>iii</strong> &nbsp; A short but intensive investigation with longer-term follow-up</div><div class="small mb-1"><strong>iv</strong> &nbsp; Problems facing sea turtles at a global level</div><div class="small mb-1"><strong>v</strong> &nbsp; Collection of eggs and their possible onward routes</div><div class="small mb-1"><strong>vi</strong> &nbsp; Intensive and large-scale poaching in one location</div><div class="small mb-1"><strong>vii</strong> &nbsp; Why catching the poachers may not solve the problem</div></div>
<p><strong>A</strong> &nbsp; Humans have been eating sea turtle eggs (and killing adult turtles for meat) for millennia. However, as human populations exploded and as sea turtles began to confront additional threats such as intensive fishing, beach development and climate change, sea turtle populations declined precipitously. Today, all but one of the world\'s seven species of sea turtles are considered threatened according to the IUCN Red List. And the one that\'s not - the flatback turtle - is listed as data deficient, which means scientists simply don\'t know how it\'s doing.</p>
<p><strong>B</strong> &nbsp; One major problem is that every year millions of sea turtle eggs are illegally taken by poachers for sale on the black market. The situation is particularly serious in Nicaragua, in Central America, which is home to four sea turtle species. Kim Williams-Guillen, who works for conservation body Paso Pacifico, described the poaching of sea turtle nests on the beaches of Nicaragua as \'uncontrolled, unregulated, extensive and contested\'. Even the best-protected beaches are plundered to some extent and it\'s not uncommon to see poachers digging up nests just meters from tourists watching sea turtles laying their clutch at night, she said. This poaching becomes particularly frenzied during the arribadas - mass laying events where thousands of turtles nest on the same beach for a single night in a biological strategy to overwhelm natural predators.</p>
<p><strong>C</strong> &nbsp; \'Even with armed guards, the numbers of poachers overwhelm military personnel by ten or twenty to one,\' Williams-Guillen said. \'Although many poachers are locals with limited resources, during these arribadas there are influxes of gangs of poachers from larger cities outside local communities. These are not just local poor people without other options.\' But to protect the country\'s sea turtles, Williams-Guillen said conservationists shouldn\'t just depend on catching low-level operators. \'If one poacher decides to stop, another one will just step into his place ... we need to know more about the middlemen and people higher up in the distribution chain,\' she said.</p>
<p><strong>D</strong> &nbsp; Paso Pacifico\'s solution is the creation of high-tech sea turtle eggs: fake eggs convincingly crafted to look like the real thing, but which contain GPS tracking devices. These have the potential to reveal the destination markets for trafficked sea turtle eggs.</p>
<p>Making convincing sea turtle eggs is not easy, and Paso Pacifico is still working on perfecting a prototype. In particular, it\'s proving quite problematic to create the right texture, since sea turtle eggs are not covered in a hard shell like those of birds, but are quite flexible.</p>
<p>So Paso Pacifico brought in Lauren Wilde, a special effects artist in the US, to create a convincing outer shell. First, Wilde had to get her hands on the real thing. Since it\'s illegal to send sea turtle eggs over the border, Wilde is using land turtle eggs from California. \'It was really eye opening and important for me to feel these eggs and how the shell bends a little,\' she said.</p>
<p>To get the GPS device inside the shell, Paso Pacifico is using 3D printers to make a plastic ball which will then have a GPS transmitter fitted inside. This will take the place of the embryo inside the shell. Lastly the fake shells will be sealed with silicone, waterproofing them.</p>
<p><strong>E</strong> &nbsp; Sea turtles on average lay around 100 eggs in a nest, and once the fake eggs are finished they will be slipped in with the real ones. Williams-Guillen said it might even be possible to deliver fake eggs into nests while poachers are at work. Wary of tourists, poachers will often back off if strangers come near and then return when they have gone. \'It would be pretty easy to drop an egg in the dark into a nest they have been digging up,\' she said.</p>
<p>Once the poacher picks up the fake egg along with the real ones, conservationists and law enforcement agents will be able to track them. Experts believe most of the stolen eggs eventually make their way out of Nicaragua, possibly to El Salvador or Guatemala. However, there is also growing concern that sea turtle eggs from Central America are actually heading to the USA, from where they are sold on to other countries around the world.</p>
<p><strong>F</strong> &nbsp; To date, Paso Pacifico has yet to put a single fake egg in a nest. But Williams-Guillen said she isn\'t too concerned that publicity for their scheme will result in poachers looking for the eggs. \'The vast majority of the poaching is happening at night, so already it is hard to tell [the eggs] apart, and at this point, poachers and middlemen are not closely inspecting eggs, but rather shoving them into a sack as quickly as possible.\'</p>
<p>Of course, poachers will eventually become aware of the prospect of fake eggs among the real ones - especially when customers try to bite into an egg and break their teeth on the GPS transmitter instead. So, Paso Pacifico plans to do a massive deployment of as many fakes as possible to gather a lot of data before poachers get wise.</p>
<p>Knowing where the eggs go will allow conservationists and law enforcement agents to focus their resources on the right places - whether it be through awareness-building campaigns or crackdowns on illegal sellers. And eventually Paso Pacifico hopes to share the technology with interested parties around the world.</p>',
                'instructions' => '<strong>Questions 28–33.</strong> Choose the correct heading for each section from the list, write i-vii.',
                'options' => ['i', 'ii', 'iii', 'iv', 'v', 'vi', 'vii'],
                'questions' => [
                    [
                        'q' => 28,
                        'text' => 'Section A',
                    ],
                    [
                        'q' => 29,
                        'text' => 'Section B',
                    ],
                    [
                        'q' => 30,
                        'text' => 'Section C',
                    ],
                    [
                        'q' => 31,
                        'text' => 'Section D',
                    ],
                    [
                        'q' => 32,
                        'text' => 'Section E',
                    ],
                    [
                        'q' => 33,
                        'text' => 'Section F',
                    ],
                ],
            ],
            [
                'type' => 'passage_mcq',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 34–37.</strong> Choose the correct letter, <strong>A, B, C</strong> or <strong>D</strong>.',
                'questions' => [
                    [
                        'q' => 34,
                        'text' => 'What does the writer suggest about the flatback turtle?',
                        'options' => [
                            'A' => 'It could be as severely threatened as other turtles.',
                            'B' => 'It has been neglected by scientists in the past.',
                            'C' => 'It is in less danger than some other species.',
                            'D' => 'It should be removed from the IUCN Red List.',
                        ],
                    ],
                    [
                        'q' => 35,
                        'text' => 'Williams-Guillen says that the poaching of sea turtle eggs in Nicaragua',
                        'options' => [
                            'A' => 'is mainly carried out by local people.',
                            'B' => 'may be encouraged by the presence of tourists.',
                            'C' => 'sometimes has a highly organised structure.',
                            'D' => 'can only be controlled by the use of armed guards.',
                        ],
                    ],
                    [
                        'q' => 36,
                        'text' => 'In Section E, Williams-Guillen says that one way to encourage poachers to take the fake eggs is to',
                        'options' => [
                            'A' => 'make fake nests and put the eggs into them.',
                            'B' => 'put them in nests with just a few real eggs.',
                            'C' => 'distract the poachers after the fake eggs have been put in the nests.',
                            'D' => 'put them in nests that the poachers have started to dig up.',
                        ],
                    ],
                    [
                        'q' => 37,
                        'text' => 'It is planned to use a large number of fake eggs at the beginning because',
                        'options' => [
                            'A' => 'some of the fake eggs may be missed by the poachers.',
                            'B' => 'it may not be possible to continue the project indefinitely.',
                            'C' => 'some eggs may be hidden in the sand.',
                            'D' => 'it may not be feasible to fund long-term research.',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'form_fill',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 38–40.</strong> Complete the summary, choose ONE WORD ONLY from the text.',
                'form_title' => null,
                'groups' => [
                    [
                        'heading' => null,
                        'rows' => [
                            [
                                'prefix' => 'Unlike a bird\'s egg, a turtle\'s egg has a shell which is',
                                'q' => 38,
                                'suffix' => '.',
                            ],
                            [
                                'prefix' => 'Lauren Wilde has studied eggs from Californian turtles that live on',
                                'q' => 39,
                                'suffix' => '',
                            ],
                            [
                                'prefix' => 'A GPS device will then be placed inside a',
                                'q' => 40,
                                'suffix' => 'in the fake shell.',
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
    <title>IELTS General Training Reading Practice Test 3 – EduHub</title>
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
                <li class="breadcrumb-item active">IELTS General Training Reading – Practice 3</li>
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