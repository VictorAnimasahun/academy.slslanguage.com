<?php
// Completion: Notes, Summary, Table & Diagram — IELTS Academic — 1-Month Crash Course, Class 3, piece 3 (lesson).
// Exercises: the official IELTS Academic Reading sample tasks "Table completion" (Questions 9-13) and "Diagram label
// completion" (Questions 6-8), which share one passage (dung beetles), with their answer keys; and a summary-from-a-list
// task written on the same passage (the form of Reading Test 1's Questions 27-31).
require_once dirname(__DIR__, 3) . '/bootstrap.php';
require_once INCLUDES_PATH . '/lesson_shell.php';
require_once __DIR__ . '/listening_kit.php';

$beetleTypes = ['French', 'Mediterranean', 'Australian native', 'Spanish', 'South African', 'South African ball roller'];
$pick = function (string $answer, array $choices): string {
    $out = '<select data-answer="' . htmlspecialchars($answer) . '"><option value="">–</option>';
    foreach ($choices as $c) $out .= '<option value="' . htmlspecialchars($c) . '">' . htmlspecialchars($c) . '</option>';
    return $out . '</select>';
};

ob_start(); ?>
<!-- LESSON BODY START -->
<!-- DRAFT: written by Claude on 2026-10-28 from the official IELTS Academic Reading sample tasks, for the instructor to review. -->
<?= listening_kit_assets() ?>
<div class="lc">
<div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul>
<li>complete notes, sentences, summaries, tables and diagrams with words from the passage</li>
<li>keep to the word limit and copy words exactly</li>
<li>choose from a list of phrases when the summary paraphrases the passage</li>
</ul></div>

<h5>One skill, many shapes</h5>
<p>Completion tasks give you a text with gaps (notes, sentences, a summary, a table, a flow-chart or a labelled diagram) and ask you to fill them. There are two kinds:</p>
<ul>
<li><strong>Words from the passage.</strong> The instruction gives a word limit: <em>ONE WORD ONLY</em>, <em>NO MORE THAN TWO WORDS</em>, <em>NO MORE THAN THREE WORDS AND/OR A NUMBER</em>. Reading Test 1's Questions 1–6 and 18–22 are this kind (one word only).</li>
<li><strong>Choose from a list.</strong> A box of words or phrases, usually more than you need, labelled A, B, C... You write the letter. The phrases often <em>paraphrase</em> the passage. Reading Test 1's Questions 27–31 are this kind.</li>
</ul>

<h5>The rules that cost marks</h5>
<ol>
<li><strong>Word limit.</strong> "No more than three words" means one, two or three: four is wrong, however good the meaning. A number written in figures (25) is counted as a number, not a word.</li>
<li><strong>Copy exactly.</strong> Use the words in the passage, spelt as they are there. Don't change their form (the passage's <em>temperate</em> is not <em>temperature</em>).</li>
<li><strong>Grammar must fit.</strong> The completed sentence has to be correct: if the gap follows <em>a</em>, you need a singular noun; after <em>to</em>, often a verb.</li>
<li><strong>Usually in order.</strong> Notes, sentence and summary answers normally follow the passage. Tables, flow-charts and diagrams may jump around, but they usually come from one part of the passage.</li>
</ol>

<h5>The method</h5>
<ol>
<li>Read the instruction: the word limit, and whether you write words or letters.</li>
<li>For each gap, <strong>predict</strong> what kind of word is missing (a noun? a number? an adjective?) and what it might mean.</li>
<li><strong>Scan</strong> for the keywords around the gap, or their paraphrases, to find the place in the passage.</li>
<li>Read that sentence closely, take the words that fit, and check the limit and the spelling.</li>
</ol>

