<?php
require_once(__DIR__ . '/../config/db_connect.php');

// Detect environment (local or live)
$is_local = (
    strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ||
    strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false
);

// ✅ Correct redirect base (config is NOT inside slslanguage.com)
$redirect_base = $is_local
    ? 'http://localhost:8888/academy'
    : 'https://academy.slslanguage.com';
	

// Check if token is provided
if (empty($_GET['token'])) {
    header("Location: {$redirect_base}/edu_hub_registration.php?status=error&message=Invalid+verification+link");
    exit();
}

// Decode token safely
$token = trim(urldecode($_GET['token']));
error_log("VERIFY DEBUG: Received token => " . $token);

// Look for user in database
try {
    // Expiry is decided by MySQL against its own NOW(), not PHP's time(): the two clocks can sit in different time
    // zones, which once made fresh links look expired (same reason as api/mobile_resend_verification.php).
    $sql = "SELECT id, email, firstname, (token_created_at < NOW() - INTERVAL 24 HOUR) AS expired
            FROM students
            WHERE verification_token = ? AND is_verified = 0";
    $stmt = $db->prepare($sql);
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($user['expired']) {
            // An expired link sends a fresh one by itself (founder, 2026-10-26). It used to say "Please register again",
            // which can't work: the email is already registered, so the student was stuck.
            $newToken = bin2hex(random_bytes(32));
            $db->prepare("UPDATE students SET verification_token = ?, token_created_at = NOW() WHERE id = ?")->execute([$newToken, $user['id']]);
            $sent = false;
            try {
                require_once __DIR__ . '/../config/email_helper.php';
                $sent = send_verification_email($user['email'], $user['firstname'], $newToken);
            } catch (Throwable $e) {
                error_log('VERIFY: resending an expired link failed: ' . $e->getMessage());
            }
            $msg = $sent
                ? 'That link had expired, so we have sent you a new one. Check your inbox (and spam folder); it works for 24 hours.'
                : 'That link had expired and we could not send a new one just now. Please contact support and we will verify your account.';
            header("Location: {$redirect_base}/edu_hub_registration.php?status=" . ($sent ? 'resent' : 'error') . '&message=' . urlencode($msg));
            exit();
        }

        // ✅ Mark verified
        $update_sql = "UPDATE students
                       SET is_verified = 1,
                           verification_token = NULL,
                           token_created_at = NULL
                       WHERE id = ?";
        $update_stmt = $db->prepare($update_sql);
        $update_stmt->execute([$user['id']]);
        error_log("VERIFY DEBUG: User ID {$user['id']} marked verified.");

        // Attach any Selar purchase made with this (now VERIFIED) email. Never blocks verification.
        try {
            $selarFile = __DIR__ . '/../config/selar_purchases.php';
            if (is_file($selarFile)) {
                require_once $selarFile;
                selar_claim_for_student($db, (int) $user['id']);
            }
        } catch (Throwable $e) {
            error_log('VERIFY: selar claim failed: ' . $e->getMessage());
        }

        // Start session and auto-login
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_firstname'] = $user['firstname'];

        header("Location: {$redirect_base}/edu_hub_registration.php?status=success&message=Email+verified+successfully!+You+may+now+log+in");
        exit();
    } else {
        error_log("VERIFY DEBUG: Invalid or already verified token => {$token}");
        header("Location: {$redirect_base}/edu_hub_registration.php?status=error&message=Invalid+or+already+verified+token");
        exit();
    }

} catch (PDOException $e) {
    error_log("VERIFY DEBUG: Database error => " . $e->getMessage());
    header("Location: {$redirect_base}/edu_hub_registration.php?status=error&message=Verification+failed.+Please+try+again");
    exit();
}
?>