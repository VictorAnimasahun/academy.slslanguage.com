<?php
/**
 * "Mark complete" for lessons on the website (founder, 2026-10-21). Until now a lesson item could only be marked
 * complete in the app (api/v1/lesson_complete.php), so on the website a class with a lesson never counted as done.
 *
 * Included once by includes/navbar_scripts.php, i.e. on every page. It does something only when this page is a
 * LESSON item of a course the logged-in student is enrolled in: it records the first opening (the 5-minute clock in
 * includes/item_progress.php) and shows a small bar that counts down, then marks the lesson complete through
 * api/lesson_complete_web.php. Same rules as the app: lessons only, 5 minutes after first opening, checked by the server.
 */
if (defined('LESSON_COMPLETE_BAR_LOADED')) return;
define('LESSON_COMPLETE_BAR_LOADED', true);

// Pages include the navbar from inside functions too (lesson_shell.php), so the connection is taken globally.
$lcbDb = $GLOBALS['db'] ?? null;
if (empty($_SESSION['user_id']) || !$lcbDb instanceof PDO) return;

require_once INCLUDES_PATH . '/item_progress.php';

/**
 * The lesson item this request is, for this student: ['lesson_id', 'course_id', 'path', 'title'] or null.
 * A page shared by several courses (CELPIP_Gen/, IELTS_Gen/) is resolved to the course in ?from=, else the one the
 * student is enrolled in.
 */
function lesson_item_for_request(PDO $db, int $studentId): ?array {
    $base = rtrim((string) parse_url(ACADEMY_URL, PHP_URL_PATH), '/') . '/';
    $uri  = (string) ($_SERVER['REQUEST_URI'] ?? '');
    $path = str_starts_with($uri, $base) ? substr($uri, strlen($base)) : ltrim($uri, '/');
    $key  = item_progress_key($path);
    $file = explode('?', $key, 2)[0];
    if ($file === '' || !str_ends_with($file, '.php')) return null;

    $st = $db->prepare(
        "SELECT p.title, l.id AS lesson_id, l.course_id, c.folder_name,
                COALESCE(NULLIF(p.file_path, ''), l.file_path) AS item_path
           FROM lesson_parts p
           JOIN lessons l ON l.id = p.lesson_id
           JOIN courses c ON c.id = l.course_id
           JOIN enrollments e ON e.course_id = l.course_id AND e.student_id = ?
          WHERE p.kind = 'lesson' AND p.status = 'ready'
            AND CONVERT(COALESCE(NULLIF(p.file_path, ''), l.file_path) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?"
    );
    $st->execute([$studentId, $file . '%']);
    $from = preg_replace('/[^A-Za-z0-9_]/', '', $_GET['from'] ?? '');
    $found = null;
    foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) {
        if (item_progress_key($r['item_path']) !== $key) continue;
        if ($found === null || $r['folder_name'] === $from) $found = $r;
    }
    return $found ? ['lesson_id' => (int) $found['lesson_id'], 'course_id' => (int) $found['course_id'],
                     'path' => $key, 'title' => $found['title']] : null;
}

try {
    $lcbItem = lesson_item_for_request($lcbDb, (int) $_SESSION['user_id']);
} catch (Throwable $e) {
    error_log('lesson_complete_bar: ' . $e->getMessage());
    $lcbItem = null;
}
if (!$lcbItem) return;

item_progress_open($lcbDb, (int) $_SESSION['user_id'], $lcbItem['lesson_id'], $lcbItem['path']);
$st = $lcbDb->prepare("SELECT completed_at, GREATEST(0, ? - TIMESTAMPDIFF(SECOND, opened_at, NOW())) AS seconds_left
                      FROM lesson_item_progress WHERE student_id = ? AND lesson_id = ? AND item_key = ?");
$st->execute([ITEM_LESSON_MIN_SECONDS, (int) $_SESSION['user_id'], $lcbItem['lesson_id'], $lcbItem['path']]);
$lcbState = $st->fetch(PDO::FETCH_ASSOC) ?: ['completed_at' => null, 'seconds_left' => ITEM_LESSON_MIN_SECONDS];
?>
<div id="lessonCompleteBar" class="lcb" data-lesson="<?= (int) $lcbItem['lesson_id'] ?>" data-path="<?= htmlspecialchars($lcbItem['path']) ?>"
     data-left="<?= (int) $lcbState['seconds_left'] ?>" data-done="<?= $lcbState['completed_at'] ? 1 : 0 ?>">
    <span class="lcb-text" id="lcbText"></span>
    <button type="button" class="lcb-btn" id="lcbBtn"><i class="bi bi-check2-circle me-1"></i>Mark complete</button>
</div>
<style>
    .lcb { position: fixed; right: 1rem; bottom: 1rem; z-index: 1040; display: flex; align-items: center; gap: .75rem;
           background: #fff; border: 1px solid #d1d5db; border-radius: 10px; padding: .55rem .7rem .55rem .9rem;
           font-size: .85rem; color: #374151; max-width: calc(100vw - 2rem); }
    .lcb-btn { background: #0b77ff; color: #fff; border: 0; border-radius: 8px; padding: .4rem .8rem; font-weight: 600; white-space: nowrap; }
    .lcb-btn:disabled { background: #9ca3af; cursor: not-allowed; }
    .lcb.done { border-color: #86efac; color: #166534; }
    body.dark .lcb { background: #1f2937; border-color: #374151; color: #e5e7eb; }
</style>
<script>
(function () {
    const bar = document.getElementById('lessonCompleteBar');
    const text = document.getElementById('lcbText'), btn = document.getElementById('lcbBtn');
    let left = +bar.dataset.left;
    const fmt = s => Math.floor(s / 60) + ':' + String(s % 60).padStart(2, '0');
    function done() {
        bar.classList.add('done');
        text.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>Lesson complete';
        btn.remove();
    }
    function tick() {
        if (left > 0) { btn.disabled = true; text.textContent = 'You can mark this lesson complete in ' + fmt(left); left--; setTimeout(tick, 1000); }
        else { btn.disabled = false; text.textContent = 'Finished this lesson?'; }
    }
    if (bar.dataset.done === '1') { done(); return; }
    tick();
    btn.addEventListener('click', async () => {
        btn.disabled = true;
        try {
            const res = await fetch('<?= ACADEMY_URL ?>api/lesson_complete_web.php', {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ lesson_id: +bar.dataset.lesson, path: bar.dataset.path })
            });
            const data = await res.json();
            if (data.success) done();
            else if (data.secondsLeft) { left = data.secondsLeft; tick(); }
            else { text.textContent = data.error || 'Could not save. Try again.'; btn.disabled = false; }
        } catch (e) { text.textContent = 'Could not save. Try again.'; btn.disabled = false; }
    });
})();
</script>
