<?php
// IELTS Reading: Overview — IELTS Academic — 1-Month Crash Course, Class 3, piece 1 (lesson).
// The video slot is named and placed but the video is not made yet (founder, 2026-10-28: rewriting and remaking the
// reading overview video; the old one, GTR_OVERVIEW, was General Training). It shows Coming Soon until
// assets/video/ielts_reading/reading_overview.mp4 exists.
require_once dirname(__DIR__, 3) . '/bootstrap.php';
require_once INCLUDES_PATH . '/lesson_shell.php';
require_once __DIR__ . '/listening_kit.php';

ob_start(); ?>
<!-- LESSON BODY START -->
<!-- DRAFT: written by Claude on 2026-10-28, for the instructor to review. -->
<?= listening_kit_assets() ?>
<div class="lc">
<div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul>
<li>describe the three passages of IELTS Academic Reading and how they are timed and marked</li>
<li>plan your 60 minutes so that the hardest passage still gets its time</li>
<li>skim a text for its main idea and scan it for a detail</li>
</ul></div>

<h5>Watch: the Reading test</h5>
<?= lesson_video('assets/video/ielts_reading/reading_overview.mp4', 'IELTS Academic Reading: Overview') ?>

<h5>The test at a glance</h5>
<p>IELTS Academic Reading has <strong>three passages</strong> and <strong>40 questions</strong>, and you have <strong>60 minutes</strong>. Every question is worth one mark. Reading is one of the four papers and counts for a quarter of your overall band.</p>
<ul>
<li><strong>The passages</strong> come from books, journals, magazines and newspapers, written for a general reader but at the level you meet at university. Together they are about 2,150 to 2,750 words long.</li>
<li><strong>They get harder.</strong> Passage 3 is usually the longest and most complex, and at least one passage presents a detailed argument. A passage may include a diagram, graph or illustration, and a short glossary if it uses technical words.</li>
<li><strong>Eleven question types</strong> test whether you can find the main idea, find details, follow an argument and recognise the writer's opinion. Each passage has two or three sets of questions of different types.</li>
</ul>

<h5>Time: there is no extra time</h5>
<ul>
<li><strong>Paper-based test:</strong> you may write on the question paper, but your answers must be on the answer sheet <strong>before the 60 minutes end</strong>. Unlike Listening, there are no extra 10 minutes to transfer them.</li>
<li><strong>Computer-delivered test:</strong> you type your answers straight in, and you can highlight words in the passage and make notes on screen.</li>
<li><strong>On both:</strong> spelling and grammar count. A word copied wrongly from the passage is a wrong answer.</li>
</ul>
<table><thead><tr><th>Passage</th><th>Plan</th><th>Why</th></tr></thead><tbody>
<tr><td>1</td><td>about 17 minutes</td><td>The most straightforward: save time here.</td></tr>
<tr><td>2</td><td>about 20 minutes</td><td></td></tr>
<tr><td>3</td><td>about 23 minutes</td><td>The longest and hardest. Most candidates run out of time here.</td></tr>
</tbody></table>
<div class="lc-tip"><strong>The 20-minute rule.</strong> If a question is taking too long, guess, mark it and move on. A wrong answer loses nothing, and a blank never scores.</div>

<h5>Answers in order, and the exceptions</h5>
<p>For most question types the answers come <strong>in the same order as the passage</strong>: the answer to Question 8 is after the answer to Question 7. The matching types are the exception: <em>matching headings</em>, <em>matching information to paragraphs</em> and <em>matching features</em> can be anywhere in the passage. Knowing this tells you where to look, and saves minutes.</p>

