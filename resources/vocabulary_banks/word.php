<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

$wordId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$wordId) { header("Location: vocab_home.php"); exit(); }

// Load word
$stmt = $db->prepare("SELECT * FROM vocabulary_words WHERE id = ? AND is_active = 1");
$stmt->execute([$wordId]);
$word = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$word) { header("Location: vocab_home.php"); exit(); }

// Load all active words (for prev/next nav)
$allWords = $db->query("SELECT id, headword FROM vocabulary_words WHERE is_active=1 ORDER BY sort_order, headword")->fetchAll(PDO::FETCH_ASSOC);
$wordIds  = array_column($allWords, 'id');
$pos      = array_search($wordId, $wordIds);
$prevId   = ($pos > 0)                    ? $wordIds[$pos - 1] : null;
$nextId   = ($pos < count($wordIds) - 1)  ? $wordIds[$pos + 1] : null;
$prevWord = $prevId ? $allWords[$pos - 1] : null;
$nextWord = $nextId ? $allWords[$pos + 1] : null;

// Random word (different from current)
$randomPool = array_filter($wordIds, fn($id) => $id !== $wordId);
$randomId   = $randomPool ? $randomPool[array_rand($randomPool)] : null;

// Load usage examples grouped by exam_type → skill
$usageStmt = $db->prepare("SELECT * FROM word_test_usages WHERE word_id=? ORDER BY exam_type, skill, sort_order");
$usageStmt->execute([$wordId]);
$usages = $usageStmt->fetchAll(PDO::FETCH_ASSOC);

$usagesByExam = [];
foreach ($usages as $u) {
    $usagesByExam[$u['exam_type']][$u['skill']][] = $u;
}

// Quiz question count for this word
$qCount = (int)$db->prepare("SELECT COUNT(*) FROM questions WHERE word_id=?")->execute([$wordId])
    ? (int)$db->query("SELECT COUNT(*) FROM questions WHERE word_id=$wordId")->fetchColumn()
    : 0;

// Helper to split comma-separated fields
function splitComma(?string $s): array {
    if (!$s) return [];
    return array_filter(array_map('trim', explode(',', $s)));
}

