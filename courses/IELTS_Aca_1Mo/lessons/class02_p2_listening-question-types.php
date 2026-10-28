<?php
// Listening Question Types — IELTS Academic — 1-Month Crash Course, Class 2, piece 2 (lesson).
// The LISTENING_QUESTION_TYPES video (founder's, 2026-10-28) and a guide to each question type.
require_once dirname(__DIR__, 3) . '/bootstrap.php';
require_once INCLUDES_PATH . '/lesson_shell.php';
require_once __DIR__ . '/listening_kit.php';

ob_start(); ?>
<!-- LESSON BODY START -->
<?= listening_kit_assets() ?>
<div class="lc">
<div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul>
<li>recognise each IELTS Listening question type from its instructions</li>
<li>say what each type asks you to listen for</li>
<li>choose the right first step for each type before the recording starts</li>
</ul></div>

<h5>Watch: the question types</h5>
<video controls preload="metadata" src="<?= ACADEMY_URL ?>assets/video/ielts_listening/listening_question_types.mp4"></video>

<h5>The question types, and what each one needs</h5>
<table><thead><tr><th>Type</th><th>What you do</th><th>Before you listen</th></tr></thead><tbody>
<tr><td><strong>Form, note, table, flow-chart or summary completion</strong></td><td>Fill gaps with words or numbers from the recording</td><td>Read the word limit. Predict the kind of answer each gap needs: a name, a number, a date, a place, a noun, a verb.</td></tr>
<tr><td><strong>Sentence completion</strong></td><td>Finish sentences with words from the recording</td><td>Read each sentence and decide what grammar the gap needs. The finished sentence must be correct English.</td></tr>
<tr><td><strong>Short-answer questions</strong></td><td>Answer questions in a few words</td><td>Underline the question word (who, where, how many) and the word limit.</td></tr>
<tr><td><strong>Multiple choice</strong></td><td>Choose one answer from A–C, or two or three from a longer list</td><td>Underline the keywords in the question and in every option; think of other ways each option could be said.</td></tr>
<tr><td><strong>Matching</strong></td><td>Match a list of items to a set of options (for example, people to opinions)</td><td>Read the options first; they usually come up in a different order from the items.</td></tr>
<tr><td><strong>Plan, map or diagram labelling</strong></td><td>Label places or parts on a picture</td><td>Find where you start, and note words for direction and position: opposite, next to, on the left, at the end of.</td></tr>
</tbody></table>

<h5>Two things true of every type</h5>
<ul>
<li><strong>The answers come in order.</strong> The answer to Question 4 comes after the answer to Question 3. If you hear the answer to Question 5, you have missed Question 4: move on.</li>
<li><strong>The questions paraphrase the recording.</strong> The words on the page are rarely the words you hear. "Free of charge" in the recording may be "costs nothing" on the page. Listen for the meaning, not the exact words.</li>
</ul>

<div class="lx" data-lx>
  <div class="lx-head">Practice: which type is it?</div>
  <div class="lx-body">
    <div class="lx-q"><strong>1. "Complete the form below. Write ONE WORD AND/OR A NUMBER for each answer."</strong>
      <label class="lx-opt"><input type="radio" name="qt1" data-correct> Form completion</label>
      <label class="lx-opt"><input type="radio" name="qt1"> Short-answer questions</label>
      <label class="lx-opt"><input type="radio" name="qt1"> Matching</label></div>
    <div class="lx-q"><strong>2. "Choose TWO letters, A–E. Which TWO facilities will be closed next week?"</strong>
      <label class="lx-opt"><input type="radio" name="qt2"> Matching</label>
      <label class="lx-opt"><input type="radio" name="qt2" data-correct> Multiple choice</label>
      <label class="lx-opt"><input type="radio" name="qt2"> Sentence completion</label></div>
    <div class="lx-q"><strong>3. "Label the plan below. Write the correct letter, A–H, next to Questions 15–20."</strong>
      <label class="lx-opt"><input type="radio" name="qt3"> Table completion</label>
      <label class="lx-opt"><input type="radio" name="qt3"> Multiple choice</label>
      <label class="lx-opt"><input type="radio" name="qt3" data-correct> Plan or map labelling</label></div>
    <div class="lx-q"><strong>4. "What does each speaker think about the new timetable? Choose your answers from the box."</strong>
      <label class="lx-opt"><input type="radio" name="qt4" data-correct> Matching</label>
      <label class="lx-opt"><input type="radio" name="qt4"> Form completion</label>
      <label class="lx-opt"><input type="radio" name="qt4"> Short-answer questions</label></div>
    <div class="lx-actions"><button type="button" class="lx-btn" data-lx-check>Check answers</button><button type="button" class="lx-btn secondary" data-lx-reset>Try again</button><span class="lx-score"></span></div>
    <div class="lx-why" hidden>The instructions always tell you the type: "complete" means a gap-fill, "choose letters" means multiple choice, "label" means a plan or map, and "choose from the box" for each person means matching.</div>
  </div>
</div>

<div class="lc-take"><strong>Take away.</strong> Read the instruction first: it tells you the type, the word limit and what to listen for. Then use the reading time to predict.</div>
</div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_1Mo', 2, 2, ob_get_clean());
