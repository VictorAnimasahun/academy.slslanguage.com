<?php
// academy/cron/send_email_queue.php
//
// Sends the next batch of queued marketing emails (email_queue, migration 150) -- welcome emails,
// approved newsletters, and later the follow-up sequences. All the logic lives in
// config/email_queue.php; this is just the scheduled entry point.
//
// CLI-only: it sends real email, so it must never be reachable over HTTP.
// Run manually to test:
//   php send_email_queue.php --dry-run        (lists what would be sent, changes nothing)
//   php send_email_queue.php --local          (against local MAMP instead of live)
// Then schedule in cPanel -> Cron Jobs (the founder sets this up; not something run from here):
//   Command: /usr/local/bin/php /home/<cpanel_user>/academy.slslanguage.com/cron/send_email_queue.php
//   Schedule: every 10 minutes, "*/10 * * * *"
// Adjust the path above to wherever academy actually lives under the cPanel account.
// At 40 emails per run that is at most 240/hour -- see EMAIL_QUEUE_BATCH in config/email_queue.php.

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("This script is CLI-only.\n");
}

$dryRun = in_array('--dry-run', $argv, true);
// db_connect.php and lead_site_url() pick local vs live from HTTP_HOST, which CLI doesn't have.
$_SERVER['HTTP_HOST'] = in_array('--local', $argv, true) ? 'localhost' : '';

// config/ sits next to academy/ both locally (htdocs/) and on live (public_html/).
$configDir = dirname(__DIR__, 2) . '/config';
require_once $configDir . '/db_connect.php';
require_once $configDir . '/email_queue.php';

$counts = email_queue_process($db, EMAIL_QUEUE_BATCH, $dryRun);
if (!$dryRun) {
    echo date('Y-m-d H:i:s') . " sent={$counts['sent']} retry={$counts['retry']} failed={$counts['failed']} cancelled={$counts['cancelled']}\n";
}
