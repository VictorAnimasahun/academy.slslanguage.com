<?php
/**
 * Practice submissions that used to vanish (founder, 2026-10-21: "If the AI analyzer provides a score, add it to the
 * student's data. If the tutor is the grader, link that grade too."). Writing and speaking practice tests, the CELPIP
 * Diagnostic Test and the IELTS Listening formats quiz were scored or analysed on screen and never saved, so the
 * platform didn't know the student had done them and their class could never count as done.
 *
 * Every submission is now an ordinary completed test_attempts row (migration 162 adds the columns used here):
 *   response_text  the essay / speaking transcript / diagnostic writing, as submitted
 *   ai_feedback    the AI examiner's feedback, stored by the server (never trusted from the page)
 *   graded_by      'ai' or 'tutor' -- a tutor's grade replaces the AI's and is never overwritten by it
 *   tutor_notes, graded_at
 * band_score holds the grade the student sees (IELTS band, or CELPIP CLB level).
 *
 *   practice_attempt_save($db, $studentId, 'IELTS_PT_W1_ACA_001', $essay, [...])  -> attempt id (or null: unknown test)
 *   practice_attempt_ai($db, $studentId, $attemptId, $feedback, $band)            stores the AI result
 *   practice_attempt_tutor($db, $attemptId, $band, $notes)                         stores a tutor's grade
 *   practice_band_from_feedback($feedback, 'IELTS'|'CELPIP')                      'Overall Band Score: 6.5' -> 6.5
 */

if (!function_exists('practice_attempt_save')) {
    /**
     * Records a finished practice submission. Re-submitting the same response (a page refresh) returns the attempt
     * already saved instead of adding another.
     * $extra: score, max_score, band_score, time_spent (seconds), mode ('practice' by default)
     */
    function practice_attempt_save(PDO $db, int $studentId, string $testCode, ?string $response, array $extra = []): ?int {
        $st = $db->prepare("SELECT id FROM tests WHERE code = ? AND is_active = 1 LIMIT 1");
        $st->execute([$testCode]);
        $testId = (int) $st->fetchColumn();
        if (!$testId) return null;

        $st = $db->prepare("SELECT id, response_text FROM test_attempts WHERE student_id = ? AND test_id = ? ORDER BY id DESC LIMIT 1");
        $st->execute([$studentId, $testId]);
        $last = $st->fetch(PDO::FETCH_ASSOC);
        if ($last && $response !== null && $response !== '' && (string) $last['response_text'] === $response) {
            return (int) $last['id'];
        }

        $st = $db->prepare("SELECT COUNT(*) FROM test_attempts WHERE student_id = ? AND test_id = ?");
        $st->execute([$studentId, $testId]);
        $attemptNumber = (int) $st->fetchColumn() + 1;

        $timeSpent = max(0, (int) ($extra['time_spent'] ?? 0));
        $db->prepare(
            "INSERT INTO test_attempts
                (student_id, test_id, attempt_number, mode, started_at, completed_at, score, max_score, band_score, time_spent, status, response_text)
             VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, 'completed', ?)"
        )->execute([
            $studentId, $testId, $attemptNumber, $extra['mode'] ?? 'practice',
            date('Y-m-d H:i:s', time() - $timeSpent),
            $extra['score'] ?? null, $extra['max_score'] ?? null, $extra['band_score'] ?? null,
            $timeSpent, $response,
        ]);
        return (int) $db->lastInsertId();
    }

    /** Stores the AI examiner's result on the student's own attempt. A tutor's grade, once given, stays. */
    function practice_attempt_ai(PDO $db, int $studentId, int $attemptId, string $feedback, ?float $band): void {
        $db->prepare(
            "UPDATE test_attempts
                SET ai_feedback = ?,
                    band_score = IF(graded_by = 'tutor', band_score, ?),
                    graded_by = IF(graded_by = 'tutor', 'tutor', IF(? IS NULL, graded_by, 'ai'))
              WHERE id = ? AND student_id = ?"
        )->execute([$feedback, $band, $band, $attemptId, $studentId]);
    }

    /** A tutor's grade: becomes the grade the student sees. */
    function practice_attempt_tutor(PDO $db, int $attemptId, ?float $band, ?string $notes): void {
        $db->prepare("UPDATE test_attempts SET band_score = ?, tutor_notes = ?, graded_by = 'tutor', graded_at = NOW() WHERE id = ?")
           ->execute([$band, $notes, $attemptId]);
    }

    /** The overall score the AI rubric puts on its first line; null when there isn't one (or CELPIP 'M'). */
    function practice_band_from_feedback(string $feedback, string $exam): ?float {
        $pattern = strtoupper($exam) === 'CELPIP'
            ? '/overall\s*clb\s*level[:\s]*\(?(M|\d+)\)?/i'
            : '/overall\s*band\s*score[:\s]*\(?(\d+(?:\.\d+)?)\)?/i';
        if (!preg_match($pattern, $feedback, $m) || strtoupper($m[1]) === 'M') return null;
        return (float) $m[1];
    }
}
