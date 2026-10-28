<?php
// True/False/Not Given & Yes/No/Not Given — IELTS Academic — 1-Month Crash Course, Class 3, piece 2 (lesson).
// Exercises: the official IELTS Academic Reading sample task "Identifying writer's views/claims" (Questions 4-7, with
// its answer key) and a True/False/Not Given drill written on the official "Identifying information" passage.
require_once dirname(__DIR__, 3) . '/bootstrap.php';
require_once INCLUDES_PATH . '/lesson_shell.php';
require_once __DIR__ . '/listening_kit.php';

$tfng = function (string $name, string $correct): string {
    $out = '';
    foreach (['TRUE', 'FALSE', 'NOT GIVEN'] as $v) {
        $out .= '<label class="lx-opt"><input type="radio" name="' . $name . '"' . ($v === $correct ? ' data-correct' : '') . '> ' . $v . '</label>';
    }
    return $out;
};
$yn = function (string $name, string $correct): string {
    $out = '';
    foreach (['YES', 'NO', 'NOT GIVEN'] as $v) {
        $out .= '<label class="lx-opt"><input type="radio" name="' . $name . '"' . ($v === $correct ? ' data-correct' : '') . '> ' . $v . '</label>';
    }
    return $out;
};

ob_start(); ?>
<!-- LESSON BODY START -->
<!-- DRAFT: written by Claude on 2026-10-28 from the official IELTS Academic Reading sample tasks, for the instructor to review. -->
<?= listening_kit_assets() ?>
<div class="lc">
<div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul>
<li>tell True/False/Not Given (facts) from Yes/No/Not Given (the writer's views)</li>
<li>decide between False and Not Given, where most marks are lost</li>
<li>spot the small words that change a statement's meaning</li>
</ul></div>

<h5>Two versions of one question type</h5>
<table><thead><tr><th></th><th>True / False / Not Given</th><th>Yes / No / Not Given</th></tr></thead><tbody>
<tr><td><strong>Asks about</strong></td><td>The <em>information</em> in the passage (facts)</td><td>The <em>writer's views or claims</em> (opinions)</td></tr>
<tr><td><strong>Instruction</strong></td><td>"Do the following statements agree with the information given in the passage?"</td><td>"Do the following statements agree with the claims / views of the writer?"</td></tr>
<tr><td><strong>TRUE / YES</strong></td><td>The passage says the same thing</td><td>The writer says or clearly thinks the same thing</td></tr>
<tr><td><strong>FALSE / NO</strong></td><td>The passage says the <em>opposite</em></td><td>The writer says or thinks the <em>opposite</em></td></tr>
<tr><td><strong>NOT GIVEN</strong></td><td>The passage does not say; you cannot know</td><td>It is impossible to say what the writer thinks</td></tr>
</tbody></table>
<p>Reading Test 1 has both: Questions 7–13 are True/False/Not Given, and Questions 32–35 are Yes/No/Not Given. The method is the same; only the words change.</p>

<h5>False or Not Given? The one test</h5>
<p>Ask: <strong>does the passage say something that makes the statement impossible?</strong> If yes, it is FALSE (or NO). If the passage simply doesn't tell you, even if the statement sounds likely, it is NOT GIVEN.</p>
<ul>
<li>Passage: <em>"Fertiliser use doubled in Denmark between 1960 and 1985."</em></li>
<li>"Fertiliser use in Denmark fell between 1960 and 1985." <strong>FALSE</strong>: the passage says it rose.</li>
<li>"Danish farmers used more fertiliser than British farmers." <strong>NOT GIVEN</strong>: Britain is not mentioned.</li>
</ul>
<div class="lc-warn"><strong>Never use what you know.</strong> A statement can be true in the real world and still be NOT GIVEN, because the passage doesn't say it. Answer only from the passage.</div>

<h5>The method</h5>
<ol>
<li><strong>Read the statement and underline its keywords</strong>, especially names, numbers and dates, which are easy to scan for.</li>
<li><strong>Find the place in the passage.</strong> The answers come in passage order, so the answer to the next statement is after this one.</li>
<li><strong>Read that part carefully</strong> and compare it with the statement, word by word.</li>
<li><strong>Check the small words</strong> that change meaning: <em>all / some / most</em>, <em>always / usually / never</em>, <em>more / less / the most</em>, <em>only</em>, <em>first</em>, <em>can / will</em>. If the passage says "some" and the statement says "all", it is not TRUE.</li>
<li><strong>Decide</strong>, using the False-or-Not-Given test. Don't spend more than a minute: guess and move on.</li>
</ol>

<div class="lx" data-lx>
  <div class="lx-head">Practice 1: True, False or Not Given?</div>
  <div class="lx-body">
    <p>Read the passage (from an official IELTS Academic Reading sample), then decide for each statement.</p>
    <div class="lx-passage">
      <p><strong>A</strong> There are now over 700 million motor vehicles in the world - and the number is rising by more than 40 million each year. The average distance driven by car users is growing too - from 8km a day per person in western Europe in 1965 to 25 km a day in 1995. This dependence on motor vehicles has given rise to major problems, including environmental pollution, depletion of oil resources, traffic congestion and safety.</p>
      <p><strong>B</strong> While emissions from new cars are far less harmful than they used to be, city streets and motorways are becoming more crowded than ever, often with older trucks, buses and taxis which emit excessive levels of smoke and fumes. This concentration of vehicles makes air quality in urban areas unpleasant and sometimes dangerous to breathe. Even Moscow has joined the list of capitals afflicted by congestion and traffic fumes. In Mexico City, vehicle pollution is a major health hazard.</p>
      <p><strong>C</strong> Until a hundred years ago, most journeys were in the 20km range, the distance conveniently accessible by horse. Heavy freight could only be carried by water or rail. Invention of the motor vehicle brought personal mobility to the masses and made rapid freight delivery possible over a much wider area. In the United Kingdom, about 90 per cent of inland freight is carried by road. The world cannot revert to the horse-drawn wagon. Can it avoid being locked into congested and polluting ways of transporting people and goods?</p>
    </div>
    <div class="lx-q"><strong>1. The number of motor vehicles in the world grows by more than 40 million a year.</strong><?= $tfng('t1', 'TRUE') ?></div>
    <div class="lx-q"><strong>2. In 1965, car users in western Europe drove an average of 25 km a day.</strong><?= $tfng('t2', 'FALSE') ?></div>
    <div class="lx-q"><strong>3. New cars produce more harmful emissions than older cars.</strong><?= $tfng('t3', 'FALSE') ?></div>
    <div class="lx-q"><strong>4. Air pollution from traffic is worse in Moscow than in Mexico City.</strong><?= $tfng('t4', 'NOT GIVEN') ?></div>
    <div class="lx-q"><strong>5. Most inland freight in the United Kingdom travels by road.</strong><?= $tfng('t5', 'TRUE') ?></div>
    <div class="lx-q"><strong>6. Most people in the United Kingdom would like more freight to go by rail.</strong><?= $tfng('t6', 'NOT GIVEN') ?></div>
    <div class="lx-actions"><button type="button" class="lx-btn" data-lx-check>Check answers</button><button type="button" class="lx-btn secondary" data-lx-reset>Try again</button><span class="lx-score"></span></div>
    <div class="lx-why" hidden>
      <ol>
        <li><strong>TRUE.</strong> "the number is rising by more than 40 million each year": <em>rising</em> = grows, <em>each year</em> = a year.</li>
        <li><strong>FALSE.</strong> In 1965 it was 8 km a day; 25 km was in 1995. The passage gives the 1965 figure, and it is different.</li>
        <li><strong>FALSE.</strong> "emissions from new cars are far less harmful than they used to be": the opposite of the statement.</li>
        <li><strong>NOT GIVEN.</strong> Both cities are mentioned, but the passage never compares them. Two names in one paragraph is a classic Not Given trap.</li>
        <li><strong>TRUE.</strong> "about 90 per cent of inland freight is carried by road": 90% = most.</li>
        <li><strong>NOT GIVEN.</strong> The passage gives the 90% figure but says nothing about what people would like.</li>
      </ol>
    </div>
  </div>
</div>

<div class="lx" data-lx>
  <div class="lx-head">Practice 2: official sample, Yes, No or Not Given?</div>
  <div class="lx-body">
    <p>This is an official IELTS Academic Reading sample task. Read the passage, then answer Questions 4–7.</p>
    <div class="lx-passage">
      <p>Discovered in the early 1800s and named 'nicotianine', the oily essence now called nicotine is the main active ingredient of tobacco. Nicotine, however, is only a small component of cigarette smoke, which contains more than 4,700 chemical compounds, including 43 cancer-causing substances. In recent times, scientific research has been providing evidence that years of cigarette smoking vastly increases the risk of developing fatal medical conditions.</p>
      <p>In addition to being responsible for more than 85 per cent of lung cancers, smoking is associated with cancers of, amongst others, the mouth, stomach and kidneys, and is thought to cause about 14 per cent of leukemia and cervical cancers. In 1990, smoking caused more than 84,000 deaths, mainly resulting from such problems as pneumonia, bronchitis and influenza. Smoking, it is believed, is responsible for 30 per cent of all deaths from cancer and clearly represents the most important preventable cause of cancer in countries like the United States today.</p>
      <p>Passive smoking, the breathing in of the side-stream smoke from the burning of tobacco between puffs or of the smoke exhaled by a smoker, also causes a serious health risk. A report published in 1992 by the US Environmental Protection Agency (EPA) emphasized the health dangers, especially from side-stream smoke. This type of smoke contains more smaller particles and is therefore more likely to be deposited deep in the lungs. On the basis of this report, the EPA has classified environmental tobacco smoke in the highest risk category for causing cancer.</p>
      <p>As an illustration of the health risks, in the case of a married couple where one partner is a smoker and one a non-smoker, the latter is believed to have a 30 per cent higher risk of death from heart disease because of passive smoking. The risk of lung cancer also increases over the years of exposure and the figure jumps to 80 per cent if the spouse has been smoking four packs a day for 20 years. It has been calculated that 17 per cent of cases of lung cancer can be attributed to high levels of exposure to second-hand tobacco smoke during childhood and adolescence.</p>
      <p>A more recent study by researchers at the University of California at San Francisco (UCSF) has shown that second-hand cigarette smoke does more harm to non-smokers than to smokers. Leaving aside the philosophical question of whether anyone should have to breathe someone else's cigarette smoke, the report suggests that the smoke experienced by many people in their daily lives is enough to produce substantial adverse effects on a person's heart and lungs.</p>
      <p>The report, published in the Journal of the American Medical Association (AMA), was based on the researchers' own earlier research but also includes a review of studies over the past few years. The American Medical Association represents about half of all US doctors and is a strong opponent of smoking. The study suggests that people who smoke cigarettes are continually damaging their cardiovascular system, which adapts in order to compensate for the effects of smoking. It further states that people who do not smoke do not have the benefit of their system adapting to the smoke inhalation. Consequently, the effects of passive smoking are far greater on non-smokers than on smokers.</p>
      <p>This report emphasizes that cancer is not caused by a single element in cigarette smoke; harmful effects to health are caused by many components. Carbon monoxide, for example, competes with oxygen in red blood cells and interferes with the blood's ability to deliver life-giving oxygen to the heart. Nicotine and other toxins in cigarette smoke activate small blood cells called platelets, which increases the likelihood of blood clots, thereby affecting blood circulation throughout the body.</p>
      <p>The researchers criticize the practice of some scientific consultants who work with the tobacco industry for assuming that cigarette smoke has the same impact on smokers as it does on non-smokers. They argue that those scientists are underestimating the damage done by passive smoking and, in support of their recent findings, cite some previous research which points to passive smoking as the cause for between 30,000 and 60,000 deaths from heart attacks each year in the United States. This means that passive smoking is the third most preventable cause of death after active smoking and alcohol-related diseases.</p>
      <p>The study argues that the type of action needed against passive smoking should be similar to that being taken against illegal drugs and AIDS (SIDA). The UCSF researchers maintain that the simplest and most cost-effective action is to establish smoke-free work places, schools and public places.</p>
    </div>
    <p><em>Do the following statements reflect the claims of the writer? Write YES if the statement reflects the claims of the writer, NO if it contradicts them, NOT GIVEN if it is impossible to say what the writer thinks about this.</em></p>
    <div class="lx-q"><strong>4. Thirty per cent of deaths in the United States are caused by smoking-related diseases.</strong><?= $yn('y4', 'NO') ?></div>
    <div class="lx-q"><strong>5. If one partner in a marriage smokes, the other is likely to take up smoking.</strong><?= $yn('y5', 'NOT GIVEN') ?></div>
    <div class="lx-q"><strong>6. Teenagers whose parents smoke are at risk of getting lung cancer at some time during their lives.</strong><?= $yn('y6', 'YES') ?></div>
    <div class="lx-q"><strong>7. Opponents of smoking financed the UCSF study.</strong><?= $yn('y7', 'NOT GIVEN') ?></div>
    <div class="lx-actions"><button type="button" class="lx-btn" data-lx-check>Check answers</button><button type="button" class="lx-btn secondary" data-lx-reset>Try again</button><span class="lx-score"></span></div>
    <div class="lx-why" hidden>
      <p>Official answers: 4 NO, 5 NOT GIVEN, 6 YES, 7 NOT GIVEN.</p>
      <ol start="4">
        <li><strong>NO.</strong> The writer says smoking is responsible for "30 per cent of all deaths <em>from cancer</em>", not 30% of all deaths. One missing qualifier turns the claim into a different one.</li>
        <li><strong>NOT GIVEN.</strong> The married couple is discussed, but only the non-smoker's risk of heart disease from passive smoking. Nothing is said about the partner starting to smoke.</li>
        <li><strong>YES.</strong> "17 per cent of cases of lung cancer can be attributed to high levels of exposure to second-hand tobacco smoke during childhood and adolescence": teenagers = adolescence, parents who smoke = second-hand smoke at home.</li>
        <li><strong>NOT GIVEN.</strong> The AMA, which <em>published</em> the report, is "a strong opponent of smoking", but the passage never says who <em>paid for</em> the study. Published by is not financed by.</li>
      </ol>
    </div>
  </div>
</div>

<div class="lc-take"><strong>Take away.</strong> TRUE means the passage says it; FALSE means the passage says the opposite; NOT GIVEN means the passage doesn't say. Check the small words, answer only from the passage, and don't let one statement take more than a minute.</div>
</div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_1Mo', 3, 2, ob_get_clean());
