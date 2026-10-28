#!/usr/bin/env python3
"""
Builds migration 178: Class 2 of the IELTS Academic Crash Course (IELTS_Aca_1Mo) becomes a listening class
(founder, 2026-10-28: "a lesson or more and then a quiz for each class day's first hour, then a practice test as the
item for the second hour"; Class 2's lessons, quiz and exercises built from the founder's two listening videos and
the SL1/SL5 decks).

    python3 -I database/quizzes/build_ielts_aca_crash_class2.py > database/migrations/178_ielts_aca_crash_class2_listening.sql

Each quiz question is (question, right answer, [three wrong answers]); the right answer's letter comes from KEYS
(no letter more than twice running), wrong answers fill the other letters in order. Edit here and rebuild; never the .sql.
"""

CODE = 'IELTS_ACA_C2_LQUIZ'
TITLE = 'Class 2 Listening Quiz'
DESC = 'From the Class 2 videos and lessons: the test, question types, form completion and multiple choice.'
KEYS = 'BDACADBCABDCBACDABCA'

# Every question comes from the founder's materials (2026-10-28: "very important stuff were discussed in both set of
# materials"): the two videos (transcribed and read frame by frame), the SL1 deck and the SL5 deck.
QUESTIONS = [
    # LISTENING_OVERVIEW video
    ('How many questions are there in the IELTS Listening test?', '40, with 10 in each part',
     ['30, with 10 in each of three parts', '40, with more in the later parts', '50, with 10 in each of five parts']),
    ('How are the paper-based and computer-based Listening tests different?', 'They are not: each format has exactly the same test and questions',
     ['The computer test has fewer questions', 'The computer test is shorter', 'Only the paper test has Part 4']),
    ('Which accents can you hear in the recordings?', 'British, American, Canadian and Australian, among others',
     ['Only British English', 'Only British and American English', 'Only the accent of the country you take the test in']),
    ('How long do you usually get to read the questions before each part starts?', 'About 30 to 45 seconds',
     ['About 5 seconds', 'About 2 minutes', 'There is no reading time']),
    ('What has been true of the start of Part 1 since January 2020?', 'There is no example, so you must listen carefully from the very beginning',
     ['There is an example first that you can ignore', 'The first question is always a practice question', 'The recording is played twice']),
    ('What do you hear in Part 1?', 'A conversation between two people in an everyday situation, such as buying a ticket or asking about a course',
     ['One person talking about a place, such as a new gym', 'Up to four people discussing a college project', 'A lecturer talking about an academic subject']),
    ('What do you hear in Part 2?', 'One person giving information about a product, place or service, such as a new gym or a school map',
     ['Two people buying and selling a train ticket', 'Three students discussing their research', 'A professor talking about positive psychology']),
    ('What do you hear in Part 3?', 'Up to four people talking in an education or training situation',
     ['One person describing a map for new students', 'Two people arranging a delivery', 'A lecturer talking about rainforests']),
    ('What do you hear in Part 4?', 'One person, often a lecturer, talking about an academic subject',
     ['Two people in an everyday conversation', 'A group of students discussing a project', 'A guide describing a museum']),
    ('In the paper-based test, what happens after the last recording?', 'You have 10 minutes to transfer your answers to the answer sheet',
     ['You hear the recordings again', 'You have 2 minutes to check your answers', 'The test ends immediately']),
    ('In the computer-based test, when can you review your answers?', 'After each part, and for 2 minutes at the end of the test',
     ['Only for 10 minutes at the end', 'Never: answers are final', 'Only after Part 4']),
    # LISTENING_QUESTION_TYPES video
    ('A multiple-choice question has more than three options. What must you do first?', 'Read the instructions: you may have to choose more than one option',
     ['Choose one option, as always', 'Choose all the options you hear', 'Skip it and come back later']),
    ('In plan, map or diagram labelling, what do you usually do?', 'Choose the correct label from a list of options next to the picture',
     ['Draw the missing parts of the map', 'Write a description of the plan', 'Number the rooms in order']),
    ('The instructions say "NO MORE THAN TWO WORDS AND/OR A NUMBER". What happens if you write three words?', 'You lose the mark, even if the meaning is right',
     ['You still get the mark if the meaning is right', 'You get half a mark', 'Nothing, if the third word is "the"']),
    ('How is a hyphenated word such as "long-term" counted?', 'As one word',
     ['As two words', 'As three words', 'It is not allowed in an answer']),
    ('You are not sure of an answer. What should you do?', 'Write something or choose an option: you lose no marks for a wrong answer',
     ['Leave it blank so you are not penalised', 'Write two possible answers', 'Write a question mark']),
    # SL1 deck
    ('Before a form-completion task, what should you do in the reading time?', 'Highlight the keywords and predict what will go in each gap',
     ['Write a guess in every gap', 'Read only the first line', 'Count the gaps']),
    ('A form row says "Mobile: ____" and a sentence says "Jason\'s mobile phone number is ____". What is true?', 'Both ask for the same information; the sentence must also be grammatically correct',
     ['They ask for different information', 'Only the form needs exact spelling', 'The sentence needs a longer answer']),
    # SL5 deck
    ('In a multiple-choice question, what will you hear in the recording?', 'Information about all of the options, but only one answers the question',
     ['Only the correct option', 'The exact words of the correct option', 'Nothing about the wrong options']),
    ('Which keywords are best to underline in "John Lancer\'s idea for a cookery school came from"?', 'John Lancer\'s, idea and came from',
     ['idea for a cookery school', 'for, a and from', 'Only "John Lancer\'s"']),
]

