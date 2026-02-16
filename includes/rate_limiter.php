<?php
// /academy/includes/rate_limiter.php

// Load API config if not already loaded (with protection against redefinition)
if (!defined('API_KEYS_LOADED')) {
    if (defined('CONFIG_PATH') && file_exists(CONFIG_PATH . '/api_keys.php')) {
        require_once CONFIG_PATH . '/api_keys.php';
    } else {
        // Fallback defaults if config not found
        if (!defined('MAX_REQUESTS_PER_USER_PER_DAY')) {
            define('MAX_REQUESTS_PER_USER_PER_DAY', 10);
        }
        if (!defined('MAX_REQUESTS_PER_USER_PER_HOUR')) {
            define('MAX_REQUESTS_PER_USER_PER_HOUR', 3);
        }
    }
}

class RateLimiter {
    private $db;
    
    // Feature-specific limits (can be customized per feature)
    private $limits = [
        'essay_analysis' => MAX_REQUESTS_PER_USER_PER_DAY,
        'speaking_feedback' => MAX_REQUESTS_PER_USER_PER_DAY,
        'writing_feedback' => MAX_REQUESTS_PER_USER_PER_DAY,
        'general' => MAX_REQUESTS_PER_USER_PER_DAY
    ];
    
    public function __construct($database) {
        $this->db = $database;
        $this->createTableIfNotExists();
    }
    
    private function createTableIfNotExists() {
        $sql = "CREATE TABLE IF NOT EXISTS api_usage_log (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            api_type VARCHAR(50) NOT NULL,
            endpoint VARCHAR(100) NOT NULL,
            batch_id VARCHAR(50) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_user_time (user_id, created_at),
            INDEX idx_user_type (user_id, api_type),
            INDEX idx_batch (batch_id)
        )";
        
