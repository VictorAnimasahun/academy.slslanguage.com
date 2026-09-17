<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}
require_once INCLUDES_PATH . '/course_lock.php';
// Genuinely Academic content (not the GT stand-in used elsewhere) — built
// 2026-09-17 from 7 official IELTS.org "Academic Reading sample task" PDFs,
// one per question type. Two pairs share a source passage and combine into
// one continuous question set each (Government Policy/Environment: Matching
// Headings + Multiple Choice; Dung Beetles: Diagram Label + Table
// Completion); the other three (Motor Car, Cigarette Smoke, Rockets) are
// standalone excerpts. All passage/question/answer text is verbatim from
// the official material; only question numbers were renumbered 1-30
// sequentially across the 5 parts (each source PDF used its own original
// numbering in isolation). This is a compiled sample set, not one
// continuous 60-minute/3-passage Cambridge-style exam — titled accordingly.
require_course_enrollment([16, 17], 'this IELTS Academic Reading practice test');

$testCode  = 'IELTS_PT_R_ACA_001';
$timeLimit = 40 * 60;

$parts = [
    1 => [
        'title'       => 'Part 1',
        'description' => 'Read the text below and answer Questions 1–8.',
        'q_range'     => [1, 8],
        'type'        => 'mixed',
        'sections'    => [
            [
                'type'             => 'section_matching',
                'passage_title'    => 'Government Policy and the Environment',
                'passage_subtitle' => null,
                'passage'          => '<p><strong>A</strong> &nbsp; The role of governments in environmental management is difficult but inescapable. Sometimes, the state tries to manage the resources it owns, and does so badly. Often, however, governments act in an even more harmful way. They actually subsidise the exploitation and consumption of natural resources. A whole range of policies, from farm-price support to protection for coal-mining, do environmental damage and (often) make no economic sense. Scrapping them offers a two-fold bonus: a cleaner environment and a more efficient economy. Growth and environmentalism can actually go hand in hand, if politicians have the courage to confront the vested interest that subsidies create.</p>
<p><strong>B</strong> &nbsp; No activity affects more of the earth\'s surface than farming. It shapes a third of the planet\'s land area, not counting Antarctica, and the proportion is rising. World food output per head has risen by 4 per cent between the 1970s and 1980s mainly as a result of increases in yields from land already in cultivation, but also because more land has been brought under the plough. Higher yields have been achieved by increased irrigation, better crop breeding, and a doubling in the use of pesticides and chemical fertilisers in the 1970s and 1980s.</p>
<p><strong>C</strong> &nbsp; All these activities may have damaging environmental impacts. For example, land clearing for agriculture is the largest single cause of deforestation; chemical fertilisers and pesticides may contaminate water supplies; more intensive farming and the abandonment of fallow periods tend to exacerbate soil erosion; and the spread of monoculture and use of high-yielding varieties of crops have been accompanied by the disappearance of old varieties of food plants which might have provided some insurance against pests or diseases in future. Soil erosion threatens the productivity of land in both rich and poor countries. The United States, where the most careful measurements have been done, discovered in 1982 that about one-fifth of its farmland was losing topsoil at a rate likely to diminish the soil\'s productivity. The country subsequently embarked upon a program to convert 11 per cent of its cropped land to meadow or forest. Topsoil in India and China is vanishing much faster than in America.</p>
<p><strong>D</strong> &nbsp; Government policies have frequently compounded the environmental damage that farming can cause. In the rich countries, subsidies for growing crops and price supports for farm output drive up the price of land. The annual value of these subsidies is immense: about $250 billion, or more than all World Bank lending in the 1980s. To increase the output of crops per acre, a farmer\'s easiest option is to use more of the most readily available inputs: fertilisers and pesticides. Fertiliser use doubled in Denmark in the period 1960-1985 and increased in The Netherlands by 150 per cent. The quantity of pesticides applied has risen too: by 69 per cent in 1975-1984 in Denmark, for example, with a rise of 115 per cent in the frequency of application in the three years from 1981.</p>
<p>In the late 1980s and early 1990s some efforts were made to reduce farm subsidies. The most dramatic example was that of New Zealand, which scrapped most farm support in 1984. A study of the environmental effects, conducted in 1993, found that the end of fertiliser subsidies had been followed by a fall in fertiliser use (a fall compounded by the decline in world commodity prices, which cut farm incomes). The removal of subsidies also stopped land-clearing and over-stocking, which in the past had been the principal causes of erosion. Farms began to diversify. The one kind of subsidy whose removal appeared to have been bad for the environment was the subsidy to manage soil erosion.</p>
<p>In less enlightened countries, and in the European Union, the trend has been to reduce rather than eliminate subsidies, and to introduce new payments to encourage farmers to treat their land in environmentally friendlier ways, or to leave it fallow. It may sound strange but such payments need to be higher than the existing incentives for farmers to grow food crops. Farmers, however, dislike being paid to do nothing. In several countries they have become interested in the possibility of using fuel produced from crop residues either as a replacement for petrol (as ethanol) or as fuel for power stations (as biomass). Such fuels produce far less carbon dioxide than coal or oil, and absorb carbon dioxide as they grow. They are therefore less likely to contribute to the greenhouse effect. But they are rarely competitive with fossil fuels unless subsidised - and growing them does no less environmental harm than other crops.</p>
<p><strong>E</strong> &nbsp; In poor countries, governments aggravate other sorts of damage. Subsidies for pesticides and artificial fertilisers encourage farmers to use greater quantities than are needed to get the highest economic crop yield. A study by the International Rice Research Institute of pesticide use by farmers in South East Asia found that, with pest-resistant varieties of rice, even moderate applications of pesticide frequently cost farmers more than they saved. Such waste puts farmers on a chemical treadmill: bugs and weeds become resistant to poisons, so next year\'s poisons must be more lethal. One cost is to human health. Every year some 10,000 people die from pesticide poisoning, almost all of them in the developing countries, and another 400,000 become seriously ill. As for artificial fertilisers, their use world-wide increased by 40 per cent per unit of farmed land between the mid 1970s and late 1980s, mostly in the developing countries. Overuse of fertilisers may cause farmers to stop rotating crops or leaving their land fallow. That, in turn, may make soil erosion worse.</p>
<p><strong>F</strong> &nbsp; A result of the Uruguay Round of world trade negotiations is likely to be a reduction of 36 per cent in the average levels of farm subsidies paid by the rich countries in 1986-1990. Some of the world\'s food production will move from Western Europe to regions where subsidies are lower or non-existent, such as the former communist countries and parts of the developing world. Some environmentalists worry about this outcome. It will undoubtedly mean more pressure to convert natural habitat into farmland. But it will also have many desirable environmental effects. The intensity of farming in the rich world should decline, and the use of chemical inputs will diminish. Crops are more likely to be grown in the environments to which they are naturally suited. And more farmers in poor countries will have the money and the incentive to manage their land in ways that are sustainable in the long run. That is important. To feed an increasingly hungry world, farmers need every incentive to use their soil and water effectively and efficiently.</p>',
                'instructions' => 'The reading passage has six sections, <strong>A–F</strong>. Choose the correct heading for sections <strong>A, B, C, D</strong> and <strong>F</strong> from the list of headings below. Write the correct number <strong>i–ix</strong>. <em>Section E has been done for you as an example: it is </em><strong>vi</strong><em> — The effects of government policy in poor countries.</em>',
                'headings_list' => [
                    'i'   => 'The probable effects of the new international trade agreement',
                    'ii'  => 'The environmental impact of modern farming',
                    'iii' => 'Farming and soil erosion',
                    'iv'  => 'The effects of government policy in rich countries',
                    'v'   => 'Governments and management of the environment',
                    'vi'  => 'The effects of government policy in poor countries',
                    'vii' => 'Farming and food output',
                    'viii'=> 'The effects of government policy on food output',
                    'ix'  => 'The new prospects for world trade',
                ],
                'options'      => ['i', 'ii', 'iii', 'iv', 'v', 'vi', 'vii', 'viii', 'ix'],
                'questions'    => [
                    ['q' => 1, 'text' => 'Section A'],
                    ['q' => 2, 'text' => 'Section B'],
                    ['q' => 3, 'text' => 'Section C'],
                    ['q' => 4, 'text' => 'Section D'],
                    ['q' => 5, 'text' => 'Section F'],
                ],
            ],
            [
                'type'         => 'passage_mcq',
                'passage_title'=> null,
                'passage'      => null,
                'instructions' => 'Choose the correct letter, <strong>A, B, C</strong> or <strong>D</strong>.',
                'questions'    => [
                    [
                        'q'    => 6,
                        'text' => 'Research completed in 1982 found that in the United States soil erosion',
                        'options' => [
                            'A' => 'reduced the productivity of farmland by 20 per cent.',
                            'B' => 'was almost as severe as in India and China.',
                            'C' => 'was causing significant damage to 20 per cent of farmland.',
                            'D' => 'could be reduced by converting cultivated land to meadow or forest.',
                        ],
                    ],
                    [
                        'q'    => 7,
                        'text' => 'By the mid-1980s, farmers in Denmark',
                        'options' => [
                            'A' => 'used 50 per cent less fertiliser than Dutch farmers.',
                            'B' => 'used twice as much fertiliser as they had in 1960.',
                            'C' => 'applied fertiliser much more frequently than in 1960.',
                            'D' => 'more than doubled the amount of pesticide they used in just 3 years.',
                        ],
                    ],
                    [
                        'q'    => 8,
                        'text' => 'Which one of the following increased in New Zealand after 1984?',
                        'options' => [
                            'A' => 'farm incomes',
                            'B' => 'use of fertiliser',
                            'C' => 'over-stocking',
                            'D' => 'farm diversification',
                        ],
                    ],
                ],
            ],
        ],
    ],
    2 => [
        'title'       => 'Part 2',
        'description' => 'Read the text below and answer Questions 9–14.',
        'q_range'     => [9, 14],
        'type'        => 'mixed',
        'sections'    => [
            [
                'type'             => 'section_matching',
                'passage_title'    => 'The Motor Car',
                'passage_subtitle' => null,
                'passage'          => '<p><strong>A</strong> &nbsp; There are now over 700 million motor vehicles in the world - and the number is rising by more than 40 million each year. The average distance driven by car users is growing too - from 8km a day per person in western Europe in 1965 to 25 km a day in 1995. This dependence on motor vehicles has given rise to major problems, including environmental pollution, depletion of oil resources, traffic congestion and safety.</p>
<p><strong>B</strong> &nbsp; While emissions from new cars are far less harmful than they used to be, city streets and motorways are becoming more crowded than ever, often with older trucks, buses and taxis which emit excessive levels of smoke and fumes. This concentration of vehicles makes air quality in urban areas unpleasant and sometimes dangerous to breathe. Even Moscow has joined the list of capitals afflicted by congestion and traffic fumes. In Mexico City, vehicle pollution is a major health hazard.</p>
<p><strong>C</strong> &nbsp; Until a hundred years ago, most journeys were in the 20km range, the distance conveniently accessible by horse. Heavy freight could only be carried by water or rail. Invention of the motor vehicle brought personal mobility to the masses and made rapid freight delivery possible over a much wider area. In the United Kingdom, about 90 per cent of inland freight is carried by road. The world cannot revert to the horse-drawn wagon. Can it avoid being locked into congested and polluting ways of transporting people and goods?</p>
<p><strong>D</strong> &nbsp; In Europe most cities are still designed for the old modes of transport. Adaptation to the motor car has involved adding ring roads, one-way systems and parking lots. In the United States, more land is assigned to car use than to housing. Urban sprawl means that life without a car is next to impossible. Mass use of motor vehicles has also killed or injured millions of people. Other social effects have been blamed on the car such as alienation and aggressive human behaviour.</p>
<p><strong>E</strong> &nbsp; A 1993 study by the European Federation for Transport and Environment found that car transport is seven times as costly as rail travel in terms of the external social costs it entails - congestion, accidents, pollution, loss of cropland and natural habitats, depletion of oil resources, and so on. Yet cars easily surpass trains or buses as a flexible and convenient mode of personal transport. It is unrealistic to expect people to give up private cars in favour of mass transit.</p>
<p><strong>F</strong> &nbsp; Technical solutions can reduce the pollution problem and increase the fuelled efficiency of engines. But fuel consumption and exhaust emissions depend on which cars are preferred by customers and how they are driven. Many people buy larger cars than they need for daily purposes or waste fuel by driving aggressively. Besides, global car use is increasing at a faster rate than the improvement in emissions and fuel efficiency which technology is now making possible.</p>
<p><strong>G</strong> &nbsp; Some argue that the only long-term solution is to design cities and neighbourhoods so that car journeys are not necessary - all essential services being located within walking distance or easily accessible by public transport. Not only would this save energy and cut carbon dioxide emissions, it would also enhance the quality of community life, putting the emphasis on people instead of cars. Good local government is already bringing this about in some places. But few democratic communities are blessed with the vision – and the capital – to make such profound changes in modern lifestyles.</p>
<p><strong>H</strong> &nbsp; A more likely scenario seems to be a combination of mass transit systems for travel into and around cities, with small \'low emission\' cars for urban use and larger hybrid or lean burn cars for use elsewhere. Electronically tolled highways might be used to ensure that drivers pay charges geared to actual road use. Better integration of transport systems is also highly desirable - and made more feasible by modern computers. But these are solutions for countries which can afford them. In most developing countries, old cars and old technologies continue to predominate.</p>',
                'instructions' => 'The reading passage has eight paragraphs, <strong>A–H</strong>. Which paragraph contains the following information? Write the correct letter, <strong>A–H</strong>. <em>You may use any letter more than once.</em>',
                'options'      => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'],
                'questions'    => [
                    ['q' => 9,  'text' => 'a comparison of past and present transportation methods'],
                    ['q' => 10, 'text' => 'how driving habits contribute to road problems'],
                    ['q' => 11, 'text' => 'the relative merits of cars and public transport'],
                    ['q' => 12, 'text' => 'the writer\'s prediction on future solutions'],
                    ['q' => 13, 'text' => 'the increasing use of motor vehicles'],
                    ['q' => 14, 'text' => 'the impact of the car on city development'],
                ],
            ],
        ],
    ],
    3 => [
        'title'       => 'Part 3',
        'description' => 'Read the text below and answer Questions 15–18.',
        'q_range'     => [15, 18],
        'type'        => 'mixed',
        'sections'    => [
            [
                'type'             => 'true_false_ng',
                'labels'           => 'yn',
                'passage_title'    => 'The Risks of Cigarette Smoke',
                'passage_subtitle' => null,
                'passage'          => '<p>Discovered in the early 1800s and named \'nicotianine\', the oily essence now called nicotine is the main active ingredient of tobacco. Nicotine, however, is only a small component of cigarette smoke, which contains more than 4,700 chemical compounds, including 43 cancer-causing substances. In recent times, scientific research has been providing evidence that years of cigarette smoking vastly increases the risk of developing fatal medical conditions.</p>
<p>In addition to being responsible for more than 85 per cent of lung cancers, smoking is associated with cancers of, amongst others, the mouth, stomach and kidneys, and is thought to cause about 14 per cent of leukemia and cervical cancers. In 1990, smoking caused more than 84,000 deaths, mainly resulting from such problems as pneumonia, bronchitis and influenza. Smoking, it is believed, is responsible for 30 per cent of all deaths from cancer and clearly represents the most important preventable cause of cancer in countries like the United States today.</p>
<p>Passive smoking, the breathing in of the side-stream smoke from the burning of tobacco between puffs or of the smoke exhaled by a smoker, also causes a serious health risk. A report published in 1992 by the US Environmental Protection Agency (EPA) emphasized the health dangers, especially from side-stream smoke. This type of smoke contains more smaller particles and is therefore more likely to be deposited deep in the lungs. On the basis of this report, the EPA has classified environmental tobacco smoke in the highest risk category for causing cancer.</p>
<p>As an illustration of the health risks, in the case of a married couple where one partner is a smoker and one a non-smoker, the latter is believed to have a 30 per cent higher risk of death from heart disease because of passive smoking. The risk of lung cancer also increases over the years of exposure and the figure jumps to 80 per cent if the spouse has been smoking four packs a day for 20 years. It has been calculated that 17 per cent of cases of lung cancer can be attributed to high levels of exposure to second-hand tobacco smoke during childhood and adolescence.</p>
<p>A more recent study by researchers at the University of California at San Francisco (UCSF) has shown that second-hand cigarette smoke does more harm to non-smokers than to smokers. Leaving aside the philosophical question of whether anyone should have to breathe someone else\'s cigarette smoke, the report suggests that the smoke experienced by many people in their daily lives is enough to produce substantial adverse effects on a person\'s heart and lungs.</p>
<p>The report, published in the Journal of the American Medical Association (AMA), was based on the researchers\' own earlier research but also includes a review of studies over the past few years. The American Medical Association represents about half of all US doctors and is a strong opponent of smoking. The study suggests that people who smoke cigarettes are continually damaging their cardiovascular system, which adapts in order to compensate for the effects of smoking. It further states that people who do not smoke do not have the benefit of their system adapting to the smoke inhalation. Consequently, the effects of passive smoking are far greater on non-smokers than on smokers.</p>
<p>This report emphasizes that cancer is not caused by a single element in cigarette smoke; harmful effects to health are caused by many components. Carbon monoxide, for example, competes with oxygen in red blood cells and interferes with the blood\'s ability to deliver life-giving oxygen to the heart. Nicotine and other toxins in cigarette smoke activate small blood cells called platelets, which increases the likelihood of blood clots, thereby affecting blood circulation throughout the body.</p>
<p>The researchers criticize the practice of some scientific consultants who work with the tobacco industry for assuming that cigarette smoke has the same impact on smokers as it does on non-smokers. They argue that those scientists are underestimating the damage done by passive smoking and, in support of their recent findings, cite some previous research which points to passive smoking as the cause for between 30,000 and 60,000 deaths from heart attacks each year in the United States. This means that passive smoking is the third most preventable cause of death after active smoking and alcohol-related diseases.</p>
<p>The study argues that the type of action needed against passive smoking should be similar to that being taken against illegal drugs and AIDS (SIDA). The UCSF researchers maintain that the simplest and most cost-effective action is to establish smoke-free work places, schools and public places.</p>',
                'instructions' => 'Do the following statements reflect the claims of the writer in the reading passage? Write <strong>YES</strong> if the statement reflects the claims of the writer, <strong>NO</strong> if the statement contradicts the claims of the writer, or <strong>NOT GIVEN</strong> if it is impossible to say what the writer thinks about this.',
                'questions'    => [
                    ['q' => 15, 'text' => 'Thirty per cent of deaths in the United States are caused by smoking-related diseases.'],
                    ['q' => 16, 'text' => 'If one partner in a marriage smokes, the other is likely to take up smoking.'],
                    ['q' => 17, 'text' => 'Teenagers whose parents smoke are at risk of getting lung cancer at some time during their lives.'],
                    ['q' => 18, 'text' => 'Opponents of smoking financed the UCSF study.'],
                ],
            ],
        ],
    ],
    4 => [
        'title'       => 'Part 4',
        'description' => 'Read the text below and answer Questions 19–22.',
        'q_range'     => [19, 22],
        'type'        => 'mixed',
        'sections'    => [
            [
                'type'             => 'section_matching',
                'passage_title'    => 'The History of Rockets',
                'passage_subtitle' => '[This is an extract from a passage on the development of rockets. The text preceding this extract explored the slow development of the rocket and explained the principle of propulsion.]',
                'passage'          => '<p>The invention of rockets is linked inextricably with the invention of \'black powder\'. Most historians of technology credit the Chinese with its discovery. They base their belief on studies of Chinese writings or on the notebooks of early Europeans who settled in or made long visits to China to study its history and civilisation. It is probable that, some time in the tenth century, black powder was first compounded from its basic ingredients of saltpetre, charcoal and sulphur. But this does not mean that it was immediately used to propel rockets. By the thirteenth century, powder-propelled fire arrows had become rather common. The Chinese relied on this type of technological development to produce incendiary projectiles of many sorts, explosive grenades and possibly cannons to repel their enemies. One such weapon was the \'basket of fire\' or, as directly translated from Chinese, the \'arrows like flying leopards\'. The 0.7 metre-long arrows, each with a long tube of gunpowder attached near the point of each arrow, could be fired from a long, octagonal-shaped basket at the same time and had a range of 400 paces. Another weapon was the \'arrow as a flying sabre\', which could be fired from crossbows. The rocket, placed in a similar position to other rocket-propelled arrows, was designed to increase the range. A small iron weight was attached to the 1.5m bamboo shaft, just below the feathers, to increase the arrow\'s stability by moving the centre of gravity to a position below the rocket. At a similar time, the Arabs had developed the \'egg which moves and burns\'. This \'egg\' was apparently full of gunpowder and stabilised by a 1.5m tail. It was fired using two rockets attached to either side of this tail.</p>
<p>It was not until the eighteenth century that Europe became seriously interested in the possibilities of using the rocket itself as a weapon of war and not just to propel other weapons. Prior to this, rockets were used only in pyrotechnic displays. The incentive for the more aggressive use of rockets came not from within the European continent but from far-away India, whose leaders had built up a corps of rocketeers and used rockets successfully against the British in the late eighteenth century. The Indian rockets used against the British were described by a British Captain serving in India as \'an iron envelope about 200 millimetres long and 40 millimetres in diameter with sharp points at the top and a 3m-long bamboo guiding stick\'. In the early nineteenth century the British began to experiment with incendiary barrage rockets. The British rocket differed from the Indian version in that it was completely encased in a stout, iron cylinder, terminating in a conical head, measuring one metre in diameter and having a stick almost five metres long and constructed in such a way that it could be firmly attached to the body of the rocket. The Americans developed a rocket, complete with its own launcher, to use against the Mexicans in the mid-nineteenth century. A long cylindrical tube was propped up by two sticks and fastened to the top of the launcher, thereby allowing the rockets to be inserted and lit from the other end. However, the results were sometimes not that impressive as the behaviour of the rockets in flight was less than predictable.</p>',
                'instructions' => 'Look at the following items and the list of groups below. Match each item with the group which first invented or used them. Write the correct letter, <strong>A–E</strong>. <em>You may use any letter more than once.</em>',
                'options'      => ['A', 'B', 'C', 'D', 'E'],
                'options_key'  => ['A' => 'the Chinese', 'B' => 'the Indians', 'C' => 'the British', 'D' => 'the Arabs', 'E' => 'the Americans'],
                'questions'    => [
                    ['q' => 19, 'text' => 'black powder'],
                    ['q' => 20, 'text' => 'rocket-propelled arrows for fighting'],
                    ['q' => 21, 'text' => 'rockets as war weapons'],
                    ['q' => 22, 'text' => 'the rocket launcher'],
                ],
            ],
        ],
    ],
    5 => [
        'title'       => 'Part 5',
        'description' => 'Read the text below and answer Questions 23–30.',
        'q_range'     => [23, 30],
        'type'        => 'mixed',
        'sections'    => [
            [
                'type'             => 'form_fill',
                'passage_title'    => 'Dung Beetles',
                'passage_subtitle' => '[This is an extract from a passage on dung beetles. The text preceding this extract gave some background facts about dung beetles, and went on to describe a decision to introduce non-native varieties to Australia.]',
                'passage'          => '<p>Introducing dung beetles into a pasture is a simple process: approximately 1,500 beetles are released, a handful at a time, into fresh cow pats in the cow pasture. The beetles immediately disappear beneath the pats digging and tunnelling and, if they successfully adapt to their new environment, soon become a permanent, self-sustaining part of the local ecology. In time they multiply and within three or four years the benefits to the pasture are obvious.</p>
<p>Dung beetles work from the inside of the pat so they are sheltered from predators such as birds and foxes. Most species burrow into the soil and bury dung in tunnels directly underneath the pats, which are hollowed out from within. Some large species originating from France excavate tunnels to a depth of approximately 30 cm below the dung pat. These beetles make sausage-shaped brood chambers along the tunnels. The shallowest tunnels belong to a much smaller Spanish species that buries dung in chambers that hang like fruit from the branches of a pear tree. South African beetles dig narrow tunnels of approximately 20 cm below the surface of the pat. Some surface-dwelling beetles, including a South African species, cut perfectly-shaped balls from the pat, which are rolled away and attached to the bases of plants.</p>
<p>For maximum dung burial in spring, summer and autumn, farmers require a variety of species with overlapping periods of activity. In the cooler environments of the state of Victoria, the large French species (2.5 cms long), is matched with smaller (half this size), temperate-climate Spanish species. The former are slow to recover from the winter cold and produce only one or two generations of offspring from late spring until autumn. The latter, which multiply rapidly in early spring, produce two to five generations annually. The South African ball-rolling species, being a sub-tropical beetle, prefers the climate of northern and coastal New South Wales where it commonly works with the South African tunneling species. In warmer climates, many species are active for longer periods of the year.</p>
<p class="text-muted small"><em>Glossary — dung: the droppings or excreta of animals; cow pats: droppings of cows.</em></p>',
                'image'        => 'dung_beetle_tunnels.png',
                'image_alt'    => 'Cross-section diagram of three dung beetle tunnel systems of different depths below a cow pat',
                'instructions' => 'Label the tunnels on the diagram below. Choose <strong>ONE WORD ONLY</strong> from the passage for each answer.',
                'form_title'   => null,
                'groups'       => [
                    [
                        'heading' => null,
                        'rows'    => [
                            ['prefix' => 'Diagram label', 'q' => 23, 'suffix' => '(deepest tunnel, reaching about 30 cm)'],
                            ['prefix' => 'Diagram label', 'q' => 24, 'suffix' => '(middle-depth tunnel, reaching about 20 cm)'],
                            ['prefix' => 'Diagram label', 'q' => 25, 'suffix' => '(shallowest tunnel, near the surface)'],
                        ],
                    ],
                ],
            ],
            [
                'type'         => 'table',
                'instructions' => 'Complete the table below. Choose <strong>NO MORE THAN THREE WORDS</strong> from the passage for each answer.',
                'columns'      => ['Species', 'Size', 'Preferred climate', 'Complementary species', 'Start of active period', 'Generations per year'],
                'rows'         => [
                    ['French', '2.5 cm', 'cool', 'Spanish', 'late spring', '1 - 2'],
                    ['Spanish', '1.25 cm', ['q' => 26], '', ['q' => 27], ['q' => 28]],
                    ['South African ball roller', '', ['q' => 29], ['q' => 30], '', ''],
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
    <title>IELTS Academic Reading Practice Test 1 – EduHub</title>
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
                <li class="breadcrumb-item active">IELTS Academic Reading – Practice 1</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center gap-3">
            <span class="section-badge">Reading · Academic</span>
            <span class="text-muted small">30 Questions · 40 min</span>
            <span id="timerDisplay" class="timer-display">40:00</span>
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

// Real IELTS band thresholds are calibrated for the full 40-question exam —
// this test has 30 (5 compiled sample passages, not a continuous 3-passage
// exam), so the raw thresholds are scaled by 30/40 to keep the same
// proportional difficulty instead of capping a perfect score at band 7.0.
function toBand(score) {
    if (score >= 29) return '9.0';
    if (score >= 28) return '8.5';
    if (score >= 26) return '8.0';
    if (score >= 24) return '7.5';
    if (score >= 23) return '7.0';
    if (score >= 20) return '6.5';
    if (score >= 17) return '6.0';
    if (score >= 14) return '5.5';
    if (score >= 12) return '5.0';
    if (score >= 10) return '4.5';
    if (score >= 8)  return '4.0';
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
    if ($mode === 'passage'): ?>
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