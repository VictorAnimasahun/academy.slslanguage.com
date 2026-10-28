<?php
// Matching & Multiple Choice — IELTS Academic — 1-Month Crash Course, Class 3, piece 4 (lesson).
// Exercises: four official IELTS Academic Reading sample tasks with their answer keys: "Identifying information"
// (Questions 14-19, motor vehicles), "Matching headings" (Questions 1-5, farming and subsidies), "Multiple choice"
// (Questions 10-12, an extract of the same farming passage) and "Matching features" (Questions 7-10, rockets).
require_once dirname(__DIR__, 3) . '/bootstrap.php';
require_once INCLUDES_PATH . '/lesson_shell.php';
require_once __DIR__ . '/listening_kit.php';

$pick = function (string $answer, array $choices): string {
    $out = '<select data-answer="' . htmlspecialchars($answer) . '"><option value="">–</option>';
    foreach ($choices as $c) $out .= '<option value="' . htmlspecialchars($c) . '">' . htmlspecialchars($c) . '</option>';
    return $out . '</select>';
};
$mc = function (string $name, array $options, string $correct): string {
    $out = '';
    foreach ($options as $letter => $text) {
        $out .= '<label class="lx-opt"><input type="radio" name="' . $name . '"' . ($letter === $correct ? ' data-correct' : '') . '> ' . $letter . '&nbsp; ' . $text . '</label>';
    }
    return $out;
};
$AH = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
$ROMAN = ['i', 'ii', 'iii', 'iv', 'v', 'vi', 'vii', 'viii', 'ix'];
$AE = ['A', 'B', 'C', 'D', 'E'];

ob_start(); ?>
<!-- LESSON BODY START -->
<!-- DRAFT: written by Claude on 2026-10-28 from the official IELTS Academic Reading sample tasks, for the instructor to review. -->
<?= listening_kit_assets() ?>
<div class="lc">
<div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul>
<li>match information, headings and features to the right part of a passage</li>
<li>choose the main idea of a paragraph rather than one of its details</li>
<li>answer multiple-choice questions, including "choose TWO", by ruling options out</li>
</ul></div>

<h5>Three kinds of matching</h5>
<table><thead><tr><th>Type</th><th>You match...</th><th>Watch out</th></tr></thead><tbody>
<tr><td><strong>Matching information</strong> ("Which paragraph contains...?")</td><td>a detail (a comparison, an example, a reason) to the paragraph it is in</td><td>Answers are <em>not</em> in passage order, and a letter may be used more than once. You are looking for a detail, not the main idea.</td></tr>
<tr><td><strong>Matching headings</strong></td><td>a heading to each paragraph or section</td><td>The heading is the <em>main idea</em> of the whole paragraph. There are more headings than paragraphs, and some describe just one detail: that's the trap.</td></tr>
<tr><td><strong>Matching features</strong></td><td>statements or items to a list (people, groups, dates, places)</td><td>Not in passage order. Scan for each name in the list; the statements paraphrase what the passage says about them.</td></tr>
</tbody></table>
<p>Reading Test 1's Questions 14–17 are matching information ("Which section contains the following information?").</p>

