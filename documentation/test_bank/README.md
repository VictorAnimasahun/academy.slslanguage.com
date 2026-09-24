# Test Bank

A staging area for real, licensed exam content that hasn't been inserted into the live database yet. The bank holds structured content matched to specific *empty* test rows already sitting in the `tests` table — so filling a gap is "copy from bank, write a seed migration, done" instead of authoring content from scratch.

## Why this exists

`_baseline_snapshot_2026-09-24.txt` is the "original" — a snapshot of every GT/PT/ACA test row's question count *before* any bank-driven seeding starts, taken 2026-09-24. If anything ever looks off later, that file says exactly what existed (or didn't) beforehand.

Cambridge IELTS 15 General Training's four full tests turned out to be an exact fit for `IELTS_GT_MOCK01` through `MOCK04` — 20 existing `tests` rows (4 mocks × Listening/Reading/Writing Task 1/Writing Task 2/Speaking) that were all sitting at 0 questions. That gap is what `cambridge_ielts15_gt/` fills.

## Contents

```
cambridge_ielts15_gt/
  test1.json   -- Listening, Reading, Writing (both tasks), Speaking
  test2.json
  test3.json
  test4.json
  MAPPING.md   -- which json section -> which existing test_id
```

## Format

Each `testN.json` has one object per skill:

```json
{
  "source": "Cambridge IELTS 15 General Training with Answers",
  "listening": { "parts": [ { "part_number": 1, "instructions": "...", "stimulus_text": "...", "questions": [
    { "question_number": 1, "question_type": "form_note_completion", "question_text": "...", "correct_answers": ["Jamieson"] }
  ] } ] },
  "reading": { "sections": [ ... same shape, "section_number" instead of "part_number" ... ] },
  "writing": { "task1": {...}, "task2": {...} },
  "speaking": { "part1": {...}, "part2": {...}, "part3": {...} }
}
```

`question_type` values are taken directly from the live `questions.question_type` enum (`multiple_choice_single`, `matching`, `true_false_not_given`, `form_note_completion`, `table_completion`, `diagram_map_labelling`, `summary_completion`, `short_answer`, etc.) so a seed migration can insert a bank question with no type translation step. `stimulus_text` is stored once per part/section (the shared passage, notes template, or table) rather than repeated per question, and gets copied onto each question row at insert time.

**Listening has no real audio.** Same known limitation as CELPIP's practice tests. The instructor is handling audio separately later — don't build a workaround or placeholder for it without checking in first.

## Content safety

This is **General Training** content only. Cambridge's GT and Academic modules test different things (GT Reading Section 1 is short workplace/consumer texts; Academic Reading is long academic passages) and must never be cross-wired — don't let this bank's content get pulled into an `_ACA_` test code. See the GT-vs-Academic findings in `[[project_ielts_aca_course_buildout]]` memory.

## Status

Transcribed from the pasted book text 2026-09-24. Not yet inserted into the database — no migration has been written against this bank yet. When one is, tick it here and in `migration_log.md` as usual.
