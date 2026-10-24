"""
IELTS Academic Full Mock 1 = Cambridge IELTS 13 Academic, Test 1 (founder, 2026-10-24).
Transcribed from the scanned book (Downloads/cambridge_academic_12-13.pdf, PDF pages 133-154; answer key PDF pages
241-242). Every answer below is the book's official key; alternatives are the book's own "/" options and plain
spelling variants (brain/brains). Used by build_aca_mock_001.py. Edit here, then rebuild.
"""

LISTENING = [
    # (part, number, type, stimulus, question, instructions, options [(label, text)], answers [accepted])
]
_P1_INS = 'Complete the table below. Write ONE WORD AND/OR A NUMBER for each answer.'
_P1 = 'COOKERY CLASSES (Example: The Food Studio)'
for n, q, a in [
    (1, 'The Food Studio. Focus: how to ___ and cook with seasonal products', ['choose']),
    (2, 'The Food Studio. Other information: small classes; also offers ___ classes', ['private']),
    (3, 'The Food Studio. Clients who return get a ___ discount', ['20', 'twenty', '20%', '20 percent', 'twenty percent', '20 per cent', 'twenty per cent']),
    (4, "Bond's Cookery School. Focus: food that is ___", ['healthy']),
    (5, "Bond's Cookery School. Includes recipes to strengthen your ___", ['bones']),
    (6, "Bond's Cookery School. They have a free ___ every Thursday", ['lecture']),
    (7, 'The ___ Centre', ['Arretsa']),
    (8, 'The third cookery class (Question 7). Focus: mainly ___ food', ['vegetarian']),
    (9, 'The third cookery class (Question 7). Located near the ___', ['market']),
    (10, 'The third cookery class (Question 7). A special course in skills with a ___ is sometimes available', ['knife']),
]:
    LISTENING.append((1, n, 'form_note_completion', _P1 if n == 1 else None, q, _P1_INS, [], a))

_MC3 = 'Choose the correct letter, A, B or C.'
for n, q, opts, a in [
    (11, 'Why are changes needed to traffic systems in Granford?',
     ['The number of traffic accidents has risen.', 'The amount of traffic on the roads has increased.', 'The types of vehicles on the roads have changed.'], 'B'),
    (12, 'In a survey, local residents particularly complained about',
     ['dangerous driving by parents.', 'pollution from trucks and lorries.', 'inconvenience from parked cars.'], 'C'),
    (13, 'According to the speaker, one problem with the new regulations will be',
     ['raising money to pay for them.', 'finding a way to make people follow them.', 'getting the support of the police.'], 'B'),
]:
    LISTENING.append((2, n, 'multiple_choice_single', 'Traffic Changes in Granford' if n == 11 else None, q, _MC3,
                      list(zip('ABC', opts)), [a]))

_MAP_INS = 'Label the map below. Write the correct letter, A–I, next to Questions 14–20.'
for n, q, a in [(14, 'New traffic lights', 'E'), (15, 'Pedestrian crossing', 'D'), (16, 'Parking allowed', 'B'),
                (17, "New 'No Parking' sign", 'G'), (18, 'New disabled parking spaces', 'C'), (19, 'Widened pavement', 'H'),
                (20, 'Lorry loading/unloading restrictions', 'I')]:
    LISTENING.append((2, n, 'diagram_map_labelling', 'Proposed traffic changes in Granford' if n == 14 else None, q, _MAP_INS, [], [a]))

for n, q, opts, a in [
    (21, 'Why is Jack interested in investigating seed germination?',
     ['He may do a module on a related topic later on.', 'He wants to have a career in plant science.', 'He is thinking of choosing this topic for his dissertation.'], 'A'),
    (22, 'Jack and Emma agree the main advantage of their present experiment is that it can be',
     ['described very easily.', 'carried out inside the laboratory.', 'completed in the time available.'], 'C'),
    (23, 'What do they decide to check with their tutor?',
     ['whether their aim is appropriate', 'whether anyone else has chosen this topic', 'whether the assignment contributes to their final grade'], 'B'),
    (24, "They agree that Graves' book on seed germination is disappointing because",
     ['it fails to cover recent advances in seed science.', 'the content is irrelevant for them.', 'its focus is very theoretical.'], 'C'),
    (25, 'What does Jack say about the article on seed germination by Lee Hall?',
     ['The diagrams of plant development are useful.', 'The analysis of seed germination statistics is thorough.', 'The findings on seed germination after fires are surprising.'], 'B'),
]:
    LISTENING.append((3, n, 'multiple_choice_single', None, q, _MC3, list(zip('ABC', opts)), [a]))

