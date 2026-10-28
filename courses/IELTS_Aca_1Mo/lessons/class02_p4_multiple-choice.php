<?php
// Multiple Choice — IELTS Academic — 1-Month Crash Course, Class 2, piece 4 (lesson with exercises).
// From the founder's SL5 deck ("Listening: Multiple Choice") and its recording (the Lancer International Cooking
// School). The deck had no answer key: answers worked out from the recording's transcript (2026-10-28).
require_once dirname(__DIR__, 3) . '/bootstrap.php';
require_once INCLUDES_PATH . '/lesson_shell.php';
require_once __DIR__ . '/listening_kit.php';
$audio = ACADEMY_URL . 'assets/audio/ielts_listening_class2/multiple_choice_lancer.mp3';

ob_start(); ?>
<!-- LESSON BODY START -->
<!-- DRAFT: written by Claude on 2026-10-28 from the founder's SL5 deck and recording, for the instructor to review. -->
<?= listening_kit_assets() ?>
<div class="lc">
<div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul>
<li>underline the words in a multiple-choice question that really matter</li>
<li>recognise an option when the speaker says it in different words</li>
<li>tell the right option from the ones that are mentioned only to mislead you</li>
</ul></div>

<h5>How multiple choice works in IELTS Listening</h5>
<p>Multiple choice is very common in <strong>Part 2</strong>. You choose one answer from three options, A–C. The question may be half a sentence that the options complete, or a simple question with three possible answers.</p>
<ul>
<li><strong>You will hear something about every option,</strong> but only one answers the question. Listen to be sure the option you choose really is the answer.</li>
<li><strong>The questions paraphrase the recording.</strong> You may not hear the words you read: the speaker uses different vocabulary, synonyms and different grammar.</li>
<li><strong>The questions follow the order of the recording.</strong> You hear the answer to Question 1 before the answer to Question 2.</li>
</ul>

<p>The four questions this lesson builds up to:</p>
<table><tbody>
<tr><td><strong>1.</strong> John Lancer's idea for a cookery school came from<br>A his interest in foreign cuisine. &nbsp; B his mother's cooking. &nbsp; C his visit to another school.</td></tr>
<tr><td><strong>2.</strong> How many Lancer schools are there currently?<br>A 30 &nbsp; B 100 &nbsp; C 150</td></tr>
<tr><td><strong>3.</strong> The school is mainly funded by<br>A visitors. &nbsp; B restaurants. &nbsp; C the city council.</td></tr>
<tr><td><strong>4.</strong> For the weekend market tour<br>A only group bookings are accepted. &nbsp; B guests should book in advance. &nbsp; C attendance is free.</td></tr>
</tbody></table>

