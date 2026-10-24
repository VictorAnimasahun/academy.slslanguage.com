# Content Bank Map

Which test content serves which course and class, and where each piece of content came from — so the same content
is never sat twice by one student, and General Training and Academic content are never mixed. Started 2026-10-21
(founder: "note which content inside our content bank maps to which course/test so we don't conflate stuff").

**Keep it current:** any migration that points a course item at a test, or seeds new test content, updates this file in
the same commit. The "Used by" columns come from `lesson_parts` (`c8` = Class 8); re-run the query at the bottom to check.

## Rules

- **Crash courses (1 month) sit Practice Test Set 1 and one mock only** — the same Set 1 tests and the same Mock Test 1
  as their Masterclasses (founder, 2026-10-21; migrations 160, 161). Set 2+ and Mock 2+ are locked to the Masterclasses
  (`require_course_enrollment` on each page).
- **A Masterclass sits each set once, in order**, and a mock at the end of each month (Weeks 4, 8, 12).
- **General Training and Academic never share Reading or Writing content.** Listening and Speaking are the same exam
  for both modules, so those tests are shared.
- **Diagnostic content is not reused as a mock or practice test for the same exam** (the student has already seen it).

## All courses: Foundational English Assessment (placement test)

| Content | Test code(s) | Source | Used by |
|---|---|---|---|
| Foundational English Assessment — `resources/placement/foundational_english_assessment.php` | `FEA_FORM_A` (first sitting), `FEA_FORM_B` (retake): 30 questions, 60 min each, no item shared | Founder's Placement Test bank + Language in Use bank, 2026-10-24 (migration 167) | Class 1 of every paid course (the piece counts when Form A is done) |
| The bank the forms are built from | `FEA_BANK_A2`, `FEA_BANK_{B1,B2,C1,C2}_S{1,2,3}`, `LIU_BANK_{A1..C2}` (inactive) | same; source file `database/placement/bank.txt`, forms picked in `database/placement/build_migration.py` | Form A uses Set 1 items, Form B Set 2. Set 3 and the unused Set 1/2 items are spare for a Form C or for replacing items after the pilot |

## CELPIP General

| Content | Test code(s) | Source | Used by |
|---|---|---|---|
| CELPIP Diagnostic Test — `courses/CELPIP_intro/celpip_mini_mock.php` | `CELPIP_DIAGNOSTIC` (Listening + Reading score and writing saved; recordings in `speaking_recordings`) | Original SLS content | Crash c1; 2Mo c2; 3Mo c2 |
| Listening Practice Test 1 — `courses/CELPIP_Gen/lessons/celpip_listening_001.php` | `CELPIP_PT_L_001` | CELPIP Practice Test C transcripts (migration 084) | Crash c2; 2Mo c3; 3Mo c3 |
| Listening Practice Tests 2-4 | `CELPIP_PT_L_002`.. (0 questions) | — | Coming Soon (2Mo c7, c12; 3Mo c7, c13, c19) |
| Reading Practice Test 1 — `celpip_reading_001.php` | `CELPIP_PT_R_001` | Instructor's CELPIP TASKS, Reading Test 1 (055) | Crash c6; 2Mo c4; 3Mo c4 |
| Reading Practice Test 2 — `celpip_reading_002.php` | `CELPIP_PT_R_002` | Instructor's CELPIP TASKS, Reading Test 2, rewritten (056) | 2Mo c9; 3Mo c10 |
| Reading Practice Test 3 — `celpip_reading_003.php` | `CELPIP_PT_R_003` | Instructor's CELPIP TASKS, Reading Test 3 (057) | 2Mo c13; 3Mo c14 |
| Writing Practice Test 1 — `class_day.php?slot=c6&part=test` (Task 1 + Task 2 pages) | `CELPIP_PT_W1_001`, `CELPIP_PT_W2_001` | Original SLS prompts (052, 053) | Crash c7; 2Mo c6; 3Mo c6 |
| Writing Practice Test 2 — `class_day.php?slot=c10&part=test` | `CELPIP_PT_W1_002`, `CELPIP_PT_W2_002` | Original SLS prompts | 2Mo c11; 3Mo c12 |
| Writing Practice Test 3 — `class_day.php?slot=c14&part=test` | `CELPIP_PT_W1_003`, `CELPIP_PT_W2_003` | Original SLS prompts | 2Mo c15; 3Mo c18 |
| Speaking Practice Tests 1-3 — `celpip_speaking_001..003.php` | `CELPIP_PT_S_001..003` | Original SLS prompts (054) | PT1: Crash c7, 2Mo c5, 3Mo c5 · PT2: 2Mo c10, 3Mo c11 · PT3: 2Mo c14, 3Mo c15 |
| Full Mock Test A — `resources/mock_tests/celpip_full_mock_a.php` | `CELPIP_FULL_MOCK_A` (`CELPIP_FMA_*`) | Official CELPIP General Practice Test (Paragon, Teacher Support Pack) (073) | Mock Test 1: Crash c8; 2Mo c8; 3Mo c8 |
| Full Mock Test B — `celpip_full_mock_b.php` | `CELPIP_FULL_MOCK_B` (`CELPIP_FMB_*`) | Official CELPIP General Practice Test (Paragon) (073) | Mock Test 2: 2Mo c16; 3Mo c16 |
| Mock Test 3 | — | — | Coming Soon (3Mo c24) |

