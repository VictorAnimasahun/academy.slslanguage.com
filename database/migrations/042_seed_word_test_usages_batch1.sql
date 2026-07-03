-- Migration 042 — Seed test usage examples for the first 30 vocabulary words
-- Phase 1, Step 5 of the Vocabulary Banks feature.
-- 2 examples per word = 60 rows total across IELTS, CELPIP, and PTE contexts.
-- Run on LOCAL first, then LIVE.
-- Idempotent: deletes existing usages for these words before re-inserting.

DELETE FROM word_test_usages
WHERE word_id IN (SELECT id FROM vocabulary_words WHERE sort_order BETWEEN 1 AND 30);

-- ── analyse ──────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'It is important to analyse the causes of climate change before proposing solutions.',
'Use to show depth of critical thinking — signals you are going beyond surface observations.', 1
FROM vocabulary_words WHERE headword = 'analyse';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Speaking', 'Part 3',
'To give a full answer to that question, we need to analyse both sides of the argument.',
'Part 3 rewards candidates who structure their thinking — using "analyse" signals this.', 2
FROM vocabulary_words WHERE headword = 'analyse';

-- ── approach ─────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'There are several approaches that governments can take to reduce levels of youth unemployment.',
'Excellent noun for framing an argument — shows awareness of multiple solutions.', 1
FROM vocabulary_words WHERE headword = 'approach';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'CELPIP', 'Writing', 'Task 2',
'A more effective approach to tackling traffic congestion would be to invest heavily in public transport.',
'CELPIP Task 2 asks for opinions on issues — "approach" helps frame your solution clearly.', 2
FROM vocabulary_words WHERE headword = 'approach';

-- ── assess ───────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'It is difficult to assess the long-term effects of social media on young people without more research.',
'Stronger than "judge" or "look at" — lifts the register of a Task 2 argument.', 1
FROM vocabulary_words WHERE headword = 'assess';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'PTE', 'Writing', 'Essay',
'Educators must regularly assess students\' progress to identify areas requiring further support.',
'PTE Essays reward precise academic vocabulary — "assess" fits naturally in education topics.', 2
FROM vocabulary_words WHERE headword = 'assess';

-- ── benefit ──────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'The benefits of regular exercise extend well beyond physical health to include mental wellbeing.',
'Versatile noun — use "benefit from" (verb) or "the benefits of" (noun phrase) interchangeably.', 1
FROM vocabulary_words WHERE headword = 'benefit';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Speaking', 'Part 3',
'There are enormous benefits to learning a second language, including improved cognitive function.',
'Very common in Speaking Part 3 discussions on education, technology, or travel.', 2
FROM vocabulary_words WHERE headword = 'benefit';

-- ── concept ──────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Reading', 'Academic Reading',
'The concept of sustainable development has become central to modern environmental policy.',
'Frequently appears in Academic Reading passages on science, economics, and social policy.', 1
FROM vocabulary_words WHERE headword = 'concept';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'PTE', 'Writing', 'Summarize Written Text',
'The author introduces the concept of collective intelligence to explain how groups outperform individuals.',
'Summarize Written Text often contains abstract concepts — recognising this word speeds up comprehension.', 2
FROM vocabulary_words WHERE headword = 'concept';

-- ── context ──────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'In the context of globalisation, the role of national governments has changed significantly.',
'"In the context of" is a high-scoring linking phrase that shows sophisticated framing.', 1
FROM vocabulary_words WHERE headword = 'context';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Reading', 'Academic Reading',
'The word "culture" must be understood in its historical context to be interpreted correctly.',
'A clue word in Reading — if you see "in context," the question may be testing word meaning.', 2
FROM vocabulary_words WHERE headword = 'context';

-- ── contribute ───────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'Poor diet and a lack of exercise contribute significantly to the rise in global obesity rates.',
'"Contribute to" is one of the most natural cause-effect phrases in academic writing.', 1
FROM vocabulary_words WHERE headword = 'contribute';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'CELPIP', 'Writing', 'Task 2',
'Both individuals and governments must contribute to solving environmental problems if real change is to occur.',
'CELPIP writing tasks on the environment or civic issues reward this word highly.', 2
FROM vocabulary_words WHERE headword = 'contribute';

