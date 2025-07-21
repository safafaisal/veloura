<?php
session_start();

if (isset($_POST['product_id']) && isset($_POST['action'])) {
    $productId = $_POST['product_id'];
    $action = $_POST['action'];

    if (isset($_SESSION['cart'][$productId])) {
        if ($action === 'increase') {
            $_SESSION['cart'][$productId]['quantity']++;
        } elseif ($action === 'decrease') {
            $_SESSION['cart'][$productId]['quantity']--;
            if ($_SESSION['cart'][$productId]['quantity'] <= 0) {
                unset($_SESSION['cart'][$productId]);
            }
        }
    }
}

header("Location: cart.php");
exit();