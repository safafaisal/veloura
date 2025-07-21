<?php
session_start();
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']) {
    echo '<h2 style="color:red;text-align:center;margin-top:40px;">Access Denied: Users cannot access admin features.</h2>';
    exit();
}
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
    header('Location: index.php');
    exit();
}
include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username'];
            // Remove any user session
            unset($_SESSION['user_logged_in']);
            unset($_SESSION['user']);
            header('Location: index.php');
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Admin user not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - Veloura</title>
</head>
<body>
    <h2>Admin Login</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
