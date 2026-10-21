<?php
// The orientation session (founder, 2026-10-21): separate from the courses -- an interactive walk through the
// platform (real screenshots, animated pointers), a short quiz, then the form and commitment: class format, days,
// time and length, start date, weekly study hours, target score, signed. Each course's designated tutor is
// introduced here and sees the plan in SLS Admin's planner. Saved by orientation/save.php.
require_once dirname(__DIR__) . '/bootstrap.php';
require_once INCLUDES_PATH . '/course_shape.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../edu_hub_registration.php?message=Please+login+to+start+your+orientation");
    exit();
}
$studentId = (int) $_SESSION['user_id'];
$h = fn ($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');

$st = $db->prepare("SELECT firstname, lastname FROM students WHERE id = ?");
$st->execute([$studentId]);
$student = $st->fetch(PDO::FETCH_ASSOC) ?: ['firstname' => '', 'lastname' => ''];

$st = $db->prepare("SELECT c.id, c.title, c.folder_name, e2.name AS tutor
                      FROM enrollments e JOIN courses c ON c.id = e.course_id
                      LEFT JOIN employees e2 ON e2.id = c.tutor_employee_id
                     WHERE e.student_id = ? ORDER BY e.enrolled_at");
$st->execute([$studentId]);
$courses = $st->fetchAll(PDO::FETCH_ASSOC);

$st = $db->prepare("SELECT * FROM student_class_plans WHERE student_id = ?");
$st->execute([$studentId]);
$plans = array_column($st->fetchAll(PDO::FETCH_ASSOC), null, 'course_id');

$st = $db->prepare("SELECT completed_at FROM student_orientation WHERE student_id = ?");
$st->execute([$studentId]);
$doneAt = $st->fetchColumn();

$img = ACADEMY_URL . 'assets/img/orientation/';
// Each tour step: screenshot, heading, text, pointers [x%, y%, label, where the label sits: down (default) | up | right].
$tour = [
    ['dashboard', 'Your dashboard', 'Everything starts here. Your courses and progress are on the dashboard; My Results and Messages are in the menu.',
        [[31.6, 50.3, 'Continue where you stopped', 'up'], [8, 45.3, 'Your scores and feedback', 'right'], [8, 57, 'Write to your tutor', 'right']]],
    ['course', 'Your course', 'Your course runs week by week, two classes a week. Open a week to see each class and what is in it. Your tutor is named here.',
        [[30, 87, 'Your tutor'], [29, 82.6, 'Classes you have finished']]],
    ['lesson', 'Lessons', 'Each class is made of items: lessons, quizzes and tests. Spend at least five minutes on a lesson, then mark it complete.',
        [[81, 94.6, 'Mark complete after 5 minutes', 'up']]],
    ['slides', 'Class slides', 'Some lessons are your tutor\'s class slides. Move through them with the arrows, then go on to the next item.',
        [[22.9, 94, 'Move through the slides', 'up'], [50.5, 98.5, 'Then go on to the next item', 'up']]],
    ['test', 'Tests', 'Practice tests and mock tests run like the real exam: timed, one section at a time. Take them on a computer, not on your phone.',
        [[66, 24.3, 'The timer keeps running'], [75.4, 24.3, 'Move to the next part']]],
    ['results', 'Your results', 'Every test you finish is saved in My Results. Writing and speaking get an instant AI score; your tutor reviews them and gives the final grade.',
        [[22.3, 60.6, 'Your grade, with your tutor\'s notes']]],
];
// The quiz: [question, options, index of the right answer].
$quiz = [
    ['How many classes do you have each week?', ['One', 'Two', 'Four'], 1],
    ['When can you mark a lesson complete?', ['As soon as it opens', 'After at least five minutes on it', 'Only your tutor can'], 1],
    ['When does a class count as done?', ['When you open it', 'When every lesson, quiz and test in it is done', 'When the week ends'], 1],
    ['Where should you take practice tests and mock tests?', ['On a computer', 'On your phone', 'Either, it does not matter'], 0],
    ['Who gives the final grade for your writing and speaking?', ['Nobody, it is not graded', 'The AI only', 'Your tutor, after the AI\'s instant score'], 2],
    ['Where do you find your scores and your tutor\'s feedback?', ['My Results', 'The course page', 'Your email only'], 0],
];
$days = ['mon' => 'Mon', 'tue' => 'Tue', 'wed' => 'Wed', 'thu' => 'Thu', 'fri' => 'Fri', 'sat' => 'Sat', 'sun' => 'Sun'];
$zones = ['Africa/Lagos', 'America/St_Johns', 'America/Halifax', 'America/Toronto', 'America/Winnipeg', 'America/Regina',
          'America/Edmonton', 'America/Vancouver', 'Europe/London', 'Europe/Dublin', 'Asia/Dubai', 'Asia/Kolkata',
          'Asia/Manila', 'Asia/Shanghai', 'Australia/Sydney'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Orientation | EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        :root { --sls: #0b77ff; --ink: #1f2937; --muted: #6b7280; --line: #e5e7eb; }
        .ori { max-width: 980px; margin: 0 auto; padding: 1.5rem 1rem 3rem; }
        .ori-progress { height: 6px; background: var(--line); border-radius: 6px; overflow: hidden; margin-bottom: 1.25rem; }
        .ori-progress > div { height: 100%; width: 0; background: var(--sls); transition: width .5s ease; }
        .ori-card { background: #fff; border: 1px solid var(--line); border-radius: 14px; padding: 1.5rem; }
        body.dark .ori-card { background: #111827; border-color: #374151; color: #e5e7eb; }
        .ori-step { display: none; }
        .ori-step.on { display: block; animation: ori-in .45s ease both; }
        @keyframes ori-in { from { opacity: 0; transform: translateX(24px); } to { opacity: 1; transform: none; } }
        .ori-step h2 { font-weight: 700; color: var(--ink); margin-bottom: .5rem; }
        body.dark .ori-step h2 { color: #f9fafb; }
        .ori-lead { color: var(--muted); font-size: 1.02rem; }
        .ori-shot { position: relative; border: 1px solid var(--line); border-radius: 10px; overflow: hidden; margin-top: 1rem; }
        .ori-shot img { width: 100%; display: block; animation: ori-zoom 6s ease-out both; transform-origin: 30% 40%; }
        @keyframes ori-zoom { from { transform: scale(1.06); } to { transform: scale(1); } }
        .ori-pin { position: absolute; transform: translate(-50%, -50%); opacity: 0; animation: ori-pin .4s ease forwards; }
        .ori-pin .dot { display: block; width: 22px; height: 22px; border-radius: 50%; background: var(--sls); border: 3px solid #fff;
                        box-shadow: 0 0 0 0 rgba(11,119,255,.6); animation: ori-pulse 1.8s ease-out infinite; }
        .ori-pin .tag { position: absolute; left: 50%; top: 28px; transform: translateX(-50%); white-space: nowrap; background: var(--ink);
                        color: #fff; font-size: .78rem; font-weight: 600; padding: .25rem .55rem; border-radius: 6px; }
        .ori-pin.up .tag { top: auto; bottom: 28px; }
        .ori-pin.right .tag { left: 30px; top: 50%; transform: translateY(-50%); }
        @keyframes ori-pin { to { opacity: 1; } }
        @keyframes ori-pulse { 70% { box-shadow: 0 0 0 14px rgba(11,119,255,0); } 100% { box-shadow: 0 0 0 0 rgba(11,119,255,0); } }
        .ori-tutors { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: .75rem; margin-top: 1rem; }
        .ori-tutor { border: 1px solid var(--line); border-radius: 12px; padding: 1rem; display: flex; gap: .8rem; align-items: center; }
        .ori-avatar { width: 46px; height: 46px; border-radius: 50%; background: var(--sls); color: #fff; display: flex; align-items: center;
                      justify-content: center; font-weight: 700; flex-shrink: 0; }
        .ori-nav { display: flex; justify-content: space-between; gap: .75rem; margin-top: 1.25rem; }
        .btn-sls { background: var(--sls); border-color: var(--sls); color: #fff; font-weight: 600; }
        .btn-sls:hover { background: #0962d6; border-color: #0962d6; color: #fff; }
        .ori-q { border: 1px solid var(--line); border-radius: 12px; padding: 1rem; margin-bottom: .75rem; }
        .ori-q.right { border-color: #86efac; background: #f0fdf4; }
        .ori-q.wrong { border-color: #fca5a5; background: #fef2f2; }
        body.dark .ori-q.right, body.dark .ori-q.wrong { background: transparent; }
        .ori-plan { border: 1px solid var(--line); border-radius: 12px; padding: 1.1rem; margin-bottom: 1rem; }
        .ori-days { display: flex; flex-wrap: wrap; gap: .4rem; }
        .ori-days input { display: none; }
        .ori-days label { border: 1px solid #cbd5e1; border-radius: 8px; padding: .35rem .7rem; cursor: pointer; user-select: none; }
        .ori-days input:checked + label { background: var(--sls); border-color: var(--sls); color: #fff; }
        .ori-pledge { background: #f8fafc; border: 1px solid var(--line); border-radius: 10px; padding: .9rem 1rem; font-size: .95rem; }
        body.dark .ori-pledge { background: #1f2937; border-color: #374151; }
        .ori-done svg { width: 84px; height: 84px; }
        .ori-done circle { stroke: #22c55e; stroke-width: 4; fill: none; stroke-dasharray: 240; stroke-dashoffset: 240; animation: ori-draw .8s ease forwards; }
        .ori-done path { stroke: #22c55e; stroke-width: 5; fill: none; stroke-linecap: round; stroke-dasharray: 60; stroke-dashoffset: 60; animation: ori-draw .5s .7s ease forwards; }
        @keyframes ori-draw { to { stroke-dashoffset: 0; } }
        @media (prefers-reduced-motion: reduce) { .ori-step.on, .ori-shot img, .ori-pin, .ori-pin .dot, .ori-done circle, .ori-done path { animation: none; opacity: 1; stroke-dashoffset: 0; } }
        @media (max-width: 640px) { .ori-pin .tag { display: none; } .ori-card { padding: 1rem; } }
    </style>
</head>
<body>
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="mobile-overlay" id="mobileOverlay"></div>
<?php include INCLUDES_PATH . '/navbar.php'; ?>

<main class="main-wrapper">
<div class="ori">
    <div class="ori-progress"><div id="oriBar"></div></div>
    <div class="ori-card">

        <!-- Welcome and tutors -->
        <section class="ori-step" data-step>
            <h2>Welcome, <?= $h($student['firstname']) ?></h2>
            <p class="ori-lead">This short orientation shows you how the platform works, checks you have it with a quick quiz, and ends with your class plan and commitment. It takes about ten minutes.</p>
            <?php if ($doneAt): ?>
                <p class="small text-muted mb-0"><i class="bi bi-check-circle me-1"></i>You finished it on <?= date('j M Y', strtotime($doneAt)) ?>. Going through it again lets you update your class plan.</p>
            <?php endif; ?>
            <?php if ($courses): ?>
                <h5 class="mt-4 mb-0" style="font-weight:700;">Meet your tutor</h5>
                <div class="ori-tutors">
                    <?php foreach ($courses as $c): ?>
                        <div class="ori-tutor">
                            <div class="ori-avatar"><?= $c['tutor'] ? $h(mb_strtoupper(mb_substr($c['tutor'], 0, 1))) : '<i class="bi bi-person"></i>' ?></div>
                            <div>
                                <div style="font-weight:700;"><?= $c['tutor'] ? $h($c['tutor']) : 'Your tutor will be named soon' ?></div>
                                <div class="small text-muted"><?= $h($c['title']) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <p class="small text-muted mt-3 mb-0">Your tutor sees the class plan you set at the end, so they know when to expect you.</p>
            <?php endif; ?>
        </section>

        <!-- The tour -->
        <?php foreach ($tour as [$shot, $title, $text, $pins]): ?>
        <section class="ori-step" data-step>
            <h2><?= $h($title) ?></h2>
            <p class="ori-lead mb-0"><?= $h($text) ?></p>
            <div class="ori-shot">
                <img src="<?= $h($img . $shot . '.jpg') ?>" alt="<?= $h($title) ?> on the platform" loading="lazy">
                <?php foreach ($pins as $i => $pin): [$x, $y, $label] = $pin; ?>
                    <span class="ori-pin <?= $h($pin[3] ?? 'down') ?>" style="left:<?= $x ?>%;top:<?= $y ?>%;animation-delay:<?= .6 + $i * .5 ?>s;"><span class="dot"></span><span class="tag"><?= $h($label) ?></span></span>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endforeach; ?>

        <!-- Quiz -->
        <section class="ori-step" data-step data-quiz>
            <h2>Quick check</h2>
            <p class="ori-lead">Six questions about what you have just seen. Get at least five right to continue.</p>
            <?php foreach ($quiz as $qi => [$q, $opts, $ans]): ?>
                <div class="ori-q" data-answer="<?= (int) $ans ?>">
                    <div style="font-weight:600;" class="mb-2"><?= ($qi + 1) . '. ' . $h($q) ?></div>
                    <?php foreach ($opts as $oi => $o): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="q<?= $qi ?>" id="q<?= $qi ?>o<?= $oi ?>" value="<?= $oi ?>">
                            <label class="form-check-label" for="q<?= $qi ?>o<?= $oi ?>"><?= $h($o) ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            <div id="quizMsg" class="small mt-2"></div>
        </section>

        <!-- Class plan and commitment -->
        <section class="ori-step" data-step data-plan>
            <h2>Your class plan and commitment</h2>
            <?php if (!$courses): ?>
                <p class="ori-lead">You are not enrolled in a course yet. When you join one, come back here to set your class plan.</p>
            <?php else: ?>
            <p class="ori-lead">Tell us how and when you take your classes, and what you are aiming for. Your tutor plans your classes from this.</p>
            <?php foreach ($courses as $c): $p = $plans[$c['id']] ?? []; $pDays = $p ? explode(',', $p['class_days']) : []; ?>
            <div class="ori-plan" data-course="<?= (int) $c['id'] ?>">
                <h5 style="font-weight:700;"><?= $h($c['title']) ?></h5>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">How do you take your classes?</label>
                        <div class="d-flex gap-3 flex-wrap">
                            <?php foreach (['one_to_one' => 'One-to-one with my tutor', 'group' => 'In a group'] as $v => $l): ?>
                                <div class="form-check"><input class="form-check-input" type="radio" name="fmt<?= (int) $c['id'] ?>" id="fmt<?= (int) $c['id'] . $v ?>" value="<?= $v ?>" data-f="class_format" <?= ($p['class_format'] ?? '') === $v ? 'checked' : '' ?>><label class="form-check-label" for="fmt<?= (int) $c['id'] . $v ?>"><?= $l ?></label></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Class days <span class="text-muted fw-normal small">(most courses run two classes a week)</span></label>
                        <div class="ori-days">
                            <?php foreach ($days as $v => $l): ?>
                                <input type="checkbox" id="d<?= (int) $c['id'] . $v ?>" value="<?= $v ?>" data-f="class_days" <?= in_array($v, $pDays, true) ? 'checked' : '' ?>><label for="d<?= (int) $c['id'] . $v ?>"><?= $l ?></label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-sm-4"><label class="form-label fw-semibold">Class time</label><input type="time" class="form-control" data-f="class_time" value="<?= $h(substr($p['class_time'] ?? '', 0, 5)) ?>"></div>
                    <div class="col-sm-5"><label class="form-label fw-semibold">Your time zone</label>
                        <select class="form-select" data-f="timezone" data-saved="<?= $h($p['timezone'] ?? '') ?>">
                            <?php foreach ($zones as $z): ?><option value="<?= $h($z) ?>"><?= $h(str_replace('_', ' ', $z)) ?></option><?php endforeach; ?>
                        </select></div>
                    <div class="col-sm-3"><label class="form-label fw-semibold">Hours per class</label><input type="number" class="form-control" data-f="class_hours" min="0.5" max="6" step="0.5" value="<?= $h($p['class_hours'] ?? '2') ?>"></div>
                    <div class="col-sm-6"><label class="form-label fw-semibold">First class</label><input type="date" class="form-control" data-f="start_date" value="<?= $h($p['start_date'] ?? '') ?>"></div>
                    <div class="col-sm-6"><label class="form-label fw-semibold">Exam date <span class="text-muted fw-normal small">(if booked)</span></label><input type="date" class="form-control" data-f="test_date" value="<?= $h($p['test_date'] ?? '') ?>"></div>
                    <div class="col-sm-6"><label class="form-label fw-semibold">The score I am aiming for</label><input type="text" class="form-control" data-f="target_score" maxlength="30" placeholder="e.g. CLB 9 or Band 7" value="<?= $h($p['target_score'] ?? '') ?>"></div>
                    <div class="col-sm-6"><label class="form-label fw-semibold">Hours I will study each week, outside class</label><input type="number" class="form-control" data-f="study_hours_week" min="0" max="80" step="0.5" value="<?= $h($p['study_hours_week'] ?? '') ?>"></div>
                    <div class="col-12">
                        <div class="ori-pledge" data-pledge></div>
                        <div class="row g-2 align-items-end mt-1">
                            <div class="col-sm-7"><label class="form-label fw-semibold mb-1">Type your full name to sign</label><input type="text" class="form-control" data-f="signed_name" maxlength="150" value="<?= $h($p['signed_name'] ?? '') ?>"></div>
                            <div class="col-sm-5"><div class="form-check"><input class="form-check-input" type="checkbox" data-f="agree" id="ag<?= (int) $c['id'] ?>" <?= $p ? 'checked' : '' ?>><label class="form-check-label" for="ag<?= (int) $c['id'] ?>">I commit to this plan</label></div></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
            <div id="planMsg" class="small text-danger"></div>
        </section>

        <!-- Done -->
        <section class="ori-step ori-done text-center" data-step data-done>
            <svg viewBox="0 0 84 84" aria-hidden="true"><circle cx="42" cy="42" r="38"/><path d="M26 43 l11 11 l21 -23"/></svg>
            <h2 class="mt-3">You are all set</h2>
            <p class="ori-lead">Your plan is saved and your tutor can see it. You can come back to this page any time to change it.</p>
            <a href="<?= $h(ACADEMY_URL . 'learning_dashboard.php') ?>" class="btn btn-sls px-4">Go to my dashboard</a>
        </section>

        <div class="ori-nav" id="oriNav">
            <button type="button" class="btn btn-outline-secondary" id="oriBack"><i class="bi bi-arrow-left me-1"></i>Back</button>
            <button type="button" class="btn btn-sls" id="oriNext">Next <i class="bi bi-arrow-right ms-1"></i></button>
        </div>
    </div>
</div>
</main>

<script>
(function () {
    const steps = [...document.querySelectorAll('[data-step]')];
    const bar = document.getElementById('oriBar'), back = document.getElementById('oriBack'), next = document.getElementById('oriNext');
    const fullName = <?= json_encode(trim($student['firstname'] . ' ' . $student['lastname'])) ?>;
    const dayNames = { mon: 'Monday', tue: 'Tuesday', wed: 'Wednesday', thu: 'Thursday', fri: 'Friday', sat: 'Saturday', sun: 'Sunday' };
    let at = 0, quizScore = null;

    // Time zone: the saved one, else this device's (added to the list if it isn't there).
    const here = Intl.DateTimeFormat().resolvedOptions().timeZone;
    document.querySelectorAll('[data-f="timezone"]').forEach(sel => {
        const want = sel.dataset.saved || here;
        if (want && ![...sel.options].some(o => o.value === want)) sel.add(new Option(want.replace(/_/g, ' '), want), 0);
        sel.value = want || sel.options[0].value;
    });

    function show(i) {
        at = i;
        steps.forEach((s, j) => s.classList.toggle('on', j === i));
        bar.style.width = Math.round(i / (steps.length - 1) * 100) + '%';
        back.style.visibility = i === 0 || steps[i].hasAttribute('data-done') ? 'hidden' : 'visible';
        document.getElementById('oriNav').style.display = steps[i].hasAttribute('data-done') ? 'none' : 'flex';
        next.innerHTML = steps[i].hasAttribute('data-quiz') ? 'Check my answers' : steps[i].hasAttribute('data-plan') ? 'Save my plan <i class="bi bi-check2 ms-1"></i>' : 'Next <i class="bi bi-arrow-right ms-1"></i>';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function checkQuiz() {
        const qs = [...steps[at].querySelectorAll('.ori-q')];
        let right = 0, unanswered = 0;
        qs.forEach((q, i) => {
            const picked = q.querySelector('input:checked');
            q.classList.remove('right', 'wrong');
            if (!picked) { unanswered++; return; }
            const ok = +picked.value === +q.dataset.answer;
            if (ok) right++;
            q.classList.add(ok ? 'right' : 'wrong');
        });
        const msg = document.getElementById('quizMsg');
        if (unanswered) { msg.className = 'small mt-2 text-danger'; msg.textContent = 'Answer every question first.'; return false; }
        if (right < 5) { msg.className = 'small mt-2 text-danger'; msg.textContent = right + ' of ' + qs.length + ' right. Look at the ones in red and try again.'; return false; }
        quizScore = right;
        return true;
    }

    function planOf(box) {
        const f = n => box.querySelector('[data-f="' + n + '"]');
        const fmt = box.querySelector('[data-f="class_format"]:checked');
        return {
            course_id: +box.dataset.course, class_format: fmt ? fmt.value : '',
            class_days: [...box.querySelectorAll('[data-f="class_days"]:checked')].map(c => c.value),
            class_time: f('class_time').value, timezone: f('timezone').value, class_hours: f('class_hours').value,
            start_date: f('start_date').value, test_date: f('test_date').value, target_score: f('target_score').value.trim(),
            study_hours_week: f('study_hours_week').value, signed_name: f('signed_name').value.trim(), agree: f('agree').checked,
        };
    }
    function pledge(box) {
        const p = planOf(box);
        const days = p.class_days.map(d => dayNames[d]);
        const dayText = days.length ? days.join(days.length > 2 ? ', ' : ' and ') : '[my class days]';
        box.querySelector('[data-pledge]').innerHTML =
            'I, <strong>' + (p.signed_name || fullName || '[your name]').replace(/</g, '&lt;') + '</strong>, will attend my ' +
            (p.class_format === 'group' ? 'group' : p.class_format === 'one_to_one' ? 'one-to-one' : '') + ' classes on <strong>' + dayText +
            '</strong>' + (p.class_time ? ' at <strong>' + p.class_time + '</strong>' : '') + ', study <strong>' + (p.study_hours_week || '[hours]') +
            ' hours</strong> a week outside class, and work towards <strong>' + (p.target_score || '[my target]').replace(/</g, '&lt;') + '</strong>.';
    }
    document.querySelectorAll('.ori-plan').forEach(box => { box.addEventListener('input', () => pledge(box)); box.addEventListener('change', () => pledge(box)); pledge(box); });

    async function savePlan() {
        const msg = document.getElementById('planMsg');
        const plans = [...document.querySelectorAll('.ori-plan')].map(planOf);
        const missing = plans.find(p => !p.class_format || !p.class_days.length || !p.class_time || !p.start_date || !p.target_score || p.study_hours_week === '' || !p.signed_name || !p.agree);
        if (missing) { msg.textContent = 'Please fill in every part of your plan, sign it and tick "I commit to this plan".'; return false; }
        next.disabled = true; msg.textContent = '';
        try {
            const res = await fetch('save.php', { method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ quiz: { score: quizScore, total: 6 }, plans }) });
            const data = await res.json();
            if (!data.success) { msg.textContent = data.error || 'Could not save. Please try again.'; return false; }
            return true;
        } catch (e) { msg.textContent = 'Could not save. Please check your connection and try again.'; return false; }
        finally { next.disabled = false; }
    }

    next.addEventListener('click', async () => {
        const s = steps[at];
        if (s.hasAttribute('data-quiz') && !checkQuiz()) return;
        if (s.hasAttribute('data-plan') && !(await savePlan())) return;
        if (at < steps.length - 1) show(at + 1);
    });
    back.addEventListener('click', () => { if (at > 0) show(at - 1); });
    show(0);
})();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
