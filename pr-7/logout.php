<?php
session_start();

// Remove session
session_unset();
session_destroy();

// Remove cookie
if (isset($_COOKIE['user'])) {
    setcookie('user', '', time() - 3600, "/");
}

header("Location: login.html");
exit;
?>
