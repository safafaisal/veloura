<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db/db.php';

$name = 'Admin';
$email = 'admin@test.com';
$password = password_hash('admin', PASSWORD_DEFAULT);
$role = 'admin';

$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "⚠ Admin already exists in the database.";
} else {
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        echo "✅ Admin user created!";
    } else {
        echo "❌ Error inserting admin: " . $stmt->error;
    }
}
?>
