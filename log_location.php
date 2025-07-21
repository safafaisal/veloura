<?php
session_start();
include 'db/db.php';
include 'includes/activity_logger.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    exit('Invalid JSON');
}

$action = $input['action'] ?? '';
$details = $input['details'] ?? '';

// Log the activity
logUserActivity($action, $details);

// Return success response
http_response_code(200);
echo json_encode(['status' => 'success']);
?> 