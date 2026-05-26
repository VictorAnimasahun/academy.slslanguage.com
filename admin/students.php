<?php
require_once dirname(__DIR__) . '/bootstrap.php';

// Localhost-only until proper admin roles exist
$is_local = in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']);
if (!$is_local) {
    http_response_code(403);
    exit('Access denied.');
}

$tiers = ['beginner', 'intermediate', 'advanced', 'fluent'];
$tier_labels = [
    'beginner'     => 'Beginner (Free)',
    'intermediate' => 'Intermediate — 1 Mo',
    'advanced'     => 'Advanced — 2 Mo',
    'fluent'       => 'Fluent — 3 Mo',
];
$tier_colors = [
    'beginner'     => '#64748b',
    'intermediate' => '#0b77ff',
    'advanced'     => '#6366f1',
    'fluent'       => '#16a34a',
];

$message = '';
$message_type = '';

// ── Handle POST: set tier ────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    $student_id  = (int) ($_POST['student_id'] ?? 0);
    $action      = $_POST['action'];

    if ($action === 'set_tier' && $student_id > 0) {
        $tier        = in_array($_POST['tier'] ?? '', $tiers) ? $_POST['tier'] : 'beginner';
        $expiry_raw  = trim($_POST['expiry_date'] ?? '');
        $expiry_date = ($expiry_raw !== '') ? $expiry_raw : null;
        $note        = trim($_POST['note'] ?? '');

        try {
            $db->beginTransaction();

            // Expire all current active subscriptions for this student
            $db->prepare("
                UPDATE subscriptions
                SET status = 'expired', updated_at = NOW()
                WHERE student_id = ? AND status = 'active'
            ")->execute([$student_id]);

            if ($tier !== 'beginner') {
                // Insert new active subscription
                $db->prepare("
                    INSERT INTO subscriptions
                        (student_id, tier, paypal_txn_id, start_date, expiry_date, status)
                    VALUES
                        (?, ?, ?, CURDATE(), ?, 'active')
                ")->execute([$student_id, $tier, $note ?: null, $expiry_date]);
            }
            // Beginner = no subscription row needed (default fallback)

            $db->commit();
            $message = 'Tier updated successfully.';
            $message_type = 'success';

        } catch (PDOException $e) {
            $db->rollBack();
            $message = 'DB error: ' . htmlspecialchars($e->getMessage());
            $message_type = 'danger';
        }
    }

    if ($action === 'expire' && $student_id > 0) {
        $sub_id = (int) ($_POST['sub_id'] ?? 0);
        try {
            $db->prepare("
                UPDATE subscriptions SET status = 'expired', updated_at = NOW()
                WHERE id = ? AND student_id = ?
            ")->execute([$sub_id, $student_id]);
            $message = 'Subscription expired.';
            $message_type = 'warning';
        } catch (PDOException $e) {
            $message = 'DB error: ' . htmlspecialchars($e->getMessage());
            $message_type = 'danger';
        }
    }
}

// ── Fetch students with current tier ────────────────────────────────────────
$students = $db->query("
    SELECT s.id, s.firstname, s.lastname, s.email, s.phonenumber,
           s.is_verified, s.created_at,
           COALESCE(
               (SELECT sub.tier
                FROM subscriptions sub
                WHERE sub.student_id = s.id
                  AND sub.status = 'active'
                  AND (sub.expiry_date IS NULL OR sub.expiry_date >= DATE_SUB(CURDATE(), INTERVAL 3 DAY))
                ORDER BY FIELD(sub.tier,'fluent','advanced','intermediate','beginner'), sub.expiry_date DESC
                LIMIT 1),
               'beginner'
           ) AS current_tier,
           (SELECT sub.expiry_date
            FROM subscriptions sub
            WHERE sub.student_id = s.id
              AND sub.status = 'active'
              AND (sub.expiry_date IS NULL OR sub.expiry_date >= DATE_SUB(CURDATE(), INTERVAL 3 DAY))
            ORDER BY FIELD(sub.tier,'fluent','advanced','intermediate','beginner'), sub.expiry_date DESC
            LIMIT 1) AS expiry_date
    FROM students s
    ORDER BY s.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Selected student for subscription history
$selected_id = (int) ($_GET['student'] ?? 0);
$history = [];
$selected_student = null;
if ($selected_id > 0) {
    foreach ($students as $s) {
        if ($s['id'] === $selected_id) { $selected_student = $s; break; }
    }
    $history = $db->prepare("
        SELECT * FROM subscriptions WHERE student_id = ? ORDER BY created_at DESC
    ");
    $history->execute([$selected_id]);
    $history = $history->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Student Access — Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f1f5f9; }
        .page-header {
            background: #1e293b;
            color: white;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }
        .page-header h1 { font-size: 1.25rem; margin: 0; font-weight: 600; }
        .page-header small { opacity: 0.6; font-size: 0.8rem; }
        .card { border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); }
        .tier-badge {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.25rem 0.65rem;
            border-radius: 20px;
            color: white;
            display: inline-block;
        }
        .student-row:hover { background: #f8fafc; cursor: pointer; }
        .student-row.selected { background: #eff6ff; }
        .set-tier-form select, .set-tier-form input { font-size: 0.85rem; }
    </style>
</head>
<body>

<div class="page-header">
    <h1><i class="bi bi-shield-lock me-2"></i>Student Access Admin</h1>
    <small>localhost only &mdash; manual tier management</small>
</div>

<div class="container-fluid px-4">

<?php if ($message): ?>
    <div class="alert alert-<?= $message_type ?> alert-dismissible fade show py-2" role="alert">
        <?= $message ?>
        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-3">

    <!-- ── Student list ── -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <strong>Students <span class="text-muted fw-normal">(<?= count($students) ?>)</span></strong>
                <span class="text-muted small">Click a row to manage</span>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Verified</th>
                            <th>Tier</th>
                            <th>Expires</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($students as $s): ?>
                        <tr class="student-row <?= $s['id'] === $selected_id ? 'selected' : '' ?>"
                            onclick="window.location='?student=<?= $s['id'] ?>'">
                            <td class="text-muted small"><?= $s['id'] ?></td>
                            <td><?= htmlspecialchars($s['firstname'] . ' ' . $s['lastname']) ?></td>
                            <td class="small text-muted"><?= htmlspecialchars($s['email']) ?></td>
                            <td class="text-center">
                                <?php if ($s['is_verified']): ?>
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                <?php else: ?>
                                    <i class="bi bi-x-circle text-muted"></i>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="tier-badge"
                                      style="background:<?= $tier_colors[$s['current_tier']] ?>;">
                                    <?= $tier_labels[$s['current_tier']] ?>
                                </span>
                            </td>
                            <td class="small text-muted">
                                <?= $s['expiry_date'] ? date('d M Y', strtotime($s['expiry_date'])) : ($s['current_tier'] !== 'beginner' ? '∞ no expiry' : '—') ?>
                            </td>
                            <td class="small text-muted"><?= date('d M Y', strtotime($s['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($students)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">No students registered yet.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ── Manage panel ── -->
    <div class="col-lg-5">
        <?php if ($selected_student): ?>
        <div class="card mb-3">
            <div class="card-header bg-white py-3">
                <strong><?= htmlspecialchars($selected_student['firstname'] . ' ' . $selected_student['lastname']) ?></strong>
                <div class="text-muted small"><?= htmlspecialchars($selected_student['email']) ?></div>
            </div>
            <div class="card-body">

                <p class="mb-1 small text-muted">Current tier:</p>
                <p class="mb-3">
                    <span class="tier-badge fs-6"
                          style="background:<?= $tier_colors[$selected_student['current_tier']] ?>;">
                        <?= $tier_labels[$selected_student['current_tier']] ?>
                    </span>
                    <?php if ($selected_student['expiry_date']): ?>
                        <span class="text-muted small ms-2">expires <?= date('d M Y', strtotime($selected_student['expiry_date'])) ?></span>
                    <?php endif; ?>
                </p>

                <form method="POST" class="set-tier-form">
                    <input type="hidden" name="action" value="set_tier">
                    <input type="hidden" name="student_id" value="<?= $selected_student['id'] ?>">

                    <div class="mb-2">
                        <label class="form-label small fw-semibold mb-1">Set tier to</label>
                        <select name="tier" class="form-select form-select-sm">
                            <?php foreach ($tiers as $t): ?>
                                <option value="<?= $t ?>"
                                    <?= $t === $selected_student['current_tier'] ? 'selected' : '' ?>>
                                    <?= $tier_labels[$t] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-semibold mb-1">
                            Expiry date <span class="text-muted fw-normal">(leave blank = no expiry)</span>
                        </label>
                        <input type="date" name="expiry_date" class="form-control form-control-sm"
                               value="<?= htmlspecialchars($selected_student['expiry_date'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold mb-1">
                            Note / reference <span class="text-muted fw-normal">(optional — stored as txn_id)</span>
                        </label>
                        <input type="text" name="note" class="form-control form-control-sm"
                               placeholder="e.g. manual, invoice #123, PayPal ref">
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-check-lg me-1"></i>Apply Tier
                    </button>
                </form>
            </div>
        </div>

        <!-- Subscription history -->
        <?php if ($history): ?>
        <div class="card">
            <div class="card-header bg-white py-2">
                <strong class="small">Subscription History</strong>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0 small">
                    <thead class="table-light">
                        <tr><th>Tier</th><th>Status</th><th>Start</th><th>Expires</th><th>Note</th><th></th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($history as $sub): ?>
                        <tr>
                            <td>
                                <span class="tier-badge" style="background:<?= $tier_colors[$sub['tier']] ?>;">
                                    <?= ucfirst($sub['tier']) ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                $badge = ['active'=>'success','expired'=>'secondary','cancelled'=>'danger'][$sub['status']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $badge ?>"><?= $sub['status'] ?></span>
                            </td>
                            <td><?= $sub['start_date'] ?></td>
                            <td><?= $sub['expiry_date'] ?? '∞' ?></td>
                            <td class="text-muted" style="max-width:100px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                <?= htmlspecialchars($sub['paypal_txn_id'] ?? '') ?>
                            </td>
                            <td>
                                <?php if ($sub['status'] === 'active'): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="expire">
                                    <input type="hidden" name="student_id" value="<?= $selected_student['id'] ?>">
                                    <input type="hidden" name="sub_id" value="<?= $sub['id'] ?>">
                                    <button class="btn btn-outline-secondary btn-sm py-0 px-1"
                                            onclick="return confirm('Expire this subscription?')"
                                            title="Expire">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <?php else: ?>
        <div class="card">
            <div class="card-body text-center text-muted py-5">
                <i class="bi bi-person-circle" style="font-size:2.5rem;opacity:0.3;"></i>
                <p class="mt-3 mb-0">Click a student to manage their access tier.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>

</div><!-- /row -->
</div><!-- /container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
