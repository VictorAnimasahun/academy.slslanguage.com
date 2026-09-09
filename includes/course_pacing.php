<?php
/**
 * Enrollment-anchored course pacing. See migration 072 for the schema
 * rationale: due dates are computed per-student from THEIR OWN enrollment
 * date, not a shared fixed calendar, per instructor direction ("it should
 * start when a student registers for the course... every course should
 * work like that").
 *
 * course_pacing_items is the course-level template (course_id, test_code,
 * offset_days from enrollment); this function materializes it into
 * personalized `assignments` rows the moment a student enrolls.
 */

/**
 * Create this student's personalized assignment rows for a course, based on
 * that course's course_pacing_items template. Call once, right after the
 * INSERT into `enrollments` — never breaks/throws on missing content, since
 * a course's pacing template can reference test content that isn't seeded
 * yet (it's simply skipped, not fatal to enrollment).
 */
function generateAssignmentsForEnrollment(PDO $db, int $studentId, int $courseId, ?string $enrolledAt = null): void {
    $enrolledAt = $enrolledAt ?: date('Y-m-d H:i:s');

    $stmt = $db->prepare("SELECT * FROM course_pacing_items WHERE course_id = ? ORDER BY display_order");
    $stmt->execute([$courseId]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$items) return;

    // Never double-generate if this is somehow called twice for the same enrollment.
    $already = $db->prepare("SELECT COUNT(*) FROM assignments WHERE course_id = ? AND student_id = ?");
    $already->execute([$courseId, $studentId]);
    if ((int)$already->fetchColumn() > 0) return;

    $testLookup = $db->prepare("SELECT id FROM tests WHERE code = ? AND is_active = 1 LIMIT 1");
    $insert = $db->prepare("INSERT INTO assignments (course_id, student_id, test_id, type, title, due_date) VALUES (?, ?, ?, ?, ?, ?)");

    foreach ($items as $item) {
        $testLookup->execute([$item['test_code']]);
        $testId = $testLookup->fetchColumn();
        if (!$testId) continue; // content not seeded yet — skip, don't fail enrollment

        $dueDate = date('Y-m-d', strtotime($enrolledAt . ' +' . (int)$item['offset_days'] . ' days'));
        $type = $item['item_type'] === 'quiz' ? 'quiz' : 'test';

        $insert->execute([$courseId, $studentId, $testId, $type, $item['title'], $dueDate]);
    }
}
