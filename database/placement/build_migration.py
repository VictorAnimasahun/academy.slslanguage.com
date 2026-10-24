#!/usr/bin/env python3
"""
Builds migration 167 (Foundational English Assessment) from bank.txt.

    python3 -I database/placement/build_migration.py > database/migrations/167_foundational_english_assessment.sql

bank.txt holds the whole question bank (the founder's placement bank and Language in Use bank, 2026-10-24), one
inactive test per block (FEA_BANK_*, LIU_BANK_*). FORMS below picks 30 of those items for each of the two forms
students actually take (FEA_FORM_A, FEA_FORM_B): one hour, five levels, three parts. Change the bank or the picks
here and rebuild; never edit the .sql by hand.
"""
import re
import sys
from pathlib import Path

BANK = Path(__file__).with_name('bank.txt')

# Instructions shown above each Language in Use item, by its number in the level (founder's bank).
LIU_INSTRUCTIONS = {
    range(1, 5): 'Choose the best thing to say or write in this situation.',
    range(5, 8): 'Choose the word closest in meaning to the word in bold as it is used in the sentence.',
    range(8, 11): 'Choose the word with the opposite meaning to the word in bold as it is used in the sentence.',
    range(11, 13): 'Choose the meaning of the expression.',
}

# Each form: (part, level, bank code, item numbers). Levels: Basic = A1-A2. Parts: 1 grammar and vocabulary,
# 2 language in use, 3 reading. Form B never repeats a Form A item, so a retake is a fresh test.
FORMS = {
    'FEA_FORM_A': [
        (1, 'Basic', 'FEA_BANK_A2', [1, 4, 8]),
        (1, 'B1', 'FEA_BANK_B1_S1', [2, 3, 4]),
        (1, 'B2', 'FEA_BANK_B2_S1', [1, 4, 5]),
        (1, 'C1', 'FEA_BANK_C1_S1', [1, 5, 8]),
        (1, 'C2', 'FEA_BANK_C2_S1', [1, 2, 3]),
        (2, 'Basic', 'LIU_BANK_A1', [2]),
        (2, 'Basic', 'LIU_BANK_A2', [11]),
        (2, 'B1', 'LIU_BANK_B1', [1]),
        (2, 'B2', 'LIU_BANK_B2', [5]),
        (2, 'C1', 'LIU_BANK_C1', [8]),
        (2, 'C2', 'LIU_BANK_C2', [12]),
        (3, 'B1', 'FEA_BANK_B1_S1', [9, 10, 11]),
        (3, 'B2', 'FEA_BANK_B2_S1', [9, 10, 11]),
        (3, 'C1', 'FEA_BANK_C1_S1', [9, 10, 11]),
    ],
    'FEA_FORM_B': [
        (1, 'Basic', 'FEA_BANK_A2', [2, 3, 6]),
        (1, 'B1', 'FEA_BANK_B1_S2', [2, 5, 6]),
        (1, 'B2', 'FEA_BANK_B2_S2', [1, 3, 5]),
        (1, 'C1', 'FEA_BANK_C1_S2', [1, 5, 8]),
        (1, 'C2', 'FEA_BANK_C2_S2', [1, 2, 3]),
        (2, 'Basic', 'LIU_BANK_A1', [9]),
        (2, 'Basic', 'LIU_BANK_A2', [3]),
        (2, 'B1', 'LIU_BANK_B1', [11]),
        (2, 'B2', 'LIU_BANK_B2', [2]),
        (2, 'C1', 'LIU_BANK_C1', [6]),
        (2, 'C2', 'LIU_BANK_C2', [2]),
        (3, 'B1', 'FEA_BANK_B1_S2', [9, 10, 11]),
        (3, 'B2', 'FEA_BANK_B2_S2', [9, 10, 11]),
        (3, 'C1', 'FEA_BANK_C1_S2', [9, 10, 11]),
    ],
}
FORM_TITLES = {'FEA_FORM_A': 'Foundational English Assessment (Form A)', 'FEA_FORM_B': 'Foundational English Assessment (Form B)'}
LEVELS = ['Basic', 'B1', 'B2', 'C1', 'C2']
ITEM = re.compile(r'^(\d+)\.\s+(.*?)\s+\(A\)\s+(.*?)\s+\(B\)\s+(.*?)\s+\(C\)\s+(.*?)\s+\(D\)\s+(.*?)\s+=([ABCD])$')


def parse():
    blocks, cur, passage = {}, None, None
    for n, line in enumerate(BANK.read_text(encoding='utf-8').splitlines(), 1):
        line = line.strip()
        if not line or line.startswith('#'):
            continue
        if line.startswith('==='):
            code, title, level, part = [x.strip() for x in line[3:].split('|')]
            cur = blocks[code] = {'title': title, 'level': level, 'part': part, 'items': {}}
            passage = None
        elif line.startswith('P:'):
            passage = line[2:].strip()
        else:
            m = ITEM.match(line)
            if not m:
                sys.exit(f'bank.txt line {n}: not an item: {line[:80]}')
            num = int(m[1])
            if num in cur['items']:
                sys.exit(f'bank.txt line {n}: item {num} twice in a block')
            cur['items'][num] = {'text': m[2], 'options': [m[3], m[4], m[5], m[6]], 'key': m[7], 'passage': passage}
    return blocks


def q(s):
    return "NULL" if s is None else "'" + s.replace('\\', '\\\\').replace("'", "''") + "'"


def instructions(code, num, item):
    if code.startswith('LIU_'):
        return next(v for r, v in LIU_INSTRUCTIONS.items() if num in r)
    return 'Read the passage, then choose the best answer.' if item['passage'] else 'Choose the best answer.'


