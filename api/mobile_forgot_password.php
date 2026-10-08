<?php
// POST /api/mobile_forgot_password.php  body: {"email": "..."}
// SLS-Academy-App-Spec.md 8.16, Forgot password. Mirrors api/mobile_resend_verification.php's
// shape exactly: rate-limited to once a minute per account (checked in SQL against MySQL's own
// NOW(), not PHP's time() -- see that file's commit for why), and the response never reveals
// whether the email exists. The reset itself still finishes on the web (the emailed link opens
// reset_password.php) -- same pattern as email verification, no native "set new password"
// screen needed.

require_once dirname(__DIR__) . '/bootstrap.php';
require_once CONFIG_PATH . '/email_helper.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true) ?: [];
$email = trim($input['email'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email format']);
    exit();
}

$generic = ['success' => true, 'message' => 'If that account exists, a password reset link is on its way.'];

try {
    $stmt = $db->prepare("
        SELECT id, firstname, (password_reset_token_created_at > NOW() - INTERVAL 60 SECOND) AS too_recent
        FROM students WHERE email = ?
    ");
    $stmt->execute([$email]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student || $student['too_recent']) {
        echo json_encode($generic);
        exit();
    }

    $token = bin2hex(random_bytes(32));
    $db->prepare("UPDATE students SET password_reset_token = ?, password_reset_token_created_at = NOW() WHERE id = ?")->execute([$token, $student['id']]);

    $is_local = ($_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);
    $reset_base = $is_local ? 'http://localhost:8888/academy' : 'https://academy.slslanguage.com';
    $reset_link = "{$reset_base}/reset_password.php?token=" . urlencode($token);

    send_academy_password_reset_email($email, $student['firstname'], $reset_link);

    echo json_encode($generic);
} catch (PDOException $e) {
    error_log('mobile_forgot_password error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Could not send the link. Please try again later.']);
}
