<?php
// Bridges the sls_mobile token login to the PHP session the academy pages use,
// so a WebView can open normal pages already logged in.
//
//   POST (X-Api-Token header, optional JSON {"next": "learning_dashboard.php"})
//        -> {"success":true,"url":"<absolute URL to load in the WebView>"}
//   GET  ?code=...&next=...
//        -> consumes the one-time code, sets the session (same keys as the web
//           login), then redirects to `next`.
//
// The token itself never goes in a URL: the GET carries only a 60-second,
// single-use code whose hash is stored in mobile_session_codes.

require_once dirname(__DIR__) . '/bootstrap.php';
require_once __DIR__ . '/mobile_auth.php';

const MOBILE_SESSION_DEFAULT_NEXT = 'learning_dashboard.php';
const MOBILE_SESSION_CODE_TTL = 60;

// `next` is a path relative to the academy root. Anything that could leave the
// site or climb out of it falls back to the dashboard (no open redirect).
function mobileSessionSafeNext($next) {
    $next = ltrim(trim((string) $next), '/');
    if ($next === ''
        || strpos($next, '..') !== false
        || !preg_match('/^[A-Za-z0-9_\-\/\.]+(\?[A-Za-z0-9_=&%\-\.]*)?$/', $next)) {
        return MOBILE_SESSION_DEFAULT_NEXT;
    }
    return $next;
}

$academyBase = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/') . '/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $student = requireMobileAuth($db);

    $input = json_decode(file_get_contents('php://input'), true) ?: [];
    $next = mobileSessionSafeNext($input['next'] ?? '');

    try {
        $db->prepare("DELETE FROM mobile_session_codes WHERE expires_at < NOW()")->execute();

        $code = bin2hex(random_bytes(32));
        $db->prepare(
            "INSERT INTO mobile_session_codes (student_id, code_hash, expires_at)
             VALUES (?, ?, DATE_ADD(NOW(), INTERVAL " . MOBILE_SESSION_CODE_TTL . " SECOND))"
        )->execute([$student['id'], hash('sha256', $code)]);
    } catch (PDOException $e) {
        error_log('mobile_session issue error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Could not start session']);
        exit();
    }

    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $url = $scheme . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']
         . '?code=' . $code . '&next=' . rawurlencode($next);

    echo json_encode(['success' => true, 'url' => $url]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $code = $_GET['code'] ?? '';
    $next = mobileSessionSafeNext($_GET['next'] ?? '');

    if (!preg_match('/^[a-f0-9]{64}$/', $code)) {
        http_response_code(400);
        exit('Invalid or expired link.');
    }

    try {
        $hash = hash('sha256', $code);
        $stmt = $db->prepare(
            "SELECT s.id, s.firstname, s.lastname, s.email
             FROM mobile_session_codes c
             JOIN students s ON s.id = c.student_id
             WHERE c.code_hash = ? AND c.expires_at > NOW()"
        );
        $stmt->execute([$hash]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        // Single use: only the request that actually deletes the row wins.
        $del = $db->prepare("DELETE FROM mobile_session_codes WHERE code_hash = ?");
        $del->execute([$hash]);
        if (!$student || $del->rowCount() !== 1) {
            http_response_code(400);
            exit('Invalid or expired link.');
        }
    } catch (PDOException $e) {
        error_log('mobile_session consume error: ' . $e->getMessage());
        http_response_code(500);
        exit('Could not start session.');
    }

    // Same session keys as the web login in config/edu_hub_registration_handler.php.
    session_regenerate_id(true);
    $_SESSION['user_id'] = $student['id'];
    $_SESSION['user_email'] = $student['email'];
    $_SESSION['user_firstname'] = $student['firstname'];
    $_SESSION['user_lastname'] = $student['lastname'];
    $_SESSION['email_verified'] = 1;

    header('Location: ' . $academyBase . $next);
    exit();
}

http_response_code(405);
header('Content-Type: application/json');
echo json_encode(['error' => 'Method not allowed']);