_FLOW_INS = 'Complete the flow-chart below. Choose FIVE answers from the box and write the correct letter, A–H, next to Questions 26–30.'
_FLOW_BOX = list(zip('ABCDEFGH', ['container', 'soil', 'weight', 'condition', 'height', 'colour', 'types', 'depths']))
for n, q, a in [(26, 'Select seeds of different ___ and sizes.', 'G'),
                (27, 'Measure and record the ___ and size of each one.', 'C'),
                (28, 'Decide on the ___ to be used.', 'H'),
                (29, 'Use a different ___ for each seed and label it.', 'A'),
                (30, "After about 3 weeks, record the plant's ___ . (Then: investigate the findings.)", 'E')]:
    LISTENING.append((3, n, 'matching', 'Stages in the experiment' if n == 26 else None, q, _FLOW_INS, _FLOW_BOX if n == 26 else [], [a]))

_P4_INS = 'Complete the notes below. Write ONE WORD ONLY for each answer.'
for n, q, a in [
    (31, 'Introduction. It was previously thought that only a few animals were suitable for city life, e.g. the ___ – because of its general adaptability', ['crow']),
    (32, 'the pigeon – because walls of city buildings are similar to ___', ['cliffs']),
    (33, 'In fact, many urban animals are adapting with unusual ___', ['speed']),
    (34, 'Recent research. Emilie Snell-Rood studied small urbanised mammal specimens from museums in Minnesota. She found the size of their ___ had increased.', ['brain', 'brains']),
    (35, 'She suggests this may be due to the need to locate new sources of ___ and to deal with new dangers.', ['food']),
    (36, 'Catarina Miranda focused on the ___ of urban and rural blackbirds.', ['behaviour', 'behaviours', 'behavior', 'behaviors']),
    (37, 'She found urban birds were often braver, but were afraid of situations that were ___', ['new']),
    (38, 'Jonathan Atwell studies how animals respond to urban environments. He found that some animals respond to ___ by producing lower levels of hormones.', ['stress']),
    (39, "Sarah Partan's team found urban squirrels use their ___ to help them communicate.", ['tail', 'tails']),
    (40, 'Long-term possibilities. Species of animals may develop which are unique to cities. However, some changes may not be ___', ['permanent']),
]:
    LISTENING.append((4, n, 'form_note_completion', 'Effects of urban environments on animals' if n == 31 else None, q, _P4_INS, [], a))