def q(s):
    return "'" + s.replace("\\", "\\\\").replace("'", "''") + "'"

def build():
    assert len(KEYS) >= len(QUESTIONS)
    out = []
    out.append(f"""-- ============================================================
-- Migration 178 -- IELTS Academic Crash Course, Class 2: a listening class (lessons + quiz, then Listening Test 1)
--
-- Generated by database/quizzes/build_ielts_aca_crash_class2.py -- edit that, not this file.
-- Founder, 2026-10-28: a class is two hours -- "a lesson or more and then a quiz ... first hour, then a practice test
-- as the item for the second hour". The course had Listening Test 1 in Class 2 with no listening teaching before it
-- (the order was copied from the 2-Month Masterclass in 134 and patched since, never designed). Class 2 becomes:
--   1 IELTS Listening: Overview          lesson  (the founder's LISTENING_OVERVIEW video + the test's format/marking)
--   2 Listening Question Types           lesson  (LISTENING_QUESTION_TYPES video + a guide, with a practice)
--   3 Form Completion & Gap-fill         lesson  (SL1 deck + its recording: predict-the-gap and Jason's form)
--   4 Multiple Choice                    lesson  (SL5 deck + its recording: underline, paraphrase, Questions 1-4)
--   5 Class 2 Listening Quiz             quiz    ({CODE}, {len(QUESTIONS)} questions, pass 70%)
--   6 Listening Test 1                   practice test (unchanged)
-- Writing Task Overview leaves Class 2 for Week 3's first class (Class 5), straight before Writing Test 1 (Timed), as
-- the Masterclasses did in 157 -- only where that class holds Writing Test 1; otherwise it stays.
-- Found by folder + week + class, never by id; class ids kept (progress stays). Positions are parked at +100 first
-- (lesson_parts is unique on lesson_id + part_order). Re-runnable. MySQL 5.7-safe, no information_schema.
-- Needs the files: courses/IELTS_Aca_1Mo/lessons/class02_p1..p4_*.php, class05_p1_writing-task-overview.php,
-- listening_kit.php, assets/audio/ielts_listening_class2/, assets/video/ielts_listening/. The SELECT at the end lists
-- Class 2 and Class 5.
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
    cls = lambda week, order: f"(SELECT l.id FROM lessons l JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = l.course_id WHERE c.folder_name = 'IELTS_Aca_1Mo' AND m.module_order = {week} AND l.lesson_order = {order})"
    out.append(f"""
-- ---------------- Class 2 (Week 1, second class) ----------------
-- The classes, as variables (a scalar subquery, no derived tables). NULL on a course without them: every statement
-- below then matches nothing.
SET @c2 := {cls(1, 2)};
SET @c5 := {cls(3, 1)};
-- Class 5 only takes Writing Task Overview if it holds Writing Test 1.
SET @c5ok := (SELECT COUNT(*) FROM lesson_parts WHERE lesson_id = @c5 AND title = 'Writing Test 1 (Timed)');

-- Writing Task Overview: Class 2 -> Class 5, first item (Class 5's items move one place down, via +100).
UPDATE lesson_parts SET part_order = part_order + 100 WHERE lesson_id = @c5 AND @c5ok > 0 AND part_order < 100
   AND EXISTS (SELECT 1 FROM (SELECT lesson_id, title FROM lesson_parts) y WHERE y.lesson_id = @c2 AND y.title = 'Writing Task Overview');
UPDATE lesson_parts SET lesson_id = @c5, part_order = 1, file_path = '{L}class05_p1_writing-task-overview.php'
 WHERE lesson_id = @c2 AND title = 'Writing Task Overview' AND @c5ok > 0;
UPDATE lesson_parts SET part_order = part_order - 99 WHERE lesson_id = @c5 AND part_order > 100;

-- Class 2's other items park at +100, Listening Test 1 then takes place 6.
UPDATE lesson_parts SET part_order = part_order + 100 WHERE lesson_id = @c2 AND part_order < 100;
UPDATE lesson_parts SET part_order = 6 WHERE lesson_id = @c2 AND title = 'Listening Test 1';""")
    items = [
        (1, 'IELTS Listening: Overview', 'lesson', L + 'class02_p1_ielts-listening-overview.php', None, None),
        (2, 'Listening Question Types', 'lesson', L + 'class02_p2_listening-question-types.php', None, None),
        (3, 'Form Completion & Gap-fill', 'lesson', L + 'class02_p3_form-completion-gap-fill.php', None, None),
        (4, 'Multiple Choice', 'lesson', L + 'class02_p4_multiple-choice.php', None, None),
        (5, TITLE, 'quiz', f'resources/quizzes/class_quiz.php?test_code={CODE}', CODE, 70),
    ]
    for order, title, kind, path, code, pp in items:
        out.append(f"""UPDATE lesson_parts SET part_order = {order}, kind = '{kind}', status = 'ready', file_path = {q(path)}, test_code = {q(code) if code else 'NULL'}, pass_percent = {pp if pp else 'NULL'} WHERE lesson_id = @c2 AND title = {q(title)};
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path, test_code, pass_percent)
SELECT @c2, {order}, {q(title)}, '{kind}', 'ready', {q(path)}, {q(code) if code else 'NULL'}, {pp if pp else 'NULL'} FROM DUAL
 WHERE @c2 IS NOT NULL AND NOT EXISTS (SELECT 1 FROM (SELECT lesson_id, title FROM lesson_parts) y WHERE y.lesson_id = @c2 AND y.title = {q(title)});""")
    out.append(f"""-- Anything else still parked in Class 2 (an item this migration doesn't know) goes after the test, in its order.
UPDATE lesson_parts SET part_order = part_order - 93 WHERE lesson_id = @c2 AND part_order > 100;

-- The classes' titles list their items (rendered by includes/lesson_title.php); Class 2 is now a listening class.
UPDATE lessons SET title = 'IELTS Listening: Overview + Listening Question Types + Form Completion & Gap-fill + Multiple Choice + {TITLE} + Listening Test 1', icon = 'bi-headphones', duration_minutes = 120 WHERE id = @c2;
UPDATE lessons SET title = (SELECT GROUP_CONCAT(p.title ORDER BY p.part_order SEPARATOR ' + ') FROM lesson_parts p WHERE p.lesson_id = @c5) WHERE id = @c5 AND @c5ok > 0;

SELECT CASE WHEN p.lesson_id = @c2 THEN 'Class 2' ELSE 'Class 5' END AS class, p.part_order, p.title, p.kind, p.status, p.file_path
  FROM lesson_parts p WHERE p.lesson_id IN (@c2, @c5) ORDER BY p.lesson_id = @c5, p.part_order;
SELECT COUNT(*) AS quiz_questions FROM questions x JOIN tests t ON t.id = x.test_id WHERE t.code = {q(CODE)};""")
    return '\n'.join(out) + '\n'

if __name__ == '__main__':
    import sys
    sys.stdout.write(build())
    print(f'{CODE}: {len(QUESTIONS)} questions, keys {KEYS[:len(QUESTIONS)]}', file=sys.stderr)