-- ── crucial ──────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'Access to clean water is crucial for maintaining public health in developing nations.',
'Stronger than "important" or "necessary" — use to emphasise the highest priority points.', 1
FROM vocabulary_words WHERE headword = 'crucial';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Speaking', 'Part 3',
'I believe communication skills are absolutely crucial in almost any professional environment.',
'In Speaking, pairing "absolutely crucial" adds natural emphasis without sounding unnatural.', 2
FROM vocabulary_words WHERE headword = 'crucial';

-- ── demonstrate ──────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'Research has demonstrated that access to early childhood education produces lasting social benefits.',
'Use "research/studies demonstrate" to cite evidence formally without naming a specific source.', 1
FROM vocabulary_words WHERE headword = 'demonstrate';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'PTE', 'Writing', 'Essay',
'Companies must demonstrate a commitment to environmental responsibility to attract modern consumers.',
'PTE Essays on business and sustainability frequently call for this verb.', 2
FROM vocabulary_words WHERE headword = 'demonstrate';

-- ── environment ──────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'Protecting the natural environment must be treated as a global priority, not merely a local concern.',
'One of the most common topic words across all three major tests.', 1
FROM vocabulary_words WHERE headword = 'environment';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Listening', 'Part 4',
'The lecture focused on how rapid urban development is altering the natural environment in coastal areas.',
'Part 4 lectures frequently use "environment" in the context of geography or science.', 2
FROM vocabulary_words WHERE headword = 'environment';

-- ── establish ────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'Many countries have established strict laws to protect workers from exploitation in the workplace.',
'"Well-established" is a useful fixed phrase meaning something is widely accepted or long-standing.', 1
FROM vocabulary_words WHERE headword = 'establish';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Reading', 'Academic Reading',
'The study sought to establish a clear causal link between diet quality and long-term cognitive performance.',
'Common in Research/Science Reading passages — signals the aim or purpose of a study.', 2
FROM vocabulary_words WHERE headword = 'establish';

-- ── evaluate ─────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'PTE', 'Writing', 'Essay',
'It is essential to evaluate the advantages and disadvantages of renewable energy before committing to a policy.',
'PTE Essays often ask you to "evaluate" — using the word itself shows you understand the task.', 1
FROM vocabulary_words WHERE headword = 'evaluate';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'Governments must regularly evaluate the effectiveness of current education policies to ensure progress.',
'Slightly more formal than "assess" — interchangeable in most Task 2 essays.', 2
FROM vocabulary_words WHERE headword = 'evaluate';

-- ── factor ───────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'One of the main factors contributing to youth unemployment is a shortage of practical workplace skills.',
'"Key factor," "contributing factor," and "deciding factor" are all high-frequency collocations.', 1
FROM vocabulary_words WHERE headword = 'factor';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Listening', 'Part 4',
'Several key factors were identified as influencing consumer purchasing decisions in the online market.',
'Part 4 academic lectures frequently list "factors" — listen for this word as a signpost.', 2
FROM vocabulary_words WHERE headword = 'factor';

-- ── focus ────────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'Governments should focus resources on improving public healthcare rather than funding space exploration.',
'"Focus on" is one of the most natural phrases for narrowing down an argument or topic.', 1
FROM vocabulary_words WHERE headword = 'focus';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Speaking', 'Part 2',
'I would like to focus on a book that had a profound impact on the way I see the world.',
'Part 2 cue cards often say "focus on one aspect" — using the word itself sounds natural and fluent.', 2
FROM vocabulary_words WHERE headword = 'focus';

-- ── identify ─────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'Researchers have identified several key factors that consistently lead to higher academic achievement.',
'Use "identify" to introduce evidence or findings — more academic than "find" or "see."', 1
FROM vocabulary_words WHERE headword = 'identify';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Reading', 'Academic Reading',
'Readers are asked to identify which paragraph contains the writer\'s main argument.',
'Task instructions themselves use "identify" — recognising it speeds up question comprehension.', 2
FROM vocabulary_words WHERE headword = 'identify';

