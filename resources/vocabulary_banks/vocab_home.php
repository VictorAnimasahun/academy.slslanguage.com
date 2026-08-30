<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

// All active words ordered by sort_order
$words = $db->query("
    SELECT id, headword, phonetic, word_class, cefr_level, is_awl, definition, sort_order
    FROM vocabulary_words
    WHERE is_active = 1
    ORDER BY sort_order, headword
")->fetchAll(PDO::FETCH_ASSOC);

// Daily word — pure date math, no DB writes
$dailyWord = null;
if ($words) {
    $idx = (int)(date('z') + date('Y')) % count($words);
    $dailyWord = $words[$idx];
}

// A–Z filter
$filter = strtoupper(trim($_GET['letter'] ?? ''));

$userName = isset($_SESSION['user_firstname']) ? htmlspecialchars($_SESSION['user_firstname']) : 'Learner';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vocabulary Banks | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        /* ── Daily word hero ── */
        .hero {
            background: linear-gradient(135deg, #0b77ff 0%, #6366f1 100%);
            border-radius: 16px;
            padding: 2rem 2.2rem;
            color: #fff;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        .hero::after {
            content: '';
            position: absolute;
            right: -40px; top: -40px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,0.07);
        }
        .hero-label {
            font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.1em; opacity: 0.8; margin-bottom: 0.4rem;
        }
        .hero-word {
            font-size: 2.4rem; font-weight: 800; line-height: 1;
            margin-bottom: 0.3rem;
        }
        .hero-phonetic {
            font-size: 1rem; opacity: 0.8; font-style: italic; margin-bottom: 0.75rem;
        }
        .hero-meta { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem; }
        .hero-pill {
            background: rgba(255,255,255,0.2); border-radius: 20px;
            padding: 0.2rem 0.7rem; font-size: 0.78rem; font-weight: 600;
        }
        .hero-def {
            font-size: 1rem; opacity: 0.92; max-width: 520px;
            line-height: 1.55; margin-bottom: 1.25rem;
        }
        .hero-cta {
            display: inline-flex; align-items: center; gap: 0.4rem;
            background: #fff; color: #0b77ff;
            padding: 0.55rem 1.2rem; border-radius: 8px;
            font-weight: 700; font-size: 0.9rem; text-decoration: none;
            transition: opacity 0.15s;
        }
        .hero-cta:hover { opacity: 0.88; color: #0b77ff; }

        /* ── A–Z filter bar ── */
        .az-bar {
            display: flex; flex-wrap: wrap; gap: 0.3rem; margin-bottom: 1.25rem;
        }
        .az-btn {
            width: 34px; height: 34px; border-radius: 6px;
            border: 1px solid #e2e8f0; background: #fff;
            font-size: 0.82rem; font-weight: 600; color: #475569;
            cursor: pointer; text-align: center; line-height: 32px;
            text-decoration: none; display: inline-block;
            transition: background 0.15s, color 0.15s;
        }
        .az-btn:hover  { background: #f0f7ff; color: #0b77ff; }
        .az-btn.active { background: #0b77ff; color: #fff; border-color: #0b77ff; }

        /* ── Word list ── */
        .word-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 0.65rem;
        }
        .word-card {
            background: #fff; border-radius: 10px;
            padding: 0.85rem 1rem;
            box-shadow: 0 1px 5px rgba(15,23,42,0.06);
            text-decoration: none; color: inherit;
            border-left: 3px solid #0b77ff;
            display: block;
            transition: box-shadow 0.2s, border-color 0.2s;
        }
        .word-card:hover { box-shadow: 0 4px 14px rgba(11,119,255,0.12); color: inherit; }
        .word-card .hw { font-weight: 700; font-size: 0.97rem; color: #1e293b; }
        .word-card .wc { font-size: 0.76rem; color: #94a3b8; margin-top: 0.1rem; }
        .badge-cefr {
            display: inline-block; font-size: 0.68rem; font-weight: 700;
            padding: 0.1rem 0.45rem; border-radius: 4px;
            background: #f0fdf4; color: #166534; margin-left: 0.4rem;
        }
        .badge-awl {
            display: inline-block; font-size: 0.68rem; font-weight: 700;
            padding: 0.1rem 0.4rem; border-radius: 4px;
            background: #dbeafe; color: #1e40af; margin-left: 0.25rem;
        }
        .section-label {
            font-size: 0.78rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.07em; color: #64748b; margin-bottom: 0.9rem;
        }
    </style>
</head>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="mobile-overlay" id="mobileOverlay"></div>
<?php include INCLUDES_PATH . '/navbar.php'; ?>

<div class="main-wrapper flex-grow-1" style="flex:1;">
    <?php include INCLUDES_PATH . '/topbar.php'; ?>

    <main class="content p-4">
        <div style="max-width:780px;">

            <!-- ── Back link ── -->
            <a href="../resources_home.php" style="font-size:0.85rem;color:#64748b;text-decoration:none;" class="d-inline-flex align-items-center gap-1 mb-3">
                <i class="bi bi-chevron-left"></i> Resources
            </a>

            <!-- ── Daily word hero ── -->
            <?php if ($dailyWord): ?>
            <div class="hero">
                <div class="hero-label"><i class="bi bi-star-fill me-1"></i>Word of the Day</div>
                <div class="hero-word"><?= htmlspecialchars($dailyWord['headword']) ?></div>
                <?php if ($dailyWord['phonetic']): ?>
                <div class="hero-phonetic"><?= htmlspecialchars($dailyWord['phonetic']) ?></div>
                <?php endif; ?>
                <div class="hero-meta">
                    <span class="hero-pill"><?= htmlspecialchars($dailyWord['word_class']) ?></span>
                    <span class="hero-pill"><?= htmlspecialchars($dailyWord['cefr_level']) ?></span>
                    <?php if ($dailyWord['is_awl']): ?><span class="hero-pill">AWL</span><?php endif; ?>
                </div>
                <div class="hero-def"><?= htmlspecialchars($dailyWord['definition']) ?></div>
                <a href="word.php?id=<?= $dailyWord['id'] ?>" class="hero-cta">
                    Explore this word <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <?php endif; ?>

            <!-- ── Browse all words ── -->
            <div class="section-label">
                <i class="bi bi-journal-bookmark me-1"></i>
                All Words (<?= count($words) ?>)
            </div>

            <!-- A–Z filter -->
            <div class="az-bar">
                <a href="vocab_home.php" class="az-btn <?= !$filter ? 'active' : '' ?>">All</a>
                <?php foreach (range('A','Z') as $l): ?>
                <a href="vocab_home.php?letter=<?= $l ?>" class="az-btn <?= $filter === $l ? 'active' : '' ?>"><?= $l ?></a>
                <?php endforeach; ?>
            </div>

            <!-- Word grid -->
            <div class="word-grid">
                <?php
                $shown = 0;
                foreach ($words as $w):
                    if ($filter && strtoupper($w['headword'][0]) !== $filter) continue;
                    $shown++;
                ?>
                <a href="word.php?id=<?= $w['id'] ?>" class="word-card">
                    <div class="hw">
                        <?= htmlspecialchars($w['headword']) ?>
                        <span class="badge-cefr"><?= htmlspecialchars($w['cefr_level']) ?></span>
                        <?php if ($w['is_awl']): ?><span class="badge-awl">AWL</span><?php endif; ?>
                    </div>
                    <div class="wc"><?= htmlspecialchars($w['word_class']) ?></div>
                </a>
                <?php endforeach; ?>
                <?php if ($shown === 0): ?>
                <p class="text-muted" style="grid-column:1/-1;padding:1rem 0;">
                    No words starting with "<?= htmlspecialchars($filter) ?>" yet.
                </p>
                <?php endif; ?>
            </div>

            <?php include __DIR__ . '/context_vocab.php'; ?>

        </div>
    </main>
</div>

<?php include INCLUDES_PATH . '/adverts.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
