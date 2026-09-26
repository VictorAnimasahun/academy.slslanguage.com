<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}
require_once INCLUDES_PATH . '/course_lock.php';
// Cambridge IELTS 17 Academic, Test 3, Reading: three passages, 40 questions, 60 minutes (a full Academic Reading test).
// Source: documentation/test_bank/cambridge_ielts17_academic/test3.json. Generated from the bank so the page and the answer key (migration 135) agree.
// Open to students enrolled in IELTS_Aca_2Mo, IELTS_Aca_3Mo (staff, admins and testers always pass); course ids are looked up by folder, never typed.
require_course_enrollment(course_ids_for_folders(['IELTS_Aca_2Mo', 'IELTS_Aca_3Mo']), 'this IELTS Academic Reading practice test');

$testCode  = 'IELTS_PT_R_ACA_C17T3';
$timeLimit = 60 * 60;

$parts = [
    1 => [
        'title' => 'Part 1',
        'description' => 'Read the text below and answer Questions 1–13. You should spend about 20 minutes on this part.',
        'q_range' => [1, 13],
        'type' => 'mixed',
        'sections' => [
            [
                'type' => 'form_fill',
                'passage_title' => 'The thylacine',
                'passage_subtitle' => null,
                'passage' => '<p>The extinct thylacine, also known as the Tasmanian tiger, was a marsupial* that bore a superficial resemblance to a dog. Its most distinguishing feature was the 13–19 dark brown stripes over its back, beginning at the rear of the body and extending onto the tail. The thylacine\'s average nose-to-tail length for adult males was 162.6 cm, compared to 153.7 cm for females.</p>
<p>The thylacine appeared to occupy most types of terrain except dense rainforest, with open eucalyptus forest thought to be its prime habitat. In terms of feeding, it was exclusively carnivorous, and its stomach was muscular with an ability to distend so that it could eat large amounts of food at one time, probably an adaptation to compensate for long periods when hunting was unsuccessful and food scarce. The thylacine was not a fast runner and probably caught its prey by exhausting it during a long pursuit. During long-distance chases, thylacines were likely to have relied more on scent than any other sense. They emerged to hunt during the evening, night and early morning and tended to retreat to the hills and forest for shelter during the day. Despite the common name \'tiger\', the thylacine had a shy, nervous temperament. Although mainly nocturnal, it was sighted moving during the day and some individuals were even recorded basking in the sun.</p>
<p>The thylacine had an extended breeding season from winter to spring, with indications that some breeding took place throughout the year. The thylacine, like all marsupials, was tiny and hairless when born. Newborns crawled into the pouch on the belly of their mother, and attached themselves to one of the four teats, remaining there for up to three months. When old enough to leave the pouch, the young stayed in a lair such as a deep rocky cave, well-hidden nest or hollow log, whilst the mother hunted.</p>
<p>Approximately 4,000 years ago, the thylacine was widespread throughout New Guinea and most of mainland Australia, as well as the island of Tasmania. The most recent, well-dated occurrence of a thylacine on the mainland is a carbon-dated fossil from Murray Cave in Western Australia, which is around 3,100 years old. Its extinction coincided closely with the arrival of wild dogs called dingoes in Australia and a similar predator in New Guinea. Dingoes never reached Tasmania, and most scientists see this as the main reason for the thylacine\'s survival there.</p>
<p>The dramatic decline of the thylacine in Tasmania, which began in the 1830s and continued for a century, is generally attributed to the relentless efforts of sheep farmers and bounty hunters** with shotguns. While this determined campaign undoubtedly played a large part, it is likely that various other factors also contributed to the decline and eventual extinction of the species. These include competition with wild dogs introduced by European settlers, loss of habitat along with the disappearance of prey species, and a distemper-like disease which may also have affected the thylacine.</p>
<p>There was only one successful attempt to breed a thylacine in captivity, at Melbourne Zoo in 1899. This was despite the large numbers that went through some zoos, particularly London Zoo and Tasmania\'s Hobart Zoo. The famous naturalist John Gould foresaw the thylacine\'s demise when he published his Mammals of Australia between 1848 and 1863, writing, \'The numbers of this singular animal will speedily diminish, extermination will have its full sway, and it will then, like the wolf of England and Scotland, be recorded as an animal of the past.\'</p>
<p>However, there seems to have been little public pressure to preserve the thylacine, nor was much concern expressed by scientists at the decline of this species in the decades that followed. A notable exception was T.T. Flynn, Professor of Biology at the University of Tasmania. In 1914, he was sufficiently concerned about the scarcity of the thylacine to suggest that some should be captured and placed on a small island. But it was not until 1929, with the species on the very edge of extinction, that Tasmania\'s Animals and Birds Protection Board passed a motion protecting thylacines only for the month of December, which was thought to be their prime breeding season. The last known wild thylacine to be killed was shot by a farmer in the north-east of Tasmania in 1930, leaving just captive specimens. Official protection of the species by the Tasmanian government was introduced in July 1936, 59 days before the last known individual died in Hobart Zoo on 7th September, 1936.</p>
<p>There have been numerous expeditions and searches for the thylacine over the years, none of which has produced definitive evidence that thylacines still exist. The species was declared extinct by the Tasmanian government in 1986.</p>
<p class="text-muted small"><em>* marsupial: a mammal, such as a kangaroo, whose young are born incompletely developed and are typically carried and suckled in a pouch on the mother\'s belly<br>** bounty hunters: people who are paid a reward for killing a wild animal</em></p>',
                'instructions' => '<strong>Questions 1–5.</strong> Complete the notes below. Choose <strong>ONE WORD ONLY</strong> from the passage for each answer.',
                'form_title' => 'The thylacine',
                'groups' => [
                    [
                        'heading' => null,
                        'rows' => [
                            [
                                'prefix' => 'The thylacine - Appearance and behaviour: ate an entirely',
                                'q' => 1,
                                'suffix' => 'diet',
                            ],
                            [
                                'prefix' => 'probably depended mainly on',
                                'q' => 2,
                                'suffix' => 'when hunting',
                            ],
                            [
                                'prefix' => 'young spent first months of life inside its mother\'s',
                                'q' => 3,
                                'suffix' => '',
                            ],
                            [
                                'prefix' => 'Decline and extinction: last evidence in mainland Australia is a 3,100-year-old',
                                'q' => 4,
                                'suffix' => '',
                            ],
                            [
                                'prefix' => 'reduction in',
                                'q' => 5,
                                'suffix' => 'and available sources of food were partly responsible for decline in Tasmania',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'type' => 'true_false_ng',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 6–13.</strong> Do the following statements agree with the information given in Reading Passage 1? Write <strong>TRUE</strong> if the statement agrees with the information, <strong>FALSE</strong> if the statement contradicts the information, <strong>NOT GIVEN</strong> if there is no information on this.',
                'questions' => [
                    [
                        'q' => 6,
                        'text' => 'Significant numbers of thylacines were killed by humans from the 1830s onwards.',
                    ],
                    [
                        'q' => 7,
                        'text' => 'Several thylacines were born in zoos during the late 1800s.',
                    ],
                    [
                        'q' => 8,
                        'text' => 'John Gould\'s prediction about the thylacine surprised some biologists.',
                    ],
                    [
                        'q' => 9,
                        'text' => 'In the early 1900s, many scientists became worried about the possible extinction of the thylacine.',
                    ],
                    [
                        'q' => 10,
                        'text' => 'T. T. Flynn\'s proposal to rehome captive thylacines on an island proved to be impractical.',
                    ],
                    [
                        'q' => 11,
                        'text' => 'There were still reasonable numbers of thylacines in existence when a piece of legislation protecting the species during their breeding season was passed.',
                    ],
                    [
                        'q' => 12,
                        'text' => 'From 1930 to 1936, the only known living thylacines were all in captivity.',
                    ],
                    [
                        'q' => 13,
                        'text' => 'Attempts to find living thylacines are now rarely made.',
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
                'passage_title' => 'Palm oil',
                'passage_subtitle' => null,
                'passage' => '<p><strong>A</strong> &nbsp; Palm oil is an edible oil derived from the fruit of the African oil palm tree, and is currently the most consumed vegetable oil in the world. It\'s almost certainly in the soap we wash with in the morning, the sandwich we have for lunch, and the biscuits we snack on during the day. Why is palm oil so attractive for manufacturers? Primarily because its unique properties – such as remaining solid at room temperature – make it an ideal ingredient for long-term preservation, allowing many packaged foods on supermarket shelves to have \'best before\' dates of months, even years, into the future.</p>
<p><strong>B</strong> &nbsp; Many farmers have seized the opportunity to maximise the planting of oil palm trees. Between 1990 and 2012, the global land area devoted to growing oil palm trees grew from 6 to 17 million hectares, now accounting for around ten percent of total cropland in the entire world. From a mere two million tonnes of palm oil being produced annually globally 50 years ago, there are now around 60 million tonnes produced every single year, a figure looking likely to double or even triple by the middle of the century.</p>
<p><strong>C</strong> &nbsp; However, there are multiple reasons why conservationists cite the rapid spread of oil palm plantations as a major concern. There are countless news stories of deforestation, habitat destruction and dwindling species populations, all as a direct result of land clearing to establish oil palm tree monoculture on an industrial scale, particularly in Malaysia and Indonesia. Endangered species – most famously the Sumatran orangutan, but also rhinos, elephants, tigers, and numerous other fauna – have suffered from the unstoppable spread of oil palm plantations.</p>
<p><strong>D</strong> &nbsp; \'Palm oil is surely one of the greatest threats to global biodiversity,\' declares Dr Farnon Ellwood of the University of the West of England, Bristol. \'Palm oil is replacing rainforest, and rainforest is where all the species are. That\'s a problem.\' This has led to some radical questions among environmentalists, such as whether consumers should try to boycott palm oil entirely.</p>
<p>Meanwhile Bhavani Shankar, Professor at London\'s School of Oriental and African Studies, argues, \'It\'s easy to say that palm oil is the enemy and we should be against it. It makes for a more dramatic story, and it\'s very intuitive. But given the complexity of the argument, I think a much more nuanced story is closer to the truth.\'</p>
<p><strong>E</strong> &nbsp; One response to the boycott movement has been the argument for the vital role palm oil plays in lifting many millions of people in the developing world out of poverty. Is it desirable to have palm oil boycotted, replaced, eliminated from the global supply chain, given how many low-income people in developing countries depend on it for their livelihoods? How best to strike a utilitarian balance between these competing factors has become a serious bone of contention.</p>
<p><strong>F</strong> &nbsp; Even the deforestation argument isn\'t as straightforward as it seems. Oil palm plantations produce at least four and potentially up to ten times more oil per hectare than soybean, rapeseed, sunflower or other competing oils. That immensely high yield – which is predominantly what makes it so profitable – is potentially also an ecological benefit. If ten times more palm oil can be produced from a patch of land than any competing oil, then ten times more land would need to be cleared in order to produce the same volume of oil from that competitor.</p>
<p>As for the question of carbon emissions, the issue really depends on what oil palm trees are replacing. Crops vary in the degree to which they sequester carbon – in other words, the amount of carbon they capture from the atmosphere and store within the plant. The more carbon a plant sequesters, the more it reduces the effect of climate change. As Shankar explains: \'[Palm oil production] actually sequesters more carbon in some ways than other alternatives. [...] Of course, if you\'re cutting down virgin forest it\'s terrible – that\'s what\'s happening in Indonesia and Malaysia, it\'s been allowed to get out of hand. But if it\'s replacing rice, for example, it might actually sequester more carbon.\'</p>
<p><strong>G</strong> &nbsp; The industry is now regulated by a group called the Roundtable on Sustainable Palm Oil (RSPO), consisting of palm growers, retailers, product manufacturers, and other interested parties. Over the past decade or so, an agreement has gradually been reached regarding standards that producers of palm oil have to meet in order for their product to be regarded as officially \'sustainable\'. The RSPO insists upon no virgin forest clearing, transparency and regular assessment of carbon stocks, among other criteria. Only once these requirements are fully satisfied is the oil allowed to be sold as certified sustainable palm oil (CSPO). Recent figures show that the RSPO now certifies around 12 million tonnes of palm oil annually, equivalent to roughly 21 percent of the world\'s total palm oil production.</p>
<p><strong>H</strong> &nbsp; There is even hope that oil palm plantations might not need to be such sterile monocultures, or \'green deserts\', as Ellwood describes them. New research at Ellwood\'s lab hints at one plant which might make all the difference. The bird\'s nest fern (Asplenium nidus) grows on trees in an epiphytic fashion (meaning it\'s dependent on the tree only for support, not for nutrients), and is native to many tropical regions, where as a keystone species it performs a vital ecological role. Ellwood believes that reintroducing the bird\'s nest fern into oil palm plantations could potentially allow these areas to recover their biodiversity, providing a home for all manner of species, from fungi and bacteria, to invertebrates such as insects, amphibians, reptiles and even mammals.</p>',
                'instructions' => '<strong>Questions 14–20.</strong> Reading Passage 2 has eight sections, <strong>A–H</strong>. Which section contains the following information? Write the correct letter, <strong>A–H</strong>.',
                'options' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'],
                'questions' => [
                    [
                        'q' => 14,
                        'text' => 'examples of a range of potential environmental advantages of oil palm tree cultivation',
                    ],
                    [
                        'q' => 15,
                        'text' => 'description of an organisation which controls the environmental impact of palm oil production',
                    ],
                    [
                        'q' => 16,
                        'text' => 'examples of the widespread global use of palm oil',
                    ],
                    [
                        'q' => 17,
                        'text' => 'reference to a particular species which could benefit the ecosystem of oil palm plantations',
                    ],
                    [
                        'q' => 18,
                        'text' => 'figures illustrating the rapid expansion of the palm oil industry',
                    ],
                    [
                        'q' => 19,
                        'text' => 'an economic justification for not opposing the palm oil industry',
                    ],
                    [
                        'q' => 20,
                        'text' => 'examples of creatures badly affected by the establishment of oil palm plantations',
                    ],
                ],
            ],
            [
                'type' => 'section_matching',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 21 and 22.</strong> Choose <strong>TWO</strong> letters, <strong>A–E</strong>. Which TWO statements are made about the Roundtable on Sustainable Palm Oil (RSPO)?',
                'options_key' => [
                    'A' => 'Its membership has grown steadily over the course of the last decade.',
                    'B' => 'It demands that certified producers be open and honest about their practices.',
                    'C' => 'It took several years to establish its set of criteria for sustainable palm oil certification.',
                    'D' => 'Its regulations regarding sustainability are stricter than those governing other industries.',
                    'E' => 'It was formed at the request of environmentalists concerned about the loss of virgin forests.',
                ],
                'options' => ['A', 'B', 'C', 'D', 'E'],
                'questions' => [
                    [
                        'q' => 21,
                        'text' => 'First answer',
                    ],
                    [
                        'q' => 22,
                        'text' => 'Second answer',
                    ],
                ],
            ],
            [
                'type' => 'form_fill',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 23–26.</strong> Complete the sentences below. Choose <strong>NO MORE THAN TWO WORDS</strong> from the passage for each answer.',
                'form_title' => null,
                'groups' => [
                    [
                        'heading' => null,
                        'rows' => [
                            [
                                'prefix' => 'One advantage of palm oil for manufacturers is that it stays',
                                'q' => 23,
                                'suffix' => 'even when not refrigerated.',
                            ],
                            [
                                'prefix' => 'The',
                                'q' => 24,
                                'suffix' => 'is the best known of the animals suffering habitat loss as a result of the spread of oil palm plantations.',
                            ],
                            [
                                'prefix' => 'As one of its criteria for the certification of sustainable palm oil, the RSPO insists that growers check',
                                'q' => 25,
                                'suffix' => 'on a routine basis.',
                            ],
                            [
                                'prefix' => 'Ellwood and his researchers are looking into whether the bird\'s nest fern could restore',
                                'q' => 26,
                                'suffix' => 'in areas where oil palm trees are grown.',
                            ],
                        ],
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
                'type' => 'passage_mcq',
                'passage_title' => 'Building the Skyline: The Birth and Growth of Manhattan\'s Skyscrapers',
                'passage_subtitle' => 'Katharine L. Shester reviews a book by Jason Barr about the development of New York City',
                'passage' => '<p>In Building the Skyline, Jason Barr takes the reader through a detailed history of New York City. The book combines geology, history, economics, and a lot of data to explain why business clusters developed where they did and how the early decisions of workers and firms shaped the skyline we see today. Building the Skyline is organized into two distinct parts. The first is primarily historical and addresses New York\'s settlement and growth from 1609 to 1900; the second deals primarily with the 20th century and is a compilation of chapters commenting on different aspects of New York\'s urban development. The tone and organization of the book changes somewhat between the first and second parts, as the latter chapters incorporate aspects of Barr\'s related research papers.</p>
<p>Barr begins chapter one by taking the reader on a \'helicopter time-machine\' ride – giving a fascinating account of how the New York landscape in 1609 might have looked from the sky. He then moves on to a subterranean walking tour of the city, indicating the location of rock and water below the subsoil, before taking the reader back to the surface. His love of the city comes through as he describes various fun facts about the location of the New York residence of early 19th-century vice-president Aaron Burr as well as a number of legends about the city.</p>
<p>Chapters two and three take the reader up to the Civil War (1861–1865), with chapter two focusing on the early development of land and the implementation of a grid system in 1811. Chapter three focuses on land use before the Civil War. Both chapters are informative and well researched and set the stage for the economic analysis that comes later in the book. I would have liked Barr to expand upon his claim that existing tenements* prevented skyscrapers in certain neighborhoods because \'likely no skyscraper developer was interested in performing the necessary "slum clearance"\'. Later in the book, Barr makes the claim that the depth of bedrock** was not a limiting factor for developers, as foundation costs were a small fraction of the cost of development. At first glance, it is not obvious why slum clearance would be limiting, while more expensive foundations would not.</p>
<p>Chapter four focuses on immigration and the location of neighborhoods and tenements in the late 19th century. Barr identifies four primary immigrant enclaves and analyzes their locations in terms of the amenities available in the area. Most of these enclaves were located on the least valuable land, between the industries located on the waterfront and the wealthy neighborhoods bordering Central Park.</p>
<p>Part two of the book begins with a discussion of the economics of skyscraper height. In chapter five, Barr distinguishes between engineering height, economic height, and developer height – where engineering height is the tallest building that can be safely made at a given time, economic height is the height that is most efficient from society\'s point of view, and developer height is the actual height chosen by the developer, who is attempting to maximize return on investment.</p>
<p>Chapter five also has an interesting discussion of the technological advances that led to the construction of skyscrapers. For example, the introduction of iron and steel skeletal frames made thick, load-bearing walls unnecessary, expanding the usable square footage of buildings and increasing the use of windows and availability of natural light. Chapter six then presents data on building height throughout the 20th century and uses regression analysis to \'predict\' building construction. While less technical than the research paper on which the chapter is based, it is probably more technical than would be preferred by a general audience.</p>
<p>Chapter seven tackles the \'bedrock myth\', the assumption that the absence of bedrock close to the surface between Downtown and Midtown New York is the reason for skyscrapers not being built between the two urban centers. Rather, Barr argues that while deeper bedrock does increase foundation costs, these costs were neither prohibitively high nor were they large compared to the overall cost of building a skyscraper. What I enjoyed the most about this chapter was Barr\'s discussion of how foundations are actually built. He describes the use of caissons, which enable workers to dig down for considerable distances, often below the water table, until they reach bedrock. Barr\'s thorough technological history discusses not only how caissons work, but also the dangers involved. While this chapter references empirical research papers, it is a relatively easy read.</p>
<p>Chapters eight and nine focus on the birth of Midtown and the building boom of the 1920s. Chapter eight contains lengthy discussions of urban economic theory that may serve as a distraction to readers primarily interested in New York. However, they would be well-suited for undergraduates learning about the economics of cities. In the next chapter, Barr considers two of the primary explanations for the building boom of the 1920s – the first being exuberance, and the second being financing. He uses data to assess the viability of these two explanations and finds that supply and demand factors explain much of the development of the 1920s; though it enabled the boom, cheap credit was not, he argues, the primary cause.</p>
<p>In the final chapter (chapter 10), Barr discusses another of his empirical papers that estimates Manhattan land values from the mid-19th century to the present day. The data work that went into these estimations is particularly impressive. Toward the end of the chapter, Barr assesses \'whether skyscrapers are a cause or an effect of high land values\'. He finds that changes in land values predict future building height, but the reverse is not true. The book ends with an epilogue, in which Barr discusses the impact of climate change on the city and makes policy suggestions for New York going forward.</p>
<p class="text-muted small"><em>* a tenement: a multi-occupancy building of any sort, but particularly a run-down apartment building or slum building<br>** bedrock: the solid, hard rock in the ground that lies under a loose layer of soil</em></p>',
                'instructions' => '<strong>Questions 27–31.</strong> Choose the correct letter, <strong>A, B, C</strong> or <strong>D</strong>.',
                'questions' => [
                    [
                        'q' => 27,
                        'text' => 'What point does Shester make about Barr\'s book in the first paragraph?',
                        'options' => [
                            'A' => 'It gives a highly original explanation for urban development.',
                            'B' => 'Elements of Barr\'s research papers are incorporated throughout the book.',
                            'C' => 'Other books that are available on the subject have taken a different approach.',
                            'D' => 'It covers a range of factors that affected the development of New York.',
                        ],
                    ],
                    [
                        'q' => 28,
                        'text' => 'How does Shester respond to the information in the book about tenements?',
                        'options' => [
                            'A' => 'She describes the reasons for Barr\'s interest.',
                            'B' => 'She indicates a potential problem with Barr\'s analysis.',
                            'C' => 'She compares Barr\'s conclusion with that of other writers.',
                            'D' => 'She provides details about the sources Barr used for his research.',
                        ],
                    ],
                    [
                        'q' => 29,
                        'text' => 'What does Shester say about chapter six of the book?',
                        'options' => [
                            'A' => 'It contains conflicting data.',
                            'B' => 'It focuses too much on possible trends.',
                            'C' => 'It is too specialised for most readers.',
                            'D' => 'It draws on research that is out of date.',
                        ],
                    ],
                    [
                        'q' => 30,
                        'text' => 'What does Shester suggest about the chapters focusing on the 1920s building boom?',
                        'options' => [
                            'A' => 'The information should have been organised differently.',
                            'B' => 'More facts are needed about the way construction was financed.',
                            'C' => 'The explanation that is given for the building boom is unlikely.',
                            'D' => 'Some parts will have limited appeal to certain people.',
                        ],
                    ],
                    [
                        'q' => 31,
                        'text' => 'What impresses Shester the most about the chapter on land values?',
                        'options' => [
                            'A' => 'the broad time period that is covered',
                            'B' => 'the interesting questions that Barr asks',
                            'C' => 'the nature of the research into the topic',
                            'D' => 'the recommendations Barr makes for the future',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'true_false_ng',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 32–35.</strong> Do the following statements agree with the claims of the writer in Reading Passage 3? Write <strong>YES</strong> if the statement agrees with the claims of the writer, <strong>NO</strong> if the statement contradicts the claims of the writer, <strong>NOT GIVEN</strong> if it is impossible to say what the writer thinks about this.',
                'questions' => [
                    [
                        'q' => 32,
                        'text' => 'The description in the first chapter of how New York probably looked from the air in the early 1600s lacks interest.',
                    ],
                    [
                        'q' => 33,
                        'text' => 'Chapters two and three prepare the reader well for material yet to come.',
                    ],
                    [
                        'q' => 34,
                        'text' => 'The biggest problem for many nineteenth-century New York immigrant neighbourhoods was a lack of amenities.',
                    ],
                    [
                        'q' => 35,
                        'text' => 'In the nineteenth century, New York\'s immigrant neighbourhoods tended to concentrate around the harbour.',
                    ],
                ],
                'labels' => 'yn',
            ],
            [
                'type' => 'section_matching',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 36–40.</strong> Complete the summary using the list of phrases, <strong>A–J</strong>, below. Each gap in the summary below needs one letter.',
                'options_key' => [
                    'A' => 'development plans',
                    'B' => 'deep excavations',
                    'C' => 'great distance',
                    'D' => 'excessive expense',
                    'E' => 'impossible tasks',
                    'F' => 'associated risks',
                    'G' => 'water level',
                    'H' => 'specific areas',
                    'I' => 'total expenditure',
                    'J' => 'construction guidelines',
                ],
                'options' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'],
                'questions' => [
                    [
                        'q' => 36,
                        'text' => 'The bedrock myth - In chapter seven, Barr indicates how the lack of bedrock close to the surface does not explain why skyscrapers are absent from ______.',
                    ],
                    [
                        'q' => 37,
                        'text' => 'He points out that although the cost of foundations increases when bedrock is deep below the surface, this cannot be regarded as ______,',
                    ],
                    [
                        'q' => 38,
                        'text' => 'especially when compared to ______.',
                    ],
                    [
                        'q' => 39,
                        'text' => 'He describes not only how ______ are made possible by the use of caissons,',
                    ],
                    [
                        'q' => 40,
                        'text' => 'but he also discusses their ______.',
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
    <title>IELTS Academic Reading Practice Test 3 – EduHub</title>
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
                <li class="breadcrumb-item active">Practice Test 3</li>
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
const PAIRS = [[21, 22]];
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