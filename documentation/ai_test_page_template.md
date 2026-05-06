# AI Prompt Template — EduHub Practice Test Page Generator

Use this document as the full brief when asking another AI to generate a new practice test page and its migration SQL. Hand the entire document to the AI along with the question content.

---

## Context

This is a PHP + MySQL e-learning platform called EduHub, served by MAMP (Apache). The platform teaches IELTS, CELPIP, and other language exams. We are building timed practice tests that:

1. Render the questions to the student
2. Play audio per part (listening tests only)
3. Grade answers client-side on submit and show per-question feedback
4. Save the attempt to the database via a generic POST endpoint
5. Show up on the student dashboard automatically

---

## Files You Must Produce

For each new test, provide exactly **two files**:

### File 1 — Test page
**Path:** `academy/resources/practice_tests/[TEST_CODE_LOWERCASE].php`
**Example:** `academy/resources/practice_tests/ielts_listening_001.php`

### File 2 — Migration SQL
**Path:** `academy/database/migrations/[NNN]_seed_[test_code_lower].sql`
**Example:** `academy/database/migrations/009_seed_ielts_listening_pt1.sql`

---

## Test Code Naming Convention

```
IELTS_PT_[SECTION]_[NNN]

Sections:  L = Listening
           R = Reading
           W1 = Writing Task 1
           W2 = Writing Task 2
           S = Speaking

Examples:
  IELTS_PT_L_001   ← Listening Practice Test 1
  IELTS_PT_R_001   ← Reading Practice Test 1
  IELTS_PT_W1_001  ← Writing Task 1 Practice Test 1
```

---

## File 1 — PHP Page Structure

### Boilerplate (always the same — do not change)

```php
<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}

$testCode  = 'IELTS_PT_L_001'; // ← change per test
$timeLimit = 30 * 60;           // ← seconds (30 min = 1800)
$audioBase = ACADEMY_URL . 'assets/audio/' . $testCode . '/';
```

The page uses:
- Bootstrap 5.3.3
- Bootstrap Icons 1.11.3
- SweetAlert2 (CDN)
- `<?php include INCLUDES_PATH . '/navbar_styles.php'; ?>`
- `<?php include INCLUDES_PATH . '/mobile_header.php'; ?>`
- `<?php include INCLUDES_PATH . '/navbar.php'; ?>`
- `<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>`

---

### The `$parts` Array

Each test has a `$parts` array with keys 1–4. Each part entry has:

```php
$parts = [
    1 => [
        'title'       => 'Part 1',
        'description' => 'Short description for the student.',
        'audio_url'   => $audioBase . 'part1.mp3', // listening only; omit for reading/writing
        'q_range'     => [1, 10],
        'type'        => '...',  // see question types below
        // ... type-specific fields
    ],
    // parts 2, 3, 4 ...
];
```

**For reading tests:** omit `audio_url` and the audio bar HTML entirely.

---

### Question Types

#### `form_fill`
Used for: note/form completion, summary completion, sentence completion.

```php
'type'         => 'form_fill',
'instructions' => 'Complete the notes below. Write <strong>ONE WORD AND/OR A NUMBER</strong> for each answer.',
'form_title'   => 'Title of the form or notes box',
'groups'       => [
    [
        'heading' => 'Sub-section heading (or null)',
        'rows' => [
            ['prefix' => 'Text before the blank', 'q' => 5, 'suffix' => 'text after the blank'],
            ['prefix' => 'Static info line (no blank)', 'q' => null, 'suffix' => ''],
        ],
    ],
    // more groups...
],
```

#### `multiple_choice` (single, A/B/C)
Used in: MCQ questions within a part, or as the only type in a part.

```php
'type'      => 'multiple_choice',
'questions' => [
    [
        'q'       => 11,
        'text'    => 'The full question stem text',
        'options' => [
            'A' => 'Option text A',
            'B' => 'Option text B',
            'C' => 'Option text C',
        ],
    ],
    // more questions...
],
```

#### `mixed`
Used when a part has more than one question type (e.g., Part 2 has MCQ then a table).

```php
'type'     => 'mixed',
'sections' => [
    [ 'type' => 'multiple_choice', ... ],  // section 1
    [ 'type' => 'table_fill', ... ],       // section 2
    // etc.
],
```

Each section inside `mixed` uses the same structure as a standalone type.

#### `table_fill`
Used for table/timetable completion.