PASSAGES = {
    1: """Case Study: Tourism New Zealand website

New Zealand is a small country of four million inhabitants, a long-haul flight from all the major tourist-generating markets of the world. Tourism currently makes up 9% of the country's gross domestic product, and is the country's largest export sector. Unlike other export sectors, which make products and then sell them overseas, tourism brings its customers to New Zealand. The product is the country itself – the people, the places and the experiences. In 1999, Tourism New Zealand launched a campaign to communicate a new brand position to the world. The campaign focused on New Zealand's scenic beauty, exhilarating outdoor activities and authentic Maori culture, and it made New Zealand one of the strongest national brands in the world.

A key feature of the campaign was the website www.newzealand.com, which provided potential visitors to New Zealand with a single gateway to everything the destination had to offer. The heart of the website was a database of tourism services operators, both those based in New Zealand and those based abroad which offered tourism services to the country. Any tourism-related business could be listed by filling in a simple form. This meant that even the smallest bed and breakfast address or specialist activity provider could gain a web presence with access to an audience of long-haul visitors. In addition, because participating businesses were able to update the details they gave on a regular basis, the information provided remained accurate. And to maintain and improve standards, Tourism New Zealand organised a scheme whereby organisations appearing on the website underwent an independent evaluation against a set of agreed national standards of quality. As part of this, the effect of each business on the environment was considered.

To communicate the New Zealand experience, the site also carried features relating to famous people and places. One of the most popular was an interview with former New Zealand All Blacks rugby captain Tana Umaga. Another feature that attracted a lot of attention was an interactive journey through a number of the locations chosen for blockbuster films which had made use of New Zealand's stunning scenery as a backdrop. As the site developed, additional features were added to help independent travellers devise their own customised itineraries. To make it easier to plan motoring holidays, the site catalogued the most popular driving routes in the country, highlighting different routes according to the season and indicating distances and times.

Later, a Travel Planner feature was added, which allowed visitors to click and 'bookmark' places or attractions they were interested in, and then view the results on a map. The Travel Planner offered suggested routes and public transport options between the chosen locations. There were also links to accommodation in the area. By registering with the website, users could save their Travel Plan and return to it later, or print it out to take on the visit. The website also had a 'Your Words' section where anyone could submit a blog of their New Zealand travels for possible inclusion on the website.

The Tourism New Zealand website won two Webby awards for online achievement and innovation. More importantly perhaps, the growth of tourism to New Zealand was impressive. Overall tourism expenditure increased by an average of 6.9% per year between 1999 and 2004. From Britain, visits to New Zealand grew at an average annual rate of 13% between 2002 and 2006, compared to a rate of 4% overall for British visits abroad.

The website was set up to allow both individuals and travel organisations to create itineraries and travel packages to suit their own needs and interests. On the website, visitors can search for activities not solely by geographical location, but also by the particular nature of the activity. This is important as research shows that activities are the key driver of visitor satisfaction, contributing 74% to visitor satisfaction, while transport and accommodation account for the remaining 26%. The more activities that visitors undertake, the more satisfied they will be. It has also been found that visitors enjoy cultural activities most when they are interactive, such as visiting a marae (meeting ground) to learn about traditional Maori life. Many long-haul travellers enjoy such learning experiences, which provide them with stories to take home to their friends and family. In addition, it appears that visitors to New Zealand don't want to be 'one of the crowd' and find activities that involve only a few people more special and meaningful.

It could be argued that New Zealand is not a typical destination. New Zealand is a small country with a visitor economy composed mainly of small businesses. It is generally perceived as a safe English-speaking country with a reliable transport infrastructure. Because of the long-haul flight, most visitors stay for longer (average 20 days) and want to see as much of the country as possible on what is often seen as a once-in-a-lifetime visit. However, the underlying lessons apply anywhere – the effectiveness of a strong brand, a strategy based on unique experiences and a comprehensive and user-friendly website.""",

    14: """Why being bored is stimulating – and useful, too

This most common of emotions is turning out to be more interesting than we thought

A  We all know how it feels – it's impossible to keep your mind on anything, time stretches out, and all the things you could do seem equally unlikely to make you feel better. But defining boredom so that it can be studied in the lab has proved difficult. For a start, it can include a lot of other mental states, such as frustration, apathy, depression and indifference. There isn't even agreement over whether boredom is always a low-energy, flat kind of emotion or whether feeling agitated and restless counts as boredom, too. In his book, Boredom: A Lively History, Peter Toohey at the University of Calgary, Canada, compares it to disgust – an emotion that motivates us to stay away from certain situations. 'If disgust protects humans from infection, boredom may protect them from "infectious" social situations,' he suggests.

B  By asking people about their experiences of boredom, Thomas Goetz and his team at the University of Konstanz in Germany have recently identified five distinct types: indifferent, calibrating, searching, reactant and apathetic. These can be plotted on two axes – one running left to right, which measures low to high arousal, and the other from top to bottom, which measures how positive or negative the feeling is. Intriguingly, Goetz has found that while people experience all kinds of boredom, they tend to specialise in one. Of the five types, the most damaging is 'reactant' boredom with its explosive combination of high arousal and negative emotion. The most useful is what Goetz calls 'indifferent' boredom: someone isn't engaged in anything satisfying but still feels relaxed and calm. However, it remains to be seen whether there are any character traits that predict the kind of boredom each of us might be prone to.

C  Psychologist Sandi Mann at the University of Central Lancashire, UK, goes further. 'All emotions are there for a reason, including boredom,' she says. Mann has found that being bored makes us more creative. 'We're all afraid of being bored but in actual fact it can lead to all kinds of amazing things,' she says. In experiments published last year, Mann found that people who had been made to feel bored by copying numbers out of the phone book for 15 minutes came up with more creative ideas about how to use a polystyrene cup than a control group. Mann concluded that a passive, boring activity is best for creativity because it allows the mind to wander. In fact, she goes so far as to suggest that we should seek out more boredom in our lives.

D  Psychologist John Eastwood at York University in Toronto, Canada, isn't convinced. 'If you are in a state of mind-wandering you are not bored,' he says. 'In my view, by definition boredom is an undesirable state.' That doesn't necessarily mean that it isn't adaptive, he adds. 'Pain is adaptive – if we didn't have physical pain, bad things would happen to us. Does that mean that we should actively cause pain? No. But even if boredom has evolved to help us survive, it can still be toxic if allowed to fester.' For Eastwood, the central feature of boredom is a failure to put our 'attention system' into gear. This causes an inability to focus on anything, which makes time seem to go painfully slowly. What's more, your efforts to improve the situation can end up making you feel worse. 'People try to connect with the world and if they are not successful there's that frustration and irritability,' he says. Perhaps most worryingly, says Eastwood, repeatedly failing to engage attention can lead to a state where we don't know what to do any more, and no longer care.

E  Eastwood's team is now trying to explore why the attention system fails. It's early days but they think that at least some of it comes down to personality. Boredom proneness has been linked with a variety of traits. People who are motivated by pleasure seem to suffer particularly badly. Other personality traits, such as curiosity, are associated with a high boredom threshold. More evidence that boredom has detrimental effects comes from studies of people who are more or less prone to boredom. It seems those who bore easily face poorer prospects in education, their career and even life in general. But of course, boredom itself cannot kill – it's the things we do to deal with it that may put us in danger. What can we do to alleviate it before it comes to that? Goetz's group has one suggestion. Working with teenagers, they found that those who 'approach' a boring situation – in other words, see that it's boring and get stuck in anyway – report less boredom than those who try to avoid it by using snacks, TV or social media for distraction.

F  Psychologist Francoise Wemelsfelder speculates that our over-connected lifestyles might even be a new source of boredom. 'In modern human society there is a lot of overstimulation but still a lot of problems finding meaning,' she says. So instead of seeking yet more mental stimulation, perhaps we should leave our phones alone, and use boredom to motivate us to engage with the world in a more meaningful way.""",

    27: """Artificial artists

Can computers really create works of art?

The Painting Fool is one of a growing number of computer programs which, so their makers claim, possess creative talents. Classical music by an artificial composer has had audiences enraptured, and even tricked them into believing a human was behind the score. Artworks painted by a robot have sold for thousands of dollars and been hung in prestigious galleries. And software has been built which creates art that could not have been imagined by the programmer.

Human beings are the only species to perform sophisticated creative acts regularly. If we can break this process down into computer code, where does that leave human creativity? 'This is a question at the very core of humanity,' says Geraint Wiggins, a computational creativity researcher at Goldsmiths, University of London. 'It scares a lot of people. They are worried that it is taking something special away from what it means to be human.'

To some extent, we are all familiar with computerised art. The question is: where does the work of the artist stop and the creativity of the computer begin? Consider one of the oldest machine artists, Aaron, a robot that has had paintings exhibited in London's Tate Modern and the San Francisco Museum of Modern Art. Aaron can pick up a paintbrush and paint on canvas on its own. Impressive perhaps, but it is still little more than a tool to realise the programmer's own creative ideas.

Simon Colton, the designer of the Painting Fool, is keen to make sure his creation doesn't attract the same criticism. Unlike earlier 'artists' such as Aaron, the Painting Fool only needs minimal direction and can come up with its own concepts by going online for material. The software runs its own web searches and trawls through social media sites. It is now beginning to display a kind of imagination too, creating pictures from scratch. One of its original works is a series of fuzzy landscapes, depicting trees and sky. While some might say they have a mechanical look, Colton argues that such reactions arise from people's double standards towards software-produced and human-produced art. After all, he says, consider that the Painting Fool painted the landscapes without referring to a photo. 'If a child painted a new scene from its head, you'd say it has a certain level of imagination,' he points out. 'The same should be true of a machine.' Software bugs can also lead to unexpected results. Some of the Painting Fool's paintings of a chair came out in black and white, thanks to a technical glitch. This gives the work an eerie, ghostlike quality. Human artists like the renowned Ellsworth Kelly are lauded for limiting their colour palette – so why should computers be any different?

Researchers like Colton don't believe it is right to measure machine creativity directly to that of humans who 'have had millennia to develop our skills'. Others, though, are fascinated by the prospect that a computer might create something as original and subtle as our best artists. So far, only one has come close. Composer David Cope invented a program called Experiments in Musical Intelligence, or EMI. Not only did EMI create compositions in Cope's style, but also that of the most revered classical composers, including Bach, Chopin and Mozart. Audiences were moved to tears, and EMI even fooled classical music experts into thinking they were hearing genuine Bach. Not everyone was impressed however. Some, such as Wiggins, have blasted Cope's work as pseudoscience, and condemned him for his deliberately vague explanation of how the software worked. Meanwhile, Douglas Hofstadter of Indiana University said EMI created replicas which still rely completely on the original artist's creative impulses. When audiences found out the truth they were often outraged with Cope, and one music lover even tried to punch him. Amid such controversy, Cope destroyed EMI's vital databases.

But why did so many people love the music, yet recoil when they discovered how it was composed? A study by computer scientist David Moffat of Glasgow Caledonian University provides a clue. He asked both expert musicians and non-experts to assess six compositions. The participants weren't told beforehand whether the tunes were composed by humans or computers, but were asked to guess, and then rate how much they liked each one. People who thought the composer was a computer tended to dislike the piece more than those who believed it was human. This was true even among the experts, who might have been expected to be more objective in their analyses.

Where does this prejudice come from? Paul Bloom of Yale University has a suggestion: he reckons part of the pleasure we get from art stems from the creative process behind the work. This can give it an 'irresistible essence', says Bloom. Meanwhile, experiments by Justin Kruger of New York University have shown that people's enjoyment of an artwork increases if they think more time and effort was needed to create it. Similarly, Colton thinks that when people experience art, they wonder what the artist might have been thinking or what the artist is trying to tell them. It seems obvious, therefore, that with computers producing art, this speculation is cut short – there's nothing to explore. But as technology becomes increasingly complex, finding those greater depths in computer art could become possible. This is precisely why Colton asks the Painting Fool to tap into online social networks for its inspiration: hopefully this way it will choose themes that will already be meaningful to us.""",
}

