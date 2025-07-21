<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: /veloura/admin/login.php');
    exit();
}
require_once __DIR__ . '/../permissions.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Veloura</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<?php include '../navbar.php'; ?>
<div class="container mt-5">
    <h2 class="mb-4"><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h2>
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-box fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Manage Products</h5>
                    <p class="card-text">Add, edit, and delete products</p>
                    <a href="manage_products.php" class="btn btn-primary">Manage Products</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-shopping-cart fa-3x text-success mb-3"></i>
                    <h5 class="card-title">View Orders</h5>
                    <p class="card-text">Monitor customer orders</p>
                    <a href="manage_orders.php" class="btn btn-success">View Orders</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x text-info mb-3"></i>
                    <h5 class="card-title">View Users</h5>
                    <p class="card-text">Manage user accounts</p>
                    <a href="manage_users.php" class="btn btn-info">View Users</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-list fa-3x text-warning mb-3"></i>
                    <h5 class="card-title">Activity Logs</h5>
                    <p class="card-text">View user activity logs</p>
                    <a href="view_activity_logs.php" class="btn btn-warning">View Logs</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-chart-bar fa-3x text-danger mb-3"></i>
                    <h5 class="card-title">Analytics</h5>
                    <p class="card-text">View charts and statistics</p>
                    <a href="dashboard_charts.php" class="btn btn-danger">View Analytics</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-sign-out-alt fa-3x text-secondary mb-3"></i>
                    <h5 class="card-title">Logout</h5>
                    <p class="card-text">Sign out of admin panel</p>
                    <a href="logout.php" class="btn btn-secondary">Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
