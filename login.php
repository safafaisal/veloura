<?php
session_start();
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
    echo '<h2 style="color:red;text-align:center;margin-top:40px;">Access Denied: Admins cannot access user features.</h2>';
    exit();
}
include 'db/db.php';
include 'includes/activity_logger.php';

if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            $_SESSION['user_logged_in'] = true;
            if (isset($user['role']) && ($user['role'] == 1 || strtolower($user['role']) == 'admin')) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $user['name'];
            }
            
            // Log successful login
            logLogin($user['name']);
            
            header('Location: dashboard.php');
            exit();
        } else {
            $error = "Incorrect password.";
            // Log failed login attempt
            logUserActivity('LOGIN_FAILED', "Failed login attempt for email: $email");
        }
    } else {
        $error = "User not found.";
        // Log failed login attempt
        logUserActivity('LOGIN_FAILED', "Failed login attempt for non-existent email: $email");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Veloura</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container mt-5">
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
    <form method="post">
        <div class="form-group">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <p class="mt-3">Don't have an account? <a href="register.php">Register here</a></p>
</div>
</body>
</html>
