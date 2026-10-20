<?php
/**
 * Class 1, item 1 of a 2/3-month course: the COURSE introduction (founder, 2026-10-19 -- Class 1 introduces the
 * course, Class 2 the exam). Everything course-specific comes from the database (includes/course_shape.php):
 * name, length, and the real week-by-week timetable, so the page can't drift from the course.
 *
 *   course_introduction_body($db, 'IELTS_Aca_2Mo', 'IELTS Academic', 'band')   -> HTML for a lesson_shell page
 */
require_once __DIR__ . '/course_shape.php';
require_once __DIR__ . '/content_types.php';

if (!function_exists('course_introduction_body')) {
    function course_introduction_body(PDO $db, string $folder, string $exam, string $scoreWord): string {
        $course = course_by_folder($db, $folder);
        if (!$course) return '';
        $h = fn ($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');

        return '
<div class="content-section">
  <h2>Welcome to the ' . $h($course['title']) . '</h2>
  <p>This class sets you up for the next ' . (int) $course['weeks'] . ' weeks: how the course runs, where you are starting from,
     and the ' . $h($scoreWord) . ' you are aiming for. Next class introduces the ' . $h($exam) . ' exam itself.</p>
</div>

<div class="content-section">
  <h2>How the Course Runs</h2>
  <ul>
    <li><strong>' . (int) $course['classes'] . ' classes</strong> over ' . (int) $course['weeks'] . ' weeks: two a week, 2 hours each.</li>
    <li>Each class is a day, made of separate items: lessons, and quizzes, exercises or tests where the class has them.</li>
    <li>Tests are taken on a computer, not on your phone, so that conditions match the real exam. Lessons, quizzes and exercises work anywhere.</li>
  </ul>
</div>

<div class="content-section">
  <h2>Your Plan</h2>
  <p class="mb-0">' . $h(course_plan_paragraph($db, $course)) . ' <a href="' . $h(ACADEMY_URL . 'courses/' . $course['folder'] . '/course_overview.php') . '">See every class on the course page</a>.</p>
</div>

<div class="content-section">
  <h2>Your Self-Assessment</h2>
  <p>Before your first test, answer these honestly. There is no scoring here, only your own baseline:</p>
  <ol>
    <li>How comfortable are you having an unscripted conversation in English for 5+ minutes on an everyday topic?</li>
    <li>When reading a page of English text you haven\'t seen before, how much do you understand without a dictionary?</li>
    <li>When writing in English, how long does composing 150 words typically take you?</li>
    <li>When listening to English spoken at a natural, non-slowed pace, what percentage do you feel you follow?</li>
  </ol>
  <p class="mb-0">Your answers, set against your Foundational English Assessment, show your instructor whether a gap is a
     <em>confidence</em> problem or a real <em>skill</em> gap that needs English work before exam strategy will help.</p>
</div>

<div class="content-section">
  <h2>Your Target</h2>
  <p class="mb-0">Know the ' . $h($scoreWord) . ' you need, overall and in each skill, before you start. Write it down with your
     self-assessment answers and keep them for your instructor: every result in this course is read against that target.</p>
</div>';
    }
}
