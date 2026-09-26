# The principle: structure first, content later

Stated by the instructor, 2026-09-26. Everything about lessons, tests and pages follows from it.

1. **The shape is declared before any content exists.** Course → week → class → pieces. Every piece of every class is
   recorded in data with (a) a **kind** and (b) its own **page**, even while that page is empty.
2. **A piece is exactly one of four kinds:** *lesson*, *resource*, *practice test*, *mock test*. Anything that does not
   carry the word "Test" in its title is a lesson ("Mock Test/Exam N" is a mock). Students always see which kind a thing is.
3. **The instructor designs the content, page by page, later.** Developing a lesson means editing that lesson's own page.
   It never requires changing structure, navigation, access rules or the database.
4. **Anything with nothing in it yet says "Coming Soon"**: course, class, lesson, resource, test, mock: one wording, one look (`includes/coming_soon.php`). Nothing is hidden or retired for being empty. No filler, no placeholder bullet points, no invented content.
5. **Never overwrite developed content.** New pages are added beside existing ones; an existing page is only replaced when the
   instructor says so.
6. **Rules live in data, in one place** (kind and page in `lesson_parts`, plan in the class row), not typed into many files.
7. **The shared frame never limits the design.** The default lesson frame is a convenience; any lesson page may ignore it and
   write its own layout (see "Designing a lesson freely").

# Lesson pages: how it is built

## Every piece of a class has a kind
A class (a `lessons` row) holds one or two pieces of content in its title, joined with " + ". Each piece is stored in
`lesson_parts` (migration 126) as **lesson**, **resource**, **practice_test** or **mock_test**. Default when a piece has no
stored row: `lesson_piece_kind()` in `includes/lesson_title.php`. A stored row always wins, so an exception is a data edit.
Students see the kind on the course overview (every course) and as a tag on class pages (IELTS Academic 2/3-Month).
In the overview accordion, a lesson with its own page links straight to it, under its class.

## Coming Soon: where it is decided
- **Course:** `courses.availability = 'coming_soon'` (a business decision: "ready to sell?"). The catalogue shows a Coming Soon pill and a disabled button; the course page shows the Coming Soon box and refuses enrolment.
- **Piece:** a piece with a page is judged by the page (an empty lesson file = Coming Soon); a piece without one follows `lesson_parts.status`.
- **Class:** Coming Soon when every one of its pieces is. The overview tags all three.

## Every lesson piece has its own page (empty until designed)
`lesson_parts.file_path` = the page that holds the piece, relative to the academy root. `NULL` = the class's own page shows it.

Lesson pieces that had **no page at all** have an empty file at `courses/<course>/lessons/classNN_pK_<slug>.php`
(IELTS Academic 2/3-Month and PTE 1/2/3-Month; Listening Formats points at its existing worksheet). 75 files.

**Other courses were left exactly as they are** (IELTS General 1/2/3-Month, CELPIP 1/2/3-Month, IELTS Academic Masterclass /
Crash / 1-Month): no file was changed. Their pages do hold real lesson text (hundreds of words per class; CELPIP 2/3-Month
through one shared page, `class_day.php`), but most also say "coming soon" for their quizzes or tests. Whether every lesson
piece there should also get its own empty page is an open decision for the instructor.

## Developing a lesson
Open the lesson's file and put its HTML between `LESSON BODY START` and `LESSON BODY END`. Everything around it (login, plan
check from `lessons.min_tier`, header with week/class/kind, back link) is drawn by `includes/lesson_shell.php`. While the body
is empty students see "Coming Soon" (the marker comments alone do not count as content).

## Designing a lesson freely (no frame)
A lesson page can skip the default frame and be laid out however it likes. Keep the two access lines and write your own HTML:

```php
require_once dirname(__DIR__, 3) . '/bootstrap.php';
require_once INCLUDES_PATH . '/lesson_shell.php';
$ctx = lesson_context($db, 'IELTS_Aca_2Mo', 7, 2);          // login check + who may see it + what/where this is
if (!$ctx['can_access']) { echo lesson_locked_html($ctx); exit; }
// ...your own <html>, CSS and scripts. Ready to use: $ctx['title'], ['kind'], ['week'], ['class'], ['class_total'], ['back_url'].
```

## Open
- No admin screen yet to change a piece's kind or file (data edit for now).
- PTE overviews do not link the pages yet (PTE is "not built" by design; the pages exist for when it is).
- Class 1 of IELTS Academic 2/3-Month ("Orientation & Diagnostic Assessment") is a lesson by the rule although it names an assessment.
- Practice-test rows in the overview still link to the class page (where the Open Test button is), not straight to the test.
