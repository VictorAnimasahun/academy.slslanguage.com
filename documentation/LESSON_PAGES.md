# Lesson pages and what each piece of a class is

Instructor's rules, 2026-09-26.

## Every piece of a class has a kind
A class (a `lessons` row) holds one or two pieces of content in its title, joined with " + ". Each piece is stored in
`lesson_parts` (migration 126) as **lesson**, **resource**, **practice_test** or **mock_test**.

Default rule when a piece has no stored row: anything that does not carry the word **Test** in its title is a **lesson**;
"Mock Test N" / "Mock Exam N" is a **mock**; any other "... Test ..." is a **practice test**
(`lesson_piece_kind()` in `includes/lesson_title.php`). A stored row always wins, so an exception is a data edit.
Students see the kind on the course overview (every course) and as a tag on class pages (IELTS Academic 2/3-Month).

## Every lesson piece has its own page (empty until designed)
`lesson_parts.file_path` = the page that holds the piece, relative to the academy root. `NULL` = the class's own page shows it
(courses that already have real lesson files are untouched: IELTS General, CELPIP, IELTS Academic Masterclass/Crash,
IELTS Academic 1-Month).

Lesson pieces that had no page have an **empty shell** at `courses/<course>/lessons/classNN_pK_<slug>.php`
(IELTS Academic 2/3-Month and PTE 1/2/3-Month; the Listening Formats piece points at its existing worksheet).
Each shell is ~10 lines; everything around the lesson (login, plan check from `lessons.min_tier`, header with
week / class / kind, "This lesson is being designed" notice, back link) comes from `includes/lesson_shell.php`.

**To develop a lesson:** open its shell file and put the HTML between `LESSON BODY START` and `LESSON BODY END`.
Nothing else changes. While the body is empty students see the "being designed" notice.

## Not covered / open
- No admin screen yet to change a piece's kind or file (data edit for now).
- PTE overviews do not link the shells yet (PTE is "not built" by design; the pages exist for when it is).
- Class 1 of IELTS Academic 2/3-Month ("Orientation & Diagnostic Assessment") is a lesson by the rule although it names an assessment.
