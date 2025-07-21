<?php
session_start();
include 'db/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_name = trim($_POST['name']);
    $new_email = trim($_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate inputs
    if (empty($new_name) || empty($new_email)) {
        $error_message = "Name and email cannot be empty.";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } else {
        // Check if email already exists (excluding current user)
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $new_email, $user['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error_message = "This email is already registered by another user.";
        } else {
            // If password change is requested
            if (!empty($new_password)) {
                // Verify current password
                if (!password_verify($current_password, $user['password'])) {
                    $error_message = "Current password is incorrect.";
                } elseif ($new_password !== $confirm_password) {
                    $error_message = "New passwords do not match.";
                } elseif (strlen($new_password) < 6) {
                    $error_message = "New password must be at least 6 characters long.";
                } else {
                    // Update with password change
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
                    $stmt->bind_param("sssi", $new_name, $new_email, $hashed_password, $user['id']);
                    
                    if ($stmt->execute()) {
                        // Update session with new user data
                        $_SESSION['user']['name'] = $new_name;
                        $_SESSION['user']['email'] = $new_email;
                        $user = $_SESSION['user'];
                        $success_message = "Profile updated successfully!";
                    } else {
                        $error_message = "Error updating profile. Please try again.";
                    }
                }
            } else {
                // Update without password change
                $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
                $stmt->bind_param("ssi", $new_name, $new_email, $user['id']);
                
                if ($stmt->execute()) {
                    // Update session with new user data
                    $_SESSION['user']['name'] = $new_name;
                    $_SESSION['user']['email'] = $new_email;
                    $user = $_SESSION['user'];
                    $success_message = "Profile updated successfully!";
                } else {
                    $error_message = "Error updating profile. Please try again.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile - Veloura</title>
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
        .edit-profile-container {
            position: relative;
            z-index: 1;
            padding: 20px;
        }
        .profile-form {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin: 20px auto;
            max-width: 600px;
            box-shadow: 0 4px 20px rgba(216,27,96,0.15);
        }
        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-header h2 {
            color: #d81b60;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: 600;
            color: #d81b60;
            margin-bottom: 8px;
            display: block;
        }
        .form-control {
            border: 2px solid #f8bbd0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 16px;
            transition: border-color 0.2s;
        }
        .form-control:focus {
            border-color: #d81b60;
            box-shadow: 0 0 0 0.2rem rgba(216,27,96,0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #d81b60 0%, #ffb6c1 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.2s;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #ad1457 0%, #f8bbd0 100%);
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: #6c757d;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.2s;
        }
        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        .alert {
            border-radius: 8px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }
        .password-section {
            border-top: 2px solid #f8bbd0;
            padding-top: 20px;
            margin-top: 20px;
        }
        .password-section h4 {
            color: #d81b60;
            margin-bottom: 15px;
        }
        .optional-text {
            color: #6c757d;
            font-size: 14px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="edit-profile-container">
        <div class="profile-form">
            <div class="form-header">
                <h2><i class="fas fa-user-edit"></i> Edit Profile</h2>
                <p class="text-muted">Update your personal information</p>
            </div>

            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="name"><i class="fas fa-user"></i> Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="password-section">
                    <h4><i class="fas fa-lock"></i> Change Password</h4>
                    <p class="optional-text">Leave blank if you don't want to change your password</p>
                    
                    <div class="form-group">
                        <label for="current_password"><i class="fas fa-key"></i> Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password">
                    </div>

                    <div class="form-group">
                        <label for="new_password"><i class="fas fa-lock"></i> New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password">
                    </div>

                    <div class="form-group">
                        <label for="confirm_password"><i class="fas fa-lock"></i> Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Profile
                    </button>
                    <a href="dashboard.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html> 