READING = []
_T1 = 'Complete the table below. Choose ONE WORD ONLY from the passage for each answer.'
for n, q, a in [
    (1, 'Database of tourism services: allowed businesses to … information regularly', 'update'),
    (2, 'Database of tourism services: provided a country-wide evaluation of businesses, including their impact on the …', 'environment'),
    (3, 'Special features on local topics: e.g. an interview with a former sports …', 'captain'),
    (4, 'Special features on local topics: and an interactive tour of various locations used in …', 'films'),
    (5, 'Information on driving routes: varied depending on the …', 'season'),
    (6, 'Travel Planner: included a map showing selected places, details of public transport and local …', 'accommodation'),
    (7, "'Your Words': travellers could send a link to their …", 'blog'),
]:
    READING.append((1, n, 'form_note_completion', q, _T1, [], [a]))
_TF = ('Do the following statements agree with the information given in Reading Passage 1? Choose TRUE if the statement '
       'agrees with the information, FALSE if the statement contradicts the information, NOT GIVEN if there is no information on this.')
for n, q, a in [
    (8, 'The website www.newzealand.com aimed to provide ready-made itineraries and packages for travel companies and individual tourists.', 'FALSE'),
    (9, 'It was found that most visitors started searching on the website by geographical location.', 'NOT GIVEN'),
    (10, 'According to research, 26% of visitor satisfaction is related to their accommodation.', 'FALSE'),
    (11, 'Visitors to New Zealand like to become involved in the local culture.', 'TRUE'),
    (12, 'Visitors like staying in small hotels in New Zealand rather than in larger ones.', 'NOT GIVEN'),
    (13, 'Many visitors feel it is unlikely that they will return to New Zealand after their visit.', 'TRUE'),
]:
    READING.append((1, n, 'true_false_not_given', q, _TF, [], [a]))

