<?php
// ABSOLUTE PATH to the academy root (local or live)
define('ACADEMY_ROOT', __DIR__);

// PATH to includes folder
define('INCLUDES_PATH', ACADEMY_ROOT . '/includes');

// PATH to config (shared folder on both local and live)
define('CONFIG_PATH', dirname(ACADEMY_ROOT) . '/config');

// WEB ROOT for academy (this is used for href links)
$academy_url = '/academy/'; // local
if (strpos($_SERVER['HTTP_HOST'], 'academy.slslanguage.com') !== false) {
    $academy_url = '/'; // live domain root
}
define('ACADEMY_URL', $academy_url);
