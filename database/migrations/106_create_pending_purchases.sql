-- ============================================================
-- Migration 106 -- pending_purchases: Selar payments waiting to be used
--
-- One row per Selar order. Flow (see config/selar_purchases.php):
--   pending  = paid, no verified student with that email yet
--   claimed  = attached to a student, course not chosen yet
--   redeemed = student chose a course; subscription + enrolment created then
--   refunded = refunded (its subscription, if any, is cancelled)
-- selar_order_ref is UNIQUE so a resent order can never create a second row.
--
-- REQUIRES migration 105 (courses.selar_months) to be applied first, on both
-- local and live -- the code that uses this table reads that column.
-- No foreign keys (live rejected them elsewhere: errno 150). Explicit utf8mb4
-- (a table with no charset inherits the database's latin1). Idempotent.
-- Run on LOCAL first, then LIVE.
-- ============================================================

CREATE TABLE IF NOT EXISTS pending_purchases (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    buyer_email         VARCHAR(190) NOT NULL COMMENT 'stored trimmed + lowercase',
    duration_months     TINYINT UNSIGNED NOT NULL COMMENT '1, 2 or 3',
    selar_order_ref     VARCHAR(100) NOT NULL,
    status              ENUM('pending','claimed','redeemed','refunded') NOT NULL DEFAULT 'pending',
    student_id          INT UNSIGNED NULL,
    subscription_id     INT UNSIGNED NULL,
    redeemed_course_id  INT UNSIGNED NULL,
    redeemed_at         DATETIME NULL,
    created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_selar_order (selar_order_ref),
    KEY idx_buyer_email (buyer_email),
    KEY idx_student_status (student_id, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- enrollments should already have UNIQUE (student_id, course_id) per the platform docs.
-- Verify on live (redeeming relies on INSERT IGNORE + that index to avoid duplicates):
--   SHOW INDEX FROM enrollments;      -- expect a unique key covering student_id + course_id
-- If it is missing, first check there are no duplicate rows, then:
--   ALTER TABLE enrollments ADD UNIQUE KEY unique_enrollment (student_id, course_id);

-- Verify: SELECT COUNT(*) FROM pending_purchases;   -- 0
-- Rollback: DROP TABLE pending_purchases;
