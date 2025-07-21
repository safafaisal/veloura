<?php
include 'db/db.php';

// Create user_activity_log table
$sql = "CREATE TABLE IF NOT EXISTS user_activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    username VARCHAR(255),
    action VARCHAR(255) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
)";

if ($conn->query($sql) === TRUE) {
    echo "✅ User activity log table created successfully!";
} else {
    echo "❌ Error creating table: " . $conn->error;
}

$conn->close();
?> 