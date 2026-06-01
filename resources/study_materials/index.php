<?php
require_once dirname(__DIR__, 2) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+study+materials");
    exit();
}

// ── File handler (download + preview fetch) ───────────────────────────────────
if (isset($_GET['file'])) {
    $allowed = [
        'vb01' => 'SLS-VB01_ Astronomy and Stargazing Vocabulary.docx',
        'vb02' => 'SLS-VB02_ Restaurants and Food Vocabulary.docx',
        'vb03' => 'SLS-VB03_ Sustainable Food Vocabulary.docx',
        'vb04' => 'SLS-VB04_ Technology and the Future.docx',
    ];
    $key = preg_replace('/[^a-z0-9]/', '', strtolower($_GET['file']));
    if (isset($allowed[$key])) {
        $path = ACADEMY_ROOT . '/assets/downloads/vocabulary/' . $allowed[$key];
        if (file_exists($path)) {
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            // 'preview' param = fetch by JS; omit attachment so the response is readable
            if (!isset($_GET['preview'])) {
                header('Content-Disposition: attachment; filename="' . $allowed[$key] . '"');
            }
            header('Content-Length: ' . filesize($path));
            readfile($path);
            exit();
        }
    }
    http_response_code(404);
    exit('File not found.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Study Materials | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>

    <style>
        .section-label {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #9ca3af;
            margin-bottom: 1rem;
        }

        .material-card {
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: box-shadow 0.2s ease, transform 0.2s ease;
            background: #fff;
            display: flex;
            flex-direction: column;
        }
        .material-card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }

        .card-stripe { height: 6px; width: 100%; }

        .card-body-inner {
            padding: 1.25rem 1.25rem 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .material-badge {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 3px 8px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 0.65rem;
        }

        .material-title {
            font-size: 1rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.35rem;
            line-height: 1.4;
        }

        .material-meta {
            font-size: 0.8rem;
            color: #6b7280;
            margin-bottom: 0;
        }

        .card-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid #f3f4f6;
        }

        .preview-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #6b7280;
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            transition: color 0.15s ease;
        }
        .preview-btn:hover { color: #111827; }

        .download-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            color: #2563eb;
            transition: color 0.15s ease;
            margin-left: auto;
        }
        .download-btn:hover { color: #1d4ed8; }

        .placeholder-card {
            border: 2px dashed #e5e7eb;
            border-radius: 14px;
            padding: 2rem 1.25rem;
            text-align: center;
            color: #9ca3af;
            font-size: 0.85rem;
        }
        .placeholder-card i { font-size: 1.8rem; margin-bottom: 0.5rem; display: block; }

    </style>
</head>
<body class="light">
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <div class="main-wrapper flex-grow-1" style="flex:1;">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>

        <main class="content p-4">
            <div class="container-fluid" style="max-width:1100px;">

                <div class="mb-4">
                    <h1 class="display-6 fw-bold mb-1">Study Materials</h1>
                    <p class="text-muted mb-0">Downloadable vocabulary packs, guides, and templates to support your studies.</p>
                </div>

                <!-- ── Vocabulary Series ───────────────────────────────────── -->
                <p class="section-label">Vocabulary Series</p>
                <div class="row g-3 mb-5">

                    <div class="col-sm-6 col-lg-3">
                        <div class="material-card h-100">
                            <div class="card-stripe" style="background:linear-gradient(90deg,#7c3aed,#a855f7);"></div>
                            <div class="card-body-inner">
                                <span class="material-badge" style="background:#f3e8ff;color:#7c3aed;">VB01</span>
                                <p class="material-title">Astronomy &amp; Stargazing</p>
                                <p class="material-meta">Vocabulary book · .docx / PDF</p>
                                <div class="card-actions">
                                    <a href="view.php?id=vb01" class="preview-btn">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="?file=vb01" class="download-btn">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="material-card h-100">
                            <div class="card-stripe" style="background:linear-gradient(90deg,#ea580c,#f97316);"></div>
                            <div class="card-body-inner">
                                <span class="material-badge" style="background:#fff7ed;color:#ea580c;">VB02</span>
                                <p class="material-title">Restaurants &amp; Food</p>
                                <p class="material-meta">Vocabulary book · .docx / PDF</p>
                                <div class="card-actions">
                                    <a href="view.php?id=vb02" class="preview-btn">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="?file=vb02" class="download-btn">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="material-card h-100">
                            <div class="card-stripe" style="background:linear-gradient(90deg,#0f766e,#14b8a6);"></div>
                            <div class="card-body-inner">
                                <span class="material-badge" style="background:#f0fdf4;color:#0f766e;">VB03</span>
                                <p class="material-title">Sustainable Food</p>
                                <p class="material-meta">Vocabulary book · .docx / PDF</p>
                                <div class="card-actions">
                                    <a href="view.php?id=vb03" class="preview-btn">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="?file=vb03" class="download-btn">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="material-card h-100">
                            <div class="card-stripe" style="background:linear-gradient(90deg,#1e40af,#3b82f6);"></div>
                            <div class="card-body-inner">
                                <span class="material-badge" style="background:#eff6ff;color:#1e40af;">VB04</span>
                                <p class="material-title">Technology &amp; the Future</p>
                                <p class="material-meta">Vocabulary book · .docx / PDF</p>
                                <div class="card-actions">
                                    <a href="view.php?id=vb04" class="preview-btn">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="?file=vb04" class="download-btn">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ── Guides & Templates ──────────────────────────────────── -->
                <p class="section-label">Guides &amp; Templates</p>
                <div class="row g-3">
                    <div class="col-sm-6 col-lg-3">
                        <div class="placeholder-card">
                            <i class="bi bi-plus-circle-dotted"></i>
                            Essay Structuring Guide<br>
                            <span style="font-size:0.75rem;">Coming soon</span>
                        </div>
                    </div>
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