<div class="lx" data-lx>
  <div class="lx-head">Practice 1: official sample, table completion</div>
  <div class="lx-body">
    <p>This is an official IELTS Academic Reading sample task. The passage is an extract about dung beetles introduced into Australian pastures. <em>Glossary: dung = the droppings of animals; cow pats = droppings of cows.</em></p>
    <div class="lx-passage">
      <p>Introducing dung beetles into a pasture is a simple process: approximately 1,500 beetles are released, a handful at a time, into fresh cow pats in the cow pasture. The beetles immediately disappear beneath the pats digging and tunnelling and, if they successfully adapt to their new environment, soon become a permanent, self-sustaining part of the local ecology. In time they multiply and within three or four years the benefits to the pasture are obvious.</p>
      <p>Dung beetles work from the inside of the pat so they are sheltered from predators such as birds and foxes. Most species burrow into the soil and bury dung in tunnels directly underneath the pats, which are hollowed out from within. Some large species originating from France excavate tunnels to a depth of approximately 30 cm below the dung pat. These beetles make sausage-shaped brood chambers along the tunnels. The shallowest tunnels belong to a much smaller Spanish species that buries dung in chambers that hang like fruit from the branches of a pear tree. South African beetles dig narrow tunnels of approximately 20 cm below the surface of the pat. Some surface-dwelling beetles, including a South African species, cut perfectly-shaped balls from the pat, which are rolled away and attached to the bases of plants.</p>
      <p>For maximum dung burial in spring, summer and autumn, farmers require a variety of species with overlapping periods of activity. In the cooler environments of the state of Victoria, the large French species (2.5 cms long), is matched with smaller (half this size), temperate-climate Spanish species. The former are slow to recover from the winter cold and produce only one or two generations of offspring from late spring until autumn. The latter, which multiply rapidly in early spring, produce two to five generations annually. The South African ball-rolling species, being a sub-tropical beetle, prefers the climate of northern and coastal New South Wales where it commonly works with the South African tunneling species. In warmer climates, many species are active for longer periods of the year.</p>
    </div>
    <p><em>Complete the table. Choose NO MORE THAN THREE WORDS from the passage for each answer.</em></p>
    <table><thead><tr><th>Species</th><th>Size</th><th>Preferred climate</th><th>Complementary species</th><th>Start of active period</th><th>Generations per year</th></tr></thead><tbody>
      <tr><td>French</td><td>2.5 cm</td><td>cool</td><td>Spanish</td><td>late spring</td><td>1–2</td></tr>
      <tr><td>Spanish</td><td>1.25 cm</td><td><strong>9</strong> <input type="text" data-answer="temperate" autocomplete="off"></td><td></td><td><strong>10</strong> <input type="text" data-answer="early spring" autocomplete="off"></td><td><strong>11</strong> <input type="text" data-answer="two to five|2-5|2 to 5" autocomplete="off"></td></tr>
      <tr><td>South African ball roller</td><td></td><td><strong>12</strong> <input type="text" data-answer="sub-tropical|subtropical" autocomplete="off"></td><td><strong>13</strong> <input type="text" data-answer="South African tunneling|South African tunnelling" autocomplete="off"></td><td></td><td></td></tr>
    </tbody></table>
    <div class="lx-actions"><button type="button" class="lx-btn" data-lx-check>Check answers</button><button type="button" class="lx-btn secondary" data-lx-reset>Try again</button><span class="lx-score"></span></div>
    <div class="lx-why" hidden>
      <p>Official answers: 9 temperate, 10 early spring, 11 two to five / 2-5, 12 sub-tropical, 13 South African tunneling.</p>
      <ul>
        <li><strong>9.</strong> "temperate-climate Spanish species": the table's heading <em>Preferred climate</em> tells you to look for a climate word. Write <em>temperate</em>, not <em>temperate-climate</em> or <em>temperature</em>.</li>
        <li><strong>10–11.</strong> "The latter, which multiply rapidly in early spring, produce two to five generations annually": <em>the latter</em> = the Spanish species, <em>annually</em> = per year. Both answers are in one sentence; that is common in tables.</li>
        <li><strong>12–13.</strong> "The South African ball-rolling species, being a sub-tropical beetle... commonly works with the South African tunneling species": <em>works with</em> = complementary. "South African tunneling" is exactly three words.</li>
      </ul>
    </div>
  </div>
</div>

