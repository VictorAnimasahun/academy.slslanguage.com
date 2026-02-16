<?php
// /academy/bootstrap.php

// Load paths
require_once __DIR__ . '/paths.php';

// Load database connection
require_once CONFIG_PATH . '/db_connect.php';

// Load API keys configuration
if (file_exists(CONFIG_PATH . '/api_keys.php')) {
    require_once CONFIG_PATH . '/api_keys.php';
} else {
    // Fallback defaults
    if (!defined('MAX_REQUESTS_PER_USER_PER_DAY')) {
        define('MAX_REQUESTS_PER_USER_PER_DAY', 10);
    }
    if (!defined('MAX_REQUESTS_PER_USER_PER_HOUR')) {
        define('MAX_REQUESTS_PER_USER_PER_HOUR', 3);
    }
}

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Global database query function
function executeQuery($db, $sql, $params = []) {
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return false;
    }
}

// Fetch unread message count globally
$unread_count = 0;

if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];

    $stmt = executeQuery($db, "
        SELECT COUNT(*) AS unread_count
        FROM broadcast_messages m
        LEFT JOIN broadcast_message_reads r 
            ON r.message_id = m.id AND r.student_id = ?
        WHERE (
            m.target_all_students = 1
            OR FIND_IN_SET(?, m.target_student_ids)
            OR EXISTS (
                SELECT 1 FROM enrollments e
                WHERE e.student_id = ? 
                AND FIND_IN_SET(e.course_id, m.target_course_ids)
            )
        )
        AND (r.id IS NULL)
    ", [$uid, $uid, $uid]);

    if ($stmt) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $unread_count = intval($row['unread_count']);
    }
}


// Global constants
define('MAX_DESCRIPTION_LENGTH', 80);
define('MAX_COURSE_RECOMMENDATIONS', 3);
define('MAX_ASSIGNMENTS_DISPLAY', 5);