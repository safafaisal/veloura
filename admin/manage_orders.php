<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: /veloura/admin/login.php');
    exit();
}
require_once __DIR__ . '/../permissions.php';
include '../db/db.php';
$result = $conn->query("SELECT * FROM orders");
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Orders - Veloura Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<?php include '../navbar.php'; ?>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-shopping-cart"></i> View Orders</h2>
        <a href="index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
    <table class="table table-bordered">
        <thead><tr><th>ID</th><th>User ID</th><th>Phone</th><th>Address</th><th>City</th><th>Country</th><th>Total Price</th></tr></thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['user_id'] ?></td>
                <td><?= $row['phone'] ?></td>
                <td><?= $row['address'] ?></td>
                <td><?= $row['city'] ?></td>
                <td><?= $row['country'] ?></td>
                <td><?= $row['total_price'] ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
