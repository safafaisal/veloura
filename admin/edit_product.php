<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: /veloura/admin/login.php');
    exit();
}
require_once __DIR__ . '/../permissions.php';
include '../db/db.php';
$id = $_GET['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $image = $_POST['image'];
    $stmt = $conn->prepare("UPDATE products SET name=?, price=?, category=?, image=? WHERE id=?");
    $stmt->bind_param("sdssi", $name, $price, $category, $image, $id);
    $stmt->execute();
    $stmt->close();
    header('Location: manage_products.php');
    exit();
}
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Product - Veloura Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<?php include '../navbar.php'; ?>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-edit"></i> Edit Product</h2>
        <a href="manage_products.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>
    <form method="post">
        <div class="form-group">
            <input type="text" name="name" class="form-control" value="<?= $product['name'] ?>" required>
        </div>
        <div class="form-group">
            <input type="number" step="0.01" name="price" class="form-control" value="<?= $product['price'] ?>" required>
        </div>
        <div class="form-group">
            <select name="category" class="form-control" required>
                <option value="men" <?= $product['category']=='men'?'selected':'' ?>>Men</option>
                <option value="women" <?= $product['category']=='women'?'selected':'' ?>>Women</option>
            </select>
        </div>
        <div class="form-group">
            <input type="text" name="image" class="form-control" value="<?= $product['image'] ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>
</body>
</html>
