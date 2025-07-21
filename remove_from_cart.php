<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'navbar.php';
include 'db/db.php';
include 'includes/activity_logger.php';

if (isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];
    if (isset($_SESSION['cart'][$productId])) {
        // Log remove from cart activity
        logRemoveFromCart($_SESSION['cart'][$productId]['name']);
        unset($_SESSION['cart'][$productId]);
    }
}

header("Location: cart.php");
exit();