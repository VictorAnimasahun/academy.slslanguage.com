<?php
// /academy/includes/protected_reading_resources.php
// Registry of protected slide-deck resources served by resources/protected_viewer/.
// Each entry: which subscription tier unlocks it (matches tier_access.php),
// a display title, and the folder under protected_resources/ holding its
// manifest.json + numbered slide files (produced by the one-time PPTX
// extraction, not by any runtime upload flow).

if (defined('PROTECTED_READING_RESOURCES_LOADED')) return;
define('PROTECTED_READING_RESOURCES_LOADED', true);

// Who may open a deck: anyone enrolled in one of its courses (plus staff and testers, via course_lock.php). It used to be
// a subscription tier, which locked CELPIP Crash Course students out of Parts 3 and 4 although their course teaches
// them (founder, 2026-10-21: every CELPIP course uses these decks -- see documentation/CONTENT_BANK_MAP.md).
const CELPIP_COURSE_FOLDERS = ['CELPIP_Gen_1Mo', 'CELPIP_Gen_2Mo', 'CELPIP_Gen_3Mo'];
const PROTECTED_READING_RESOURCES = [
    'celpip_reading_overview' => [
        'title'   => 'CELPIP Reading Test — Overview',
        'courses' => CELPIP_COURSE_FOLDERS,
    ],
    'celpip_reading_pt1' => [
        'title'   => 'CELPIP Reading Part 1 — Reading Correspondence',
        'courses' => CELPIP_COURSE_FOLDERS,
    ],
    'celpip_reading_pt2' => [
        'title'   => 'CELPIP Reading Part 2 — Reading to Apply a Diagram',
        'courses' => CELPIP_COURSE_FOLDERS,
    ],
    'celpip_reading_pt3' => [
        'title'   => 'CELPIP Reading Part 3 — Reading for Information',
        'courses' => CELPIP_COURSE_FOLDERS,
    ],
    'celpip_reading_pt4' => [
        'title'   => 'CELPIP Reading Part 4 — Reading for Viewpoints',
        'courses' => CELPIP_COURSE_FOLDERS,
    ],
    'celpip_reading_paraphrases' => [
        'title'   => 'CELPIP Reading — Identifying Paraphrases',
        'courses' => CELPIP_COURSE_FOLDERS,
    ],
    'celpip_reading_summaries' => [
        'title'   => 'CELPIP Reading — Identifying Summaries',
        'courses' => CELPIP_COURSE_FOLDERS,
    ],
];

function protected_reading_unlocked(string $key): bool {
    require_once INCLUDES_PATH . '/course_lock.php';
    return isset(PROTECTED_READING_RESOURCES[$key])
        && is_enrolled_in_any_course(course_ids_for_folders(PROTECTED_READING_RESOURCES[$key]['courses']));
}
