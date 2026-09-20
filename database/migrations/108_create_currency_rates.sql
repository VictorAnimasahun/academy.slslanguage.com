-- ============================================================
-- Migration 108 -- currency_rates: Naira -> Pounds/Dollars for price display
--
-- Course prices are stored in Naira (migration 107). includes/currency.php
-- converts them for visitors outside Nigeria. rate_per_ngn = how much of that
-- currency 1 Naira buys (NGN itself = 1). The code refreshes GBP/USD from a free
-- public feed at most once a day; you can also fetch or edit them in
-- sls-admin -> Currency Rates. Seeded with the rates read on 2026-09-20.
-- Explicit utf8mb4, no foreign keys, idempotent. LOCAL first, then LIVE.
-- ============================================================

CREATE TABLE IF NOT EXISTS currency_rates (
    code          CHAR(3) NOT NULL PRIMARY KEY,
    rate_per_ngn  DECIMAL(18,10) NOT NULL,
    updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    checked_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO currency_rates (code, rate_per_ngn) VALUES
    ('NGN', 1.0000000000),
    ('GBP', 0.0005500000),
    ('USD', 0.0007490000);

-- Verify: SELECT * FROM currency_rates;
-- Rollback: DROP TABLE currency_rates;   (display falls back to built-in rates)
