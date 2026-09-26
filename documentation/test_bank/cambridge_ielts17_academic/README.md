# Cambridge IELTS 17 Academic (staging bank)

Source: "Cambridge IELTS 17 Academic with Answers", provided by the instructor 2026-09-26. Same idea as `../cambridge_ielts15_gt/`: one file per test; `question_type` values are the live `questions.question_type` enum; every answer was checked against the printed key.

**Academic content only.** Never cross-wire it into General Training tests.

## Contents (all four tests complete)
Each `testN.json` holds:
- `reading` - 3 passages, 40 questions, answer key (key pages 120 / 122 / 124 / 126). "Choose TWO letters" pairs are one `multiple_choice_multiple` entry covering two question numbers (correct_answers lists both letters, either order).
- `listening` - 4 parts, 40 questions, answer key (pages 119 / 121 / 123 / 125) and the full audioscript of every part (`audioscript`). **The audio itself is not in the book text** (separate download): the instructor is handling audio.
- `writing` - Task 1 and Task 2 prompts, plus the book's sample answer for each with its band and the examiner's comment. Task 1 figures (maps and charts) are images: Test 2's table and pie-chart data are in the file as data; for Tests 1, 3 and 4 the figure is only described and must be re-created from the book page if a Task 1 is ever wired.
- `speaking` - Part 1 topic and questions, Part 2 cue card, Part 3 discussion topics.

The sample answers keep the candidates' own mistakes on purpose (they are what the examiner comments discuss).

## Wired into the platform
Only **Test 2 Reading** (`IELTS_PT_R_ACA_002`, migration 133, page `resources/practice_tests/ielts_reading_academic_002.php`; live 2026-09-26): the "Reading Test 2" piece of IELTS Academic 2-Month and 3-Month, Class 7. Everything else is staging only; nothing else is inserted. Which test goes where is decided at the content step.
