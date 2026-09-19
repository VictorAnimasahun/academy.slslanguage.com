-- 100 — Week Brief infrastructure (per-week summary, resources, vocab list)
-- Every course "week" is a row in `modules`. These three tables hang extra,
-- optional content off a module so the app can show (and later message) a
-- "Week Brief": what the week covers, the vocab list, exercises/readings,
-- and the tests/quizzes due. Everything is optional -- an empty week still
-- renders cleanly ("nothing added yet"), so the system can ship before the
-- content exists. Tests already attached via course_pacing_items are merged
-- in automatically by includes/week_brief.php; no duplicate entry needed.
--
-- No FOREIGN KEY constraints on purpose: live's `modules`/`vocabulary_words`
-- have a different id type/engine than local, and MySQL rejected the FKs there
-- (errno 150). Plain indexed columns work everywhere; orphan rows are harmless
-- (the composer only ever reads rows for a module that exists).

CREATE TABLE IF NOT EXISTS week_briefs (
    module_id  INT UNSIGNED NOT NULL,
    summary    TEXT NULL,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (module_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS week_resources (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    module_id     INT UNSIGNED NOT NULL,
    item_type     ENUM('exercise','reading','video','practice_test','mock_test','quiz','note') NOT NULL,
    title         VARCHAR(255) NOT NULL,
    url           VARCHAR(500) NULL,
    test_code     VARCHAR(64) NULL,
    status        ENUM('planned','ready') NOT NULL DEFAULT 'planned',
    display_order INT NOT NULL DEFAULT 0,
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_week_resources_module (module_id, display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS week_vocab_words (
    module_id     INT UNSIGNED NOT NULL,
    word_id       INT UNSIGNED NOT NULL,
    display_order INT NOT NULL DEFAULT 0,
    PRIMARY KEY (module_id, word_id),
    KEY idx_week_vocab_word (word_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Verify: SHOW TABLES LIKE 'week_%';  (expect 3 rows)
