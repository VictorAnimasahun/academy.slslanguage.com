<?php
require_once dirname(__DIR__, 2) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+study+materials");
    exit();
}

// ── Book catalogue ─────────────────────────────────────────────────────────────
$books = [
    'vb01' => [
        'title'       => 'Astronomy & Stargazing',
        'series'      => 'VB01',
        'badge_bg'    => '#f3e8ff',
        'badge_color' => '#7c3aed',
        'stripe'      => 'linear-gradient(90deg,#7c3aed,#a855f7)',
        'docx'        => 'SLS-VB01_ Astronomy and Stargazing Vocabulary.docx',
        'description' => 'Expand your vocabulary around space, celestial objects, and astronomical phenomena.',
    ],
    'vb02' => [
        'title'       => 'Restaurants & Food',
        'series'      => 'VB02',
        'badge_bg'    => '#fff7ed',
        'badge_color' => '#ea580c',
        'stripe'      => 'linear-gradient(90deg,#ea580c,#f97316)',
        'docx'        => 'SLS-VB02_ Restaurants and Food Vocabulary.docx',
        'description' => 'Key vocabulary for describing food, dining experiences, and culinary contexts.',
    ],
    'vb03' => [
        'title'       => 'Sustainable Food',
        'series'      => 'VB03',
        'badge_bg'    => '#f0fdf4',
        'badge_color' => '#0f766e',
        'stripe'      => 'linear-gradient(90deg,#0f766e,#14b8a6)',
        'docx'        => 'SLS-VB03_ Sustainable Food Vocabulary.docx',
        'description' => 'Vocabulary for discussing sustainability, food systems, and environmental impact.',
    ],
    'vb04' => [
        'title'       => 'Technology & the Future',
        'series'      => 'VB04',
        'badge_bg'    => '#eff6ff',
        'badge_color' => '#1e40af',
        'stripe'      => 'linear-gradient(90deg,#1e40af,#3b82f6)',
        'docx'        => 'SLS-VB04_ Technology and the Future.docx',
        'description' => 'Modern vocabulary around digital innovation, AI, and future technology trends.',
    ],
];

$id = preg_replace('/[^a-z0-9]/', '', strtolower($_GET['id'] ?? ''));
if (!isset($books[$id])) {
    header("Location: index.php");
    exit();
}

$book     = $books[$id];
$docxPath = ACADEMY_ROOT . '/assets/downloads/vocabulary/' . $book['docx'];
$pdfFile  = preg_replace('/\.docx$/i', '.pdf', $book['docx']);
$pdfPath  = ACADEMY_ROOT . '/assets/downloads/vocabulary/' . $pdfFile;
$hasPdf   = file_exists($pdfPath);

// ── File serve ────────────────────────────────────────────────────────────────
if (isset($_GET['download'])) {
    $type = $_GET['download'];
    if ($type === 'docx' && file_exists($docxPath)) {
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment; filename="' . $book['docx'] . '"');
        header('Content-Length: ' . filesize($docxPath));
        readfile($docxPath);
        exit();
    }
    if ($type === 'pdf' && $hasPdf) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $pdfFile . '"');
        header('Content-Length: ' . filesize($pdfPath));
        readfile($pdfPath);
        exit();
    }
    http_response_code(404); exit('File not found.');
}

// Serve raw file for Mammoth fetch (no attachment header)
if (isset($_GET['raw'])) {
    if (file_exists($docxPath)) {
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Length: ' . filesize($docxPath));
        readfile($docxPath);
    }
    exit();
}

// Serve PDF inline for embed
if (isset($_GET['pdf']) && $hasPdf) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . $pdfFile . '"');
    header('Content-Length: ' . filesize($pdfPath));
    readfile($pdfPath);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($book['title']) ?> | Study Materials | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>

    <style>
        .book-header {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .book-stripe {
            width: 6px;
            border-radius: 6px;
            align-self: stretch;
            flex-shrink: 0;
            min-height: 60px;
        }
        .series-badge {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 3px 9px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 0.4rem;
        }
        .viewer-wrapper {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .viewer-toolbar {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid #f3f4f6;
            background: #fafafa;
        }
        .viewer-toolbar .badge-mode {
            font-size: 0.7rem;
            padding: 3px 8px;
            border-radius: 20px;
            background: #f3f4f6;
            color: #6b7280;
            font-weight: 600;
        }

        /* PDF embed */
        .pdf-embed {
            width: 100%;
            height: 78vh;
            border: none;
            display: block;
        }

        /* Mammoth HTML render */
        #docContent {
            padding: 2.5rem 3rem;
            min-height: 60vh;
        }
        #docContent h1, #docContent h2, #docContent h3 {
            font-family: Inter, system-ui, sans-serif;
            color: #111827;
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
        }
        #docContent p { margin-bottom: 0.75rem; line-height: 1.8; color: #1f2937; }
        #docContent strong { color: #111827; }
        #docContent table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
            font-size: 0.9rem;
        }
        #docContent th, #docContent td {
            border: 1px solid #e5e7eb;
            padding: 0.5rem 0.75rem;
            text-align: left;
        }
        #docContent th { background: #f9fafb; font-weight: 600; }
        #docContent img { max-width: 180px; height: auto; }

        #docLoading { padding: 3rem; text-align: center; color: #9ca3af; }
        #docError   { padding: 2rem; }
    </style>
