<?php
// IELTS Listening: Overview — IELTS Academic — 1-Month Crash Course, Class 2, piece 1 (lesson).
// The LISTENING_OVERVIEW video (founder's, 2026-10-28) and the test's format, timing, marking and band table.
require_once dirname(__DIR__, 3) . '/bootstrap.php';
require_once INCLUDES_PATH . '/lesson_shell.php';
require_once __DIR__ . '/listening_kit.php';

ob_start(); ?>
<!-- LESSON BODY START -->
<?= listening_kit_assets() ?>
<div class="lc">
<div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul>
<li>describe the four parts of IELTS Listening and what you hear in each</li>
<li>explain how the test is timed and marked, and what your raw score means as a band</li>
<li>name the rules that cost candidates marks: one hearing, spelling and word limits</li>
</ul></div>

<h5>Watch: the Listening test in four minutes</h5>
<video controls preload="metadata" src="<?= ACADEMY_URL ?>assets/video/ielts_listening/listening_overview.mp4"></video>

<h5>The test at a glance</h5>
<p>IELTS Listening is the same test for Academic and General Training. It has <strong>four parts</strong> and <strong>40 questions</strong>, ten in each part, and the recordings last about <strong>30 minutes</strong>. You hear each recording <strong>once only</strong>, so you answer while you listen.</p>
<table><thead><tr><th>Part</th><th>What you hear</th><th>Typical example</th></tr></thead><tbody>
<tr><td>1</td><td>A conversation between two people in an everyday situation</td><td>Booking a course, joining a leisure centre, reporting a lost item</td></tr>
<tr><td>2</td><td>One person speaking about an everyday topic</td><td>A guide describing a museum, a talk about local services</td></tr>
<tr><td>3</td><td>Up to four people discussing something in an education or training setting</td><td>Two students and a tutor talking about an assignment</td></tr>
<tr><td>4</td><td>One person giving a talk on an academic subject</td><td>A university lecture</td></tr>
</tbody></table>
<p>The parts get harder as you go: Part 1 is the most predictable, Part 4 the most academic. Before each part you get a short time to read the questions; use it to underline keywords and predict the answers (the next lessons show you how).</p>

<h5>Timing on paper and on computer</h5>
<ul>
<li><strong>Paper-based test:</strong> you write your answers on the question paper while you listen, then have <strong>10 minutes</strong> at the end to copy them onto the answer sheet.</li>
<li><strong>Computer-delivered test:</strong> you type your answers as you go, then have <strong>2 minutes</strong> at the end to check them. There is no transfer time, so your spelling must be right first time.</li>
</ul>

<h5>How it is marked</h5>
<p>Every question is worth one mark. Your raw score out of 40 becomes a band. The conversion below is the usual one; it can move by a mark or so from one test to another.</p>
<table><thead><tr><th>Raw score (out of 40)</th><th>Band</th><th>Raw score</th><th>Band</th></tr></thead><tbody>
<tr><td>39–40</td><td>9</td><td>23–25</td><td>6</td></tr>
<tr><td>37–38</td><td>8.5</td><td>18–22</td><td>5.5</td></tr>
<tr><td>35–36</td><td>8</td><td>16–17</td><td>5</td></tr>
<tr><td>32–34</td><td>7.5</td><td>13–15</td><td>4.5</td></tr>
<tr><td>30–31</td><td>7</td><td>10–12</td><td>4</td></tr>
<tr><td>26–29</td><td>6.5</td><td></td><td></td></tr>
</tbody></table>
<div class="lc-tip"><strong>What this means for you.</strong> Band 7 needs about 30 correct answers out of 40, so you can lose about one question in four. Every careless spelling mistake is a lost mark.</div>

<h5>The three rules that cost the most marks</h5>
<ol>
<li><strong>One hearing.</strong> If you miss an answer, leave it, move on to the next question and guess at the end. Do not let one question take the next two with it.</li>
<li><strong>Spelling and grammar count.</strong> A word spelt wrongly is wrong, even if you understood it. Names and addresses are often spelt out letter by letter for you; write them exactly.</li>
<li><strong>Word limits are strict.</strong> If the instruction says <em>NO MORE THAN TWO WORDS AND/OR A NUMBER</em>, three words is wrong even if the meaning is right. "The" and "a" count as words.</li>
</ol>

<div class="lc-take"><strong>Take away.</strong> Four parts, forty questions, one hearing. Read ahead, listen for what the question needs, write it exactly as you hear it, and keep moving.</div>
</div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_1Mo', 2, 1, ob_get_clean());
