<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db/db.php';
include 'includes/activity_logger.php';

// Log logout before destroying session
if (isset($_SESSION['user']['name'])) {
    logLogout($_SESSION['user']['name']);
}

include 'navbar.php';

// Unset all session variables
$_SESSION = [];

// Destroy session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
header("Location: $redirect");
exit();
