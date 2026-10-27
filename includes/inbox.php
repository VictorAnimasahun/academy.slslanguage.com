<?php
/**
 * The Inbox's items and state (founder, 2026-10-27: one Inbox, "the same UI" as Canvas's; Notifications fold into it).
 *
 *   inbox_items($db, $studentId)          every item, newest first (see below)
 *   inbox_unread_count($db, $studentId)   unread items in the Inbox (not archived or deleted): the bell's count
 *   inbox_apply($db, $studentId, $action, $keys)   read / unread / star / unstar / archive / unarchive / delete / read_all
 *
 * Four kinds, keyed so one state table covers them all (inbox_item_state, migration 175):
 *   a<id>  announcement, course update or reminder   broadcast_messages (read state: broadcast_message_reads)
 *   t<id>  conversation with a mentor                threads (read state: thread_participants.last_read_message_id)
 *   d<id>  assignment due within two days, not done  assignments (read state: inbox_item_state.read_at)
 *   e<id>  registered event starting within 24 hours events (read state: inbox_item_state.read_at)
 * Due and event items appear while their window is open, as the Notifications feed showed them.
 */
if (defined('INBOX_LIB_LOADED')) return;
define('INBOX_LIB_LOADED', true);

/** Rows of a query, or none if it failed (executeQuery logs it and returns false) -- so a missing table or column
 *  (e.g. migration 175 not run yet) empties that part of the inbox instead of a fatal error on every page. */
function inbox_rows(PDO $db, string $sql, array $params): array {
    $st = executeQuery($db, $sql, $params);
    return $st ? $st->fetchAll(PDO::FETCH_ASSOC) : [];
}

