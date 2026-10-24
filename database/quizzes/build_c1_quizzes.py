#!/usr/bin/env python3
"""
Builds migration 170: the Class 1 exam quizzes of the four crash courses (founder, 2026-10-24: "write this one, make it
20 questions, don't make it dumb please and don't use the same letter as the right answer").

    python3 -I database/quizzes/build_c1_quizzes.py > database/migrations/170_crash_course_exam_quizzes.sql

Each question is written as (question, right answer, [three wrong answers]). The quiz page shows options A-D in stored
order, so the right answer's letter comes from KEYS: five of each letter per quiz, never the same letter three times in
a row, shifted per quiz. Wrong answers fill the other letters in a fixed, seeded order. The questions check the exam
introduction (includes/exam_introduction.php) by applying it, not by repeating it. Edit here and rebuild; never the .sql.
"""
import random
import sys

KEYS = 'BDACABDCCADBDACBACBD'   # 5 x A, B, C, D; no letter three times running

QUIZZES = {
    'IELTS_ACA_C1_QUIZ': {
        'folder': 'IELTS_Aca_1Mo', 'type': 'IELTS_Academic', 'item': 'IELTS Academic Introduction Quiz',
        'title': 'Class 1 Quiz — IELTS Academic Introduction',
        'qs': [
            ('A university asks for an overall band of 6.5. Your results are Listening 6.5, Reading 6.0, Writing 6.0 and Speaking 6.0. What is your overall band?',
             '6.0', ['6.5', '6.125', '6.25']),
            ('Your results are Listening 6.5, Reading 6.5, Writing 6.0 and Speaking 6.0. What is your overall band?',
             '6.5', ['6.0', '6.25', '7.0']),
            ('A university asks for 6.5 overall with no part below 6.0. You score Listening 7.5, Reading 7.0, Writing 5.5 and Speaking 7.0. Do you meet the requirement?',
             'No: your overall is 7.0, but Writing is below 6.0',
             ['Yes: your overall band is 7.0', 'Yes: one part below 6.0 is allowed if the overall is higher', 'No: your overall is only 6.5']),
            ('You have 10 minutes left of your Writing test and Task 2 is not finished. Which choice protects your band best?',
             'Finish Task 2, because it counts twice as much as Task 1',
             ['Go back and polish Task 1, because it is shorter', 'Stop writing: unfinished essays are not marked', 'Split the time equally between the two tasks']),
            ('In the paper-based test, you finish Reading with six answers still written on the question paper. What happens?',
             'Those six are not marked: there is no extra time to copy answers in Reading',
             ['You get 10 minutes to copy them, as in Listening', 'The examiner marks them from the question paper', 'You get 2 minutes to check and copy them']),
            ('What happens at the end of the computer-delivered Listening test?',
             'You get 2 minutes to check your answers',
             ['You get 10 minutes to copy your answers', 'You can play one recording again', 'Nothing: the Reading test starts immediately']),
            ('In which part of the Listening test are you most likely to hear a single speaker giving a university lecture?',
             'Part 4', ['Part 1', 'Part 2', 'Part 3']),
            ('A Reading question asks whether a statement agrees with the claims or opinions of the writer. Which question type is this?',
             'Yes / No / Not Given', ['True / False / Not Given', 'Matching headings', 'Matching features']),
            ('A Writing Task 1 answer describes a line graph accurately and ends: "In my opinion, the government should invest more in public transport." What is wrong with it?',
             'Task 1 asks you to report and compare the data, not give opinions',
             ['Nothing: a personal opinion earns extra marks', 'It should have started with the opinion', 'Task 1 must be written in the past tense only']),
            ('Your Task 2 essay is 220 words long and answers the question well. What is the problem?',
             'It is under the 250-word minimum, which costs marks',
             ['There is none: 200 words is the minimum', 'Only Task 1 has a minimum length', 'Length never matters if the ideas are strong']),
            ('A nurse needs an English test to register with a professional body in the UK. Which test does the body most likely ask for?',
             'IELTS Academic', ['IELTS General Training', 'Either one: the scores are the same', 'Neither: nurses take a separate test']),
            ('Which two parts of IELTS are exactly the same in the Academic and General Training tests?',
             'Listening and Speaking', ['Reading and Writing', 'Listening and Reading', 'Writing and Speaking']),
            ('Your Listening, Reading and Writing tests are on a Saturday and your Speaking test is the following Wednesday. Is that normal?',
             'Yes: Speaking can be up to a week before or after the other three',
             ['No: all four parts must be on the same day', 'No: Speaking always comes first', 'Yes, but only on the computer-delivered test']),
            ('Which criterion is used to mark Speaking but not Writing?', 'Pronunciation',
             ['Lexical Resource', 'Grammatical Range and Accuracy', 'Coherence']),
            ('In Speaking Part 2 you are given a topic card. What happens next?',
             'You have 1 minute to prepare, then speak for up to 2 minutes',
             ['You speak straight away for 4 minutes', 'You have 2 minutes to prepare, then speak for 1 minute', 'You write notes for 5 minutes and read them aloud']),
            ('How is Speaking Part 3 different from Part 1?',
             'It discusses wider, more abstract questions linked to the Part 2 topic',
             ['It is a second long turn on a new card', 'It is about you, your home and your work', 'It is a role play with the examiner']),
            ('You get 30 out of 40 in Reading. How does this appear in your results?',
             'As a band from 0 to 9, converted from your 30 correct answers',
             ['As 75%', 'As band 7.5, because 30 divided by 4 is 7.5', 'It is not shown: only the overall band is reported']),
            ('A Reading passage is about volcanology, which you have never studied. What do you need?',
             'Only the text: the passages are written for a general reader and the answers are in them',
             ['Background knowledge of geology', 'A dictionary, which is allowed in Reading', 'To skip it: specialist passages are optional']),
            ('Which statement about the IELTS Speaking test is true?',
             'It is a recorded interview with an examiner',
             ['You record your answers into a microphone with no examiner', 'It is marked by a computer', 'It lasts about 30 minutes']),
            ('Two candidates get the same overall band. Candidate A scored 7.0 in Task 1 and 6.0 in Task 2; Candidate B scored 6.0 in Task 1 and 7.0 in Task 2. Who has the higher Writing band?',
             'Candidate B, because Task 2 counts twice as much', ['Candidate A, because Task 1 comes first', 'Neither: the tasks count equally', 'It depends on the word count']),
        ],
    },
    'IELTS_GT_C1_QUIZ': {
        'folder': 'IELTS_Gen_1Mo', 'type': 'IELTS_General', 'item': 'IELTS General Introduction Quiz',
        'title': 'Class 1 Quiz — IELTS General Training Introduction',
        'qs': [
            ('You are applying to immigrate to Canada and the programme accepts IELTS. Which IELTS test do you take?',
             'IELTS General Training', ['IELTS Academic', 'Either: immigration accepts both', 'IELTS Life Skills, which has no Reading test']),
            ('Your results are Listening 6.0, Reading 6.5, Writing 6.5 and Speaking 6.5. What is your overall band?',
             '6.5', ['6.0', '6.375', '7.0']),
            ('Your results are Listening 6.5, Reading 6.0, Writing 6.0 and Speaking 6.0. What is your overall band?',
             '6.0', ['6.5', '6.125', '6.25']),
            ('An immigration programme needs at least 6.0 in every part. You score Listening 7.0, Reading 5.5, Writing 6.5 and Speaking 7.0. Do you meet it?',
             'No: Reading is below 6.0, even though the overall is 6.5',
             ['Yes: the overall band is 6.5', 'Yes: the other parts make up for Reading', 'No: the overall is below 6.0']),
            ('Writing Task 1 asks you to write to a company you have never dealt with, to complain about a faulty product. Which tone should you use?',
             'Formal', ['Informal, to sound friendly', 'Any tone: only the content is marked', 'Academic, as in an essay']),
            ('Writing Task 1 asks you to write to a close friend to invite them to stay with you. Which opening fits?',
             '"Dear Sam,"', ['"Dear Sir or Madam,"', '"To whom it may concern,"', '"Dear Mr Sam Ade,"']),
            ('Which task would you never see in IELTS General Training Writing?',
             'Describing a bar chart', ['Writing a letter of complaint', 'Writing an essay giving your opinion', 'Writing a letter asking for information']),
            ('In the General Training Reading test, where are you most likely to find a text about a company\'s training policy?',
             'Section 2', ['Section 1', 'Section 3', 'In Listening Part 4, not Reading']),
            ('Which section of General Training Reading usually has the longest and hardest text?',
             'Section 3', ['Section 1', 'Section 2', 'All three are the same length']),
            ('Is a raw Reading score converted to the same band in General Training as in Academic?',
             'No: General Training needs more correct answers for the same band',
             ['Yes: 30 out of 40 is the same band in both', 'No: Academic needs more correct answers for the same band', 'There is no conversion: the score is a percentage']),
            ('You finish Writing with 12 minutes left and your letter has 130 words. What should you do?',
             'Add to the letter: it is under the 150-word minimum',
             ['Leave it: 130 is close enough', 'Start Task 2 again from the beginning', 'Nothing: only the essay has a minimum']),
            ('Your essay is excellent but the letter is weak. Which is true?',
             'The essay helps more, because Task 2 counts twice as much as Task 1',
             ['The letter counts more, because it comes first', 'Both count equally', 'The letter is not marked if the essay is strong']),
            ('How many times do you hear each Listening recording?', 'Once', ['Twice', 'Once, and again if you ask', 'Twice in Parts 1 and 2, once in Parts 3 and 4']),
            ('Which two parts of the General Training test are exactly the same as in IELTS Academic?',
             'Listening and Speaking', ['Reading and Writing', 'Listening and Reading', 'Writing and Speaking']),
            ('A Reading question asks whether a statement agrees with the information in the text. Which question type is this?',
             'True / False / Not Given', ['Yes / No / Not Given', 'Matching headings', 'Sentence completion']),
            ('Which criterion is used to mark Speaking but not Writing?', 'Pronunciation',
             ['Lexical Resource', 'Grammatical Range and Accuracy', 'Coherence']),
            ('In Speaking Part 2 you are given a topic card. What happens next?',
             'You have 1 minute to prepare, then speak for up to 2 minutes',
             ['You speak straight away for 4 minutes', 'You have 2 minutes to prepare, then speak for 1 minute', 'The examiner asks you ten short questions about it']),
            ('In the paper-based test, when do you copy your Reading answers onto the answer sheet?',
             'Within the 60 minutes: there is no extra transfer time',
             ['In 10 extra minutes at the end', 'In 2 extra minutes at the end', 'The examiner copies them for you']),
            ('Which Listening part is most likely to be a lecture on an academic subject?',
             'Part 4', ['Part 1', 'Part 2', 'Part 3']),
            ('Your Speaking test is two days before your written tests. Is that allowed?',
             'Yes: Speaking can be up to a week before or after the other three',
             ['No: Speaking is always after Writing', 'No: all four parts are on one day', 'Only if you take the paper-based test']),
        ],
    },
    'CELPIP_C1_QUIZ': {
        'folder': 'CELPIP_Gen_1Mo', 'type': 'CELPIP_General', 'item': 'CELPIP Introduction Quiz',
        'title': 'Class 1 Quiz — CELPIP General Introduction',
        'qs': [
            ('Which organisation accepts CELPIP General for permanent residence and citizenship in Canada?',
             'Immigration, Refugees and Citizenship Canada (IRCC)', ['The British Council', 'Pearson', 'Universities only']),
            ('What is your CELPIP overall score?',
             'There is none: you get a separate level for each of the four parts',
             ['The average of the four levels', 'The lowest of the four levels', 'A score out of 90']),
            ('You score CELPIP level 9 in Reading. What is that on the Canadian Language Benchmarks?',
             'CLB 9', ['CLB 8', 'CLB 10', 'It depends on your other three levels']),
            ('A programme needs CLB 7 in every skill. You get Listening 9, Reading 8, Writing 6 and Speaking 7. Do you meet it?',
             'No: Writing is below 7', ['Yes: your average is 7.5', 'Yes: two parts are well above 7', 'No: Speaking must be at least 8']),
            ('Your score report shows "M" for one part. What does it mean?',
             'There was not enough in your answer to rate', ['Maximum score', 'Medium level, about 6', 'Missed: you were absent for that part']),
            ('In what order do you take the four parts of CELPIP General?',
             'Listening, Reading, Writing, Speaking', ['Speaking, Writing, Reading, Listening', 'Reading, Writing, Listening, Speaking', 'Listening, Speaking, Reading, Writing']),
            ('How long is the whole CELPIP General test, and how is it taken?',
             'About 3 hours, on a computer, in one sitting', ['About 2 hours, on paper', 'About 3 hours, over two days', 'About 4 hours, with an interview on another day']),
            ('During a Listening recording you miss an important detail. What can you do?',
             'Nothing: you cannot replay a recording', ['Replay it once', 'Ask for it to be repeated', 'Read the transcript after answering']),
            ('Writing Task 1 asks you to email a store manager about a faulty product. What is part of your mark besides the content?',
             'Using a tone that fits the person and situation', ['Using as many advanced words as possible', 'Writing at least 300 words', 'Writing in Canadian spelling only']),
            ('What do you do in Writing Task 2?',
             'Choose one of two options in a survey and give your reasons', ['Write a story about a past event', 'Summarise a news article', 'Describe a chart']),
            ('How long do you have for Writing Task 1, and how much should you write?',
             '27 minutes; 150–200 words', ['20 minutes; at least 150 words', '30 minutes; 250 words', '26 minutes; 100–150 words']),
            ('How is CELPIP Speaking taken?',
             'You speak into a microphone; your answers are recorded and rated later', ['Face to face with an examiner', 'On a video call with a rater', 'You type your answers']),
            ('Which rating criterion appears in Speaking but not in Writing?', 'Listenability',
             ['Readability', 'Task Fulfillment', 'Vocabulary']),
            ('Which rating criterion appears in Writing but not in Speaking?', 'Readability',
             ['Listenability', 'Content and Coherence', 'Task Fulfillment']),
            ('In which Speaking task do you choose between two options and convince someone of your choice?',
             'Comparing and Persuading', ['Giving Advice', 'Describing a Scene', 'Making Predictions']),
            ('About how long do you speak in each Speaking task?',
             '60 to 90 seconds', ['About 10 seconds', '3 to 4 minutes', 'As long as you like']),
            ('Which of these is NOT a part of the CELPIP Listening test?',
             'Listening to a university lecture', ['Listening to a news item', 'Listening to a discussion', 'Listening for information']),
            ('Which of these is NOT a part of the CELPIP Reading test?',
             'Reading an academic journal article', ['Reading correspondence', 'Reading to apply a diagram', 'Reading for viewpoints']),
            ('The timer reaches zero while you are still writing your email. What happens?',
             'The test moves on, and what you have written is what is rated', ['You get 5 extra minutes', 'Your email is not rated at all', 'You can come back to it after Task 2']),
            ('What are CELPIP General topics mostly about?',
             'Everyday life in Canada: work, community and daily situations', ['University study and lectures', 'Science and technology research', 'Canadian history and law']),
        ],
    },
    'PTE_C1_QUIZ': {
        'folder': 'PTE_Gen_1Mo', 'type': 'PTE_Academic', 'item': 'PTE Academic Introduction Quiz',
        'title': 'Class 1 Quiz — PTE Academic Introduction',
        'qs': [
            ('What scale are PTE Academic scores reported on?', '10 to 90', ['0 to 9', '1 to 12', '0 to 100']),
            ('You read a paragraph aloud clearly and fluently in Read Aloud. Which scores can this task help?',
             'Speaking and Reading', ['Speaking only', 'Reading and Writing', 'Listening and Speaking']),
            ('Which two skills does Write from Dictation count towards?',
             'Listening and Writing', ['Writing only', 'Listening and Reading', 'Speaking and Writing']),
            ('You need an English test for Canadian immigration and want a Pearson test. Which one does IRCC accept?',
             'PTE Core', ['PTE Academic', 'Either PTE test', 'Neither: Canada only accepts IELTS']),
            ('In what order are the three parts of PTE Academic?',
             'Speaking and Writing, then Reading, then Listening', ['Listening, Reading, then Speaking and Writing', 'Reading, Listening, then Speaking and Writing', 'Speaking, Writing, Reading, Listening as four separate parts']),
            ('About how long is the whole test?', 'About 2 hours', ['About 3 hours', 'About 1 hour', 'About 4 hours']),
            ('You stumble in the middle of a Describe Image answer. What should you do?',
             'Keep going: you cannot re-record, and a long silence ends the recording', ['Stop and press re-record', 'Pause for a few seconds to collect your thoughts', 'Start the sentence again from the beginning']),
            ('Which habit hurts your oral fluency score most?',
             'Stopping often to correct and restart sentences', ['Speaking at an even, steady pace', 'Using simple but clear words', 'Having an accent']),
            ('What must your Summarize Written Text answer be?',
             'One sentence of 5 to 75 words', ['One paragraph of 100 to 150 words', 'Two or three short sentences', 'Bullet points of the main ideas']),
            ('Your essay is excellent but 340 words long. What is the problem?',
             'It is over the 200–300 word limit, which costs marks for form', ['There is none: longer essays score higher', 'Only essays under 200 words lose marks', 'The essay is not scored if it is too long']),
            ('How long do you have to write the essay?', '20 minutes', ['40 minutes', '10 minutes', 'As long as you need within the Speaking and Writing part']),
            ('How is the Reading part timed?',
             'As a whole: you manage the time across all the tasks yourself', ['Task by task, with a timer on each question', 'There is no time limit', 'Each task has exactly 2 minutes']),
            ('Which task comes last in the test?', 'Write from Dictation', ['Summarize Spoken Text', 'Essay', 'Re-order Paragraphs']),
            ('Which task comes first in the Listening part?', 'Summarize Spoken Text', ['Write from Dictation', 'Highlight Incorrect Words', 'Select Missing Word']),
            ('Which task is not scored at all?', 'Personal Introduction', ['Read Aloud', 'Answer Short Question', 'Repeat Sentence']),
            ('In which part of the test do you find Re-order Paragraphs?', 'Reading', ['Listening', 'Speaking and Writing', 'Every part']),
            ('In which part of the test do you find Highlight Incorrect Words?', 'Listening', ['Reading', 'Speaking and Writing', 'It is not a PTE task']),
            ('Who or what scores your PTE Academic answers?',
             'A computer scoring system trained on human examiners\' marks', ['A human examiner for every task', 'Your tutor', 'Other test takers, anonymously']),
            ('On test day, other candidates in the room are speaking while you do your Speaking tasks. How should you prepare for this?',
             'Practise speaking clearly with background noise', ['Ask for a separate room', 'Wait until they finish before answering', 'Speak as quietly as possible']),
            ('Your Answer Short Question response is right but given as a full sentence after a long pause. What is the bigger risk?',
             'The long pause: the recording may stop before you answer', ['The full sentence: one word is required or it scores zero', 'Nothing: content is all that counts', 'The answer is marked by a human who may disagree']),
        ],
    },
}


