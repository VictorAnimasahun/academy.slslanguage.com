<?php
// Registers a new student from the mobile app. Mirrors the validation and
// verification-email logic in config/edu_hub_registration_handler.php's
// registration branch, but returns JSON instead of redirecting.
//
// The account is created unverified (is_verified=0), same as the web flow —
// the student still has to click the link in the verification email before
// mobile_login.php will let them in.

require_once dirname(__DIR__) . '/bootstrap.php';
require_once CONFIG_PATH . '/email_helper.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true) ?? [];

$firstname = trim($input['firstname'] ?? '');
$lastname = trim($input['lastname'] ?? '');
$email = trim($input['email'] ?? '');
$phonenumber = trim($input['phonenumber'] ?? '');
$password = trim($input['password'] ?? '');
$confirmpassword = trim($input['confirmpassword'] ?? '');

$required = compact('firstname', 'lastname', 'email', 'phonenumber', 'password', 'confirmpassword');
foreach ($required as $name => $value) {
    if ($value === '') {
        http_response_code(400);
        echo json_encode(['error' => ucfirst(str_replace('_', ' ', $name)) . ' is required']);
        exit();
    }
}

// NOTE: the web registration handler (config/edu_hub_registration_handler.php) additionally
// runs checkdnsrr() here to confirm the domain has mail servers. Deliberately skipped that
// here — testing showed it hangs intermittently for the full 30s execution-time limit under
// this server's FastCGI pool (reproduced twice; CLI PHP resolves the same domain instantly).
// A 30s hang before an error is a much worse failure mode on mobile than accepting a
// syntactically-valid-but-undeliverable address — the verification email step already
// handles that case (it just won't arrive).
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email format']);
    exit();
}

if ($password !== $confirmpassword) {
    http_response_code(400);
    echo json_encode(['error' => 'Passwords do not match']);
    exit();
}

if (strlen($password) < 8) {
    http_response_code(400);
    echo json_encode(['error' => 'Password must be at least 8 characters']);
    exit();
}

$verification_token = bin2hex(random_bytes(32));
$hashed_password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

try {
    $stmt = $db->prepare(
        "INSERT INTO students (firstname, lastname, email, phonenumber, password, is_verified, verification_token, token_created_at)
         VALUES (?, ?, ?, ?, ?, 0, ?, NOW())"
    );
    $stmt->execute([$firstname, $lastname, $email, $phonenumber, $hashed_password, $verification_token]);

    $emailSent = send_verification_email($email, $firstname, $verification_token);

    echo json_encode([
        'success' => true,
        'email_sent' => $emailSent,
        'message' => $emailSent
            ? 'Registration successful! Please check your email to verify your account.'
            : 'Account created but the verification email failed to send. Please contact support.',
    ]);
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        http_response_code(409);
        echo json_encode(['error' => 'Email address already exists']);
    } else {
        error_log('mobile_register error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Registration failed. Please try again later.']);
    }
}
