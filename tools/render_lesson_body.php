<?php
/**
 * Prints the lesson body of one academy page, rendered, for academy-laravel's content import (academy:import-content).
 * Needed to move content at cutover: the newer lesson pages build their exercises with PHP (listening_kit.php, the
 * $tfng / $pick helpers, course_introduction.php), so their text can only be read by running them.
 * Command line only (on the Mac, or on the server with EDUHUB_PHP):
 *
 *   php academy/tools/render_lesson_body.php courses/IELTS_Aca_1Mo/lessons/class03_p2_true-false-not-given.php
 *
 * The page's frame is not drawn. render_lesson_shell(), listening_kit_assets() and lesson_video() are defined here
 * first (a page only defines them when they are missing), so only the body comes out, with no session or access check.
 * A video is printed as <div data-lesson-video="<path>" data-video-title="<title>"></div> for the importer to turn into
 * a video slot; the kit's styles and script are not printed (academy-laravel has its own copy).
 */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
if ($argc !== 2) { fwrite(STDERR, "Usage: php academy/tools/render_lesson_body.php <page path under academy/>\n"); exit(1); }

$root = dirname(__DIR__);
$rel  = explode('?', $argv[1], 2)[0];
if (!preg_match('~^(courses|resources)/[A-Za-z0-9_./-]+\.php$~', $rel) || str_contains($rel, '..') || !is_file("$root/$rel")) {
    fwrite(STDERR, "Not a page under academy/courses or academy/resources: {$argv[1]}\n");
    exit(1);
}

// The host decides which database config/db_connect.php opens: the Mac's MAMP, or live's (on the server, where
// academy sits at the domain root). Nothing is written: the page runs with no session, so no student is signed in.
$onMac = str_starts_with($root, '/Applications/MAMP/');
$_SERVER['HTTP_HOST'] = $onMac ? 'localhost' : 'academy.slslanguage.com';
$base = $onMac ? '/academy/' : '/';
$_SERVER['REQUEST_URI'] = $base . $rel;
$_SERVER['SCRIPT_NAME'] = $_SERVER['PHP_SELF'] = $base . $rel;

function render_lesson_shell(PDO $db, string $folder, int $classNum, int $partOrder, string $bodyHtml = ''): void {
    echo "\n<!-- RENDERED BODY START -->", $bodyHtml, "<!-- RENDERED BODY END -->\n";
}
function listening_kit_assets(): string { return ''; }
function lesson_video(string $path, string $title): string {
    return '<div data-lesson-video="' . htmlspecialchars($path) . '" data-video-title="' . htmlspecialchars($title) . '"></div>';
}

// The page runs at the top level, as under Apache (bootstrap's globals stay global); hence the unusual names.
$__renderFile = "$root/$rel";
ob_start();
chdir(dirname($__renderFile));
require $__renderFile;
$out = ob_get_clean();

// The page's own markers win (a page that draws its own frame); otherwise what the page handed the shell.
foreach (['LESSON BODY', 'RENDERED BODY'] as $m) {
    if (preg_match("/<!-- $m START -->(.*?)<!-- $m END -->/s", $out, $x)) {
        echo trim($x[1]), "\n<!-- RENDER OK -->\n";   // the importer refuses output without this (a page that exit()s early)
        exit(0);
    }
}
fwrite(STDERR, "The page printed no lesson body: $rel\n");
exit(1);
