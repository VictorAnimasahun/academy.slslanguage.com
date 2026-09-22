<?php
// Revokes the calling app's API token so it stops working immediately,
// rather than lingering until its 30-day expiry.

require_once dirname(__DIR__) . '/bootstrap.php';
require_once __DIR__ . '/mobile_auth.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

// Same header parsing as authenticateMobileRequest(); an already-invalid token
// is not an error here, the client is logging out either way.
$headers = function_exists('getallheaders') ? getallheaders() : [];
$token = trim($headers['X-Api-Token'] ?? $headers['x-api-token'] ?? ($_SERVER['HTTP_X_API_TOKEN'] ?? ''));

if (preg_match('/^[a-f0-9]{64}$/', $token)) {
    try {
        $db->prepare("UPDATE api_tokens SET revoked_at = NOW() WHERE token = ? AND revoked_at IS NULL")
           ->execute([$token]);
    } catch (PDOException $e) {
        error_log('mobile_logout error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Logout failed']);
        exit();
    }
}

echo json_encode(['success' => true]);
