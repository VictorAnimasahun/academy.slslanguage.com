-- ============================================================
-- Migration 129 -- Step 1: the course definition record + the instructor's answers to decisions 1-11 (2026-09-26)
--
-- New columns on courses (the fields Step 1 needs and the platform had no place for):
--   exam_variant          'Academic' | 'General' | ...   (category alone said only IELTS / CELPIP / PTE)
--   length_months         1 | 2 | 3 (NULL when a course has no fixed length)
--   access_extra_months   you own a course for its length PLUS this many months (rule: 1)
--   free_preview_classes  how many classes are free to everyone (rule: Class 1 only = 1)
--   delivery              self_paced | live | both     (all self_paced for now)
--   price_currency        currency the stored price is in (NGN for Selar-sold plans, USD for the older programmes)
--
-- Answers applied:
--   2  names: the Selar standard -- 1 month = "Crash Course", 2 and 3 months = "Masterclass": "<Exam> Crash Course — 1 Month", "<Exam> Masterclass — 2 Months"/"— 3 Months"
--   5  buy links: ONE Selar product per LENGTH (1/2/3 months), shared by every course of that length -- the student
--      pays, then chooses the course on the dashboard (config/selar_purchases.php). Nothing to change; a course has no
--      link of its own by design. (My earlier "one link per course" requirement was wrong.)
--   6  free preview: Class 1 only (the Academic Crash Course keeps its whole one-page Introduction module: 5)
--   7  ownership: length + 1 month for every plan
--   8  delivery: the option exists on every course; all are self_paced for now
--   9  instructor: Victor Animasahun on every course; NO stored star ratings (students rate through course_ratings)
--   10 BEL (Basic English Language) becomes a course (Coming Soon: it has an overview page only)
--   11 exam variant is a field
--   (3) CELPIP 2-Month 8 weeks / 16 classes: agreed as the target; reshaping its 9 weeks is Step 2 (the schedule).
--
-- NOT idempotent at the top (ALTER TABLE ... ADD COLUMN); everything after it can be re-run. Tested on MySQL 5.7.
-- ============================================================

SET NAMES utf8mb4;

ALTER TABLE courses
    ADD COLUMN exam_variant VARCHAR(30) NULL AFTER category,
    ADD COLUMN price_currency CHAR(3) NOT NULL DEFAULT 'NGN' AFTER compare_price,
    ADD COLUMN length_months TINYINT UNSIGNED NULL AFTER selar_months,
    ADD COLUMN access_extra_months TINYINT UNSIGNED NOT NULL DEFAULT 1 AFTER length_months,
    ADD COLUMN free_preview_classes TINYINT UNSIGNED NOT NULL DEFAULT 1 AFTER access_extra_months,
    ADD COLUMN delivery ENUM('self_paced','live','both') NOT NULL DEFAULT 'self_paced' AFTER free_preview_classes;

-- exam variant
UPDATE courses SET exam_variant = 'Academic' WHERE folder_name IN ('IELTS_Aca_Mst','IELTS_Aca_Crash','IELTS_Aca_1Mo','IELTS_Aca_2Mo','IELTS_Aca_3Mo','PTE_Gen_1Mo','PTE_Gen_2Mo','PTE_Gen_3Mo');
UPDATE courses SET exam_variant = 'General'  WHERE folder_name IN ('IELTS_Gen_Mst','IELTS_Gen_1Mo','IELTS_Gen_2Mo','CELPIP_Gen_1Mo','CELPIP_Gen_2Mo','CELPIP_Gen_3Mo');

-- length (the crash courses are one-month programmes; the Academic Masterclass is 8 weeks = 2 months)
UPDATE courses SET length_months = 1 WHERE folder_name IN ('IELTS_Crash_Course','CELPIP_Crash_Course','IELTS_Aca_Crash','IELTS_Gen_1Mo','CELPIP_Gen_1Mo','IELTS_Aca_1Mo','PTE_Gen_1Mo');
UPDATE courses SET length_months = 2 WHERE folder_name IN ('IELTS_Aca_Mst','IELTS_Gen_2Mo','CELPIP_Gen_2Mo','IELTS_Aca_2Mo','PTE_Gen_2Mo');
UPDATE courses SET length_months = 3 WHERE folder_name IN ('IELTS_Gen_Mst','CELPIP_Gen_3Mo','IELTS_Aca_3Mo','PTE_Gen_3Mo');

-- free preview: Class 1 only (default 1); the Crash Course's Introduction is one page holding 5 short lessons
UPDATE courses SET free_preview_classes = 5 WHERE folder_name = 'IELTS_Aca_Crash';
UPDATE lessons l JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
   SET l.min_tier = IF(m.min_tier = 'beginner', 'intermediate', m.min_tier)
 WHERE c.folder_name IN ('IELTS_Aca_Mst','CELPIP_Gen_2Mo','CELPIP_Gen_3Mo') AND m.module_order = 1 AND l.lesson_order = 2 AND l.min_tier = 'beginner';

