<?php
/**
 * Week Brief -- one composed view of a course week (a `modules` row):
 * the classes and their focus, an optional summary, the vocab list, other
 * resources (exercises, readings, videos, notes) and the tests/quizzes due.
 *
 * Pure PDO + HTML, no constants, so both the student course pages
 * (academy/) and the sls-admin preview can include it. Everything is
 * optional: a week with nothing attached still composes/renders cleanly, so
 * the system works before any content is authored. Tests already attached
 * through course_pacing_items (the assignment template) are merged in
 * automatically -- authors don't enter them twice.
 *
 * weekBriefLoad()   -> array (data)
 * weekBriefRender() -> HTML card for a course page
 * weekBriefText()   -> plain text, for the future day-before message/email
 */

const WEEK_BRIEF_TEST_TYPES = ['practice_test', 'mock_test', 'quiz'];

const WEEK_BRIEF_TYPE_LABELS = [
    'exercise'      => 'Exercise',
    'reading'       => 'Reading',
    'video'         => 'Video',
    'practice_test' => 'Practice test',
    'mock_test'     => 'Mock test',
    'quiz'          => 'Quiz',
    'note'          => 'Note',
];

require_once __DIR__ . '/lesson_title.php';

function weekBriefLoad(PDO $db, int $moduleId): ?array {
    $stmt = $db->prepare("SELECT id, course_id, module_title, module_order FROM modules WHERE id = ?");
    $stmt->execute([$moduleId]);
    $module = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$module) return null;

    $stmt = $db->prepare("SELECT id, title, duration_minutes FROM lessons WHERE module_id = ? ORDER BY lesson_order");
    $stmt->execute([$moduleId]);
    $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->prepare("SELECT summary FROM week_briefs WHERE module_id = ?");
    $stmt->execute([$moduleId]);
    $summary = trim((string)$stmt->fetchColumn());

    $stmt = $db->prepare("
        SELECT w.id, w.headword, w.phonetic, w.word_class, w.definition, w.collocations, w.synonyms
        FROM week_vocab_words wv JOIN vocabulary_words w ON w.id = wv.word_id
        WHERE wv.module_id = ? ORDER BY wv.display_order, w.headword
    ");
    $stmt->execute([$moduleId]);
    $vocab = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->prepare("SELECT * FROM week_resources WHERE module_id = ? ORDER BY display_order, id");
    $stmt->execute([$moduleId]);
    $resources = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $tests = [];
    $others = [];
    foreach ($resources as $r) {
        if (in_array($r['item_type'], WEEK_BRIEF_TEST_TYPES, true)) $tests[] = $r + ['source' => 'week'];
        else $others[] = $r;
    }

    // Tests already scheduled through the assignment template for this
    // week's lessons (offset_days from enrollment) count as this week's tests.
    if ($lessons) {
        $ids = array_column($lessons, 'id');
        $in = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $db->prepare("SELECT item_type, title, test_code, offset_days FROM course_pacing_items WHERE lesson_id IN ($in) ORDER BY display_order");
        $stmt->execute($ids);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $p) {
            $tests[] = ['item_type' => $p['item_type'], 'title' => $p['title'], 'test_code' => $p['test_code'],
                        'url' => null, 'status' => 'ready', 'source' => 'pacing'];
        }
    }

    return [
        'module'    => $module,
        'lessons'   => $lessons,
        'summary'   => $summary,
        'vocab'     => $vocab,
        'resources' => $others,
        'tests'     => $tests,
        'tests_message' => weekBriefTestsMessage(count($tests)),
    ];
}

function weekBriefTestsMessage(int $n): string {
    if ($n === 0) return "Rest easy — you have no tests this week.";
    if ($n <= 2)  return "A light week for tests — just $n to get through.";
    return "Buckle up — it's looking like a rainy week of tests and quizzes ($n on the list).";
}

function weekBriefHasContent(array $b): bool {
    return $b['summary'] !== '' || $b['vocab'] || $b['resources'] || $b['tests'];
}