<h5>How it is marked</h5>
<p>Your raw score out of 40 becomes a band. The Academic table below is the usual one; it can move by a mark or so from one test to another. General Training uses a different table.</p>
<table><thead><tr><th>Raw score (out of 40)</th><th>Band</th><th>Raw score</th><th>Band</th></tr></thead><tbody>
<tr><td>39–40</td><td>9</td><td>23–26</td><td>6</td></tr>
<tr><td>37–38</td><td>8.5</td><td>19–22</td><td>5.5</td></tr>
<tr><td>35–36</td><td>8</td><td>15–18</td><td>5</td></tr>
<tr><td>33–34</td><td>7.5</td><td>13–14</td><td>4.5</td></tr>
<tr><td>30–32</td><td>7</td><td>10–12</td><td>4</td></tr>
<tr><td>27–29</td><td>6.5</td><td></td><td></td></tr>
</tbody></table>

<h5>Two ways to read: skimming and scanning</h5>
<p>You cannot read 2,500 words slowly and still answer 40 questions. Strong candidates switch between two kinds of fast reading:</p>
<ul>
<li><strong>Skimming</strong>: reading quickly for the <em>main idea</em>. Read the title, the first sentence of each paragraph and the last paragraph. Use it first, to get a map of the passage, and for headings questions.</li>
<li><strong>Scanning</strong>: looking for one <em>specific detail</em> (a name, a date, a number, a key term) without reading the rest. Use it to find where a question's answer is, then read that part carefully.</li>
</ul>

<div class="lx" data-lx>
  <div class="lx-head">Practice: skim, then scan</div>
  <div class="lx-body">
    <p><strong>Bees on the rooftops</strong></p>
    <p><strong>A</strong> Over the past two decades, beekeeping has moved from the countryside into cities. In London alone, the number of registered hives roughly doubled between 2008 and 2013, as offices, hotels and schools placed hives on their roofs.</p>
    <p><strong>B</strong> Supporters argue that city bees do well: parks, gardens and street trees offer a wide variety of flowers, and urban honey often wins competitions. Some researchers, however, warn that too many hives in one area may leave too little food for wild bees, which are already in decline.</p>
    <p><strong>C</strong> A 2020 study by the Royal Botanic Gardens, Kew, found that London's flowers could no longer support the number of honeybee colonies the city held. The authors recommended that new hives be matched by new planting rather than simply added.</p>
    <div class="lx-q"><strong>1. Skim (30 seconds): what is the text mainly about?</strong>
      <label class="lx-opt"><input type="radio" name="sk1"> How to start keeping bees on a roof</label>
      <label class="lx-opt"><input type="radio" name="sk1" data-correct> The growth of city beekeeping and a possible problem with it</label>
      <label class="lx-opt"><input type="radio" name="sk1"> Why urban honey wins competitions</label></div>
    <div class="lx-q"><strong>2. Scan: which organisation carried out the 2020 study?</strong>
      <input type="text" data-answer="royal botanic gardens kew|royal botanic gardens|kew|the royal botanic gardens kew" autocomplete="off"></div>
    <div class="lx-q"><strong>3. Scan: between which years did London's registered hives roughly double?</strong>
      <input type="text" data-answer="2008 and 2013|2008 2013|20082013|2008 to 2013|2008-2013" autocomplete="off"></div>
    <div class="lx-actions"><button type="button" class="lx-btn" data-lx-check>Check answers</button><button type="button" class="lx-btn secondary" data-lx-reset>Try again</button><span class="lx-score"></span></div>
    <div class="lx-why" hidden>
      <ol>
        <li>The first sentences of A, B and C give the map: beekeeping moved into cities, supporters say it works, a study found a limit. "Urban honey wins competitions" is one detail in B, not the main idea.</li>
        <li><strong>The Royal Botanic Gardens, Kew.</strong> Scan for "2020" or "study": a number or a capital letter is quick to spot.</li>
        <li><strong>2008 and 2013</strong>, in paragraph A. Scan for numbers; you do not need to read the rest.</li>
      </ol>
    </div>
  </div>
</div>

<div class="lc-take"><strong>Take away.</strong> Three passages, forty questions, sixty minutes, and no extra time at the end. Skim for the map, scan for the detail, read closely only where the answer is, and leave Passage 3 its time.</div>
</div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_1Mo', 3, 1, ob_get_clean());