-- currency: courses with a Selar month setting are priced in naira; the older programmes' small prices are dollars
UPDATE courses SET price_currency = 'NGN' WHERE selar_months IS NOT NULL;
UPDATE courses SET price_currency = 'USD' WHERE selar_months IS NULL AND price > 0;
UPDATE courses SET price_currency = 'NGN' WHERE folder_name IN ('PTE_Gen_1Mo','PTE_Gen_2Mo','PTE_Gen_3Mo');   -- PTE plans: 25,000 / 45,000 / 60,000 naira (not on sale yet: Coming Soon)

-- names: the standard taken from the Selar product pages (1 month = Crash Course; 2 and 3 months = Masterclass):
--   "IELTS/CELPIP CRASH COURSE 2026 - Four Sections (1-Month Standard)", "IELTS/CELPIP Masterclass 2026 - ... (2-Month / 3 Months Standard)"
--   pattern here: "<Exam> Crash Course — 1 Month", "<Exam> Masterclass — 2 Months", "<Exam> Masterclass — 3 Months"
UPDATE courses SET title = 'IELTS General Crash Course — 1 Month'      WHERE folder_name = 'IELTS_Gen_1Mo';
UPDATE courses SET title = 'IELTS General Masterclass — 2 Months'      WHERE folder_name = 'IELTS_Gen_2Mo';
UPDATE courses SET title = 'IELTS General Masterclass — 3 Months'      WHERE folder_name = 'IELTS_Gen_Mst';
UPDATE courses SET title = 'CELPIP General Crash Course — 1 Month'     WHERE folder_name = 'CELPIP_Gen_1Mo';
UPDATE courses SET title = 'CELPIP General Masterclass — 2 Months'     WHERE folder_name = 'CELPIP_Gen_2Mo';
UPDATE courses SET title = 'CELPIP General Masterclass — 3 Months'     WHERE folder_name = 'CELPIP_Gen_3Mo';
UPDATE courses SET title = 'IELTS Academic Crash Course — 1 Month'     WHERE folder_name = 'IELTS_Aca_1Mo';
UPDATE courses SET title = 'IELTS Academic Masterclass — 2 Months'     WHERE folder_name = 'IELTS_Aca_2Mo';
UPDATE courses SET title = 'IELTS Academic Masterclass — 3 Months'     WHERE folder_name = 'IELTS_Aca_3Mo';
UPDATE courses SET title = 'PTE Academic Crash Course — 1 Month'       WHERE folder_name = 'PTE_Gen_1Mo';
UPDATE courses SET title = 'PTE Academic Masterclass — 2 Months'       WHERE folder_name = 'PTE_Gen_2Mo';
UPDATE courses SET title = 'PTE Academic Masterclass — 3 Months'       WHERE folder_name = 'PTE_Gen_3Mo';

-- buy links: the canonical Selar product pages (the share.google addresses resolve to these); one product per length
UPDATE courses SET buy_url = 'https://selar.com/sls_ielts_celpip_crash_course'            WHERE selar_months = 1;
UPDATE courses SET buy_url = 'https://selar.com/sls_ielts_celpip_masterclass_two_months'  WHERE selar_months = 2;
UPDATE courses SET buy_url = 'https://selar.com/sls_ielts_celpip_masterclass_three_months' WHERE selar_months = 3;

-- descriptions that still speak in months for the weekly structure
UPDATE courses SET description = REPLACE(REPLACE(description, 'Month 1 covers', 'Weeks 1-4 cover'), 'Month 2 delivers', 'Weeks 5-8 deliver') WHERE folder_name IN ('IELTS_Aca_2Mo','PTE_Gen_2Mo');

-- instructor and ratings
UPDATE courses SET instructor_name = 'Victor Animasahun';
UPDATE courses SET rating = NULL;

-- student ratings (empty until students rate; one rating per student per course)
CREATE TABLE IF NOT EXISTS course_ratings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id INT UNSIGNED NOT NULL,
    student_id INT UNSIGNED NOT NULL,
    rating TINYINT UNSIGNED NOT NULL,
    comment TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY course_ratings_one (course_id, student_id),
    KEY course_ratings_course (course_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- BEL (Basic English Language): the entry-level free course becomes a course; nothing is built beyond its overview page
INSERT INTO courses (title, folder_name, description, category, price, is_free, is_visible, availability, instructor_name, rating, total_lessons, delivery, price_currency)
SELECT 'Basic English Language', 'BEL', 'The entry-level English course: build the basics before you start test preparation.', 'English', 0.00, 1, 1, 'coming_soon', 'Victor Animasahun', NULL, 0, 'self_paced', 'NGN'
  FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM courses WHERE folder_name = 'BEL');

-- Verify
SELECT id, folder_name, title, exam_variant, length_months, free_preview_classes, delivery, price_currency, availability FROM courses ORDER BY id;
