-- ============================================================
-- Migration 017 — Seed IELTS_FM1_R (Reading Full Mock Test 1)
-- Cambridge IELTS General Training Test 1 — full real content
--
-- NON-DESTRUCTIVE: all INSERTs are guarded by COUNT = 0 checks.
-- Run on LOCAL (useraccounts) first, then LIVE (slslanguage_db)
-- ============================================================

-- Step 1: Ensure test record exists
INSERT INTO tests (code, title, description, test_type, category, is_mock_section, duration_minutes, total_questions, is_active)
SELECT 'IELTS_FM1_R', 'IELTS Full Mock 1 — Reading',
       'Cambridge IELTS GT Test 1 — Reading section (40Q/60 min)',
       'IELTS', 'Reading', 1, 60, 40, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_FM1_R');

SET @tid = (SELECT id FROM tests WHERE code = 'IELTS_FM1_R' LIMIT 1);

-- ============================================================
-- Step 2: Insert all 40 questions
-- stimulus_text on Q1, Q7, Q15, Q21, Q28 holds the passage text
-- for that question group; all others are NULL.
-- ============================================================
INSERT INTO questions (test_id, part_number, question_number, stimulus_text, question_text, question_type, instructions, points, display_order)
SELECT test_id, part_number, question_number, stimulus_text, question_text, question_type, instructions, points, display_order
FROM (

    -- ── SECTION 1 ─────────────────────────────────────────────────────────────
    -- Text A: Helping pupils choose optional subjects (Q1–6, matching A–E)
    SELECT @tid AS test_id, 1 AS part_number, 1 AS question_number,
        'Helping pupils to choose optional subjects when they''re aged 14–15: what some pupils say

A  Krishnan
I''m studying Spanish, because it''s important to learn foreign languages – and I''m very pleased when I can watch a video in class and understand it. Mr Peckham really pushes us, and offers us extra assignments, to help us improve. That''s good for me, because otherwise I''d be quite lazy.

B  Lucy
History is my favourite subject, and it''s fascinating to see how what we learn about the past is relevant to what''s going on in the world now. It''s made me understand much more about politics, for instance. My plan is to study history at university, and maybe go into the diplomatic service, so I can apply a knowledge of history.

C  Mark
Thursdays are my favourite days, because that''s when we have computing. It''s the high spot of the week for me – I love learning how to program. I began when I was about eight, so when I started doing it at school, I didn''t think I''d have any problem with it, but I was quite wrong! When I leave school, I''m going into my family retail business, so sadly I can''t see myself becoming a programmer.

D  Violeta
My parents both work in leisure and tourism, and they''ve always talked about their work a lot at home. I find it fascinating. I''m studying it at school, and the teacher is very knowledgeable, though I think we spend too much time listening to her: I''d like to meet more people working in the sector, and learn from their experience.

E  Walid
I''ve always been keen on art, so I chose it as an optional subject, though I was afraid the lessons might be a bit dull. I needn''t have worried, though – our teacher gets us to do lots of fun things, so there''s no risk of getting bored. At the end of the year the class puts on an exhibition for the school, and I''m looking forward to showing some of my work to other people.' AS stimulus_text,
        'This pupil is interested in the subject despite the way it is taught.' AS question_text,
        'matching' AS question_type,
        'Look at the five comments about lessons, A–E. For which comments are the following statements true? Write the correct letter, A–E. NB You may use any letter more than once.' AS instructions,
        1.0 AS points, 10 AS display_order
    UNION ALL SELECT @tid, 1, 2, NULL, 'This pupil is hoping to have a career that makes use of the subject.',   'matching', 'Write the correct letter, A–E.', 1.0, 20
    UNION ALL SELECT @tid, 1, 3, NULL, 'This pupil finds the subject harder than they expected.',                 'matching', 'Write the correct letter, A–E.', 1.0, 30
    UNION ALL SELECT @tid, 1, 4, NULL, 'This pupil finds the lessons very entertaining.',                         'matching', 'Write the correct letter, A–E.', 1.0, 40
    UNION ALL SELECT @tid, 1, 5, NULL, 'This pupil appreciates the benefit of doing challenging work.',           'matching', 'Write the correct letter, A–E.', 1.0, 50
    UNION ALL SELECT @tid, 1, 6, NULL, 'This pupil has realised the connection between two things.',              'matching', 'Write the correct letter, A–E.', 1.0, 60

    -- Text B: Ripton Festival (Q7–14, TRUE/FALSE/NOT GIVEN)
    UNION ALL SELECT @tid, 1, 7,
        'It''s almost time for the next Ripton Festival!

As usual, the festival will be held in the last weekend of June, this year on Saturday to Monday, 27–29 June. Ever since last year''s festival, the committee has been hard at work to make this year''s the best ever! The theme is Ripton through the ages. Scenes will be acted out showing how the town has developed since it was first established. But there''s also plenty that''s up-to-date, from the latest music to local crafts.

The Craft Fair is a regular part of the festival. Come and meet professional artists, designers and craftsmen and women, who will display their jewellery, paintings, ceramics, and much more. They''ll also take orders, so if you want one of them to make something especially for you, just ask! You''ll get it within a month of the festival ending.

The Saturday barbecue will start at 2 pm and continue until 10 pm, with a bouncy castle for kids. The barbecue will be held in Palmer''s Field, or in the town hall if there''s rain. Book your tickets now, as they always sell out very quickly! Entry for under 16s is free all day; adults can come for free until 6 pm and pay £5 after that. There''ll be live music throughout, with local amateur bands in the afternoon and professional musicians in the evening.

On Sunday we''re delighted to introduce an afternoon of boat races, arranged by the Ripton Rowing Club. The spectator area by the bridge has plenty of room to stand and cheer the boats home, in addition to a number of benches. The winners of the races will be presented with trophies by the mayor of Ripton.

All money raised by the festival will go to support the sports clubs in Ripton.',
        'The festival is held every year.',
        'true_false_not_given', 'Write TRUE, FALSE or NOT GIVEN.', 1.0, 70
    UNION ALL SELECT @tid, 1,  8, NULL, 'This year''s festival focuses on the town''s history.',                  'true_false_not_given', 'Write TRUE, FALSE or NOT GIVEN.', 1.0,  80
    UNION ALL SELECT @tid, 1,  9, NULL, 'Goods displayed in the craft fair are unlike ones found in shops.',      'true_false_not_given', 'Write TRUE, FALSE or NOT GIVEN.', 1.0,  90
    UNION ALL SELECT @tid, 1, 10, NULL, 'The barbecue will be cancelled if it rains.',                             'true_false_not_given', 'Write TRUE, FALSE or NOT GIVEN.', 1.0, 100
    UNION ALL SELECT @tid, 1, 11, NULL, 'Adults can attend the barbecue at any time without charge.',              'true_false_not_given', 'Write TRUE, FALSE or NOT GIVEN.', 1.0, 110
    UNION ALL SELECT @tid, 1, 12, NULL, 'Amateur musicians will perform during the whole of the barbecue.',        'true_false_not_given', 'Write TRUE, FALSE or NOT GIVEN.', 1.0, 120
    UNION ALL SELECT @tid, 1, 13, NULL, 'Seating is available for watching the boat races.',                       'true_false_not_given', 'Write TRUE, FALSE or NOT GIVEN.', 1.0, 130
    UNION ALL SELECT @tid, 1, 14, NULL, 'People attending the festival will be asked to donate some money.',       'true_false_not_given', 'Write TRUE, FALSE or NOT GIVEN.', 1.0, 140

    -- ── SECTION 2 ─────────────────────────────────────────────────────────────
    -- Text C: Reducing injuries on the farm (Q15–20, table completion)
    UNION ALL SELECT @tid, 2, 15,
        'Reducing injuries on the farm

Farms tend to be full of activity. There are always jobs to be done and some tasks require physical manual work. While it is good for people to be active, there are risk factors associated with this, and efforts need to be made to reduce them.

The first risk relates to the carrying of an excessive load or weight. This places undue demands on the spine and can cause permanent damage. Examples of tasks that involve this risk are moving 50-kilogramme fertiliser bags from one site to another or carrying heavy buckets of animal feed around fields. According to the UK Health and Safety Executive, activities such as these ''should be avoided at all times''. Their documentation states that other methods should be considered, such as breaking down the load into smaller containers prior to movement or transporting the materials using a tractor or other vehicle. The risk posed by excessive force is made worse if the person lifting is also bending over as this increases pressure on the discs in the back.

If a load is bulky or hard to grasp, such as a lively or agitated animal, it will be more difficult to hold while lifting and carrying. The holder may adopt an awkward posture, which is tiring and increases the risk of injury. Sometimes a load has to be held away from the body because there is a large obstacle in the area and the person lifting needs to be able to see where their feet are going. This results in increased stress on the back; holding a load at arm''s length imposes about five times the stress of a close-to-the-body position. In such cases, handling aids should be purchased that can take the weight off the load and minimise the potential for injury.

Another risk that relates to awkward posture is repetitive bending when carrying out a task. An example might be repairing a gate that has collapsed onto the ground. This type of activity increases the stress on the lower back because the back muscles have to support the weight of the upper body. The farmer should think about whether the job can be performed on a workbench, reducing the need for prolonged awkward posture.',
        'Lifting sacks of ___',
        'table_completion', 'Choose ONE WORD ONLY from the text for each answer.', 1.0, 150
    UNION ALL SELECT @tid, 2, 16, NULL, 'Lifting a restless ___',                     'table_completion', 'Choose ONE WORD ONLY from the text for each answer.', 1.0, 160
    UNION ALL SELECT @tid, 2, 17, NULL, 'Moving something around a big ___',           'table_completion', 'Choose ONE WORD ONLY from the text for each answer.', 1.0, 170
    UNION ALL SELECT @tid, 2, 18, NULL, 'Buy particular ___ to help with support',     'table_completion', 'Choose ONE WORD ONLY from the text for each answer.', 1.0, 180
    UNION ALL SELECT @tid, 2, 19, NULL, 'A lot of ___ while working',                  'table_completion', 'Choose ONE WORD ONLY from the text for each answer.', 1.0, 190
    UNION ALL SELECT @tid, 2, 20, NULL, 'Fixing a fallen ___',                          'table_completion', 'Choose ONE WORD ONLY from the text for each answer.', 1.0, 200

    -- Text D: Good customer service in retail (Q21–27, sentence completion)
    UNION ALL SELECT @tid, 2, 21,
        'Good customer service in retail

Without customers, your retail business would not exist. It stands to reason, therefore, that how you treat your customers has a direct impact on your profit margins.

Some customers just want to browse and not be bothered by sales staff. Try to be sensitive to how much help a customer wants; be proactive in offering help without being annoying. Suggest a product that naturally accompanies what the customer is considering or point out products for which there are special offers, but don''t pressure a customer into buying an item they don''t want.

Build up a comprehensive knowledge of all the products in your shop, including the pros and cons of products that are alike but that have been produced under a range of brand names. If you have run out of a particular item, make sure you know when the next orders are coming in. Negativity can put customers off instantly. If a customer asks a question to which the answer is ''no'', do not just leave it at that – follow it with a positive, for example: ''we''re expecting more of that product on Tuesday''.

Meanwhile, if you see a product in the wrong place on a shelf, don''t ignore it – put it back where it belongs. This attention to presentation keeps the shop tidy, giving the right impression to your customers. Likewise, if you notice a fault with a product, remove it and replace it with another.

When necessary, be discreet. For example, if the customer''s credit card is declined at the till, keep your voice down and enquire about an alternative payment method quietly so that the customer doesn''t feel humiliated. If they experience uncomfortable emotions in your shop, it''s unlikely that they''ll come back.

Finally, good manners are probably the most important aspect of dealing with customers. Treat each person with respect at all times, even when you are faced with rudeness. Being discourteous yourself will only add more fuel to the fire.

Build a reputation for polite, helpful staff and you''ll find that customers not only keep giving you their custom, but also tell their friends about you.',
        'A ___ approach to selling is fine as long as you do not irritate the customer.',
        'sentence_completion', 'Choose NO MORE THAN TWO WORDS from the text for each answer.', 1.0, 210
    UNION ALL SELECT @tid, 2, 22, NULL, 'Recommend additional products and ___ without being too forceful.',                              'sentence_completion', 'Choose NO MORE THAN TWO WORDS from the text for each answer.', 1.0, 220
    UNION ALL SELECT @tid, 2, 23, NULL, 'Know how to compare similar products which have different ___',                                   'sentence_completion', 'Choose NO MORE THAN TWO WORDS from the text for each answer.', 1.0, 230
    UNION ALL SELECT @tid, 2, 24, NULL, 'Avoid ___ by always saying more than ''no''.',                                                   'sentence_completion', 'Choose NO MORE THAN TWO WORDS from the text for each answer.', 1.0, 240
    UNION ALL SELECT @tid, 2, 25, NULL, 'Keep an eye on the ___ of goods on the shelves.',                                                'sentence_completion', 'Choose NO MORE THAN TWO WORDS from the text for each answer.', 1.0, 250
    UNION ALL SELECT @tid, 2, 26, NULL, 'If a customer has problems paying with their ___ , handle the problem with care.',                'sentence_completion', 'Choose NO MORE THAN TWO WORDS from the text for each answer.', 1.0, 260
    UNION ALL SELECT @tid, 2, 27, NULL, 'Any ___ from a customer should not affect how you treat them.',                                  'sentence_completion', 'Choose NO MORE THAN TWO WORDS from the text for each answer.', 1.0, 270

    -- ── SECTION 3 ─────────────────────────────────────────────────────────────
    -- Text E: Plastic is no longer fantastic (Q28–40)
    UNION ALL SELECT @tid, 3, 28,
        'Plastic is no longer fantastic

A  In 2017, Carlos Ferrando, a Spanish engineer-turned-entrepreneur, saw a piece of art in a museum that profoundly affected him. ''What Lies Under'', a photographic composition by Indonesian digital artist Ferdi Rizkiyanto, shows a child crouching by the edge of the ocean and ''lifting up'' a wave, to reveal a cluster of assorted plastic waste, from polyethylene bags to water bottles. The artwork, designed to raise public awareness, left Ferrando angry – and fuelled with entrepreneurial ideas.

B  Ferrando runs a Spanish-based design company, Closca, that produces an ingenious foldable bicycle helmet. But he has now also designed a stylish glass water bottle with a stretchy silicone strap and magnetic closure mechanism that means it can be attached to almost anything, from a bike to a bag to a pushchair handle. The product comes with an app that tells people where they can fill their bottles with water for free.

C  The intention is to persuade people to stop buying water in plastic bottles, thus saving consumers money and reducing the plastic waste piling up in our oceans. ''Bottled water is now a $100 billion business, and 81 per cent of the bottles are not recycled. It''s a complete waste – water is only 1.5 per cent of the price of the bottle!'' Ferrando cries. Indeed, environmentalists estimate that by 2050 there will be more plastic in our oceans than fish and that''s mainly down to such bottles. ''We are trying to create a sense that being environmentally sophisticated is a status symbol,'' he adds. ''We want people to clip their bottles onto what they are wearing, to show that they are recycling – and to look cool.''

D  Ferrando''s story is fascinating because it seems like an indicator of something unexpected. Three decades ago, conspicuous consumption – the purchase of luxuries, such as handbags, shoes, cars, etc. on a lavish scale – heightened people''s social status. Indeed, the closing decades of the 20th century were a time when it seemed that anything could be turned into a commodity. Hence the fact that water became a consumer item, sold in plastic bottles, instead of just emerging, for free, from a tap.

E  Today, though, conspicuous extravagance no longer seems desirable among consumers. Now, recycling is fashionable – as is cycling rather than driving. Plastic water bottles have become so common that they do not command status; instead, what many millennials – young people born in the late 20th century – prefer to post on social media are ''real'' (refillable) bottles or even the once widespread Thermos bottles. Some teenagers currently think that these stainless-steel vacuum-insulated water bottles that are coming back onto the market are ultra ''cool''; never mind the fact that they feel oddly out-of-date to anyone over the age of 40 or that teenagers in the 1970s would have avoided ever being seen with one.

F  It is uncertain whether Closca will succeed in its goal. Although its foldable bike helmet is available in some outlets in New York, including the Museum of Modern Art, it can be very hard for any design entrepreneur to really take off in the global mass market, though not as hard as it might have been in the past. If an entrepreneur had wanted to fund a smart invention a few decades ago, he or she would have had to either raise a bank loan, borrow money from a family member or use a credit card. Things have moved on slightly since then.

G  Entrepreneurs are still using the last two options, but some are also tapping into the ever-growing pot of money that is becoming available in the management world for ''corporate social responsibility'' (CSR) investments. And then there are other options for those who wish to raise money straight away. Ferrando posted details about his water-bottle venture on a large, recognised platform for funding creative projects. He appealed for people to donate $30,000 of seed money – the money he needed to get his project going – and promised to give a bottle to anyone who provided more than $39 in ''donations''. If he received the funds, he stated that the company would produce bottles in grey and white; if $60,000 was raised, a multicoloured one would be made. Using this approach, none of the donors has a stake in his idea, nor does he have any debt. Instead, it is almost a pre-sale of the product, in a manner that tests demand in advance and creates a potential crowd of enthusiasts. This old-fashioned community funding with a digital twist is supporting a growing array of projects ranging from films to card games, videos, watches and so on. And, at last count, Closca had raised some $52,838 from 803 backers. Maybe, 20 years from now, it will be the plastic bottle that seems peculiarly old-fashioned.',
        'Paragraph A',
        'matching', 'Choose the correct heading for each paragraph from the list of headings below. Write the correct number, i–viii.', 1.0, 280
    UNION ALL SELECT @tid, 3, 29, NULL, 'Paragraph B', 'matching', 'Choose the correct heading for each paragraph from the list of headings below. Write the correct number, i–viii.', 1.0, 290
    UNION ALL SELECT @tid, 3, 30, NULL, 'Paragraph C', 'matching', 'Choose the correct heading for each paragraph from the list of headings below. Write the correct number, i–viii.', 1.0, 300
    UNION ALL SELECT @tid, 3, 31, NULL, 'Paragraph D', 'matching', 'Choose the correct heading for each paragraph from the list of headings below. Write the correct number, i–viii.', 1.0, 310
    UNION ALL SELECT @tid, 3, 32, NULL, 'Paragraph E', 'matching', 'Choose the correct heading for each paragraph from the list of headings below. Write the correct number, i–viii.', 1.0, 320
    UNION ALL SELECT @tid, 3, 33, NULL, 'Paragraph F', 'matching', 'Choose the correct heading for each paragraph from the list of headings below. Write the correct number, i–viii.', 1.0, 330
    UNION ALL SELECT @tid, 3, 34, NULL, 'Paragraph G', 'matching', 'Choose the correct heading for each paragraph from the list of headings below. Write the correct number, i–viii.', 1.0, 340

    -- Q35–37: MCQ A–D
    UNION ALL SELECT @tid, 3, 35, NULL, 'What does Ferrando say about his glass water bottle?',            'multiple_choice_single', 'Choose the correct letter, A, B, C or D.', 1.0, 350
    UNION ALL SELECT @tid, 3, 36, NULL, 'What does the writer find fascinating about Ferrando''s story?',  'multiple_choice_single', 'Choose the correct letter, A, B, C or D.', 1.0, 360
    UNION ALL SELECT @tid, 3, 37, NULL, 'What does the writer suggest about Closca''s bike helmet?',       'multiple_choice_single', 'Choose the correct letter, A, B, C or D.', 1.0, 370

    -- Q38–40: Summary completion "Funding a smart invention"
    UNION ALL SELECT @tid, 3, 38, NULL,
        'Thirty years ago, the methods used by creators to fund their projects involved getting money from the bank or from someone in the ___',
        'summary_completion', 'Choose ONE WORD ONLY from the text for each answer.', 1.0, 380
    UNION ALL SELECT @tid, 3, 39, NULL,
        'the method Ferrando took was to use a well-known ___ to advertise his product and request financial support',
        'summary_completion', 'Choose ONE WORD ONLY from the text for each answer.', 1.0, 390
    UNION ALL SELECT @tid, 3, 40, NULL,
        'Ferrando advised his donors that his company would create bottles in two colours, followed by a ___ bottle once they had received a more significant amount',
        'summary_completion', 'Choose ONE WORD ONLY from the text for each answer.', 1.0, 400

) _qs
WHERE (SELECT COUNT(*) FROM questions WHERE test_id = @tid) = 0;

