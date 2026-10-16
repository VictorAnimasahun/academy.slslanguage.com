-- ============================================================
-- Migration 150 -- leads, lead_events, newsletter_issues, email_queue
--
-- Marketing/lead-generation foundation (Item 1 of the 2026-10-16 marketing plan). One shared
-- `leads` list that every public form writes to (homepage newsletter first; later the mini-tests,
-- the Professionals/Teachers interest forms, PTE "Register interest", events...), tagged by who
-- the person is (audience), what they want (goal) and where they came from (source + UTM).
--
--   leads              -- one row per email address (de-duplicated), with NDPR consent record
--   lead_events        -- what each lead did, in order (signed up, took a test, unsubscribed...)
--   newsletter_issues  -- weekly newsletter built from the blog feed; saved as a DRAFT and only
--                         sent after a person approves it in sls-admin
--   email_queue        -- every outgoing marketing email; a cron job sends it in small batches
--                         (live uses PHP mail() through cPanel, which caps emails per hour)
--
-- Deliberately NOT here: the follow-up email sequences (email_sequences etc.). Those come with
-- Item 2, designed against the real sequence content, rather than guessed now.
--
-- Separate from `students` on purpose: a lead is anyone who left an email, not an account.
-- leads.student_id links the two once a lead registers (nullable, set later).
--
-- Idempotent (MySQL 5.7-safe): CREATE TABLE IF NOT EXISTS only, no data changes. Collation is
-- utf8mb4_unicode_ci (not 8.0's utf8mb4_0900_ai_ci), so it exists on both engines.
-- ============================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS leads (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL COMMENT 'stored lower-cased; one row per address',
    first_name VARCHAR(100) NULL,
    last_name VARCHAR(100) NULL,
    phone VARCHAR(40) NULL COMMENT 'WhatsApp/phone, optional',
    audience ENUM('exam','professional','teacher','general') NOT NULL DEFAULT 'general',
    goal VARCHAR(100) NULL COMMENT 'e.g. canada_pr, study_abroad, job_interview',
    level VARCHAR(50) NULL COMMENT 'e.g. a mini-test band/CLB estimate',
    source VARCHAR(60) NOT NULL COMMENT 'first form that captured them, e.g. homepage_newsletter',
    utm_source VARCHAR(100) NULL,
    utm_medium VARCHAR(100) NULL,
    utm_campaign VARCHAR(100) NULL,
    consent_marketing TINYINT(1) NOT NULL DEFAULT 0,
    consent_text VARCHAR(255) NULL COMMENT 'exact wording they ticked (NDPR record)',
    consent_at DATETIME NULL,
    consent_ip VARCHAR(45) NULL,
    unsubscribe_token CHAR(64) NOT NULL COMMENT 'random; used in one-click unsubscribe links',
    unsubscribed_at DATETIME NULL,
    student_id INT UNSIGNED NULL COMMENT 'set once this lead has a students account',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uk_leads_email (email),
    UNIQUE KEY uk_leads_unsub_token (unsubscribe_token),
    KEY idx_leads_audience (audience),
    KEY idx_leads_source (source),
    KEY idx_leads_created (created_at),
    KEY idx_leads_student (student_id),
    CONSTRAINT fk_leads_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS lead_events (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    lead_id INT UNSIGNED NOT NULL,
    event_type VARCHAR(50) NOT NULL COMMENT 'e.g. subscribed, resubscribed, unsubscribed, test_taken, email_sent',
    source VARCHAR(60) NULL,
    details TEXT NULL COMMENT 'small JSON blob; TEXT rather than JSON type for engine portability',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_lead_events_lead (lead_id, created_at),
    KEY idx_lead_events_type (event_type),
    CONSTRAINT fk_lead_events_lead FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS newsletter_issues (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    subject VARCHAR(255) NOT NULL,
    intro TEXT NULL COMMENT 'optional editor note shown above the posts',
    html_body MEDIUMTEXT NOT NULL,
    text_body MEDIUMTEXT NULL,
    post_urls TEXT NULL COMMENT 'JSON list of blog post URLs included, so a post is never re-sent',
    status ENUM('draft','approved','sending','sent','discarded') NOT NULL DEFAULT 'draft',
    approved_by VARCHAR(255) NULL COMMENT 'admin who approved it',
    approved_at DATETIME NULL,
    sent_at DATETIME NULL,
    recipients_count INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_newsletter_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS email_queue (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    lead_id INT UNSIGNED NULL,
    to_email VARCHAR(255) NOT NULL,
    to_name VARCHAR(200) NULL,
    subject VARCHAR(255) NOT NULL,
    html_body MEDIUMTEXT NOT NULL,
    text_body MEDIUMTEXT NULL,
    kind VARCHAR(40) NOT NULL COMMENT 'e.g. welcome, newsletter, sequence',
    newsletter_issue_id INT UNSIGNED NULL,
    status ENUM('pending','sending','sent','failed','cancelled') NOT NULL DEFAULT 'pending',
    attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
    last_error VARCHAR(500) NULL,
    send_after DATETIME NOT NULL COMMENT 'not sent before this time',
    sent_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_queue_due (status, send_after),
    KEY idx_queue_lead (lead_id),
    KEY idx_queue_issue (newsletter_issue_id),
    CONSTRAINT fk_queue_lead FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE SET NULL,
    CONSTRAINT fk_queue_issue FOREIGN KEY (newsletter_issue_id) REFERENCES newsletter_issues(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Verify: SHOW TABLES LIKE 'lead%'; SHOW TABLES LIKE '%_queue'; SHOW TABLES LIKE 'newsletter%';
--         DESCRIBE leads;
-- Rollback (drops all captured leads -- back up first): DROP TABLE email_queue, newsletter_issues, lead_events, leads;