_HEADINGS = list(zip(['i', 'ii', 'iii', 'iv', 'v', 'vi', 'vii', 'viii'], [
    'The productive outcomes that may result from boredom', 'What teachers can do to prevent boredom',
    'A new explanation and a new cure for boredom', 'Problems with a scientific approach to boredom',
    'A potential danger arising from boredom', 'Creating a system of classification for feelings of boredom',
    'Age groups most affected by boredom', 'Identifying those most affected by boredom']))
_H = 'Reading Passage 2 has six paragraphs, A–F. Choose the correct heading for each paragraph from the list of headings.'
for n, para, a in [(14, 'A', 'iv'), (15, 'B', 'vi'), (16, 'C', 'i'), (17, 'D', 'v'), (18, 'E', 'viii'), (19, 'F', 'iii')]:
    READING.append((2, n, 'matching', 'Paragraph ' + para, _H, _HEADINGS, [a]))
_IDEAS = list(zip('ABCDE', ['The way we live today may encourage boredom.', 'One sort of boredom is worse than all the others.',
                            'Levels of boredom may fall in the future.', 'Trying to cope with boredom can increase its negative effects.',
                            'Boredom may encourage us to avoid an unpleasant experience.']))
_M = 'Look at the following people and the list of ideas. Match each person with the correct idea, A–E.'
for n, person, a in [(20, 'Peter Toohey', 'E'), (21, 'Thomas Goetz', 'B'), (22, 'John Eastwood', 'D'), (23, 'Francoise Wemelsfelder', 'A')]:
    READING.append((2, n, 'matching', person, _M, _IDEAS, [a]))
