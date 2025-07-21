<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: /veloura/admin/login.php');
    exit();
}
require_once __DIR__ . '/../permissions.php';
include '../db/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $image = $_POST['image'];
    $stmt = $conn->prepare("INSERT INTO products (name, price, category, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $name, $price, $category, $image);
    $stmt->execute();
    $stmt->close();
    header('Location: manage_products.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Product - Veloura Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<?php include '../navbar.php'; ?>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-plus-circle"></i> Add Product</h2>
        <a href="manage_products.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>
    <form method="post">
        <div class="form-group">
            <input type="text" name="name" class="form-control" placeholder="Product Name" required>
        </div>
        <div class="form-group">
            <input type="number" step="0.01" name="price" class="form-control" placeholder="Price" required>
        </div>
        <div class="form-group">
            <select name="category" class="form-control" required>
                <option value="men">Men</option>
                <option value="women">Women</option>
            </select>
        </div>
        <div class="form-group">
            <input type="text" name="image" class="form-control" placeholder="Image filename (e.g. vanilla.jpg)" required>
        </div>
        <button type="submit" class="btn btn-success">Add Product</button>
    </form>
</div>
</body>
</html>
