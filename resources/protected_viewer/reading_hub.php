<?php
// /academy/resources/protected_viewer/reading_hub.php?parts=celpip_reading_pt3,celpip_reading_pt4
// Small landing page for a class that links to more than one protected
// resource (lessons.file_path only holds one URL per class) -- lists each
// part with its own tier-gated link into celpip_reading.php.

require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once INCLUDES_PATH . '/protected_reading_resources.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . ACADEMY_URL . "edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

$partKeys = array_filter(array_map('trim', explode(',', $_GET['parts'] ?? '')));
$items = [];
foreach ($partKeys as $key) {
    if (isset(PROTECTED_READING_RESOURCES[$key])) {
        $items[$key] = PROTECTED_READING_RESOURCES[$key];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reading Resources | EduHub</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
<style>
    .hub-wrapper { max-width: 720px; margin: 0 auto; padding: 2rem 1.5rem; }
    .hub-item {
        display: flex; align-items: center; justify-content: space-between;
        background: #fff; border: 1px solid #e5e7eb; border-radius: 12px;
        padding: 1.1rem 1.4rem; margin-bottom: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,.04);
    }
    .hub-item.locked { opacity: .6; }
    .hub-item-title { font-weight: 600; color: #1f2937; font-size: .95rem; }
    .hub-open-btn {
        background: #667eea; color: #fff; border: none; border-radius: 8px;
        padding: .55rem 1.1rem; font-weight: 600; font-size: .85rem; text-decoration: none;
    }
    .hub-open-btn:hover { background: #5a67d8; color: #fff; }
</style>
</head>
<body>

<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<?php include INCLUDES_PATH . '/navbar.php'; ?>

<main class="main-wrapper" style="flex:1;">
    <div class="hub-wrapper">
        <h1 class="h4 mb-3" style="color:#1f2937;font-weight:700;">Reading Resources</h1>
        <p style="color:#6b7280;font-size:.9rem;margin-bottom:1.5rem;">This class covers more than one reading part — open each one below.</p>

        <?php foreach ($items as $key => $resource):
            $unlocked = can_access($resource['min_tier']);
        ?>
        <div class="hub-item <?= $unlocked ? '' : 'locked' ?>">
            <span class="hub-item-title">
                <i class="bi <?= $unlocked ? 'bi-file-earmark-text-fill' : 'bi-lock-fill' ?> me-2"></i>
                <?= htmlspecialchars($resource['title']) ?>
            </span>
            <?php if ($unlocked): ?>
                <a class="hub-open-btn" href="celpip_reading.php?part=<?= urlencode($key) ?>">Open</a>
            <?php else: ?>
                <span class="text-muted small">Locked</span>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</main>

<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
