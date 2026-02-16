# E‑Learning Assessment Platform – Database Schema

This document describes the **complete, consolidated database schema** for an e‑learning and assessment platform designed for **IELTS, CELPIP, TOEFL, and custom exams**.

The schema supports:

- IELTS-style exams (varied question types: gap-fill, matching, essays, speaking)
- CELPIP-style exams (largely MCQ-based reading & listening)
- Student authentication and identity
- Test attempts and history
- One-attempt or multiple-attempt rules
- Practice vs mock test modes
- Objective auto-marking and subjective manual marking
- Future extensibility (analytics, remarks, adaptive testing)

The design follows **modern (2025–2026) relational best practices**:
- Unsigned numeric IDs
- Clear foreign keys and cascading rules
- Indexes for performance
- Enums for constrained states
- Separation of content, attempts, and results

---

## Core Conceptual Model

The schema is intentionally divided into **three layers**:

1. **Content Layer** – what the exam *is*
   - Tests
   - Questions
   - Options and correct answers

2. **Attempt Layer** – who took what, when, and how many times
   - Test attempts
   - Per-question answers

3. **User Layer** – who the student is
   - Students (authentication & identity)

This separation ensures clean logic, scalability, and auditability.

---

## 1. Students

**Purpose:**
Stores authenticated learners (students). This table already exists in your system and replaces a generic `users` table.

Each student can:
- Take many tests
- Have multiple attempts (unless restricted)
- Accumulate a complete test history

```sql
CREATE TABLE students (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name       VARCHAR(150) NOT NULL,
    email           VARCHAR(150) UNIQUE NOT NULL,
    password_hash   VARCHAR(255) NOT NULL,
    is_active       TINYINT(1) DEFAULT 1,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);
```

---

## 2. Tests / Exams / Quizzes

**Purpose:**
Defines an exam or test container (e.g. *IELTS Academic Mock Test 1*, *CELPIP Reading Practice*).

A test:
- Belongs to a test type (IELTS, CELPIP, etc.)
- May represent a full exam or a single section
- Controls duration, availability, and rules

```sql
CREATE TABLE tests (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code              VARCHAR(32) UNIQUE,
    title             VARCHAR(150) NOT NULL,
    description       TEXT,
    test_type         ENUM('IELTS','CELPIP','TOEFL','Custom') DEFAULT 'Custom',
    section           VARCHAR(50),
    duration_minutes  SMALLINT UNSIGNED,
    total_questions   SMALLINT UNSIGNED,
    mode              ENUM('practice','mock') DEFAULT 'practice',
    is_active         TINYINT(1) DEFAULT 1,
    created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 3. Questions

**Purpose:**
Stores the actual exam items (reading questions, listening questions, writing tasks, speaking prompts).

Supports all IELTS and CELPIP question formats.

```sql
CREATE TABLE questions (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    test_id           INT UNSIGNED NOT NULL,
    question_number   SMALLINT UNSIGNED NOT NULL,
    stimulus_text     TEXT,
    question_text     TEXT NOT NULL,
    question_type     ENUM(
        'multiple_choice_single',
        'multiple_choice_multiple',
        'true_false_not_given',
        'yes_no_not_given',
        'matching',
        'gap_fill',
        'sentence_completion',
        'summary_completion',
        'table_completion',
        'form_note_completion',
        'diagram_map_labelling',
        'short_answer',
        'essay',
        'letter',
        'speaking_long_turn',
        'speaking_discussion'
    ) NOT NULL,
    instructions      TEXT,
    points            DECIMAL(4,1) DEFAULT 1.0,
    display_order     SMALLINT UNSIGNED DEFAULT 10,
    created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (test_id) REFERENCES tests(id) ON DELETE CASCADE,
    INDEX idx_test_order (test_id, display_order)
);
```

---

## 4. Question Options

**Purpose:**
Stores selectable options for questions that require them (MCQs, matching, etc.).

Zero rows = non-option questions (essays, gap-fills).

```sql
CREATE TABLE question_options (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question_id     INT UNSIGNED NOT NULL,
    option_label    VARCHAR(16) NOT NULL,
    option_text     TEXT NOT NULL,
    is_correct      TINYINT(1) DEFAULT 0,
    weight          DECIMAL(4,2) DEFAULT 1.00,
    display_order   SMALLINT UNSIGNED DEFAULT 10,

    UNIQUE KEY uq_q_label (question_id, option_label),
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
    INDEX idx_question (question_id)
);
```

---

## 5. Correct Answers (Non‑MCQ)

**Purpose:**
Stores acceptable answers for gap-fills, short answers, and similar question types.

```sql
CREATE TABLE question_correct_answers (
    id                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question_id       INT UNSIGNED NOT NULL,
    answer_text       VARCHAR(255) NOT NULL,
    is_case_sensitive TINYINT(1) DEFAULT 0,
    is_alternative    TINYINT(1) DEFAULT 0,
    created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
    INDEX idx_question (question_id)
);
```

---

## 6. Test Attempts

**Purpose:**
Represents a single sitting of a test by a student.

This table is **central** to:
- Recording scores
- Enforcing one-attempt rules
- Tracking history
- Distinguishing practice vs mock

```sql
CREATE TABLE test_attempts (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id      INT UNSIGNED NOT NULL,
    test_id         INT UNSIGNED NOT NULL,

    attempt_number  SMALLINT UNSIGNED DEFAULT 1,
    started_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at    TIMESTAMP NULL,

    score           DECIMAL(6,2) NULL,
    max_score       DECIMAL(6,2) NULL,
    band_score      DECIMAL(3,1) NULL,
    status          ENUM('in_progress','completed','abandoned') DEFAULT 'in_progress',

    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (test_id) REFERENCES tests(id),

    UNIQUE KEY uq_student_test_attempt (student_id, test_id, attempt_number),
    INDEX idx_student_test (student_id, test_id)
);
```

**One-attempt enforcement:**
- Enforce at application level, or
- Add a unique constraint on `(student_id, test_id)` for strict mocks

---

## 7. Attempt Answers

**Purpose:**
Stores every answer given by a student during an attempt.

Supports:
- Auto-marking (MCQs)
- Manual marking (writing & speaking)
- Analytics and re-marking

```sql
CREATE TABLE attempt_answers (
    id                    BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    attempt_id            BIGINT UNSIGNED NOT NULL,
    question_id           INT UNSIGNED NOT NULL,

    selected_option_id    BIGINT UNSIGNED NULL,
    answer_text           TEXT NULL,
    score_awarded         DECIMAL(4,1) DEFAULT 0,

    created_at            TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (attempt_id) REFERENCES test_attempts(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id),
    FOREIGN KEY (selected_option_id) REFERENCES question_options(id),

    UNIQUE KEY uq_attempt_question (attempt_id, question_id)
);
```

---

## Common Queries Enabled

- **All tests taken by a student**
- **Prevent retaking a mock test**
- **Score breakdown per section**
- **Band score recalculation**
- **Question-level analytics (difficulty, failure rate)**

---

## Final Notes

This schema is:
- Production-ready
- Exam-board aligned (IELTS / CELPIP logic)
- Framework-agnostic (Laravel, Django, Node, etc.)
- Designed for long-term growth

It cleanly separates **content**, **attempts**, and **results**, which is the key architectural requirement for any serious assessment platform.

---

*End of document*

