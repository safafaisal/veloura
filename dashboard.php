<?php
session_start();
include 'db/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];

// Get user's order history
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC LIMIT 5");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$recent_orders = $stmt->get_result();

// Get cart count from session
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$cart_count = 0;
foreach ($cart as $item) {
    $cart_count += $item['quantity'];
}

// Get total orders count
$stmt = $conn->prepare("SELECT COUNT(*) as total_orders FROM orders WHERE user_id = ?");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$orders_result = $stmt->get_result();
$total_orders = $orders_result->fetch_assoc()['total_orders'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Veloura</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
            margin: 0;
            background: url('images/bg.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            color: #333;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(255,255,255,0.5);
            z-index: 0;
            pointer-events: none;
        }
        .dashboard-container {
            position: relative;
            z-index: 1;
            padding: 20px;
        }
        .welcome-section {
            background: linear-gradient(135deg, #d81b60 0%, #ffb6c1 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(216,27,96,0.15);
        }
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .stats-icon {
            font-size: 2.5rem;
            color: #d81b60;
            margin-bottom: 15px;
        }
        .quick-actions {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .action-btn {
            background: linear-gradient(135deg, #d81b60 0%, #ffb6c1 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            margin: 5px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
            font-weight: 600;
        }
        .action-btn:hover {
            background: linear-gradient(135deg, #ad1457 0%, #f8bbd0 100%);
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }
        .recent-orders {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .order-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-completed {
            background: #d4edda;
            color: #155724;
        }
        .profile-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #d81b60 0%, #ffb6c1 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="dashboard-container">
        <div class="welcome-section">
            <h1><i class="fas fa-user-circle"></i> Welcome back, <?php echo htmlspecialchars($user['name']); ?>!</h1>
            <p class="mb-0">Manage your account, view orders, and explore our latest perfumes.</p>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="stats-card text-center">
                    <div class="stats-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3><?php echo $cart_count; ?></h3>
                    <p class="text-muted">Items in Cart</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card text-center">
                    <div class="stats-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <h3><?php echo $total_orders; ?></h3>
                    <p class="text-muted">Total Orders</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card text-center">
                    <div class="stats-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3>Premium</h3>
                    <p class="text-muted">Member Since <?php echo date('M Y', strtotime($user['created_at'] ?? 'now')); ?></p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="profile-section">
                    <h4><i class="fas fa-user"></i> Profile Information</h4>
                    <div class="text-center mb-3">
                        <div class="profile-avatar">
                            <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <strong>Name:</strong><br>
                            <?php echo htmlspecialchars($user['name']); ?>
                        </div>
                        <div class="col-6">
                            <strong>Email:</strong><br>
                            <?php echo htmlspecialchars($user['email']); ?>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="edit_profile.php" class="action-btn">
                            <i class="fas fa-edit"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="quick-actions">
                    <h4><i class="fas fa-bolt"></i> Quick Actions</h4>
                    <div class="text-center">
                        <a href="index.php" class="action-btn">
                            <i class="fas fa-shopping-bag"></i> Shop Now
                        </a>
                        <a href="cart.php" class="action-btn">
                            <i class="fas fa-shopping-cart"></i> View Cart
                        </a>
                        <a href="help_center.php" class="action-btn">
                            <i class="fas fa-question-circle"></i> Help Center
                        </a>
                        <a href="logout.php" class="action-btn" style="background: #dc3545;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="recent-orders">
            <h4><i class="fas fa-history"></i> Recent Orders</h4>
            <?php if ($recent_orders->num_rows > 0): ?>
                <?php while($order = $recent_orders->fetch_assoc()): ?>
                    <div class="order-item">
                        <div class="row align-items-center">
                                                    <div class="col-md-3">
                            <strong>Order #<?php echo $order['id']; ?></strong>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted"><?php echo $order['city']; ?>, <?php echo $order['country']; ?></span>
                        </div>
                            <div class="col-md-3">
                                <strong>Rs. <?php echo number_format($order['total_price'], 2); ?></strong>
                            </div>
                            <div class="col-md-3">
                                <span class="status-badge status-pending">
                                    Pending
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="text-center text-muted py-4">
                    <i class="fas fa-shopping-bag fa-3x mb-3"></i>
                    <p>No orders yet. Start shopping to see your order history here!</p>
                    <a href="index.php" class="action-btn">Start Shopping</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html> 