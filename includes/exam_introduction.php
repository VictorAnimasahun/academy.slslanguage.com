<?php
/**
 * Class 1, item 3 of a CRASH course: the exam introduction (founder, 2026-10-24: course introduction, Foundational
 * English Assessment, exam introduction, exam quiz, diagnostic test). One structure for all four exams: what the exam
 * is, the test in one table, how it is scored, each part's question types, test day, and what each week of this
 * course covers (read from the course itself). The 2/3-month courses keep their own exam introduction pages.
 *
 *   crash_exam_introduction_body($db, 'IELTS_Aca_1Mo', 'ielts_academic')   -> HTML for a lesson_shell page
 *
 * Facts are the test owners' published formats (IDP/British Council for IELTS, Paragon for CELPIP, Pearson for PTE).
 * When an owner changes a format, change it here once.
 */
require_once __DIR__ . '/course_shape.php';

if (!function_exists('crash_exam_introduction_body')) {

    function exam_intro_data(string $exam): ?array {
        $ieltsListening = ['Listening', 'About 30 minutes (paper: plus 10 minutes to copy answers; computer: plus 2 minutes to check)',
            '4 recordings, 40 questions. You hear each recording once.', 'Answer key, 1 mark a question'];
        $ieltsSpeaking = ['Speaking', '11–14 minutes', 'A recorded interview with an examiner, in three parts.', 'Examiner, 4 criteria'];
        $ieltsScoring = [
            'Each of the four parts gets a band from 0 to 9, in whole or half bands. Listening and Reading are marked out of 40 and turned into a band; Writing and Speaking are marked by trained examiners against four criteria, which count equally.',
            'Your <strong>overall band</strong> is the average of the four, rounded to the nearest half band: an average of 6.25 becomes 6.5, and 6.75 becomes 7.',
            'Universities and immigration authorities usually set a minimum overall band and often a minimum for each part. Know both numbers for your goal before you start.',
        ];
        $ieltsCriteria = ['Writing: Task Achievement (Task 1) or Task Response (Task 2); Coherence and Cohesion; Lexical Resource; Grammatical Range and Accuracy.',
                          'Speaking: Fluency and Coherence; Lexical Resource; Grammatical Range and Accuracy; Pronunciation.'];
        $ieltsListeningTypes = 'Multiple choice; matching; labelling a plan, map or diagram; completing a form, notes, a table, a flow-chart or a summary; completing sentences; short answers. The recordings move from everyday conversation (Parts 1 and 2) to academic talk (Parts 3 and 4).';
        $ieltsSpeakingTypes = 'Part 1 (4–5 minutes): questions about you and familiar topics. Part 2 (3–4 minutes): a card with a topic; 1 minute to prepare, then speak for up to 2 minutes. Part 3 (4–5 minutes): a discussion of wider, more abstract questions linked to Part 2.';
        $ieltsDay = [
            'Listening, Reading and Writing are taken together, in that order, with no break between them. Speaking is on the same day or within a week before or after.',
            'Bring the same identity document you registered with. Nothing else goes into the room.',
            'Paper or computer: the questions and timing are the same. On paper, spelling and transfer time matter; on computer, you type and can check at the end of Listening.',
        ];
        $ieltsReadingTypes = 'Multiple choice; True / False / Not Given; Yes / No / Not Given; matching headings, information, features and sentence endings; completing sentences, summaries, notes, tables, flow-charts and diagrams; short answers.';

        $data = [
            'ielts_academic' => [
                'name' => 'IELTS Academic', 'score' => 'band score',
                'what' => 'IELTS Academic is for university and college study and for professional registration (for example, nursing or medicine) in English-speaking countries. It tests whether your English is ready for study at that level. The Listening and Speaking parts are the same as in IELTS General Training; Reading and Writing are academic.',
                'table' => [$ieltsListening,
                    ['Reading', '60 minutes', '3 long texts from books, journals, magazines and newspapers, 40 questions. No extra time to copy answers.', 'Answer key, 1 mark a question'],
                    ['Writing', '60 minutes', 'Task 1: describe a graph, chart, table, diagram, map or process (at least 150 words). Task 2: an essay (at least 250 words). Task 2 counts twice as much as Task 1.', 'Examiner, 4 criteria'],
                    $ieltsSpeaking],
                'scoring' => $ieltsScoring, 'criteria' => $ieltsCriteria,
                'parts' => [
                    ['Listening', $ieltsListeningTypes],
                    ['Reading', $ieltsReadingTypes . ' The texts are long and written for a general but educated reader; managing 60 minutes across three of them is a skill in itself.'],
                    ['Writing', 'Task 1 (about 20 minutes): report the main features of visual information and compare where relevant, without giving opinions. Task 2 (about 40 minutes): an essay giving an argument, a discussion or a solution, with reasons and examples.'],
                    ['Speaking', $ieltsSpeakingTypes],
                ],
                'day' => $ieltsDay,
            ],
            'ielts_general' => [
                'name' => 'IELTS General Training', 'score' => 'band score',
                'what' => 'IELTS General Training is for migration (Canada, Australia, New Zealand and the UK all accept it), for work, and for training below degree level. It tests English for everyday life and the workplace. The Listening and Speaking parts are the same as in IELTS Academic; Reading and Writing are general.',
                'table' => [$ieltsListening,
                    ['Reading', '60 minutes', '3 sections, 40 questions: short everyday texts, then work-related texts, then one longer text on a topic of general interest. No extra time to copy answers.', 'Answer key, 1 mark a question'],
                    ['Writing', '60 minutes', 'Task 1: a letter (at least 150 words). Task 2: an essay (at least 250 words). Task 2 counts twice as much as Task 1.', 'Examiner, 4 criteria'],
                    $ieltsSpeaking],
                'scoring' => $ieltsScoring, 'criteria' => $ieltsCriteria,
                'parts' => [
                    ['Listening', $ieltsListeningTypes],
                    ['Reading', $ieltsReadingTypes . ' Section 1 is notices, adverts and timetables; Section 2 is about work (job descriptions, contracts, training); Section 3 is one longer, harder text.'],
                    ['Writing', 'Task 1 (about 20 minutes): a letter asking for information or explaining a situation, to a friend, a manager or an organisation. The right tone (informal, semi-formal or formal) is part of the mark. Task 2 (about 40 minutes): an essay giving your view on a problem or an argument, with reasons and examples.'],
                    ['Speaking', $ieltsSpeakingTypes],
                ],
                'day' => $ieltsDay,
            ],
            'celpip' => [
                'name' => 'CELPIP General', 'score' => 'CELPIP level',
                'what' => 'CELPIP General is a Canadian English test, accepted by Immigration, Refugees and Citizenship Canada (IRCC) for permanent residence and citizenship, and by some professional bodies. It is in Canadian English and about everyday life in Canada: work, community and daily situations.',
                'table' => [
                    ['Listening', '47–55 minutes', '6 parts, 38 questions. You hear each recording once.', 'Answer key'],
                    ['Reading', '55–60 minutes', '4 parts, 38 questions.', 'Answer key'],
                    ['Writing', '53–60 minutes', 'Task 1: an email (27 minutes). Task 2: a response to survey questions (26 minutes). 150–200 words each.', 'Trained raters'],
                    ['Speaking', '15–20 minutes', '8 tasks, spoken into a microphone and recorded. Short preparation time, then 60–90 seconds to speak.', 'Trained raters'],
                ],
                'scoring' => [
                    'Each part gets a <strong>CELPIP level</strong> from 1 to 12 (or M, for not enough to rate). There is no overall score: you get four levels.',
                    'Each CELPIP level matches the same number on the Canadian Language Benchmarks (CLB): CELPIP 9 is CLB 9. Immigration programmes state the CLB you need in each part.',
                    'Listening and Reading are marked by answer key and converted into a level. Writing and Speaking are rated by trained raters.',
                ],
                'criteria' => ['Writing: Content and Coherence; Vocabulary; Readability; Task Fulfillment.',
                               'Speaking: Content and Coherence; Vocabulary; Listenability; Task Fulfillment.'],
                'parts' => [
                    ['Listening', 'Problem solving; a daily-life conversation; listening for information; a news item; a discussion; viewpoints. Most questions are multiple choice, some with pictures. You cannot replay a recording.'],
                    ['Reading', 'Correspondence (an email or letter); applying a diagram; reading for information; reading for viewpoints. Multiple choice, including choosing the right word for a gap and matching statements to paragraphs.'],
                    ['Writing', 'Task 1: an email for a real situation (a complaint, a request, a thank-you), with the right tone. Task 2: choose one of two options in a survey and give your reasons. Both are typed on screen.'],
                    ['Speaking', 'Giving advice; a personal experience; describing a scene; making predictions; comparing and persuading; dealing with a difficult situation; expressing opinions; describing an unusual situation. Each answer is timed on screen.'],
                ],
                'day' => [
                    'The whole test is on a computer, in one sitting of about 3 hours, in this order: Listening, Reading, Writing, Speaking.',
                    'Bring the identity document you registered with. You speak into a headset microphone; there is no examiner in front of you.',
                    'Timers run on screen for every part and task. When time is up, the test moves on, so pacing is part of the skill.',
                ],
            ],
            'pte' => [
                'name' => 'PTE Academic', 'score' => 'PTE score',
                'what' => 'PTE Academic, by Pearson, is accepted by universities worldwide and for visas in Australia, New Zealand and the UK. For Canadian immigration, the accepted Pearson test is a different one, PTE Core. Check which test and score your goal needs before you book.',
                'table' => [
                    ['Speaking and Writing', '54–67 minutes', 'A short personal introduction, then speaking tasks (read aloud, repeat sentences, describe images, re-tell lectures, answer short questions and more) and writing tasks (summarise a text, write an essay).', 'Computer'],
                    ['Reading', '29–30 minutes', 'Fill in the blanks (two kinds), multiple choice (two kinds), re-order paragraphs.', 'Computer'],
                    ['Listening', '30–43 minutes', 'Summarise a spoken text, multiple choice, fill in the blanks, highlight the correct summary, select the missing word, highlight incorrect words, write from dictation.', 'Computer'],
                ],
                'scoring' => [
                    'You get an <strong>overall score from 10 to 90</strong>, and a score from 10 to 90 for each of Listening, Reading, Speaking and Writing.',
                    'Many tasks count towards more than one skill: Read Aloud counts for Speaking and Reading; Write from Dictation counts for Listening and Writing. A strong task can lift two scores at once, and a weak one can pull two down.',
                    'Scoring is by computer, trained on human examiners. It rewards content, form (the right length and format), and how fluent and clear you are.',
                ],
                'criteria' => ['Speaking tasks: content, oral fluency (smooth, even pace, no long pauses or restarts) and pronunciation.',
                               'Writing tasks: content, form (word limits matter), grammar, vocabulary, spelling and, in the essay, structure and coherence.'],
                'parts' => [
                    ['Speaking', 'Most speaking tasks give you a few seconds to prepare, then record once. You cannot re-record. A long silence ends the recording, so keep going even after a mistake.'],
                    ['Writing', 'Summarise Written Text: one sentence of 5–75 words. Essay: 200–300 words in 20 minutes. Going outside the word limits costs marks.'],
                    ['Reading', 'The Reading part is timed as a whole, not task by task, so manage the clock yourself. Re-order Paragraphs and the fill-in-the-blanks tasks reward knowing how sentences and texts link together.'],
                    ['Listening', 'Each recording plays once. Summarise Spoken Text and Write from Dictation come first and last; Write from Dictation is short but counts for both Listening and Writing.'],
                ],
                'day' => [
                    'The whole test is on a computer, in one sitting of about 2 hours, with a headset and microphone.',
                    'Bring the identity document you registered with. Your photo, palm vein scan and signature are taken at check-in.',
                    'Other test takers speak at the same time around you. Practise speaking clearly with background noise.',
                ],
            ],
        ];
        return $data[$exam] ?? null;
    }

    function crash_exam_introduction_body(PDO $db, string $folder, string $exam): string {
        $d = exam_intro_data($exam);
        $course = course_by_folder($db, $folder);
        if (!$d || !$course) return '';
        $h = fn ($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');

        $rows = '';
        foreach ($d['table'] as [$part, $time, $what, $marked]) {
            $rows .= '<tr><td><strong>' . $h($part) . '</strong></td><td>' . $h($time) . '</td><td>' . $h($what) . '</td><td>' . $h($marked) . '</td></tr>';
        }
        $parts = '';
        foreach ($d['parts'] as [$part, $text]) {
            $parts .= '<h3 class="ei-h3">' . $h($part) . '</h3><p>' . $h($text) . '</p>';
        }
        // What each week of this course covers, from the course itself.
        $st = $db->prepare("SELECT m.module_title FROM modules m JOIN courses c ON c.id = m.course_id WHERE c.folder_name = ? ORDER BY m.module_order");
        $st->execute([$folder]);
        $weeks = '';
        foreach ($st->fetchAll(PDO::FETCH_COLUMN) as $t) $weeks .= '<li>' . $h($t) . '</li>';
        // The item after this one in Class 1 (the quiz), by its real name.
        $st = $db->prepare("SELECT p.title FROM lesson_parts p JOIN lessons l ON l.id = p.lesson_id JOIN modules m ON m.id = l.module_id
                             JOIN courses c ON c.id = l.course_id
                            WHERE c.folder_name = ? AND m.module_order = 1 AND l.lesson_order = 1 AND p.part_order = 4");
        $st->execute([$folder]);
        $quiz = $st->fetchColumn() ?: $d['name'] . ' Introduction Quiz';

        return '
<style>
  .ei table { width: 100%; border-collapse: collapse; font-size: .92rem; margin: .5rem 0 1rem; }
  .ei th { text-align: left; padding: .5rem .7rem; border: 1px solid #dbe1f0; background: #f4f7fd; }
  .ei td { padding: .5rem .7rem; border: 1px solid #e5e7eb; vertical-align: top; }
  .ei .ei-box { border: 1px solid #dbe1f0; border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: 1rem; }
  .ei .ei-h3 { font-size: 1.02rem; font-weight: 700; margin: 1.1rem 0 .35rem; color: #16202f; }
  body.dark .ei th { background: #1e293b; border-color: #334155; }
  body.dark .ei td, body.dark .ei .ei-box { border-color: #334155; }
  body.dark .ei .ei-h3 { color: #e2e8f0; }
</style>
<div class="ei">
<div class="ei-box">
  <strong>By the end of this lesson you can:</strong>
  <ul class="mb-0">
    <li>say what ' . $h($d['name']) . ' is for and who accepts it</li>
    <li>name every part of the test, its timing and what you do in it</li>
    <li>explain how your ' . $h($d['score']) . ' is worked out</li>
  </ul>
</div>

<div class="content-section">
  <h2>What ' . $h($d['name']) . ' Is</h2>
  <p class="mb-0">' . $h($d['what']) . '</p>
</div>

<div class="content-section">
  <h2>The Test in One Table</h2>
  <table><thead><tr><th>Part</th><th>Time</th><th>What you do</th><th>Marked by</th></tr></thead><tbody>' . $rows . '</tbody></table>
</div>

<div class="content-section">
  <h2>How It Is Scored</h2>
  <ul>' . implode('', array_map(fn ($s) => '<li>' . $s . '</li>', $d['scoring'])) . '</ul>
  <p class="mb-1"><strong>What the raters look for</strong></p>
  <ul class="mb-0">' . implode('', array_map(fn ($s) => '<li>' . $h($s) . '</li>', $d['criteria'])) . '</ul>
</div>

<div class="content-section">
  <h2>Each Part, Closer Up</h2>' . $parts . '
</div>

<div class="content-section">
  <h2>Test Day</h2>
  <ul class="mb-0">' . implode('', array_map(fn ($s) => '<li>' . $h($s) . '</li>', $d['day'])) . '</ul>
</div>

<div class="content-section">
  <h2>How This Course Covers It</h2>
  <p>The ' . (int) $course['weeks'] . ' weeks, as they appear on your course page:</p>
  <ol class="mb-0">' . $weeks . '</ol>
</div>

<div class="content-section">
  <h2>Next</h2>
  <p class="mb-0">The <strong>' . $h($quiz) . '</strong> checks what you have just read: the parts, the timing and the scoring. Then the diagnostic test shows where you are starting from.</p>
</div>
</div>';
    }
}