function inbox_items(PDO $db, int $sid): array {
    $courses = [];   // the student's courses: id => title
    foreach (inbox_rows($db, "SELECT c.id, c.title FROM enrollments e JOIN courses c ON c.id = e.course_id WHERE e.student_id = ? ORDER BY c.title", [$sid]) as $c) {
        $courses[(int) $c['id']] = $c['title'];
    }
    $state = [];
    foreach (inbox_rows($db, "SELECT * FROM inbox_item_state WHERE student_id = ?", [$sid]) as $s) $state[$s['item_key']] = $s;
    $items = [];
    $add = function (array $i) use (&$items, $state) {
        $s = $state[$i['key']] ?? [];
        $items[] = $i + ['starred' => !empty($s['starred']), 'archived' => !empty($s['archived']), 'deleted' => !empty($s['deleted'])];
    };

    $rows = inbox_rows($db, "
        SELECT m.id, m.title, m.content, m.message_type, m.link_url, m.link_text, m.created_at, m.course_id, m.target_course_ids,
               EXISTS (SELECT 1 FROM broadcast_message_reads r WHERE r.message_id = m.id AND r.student_id = ?) AS is_read
        FROM broadcast_messages m
        WHERE m.target_all_students = 1
           OR FIND_IN_SET(?, m.target_student_ids) > 0
           OR EXISTS (SELECT 1 FROM enrollments e WHERE e.student_id = ? AND FIND_IN_SET(e.course_id, m.target_course_ids) > 0)
    ", [$sid, $sid, $sid]);
    foreach ($rows as $m) {
        $cid = (int) $m['course_id'];
        if (!$cid) foreach (array_filter(array_map('intval', explode(',', (string) $m['target_course_ids']))) as $t) if (isset($courses[$t])) { $cid = $t; break; }
        $add(['key' => 'a' . $m['id'], 'kind' => 'announcement', 'sender' => 'SLS Academy', 'subject' => $m['title'],
              'body' => $m['content'], 'at' => $m['created_at'], 'unread' => !$m['is_read'], 'count' => 0, 'sent' => false,
              'course_id' => $cid ?: null, 'course' => $courses[$cid] ?? null,
              'link_url' => $m['link_url'], 'link_text' => $m['link_text'] ?: 'Open']);
    }

    $rows = inbox_rows($db, "
        SELECT t.id, m.display_name AS mentor_name, lm.body AS last_body, lm.created_at AS last_at,
               (SELECT COUNT(*) FROM thread_messages x WHERE x.thread_id = t.id) AS n,
               (SELECT COUNT(*) FROM thread_messages x WHERE x.thread_id = t.id AND x.sender_id = ?) AS mine,
               (lm.sender_id != ? AND lm.id > COALESCE(tp.last_read_message_id, 0)) AS unread
        FROM threads t
        JOIN thread_participants tp ON tp.thread_id = t.id AND tp.student_id = ?
        LEFT JOIN mentors m ON m.id = t.mentor_id
        JOIN thread_messages lm ON lm.id = (SELECT MAX(id) FROM thread_messages WHERE thread_id = t.id)
    ", [$sid, $sid, $sid]);
    foreach ($rows as $t) {
        $name = $t['mentor_name'] ?: 'Mentor';
        $add(['key' => 't' . $t['id'], 'kind' => 'conversation', 'sender' => $name, 'subject' => 'Conversation with ' . $name,
              'body' => $t['last_body'], 'at' => $t['last_at'], 'unread' => (bool) $t['unread'], 'count' => (int) $t['n'],
              'sent' => $t['mine'] > 0, 'course_id' => null, 'course' => null, 'link_url' => null, 'link_text' => null]);
    }

    $rows = inbox_rows($db, "
        SELECT a.id, a.title, a.due_date, a.course_id
        FROM assignments a
        JOIN enrollments e ON e.course_id = a.course_id AND e.student_id = ?
        LEFT JOIN (
            SELECT ta1.test_id, ta1.status FROM test_attempts ta1
            JOIN (SELECT test_id, MAX(id) AS max_id FROM test_attempts WHERE student_id = ? GROUP BY test_id) ta2 ON ta1.id = ta2.max_id
        ) latest ON latest.test_id = a.test_id
        WHERE (a.student_id IS NULL OR a.student_id = ?)
          AND a.due_date IS NOT NULL AND a.due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 2 DAY)
          AND (latest.status IS NULL OR latest.status != 'completed')
    ", [$sid, $sid, $sid]);
    foreach ($rows as $a) {
        $key = 'd' . $a['id'];
        $due = new DateTime($a['due_date']);
        $add(['key' => $key, 'kind' => 'assignment', 'sender' => 'Assignments', 'subject' => 'Due ' . $due->format('D j M') . ': ' . $a['title'],
              'body' => "**{$a['title']}** is due on " . $due->format('l j F') . ". You haven't completed it yet.",
              'at' => max(date('Y-m-d 00:00:00', strtotime($a['due_date'] . ' -2 days')), date('Y-m-d 00:00:00', strtotime('-2 days'))),
              'unread' => empty($state[$key]['read_at']), 'count' => 0, 'sent' => false,
              'course_id' => (int) $a['course_id'], 'course' => $courses[(int) $a['course_id']] ?? null,
              'link_url' => 'assignments.php', 'link_text' => 'Open my assignments']);
    }

    $rows = inbox_rows($db, "
        SELECT ev.id, ev.title, ev.description, ev.host, ev.starts_at, ev.join_url FROM events ev
        JOIN event_registrations r ON r.event_id = ev.id AND r.student_id = ?
        WHERE ev.starts_at BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 24 HOUR)
    ", [$sid]);
    foreach ($rows as $ev) {
        $key = 'e' . $ev['id'];
        $at = new DateTime($ev['starts_at']);
        $add(['key' => $key, 'kind' => 'event', 'sender' => 'Events', 'subject' => 'Starting soon: ' . $ev['title'],
              'body' => "**{$ev['title']}** starts " . $at->format('l j F \a\t g:ia') . ($ev['host'] ? " with {$ev['host']}" : '') . '.'
                        . ($ev['description'] ? "\n\n" . $ev['description'] : ''),
              'at' => date('Y-m-d H:i:s', strtotime($ev['starts_at'] . ' -24 hours')),
              'unread' => empty($state[$key]['read_at']), 'count' => 0, 'sent' => false, 'course_id' => null, 'course' => null,
              'link_url' => $ev['join_url'] ?: 'events.php', 'link_text' => $ev['join_url'] ? 'Join the event' : 'Open events']);
    }

    usort($items, fn ($x, $y) => strcmp($y['at'], $x['at']));
    return $items;
}

function inbox_unread_count(PDO $db, int $sid): int {
    return count(array_filter(inbox_items($db, $sid), fn ($i) => $i['unread'] && !$i['archived'] && !$i['deleted']));
}

/** The student's courses, for the course filter: id => title. */
function inbox_courses(PDO $db, int $sid): array {
    $out = [];
    foreach (inbox_rows($db, "SELECT c.id, c.title FROM enrollments e JOIN courses c ON c.id = e.course_id WHERE e.student_id = ? ORDER BY c.title", [$sid]) as $c) {
        $out[(int) $c['id']] = $c['title'];
    }
    return $out;
}

/** Apply an action to some of the student's items (keys like a12, t3). Keys not in their inbox are ignored. */
function inbox_apply(PDO $db, int $sid, string $action, array $keys): void {
    $mine = array_column(inbox_items($db, $sid), null, 'key');
    if ($action === 'read_all') $keys = array_keys($mine);
    $setState = function (string $key, string $field, $value) use ($db, $sid) {
        $db->prepare("INSERT INTO inbox_item_state (student_id, item_key, $field) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE $field = VALUES($field)")
           ->execute([$sid, $key, $value]);
    };
    foreach ($keys as $key) {
        if (!isset($mine[$key])) continue;
        $id = (int) substr($key, 1);
        switch ($action) {
            case 'read':
            case 'read_all':
                if ($key[0] === 'a') executeQuery($db, "INSERT IGNORE INTO broadcast_message_reads (message_id, student_id) VALUES (?, ?)", [$id, $sid]);
                elseif ($key[0] === 't') executeQuery($db, "UPDATE thread_participants SET last_read_message_id = (SELECT MAX(id) FROM thread_messages WHERE thread_id = ?) WHERE thread_id = ? AND student_id = ?", [$id, $id, $sid]);
                else $setState($key, 'read_at', date('Y-m-d H:i:s'));
                break;
            case 'unread':
                if ($key[0] === 'a') executeQuery($db, "DELETE FROM broadcast_message_reads WHERE message_id = ? AND student_id = ?", [$id, $sid]);
                elseif ($key[0] === 't') executeQuery($db, "UPDATE thread_participants SET last_read_message_id = 0 WHERE thread_id = ? AND student_id = ?", [$id, $sid]);
                else $setState($key, 'read_at', null);
                break;
            case 'star':      $setState($key, 'starred', 1); break;
            case 'unstar':    $setState($key, 'starred', 0); break;
            case 'archive':   $setState($key, 'archived', 1); break;
            case 'unarchive': $setState($key, 'archived', 0); break;
            case 'delete':    $setState($key, 'deleted', 1); break;
        }
    }
}

/**
 * The Markdown messages use, as safe HTML, line by line: # headings, --- dividers, - / * bullet lists, 1. numbered
 * lists, **bold**, [links](https://...), and paragraphs (a single line break stays a line break).
 */
function inbox_markdown(string $md): string {
    $inline = function (string $t): string {
        $t = htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
        $t = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $t);
        return preg_replace_callback('/\[([^\]]+)\]\((https?:\/\/[^)\s]+)\)/', fn ($m) => '<a href="' . $m[2] . '">' . $m[1] . '</a>', $t);
    };
    $out = '';
    $para = [];
    $list = null;   // 'ul' or 'ol' while inside a list
    $flush = function () use (&$out, &$para, &$list, $inline) {
        if ($para) { $out .= '<p>' . implode('<br>', array_map($inline, $para)) . '</p>'; $para = []; }
        if ($list) { $out .= "</$list>"; $list = null; }
    };
    foreach (explode("\n", str_replace("\r", '', $md)) as $line) {
        $t = trim($line);
        if ($t === '') { $flush(); continue; }
        if (preg_match('/^(-{3,}|\*{3,}|_{3,})$/', $t)) { $flush(); $out .= '<hr style="margin:1rem 0;opacity:.2;">'; continue; }
        if (preg_match('/^#{1,6}\s+(.*)$/', $t, $h)) { $flush(); $out .= '<h3 style="font-size:1.08rem;font-weight:700;margin:1.1rem 0 .4rem;">' . $inline($h[1]) . '</h3>'; continue; }
        if (preg_match('/^(?:[-*+]|(\d+)[.)])\s+(.*)$/', $t, $li)) {
            $type = $li[1] !== '' ? 'ol' : 'ul';
            if ($para) { $out .= '<p>' . implode('<br>', array_map($inline, $para)) . '</p>'; $para = []; }
            if ($list !== $type) { if ($list) $out .= "</$list>"; $out .= "<$type>"; $list = $type; }
            $out .= '<li>' . $inline($li[2]) . '</li>';
            continue;
        }
        if ($list) { $out .= "</$list>"; $list = null; }
        $para[] = $t;
    }
    $flush();
    return $out;
}

/** Markdown-ish text as one plain line, for the list preview. */
function inbox_preview(string $s): string {
    $s = preg_replace('/[*_#>`]+|\[([^\]]*)\]\([^)]*\)/', '$1', strip_tags($s));
    return mb_substr(trim(preg_replace('/\s+/', ' ', $s)), 0, 110);
}

function inbox_initials(string $name): string {
    $parts = preg_split('/\s+/', trim($name)) ?: ['?'];
    return mb_strtoupper(mb_substr($parts[0], 0, 1) . (isset($parts[1]) ? mb_substr($parts[1], 0, 1) : ''));
}
