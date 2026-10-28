<?php
// Form Completion & Gap-fill — IELTS Academic — 1-Month Crash Course, Class 2, piece 3 (lesson with exercises).
// From the founder's SL1 deck ("Form Completion & Multiple Choice") and its recording (Jason's leisure-centre
// membership), expanded with a predict-the-gap drill. Answers checked against the recording's transcript.
require_once dirname(__DIR__, 3) . '/bootstrap.php';
require_once INCLUDES_PATH . '/lesson_shell.php';
require_once __DIR__ . '/listening_kit.php';
$audio = ACADEMY_URL . 'assets/audio/ielts_listening_class2/form_completion_jason.mp3';

ob_start(); ?>
<!-- LESSON BODY START -->
<!-- DRAFT: written by Claude on 2026-10-28 from the founder's SL1 deck and recording, for the instructor to review. -->
<?= listening_kit_assets() ?>
<div class="lc">
<div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul>
<li>use the reading time to predict what each gap in a form needs</li>
<li>catch names spelt letter by letter, numbers, postcodes and email addresses</li>
<li>spot when a speaker corrects themselves, and write the final answer</li>
</ul></div>

<h5>How to complete a form</h5>
<p>Completing a form is a gap-fill exercise, and it usually comes in <strong>Part 1</strong>: a conversation in which someone gives their details. Two steps before the recording starts:</p>
<ol>
<li><strong>Highlight the keywords</strong> on the form: the labels such as <em>name</em>, <em>surname</em>, <em>address</em>, <em>postcode</em>. They tell you what is coming.</li>
<li><strong>Predict what goes in each gap:</strong> a name, a number, a date, a word. If you know you are listening for a postcode, you will hear it.</li>
</ol>

<div class="lx" data-lx>
  <div class="lx-head">Practice 1: predict the gap (no audio)</div>
  <div class="lx-body">
    <p class="mb-3">For each gap, choose what kind of answer you expect to hear.</p>
    <p><strong>Jason's membership form</strong></p>
    <div class="lx-q"><strong>Last name: ______</strong>
      <label class="lx-opt"><input type="radio" name="pg1" data-correct> A surname, probably spelt out letter by letter</label>
      <label class="lx-opt"><input type="radio" name="pg1"> A number</label>
      <label class="lx-opt"><input type="radio" name="pg1"> A place</label></div>
    <div class="lx-q"><strong>Address: ______ Crows Avenue, Swindon</strong>
      <label class="lx-opt"><input type="radio" name="pg2"> A street name</label>
      <label class="lx-opt"><input type="radio" name="pg2" data-correct> A house number</label>
      <label class="lx-opt"><input type="radio" name="pg2"> A town</label></div>
    <div class="lx-q"><strong>Email: ______@gmail.com</strong>
      <label class="lx-opt"><input type="radio" name="pg3"> A whole email address</label>
      <label class="lx-opt"><input type="radio" name="pg3" data-correct> Only the part before the @</label>
      <label class="lx-opt"><input type="radio" name="pg3"> A phone number</label></div>
    <p class="mt-3"><strong>A library card application</strong></p>
    <div class="lx-q"><strong>Date of birth: ______</strong>
      <label class="lx-opt"><input type="radio" name="pg4" data-correct> A date: a day and a month, perhaps a year</label>
      <label class="lx-opt"><input type="radio" name="pg4"> An age</label>
      <label class="lx-opt"><input type="radio" name="pg4"> A day of the week</label></div>
    <div class="lx-q"><strong>Occupation: ______</strong>
      <label class="lx-opt"><input type="radio" name="pg5"> A company name</label>
      <label class="lx-opt"><input type="radio" name="pg5" data-correct> A job</label>
      <label class="lx-opt"><input type="radio" name="pg5"> A number of years</label></div>
    <div class="lx-q"><strong>Annual fee: £______</strong>
      <label class="lx-opt"><input type="radio" name="pg6" data-correct> An amount of money (the £ is already there)</label>
      <label class="lx-opt"><input type="radio" name="pg6"> A date</label>
      <label class="lx-opt"><input type="radio" name="pg6"> A word such as "free"</label></div>
    <p class="mt-3"><strong>A hotel booking</strong></p>
    <div class="lx-q"><strong>Number of nights: ______</strong>
      <label class="lx-opt"><input type="radio" name="pg7" data-correct> A number</label>
      <label class="lx-opt"><input type="radio" name="pg7"> A date</label>
      <label class="lx-opt"><input type="radio" name="pg7"> A room type</label></div>
    <div class="lx-q"><strong>Arrival time: ______</strong>
      <label class="lx-opt"><input type="radio" name="pg8"> A day</label>
      <label class="lx-opt"><input type="radio" name="pg8" data-correct> A time, such as 6.30 pm</label>
      <label class="lx-opt"><input type="radio" name="pg8"> A place</label></div>
    <div class="lx-actions"><button type="button" class="lx-btn" data-lx-check>Check answers</button><button type="button" class="lx-btn secondary" data-lx-reset>Try again</button><span class="lx-score"></span></div>
    <div class="lx-why" hidden>The label and the words around the gap tell you almost everything: "@gmail.com" is already printed, so you only need the part before it; "£" is printed, so you only need the number.</div>
  </div>