-- ── impact ───────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'The impact of social media on interpersonal relationships should not be underestimated.',
'"Have a significant/negative/positive impact on" is one of the most useful fixed phrases in test writing.', 1
FROM vocabulary_words WHERE headword = 'impact';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'CELPIP', 'Writing', 'Task 2',
'The long-term impact of remote working on employee productivity is still being studied by researchers.',
'CELPIP Task 2 workplace topics frequently involve discussing the impact of modern trends.', 2
FROM vocabulary_words WHERE headword = 'impact';

-- ── indicate ─────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 1',
'The graph indicates a steady and significant increase in global average temperatures over the past century.',
'Essential for Task 1 — use "the graph/chart/data indicates that" to describe what visuals show.', 1
FROM vocabulary_words WHERE headword = 'indicate';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Reading', 'Academic Reading',
'The findings indicate that regular physical activity significantly reduces the risk of heart disease.',
'Signals that evidence is being presented — a key reading comprehension cue word.', 2
FROM vocabulary_words WHERE headword = 'indicate';

-- ── individual ───────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'While society as a whole must act, individual choices also play an important role in reducing pollution.',
'Useful for contrasting collective vs. personal responsibility — a common Task 2 theme.', 1
FROM vocabulary_words WHERE headword = 'individual';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Speaking', 'Part 3',
'In my view, every individual has a responsibility to reduce their own carbon footprint where possible.',
'Using "individual" instead of "person" immediately raises the register of a Speaking answer.', 2
FROM vocabulary_words WHERE headword = 'individual';

-- ── involve ──────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'Addressing poverty effectively involves far more than simply providing short-term financial assistance.',
'"Involve" is stronger than "include" for describing what a process requires or demands.', 1
FROM vocabulary_words WHERE headword = 'involve';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'PTE', 'Writing', 'Essay',
'Any lasting solution to the climate crisis will necessarily involve significant international cooperation.',
'"Will involve" or "necessarily involves" is a natural PTE Essay phrase for solution-based arguments.', 2
FROM vocabulary_words WHERE headword = 'involve';

-- ── issue ────────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'Homelessness is a deeply complex issue that demands both short-term relief and long-term structural reform.',
'"Address an issue," "raise an issue," and "tackle an issue" are all high-scoring collocations.', 1
FROM vocabulary_words WHERE headword = 'issue';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Speaking', 'Part 3',
'Pollution is one of the most pressing issues facing large cities across the world today.',
'"Pressing issue" is a strong collocation — it conveys urgency naturally.', 2
FROM vocabulary_words WHERE headword = 'issue';

-- ── maintain ─────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'It is increasingly difficult for families to maintain a healthy balance between work and personal life.',
'"Maintain a balance" and "maintain standards" are both very natural academic collocations.', 1
FROM vocabulary_words WHERE headword = 'maintain';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'CELPIP', 'Writing', 'Task 1',
'I am writing to request that the building management maintain the communal areas to a higher standard.',
'CELPIP Task 1 emails often involve complaints or requests — "maintain" fits formal letter register.', 2
FROM vocabulary_words WHERE headword = 'maintain';

-- ── method ───────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'Traditional teaching methods may no longer be adequate for preparing students for a rapidly changing world.',
'"Method" is interchangeable with "approach" or "technique" in most Task 2 discussions.', 1
FROM vocabulary_words WHERE headword = 'method';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'PTE', 'Writing', 'Summarize Written Text',
'The article explores several methods used to reduce carbon emissions in the commercial transport sector.',
'SWT passages on science, technology, or environment commonly list "methods" — a key signpost word.', 2
FROM vocabulary_words WHERE headword = 'method';

-- ── policy ───────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'Government policies on immigration have a direct and measurable effect on national labour markets.',
'Combine "implement," "introduce," or "review" with "policy" for strong collocations.', 1
FROM vocabulary_words WHERE headword = 'policy';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'CELPIP', 'Writing', 'Task 2',
'A strict recycling policy introduced by the city council resulted in a 30% reduction in household waste.',
'CELPIP often includes civic and environmental topics where "policy" appears in the stimulus.', 2
FROM vocabulary_words WHERE headword = 'policy';

