<?php
// Shared helper for token auth on mobile API endpoints.
// Not directly requestable — include it, then call authenticateMobileRequest($db).
//
// Uses a custom `X-Api-Token` header rather than `Authorization: Bearer` because
// Apache/FastCGI setups (this local MAMP install, and commonly shared cPanel hosts
// too) strip the Authorization header before PHP ever sees it, and there's no
// mod_rewrite here to work around it. Custom headers aren't touched.

function authenticateMobileRequest($db) {
    $headers = function_exists('getallheaders') ? getallheaders() : [];
    $token = trim($headers['X-Api-Token'] ?? $headers['x-api-token'] ?? ($_SERVER['HTTP_X_API_TOKEN'] ?? ''));

    if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
        return null;
    }

    $stmt = $db->prepare(
        "SELECT s.id, s.firstname, s.lastname, s.email,
                s.exam_type, s.target_band, s.baseline_band, s.test_date
         FROM api_tokens t
         JOIN students s ON s.id = t.student_id
         WHERE t.token = ?
           AND t.revoked_at IS NULL
           AND t.expires_at > NOW()"
    );
    $stmt->execute([$token]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    return $student ? normalizeMobileStudent($student) : null;
}

/**
 * PDO returns DECIMAL/DATE columns as strings; the app expects target_band/baseline_band as
 * numbers (or null) and the rest as plain strings (or null). Every endpoint that serializes a
 * student row -- this file, mobile_login.php, mobile_update_goal.php -- should pass it through
 * this so the shape is identical everywhere.
 */
function normalizeMobileStudent(array $row): array {
    $row['target_band'] = $row['target_band'] !== null ? (float) $row['target_band'] : null;
    $row['baseline_band'] = $row['baseline_band'] !== null ? (float) $row['baseline_band'] : null;
    return $row;
}

/**
 * Call this at the top of a mobile endpoint to require auth.
 * Sends a 401 and exits if there's no valid token.
 */
function requireMobileAuth($db) {
    $student = authenticateMobileRequest($db);
    if (!$student) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized. Missing or invalid token.']);
        exit();
    }
    return $student;
}