-- ============================================================
-- Step 3: Resolve question IDs
-- ============================================================
SET @q1  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  1);
SET @q2  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  2);
SET @q3  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  3);
SET @q4  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  4);
SET @q5  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  5);
SET @q6  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  6);
SET @q7  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  7);
SET @q8  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  8);
SET @q9  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  9);
SET @q10 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 10);
SET @q11 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 11);
SET @q12 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 12);
SET @q13 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 13);
SET @q14 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 14);
SET @q15 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 15);
SET @q16 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 16);
SET @q17 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 17);
SET @q18 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 18);
SET @q19 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 19);
SET @q20 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 20);
SET @q21 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 21);
SET @q22 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 22);
SET @q23 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 23);
SET @q24 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 24);
SET @q25 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 25);
SET @q26 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 26);
SET @q27 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 27);
SET @q28 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 28);
SET @q29 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 29);
SET @q30 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 30);
SET @q31 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 31);
SET @q32 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 32);
SET @q33 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 33);
SET @q34 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 34);
SET @q35 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 35);
SET @q36 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 36);
SET @q37 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 37);
SET @q38 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 38);
SET @q39 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 39);
SET @q40 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 40);

-- ============================================================
-- Step 4: Insert options
-- Q1–6:   pupils A–E (same options, 5 per question = 30 rows)
-- Q28–34: headings i–viii (same options, 8 per question = 56 rows)
-- Q35–37: MCQ A–D (4 per question = 12 rows)
-- ============================================================
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT question_id, option_label, option_text, is_correct, display_order FROM (

    -- Q1 (correct: D — Violeta)
    SELECT @q1 AS question_id, 'A' AS option_label, 'Krishnan' AS option_text, 0 AS is_correct, 10 AS display_order
    UNION ALL SELECT @q1, 'B', 'Lucy',    0, 20
    UNION ALL SELECT @q1, 'C', 'Mark',    0, 30
    UNION ALL SELECT @q1, 'D', 'Violeta', 1, 40
    UNION ALL SELECT @q1, 'E', 'Walid',   0, 50
    -- Q2 (correct: B — Lucy)
    UNION ALL SELECT @q2, 'A', 'Krishnan', 0, 10
    UNION ALL SELECT @q2, 'B', 'Lucy',     1, 20
    UNION ALL SELECT @q2, 'C', 'Mark',     0, 30
    UNION ALL SELECT @q2, 'D', 'Violeta',  0, 40
    UNION ALL SELECT @q2, 'E', 'Walid',    0, 50
    -- Q3 (correct: C — Mark)
    UNION ALL SELECT @q3, 'A', 'Krishnan', 0, 10
    UNION ALL SELECT @q3, 'B', 'Lucy',     0, 20
    UNION ALL SELECT @q3, 'C', 'Mark',     1, 30
    UNION ALL SELECT @q3, 'D', 'Violeta',  0, 40
    UNION ALL SELECT @q3, 'E', 'Walid',    0, 50
    -- Q4 (correct: E — Walid)
    UNION ALL SELECT @q4, 'A', 'Krishnan', 0, 10
    UNION ALL SELECT @q4, 'B', 'Lucy',     0, 20
    UNION ALL SELECT @q4, 'C', 'Mark',     0, 30
    UNION ALL SELECT @q4, 'D', 'Violeta',  0, 40
    UNION ALL SELECT @q4, 'E', 'Walid',    1, 50
    -- Q5 (correct: A — Krishnan)
    UNION ALL SELECT @q5, 'A', 'Krishnan', 1, 10
    UNION ALL SELECT @q5, 'B', 'Lucy',     0, 20
    UNION ALL SELECT @q5, 'C', 'Mark',     0, 30
    UNION ALL SELECT @q5, 'D', 'Violeta',  0, 40
    UNION ALL SELECT @q5, 'E', 'Walid',    0, 50
    -- Q6 (correct: B — Lucy)
    UNION ALL SELECT @q6, 'A', 'Krishnan', 0, 10
    UNION ALL SELECT @q6, 'B', 'Lucy',     1, 20
    UNION ALL SELECT @q6, 'C', 'Mark',     0, 30
    UNION ALL SELECT @q6, 'D', 'Violeta',  0, 40
    UNION ALL SELECT @q6, 'E', 'Walid',    0, 50

    -- Q28–34: headings i–viii (correct per answer key: vi, iv, ii, viii, v, i, iii)
    UNION ALL SELECT @q28, 'i',    'A time when opportunities were limited',         0, 10
    UNION ALL SELECT @q28, 'ii',   'The reasons why Ferrando''s product is needed',  0, 20
    UNION ALL SELECT @q28, 'iii',  'A no-risk solution',                              0, 30
    UNION ALL SELECT @q28, 'iv',   'Two inventions and some physical details',        0, 40
    UNION ALL SELECT @q28, 'v',    'The contrasting views of different generations',  0, 50
    UNION ALL SELECT @q28, 'vi',   'A disturbing experience',                         1, 60
    UNION ALL SELECT @q28, 'vii',  'The problems with replacing a consumer item',     0, 70
    UNION ALL SELECT @q28, 'viii', 'Looking back at why water was bottled',           0, 80

    UNION ALL SELECT @q29, 'i',    'A time when opportunities were limited',         0, 10
    UNION ALL SELECT @q29, 'ii',   'The reasons why Ferrando''s product is needed',  0, 20
    UNION ALL SELECT @q29, 'iii',  'A no-risk solution',                              0, 30
    UNION ALL SELECT @q29, 'iv',   'Two inventions and some physical details',        1, 40
    UNION ALL SELECT @q29, 'v',    'The contrasting views of different generations',  0, 50
    UNION ALL SELECT @q29, 'vi',   'A disturbing experience',                         0, 60
    UNION ALL SELECT @q29, 'vii',  'The problems with replacing a consumer item',     0, 70
    UNION ALL SELECT @q29, 'viii', 'Looking back at why water was bottled',           0, 80

    UNION ALL SELECT @q30, 'i',    'A time when opportunities were limited',         0, 10
    UNION ALL SELECT @q30, 'ii',   'The reasons why Ferrando''s product is needed',  1, 20
    UNION ALL SELECT @q30, 'iii',  'A no-risk solution',                              0, 30
    UNION ALL SELECT @q30, 'iv',   'Two inventions and some physical details',        0, 40
    UNION ALL SELECT @q30, 'v',    'The contrasting views of different generations',  0, 50
    UNION ALL SELECT @q30, 'vi',   'A disturbing experience',                         0, 60
    UNION ALL SELECT @q30, 'vii',  'The problems with replacing a consumer item',     0, 70
    UNION ALL SELECT @q30, 'viii', 'Looking back at why water was bottled',           0, 80

    UNION ALL SELECT @q31, 'i',    'A time when opportunities were limited',         0, 10
    UNION ALL SELECT @q31, 'ii',   'The reasons why Ferrando''s product is needed',  0, 20
    UNION ALL SELECT @q31, 'iii',  'A no-risk solution',                              0, 30
    UNION ALL SELECT @q31, 'iv',   'Two inventions and some physical details',        0, 40
    UNION ALL SELECT @q31, 'v',    'The contrasting views of different generations',  0, 50
    UNION ALL SELECT @q31, 'vi',   'A disturbing experience',                         0, 60
    UNION ALL SELECT @q31, 'vii',  'The problems with replacing a consumer item',     0, 70
    UNION ALL SELECT @q31, 'viii', 'Looking back at why water was bottled',           1, 80

    UNION ALL SELECT @q32, 'i',    'A time when opportunities were limited',         0, 10
    UNION ALL SELECT @q32, 'ii',   'The reasons why Ferrando''s product is needed',  0, 20
    UNION ALL SELECT @q32, 'iii',  'A no-risk solution',                              0, 30
    UNION ALL SELECT @q32, 'iv',   'Two inventions and some physical details',        0, 40
    UNION ALL SELECT @q32, 'v',    'The contrasting views of different generations',  1, 50
    UNION ALL SELECT @q32, 'vi',   'A disturbing experience',                         0, 60
    UNION ALL SELECT @q32, 'vii',  'The problems with replacing a consumer item',     0, 70
    UNION ALL SELECT @q32, 'viii', 'Looking back at why water was bottled',           0, 80

    UNION ALL SELECT @q33, 'i',    'A time when opportunities were limited',         1, 10
    UNION ALL SELECT @q33, 'ii',   'The reasons why Ferrando''s product is needed',  0, 20
    UNION ALL SELECT @q33, 'iii',  'A no-risk solution',                              0, 30
    UNION ALL SELECT @q33, 'iv',   'Two inventions and some physical details',        0, 40
    UNION ALL SELECT @q33, 'v',    'The contrasting views of different generations',  0, 50
    UNION ALL SELECT @q33, 'vi',   'A disturbing experience',                         0, 60
    UNION ALL SELECT @q33, 'vii',  'The problems with replacing a consumer item',     0, 70
    UNION ALL SELECT @q33, 'viii', 'Looking back at why water was bottled',           0, 80

    UNION ALL SELECT @q34, 'i',    'A time when opportunities were limited',         0, 10
    UNION ALL SELECT @q34, 'ii',   'The reasons why Ferrando''s product is needed',  0, 20
    UNION ALL SELECT @q34, 'iii',  'A no-risk solution',                              1, 30
    UNION ALL SELECT @q34, 'iv',   'Two inventions and some physical details',        0, 40
    UNION ALL SELECT @q34, 'v',    'The contrasting views of different generations',  0, 50
    UNION ALL SELECT @q34, 'vi',   'A disturbing experience',                         0, 60
    UNION ALL SELECT @q34, 'vii',  'The problems with replacing a consumer item',     0, 70
    UNION ALL SELECT @q34, 'viii', 'Looking back at why water was bottled',           0, 80

    -- Q35 (correct: D)
    UNION ALL SELECT @q35, 'A', 'It matches his bicycle helmet.', 0, 10
    UNION ALL SELECT @q35, 'B', 'It is cheaper than a plastic bottle.', 0, 20
    UNION ALL SELECT @q35, 'C', 'He has designed it to suit all ages.', 0, 30
    UNION ALL SELECT @q35, 'D', 'He wants people to be proud to show it.', 1, 40
    -- Q36 (correct: D)
    UNION ALL SELECT @q36, 'A', 'the youthfulness of his ideas', 0, 10
    UNION ALL SELECT @q36, 'B', 'the old-fashioned nature of his products', 0, 20
    UNION ALL SELECT @q36, 'C', 'the choice it is creating for consumers', 0, 30
    UNION ALL SELECT @q36, 'D', 'the change it is revealing in people''s attitudes', 1, 40
    -- Q37 (correct: A)
    UNION ALL SELECT @q37, 'A', 'It has both functional and artistic value.', 1, 10
    UNION ALL SELECT @q37, 'B', 'Its main appeal is to older people.', 0, 20
    UNION ALL SELECT @q37, 'C', 'It has had extraordinary success worldwide.', 0, 30
    UNION ALL SELECT @q37, 'D', 'It is a more exciting invention than the glass bottle.', 0, 40

) _opts
WHERE (SELECT COUNT(*) FROM question_options qo JOIN questions q ON q.id = qo.question_id WHERE q.test_id = @tid) = 0;

