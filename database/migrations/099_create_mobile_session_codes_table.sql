-- 099 — Create `mobile_session_codes` table
-- One-time, 60-second codes that let the sls_mobile WebView exchange a valid
-- API token for a normal PHP session cookie (see api/mobile_session.php).
-- Only a SHA-256 hash of the code is stored, so a DB leak exposes nothing usable.

CREATE TABLE IF NOT EXISTS mobile_session_codes (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    student_id INT UNSIGNED NOT NULL,
    code_hash CHAR(64) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uniq_code_hash (code_hash),
    KEY idx_expires_at (expires_at),
    CONSTRAINT fk_mobile_session_codes_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