$synonyms    = splitComma($word['synonyms']);
$antonyms    = splitComma($word['antonyms']);
$collocations = splitComma($word['collocations']);
$wordFamily  = splitComma($word['word_family']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($word['headword']) ?> | Vocabulary | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        /* ── Word header ── */
        .word-header {
            background: linear-gradient(135deg, #0b77ff 0%, #6366f1 100%);
            border-radius: 16px; padding: 2rem 2.2rem; color: #fff;
            margin-bottom: 1.75rem; position: relative; overflow: hidden;
        }
        .word-header::after {
            content: ''; position: absolute; right: -30px; top: -30px;
            width: 180px; height: 180px; border-radius: 50%;
            background: rgba(255,255,255,0.07);
        }
        .word-main    { font-size: 2.6rem; font-weight: 800; line-height: 1; }
        .word-phonetic { font-size: 1rem; opacity: 0.8; font-style: italic; margin-top: 0.3rem; }
        .pill {
            display: inline-block; border-radius: 20px;
            padding: 0.2rem 0.7rem; font-size: 0.78rem; font-weight: 600;
        }
        .pill-glass  { background: rgba(255,255,255,0.2); color: #fff; }
        .pill-cefr   { background: #f0fdf4; color: #166534; }
        .pill-awl    { background: #dbeafe; color: #1e40af; }
        .pill-class  { background: #fef3c7; color: #92400e; }
        .pill-pink   { background: #fce7f3; color: #9d174d; }

        /* ── Sections ── */
        .vocab-section {
            background: #fff; border-radius: 12px; padding: 1.4rem 1.5rem;
            box-shadow: 0 1px 6px rgba(15,23,42,0.06); margin-bottom: 1.1rem;
        }
        .section-title {
            font-size: 0.76rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.08em; color: #64748b; margin-bottom: 0.85rem;
        }
        .definition-text { font-size: 1rem; color: #1e293b; line-height: 1.65; }
        .definition-secondary {
            font-size: 0.9rem; color: #475569; margin-top: 0.5rem;
            border-left: 3px solid #e2e8f0; padding-left: 0.75rem;
        }

        /* ── Syn/ant ── */
        .word-chip {
            display: inline-block; padding: 0.25rem 0.65rem;
            border-radius: 20px; font-size: 0.82rem; font-weight: 600;
            margin: 0.2rem; text-decoration: none;
        }
        .chip-syn { background: #f0fdf4; color: #166534; }
        .chip-ant { background: #fff1f2; color: #9f1239; }
        .chip-col { background: #f0f9ff; color: #0369a1; border: 1px solid #e0f2fe; }
        .chip-fam {
            background: #faf5ff; color: #7e22ce; border: 1px solid #ede9fe;
            font-size: 0.85rem; padding: 0.3rem 0.8rem; font-weight: 700;
        }

        /* ── Usage tabs ── */
        .nav-pills .nav-link        { font-size: 0.85rem; padding: .35rem .85rem; color: #475569; }
        .nav-pills .nav-link.active { background: #0b77ff; color: #fff; }
        .skill-group { margin-bottom: 1rem; }
        .skill-label {
            font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.07em; color: #94a3b8; margin-bottom: 0.5rem;
        }
        .usage-item {
            background: #f8fafc; border-radius: 8px; padding: 0.8rem 1rem;
            margin-bottom: 0.5rem; border-left: 3px solid #0b77ff;
        }
        .usage-item .sub { font-size: 0.75rem; color: #94a3b8; margin-bottom: 0.25rem; }
        .usage-item .sentence { font-size: 0.93rem; color: #1e293b; line-height: 1.55; }
        .usage-item .note { font-size: 0.8rem; color: #64748b; margin-top: 0.3rem; font-style: italic; }

        /* ── Quiz CTA ── */
        .quiz-cta {
            background: linear-gradient(135deg, #ec4899, #f43f5e);
            border-radius: 12px; padding: 1.2rem 1.5rem; color: #fff;
            display: flex; align-items: center; justify-content: space-between;
            gap: 1rem; text-decoration: none; transition: opacity 0.15s;
        }
        .quiz-cta:hover { opacity: 0.9; color: #fff; }
        .quiz-cta-label { font-weight: 700; font-size: 1rem; }
        .quiz-cta-sub   { font-size: 0.82rem; opacity: 0.85; margin-top: 0.15rem; }

        /* ── Prev / Next nav ── */
        .word-nav {
            display: flex; gap: 0.75rem; margin-top: 1.5rem; margin-bottom: 0.5rem;
        }
        .nav-btn {
            flex: 1; background: #fff; border-radius: 10px; padding: 0.8rem 1rem;
            box-shadow: 0 1px 5px rgba(15,23,42,0.06); text-decoration: none;
            color: #1e293b; transition: box-shadow 0.2s;
        }
        .nav-btn:hover { box-shadow: 0 4px 14px rgba(11,119,255,0.1); color: #0b77ff; }
        .nav-btn.prev { text-align: left; }
        .nav-btn.next { text-align: right; }
        .nav-btn .dir { font-size: 0.72rem; color: #94a3b8; text-transform: uppercase; letter-spacing: .06em; }
        .nav-btn .wrd { font-weight: 700; font-size: 0.95rem; }
    </style>
</head>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="mobile-overlay" id="mobileOverlay"></div>
<?php include INCLUDES_PATH . '/navbar.php'; ?>

<div class="main-wrapper flex-grow-1" style="flex:1;">
    <?php include INCLUDES_PATH . '/topbar.php'; ?>

    <main class="content p-4">
        <div style="max-width:720px;">

            <!-- Breadcrumb -->
            <a href="vocab_home.php" style="font-size:0.85rem;color:#64748b;text-decoration:none;" class="d-inline-flex align-items-center gap-1 mb-3">
                <i class="bi bi-chevron-left"></i> Vocabulary Banks
            </a>

            <!-- ── Word header ── -->
            <div class="word-header">
                <div class="word-main"><?= htmlspecialchars($word['headword']) ?></div>
                <?php if ($word['phonetic']): ?>
                <div class="word-phonetic"><?= htmlspecialchars($word['phonetic']) ?></div>
                <?php endif; ?>
                <div class="d-flex gap-2 flex-wrap mt-3">
                    <span class="pill pill-glass"><?= htmlspecialchars($word['word_class']) ?></span>
                    <span class="pill pill-glass"><?= htmlspecialchars($word['cefr_level']) ?></span>
                    <?php if ($word['is_awl']): ?><span class="pill pill-glass">AWL</span><?php endif; ?>
                </div>
            </div>

            <!-- ── Definition ── -->
            <div class="vocab-section">
                <div class="section-title"><i class="bi bi-book me-1"></i>Definition</div>
                <div class="definition-text"><?= htmlspecialchars($word['definition']) ?></div>
                <?php if ($word['secondary_definitions']): ?>
                <div class="definition-secondary">
                    <?= nl2br(htmlspecialchars($word['secondary_definitions'])) ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- ── Synonyms & Antonyms ── -->
            <?php if ($synonyms || $antonyms): ?>
            <div class="vocab-section">
                <div class="row">
                    <?php if ($synonyms): ?>
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <div class="section-title"><i class="bi bi-arrow-left-right me-1"></i>Synonyms</div>
                        <?php foreach ($synonyms as $s): ?>
                        <span class="word-chip chip-syn"><?= htmlspecialchars($s) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($antonyms): ?>
                    <div class="col-sm-6">
                        <div class="section-title"><i class="bi bi-slash-circle me-1"></i>Antonyms</div>
                        <?php foreach ($antonyms as $a): ?>
                        <span class="word-chip chip-ant"><?= htmlspecialchars($a) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- ── Collocations ── -->
            <?php if ($collocations): ?>
            <div class="vocab-section">
                <div class="section-title"><i class="bi bi-link-45deg me-1"></i>Common Collocations</div>
                <?php foreach ($collocations as $c): ?>
                <span class="word-chip chip-col"><?= htmlspecialchars($c) ?></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- ── Word family ── -->
            <?php if ($wordFamily): ?>
            <div class="vocab-section">
                <div class="section-title"><i class="bi bi-diagram-3 me-1"></i>Word Family</div>
                <div>
                    <?php foreach ($wordFamily as $f): ?>
                    <span class="word-chip chip-fam"><?= htmlspecialchars($f) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- ── Test usage examples ── -->
            <?php if ($usagesByExam): ?>
            <div class="vocab-section">
                <div class="section-title"><i class="bi bi-mortarboard me-1"></i>In the Exam</div>

                <!-- Tabs -->
                <ul class="nav nav-pills mb-3" id="examTabs" role="tablist">
                    <?php $first = true; foreach ($usagesByExam as $exam => $skills): ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $first ? 'active' : '' ?>"
                            data-bs-toggle="pill"
                            data-bs-target="#tab-<?= htmlspecialchars($exam) ?>"
                            type="button"><?= htmlspecialchars($exam) ?></button>
                    </li>
                    <?php $first = false; endforeach; ?>
                </ul>

                <div class="tab-content">
                    <?php $first = true; foreach ($usagesByExam as $exam => $skills): ?>
                    <div class="tab-pane fade <?= $first ? 'show active' : '' ?>" id="tab-<?= htmlspecialchars($exam) ?>">
                        <?php foreach ($skills as $skill => $rows): ?>
                        <div class="skill-group">
                            <div class="skill-label"><?= htmlspecialchars($skill) ?></div>
                            <?php foreach ($rows as $u): ?>
                            <div class="usage-item">
                                <?php if ($u['sub_section']): ?>
                                <div class="sub"><?= htmlspecialchars($u['sub_section']) ?></div>
                                <?php endif; ?>
                                <div class="sentence"><?= htmlspecialchars($u['example_sentence']) ?></div>
                                <?php if ($u['context_note']): ?>
                                <div class="note"><i class="bi bi-lightbulb me-1"></i><?= htmlspecialchars($u['context_note']) ?></div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php $first = false; endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- ── Quiz CTA ── -->
            <?php if ($qCount > 0): ?>
            <a href="word_quiz.php?word_id=<?= $wordId ?>" class="quiz-cta d-flex">
                <div>
                    <div class="quiz-cta-label"><i class="bi bi-lightning-charge-fill me-1"></i>Practice This Word</div>
                    <div class="quiz-cta-sub"><?= $qCount ?> question<?= $qCount !== 1 ? 's' : '' ?> — definition, gap-fill, word form</div>
                </div>
                <i class="bi bi-arrow-right-circle fs-3 align-self-center"></i>
            </a>
            <?php endif; ?>

            <!-- ── Prev / Next nav ── -->
            <div class="word-nav">
                <?php if ($prevWord): ?>
                <a href="word.php?id=<?= $prevWord['id'] ?>" class="nav-btn prev">
                    <div class="dir"><i class="bi bi-chevron-left"></i> Previous</div>
                    <div class="wrd"><?= htmlspecialchars($prevWord['headword']) ?></div>
                </a>
                <?php else: ?>
                <div class="nav-btn prev" style="opacity:.35;cursor:default;">
                    <div class="dir"><i class="bi bi-chevron-left"></i> Previous</div>
                    <div class="wrd">—</div>
                </div>
                <?php endif; ?>

                <?php if ($randomId): ?>
                <a href="word.php?id=<?= $randomId ?>" class="nav-btn" style="flex:0;text-align:center;padding:.8rem 1.2rem;" title="Random word">
                    <i class="bi bi-shuffle fs-5 text-muted"></i>
                </a>
                <?php endif; ?>

                <?php if ($nextWord): ?>
                <a href="word.php?id=<?= $nextWord['id'] ?>" class="nav-btn next">
                    <div class="dir">Next <i class="bi bi-chevron-right"></i></div>
                    <div class="wrd"><?= htmlspecialchars($nextWord['headword']) ?></div>
                </a>
                <?php else: ?>
                <div class="nav-btn next" style="opacity:.35;cursor:default;">
                    <div class="dir">Next <i class="bi bi-chevron-right"></i></div>
                    <div class="wrd">—</div>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </main>
</div>

<?php include INCLUDES_PATH . '/adverts.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