def emit_test(out, code, title, description, test_type, active, duration, items):
    """items: list of (number, item, bank code, bank number, part, section name or None)."""
    out.append(f"\n-- {code}: {title}")
    out.append(f"INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active) "
               f"VALUES ({q(code)}, {q(title)}, {q(description)}, {q(test_type)}, {duration or 'NULL'}, {len(items)}, {active}) "
               f"ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description), test_type = VALUES(test_type), "
               f"duration_minutes = VALUES(duration_minutes), total_questions = VALUES(total_questions), is_active = VALUES(is_active);")
    for i, name in enumerate([s for s in LEVELS if any(it[5] == s for it in items)], 1):
        out.append(f"INSERT INTO sections (test_id, name, order_num) SELECT t.id, {q(name)}, {i} FROM tests t WHERE t.code = {q(code)} "
                   f"AND NOT EXISTS (SELECT 1 FROM sections s WHERE s.test_id = t.id AND s.name = {q(name)});")
    for num, it, bank, bnum, part, section in items:
        sec = (f"(SELECT s.id FROM sections s WHERE s.test_id = t.id AND s.name = {q(section)} LIMIT 1)" if section else 'NULL')
        out.append(f"INSERT INTO questions (test_id, section_id, question_number, part_number, stimulus_text, question_text, question_type, instructions, points, display_order) "
                   f"SELECT t.id, {sec}, {num}, {part}, {q(it['passage'])}, {q(it['text'])}, 'multiple_choice_single', {q(instructions(bank, bnum, it))}, 1.0, {num} "
                   f"FROM tests t WHERE t.code = {q(code)} AND NOT EXISTS (SELECT 1 FROM questions x WHERE x.test_id = t.id AND x.question_number = {num});")
        for k, (label, text) in enumerate(zip('ABCD', it['options']), 1):
            out.append(f"INSERT IGNORE INTO question_options (question_id, option_label, option_text, is_correct, display_order) "
                       f"SELECT x.id, '{label}', {q(text)}, {1 if label == it['key'] else 0}, {k} FROM questions x JOIN tests t ON t.id = x.test_id "
                       f"WHERE t.code = {q(code)} AND x.question_number = {num};")


def main():
    blocks = parse()
    used = {}
    for form, picks in FORMS.items():
        n = sum(len(nums) for *_, nums in picks)
        if n != 30:
            sys.exit(f'{form} has {n} items, not 30')
        for part, level, bank, nums in picks:
            for num in nums:
                if num not in blocks[bank]['items']:
                    sys.exit(f'{form}: {bank} has no item {num}')
                if (bank, num) in used:
                    sys.exit(f'{form}: {bank} item {num} is already in {used[(bank, num)]}')
                used[(bank, num)] = form

    total = sum(len(b['items']) for b in blocks.values())
    out = [
        "-- ============================================================",
        "-- Migration 167 -- Foundational English Assessment (placement test): question bank + two one-hour forms",
        "--",
        f"-- Generated by database/placement/build_migration.py from database/placement/bank.txt -- edit those, not this file.",
        f"-- The founder's Placement Test bank and Language in Use bank (2026-10-24): {total} questions in {len(blocks)} bank blocks",
        "-- (FEA_BANK_* by level and set, LIU_BANK_* by level), inactive: they are the bank, not tests students open.",
        "-- Students take FEA_FORM_A, then FEA_FORM_B on a retake: 30 questions each, 60 minutes, in three parts",
        "-- (grammar and vocabulary 15, language in use 6, reading 9), tagged by level (Basic = A1-A2, B1, B2, C1, C2)",
        "-- through the sections table. resources/placement/foundational_english_assessment.php runs them.",
        "-- The placement bank's answer keys were not in the document; they were worked out item by item -- check before relying",
        "-- on results. The Language in Use keys are the founder's.",
        "-- Last: the Class 1 'Foundational English Assessment' pieces stop being Coming Soon and count when Form A is done.",
        "--",
        "-- Re-run safe (tests upserted by code, questions added only where missing, options INSERT IGNORE). MySQL 5.7-safe.",
        "-- ============================================================",
        "",
        "SET NAMES utf8mb4;",
    ]
    for code, b in blocks.items():
        items = [(num, it, code, num, 3 if it['passage'] else (2 if b['part'] == 'language' else 1), None) for num, it in sorted(b['items'].items())]
        emit_test(out, code, b['title'], f"Question bank ({b['level']}). Not opened by students: the assessment forms are built from it.",
                  'Placement_Bank', 0, None, items)
    for form, picks in FORMS.items():
        items, num = [], 0
        for part in (1, 2, 3):
            for p, level, bank, nums in picks:
                if p != part:
                    continue
                for bnum in nums:
                    num += 1
                    items.append((num, blocks[bank]['items'][bnum], bank, bnum, part, level))
        emit_test(out, form, FORM_TITLES[form], 'General English level check: grammar and vocabulary, language in use, and reading. 30 questions, one hour.',
                  'Placement', 1, 60, items)

    out += [
        "",
        "-- Class 1 of every paid course: the assessment is ready, and done once Form A is completed.",
        "UPDATE lesson_parts SET status = 'ready', test_code = 'FEA_FORM_A'",
        " WHERE file_path = 'resources/placement/foundational_english_assessment.php';",
        "",
    ]
    print('\n'.join(out))
    print(f'-- {total} bank questions; forms: ' + ', '.join(f'{f} 30' for f in FORMS), file=sys.stderr)


main()
