<?php
// CELPIP Writing Task 2 Practice 1 – TODO: Add hardcoded question/passage content
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../../edu_hub_registration.php?message=Please+login"); exit(); }
$testCode = 'CELPIP_PT_W2_001';
?>
<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CELPIP Writing Task 2 Practice 1 | EduHub</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
<style>
.main-wrapper{padding:2rem 1.5rem;min-height:100vh;background:#f8f9fa}
.stub-card{background:white;border-radius:16px;padding:3rem;box-shadow:0 4px 20px rgba(0,0,0,0.07);max-width:700px;margin:2rem auto;text-align:center}
.exam-badge{background:#3b82f6;color:white;padding:.5rem 1.5rem;border-radius:50px;font-weight:700;font-size:.95rem;display:inline-block;margin-bottom:1.5rem}
</style>
</head><body>
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="mobile-overlay" id="mobileOverlay"></div>
<?php include INCLUDES_PATH . '/navbar.php'; ?>
<main class="main-wrapper">
<div class="container">
<nav aria-label="breadcrumb"><ol class="breadcrumb">
<li class="breadcrumb-item"><a href="../resources_home.php">Resources</a></li>
<li class="breadcrumb-item"><a href="index.php">Practice Tests</a></li>
<li class="breadcrumb-item active">CELPIP Writing Task 2 Practice 1</li>
</ol></nav>
<div class="stub-card">
<span class="exam-badge">CELPIP</span>
<h2 class="mb-2">CELPIP Writing Task 2 Practice 1</h2>
<p class="text-muted mb-4">Writing Task 2 – Survey Response</p>
<div class="alert alert-info text-start">
<i class="bi bi-folder2-open me-2"></i>
<strong>Source writing set mapped:</strong><br>
This task corresponds to the downloaded CELPIP Writing bundle under <code>Downloads/CELPIP TASKS/Celpip Writing /Test 2</code> and the Test 3 set.
</div>
<div class="alert alert-warning text-start">
<i class="bi bi-hammer me-2"></i>
<strong>Survey prompt bank remains to be converted.</strong><br>
The project now has the source mapping and database record in place so each downloaded scenario can be sequenced into the live assessment workflow.
</div>
<a href="index.php" class="btn btn-outline-secondary mt-2">← Back to Practice Tests</a>
</div>
</div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
</body></html>