-- ============================================================
-- Step 5: Insert correct answers
-- ============================================================
INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative)
SELECT question_id, answer_text, is_case_sensitive, is_alternative FROM (

    -- Q1–6 matching (D, B, C, E, A, B)
    SELECT @q1  AS question_id, 'D' AS answer_text, 0 AS is_case_sensitive, 0 AS is_alternative
    UNION ALL SELECT @q1,  'd', 0, 1
    UNION ALL SELECT @q2,  'B', 0, 0 UNION ALL SELECT @q2,  'b', 0, 1
    UNION ALL SELECT @q3,  'C', 0, 0 UNION ALL SELECT @q3,  'c', 0, 1
    UNION ALL SELECT @q4,  'E', 0, 0 UNION ALL SELECT @q4,  'e', 0, 1
    UNION ALL SELECT @q5,  'A', 0, 0 UNION ALL SELECT @q5,  'a', 0, 1
    UNION ALL SELECT @q6,  'B', 0, 0 UNION ALL SELECT @q6,  'b', 0, 1

    -- Q7–14 TRUE / FALSE / NOT GIVEN
    UNION ALL SELECT @q7,  'TRUE',      0, 0
    UNION ALL SELECT @q8,  'TRUE',      0, 0
    UNION ALL SELECT @q9,  'NOT GIVEN', 0, 0 UNION ALL SELECT @q9,  'not given', 0, 1
    UNION ALL SELECT @q10, 'FALSE',     0, 0
    UNION ALL SELECT @q11, 'FALSE',     0, 0
    UNION ALL SELECT @q12, 'FALSE',     0, 0
    UNION ALL SELECT @q13, 'TRUE',      0, 0
    UNION ALL SELECT @q14, 'NOT GIVEN', 0, 0 UNION ALL SELECT @q14, 'not given', 0, 1

    -- Q15–20 table completion
    UNION ALL SELECT @q15, 'fertiliser', 0, 0 UNION ALL SELECT @q15, 'fertilizer', 0, 1
    UNION ALL SELECT @q16, 'animal',     0, 0
    UNION ALL SELECT @q17, 'obstacle',   0, 0
    UNION ALL SELECT @q18, 'aids',       0, 0
    UNION ALL SELECT @q19, 'bending',    0, 0
    UNION ALL SELECT @q20, 'gate',       0, 0

    -- Q21–27 sentence completion
    UNION ALL SELECT @q21, 'proactive',      0, 0
    UNION ALL SELECT @q22, 'special offers', 0, 0
    UNION ALL SELECT @q23, 'brand names',    0, 0
    UNION ALL SELECT @q24, 'negativity',     0, 0
    UNION ALL SELECT @q25, 'presentation',   0, 0
    UNION ALL SELECT @q26, 'credit card',    0, 0
    UNION ALL SELECT @q27, 'rudeness',       0, 0

    -- Q28–34 matching headings
    UNION ALL SELECT @q28, 'vi',   0, 0
    UNION ALL SELECT @q29, 'iv',   0, 0
    UNION ALL SELECT @q30, 'ii',   0, 0
    UNION ALL SELECT @q31, 'viii', 0, 0
    UNION ALL SELECT @q32, 'v',    0, 0
    UNION ALL SELECT @q33, 'i',    0, 0
    UNION ALL SELECT @q34, 'iii',  0, 0

    -- Q38–40 summary completion
    UNION ALL SELECT @q38, 'family',         0, 0
    UNION ALL SELECT @q39, 'platform',       0, 0
    UNION ALL SELECT @q40, 'multicoloured',  0, 0
    UNION ALL SELECT @q40, 'multi-coloured', 0, 1
    UNION ALL SELECT @q40, 'multicolored',   0, 1
    UNION ALL SELECT @q40, 'multi-colored',  0, 1

) _ans
WHERE (SELECT COUNT(*) FROM question_correct_answers qca JOIN questions q ON q.id = qca.question_id WHERE q.test_id = @tid) = 0;

-- Verify: expect 40 questions
-- SELECT COUNT(*) FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM1_R');
