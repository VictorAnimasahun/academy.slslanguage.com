<?php
// /academy/includes/protected_reading_resources.php
// Registry of protected slide-deck resources served by resources/protected_viewer/.
// Each entry: which subscription tier unlocks it (matches tier_access.php),
// a display title, and the folder under protected_resources/ holding its
// manifest.json + numbered slide files (produced by the one-time PPTX
// extraction, not by any runtime upload flow).

if (defined('PROTECTED_READING_RESOURCES_LOADED')) return;
define('PROTECTED_READING_RESOURCES_LOADED', true);

const PROTECTED_READING_RESOURCES = [
    'celpip_reading_pt1' => [
        'title'    => 'CELPIP Reading Part 1 — Reading a Correspondence',
        'min_tier' => 'intermediate',
    ],
    'celpip_reading_pt2' => [
        'title'    => 'CELPIP Reading Part 2 — Reading to Apply a Diagram',
        'min_tier' => 'intermediate',
    ],
    'celpip_reading_pt3' => [
        'title'    => 'CELPIP Reading Part 3 — Reading for Information',
        'min_tier' => 'advanced',
    ],
    'celpip_reading_pt4' => [
        'title'    => 'CELPIP Reading Part 4 — Reading for Viewpoints',
        'min_tier' => 'advanced', // also linked from the 2-month plan, which has no 'fluent' tier
    ],
];
