<?php
session_start();
session_unset();   // remove all session vars

// Explicitly expire the session cookie client-side too -- session_destroy()
// alone only clears the server-side session data, leaving the browser
// holding a now-inert cookie value until it naturally expires or the
// browser closes. Not a hijack risk on its own (the destroyed session has
// nothing behind it), but this is the textbook-correct way to log out.
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

session_destroy(); // destroy the session

header("Location: index.php?message=You+have+been+logged+out");
exit();
?>