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
     and the ' . $h($scoreWord) . ' you are aiming for. The next lesson introduces the ' . $h($exam) . ' exam itself.</p>
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

if (!function_exists('crash_course_introduction_body')) {
    /**
     * Class 1, item 1 of a CRASH course (1 month): what the course is, what is expected of the student, the orientation,
     * how each kind of item works, the four weeks, and the rest of Class 1 (founder, 2026-10-24: "what is expected in that
     * program, point to the orientation simulation, the lesson and learning formats"). Class 1's order: this, the
     * Foundational English Assessment, the exam introduction, the exam quiz, the diagnostic test. The 2/3-month courses
     * keep course_introduction_body() above.
     *
     *   crash_course_introduction_body($db, 'IELTS_Aca_1Mo', 'IELTS Academic', 'band score')
     */
    function crash_course_introduction_body(PDO $db, string $folder, string $exam, string $scoreWord): string {
        $course = course_by_folder($db, $folder);
        if (!$course) return '';
        $h = fn ($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
        $weeks = (int) $course['weeks'];
        $classes = (int) $course['classes'];

        $st = $db->prepare("SELECT access_extra_months FROM courses WHERE folder_name = ? LIMIT 1");
        $st->execute([$folder]);
        $extra = (int) ($st->fetchColumn() ?: 0);

        $oriented = false;
        if (isset($_SESSION['user_id'])) {
            try {
                $st = $db->prepare("SELECT completed_at FROM student_orientation WHERE student_id = ?");
                $st->execute([(int) $_SESSION['user_id']]);
                $oriented = (bool) $st->fetchColumn();
            } catch (PDOException $e) { /* table not there yet: show the invitation */ }
        }

        // The same type labels and colours as the course page (includes/content_types.php).
        $chip = function (string $kind) use ($h): string {
            $t = content_type_info($kind);
            return '<span style="display:inline-block;padding:.12rem .55rem;border-radius:999px;font-size:.72rem;font-weight:700;background:'
                 . $h($t['bg']) . ';color:' . $h($t['fg']) . ';">' . $h($t['label']) . '</span>';
        };
        $formats = [
            ['lesson',          'Teaching: the strategy, language and examples for one part of the exam. Read it through and try what it asks. You can mark a lesson complete 5 minutes after you first open it.'],
            ['quiz',            'A short check on one topic or question type, straight after you learn it. Some quizzes have a pass mark; you can try again until you reach it.'],
            ['exercise',        'Skill practice, such as spelling and word-building games, in Resources. Short, and good on a phone between classes.'],
            ['placement_test',  'Your general English level, in Class 1. It shows whether you need English work alongside exam practice.'],
            ['diagnostic_test', 'Your starting ' . $scoreWord . ' in the ' . $exam . ' exam, at the end of Class 1. Everything after it is measured against this.'],
            ['practice_test',   'One part of the real exam (Listening, Reading, Writing or Speaking) under exam conditions: timed, and in one sitting.'],
            ['mock_test',       'The whole exam, all four parts, timed, in Week ' . $weeks . '. Your best measure of where you will land on test day.'],
        ];
        $formatRows = '';
        foreach ($formats as [$kind, $text]) {
            $formatRows .= '<tr><td style="padding:.55rem .75rem .55rem 0;vertical-align:top;white-space:nowrap;">' . $chip($kind) . '</td>'
                         . '<td style="padding:.55rem 0;">' . $h($text) . '</td></tr>';
        }

        return '
<div class="content-section">
  <h2>Welcome to the ' . $h($course['title']) . '</h2>
  <p>This is a ' . $weeks . '-week crash course: the ' . $h($exam) . ' exam, part by part, with practice under exam conditions every week
     and a full mock exam at the end. It moves fast. You get the most from it by keeping pace with the classes and doing every item in them.</p>
  <p class="mb-0">This page explains what we expect from you, how the platform works, and what the next ' . $weeks . ' weeks hold.</p>
</div>

<div class="content-section">
  <h2>What We Expect From You</h2>
  <ul>
    <li><strong>Two classes a week.</strong> ' . $classes . ' classes in all, about 2 hours each, on the days you choose in your orientation.</li>
    <li><strong>Every item in every class.</strong> A class counts as done only when everything in it is done: lessons completed, quizzes and tests submitted (and passed, where a pass mark is set).</li>
    <li><strong>Study time between classes.</strong> Keep the weekly study hours you commit to in your orientation. Practice between classes is what moves your ' . $h($scoreWord) . '.</li>
    <li><strong>Tests on a computer.</strong> Tests only open on a computer, not a phone, so that the conditions match the real exam. Sit each one in a quiet place, in one go, without help.</li>
    <li><strong>Talk to your tutor.</strong> If you fall behind or something is unclear, say so early, through Messages.</li>
  </ul>
  <p class="mb-0">You keep access for ' . ($extra > 0 ? $weeks . ' weeks plus ' . $extra . ' extra month' . ($extra > 1 ? 's' : '') : 'the ' . $weeks . ' weeks') . ', so you can go back over anything before your exam.</p>
</div>

<div class="content-section">
  <h2>Start With Your Orientation</h2>
  ' . ($oriented
    ? '<p class="mb-0"><i class="bi bi-check-circle-fill text-success me-1"></i>You have completed your orientation. You can open it again at any time to look back at the walkthrough or your plan: <a href="' . $h(ACADEMY_URL . 'orientation/index.php') . '">Open your orientation</a>.</p>'
    : '<p>Before going further, do your orientation:</p>
  <ol>
    <li>A walk through the platform, with real screens: your dashboard, your course, lessons, class slides, tests and your results.</li>
    <li>A short quiz on what you have just seen.</li>
    <li>Your plan: the days and time of your classes, your start date, your weekly study hours and your target ' . $h($scoreWord) . '. Your tutor sees this plan and works to it.</li>
  </ol>
  <p class="mb-0"><a href="' . $h(ACADEMY_URL . 'orientation/index.php') . '" class="btn btn-primary btn-sm">Start your orientation</a></p>') . '
</div>

<div class="content-section">
  <h2>How You Learn Here</h2>
  <p>Each class is made of separate items. Each item is labelled with what it is, here and on the course page:</p>
  <table style="width:100%;font-size:.93rem;"><tbody>' . $formatRows . '</tbody></table>
  <p class="mb-0 mt-2">Your scores appear in <a href="' . $h(ACADEMY_URL . 'resources/practice_tests/my_results.php') . '">My Results</a> as you go.</p>
</div>

<div class="content-section">
  <h2>Your ' . $weeks . ' Weeks</h2>
  <p class="mb-0">' . $h(course_plan_paragraph($db, $course)) . ' <a href="' . $h(ACADEMY_URL . 'courses/' . $course['folder'] . '/course_overview.php') . '">See every class on the course page</a>.</p>
</div>

<div class="content-section">
  <h2>Next in This Class</h2>
  <ol class="mb-0">
    <li><strong>Foundational English Assessment</strong>: your general English level (50 questions, one hour, on a computer).</li>
    <li><strong>' . $h($exam) . ' Introduction</strong>: the exam itself, part by part, and how it is scored.</li>
    <li><strong>' . $h($exam) . ' Introduction Quiz</strong>: a check on what you have just learned about the exam.</li>
    <li><strong>' . $h($exam) . ' Diagnostic Test</strong>: your starting ' . $h($scoreWord) . ', to measure every later result against.</li>
  </ol>
</div>';
    }
}