### CELPIP Reading lesson slides (the instructor's decks, migration 164)

Shown in the protected viewer `resources/protected_viewer/celpip_reading.php?part=<key>` (watermarked, no download).
Open to anyone enrolled in a CELPIP course (`includes/protected_reading_resources.php`). Source PowerPoints:
`~/Downloads/Reading/` (copies in `~/Documents/CELPIP/Materials/Reading/`).

| Deck (key) | What it teaches | Used by |
|---|---|---|
| Overview (`celpip_reading_overview`, 7 slides) | The four parts, timings, question types, score-to-CLB table | Crash c3; 2Mo c3; 3Mo c3 |
| Part 1 (`celpip_reading_pt1`, 46) | Reading Correspondence: format, strategy, a real passage worked through | Crash c3; 2Mo c3; 3Mo c3 |
| Part 2 (`celpip_reading_pt2`, 42) | Reading to Apply a Diagram | Crash c3; 2Mo c7; 3Mo c7 |
| Part 3 (`celpip_reading_pt3`, 45) | Reading for Information | Crash c6; 2Mo c12; 3Mo c13 |
| Part 4 (`celpip_reading_pt4`, 51) | Reading for Viewpoints | Crash c6; 2Mo c12; 3Mo c19 |
| Identifying Paraphrases (`celpip_reading_paraphrases`, 32) | Questions never repeat the passage's words | Crash c3; 2Mo c3; 3Mo c3 |
| Identifying Summaries (`celpip_reading_summaries`, 33) | Reduce to the key ideas, then reword | Crash c6; 2Mo c12; 3Mo c13 |

## IELTS General Training

