<?php
// POST /api/mobile_resend_verification.php  body: {"email": "..."}
// SUP-06: resends the verification email, for the mobile "Check your email" screen's
// "Resend the link" action. No endpoint for this existed anywhere (web or mobile) before.
// Rate-limited to once a minute per account, same 24h token lifetime as the original
// (verify_email.php checks token_created_at), and the response never reveals whether the
// email exists or was already verified -- same style as not revealing login failures.

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

$generic = ['success' => true, 'message' => 'If that account needs verifying, we sent a new link.'];

try {
    // The 60-second check is done by MySQL itself (token_created_at vs its own NOW()), not by
    // comparing a MySQL-sourced string against PHP's time() -- the two clocks disagree by about
    // an hour here (migration 144's finding: MySQL's SYSTEM time zone isn't PHP's UTC), which
    // made a naive strtotime()/time() comparison stay "too recent" long after it should have expired.
    $stmt = $db->prepare("
        SELECT id, firstname, (token_created_at > NOW() - INTERVAL 60 SECOND) AS too_recent
        FROM students WHERE email = ? AND is_verified = 0
    ");
    $stmt->execute([$email]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        echo json_encode($generic); // no such account, or already verified -- same response either way
        exit();
    }

    if ($student['too_recent']) {
        http_response_code(429);
        echo json_encode(['error' => 'Please wait a moment before requesting another link.']);
        exit();
    }

    $token = bin2hex(random_bytes(32));
    $db->prepare("UPDATE students SET verification_token = ?, token_created_at = NOW() WHERE id = ?")->execute([$token, $student['id']]);
    send_verification_email($email, $student['firstname'], $token);

    echo json_encode($generic);
} catch (PDOException $e) {
    error_log('mobile_resend_verification error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Could not send the link. Please try again later.']);
}
