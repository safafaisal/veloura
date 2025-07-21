<?php // Fixed version of permissions.php ?>

<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: /veloura/admin/login.php');
    exit();
}
?>
