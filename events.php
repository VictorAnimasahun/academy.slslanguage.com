<?php
require_once __DIR__ . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) { header("Location: edu_hub_registration.php"); exit(); }
$userName = isset($_SESSION['user_firstname']) ? htmlspecialchars($_SESSION['user_firstname']) : 'Learner';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Events - EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <link href="assets/css/dashboard.css" rel="stylesheet">
    <style>
        @media(min-width:1400px){.content{max-width:calc(100vw - 500px);}}

        .event-card {
            border-radius: 18px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 6px 24px rgba(15,23,42,.07);
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .event-card:hover { transform: translateY(-4px); box-shadow: 0 14px 36px rgba(15,23,42,.1); }
        body.dark .event-card { background: #1f1f1f; }

        .event-card-header {
            background: linear-gradient(135deg, #0d1b4e 0%, #0b5c45 100%);
            color: #fff;
            padding: 2.5rem 2.25rem 2rem;
            position: relative;
        }
        .event-live-badge {
            position: absolute; top: 1.25rem; right: 1.25rem;
            background: rgba(255,255,255,.15);
            border: 1px solid rgba(255,255,255,.3);
            border-radius: 20px; padding: .3rem 1rem;
            font-size: .78rem; font-weight: 600; letter-spacing: .05em;
            display: flex; align-items: center; gap: .4rem;
        }
        .event-card-body { padding: 1.75rem 2.25rem; }

        .countdown-row {
            display: flex; gap: 1rem; margin: 1.5rem 0 .5rem;
            flex-wrap: wrap;
        }
        .cd-unit {
            flex: 1; min-width: 70px; background: rgba(255,255,255,.12);
            border-radius: 12px; text-align: center; padding: .75rem .5rem;
        }
        .cd-num {
            display: block; font-size: 2.2rem; font-weight: 900; line-height: 1;
        }
        .cd-lbl {
            font-size: .68rem; text-transform: uppercase; letter-spacing: .07em;
            opacity: .75; margin-top: .3rem;
        }

        .detail-btn {
            display: inline-flex; align-items: center; gap: .5rem;
            background: #fff; color: #0b77ff;
            border: 2px solid #fff; border-radius: 12px;
            padding: .7rem 1.75rem; font-weight: 700; font-size: 1rem;
            text-decoration: none; transition: background .15s, color .15s;
            margin-top: 1.25rem;
        }
        .detail-btn:hover { background: transparent; color: #fff; }

        .event-meta span {
            font-size: .88rem; opacity: .85;
            display: inline-flex; align-items: center; gap: .35rem;
        }
    </style>
</head>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="d-flex">
    <?php include INCLUDES_PATH . '/navbar.php'; ?>
    <div class="main-wrapper flex-grow-1">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>
        <main class="content p-4">

            <div class="d-flex align-items-center gap-3 mb-4">
                <i class="bi bi-calendar-event-fill fs-2 text-primary"></i>
                <div>
                    <h2 class="mb-0">Events</h2>
                    <p class="text-muted mb-0">Upcoming workshops, webinars, and live sessions</p>
                </div>
            </div>

            <!-- ── EVENT CARD: Gen-Z Experience ── -->
            <div class="event-card mb-4">
                <div class="event-card-header">
                    <div class="event-live-badge">
                        <i class="bi bi-linkedin"></i> LinkedIn Live
                    </div>
                    <p class="mb-1" style="opacity:.7;font-size:.8rem;text-transform:uppercase;letter-spacing:.08em;">
                        Navigating the Corporate World
                    </p>
                    <h3 class="fw-bold mb-2" style="font-size:1.9rem;line-height:1.2;">The Gen-Z Experience</h3>
                    <div class="event-meta d-flex flex-wrap gap-3 mb-1">
                        <span><i class="bi bi-calendar3"></i> Saturday, May 9, 2026</span>
                        <span><i class="bi bi-clock"></i> 11:00 AM EDT &nbsp;·&nbsp; 4:00 PM WAT</span>
                        <span><i class="bi bi-globe"></i> Online — Free</span>
                    </div>
                    <!-- Countdown -->
                    <div class="countdown-row" id="genz-countdown">
                        <div class="cd-unit"><span class="cd-num" id="gz-d">--</span><div class="cd-lbl">Days</div></div>
                        <div class="cd-unit"><span class="cd-num" id="gz-h">--</span><div class="cd-lbl">Hours</div></div>
                        <div class="cd-unit"><span class="cd-num" id="gz-m">--</span><div class="cd-lbl">Minutes</div></div>
                        <div class="cd-unit"><span class="cd-num" id="gz-s">--</span><div class="cd-lbl">Seconds</div></div>
                    </div>
                    <a href="events/gen_z_experience.php" class="detail-btn">
                        <i class="bi bi-arrow-right-circle-fill"></i> View Full Event Details
                    </a>
                </div>
                <div class="event-card-body">
                    <div class="row g-4 align-items-center">
                        <div class="col-md-8">
                            <p class="text-muted mb-0" style="font-size:.93rem;line-height:1.75;">
                                What does it really feel like to enter the corporate world as a Gen-Z professional?
                                Join host <strong>Scholar Mfeseer Alibo</strong> and guest speaker <strong>Georgina Ijachi</strong>
                                alongside four panelists for an unfiltered live conversation about workplace culture,
                                communication, and career growth in today's world.
                            </p>
                        </div>
                        <div class="col-md-4 text-center text-md-end">
                            <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                                <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-2">1 Host</span>
                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2">1 Guest Speaker</span>
                                <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning px-3 py-2">4 Panelists</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Placeholder for future events -->
            <div class="event-card" style="border: 2px dashed #e2e8f0; box-shadow:none; background:transparent;">
                <div class="event-card-body text-center py-5">
                    <i class="bi bi-calendar-plus fs-1 text-muted mb-3 d-block"></i>
                    <h6 class="text-muted">More Events Coming Soon</h6>
                    <p class="text-muted mb-0" style="font-size:.85rem;">
                        New workshops and webinars will be listed here. Stay tuned!
                    </p>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include INCLUDES_PATH . '/adverts.php'; ?>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>

<script>
const genzDate = new Date('2026-05-09T15:00:00Z');
function tickGenz() {
    const diff = genzDate - new Date();
    if (diff <= 0) {
        document.getElementById('genz-countdown').innerHTML =
            '<span style="font-weight:700;font-size:1rem;">🔴 Live Now!</span>';
        return;
    }
    const pad = n => String(Math.floor(n)).padStart(2,'0');
    document.getElementById('gz-d').textContent = pad(diff / 86400000);
    document.getElementById('gz-h').textContent = pad((diff % 86400000) / 3600000);
    document.getElementById('gz-m').textContent = pad((diff % 3600000) / 60000);
    document.getElementById('gz-s').textContent = pad((diff % 60000) / 1000);
}
tickGenz(); setInterval(tickGenz, 1000);
</script>
</body>
</html>