| Content | Test code(s) | Source | Used by |
|---|---|---|---|
| GT Diagnostic — `resources/mock_tests/ielts_gt_diagnostic.php` | `IELTS_GT_DIAGNOSTIC` (`IELTS_GT_DIAG_*`) | Abridged from Full Mock 3 (068) | Crash c1; 2Mo c2; 3Mo c2 |
| Class quizzes 1-7 — `resources/quizzes/class_quiz.php` | `IELTS_GM_C1_QUIZ`..`C7` | Original SLS content (071) | Crash c1-c7; 2Mo/3Mo c2-c7 |
| Listening Practice Test 1 — `ielts_listening_001.php` | `IELTS_PT_L_001` | Cambridge IELTS 15 GT, Test 1 Listening | Set 1: Crash c3; 2Mo c3; 3Mo c3 |
| Listening Practice Test 2 — `ielts_listening_002.php` | `IELTS_PT_L_002` | Cambridge IELTS 15 GT, Test 2 Listening | 2Mo c7; 3Mo c7 |
| Listening Practice Test 3 — `ielts_listening_003.php` | `IELTS_PT_L_003` | Cambridge IELTS 17 Academic, Test 1 Listening (140; Listening is the same exam for both modules) | 3Mo c23 (migration 163) |
| Reading Practice Test 1 — `ielts_reading_001.php` | `IELTS_PT_R_001` | Cambridge IELTS 15 GT, Test 1 Reading | Set 1: Crash c4; 2Mo c4; 3Mo c4 |
| Reading Practice Tests 2-5 — `ielts_reading_002..005.php` | `IELTS_PT_R_002..005` | Cambridge IELTS 15 GT, Tests 1-4 Reading (137) | PT3: 3Mo c21 (migration 163). **PT2 is the same passages as PT1 — never give one student both.** PT4-5 unused |
| Writing Task 1 Practice Test 1 — `ielts_writing_t1_001.php` | `IELTS_PT_W1_001` | Original SLS prompt (011) | Set 1: Crash c5; 2Mo c5; 3Mo c5 |
| Writing Task 2 Practice Test 2 — `ielts_writing_t2_002.php` | `IELTS_PT_W2_002` | — | 3Mo c20 |
| Full Mock Test 1 — `resources/mock_tests/ielts_full_mock_001.php` | `IELTS_FULL_MOCK_001` (`IELTS_FM1_*`) | Cambridge IELTS General Training, Test 1 (a different book from IELTS 15) (015-020) | Mock Test 1: Crash c8; 2Mo c8; 3Mo c8 |
| Full Mock Test 2 — `ielts_full_mock_002.php` | `IELTS_FULL_MOCK_002` (`IELTS_FM2_*`) | Same Cambridge GT book, Test 2 (022-027) | Mock Test 2: 2Mo c16; 3Mo c16 |
| Full Mock Test 3 — `ielts_full_mock_003.php` | `IELTS_FULL_MOCK_003` (`IELTS_FM3_*`) | Same Cambridge GT book, Test 3 (028-032) | **Not used**: the GT Diagnostic is made from it |
| Full Mock Test 4 — `ielts_full_mock_004.php` | `IELTS_FULL_MOCK_004` (`IELTS_FM4_*`) | Same Cambridge GT book, Test 4 (033-037) | Mock Test 3: 3Mo c24 |

## IELTS Academic