<div class="lx" data-lx>
  <div class="lx-head">Practice 2: official sample, diagram labelling</div>
  <div class="lx-body">
    <p>Same passage. <em>Label the tunnels on the diagram using words from the box.</em></p>
    <div class="lx-box"><strong>Dung beetle types:</strong> French · Mediterranean · Australian native · Spanish · South African · South African ball roller</div>
    <svg viewBox="0 0 520 260" role="img" aria-label="Diagram: tunnels below a cow pat at about 5, 20 and 30 cm deep" style="width:100%;max-width:520px;display:block;margin:.5rem 0 1rem;font:13px system-ui,sans-serif">
      <rect x="0" y="0" width="520" height="260" fill="none"/>
      <text x="8" y="14" fill="currentColor">depth (cm)</text>
      <g fill="currentColor"><text x="22" y="44">0</text><text x="16" y="94">10</text><text x="16" y="144">20</text><text x="16" y="194">30</text></g>
      <line x1="44" y1="40" x2="510" y2="40" stroke="currentColor" stroke-width="2"/>
      <ellipse cx="300" cy="34" rx="70" ry="10" fill="#8b6b4a"/><text x="380" y="26" fill="currentColor">cow pat (dung)</text>
      <path d="M300 44 C 302 52, 296 58, 300 64" stroke="#8b6b4a" stroke-width="5" fill="none"/>
      <circle cx="292" cy="60" r="5" fill="#8b6b4a"/><circle cx="308" cy="62" r="5" fill="#8b6b4a"/>
      <text x="330" y="66" fill="currentColor" font-weight="700">8</text>
      <path d="M270 44 L 270 140" stroke="#8b6b4a" stroke-width="4" fill="none"/>
      <text x="200" y="130" fill="currentColor" font-weight="700">6</text>
      <path d="M240 44 L 240 190 L 200 205" stroke="#8b6b4a" stroke-width="7" fill="none"/>
      <ellipse cx="220" cy="190" rx="14" ry="6" fill="#8b6b4a"/>
      <text x="150" y="200" fill="currentColor" font-weight="700">7</text>
    </svg>
    <div class="lx-match"><strong>6</strong><span>tunnel about 20 cm below the surface</span><?= $pick('South African', $beetleTypes) ?></div>
    <div class="lx-match"><strong>7</strong><span>tunnel about 30 cm below the surface</span><?= $pick('French', $beetleTypes) ?></div>
    <div class="lx-match"><strong>8</strong><span>chambers just below the pat</span><?= $pick('Spanish', $beetleTypes) ?></div>
    <div class="lx-actions"><button type="button" class="lx-btn" data-lx-check>Check answers</button><button type="button" class="lx-btn secondary" data-lx-reset>Try again</button><span class="lx-score"></span></div>
    <div class="lx-why" hidden>
      <p>Official answers: 6 South African, 7 French, 8 Spanish.</p>
      <p>Diagram answers are usually in one paragraph (here, the second). Match each shape and depth to its description: French ~30 cm, South African ~20 cm, Spanish the shallowest. <em>South African ball roller</em> is the trap: those beetles roll balls away on the surface; they don't dig tunnels. Mediterranean and Australian native are not in the passage.</p>
    </div>
  </div>
</div>

<div class="lx" data-lx>
  <div class="lx-head">Practice 3: summary, choosing from a list</div>
  <div class="lx-body">
    <p>Same passage, first two paragraphs. <em>Complete the summary using the list of phrases, A–G. Write the correct letter.</em></p>
    <div class="lx-box"><ol type="A">
      <li>under the dung</li><li>a permanent part of the local environment</li><li>clear improvements to the pasture</li>
      <li>protected from predators</li><li>a temporary visitor</li><li>easy prey for birds</li><li>a fall in cattle numbers</li>
    </ol></div>
    <?php $sum = ['A', 'B', 'C', 'D', 'E', 'F', 'G']; ?>
    <p style="line-height:2.4">About 1,500 beetles are released into fresh cow pats, and they quickly go <strong>1</strong> <?= $pick('A', $sum) ?>. If they adapt well, they become <strong>2</strong> <?= $pick('B', $sum) ?>, and after three or four years farmers see <strong>3</strong> <?= $pick('C', $sum) ?>. Because the beetles work inside the pat, they are <strong>4</strong> <?= $pick('D', $sum) ?>.</p>
    <div class="lx-actions"><button type="button" class="lx-btn" data-lx-check>Check answers</button><button type="button" class="lx-btn secondary" data-lx-reset>Try again</button><span class="lx-score"></span></div>
    <div class="lx-why" hidden>
      <ol>
        <li><strong>A.</strong> "disappear beneath the pats": <em>beneath the pats</em> = under the dung.</li>
        <li><strong>B.</strong> "a permanent, self-sustaining part of the local ecology": <em>ecology</em> = environment. E says the opposite.</li>
        <li><strong>C.</strong> "within three or four years the benefits to the pasture are obvious": <em>benefits... obvious</em> = clear improvements.</li>
        <li><strong>D.</strong> "sheltered from predators such as birds and foxes": <em>sheltered</em> = protected. F mentions birds but says the opposite, and G is not in the passage.</li>
      </ol>
      <p>Grammar narrows the choice too: after <em>become</em> you need a noun phrase (B or E), after <em>are</em> an adjective phrase (D or F).</p>
    </div>
  </div>
</div>

<div class="lc-take"><strong>Take away.</strong> Read the limit, predict the word, scan for the place, copy exactly. When you choose from a list, match the meaning, not the words, and let the grammar rule options out.</div>
</div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_1Mo', 3, 3, ob_get_clean());
