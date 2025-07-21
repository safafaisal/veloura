<?php // Fixed version of admin/delete_product.php ?>

<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: /veloura/admin/login.php');
    exit();
}

require_once __DIR__ . '/../permissions.php';
include '../db/db.php';
$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM products WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();
header('Location: manage_products.php');
exit();
