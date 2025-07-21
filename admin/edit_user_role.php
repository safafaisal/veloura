<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: /veloura/admin/login.php');
    exit();
}
include '../db/db.php';

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($user_id <= 0) {
    die('Invalid user ID.');
}

// Fetch user info
$stmt = $conn->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    die('User not found.');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_role = $_POST['role'];
    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $new_role, $user_id);
    $stmt->execute();
    $stmt->close();
    header('Location: manage_users.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User Role - Veloura Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<?php include '../navbar.php'; ?>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user-edit"></i> Edit Role for <?= htmlspecialchars($user['name']) ?></h2>
        <a href="manage_users.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>
    <form method="post">
        <div class="form-group">
            <label>Email: <?= htmlspecialchars($user['email']) ?></label>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <select name="role" id="role" class="form-control" required>
                <option value="user" <?= $user['role']=='user'?'selected':'' ?>>User</option>
                <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Update Role
        </button>
    </form>
</div>
</body>
</html>