-- ── principle ────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'The principle of equal opportunity should apply to every area of public life, including education and employment.',
'Note: "principle" (rule) vs "principal" (main/head) — a common spelling confusion in exams.', 1
FROM vocabulary_words WHERE headword = 'principle';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Reading', 'Academic Reading',
'The article outlines the fundamental principles underlying democratic governance in modern nation states.',
'Watch for "fundamental," "guiding," or "basic" before "principle" — common in academic texts.', 2
FROM vocabulary_words WHERE headword = 'principle';

-- ── process ──────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 1',
'The diagram illustrates the process by which rainwater is filtered and made safe for human consumption.',
'Task 1 process diagrams require "process" repeatedly — "the process involves," "at each stage of the process."', 1
FROM vocabulary_words WHERE headword = 'process';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Listening', 'Part 2',
'The guide explained the step-by-step process for submitting a successful student visa application.',
'Part 2 often describes procedures or processes — listen for sequence words alongside this noun.', 2
FROM vocabulary_words WHERE headword = 'process';

-- ── significant ──────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'There has been a significant increase in the number of people choosing to work from home in recent years.',
'One of the highest-frequency adjectives in academic writing — pair with "increase/decrease/change/role."', 1
FROM vocabulary_words WHERE headword = 'significant';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 1',
'The chart reveals a significant decline in manufacturing employment between 2000 and 2020.',
'In Task 1, "significant" avoids vague words like "big" or "large" and raises the band score.', 2
FROM vocabulary_words WHERE headword = 'significant';

-- ── structure ────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'A well-structured essay presents a clear introduction, logically ordered body paragraphs, and a concise conclusion.',
'Knowing this word helps students self-evaluate their own writing before submission.', 1
FROM vocabulary_words WHERE headword = 'structure';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Reading', 'Academic Reading',
'The author examines the social structure of ancient civilisations in the opening section of the text.',
'Used in Reading passages about history, sociology, or politics — often appears in questions too.', 2
FROM vocabulary_words WHERE headword = 'structure';

-- ── suggest ──────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'Some experts suggest that excessive use of technology is causing people to become less socially connected.',
'"Some experts suggest that" is a natural way to introduce an opposing viewpoint without committing to it.', 1
FROM vocabulary_words WHERE headword = 'suggest';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Speaking', 'Part 3',
'Many urban planners suggest that green spaces are essential for improving quality of life in cities.',
'In Speaking, using "suggest" to attribute ideas to experts sounds more mature than "people think."', 2
FROM vocabulary_words WHERE headword = 'suggest';

-- ── therefore ────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 2',
'Public transport is chronically underfunded and, therefore, many commuters feel compelled to drive instead.',
'Punctuate correctly: use a comma before and after "therefore" when placed mid-sentence.', 1
FROM vocabulary_words WHERE headword = 'therefore';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'PTE', 'Writing', 'Essay',
'The available data is limited in scope; therefore, it is premature to draw definitive conclusions.',
'PTE Academic essays reward precise logical connectors — "therefore" scores better than "so."', 2
FROM vocabulary_words WHERE headword = 'therefore';

-- ── vary ─────────────────────────────────────────────────────────────────────
INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Writing', 'Task 1',
'Temperatures varied significantly across the four regions, with the north recording the highest values.',
'Essential for Task 1 — use "vary" instead of "change" when comparing data across categories.', 1
FROM vocabulary_words WHERE headword = 'vary';

INSERT INTO word_test_usages (word_id, exam_type, skill, sub_section, example_sentence, context_note, sort_order)
SELECT id, 'IELTS', 'Reading', 'Academic Reading',
'Attitudes towards work-life balance vary considerably between different age groups and cultural backgrounds.',
'A common sentence structure in comparison passages — signals a contrast or range.', 2
FROM vocabulary_words WHERE headword = 'vary';
