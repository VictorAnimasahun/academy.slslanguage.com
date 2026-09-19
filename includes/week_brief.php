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
        SELECT w.id, w.headword, w.phonetic, w.word_class, w.definition
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
        foreach ($b['lessons'] as $l) $t .= ' - ' . $l['title'] . "\n";
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