</div>

<h5>Form or gap-fill: the same information, two shapes</h5>
<p>The same fact can be tested as a row in a form or as a gap in a sentence:</p>
<table><tbody>
<tr><th style="width:4rem;">A</th><td><strong>Jason's Personal Information</strong> — Mobile: 1. ______</td></tr>
<tr><th>B</th><td>1. Jason's mobile phone number is ______.</td></tr>
</tbody></table>
<p>Both want the same number. In a sentence (B), also check that your answer fits the grammar: the finished sentence must be correct English.</p>

<h5>What to listen for</h5>
<ul>
<li><strong>Names are spelt out.</strong> Write each letter as you hear it. Letters that are easy to confuse: B and V, M and N, G and J, A and R, E and I. Speakers often clarify: "B for banana".</li>
<li><strong>Numbers come in groups.</strong> Phone numbers are read in twos or threes; "oh" means zero; "double four" means 44.</li>
<li><strong>Speakers correct themselves.</strong> "Is that V for Victor?" "No, sorry, B for banana." The <em>final</em> version is the answer.</li>
<li><strong>Email addresses:</strong> "at" is @ and "dot" is a full stop. Listen for the spelling of the name part.</li>
</ul>

<div class="lx" data-lx>
  <div class="lx-head">Practice 2: Jason's membership (audio)</div>
  <div class="lx-body">
    <p>Jason is phoning a leisure centre to become a member. Look at the form and predict, then press play and complete it. In the test you hear the recording <strong>once</strong>; try that first, then replay it to check.</p>
    <audio controls preload="metadata" src="<?= $audio ?>"></audio>
    <div class="lx-form">
      <label>First name</label><span>Jason</span>
      <label for="jf1">Last name</label><span>1. <input type="text" id="jf1" data-answer="Haidt" autocomplete="off"></span>
      <label for="jf2">Address</label><span>2. <input type="text" id="jf2" data-answer="47" autocomplete="off" style="min-width:5rem;width:5rem;"> Crows Avenue, Swindon</span>
      <label for="jf3">Postcode</label><span>3. <input type="text" id="jf3" data-answer="SW13 9LB" autocomplete="off"></span>
      <label>Home phone</label><span>N/A</span>
      <label for="jf4">Mobile</label><span>4. <input type="text" id="jf4" data-answer="07676 254 143" autocomplete="off"></span>
      <label for="jf5">Email</label><span>5. <input type="text" id="jf5" data-answer="jhaidt|jhaidt@gmail.com" autocomplete="off"> @gmail.com</span>
      <label for="jf6">Prefers contact by</label><span>6. <input type="text" id="jf6" data-answer="email|e-mail" autocomplete="off"></span>
    </div>
    <div class="lx-actions"><button type="button" class="lx-btn" data-lx-check>Check answers</button><button type="button" class="lx-btn secondary" data-lx-reset>Try again</button><span class="lx-score"></span></div>
    <div class="lx-why" hidden>
      <p><strong>Where the answers are</strong></p>
      <ol>
        <li><strong>Haidt.</strong> He corrects the receptionist's spelling: "sorry, it's a strange spelling, it's actually H-A-I-D-T."</li>
        <li><strong>47.</strong> "Could I take your house number and postcode?" "Yeah, sure, it's 47..."</li>
        <li><strong>SW13 9LB.</strong> "Is that V for Victor?" "No, sorry, B for banana." The correction is the answer.</li>
        <li><strong>07676 254 143.</strong> When the receptionist reads it back as "...254 413", Jason corrects her: the last three numbers are 1-4-3.</li>
        <li><strong>jhaidt.</strong> "It's J and then my surname, Haidt, at gmail.com." The surname is spelt as he gave it earlier.</li>
        <li><strong>Email.</strong> "I think phone. No, actually, email is probably better." He changes his mind: the final answer counts.</li>
      </ol>
      <p class="mb-0">Notice that three of the six answers came after a correction. IELTS Part 1 does this on purpose.</p>
    </div>
  </div>
</div>

<div class="lc-take"><strong>Take away.</strong> Predict every gap before you listen, write names and numbers exactly, and never write the first thing you hear when the speaker is about to correct it.</div>
</div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_1Mo', 2, 3, ob_get_clean());
