# Vocabulary Banks — Build Checklist

Full feature: daily word page, word profiles, test-specific usage examples, word families,
exercises and quizzes per word, admin management. Reuses the existing questions/tests/attempts
DB infrastructure. Check boxes in order — later steps depend on earlier ones.

---

## Phase 1 — Database

- [x] **Migration: Create `vocabulary_words` table** ✓ local + live 2026-07-01
- [x] **Migration: Add `word_id` (nullable FK) to `questions` table** ✓ local + live 2026-07-01

- [x] **Migration: Create `word_test_usages` table** ✓ local + live 2026-07-01
      Fields: id, word_id (FK), exam_type (IELTS/CELPIP/PTE), skill
      (Listening/Reading/Writing/Speaking), sub_section (e.g. Task 1, Task 2, Part 3,
      Section 2, Summarize Written Text, etc.), example_sentence, context_note, created_at

- [x] **Migration: Seed first batch — 30 high-frequency academic words** ✓ local + live 2026-07-01
      Rows in `vocabulary_words` only (no usages or questions yet).
      Source: Academic Word List Sublist 1 (most frequent).

- [x] **Migration: Seed test usage examples for the first 30 words** ✓ local + live 2026-07-01
      At least 2–3 usages per word across different exams/skills.
      Rows go into `word_test_usages`.

- [x] **Migration: Add vocab quiz test containers to `tests` table** ✓ local + live 2026-07-01/02
      One row per word: code = VOCAB_WORD_001, test_type = 'Vocabulary',
      category = 'Word Exercise', is_active = 1.

- [x] **Migration: Seed quiz questions for the first 30 words** ✓ local + live 2026-07-02
      3–5 questions per word using existing question types:
      - Definition MCQ (multiple_choice_single)
      - Gap-fill / sentence completion (gap_fill)
      - Word form choice (multiple_choice_single)
      - Collocation match (matching)
      Rows go into `questions` (with word_id set) + `question_options` + `question_correct_answers`.

---

## Phase 2 — Admin Panel ✅ 2026-07-02

- [x] **Vocabulary words list page** (`sls-admin/vocab_words.php`) ✓ 2026-07-02
      Table of all words: headword, CEFR, AWL flag, question count, active toggle, Edit link.

- [x] **Add / edit word form** (`sls-admin/vocab_word_edit.php`) ✓ 2026-07-02
      Fields: headword, phonetic, word_class, CEFR level, AWL flag (checkbox),
      definition, secondary definitions, synonyms, antonyms, collocations, word family,
      sort_order, active toggle.

- [x] **Test usage examples manager** (inline on edit page) ✓ 2026-07-02
      Add / edit / delete rows in `word_test_usages` for a given word.
      Dropdowns: exam_type → skill → sub_section. Example sentence + context note.

- [x] **Quiz question manager per word** (inline on edit page) ✓ 2026-07-02
      Lists existing questions (with options summary). Add MCQ or gap-fill with options/answers.
      Delete question link. Auto-updates total_questions on the test container.

- [x] **Add Vocabulary to admin sidebar** ✓ 2026-07-02
      "Vocabulary" section added to `sls-admin/sidebar.php` with Words + Add Word links.

---

## Phase 3 — Student-Facing Pages ✅ 2026-07-02

- [x] **Vocabulary home page** (`resources/vocabulary_banks/vocab_home.php`) ✓ 2026-07-02
      Hero with Word of the Day (date % word count). A–Z filter bar. Responsive word grid.

- [x] **Daily word selection logic** ✓ 2026-07-02
      `$idx = (int)(date('z') + date('Y')) % count($words)` — pure PHP, no DB writes.

- [x] **Word detail page** (`resources/vocabulary_banks/word.php?id=N`) ✓ 2026-07-02
      Blue gradient header, definition(s), synonyms/antonyms chips, collocations chips,
      word family chips, usage examples tabbed by exam type (IELTS/CELPIP/PTE) then skill,
      pink "Practice This Word" quiz CTA, prev/next/random word nav.

- [x] **Word quiz page** (`resources/vocabulary_banks/word_quiz.php?word_id=N`) ✓ 2026-07-02
      Loads questions from DB (MCQ + gap-fill). Marks on submit, saves to test_attempts +
      attempt_answers. Shows colour-coded score banner + per-question feedback with correct answers.

- [x] **Wire the Vocabulary Banks card on `resources_home.php`** ✓ 2026-07-02
      Updated href → `vocabulary_banks/vocab_home.php`

---

## Phase 4 — Expansion (after Phase 3 is live)

- [ ] Grow word bank to 100 words (next seed migration)
- [ ] Grow word bank to 200 words
- [ ] Add audio pronunciation files per word (MP3) and wire up a play button
- [ ] "Word history" panel — show student's previously viewed/quizzed words
- [ ] Spaced repetition queue — resurface words the student got wrong in quizzes
- [ ] Admin bulk-import tool (CSV upload for adding many words at once)
- [ ] CELPIP-specific and PTE-specific usage examples (expand word_test_usages seed)

---

## Notes

- Always run migrations on LOCAL first, verify, then run on LIVE.
- `word_id` on `questions` is nullable — existing questions are unaffected.
- The `tests` table vocab containers (VOCAB_WORD_XXX) work with the existing
  `test_attempts` flow, so attempt history and scoring are free.
- Phase 4 items are stretch goals — do not block Phase 3 on them.
