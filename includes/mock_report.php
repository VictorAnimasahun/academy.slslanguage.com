<?php
/**
 * Raw saved fields for one RELEASED mock session, handed to
 * assets/js/mock_report_pdf.js, which decides what the student is shown and
 * builds the PDF. Keeping that logic in the one JS file means the tutor's
 * "Preview what the student receives" panel in sls-admin (which loads the same
 * file) can't show something different from what a student downloads.
 */

if (!function_exists('mock_report_raw')) {

    /**
     * True when a tutor set this attempt's total by hand AND it no longer equals what the question
     * marks add up to -- the student is then told, so the page never looks like an arithmetic error.
     * Quietly false if the score_corrections table doesn't exist yet (migration 125 not run).
     */
    function attempt_total_adjusted(PDO $db, ?int $attemptId, float $attemptScore, array $answerRows): bool {
        if (!$attemptId) return false;
        $sum = 0.0;
        foreach ($answerRows as $r) $sum += (float)($r['score_awarded'] ?? 0);
        if (abs($sum - $attemptScore) < 0.001) return false;
        try {
            $st = $db->prepare("SELECT 1 FROM score_corrections WHERE attempt_id = ? AND kind = 'total' LIMIT 1");
            $st->execute([$attemptId]);
            return (bool)$st->fetchColumn();
        } catch (\Throwable $e) { return false; }
    }

    /** CELPIP mock? (scored in whole CLB levels, not IELTS bands) */
    function mock_is_celpip(array $row): bool {
        return str_starts_with((string)($row['mock_test_type'] ?? ''), 'CELPIP');
    }

    /**
     * Score as a student should see it. IELTS: one decimal ("7.5"). CELPIP levels are whole numbers,
     * and the overall is only an estimate kept to the nearest half, so 8.5 is shown as "8-9".
     * (Same rule as fmtCelpip() in assets/js/mock_report_pdf.js.)
     */
    function mock_fmt_score($v, bool $isCelpip, bool $isOverall = false): string {
        if ($v === null || $v === '') return $isCelpip ? '-' : '–';
        $n = (float)$v;
        if (!$isCelpip) return number_format($n, 1);
        if ($isOverall && abs($n - floor($n) - 0.5) < 0.001) return (int)floor($n) . '-' . (int)ceil($n);
        return (string)(int)round($n);
    }

    /** True when saved AI writing feedback is really a failure placeholder, not a critique. */
    function mock_ai_feedback_failed(?string $text): bool {
        return (bool)preg_match('/\[AI GRADING FAILED\]|AI grading temporarily unavailable|Could not parse AI response/i', (string)$text);
    }

    /** Latest recording per speaking task for a mock session (a re-record replaces the earlier one). */
    function mock_report_speaking_tasks(PDO $db, int $sessionId): array {
        $st = $db->prepare("
            SELECT sr.task_number, sr.task_title, sr.manual_score, sr.manual_analysis
            FROM speaking_recordings sr
            INNER JOIN (
                SELECT task_number, MAX(id) AS max_id
                FROM speaking_recordings
                WHERE mock_session_id = ?
                GROUP BY task_number
            ) latest ON latest.max_id = sr.id
            ORDER BY sr.task_number
        ");
        $st->execute([$sessionId]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param array $ms  a mock_sessions row (ms.*) joined with: mock_title, mock_test_type,
     *                   l_band, l_score, l_max, r_band, r_score, r_max
     */
    function mock_report_raw(PDO $db, array $ms, string $studentName): array {
        $isCelpip = str_starts_with((string)($ms['mock_test_type'] ?? ''), 'CELPIP');
        $stamp    = $ms['released_at'] ?? null;
        $tasks    = [];
        foreach (mock_report_speaking_tasks($db, (int)$ms['id']) as $t) {
            $tasks[] = ['n' => (int)$t['task_number'], 'title' => (string)$t['task_title'],
                        'score' => (string)$t['manual_score'], 'analysis' => (string)$t['manual_analysis']];
        }
        return [
            'title'          => (string)$ms['mock_title'],
            'name'           => $studentName,
            'date'           => $stamp ? date('d M Y', strtotime($stamp)) : date('d M Y'),
            'label'          => $isCelpip ? 'CLB Level' : 'Band',
            'l'              => $ms['l_band'],
            'r'              => $ms['r_band'],
            'w'              => $ms['writing_band'],
            's'              => $ms['speaking_band'],
            'overall'        => $ms['overall_band'],
            'l_score'        => (int)$ms['l_score'] . '/' . (int)$ms['l_max'],
            'r_score'        => (int)$ms['r_score'] . '/' . (int)$ms['r_max'],
            'writing_by'     => ($ms['writing_graded_by'] ?? 'ai') === 'instructor' ? 'instructor' : 'ai',
            'writing_notes'  => (string)($ms['writing_notes'] ?? ''),
            'writing_ai'     => (string)($ms['writing_ai_feedback'] ?? ''),
            'speaking_notes' => (string)($ms['speaking_notes'] ?? ''),
            'tasks'          => $tasks,
        ];
    }
}
