<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'navbar.php';
include 'db/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 2; // Always regular user

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        $error = "⚠ Email already exists!";
    } else {
        // Insert new user
        $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $email, $password, $role);

        if ($stmt->execute()) {
            // Set proper session
            $_SESSION['user'] = [
                'name' => $name,
                'email' => $email,
                'role' => 2
            ];
            $_SESSION['user_logged_in'] = true;

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "❌ Something went wrong. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Veloura</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Register</h2>
    <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
    <form method="post">
        <div class="form-group">
            <input type="text" name="name" class="form-control" placeholder="Full Name" required>
        </div>
        <div class="form-group">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
    <p class="mt-3">Already have an account? <a href="login.php">Login here</a></p>
</div>

</body>
</html>
