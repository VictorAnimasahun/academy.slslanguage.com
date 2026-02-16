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
    $sql = "SELECT id, email, firstname, token_created_at
            FROM students
            WHERE verification_token = ? AND is_verified = 0";
    $stmt = $db->prepare($sql);
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $token_age = time() - strtotime($user['token_created_at']);
        $max_age = 24 * 60 * 60; // 24 hours

        if ($token_age > $max_age) {
            header("Location: {$redirect_base}/edu_hub_registration.php?status=error&message=Verification+link+expired.+Please+register+again");
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