_S = 'Complete the summary "Responses to boredom" below. Choose ONE WORD ONLY from the passage for each answer.'
for n, q, a in [
    (24, "For John Eastwood, the central feature of boredom is that people cannot …, due to a failure in what he calls the 'attention system', and as a result they become frustrated and irritable.", 'focus'),
    (25, 'His team suggests that those for whom … is an important aim in life may have problems in coping with boredom,', 'pleasure'),
    (26, 'whereas those who have the characteristic of … can generally cope with it.', 'curiosity'),
]:
    READING.append((2, n, 'form_note_completion', q, _S, [], [a]))

_MC4 = 'Choose the correct letter, A, B, C or D.'
for n, q, opts, a in [
    (27, 'What is the writer suggesting about computer-produced works in the first paragraph?',
     ["People's acceptance of them can vary considerably.", 'A great deal of progress has already been attained in this field.',
      'They have had more success in some artistic genres than in others.', 'The advances are not as significant as the public believes them to be.'], 'B'),
    (28, 'According to Geraint Wiggins, why are many people worried by computer art?',
     ['It is aesthetically inferior to human art.', 'It may ultimately supersede human art.',
      'It undermines a fundamental human quality.', 'It will lead to a deterioration in human ability.'], 'C'),
    (29, 'What is a key difference between Aaron and the Painting Fool?',
     ["its programmer's background", 'public response to its work', 'the source of its subject matter', 'the technical standard of its output'], 'C'),
    (30, 'What point does Simon Colton make in the fourth paragraph?',
     ['Software-produced art is often dismissed as childish and simplistic.', 'The same concepts of creativity should not be applied to all forms of art.',
      'It is unreasonable to expect a machine to be as imaginative as a human being.', 'People tend to judge computer art and human art according to different criteria.'], 'D'),
    (31, 'The writer refers to the paintings of a chair as an example of computer art which',
     ['achieves a particularly striking effect.', 'exhibits a certain level of genuine artistic skill.',
      'closely resembles that of a well-known artist.', 'highlights the technical limitations of the software.'], 'A'),
]:
    READING.append((3, n, 'multiple_choice_single', q, _MC4, list(zip('ABCD', opts)), [a]))