function weekBriefRender(array $b, string $color = '#0b77ff'): string {
    $h = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
    $out = '<div class="week-brief" style="border-left:4px solid ' . $h($color) . ';background:#f8fafc;padding:1rem 1.1rem;margin:0;">';
    $out .= '<div class="fw-bold mb-2" style="color:' . $h($color) . ';"><i class="bi bi-journal-text me-1"></i>This week at a glance</div>';

    if ($b['summary'] !== '') {
        $out .= '<p class="mb-3" style="white-space:pre-line;">' . $h($b['summary']) . '</p>';
    }

    // Tests: always shown -- the "rest easy / buckle up" line is the point.
    $out .= '<div class="mb-3"><div class="fw-semibold small text-uppercase text-muted mb-1">Tests &amp; quizzes</div>';
    $out .= '<div class="mb-1">' . $h($b['tests_message']) . '</div>';
    if ($b['tests']) {
        $out .= '<ul class="mb-0 ps-3">';
        foreach ($b['tests'] as $t) {
            $label = WEEK_BRIEF_TYPE_LABELS[$t['item_type']] ?? 'Test';
            $out .= '<li>' . $h($t['title']) . ' <span class="text-muted small">(' . $h($label) . ')</span></li>';
        }
        $out .= '</ul>';
    }
    $out .= '</div>';

    // Vocab
    $out .= '<div class="mb-3"><div class="fw-semibold small text-uppercase text-muted mb-1">Vocabulary</div>';
    if ($b['vocab']) {
        $out .= '<div class="mb-2 small text-muted">Try to use at least a few of these words in this week\'s assignments and tests.</div>';
        $out .= '<div class="d-flex flex-wrap gap-2">';
        foreach ($b['vocab'] as $w) {
            $tip = trim($w['word_class'] . ($w['definition'] ? ' — ' . $w['definition'] : ''));
            $out .= '<span class="badge rounded-pill text-bg-light border" style="font-weight:600;" title="' . $h($tip) . '">' . $h($w['headword']) . '</span>';
        }
        $out .= '</div>';
    } else {
        $out .= '<div class="text-muted small">No vocabulary list for this week yet.</div>';
    }
    $out .= '</div>';

    // Other resources
    $out .= '<div><div class="fw-semibold small text-uppercase text-muted mb-1">Resources &amp; exercises</div>';
    if ($b['resources']) {
        $out .= '<ul class="mb-0 ps-3">';
        foreach ($b['resources'] as $r) {
            $label = WEEK_BRIEF_TYPE_LABELS[$r['item_type']] ?? '';
            $title = $h($r['title']);
            if ($r['status'] === 'ready' && !empty($r['url'])) $title = '<a href="' . $h($r['url']) . '">' . $title . '</a>';
            $soon = $r['status'] === 'planned' ? ' <span class="badge text-bg-secondary">Coming soon</span>' : '';
            $out .= '<li>' . $title . ' <span class="text-muted small">(' . $h($label) . ')</span>' . $soon . '</li>';
        }
        $out .= '</ul>';
    } else {
        $out .= '<div class="text-muted small">Nothing added for this week yet.</div>';
    }
    $out .= '</div></div>';
    return $out;
}

function weekBriefText(array $b): string {
    $t = $b['module']['module_title'] . "\n\n";
    if ($b['lessons']) {
        $t .= "Classes:\n";
        foreach ($b['lessons'] as $l) foreach (lesson_title_lines($l['title']) as $line) $t .= ' - ' . $line . "\n";
        $t .= "\n";
    }
    if ($b['summary'] !== '') $t .= $b['summary'] . "\n\n";
    $t .= $b['tests_message'] . "\n";
    foreach ($b['tests'] as $x) $t .= ' - ' . $x['title'] . ' (' . (WEEK_BRIEF_TYPE_LABELS[$x['item_type']] ?? 'Test') . ")\n";
    if ($b['vocab']) {
        $t .= "\nVocabulary (use a few in this week's work):\n";
        foreach ($b['vocab'] as $w) $t .= ' - ' . $w['headword'] . ($w['definition'] ? ': ' . $w['definition'] : '') . "\n";
    }
    if ($b['resources']) {
        $t .= "\nResources & exercises:\n";
        foreach ($b['resources'] as $r) $t .= ' - ' . $r['title'] . ($r['status'] === 'planned' ? ' (coming soon)' : '') . "\n";
    }
    return $t;
}


