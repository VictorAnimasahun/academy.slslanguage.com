#!/usr/bin/env python3
"""
Builds migration 179: Class 3 of the IELTS Academic Crash Course (IELTS_Aca_1Mo) becomes a reading class, as Class 2
became a listening class in 178 (founder, 2026-10-28: "Same idea/s as Class 2" -- lessons and a quiz in the first hour,
the practice test in the second). The lessons' exercises are the official IELTS Academic Reading sample tasks (with their
answer keys) from the founder's "Academic Reading" download; the overview lesson has a named video slot for the
founder's remade reading video.

    python3 -I database/quizzes/build_ielts_aca_crash_class3.py > database/migrations/179_ielts_aca_crash_class3_reading.sql

Each quiz question is (question, right answer, [three wrong answers]); the right answer's letter comes from KEYS
(no letter more than twice running), wrong answers fill the other letters in order. Edit here and rebuild; never the .sql.
"""

CODE = 'IELTS_ACA_C3_RQUIZ'
TITLE = 'Class 3 Reading Quiz'
DESC = 'From the Class 3 lessons: the Reading test, True/False/Not Given, completion tasks, matching and multiple choice.'
KEYS = 'CABDBCADBDACBADCABDC'

QUESTIONS = [
    # IELTS Reading: Overview
    ('How is IELTS Academic Reading made up?', 'Three passages, 40 questions, 60 minutes',
     ['Four passages, 40 questions, 60 minutes', 'Three passages, 30 questions, 45 minutes', 'Two passages, 40 questions, 60 minutes']),
    ('In the paper-based test, when must your answers be on the answer sheet?', 'Before the 60 minutes end: there is no extra time to transfer them',
     ['In 10 extra minutes at the end, as in Listening', 'In 2 extra minutes at the end', 'At the end of the whole IELTS test']),
    ('Which passage is usually the longest and hardest?', 'Passage 3',
     ['Passage 1', 'Passage 2', 'They are always equally difficult']),
    ('Where do IELTS Academic Reading passages come from?', 'Books, journals, magazines and newspapers',
     ['Notices, adverts and company handbooks', 'Only university textbooks', 'Transcripts of university lectures']),
    ('What is skimming?', 'Reading quickly for the main idea',
     ['Looking for one specific detail, such as a date', 'Reading every word slowly and carefully', 'Reading only the questions']),
    ('What is scanning?', 'Looking quickly for one specific detail, such as a name or a number',
     ['Reading quickly for the main idea', 'Reading the first sentence of each paragraph', 'Checking your answers for spelling']),
    ('Which question types do NOT necessarily follow the order of the passage?', 'Matching headings, matching information and matching features',
     ['True/False/Not Given', 'Sentence and summary completion', 'Multiple choice']),
    # True/False/Not Given & Yes/No/Not Given
    ('What is the difference between True/False/Not Given and Yes/No/Not Given?', 'TFNG asks about the information in the passage; YNNG asks about the writer\'s views or claims',
     ['TFNG asks about the writer\'s views; YNNG asks about facts', 'YNNG is only used in General Training', 'There is none: they are two names for the same thing']),
    ('The passage says the opposite of the statement. What is the answer?', 'FALSE',
     ['TRUE', 'NOT GIVEN', 'Either FALSE or NOT GIVEN']),
    ('Passage: "Fertiliser use doubled in Denmark between 1960 and 1985." Statement: "Danish farmers used more fertiliser than British farmers." What is the answer?', 'NOT GIVEN',
     ['TRUE', 'FALSE', 'TRUE, because the use doubled']),
    ('You know from your own knowledge that a statement is true, but the passage does not mention it. What is the answer?', 'NOT GIVEN',
     ['TRUE', 'FALSE', 'Either TRUE or NOT GIVEN']),
    ('The writer says smoking causes "30 per cent of all deaths from cancer". Statement: "Thirty per cent of deaths in the United States are caused by smoking." What is the answer?', 'NO: the writer means 30% of cancer deaths, not of all deaths',
     ['YES: the passage gives the same figure', 'NOT GIVEN: the passage does not mention the United States', 'YES: smoking causes cancer']),
    # Completion
    ('The instruction says "NO MORE THAN THREE WORDS". You write four words with the right meaning. What happens?', 'The answer is wrong',
     ['You get the mark, because the meaning is right', 'You get half a mark', 'You get the mark if one of the words is "the"']),
    ('The passage says "temperate-climate Spanish species". The table\'s gap is under "Preferred climate". What do you write?', 'temperate',
     ['temperature', 'temperate-climate Spanish', 'cool']),
    ('In a completion task that uses words from the passage, how should you write them?', 'Exactly as they are in the passage, spelt the same way',
     ['In your own words, to show you understood', 'As synonyms of the passage\'s words', 'In any form, as long as the meaning is right']),
    ('In a summary completed from a list of phrases, what is usually true?', 'There are more phrases than gaps, and they paraphrase the passage',
     ['Each phrase is copied word for word from the passage', 'There is exactly one phrase for each gap', 'You write the whole phrase in the gap']),
    ('The gap comes straight after the word "a". What does the gap probably need?', 'A singular noun, perhaps with an adjective',
     ['A plural noun', 'A verb', 'A number']),
    # Matching & multiple choice
    ('In matching headings, what does the right heading describe?', 'The main idea of the whole paragraph or section',
     ['One example given in the paragraph', 'The first sentence of the paragraph only', 'A word that appears in the paragraph']),
    ('"Which paragraph contains the following information?" What is true of this question type?', 'The answers are not in passage order, and a letter may be used more than once',
     ['The answers follow the passage order', 'Each letter is used once only', 'The answer is always the first or last paragraph']),
    ('In multiple choice, what are the wrong options often like?', 'Half right: the right words but the wrong number, time or thing',
     ['Not mentioned in the passage at all', 'Longer than the right option', 'Copied exactly from the passage']),
]

