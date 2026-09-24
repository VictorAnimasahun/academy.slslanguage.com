# Mapping: Cambridge IELTS 15 GT -> existing empty test rows

| Bank file | Skill | -> `tests.id` | `tests.code` |
|---|---|---|---|
| test1.json `listening` | Listening | 8 | IELTS_GT_MOCK01_L |
| test1.json `reading` | Reading | 9 | IELTS_GT_MOCK01_R |
| test1.json `writing.task1` | Writing Task 1 | 10 | IELTS_GT_MOCK01_W1 |
| test1.json `writing.task2` | Writing Task 2 | 11 | IELTS_GT_MOCK01_W2 |
| test1.json `speaking` | Speaking | 12 | IELTS_GT_MOCK01_S |
| test2.json `listening` | Listening | 13 | IELTS_GT_MOCK02_L |
| test2.json `reading` | Reading | 14 | IELTS_GT_MOCK02_R |
| test2.json `writing.task1` | Writing Task 1 | 15 | IELTS_GT_MOCK02_W1 |
| test2.json `writing.task2` | Writing Task 2 | 16 | IELTS_GT_MOCK02_W2 |
| test2.json `speaking` | Speaking | 17 | IELTS_GT_MOCK02_S |
| test3.json `listening` | Listening | 18 | IELTS_GT_MOCK03_L |
| test3.json `reading` | Reading | 19 | IELTS_GT_MOCK03_R |
| test3.json `writing.task1` | Writing Task 1 | 20 | IELTS_GT_MOCK03_W1 |
| test3.json `writing.task2` | Writing Task 2 | 21 | IELTS_GT_MOCK03_W2 |
| test3.json `speaking` | Speaking | 22 | IELTS_GT_MOCK03_S |
| test4.json `listening` | Listening | 23 | IELTS_GT_MOCK04_L |
| test4.json `reading` | Reading | 24 | IELTS_GT_MOCK04_R |
| test4.json `writing.task1` | Writing Task 1 | 25 | IELTS_GT_MOCK04_W1 |
| test4.json `writing.task2` | Writing Task 2 | 26 | IELTS_GT_MOCK04_W2 |
| test4.json `speaking` | Speaking | 27 | IELTS_GT_MOCK04_S |

Confirmed empty (0 questions) for all 20 rows as of the 2026-09-24 baseline snapshot. Re-check counts before writing a seed migration in case something else populated them meanwhile.