_ENDINGS = list(zip('ABCDEFG', [
    'generating work that was virtually indistinguishable from that of humans.', 'knowing whether it was the work of humans or software.',
    'producing work entirely dependent on the imagination of its creator.', 'comparing the artistic achievements of humans and computers.',
    'revealing the technical details of his program.', 'persuading the public to appreciate computer art.',
    'discovering that it was the product of a computer program.']))
_E = 'Complete each sentence with the correct ending, A–G.'
for n, q, a in [
    (32, 'Simon Colton says it is important to consider the long-term view when', 'D'),
    (33, "David Cope's EMI software surprised people by", 'A'),
    (34, 'Geraint Wiggins criticised Cope for not', 'E'),
    (35, 'Douglas Hofstadter claimed that EMI was', 'C'),
    (36, "Audiences who had listened to EMI's music became angry after", 'G'),
    (37, "The participants in David Moffat's study had to assess music without", 'B'),
]:
    READING.append((3, n, 'matching', q, _E, _ENDINGS, [a]))
_YN = ('Do the following statements agree with the claims of the writer in Reading Passage 3? Choose YES if the statement agrees '
       'with the claims of the writer, NO if the statement contradicts the claims of the writer, NOT GIVEN if it is impossible to say '
       'what the writer thinks about this.')
for n, q, a in [
    (38, "Moffat's research may help explain people's reactions to EMI.", 'YES'),
    (39, "The non-experts in Moffat's study all responded in a predictable way.", 'NOT GIVEN'),
    (40, "Justin Kruger's findings cast doubt on Paul Bloom's theory about people's prejudice towards computer art.", 'NO'),
]:
    READING.append((3, n, 'yes_no_not_given', q, _YN, [], [a]))

WRITING = [
    (1, 'The two maps below show road access to a city hospital in 2007 and in 2010.\n\n'
        'Summarise the information by selecting and reporting the main features, and make comparisons where relevant.\n\n'
        'Write at least 150 words.',
     'assets/img/mock_tests/IELTS_ACA_FULL_MOCK_001/writing_task1_maps.png'),
    (2, 'Living in a country where you have to speak a foreign language can cause serious social problems, as well as practical problems.\n\n'
        'To what extent do you agree or disagree with this statement?\n\n'
        'Give reasons for your answer and include any relevant examples from your own knowledge or experience.\n\n'
        'Write at least 250 words.',
     None),
]

# For the tutor who administers Speaking (Full Mocks keep tutor-administered Speaking).
SPEAKING = {
    'part1': ('Television programmes', ['Where do you usually watch TV programmes/shows? [Why?/Why not?]',
                                     "What's your favourite TV programme/show? [Why?]",
                                     "Are there any programmes/shows you don't like watching? [Why?/Why not?]",
                                     'Do you think you will watch more TV or fewer TV programmes/shows in the future? [Why?/Why not?]']),
    'part2': ('Describe someone you know who has started a business.',
              ['who this person is', 'what work this person does', 'why this person decided to start a business'],
              'and explain whether you would like to do the same kind of work as this person.'),
    'part3': [('Choosing work', ['What kinds of jobs do young people not want to do in your country?',
                                 'Who is best at advising young people about choosing a job: teachers or parents?',
                                 'Is money always the most important thing when choosing a job?']),
              ('Work–life balance', ['Do you agree that many people nowadays are under pressure to work longer hours and take less holiday?',
                                     'What is the impact on society of people having a poor work–life balance?',
                                     'Could you recommend some effective strategies for governments and employers to ensure people have a good work–life balance?'])],
}