        try {
            $this->db->exec($sql);
        } catch (PDOException $e) {
            error_log("Rate limiter table creation error: " . $e->getMessage());
        }
    }
    
    /**
     * Check if user can make a request
     * @return array ['allowed' => bool, 'message' => string, 'remaining' => int]
     */
    public function checkLimit($userId, $apiType = 'general') {
        // Check hourly limit
        $hourlyCount = $this->getRequestCount($userId, $apiType, 'hour');
        if ($hourlyCount >= MAX_REQUESTS_PER_USER_PER_HOUR) {
            return [
                'allowed' => false,
                'message' => 'Rate limit exceeded. You can make ' . MAX_REQUESTS_PER_USER_PER_HOUR . ' requests per hour. Please try again later.',
                'remaining' => 0,
                'reset_time' => $this->getResetTime('hour')
            ];
        }
        
        // Check daily limit
        $dailyCount = $this->getRequestCount($userId, $apiType, 'day');
        if ($dailyCount >= MAX_REQUESTS_PER_USER_PER_DAY) {
            return [
                'allowed' => false,
                'message' => 'Daily limit reached. You can make ' . MAX_REQUESTS_PER_USER_PER_DAY . ' requests per day. Come back tomorrow!',
                'remaining' => 0,
                'reset_time' => $this->getResetTime('day')
            ];
        }
        
        return [
            'allowed' => true,
            'message' => 'Request allowed',
            'remaining' => MAX_REQUESTS_PER_USER_PER_DAY - $dailyCount,
            'hourly_remaining' => MAX_REQUESTS_PER_USER_PER_HOUR - $hourlyCount
        ];
    }
    
    /**
     * Check if user can make multiple requests at once (batch submission)
     * Example: Submitting all 8 CELPIP speaking tasks at once
     * 
     * @param int $userId - User ID
     * @param string $feature - Feature name (e.g., 'speaking_feedback', 'essay_analysis')
     * @param int $count - Number of requests in batch
     * @return array ['allowed' => bool, 'message' => string, 'remaining' => int]
     */
    public function checkBatchLimit($userId, $feature, $count) {
        // Validate feature
        if (!isset($this->limits[$feature])) {
            $feature = 'general';
        }
        
        $currentUsage = $this->getCurrentUsage($userId, $feature);
        $limit = $this->limits[$feature];
        
        // Check if batch would exceed daily limit
        if (($currentUsage + $count) > $limit) {
            return [
                'allowed' => false,
                'message' => "Submitting $count items would exceed your daily limit. You have " . 
                            max(0, $limit - $currentUsage) . " requests remaining.",
                'remaining' => max(0, $limit - $currentUsage),
                'requested' => $count,
                'limit' => $limit,
                'current_usage' => $currentUsage
            ];
        }
        
        // Check hourly limit for batch
        $hourlyCount = $this->getRequestCount($userId, $feature, 'hour');
        if (($hourlyCount + $count) > MAX_REQUESTS_PER_USER_PER_HOUR) {
            return [
                'allowed' => false,
                'message' => "Submitting $count items would exceed your hourly limit. " .
                            "You can submit " . max(0, MAX_REQUESTS_PER_USER_PER_HOUR - $hourlyCount) . 
                            " more items this hour.",
                'remaining' => max(0, MAX_REQUESTS_PER_USER_PER_HOUR - $hourlyCount),
                'requested' => $count,
                'reset_time' => $this->getResetTime('hour')
            ];
        }
        
        return [
            'allowed' => true,
            'message' => 'Batch request allowed',
            'remaining' => $limit - $currentUsage - $count,
            'will_use' => $count,
            'new_total' => $currentUsage + $count
        ];
    }
    
    /**
     * Get current usage for a specific feature today
     * 
     * @param int $userId
     * @param string $feature
     * @return int
     */
    private function getCurrentUsage($userId, $feature) {
        return $this->getRequestCount($userId, $feature, 'day');
    }
    
    private function getRequestCount($userId, $apiType, $period) {
        $timeConstraint = $period === 'hour' 
            ? "created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)"
            : "created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)";
        
        $sql = "SELECT COUNT(*) FROM api_usage_log 
                WHERE user_id = ? AND api_type = ? AND $timeConstraint";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $apiType]);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Rate limiter count error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Log a successful API request
     * 
     * @param int $userId
     * @param string $apiType
     * @param string $endpoint
     * @param string|null $batchId - Optional batch ID for grouping related requests
     * @return bool
     */
    public function logRequest($userId, $apiType, $endpoint, $batchId = null) {
        $sql = "INSERT INTO api_usage_log (user_id, api_type, endpoint, batch_id) VALUES (?, ?, ?, ?)";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $apiType, $endpoint, $batchId]);
            return true;
        } catch (PDOException $e) {
            error_log("Rate limiter log error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Log multiple requests as a batch
     * 
     * @param int $userId
     * @param string $apiType
     * @param string $endpoint
     * @param int $count - Number of requests in batch
     * @return array ['success' => bool, 'batch_id' => string, 'logged' => int]
     */
    public function logBatchRequest($userId, $apiType, $endpoint, $count) {
        $batchId = uniqid('batch_', true);
        $logged = 0;
        
        try {
            $this->db->beginTransaction();
            
            $sql = "INSERT INTO api_usage_log (user_id, api_type, endpoint, batch_id) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            
            for ($i = 0; $i < $count; $i++) {
                $stmt->execute([$userId, $apiType, $endpoint, $batchId]);
                $logged++;
            }
            
            $this->db->commit();
            
            return [
                'success' => true,
                'batch_id' => $batchId,
                'logged' => $logged,
                'message' => "Successfully logged $logged requests"
            ];
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Rate limiter batch log error: " . $e->getMessage());
            
            return [
                'success' => false,
                'batch_id' => null,
                'logged' => 0,
                'error' => $e->getMessage()
            ];
        }
    }
    
    private function getResetTime($period) {
        $now = new DateTime();
        if ($period === 'hour') {
            $now->modify('+1 hour');
            $now->setTime((int)$now->format('H'), 0, 0);
        } else {
            $now->modify('+1 day');
            $now->setTime(0, 0, 0);
        }
        return $now->format('Y-m-d H:i:s');
    }
    
    /**
     * Get user's current usage stats
     * 
     * @param int $userId
     * @return array
     */
    public function getUserStats($userId) {
        $hourlyCount = $this->getRequestCount($userId, 'general', 'hour');
        $dailyCount = $this->getRequestCount($userId, 'general', 'day');
        
        return [
            'hourly_used' => $hourlyCount,
            'hourly_limit' => MAX_REQUESTS_PER_USER_PER_HOUR,
            'daily_used' => $dailyCount,
            'daily_limit' => MAX_REQUESTS_PER_USER_PER_DAY,
            'hourly_remaining' => MAX_REQUESTS_PER_USER_PER_HOUR - $hourlyCount,
            'daily_remaining' => MAX_REQUESTS_PER_USER_PER_DAY - $dailyCount
        ];
    }
    
    /**
     * Get detailed usage stats for a specific feature
     * 
     * @param int $userId
     * @param string $feature
     * @return array
     */
    public function getFeatureStats($userId, $feature) {
        if (!isset($this->limits[$feature])) {
            $feature = 'general';
        }
        
        $hourlyCount = $this->getRequestCount($userId, $feature, 'hour');
        $dailyCount = $this->getRequestCount($userId, $feature, 'day');
        $limit = $this->limits[$feature];
        
        return [
            'feature' => $feature,
            'hourly_used' => $hourlyCount,
            'hourly_limit' => MAX_REQUESTS_PER_USER_PER_HOUR,
            'daily_used' => $dailyCount,
            'daily_limit' => $limit,
            'hourly_remaining' => MAX_REQUESTS_PER_USER_PER_HOUR - $hourlyCount,
            'daily_remaining' => $limit - $dailyCount,
            'can_use' => $dailyCount < $limit && $hourlyCount < MAX_REQUESTS_PER_USER_PER_HOUR
        ];
    }
    
    /**
     * Set custom limit for a specific feature
     * Useful for premium users or special features
     * 
     * @param string $feature
     * @param int $limit
     */
    public function setFeatureLimit($feature, $limit) {
        $this->limits[$feature] = $limit;
    }
    
    /**
     * Get batch usage statistics
     * 
     * @param int $userId
     * @param string $batchId
     * @return array
     */
    public function getBatchStats($userId, $batchId) {
        $sql = "SELECT COUNT(*) as count, MIN(created_at) as started_at, MAX(created_at) as completed_at
                FROM api_usage_log 
                WHERE user_id = ? AND batch_id = ?";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $batchId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Rate limiter batch stats error: " . $e->getMessage());
            return null;
        }
    }
}