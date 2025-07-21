<?php // Fixed version of admin/logout.php ?>

<?php
session_start();
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
session_destroy();
$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/veloura/admin/login.php';
header("Location: $redirect");
exit();
