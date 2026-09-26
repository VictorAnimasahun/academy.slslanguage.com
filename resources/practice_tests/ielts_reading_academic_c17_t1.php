<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}
require_once INCLUDES_PATH . '/course_lock.php';
// Cambridge IELTS 17 Academic, Test 1, Reading: three passages, 40 questions, 60 minutes (a full Academic Reading test).
// Source: documentation/test_bank/cambridge_ielts17_academic/test1.json. Generated from the bank so the page and the answer key (migration 135) agree.
// Open to students enrolled in IELTS_Aca_1Mo, IELTS_Aca_2Mo, IELTS_Aca_3Mo (staff, admins and testers always pass); course ids are looked up by folder, never typed.
require_course_enrollment(course_ids_for_folders(['IELTS_Aca_1Mo', 'IELTS_Aca_2Mo', 'IELTS_Aca_3Mo']), 'this IELTS Academic Reading practice test');

$testCode  = 'IELTS_PT_R_ACA_C17T1';
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
                'passage_title' => 'The development of the London underground railway',
                'passage_subtitle' => null,
                'passage' => '<p>In the first half of the 1800s, London\'s population grew at an astonishing rate, and the central area became increasingly congested. In addition, the expansion of the overground railway network resulted in more and more passengers arriving in the capital. However, in 1846, a Royal Commission decided that the railways should not be allowed to enter the City, the capital\'s historic and business centre. The result was that the overground railway stations formed a ring around the City. The area within consisted of poorly built, overcrowded slums and the streets were full of horse-drawn traffic. Crossing the City became a nightmare. It could take an hour and a half to travel 8 km by horse-drawn carriage or bus. Numerous schemes were proposed to resolve these problems, but few succeeded.</p>