def q(s):
    return "'" + s.replace("\\", "\\\\").replace("'", "''") + "'"

def build():
    assert len(KEYS) >= len(QUESTIONS)
    out = []
    out.append(f"""-- ============================================================
-- Migration 179 -- IELTS Academic Crash Course, Class 3: a reading class (lessons + quiz, then Reading Test 1)
--
-- Generated by database/quizzes/build_ielts_aca_crash_class3.py -- edit that, not this file.
-- Founder, 2026-10-28: "Let's move on to Class 3. Same idea/s as Class 2" (178): lessons and a quiz in the first hour,
-- the practice test in the second. Class 3 had Reading Test 1 first with no reading teaching, then a Writing Task 1
-- lesson. Class 3 becomes:
--   1 IELTS Reading: Overview                       lesson  (named slot for the founder's remade video + format/timing)
--   2 True/False/Not Given & Yes/No/Not Given       lesson  (official sample: identifying writer's views + a drill)
--   3 Completion: Notes, Summary, Table & Diagram   lesson  (official samples: table + diagram completion, a summary)
--   4 Matching & Multiple Choice                    lesson  (official samples: information, headings, MC, features)
--   5 Class 3 Reading Quiz                          quiz    ({CODE}, {len(QUESTIONS)} questions, pass 70%)
--   6 Reading Test 1                                practice test (unchanged)
-- Trend Vocabulary & Paraphrasing (a Writing Task 1 lesson) leaves Class 3 for Class 5, straight before Writing Test 1
-- (Timed) -- only where Class 5 holds Writing Test 1; otherwise it stays in Class 3, after the test. Its page is now
-- class05_p2_trend-vocabulary-paraphrasing.php wherever it sits.
-- Found by folder + week + class, never by id; class ids kept (progress stays). Positions are parked at +100 first
-- (lesson_parts is unique on lesson_id + part_order). Re-runnable. MySQL 5.7-safe, no lookups in system databases.
-- Needs the files: courses/IELTS_Aca_1Mo/lessons/class03_p1..p4_*.php, class05_p2_trend-vocabulary-paraphrasing.php,
-- listening_kit.php. The SELECT at the end lists Class 3 and Class 5.
-- ============================================================

SET NAMES utf8mb4;

-- ---------------- The quiz ----------------
INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active) VALUES ({q(CODE)}, {q(TITLE)}, {q(DESC)}, 'IELTS_Academic', 20, {len(QUESTIONS)}, 1) ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description), test_type = VALUES(test_type), total_questions = VALUES(total_questions), is_active = 1;""")
    for i, (text, right, wrongs) in enumerate(QUESTIONS, 1):
        key = KEYS[i - 1]
        letters = 'ABCD'
        opts, w = {}, list(wrongs)
        for L in letters:
            opts[L] = right if L == key else w.pop(0)
        out.append(f"INSERT INTO questions (test_id, question_number, question_text, question_type, points, display_order) SELECT t.id, {i}, {q(text)}, 'multiple_choice_single', 1.0, {i} FROM tests t WHERE t.code = {q(CODE)} AND NOT EXISTS (SELECT 1 FROM questions x WHERE x.test_id = t.id AND x.question_number = {i});")
        out.append(f"UPDATE questions x JOIN tests t ON t.id = x.test_id SET x.question_text = {q(text)} WHERE t.code = {q(CODE)} AND x.question_number = {i};")
        for n, L in enumerate(letters, 1):
            out.append(f"INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) SELECT x.id, '{L}', {q(opts[L])}, {1 if L == key else 0}, {n} FROM questions x JOIN tests t ON t.id = x.test_id WHERE t.code = {q(CODE)} AND x.question_number = {i} ON DUPLICATE KEY UPDATE option_text = VALUES(option_text), is_correct = VALUES(is_correct), display_order = VALUES(display_order);")

    out.append(f"DELETE o FROM question_options o JOIN questions x ON x.id = o.question_id JOIN tests t ON t.id = x.test_id WHERE t.code = {q(CODE)} AND x.question_number > {len(QUESTIONS)};")
    out.append(f"DELETE x FROM questions x JOIN tests t ON t.id = x.test_id WHERE t.code = {q(CODE)} AND x.question_number > {len(QUESTIONS)};")
    L = "courses/IELTS_Aca_1Mo/lessons/"
    TREND = 'Trend Vocabulary & Paraphrasing'
    cls = lambda week, order: f"(SELECT l.id FROM lessons l JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = l.course_id WHERE c.folder_name = 'IELTS_Aca_1Mo' AND m.module_order = {week} AND l.lesson_order = {order})"
    out.append(f"""
-- ---------------- Class 3 (Week 2, first class) ----------------
-- The classes, as variables (a scalar subquery, no derived tables). NULL on a course without them: every statement
-- below then matches nothing.
SET @c3 := {cls(2, 1)};
SET @c5 := {cls(3, 1)};
-- Trend Vocabulary goes to Class 5 only if Class 5 holds Writing Test 1, and lands straight before it.
SET @wt := (SELECT MIN(part_order) FROM lesson_parts WHERE lesson_id = @c5 AND title = 'Writing Test 1 (Timed)');

-- The page was renamed: point every row at the new file, wherever it sits.
UPDATE lesson_parts SET file_path = '{L}class05_p2_trend-vocabulary-paraphrasing.php' WHERE file_path = '{L}class03_p2_trend-vocabulary-paraphrasing.php';

-- Trend Vocabulary: Class 3 -> Class 5, in Writing Test 1's place (it and everything after move one down, via +100).
UPDATE lesson_parts SET part_order = part_order + 100 WHERE lesson_id = @c5 AND @wt IS NOT NULL AND part_order >= @wt AND part_order < 100
   AND EXISTS (SELECT 1 FROM (SELECT lesson_id, title FROM lesson_parts) y WHERE y.lesson_id = @c3 AND y.title = {q(TREND)});
UPDATE lesson_parts SET lesson_id = @c5, part_order = @wt WHERE lesson_id = @c3 AND title = {q(TREND)} AND @wt IS NOT NULL;
UPDATE lesson_parts SET part_order = part_order - 99 WHERE lesson_id = @c5 AND part_order > 100;

-- Class 3's items park at +100; the six below then take places 1-6.
UPDATE lesson_parts SET part_order = part_order + 100 WHERE lesson_id = @c3 AND part_order < 100;""")
    items = [
        (1, 'IELTS Reading: Overview', 'lesson', L + 'class03_p1_ielts-reading-overview.php', None, None),
        (2, 'True/False/Not Given & Yes/No/Not Given', 'lesson', L + 'class03_p2_true-false-not-given.php', None, None),
        (3, 'Completion: Notes, Summary, Table & Diagram', 'lesson', L + 'class03_p3_completion-tasks.php', None, None),
        (4, 'Matching & Multiple Choice', 'lesson', L + 'class03_p4_matching-multiple-choice.php', None, None),
        (5, TITLE, 'quiz', f'resources/quizzes/class_quiz.php?test_code={CODE}', CODE, 70),
        (6, 'Reading Test 1', 'practice_test', 'resources/practice_tests/ielts_reading_academic_c17_t1.php', 'IELTS_PT_R_ACA_C17T1', None),
    ]
    for order, title, kind, path, code, pp in items:
        out.append(f"""UPDATE lesson_parts SET part_order = {order}, kind = '{kind}', status = 'ready', file_path = {q(path)}, test_code = {q(code) if code else 'NULL'}, pass_percent = {pp if pp else 'NULL'} WHERE lesson_id = @c3 AND title = {q(title)};
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path, test_code, pass_percent)
SELECT @c3, {order}, {q(title)}, '{kind}', 'ready', {q(path)}, {q(code) if code else 'NULL'}, {pp if pp else 'NULL'} FROM DUAL
 WHERE @c3 IS NOT NULL AND NOT EXISTS (SELECT 1 FROM (SELECT lesson_id, title FROM lesson_parts) y WHERE y.lesson_id = @c3 AND y.title = {q(title)});""")
    out.append(f"""-- Anything else still parked in Class 3 (an item this migration doesn't know) goes after the test, in its order.
UPDATE lesson_parts SET part_order = part_order - 93 WHERE lesson_id = @c3 AND part_order > 100;

-- The classes' titles list their items (rendered by includes/lesson_title.php); Class 3 is now a reading class.
UPDATE lessons SET title = (SELECT GROUP_CONCAT(p.title ORDER BY p.part_order SEPARATOR ' + ') FROM lesson_parts p WHERE p.lesson_id = @c3), icon = 'bi-book', duration_minutes = 120 WHERE id = @c3;
UPDATE lessons SET title = (SELECT GROUP_CONCAT(p.title ORDER BY p.part_order SEPARATOR ' + ') FROM lesson_parts p WHERE p.lesson_id = @c5) WHERE id = @c5 AND @wt IS NOT NULL;

SELECT CASE WHEN p.lesson_id = @c3 THEN 'Class 3' ELSE 'Class 5' END AS class, p.part_order, p.title, p.kind, p.status, p.file_path
  FROM lesson_parts p WHERE p.lesson_id IN (@c3, @c5) ORDER BY p.lesson_id = @c5, p.part_order;
SELECT COUNT(*) AS quiz_questions FROM questions x JOIN tests t ON t.id = x.test_id WHERE t.code = {q(CODE)};""")
    return '\n'.join(out) + '\n'

if __name__ == '__main__':
    import sys
    sys.stdout.write(build())
    print(f'{CODE}: {len(QUESTIONS)} questions, keys {KEYS[:len(QUESTIONS)]}', file=sys.stderr)
