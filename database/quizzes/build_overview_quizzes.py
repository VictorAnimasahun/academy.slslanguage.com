#!/usr/bin/env python3
"""
Builds migration 173: a Course Overview Quiz in Class 1 of every 2- and 3-month course (founder, 2026-10-26: "Program
overview introduction, foundational english language assessment ... we need a quiz too, but not the introduction quiz,
the overview quiz"; order chosen: Course Introduction, Course Overview Quiz, Foundational English Assessment).

    python3 -I database/quizzes/build_overview_quizzes.py > database/migrations/173_course_overview_quizzes.sql

Each quiz has 10 questions on what THAT course's Course Introduction says: six on the facts every introduction gives
(length, classes, mocks, computer-only tests, Week 1), with the course's own numbers, and four on its own version of the
introduction (CELPIP's shared page, IELTS General's shared page, or the generated one IELTS Academic and PTE use). Right
answers spread over A-D (3/3/2/2), never one letter three times running. Courses found by folder, never by id.
"""
import random
import sys

KEYS = 'BDACCABDAC'
COURSES = [
    # folder, quiz code, version, exam name, weeks, classes, mocks
    ('CELPIP_Gen_2Mo', 'OVW_CELPIP_2MO', 'celpip', 'CELPIP General', 8, 16, 2),
    ('CELPIP_Gen_3Mo', 'OVW_CELPIP_3MO', 'celpip', 'CELPIP General', 12, 24, 3),
    ('IELTS_Gen_2Mo', 'OVW_IELTS_GT_2MO', 'ielts_gen', 'IELTS General', 8, 16, 2),
    ('IELTS_Gen_Mst', 'OVW_IELTS_GT_3MO', 'ielts_gen', 'IELTS General', 12, 24, 3),
    ('IELTS_Aca_2Mo', 'OVW_IELTS_ACA_2MO', 'generated', 'IELTS Academic', 8, 16, 2),
    ('IELTS_Aca_3Mo', 'OVW_IELTS_ACA_3MO', 'generated', 'IELTS Academic', 12, 24, 3),
    ('PTE_Gen_2Mo', 'OVW_PTE_2MO', 'generated', 'PTE Academic', 8, 16, 2),
    ('PTE_Gen_3Mo', 'OVW_PTE_3MO', 'generated', 'PTE Academic', 12, 24, 3),
]
SCORE = {'CELPIP General': 'CLB level', 'IELTS General': 'band score', 'IELTS Academic': 'band score', 'PTE Academic': 'PTE score'}


