<?php
/**
 * Shared between assignments.php (cross-course view) and
 * courses/course_schedule.php (single-course view) — kept in one place so
 * the test_code -> launch URL mapping is never hand-maintained twice.
 */

$SECTION_FILE_MAP = ['L' => 'listening', 'R' => 'reading', 'S' => 'speaking', 'W1' => 'writing_t1', 'W2' => 'writing_t2'];

/**
 * Derive the launch URL (relative to the academy root) for an assignment row.
 * $a needs: test_id, test_code, vocab_word_id (from the vocabulary_words LEFT JOIN).
 */
function assignmentUrl(array $a, array $sectionFileMap): ?string {
    if (empty($a['test_id'])) return null;
    $code = $a['test_code'] ?? '';

    if (!empty($a['vocab_word_id'])) {
        return 'resources/vocabulary_banks/word_quiz.php?word_id=' . $a['vocab_word_id'];
    }

    if (preg_match('/^IELTS_FULL_MOCK_\d+$/', $code)) {
        return 'resources/mock_tests/take.php?code=' . urlencode($code);
    }

    // Course-pacing class quizzes (see migration 072 / course_pacing_items),
    // e.g. IELTS_GM_C3_QUIZ — any course's class-quiz codes follow this
    // "ends in _QUIZ" convention so future courses need no new branch here.
    if (preg_match('/_QUIZ$/', $code)) {
        return 'resources/quizzes/class_quiz.php?test_code=' . urlencode($code);
    }

    if (preg_match('/^([A-Z]+)_PT_(L|R|S|W1|W2)_(\d{3})$/', $code, $m) && isset($sectionFileMap[$m[2]])) {
        $relPath = 'resources/practice_tests/' . strtolower($m[1]) . '_' . $sectionFileMap[$m[2]] . '_' . $m[3] . '.php';
        if (file_exists(ACADEMY_ROOT . '/' . $relPath)) return $relPath;
    }

    return null;
}

function typeBadge(string $type): string {
    $map = [
        'test'       => ['bg:#dbeafe;color:#1d4ed8', 'bi-journal-check',   'Test'],
        'quiz'       => ['bg:#ede9fe;color:#6d28d9', 'bi-patch-question',  'Quiz'],
        'vocabulary' => ['bg:#dcfce7;color:#15803d', 'bi-alphabet',        'Vocabulary'],
        'task'       => ['bg:#f3f4f6;color:#4b5563', 'bi-check2-square',   'Task'],
    ];
    [$style, $icon, $label] = $map[$type] ?? $map['task'];
    return "<span style='display:inline-flex;align-items:center;gap:.3rem;padding:.2rem .6rem;border-radius:999px;font-size:.7rem;font-weight:700;{$style}'><i class='bi {$icon}'></i>{$label}</span>";
}

/**
 * Space-separated data-filter tags for one assignment row: status
 * (pending/completed/overdue) plus due-window tags (week/month) used by
 * both the Assignments page's quick-filter tiles and, potentially, any
 * future view that wants the same "what's due soon" grouping.
 */
function assignmentFilterTags(array $a, string $today): array {
    $completed = $a['attempt_status'] === 'completed';
    $overdue   = !$completed && !empty($a['due_date']) && $a['due_date'] < $today;
    $tags      = [$completed ? 'completed' : ($overdue ? 'overdue' : 'pending')];

    if (!$completed && !empty($a['due_date']) && $a['due_date'] >= $today) {
        if ($a['due_date'] <= date('Y-m-d', strtotime($today . ' +7 days')))  $tags[] = 'week';
        if ($a['due_date'] <= date('Y-m-d', strtotime($today . ' +30 days'))) $tags[] = 'month';
    }

    return $tags;
}