<p>Amongst the most vocal advocates for a solution to London\'s traffic problems was Charles Pearson, who worked as a solicitor for the City of London. He saw both social and economic advantages in building an underground railway that would link the overground railway stations together and clear London slums at the same time. His idea was to relocate the poor workers who lived in the inner-city slums to newly constructed suburbs, and to provide cheap rail travel for them to get to work. Pearson\'s ideas gained support amongst some businessmen and in 1851 he submitted a plan to Parliament. It was rejected, but coincided with a proposal from another group for an underground connecting line, which Parliament passed.</p>
<p>The two groups merged and established the Metropolitan Railway Company in August 1854. The company\'s plan was to construct an underground railway line from the Great Western Railway\'s (GWR) station at Paddington to the edge of the City at Farringdon Street – a distance of almost 5 km. The organisation had difficulty in raising the funding for such a radical and expensive scheme, not least because of the critical articles printed by the press. Objectors argued that the tunnels would collapse under the weight of traffic overhead, buildings would be shaken and passengers would be poisoned by the emissions from the train engines. However, Pearson and his partners persisted.</p>
<p>The GWR, aware that the new line would finally enable them to run trains into the heart of the City, invested almost £250,000 in the scheme. Eventually, over a five-year period, £1m was raised. The chosen route ran beneath existing main roads to minimise the expense of demolishing buildings. Originally scheduled to be completed in 21 months, the construction of the underground line took three years. It was built just below street level using a technique known as \'cut and cover\'. A trench about ten metres wide and six metres deep was dug, and the sides temporarily held up with timber beams. Brick walls were then constructed, and finally a brick arch was added to create a tunnel. A two-metre-deep layer of soil was laid on top of the tunnel and the road above rebuilt.</p>
<p>The Metropolitan line, which opened on 10 January 1863, was the world\'s first underground railway. On its first day, almost 40,000 passengers were carried between Paddington and Farringdon, the journey taking about 18 minutes. By the end of the Metropolitan\'s first year of operation, 9.5 million journeys had been made.</p>
<p>Even as the Metropolitan began operation, the first extensions to the line were being authorised; these were built over the next five years, reaching Moorgate in the east of London and Hammersmith in the west. The original plan was to pull the trains with steam locomotives, using firebricks in the boilers to provide steam, but these engines were never introduced. Instead, the line used specially designed locomotives that were fitted with water tanks in which steam could be condensed. However, smoke and fumes remained a problem, even though ventilation shafts were added to the tunnels.</p>
<p>Despite the extension of the underground railway, by the 1880s, congestion on London\'s streets had become worse. The problem was partly that the existing underground lines formed a circuit around the centre of London and extended to the suburbs, but did not cross the capital\'s centre. The \'cut and cover\' method of construction was not an option in this part of the capital. The only alternative was to tunnel deep underground.</p>
<p>Although the technology to create these tunnels existed, steam locomotives could not be used in such a confined space. It wasn\'t until the development of a reliable electric motor, and a means of transferring power from the generator to a moving train, that the world\'s first deep-level electric railway, the City &amp; South London, became possible. The line opened in 1890, and ran from the City to Stockwell, south of the River Thames. The trains were made up of three carriages and driven by electric engines. The carriages were narrow and had tiny windows just below the roof because it was thought that passengers would not want to look out at the tunnel walls. The line was not without its problems, mainly caused by an unreliable power supply. Although the City &amp; South London Railway was a great technical achievement, it did not make a profit. Then, in 1900, the Central London Railway, known as the \'Tuppenny Tube\', began operation using new electric locomotives. It was very popular and soon afterwards new railways and extensions were added to the growing tube network. By 1907, the heart of today\'s Underground system was in place.</p>',
                'instructions' => '<strong>Questions 1–6.</strong> Complete the notes below. Choose <strong>ONE WORD ONLY</strong> from the passage for each answer.',
                'form_title' => 'The London underground railway',
                'groups' => [
                    [
                        'heading' => null,
                        'rows' => [
                            [
                                'prefix' => 'The London underground railway - The problem: The',
                                'q' => 1,
                                'suffix' => 'of London increased rapidly between 1800 and 1850',
                            ],
                            [
                                'prefix' => 'The proposed solution: Building the railway would make it possible to move people to better housing in the',
                                'q' => 2,
                                'suffix' => '',
                            ],
                            [
                                'prefix' => 'A number of',
                                'q' => 3,
                                'suffix' => 'agreed with Pearson\'s idea',
                            ],
                            [
                                'prefix' => 'The company initially had problems getting the',
                                'q' => 4,
                                'suffix' => 'needed for the project',
                            ],
                            [
                                'prefix' => 'Negative articles about the project appeared in the',
                                'q' => 5,
                                'suffix' => '',
                            ],
                            [
                                'prefix' => 'The construction: With the completion of the brick arch, the tunnel was covered with',
                                'q' => 6,
                                'suffix' => '',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'type' => 'true_false_ng',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 7–13.</strong> Do the following statements agree with the information given in Reading Passage 1? Write <strong>TRUE</strong> if the statement agrees with the information, <strong>FALSE</strong> if the statement contradicts the information, <strong>NOT GIVEN</strong> if there is no information on this.',
                'questions' => [
                    [
                        'q' => 7,
                        'text' => 'Other countries had built underground railways before the Metropolitan line opened.',
                    ],
                    [
                        'q' => 8,
                        'text' => 'More people than predicted travelled on the Metropolitan line on the first day.',
                    ],
                    [
                        'q' => 9,
                        'text' => 'The use of ventilation shafts failed to prevent pollution in the tunnels.',
                    ],
                    [
                        'q' => 10,
                        'text' => 'A different approach from the \'cut and cover\' technique was required in London\'s central area.',
                    ],
                    [
                        'q' => 11,
                        'text' => 'The windows on City & South London trains were at eye level.',
                    ],
                    [
                        'q' => 12,
                        'text' => 'The City & South London Railway was a financial success.',
                    ],
                    [
                        'q' => 13,
                        'text' => 'Trains on the \'Tuppenny Tube\' nearly always ran on time.',
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
                'passage_title' => 'Stadiums: past, present and future',
                'passage_subtitle' => null,
                'passage' => '<p><strong>A</strong> &nbsp; Stadiums are among the oldest forms of urban architecture: vast stadiums where the public could watch sporting events were at the centre of western city life as far back as the ancient Greek and Roman Empires, well before the construction of the great medieval cathedrals and the grand 19th- and 20th-century railway stations which dominated urban skylines in later eras.</p>
<p>Today, however, stadiums are regarded with growing scepticism. Construction costs can soar above £1 billion, and stadiums finished for major events such as the Olympic Games or the FIFA World Cup have notably fallen into disuse and disrepair.</p>
<p>But this need not be the case. History shows that stadiums can drive urban development and adapt to the culture of every age. Even today, architects and planners are finding new ways to adapt the mono-functional sports arenas which became emblematic of modernisation during the 20th century.</p>
<p><strong>B</strong> &nbsp; The amphitheatre* of Arles in southwest France, with a capacity of 25,000 spectators, is perhaps the best example of just how versatile stadiums can be. Built by the Romans in 90 AD, it became a fortress with four towers after the fifth century, and was then transformed into a village containing more than 200 houses. With the growing interest in conservation during the 19th century, it was converted back into an arena for the staging of bullfights, thereby returning the structure to its original use as a venue for public spectacles.</p>
<p>Another example is the imposing arena of Verona in northern Italy, with space for 30,000 spectators, which was built 60 years before the Arles amphitheatre and 40 years before Rome\'s famous Colosseum. It has endured the centuries and is currently considered one of the world\'s prime sites for opera, thanks to its outstanding acoustics.</p>
<p><strong>C</strong> &nbsp; The area in the centre of the Italian town of Lucca, known as the Piazza dell\'Anfiteatro, is yet another impressive example of an amphitheatre becoming absorbed into the fabric of the city. The site evolved in a similar way to Arles and was progressively filled with buildings from the Middle Ages until the 19th century, variously used as houses, a salt depot and a prison. But rather than reverting to an arena, it became a market square, designed by Romanticist architect Lorenzo Nottolini. Today, the ruins of the amphitheatre remain embedded in the various shops and residences surrounding the public square.</p>
<p><strong>D</strong> &nbsp; There are many similarities between modern stadiums and the ancient amphitheatres intended for games. But some of the flexibility was lost at the beginning of the 20th century, as stadiums were developed using new products such as steel and reinforced concrete, and made use of bright lights for night-time matches.</p>
<p>Many such stadiums are situated in suburban areas, designed for sporting use only and surrounded by parking lots. These factors mean that they may not be as accessible to the general public, require more energy to run and contribute to urban heat.</p>
<p><strong>E</strong> &nbsp; But many of today\'s most innovative architects see scope for the stadium to help improve the city. Among the current strategies, two seem to be having particular success: the stadium as an urban hub, and as a power plant.</p>
<p>There\'s a growing trend for stadiums to be equipped with public spaces and services that serve a function beyond sport, such as hotels, retail outlets, conference centres, restaurants and bars, children\'s playgrounds and green space. Creating mixed-use developments such as this reinforces compactness and multi-functionality, making more efficient use of land and helping to regenerate urban spaces.</p>
<p>This opens the space up to families and a wider cross-section of society, instead of catering only to sportspeople and supporters. There have been many examples of this in the UK: the mixed-use facilities at Wembley and Old Trafford have become a blueprint for many other stadiums in the world.</p>
<p><strong>F</strong> &nbsp; The phenomenon of stadiums as power stations has arisen from the idea that energy problems can be overcome by integrating interconnected buildings by means of a smart grid, which is an electricity supply network that uses digital communications technology to detect and react to local changes in usage, without significant energy losses. Stadiums are ideal for these purposes, because their canopies have a large surface area for fitting photovoltaic panels and rise high enough (more than 40 metres) to make use of micro wind turbines.</p>
<p>Freiburg Mage Solar Stadium in Germany is the first of a new wave of stadiums as power plants, which also includes the Amsterdam Arena and the Kaohsiung Stadium. The latter, inaugurated in 2009, has 8,844 photovoltaic panels producing up to 1.14 GWh of electricity annually. This reduces the annual output of carbon dioxide by 660 tons and supplies up to 80 percent of the surrounding area when the stadium is not in use. This is proof that a stadium can serve its city, and have a decidedly positive impact in terms of reduction of CO2 emissions.</p>
<p><strong>G</strong> &nbsp; Sporting arenas have always been central to the life and culture of cities. In every era, the stadium has acquired new value and uses: from military fortress to residential village, public space to theatre and most recently a field for experimentation in advanced engineering. The stadium of today now brings together multiple functions, thus helping cities to create a sustainable future.</p>
<p class="text-muted small"><em>* amphitheatre: (especially in Greek and Roman architecture) an open circular or oval building with a central space surrounded by tiers of seats for spectators, for the presentation of dramatic or sporting events</em></p>',
                'instructions' => '<strong>Questions 14–17.</strong> Reading Passage 2 has seven sections, <strong>A–G</strong>. Which section contains the following information? Write the correct letter, <strong>A–G</strong>. You may use any letter more than once.',
                'options' => ['A', 'B', 'C', 'D', 'E', 'F', 'G'],
                'questions' => [
                    [
                        'q' => 14,
                        'text' => 'a mention of negative attitudes towards stadium building projects',
                    ],
                    [
                        'q' => 15,
                        'text' => 'figures demonstrating the environmental benefits of a certain stadium',
                    ],
                    [
                        'q' => 16,
                        'text' => 'examples of the wide range of facilities available at some new stadiums',
                    ],
                    [
                        'q' => 17,
                        'text' => 'reference to the disadvantages of the stadiums built during a certain era',
                    ],
                ],
            ],
            [
                'type' => 'form_fill',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 18–22.</strong> Complete the summary below. Choose <strong>ONE WORD ONLY</strong> from the passage for each answer.',
                'form_title' => 'Roman amphitheatres',
                'groups' => [
                    [
                        'heading' => null,
                        'rows' => [
                            [
                                'prefix' => 'Roman amphitheatres - The amphitheatre of Arles, for example, was converted first into a',
                                'q' => 18,
                                'suffix' => ',',
                            ],
                            [
                                'prefix' => 'then into a residential area and finally into an arena where spectators could watch',
                                'q' => 19,
                                'suffix' => '.',
                            ],
                            [
                                'prefix' => 'Meanwhile, the arena in Verona, one of the oldest Roman amphitheatres, is famous today as a venue where',
                                'q' => 20,
                                'suffix' => 'is performed.',
                            ],
                            [
                                'prefix' => 'The site of Lucca\'s amphitheatre has also been used for many purposes over the centuries, including the storage of',
                                'q' => 21,
                                'suffix' => '.',
                            ],
                            [
                                'prefix' => 'It is now a market square with',
                                'q' => 22,
                                'suffix' => 'and homes incorporated into the remains of the Roman amphitheatre.',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'type' => 'section_matching',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 23 and 24.</strong> Choose <strong>TWO</strong> letters, <strong>A–E</strong>. When comparing twentieth-century stadiums to ancient amphitheatres in Section D, which TWO negative features does the writer mention?',
                'options_key' => [
                    'A' => 'They are less imaginatively designed.',
                    'B' => 'They are less spacious.',
                    'C' => 'They are in less convenient locations.',
                    'D' => 'They are less versatile.',
                    'E' => 'They are made of less durable materials.',
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
                'instructions' => '<strong>Questions 25 and 26.</strong> Choose <strong>TWO</strong> letters, <strong>A–E</strong>. Which TWO advantages of modern stadium design does the writer mention?',
                'options_key' => [
                    'A' => 'offering improved amenities for the enjoyment of sports events',
                    'B' => 'bringing community life back into the city environment',
                    'C' => 'facilitating research into solar and wind energy solutions',
                    'D' => 'enabling local residents to reduce their consumption of electricity',
                    'E' => 'providing a suitable site for the installation of renewable power generators',
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
                'passage_title' => 'To catch a king',
                'passage_subtitle' => 'Anna Keay reviews Charles Spencer\'s book about the hunt for King Charles II during the English Civil War of the seventeenth century',
                'passage' => '<p>Charles Spencer\'s latest book, To Catch a King, tells us the story of the hunt for King Charles II in the six weeks after his resounding defeat at the Battle of Worcester in September 1651. And what a story it is. After his father was executed by the Parliamentarians in 1649, the young Charles II sacrificed one of the very principles his father had died for and did a deal with the Scots, thereby accepting Presbyterianism* as the national religion in return for being crowned King of Scots. His arrival in Edinburgh prompted the English Parliamentary army to invade Scotland in a pre-emptive strike. This was followed by a Scottish invasion of England. The two sides finally faced one another at Worcester in the west of England in 1651. After being comprehensively defeated on the meadows outside the city by the Parliamentarian army, the 21-year-old king found himself the subject of a national manhunt, with a huge sum offered for his capture. Over the following six weeks he managed, through a series of heart-poundingly close escapes, to evade the Parliamentarians before seeking refuge in France. For the next nine years, the penniless and defeated Charles wandered around Europe with only a small group of loyal supporters.</p>
<p>Years later, after his restoration as king, the 50-year-old Charles II requested a meeting with the writer and diarist Samuel Pepys. His intention when asking Pepys to commit his story to paper was to ensure that this most extraordinary episode was never forgotten. Over two three-hour sittings, the king related to him in great detail his personal recollections of the six weeks he had spent as a fugitive. As the king and secretary settled down (a scene that is surely a gift for a future scriptwriter), Charles commenced his story: \'After the battle was so absolutely lost as to be beyond hope of recovery, I began to think of the best way of saving myself.\'</p>
<p>One of the joys of Spencer\'s book, a result not least of its use of Charles II\'s own narrative as well as those of his supporters, is just how close the reader gets to the action. The day-by-day retelling of the fugitives\' doings provides delicious details: the cutting of the king\'s long hair with agricultural shears, the use of walnut leaves to dye his pale skin, and the day Charles spent lying on a branch of the great oak tree in Boscobel Wood as the Parliamentary soldiers scoured the forest floor below. Spencer draws out both the humour – such as the preposterous refusal of Charles\'s friend Henry Wilmot to adopt disguise on the grounds that it was beneath his dignity – and the emotional tension when the secret of the king\'s presence was cautiously revealed to his supporters.</p>
<p>Charles\'s adventures after losing the Battle of Worcester hide the uncomfortable truth that whilst almost everyone in England had been appalled by the execution of his father, they had not welcomed the arrival of his son with the Scots army, but had instead firmly bolted their doors. This was partly because he rode at the head of what looked like a foreign invasion force and partly because, after almost a decade of civil war, people were desperate to avoid it beginning again. This makes it all the more interesting that Charles II himself loved the story so much ever after. As well as retelling it to anyone who would listen, causing eye-rolling among courtiers, he set in train a series of initiatives to memorialise it. There was to be a new order of chivalry, the Knights of the Royal Oak. A series of enormous oil paintings depicting the episode were produced, including a two-metre-wide canvas of Boscobel Wood and a set of six similarly enormous paintings of the king on the run. In 1660, Charles II commissioned the artist John Michael Wright to paint a flying squadron of cherubs* carrying an oak tree to the heavens on the ceiling of his bedchamber. It is hard to imagine many other kings marking the lowest point in their life so enthusiastically, or indeed pulling off such an escape in the first place.</p>
<p>Charles Spencer is the perfect person to pass the story on to a new generation. His pacey, readable prose steers deftly clear of modern idioms and elegantly brings to life the details of the great tale. He has even-handed sympathy for both the fugitive king and the fierce republican regime that hunted him, and he succeeds in his desire to explore far more of the background of the story than previous books on the subject have done. Indeed, the opening third of the book is about how Charles II found himself at Worcester in the first place, which for some will be reason alone to read To Catch a King.</p>
<p>The tantalising question left, in the end, is that of what it all meant. Would Charles II have been a different king had these six weeks never happened? The days and nights spent in hiding must have affected him in some way. Did the need to assume disguises, to survive on wit and charm alone, to use trickery and subterfuge to escape from tight corners help form him? This is the one area where the book doesn\'t quite hit the mark. Instead its depiction of Charles II in his final years as an ineffective, pleasure-loving monarch doesn\'t do justice to the man (neither is it accurate), or to the complexity of his character. But this one niggle aside, To Catch a King is an excellent read, and those who come to it knowing little of the famous tale will find they have a treat in store.</p>
<p class="text-muted small"><em>* Presbyterianism: part of the reformed Protestant religion<br>* cherub: an image of angelic children used in paintings</em></p>',
                'instructions' => '<strong>Questions 27–31.</strong> Complete the summary using the list of phrases, <strong>A–J</strong>, below. Each gap in the summary below needs one letter.',
                'options_key' => [
                    'A' => 'military innovation',
                    'B' => 'large reward',
                    'C' => 'widespread conspiracy',
                    'D' => 'relative safety',
                    'E' => 'new government',
                    'F' => 'decisive victory',
                    'G' => 'political debate',
                    'H' => 'strategic alliance',
                    'I' => 'popular solution',
                    'J' => 'religious conviction',
                ],
                'options' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'],
                'questions' => [
                    [
                        'q' => 27,
                        'text' => 'The story behind the hunt for Charles II - Charles II\'s father was executed by the Parliamentarian forces in 1649. Charles II then formed a ______ with the Scots,',
                    ],
                    [
                        'q' => 28,
                        'text' => 'and in order to become King of Scots, he abandoned an important ______ that was held by his father and had contributed to his father\'s death.',
                    ],
                    [
                        'q' => 29,
                        'text' => 'The battle led to a ______ for the Parliamentarians and Charles had to flee for his life.',
                    ],
                    [
                        'q' => 30,
                        'text' => 'A ______ was offered for Charles\'s capture,',
                    ],
                    [
                        'q' => 31,
                        'text' => 'but after six weeks spent in hiding, he eventually managed to reach the ______ of continental Europe.',
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
                        'text' => 'Charles chose Pepys for the task because he considered him to be trustworthy.',
                    ],
                    [
                        'q' => 33,
                        'text' => 'Charles\'s personal recollection of the escape lacked sufficient detail.',
                    ],
                    [
                        'q' => 34,
                        'text' => 'Charles indicated to Pepys that he had planned his escape before the battle.',
                    ],
                    [
                        'q' => 35,
                        'text' => 'The inclusion of Charles\'s account is a positive aspect of the book.',
                    ],
                ],
                'labels' => 'yn',
            ],
            [
                'type' => 'passage_mcq',
                'passage_title' => null,
                'passage' => null,
                'instructions' => '<strong>Questions 36–40.</strong> Choose the correct letter, <strong>A, B, C</strong> or <strong>D</strong>.',
                'questions' => [
                    [
                        'q' => 36,
                        'text' => 'What is the reviewer\'s main purpose in the first paragraph?',
                        'options' => [
                            'A' => 'to describe what happened during the Battle of Worcester',
                            'B' => 'to give an account of the circumstances leading to Charles II\'s escape',
                            'C' => 'to provide details of the Parliamentarians\' political views',
                            'D' => 'to compare Charles II\'s beliefs with those of his father',
                        ],
                    ],
                    [
                        'q' => 37,
                        'text' => 'Why does the reviewer include examples of the fugitives\' behaviour in the third paragraph?',
                        'options' => [
                            'A' => 'to explain how close Charles II came to losing his life',
                            'B' => 'to suggest that Charles II\'s supporters were badly prepared',
                            'C' => 'to illustrate how the events of the six weeks are brought to life',
                            'D' => 'to argue that certain aspects are not as well known as they should be',
                        ],
                    ],
                    [
                        'q' => 38,
                        'text' => 'What point does the reviewer make about Charles II in the fourth paragraph?',
                        'options' => [
                            'A' => 'He chose to celebrate what was essentially a defeat.',
                            'B' => 'He misunderstood the motives of his opponents.',
                            'C' => 'He aimed to restore people\'s faith in the monarchy.',
                            'D' => 'He was driven by a desire to be popular.',
                        ],
                    ],
                    [
                        'q' => 39,
                        'text' => 'What does the reviewer say about Charles Spencer in the fifth paragraph?',
                        'options' => [
                            'A' => 'His decision to write the book comes as a surprise.',
                            'B' => 'He takes an unbiased approach to the subject matter.',
                            'C' => 'His descriptions of events would be better if they included more detail.',
                            'D' => 'He chooses language that is suitable for a twenty-first-century audience.',
                        ],
                    ],
                    [
                        'q' => 40,
                        'text' => 'When the reviewer says the book \'doesn\'t quite hit the mark\', she is making the point that',
                        'options' => [
                            'A' => 'it overlooks the impact of events on ordinary people.',
                            'B' => 'it lacks an analysis of prevalent views on monarchy.',
                            'C' => 'it omits any references to the deceit practised by Charles II during his time in hiding.',
                            'D' => 'it fails to address whether Charles II\'s experiences had a lasting influence on him.',
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
                <li class="breadcrumb-item active">Practice Test 1</li>
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