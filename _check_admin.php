<?php
$_SERVER['HTTP_HOST'] = 'localhost';
require __DIR__ . '/bootstrap.php';
header('Content-Type: text/plain');

foreach (['employees', 'students'] as $tbl) {
    echo "=== $tbl ===\n";
    foreach ($db->query("DESCRIBE `$tbl`")->fetchAll(PDO::FETCH_ASSOC) as $c) {
        echo "  {$c['Field']} | {$c['Type']} | default=" . ($c['Default'] ?? 'NULL') . "\n";
    }
    echo "  [rows: " . $db->query("SELECT COUNT(*) FROM `$tbl`")->fetchColumn() . "]\n\n";
}