```php
'type'        => 'table_fill',
'instructions'=> 'Complete the table. Write <strong>ONE WORD AND/OR A NUMBER</strong> for each answer.',
'table_title' => 'Table heading',
'rows' => [
    [
        'day'      => 'Row label (e.g. Day 1)',
        'activity' => ['text' => 'Static text', 'q' => null],
        'notes'    => ['prefix' => 'Before blank', 'q' => 15, 'suffix' => 'after blank'],
    ],
    // ...
],
```

#### `matching`
Used for match-the-option questions (student picks from a lettered box).

```php
'type'         => 'matching',
'instructions' => 'Choose from the box and write the correct letter (A–H) next to Questions 21–26.',
'options_box'  => [
    'A' => 'Option text A',
    'B' => 'Option text B',
    // up to H
],
'questions' => [
    ['q' => 21, 'label' => 'Item to match'],
    // ...
],
```

#### `multi_select`
Used for "choose TWO" questions (Questions 29 & 30 in this test).

```php
'type'         => 'multi_select',
'instructions' => 'Choose <strong>TWO</strong> letters, <strong>A–E</strong>.<br><br>Question text here.',
'q_labels'     => [29 => 'Answer 1', 30 => 'Answer 2'],
'options'      => [
    'A' => 'Option A text',
    'B' => 'Option B text',
    // ...
],
```

---

### The `$answers` Array

```php
// All answers lowercase. Use arrays to support alternative accepted forms.
$answers = [
    1  => ['jamieson'],
    5  => ['10', 'ten'],        // multiple accepted forms
    11 => ['a'],                // MCQ: just the letter, lowercase
    21 => ['g'],                // matching: just the letter, lowercase
    35 => ['grass', 'grasses'], // plural alternative
    // Q29-30 handled separately — see below
];

// For "choose TWO" pairs: list BOTH correct letters lowercase
$answers_pair = ['b', 'd'];
```

**Rules:**
- All answer strings must be **lowercase**
- MCQ answers are just the letter: `'a'`, `'b'`, `'c'`
- Matching answers are just the letter: `'g'`, `'f'`, etc.
- Fill-in answers are the exact word(s) expected, lowercase
- Alternative spellings/forms go in the same array: `['grass', 'grasses']`
- Q29–30 "choose two" correct pair goes in `$answers_pair`, NOT in `$answers`

---

### JavaScript Constants (auto-generated from PHP — do not change the pattern)

```javascript
const CORRECT      = <?= json_encode($answers) ?>;
const CORRECT_PAIR = <?= json_encode($answers_pair) ?>; // Q29 & 30
const TEST_CODE    = <?= json_encode($testCode) ?>;
const startTime    = Date.now();
let userAnswers = {}, timeLeft = <?= $timeLimit ?>, submitted = false;
```

---

### Save Endpoint (always the same — do not change)

On submit, the page calls `save_attempt.php` in the same directory:

```javascript
function saveAttempt(score, maxScore, bandScore, timeSpent) {
    fetch('save_attempt.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            test_code:  TEST_CODE,
            score:      score,
            max_score:  maxScore,
            band_score: bandScore,
            time_spent: timeSpent,
            answers:    userAnswers,
        }),
    })
    .then(r => r.json())
    .then(data => { if (!data.success) console.error('save_attempt error:', data.error); })
    .catch(err => console.error('save_attempt fetch error:', err));
}
```

`save_attempt.php` already exists and is generic — it works for any test code. Do not recreate it.

---

### IELTS Band Score Conversion (use this exact table)

```javascript
function toBand(score) {
    if (score >= 39) return '9.0';
    if (score >= 37) return '8.5';
    if (score >= 35) return '8.0';
    if (score >= 32) return '7.5';
    if (score >= 30) return '7.0';
    if (score >= 26) return '6.5';
    if (score >= 23) return '6.0';
    if (score >= 18) return '5.5';
    if (score >= 16) return '5.0';
    if (score >= 13) return '4.5';
    if (score >= 10) return '4.0';
    return '<4.0';
}
```

---

## File 2 — Migration SQL Structure

