<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}
require_once INCLUDES_PATH . '/course_lock.php';
// Cambridge IELTS 17 Academic, Test 4, Reading: three passages, 40 questions, 60 minutes (a full Academic Reading test).
// Source: documentation/test_bank/cambridge_ielts17_academic/test4.json. Generated from the bank so the page and the answer key (migration 135) agree.
// Open to students enrolled in IELTS_Aca_3Mo (staff, admins and testers always pass); course ids are looked up by folder, never typed.
require_course_enrollment(course_ids_for_folders(['IELTS_Aca_3Mo']), 'this IELTS Academic Reading practice test');

$testCode  = 'IELTS_PT_R_ACA_C17T4';
$timeLimit = 60 * 60;

$parts = [
    1 => [
        'title' => 'Part 1',
        'description' => 'Read the text below and answer Questions 1–13. You should spend about 20 minutes on this part.',
        'q_range' => [1, 13],
        'type' => 'mixed',
        'sections' => [
            [
                'type' => 'true_false_ng',
                'passage_title' => 'Bats to the rescue',
                'passage_subtitle' => 'How Madagascar\'s bats are helping to save the rainforest',
                'passage' => '<p>There are few places in the world where relations between agriculture and conservation are more strained. Madagascar\'s forests are being converted to agricultural land at a rate of one percent every year. Much of this destruction is fuelled by the cultivation of the country\'s main staple crop: rice. And a key reason for this destruction is that insect pests are destroying vast quantities of what is grown by local subsistence farmers, leading them to clear forest to create new paddy fields. The result is devastating habitat and biodiversity loss on the island, but not all species are suffering. In fact, some of the island\'s insectivorous bats are currently thriving and this has important implications for farmers and conservationists alike.</p>
<p>Enter University of Cambridge zoologist Ricardo Rocha. He\'s passionate about conservation, and bats. More specifically, he\'s interested in how bats are responding to human activity and deforestation in particular. Rocha\'s new study shows that several species of bats are giving Madagascar\'s rice farmers a vital pest control service by feasting on plagues of insects. And this, he believes, can ease the financial pressure on farmers to turn forest into fields.</p>
<p>Bats comprise roughly one-fifth of all mammal species in Madagascar and thirty-six recorded bat species are native to the island, making it one of the most important regions for conservation of this animal group anywhere in the world.</p>
<p>Co-leading an international team of scientists, Rocha found that several species of indigenous bats are taking advantage of habitat modification to hunt insects swarming above the country\'s rice fields. They include the Malagasy mouse-eared bat, Major\'s long-fingered bat, the Malagasy white-bellied free-tailed bat and Peters\' wrinkle-lipped bat.</p>
<p>\'These winner species are providing a valuable free service to Madagascar as biological pest suppressors,\' says Rocha. \'We found that six species of bat are preying on rice pests, including the paddy swarming caterpillar and grass webworm. The damage which these insects cause puts the island\'s farmers under huge financial pressure and that encourages deforestation.\'</p>
<p>The study, now published in the journal Agriculture, Ecosystems and Environment, set out to investigate the feeding activity of insectivorous bats in the farmland bordering the Ranomafana National Park in the southeast of the country.</p>
<p>Rocha and his team used state-of-the-art ultrasonic recorders to record over a thousand bat \'feeding buzzes\' (echolocation sequences used by bats to target their prey) at 54 sites, in order to identify the favourite feeding spots of the bats. They next used DNA barcoding techniques to analyse droppings collected from bats at the different sites.</p>
<p>The recordings revealed that bat activity over rice fields was much higher than it was in continuous forest – seven times higher over rice fields which were on flat ground, and sixteen times higher over fields on the sides of hills – leaving no doubt that the animals are preferentially foraging in these man-made ecosystems. The researchers suggest that the bats favour these fields because lack of water and nutrient run-off make these crops more susceptible to insect pest infestations. DNA analysis showed that all six species of bat had fed on economically important insect pests. While the findings indicated that rice farming benefits most from the bats, the scientists also found indications that the bats were consuming pests of other crops, including the black twig borer (which infests coffee plants), the sugarcane cicada, the macadamia nut-borer, and the sober tabby (a pest of citrus fruits).</p>
<p>\'The effectiveness of bats as pest controllers has already been proven in the USA and Catalonia,\' said co-author James Kemp, from the University of Lisbon. \'But our study is the first to show this happening in Madagascar, where the stakes for both farmers and conservationists are so high.\'</p>
<p>Local people may have a further reason to be grateful to their bats. While the animal is often associated with spreading disease, Rocha and his team found evidence that Malagasy bats feed not just on crop pests but also on mosquitoes – carriers of malaria, Rift Valley fever virus and elephantiasis – as well as blackflies, which spread river blindness.</p>
<p>Rocha points out that the relationship is complicated. When food is scarce, bats become a crucial source of protein for local people. Even the children will hunt them. And as well as roosting in trees, the bats sometimes roost in buildings, but are not welcomed there because they make them unclean. At the same time, however, they are associated with sacred caves and the ancestors, so they can be viewed as beings between worlds, which makes them very significant in the culture of the people. And one potential problem is that while these bats are benefiting from farming, at the same time deforestation is reducing the places where they can roost, which could have long-term effects on their numbers. Rocha says, \'With the right help, we hope that farmers can promote this mutually beneficial relationship by installing bat houses.\'</p>
<p>Rocha and his colleagues believe that maximising bat populations can help to boost crop yields and promote sustainable livelihoods. The team is now calling for further research to quantify this contribution. \'I\'m very optimistic,\' says Rocha. \'If we give nature a hand, we can speed up the process of regeneration.\'</p>',
                'instructions' => '<strong>Questions 1–6.</strong> Do the following statements agree with the information given in Reading Passage 1? Write <strong>TRUE</strong> if the statement agrees with the information, <strong>FALSE</strong> if the statement contradicts the information, <strong>NOT GIVEN</strong> if there is no information on this.',
                'questions' => [
                    [
                        'q' => 1,
                        'text' => 'Many Madagascan forests are being destroyed by attacks from insects.',
                    ],
                    [
                        'q' => 2,
                        'text' => 'Loss of habitat has badly affected insectivorous bats in Madagascar.',
                    ],
                    [
                        'q' => 3,
                        'text' => 'Ricardo Rocha has carried out studies of bats in different parts of the world.',
                    ],
                    [
                        'q' => 4,
                        'text' => 'Habitat modification has resulted in indigenous bats in Madagascar becoming useful to farmers.',
                    ],
                    [
                        'q' => 5,
                        'text' => 'The Malagasy mouse-eared bat is more common than other indigenous bat species in Madagascar.',
                    ],
                    [
                        'q' => 6,
                        'text' => 'Bats may feed on paddy swarming caterpillars and grass webworms.',
                    ],
                ],
            ],
            [
                'type' => 'form_fill',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 7–13.</strong> Complete the table below. Choose <strong>ONE WORD ONLY</strong> from the passage for each answer.',
                'form_title' => 'The study carried out by Rocha’s team',
                'groups' => [
                    [
                        'heading' => null,
                        'rows' => [
                            [
                                'prefix' => 'The study carried out by Rocha\'s team - Method: ultrasonic recording to identify favourite feeding spots; DNA analysis of bat',
                                'q' => 7,
                                'suffix' => '',
                            ],
                            [
                                'prefix' => 'Findings: the bats ate pests of rice,',
                                'q' => 8,
                                'suffix' => ', sugarcane, nuts and fruit',
                            ],
                            [
                                'prefix' => 'the bats prevent the spread of disease by eating',
                                'q' => 9,
                                'suffix' => 'and blackflies',
                            ],
                            [
                                'prefix' => 'local attitudes to bats are mixed: they provide food rich in',
                                'q' => 10,
                                'suffix' => '',
                            ],
                            [
                                'prefix' => 'the buildings where they roost become',
                                'q' => 11,
                                'suffix' => '',
                            ],
                            [
                                'prefix' => 'they play an important role in local',
                                'q' => 12,
                                'suffix' => '',
                            ],
                            [
                                'prefix' => 'Recommendation: farmers should provide special',
                                'q' => 13,
                                'suffix' => 'to support the bat population',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    2 => [
        'title' => 'Part 2',
        'description' => 'Read the text below and answer Questions 14–26. You should spend about 20 minutes on this part.',
        'q_range' => [14, 26],
        'type' => 'mixed',
        'sections' => [
            [
                'type' => 'section_matching',
                'passage_title' => 'Does education fuel economic growth?',
                'passage_subtitle' => null,
                'passage' => '<p><strong>A</strong> &nbsp; Over the last decade, a huge database about the lives of southwest German villagers between 1600 and 1900 has been compiled by a team led by Professor Sheilagh Ogilvie at Cambridge University\'s Faculty of Economics. It includes court records, guild ledgers, parish registers, village censuses, tax lists and – the most recent addition – 9,000 handwritten inventories listing over a million personal possessions belonging to ordinary women and men across three centuries. Ogilvie, who discovered the inventories in the archives of two German communities 30 years ago, believes they may hold the answer to a conundrum that has long puzzled economists: the lack of evidence for a causal link between education and a country\'s economic growth.</p>
<p><strong>B</strong> &nbsp; As Ogilvie explains, \'Education helps us to work more productively, invent better technology, and earn more ... surely it must be critical for economic growth? But, if you look back through history, there\'s no evidence that having a high literacy rate made a country industrialise earlier.\' Between 1600 and 1900, England had only mediocre literacy rates by European standards, yet its economy grew fast and it was the first country to industrialise. During this period, Germany and Scandinavia had excellent literacy rates, but their economies grew slowly and they industrialised late. \'Modern cross-country analyses have also struggled to find evidence that education causes economic growth, even though there is plenty of evidence that growth increases education,\' she adds.</p>
<p><strong>C</strong> &nbsp; In the handwritten inventories that Ogilvie is analysing are the belongings of women and men at marriage, remarriage and death. From badger skins to Bibles, sewing machines to scarlet bodices – the villagers\' entire worldly goods are included. Inventories of agricultural equipment and craft tools reveal economic activities; ownership of books and education-related objects like pens and slates suggests how people learned. In addition, the tax lists included in the database record the value of farms, workshops, assets and debts; signatures and people\'s estimates of their age indicate literacy and numeracy levels; and court records reveal obstacles (such as the activities of the guilds*) that stifled industry.</p>
<p>Previous studies usually had just one way of linking education with economic growth – the presence of schools and printing presses, perhaps, or school enrolment, or the ability to sign names. According to Ogilvie, the database provides multiple indicators for the same individuals, making it possible to analyse links between literacy, numeracy, wealth, and industriousness, for individual women and men over the long term.</p>
<p><strong>D</strong> &nbsp; Ogilvie and her team have been building the vast database of material possessions on top of their full demographic reconstruction of the people who lived in these two German communities. \'We can follow the same people – and their descendants – across 300 years of educational and economic change,\' she says. Individual lives have unfolded before their eyes. Stories like that of the 24-year-olds Ana Regina and Magdalena Riethmüllerin, who were chastised in 1707 for reading books in church instead of listening to the sermon. \'This tells us they were continuing to develop their reading skills at least a decade after leaving school,\' explains Ogilvie. The database also reveals the case of Juliana Schweickherdt, a 50-year-old spinster living in the small Black Forest community of Wildberg, who was reprimanded in 1752 by the local weavers\' guild for \'weaving cloth and combing wool, counter to the guild ordinance\'. When Juliana continued taking jobs reserved for male guild members, she was summoned before the guild court and told to pay a fine equivalent to one third of a servant\'s annual wage. It was a small act of defiance by today\'s standards, but it reflects a time when laws in Germany and elsewhere regulated people\'s access to labour markets. The dominance of guilds not only prevented people from using their skills, but also held back even the simplest industrial innovation.</p>
<p><strong>E</strong> &nbsp; The data-gathering phase of the project has been completed and now, according to Ogilvie, it is time \'to ask the big questions\'. One way to look at whether education causes economic growth is to \'hold wealth constant\'. This involves following the lives of different people with the same level of wealth over a period of time. If wealth is constant, it is possible to discover whether education was, for example, linked to the cultivation of new crops, or to the adoption of industrial innovations like sewing machines. The team will also ask what aspect of education helped people engage more with productive and innovative activities. Was it, for instance, literacy, numeracy, book ownership, years of schooling? Was there a threshold level – a tipping point – that needed to be reached to affect economic performance?</p>
<p><strong>F</strong> &nbsp; Ogilvie hopes to start finding answers to these questions over the next few years. One thing is already clear, she says: the relationship between education and economic growth is far from straightforward. \'German-speaking central Europe is an excellent laboratory for testing theories of economic growth,\' she explains. Between 1600 and 1900, literacy rates and book ownership were high and yet the region remained poor. It was also the case that local guilds and merchant associations were extremely powerful and legislated against anything that undermined their monopolies. In villages throughout the region, guilds blocked labour migration and resisted changes that might reduce their influence.</p>
<p>\'Early findings suggest that the potential benefits of education for the economy can be held back by other barriers, and this has implications for today,\' says Ogilvie. \'Huge amounts are spent improving education in developing countries, but this spending can fail to deliver economic growth if restrictions block people – especially women and the poor – from using their education in economically productive ways. If economic institutions are poorly set up, for instance, education can\'t lead to growth.\'</p>
<p class="text-muted small"><em>* guild: an association of artisans or merchants which oversees the practice of their craft or trade in a particular area</em></p>',
                'instructions' => '<strong>Questions 14–18.</strong> Reading Passage 2 has six sections, <strong>A–F</strong>. Which section contains the following information? Write the correct letter, <strong>A–F</strong>.',
                'options' => ['A', 'B', 'C', 'D', 'E', 'F'],
                'questions' => [
                    [
                        'q' => 14,
                        'text' => 'an explanation of the need for research to focus on individuals with a fairly consistent income',
                    ],
                    [
                        'q' => 15,
                        'text' => 'examples of the sources the database has been compiled from',
                    ],
                    [
                        'q' => 16,
                        'text' => 'an account of one individual\'s refusal to obey an order',
                    ],
                    [
                        'q' => 17,
                        'text' => 'a reference to a region being particularly suited to research into the link between education and economic growth',
                    ],
                    [
                        'q' => 18,
                        'text' => 'examples of the items included in a list of personal possessions',
                    ],
                ],
            ],
            [
                'type' => 'form_fill',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 19–22.</strong> Complete the summary below. Choose <strong>ONE WORD ONLY</strong> from the passage for each answer.',
                'form_title' => 'Demographic reconstruction of two German communities',
                'groups' => [
                    [
                        'heading' => null,
                        'rows' => [
                            [
                                'prefix' => 'Demographic reconstruction of two German communities - The database sheds light on the lives of a range of individuals, as well as those of their',
                                'q' => 19,
                                'suffix' => ', over a 300-year period.',
                            ],
                            [
                                'prefix' => 'Ana Regina and Magdalena Riethmüllerin were reprimanded for reading while they should have been paying attention to a',
                                'q' => 20,
                                'suffix' => '.',
                            ],
                            [
                                'prefix' => 'Juliana Schweickherdt came to the notice of the weavers\' guild in the year 1752 for breaking guild rules. As a punishment, she was later given a',
                                'q' => 21,
                                'suffix' => '.',
                            ],
                            [
                                'prefix' => 'Cases like this illustrate how the guilds could prevent',
                                'q' => 22,
                                'suffix' => 'and stop skilled people from working.',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'type' => 'section_matching',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 23 and 24.</strong> Choose <strong>TWO</strong> letters, <strong>A–E</strong>. Which TWO of the following statements does the writer make about literacy rates in Section B?',
                'options_key' => [
                    'A' => 'Very little research has been done into the link between high literacy rates and improved earnings.',
                    'B' => 'Literacy rates in Germany between 1600 and 1900 were very good.',
                    'C' => 'There is strong evidence that high literacy rates in the modern world result in economic growth.',
                    'D' => 'England is a good example of how high literacy rates helped a country industrialise.',
                    'E' => 'Economic growth can help to improve literacy rates.',
                ],
                'options' => ['A', 'B', 'C', 'D', 'E'],
                'questions' => [
                    [
                        'q' => 23,
                        'text' => 'First answer',
                    ],
                    [
                        'q' => 24,
                        'text' => 'Second answer',
                    ],
                ],
            ],
            [
                'type' => 'section_matching',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 25 and 26.</strong> Choose <strong>TWO</strong> letters, <strong>A–E</strong>. Which TWO of the following statements does the writer make in Section F about guilds in German-speaking Central Europe between 1600 and 1900?',
                'options_key' => [
                    'A' => 'They helped young people to learn a skill.',
                    'B' => 'They were opposed to people moving to an area for work.',
                    'C' => 'They kept better records than guilds in other parts of the world.',
                    'D' => 'They opposed practices that threatened their control over a trade.',
                    'E' => 'They predominantly consisted of wealthy merchants.',
                ],
                'options' => ['A', 'B', 'C', 'D', 'E'],
                'questions' => [
                    [
                        'q' => 25,
                        'text' => 'First answer',
                    ],
                    [
                        'q' => 26,
                        'text' => 'Second answer',
                    ],
                ],
            ],
        ],
    ],
    3 => [
        'title' => 'Part 3',
        'description' => 'Read the text below and answer Questions 27–40. You should spend about 20 minutes on this part.',
        'q_range' => [27, 40],
        'type' => 'mixed',
        'sections' => [
            [
                'type' => 'section_matching',
                'passage_title' => 'Timur Gareyev – blindfold chess champion',
                'passage_subtitle' => null,
                'passage' => '<p><strong>A</strong> &nbsp; Next month, a chess player named Timur Gareyev will take on nearly 50 opponents at once. But that is not the hard part. While his challengers will play the games as normal, Gareyev himself will be blindfolded. Even by world record standards, it sets a high bar for human performance. The 28-year-old already stands out in the rarefied world of blindfold chess. He has a fondness for bright clothes and unusual hairstyles, and he gets his kicks from the adventure sport of BASE jumping. He has already proved himself a strong chess player, too. In a 10-hour chess marathon in 2013, Gareyev played 33 games in his head simultaneously. He won 29 and lost none. The skill has become his brand: he calls himself the Blindfold King.</p>
<p><strong>B</strong> &nbsp; But Gareyev\'s prowess has drawn interest from beyond the chess-playing community. In the hope of understanding how he and others like him can perform such mental feats, researchers at the University of California in Los Angeles (UCLA) called him in for tests. They now have their first results. \'The ability to play a game of chess with your eyes closed is not a far reach for most accomplished players,\' said Jesse Rissman, who runs a memory lab at UCLA. \'But the thing that\'s so remarkable about Timur and a few other individuals is the number of games they can keep active at once. To me it is simply astonishing.\'</p>
<p><strong>C</strong> &nbsp; Gareyev learned to play chess in his native Uzbekistan when he was six years old. Tutored by his grandfather, he entered his first tournament aged eight and soon became obsessed with competitions. At 16, he was crowned Asia\'s youngest ever chess grandmaster. He moved to the US soon after, and as a student helped his university win its first national chess championship. In 2013, Gareyev was ranked the third best chess player in the US.</p>
<p><strong>D</strong> &nbsp; To the uninitiated, blindfold chess seems to call for superhuman skill. But displays of the feat go back centuries. The first recorded game in Europe was played in 13th-century Florence. In 1947, the Argentinian grandmaster Miguel Najdorf played 45 simultaneous games in his mind, winning 39 in the 24-hour session.</p>
<p><strong>E</strong> &nbsp; Accomplished players can develop the skill of playing blind even without realising it. The nature of the game is to run through possible moves in the mind to see how they play out. From this, regular players develop a memory for the patterns the pieces make, the defences and attacks. \'You recreate it in your mind,\' said Gareyev. \'A lot of players are capable of doing what I\'m doing.\' The real mental challenge comes from playing multiple games at once in the head. Not only must the positions of each piece on every board be memorised, they must be recalled faithfully when needed, updated with each player\'s moves, and then reliably stored again, so the brain can move on to the next board. First moves can be tough to remember because they are fairly uninteresting. But the ends of games are taxing too, as exhaustion sets in. When Gareyev is tired, his recall can get patchy. He sometimes makes moves based on only a fragmented memory of the pieces\' positions.</p>
<p><strong>F</strong> &nbsp; The scientists first had Gareyev perform some standard memory tests. These assessed his ability to hold numbers, pictures and words in mind. One classic test measures how many numbers a person can repeat, both forwards and backwards, soon after hearing them. Most people manage about seven. \'He was not exceptional on any of these standard tests,\' said Rissman. \'We didn\'t find anything other than playing chess that he seems to be supremely gifted at.\' But next came the brain scans. With Gareyev lying down in the machine, Rissman looked at how well connected the various regions of the chess player\'s brain were. Though the results are tentative and as yet unpublished, the scans found much greater than average communication between parts of Gareyev\'s brain that make up what is called the frontoparietal control network. Of 63 people scanned alongside the chess player, only one or two scored more highly on the measure. \'You use this network in almost any complex task. It helps you to allocate attention, keep rules in mind, and work out whether you should be responding or not,\' said Rissman.</p>
<p><strong>G</strong> &nbsp; It was not the only hint of something special in Gareyev\'s brain. The scans also suggest that Gareyev\'s visual network is more highly connected to other brain parts than usual. Initial results suggest that the areas of his brain that process visual images – such as chess boards – may have stronger links to other brain regions, and so be more powerful than normal. While the analyses are not finalised yet, they may hold the first clues to Gareyev\'s extraordinary ability.</p>
<p><strong>H</strong> &nbsp; For the world record attempt, Gareyev hopes to play 47 blindfold games at once in about 16 hours. He will need to win 80% to claim the title. \'I don\'t worry too much about the winning percentage, that\'s never been an issue for me,\' he said. \'The most important part of blindfold chess for me is that I have found the one thing that I can fully dedicate myself to. I miss having an obsession.\'</p>',
                'instructions' => '<strong>Questions 27–32.</strong> Reading Passage 3 has eight paragraphs, <strong>A–H</strong>. Which paragraph contains the following information? Write the correct letter, <strong>A–H</strong>. You may use any letter more than once.',
                'options' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'],
                'questions' => [
                    [
                        'q' => 27,
                        'text' => 'a reference to earlier examples of blindfold chess',
                    ],
                    [
                        'q' => 28,
                        'text' => 'an outline of what blindfold chess involves',
                    ],
                    [
                        'q' => 29,
                        'text' => 'a claim that Gareyev\'s skill is limited to chess',
                    ],
                    [
                        'q' => 30,
                        'text' => 'why Gareyev\'s skill is of interest to scientists',
                    ],
                    [
                        'q' => 31,
                        'text' => 'an outline of Gareyev\'s priorities',
                    ],
                    [
                        'q' => 32,
                        'text' => 'a reason why the last part of a game may be difficult',
                    ],
                ],
            ],
            [
                'type' => 'true_false_ng',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 33–36.</strong> Do the following statements agree with the information given in Reading Passage 3? Write <strong>TRUE</strong> if the statement agrees with the information, <strong>FALSE</strong> if the statement contradicts the information, <strong>NOT GIVEN</strong> if there is no information on this.',
                'questions' => [
                    [
                        'q' => 33,
                        'text' => 'In the forthcoming games, all the participants will be blindfolded.',
                    ],
                    [
                        'q' => 34,
                        'text' => 'Gareyev has won competitions in BASE jumping.',
                    ],
                    [
                        'q' => 35,
                        'text' => 'UCLA is the first university to carry out research into blindfold chess players.',
                    ],
                    [
                        'q' => 36,
                        'text' => 'Good chess players are likely to be able to play blindfold chess.',
                    ],
                ],
            ],
            [
                'type' => 'form_fill',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 37–40.</strong> Complete the summary below. Choose <strong>ONE WORD ONLY</strong> from the passage for each answer.',
                'form_title' => 'How the research was carried out',
                'groups' => [
                    [
                        'heading' => null,
                        'rows' => [
                            [
                                'prefix' => 'How the research was carried out - The researchers started by testing Gareyev\'s',
                                'q' => 37,
                                'suffix' => ';',
                            ],
                            [
                                'prefix' => 'for example, he was required to recall a string of',
                                'q' => 38,
                                'suffix' => 'in order and also in reverse order.',
                            ],
                            [
                                'prefix' => 'Although his performance was normal, scans showed an unusual amount of',
                                'q' => 39,
                                'suffix' => 'within the areas of Gareyev\'s brain that are concerned with directing attention.',
                            ],
                            [
                                'prefix' => 'In addition, the scans raised the possibility of unusual strength in the parts of his brain that deal with',
                                'q' => 40,
                                'suffix' => 'input.',
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
$answers = loadTestAnswersMulti($db, $testCode);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Academic Reading Practice Test 4 – EduHub</title>
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
                <li class="breadcrumb-item active">Practice Test 4</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center gap-3">
            <span class="section-badge">Reading · Academic</span>
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
const PAIRS = [[23, 24], [25, 26]];
const PAIR_OF = {}; PAIRS.forEach(p => p.forEach(q => PAIR_OF[q] = p));
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

// One answer: right if it is in the key. For a "choose TWO letters" pair, the two answers may come in either order, but the same letter twice only counts once.
function isCorrect(q, given) {
    q = Number(q);   // keys arrive as strings (for...in, data-q)
    given = (given || '').toLowerCase().trim();
    if (!given) return false;
    const pr = PAIR_OF[q];
    if (pr && pr.indexOf(q) > 0 && (userAnswers[pr[0]] || '').toLowerCase().trim() === given) return false;
    return (CORRECT[q] || []).includes(given);
}

function gradeAnswers() {
    let score = 0;
    for (let q in CORRECT) {
        const given = (userAnswers[q] || '').toLowerCase().trim();
        if (isCorrect(q, given)) score++;
    }
    return score;
}

// Estimated band from the raw score out of 40 (the usual IELTS Academic Reading conversion). An estimate, not an official result.
function toBand(score) {
    if (score >= 39) return '9.0';
    if (score >= 37) return '8.5';
    if (score >= 35) return '8.0';
    if (score >= 33) return '7.5';
    if (score >= 30) return '7.0';
    if (score >= 27) return '6.5';
    if (score >= 23) return '6.0';
    if (score >= 19) return '5.5';
    if (score >= 15) return '5.0';
    if (score >= 13) return '4.5';
    if (score >= 10) return '4.0';
    return '<4.0';
}

function showFeedback() {
    document.querySelectorAll('input[type=text][data-q]').forEach(el => {
        const q      = el.dataset.q;
        const given  = el.value.trim().toLowerCase();
        const correct = CORRECT[q] || [];
        el.classList.remove('correct', 'incorrect');
        el.classList.add(isCorrect(q, given) ? 'correct' : 'incorrect');
        let fb = el.nextElementSibling;
        if (!fb || !fb.classList.contains('feedback-text')) {
            fb = document.createElement('span');
            fb.className = 'feedback-text ms-1';
            el.after(fb);
        }
        fb.className  = isCorrect(q, given) ? 'feedback-correct ms-1' : 'feedback-incorrect ms-1';
        fb.textContent = isCorrect(q, given) ? '✓' : `✗ ${correct[0]}`;
    });
    document.querySelectorAll('select[data-q]').forEach(el => {
        const q      = el.dataset.q;
        const given  = el.value.trim().toLowerCase();
        const correct = CORRECT[q] || [];
        el.style.borderColor = isCorrect(q, given) ? '#198754' : '#dc3545';
        el.style.background  = isCorrect(q, given) ? '#d1e7dd' : '#f8d7da';
        let fb = el.nextElementSibling;
        if (!fb || !fb.classList.contains('feedback-text')) {
            fb = document.createElement('span');
            fb.className = 'feedback-text ms-1';
            el.after(fb);
        }
        fb.className  = isCorrect(q, given) ? 'feedback-correct ms-1' : 'feedback-incorrect ms-1';
        fb.textContent = isCorrect(q, given) ? '✓' : `✗ ${correct[0].toUpperCase()}`;
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