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

/**
 * Sequential completion gate: a student may not open lesson $lessonId until
 * every course_pacing_items entry attached to an EARLIER lesson in the same
 * course is completed. Checks ALL earlier lessons (not just the immediately
 * preceding one) so a direct link to a later class can't skip the gate.
 *
 * Returns the titles of whatever's still incomplete — an empty array means
 * nothing is blocking, the student may proceed. Fails OPEN (returns [], i.e.
 * does not block) if the lesson's position can't be determined or it has no
 * pacing template at all — a data/config problem should never lock a
 * student out of content entirely.
 */
function getIncompletePriorPacingItems(PDO $db, int $studentId, int $courseId, int $lessonId): array {
    $posStmt = $db->prepare("
        SELECT m.module_order, l.lesson_order
        FROM lessons l JOIN modules m ON m.id = l.module_id
        WHERE l.id = ? AND m.course_id = ?
    ");
    $posStmt->execute([$lessonId, $courseId]);
    $pos = $posStmt->fetch(PDO::FETCH_ASSOC);
    if (!$pos) return [];

    $itemsStmt = $db->prepare("
        SELECT cpi.title, cpi.test_code
        FROM course_pacing_items cpi
        JOIN lessons l ON l.id = cpi.lesson_id
        JOIN modules m ON m.id = l.module_id
        WHERE cpi.course_id = ?
          AND (m.module_order < ? OR (m.module_order = ? AND l.lesson_order < ?))
        ORDER BY m.module_order, l.lesson_order, cpi.display_order
    ");
    $itemsStmt->execute([$courseId, $pos['module_order'], $pos['module_order'], $pos['lesson_order']]);
    $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$items) return [];

    $testLookup = $db->prepare("SELECT id FROM tests WHERE code = ? LIMIT 1");
    $doneLookup = $db->prepare("SELECT COUNT(*) FROM test_attempts WHERE student_id = ? AND test_id = ? AND status = 'completed'");

    $incomplete = [];
    foreach ($items as $item) {
        $testLookup->execute([$item['test_code']]);
        $testId = $testLookup->fetchColumn();
        if (!$testId) continue; // content not seeded yet — can't be completed, so don't block on it either

        $doneLookup->execute([$studentId, $testId]);
        if ((int)$doneLookup->fetchColumn() === 0) {
            $incomplete[] = $item['title'];
        }
    }
    return $incomplete;
}

/**
 * Renders the "finish earlier tasks first" lock card — same visual language
 * as tier_access.php's render_upgrade_prompt(), for a consistent feel
 * between the two gates a lesson page can show.
 */
function render_pacing_gate(array $incompleteTitles, string $scheduleUrl): void {
    ?>
    <div style="
        background: #f8fafc;
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 2.5rem 2rem;
        text-align: center;
        margin: 1.5rem 0;
        max-width: 560px;
    ">
        <div style="font-size: 2.5rem; margin-bottom: 1rem;">📋</div>
        <h3 style="font-weight: 700; margin-bottom: .5rem;">Finish earlier tasks first</h3>
        <p style="color: #64748b; margin-bottom: 1.25rem;">
            Complete <?= count($incompleteTitles) === 1 ? 'this item' : 'these ' . count($incompleteTitles) . ' items' ?> from earlier classes before moving on:
        </p>
        <ul style="text-align: left; display: inline-block; color: #334155; font-size: .92rem; margin-bottom: 1.5rem;">
            <?php foreach ($incompleteTitles as $title): ?>
            <li><?= htmlspecialchars($title) ?></li>
            <?php endforeach; ?>
        </ul>
        <div>
            <a href="<?= htmlspecialchars($scheduleUrl) ?>" class="btn btn-primary">
                <i class="bi bi-calendar-check me-1"></i>Go to Your Schedule
            </a>
        </div>
    </div>
    <?php
}