</head>
<body class="light">
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <div class="main-wrapper flex-grow-1" style="flex:1;">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>

        <main class="content p-4">
            <div class="container-fluid" style="max-width:1000px;">

                <!-- Breadcrumb -->
                <nav class="mb-3" style="font-size:0.85rem;">
                    <a href="index.php" class="text-decoration-none text-muted">
                        <i class="bi bi-arrow-left me-1"></i>Study Materials
                    </a>
                </nav>

                <!-- Book header -->
                <div class="book-header">
                    <div class="book-stripe" style="background: <?= $book['stripe'] ?>;"></div>
                    <div>
                        <span class="series-badge"
                              style="background:<?= $book['badge_bg'] ?>;color:<?= $book['badge_color'] ?>;">
                            <?= htmlspecialchars($book['series']) ?>
                        </span>
                        <h1 class="fw-bold mb-1" style="font-size:1.6rem;">
                            <?= htmlspecialchars($book['title']) ?>
                        </h1>
                        <p class="text-muted mb-0" style="font-size:0.9rem;">
                            <?= htmlspecialchars($book['description']) ?>
                        </p>
                    </div>
                    <!-- Download buttons -->
                    <div class="ms-auto d-flex gap-2 flex-shrink-0">
                        <?php if ($hasPdf): ?>
                        <a href="?id=<?= $id ?>&download=pdf" class="btn btn-sm btn-primary">
                            <i class="bi bi-download me-1"></i>PDF
                        </a>
                        <?php endif; ?>
                        <a href="?id=<?= $id ?>&download=docx" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-download me-1"></i>Word
                        </a>
                    </div>
                </div>

                <!-- Viewer -->
                <div class="viewer-wrapper">
                    <div class="viewer-toolbar">
                        <i class="bi bi-file-earmark-text text-muted"></i>
                        <span class="fw-semibold" style="font-size:0.85rem;">
                            <?= htmlspecialchars($book['title']) ?>
                        </span>
                        <span class="badge-mode ms-auto">
                            <?= $hasPdf ? 'PDF' : 'Document' ?>
                        </span>
                    </div>

                    <?php if ($hasPdf): ?>
                        <!-- PDF embed -->
                        <iframe class="pdf-embed"
                                src="?id=<?= $id ?>&pdf=1"
                                title="<?= htmlspecialchars($book['title']) ?>">
                            <p>Your browser cannot display PDFs.
                               <a href="?id=<?= $id ?>&download=pdf">Download the PDF</a> instead.
                            </p>
                        </iframe>

                    <?php else: ?>
                        <!-- Mammoth.js render -->
                        <div id="docLoading">
                            <div class="spinner-border spinner-border-sm me-2 text-muted" role="status"></div>
                            Loading document…
                        </div>
                        <div id="docContent" style="display:none;"></div>
                        <div id="docError"   style="display:none;">
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Could not render this document.
                                <a href="?id=<?= $id ?>&download=docx" class="alert-link ms-1">Download it instead.</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </main>
    </div>

    <?php include INCLUDES_PATH . '/adverts.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php if (!$hasPdf): ?>
    <script src="https://cdn.jsdelivr.net/npm/mammoth@1.6.0/mammoth.browser.min.js"></script>
    <script>
    (async () => {
        try {
            const res = await fetch('?id=<?= $id ?>&raw=1');
            if (!res.ok) throw new Error();
            const buf    = await res.arrayBuffer();
            const result = await mammoth.convertToHtml(
                { arrayBuffer: buf },
                { convertImage: mammoth.images.imgElement(img => img.read('base64').then(data => ({
                    src: 'data:' + img.contentType + ';base64,' + data
                }))) }
            );
            document.getElementById('docContent').innerHTML = result.value;
            document.getElementById('docLoading').style.display = 'none';
            document.getElementById('docContent').style.display = 'block';
        } catch(e) {
            document.getElementById('docLoading').style.display = 'none';
            document.getElementById('docError').style.display   = 'block';
        }
    })();
    </script>
    <?php endif; ?>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