```sql
-- ============================================================
-- Migration [NNN] — Seed [TEST_CODE]
-- IDEMPOTENT: safe to re-run.
-- ============================================================

-- Step 1: Ensure test record exists
INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT '[TEST_CODE]',
       '[Human-readable title]',
       '[Description]',
       'IELTS',
       [duration_minutes],
       [total_questions],
       1, 0,
       '[Listening|Reading|Writing|Speaking]'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = '[TEST_CODE]');

SET @tid = (SELECT id FROM tests WHERE code = '[TEST_CODE]' LIMIT 1);

-- Step 2: Wipe child records (makes re-runs safe)
DELETE FROM question_correct_answers
    WHERE question_id IN (SELECT id FROM questions WHERE test_id = @tid);
DELETE FROM question_options
    WHERE question_id IN (SELECT id FROM questions WHERE test_id = @tid);
DELETE FROM questions WHERE test_id = @tid;

-- Step 3: Insert questions
-- Use batch INSERT for efficiency:
INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, display_order)
VALUES
(@tid, 1, 'Context/heading', 'Question text with ___ blank', 'form_note_completion', 'Write ONE WORD...', 1.0, 10),
(@tid, 2, 'Context/heading', 'Next question',                 'form_note_completion', 'Write ONE WORD...', 1.0, 20),
-- ...
;

-- Step 4: Set question ID variables (always use LIMIT 1)
SET @q1 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 1 LIMIT 1);
SET @q2 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 2 LIMIT 1);
-- ...

-- Step 5: Insert correct answers for text-type questions
INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative) VALUES
(@q1, 'answer',     0, 0),
(@q1, 'altanswer',  0, 1),  -- is_alternative=1 for second accepted form
(@q2, 'answer',     0, 0),
-- ...
;

-- Step 6: Insert MCQ options
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q11, 'A', 'Option text A', 1, 10),  -- is_correct=1 for the correct answer
(@q11, 'B', 'Option text B', 0, 20),
(@q11, 'C', 'Option text C', 0, 30),
-- ...
;
```

---

## Database Table Reference

### `questions.question_type` — valid ENUM values
```
multiple_choice_single     ← standard MCQ, pick one from A/B/C
multiple_choice_multiple   ← choose TWO (Q29-30 style)
matching                   ← match item to lettered option box
gap_fill                   ← inline gap in a sentence
sentence_completion        ← complete a sentence
form_note_completion       ← form/notes box fill-in
table_completion           ← table with blanks
summary_completion         ← summary paragraph with blanks
diagram_map_labelling      ← label a diagram or map
short_answer               ← write a short answer phrase
true_false_not_given       ← T/F/NG reading question
yes_no_not_given           ← Y/N/NG reading question
essay                      ← writing task 2
letter                     ← writing task 1 (GT)
speaking_long_turn         ← speaking part 2
speaking_discussion        ← speaking part 3
```

### `test_attempts` — key columns
```
student_id     INT  — from session
test_id        INT  — FK to tests.id
attempt_number INT  — auto-incremented per student
mode           ENUM — 'practice' for all practice tests
score          DECIMAL(6,2)
max_score      DECIMAL(6,2)
band_score     DECIMAL(3,1)
status         ENUM — 'completed' on submit
```

### `attempt_answers` — key columns
```
attempt_id         BIGINT — FK to test_attempts.id
question_id        INT    — FK to questions.id
selected_option_id BIGINT — FK to question_options.id (MCQ only, else NULL)
answer_text        TEXT   — the student's raw answer string
score_awarded      DECIMAL(4,1) — 0.0 or 1.0
```

---

## Audio Files (Listening tests only)

Place audio files at:
```
academy/assets/audio/[TEST_CODE]/part1.mp3
academy/assets/audio/[TEST_CODE]/part2.mp3
academy/assets/audio/[TEST_CODE]/part3.mp3
academy/assets/audio/[TEST_CODE]/part4.mp3
```

The `$audioBase` variable in the PHP resolves the correct URL for both local (MAMP) and live (cPanel) automatically.

---

## Reference: Existing Test to Copy From

A complete, working example lives at:
```
academy/resources/practice_tests/ielts_listening_001.php
academy/database/migrations/009_seed_ielts_listening_pt1.sql
```

Use these as your structural reference. The migration 009 file demonstrates the exact idempotent pattern required.

---

## Checklist Before Submitting Generated Files

- [ ] Test code follows `IELTS_PT_[SECTION]_[NNN]` format
- [ ] All `$answers` values are lowercase
- [ ] `$answers_pair` defined for any "choose two" questions (or omitted if none)
- [ ] Migration uses `WHERE NOT EXISTS` guard on test insert
- [ ] Migration DELETEs child records before re-inserting (idempotent)
- [ ] All `SET @qN = (SELECT ... LIMIT 1)` — **LIMIT 1 is mandatory**
- [ ] `save_attempt.php` is NOT recreated — the page just calls it via `fetch()`
- [ ] Audio paths use `$audioBase . 'partN.mp3'`
- [ ] Breadcrumb links back to `../resources_home.php` and `index.php`
