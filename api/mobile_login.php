<?php
// Issues a bearer token for the sls_mobile app.
// Mirrors the credential-check logic in config/edu_hub_registration_handler.php's
// login branch, but returns a JSON token instead of setting a PHP session.

require_once dirname(__DIR__) . '/bootstrap.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$email = trim($input['email'] ?? '');
$password = trim($input['password'] ?? '');

if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'Email and password are required']);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email format']);
    exit();
}

try {
    $stmt = $db->prepare(
        "SELECT id, firstname, lastname, email, password, is_verified FROM students WHERE email = ?"
    );
    $stmt->execute([$email]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student || !password_verify($password, $student['password'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid email or password']);
        exit();
    }

    if ($student['is_verified'] == 0) {
        http_response_code(403);
        echo json_encode(['error' => 'Please verify your email address before logging in.']);
        exit();
    }

    $token = bin2hex(random_bytes(32));
    $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));

    $insert = $db->prepare(
        "INSERT INTO api_tokens (student_id, token, expires_at) VALUES (?, ?, ?)"
    );
    $insert->execute([$student['id'], $token, $expiresAt]);

    echo json_encode([
        'success' => true,
        'token' => $token,
        'expires_at' => $expiresAt,
        'student' => [
            'id' => $student['id'],
            'firstname' => $student['firstname'],
            'lastname' => $student['lastname'],
            'email' => $student['email'],
        ],
    ]);
} catch (PDOException $e) {
    error_log('mobile_login error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Login failed']);
}
