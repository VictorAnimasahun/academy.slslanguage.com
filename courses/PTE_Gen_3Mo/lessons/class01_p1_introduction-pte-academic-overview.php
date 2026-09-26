<?php
// Introduction & PTE Academic Overview — PTE Academic Masterclass — 3 Months, Class 1, piece 1 (lesson).
// EMPTY SHELL. To develop this lesson, put its HTML between the two LESSON BODY markers below.
// Everything around it (login, plan check, header, back link) comes from includes/lesson_shell.php.
require_once dirname(__DIR__, 3) . '/bootstrap.php';
require_once INCLUDES_PATH . '/lesson_shell.php';

ob_start(); ?>
<!-- LESSON BODY START -->
<!-- DRAFT: written by Claude on 2026-09-26 for the instructor to review and edit before students see it. Not yet checked. -->
<style>
.lc { font-size:.97rem; line-height:1.75; color:#1f2937; }
.lc h5 { margin:1.6rem 0 .5rem; font-weight:700; color:#0b4fb3; }
.lc .lc-aim { border-left:4px solid #0b77ff; background:#f0f7ff; border-radius:8px; padding:1rem 1.25rem; margin-bottom:1.25rem; }
.lc .lc-aim strong { display:block; margin-bottom:.35rem; }
.lc .lc-aim ul { margin:0; padding-left:1.1rem; }
.lc .lc-tip { border-left:4px solid #16a34a; background:#f0fdf4; border-radius:8px; padding:.8rem 1.1rem; margin:1rem 0; }
.lc .lc-warn { border-left:4px solid #dc2626; background:#fef2f2; border-radius:8px; padding:.8rem 1.1rem; margin:1rem 0; }
.lc .lc-ex { border:1px solid #e5e7eb; background:#fafafa; border-radius:8px; padding:.9rem 1.1rem; margin:1rem 0; }
.lc .lc-ex .lc-ex-t { font-size:.75rem; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:#6b7280; margin-bottom:.35rem; }
.lc table { width:100%; border-collapse:collapse; margin:.75rem 0 1rem; font-size:.92rem; }
.lc th { background:#eef2ff; text-align:left; padding:.5rem .7rem; border:1px solid #dbe1f0; }
.lc td { padding:.45rem .7rem; border:1px solid #e5e7eb; vertical-align:top; }
.lc .lc-try { border:2px solid #0b77ff; border-radius:10px; padding:1rem 1.25rem; margin:1.4rem 0; }
.lc .lc-try .lc-try-t { font-weight:700; color:#0b77ff; margin-bottom:.4rem; }
.lc details { margin-top:.6rem; } .lc summary { cursor:pointer; font-weight:600; color:#0b4fb3; }
.lc .lc-take { background:#111827; color:#f9fafb; border-radius:10px; padding:1rem 1.25rem; margin-top:1.6rem; }
.lc .lc-take strong { color:#93c5fd; }
</style>
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>describe the three parts of PTE Academic and the tasks in each</li><li>explain how the AI scoring works and what it rewards</li><li>plan how to use this course</li></ul></div><h5>The test at a glance</h5>
<p>PTE Academic is a computer-based test of about two hours. You answer at a computer, wearing a headset with a microphone. The test is scored by computer on a scale of <strong>10 to 90</strong>, and one task can count towards more than one skill.</p>
<table><thead><tr><th>Part</th><th>What it contains</th><th>Skills scored</th></tr></thead><tbody><tr><td>Part 1: Speaking and Writing</td><td>Personal Introduction, Read Aloud, Repeat Sentence, Describe Image, Re-tell Lecture, Answer Short Question, Summarize Written Text, Essay</td><td>Speaking, Writing</td></tr><tr><td>Part 2: Reading</td><td>Fill in the Blanks (two kinds), Multiple Choice (two kinds), Re-order Paragraphs</td><td>Reading, Writing</td></tr><tr><td>Part 3: Listening</td><td>Summarize Spoken Text, Multiple Choice (two kinds), Fill in the Blanks, Highlight Correct Summary, Select Missing Word, Highlight Incorrect Words, Write from Dictation</td><td>Listening, Writing</td></tr></tbody></table>
<h5>Enabling skills</h5>
<p>Besides the four main skills, the computer also scores <strong>enabling skills</strong>: grammar, oral fluency, pronunciation, spelling, vocabulary and written discourse. They are reported separately and they feed into the main skills. A single Read Aloud answer, for example, gives marks for reading, speaking, oral fluency and pronunciation.</p>
<h5>What the computer can and cannot judge</h5>
<ul><li>It can hear <strong>clarity</strong>, speed and rhythm, not accent. A clear accent is fine.</li><li>It counts <strong>content words</strong> you say in the right order in Repeat Sentence and Re-tell Lecture.</li><li>It checks spelling, grammar, structure and length in writing.</li><li>It does not reward memorised templates that ignore the question.</li></ul>
<div class="lc-warn"><strong>Watch out.</strong> Task time and word limits matter. An essay under 200 words or over 300, or a summary that is not a single sentence, loses marks even if the writing is good.</div>
<h5>How this course works</h5>
<ul><li>Each week teaches two or three task types, then practises them under time.</li><li>Use the practice tests to find your weak task types. The AI review lesson later in the course looks at your scores.</li><li>Speak aloud and record yourself every day. Speaking tasks are where most candidates lose marks.</li></ul>
<div class="lc-take"><strong>Take away.</strong> Learn the tasks, know what the AI rewards, and practise under real timings from the first week.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'PTE_Gen_3Mo', 1, 1, ob_get_clean());
