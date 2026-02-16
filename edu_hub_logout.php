<?php
session_start();
session_unset();   // remove all session vars
session_destroy(); // destroy the session

header("Location: index.php?message=You+have+been+logged+out");
exit();
?>