def common(exam, weeks, classes, mocks):
    other_weeks = [w for w in (weeks // 2, weeks + 4, weeks * 2) if w != weeks]
    mock_weeks = ', '.join(str(4 * i) for i in range(1, mocks)) + ' and ' + str(4 * mocks)
    return [
        ('How often do classes run, and how long is each one?', 'Two a week, about 2 hours each',
         ['One a week, about 3 hours each', 'Every weekday, about 1 hour each', 'Two a week, about 1 hour each']),
        (f'This course has {classes} classes. How many weeks does it run for?', f'{weeks} weeks',
         [f'{w} weeks' for w in other_weeks]),
        ('How many full mock exams do you sit in this course?', str(mocks),
         [str(n) for n in (1, 2, 3, 4) if n != mocks][:3]),
        ('When do you sit the full mock exams?', f'In Weeks {mock_weeks}, at the end of each month',
         ['All together in the final week', 'Only in Week 1, to find your starting point', 'After the course ends, booked separately']),
        ('Where do you take the tests in this course?', 'On a computer, so that conditions match the real exam',
         ['On your phone, wherever you are', 'At an official test centre', 'On paper, handed in to your tutor']),
        ('What does Week 1 do?', f'It introduces the course and the {exam} exam and finds your starting point',
         ['It starts straight away with full practice tests', 'It is a free trial week with no classes', 'It covers only the Writing tasks']),
    ]


def specific(version, exam):
    if version == 'celpip':
        return [
            ('Why does the course check your general English before any CELPIP content?',
             'The exam format can be learned in a few weeks, but vocabulary, grammar and listening speed take longer',
             ['Because CELPIP is a general English test with no format to learn', 'Because the assessment replaces the CELPIP Diagnostic Test',
              'Because students with high scores can skip the course']),
            ('What does the CELPIP Diagnostic Test (the "Mini Mock") show?', 'Your CELPIP level today, in all four skills',
             ['Your general English level only', 'Your final CLB result for immigration', 'Which course length you should buy']),
            ('What are your self-assessment answers for?', 'To show your instructor whether a gap is a confidence problem or a real skill gap',
             ['They are scored and count towards your final level', 'They replace the Foundational English Assessment', 'They decide which class you start in']),
            ('Why should you know your target CLB for each skill before you start?',
             'Immigration and citizenship pathways set CLB levels per skill, so every result is read against your real target',
             ['Because only your overall CLB matters', 'Because the course is shorter if your target is low', 'So that you can skip the diagnostic test']),
        ]
    if version == 'ielts_gen':
        return [
            ('How long are the mock exam classes?', 'About 3 hours: a full timed exam',
             ['About 2 hours, like every other class', 'About 1 hour: one section only', 'They have no time limit']),
            ('What comes at the end of each class?', 'A short consolidation quiz, typically about 10 questions',
             ['A full mock exam', 'A one-to-one speaking test', 'Nothing: classes end with the lesson']),
            ('What do you get after each mock exam?', 'Written feedback, band score estimates and a plan for the next month',
             ['Only a pass or fail', 'Your official IELTS certificate', 'A new course timetable']),
            ('What do you do with the self-assessment grid?', 'Rate each of the four skills from 1 to 5 and bring the grid to Class 2',
             ['Send it to IELTS to book the real test', 'Score it yourself and keep it private', 'Fill it in only after the last mock exam']),
        ]
    score = SCORE[exam]
    return [
        ('Which items can you do on your phone?', 'Lessons, quizzes and exercises',
         ['Everything, including the tests', 'Only the mock exams', 'Nothing: the whole course is computer-only']),
        ('What is each class made of?', 'Separate items: lessons, plus quizzes, exercises or tests where the class has them',
         ['One long video', 'A single test to pass before the next class', 'A live session only, with nothing to open']),
        ('What do your self-assessment answers show your instructor?',
         'Set against your Foundational English Assessment, whether a gap is a confidence problem or a real skill gap',
         ['Your final score in the exam', 'Which tutor you should have', 'Whether you can skip Week 1']),
        (f'What should you know about your target {score} before you start?', 'The score you need overall and in each skill',
         ['Only the overall score', 'Nothing yet: the diagnostic sets it for you', 'The average score of other students']),
    ]


def q(s):
    return 'NULL' if s is None else "'" + str(s).replace('\\', '\\\\').replace("'", "''") + "'"


def main():
    out = [
        "-- ============================================================",
        "-- Migration 173 -- Course Overview Quiz in Class 1 of every 2- and 3-month course",
        "--",
        "-- Generated by database/quizzes/build_overview_quizzes.py -- edit that, not this file.",
        "-- Founder, 2026-10-26: Class 1 of the 2/3-month courses = Course Introduction, Course Overview Quiz, Foundational",
        "-- English Assessment (the exam introduction, its quiz and the diagnostic stay in Class 2). One 10-question quiz per",
        "-- course on what its own Course Introduction says. The Foundational English Assessment moves from item 2 to item 3;",
        "-- the quiz goes in at item 2 (only where item 2 is free, so a re-run or an unexpected shape changes nothing).",
        "-- Courses found by folder. Re-runnable. MySQL 5.7-safe. The SELECT at the end lists each Class 1.",
        "-- ============================================================",
        "",
        "SET NAMES utf8mb4;",
    ]
    for ci, (folder, code, version, exam, weeks, classes, mocks) in enumerate(COURSES):
        qs = common(exam, weeks, classes, mocks) + specific(version, exam)
        if len(qs) != 10:
            sys.exit(f'{code}: {len(qs)} questions')
        keys = [KEYS[(i + ci) % 10] for i in range(10)]
        for i in range(8):
            if keys[i] == keys[i + 1] == keys[i + 2]:
                sys.exit(f'{code}: letter {keys[i]} three times running')
        rnd = random.Random(code)
        title = 'Class 1 Quiz — Course Overview'
        out.append(f"\n-- {folder}: {code}")
        out.append(f"INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active) "
                   f"VALUES ({q(code)}, {q(title)}, {q('How this course runs: classes, mocks, tests and your starting point.')}, "
                   f"{q('CELPIP_General' if exam.startswith('CELPIP') else ('PTE_Academic' if exam.startswith('PTE') else ('IELTS_General' if 'General' in exam else 'IELTS_Academic')))}, 10, 10, 1) "
                   f"ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description), total_questions = VALUES(total_questions), is_active = 1;")
        for n, (text, right, wrong) in enumerate(qs, 1):
            if len(wrong) != 3 or right in wrong or len(set(wrong)) != 3:
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
        c1 = (f"JOIN lessons l ON l.id = p.lesson_id JOIN modules m ON m.id = l.module_id AND m.module_order = 1 "
              f"JOIN courses c ON c.id = l.course_id AND c.folder_name = {q(folder)}")
        out.append(f"UPDATE lesson_parts p {c1} SET p.part_order = 3 WHERE l.lesson_order = 1 AND p.kind = 'placement_test' AND p.part_order = 2;")
        out.append(f"INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path, test_code, pass_percent) "
                   f"SELECT l.id, 2, 'Course Overview Quiz', 'quiz', 'ready', {q('resources/quizzes/class_quiz.php?test_code=' + code)}, {q(code)}, 70 "
                   f"FROM lessons l JOIN modules m ON m.id = l.module_id AND m.module_order = 1 JOIN courses c ON c.id = l.course_id AND c.folder_name = {q(folder)} "
                   f"WHERE l.lesson_order = 1 AND NOT EXISTS (SELECT 1 FROM lesson_parts z WHERE z.lesson_id = l.id AND z.part_order = 2);")
        out.append(f"UPDATE lessons l JOIN modules m ON m.id = l.module_id AND m.module_order = 1 JOIN courses c ON c.id = l.course_id AND c.folder_name = {q(folder)} "
                   f"SET l.title = REPLACE(l.title, 'Course Introduction + Foundational English Assessment', 'Course Introduction + Course Overview Quiz + Foundational English Assessment') "
                   f"WHERE l.lesson_order = 1;")
        print(f'{code}: keys {"".join(keys)}', file=sys.stderr)
    out += ["",
            "-- Check: every course's Class 1 = Course Introduction, Course Overview Quiz, Foundational English Assessment.",
            "SELECT c.folder_name, p.part_order, p.title, p.status, p.test_code FROM lesson_parts p JOIN lessons l ON l.id = p.lesson_id "
            "JOIN modules m ON m.id = l.module_id AND m.module_order = 1 JOIN courses c ON c.id = l.course_id "
            f"WHERE l.lesson_order = 1 AND c.folder_name IN ({', '.join(q(c[0]) for c in COURSES)}) ORDER BY c.folder_name, p.part_order;",
            "SELECT t.code, COUNT(DISTINCT x.id) AS questions, SUM(o.is_correct) AS right_answers FROM tests t JOIN questions x ON x.test_id = t.id "
            f"JOIN question_options o ON o.question_id = x.id WHERE t.code IN ({', '.join(q(c[1]) for c in COURSES)}) GROUP BY t.code;",
            ""]
    print('\n'.join(out))


main()
