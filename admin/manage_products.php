<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: /veloura/admin/login.php');
    exit();
}
require_once __DIR__ . '/../permissions.php';
include '../db/db.php';
$result = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Products - Veloura Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<?php include '../navbar.php'; ?>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-box"></i> Manage Products</h2>
        <a href="index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
    <a href="add_product.php" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Add Product
    </a>
    <table class="table table-bordered">
        <thead><tr><th>ID</th><th>Name</th><th>Price</th><th>Category</th><th>Actions</th></tr></thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['price'] ?></td>
                <td><?= $row['category'] ?></td>
                <td>
                    <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="delete_product.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