<div class="lx" data-lx>
  <div class="lx-head">Step 1: underline the keywords</div>
  <div class="lx-body">
    <p>Two students underlined the keywords of Question 1. Which underlining is better?</p>
    <table><tbody>
      <tr><th style="width:7rem;">Student A</th><td>1. <u class="key">John Lancer's</u> <u class="key">idea</u> for a cookery school <u class="key">came from</u><br>A his <u class="key">interest</u> in <u class="key">foreign cuisine</u>. B his <u class="key">mother's cooking</u>. C his <u class="key">visit</u> to <u class="key">another school</u>.</td></tr>
      <tr><th>Student B</th><td>1. John Lancer's <u class="key">idea for a cookery school</u> came from<br>A <u class="key">his interest</u> in foreign cuisine. B <u class="key">his mother</u>'s cooking. C his visit to <u class="key">another school</u>.</td></tr>
    </tbody></table>
    <div class="lx-q"><strong>Which is better?</strong>
      <label class="lx-opt"><input type="radio" name="ul" data-correct> Student A</label>
      <label class="lx-opt"><input type="radio" name="ul"> Student B</label></div>
    <div class="lx-actions"><button type="button" class="lx-btn" data-lx-check>Check</button><button type="button" class="lx-btn secondary" data-lx-reset>Try again</button></div>
    <div class="lx-why" hidden>Student A underlines the words that carry the meaning: who (John Lancer), what you are listening for (where the idea <em>came from</em>), and what makes each option different (<em>foreign cuisine</em>, <em>mother's cooking</em>, <em>visit</em>, <em>another school</em>). Student B underlines "his" and misses the words that separate the options, such as "foreign cuisine" and "visit".</div>
  </div>
</div>

<div class="lx" data-lx>
  <div class="lx-head">Step 2: how else could it be said?</div>
  <div class="lx-body">
    <div class="lx-q"><strong>Which TWO sentences paraphrase option A, "his interest in foreign cuisine"?</strong>
      <label class="lx-opt"><input type="checkbox"> He owned many restaurants.</label>
      <label class="lx-opt"><input type="checkbox"> He cooked a lot for his friends and family.</label>
      <label class="lx-opt"><input type="checkbox" data-correct> International food was his true passion.</label>
      <label class="lx-opt"><input type="checkbox"> He would go to the market every day to find fresh ingredients.</label>
      <label class="lx-opt"><input type="checkbox" data-correct> He was very keen on Spanish, Italian and French cooking.</label></div>
    <div class="lx-actions"><button type="button" class="lx-btn" data-lx-check>Check</button><button type="button" class="lx-btn secondary" data-lx-reset>Try again</button></div>
    <div class="lx-why" hidden><em>Interest</em> = passion, keen on. <em>Foreign cuisine</em> = international food, Spanish, Italian and French cooking. The other three are about cooking, but not about foreign food. In the recording you will hear: "Lancer has always been interested in <strong>international food</strong>."</div>
  </div>
</div>

<div class="lx" data-lx>
  <div class="lx-head">Step 3: listen to the first part (audio)</div>
  <div class="lx-body">
    <p>Listen to the start of the recording, up to "And so Lancer Cooking School was born." Which THREE things does the speaker talk about? Remember: you will hear about every option of Question 1, and the speaker may paraphrase.</p>
    <audio controls preload="metadata" src="<?= $audio ?>"></audio>
    <div class="lx-q"><strong>Choose THREE.</strong>
      <label class="lx-opt"><input type="checkbox" data-correct> His mother</label>
      <label class="lx-opt"><input type="checkbox"> His health problems</label>
      <label class="lx-opt"><input type="checkbox"> His children</label>
      <label class="lx-opt"><input type="checkbox" data-correct> Foreign cuisine</label>
      <label class="lx-opt"><input type="checkbox"> His favourite vegetables</label>
      <label class="lx-opt"><input type="checkbox" data-correct> His visit to a cooking school</label></div>
    <div class="lx-actions"><button type="button" class="lx-btn" data-lx-check>Check</button><button type="button" class="lx-btn secondary" data-lx-reset>Try again</button></div>
    <div class="lx-why" hidden>"Lancer has always been interested in <strong>international food</strong> and started cooking from a very young age <strong>with his mother</strong>. However, it was a <strong>trip to cooking school in France</strong> that inspired him..." All three options of Question 1 are mentioned. That is normal in IELTS: the next step is deciding which one actually answers the question.</div>
  </div>
</div>

<div class="lx" data-lx>
  <div class="lx-head">Step 4: listen again and answer Question 1</div>
  <div class="lx-body">
    <p>Play the first part again. This time listen for what the question asks: where the <em>idea</em> for the school <em>came from</em>.</p>
    <audio controls preload="metadata" src="<?= $audio ?>"></audio>
    <div class="lx-q"><strong>1. John Lancer's idea for a cookery school came from</strong>
      <label class="lx-opt"><input type="radio" name="q1a"> A his interest in foreign cuisine.</label>
      <label class="lx-opt"><input type="radio" name="q1a"> B his mother's cooking.</label>
      <label class="lx-opt"><input type="radio" name="q1a" data-correct> C his visit to another school.</label></div>
    <div class="lx-actions"><button type="button" class="lx-btn" data-lx-check>Check</button><button type="button" class="lx-btn secondary" data-lx-reset>Try again</button></div>
    <div class="lx-why" hidden><strong>C.</strong> "<strong>However</strong>, it was a trip to cooking school in France that <strong>inspired him</strong> to set up his own one." "Inspired him to set up" is the paraphrase of "his idea came from". His interest in international food and his mother are true, but they are not where the idea for the school came from. Watch for <em>however</em>, <em>but</em> and <em>actually</em>: the answer often follows them.</div>
  </div>
</div>

<div class="lx" data-lx>
  <div class="lx-head">Step 5: the whole recording, Questions 1–4</div>
  <div class="lx-body">
    <p>Now do it as in the test: read all four questions, then play the whole recording once and answer as you listen.</p>
    <audio controls preload="metadata" src="<?= $audio ?>"></audio>
    <div class="lx-q"><strong>1. John Lancer's idea for a cookery school came from</strong>
      <label class="lx-opt"><input type="radio" name="f1"> A his interest in foreign cuisine.</label>
      <label class="lx-opt"><input type="radio" name="f1"> B his mother's cooking.</label>
      <label class="lx-opt"><input type="radio" name="f1" data-correct> C his visit to another school.</label></div>
    <div class="lx-q"><strong>2. How many Lancer schools are there currently?</strong>
      <label class="lx-opt"><input type="radio" name="f2"> A 30</label>
      <label class="lx-opt"><input type="radio" name="f2"> B 100</label>
      <label class="lx-opt"><input type="radio" name="f2" data-correct> C 150</label></div>
    <div class="lx-q"><strong>3. The school is mainly funded by</strong>
      <label class="lx-opt"><input type="radio" name="f3" data-correct> A visitors.</label>
      <label class="lx-opt"><input type="radio" name="f3"> B restaurants.</label>
      <label class="lx-opt"><input type="radio" name="f3"> C the city council.</label></div>
    <div class="lx-q"><strong>4. For the weekend market tour</strong>
      <label class="lx-opt"><input type="radio" name="f4"> A only group bookings are accepted.</label>
      <label class="lx-opt"><input type="radio" name="f4" data-correct> B guests should book in advance.</label>
      <label class="lx-opt"><input type="radio" name="f4"> C attendance is free.</label></div>
    <div class="lx-actions"><button type="button" class="lx-btn" data-lx-check>Check answers</button><button type="button" class="lx-btn secondary" data-lx-reset>Try again</button><span class="lx-score"></span></div>
    <div class="lx-why" hidden>
      <ol>
        <li><strong>C.</strong> The trip to the cooking school in France inspired him (see Step 4).</li>
        <li><strong>C, 150.</strong> "The first school was in London, but that one school soon became <strong>30</strong> across Europe... we're on track... with <strong>150</strong> schools operating across the globe <strong>at the moment</strong>." 30 is the past; the question says <em>currently</em> = at the moment.</li>
        <li><strong>A, visitors.</strong> The money comes from the city council, local restaurants and students' donations, but "now <strong>visitor donations account for over 70%</strong> of all total funds". <em>Mainly</em> = over 70%. The council and restaurants are mentioned, but they are not the main source.</li>
        <li><strong>B.</strong> "You'll need to <strong>phone us a few days ahead</strong> to get a place" = book in advance. They keep tours to <em>small groups</em> (not "only group bookings"), and "there is a small charge" (so it is not free; it is the classes that are free).</li>
      </ol>
    </div>
  </div>
</div>

<div class="lc-take"><strong>Take away.</strong> Underline the words that make each option different, think of other ways to say them, and expect to hear all three options: choose the one that answers the question, often after <em>however</em> or <em>but</em>.</div>
</div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_1Mo', 2, 4, ob_get_clean());