def q(s):
    return "NULL" if s is None else "'" + str(s).replace('\\', '\\\\').replace("'", "''") + "'"


def main():
    out = [
        "-- ============================================================",
        "-- Migration 170 -- Class 1 exam quizzes for the four crash courses (20 questions each)",
        "--",
        "-- Generated by database/quizzes/build_c1_quizzes.py -- edit that, not this file.",
        "-- Founder, 2026-10-24: Class 1 of each crash course is course introduction, Foundational English Assessment, exam",
        "-- introduction, EXAM QUIZ, diagnostic test. The quiz checks the exam introduction by applying it (overall-band",
        "-- rounding, per-part requirements, tone, question types, integrated scoring). Right answers: five on each letter",
        "-- per quiz, never three of the same letter in a row.",
        "-- New codes: IELTS_ACA_C1_QUIZ, IELTS_GT_C1_QUIZ, CELPIP_C1_QUIZ, PTE_C1_QUIZ. The IELTS General Masterclass's",
        "-- 10-question IELTS_GM_C1_QUIZ is not touched. Class 1 item 4 of each crash course (found by folder) opens its quiz,",
        "-- pass mark 70%. Re-runnable (tests and options upserted, questions by number). MySQL 5.7-safe.",
        "-- ============================================================",
        "",
        "SET NAMES utf8mb4;",
    ]
    for code, d in QUIZZES.items():
        qs = d['qs']
        if len(qs) != 20:
            sys.exit(f'{code} has {len(qs)} questions, not 20')
        shift = list(QUIZZES).index(code)
        keys = [KEYS[(i + shift) % 20] for i in range(20)]
        for i in range(18):
            if keys[i] == keys[i + 1] == keys[i + 2]:
                sys.exit(f'{code}: letter {keys[i]} three times from question {i + 1}')
        if sorted(keys) != sorted('ABCD' * 5):
            sys.exit(f'{code}: keys not balanced')
        rnd = random.Random(code)
        out.append(f"\n-- {code}: {d['title']}")
        out.append(f"INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active) "
                   f"VALUES ({q(code)}, {q(d['title'])}, {q('Class 1 quiz: the exam parts, timing and scoring.')}, {q(d['type'])}, 20, 20, 1) "
                   f"ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description), test_type = VALUES(test_type), "
                   f"duration_minutes = VALUES(duration_minutes), total_questions = VALUES(total_questions), is_active = VALUES(is_active);")
        for n, (text, right, wrong) in enumerate(qs, 1):
            if len(wrong) != 3 or right in wrong:
                sys.exit(f'{code} q{n}: needs three different wrong answers')
            wrong = wrong[:]
            rnd.shuffle(wrong)
            pos = 'ABCD'.index(keys[n - 1])
            opts = wrong[:pos] + [right] + wrong[pos:]
            out.append(f"INSERT INTO questions (test_id, question_number, question_text, question_type, points, display_order) "
                       f"SELECT t.id, {n}, {q(text)}, 'multiple_choice_single', 1.0, {n} FROM tests t WHERE t.code = {q(code)} "
                       f"AND NOT EXISTS (SELECT 1 FROM questions x WHERE x.test_id = t.id AND x.question_number = {n});")
            out.append(f"UPDATE questions x JOIN tests t ON t.id = x.test_id SET x.question_text = {q(text)} WHERE t.code = {q(code)} AND x.question_number = {n};")
            for k, (label, opt) in enumerate(zip('ABCD', opts), 1):
                out.append(f"INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) "
                           f"SELECT x.id, '{label}', {q(opt)}, {1 if opt == right else 0}, {k} FROM questions x JOIN tests t ON t.id = x.test_id "
                           f"WHERE t.code = {q(code)} AND x.question_number = {n} "
                           f"ON DUPLICATE KEY UPDATE option_text = VALUES(option_text), is_correct = VALUES(is_correct), display_order = VALUES(display_order);")
        out.append(f"UPDATE lesson_parts p JOIN lessons l ON l.id = p.lesson_id JOIN modules m ON m.id = l.module_id AND m.module_order = 1 "
                   f"JOIN courses c ON c.id = l.course_id AND c.folder_name = {q(d['folder'])} "
                   f"SET p.title = {q(d['item'])}, p.kind = 'quiz', p.status = 'ready', "
                   f"p.file_path = {q('resources/quizzes/class_quiz.php?test_code=' + code)}, p.test_code = {q(code)}, p.pass_percent = 70 "
                   f"WHERE l.lesson_order = 1 AND p.part_order = 4;")
        print(f'{code}: 20 questions, keys ' + ''.join(keys), file=sys.stderr)
    out += ["",
            "-- Check: 4 quizzes, 20 questions and 20 right answers each; each crash course's Class 1 item 4 opens its quiz.",
            "SELECT t.code, COUNT(DISTINCT x.id) AS questions, SUM(o.is_correct) AS right_answers FROM tests t JOIN questions x ON x.test_id = t.id "
            "JOIN question_options o ON o.question_id = x.id WHERE t.code IN (" + ', '.join(q(c) for c in QUIZZES) + ") GROUP BY t.code;",
            "SELECT c.folder_name, p.title, p.status, p.test_code FROM lesson_parts p JOIN lessons l ON l.id = p.lesson_id "
            "JOIN modules m ON m.id = l.module_id AND m.module_order = 1 JOIN courses c ON c.id = l.course_id "
            "WHERE l.lesson_order = 1 AND p.part_order = 4 AND c.folder_name IN (" + ', '.join(q(d['folder']) for d in QUIZZES.values()) + ");",
            ""]
    print('\n'.join(out))


main()
