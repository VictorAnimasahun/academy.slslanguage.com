-- Migration 001: Create subscriptions table
-- Safe to run multiple times (IF NOT EXISTS)
-- References: students.id (existing table)
-- Applied on LOCAL:  [ ] Date: ___________
-- Applied on LIVE:   [ ] Date: ___________

CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id`            int unsigned    NOT NULL AUTO_INCREMENT,
  `student_id`    int unsigned    NOT NULL,
  `tier`          ENUM('beginner','intermediate','advanced','fluent') NOT NULL DEFAULT 'beginner',
  `paypal_txn_id` varchar(255)    DEFAULT NULL,
  `start_date`    date            NOT NULL,
  `expiry_date`   date            DEFAULT NULL   COMMENT 'NULL = no expiry (free/beginner tier)',
  `status`        ENUM('active','expired','cancelled') NOT NULL DEFAULT 'active',
  `created_at`    timestamp       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    timestamp       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_student_status` (`student_id`, `status`),
  KEY `idx_expiry` (`expiry_date`),
  CONSTRAINT `fk_sub_student`
    FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Verify: SELECT COUNT(*) FROM subscriptions;