/* ─────────────────────────────────────────────────────────────────────────
 * Site-wide week navigation (shared by every course overview + week.php)
 * ───────────────────────────────────────────────────────────────────────── */

/** All weeks (modules) of a course, in order, with lesson counts. Cached per request. */
function weekModules(PDO $db, int $courseId): array {
    static $cache = [];
    if (!isset($cache[$courseId])) {
        $stmt = $db->prepare("
            SELECT m.id, m.module_title, m.module_order,
                   (SELECT COUNT(*) FROM lessons l WHERE l.module_id = m.id) AS lesson_count
            FROM modules m WHERE m.course_id = ? ORDER BY m.module_order, m.id
        ");
        $stmt->execute([$courseId]);
        $cache[$courseId] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return $cache[$courseId];
}

function weekIntroUrl(int $moduleId, string $anchor = ''): string {
    return ACADEMY_URL . 'courses/week.php?module=' . $moduleId . ($anchor !== '' ? '#' . $anchor : '');
}

/** module_order -> module id for a course (the overview pages key their loops by module_order). */
function weekModuleIdByOrder(PDO $db, int $courseId, int $order): ?int {
    foreach (weekModules($db, $courseId) as $m) if ((int)$m['module_order'] === $order) return (int)$m['id'];
    return null;
}

/** The week a student is "in": first module with a lesson not yet completed; the last one when all done. */
function weekCurrentModuleId(PDO $db, int $courseId, int $studentId): ?int {
    $mods = weekModules($db, $courseId);
    if (!$mods) return null;
    $stmt = $db->prepare("
        SELECT l.module_id, l.id FROM lessons l WHERE l.course_id = ? ORDER BY l.module_id, l.lesson_order
    ");
    $stmt->execute([$courseId]);
    $byModule = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) $byModule[(int)$r['module_id']][] = (int)$r['id'];
    $done = [];
    $stmt = $db->prepare("SELECT lesson_id FROM lesson_progress WHERE student_id = ? AND completed = 1");
    $stmt->execute([$studentId]);
    foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $id) $done[(int)$id] = true;
    foreach ($mods as $m) {
        foreach ($byModule[(int)$m['id']] ?? [] as $lid) {
            if (empty($done[$lid])) return (int)$m['id'];
        }
    }
    return (int)end($mods)['id'];
}

/**
 * Hook for the planned "weeks unlock once the previous week is complete" rule.
 * Not enforced yet -- every week is open. When it is, implement it HERE (week.php
 * already calls it) and the panel/accordion can grey out locked weeks.
 */
function weekIsUnlocked(PDO $db, int $courseId, int $studentId, int $moduleId): bool {
    return true;
}

function weekBriefButton(PDO $db, int $courseId, int $moduleOrder, string $color = ''): string {
    $id = weekModuleIdByOrder($db, $courseId, $moduleOrder);
    if (!$id) return '';
    return '<div class="px-3 py-2 border-bottom small"><a href="' . htmlspecialchars(weekIntroUrl($id)) . '">Week introduction &rarr;</a>'
         . ' <span class="text-muted">— summary, vocabulary, resources &amp; tests</span></div>';
}

/**
 * Right-pane panel: the week the student is in (or $moduleId when given), abridged,
 * with a link under each line pointing at the matching section of the full week page.
 * Replaces the old static per-course "Quick Access" box -- it still lists the week's
 * class links, so nothing was lost.
 */
function renderWeekPanel(PDO $db, int $courseId, int $studentId, ?int $moduleId = null, string $courseFolder = ''): string {
    $h = fn($x) => htmlspecialchars((string)$x, ENT_QUOTES, 'UTF-8');
    $moduleId = $moduleId ?: weekCurrentModuleId($db, $courseId, $studentId);
    if (!$moduleId) return '';
    $b = weekBriefLoad($db, $moduleId);
    if (!$b) return '';

    $mods = weekModules($db, $courseId);
    $pos = 1; foreach ($mods as $i => $m) if ((int)$m['id'] === $moduleId) { $pos = $i + 1; break; }

    $stmt = $db->prepare("SELECT lesson_id FROM lesson_progress WHERE student_id = ? AND completed = 1");
    $stmt->execute([$studentId]);
    $done = array_flip(array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN)));
    $tier = function_exists('get_student_tier_level') ? get_student_tier_level() : 4;

    $stmt = $db->prepare("SELECT id, title, file_path, min_tier FROM lessons WHERE module_id = ? ORDER BY lesson_order");
    $stmt->execute([$moduleId]);
    $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Flat, quiet: white box, hairline border, plain text with a link under each line.
    $sec = 'font-size:.7rem;letter-spacing:.06em;text-transform:uppercase;color:#5b6673;margin:.8rem 0 .2rem;';
    $a   = 'font-size:.78rem;';
    $o  = '<div class="week-panel" style="background:#fff;border:1px solid #d7dce0;border-top:3px solid #1e3a5f;padding:.8rem .9rem;color:#1f2937;font-size:.84rem;line-height:1.4;">';
    $o .= '<div style="font-size:.7rem;letter-spacing:.06em;text-transform:uppercase;color:#5b6673;">This week &middot; ' . $pos . ' of ' . count($mods) . '</div>';
    $o .= '<div style="font-weight:700;font-size:.95rem;margin:.15rem 0 .4rem;">' . $h($b['module']['module_title']) . '</div>';

    // Classes
    if (!$lessons) $o .= '<div style="color:#5b6673;">Classes for this week are still being prepared.</div>';
    foreach ($lessons as $l) {
        $need = ['beginner'=>1,'intermediate'=>2,'advanced'=>3,'fluent'=>4][$l['min_tier']] ?? 1;
        $locked = $tier < $need;
        $fp = (string)$l['file_path'];
        if ($locked) $href = ACADEMY_URL . 'upgrade.php?required=' . urlencode($l['min_tier']);
        elseif ($fp === '') $href = weekIntroUrl($moduleId, 'classes');
        else $href = ACADEMY_URL . $fp . (str_contains($fp, '?') ? '&' : '?') . 'from=' . urlencode($courseFolder);
        $mark = isset($done[(int)$l['id']]) ? '&#10003; ' : ($locked ? '&#128274; ' : '');
        $o .= '<div style="padding:.25rem 0;border-bottom:1px solid #eef1f4;"><a href="' . $h($href) . '" style="text-decoration:none;">' . $mark . lesson_title_html($l['title'], $h) . '</a></div>';
    }

    if ($b['summary'] !== '') {
        $short = mb_strlen($b['summary']) > 100 ? mb_substr($b['summary'], 0, 97) . '…' : $b['summary'];
        $o .= '<div style="' . $sec . '">Summary</div><div>' . $h($short) . '</div><a href="' . $h(weekIntroUrl($moduleId, 'summary')) . '" style="' . $a . '">Read the summary</a>';
    }
    $o .= '<div style="' . $sec . '">Tests</div><div>' . $h($b['tests_message']) . '</div><a href="' . $h(weekIntroUrl($moduleId, 'tests')) . '" style="' . $a . '">See tests &amp; quizzes</a>';
    if ($b['vocab']) {
        $words = array_slice(array_column($b['vocab'], 'headword'), 0, 5);
        $o .= '<div style="' . $sec . '">Vocabulary</div><div>' . count($b['vocab']) . ' words: ' . $h(implode(', ', $words)) . (count($b['vocab']) > 5 ? '…' : '') . '</div>'
            . '<a href="' . $h(weekIntroUrl($moduleId, 'vocab')) . '" style="' . $a . '">Open the vocabulary sheet</a>';
    }
    if ($b['resources']) {
        $o .= '<div style="' . $sec . '">Resources</div><div>' . count($b['resources']) . ' item' . (count($b['resources']) === 1 ? '' : 's') . '</div>'
            . '<a href="' . $h(weekIntroUrl($moduleId, 'resources')) . '" style="' . $a . '">See resources</a>';
    }
    $o .= '<div style="margin-top:.9rem;padding-top:.6rem;border-top:1px solid #d7dce0;"><a href="' . $h(weekIntroUrl($moduleId)) . '" style="font-weight:600;">Full week introduction &rarr;</a></div>';
    $o .= '</div>';
    return $o;
}
