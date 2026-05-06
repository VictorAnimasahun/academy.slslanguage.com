<?php
/**
 * TEMPORARY — database inspector. Delete after use.
 * Visit: http://localhost:8888/academy/db_inspector.php
 */
require_once __DIR__ . '/bootstrap.php';

// Only accessible locally
if (!in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    http_response_code(403); exit('Forbidden');
}

$section = $_GET['s'] ?? 'tables';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>DB Inspector</title>
<style>
body{font-family:monospace;background:#0f172a;color:#e2e8f0;padding:2rem;font-size:.85rem;}
h2{color:#38bdf8;margin:1.5rem 0 .5rem;}
h3{color:#a78bfa;margin:1rem 0 .25rem;}
a{color:#60a5fa;margin-right:1rem;}
table{border-collapse:collapse;width:100%;margin-bottom:2rem;}
th{background:#1e293b;color:#94a3b8;text-align:left;padding:.5rem .75rem;border:1px solid #334155;}
td{padding:.4rem .75rem;border:1px solid #1e293b;vertical-align:top;white-space:pre-wrap;word-break:break-word;max-width:400px;}
tr:nth-child(even) td{background:#0f1d2e;}
.null{color:#475569;font-style:italic;}
</style>
</head>
<body>
<h2>DB Inspector — <?= htmlspecialchars($section) ?></h2>
<nav>
    <a href="?s=tables">Tables</a>
    <a href="?s=courses">Courses</a>
    <a href="?s=modules">Modules</a>
    <a href="?s=lessons">Lessons</a>
    <a href="?s=ielts_masterclass">IELTS Masterclass</a>
    <a href="?s=subscriptions">Subscriptions/Tiers</a>
    <a href="?s=enrollments">Enrollments</a>
    <a href="?s=all_schema">Full Schema</a>
</nav>
<hr style="border-color:#334155;margin:1rem 0;">

<?php

function renderTable($stmt) {
    if (!$stmt) { echo '<p style="color:#f87171">Query failed.</p>'; return; }
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($rows)) { echo '<p style="color:#94a3b8">No rows found.</p>'; return; }
    echo '<table><tr>';
    foreach (array_keys($rows[0]) as $col) echo '<th>' . htmlspecialchars($col) . '</th>';
    echo '</tr>';
    foreach ($rows as $row) {
        echo '<tr>';
        foreach ($row as $val) {
            $display = $val === null ? '<span class="null">NULL</span>' : htmlspecialchars((string)$val);
            echo '<td>' . $display . '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
}

switch ($section) {

    case 'tables':
        echo '<h3>All Tables</h3>';
        renderTable($db->query("SHOW TABLES"));
        break;

    case 'courses':
        echo '<h3>All Courses</h3>';
        renderTable($db->query("SELECT id, title, slug, description, duration_months, price, level, status, created_at FROM courses ORDER BY id"));
        break;

    case 'modules':
        echo '<h3>All Course Modules (with course title)</h3>';
        renderTable($db->query("
            SELECT m.id, c.title AS course, m.module_number, m.title, m.description, m.status, m.created_at
            FROM course_modules m
            JOIN courses c ON c.id = m.course_id
            ORDER BY c.id, m.module_number
        "));
        break;

    case 'lessons':
        echo '<h3>All Lessons (with module + course)</h3>';
        renderTable($db->query("
            SELECT l.id, c.title AS course, m.title AS module, l.lesson_number, l.title,
                   l.content_type, l.duration_minutes, l.is_free_preview, l.status
            FROM course_lessons l
            JOIN course_modules m ON m.id = l.module_id
            JOIN courses c ON c.id = m.course_id
            ORDER BY c.id, m.module_number, l.lesson_number
        "));
        break;

    case 'ielts_masterclass':
        echo '<h3>Courses containing "IELTS" or "Masterclass"</h3>';
        $stmt = $db->query("SELECT * FROM courses WHERE title LIKE '%IELTS%' OR title LIKE '%Masterclass%' OR title LIKE '%masterclass%'");
        renderTable($stmt);

        echo '<h3>Modules for those courses</h3>';
        renderTable($db->query("
            SELECT m.*, c.title AS course_title
            FROM course_modules m
            JOIN courses c ON c.id = m.course_id
            WHERE c.title LIKE '%IELTS%' OR c.title LIKE '%Masterclass%'
            ORDER BY m.course_id, m.module_number
        "));

        echo '<h3>Lessons for those modules</h3>';
        renderTable($db->query("
            SELECT l.*, m.title AS module_title, c.title AS course_title
            FROM course_lessons l
            JOIN course_modules m ON m.id = l.module_id
            JOIN courses c ON c.id = m.course_id
            WHERE c.title LIKE '%IELTS%' OR c.title LIKE '%Masterclass%'
            ORDER BY m.course_id, m.module_number, l.lesson_number
        "));
        break;

    case 'subscriptions':
        echo '<h3>Subscription / Tier tables (guessing table names)</h3>';
        $candidates = ['subscriptions','subscription_plans','tiers','access_tiers',
                       'plans','user_subscriptions','student_subscriptions',
                       'course_access','content_access','tier_content'];
        foreach ($candidates as $t) {
            try {
                $r = $db->query("SELECT * FROM `$t` LIMIT 20");
                echo "<h3>$t</h3>"; renderTable($r);
            } catch (Exception $e) {
                echo "<p style='color:#475569'>$t — not found</p>";
            }
        }
        // Also show any table with "tier" or "plan" or "subscription" in its name
        echo '<h3>Tables with "tier", "plan", or "subscription" in name</h3>';
        $all = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        $matches = array_filter($all, fn($t) => preg_match('/tier|plan|subscri|access/i', $t));
        if ($matches) {
            foreach ($matches as $t) {
                echo "<h4>$t</h4>"; renderTable($db->query("SELECT * FROM `$t` LIMIT 30"));
            }
        } else {
            echo '<p style="color:#94a3b8">None found.</p>';
        }
        break;

    case 'enrollments':
        echo '<h3>Enrollments (sample)</h3>';
        renderTable($db->query("SELECT e.*, c.title AS course_title FROM enrollments e JOIN courses c ON c.id = e.course_id LIMIT 50"));
        break;

    case 'all_schema':
        echo '<h3>Full Schema (CREATE TABLE statements)</h3>';
        $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($tables as $t) {
            echo "<h3>$t</h3>";
            $row = $db->query("SHOW CREATE TABLE `$t`")->fetch(PDO::FETCH_ASSOC);
            echo '<pre style="background:#1e293b;padding:1rem;border-radius:8px;overflow-x:auto;">' . htmlspecialchars($row['Create Table'] ?? $row[array_key_last($row)]) . '</pre>';
        }
        break;
}
?>
</body>
</html>