| Content | Test code(s) | Source | Used by |
|---|---|---|---|
| Academic Diagnostic — `resources/mock_tests/ielts_aca_diagnostic.php` | `IELTS_ACA_DIAGNOSTIC` (`IELTS_ACA_DIAG_*`) | Listening Part 1 is the same recording as Full Mock 1's (GT); Reading/Writing original (063-065) | Crash c1; 2Mo c2; 3Mo c2 |
| Listening question formats quiz — `ielts_listening_formats_sample.php` | `IELTS_QUIZ_L_FORMATS` | Original SLS content | Crash c5; 2Mo c5; 3Mo c5 |
| Listening Practice Tests 1-2 | `IELTS_PT_L_001`, `_002` | Shared with General Training (above) | PT1: Crash c2, 2Mo c3, 3Mo c3 · PT2: 2Mo c6, 3Mo c6 |
| Listening Practice Tests 3-4 — `ielts_listening_003/004.php` | `IELTS_PT_L_003`, `_004` | Cambridge IELTS 17 Academic, Tests 1-2 Listening (140) | PT3: IELTS General 3Mo c23. Academic's "Listening Test 3/4" items are still Coming Soon; wiring them to these is safe (different students) |
| Reading Test 1 — `ielts_reading_academic_c17_t1.php` | `IELTS_PT_R_ACA_C17T1` | Cambridge IELTS 17 Academic, Test 1 (135) | Set 1: Crash c3; 2Mo c3; 3Mo c3 |
| Reading Test 2 — `ielts_reading_academic_002.php` | `IELTS_PT_R_ACA_002` | Cambridge IELTS 17 Academic, Test 2 (133) | 2Mo c7; 3Mo c7 |
| Reading Test 3 — `ielts_reading_academic_c17_t3.php` | `IELTS_PT_R_ACA_C17T3` | Cambridge IELTS 17 Academic, Test 3 (135) | 2Mo c13; 3Mo c13 |
| Reading Test 4 — `ielts_reading_academic_c17_t4.php` | `IELTS_PT_R_ACA_C17T4` | Cambridge IELTS 17 Academic, Test 4 (135) | 3Mo c19 |
| `ielts_reading_academic_001.php` | `IELTS_PT_R_ACA_001` | "Cambridge-style" (089) | Not used by any course |
| Writing Test 1 — `ielts_writing_academic_001.php` (Task 1 + Task 2 pages) | `IELTS_PT_W1_ACA_001`, `IELTS_PT_W2_ACA_001` | Official IELTS.org Academic samples 1A/2A (110) | Set 1: Crash c5; 2Mo c5; 3Mo c5 |
| Writing Tests 2-4 — `ielts_writing_academic_002..004.php` | `IELTS_PT_W1_ACA_00n`, `IELTS_PT_W2_ACA_00n` | Cambridge IELTS 17 Academic, Tests 2-4 (136) | WT2: 2Mo c11, 3Mo c11 · WT3: 2Mo c15, 3Mo c15 · WT4: 3Mo c21 |
| Speaking Tests — `ielts_speaking_001..004.php` | `IELTS_PT_S_001..004` (transcript saved, no audio) | Original SLS prompts (011, 047-049) | ST1 (`_002`): Crash c4, 2Mo c4, 3Mo c4 · ST2 (`_003`): 2Mo c10, 3Mo c10 · ST3 (`_004`): 2Mo c14, 3Mo c14 · ST4 (`_001`): 3Mo c20 |
| Mock Tests 1-3 | — | **No IELTS Academic full mock exists yet** (all four Full Mocks are GT) | Coming Soon everywhere |

IELTS Speaking is the same for both modules: General Training's Set 1 Speaking is `ielts_speaking_001.php`, which is
Academic's Speaking Test 4; Academic's Speaking Test 1 (`_002`) is General Training 3-Month's Class 22 test.

## PTE Academic

No tests exist yet. Every PTE diagnostic, practice test and mock item is Coming Soon.

## Known clashes (to fix)

1. ~~IELTS General 3-Month, Classes 4 and 21~~ and ~~Classes 7 and 23~~: fixed by migration 163 (Class 21 → Reading
   Practice Test 3, Class 23 → Listening Practice Test 3).
2. **Test rows with no content** (safe to ignore; nothing points at them): `IELTS_GT_MOCK01..04_*` (the slots the
   Cambridge IELTS 15 GT bank was first meant for — it went into Reading Practice Tests 2-5 instead), `IELTS_MOCK01_*`,
   `IAW001`, `IELTS-A-001`, `CELPIP_PT_L_002/003`.

## How results are saved (migration 162)

Every test, quiz and diagnostic above saves a completed `test_attempts` row under its test code, which is what makes it
count towards its class. Writing and speaking practice keep the student's response (`response_text`), the AI's
feedback and score, and a tutor's grade (`graded_by = 'tutor'`, which replaces the AI's). Tutors grade writing and
IELTS Speaking under SLS Admin → Practice Grading, CELPIP Speaking and the CELPIP Diagnostic's recordings under
Practice Sessions.

## Check query

```sql
SELECT COALESCE(NULLIF(p.file_path,''), l.file_path) AS page, p.test_code, c.folder_name,
       (m.module_order - 1) * 2 + l.lesson_order AS class
  FROM lesson_parts p JOIN lessons l ON l.id = p.lesson_id JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = l.course_id
 WHERE p.kind IN ('practice_test','mock_test','diagnostic_test','quiz') AND p.status = 'ready'
 ORDER BY page, c.folder_name, class;
```
