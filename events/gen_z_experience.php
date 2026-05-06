<?php
require_once dirname(__DIR__) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../edu_hub_registration.php"); exit(); }
$userName = isset($_SESSION['user_firstname']) ? htmlspecialchars($_SESSION['user_firstname']) : 'Learner';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>The Gen-Z Experience – Events | EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <style>
        @media(min-width:1400px){.content{max-width:calc(100vw - 500px);}}
        /* Cap content width for readability — centers it in the available space */
        .content > * { max-width: 900px; }

        /* Hero */
        .event-hero {
            background: linear-gradient(135deg, #0d1b4e 0%, #0b5c45 100%);
            border-radius: 18px; color: #fff;
            padding: 2.75rem 2.25rem 2rem; position: relative; overflow: hidden;
        }
        .event-hero::after {
            content:''; position:absolute; top:-80px; right:-80px;
            width:280px; height:280px; border-radius:50%;
            background: rgba(255,255,255,.04);
        }
        .platform-pill {
            display: inline-flex; align-items: center; gap: .4rem;
            background: #fff; color: #0a66c2; border-radius: 20px;
            padding: .3rem 1rem; font-weight: 700; font-size: .85rem;
            margin-bottom: 1.25rem;
        }
        .info-chip {
            display: inline-flex; align-items: center; gap: .35rem;
            background: rgba(255,255,255,.12); border-radius: 20px;
            padding: .3rem .9rem; font-size: .82rem;
        }

        /* Countdown */
        .countdown-bar {
            background: rgba(0,0,0,.25); border-radius: 14px;
            padding: 1.25rem 1rem; display: flex;
            gap: 1rem; justify-content: center; flex-wrap: wrap;
            margin-top: 1.75rem;
        }
        .cd-cell { text-align: center; min-width: 70px; }
        .cd-num {
            font-size: 2.4rem; font-weight: 900; line-height: 1;
            background: rgba(255,255,255,.15); border-radius: 12px;
            padding: .4rem .6rem; display: block;
        }
        .cd-lbl {
            font-size: .68rem; text-transform: uppercase;
            letter-spacing: .08em; opacity: .7; margin-top: .3rem;
        }

        /* Speaker / panelist cards */
        .person-card {
            background: #fff; border-radius: 16px; padding: 1.5rem 1.25rem;
            text-align: center; box-shadow: 0 4px 18px rgba(15,23,42,.06);
            height: 100%;
        }
        body.dark .person-card { background: #1f1f1f; }
        .avatar-circle {
            width: 80px; height: 80px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.7rem; font-weight: 800; color: #fff;
            margin: 0 auto 1rem;
        }
        .role-badge {
            font-size: .72rem; border-radius: 20px;
            padding: .25rem .75rem; font-weight: 700;
            letter-spacing: .04em; display: inline-block; margin-bottom: .75rem;
        }
        .person-card h6 { font-size: .95rem; font-weight: 700; margin-bottom: .25rem; }
        .person-card p  { font-size: .8rem; color: #64748b; margin: 0; }
        body.dark .person-card p { color: #94a3b8; }

        /* About section */
        .about-card {
            background: #f8fafc; border-radius: 14px; padding: 1.75rem;
        }
        body.dark .about-card { background: #161f2e; }

        /* Register CTA */
        .cta-card {
            background: linear-gradient(135deg, #0d1b4e 0%, #0b5c45 100%);
            border-radius: 16px; color: #fff; padding: 2rem; text-align: center;
        }
        .linkedin-btn {
            display: inline-flex; align-items: center; gap: .5rem;
            background: #fff; color: #0a66c2;
            border-radius: 10px; padding: .8rem 2rem;
            font-weight: 700; font-size: .95rem; text-decoration: none;
            transition: opacity .2s;
        }
        .linkedin-btn:hover { opacity: .88; color: #0a66c2; }
    </style>
</head>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="d-flex">
    <?php include INCLUDES_PATH . '/navbar.php'; ?>
    <div class="main-wrapper flex-grow-1">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>
        <main class="content p-4">

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../events.php"><i class="bi bi-calendar-event me-1"></i>Events</a></li>
                    <li class="breadcrumb-item active">The Gen-Z Experience</li>
                </ol>
            </nav>

            <!-- ── HERO ── -->
            <div class="event-hero mb-4">
                <div class="platform-pill">
                    <i class="bi bi-linkedin" style="font-size:1rem;"></i> LinkedIn Live
                </div>
                <p class="mb-1" style="opacity:.7;font-size:.8rem;text-transform:uppercase;letter-spacing:.08em;">
                    Navigating the Corporate World
                </p>
                <h2 class="fw-900 mb-3" style="font-size:2rem;font-weight:900;line-height:1.2;">
                    The Gen-Z Experience
                </h2>
                <div class="d-flex flex-wrap gap-3 mb-3">
                    <span class="info-chip"><i class="bi bi-calendar3"></i> Saturday, May 9, 2026</span>
                    <span class="info-chip"><i class="bi bi-clock"></i> 11:00 AM EDT &nbsp;·&nbsp; 4:00 PM WAT</span>
                    <span class="info-chip"><i class="bi bi-globe"></i> Online — Free to Join</span>
                </div>
                <!-- Countdown -->
                <div class="countdown-bar" id="countdown">
                    <div class="cd-cell"><span class="cd-num" id="cd-d">--</span><div class="cd-lbl">Days</div></div>
                    <div class="cd-cell"><span class="cd-num" id="cd-h">--</span><div class="cd-lbl">Hours</div></div>
                    <div class="cd-cell"><span class="cd-num" id="cd-m">--</span><div class="cd-lbl">Minutes</div></div>
                    <div class="cd-cell"><span class="cd-num" id="cd-s">--</span><div class="cd-lbl">Seconds</div></div>
                </div>
            </div>

            <!-- ── ABOUT ── -->
            <div class="about-card mb-4">
                <h5 class="fw-semibold mb-2"><i class="bi bi-info-circle me-2 text-primary"></i>About This Event</h5>
                <p class="mb-0" style="line-height:1.75;">
                    What does it really feel like to enter the corporate world as a Gen-Z professional?
                    In this LinkedIn Live session, <strong>Scholarly Language Services</strong> brings together
                    a host, a guest speaker, and four panelists to have an honest conversation about workplace
                    culture, communication, career navigation, and the unique challenges and strengths
                    that define the Gen-Z professional experience. Whether you're about to start your
                    career or already navigating the corporate ladder, this session is for you.
                </p>
            </div>

            <!-- ── HOST & GUEST SPEAKER ── -->
            <h5 class="fw-semibold mb-3">Host &amp; Guest Speaker</h5>
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-lg-4">
                    <div class="person-card">
                        <div class="avatar-circle" style="background:linear-gradient(135deg,#0b77ff,#7c3aed);">MA</div>
                        <span class="role-badge bg-primary text-white">Host</span>
                        <h6>Scholar Mfeseer Alibo</h6>
                        <p>Founder, SLS<br>Legal and Marketing Assistant</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="person-card">
                        <div class="avatar-circle" style="background:linear-gradient(135deg,#0b9d6e,#14b8a6);">GI</div>
                        <span class="role-badge bg-success text-white">Guest Speaker</span>
                        <h6>Georgina Ijachi</h6>
                        <p>Founder, AVI<br>Communication Strategist</p>
                    </div>
                </div>
            </div>

            <!-- ── PANELISTS ── -->
            <h5 class="fw-semibold mb-3">Panelists</h5>
            <div class="row g-3 mb-4">
                <?php
                $panelists = [
                    ['name'=>'Promise Aiden Godwin', 'initials'=>'PG', 'grad'=>'#f59e0b,#ef4444'],
                    ['name'=>'Ijeoma Ntada',         'initials'=>'IN', 'grad'=>'#ec4899,#f43f5e'],
                    ['name'=>'Victor Animashaun',    'initials'=>'VA', 'grad'=>'#0b77ff,#6366f1'],
                    ['name'=>'Veronica Iorwa',       'initials'=>'VI', 'grad'=>'#8b5cf6,#ec4899'],
                ];
                foreach ($panelists as $p): ?>
                <div class="col-6 col-md-3">
                    <div class="person-card">
                        <div class="avatar-circle" style="background:linear-gradient(135deg,<?= $p['grad'] ?>);font-size:1.3rem;">
                            <?= $p['initials'] ?>
                        </div>
                        <span class="role-badge" style="background:#f1f5f9;color:#334155;">Panelist</span>
                        <h6><?= htmlspecialchars($p['name']) ?></h6>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- ── CTA ── -->
            <div class="cta-card">
                <i class="bi bi-broadcast-pin" style="font-size:2.5rem;margin-bottom:.75rem;display:block;"></i>
                <h5 class="mb-1">Watch It Live — Free to Attend</h5>
                <p style="opacity:.8;font-size:.9rem;margin-bottom:1.5rem;">
                    Follow SLS on LinkedIn to get notified when we go live on Saturday, May 9, 2026.
                </p>
                <a href="https://www.linkedin.com/company/scholarly-language-services"
                   target="_blank" rel="noopener" class="linkedin-btn">
                    <i class="bi bi-linkedin"></i> Follow SLS on LinkedIn
                </a>
            </div>

        </main>
    </div>
</div>
<?php include INCLUDES_PATH . '/adverts.php'; ?>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>

<script>
const eventDate = new Date('2026-05-09T15:00:00Z');
function tick() {
    const diff = eventDate - new Date();
    if (diff <= 0) {
        document.getElementById('countdown').innerHTML =
            '<div style="font-size:1.25rem;font-weight:800;">🔴 We are Live Right Now!</div>';
        return;
    }
    const pad = n => String(Math.floor(n)).padStart(2,'0');
    document.getElementById('cd-d').textContent = pad(diff / 86400000);
    document.getElementById('cd-h').textContent = pad((diff % 86400000) / 3600000);
    document.getElementById('cd-m').textContent = pad((diff % 3600000) / 60000);
    document.getElementById('cd-s').textContent = pad((diff % 60000) / 1000);
}
tick(); setInterval(tick, 1000);
</script>
</body>
</html>
