<?php

function logUserActivity($action, $details = '', $user_id = null, $username = null) {
    global $conn;
    
    // If user_id and username not provided, try to get from session
    if ($user_id === null && isset($_SESSION['user']['id'])) {
        $user_id = $_SESSION['user']['id'];
    }
    
    // Always try to get the user's name from session if available
    if ($username === null) {
        if (isset($_SESSION['user']['username'])) {
            $username = $_SESSION['user']['username'];
        } elseif (isset($_SESSION['user']['name'])) {
            $username = $_SESSION['user']['name'];
        }
    }
    
    // Get IP address
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    
    // Get user agent
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    // Prepare and execute the insert statement
    $stmt = $conn->prepare("INSERT INTO user_activity_log (user_id, username, action, details, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $username, $action, $details, $ip_address, $user_agent);
    
    $result = $stmt->execute();
    $stmt->close();
    
    return $result;
}

// Common logging actions
function logLogin($username) {
    logUserActivity('LOGIN', "User logged in: $username", null, $username);
}

function logLogout($username) {
    logUserActivity('LOGOUT', "User logged out: $username", null, $username);
}

function logRegistration($username) {
    logUserActivity('REGISTRATION', "New user registered: $username", null, $username);
}

function logProductView($product_name) {
    logUserActivity('PRODUCT_VIEW', "Viewed product: $product_name");
}

function logAddToCart($product_name, $quantity) {
    logUserActivity('ADD_TO_CART', "Added to cart: $product_name (Qty: $quantity)");
}

function logRemoveFromCart($product_name) {
    logUserActivity('REMOVE_FROM_CART', "Removed from cart: $product_name");
}

function logCheckout($total_amount) {
    logUserActivity('CHECKOUT', "Order placed with total: Rs. $total_amount");
}

function logProfileUpdate($field_updated) {
    logUserActivity('PROFILE_UPDATE', "Profile updated: $field_updated");
}

function logLocationSearch($location) {
    logUserActivity('LOCATION_SEARCH', "Searched for location: $location");
}

function logMapLocation($coordinates) {
    logUserActivity('MAP_LOCATION', "Selected location on map: $coordinates");
}

function logPageVisit($page_name) {
    logUserActivity('PAGE_VISIT', "Visited page: $page_name");
}

function logError($error_message) {
    logUserActivity('ERROR', "Error occurred: $error_message");
}

function logAdminAction($action, $details) {
    logUserActivity('ADMIN_ACTION', "Admin action: $action - $details");
}
?> 