<h5>Multiple choice</h5>
<ul>
<li><strong>One answer from A–D</strong> (Test 1's Questions 36–40), or <strong>TWO answers from A–E</strong> (Questions 23–26). With "choose TWO", each correct letter usually scores one mark, in any order.</li>
<li>Questions follow passage order. Find the place with the question's keywords, then check every option against the passage.</li>
<li>Wrong options are usually <strong>half right</strong>: the right words with the wrong number, the wrong time, or the wrong thing. Rule them out one by one.</li>
</ul>

<div class="lx" data-lx>
  <div class="lx-head">Practice 1: official sample, which paragraph contains the information?</div>
  <div class="lx-body">
    <p>You read paragraphs A–C of this passage in the True/False/Not Given lesson. Here is the whole passage.</p>
    <div class="lx-passage">
      <p><strong>A</strong> There are now over 700 million motor vehicles in the world - and the number is rising by more than 40 million each year. The average distance driven by car users is growing too - from 8km a day per person in western Europe in 1965 to 25 km a day in 1995. This dependence on motor vehicles has given rise to major problems, including environmental pollution, depletion of oil resources, traffic congestion and safety.</p>
      <p><strong>B</strong> While emissions from new cars are far less harmful than they used to be, city streets and motorways are becoming more crowded than ever, often with older trucks, buses and taxis which emit excessive levels of smoke and fumes. This concentration of vehicles makes air quality in urban areas unpleasant and sometimes dangerous to breathe. Even Moscow has joined the list of capitals afflicted by congestion and traffic fumes. In Mexico City, vehicle pollution is a major health hazard.</p>
      <p><strong>C</strong> Until a hundred years ago, most journeys were in the 20km range, the distance conveniently accessible by horse. Heavy freight could only be carried by water or rail. Invention of the motor vehicle brought personal mobility to the masses and made rapid freight delivery possible over a much wider area. In the United Kingdom, about 90 per cent of inland freight is carried by road. The world cannot revert to the horse-drawn wagon. Can it avoid being locked into congested and polluting ways of transporting people and goods?</p>
      <p><strong>D</strong> In Europe most cities are still designed for the old modes of transport. Adaptation to the motor car has involved adding ring roads, one-way systems and parking lots. In the United States, more land is assigned to car use than to housing. Urban sprawl means that life without a car is next to impossible. Mass use of motor vehicles has also killed or injured millions of people. Other social effects have been blamed on the car such as alienation and aggressive human behaviour.</p>
      <p><strong>E</strong> A 1993 study by the European Federation for Transport and Environment found that car transport is seven times as costly as rail travel in terms of the external social costs it entails - congestion, accidents, pollution, loss of cropland and natural habitats, depletion of oil resources, and so on. Yet cars easily surpass trains or buses as a flexible and convenient mode of personal transport. It is unrealistic to expect people to give up private cars in favour of mass transit.</p>
      <p><strong>F</strong> Technical solutions can reduce the pollution problem and increase the fuel efficiency of engines. But fuel consumption and exhaust emissions depend on which cars are preferred by customers and how they are driven. Many people buy larger cars than they need for daily purposes or waste fuel by driving aggressively. Besides, global car use is increasing at a faster rate than the improvement in emissions and fuel efficiency which technology is now making possible.</p>
      <p><strong>G</strong> Some argue that the only long-term solution is to design cities and neighbourhoods so that car journeys are not necessary - all essential services being located within walking distance or easily accessible by public transport. Not only would this save energy and cut carbon dioxide emissions, it would also enhance the quality of community life, putting the emphasis on people instead of cars. Good local government is already bringing this about in some places. But few democratic communities are blessed with the vision – and the capital – to make such profound changes in modern lifestyles.</p>
      <p><strong>H</strong> A more likely scenario seems to be a combination of mass transit systems for travel into and around cities, with small 'low emission' cars for urban use and larger hybrid or lean burn cars for use elsewhere. Electronically tolled highways might be used to ensure that drivers pay charges geared to actual road use. Better integration of transport systems is also highly desirable - and made more feasible by modern computers. But these are solutions for countries which can afford them. In most developing countries, old cars and old technologies continue to predominate.</p>
    </div>
    <p><em>Which paragraph contains the following information? Choose A–H. You may use any letter more than once.</em></p>
    <div class="lx-match"><strong>14</strong><span>a comparison of past and present transportation methods</span><?= $pick('C', $AH) ?></div>
    <div class="lx-match"><strong>15</strong><span>how driving habits contribute to road problems</span><?= $pick('F', $AH) ?></div>
    <div class="lx-match"><strong>16</strong><span>the relative merits of cars and public transport</span><?= $pick('E', $AH) ?></div>
    <div class="lx-match"><strong>17</strong><span>the writer's prediction on future solutions</span><?= $pick('H', $AH) ?></div>
    <div class="lx-match"><strong>18</strong><span>the increasing use of motor vehicles</span><?= $pick('A', $AH) ?></div>
    <div class="lx-match"><strong>19</strong><span>the impact of the car on city development</span><?= $pick('D', $AH) ?></div>
    <div class="lx-actions"><button type="button" class="lx-btn" data-lx-check>Check answers</button><button type="button" class="lx-btn secondary" data-lx-reset>Try again</button><span class="lx-score"></span></div>
    <div class="lx-why" hidden>
      <p>Official answers: 14 C, 15 F, 16 E, 17 H, 18 A, 19 D. Note the order: C, F, E, H, A, D, not the passage order.</p>
      <ul>
        <li><strong>14 C.</strong> "Until a hundred years ago... by horse... Invention of the motor vehicle brought personal mobility": past vs present.</li>
        <li><strong>15 F.</strong> "Many people buy larger cars than they need... or waste fuel by driving aggressively": <em>driving habits</em>.</li>
        <li><strong>16 E.</strong> Car transport costs seven times as much as rail, "Yet cars easily surpass trains or buses as a flexible and convenient mode": merits of each.</li>
        <li><strong>17 H.</strong> "A more likely scenario seems to be...": <em>scenario</em> = prediction. G is what "some argue", not the writer's own prediction.</li>
        <li><strong>18 A.</strong> "the number is rising by more than 40 million each year".</li>
        <li><strong>19 D.</strong> "Adaptation to the motor car has involved adding ring roads, one-way systems and parking lots... Urban sprawl".</li>
      </ul>
    </div>
  </div>
</div>

<div class="lx" data-lx>
  <div class="lx-head">Practice 2: official sample, matching headings</div>
  <div class="lx-body">
    <p>Read the passage (six sections, A–F) and choose the correct heading for sections A–D and F. Section E is done as an example.</p>
    <div class="lx-box"><strong>List of headings</strong><ol type="i">
      <li>The probable effects of the new international trade agreement</li>
      <li>The environmental impact of modern farming</li>
      <li>Farming and soil erosion</li>
      <li>The effects of government policy in rich countries</li>
      <li>Governments and management of the environment</li>
      <li>The effects of government policy in poor countries</li>
      <li>Farming and food output</li>
      <li>The effects of government policy on food output</li>
      <li>The new prospects for world trade</li>
    </ol></div>
    <div class="lx-passage">
      <p><strong>Section A</strong> The role of governments in environmental management is difficult but inescapable. Sometimes, the state tries to manage the resources it owns, and does so badly. Often, however, governments act in an even more harmful way. They actually subsidise the exploitation and consumption of natural resources. A whole range of policies, from farm-price support to protection for coal-mining, do environmental damage and (often) make no economic sense. Scrapping them offers a two-fold bonus: a cleaner environment and a more efficient economy. Growth and environmentalism can actually go hand in hand, if politicians have the courage to confront the vested interest that subsidies create.</p>
      <p><strong>Section B</strong> No activity affects more of the earth's surface than farming. It shapes a third of the planet's land area, not counting Antarctica, and the proportion is rising. World food output per head has risen by 4 per cent between the 1970s and 1980s mainly as a result of increases in yields from land already in cultivation, but also because more land has been brought under the plough. Higher yields have been achieved by increased irrigation, better crop breeding, and a doubling in the use of pesticides and chemical fertilisers in the 1970s and 1980s.</p>
      <p><strong>Section C</strong> All these activities may have damaging environmental impacts. For example, land clearing for agriculture is the largest single cause of deforestation; chemical fertilisers and pesticides may contaminate water supplies; more intensive farming and the abandonment of fallow periods tend to exacerbate soil erosion; and the spread of monoculture and use of high-yielding varieties of crops have been accompanied by the disappearance of old varieties of food plants which might have provided some insurance against pests or diseases in future. Soil erosion threatens the productivity of land in both rich and poor countries. The United States, where the most careful measurements have been done, discovered in 1982 that about one-fifth of its farmland was losing topsoil at a rate likely to diminish the soil's productivity. The country subsequently embarked upon a program to convert 11 per cent of its cropped land to meadow or forest. Topsoil in India and China is vanishing much faster than in America.</p>
      <p><strong>Section D</strong> Government policies have frequently compounded the environmental damage that farming can cause. In the rich countries, subsidies for growing crops and price supports for farm output drive up the price of land. The annual value of these subsidies is immense: about $250 billion, or more than all World Bank lending in the 1980s. To increase the output of crops per acre, a farmer's easiest option is to use more of the most readily available inputs: fertilisers and pesticides. Fertiliser use doubled in Denmark in the period 1960-1985 and increased in The Netherlands by 150 per cent. The quantity of pesticides applied has risen too: by 69 per cent in 1975-1984 in Denmark, for example, with a rise of 115 per cent in the frequency of application in the three years from 1981.</p>
      <p>In the late 1980s and early 1990s some efforts were made to reduce farm subsidies. The most dramatic example was that of New Zealand, which scrapped most farm support in 1984. A study of the environmental effects, conducted in 1993, found that the end of fertiliser subsidies had been followed by a fall in fertiliser use (a fall compounded by the decline in world commodity prices, which cut farm incomes). The removal of subsidies also stopped land-clearing and over-stocking, which in the past had been the principal causes of erosion. Farms began to diversify. The one kind of subsidy whose removal appeared to have been bad for the environment was the subsidy to manage soil erosion.</p>
      <p>In less enlightened countries, and in the European Union, the trend has been to reduce rather than eliminate subsidies, and to introduce new payments to encourage farmers to treat their land in environmentally friendlier ways, or to leave it fallow. It may sound strange but such payments need to be higher than the existing incentives for farmers to grow food crops. Farmers, however, dislike being paid to do nothing. In several countries they have become interested in the possibility of using fuel produced from crop residues either as a replacement for petrol (as ethanol) or as fuel for power stations (as biomass). Such fuels produce far less carbon dioxide than coal or oil, and absorb carbon dioxide as they grow. They are therefore less likely to contribute to the greenhouse effect. But they are rarely competitive with fossil fuels unless subsidised - and growing them does no less environmental harm than other crops.</p>
      <p><strong>Section E</strong> In poor countries, governments aggravate other sorts of damage. Subsidies for pesticides and artificial fertilisers encourage farmers to use greater quantities than are needed to get the highest economic crop yield. A study by the International Rice Research Institute of pesticide use by farmers in South East Asia found that, with pest-resistant varieties of rice, even moderate applications of pesticide frequently cost farmers more than they saved. Such waste puts farmers on a chemical treadmill: bugs and weeds become resistant to poisons, so next year's poisons must be more lethal. One cost is to human health. Every year some 10,000 people die from pesticide poisoning, almost all of them in the developing countries, and another 400,000 become seriously ill. As for artificial fertilisers, their use world-wide increased by 40 per cent per unit of farmed land between the mid 1970s and late 1980s, mostly in the developing countries. Overuse of fertilisers may cause farmers to stop rotating crops or leaving their land fallow. That, in turn, may make soil erosion worse.</p>
      <p><strong>Section F</strong> A result of the Uruguay Round of world trade negotiations is likely to be a reduction of 36 per cent in the average levels of farm subsidies paid by the rich countries in 1986-1990. Some of the world's food production will move from Western Europe to regions where subsidies are lower or non-existent, such as the former communist countries and parts of the developing world. Some environmentalists worry about this outcome. It will undoubtedly mean more pressure to convert natural habitat into farmland. But it will also have many desirable environmental effects. The intensity of farming in the rich world should decline, and the use of chemical inputs will diminish. Crops are more likely to be grown in the environments to which they are naturally suited. And more farmers in poor countries will have the money and the incentive to manage their land in ways that are sustainable in the long run. That is important. To feed an increasingly hungry world, farmers need every incentive to use their soil and water effectively and efficiently.</p>
    </div>
    <div class="lx-match"><strong>1</strong><span>Section A</span><?= $pick('v', $ROMAN) ?></div>
    <div class="lx-match"><strong>2</strong><span>Section B</span><?= $pick('vii', $ROMAN) ?></div>
    <div class="lx-match"><strong>3</strong><span>Section C</span><?= $pick('ii', $ROMAN) ?></div>
    <div class="lx-match"><strong>4</strong><span>Section D</span><?= $pick('iv', $ROMAN) ?></div>
    <div class="lx-match"><strong></strong><span><em>Example: Section E</em></span><span><strong>vi</strong></span></div>
    <div class="lx-match"><strong>5</strong><span>Section F</span><?= $pick('i', $ROMAN) ?></div>
    <div class="lx-actions"><button type="button" class="lx-btn" data-lx-check>Check answers</button><button type="button" class="lx-btn secondary" data-lx-reset>Try again</button><span class="lx-score"></span></div>
    <div class="lx-why" hidden>
      <p>Official answers: A v, B vii, C ii, D iv, F i.</p>
      <ul>
        <li><strong>A v.</strong> The first sentence gives it: "The role of governments in environmental management".</li>
        <li><strong>B vii.</strong> Farming's reach and "World food output per head has risen". viii is a trap: B says nothing about government policy.</li>
        <li><strong>C ii.</strong> "All these activities may have damaging environmental impacts", then a list of them. <em>iii Farming and soil erosion</em> is the trap: soil erosion is one example in C, not the whole section.</li>
        <li><strong>D iv.</strong> "In the rich countries, subsidies...", then New Zealand and the EU. Compare the example: E is the same for poor countries (vi).</li>
        <li><strong>F i.</strong> "A result of the Uruguay Round of world trade negotiations is likely to be..." = probable effects of a new trade agreement. ix is close, but F is about effects on farming and the environment, not prospects for trade.</li>
      </ul>
    </div>
  </div>
</div>

<div class="lx" data-lx>
  <div class="lx-head">Practice 3: official sample, multiple choice</div>
  <div class="lx-body">
    <p>These questions are on Sections C and D of the farming passage above. <em>Choose the correct letter, A, B, C or D.</em></p>
    <div class="lx-q"><strong>10. Research completed in 1982 found that in the United States soil erosion</strong><?= $mc('m10', ['A' => 'reduced the productivity of farmland by 20 per cent.', 'B' => 'was almost as severe as in India and China.', 'C' => 'was causing significant damage to 20 per cent of farmland.', 'D' => 'could be reduced by converting cultivated land to meadow or forest.'], 'C') ?></div>
    <div class="lx-q"><strong>11. By the mid-1980s, farmers in Denmark</strong><?= $mc('m11', ['A' => 'used 50 per cent less fertiliser than Dutch farmers.', 'B' => 'used twice as much fertiliser as they had in 1960.', 'C' => 'applied fertiliser much more frequently than in 1960.', 'D' => 'more than doubled the amount of pesticide they used in just 3 years.'], 'B') ?></div>
    <div class="lx-q"><strong>12. Which one of the following increased in New Zealand after 1984?</strong><?= $mc('m12', ['A' => 'farm incomes', 'B' => 'use of fertiliser', 'C' => 'over-stocking', 'D' => 'farm diversification'], 'D') ?></div>
    <div class="lx-actions"><button type="button" class="lx-btn" data-lx-check>Check answers</button><button type="button" class="lx-btn secondary" data-lx-reset>Try again</button><span class="lx-score"></span></div>
    <div class="lx-why" hidden>
      <p>Official answers: 10 C, 11 B, 12 D.</p>
      <ol start="10">
        <li><strong>C.</strong> "about one-fifth of its farmland was losing topsoil at a rate likely to diminish the soil's productivity": one-fifth = 20%. A is half right: the productivity was <em>likely</em> to fall, not reduced by 20%. B: India and China are much <em>worse</em>. D happened <em>afterwards</em> ("subsequently"); it isn't what the research found.</li>
        <li><strong>B.</strong> "Fertiliser use doubled in Denmark in the period 1960-1985". C and D mix up the numbers: the 115% rise in three years was in how <em>often</em> <em>pesticide</em> was applied, not the amount, and not fertiliser.</li>
        <li><strong>D.</strong> "Farms began to diversify." Incomes were cut (A), fertiliser use fell (B), and over-stocking stopped (C).</li>
      </ol>
    </div>
  </div>
</div>

<div class="lx" data-lx>
  <div class="lx-head">Practice 4: official sample, matching features</div>
  <div class="lx-body">
    <p>An extract on the development of rockets. <em>Match each item with the group which first invented or used it. You may use any letter more than once.</em></p>
    <div class="lx-passage">
      <p>The invention of rockets is linked inextricably with the invention of 'black powder'. Most historians of technology credit the Chinese with its discovery. They base their belief on studies of Chinese writings or on the notebooks of early Europeans who settled in or made long visits to China to study its history and civilisation. It is probable that, some time in the tenth century, black powder was first compounded from its basic ingredients of saltpetre, charcoal and sulphur. But this does not mean that it was immediately used to propel rockets. By the thirteenth century, powder-propelled fire arrows had become rather common. The Chinese relied on this type of technological development to produce incendiary projectiles of many sorts, explosive grenades and possibly cannons to repel their enemies. One such weapon was the 'basket of fire' or, as directly translated from Chinese, the 'arrows like flying leopards'. The 0.7 metre-long arrows, each with a long tube of gunpowder attached near the point of each arrow, could be fired from a long, octagonal-shaped basket at the same time and had a range of 400 paces. Another weapon was the 'arrow as a flying sabre', which could be fired from crossbows. The rocket, placed in a similar position to other rocket-propelled arrows, was designed to increase the range. A small iron weight was attached to the 1.5m bamboo shaft, just below the feathers, to increase the arrow's stability by moving the centre of gravity to a position below the rocket. At a similar time, the Arabs had developed the 'egg which moves and burns'. This 'egg' was apparently full of gunpowder and stabilised by a 1.5m tail. It was fired using two rockets attached to either side of this tail.</p>
      <p>It was not until the eighteenth century that Europe became seriously interested in the possibilities of using the rocket itself as a weapon of war and not just to propel other weapons. Prior to this, rockets were used only in pyrotechnic displays. The incentive for the more aggressive use of rockets came not from within the European continent but from far-away India, whose leaders had built up a corps of rocketeers and used rockets successfully against the British in the late eighteenth century. The Indian rockets used against the British were described by a British Captain serving in India as 'an iron envelope about 200 millimetres long and 40 millimetres in diameter with sharp points at the top and a 3m-long bamboo guiding stick'. In the early nineteenth century the British began to experiment with incendiary barrage rockets. The British rocket differed from the Indian version in that it was completely encased in a stout, iron cylinder, terminating in a conical head, measuring one metre in diameter and having a stick almost five metres long and constructed in such a way that it could be firmly attached to the body of the rocket. The Americans developed a rocket, complete with its own launcher, to use against the Mexicans in the mid-nineteenth century. A long cylindrical tube was propped up by two sticks and fastened to the top of the launcher, thereby allowing the rockets to be inserted and lit from the other end. However, the results were sometimes not that impressive as the behaviour of the rockets in flight was less than predictable.</p>
    </div>
    <div class="lx-box"><strong>First invented or used by:</strong> A the Chinese · B the Indians · C the British · D the Arabs · E the Americans</div>
    <div class="lx-match"><strong>7</strong><span>black powder</span><?= $pick('A', $AE) ?></div>
    <div class="lx-match"><strong>8</strong><span>rocket-propelled arrows for fighting</span><?= $pick('A', $AE) ?></div>
    <div class="lx-match"><strong>9</strong><span>rockets as war weapons</span><?= $pick('B', $AE) ?></div>
    <div class="lx-match"><strong>10</strong><span>the rocket launcher</span><?= $pick('E', $AE) ?></div>
    <div class="lx-actions"><button type="button" class="lx-btn" data-lx-check>Check answers</button><button type="button" class="lx-btn secondary" data-lx-reset>Try again</button><span class="lx-score"></span></div>
    <div class="lx-why" hidden>
      <p>Official answers: 7 A, 8 A, 9 B, 10 E.</p>
      <ul>
        <li><strong>7 A.</strong> "Most historians of technology credit the Chinese with its discovery."</li>
        <li><strong>8 A.</strong> The Chinese "powder-propelled fire arrows" and the "arrow as a flying sabre". The Arabs' "egg" came "at a similar time", not first.</li>
        <li><strong>9 B.</strong> Using "the rocket itself as a weapon of war" came "from far-away India, whose leaders... used rockets successfully against the British". The British only experimented later (C is the trap).</li>
        <li><strong>10 E.</strong> "The Americans developed a rocket, complete with its own launcher".</li>
      </ul>
    </div>
  </div>
</div>

<div class="lc-take"><strong>Take away.</strong> Matching information: a detail, any order. Headings: the main idea of the whole paragraph, so beware headings that describe one example. Multiple choice: find the place, then rule out the options that are half right.</div>
</div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_1Mo', 3, 4, ob_get_clean());
